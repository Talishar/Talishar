<?php

//include "ParseGamestate.php";
//include "WriteLog.php";

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

if ($p2Char[0] == "DUMMY") {
  SetCachePiece($gameName, 3, "99999999999999");
}

//CR 2.0 4.1.5b Meta-static abilities affecting deck composition
//Dash
$p1IsDash = $p1Char[0] == "ARC001" || $p1Char[0] == "ARC002";
$p2IsDash = $p2Char[0] == "ARC001" || $p2Char[0] == "ARC002";
if ($p1IsDash) {
  $items = SearchDeck(1, "", "Item", 2, -1, "MECHANOLOGIST");//Player 1, max cost 2
  AddDecisionQueue("CHOOSEDECK", 1, $items);
  AddDecisionQueue("SETDQVAR", 1, "0");
}
if ($p2IsDash) {
  $items = SearchDeck(2, "", "Item", 2, -1, "MECHANOLOGIST");//Player 2, max cost 2
  AddDecisionQueue("CHOOSEDECK", 2, $items);
  AddDecisionQueue("SETDQVAR", 2, "1");
}
//Actually put the item into play after each has chosen to prevent unfair advantage
if ($p1IsDash) {
  AddDecisionQueue("PASSPARAMETER", 1, "{0}");
  AddDecisionQueue("PUTPLAY", 1, "-");
}
if ($p2IsDash) {
  AddDecisionQueue("PASSPARAMETER", 2, "{1}");
  AddDecisionQueue("PUTPLAY", 2, "-");
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
  AddDecisionQueue("PASSPARAMETER", 1, "DYN243");
  AddDecisionQueue("PUTPLAY", 1, "-");
}
if(SearchCharacterForCard(2, "DYN234")) {
  AddDecisionQueue("PASSPARAMETER", 2, "DYN243");
  AddDecisionQueue("PUTPLAY", 2, "-");
}

//Seasoned Saviour
if (SearchCharacterForCard(1, "DYN026")) {
  $index = FindCharacterIndex(1, "DYN026");
  $p1Char[$index + 4] = -2;
}
if (SearchCharacterForCard(2, "DYN026")) {
  $index = FindCharacterIndex(2, "DYN026");
  $p2Char[$index + 4] = -2;
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
