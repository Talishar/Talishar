<?php

function OUTAbilityCost($cardID)
{
  switch($cardID)
  {
    case "OUT049": return 1;
    case "OUT096": return 3;
    default: return 0;
  }
}

  function OUTAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {
      case "OUT049": return "I";
      case "OUT096": return "I";
      default: return "";
    }
  }

  function OUTAbilityHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      default: return false;
    }
  }

  function OUTEffectAttackModifier($cardID)
  {
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      case "OUT052": return 1;
      default: return 0;
    }
  }

  function OUTCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      case "OUT049": return CardType($attackID) == "AA";
      case "OUT052": return count($idArr) > 1 && IsCurrentAttackName(GamestateUnsanitize($idArr[1]));
      case "OUT068": case "OUT069": case "OUT070": return true;
      default: return false;
    }
  }

  function OUTHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      case "OUT052": return true;
      case "OUT056": case "OUT057": case "OUT058": return ComboActive($cardID);
      case "OUT068": case "OUT069": case "OUT070": return true;
      case "OUT160": return true;
      default: return false;
    }
  }

  function OUTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer, $CS_PlayIndex, $mainPlayer;
    global $CID_Frailty;
    $rv = "";
    switch ($cardID)
    {
      case "OUT049":
        AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "OUT049-");
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
        return "";
      case "OUT052":
        AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "OUT052-");
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
        return "";
      case "OUT056": case "OUT057": case "OUT058":
        if(ComboActive())
        {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:comboOnly=true");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Combo to banish from your graveyard");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer, 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "MYDECK:sameName=", 1);
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "<-", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $currentPlayer, "DECK,TCC," . $currentPlayer, 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        }
        return "";
      case "OUT096":
        $deck = new Deck($currentPlayer);
        if($deck->Reveal())
        {
          $topCard = $deck->Top(remove:true);
          if(CardSubType($topCard) == "Arrow")
          {
            if(!ArsenalFull($currentPlayer)) AddArsenal($topCard, $currentPlayer, "DECK", "UP");
            DestroyCharacter($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
            $rv = "The top card was an arrow, so Quiver of Rustling Leaves is destroyed.";
          }
        }
        return $rv;
      case "OUT103":
        if(DoesAttackHaveGoAgain())
        {
          $hand = &GetHand($mainPlayer);
          $numDraw = count($hand) - 1;
          DiscardHand($mainPlayer);
          for($i=0; $i<$numDraw; ++$i) Draw($mainPlayer);
          WriteLog("Attacker discarded their hand and drew $numDraw cards.");
        }
        return "";
      case "OUT160":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        if(!ArsenalFull($currentPlayer))
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "GYAA");
          AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEDOWN", $currentPlayer, "GY", 1);
          PummelHit($currentPlayer, true);
        }
        if(!ArsenalFull($otherPlayer))
        {
          AddDecisionQueue("FINDINDICES", $otherPlayer, "GYAA");
          AddDecisionQueue("CHOOSEDISCARD", $otherPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $otherPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEDOWN", $otherPlayer, "GY", 1);
          PummelHit($otherPlayer, true);
        }
        PlayAura("DYN244", $currentPlayer);
        PlayAura($CID_Frailty, $otherPlayer);
        return "";
      case "OUT231": case "OUT232": case "OUT233":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("DYN244", $currentPlayer);
        return "Prevents some of the next combat damage you take this turn.";
      default: return "";
    }
  }

  function OUTHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer, $chainLinkSummary;
    global $CID_BloodRotPox, $CID_Frailty, $CID_Inertia;
    $attackID = $combatChain[0];
    switch ($cardID)
    {
      case "OUT021":
        if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
        break;
      case "OUT022":
        if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
        break;
      case "OUT023":
        if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
        break;
      case "OUT024": case "OUT025": case "OUT026":
        if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
        break;
      case "OUT036": case "OUT037": case "OUT038":
        if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
        break;
      case "OUT039": case "OUT040": case "OUT041":
        if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
        break;
      case "OUT051":
        $char = &GetPlayerCharacter($defPlayer);
        if(HasAttackName("Surging Strike") && HasAttackName("Descendent Gustwave") && HasAttackName("Bonds of Ancestry"))
        {
          if($char[0] == "DUMMY") WriteLog("Combat Dummies have no honor.");
          else if($char[0] == "DUMMYDISHONORED") WriteLog("Those who have been dishonored have nothing left to lose.");
          else $char[0] = "DUMMYDISHONORED";
        }
        break;
      case "OUT068": case "OUT069": case "OUT070":
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to pay 1 to give this a name", 0, 1);
        AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "1", 1);
        AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
        AddDecisionQueue("BUTTONINPUT", $mainPlayer, "Head_Jab,Surging_Strike,Twin_Twisters");
        AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, $cardID . "-");
        AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "<-");
        break;
      default: break;
    }
  }

  function HasStealth($cardID)
  {
    switch($cardID)
    {
      case "OUT024": case "OUT025": case "OUT026":
      case "OUT036": case "OUT037": case "OUT038":
      case "OUT039": case "OUT040": case "OUT041":
        return true;
      default:
        return false;
    }
  }

?>
