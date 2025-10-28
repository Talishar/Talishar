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
$authKey = $_POST["authKey"] ?? null;
if (!$authKey) {
  if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
  else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
}
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

if ($action == "Go First") {
  $firstPlayer = $playerID;
} else {
  $firstPlayer = $playerID == 1 ? 2 : 1;
}
WriteLog("Player " . $firstPlayer . " will go first.", path: "../");
$gameStatus = $MGS_P2Sideboard;
SetCachePiece($gameName, 14, $gameStatus);
GamestateUpdated($gameName);

WriteGameFile();

$response->success = true;
echo (json_encode($response));
