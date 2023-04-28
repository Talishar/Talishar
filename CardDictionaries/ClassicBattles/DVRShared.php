<?php

  function DVRAbilityType($cardID)
  {
    switch($cardID)
    {
      case "DVR002": return "AA";
      case "DVR004": return "A";
      default: return "";
    }
  }

  function DVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "DVR002": return 1;
      case "DVR004": return 0;
      default: return "";
    }
  }

  function DVRPlayAbility($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
    case "DVR004":
      $resources = &GetResources($currentPlayer);
      $resources[0] += 1;
      return "Gain 1 resource.";
    case "DVR008":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      return "Gives your next Dawnblade attack go again.";
    case "DVR009":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";
    case "DVR013":
      GiveAttackGoAgain();
      AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
      return "Gives go again and buffs your next sword weapon attack.";
    case "DVR014":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next +3 to your sword attack.";
    case "DVR019":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your sword attack go again.";
    case "DVR022":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";
    case "DVR023":
      GiveAttackGoAgain();
      return "Gives your sword attack go again.";
    }
  }

function DVREffectAttackModifier($cardID)
{
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch ($cardID) {
    case "DVR009":
      return 3;
    case "DVR013":
      return 2;
    case "DVR014":
      return 3;
    case "DVR022":
      return 1;
    default:
      return 0;
  }
}

function DVRCombatEffectActive($cardID, $attackID)
{
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch ($cardID) {
    case "DVR008":
    case "DVR008-1":
      return $attackID == "DVR002" || $attackID == "WTR115";
    case "DVR009":
      return CardType($attackID) == "W";
    case "DVR013":
    case "DVR014":
    case "DVR019":
    case "DVR022":
    case "DVR023":
      return CardSubType($attackID) == "Sword";
    default:
      return false;
  }
}

function HalaGoldenhelmAbility($player, $index)
{
  GiveAttackGoAgain();
  $rv = CardLink("DVR007", "DVR007") . " gave the attack go again";
  $arsenal = &GetArsenal($player);
  ++$arsenal[$index + 3];
  if ($arsenal[$index + 3] >= 2) {
    $rv .= " and searches for Glistening Steelblade";
    MentorTrigger($player, $index, specificCard:"DVR008");
  }
  WriteLog($rv);
}

function DoriQuicksilverProdigyEffect()
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
  $char = &GetPlayerCharacter($mainPlayer);
  $char[1] = 1; //Exhause Dori
  $char[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
  ++$char[$combatChainState[$CCS_WeaponIndex] + 5];
}
