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
if($p1StartingHealth != "") $p1H = $p1StartingHealth;

$mainPlayer = $firstPlayer;
$currentPlayer = $firstPlayer;
$otherPlayer = ($currentPlayer == 1 ? 2 : 1);
StatsStartTurn();

$MakeStartTurnBackup = false;
$MakeStartGameBackup = false;

WriteLog("If you see a bug, use the Report Bug button in the menu. There is also a manual control mode to help correct the game state.");

if ($p2Char[0] == "DUMMY") {
  SetCachePiece($gameName, 3, "99999999999999");
}

//CR 2.0 4.1.5b Meta-static abilities affecting deck composition
//Dash
if ($p1Char[0] == "ARC001" || $p1Char[0] == "ARC002") {
  $items = SearchDeck(1, "", "Item", 2, -1, "MECHANOLOGIST");
  AddDecisionQueue("CHOOSEDECK", 1, $items);
  AddDecisionQueue("SETDQVAR", 1, "0");
}
if ($p2Char[0] == "ARC001" || $p2Char[0] == "ARC002") {
  $items = SearchDeck(2, "", "Item", 2, -1, "MECHANOLOGIST");
  AddDecisionQueue("CHOOSEDECK", 2, $items);
  AddDecisionQueue("SETDQVAR", 2, "1");
}
// Syncronous item put in play so there's no advantage in the mirror match-ups
if ($p1Char[0] == "ARC001" || $p1Char[0] == "ARC002") {
  AddDecisionQueue("PUTPLAYITEMDQVAR", 1, "0");
}
if ($p2Char[0] == "ARC001" || $p2Char[0] == "ARC002") {
  AddDecisionQueue("PUTPLAYITEMDQVAR", 2, "1");
}

//Fai
if ($p1Char[0] == "UPR044" || $p1Char[0] == "UPR045") {
  $cards = SearchDeckForCard(1, "UPR101");
  if ($cards != "") {
    AddDecisionQueue("CHOOSEDECK", 1, $cards);
    AddDecisionQueue("ADDDISCARD", 1, "DECK", 1);
  }
}
if ($p2Char[0] == "UPR044" || $p2Char[0] == "UPR045") {
  $cards = SearchDeckForCard(2, "UPR101");
  if ($cards != "") {
    AddDecisionQueue("CHOOSEDECK", 2, $cards);
    AddDecisionQueue("ADDDISCARD", 2, "DECK", 1);
  }
}

//Crown of Dominion
if(SearchCharacterForCard(1, "DYN234")) {
  AddDecisionQueue("STARTOFGAMEPUTPLAY", 1, "DYN243");
}
if(SearchCharacterForCard(2, "DYN234")) {
  AddDecisionQueue("STARTOFGAMEPUTPLAY", 2, "DYN243");
}

//Seasoned Saviour
if (SearchCharacterForCard(1, "DYN026")) {
  $index = FindCharacterIndex(1, "DYN026");
  $p1Char[$index + 4] = -2;
  WriteLog("When you equip " . CardLink("DYN026", "DYN026") . " it gets two -1 counters.");
}
if (SearchCharacterForCard(2, "DYN026")) {
  $index = FindCharacterIndex(2, "DYN026");
  $p2Char[$index + 4] = -2;
  WriteLog("When you equip " . CardLink("DYN026", "DYN026") . " it gets two -1 counters.");
}

//roguelike gamemode powers
if(CardSet($p2Char[0]) == "ROG") {
  $deck = &GetDeck(1);
  //array_unshift($deck, "ROGUE508");
  $powers = SearchDeck(1, "", "Power");
  //WriteLog(strlen($powers));
  //WriteLog($powersArray[0]);
  //WriteLog($powersArray[1]);
  if(strlen($powers) != 0) {
    $powersArray = explode(",", $powers);
    //WriteLog(count($powersArray));
    for($i = count($powersArray)-1; $i >= 0; --$i)
    {
      PutPermanentIntoPlay(1, $deck[$powersArray[$i]]);
      //WriteLog($deck[$powersArray[$i]]);
      array_splice($deck, $powersArray[$i], 1);
      ROGUEPowerStart();
    }
  //WriteLog($deck[$powers[0]]);
  }  

  //Quickshot Apprentice
  if ($p2Char[0] == "ROGUE016") {
    $p2Hand = &GetHand(2);
    array_unshift($p2Hand, "ARC069");
  }

  //Runeblade Scholar
  if ($p2Char[0] == "ROGUE010") {
    PutPermanentIntoPlay(1, "ARC163"); //For the main player, not the scholar
  }
}

AddDecisionQueue("SHUFFLEDECK", 1, "SKIPSEED"); //CR 2.0 4.1.7 Shuffle Deck
AddDecisionQueue("SHUFFLEDECK", 2, "SKIPSEED"); //CR 2.0 4.1.7 Shuffle Deck
AddDecisionQueue("DRAWTOINTELLECT", 1, "-"); //CR 2.0 4.1.9 Draw to Intellect
AddDecisionQueue("DRAWTOINTELLECT", 2, "-"); //CR 2.0 4.1.9 Draw to Intellect
AddDecisionQueue("STARTGAME", $mainPlayer, "-"); //CR ?? Start Game
AddDecisionQueue("STARTTURNABILITIES", $mainPlayer, "-"); //CR 2.0 4.2 Start Phase

ProcessDecisionQueue();

DoGamestateUpdate();
include "WriteGamestate.php";

if ($MakeStartTurnBackup) MakeStartTurnBackup();
if ($MakeStartGameBackup) MakeGamestateBackup("origGamestate.txt");

?>

Something is wrong with the XAMPP installation :-(
