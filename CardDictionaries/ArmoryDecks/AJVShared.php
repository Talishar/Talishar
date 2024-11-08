<?php

function AJVAbilityType($cardID): string
{
  return match ($cardID) {
    "AJV002" => "AA",
    default => ""
  };
}

function AJVAbilityCost($cardID)
{
  return match ($cardID) {
    "AJV002" => 6,
    default => 0
  };
}

function AJVPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
  switch ($cardID) {
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
    case "AJV018": return true;
    case "AJV002": return true;
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

function FrostbiteExposed($otherPlayer, $player) {
  AddDecisionQueue("LISTEXPOSEDEQUIPSLOTS", $otherPlayer, "-");
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose an exposed equipment zone to " . CardLink("ELE111", "ELE111"), 1);
  AddDecisionQueue("BUTTONINPUT", $player, "<-", 1);
  AddDecisionQueue("SETDQVAR", $player, "0", 1);
  AddDecisionQueue("EQUIPCARD", $otherPlayer, "ELE111-{0}", 1);
}

function CheckHeavy($player) {
  $numWeapons = count(explode(",", SearchCharacter($player, type:"W")));
  $numOffHands = count(explode(",", SearchCharacter($player, subtype:"Off-Hand")));
  return $numWeapons + $numOffHands == 1;
}

?>