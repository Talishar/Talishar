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

if($p2Char[0] == "DUMMY") {
  SetCachePiece($gameName, 3, "99999999999999");
}

//roguelike gamemode powers
if(CardSet($p2Char[0]) == "ROG") {
  $deck = &GetDeck(1);
  $powers = SearchDeck(1, "", "Power");
  if(strlen($powers) != 0) {
    $powersArray = explode(",", $powers);
    for($i = count($powersArray)-1; $i >= 0; --$i)
    {
      PutPermanentIntoPlay(1, $deck[$powersArray[$i]]);
      array_splice($deck, $powersArray[$i], 1);
    }
  }
  ROGUEPowerStart();
}

//CR 2.0 4.1.5b Meta-static abilities affecting deck composition
//Dash
$p1IsDash = $p1Char[0] == "ARC001" || $p1Char[0] == "ARC002";
$p2IsDash = $p2Char[0] == "ARC001" || $p2Char[0] == "ARC002";
if($p1IsDash) {
  $items = SearchDeck(1, "", "Item", 2, -1, "MECHANOLOGIST");//Player 1, max cost 2
  AddDecisionQueue("CHOOSEDECK", 1, $items);
  AddDecisionQueue("SETDQVAR", 1, "0");
}
if($p2IsDash) {
  $items = SearchDeck(2, "", "Item", 2, -1, "MECHANOLOGIST");//Player 2, max cost 2
  AddDecisionQueue("CHOOSEDECK", 2, $items);
  AddDecisionQueue("SETDQVAR", 2, "1");
}
//Actually put the item into play after each has chosen to prevent unfair advantage
if($p1IsDash) {
  AddDecisionQueue("PASSPARAMETER", 1, "{0}");
  AddDecisionQueue("PUTPLAY", 1, "-");
}
if($p2IsDash) {
  AddDecisionQueue("PASSPARAMETER", 2, "{1}");
  AddDecisionQueue("PUTPLAY", 2, "-");
}

//Fai
if($p1Char[0] == "UPR044" || $p1Char[0] == "UPR045") {
  $cards = SearchDeckForCard(1, "UPR101");
  if($cards != "") {
    AddDecisionQueue("CHOOSEDECK", 1, $cards);
    AddDecisionQueue("ADDDISCARD", 1, "DECK", 1);
  }
}
if($p2Char[0] == "UPR044" || $p2Char[0] == "UPR045") {
  $cards = SearchDeckForCard(2, "UPR101");
  if($cards != "") {
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
if(($index = FindCharacterIndex(1, "DYN026")) > 0) {
  $p1Char[$index + 4] = -2;
}
if(($index = FindCharacterIndex(2, "DYN026")) > 0) {
  $p2Char[$index + 4] = -2;
}

//Victor
if(SearchCharacterForCard(1, "HVY047") || SearchCharacterForCard(1, "HVY048"))
{
  AddDecisionQueue("ADDCURRENTEFFECT", 1, $p1Char[0]."-1", 1);
}
if(SearchCharacterForCard(2, "HVY047") || SearchCharacterForCard(2, "HVY048"))
{
  AddDecisionQueue("ADDCURRENTEFFECT", 2, $p2Char[0]."-1", 1);
}

InventoryStartGameAbilities(1);
InventoryStartGameAbilities(2);

//Cogwerx equipments
EquipWithSteamCounter("EVO014", $p1Char, $p2Char);
EquipWithSteamCounter("EVO015", $p1Char, $p2Char);
EquipWithSteamCounter("EVO016", $p1Char, $p2Char);
EquipWithSteamCounter("EVO017", $p1Char, $p2Char);

  //Quickshot Apprentice
  if ($p2Char[0] == "ROGUE016") {
    $p2Hand = &GetHand(2);
    array_unshift($p2Hand, "ARC069");
  }
if ($p2Char[0] == "ROGUE025") {
  $options = array("ROGUE801", "ROGUE803", "ROGUE805");
  PutPermanentIntoPlay(0, $options[rand(0, count($options)-1)]);
}

if ($p2Char[0] == "ROGUE008") {
  PutPermanentIntoPlay(0, "ROGUE601");
  PutPermanentIntoPlay(0, "ROGUE603");
  PutPermanentIntoPlay(0, "ROGUE803");
}

AddDecisionQueue("SHUFFLEDECK", 1, "SKIPSEED"); //CR 2.0 4.1.7 Shuffle Deck
AddDecisionQueue("SHUFFLEDECK", 2, "SKIPSEED"); //CR 2.0 4.1.7 Shuffle Deck
AddDecisionQueue("DRAWTOINTELLECT", 1, "-"); //CR 2.0 4.1.9 Draw to Intellect
AddDecisionQueue("DRAWTOINTELLECT", 2, "-"); //CR 2.0 4.1.9 Draw to Intellect
AddDecisionQueue("STARTGAME", $mainPlayer, "-"); //CR ?? Start Game
AddDecisionQueue("STARTTURNABILITIES", $mainPlayer, "-"); //CR 2.0 4.2 Start Phase

ProcessDecisionQueue();
CombatDummyAI(); //Only does anything if applicable
DoGamestateUpdate();
include "WriteGamestate.php";

if($MakeStartTurnBackup) MakeStartTurnBackup();
if($MakeStartGameBackup) MakeGamestateBackup("origGamestate.txt");

function EquipWithSteamCounter($cardID, &$p1Char, &$p2Char) {
  if(($index = FindCharacterIndex(1, $cardID)) > 0) $p1Char[$index+2] += 1;
  if(($index = FindCharacterIndex(2, $cardID)) > 0) $p2Char[$index+2] += 1;
}

function InventoryStartGameAbilities($player) {
  global $p1Inventory, $p2Inventory;
  $inventory = $player == 1 ? $p1Inventory : $p2Inventory;
  for($i=0; $i<count($inventory); $i+=InventoryPieces())
  {
    switch($inventory[$i]) {
      case "DTD164":
        PutPermanentIntoPlay($player, "DTD164");
        array_push($inventory, "DTD564");
        break;
      case "EVO013":
        AddDecisionQueue("LISTEMPTYEQUIPSLOTS", $player, "-");
        AddDecisionQueue("SETDQVAR", $player, "0", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose where to equip your " . CardLink($inventory[$i], $inventory[$i]) . " (one at a time)", 1);
        AddDecisionQueue("BUTTONINPUT", $player, "{0},None", 1);
        AddDecisionQueue("MODAL", $player, "ADAPTIVEPLATING", 1);
        AddDecisionQueue("SHOWMODES", $player, "EVO013", 1);
        break;
      default: break;
    }
  }
}
