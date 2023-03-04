<?php

function OUTAbilityCost($cardID)
{
  switch($cardID)
  {
    case "OUT049": return 1;
    case "OUT093": return 1;
    case "OUT096": return 3;
    case "OUT158": return 1;
    default: return 0;
  }
}

  function OUTAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {
      case "OUT001": case "OUT002": return "AR";
      case "OUT049": return "I";
      case "OUT093": return "I";
      case "OUT096": return "I";
      case "OUT158": return "A";
      default: return "";
    }
  }

  function OUTAbilityHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      case "OUT158": return true;
      default: return false;
    }
  }

  function OUTEffectAttackModifier($cardID)
  {
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      case "OUT033": case "OUT034": case "OUT035": return 1;
      case "OUT052": return 1;
      case "OUT195": case "OUT196": case "OUT197": return 1;
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
      case "OUT033": case "OUT034": case "OUT035": return HasStealth($attackID);
      case "OUT049": return CardType($attackID) == "AA";
      case "OUT052": return count($idArr) > 1 && IsCurrentAttackName(GamestateUnsanitize($idArr[1]));
      case "OUT068": case "OUT069": case "OUT070": return true;
      case "OUT158": return CardType($attackID) == "AA";
      case "OUT195": case "OUT196": case "OUT197": return true;
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
      case "OUT148": return true;
      case "OUT159": case "OUT160": return true;//Tomes
      default: return false;
    }
  }

  function OUTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer, $CS_PlayIndex, $mainPlayer, $combatChain, $combatChainState, $CCS_LinkBaseAttack;
    global $CID_Frailty, $CID_BloodRotPox, $CID_Inertia;
    $rv = "";
    switch ($cardID)
    {
      case "OUT001": case "OUT002":
        $banish = &GetBanish($currentPlayer);
        $index = -1;
        for($i=0; $i<count($banish); $i+=BanishPieces()) if($banish[$i+1] == "UZURI") $index = $i;
        if($index == -1) return "Uzuri's knife is re-sheathed.";
        if(CardType($banish[$index]) != "AA") { $banish[$index+1] = "-"; return "Uzuri was bluffing."; }
        if(CardCost($banish[$index]) > 2) { $banish[$index+1] = "-"; return "Uzuri was bluffing."; }
        $deck = &GetDeck($currentPlayer);
        array_push($deck, $combatChain[0]);
        AttackReplaced();
        $combatChain[0] = $banish[$index];
        $combatChainState[$CCS_LinkBaseAttack] = AttackValue($combatChain[0]);
        RemoveBanish($currentPlayer, $index);
        return "";
      case "OUT033": case "OUT034": case "OUT035":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
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
      case "OUT093":
        $abilityName = GetResolvedAbilityName($cardID);
        if($abilityName == "Load")
        {
          if(ArsenalFull($currentPlayer)) return "Your arsenal is full, so you cannot put an arrow in your arsenal.";
          AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
          AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        }
        else if($abilityName == "Aim") {
          if (ArsenalHasFaceDownCard($currentPlayer)) {
            SetArsenalFacing("UP", $currentPlayer);
            $arsenal = &GetArsenal($currentPlayer);
            $arsenal[count($arsenal) - ArsenalPieces() + 3] += 1;
          }
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
      case "OUT148":
        if(DelimStringContains($additionalCosts, "PAY1"))
        {
          ThrowWeapon("Dagger");
        }
        return "";
      case "OUT158":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT159":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        if(!ArsenalFull($currentPlayer))
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
          AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEDOWN", $currentPlayer, "GY", 1);
        }
        if(!ArsenalFull($otherPlayer))
        {
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("ADDARSENALFACEDOWN", $otherPlayer, "GY", 1);
        }
        PlayAura("DYN244", $currentPlayer);//Ponder
        PlayAura($CID_BloodRotPox, $otherPlayer);
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
      case "OUT195": case "OUT196": case "OUT197":
        if(DelimStringContains($additionalCosts, "BANISH1ATTACK"))
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          GiveAttackGoAgain();
        }
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
      case "OUT012":
        $deck = new Deck($defPlayer);
        $deckCard = $deck->Top(true);
        BanishCardForPlayer($deckCard, $mainPlayer, "THEIRDECK", "NT", $cardID);
        break;
      case "OUT013":
        if(HasPlayedAttackReaction())
        {
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a pitch value");
          AddDecisionQueue("BUTTONINPUT", $mainPlayer, "1,2,3");
          AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, "THEIRHAND:pitch=");
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "<-");
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,-," . $defPlayer, 1);
          AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
        }
        break;
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
      case "OUT162": case "OUT163": case "OUT164":
        if(IsHeroAttackTarget())
        {
          AddDecisionQueue("CHOOSECARD", $mainPlayer, $CID_BloodRotPox . "," . $CID_Frailty . "," . $CID_Inertia);
          AddDecisionQueue("PUTPLAY", $defPlayer, "-", 1);
        }
        break;
      default: break;
    }
  }

  function HasStealth($cardID)
  {
    switch($cardID)
    {
      case "OUT012":
      case "OUT015": case "OUT016": case "OUT017":
      case "OUT024": case "OUT025": case "OUT026":
      case "OUT027": case "OUT028": case "OUT029":
      case "OUT033": case "OUT034": case "OUT035":
      case "OUT036": case "OUT037": case "OUT038":
      case "OUT039": case "OUT040": case "OUT041":
        return true;
      default:
        return false;
    }
  }

  function ThrowWeapon($subtype)
  {
    global $currentPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:subtype=" . $subtype);
    AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
    AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
    AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
    AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "1-", 1);
    AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, "-DAMAGE", 1);
    AddDecisionQueue("DEALDAMAGE", $otherPlayer, "<-", 1);
    AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
    AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
    AddDecisionQueue("HITEFFECT", $otherPlayer, "<-", 1);
  }

?>
