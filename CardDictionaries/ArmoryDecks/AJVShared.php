<?php

function AJVAbilityType($cardID): string
{
  return match ($cardID) {
    "AJV002" => "AA",
    "AJV006" => "A",
    default => ""
  };
}

function AJVAbilityCost($cardID)
{
  return match ($cardID) {
    "AJV002" => 6,
    "AJV006" => 2,
    default => 0
  };
}

function AJVPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
  switch ($cardID) {
    case "AJV006":
      if(SearchCardList($additionalCosts, $currentPlayer, talent:"EARTH") != "") AddCurrentTurnEffect("AJV006-E", $currentPlayer);
      if(SearchCardList($additionalCosts, $currentPlayer, talent:"ICE") != "") AddCurrentTurnEffect("AJV006-I", $currentPlayer);
      return "";
    case "AJV018":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRCHAR:type=E&MYCHAR:type=E");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an equipment to add a -1 defense counter", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MODDEFCOUNTER", $currentPlayer, "-1", 1);
      return "";
    case "AJV020":
      if (DelimStringContains($additionalCosts, "ICE")) {
        Mangle();
      }
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      FrostbiteExposed($otherPlayer, $currentPlayer);
      return "";
    default:
      return "";
  }
}

function AJVCombatEffectActive($cardID, $attackID)
{
switch($cardID) {
    case "AJV002": return true;
    // case "AJV006": return CardNameContains($attackID, "Mangle");//check if I need to do this or the next 2 line
    case "AJV006-E": return CardNameContains($attackID, "Mangle");
    case "AJV006-I": return CardNameContains($attackID, "Mangle");
    case "AJV018": return true;
    default: return false;
}
}

function AJVHitEffect($cardID) {
  global $currentPlayer, $defPlayer;
  switch($cardID)
  {
    case "AJV002":
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
    case "AJV006":
      return true;
    default:
      return false;
  }
}

function AJVEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "AJV006-E" => 2,
    default => 0,
  };
}

function FrostbiteExposed($otherPlayer, $player, $may=false) {
  AddDecisionQueue("LISTEXPOSEDEQUIPSLOTS", $otherPlayer, "-");
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose an exposed equipment zone to " . CardLink("ELE111", "ELE111"), 1);
  AddDecisionQueue("BUTTONINPUT", $player, "<-", 1);
  AddDecisionQueue("SETDQVAR", $player, "0", 1);
  AddDecisionQueue("EQUIPCARD", $otherPlayer, "ELE111-{0}", 1);
}

function CheckHeavy($player) {
  $weapons = SearchCharacter($player, type:"W");
  $numWeapons = $weapons != "" ? count(explode(",", $weapons)) : 0;
  $offHands = SearchCharacter($player, subtype:"Off-Hand");
  $numOffHands = $offHands != "" ? count(explode(",", $offHands)) : 0;
  return $numWeapons + $numOffHands == 1;
}

?>