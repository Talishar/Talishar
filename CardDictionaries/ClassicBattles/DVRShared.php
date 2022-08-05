<?php

  function DVRCardType($cardID)
  {
    switch($cardID)
    {
      case "DVR001": return "C";
      case "DVR002": return "W";
      case "DVR003": case "DVR004": case "DVR005": case "DVR006": return "E";
      case "DVR007": return "M";
      case "DVR008": case "DVR009": return "A";
      case "DVR012": return "A";
      case "DVR019": return "A";
      case "DVR022": return "A";
      case "DVR013": return "AR";
      case "DVR014": return "AR";
      case "DVR023": return "AR";
      case "DVR024": return "DR";
      case "DVR027": return "R";
      default: return "";
    }
  }

  function DVRCardSubType($cardID)
  {
    switch($cardID)
    {
      case "DVR002": return "Sword";
      case "DVR003": return "Head";
      case "DVR004": return "Chest";
      case "DVR005": return "Arms";
      case "DVR006": return "Legs";
      default: return "";
    }
  }

  //Minimum cost of the card
  function DVRCardCost($cardID)
  {
    switch($cardID)
    {

      case "DVR008": case "DVR009": return 1;
      case "DVR013": case "DVR014": return 1;
      case "DVR023": return 1;
      case "DVR024": return 2;
      default: return 0;
    }
  }

  function DVRPitchValue($cardID)
  {
    switch($cardID)
    {
      case "DVR001": case "DVR002": case "DVR007": return 0;
      case "DVR003": case "DVR004": case "DVR005": case "DVR006": return 0;
      case "DVR008": case "DVR013": case "DVR019": return 2;
      case "DVR009": return 1;
      case "DVR012": case "DVR014": return 1;
      default: return 3;
    }
  }

  function DVRBlockValue($cardID)
  {
    switch($cardID)
    {
      case "DVR001": case "DVR002": return -1;
      case "DVR004": return 0;
      case "DVR003": case "DVR005": case "DVR006": return 1;
      case "DVR014": case "DVR019": return 2;
      case "DVR022": case "DVR023": return 2;
      case "DVR024": return 4;
      default: return 3;
    }
  }

  function DVRAttackValue($cardID)
  {
    switch($cardID)
    {
      case "DVR002": return 2;
      default: return 0;
    }
  }

  function DVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "DVR004": return true;
      case "DVR005": return true;
      case "DVR008": return true;
      case "DVR009": return true;
      case "DVR012": return true;
      case "DVR019": return true;
      case "DVR022": return true;
      default: return false;
    }
  }

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
    $rv = "";
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
  $log = "Hala Goldenhelm gives the sword attack go again";
  $arsenal = &GetArsenal($player);
  ++$arsenal[$index + 3];
  if ($arsenal[$index + 3] >= 2) {
    $log .= " and searchs for a Glistening Steelblade card.";
    RemoveArsenal($player, $index);
    BanishCardForPlayer("DVR007", $player, "ARS", "-");
    AddDecisionQueue("FINDINDICES", $player, "DECKCARD,DVR008");
    AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
    AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
    AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
  }
  WriteLog($log . ".");
}

function DoriQuicksilverProdigyEffect()
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
  $char = &GetPlayerCharacter($mainPlayer);
  $char[1] = 1; //Exhause Dori
  $char[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
  ++$char[$combatChainState[$CCS_WeaponIndex] + 5];
}
