<?php

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";

ob_implicit_flush(true);
ob_end_flush();

// array holding allowed Origin domains
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
$loopStartTime = time();
$maxIdleTime = 110; // seconds - just under 120 second limit

while (true) {
  if(($count % 100 == 0) && !file_exists("./Games/" . $gameName . "/GameFile.txt")) exit;
  ++$count;
  
  // Check if we've been in the loop too long without reset
  if ((time() - $loopStartTime) > $maxIdleTime) {
    exit; // Exit gracefully to avoid timeout
  }
  
  $cacheVal = intval(GetCachePiece($gameName, 1));
  if ($cacheVal > $lastUpdate) {
    $lastUpdate = $cacheVal;
    $response->cacheVal = $cacheVal;
    echo("data: " . json_encode($response) . "\n\n");
    ob_flush();
    flush();
    set_time_limit(120); //Reset script time limit
    $loopStartTime = time(); // Reset the loop timer on activity
  }
  if (connection_aborted()) break;

  $currentTime = round(microtime(true) * 1000);
  $lastOppStatus = 0;
  if($isGamePlayer) {
    SetCachePiece($gameName, $playerID + 1, $currentTime);
    $otherP = $playerID == 1 ? 2 : 1;
    $oppLastTime = intval(GetCachePiece($gameName, $otherP + 1));
    $oppStatus = intval(GetCachePiece($gameName, $otherP + 3));
    if (($currentTime - $oppLastTime) > 3000 && $oppStatus == 0 && $oppStatus != $lastOppStatus) {
      WriteLog("🔌Opponent has disconnected. Waiting 60 seconds to reconnect.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "1");
    } else if (($currentTime - $oppLastTime) > 60000 && $oppStatus == 1 && $oppStatus != $lastOppStatus) {
      WriteLog("Opponent has left the game.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "2");
      //$lastUpdate = 0;
      $opponentDisconnected = true;
    } else if (($currentTime - $oppLastTime) < 3000 && $oppStatus > 0) {
      SetCachePiece($gameName, $otherP + 3, "0");
    }
    $lastOppStatus = intval($oppStatus);
    //Handle server timeout
    $lastUpdateTime = GetCachePiece($gameName, 6);
    if($lastUpdateTime == "") { echo("The game no longer exists on the server."); exit; }
    if($currentTime - $lastUpdateTime > 60000 && GetCachePiece($gameName, 12) != "1") //60 seconds
    {
      SetCachePiece($gameName, 12, "1");
      $opponentInactive = true;
      $response->cacheVal = $cacheVal;
      echo ("data: " . json_encode($response) . "\n\n");
      ob_flush();
      flush();
      //$lastUpdate = 0;
    }
  }

  // Wait 100ms to check again
  usleep(100000); //100 milliseconds
}

echo ("data: SOMETHING WRONG " . json_encode($response) . " " . str_pad("", 4096) . "\n\n");

ob_end_flush();

// Set file mime type event-stream
// header('Content-Type: text/event-stream');
// header('Cache-Control: no-cache');

// // Loop until the client close the stream
// while (true) {

//   // Echo time
//   $time = date('r');
//   $padding = str_pad("", 4096);
//   echo "data: The server time is: {$time}\n{$padding}\n\n";

//   // Flush buffer (force sending data to client)
//   flush();

//   // Wait 2 seconds for the next message / event
//   sleep(2);
// }
