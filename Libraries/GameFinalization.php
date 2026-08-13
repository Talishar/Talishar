<?php

// Shared processing for ProcessInput.php and ProcessInputAPI.php
include_once "Libraries/CacheLibraries.php";

ProcessMacros();

if ($inGameStatus == $GameStatus_Rematch || $inGameStatus == $GameStatus_SwapRematch) {
  $isSwapRematch = ($inGameStatus == $GameStatus_SwapRematch);

  include "MenuFiles/ParseGamefile.php";

  $p1OrigPath = "./Games/{$gameName}/p1DeckOrig.txt";
  $p2OrigPath = "./Games/{$gameName}/p2DeckOrig.txt";

  if ($isSwapRematch && file_exists($p1OrigPath) && file_exists($p2OrigPath)) {
    $tempPath = "./Games/{$gameName}/p_swap_temp.txt";
    rename($p1OrigPath, $tempPath);
    rename($p2OrigPath, $p1OrigPath);
    rename($tempPath, $p2OrigPath);
  }

  if (file_exists($p1OrigPath)) copy($p1OrigPath, "./Games/{$gameName}/p1Deck.txt");
  if (file_exists($p2OrigPath)) copy($p2OrigPath, "./Games/{$gameName}/p2Deck.txt");

  include "MenuFiles/WriteGamefile.php";

  if ($isSwapRematch) {
    // Swap deck metadata so the lobby shows the correct hero for each player.
    [$p1DeckLink, $p2DeckLink] = [$p2DeckLink, $p1DeckLink];
    [$p1deckbuilderID, $p2deckbuilderID] = [$p2deckbuilderID, $p1deckbuilderID];
    [$p1StartingEquipment, $p2StartingEquipment] = [$p2StartingEquipment, $p1StartingEquipment];
    [$p1MetafyTiers, $p2MetafyTiers] = [$p2MetafyTiers, $p1MetafyTiers];
    [$p1MetafyCommunities, $p2MetafyCommunities] = [$p2MetafyCommunities, $p1MetafyCommunities];
  }

  $gameGUID = GenerateGameGUID(); // Generate a unique game GUID (e.g. for hero mastery)
  $p2IsAILocal = $p2IsAI == "1";
  $gameStatus = ($p2IsAILocal ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
  SetCachePiece($gameName, 14, $gameStatus);
  $firstPlayer = 1;
  $firstPlayerChooser = ($winner == 1 ? 2 : 1);
  $p1SideboardSubmitted = "0";
  $p2SideboardSubmitted = ($p2IsAILocal ? "1" : "0");

  if ($isSwapRematch) {
    TruncateLogAboveMarker(["offered to swap heroes and rematch."]);
    WriteLog("🔁 Heroes swapped! Player $firstPlayerChooser will choose who goes first.", highlight: true, highlightColor: "darkblue");
  } else {
    TruncateLogAboveMarker(["sent a rematch invitation."]);
    WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
  }

  WriteGameFile();
  $turn[0] = "REMATCH";
  include "WriteGamestate.php";
  $currentTime = round(microtime(true) * 1000);
  SetCachePieces($gameName, [2 => $currentTime, 3 => $currentTime]);
  InvalidateGamestateCache($gameName);
  GamestateUpdated($gameName);
  exit;
} else if ($winner != 0 && ($turn[0] ?? "") != "YESNO") {
  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  $currentPlayer = 1;
  // Clear events to prevent infinite event loops when the game is over.
  global $events;
  $events = [];
}

CombatDummyAI(); //Only does anything if applicable
if ($p2IsAI == "1") {
  EncounterAI();
}
CacheCombatResult();

if (!IsGameOver()) {
  $now = time();
  $update = $now - intval($lastUpdateTime);
  switch ($playerID) {
    case 1:
      $p1TotalTime = is_numeric($p1TotalTime) ? $p1TotalTime + $update : $update;
      break;
    case 2:
      $p2TotalTime = is_numeric($p2TotalTime) ? $p2TotalTime + $update : $update;
      break;
  }
  $lastUpdateTime = $now;
}

if (!$skipWriteGamestate) {
  if (!IsModeAsync($mode)) {
    $currentTime = round(microtime(true) * 1000);
    SetCachePieces($gameName, [12 => "0", 2 => $currentTime, 3 => $currentTime]);
  }
  DoGamestateUpdate();
  include "WriteGamestate.php";
}

if ($makeCheckpoint) MakeGamestateBackup();
if ($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");
if ($MakeStartTurnBackup) MakeStartTurnBackup();
if ($MakeStartGameBackup) MakeGamestateBackup("origGamestate.txt");

InvalidateGamestateCache($gameName);
GamestateUpdated($gameName);

exit;
