<?php

if(!isset($filename) || !str_contains($filename, "gamestate.txt")) $filename = "./Games/" . $gameName . "/gamestate.txt";
$handler = fopen($filename, "w");

if ($handler === false) {
  error_log("ERROR: Failed to open gamestate file: " . $filename . " (from game: " . $gameName . ")");
  exit;
}

$lockTries = 0;
while (!flock($handler, LOCK_EX) && $lockTries < 10) {
  usleep(100000); //50ms
  ++$lockTries;
}

if ($lockTries == 10) { fclose($handler); exit; }

$gamestateContent = "";

$gamestateContent .= implode(" ", $playerHealths) . "\r\n";

//Player 1
$gamestateContent .= implode(" ", $p1Hand) . "\r\n";
$gamestateContent .= implode(" ", $p1Deck) . "\r\n";
$gamestateContent .= implode(" ", $p1CharEquip) . "\r\n";
$gamestateContent .= implode(" ", $p1Resources) . "\r\n";
$gamestateContent .= implode(" ", $p1Arsenal) . "\r\n";
$gamestateContent .= implode(" ", $p1Items) . "\r\n";
$gamestateContent .= implode(" ", $p1Auras) . "\r\n";
$gamestateContent .= implode(" ", $p1Discard) . "\r\n";
$gamestateContent .= implode(" ", $p1Pitch) . "\r\n";
$gamestateContent .= implode(" ", $p1Banish) . "\r\n";
$gamestateContent .= implode(" ", $p1ClassState) . "\r\n";
$gamestateContent .= implode(" ", $p1CharacterEffects) . "\r\n";
$gamestateContent .= implode(" ", $p1Soul) . "\r\n";
$gamestateContent .= implode(" ", $p1CardStats) . "\r\n";
$gamestateContent .= implode(" ", $p1TurnStats) . "\r\n";
$gamestateContent .= implode(" ", $p1Allies) . "\r\n";
$gamestateContent .= implode(" ", $p1Permanents) . "\r\n";
$gamestateContent .= implode(" ", $p1Settings) . "\r\n";

//Player 2
$gamestateContent .= implode(" ", $p2Hand) . "\r\n";
$gamestateContent .= implode(" ", $p2Deck) . "\r\n";
$gamestateContent .= implode(" ", $p2CharEquip) . "\r\n";
$gamestateContent .= implode(" ", $p2Resources) . "\r\n";
$gamestateContent .= implode(" ", $p2Arsenal) . "\r\n";
$gamestateContent .= implode(" ", $p2Items) . "\r\n";
$gamestateContent .= implode(" ", $p2Auras) . "\r\n";
$gamestateContent .= implode(" ", $p2Discard) . "\r\n";
$gamestateContent .= implode(" ", $p2Pitch) . "\r\n";
$gamestateContent .= implode(" ", $p2Banish) . "\r\n";
$gamestateContent .= implode(" ", $p2ClassState) . "\r\n";
$gamestateContent .= implode(" ", $p2CharacterEffects) . "\r\n";
$gamestateContent .= implode(" ", $p2Soul) . "\r\n";
$gamestateContent .= implode(" ", $p2CardStats) . "\r\n";
$gamestateContent .= implode(" ", $p2TurnStats) . "\r\n";
$gamestateContent .= implode(" ", $p2Allies) . "\r\n";
$gamestateContent .= implode(" ", $p2Permanents) . "\r\n";
$gamestateContent .= implode(" ", $p2Settings) . "\r\n";

$gamestateContent .= implode(" ", $landmarks) . "\r\n";
$gamestateContent .= $winner . "\r\n";
$gamestateContent .= $firstPlayer . "\r\n";
$gamestateContent .= $currentPlayer . "\r\n";
$gamestateContent .= $currentTurn . "\r\n";
$gamestateContent .= implode(" ", $turn) . "\r\n";
$gamestateContent .= $actionPoints . "\r\n";
$gamestateContent .= implode(" ", $combatChain) . "\r\n";
$gamestateContent .= implode(" ", $combatChainState) . "\r\n";
$gamestateContent .= implode(" ", $currentTurnEffects) . "\r\n";
$gamestateContent .= implode(" ", $currentTurnEffectsFromCombat) . "\r\n";
$gamestateContent .= implode(" ", $nextTurnEffects) . "\r\n";
$gamestateContent .= implode(" ", $decisionQueue) . "\r\n";
$gamestateContent .= implode(" ", $dqVars) . "\r\n";
$gamestateContent .= implode(" ", $dqState) . "\r\n";
$gamestateContent .= implode(" ", $layers) . "\r\n";
$gamestateContent .= implode(" ", $layerPriority) . "\r\n";
$gamestateContent .= $mainPlayer . "\r\n";
$gamestateContent .= implode(" ", $lastPlayed) . "\r\n";
$gamestateContent .= count($chainLinks) . "\r\n";
for ($i = 0; $i < count($chainLinks); ++$i) {
  $gamestateContent .= implode(" ", $chainLinks[$i]) . "\r\n";
}
$gamestateContent .= implode(" ", $chainLinkSummary) . "\r\n";
$gamestateContent .= $p1Key . "\r\n";
$gamestateContent .= $p2Key . "\r\n";
$gamestateContent .= $permanentUniqueIDCounter . "\r\n";
$gamestateContent .= $inGameStatus . "\r\n"; //Game status -- 0 = START, 1 = PLAY, 2 = OVER
$gamestateContent .= "\r\n"; //Animations - Deprecated
$gamestateContent .= $currentPlayerActivity . "\r\n"; //Current Player activity status -- 0 = active, 2 = inactive
$gamestateContent .= "\r\n"; //Unused
$gamestateContent .= "\r\n"; //Unused
$gamestateContent .= $p1TotalTime . "\r\n"; //Player 1 total time
$gamestateContent .= $p2TotalTime . "\r\n"; //Player 2 total time
$gamestateContent .= $lastUpdateTime . "\r\n"; //Last update time
$gamestateContent .= $roguelikeGameID . "\r\n"; //Last update time
$gamestateContent .= implode(" ", $events) . "\r\n";//Events
$gamestateContent .= $EffectContext . "\r\n";//Update number the gamestate is for
$gamestateContent .= implode(" ", $p1Inventory) . "\r\n";
$gamestateContent .= implode(" ", $p2Inventory) . "\r\n";
$gamestateContent .= $p1IsAI . "\r\n";
$gamestateContent .= $p2IsAI . "\r\n";

fwrite($handler, $gamestateContent);

flock($handler, LOCK_UN);
fclose($handler);

WriteGamestateCache($gameName, $gamestateContent);