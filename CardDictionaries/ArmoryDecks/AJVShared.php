<?php

function AJVAbilityType($cardID): string
{
  return match ($cardID) {
    "summit_the_unforgiving" => "AA",
    "gauntlets_of_the_boreal_domain" => "A",
    default => ""
  };
}

function AJVAbilityCost($cardID)
{
  return match ($cardID) {
    "summit_the_unforgiving" => 6,
    "gauntlets_of_the_boreal_domain" => 2,
    default => 0
  };
}

function AJVPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
  switch ($cardID) {
    case "gauntlets_of_the_boreal_domain":
      if(SearchCardList($additionalCosts, $currentPlayer, talent:"EARTH") != "") AddCurrentTurnEffect("gauntlets_of_the_boreal_domain-E", $currentPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, talent:"ICE") != "") AddCurrentTurnEffect("gauntlets_of_the_boreal_domain-I", $currentPlayer);
      return "";
    case "crumble_to_eternity_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRCHAR:type=E&MYCHAR:type=E");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an equipment to add a -1 defense counter", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MODDEFCOUNTER", $currentPlayer, "-1", 1);
      return "";
    case "frozen_to_death_blue":
      if (DelimStringContains($additionalCosts, "ICE")) {
        Mangle();
      }
      $otherPlayer = $currentPlayer == 1 ? 2 : 1;
      FrostbiteExposed($otherPlayer, $currentPlayer);
      return "";
    default:
      return "";
  }
}

function AJVCombatEffectActive($cardID, $attackID)
{
switch($cardID) {
    case "gauntlets_of_the_boreal_domain-E": return CardNameContains($attackID, "Mangle");
    case "gauntlets_of_the_boreal_domain-I": return CardNameContains($attackID, "Mangle");
    case "crumble_to_eternity_blue": return true;
    default: return false;
}
}

function AJVHitEffect($cardID) {
  global $currentPlayer, $defPlayer;
  switch($cardID)
  {
    case "summit_the_unforgiving":
      if(IsHeroAttackTarget()) {
        FrostbiteExposed($defPlayer, $currentPlayer);
      }
      break;
    default:
      break;
  }
}

function AJVAbilityHasGoAgain($cardID) {
  switch($cardID)
  {
    case "gauntlets_of_the_boreal_domain":
      return true;
    default:
      return false;
  }
}

function AJVEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "gauntlets_of_the_boreal_domain-E" => 2,
    default => 0,
  };
}

function FrostbiteExposed($otherPlayer, $player, $may=false) {
  $renouceInd = SearchCurrentTurnEffectsForIndex("renounce_grandeur_red", $player);
  $rippleAways = CountCurrentTurnEffects("ripple_away_blue", $player) + CountCurrentTurnEffects("ripple_away_blue", $otherPlayer);
  if ($otherPlayer == "-") {
    if (ListExposedEquipSlots(1) == "PASS") $otherPlayer = 2;
    elseif (ListExposedEquipSlots(2) == "PASS") $otherPlayer = 1;
    elseif (ShouldAutotargetOpponent($player)) $otherPlayer = $player == 2 ? 1 : 2;
  }
  if($rippleAways <= 0 && $renouceInd == -1) {
    if ($otherPlayer == "-") {
      AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET,0"); //Arcane Target isn't used for arcane only. Should be renamed to something else.
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a player to frostbite an exposed zone", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("SETDQVAR", $player, "0", 1);
      AddDecisionQueue("LISTEXPOSEDEQUIPSLOTS", $player, "<-", 1);
    }
    else AddDecisionQueue("LISTEXPOSEDEQUIPSLOTS", $otherPlayer, "-");
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose an exposed equipment zone to " . CardLink("frostbite", "frostbite"), 1);
    AddDecisionQueue("BUTTONINPUT", $player, "<-", 1);
    if ($otherPlayer == "-") {
      AddDecisionQueue("SETDQVAR", $player, "1", 1);
      AddDecisionQueue("EQUIPCARD", $player, "frostbite-{1}-{0}", 1);
    }
    else {
      AddDecisionQueue("SETDQVAR", $player, "0", 1);
      AddDecisionQueue("EQUIPCARD", $otherPlayer, "frostbite-{0}", 1);
    }
  }
}

function CheckHeavy($player) {
  $count = 0;
  $char = GetPlayerCharacter($player);
  for ($i = 0; $i < count($char); $i += CharacterPieces()) {
    if (TypeContains($char[$i], "W", $player) || SubtypeContains($char[$i], "Off-Hand", $player)) ++$count;
  }
  return $count == 1;
  // $weapons = SearchCharacter($player, type:"W");
  // $numWeapons = $weapons != "" ? count(explode(",", $weapons)) : 0;
  // $offHands = SearchCharacter($player, subtype:"Off-Hand");
  // $numOffHands = $offHands != "" ? count(explode(",", $offHands)) : 0;
  // return $numWeapons + $numOffHands == 1;
}