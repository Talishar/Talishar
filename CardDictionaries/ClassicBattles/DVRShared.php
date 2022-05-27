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
      case "DVR013": case "DVR014": return 1;
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
      case "DVR008": case "DVR013": case "DVR019": return 2;
      case "DVR009": return 1;
      case "DVR012": case "DVR014": return 1;
      default: return 3;
    }
  }

  function DVRSharedBlockValue($cardID)
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

  function DVRSharedAttackValue($cardID)
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
      case "DVR004": return "A";
      default: return "";
    }
  }

  function DVRSharedPlayAbility($cardID, $from, $resourcesPaid)
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

    // case "DVR008": /* Work in Progress */
    //   //Grant Go Again
    //   GiveAttackGoAgain();
    //   AddCurrentTurnEffect($cardID, $currentPlayer);
    //   //Grant Counter
    //   Addcurrentturneffect($cardID, $currentPlayer);
    //   return "Glistening Steelblade gives your next weapon attack Go Again";

    case "DVR009":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "En Garde gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";

    case "DVR013";
      GiveAttackGoAgain();
      AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
      return "Run Through gives Go Again and buffs your next sword weapon attack.";

    case "DVR014";
      AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
      return "Thrust gives your next +3 to your sword attack.";

    case "DVR019":
      GiveAttackGoAgain();
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "On a Knife Edge gives your sword attack Go Again.";

    case "DVR022":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Visit the Blacksmith gives your next weapon attack +" . EffectAttackModifier($cardID) . ".";

    case "DVR023":
      GiveAttackGoAgain();
      AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
      return "Blade Flash gives your sword attack Go Again.";

    }
  }

  function DVRSharedHitEffect($cardID)
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
      case "DVR009": return CardType($attackID) == "W";
      case "DVR013": case "DVR014": case "DVR022": case "DVR023": return CardSubType($attackID) == "Sword";
      default: return false;
    }
  }

  function HalaGoldenhelmAbility($player, $index)
  {
    GiveAttackGoAgain();
    $log = "Hala Goldenhelm gives the sword attack go again";
    $arsenal = &GetArsenal($player);
    ++$arsenal[$index+2];
    if($arsenal[$index+2] == 2)
    {
      $log .= " and searchs for a Glistening Steelblade card.";
      RemoveArsenal($player, $index);
      BanishCardForPlayer("DVR007", $player, "ARS", "-");
      AddDecisionQueue("FINDINDICES", $player, "GLISTENINGSTEELBLADE");
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
    }
    WriteLog($log . ".");
  }

?>
