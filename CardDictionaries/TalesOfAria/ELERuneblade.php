<?php

  function ELERunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $otherPlayer, $CS_NumNonAttackCards, $CS_NumAttackCards, $combatChainState, $CCS_WeaponIndex;
    global $CS_NextNAAInstant, $CS_ArcaneDamageDealt;
    $rv = "";
    switch($cardID)
    {
      case "ELE064":
        if(DelimStringContains($additionalCosts, "EARTH") && DelimStringContains($additionalCosts, "LIGHTNING")) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          DealArcane(1, 0, "PLAYCARD", $cardID, false);
        }
        return "";
      case "ELE065":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "ELE066":
        AddCurrentTurnEffect($cardID . "-HIT", $currentPlayer);
        return "";
      case "ELE067": case "ELE068": case "ELE069":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "ELE070": case "ELE071": case "ELE072":
        AddDecisionQueue("CLASSSTATEGREATERORPASS", $currentPlayer, $CS_ArcaneDamageDealt . "-1", 1);
        AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
        return "";
      case "ELE079": case "ELE080": case "ELE081":
        if(GetClassState($currentPlayer, $CS_ArcaneDamageDealt) > 0) MZMoveCard($currentPlayer, "MYDISCARD:type=A", "MYBOTDECK", may:true);
        return "";
      case "ELE085": case "ELE086": case "ELE087":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE222":
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          DealArcane(2, 0, "PLAYCARD", $cardID);
        }
        return $rv;
      case "ELE223":
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          $character = &GetPlayerCharacter($currentPlayer);
          ++$character[$combatChainState[$CCS_WeaponIndex]+3];
        }
        return $rv;
      case "ELE224":
        SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
        return "";
      case "ELE225":
        GiveAttackGoAgain();
        return "";
      case "ELE227": case "ELE228": case "ELE229":
        if(!IsAllyAttacking()) DealArcane(1, 1, "PLAYCARD", $cardID);
        return "";
      case "ELE230": case "ELE231": case "ELE232":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      default: return "";
    }
  }

  function ELERunebladeHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

?>
