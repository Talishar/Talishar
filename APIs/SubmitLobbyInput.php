<?php

include "../WriteLog.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";

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

if ($playerID == 3) {
  $response->error = "Spectators cannot submit lobby input";
  echo json_encode($response);
  exit;
}

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
if ($authKey !== $targetAuth) {
  // Failsafe: Use game file's auth key if mismatch (lost on page refresh)
  $authKey = $targetAuth;
}

if($action == "Request Chat")
{
  $myName = substr($playerID == 1 ? $p1uid : $p2uid, 0, 20);
  $theirName = ($playerID == 1 ? $p2uid : $p1uid);

  $cacheArr = ReadCacheArray($gameName);
  if ($cacheArr !== null) {
    if (!str_contains($myName, "Omegaeclipse") && !str_contains($theirName, "Omegaeclipse")) {
      if ($playerID == 1) $cacheArr[14] = 1;
      else $cacheArr[15] = 1;
      WriteCache($gameName, implode("!", $cacheArr));
    }
    if (($cacheArr[14] ?? "") != 1 || ($cacheArr[15] ?? "") != 1) {
      WriteLog($myName . " wants to enable chat", path: "../");
    }
  }
  GamestateUpdated($gameName);

  UnlockGamefile();
  $response->success = true;
  echo json_encode($response);
  exit;
}

if ($action == "Unready Sideboard") {
  $didChangeSideboardState = false;

  if ($playerID == 1 && $p1SideboardSubmitted == "1") {
    $p1SideboardSubmitted = "0";
    $didChangeSideboardState = true;
  } else if ($playerID == 2 && $p2SideboardSubmitted == "1") {
    $p2SideboardSubmitted = "0";
    $didChangeSideboardState = true;
  }

  if ($didChangeSideboardState) {
    $gameStatusChanged = ($gameStatus == $MGS_ReadyToStart);
    if ($gameStatusChanged) {
      $gameStatus = $MGS_P2Sideboard;
    }

    if (function_exists('FlushLogBuffer')) FlushLogBuffer();
    $cacheArr = ReadCacheArray($gameName);
    if ($cacheArr !== null) {
      $cacheArr[0] = (int)($cacheArr[0]) + 1;
      $cacheArr[5] = round(microtime(true) * 1000);
      if ($gameStatusChanged) {
        $cacheArr[13] = $gameStatus;
      }
      WriteCache($gameName, implode("!", $cacheArr));
    }
  }
}

WriteGameFile();

$response->success = true;
echo json_encode($response);
