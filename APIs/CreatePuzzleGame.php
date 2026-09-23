<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/FormatCodes.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/PuzzleHarvest.php';
include_once '../Libraries/PuzzleGame.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

$useruid = RequireModeratorSession();
session_write_close();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["error" => "POST required"]);
  exit;
}

$request = ReadJsonBody() ?? [];
$candidateID = intval($request["candidateId"] ?? 0);
$emptyOpponentHand = ($request["emptyOpponentHand"] ?? true) == true;
$removeDecks = ($request["removeDecks"] ?? true) == true;

$conn = GetDBConnection(DBL_CREATE_PUZZLE_GAME);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}
$candidate = null;
try {
  EnsurePuzzleCandidatesTable($conn);
  $stmt = mysqli_prepare($conn, "SELECT player, format, opponent_life, gamestate FROM puzzle_candidates WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $candidateID);
  mysqli_stmt_execute($stmt);
  $candidate = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
} catch (Throwable $e) {
  error_log("CreatePuzzleGame failed: " . $e->getMessage());
}
mysqli_close($conn);

$content = $candidate ? @gzuncompress($candidate["gamestate"]) : false;
if ($content === false) {
  http_response_code(404);
  echo json_encode(["error" => "Puzzle candidate not found"]);
  exit;
}

$gameName = GetGameCounter("../");
if (file_exists("../Games/$gameName") || !mkdir("../Games/$gameName", 0700, true)) {
  http_response_code(500);
  echo json_encode(["error" => "Game file could not be created."]);
  exit;
}

$player = intval($candidate["player"]);
$opponent = $player == 1 ? 2 : 1;
$p1Key = bin2hex(random_bytes(32));
$p2Key = bin2hex(random_bytes(32));
$gamestate = PreparePuzzleGamestate($content, $player, $p1Key, $p2Key, $emptyOpponentHand, $removeDecks);
$lines = explode("\r\n", $gamestate);
$p1Hero = explode(" ", trim($lines[3]))[0];
$p2Hero = explode(" ", trim($lines[21]))[0];

$p1Data = [1];
$p2Data = [2];
$gameStatus = 5; //MGS_GameStarted
$format = FormatName(intval($candidate["format"]));
$visibility = "private";
$firstPlayerChooser = "";
$firstPlayer = trim($lines[39]);
$p1uid = $player == 1 ? $useruid : "Puzzle Opponent";
$p2uid = $player == 2 ? $useruid : "Puzzle Opponent";
$p1id = "-";
$p2id = "-";
$gameDescription = "Puzzle #$candidateID";
$hostIP = GetClientIP();
$p1IsPatron = "";
$p2IsPatron = "";
$p1DeckLink = "";
$p2DeckLink = "";
$p1IsChallengeActive = "0";
$p2IsChallengeActive = "0";
$joinerIP = "";
$p1Matchups = [];
$p2Matchups = [];
$p1deckbuilderID = "";
$p2deckbuilderID = "";
$p1StartingHealth = "";
$p1ContentCreatorID = "";
$p2ContentCreatorID = "";
$p1SideboardSubmitted = "1";
$p2SideboardSubmitted = "1";
$p1StartingEquipment = [];
$p2StartingEquipment = [];
$p1IsAI = "0";
$p2IsAI = "0";
$gameGUID = GenerateGameGUID();
$p1DisplayName = $p1uid;
$p2DisplayName = $p2uid;
$p1EquipmentSubmitted = "1";
$p2EquipmentSubmitted = "1";

$gameFileHandler = @fopen("../Games/$gameName/GameFile.txt", "w");
if ($gameFileHandler === false) {
  http_response_code(500);
  echo json_encode(["error" => "Game file could not be initialized."]);
  exit;
}
include "../MenuFiles/WriteGamefile.php";
WriteGameFile();

$opponentLife = intval($candidate["opponent_life"]);
$intro = "<p style='background: #005900;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>"
  . "🧩 Puzzle #$candidateID: win this turn. Your opponent is at $opponentLife life.</span></p>\r\n";
file_put_contents("../Games/$gameName/gamestate.txt", $gamestate);
file_put_contents("../Games/$gameName/beginTurnGamestate.txt", $gamestate);
file_put_contents("../Games/$gameName/gamelog.txt", $intro);
file_put_contents("../Games/$gameName/" . PUZZLE_MARKER_FILE, (string)$candidateID);

$currentTime = round(microtime(true) * 1000);
WriteCache($gameName, "1!$currentTime!$currentTime!-1!-1!$currentTime!$p1Hero!$p2Hero!0!0!0!0!" . FormatCode($format) . "!$gameStatus!0!0");
WriteGamestateCache($gameName, $gamestate);
GamestateUpdated($gameName);

echo json_encode([
  "gameName" => $gameName,
  "playerID" => $player,
  "authKey" => $player == 1 ? $p1Key : $p2Key,
  "opponentPlayerID" => $opponent,
  "opponentAuthKey" => $opponent == 1 ? $p1Key : $p2Key
]);
