<?php

error_reporting(E_ALL);

include "WriteLog.php";
include "GameLogic.php";
include "GameTerms.php";
include "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include "Libraries/StatFunctions.php";
include "Libraries/UILibraries.php";
include "Libraries/PlayerSettings.php";
include "Libraries/NetworkingLibraries.php";
include "Libraries/CacheLibraries.php";
include_once "includes/MetafyHelper.php";
include "AI/CombatDummy.php";
include "Libraries/HTTPLibraries.php";
include_once "Libraries/ReplayLibraries.php";
require_once "Libraries/CoreLibraries.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "APIKeys/APIKeys.php";
include_once "./Libraries/ValidationLibraries.php";

@set_time_limit(1);
@ini_set('max_execution_time', '1');

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"] ?? "";
if (!IsGameNameValid($gameName)) {
  echo "Invalid game name.";
  exit;
}
$playerID = $_GET["playerID"] ?? "";
$authKey = $_GET["authKey"] ?? "";
$mode = $_GET["mode"] ?? "";

// Validate player ID
if (!validatePlayerID($playerID)) {
  echo "Invalid player ID.";
  exit;
}

// Validate mode is a valid integer
if (!validateInteger($mode, 1, 999999)) {
  echo "Invalid mode.";
  exit;
}

if ($mode == 100015) {
  if ($playerID != 1 && $playerID != 2) exit; // skip cache I/O for spectators
  $ackPiece = ($playerID == 1) ? 15 : 16;
  if (intval(GetCachePiece($gameName, $ackPiece)) == 1) exit;
}

//We should also have some information on the type of command
$buttonInput = isset($_GET["buttonInput"]) ? sanitizeString($_GET["buttonInput"]) : ""; //The player that is the target of the command - e.g. for changing life total
$cardID = isset($_GET["cardID"]) ? sanitizeString($_GET["cardID"]) : "";
$numMode = isset($_GET["numMode"]) ? intval($_GET["numMode"]) : 0;
$chkCount = isset($_GET["chkCount"]) ? intval($_GET["chkCount"]) : 0;

// Validate card ID if provided
if (!empty($cardID) && !validateCardID($cardID)) {
  echo "Invalid card ID.";
  exit;
}

// Validate check count
if ($chkCount < 0 || $chkCount > 100) {
  echo "Invalid check count.";
  exit;
}
$chkInput = [];
for ($i = 0; $i < $chkCount; ++$i) {
  $key = "chk$i";
  if (isset($_GET[$key])) {
    $chk = sanitizeString($_GET[$key]);
    if ($chk !== "") $chkInput[] = $chk;
  }
}
$inputText = isset($_GET["inputText"]) ? sanitizeString($_GET["inputText"]) : "";

SetHeaders();

$numPass = 0;
//First we need to parse the game state from the file
$gameActionLock = AcquireGameActionLock($gameName);
if ($gameActionLock === false) {
  http_response_code(503);
  echo "Unable to lock game for processing.";
  exit;
}
register_shutdown_function(static function () use (&$gameActionLock): void {
  ReleaseGameActionLock($gameActionLock);
  $gameActionLock = null;
});

include "ParseGamestate.php";

$isReplayAdvance = false;
$replayUndoHasRecordedResponse = false;
if (IsReplay()) {
  $requestedReplayMode = intval($mode);
  $isReplayAdvance = $requestedReplayMode === 99;

  if (!IsReplayControlMode($requestedReplayMode)) {
    http_response_code(403);
    echo "Only replay controls can be used while reviewing a replay.";
    exit;
  }
}

if (IsReplay() && $mode == 99) {
  $filename = "./Games/$gameName/replayCommands.txt";
  $commands = file($filename);
  $currentPointer = intval(trim($commands[0] ?? "0"));
  [$pointer, $params] = NextReplayCommand($commands, $currentPointer);
  $playerID = $params[0] ?? "";
  $mode = $params[1] ?? "";
  $buttonInput = $params[2] ?? "";
  $cardID = $params[3] ?? "";
  $chkCount = $params[4] ?? "0";
  $chkInput = isset($params[5]) ? array_map('trim', explode("|", $params[5])) : [];
  if (intval($mode) === 10000 || intval($mode) === 10003) {
    $replayUndoHasRecordedResponse = ReplayUndoHasRecordedResponse($commands, $pointer);
  }
  global $filepath;
  $snapshotName = "replayStep_$currentPointer.txt";
  if (SaveGamestateSnapshot($filepath . $snapshotName)) {
    $historyFilename = $filepath . "replayStepHistory.json";
    $history = file_exists($historyFilename)
      ? json_decode(file_get_contents($historyFilename), true)
      : [];
    if (!is_array($history)) $history = [];
    $history[(string)$pointer] = $currentPointer;
    file_put_contents($historyFilename, json_encode($history), LOCK_EX);
  }
  $commands[0] = "$pointer\r\n";
  file_put_contents($filename, $commands);
}

$isProcessInput = true;

$otherPlayer = 3 - $currentPlayer;
$skipWriteGamestate = false;
$mainPlayerGamestateStillBuilt = 0;
$makeCheckpoint = 0;
$makeBlockBackup = 0;
$MakeStartTurnBackup = false;
$MakeStartGameBackup = false;
$conceded = false;
$randomSeeded = false;

if(!IsReplay()) {
  if ($playerID == 3 && !IsModeAllowedForSpectators($mode)) exit;
  if (!IsModeAsync($mode) && $currentPlayer != $playerID) {
    $currentTime = (int)(microtime(true) * 1000);
    SetCachePieces($gameName, [2 => $currentTime, 3 => $currentTime]);
    exit;
  }
  if (($playerID == 1 || $playerID == 2) && $authKey == "") {
    if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
  }
  if (!validateGameAuthKey($playerID, $authKey, $p1Key, $p2Key)) {
    echo "Invalid auth key.";
    exit;
  }
}

$afterResolveEffects = [];

$animations = [];
$priorEvents = $events;//Kept so a still pending undo request can be reissued with a reason
$events = [];//Clear events each time so it's only updated ones that get sent

if ($mode == 27) { //TODO add this to other play/activate modes
  $hand = GetHand($playerID);
  $index = intval($cardID);
  $buttonInput = $hand[$index] ?? "";
}
// if ((IsPatron(1) || IsPatron(2)) && !IsReplay()) {
if (SaveReplay() && !IsReplay()) {
  $commandFile = fopen("./Games/$gameName/commandfile.txt", "a");
  fwrite($commandFile, "$playerID $mode $buttonInput $cardID $chkCount " . implode("|", $chkInput) . "\r\n");
  fclose($commandFile);
}

//Now we can process the command
ProcessInput($playerID, $mode, $buttonInput, $cardID, $chkCount, $chkInput, false, $inputText);

if ((int)$mode === 100012) {
  echo json_encode($replaySaveResult ?? [
    "success" => false,
    "message" => "Replay could not be saved. Please try again."
  ]);
}

// Rematch handling, AI turns, clock accumulation, persistence and backups.
include "Libraries/GameFinalization.php";
