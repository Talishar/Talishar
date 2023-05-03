<?php

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";

// array holding allowed Origin domains
SetHeaders();

header('Content-Type: application/json; charset=utf-8');

$response = new stdClass();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  $response->errorMessage = ("Invalid game name.");
  echo (json_encode($response));
  exit;
}

$playerID = TryGet("playerID", 3);
if (!is_numeric($playerID)) {
  $response->errorMessage = ("Invalid player ID.");
  echo(json_encode($response));
  exit;
}

$authKey = TryGet("authKey", "");
if(($playerID == 1 || $playerID == 2) && $authKey == "") {
  if(isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$lastUpdate = 0;
$response->message = "update";
$response->padding = str_pad("",4096);

ob_start();
// Loop until the client close the stream
while(true) {
  $cacheVal = intval(GetCachePiece($gameName, 1));
  if($cacheVal > $lastUpdate)
  {
    $lastUpdate = $cacheVal;
    echo("data: " . json_encode($response));
    ob_flush();
    flush();
    set_time_limit(120);//Reset script time limit
  }

  // Wait 100ms to check again
  usleep(100000); //100 milliseconds
}

ob_end_flush();

?>
