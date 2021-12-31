<?php

  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }

  $playerID=$_GET["playerID"];

  ob_start();
  include "ParseGamestate.php";
  include "GameLogic.php";
  include "Libraries/UILibraries.php";
  include "GameTerms.php";
  ob_end_clean();

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
  $totalAttack = 0; $totalBlock = 0; $activeCombatEffects = ""; $otherCurrentEffects = "";
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
      else
      {
        if($otherCurrentEffects != "") $otherCurrentEffects .= " ";
        $otherCurrentEffects .= $currentTurnEffects[$i] . " " . $currentTurnEffects[$i+1];
      }
    }
  }
  echo($totalAttack . " " . $totalBlock . "<BR>");
  echo($activeCombatEffects . "<BR>");
  echo($otherCurrentEffects . "<BR>");
  if($currentPlayer != $playerID) echo("Waiting for other player to choose " . TypeToPlay($turn[0]));
  else if($currentPlayer == $playerID) echo("Please choose " . TypeToPlay($turn[0]));
  else echo("Waiting for player " . $currentPlayer . " to choose " . TypeToPlay($turn[0]));
  echo(implode(" ", $landmarks) . "<BR>");

  function OutputPlayerData($player)
  {
    global $turn, $currentPlayer, $playerID, $p1Hand, $p2Hand, $p1Deck, $p2Deck, $p1CharEquip, $p2CharEquip, $p1Resources, $p2Resources, $p1Arsenal, $p2Arsenal;
    global $p1Items, $p2Items, $p1Auras, $p2Auras, $p1Discard, $p2Discard, $p1Pitch, $p2Pitch, $p1Banish, $p2Banish, $p1ClassState, $p2ClassState;
    global $p1CharacterEffects, $p2CharacterEffects, $p1Allies, $p2Allies;
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
    $allies = ($player == 1 ? $p1Allies : $p2Allies);
    if($playerID == $player)
    {
      $actionType = $turn[0] == "ARS" ? 4 : 2;
      if(strpos($turn[0], "CHOOSEHAND") !== false && $turn[0] != "MULTICHOOSEHAND") $actionType = 16;
      $handOut = "";
      for($i=0; $i<count($hand); ++$i)
      {
        if($player != $currentPlayer) $playable = 0;
        else $playable = $turn[0] == "ARS" || IsPlayable($hand[$i], $turn[0], "HAND") || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
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
      if($player != $currentPlayer) $playable = 0;
      else $playable = $charEquip[$i+1] == 2 && IsPlayable($charEquip[$i], $turn[0], "CHAR", $i);
      $border = CardBorderColor($charEquip[$i], "CHAR", $playable);
      for($j=0; $j<CharacterPieces(); ++$j)
      {
        $charEquipOut .= $charEquip[$j+$i] . " ";
      }
      $charEquipOut .= $border;
    }
    echo($charEquipOut . "<BR>");
    echo(implode(" ", $resources) . "<BR>");
    $arsenalOut = "";
    for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
    {
      if($arsenalOut != "") $arsenalOut .= " ";
      if($player != $currentPlayer) $playable = 0;
      else $playable = $turn[0] != "P" && IsPlayable($arsenal[$i], $turn[0], "ARS", $i);
      $border = CardBorderColor($arsenal[$i], "ARS", $playable);
      $facing = $arsenal[$i + 1];
      $arsenalOut .= ($playerID == $player || $facing == "UP" ? $arsenal[$i] : "-") . " ";
      $arsenalOut .= $facing . " " . $border;
    }
    echo($arsenalOut . "<BR>");
    $itemsOut = "";
    for($i=0; $i<count($items); $i+=ItemPieces())
    {
      if($itemsOut != "") $itemsOut .= " ";
      if($player != $currentPlayer) $playable = 0;
      else $playable = $items[$i+1] == 2 && IsPlayable($items[$i], $turn[0], "PLAY", $i);
      $border = CardBorderColor($items[$i], "PLAY", $playable);
      for($j=0; $j<ItemPieces(); ++$j)
      {
        $itemsOut .= $items[$j+$i] . " ";
      }
      $itemsOut .= $border;
    }
    echo($itemsOut . "<BR>");
    //echo(implode(" ", $items) . "<BR>");
    echo(implode(" ", $auras) . "<BR>");
    echo(implode(" ", $discard) . "<BR>");
    echo(implode(" ", $pitch) . "<BR>");
    $banishOut = "";
    for($i=0; $i<count($banish); $i+=BanishPieces())
    {
      if($banishOut != "") $banishOut .= " ";
      if($player != $currentPlayer) $playable = 0;
      else $playable = IsPlayable($banish[$i], $turn[0], "BANISH", $i);
      $border = CardBorderColor($banish[$i], "BANISH", $playable);
      for($j=0; $j<BanishPieces(); ++$j)
      {
        $banishOut .= $banish[$j+$i] . " ";
      }
      $banishOut .= $border;
    }
    echo($banishOut . "<BR>");
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
    echo(implode(" ", $allies) . "<BR>");
  }
  
?>