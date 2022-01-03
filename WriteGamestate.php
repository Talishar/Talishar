<?php

  UpdateGameState($playerID);

  $filename = "./Games/" . $gameName . "/gamestate.txt";
  $handler = fopen($filename, "w");

  fwrite($handler, implode(" ", $playerHealths) . "\r\n");

  //Player 1
  fwrite($handler, implode(" ", $p1Hand) . "\r\n");
  fwrite($handler, implode(" ", $p1Deck) . "\r\n");
  fwrite($handler, implode(" ", $p1CharEquip) . "\r\n");
  fwrite($handler, implode(" ", $p1Resources) . "\r\n");
  fwrite($handler, implode(" ", $p1Arsenal) . "\r\n");
  fwrite($handler, implode(" ", $p1Items) . "\r\n");
  fwrite($handler, implode(" ", $p1Auras) . "\r\n");
  fwrite($handler, implode(" ", $p1Discard) . "\r\n");
  fwrite($handler, implode(" ", $p1Pitch) . "\r\n");
  fwrite($handler, implode(" ", $p1Banish) . "\r\n");
  fwrite($handler, implode(" ", $p1ClassState) . "\r\n");
  fwrite($handler, implode(" ", $p1CharacterEffects) . "\r\n");
  fwrite($handler, implode(" ", $p1Soul) . "\r\n");
  fwrite($handler, implode(" ", $p1CardStats) . "\r\n");
  fwrite($handler, implode(" ", $p1TurnStats) . "\r\n");
  fwrite($handler, implode(" ", $p1Allies) . "\r\n");
  fwrite($handler, implode(" ", $p1Settings) . "\r\n");

  //Player 2
  fwrite($handler, implode(" ", $p2Hand) . "\r\n");
  fwrite($handler, implode(" ", $p2Deck) . "\r\n");
  fwrite($handler, implode(" ", $p2CharEquip) . "\r\n");
  fwrite($handler, implode(" ", $p2Resources) . "\r\n");
  fwrite($handler, implode(" ", $p2Arsenal) . "\r\n");
  fwrite($handler, implode(" ", $p2Items) . "\r\n");
  fwrite($handler, implode(" ", $p2Auras) . "\r\n");
  fwrite($handler, implode(" ", $p2Discard) . "\r\n");
  fwrite($handler, implode(" ", $p2Pitch) . "\r\n");
  fwrite($handler, implode(" ", $p2Banish) . "\r\n");
  fwrite($handler, implode(" ", $p2ClassState) . "\r\n");
  fwrite($handler, implode(" ", $p2CharacterEffects) . "\r\n");
  fwrite($handler, implode(" ", $p2Soul) . "\r\n");
  fwrite($handler, implode(" ", $p2CardStats) . "\r\n");
  fwrite($handler, implode(" ", $p2TurnStats) . "\r\n");
  fwrite($handler, implode(" ", $p2Allies) . "\r\n");
  fwrite($handler, implode(" ", $p2Settings) . "\r\n");

  fwrite($handler, implode(" ", $landmarks) . "\r\n");
  fwrite($handler, $winner . "\r\n");
  fwrite($handler, $firstPlayer . "\r\n");
  fwrite($handler, $currentPlayer . "\r\n");
  fwrite($handler, $currentTurn . "\r\n");
  fwrite($handler, implode(" ", $turn) . "\r\n");
  fwrite($handler, $actionPoints . "\r\n");
  fwrite($handler, implode(" ", $combatChain) . "\r\n");
  fwrite($handler, implode(" ", $combatChainState) . "\r\n");
  fwrite($handler, implode(" ", $currentTurnEffects) . "\r\n");
  fwrite($handler, implode(" ", $currentTurnEffectsFromCombat) . "\r\n");
  fwrite($handler, implode(" ", $nextTurnEffects) . "\r\n");
  fwrite($handler, implode(" ", $decisionQueue) . "\r\n");
  fwrite($handler, implode(" ", $dqVars) . "\r\n");
  fwrite($handler, implode(" ", $layers) . "\r\n");
  fwrite($handler, implode(" ", $layerPriority) . "\r\n");
  fwrite($handler, $mainPlayer . "\r\n");
  fwrite($handler, $lastPlayed . "\r\n");
  fclose($handler);

?>

