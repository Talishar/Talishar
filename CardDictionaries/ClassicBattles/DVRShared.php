<?php

  function DVRCardType($cardID)
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
      case "DVR012": return "A";
      case "DVR019": return "A";
      case "DVR022": return "A";
      //Attack Reaction
      case "DVR013": return "AR";
      case "DVR014": return "AR";
      case "DVR023": return "AR";
      //Defense Reaction
      case "DVR024": return "DR";
      //Bauble
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
      case "DVR009": return 1;
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

  function DVRAbilityType($cardID, $index=-1)
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

  function DVRPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $combatChain, $currentPlayer, $CS_LastAttack, $combatChainState, $CCS_WeaponIndex, $atkWWpn;
    $rv = "";
    switch($cardID)
    {

    // case "DVR002": /* Work in Progress */
    //   if(GetClassState($currentPlayer, $CS_LastAttack) != "DVR002") return "";
    //
    //   if(){
    //     AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
    //     return "Dawnblade, Resplendent get +1 attack until the end of turn.";
    //   }

    case "DVR004":
      $resources = &GetResources($currentPlayer);
      $resources[0] += 1;
      return "Blossom of Spring added 1 resource.";
    case "DVR008":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      return "Glistening Steelblade gives your next Dawnblade attack Go Again.";
    //   //Grant Go Again
    //   GiveAttackGoAgain();
    //   AddCurrentTurnEffect($cardID, $currentPlayer);
    //   //Grant Counter
    //   Addcurrentturneffect($cardID, $currentPlayer);
    //   return "Glistening Steelblade gives your next weapon attack Go Again";

    case "DVR009":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "En Garde gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";

    case "DVR013";
      GiveAttackGoAgain();
      AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
      return "Run Through gives Go Again and buffs your next sword weapon attack.";

    case "DVR014";
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Thrust gives your next +3 to your sword attack.";

    case "DVR019":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "On a Knife Edge gives your sword attack Go Again.";

    case "DVR022":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Visit the Blacksmith gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";

    case "DVR023":
      GiveAttackGoAgain();
      return "Blade Flash gives your sword attack Go Again.";
    }
  }

  function DVRHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
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
      case "DVR009": return 3;
      case "DVR013": return 2;
      case "DVR014": return 3;
      case "DVR022": return 1;
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
      case "DVR008": case "DVR008-1": return $attackID == "DVR002" || $attackID == "WTR115";
      case "DVR009": return CardType($attackID) == "W";
      case "DVR013": case "DVR014": case "DVR019": case "DVR022": case "DVR023": return CardSubType($attackID) == "Sword";
      default: return false;
    }
  }

  function HalaGoldenhelmAbility($player, $index)
  {
    GiveAttackGoAgain();
    $log = "Hala Goldenhelm gives the sword attack go again";
    $arsenal = &GetArsenal($player);
    ++$arsenal[$index+3];
    if($arsenal[$index+3] >= 2)
    {
      $log .= " and searchs for a Glistening Steelblade card.";
      RemoveArsenal($player, $index);
      BanishCardForPlayer("DVR007", $player, "ARS", "-");
      AddDecisionQueue("FINDINDICES", $player, "DECKCARD,DVR008");
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
    }
    WriteLog($log . ".");
  }

  function DoriQuicksilverProdigyEffect()
  {
    global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
    $char = &GetPlayerCharacter($mainPlayer);
    $char[1] = 1;//Exhause Dori
    $char[$combatChainState[$CCS_WeaponIndex]+1] = 2;
    ++$char[$combatChainState[$CCS_WeaponIndex]+5];
  }

?>
