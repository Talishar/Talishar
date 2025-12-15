<?php

if(!isset($filename) || !str_contains($filename, "gamestate.txt")) $filename = "./Games/" . $gameName . "/gamestate.txt";
$handler = fopen($filename, "w");

if ($handler === false) {
  error_log("ERROR: Failed to open gamestate file: " . $filename . " (from game: " . $gameName . ")");
  exit;
}

$lockTries = 0;
while (!flock($handler, LOCK_EX) && $lockTries < 10) {
  usleep(100000); //100ms
  ++$lockTries;
}

if ($lockTries == 10) { fclose($handler); exit; }

$gamestateLines = [
  implode(" ", $playerHealths),
  
  // Player 1
  implode(" ", $p1Hand),
  implode(" ", $p1Deck),
  implode(" ", $p1CharEquip),
  implode(" ", $p1Resources),
  implode(" ", $p1Arsenal),
  implode(" ", $p1Items),
  implode(" ", $p1Auras),
  implode(" ", $p1Discard),
  implode(" ", $p1Pitch),
  implode(" ", $p1Banish),
  implode(" ", $p1ClassState),
  implode(" ", $p1CharacterEffects),
  implode(" ", $p1Soul),
  implode(" ", $p1CardStats),
  implode(" ", $p1TurnStats),
  implode(" ", $p1Allies),
  implode(" ", $p1Permanents),
  implode(" ", $p1Settings),
  
  // Player 2
  implode(" ", $p2Hand),
  implode(" ", $p2Deck),
  implode(" ", $p2CharEquip),
  implode(" ", $p2Resources),
  implode(" ", $p2Arsenal),
  implode(" ", $p2Items),
  implode(" ", $p2Auras),
  implode(" ", $p2Discard),
  implode(" ", $p2Pitch),
  implode(" ", $p2Banish),
  implode(" ", $p2ClassState),
  implode(" ", $p2CharacterEffects),
  implode(" ", $p2Soul),
  implode(" ", $p2CardStats),
  implode(" ", $p2TurnStats),
  implode(" ", $p2Allies),
  implode(" ", $p2Permanents),
  implode(" ", $p2Settings),
  
  implode(" ", $landmarks),
  $winner,
  $firstPlayer,
  $currentPlayer,
  $currentTurn,
  implode(" ", $turn),
  $actionPoints,
  implode(" ", $combatChain),
  implode(" ", $combatChainState),
  implode(" ", $currentTurnEffects),
  implode(" ", $currentTurnEffectsFromCombat),
  implode(" ", $nextTurnEffects),
  implode(" ", $decisionQueue),
  implode(" ", $dqVars),
  implode(" ", $dqState),
  implode(" ", $layers),
  implode(" ", $layerPriority),
  $mainPlayer,
  implode(" ", $lastPlayed),
  count($chainLinks),
];

// Add chain links
$chainLinksCount = count($chainLinks);
for ($i = 0; $i < $chainLinksCount; ++$i) {
  $gamestateLines[] = implode(" ", $chainLinks[$i]);
}

$gamestateLines = array_merge($gamestateLines, [
  implode(" ", $chainLinkSummary),
  $p1Key,
  $p2Key,
  $permanentUniqueIDCounter,
  $inGameStatus, // Game status -- 0 = START, 1 = PLAY, 2 = OVER
  "", // Animations - Deprecated
  $currentPlayerActivity, // Current Player activity status -- 0 = active, 2 = inactive
  "", // Unused
  "", // Unused
  $p1TotalTime, // Player 1 total time
  $p2TotalTime, // Player 2 total time
  $lastUpdateTime, // Last update time
  $roguelikeGameID, // Roguelike game ID
  implode(" ", $events), // Events
  $EffectContext, // Update number the gamestate is for
  implode(" ", $p1Inventory),
  implode(" ", $p2Inventory),
  $p1IsAI,
  $p2IsAI,
  $AIHasInfiniteHP ? "1" : "0",
]);

$gamestateContent = implode("\r\n", $gamestateLines) . "\r\n";

fwrite($handler, $gamestateContent);

flock($handler, LOCK_UN);
fclose($handler);

WriteGamestateCache($gameName, $gamestateContent);