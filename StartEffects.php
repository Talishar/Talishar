<?php

  include "ParseGamestate.php";
  include "WriteLog.php";

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

  WriteLog("If you see a bug, use the Report Bug button in the menu. There is also a manual control mode to help correct the game state.");

  if($p2Char[0] == "DUMMY")
  {
    SetCachePiece($gameName, 3, "99999999999999");
  }

  //CR 2.0 4.1.5b Meta-static abilities affecting deck composition
  //Dash
  if($p1Char[0] == "ARC001" || $p1Char[0] == "ARC002")
  {
    $items = SearchDeck(1, "", "Item", 2);
    AddDecisionQueue("CHOOSEDECK", 1, $items);
    AddDecisionQueue("PUTPLAY", 1, "0");
  }
  if($p2Char[0] == "ARC001" || $p2Char[0] == "ARC002")
  {
    $items = SearchDeck(2, "", "Item", 2);
    AddDecisionQueue("CHOOSEDECK", 2, $items);
    AddDecisionQueue("PUTPLAY", 2, "0");
  }
  //Fai
  if($p1Char[0] == "UPR044" || $p1Char[0] == "UPR045")
  {
    $cards = SearchDeckForCard(1, "UPR101");
    if($cards != "")
    {
      AddDecisionQueue("CHOOSEDECK", 1, $cards);
      AddDecisionQueue("ADDDISCARD", 1, "DECK", 1);
    }
  }
  if($p2Char[0] == "UPR044" || $p2Char[0] == "UPR045")
  {
    $cards = SearchDeckForCard(2, "UPR101");
    if($cards != "")
    {
      AddDecisionQueue("CHOOSEDECK", 2, $cards);
      AddDecisionQueue("ADDDISCARD", 2, "DECK", 1);
    }
  }
  AddDecisionQueue("SHUFFLEDECK", 1, "-");//CR 2.0 4.1.7 Shuffle Deck
  AddDecisionQueue("SHUFFLEDECK", 2, "-");//CR 2.0 4.1.7 Shuffle Deck
  AddDecisionQueue("DRAWTOINTELLECT", 1, "-");//CR 2.0 4.1.9 Draw to Intellect
  AddDecisionQueue("DRAWTOINTELLECT", 2, "-");//CR 2.0 4.1.9 Draw to Intellect
  AddDecisionQueue("STARTTURNABILITIES", $mainPlayer, "-");//CR 2.0 4.2 Start Phase

  ProcessDecisionQueue();

  DoGamestateUpdate();
  include "WriteGamestate.php";

?>

Something is wrong with the XAMPP installation :-(
