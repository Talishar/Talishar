<?php

  function DVRAbilityType($cardID) {
    switch($cardID) {
      case "DVR002": return "AA";
      case "DVR004": return "A";
      default: return "";
    }
  }

  function DVRAbilityCost($cardID) {
    switch($cardID) {
      case "DVR002": return 1;
      case "DVR004": return 0;
      default: return "";
    }
  }

  function DVRPlayAbility($cardID) {
    global $currentPlayer;
    switch($cardID)
    {
      case "DVR004":
        GainResources($currentPlayer, 1);
        return "";
      case "DVR008":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        return "";
      case "DVR009":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "DVR013":
        GiveAttackGoAgain();
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
      case "DVR014":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "DVR019":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "DVR022":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "DVR023":
        GiveAttackGoAgain();
        return "";
    }
  }

function DVREffectAttackModifier($cardID)
{
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch($cardID) {
    case "DVR009": return 3;
    case "DVR013": return 2;
    case "DVR014": return 3;
    case "DVR022": return 1;
    default: return 0;
  }
}

function DVRCombatEffectActive($cardID, $attackID)
{
  global $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch($cardID) {
    case "DVR008": case "DVR008-1": return CardNameContains($attackID, "Dawnblade", $mainPlayer, true); 
    case "DVR009": return TypeContains($attackID, "W", $mainPlayer);
    case "DVR013": case "DVR014": case "DVR019": case "DVR022": case "DVR023": return CardSubType($attackID) == "Sword";
    default: return false;
  }
}

function HalaGoldenhelmAbility($player, $index)
{
  GiveAttackGoAgain();
  $rv = CardLink("DVR007", "DVR007") . " gave the attack go again";
  $arsenal = &GetArsenal($player);
  ++$arsenal[$index+3];
  if($arsenal[$index+3] >= 2) {
    $rv .= " and searches for Glistening Steelblade";
    MentorTrigger($player, $index, specificCard:"DVR008");
  }
  WriteLog($rv);
}

function DoriQuicksilverProdigyEffect()
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
  $char = &GetPlayerCharacter($mainPlayer);
  $char[1] = 1;
  $char[$combatChainState[$CCS_WeaponIndex]+1] = 2;
  ++$char[$combatChainState[$CCS_WeaponIndex]+5];
}
