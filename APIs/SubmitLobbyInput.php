<?php

include "../WriteLog.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";

SetHeaders();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);

if($_POST == NULL) {
  $response->error = "Parameters were not passed";
  echo json_encode($response);
  exit;
}

$gameName = $_POST["gameName"];
$playerID = $_POST["playerID"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];
$action = $_POST["action"]; //"Go First" to choose to go first, anything else will choose to go second

if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}

include "../HostFiles/Redirector.php";
include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  // Failsafe: Use game file's auth key if mismatch (lost on page refresh)
  $authKey = $targetAuth;
}

if($action == "Request Chat")
{
  $myName = ($playerID == 1 ? $p1uid : $p2uid);
  $theirName = ($playerID == 1 ? $p2uid : $p1uid);
  if(!str_contains($myName, "Omegaeclipse") && !str_contains($theirName, "Omegaeclipse")) {
    if($playerID == 1) SetCachePiece($gameName, 15, 1);
    else if($playerID == 2) SetCachePiece($gameName, 16, 1);
  }
  if(GetCachePiece($gameName, 15) != 1 || GetCachePiece($gameName, 16) != 1)
  {
    WriteLog($myName . " wants to enable chat", path: "../");
  }
  GamestateUpdated($gameName);
}

WriteGameFile();

$response->success = true;
echo (json_encode($response));
