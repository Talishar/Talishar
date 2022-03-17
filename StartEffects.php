<?php

  include "ParseGamestate.php";

  array_push($layerPriority, ShouldHoldPriority(1));
  array_push($layerPriority, ShouldHoldPriority(2));

  $p1Char = &GetPlayerCharacter(1);
  $p2Char = &GetPlayerCharacter(2);
  $p1H = &GetHealth(1);
  $p2H = &GetHealth(2);
  $p1H = CharacterHealth($p1Char[0]);
  $p2H = CharacterHealth($p2Char[0]);
  StartReplay();

  $mainPlayer = $firstPlayer;
  $currentPlayer = $firstPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  StatsStartTurn();

  if($p1Char[0] == "ARC001" || $p1Char[0] == "ARC002")
  {
      $items = SearchMyDeck("", "Item", 2);
      AddDecisionQueue("CHOOSEDECK", 1, $items);
      AddDecisionQueue("PUTPLAY", 1, "-");
  }
  if($p2Char[0] == "ARC001" || $p2Char[0] == "ARC002")
  {
      $items = SearchTheirDeck("", "Item", 2);
      AddDecisionQueue("CHOOSEDECK", 2, $items);
      AddDecisionQueue("PUTPLAY", 2, "-");
  }

  AddDecisionQueue("STARTTURNABILITIES", $mainPlayer, "-");

  ProcessDecisionQueue();

  DoGamestateUpdate();
  include "WriteGamestate.php";

  WriteCache($gameName, strval(round(microtime(true) * 1000)));

  header("Location: " . $redirectPath . "/NextTurn3.php?gameName=$gameName&playerID=1");

?>

Something is wrong with the XAMPP installation :-(
