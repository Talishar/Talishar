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

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$lastUpdate = 0;
$response->message = "update";

// Loop until the client close the stream
while(true) {
  $cacheVal = intval(GetCachePiece($gameName, 1));
  if($cacheVal > $lastUpdate)
  {
    $lastUpdate = $cacheVal;
    echo(json_encode($response));
    // Flush buffer (force sending data to client)
    flush();
  }

  // Wait 100ms to check again
  usleep(100000); //100 milliseconds
}


?>
