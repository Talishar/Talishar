<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/ValidationLibraries.php";
include_once "../Libraries/GameAuthLibraries.php";
include_once "../AccountFiles/AccountSessionAPI.php";

SetHeaders();
header('Content-Type: application/json; charset=utf-8');

$response = new stdClass();
$response->success = false;
$response->error = '';
$response->authKey = '';

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) $body = [];
$input = array_merge($_GET, $_POST, $body);

if (!IsUserLoggedIn()) {
  $response->error = "User not logged in";
  http_response_code(401);
  echo json_encode($response);
  exit;
}

$accountUid = LoggedInUserName();
session_write_close();

$gameName = strval($input["gameName"] ?? "");
$requestedPlayerID = intval($input["playerID"] ?? 0);

if ($gameName === "" || !IsGameNameValid($gameName)) {
  $response->error = "Invalid game name";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

if (!file_exists("../Games/" . $gameName . "/GameFile.txt")) {
  $response->error = "Game does not exist";
  http_response_code(404);
  echo json_encode($response);
  exit;
}

include "./APIParseGamefile.php";
UnlockGamefile();

// The seat is owned by whichever account is recorded in the game file, so the
// account handle alone is enough to hand the key back on a new device.
$playerID = GameSeatForAccount($p1uid, $p2uid, $accountUid);
if ($playerID === 0) {
  $response->error = "You are not a player in this game";
  http_response_code(403);
  echo json_encode($response);
  exit;
}

if ($requestedPlayerID === 1 || $requestedPlayerID === 2) {
  if ($requestedPlayerID !== $playerID) {
    $response->error = "You are not that player in this game";
    http_response_code(403);
    echo json_encode($response);
    exit;
  }
}

$authKey = ResolveGameAuthKey($playerID, null, $p1Key, $p2Key, $p1uid, $p2uid, $accountUid);
if ($authKey === null || $authKey === '') {
  $response->error = "No auth key on file for this game";
  http_response_code(404);
  echo json_encode($response);
  exit;
}

if (!empty($_SESSION["userid"])) {
  include_once "../includes/dbh.inc.php";
  include_once "../includes/functions.inc.php";
  StoreLastGameInfo($_SESSION["userid"], $gameName, $playerID, $authKey);
}

$response->success = true;
$response->authKey = $authKey;
$response->playerID = $playerID;
$response->gameName = $gameName;
echo json_encode($response);
exit;
