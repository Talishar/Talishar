<?php

error_reporting(E_ALL);

include "WriteLog.php";
include "WriteReplay.php";
include "GameLogic.php";
include "GameTerms.php";
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "Libraries/StatFunctions.php";
include "Libraries/UILibraries.php";
include "Libraries/PlayerSettings.php";
include "Libraries/NetworkingLibraries.php";
include "AI/CombatDummy.php";
include "AI/PlayerMacros.php";
include "Libraries/HTTPLibraries.php";
require_once("Libraries/CoreLibraries.php");
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "APIKeys/APIKeys.php";

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$authKey = $_GET["authKey"];

//We should also have some information on the type of command
$mode = $_GET["mode"];
$buttonInput = isset($_GET["buttonInput"]) ? $_GET["buttonInput"] : ""; //The player that is the target of the command - e.g. for changing health total
$cardID = isset($_GET["cardID"]) ? $_GET["cardID"] : "";
$chkCount = isset($_GET["chkCount"]) ? $_GET["chkCount"] : 0;
$chkInput = [];
for ($i = 0; $i < $chkCount; ++$i) {
  $chk = isset($_GET[("chk" . $i)]) ? $_GET[("chk" . $i)] : "";
  if ($chk != "") array_push($chkInput, $chk);
}

//Testing only
//WriteLog('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . "&gameName=$gameName&playerID=$playerID&authKey=$authKey&mode=$mode&cardID=$cardID");

//First we need to parse the game state from the file
include "ParseGamestate.php";
$otherPlayer = $currentPlayer == 1 ? 2 : 1;
$skipWriteGamestate = false;
$mainPlayerGamestateStillBuilt = 0;
$makeCheckpoint = 0;
$makeBlockBackup = 0;
$MakeStartTurnBackup = false;
$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
$conceded = false;
$randomSeeded = false;

if ($playerID != 3 && $authKey != $targetAuth) exit;
if ($playerID == 3 && !IsModeAllowedForSpectators($mode)) ExitProcessInput();
if (!IsModeAsync($mode) && $currentPlayer != $playerID)
{
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 2, $currentTime);
  SetCachePiece($gameName, 3, $currentTime);
  ExitProcessInput();
}

$afterResolveEffects = [];

$animations = [];

if((IsPatron(1) || IsPatron(2)))
{
  $commandFile = fopen("./Games/" . $gameName . "/commandfile.txt", "a");
  fwrite($commandFile, $playerID . " " . $mode . " " . $buttonInput . " " . $cardID . " " . $chkCount . " " . implode("|", $chkInput) . "\r\n");
  fclose($commandFile);
}

//Now we can process the command
ProcessInput($playerID, $mode, $buttonInput, $cardID, $chkCount, $chkInput);

ProcessMacros();
if($inGameStatus == $GameStatus_Rematch)
{
  $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
  $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
  include "MenuFiles/ParseGamefile.php";
  include "MenuFiles/WriteGamefile.php";
  $gameStatus = (IsPlayerAI(2) ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
  $firstPlayer = 1;
  $firstPlayerChooser = ($winner == 1 ? 2 : 1);
  WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
  WriteGameFile();
  $turn[0] = "REMATCH";
  include "WriteGamestate.php";
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 2, $currentTime);
  SetCachePiece($gameName, 3, $currentTime);
  GamestateUpdated($gameName);
  exit;
}
else if ($winner != 0 && $turn[0] != "YESNO") {
  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  $currentPlayer = 1;
}

CombatDummyAI(); //Only does anything if applicable
CacheCombatResult();

if(!IsGameOver())
{
  if($playerID == 1) $p1TotalTime += time() - intval($lastUpdateTime);
  else if($playerID == 2) $p2TotalTime += time() - intval($lastUpdateTime);
  $lastUpdateTime = time();
}

//Now write out the game state
if (!$skipWriteGamestate) {
  //if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGamestate();
  //else UpdateGameState(1);
  DoGamestateUpdate();
  include "WriteGamestate.php";
}

if ($makeCheckpoint) MakeGamestateBackup();
if ($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");
if ($MakeStartTurnBackup) MakeStartTurnBackup();

GamestateUpdated($gameName);

ExitProcessInput();
