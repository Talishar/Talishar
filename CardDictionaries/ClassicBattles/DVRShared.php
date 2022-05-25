<?php

  function DVRSharedCardType($cardID)
  {
    switch($cardID)
    {
      //Hero
      case "DVR001": return "C";

      //Equipment
      case "DVR002": return "W";
      case "DVR003": case "DVR004": case "DVR005": case "DVR006": return "E";

      //Mentor
      case "DVR007": return "M";

      //Action
      case "DVR008": case "DVR009": return "A";
      case "DVR012": case "DVR014": return "A";
      case "DVR019": return "A";
      case "DVR022": return "A";

      //Attack Reaction
      case "DVR023": return "AR";

      //Defense Reaction
      case "DVR024": return "DR";

      //Bauble
      case "DVR027": return "R";

      default: return "";
    }
  }

  function DVRSharedCardSubType($cardID)
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
  function DVRSharedCardCost($cardID)
  {
    switch($cardID)
    {

      case "DVR008": case "DVR009": return 1;
      case "DVR014": return 1;
      case "DVR023": return 1;
      case "DVR024": return 2;

      default: return 0;
    }
  }

  function DVRSharedPitchValue($cardID)
  {
    switch($cardID)
    {
      case "DVR001": case "DVR002": case "DVR007": return 0;
      case "DVR003": case "DVR004": case "DVR005": case "DVR006": return 0;
      case "DVR008": case "DVR019": return 2;
      case "DVR009": return 1;
      case "DVR012": case "DVR014": return 1;

      default: return 3;
    }
  }

  function DVRSharedBlockValue($cardID)
  {
    switch($cardID)
    {
      case "DVR001": case "DVR002": case "DVR004": return 0;
      case "DVR003": case "DVR005": case "DVR006": return 1;
      case "DVR009": return 1;
      case "DVR014": case "DVR019": return 2;
      case "DVR022": case "DVR023": return 2;
      case "DVR024": return 4;

      default: return 3;
    }
  }

  function DVRSharedAttackValue($cardID)
  {
    switch($cardID)
    {
      case "DVR002": return 2;

      default: return 0;
    }
  }

  function DVRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  function DVRHasGoAgain($cardID)
  {
    global $mainPlayer, $CS_NumAuras;
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

  function DVREffectAttackModifier($cardID)
  {
    global $combatChainState, $CCS_LinkBaseAttack;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $parameter = $params[1];
    switch($cardID)
    {
      case "DVR005": return 1;
      case "DVR009": return 3;
      case "DVR012": return 3;

      case "DVR022": return 1;
      case "DVR014": return 1;

      default: return 0;
    }
  }

  function DVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain, $CS_AtksWWeapon, $mainPlayer;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if(count($params) > 1) $parameter = $params[1];
    switch($cardID)
    {
      case "DVR009": return CardType($attackID) == "W";
      case "DVR005": return CardType($attackID) == "W";
      case "DVR012": return CardType($attackID) == "W";

      case "DVR014": return CardSubType($attackID) == "Sword";
      case "DVR019": return CardSubType($attackID) == "Sword";
      case "DVR022": return CardSubType($attackID) == "Sword";
      case "DVR023": return CardSubType($attackID) == "Sword";
    }
  }

  function DVRSharedPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $CS_Num6PowBan, $combatChain, $currentPlayer;
    $rv = "";
    switch($cardID)
    {

    case "DVR019":
      GiveAttackGoAgain();
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "On a Knife Edge gives your next sword attack go again.";

    case "DVR023":
      GiveAttackGoAgain();
      AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
      return "Blade Flash gives your sword attack go again.";

    }
  }

  function DVRSharedHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

  function HalaGoldenhelmAbility($player, $index)
  {
    $deck = &GetDeck($player);
    if(count($deck) == 0) return;
    $topDeck = array_shift($deck);

  }

?>
