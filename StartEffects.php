<?php

include "HostFiles/Redirector.php";
include_once "AccountFiles/AccountSessionAPI.php";

// Initialize game state
$layerPriority = [];
array_push($layerPriority, ShouldHoldPriority(1));
array_push($layerPriority, ShouldHoldPriority(2));

// Get player characters and health
$p1Char = &GetPlayerCharacter(1);
$p2Char = &GetPlayerCharacter(2);
$p1H = &GetHealth(1);
$p2H = &GetHealth(2);
$p1H = CharacterHealth($p1Char[0]);
$p2H = CharacterHealth($p2Char[0]);
$format = is_numeric($format) ? FormatName($format) : $format;

if ($p1StartingHealth != "") $p1H = $p1StartingHealth;

// Initialize game log
$fullLog = "../Games/" . $gameName . "/fullGamelog.txt";
if (!file_exists($fullLog)) $fullLog = "../Games/" . $gameName . "/fullGamelog.txt";
if (file_exists($fullLog)) {
  $handler = fopen($fullLog, "w+");
  fwrite($handler, "Player $firstPlayer is the first player and will begin play\r\n");
  fclose($handler);
}

// Set up initial game state
$mainPlayer = $firstPlayer;
$currentPlayer = $firstPlayer;
$otherPlayer = ($currentPlayer == 1 ? 2 : 1);
StatsStartTurn();

$MakeStartTurnBackup = false;
$MakeStartGameBackup = false;

if (IsPlayerAI($currentPlayer)) {
  SetCachePiece($gameName, 3, "99999999999999");
}

//Dummy - Single Player
if ($p2Char[0] == "DUMMY") {
  $cards = ["combustible_courier_red", "zipper_hit_red", "over_loop_blue", "micro_processor_blue", "induction_chamber_red", "zero_to_sixty_red"];
  for ($i = 0; $i < 5; ++$i) {
    AddGraveyard($cards[array_rand($cards)], 2, "DECK");
  }
}

// Handle character-specific start game abilities
handleCharacterStartAbilities();

// Handle inventory start game abilities
InventoryStartGameAbilities(1);
InventoryStartGameAbilities(2);

// Handle Cogwerx equipment
handleCogwerxEquipment($p1Char, $p2Char);

//Aria Sanctuary for Rosseta Limited
/* if($format == "draft"){
  AddDecisionQueue("PASSPARAMETER", 1, "sanctuary_of_aria");
  AddDecisionQueue("PUTPLAY", 1, "-");
  AddDecisionQueue("PASSPARAMETER", 2, "sanctuary_of_aria");
  AddDecisionQueue("PUTPLAY", 2, "-");
} */

// Add final game setup decisions
GameSetup();

ProcessDecisionQueue();
CombatDummyAI(); //Only does anything if applicable
DoGamestateUpdate();
include "WriteGamestate.php";

if ($MakeStartTurnBackup) MakeStartTurnBackup();
if ($MakeStartGameBackup) MakeGamestateBackup("origGamestate.txt");

function handleCharacterStartAbilities()
{
  global $p1Char, $p2Char, $format;

  // Dash abilities
  $p1IsDash = in_array($p1Char[0], ["dash_inventor_extraordinaire", "dash"]);
  $p2IsDash = in_array($p2Char[0], ["dash_inventor_extraordinaire", "dash"]);

  if ($p1IsDash) {
    $items = SearchDeck(1, "", "Item", 2, -1, "MECHANOLOGIST");
    AddDecisionQueue("CHOOSEDECK", 1, $items);
    AddDecisionQueue("SETDQVAR", 1, "0");
  }
  if ($p2IsDash) {
    $items = SearchDeck(2, "", "Item", 2, -1, "MECHANOLOGIST");
    AddDecisionQueue("CHOOSEDECK", 2, $items);
    AddDecisionQueue("SETDQVAR", 2, "1");
  }
  if ($p1IsDash) {
    AddDecisionQueue("PASSPARAMETER", 1, "{0}");
    AddDecisionQueue("PUTPLAY", 1, "-");
  }
  if ($p2IsDash) {
    AddDecisionQueue("PASSPARAMETER", 2, "{1}");
    AddDecisionQueue("PUTPLAY", 2, "-");
  }

  // Fai abilities
  if (in_array($p1Char[0], ["fai_rising_rebellion", "fai"])) {
    $cards = SearchDeckForCard(1, "phoenix_flame_red");
    if ($cards != "") {
      AddDecisionQueue("MAYCHOOSEDECK", 1, $cards);
      AddDecisionQueue("ADDDISCARD", 1, "DECK", 1);
    }
  }
  if (in_array($p2Char[0], ["fai_rising_rebellion", "fai"])) {
    $cards = SearchDeckForCard(2, "phoenix_flame_red");
    if ($cards != "") {
      AddDecisionQueue("MAYCHOOSEDECK", 2, $cards);
      AddDecisionQueue("ADDDISCARD", 2, "DECK", 1);
    }
  }

  // Crown of Dominion
  if (SearchCharacterForCard(1, "crown_of_dominion")) {
    AddDecisionQueue("PASSPARAMETER", 1, "gold");
    AddDecisionQueue("PUTPLAY", 1, "-");
  }
  if (SearchCharacterForCard(2, "crown_of_dominion")) {
    AddDecisionQueue("PASSPARAMETER", 2, "gold");
    AddDecisionQueue("PUTPLAY", 2, "-");
  }

  // Seasoned Saviour
  if (($index = FindCharacterIndex(1, "seasoned_saviour")) > 0) {
    $p1Char[$index + 4] = -2;
  }
  if (($index = FindCharacterIndex(2, "seasoned_saviour")) > 0) {
    $p2Char[$index + 4] = -2;
  }
  
  // Barbed Castaway
  if (($index = FindCharacterIndex(1, "barbed_castaway")) > 0) {
    AddCurrentTurnEffect("barbed_castaway-Load", 1);
    AddCurrentTurnEffect("barbed_castaway-Aim", 1);
  }
  if (($index = FindCharacterIndex(2, "barbed_castaway")) > 0) {
    AddCurrentTurnEffect("barbed_castaway-Load", 2);
    AddCurrentTurnEffect("barbed_castaway-Aim", 2);
  }

  // Victor
  if (SearchCharacterForCard(1, "victor_goldmane_high_and_mighty") || SearchCharacterForCard(1, "victor_goldmane")) {
    AddDecisionQueue("ADDCURRENTEFFECT", 1, $p1Char[0] . "-1", 1);
  }
  if (SearchCharacterForCard(2, "victor_goldmane_high_and_mighty") || SearchCharacterForCard(2, "victor_goldmane")) {
    AddDecisionQueue("ADDCURRENTEFFECT", 2, $p2Char[0] . "-1", 1);
  }
}

function handleCogwerxEquipment(&$p1Char, &$p2Char)
{
  $equipment = ["cogwerx_base_head", "cogwerx_base_chest", "cogwerx_base_arms", "cogwerx_base_legs"];
  foreach ($equipment as $cardID) {
    EquipWithSteamCounter($cardID, $p1Char, $p2Char);
  }
}

function GameSetup()
{
  global $mainPlayer;

  AddDecisionQueue("SHUFFLEDECK", 1, "SKIPSEED");
  AddDecisionQueue("SHUFFLEDECK", 2, "SKIPSEED");
  AddDecisionQueue("DRAWTOINTELLECT", 1, "-");
  AddDecisionQueue("DRAWTOINTELLECT", 2, "-");
  AddDecisionQueue("STARTGAME", $mainPlayer, "-");
  AddDecisionQueue("STARTTURNABILITIES", $mainPlayer, "-");
}

function EquipWithSteamCounter($cardID, &$p1Char, &$p2Char)
{
  if (($index = FindCharacterIndex(1, $cardID)) >= 0) $p1Char[$index + 2] += 1;
  if (($index = FindCharacterIndex(2, $cardID)) >= 0) $p2Char[$index + 2] += 1;
}

function InventoryStartGameAbilities($player)
{
  global $p1Inventory, $p2Inventory;
  $inventory = $player == 1 ? $p1Inventory : $p2Inventory;
  for ($i = 0; $i < count($inventory); $i += InventoryPieces()) {
    switch ($inventory[$i]) {
      case "levia_redeemed":
        PutPermanentIntoPlay($player, "levia_redeemed");
        array_push($inventory, "blasmophet_levia_consumed");
        break;
      case "adaptive_plating":
        addAdaptiveEquipmentDecision($player, $inventory[$i], "ADAPTIVEPLATING");
        break;
      case "adaptive_dissolver":
        addAdaptiveEquipmentDecision($player, $inventory[$i], "ADAPTIVEDISSOLVER");
        break;
    }
  }
}

function addAdaptiveEquipmentDecision($player, $cardID, $modalType)
{
  AddDecisionQueue("LISTEMPTYEQUIPSLOTS", $player, "-");
  AddDecisionQueue("SETDQVAR", $player, "0", 1);
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose where to equip your " . CardLink($cardID, $cardID), 1);
  AddDecisionQueue("BUTTONINPUT", $player, "{0},None", 1);
  AddDecisionQueue("MODAL", $player, $modalType, 1);
  AddDecisionQueue("SHOWMODES", $player, strtolower($modalType), 1);
}
