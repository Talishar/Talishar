<?php

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "Libraries/CacheLibraries.php";
include "WriteLog.php";

ob_implicit_flush(true);
ob_end_flush();
SetHeaders();

$response = new stdClass();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("error: Invalid gamename\n\n");
  ob_flush();
  flush();
  exit;
}

$playerID = TryGet("playerID", 3);
if (!is_numeric($playerID)) {
  echo ("error: Invalid playerID\n\n");
  ob_flush();
  flush();
  exit;
}

$authKey = TryGet("authKey", "");
if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$lastUpdate = 0;
$response->message = "update";

$isGamePlayer = $playerID == 1 || $playerID == 2;

ob_start();
// Loop until the client close the stream
$cacheVal = intval(GetCachePiece($gameName, 1));
$lastUpdate = $cacheVal;
$response->cacheVal = $cacheVal;
echo ("data: " . json_encode($response) . "\n\n");
ob_flush();
flush();

$count = 0;
$sleepMs = 50;
$otherP = $playerID == 1 ? 2 : 1;
$lastOppStatus = 0;
$lastFileCheckTime = microtime(true);
$fileCheckInterval = 30.0;
$gameFileExists = true;
$lastConnectionCheck = microtime(true);
$connectionCheckInterval = 2.0; // Check connection every 2 seconds

while (true) {
  $currentRealTime = microtime(true);
  
  // Check client connection more frequently to detect refreshes/disconnects
  if ($currentRealTime - $lastConnectionCheck >= $connectionCheckInterval) {
    if (connection_aborted()) exit;
    $lastConnectionCheck = $currentRealTime;
  }
  
  // Check if game file still exists
  if ($currentRealTime - $lastFileCheckTime >= $fileCheckInterval) {
    if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) exit;
    $lastFileCheckTime = $currentRealTime;
  }
  
  // Check if game is over (status 99)
  $gameStatus = intval(GetCachePiece($gameName, 14));
  if ($gameStatus == 99) {
    exit;
  }
  
  ++$count;
  
  // Single cache read instead of multiple GetCachePiece calls
  $cacheVal = intval(GetCachePiece($gameName, 1));
  if ($cacheVal > $lastUpdate) {
    $lastUpdate = $cacheVal;
    $response->cacheVal = $cacheVal;
    echo("data: " . json_encode($response) . "\n\n");
    ob_flush();
    flush();
    set_time_limit(120);
    $sleepMs = 100;
  }

  if($isGamePlayer) {
    $currentTime = round(microtime(true) * 1000);
    
    // Batch cache operations - read all pieces once
    $oppLastTime = intval(GetCachePiece($gameName, $otherP + 1));
    $oppStatus = intval(GetCachePiece($gameName, $otherP + 3));
    $lastUpdateTime = GetCachePiece($gameName, 6);
    $playerInactiveStatus = GetCachePiece($gameName, 12);
    
    // Early exit if game no longer exists
    if ($lastUpdateTime == "") {
      echo("The game no longer exists on the server.");
      exit;
    }
    
    // Write current player status (required for opponent disconnect detection)
    SetCachePiece($gameName, $playerID + 1, $currentTime);
    
    // Handle opponent status changes (only write if status actually changes)
    $timeSinceOppLastConnection = $currentTime - $oppLastTime;
    
    if ($timeSinceOppLastConnection > 3000 && $oppStatus == 0) {
      // Opponent disconnected (not yet marked as disconnected)
      WriteLog("🔌Opponent has disconnected. Waiting 60 seconds to reconnect.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "1");
    } else if ($timeSinceOppLastConnection > 60000 && $oppStatus == 1) {
      // Opponent confirmed left (more than 60 seconds since last activity)
      $currentPlayerActivity = 2;
      WriteLog("Opponent has left the game.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "2");
      $opponentDisconnected = true;
    } else if ($timeSinceOppLastConnection < 3000 && $oppStatus > 0) {
      // Opponent reconnected
      SetCachePiece($gameName, $otherP + 3, "0");
    }
    
    // Handle server timeout (60 seconds of no game updates)
    if ($currentTime - $lastUpdateTime > 60000 && $playerInactiveStatus != "1") {
      SetCachePiece($gameName, 12, "1");
      $opponentInactive = true;
      $response->cacheVal = $cacheVal;
      echo("data: " . json_encode($response) . "\n\n");
      ob_flush();
      flush();
    }
  }

  // Wait with exponential backoff (50ms → 500ms cap)
  usleep(intval($sleepMs * 1000));
  //$sleepMs = min($sleepMs * 1.5, 500);
}
