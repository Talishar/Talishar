<?php

include "../WriteLog.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";

SetHeaders();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);

if ($_POST == NULL) {
  $response->error = "Parameters were not passed";
  echo json_encode($response);
  exit;
}

$gameName = $_POST["gameName"];
$playerID = intval($_POST["playerID"]);

if ($playerID !== 1) {
  $response->error = "Only the host (Player 1) can kick opponents";
  echo json_encode($response);
  exit;
}

if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];
else $authKey = null;

if (!IsGameNameValid($gameName)) {
  $response->error = "Invalid game name";
  echo json_encode($response);
  exit;
}

include "../HostFiles/Redirector.php";
include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = $p1Key;
if ($authKey !== $targetAuth) {
  $response->error = "Authentication failed";
  echo json_encode($response);
  exit;
}

if ($gameStatus >= $MGS_GameStarted) {
  $response->error = "Cannot kick a player after the game has started";
  echo json_encode($response);
  exit;
}

// Only allow kick in non-competitive formats
$competitiveFormats = [$FORMAT_CompCC, $FORMAT_CompBlitz, $FORMAT_CompLL, $FORMAT_CompSage];
if (in_array(intval($format), $competitiveFormats)) {
  $response->error = "Kick is not available in competitive formats";
  echo json_encode($response);
  exit;
}

if ($p2uid === "" || $p2uid === "-") {
  $response->error = "No opponent is currently in the lobby";
  echo json_encode($response);
  exit;
}

$kickedName = $p2uid;

// Remove player 2's deck files
if (file_exists("../Games/" . $gameName . "/p2Deck.txt")) unlink("../Games/" . $gameName . "/p2Deck.txt");
if (file_exists("../Games/" . $gameName . "/p2DeckOrig.txt")) unlink("../Games/" . $gameName . "/p2DeckOrig.txt");

// Reset game state back to waiting for opponent
$gameStatus = $MGS_Initial;
SetCachePiece($gameName, 14, $gameStatus);
SetCachePiece($gameName, 8, "");
SetCachePiece($gameName, 5, "-1");
SetCachePiece($gameName, 11, 0);
SetCachePiece($gameName, 17, "kicked");
SetCachePiece($gameName, 18, $kickedName);

$p2Data = [];
$p2uid = "";
$p2id = "";
$p2SideboardSubmitted = "0";

WriteGameFile();

WriteLog("🚫 " . substr($kickedName, 0, 20) . " was kicked from the lobby.", path: "../");

GamestateUpdated($gameName);

$response->success = true;
echo json_encode($response);
