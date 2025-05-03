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
include "AI/CombatDummy.php";
include "Libraries/HTTPLibraries.php";
require_once "Libraries/CoreLibraries.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "APIKeys/APIKeys.php";

SetHeaders();
$_POST = json_decode(file_get_contents('php://input'), true);

//We should always have a player ID as a URL parameter
// Check if the "gameName" key exists in the $_POST array
$gameName = $_POST["gameName"]; // null;ProcessInput.php
if (!IsGameNameValid($gameName)) {
  echo "Invalid game name.";
  exit;
}
$playerID = $_POST["playerID"];
$authKey = $_POST["authKey"];

//We should also have some information on the type of command
$mode = $_POST["mode"];
$submission = $_POST["submission"];
$submission = json_encode($submission);
$submission = json_decode($submission); //I don't know why it's not correctly parsing as objects all the way down here

//First we need to parse the game state from the file
include "ParseGamestate.php";

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

if (!IsReplay()) {
  if (($playerID == 1 || $playerID == 2) && $authKey == "") {
    if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
  }
  if ($playerID != 3 && $authKey != $targetAuth) exit;
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
$events = []; //Clear events each time so it's only updated ones that get sent

$isSimulation = false;
$response = new stdClass();

//Now we can process the command
switch ($mode) {
  case 26: //Change setting
    $userID = "";
    if (!$isSimulation) {
      include "MenuFiles/ParseGamefile.php";
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      if ($playerID == 1) $userID = $p1id;
      else $userID = $p2id;
    }
    for ($i = 0; $i < count($submission->settings); ++$i) {
      $setting = $submission->settings[$i];
      $setting->id = ParseSettingsStringValueToIdInt($setting->name) ?? $setting->id;
      ChangeSetting($playerID, $setting->id, $setting->value, $userID);
    }
    $response->message = "Settings changed successfully.";
    break;
  case 33: //Fully re-order layers
    //First validate
    $isValid = true;
    if (count($submission->layers) < $dqState[8] / LayerPieces()) {
      $response->error = "Not enough layers.";
      $isValid = false;
      break;
    }
    for ($i = 0; $i < count($submission->layers); ++$i) {
      $layerID = $submission->layers[$i];
      if ($layerID % LayerPieces() != 0) {
        $response->error = "Not a layer ID.";
        $isValid = false;
        break;
      }
      if ($layerID < 0 || $layerID > $dqState[8]) {
        $response->error = "Layer ID out of range.";
        $isValid = false;
        break;
      }
      for ($j = $i + 1; $j < count($submission->layers); ++$j) {
        if ($layerID == $submission->layers[$j]) {
          $response->error = "Layer ID is duplicated.";
          $isValid = false;
          break;
        }
      }
    }
    //Now if it's valid, do the swap
    $newLayers = [];
    for($i = 0; $i < count($submission->layers); ++$i) {
      for($j = $submission->layers[$i]; $j < $submission->layers[$i] + LayerPieces(); ++$j) {
        if(isset($layers[$j])) array_push($newLayers, $layers[$j]);
      }
    }
    if(count($layers) > count($newLayers)) {
      for($i = $dqState[8] + LayerPieces(); $i < $dqState[8] + LayerPieces() * count($layers); ++$i) {
        if(isset($layers[$i])) array_push($newLayers, $layers[$i]);
      }
    }
    $layers = $newLayers;
    break;
  case 106: // change opt order
      $deck = new Deck($playerID);
      $cardListTop = $submission->cardListTop;
      $cardListBottom = $submission->cardListBottom;
      $cardListTopString = implode(",", $cardListTop);
      $cardListBottomString = implode(",", $cardListBottom);
      $newOptions = $cardListTopString . ";" . $cardListBottomString;
      $turn[2] = $newOptions;
      break;
    case 107: //submit Opt
      $deck = new Deck($playerID);
      $cardListTop = $submission->cardListTop;
      $cardListBottom = $submission->cardListBottom;
      $deck->Opt($cardListTop, $cardListBottom);

      $topCount = count($cardListTop);
      $bottomCount = count($cardListBottom);
      $topMessage = $topCount . " card" . ($topCount > 1 ? "s" : "") . " on top";
      $bottomMessage = $bottomCount . " card" . ($bottomCount > 1 ? "s" : "") . " on the bottom";
      WriteLog("Player " . $playerID . " has put " . $topMessage . " and " . $bottomMessage . " of their deck.");
      ContinueDecisionQueue();
      break;
  case 100011: //Resume adventure (roguelike)
    if($roguelikeGameID == "") {
      $response->error = "Cannot resume adventure - not a roguelike game.";
      $isValid = false;
      break;
    }
    $response->redirectLink = $redirectPath . "/Roguelike/ContinueAdventure.php?gameName=" . $roguelikeGameID . "&playerID=1&health=" . GetHealth(1);
    break;
  default:
    break;
}
echo (json_encode($response));

ProcessMacros();
if ($inGameStatus == $GameStatus_Rematch) {
  $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
  $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
  include "MenuFiles/ParseGamefile.php";
  include "MenuFiles/WriteGamefile.php";
  $gameStatus = (IsPlayerAI(2) ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
  SetCachePiece($gameName, 14, $gameStatus);
  $firstPlayer = 1;
  $firstPlayerChooser = ($winner == 1 ? 2 : 1);
  $p1SideboardSubmitted = "0";
  $p2SideboardSubmitted = (IsPlayerAI(2) ? "1" : "0");
  WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
  WriteGameFile();
  $turn[0] = "REMATCH";
  include "WriteGamestate.php";
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 2, $currentTime);
  SetCachePiece($gameName, 3, $currentTime);
  GamestateUpdated($gameName);
  exit;
} else if ($winner != 0 && $turn[0] != "YESNO") {
  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  $currentPlayer = 1;
}

CombatDummyAI(); //Only does anything if applicable
EncounterAI();
CacheCombatResult();

if (!IsGameOver()) {
  if ($playerID == 1) $p1TotalTime += time() - intval($lastUpdateTime);
  else if ($playerID == 2) $p2TotalTime += time() - intval($lastUpdateTime);
  $lastUpdateTime = time();
}

//Now write out the game state
if (!$skipWriteGamestate) {
  if (!IsModeAsync($mode)) {
    SetCachePiece($gameName, 12, "0");
    $currentPlayerActivity = 0;
  }
  DoGamestateUpdate();
  include "WriteGamestate.php";
}

if ($makeCheckpoint) MakeGamestateBackup();
if ($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");
if ($MakeStartTurnBackup) MakeStartTurnBackup();
if ($MakeStartGameBackup) MakeGamestateBackup("origGamestate.txt");

GamestateUpdated($gameName);

exit;
