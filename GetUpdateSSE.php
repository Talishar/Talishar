<?php

/**
 * GetUpdateSSE.php
 *
 * Server-Sent Events endpoint that pushes full game state updates directly to clients.
 * This eliminates the extra HTTP round-trip that was previously needed to GetNextTurn.php.
 */

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include "Libraries/CacheLibraries.php";
include "WriteLog.php";
include_once "./Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "./Assets/MetafyDictionary.php";
include_once "./AccountFiles/AccountSessionAPI.php";
include_once "includes/dbh.inc.php";
include_once "includes/MetafyHelper.php";

include_once 'GameLogic.php';
include_once "GameTerms.php";
include_once "Libraries/UILibraries.php";
include_once "Libraries/StatFunctions.php";
include_once "Libraries/PlayerSettings.php";

include_once "BuildGameState.php";
include_once "BuildPlayerInputPopup.php";

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

// Capture session data before setting SSE headers
// This is critical - we must capture and close session BEFORE the long-running SSE loop
$sessionData = [];
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
$sessionData['userLoggedIn'] = IsUserLoggedIn();
$sessionData['userName'] = LoggedInUserName() ?: null;
$sessionData['isPvtVoidPatron'] = isset($_SESSION["isPvtVoidPatron"]);

// Capture all Patreon campaign session IDs
$sessionData['patreonCampaigns'] = [];
foreach(PatreonCampaign::cases() as $campaign) {
  $sessionID = $campaign->SessionID();
  $sessionData['patreonCampaigns'][$sessionID] = isset($_SESSION[$sessionID]);
}

// Load friend list if user is logged in (for friend hand visibility checks)
$sessionData['friendList'] = [];
$friendsListParam = TryGet("friendsList", "");
if (!empty($friendsListParam)) {
  try {
    $sessionData['friendList'] = json_decode($friendsListParam, true) ?? [];
  } catch (Exception $e) {
    // friendsList parameter parsing failed
  }
}

// Release session lock BEFORE SSE loop to prevent deadlock
if (session_status() === PHP_SESSION_ACTIVE) {
  session_write_close();
}

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

$initialState = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, true);
if (is_string($initialState)) {
  // Error occurred
  echo ("data: " . json_encode(["error" => $initialState]) . "\n\n");
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
    $finalState = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, false);
    if (!is_string($finalState)) {
      SendContent($finalState);
    }
    exit;
  }

  // Check for game state updates
  $cacheVal = intval(GetCachePiece($gameName, 1));
  if ($cacheVal > $lastUpdate) {
    $lastUpdate = $cacheVal;

    // Build and send full game state
    $gameState = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, false);
    if (is_string($gameState)) {
      SendContent(["error" => $gameState]);
      exit;
    }
    SendContent($gameState);
    set_time_limit(120);
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
      $gameState = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, false);
      if (!is_string($gameState)) {
        SendContent($gameState);
      }
    }
  }

  usleep(intval($sleepMs * 1000));
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