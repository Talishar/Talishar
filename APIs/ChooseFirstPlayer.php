<?php

include "../WriteLog.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";

SetHeaders();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);

if ($_POST == NULL) {
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
  echo ("Invalid Auth Key");
  exit;
}

switch ($action) {
  case "Request Chat":
    include_once "../WriteLog.php";
    if ($playerID == 1) SetCachePiece($gameName, 15, 1);
    else if ($playerID == 2) SetCachePiece($gameName, 16, 1);
    $myName = ($playerID == 1 ? $p1uid : $p2uid);
    WriteLog($myName . " wants to enable chat", path: "../");
    break;
  case "Go First":
    $firstPlayer = $playerID;
    WriteLog("Player " . $firstPlayer . " will go first.", path: "../");
    break;
  case "Go Second":
    $firstPlayer = ($playerID == 1 ? 2 : 1);
    WriteLog("Player " . $firstPlayer . " will go first.", path: "../");
    break;
  default:
    break;
}

// WriteLog("Player " . $firstPlayer . " will go first.", path: "../");
$gameStatus = $MGS_P2Sideboard;
SetCachePiece($gameName, 14, $gameStatus);
GamestateUpdated($gameName);

WriteGameFile();

$response->success = true;
echo (json_encode($response));
