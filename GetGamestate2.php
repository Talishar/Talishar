<?php

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  include "ParseGamestate.php";
  include "GameLogic.php";
  include "Libraries/UILibraries.php";

  echo(implode(" ", $playerHealths) . "<BR>");

  OutputPlayerData(1);
  OutputPlayerData(2);

  echo($winner . "<BR>");
  echo($currentPlayer . "<BR>");
  echo($currentTurn . "<BR>");
  echo(implode(" ", $turn) . "<BR>");
  echo($actionPoints . "<BR>");
  echo(implode(" ", $combatChain) . "<BR>");
  echo(implode(" ", $combatChainState) . "<BR>");
  echo(implode(" ", $currentTurnEffects) . "<BR>");
  echo(implode(" ", $nextTurnEffects) . "<BR>");
  echo(implode(" ", $decisionQueue) . "<BR>");
  echo($mainPlayer . "<BR>");
  $totalAttack = 0; $totalBlock = 0; $activeCombatEffects = "";
  if(count($combatChain) > 0)
  {
    EvaluateCombatChain($totalAttack, $totalBlock);
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]))
      {
        if($activeCombatEffects != "") $activeCombatEffects .= " ";
        $activeCombatEffects .= $currentTurnEffects[$i] . " " . $currentTurnEffects[$i+1];
      }
    }
  }
  echo($totalAttack . " " . $totalBlock . "<BR>");
  echo($activeCombatEffects . "<BR>");

  function OutputPlayerData($player)
  {
    global $turn, $currentPlayer, $playerID, $p1Hand, $p2Hand, $p1Deck, $p2Deck, $p1CharEquip, $p2CharEquip, $p1Resources, $p2Resources, $p1Arsenal, $p2Arsenal;
    global $p1Items, $p2Items, $p1Auras, $p2Auras, $p1Discard, $p2Discard, $p1Pitch, $p2Pitch, $p1Banish, $p2Banish, $p1ClassState, $p2ClassState;
    global $p1CharacterEffects, $p2CharacterEffects;
    $hand = ($player == 1 ? $p1Hand : $p2Hand);
    $deck = ($player == 1 ? $p1Deck : $p2Deck);
    $charEquip = ($player == 1 ? $p1CharEquip : $p2CharEquip);
    $resources = ($player == 1 ? $p1Resources : $p2Resources);
    $arsenal = ($player == 1 ? $p1Arsenal : $p2Arsenal);
    $items = ($player == 1 ? $p1Items : $p2Items);
    $auras = ($player == 1 ? $p1Auras : $p2Auras);
    $discard = ($player == 1 ? $p1Discard : $p2Discard);
    $pitch = ($player == 1 ? $p1Pitch : $p2Pitch);
    $banish = ($player == 1 ? $p1Banish : $p2Banish);
    $classState = ($player == 1 ? $p1ClassState : $p2ClassState);
    $characterEffects = ($player == 1 ? $p1CharacterEffects : $p2CharacterEffects);
    if($playerID == $player)
    {
      $actionType = $turn[0] == "ARS" ? 4 : 2;
      if(strpos($turn[0], "CHOOSEHAND") !== false && $turn[0] != "MULTICHOOSEHAND") $actionType = 16;
      $handOut = "";
      for($i=0; $i<count($hand); ++$i)
      {
        $playable = $turn[0] == "ARS" || IsPlayable($hand[$i], $turn[0], "HAND") || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
        $border = CardBorderColor($hand[$i], "HAND", $playable);
        if($handOut != "") $handOut .= " ";
        $handOut .= $hand[$i] . " " . $border;
      }
      echo($handOut . "<BR>");
    }
    else echo(count($hand) . "<BR>");
    echo(count($deck) . "<BR>");
    $charEquipOut = "";
    for($i=0; $i<count($charEquip); $i+=CharacterPieces())
    {
      if($charEquipOut != "") $charEquipOut .= " ";
      $playable = $charEquip[$i+1] == 2 && IsPlayable($charEquip[$i], $turn[0], "CHAR", $i);
      $border = CardBorderColor($charEquip[$i], "CHAR", $playable);
      for($j=0; $j<CharacterPieces(); ++$j)
      {
        $charEquipOut .= $charEquip[$j+$i] . " ";
      }
      $charEquipOut .= $border;
    }
    echo($charEquipOut . "<BR>");
    echo(implode(" ", $resources) . "<BR>");
    if($playerID == $player) echo(implode(" ", $arsenal) . "<BR>");
    echo(implode(" ", $items) . "<BR>");
    echo(implode(" ", $auras) . "<BR>");
    echo(implode(" ", $discard) . "<BR>");
    echo(implode(" ", $pitch) . "<BR>");
    echo(implode(" ", $banish) . "<BR>");
    echo(implode(" ", $classState) . "<BR>");
    echo(implode(" ", $characterEffects) . "<BR>");
    if($currentPlayer == $player && $playerID == $player && count($turn) > 0 && ($turn[0] == "CHOOSEDECK" || $turn[0] == "MULTICHOOSEDECK"))
    {
      $dispDeck = "";
      $indices = explode(",", $turn[2]);
      for($i=0; $i<count($indices); ++$i)
      {
        if($dispDeck != "") $dispDeck .= " ";
        $dispDeck .= $deck[$indices[$i]];
      }
      echo($dispDeck . "<BR>");
    }
  }
  
?>