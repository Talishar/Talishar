<?php

/**
 * GetUpdateSSE.php
 *
 * Server-Sent Events endpoint that pushes game state updates to clients.
 *
 * Architecture note: game state is built by curling GetNextTurn.php via a loopback
 * HTTP request (see FetchGameState). This keeps the SSE process lean — it only holds
 * the lightweight cache/log libraries in memory. Each game state build happens in its
 * own short-lived PHP process whose memory is fully reclaimed when the request ends,
 * preventing the memory exhaustion that occurs when ParseGamestate() accumulates large
 * global arrays repeatedly inside a single long-running process.
 */

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include "Libraries/CacheLibraries.php";
include "WriteLog.php";

// Close any buffers that php.ini may have opened (e.g. output_buffering=On).
// Then immediately open our own buffer so every ob_flush() call below is safe.
while (ob_get_level() > 0) {
  ob_end_clean();
}
ob_start();
SetHeaders();

$response = new stdClass();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("data: " . json_encode(["error" => "Invalid gamename"]) . "\n\n");
  ob_flush();
  flush();
  exit;
}

$playerID = TryGet("playerID", 3);
if (!is_numeric($playerID)) {
  echo ("data: " . json_encode(["error" => "Invalid playerID"]) . "\n\n");
  ob_flush();
  flush();
  exit;
}

$authKey = TryGet("authKey", "");
if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

// Session handling is delegated to GetNextTurn.php (called via FetchGameState).
// The SSE process itself never needs to read the session, so no session_start() here.

if ($playerID == 3) {
  UpdateSpectatorPresence($gameName);
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$lastUpdate = 0;
$isGamePlayer = $playerID == 1 || $playerID == 2;

// Send initial full game state
$cacheVal = intval(GetCachePiece($gameName, 1));
$lastUpdate = $cacheVal;

$initialState = FetchGameState($gameName, $playerID, $authKey);
if ($initialState === null || isset($initialState['errorMessage'])) {
  $errMsg = $initialState['errorMessage'] ?? "Failed to build initial game state";
  echo ("data: " . json_encode(["error" => $errMsg]) . "\n\n");
  ob_flush();
  flush();
  exit;
}
echo ("data: " . json_encode($initialState) . "\n\n");
ob_flush();
flush();

$sleepMs = 50;
$otherP = $playerID == 1 ? 2 : 1;
$lastOppStatus = 0;
$lastFileCheckTime = microtime(true);
$fileCheckInterval = 30.0;
$gameFileExists = true;
$lastConnectionCheck = microtime(true);
$connectionCheckInterval = 2.0;
$lastSpectatorRefresh = microtime(true);
$spectatorRefreshInterval = 30.0;
$rateLimitStartInterval = microtime(true);
$rateLimitProcessCount = 0;
$inactivityMessageSent = false;

while (true) {
  $currentRealTime = microtime(true);

  // Check client connection more frequently to detect refreshes/disconnects
  if ($currentRealTime - $lastConnectionCheck >= $connectionCheckInterval) {
    if (connection_aborted()) exit;
    $lastConnectionCheck = $currentRealTime;
  }

  if ($playerID == 3 && $currentRealTime - $lastSpectatorRefresh >= $spectatorRefreshInterval) {
    UpdateSpectatorPresence($gameName);//, $sessionData['userName'] ?? 'anonymous');
    $lastSpectatorRefresh = $currentRealTime;
  }

  $lastUpdateTime = GetCachePiece($gameName, 6);
  // Check if game file still exists
  if ($currentRealTime - $lastFileCheckTime >= $fileCheckInterval || $lastUpdateTime == "") {
    if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
      SendContent(["error" => "Game no longer exists"]);
      exit;
    }
    $lastFileCheckTime = $currentRealTime;
  }

  // Check if game is over (status 99)
  $gameStatus = intval(GetCachePiece($gameName, 14));
  if ($gameStatus == 99) {
    // Send final state before exiting
    $finalState = FetchGameState($gameName, $playerID, $authKey);
    if ($finalState !== null && !isset($finalState['errorMessage'])) {
      SendContent($finalState);
    }
    exit;
  }

  // Check for game state updates
  $cacheVal = intval(GetCachePiece($gameName, 1));
  if ($cacheVal > $lastUpdate) {
    $lastUpdate = $cacheVal;

    // Build and send full game state
    $gameState = FetchGameState($gameName, $playerID, $authKey);
    if ($gameState === null) {
      // Transient curl failure — skip this cycle and retry on next cache update
    } elseif (isset($gameState['errorMessage'])) {
      SendContent(["error" => $gameState['errorMessage']]);
      exit;
    } else {
      SendContent($gameState);
      set_time_limit(120);
    }
  }

  if($isGamePlayer) {
    $currentTime = round(microtime(true) * 1000);

    // Read cache values for opponent status
    $oppLastTime = intval(GetCachePiece($gameName, $otherP + 1));
    $oppStatus = intval(GetCachePiece($gameName, $otherP + 3));
    $playerInactiveStatus = GetCachePiece($gameName, 12);

    // Write current player status (required for opponent disconnect detection)
    SetCachePiece($gameName, $playerID + 1, $currentTime);

    // Handle opponent status changes
    $timeSinceOppLastConnection = $currentTime - $oppLastTime;

    if ($timeSinceOppLastConnection > 3000 && $oppStatus == 0) {
      // Opponent disconnected (not yet marked as disconnected)
      WriteLog("🔌Opponent has disconnected. Waiting 60 seconds to reconnect.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "1");
    } else if ($timeSinceOppLastConnection > 60000 && $oppStatus == 1) {
      // Opponent confirmed left (more than 60 seconds since last activity)
      WriteLog("Opponent has left the game.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "2");
    } else if ($timeSinceOppLastConnection < 3000 && $oppStatus > 0) {
      // Opponent reconnected
      SetCachePiece($gameName, $otherP + 3, "0");
      $inactivityMessageSent = false;
      WriteLog("🔌Opponent has reconnected.");
    }

    // Handle server timeout (60 seconds of no game updates)
    $noUpdates = $currentTime - $lastUpdateTime;
    if ($noUpdates > 60000 && $playerInactiveStatus != "1" && !$inactivityMessageSent) {
      SetCachePiece($gameName, 12, "1");
      $inactivityMessageSent = true;
      // Trigger a game state update to reflect inactivity
      $gameState = FetchGameState($gameName, $playerID, $authKey);
      if ($gameState !== null && !isset($gameState['errorMessage'])) {
        SendContent($gameState);
      }
    }
  }

  usleep(intval($sleepMs * 1000));
}

/**
 * Fetch game state via an internal loopback curl to GetNextTurn.php.
 *
 * This keeps the long-lived SSE process lean: each game state build runs in its
 * own short-lived PHP process whose memory is fully reclaimed after the request,
 * eliminating the memory exhaustion caused by repeated ParseGamestate() calls
 * accumulating global state inside a single long-running process.
 *
 * The browser's session cookie is forwarded so GetNextTurn.php can resolve
 * patron status, alt arts, and other session-backed cosmetic data.
 *
 * Returns the decoded JSON array on success, or null on curl/HTTP failure.
 * On a game-logic error the returned array will contain an 'errorMessage' key.
 */
function FetchGameState($gameName, $playerID, $authKey) {
  // Derive the path prefix from the current script so subdirectory installs work.
  $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

  $params = http_build_query([
    'gameName'    => $gameName,
    'playerID'    => $playerID,
    'authKey'     => $authKey,
    'friendsList' => $_GET['friendsList'] ?? '',
  ]);

  // Use 127.0.0.1 + Host header: avoids DNS and TLS overhead.
  // Apache always listens on plain HTTP internally even when the outer connection is HTTPS.
  $url = "http://127.0.0.1{$basePath}/GetGamestateAPI.php?{$params}";

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => ["Host: {$host}"],
  ]);

  // Forward the browser's session cookie so GetNextTurn.php can read patron/alt-art data.
  if (!empty($_SERVER['HTTP_COOKIE'])) {
    curl_setopt($ch, CURLOPT_COOKIE, $_SERVER['HTTP_COOKIE']);
  }

  $result   = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($result === false || $httpCode !== 200) {
    return null;
  }

  return json_decode($result, true);
}

function SendContent($jsonContent) {
  global $rateLimitStartInterval, $rateLimitProcessCount;
  $currentRealTime = microtime(true);
  if($currentRealTime - $rateLimitStartInterval > 1.0) {
    // Reset rate limit counters every second
    $rateLimitStartInterval = $currentRealTime;
    $rateLimitProcessCount = 0;
  } else {
    $rateLimitProcessCount++;
    if($rateLimitProcessCount > 5) {
      SendContent(["error" => "Too many game updates in last second. A likely logic error has occurred."]);
      exit;
    }
  }
  echo ("data: " . json_encode($jsonContent) . "\n\n");
  ob_flush();
  flush();
}