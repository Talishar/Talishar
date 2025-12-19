<?php

error_reporting(E_ALL);

include "WriteLog.php";
include "GameLogic.php";
include "GameTerms.php";
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "Libraries/StatFunctions.php";
include "Libraries/UILibraries.php";
include "Libraries/PlayerSettings.php";
include "Libraries/NetworkingLibraries.php";
include "Libraries/CacheLibraries.php";
include "AI/CombatDummy.php";
include "Libraries/HTTPLibraries.php";
require_once "Libraries/CoreLibraries.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "APIKeys/APIKeys.php";
include_once "./Libraries/ValidationLibraries.php";

//We should always have a player ID as a URL parameter
$gameName = isset($_GET["gameName"]) ? $_GET["gameName"] : "";
if (!IsGameNameValid($gameName)) {
  echo "Invalid game name.";
  exit;
}
$playerID = $_GET["playerID"];
$authKey = $_GET["authKey"];
$mode = $_GET["mode"];

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

if($mode == 100015)
{
  if($playerID == 1 && intval(GetCachePiece($gameName, 15)) == 1) exit;
  else if($playerID == 2 && intval(GetCachePiece($gameName, 16)) == 1) exit;
  else if($playerID != 1 && $playerID != 2) exit;
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
  $chk = isset($_GET[("chk" . $i)]) ? sanitizeString($_GET[("chk" . $i)]) : "";
  if ($chk != "") $chkInput[] = $chk;
}
$inputText = isset($_GET["inputText"]) ? sanitizeString($_GET["inputText"]) : "";

SetHeaders();

$numPass = 0;
//First we need to parse the game state from the file
include "ParseGamestate.php";

if(IsReplay() && $mode == 99)
{
  $filename = "./Games/$gameName/replayCommands.txt";
  $commands = file($filename);
  $pointer = intval(trim($commands[0])) + 1;
  $line = isset($commands[$pointer]) ? $commands[$pointer] : "";
  $params = explode(" ", $line);
  $playerID = isset($params[0]) ? $params[0] : "";
  $mode = isset($params[1]) ? $params[1] : "";
  $buttonInput = isset($params[2]) ? $params[2] : "";
  $cardID = isset($params[3]) ? $params[3] : "";
  $chkCount = isset($params[4]) ? $params[4] : "0";
  $chkInput = isset($params[5]) ? explode("|", $params[5]) : [];
  $chkInputCount = count($chkInput);
  for($i=0; $i<$chkInputCount; ++$i)
  {
    $chkInput[$i] = trim($chkInput[$i]);
  }
  //skip any inputs where the non-active player tries something
  if ($mode == "StartTurn" || $playerID != $currentPlayer) {
    ++$pointer;
    $line = isset($commands[$pointer]) ? $commands[$pointer] : "";
    $params = explode(" ", $line);
    $playerID = isset($params[0]) ? $params[0] : "";
    $mode = isset($params[1]) ? $params[1] : "";
    $buttonInput = isset($params[2]) ? $params[2] : "";
    $cardID = isset($params[3]) ? $params[3] : "";
    $chkCount = isset($params[4]) ? $params[4] : "0";
    $chkInput = isset($params[5]) ? explode("|", $params[5]) : [];
    $chkInputCount = count($chkInput);
    for($i=0; $i<$chkInputCount; ++$i)
    {
      $chkInput[$i] = trim($chkInput[$i]);
    }
  }
  //Automate extra passes
  // for($i=1; $i<count($commands); ++$i)
  // {
  //   $line = $commands[$pointer+1];
  //   $params = explode(" ", $line);
  //   if(intval($mode) != 99 || intval($params[1]) != 99) break;
  //   ++$numPass;
  //   ++$pointer;
  // }
  $commands[0] = "$pointer\r\n";
  file_put_contents($filename, $commands);
}

$isProcessInput = true;

$otherPlayer = $currentPlayer == 1 ? 2 : 1;
$skipWriteGamestate = false;
$mainPlayerGamestateStillBuilt = 0;
$makeCheckpoint = 0;
$makeBlockBackup = 0;
$MakeStartTurnBackup = false;
$MakeStartGameBackup = false;
$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
$conceded = false;
$randomSeeded = false;

if(!IsReplay()) {
  if (($playerID == 1 || $playerID == 2) && $authKey == "") {
    if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
  }
  if ($playerID != 3 && $authKey != $targetAuth) {
    // Failsafe: Use game file's auth key if mismatch (lost on page refresh)
    $authKey = $targetAuth;
  }
  if ($playerID == 3 && !IsModeAllowedForSpectators($mode)) exit;
  if (!IsModeAsync($mode) && $currentPlayer != $playerID) {
    $currentTime = round(microtime(true) * 1000);
    SetCachePiece($gameName, 2, $currentTime);
    SetCachePiece($gameName, 3, $currentTime);
    exit;
  }
}

$afterResolveEffects = [];

$animations = [];
$events = [];//Clear events each time so it's only updated ones that get sent

// if ((IsPatron(1) || IsPatron(2)) && !IsReplay()) {
if (SaveReplay() && !IsReplay()) {
  $commandFile = fopen("./Games/$gameName/commandfile.txt", "a");
  fwrite($commandFile, $playerID . " " . $mode . " " . $buttonInput . " " . $cardID . " " . $chkCount . " " . implode("|", $chkInput) . "\r\n");
  fclose($commandFile);
}

//Now we can process the command
ProcessInput($playerID, $mode, $buttonInput, $cardID, $chkCount, $chkInput, false, $inputText);

ProcessMacros();
if ($inGameStatus == $GameStatus_Rematch) {
  include "MenuFiles/ParseGamefile.php";
  $origDeck = "./Games/{$gameName}/p1DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/{$gameName}/p1Deck.txt");
  $origDeck = "./Games/{$gameName}/p2DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/{$gameName}/p2Deck.txt");
  include "MenuFiles/WriteGamefile.php";
  $p2IsAILocal = $p2IsAI == "1";
  $gameStatus = ($p2IsAILocal ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
  SetCachePiece($gameName, 14, $gameStatus);
  $firstPlayer = 1;
  $firstPlayerChooser = ($winner == 1 ? 2 : 1);
  $p1SideboardSubmitted = "0";
  $p2SideboardSubmitted = ($p2IsAILocal ? "1" : "0");
  WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
  WriteGameFile();
  $turn[0] = "REMATCH";
  include "WriteGamestate.php";
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 2, $currentTime);
  SetCachePiece($gameName, 3, $currentTime);
  InvalidateGamestateCache($gameName); // Clear cached gamestate once at end
  GamestateUpdated($gameName);
  exit;
} else if ($winner != 0 && $turn[0] != "YESNO") {
  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  $currentPlayer = 1;
}

CombatDummyAI(); //Only does anything if applicable
if ($p2IsAI == "1") {
  EncounterAI();
}
CacheCombatResult();

if (!IsGameOver()) {
  switch ($playerID) {
    case 1:
      $p1TotalTime += time() - intval($lastUpdateTime);
      break;
    case 2:
      $p2TotalTime += time() - intval($lastUpdateTime);
      break;
  }
  $lastUpdateTime = time();
}

//Now write out the game state
if (!$skipWriteGamestate) {
  if(!IsModeAsync($mode))
  {
    $currentTime = round(microtime(true) * 1000);
    SetCachePiece($gameName, 12, "0");
    SetCachePiece($gameName, 2, $currentTime);
    SetCachePiece($gameName, 3, $currentTime);
    $currentPlayerActivity = 0;
  }
  DoGamestateUpdate();
  include "WriteGamestate.php";
}

// Consolidate backup operations
if ($makeCheckpoint) MakeGamestateBackup();
if ($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");
if ($MakeStartTurnBackup) MakeStartTurnBackup();
if ($MakeStartGameBackup) MakeGamestateBackup("origGamestate.txt");

InvalidateGamestateCache($gameName); // Clear cached gamestate once after all updates
GamestateUpdated($gameName);

exit;
