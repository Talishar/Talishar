<?php

function OUTAbilityCost($cardID)
{
  switch($cardID)
  {
    case "OUT005": case "OUT006": return 2;
    case "OUT007": case "OUT008": return 2;
    case "OUT009": case "OUT010": return 2;
    case "OUT011": return 0;
    case "OUT049": return 1;
    case "OUT054": return 0;
    case "OUT093": return 1;
    case "OUT094": return 0;
    case "OUT095": return 3;
    case "OUT096": return 3;
    case "OUT098": return 0;
    case "OUT139": return 0;
    case "OUT140": return 0;
    case "OUT141": return 2;
    case "OUT157": return 1;
    case "OUT158": return 1;
    case "OUT174": return 1;
    case "OUT175": case "OUT176": case "OUT177": case "OUT178": return 1;//Seeker's Equips
    case "OUT179": return 0;
    case "OUT180": return 0;
    case "OUT181": return 2;
    case "OUT182": return 0;
    default: return 0;
  }
}

  function OUTAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {
      case "OUT001": case "OUT002": return "AR";
      case "OUT005": case "OUT006": return "AA";
      case "OUT007": case "OUT008": return "AA";
      case "OUT009": case "OUT010": return "AA";
      case "OUT011": return "AR";
      case "OUT049": return "I";
      case "OUT054": return "AR";
      case "OUT093": return "I";
      case "OUT094": return "I";
      case "OUT095": return "I";
      case "OUT096": return "I";
      case "OUT098": return "I";
      case "OUT139": return "AR";
      case "OUT140": return "AR";
      case "OUT141": return "A";
      case "OUT157": return "A";
      case "OUT158": return "A";
      case "OUT174": return "AR";
      case "OUT175": case "OUT176": case "OUT177": case "OUT178": return "I";//Seeker's Equips
      case "OUT179": return "I";
      case "OUT180": return "I";
      case "OUT181": return "AR";
      case "OUT182": return "AR";
      default: return "";
    }
  }

  function OUTAbilityHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      case "OUT141": return true;
      case "OUT157": return true;
      case "OUT158": return true;
      default: return false;
    }
  }

  function OUTEffectAttackModifier($cardID)
  {
    $idArr = explode("-", $cardID);
    $idArr2 = explode(",", $idArr[0]);
    $cardID = $idArr2[0];
    switch($cardID)
    {
      case "OUT021": case "OUT022": case "OUT023": return 3;
      case "OUT033": case "OUT034": case "OUT035": return 1;
      case "OUT042": return 3;
      case "OUT043": return 2;
      case "OUT044": return 1;
      case "OUT052": return 1;
      case "OUT071": case "OUT072": case "OUT073": return 2;
      case "OUT105": return 4;
      case "OUT109": return 4;
      case "OUT110": return 3;
      case "OUT111": return 2;
      case "OUT112": return 3;
      case "OUT113": return 3;
      case "OUT114": return 3;
      case "OUT115": case "OUT116": case "OUT117": return 1;
      case "OUT118": case "OUT119": case "OUT120": return 1;
      case "OUT121": case "OUT122": case "OUT123": return 1;
      case "OUT124": case "OUT125": case "OUT126": return 1;
      case "OUT127": case "OUT128": case "OUT129": return 1;
      case "OUT136": case "OUT137": case "OUT138": return 1;
      case "OUT141": return 1;
      case "OUT143": return 1;
      case "OUT144": return 1;
      case "OUT151": case "OUT152": case "OUT153": return 1;
      case "OUT154": return 3;
      case "OUT155": return 2;
      case "OUT156": return 1;
      case "OUT179_2": return -1;
      case "OUT186": return (-1 * $idArr[1]);
      case "OUT188_2": return 3;
      case "OUT195": case "OUT196": case "OUT197": return 1;
      case "OUT219": return 3;
      case "OUT220": return 2;
      case "OUT221": return 1;
      case "OUT225": return 3;
      case "OUT226": return 2;
      case "OUT227": return 1;
      default: return 0;
    }
  }

  function OUTCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $dashArr = explode("-", $cardID);
    $commaArr = explode(",", $cardID);
    $cardID = $dashArr[0];
    if(strlen($cardID) > 6) $cardID = $commaArr[0];
    switch ($cardID)
    {
      case "OUT021": case "OUT022": case "OUT023": return true;
      case "OUT033": case "OUT034": case "OUT035": return HasStealth($attackID);
      case "OUT042": case "OUT043": case "OUT044": return true;
      case "OUT049": return CardType($attackID) == "AA";
      case "OUT052": return CardType($attackID) == "AA" && count($commaArr) > 1 && IsCurrentAttackName(GamestateUnsanitize($commaArr[1]));
      case "OUT068": case "OUT069": case "OUT070": return true;
      case "OUT071": case "OUT072": case "OUT073": return CardType($attackID) == "AA" && AttackValue($attackID) <= 2;//Base power
      case "OUT102": return true;
      case "OUT105": return CardSubType($attackID) == "Arrow";
      case "OUT109": case "OUT110": case "OUT111": return CardSubType($attackID) == "Arrow";
      case "OUT112": return CardSubType($attackID) == "Arrow";
      case "OUT113": return CardSubType($attackID) == "Arrow";
      case "OUT114": return CardSubType($attackID) == "Arrow";
      case "OUT115": case "OUT116": case "OUT117": return true;
      case "OUT118": case "OUT119": case "OUT120": return true;
      case "OUT121": case "OUT122": case "OUT123": return true;
      case "OUT124": case "OUT125": case "OUT126": return true;
      case "OUT127": case "OUT128": case "OUT129": return true;
      case "OUT136": case "OUT137": case "OUT138": return true;
      case "OUT140": return CardSubType($attackID) == "Dagger";
      case "OUT141": return CardSubType($attackID) == "Dagger";
      case "OUT143": return true;
      case "OUT144": return CardSubType($attackID) == "Dagger";
      case "OUT151": case "OUT152": case "OUT153": return CardSubType($attackID) == "Dagger";
      case "OUT154": case "OUT155": case "OUT156": return true;
      case "OUT158": return CardType($attackID) == "AA";
      case "OUT165": case "OUT166": case "OUT167": return CardType($attackID) == "AA" && (ClassContains($attackID, "ASSASSIN", $mainPlayer) || ClassContains($attackID, "RANGER", $mainPlayer));
      case "OUT179_2": return CardType($attackID) == "AA";
      case "OUT186": return true;
      case "OUT188_1": return CardType($attackID) == "AA";
      case "OUT188_2": return CardType($attackID) == "AA" && AttackPlayedFrom() == "ARS";
      case "OUT195": case "OUT196": case "OUT197": return true;
      case "OUT219": case "OUT220": case "OUT221": return true;
      case "OUT225": case "OUT226": case "OUT227": return CardType($attackID) == "AA" && AttackPlayedFrom() == "ARS";
      default: return false;
    }
  }

  function OUTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer, $CS_PlayIndex, $mainPlayer, $defPlayer, $combatChain, $combatChainState, $CCS_LinkBaseAttack;
    global $CID_Frailty, $CID_BloodRotPox, $CID_Inertia, $CombatChain;
    $rv = "";
    $otherPlayer = $currentPlayer == 1 ? 2 : 1;
    switch ($cardID)
    {
      case "OUT001": case "OUT002":
        $banish = new Banish($currentPlayer);
        $card = $banish->FirstCardWithModifier("UZURI");
        if($card == null) return "Uzuri's knife is re-sheathed";
        if(CardType($card->ID()) != "AA") { $card->ClearModifier(); return "Uzuri was bluffing"; }
        if(CardCost($card->ID()) > 2) { $card->ClearModifier(); return "Uzuri was bluffing"; }
        if(substr($CombatChain->AttackCard()->From(), 0, 5) != "THEIR") $deck = new Deck($currentPlayer);
        else $deck = new Deck($otherPlayer);
        $deck->AddBottom($combatChain[0], "CC");
        AttackReplaced();
        $combatChain[0] = $card->ID();
        $combatChainState[$CCS_LinkBaseAttack] = ModifiedAttackValue($combatChain[0], $currentPlayer, "CC", source:"");
        $card->Remove();
        return "";
      case "OUT011":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT014":
        for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
        {
          if($combatChain[$i+1] == $defPlayer && $combatChain[$i+2] != "PLAY" && CardType($combatChain[$i]) != "C") PlayAura($CID_BloodRotPox, $defPlayer);
        }
        return "";
      case "OUT021": case "OUT022": case "OUT023":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT033": case "OUT034": case "OUT035":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
      case "OUT042": case "OUT043": case "OUT044":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT049":
        AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "OUT049-");
        AddDecisionQueue("ADDCURRENTEFFECTNEXTATTACK", $currentPlayer, "<-");
        AddDecisionQueue("WRITELOG", $currentPlayer, "<b>{0}</b> was chosen");
        return "";
      case "OUT052":
        AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "OUT052");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "OUT052,");
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "<-");
        AddDecisionQueue("WRITELOG", $currentPlayer, "<b>{0}</b> was chosen");
        return "";
      case "OUT055":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:sameName=WTR107");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Surging Strike from your graveyard");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:comboOnly=true");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Combo card from your graveyard");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("OPTX", $currentPlayer, "2", 1);
        return "";
      case "OUT056": case "OUT057": case "OUT058":
        if(ComboActive())
        {
          GiveAttackGoAgain();
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
        if($abilityName == "Load") LoadArrow($currentPlayer);
        else if($abilityName == "Aim") {
          $arsenalFaceDown = ArsenalFaceDownCard($currentPlayer);
          if($arsenalFaceDown != "" && CardSubType($arsenalFaceDown) == "Arrow") {
            SetArsenalFacing("UP", $currentPlayer);
            $arsenal = &GetArsenal($currentPlayer);
            $arsenal[count($arsenal)-ArsenalPieces()+3] += 1;
          }
        }
        return "";
      case "OUT094":
        GainResources($currentPlayer, 1);
        return "";
      case "OUT095":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYDISCARDARROW");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "3-", 1);
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("VALIDATEALLDIFFERENTNAME", $currentPlayer, "DISCARD", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "QUIVEROFABYSSALDEPTH", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "OUT096":
        $deck = new Deck($currentPlayer);
        if($deck->Reveal())
        {
          $topCard = $deck->Top();
          if(CardSubType($topCard) == "Arrow")
          {
            if(!ArsenalFull($currentPlayer))
            {
              AddArsenal($topCard, $currentPlayer, "DECK", "UP");
              $deck->Top(remove:true);
            }
            DestroyCharacter($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex));
            $rv = "The top card was an arrow, so Quiver of Rustling Leaves is destroyed.";
          }
        }
        return $rv;
      case "OUT098":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
        AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        break;
      case "OUT102":
        if(!IsAllyAttacking() && HasIncreasedAttack())
        {
          AddCurrentTurnEffect($cardID, $mainPlayer);
          $rv = "Trap triggered and the attack cannot gain power.";
          TrapTriggered($cardID);
        }
        return "";
      case "OUT103":
        if(!IsAllyAttacking() && DoesAttackHaveGoAgain())
        {
          $hand = &GetHand($mainPlayer);
          $numDraw = count($hand) - 1;
          DiscardHand($mainPlayer);
          for($i=0; $i<$numDraw; ++$i) Draw($mainPlayer);
          WriteLog("Attacker discarded their hand and drew $numDraw cards");
          TrapTriggered($cardID);
        }
        return "";
      case "OUT104":
        if(!IsAllyAttacking() && NumAttackReactionsPlayed() > 0)
        {
          $deck = new Deck($mainPlayer);
          $topDeck = $deck->Top(remove:true);
          AddGraveyard($topDeck, $mainPlayer, "DECK");
          $numName = SearchCount(SearchMultizone($mainPlayer, "MYDISCARD:sameName=" . $topDeck));
          LoseHealth($numName, $mainPlayer);
          $rv = Cardlink($topDeck, $topDeck) . " put into discard. Player $mainPlayer lost $numName life";
          TrapTriggered($cardID);
        }
        return $rv;
      case "OUT105":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT106":
        if(!IsAllyAttacking() && HasIncreasedAttack())
        {
          AddDecisionQueue("FINDINDICES", $mainPlayer, "EQUIP");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("MODDEFCOUNTER", $mainPlayer, "-1", 1);
          $rv = "Trap triggered and puts a -1 counter on an equipment";
          TrapTriggered($cardID);
        }
        return "";
      case "OUT107":
        if(!IsAllyAttacking() && NumAttackReactionsPlayed() > 0)
        {
          $deck = new Deck($mainPlayer);
          for($i=0; $i<2; ++$i)
          {
            $cardRemoved = $deck->Top(remove:true);
            AddGraveyard($cardRemoved, $mainPlayer, "DECK");
          }
          TrapTriggered($cardID);
          $rv = "Milled two cards.";
        }
        return $rv;
      case "OUT108":
        if(DoesAttackHaveGoAgain())
        {
          AddCurrentTurnEffect($cardID, $mainPlayer);
          WriteLog("Trap triggers and hit effects do not fire.");
          if(!IsAllyAttacking()) TrapTriggered($cardID);
        }
        return "";
      case "OUT109": case "OUT110": case "OUT111":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "OUT112":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT113":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT114":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT115": case "OUT116": case "OUT117":
        if(HasAimCounter()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "OUT118": case "OUT119": case "OUT120":
        if(HasAimCounter()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "OUT121": case "OUT122": case "OUT123":
        if(HasAimCounter()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gets +1.";
        }
        return $rv;
      case "OUT124": case "OUT125": case "OUT126":
        if(HasAimCounter()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gets +1.";
        }
        return $rv;
      case "OUT127": case "OUT128": case "OUT129":
        if(HasAimCounter()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gets +1.";
        }
        return $rv;
      case "OUT136": case "OUT137": case "OUT138":
        if(HasAimCounter()) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Gets +1.";
        }
        return $rv;
      case "OUT139":
        ThrowWeapon("Dagger");
        return "";
      case "OUT140":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT141":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT143":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT144":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT148": case "OUT149": case "OUT150":
        if(DelimStringContains($additionalCosts, "PAY1"))
        {
          ThrowWeapon("Dagger");
        }
        return "";
      case "OUT154": case "OUT155": case "OUT156":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT157":
        $cardRemoved = Belch();
        AddPlayerHand($cardRemoved, $currentPlayer, "DECK");
        return "";
      case "OUT158":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT159":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        if(!ArsenalFull($currentPlayer))
        {
          MZMoveCard($currentPlayer, "MYHAND", "MYARS,HAND,DOWN", silent:true);
        }
        if(!ArsenalFull($otherPlayer))
        {
          MZMoveCard($otherPlayer, "MYHAND", "MYARS,HAND,DOWN", silent:true);
        }
        PlayAura("DYN244", $currentPlayer);//Ponder
        PlayAura($CID_BloodRotPox, $otherPlayer);
        return "";
      case "OUT160":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        CodexOfFrailty($currentPlayer);
        PlayAura("DYN244", $currentPlayer);
        PlayAura($CID_Frailty, $otherPlayer);
        return "";
      case "OUT161":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $myDeck = new Deck($currentPlayer);
        if(!ArsenalFull($currentPlayer) && !$myDeck->Empty())
        {
          TopDeckToArsenal($currentPlayer);
          PummelHit($currentPlayer);
        }
        $theirDeck = new Deck($otherPlayer);
        if(!ArsenalFull($otherPlayer) && !$theirDeck->Empty())
        {
          TopDeckToArsenal($otherPlayer);
          PummelHit($otherPlayer);
        }
        PlayAura("DYN244", $currentPlayer);
        PlayAura($CID_Inertia, $otherPlayer);
        return "";
      case "OUT165": case "OUT166": case "OUT167":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Your opponent loses life if your next assassin or ranger attack hits.";
      case "OUT171":
        if(!IsAllyAttacking() && NumAttackReactionsPlayed() > 0)
        {
          PlayAura($CID_BloodRotPox, $mainPlayer);
          $rv = "Trap triggered and created a Bloodrot Pox.";
          TrapTriggered($cardID);
        }
        return $rv;
      case "OUT172":
        if(!IsAllyAttacking() && DoesAttackHaveGoAgain())
        {
          PlayAura($CID_Frailty, $mainPlayer);
          $rv = "Trap triggered and created a Frailty.";
          TrapTriggered($cardID);
        }
        return $rv;
      case "OUT173":
        if(!IsAllyAttacking() && HasIncreasedAttack())
        {
          PlayAura($CID_Inertia, $mainPlayer);
          $rv = "Trap triggered and created an Inertia.";
          TrapTriggered($cardID);
        }
        return $rv;
      case "OUT174":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "OUT175": case "OUT176": case "OUT177": case "OUT178":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayerOpt($currentPlayer, 1);
        return "";
      case "OUT179":
        AddCurrentTurnEffectNextAttack($cardID . "_1", $currentPlayer);
        AddCurrentTurnEffectNextAttack($cardID . "_2", $currentPlayer);
        return "";
      case "OUT180":
        GainResources($currentPlayer, 1);
        break;
      case "OUT182":
        GiveAttackGoAgain();
        break;
      case "OUT186":
        $cardRemoved = Belch();
        if($cardRemoved == "") { AddCurrentTurnEffect("OUT186-7", $currentPlayer); return "You cannot reveal cards so Gore Belching gets -7."; }
        else {
          BanishCardForPlayer($cardRemoved, $currentPlayer, "DECK", "-", "OUT186");
          AddCurrentTurnEffect("OUT186-" . ModifiedAttackValue($cardRemoved, $currentPlayer, "DECK", source:"OUT186"), $currentPlayer);
        }
        return "";
      case "OUT187":
        if(ShouldAutotargetOpponent($currentPlayer)) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "Target_Opponent");
          AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "BURDENSOFTHEPAST", 1);
        } else {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target hero");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Yourself");
          AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "BURDENSOFTHEPAST", 1);
        }
        return "";
      case "OUT188":
        AddCurrentTurnEffect($cardID . "_1", $currentPlayer);
        AddCurrentTurnEffect($cardID . "_2", $currentPlayer);
        return "";
      case "OUT192": case "OUT193": case "OUT194":
        if(SearchAuras($CID_Frailty, $currentPlayer)) PlayAura($CID_Frailty, $defPlayer);
        if(SearchAuras($CID_BloodRotPox, $currentPlayer)) PlayAura($CID_BloodRotPox, $defPlayer);
        if(SearchAuras($CID_Inertia, $currentPlayer)) PlayAura($CID_Inertia, $defPlayer);
        return "";
      case "OUT195": case "OUT196": case "OUT197":
        if(DelimStringContains($additionalCosts, "BANISH1ATTACK"))
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          GiveAttackGoAgain();
        }
        return "";
      case "OUT219": case "OUT220": case "OUT221":
        $hand = &GetHand($currentPlayer);
        if(count($hand) == 0)
        {
          $rv = "Spring Load gains bonus power";
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return $rv;
      case "OUT225": case "OUT226": case "OUT227":
        LookAtTopCard($currentPlayer, $cardID);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT228": case "OUT229": case "OUT230":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "OUT231": case "OUT232": case "OUT233":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("DYN244", $currentPlayer);
        return "Prevents some of the next combat damage you take this turn.";
      default: return "";
    }
  }

  function OUTHitEffect($cardID, $from)
  {
    global $mainPlayer, $defPlayer, $combatChain, $chainLinks, $chainLinkSummary;
    global $CID_BloodRotPox, $CID_Frailty, $CID_Inertia;
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch ($cardID)
    {
      case "OUT005": case "OUT006":
        if (IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
        }
        break;
      case "OUT007": case "OUT008":
        if (IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
        }
        break;
      case "OUT009": case "OUT010":
        if (IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
        }
        break;
      case "OUT012":
        if(IsHeroAttackTarget())
        {
          $deck = new Deck($defPlayer);
          $deckCard = $deck->Top(true);
          if($deckCard != "") BanishCardForPlayer($deckCard, $defPlayer, "THEIRDECK", "NTFromOtherPlayer", $cardID);
        }
        break;
      case "OUT013":
        if(NumAttackReactionsPlayed() > 0 && IsHeroAttackTarget())
        {
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a pitch value", 1);
          AddDecisionQueue("BUTTONINPUT", $mainPlayer, "1,2,3", 1);
          AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
          AddDecisionQueue("WRITELOG", $mainPlayer, "Main player chose: {0}", 1);
          AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
          AddDecisionQueue("REVEALHANDCARDS", $defPlayer, "-", 1);
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND:pitch={0}", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,-," . $mainPlayer, 1);
          AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
        }
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
        if(IsHeroAttackTarget() && HasAttackName("Surging Strike") && HasAttackName("Descendent Gustwave") && HasAttackName("Bonds of Ancestry"))
        {
          if($char[0] == "DUMMY") WriteLog("Combat Dummies have no honor.");
          else if($char[1] == 4) WriteLog("🥷 Those who have been dishonored have nothing left to lose.");
          else $char[1] = 4;
        }
        break;
      case "OUT053":
        KatsuHit("to_discard_and_search_for_a_combo_card");
        break;
      case "OUT059": case "OUT060": case "OUT061":
        if(ComboActive() && IsHeroAttackTarget()) MZMoveCard($defPlayer, "MYHAND", "MYTOPDECK", silent:true);
        break;
      case "OUT062": case "OUT063": case "OUT064":
        if(ComboActive()) {
          if(substr($from, 0, 5) != "THEIR") $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
          else $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "THEIRBOTDECK";
        }
        break;
      case "OUT068": case "OUT069": case "OUT070":
        $hand = &GetHand($mainPlayer);
        $resources = &GetResources($mainPlayer);
        if(Count($hand) > 0 || $resources[0] > 0)
        {
          AddDecisionQueue("YESNO", $mainPlayer, "if you want to pay 1 to give this a name", 0, 1);
          AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, "1", 1);
          AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
          AddDecisionQueue("BUTTONINPUT", $mainPlayer, "Head_Jab,Surging_Strike,Twin_Twisters", 1);
          AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
          AddDecisionQueue("WRITELOG", $mainPlayer, "Attack renamed to <b>{0}</b>", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, $cardID . "-", 1);
          AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "<-", 1);
        }
        break;
      case "OUT071": case "OUT072": case "OUT073":
        AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
        break;
      case "OUT080": case "OUT081": case "OUT082":
        if(ComboActive() && IsHeroAttackTarget())
        {
          WriteLog("Deals 2 damage");
          AddDecisionQueue("DEALDAMAGE", $defPlayer, "2-" . $cardID . "-DAMAGE", 1);
        }
        break;
      case "OUT101":
        if(HasAimCounter() && IsHeroAttackTarget()) {
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a pitch value", 1);
          AddDecisionQueue("BUTTONINPUT", $mainPlayer, "1,2,3", 1);
          AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, "OUT101-", 1);
          AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $defPlayer, "<-", 1);
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, "{0}", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, "Cannot_pitch_", 1);
          AddDecisionQueue("APPENDLASTRESULT", $mainPlayer, "_resource_cards_this_turn_and_next", 1);
          AddDecisionQueue("WRITELOG", $mainPlayer, "<-", 1);
        }
        break;
      case "OUT118": case "OUT119": case "OUT120":
        if(IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer);
        break;
      case "OUT124": case "OUT125": case "OUT126":
        if(IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer);
        break;
      case "OUT136": case "OUT137": case "OUT138":
        if(IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer);
        break;
      case "OUT142":
        $numDaggerHits = 0;
        for($i=0; $i<count($chainLinks); ++$i)
        {
          if(CardSubType($chainLinks[$i][0]) == "Dagger" && $chainLinkSummary[$i*ChainLinkSummaryPieces()] > 0) ++$numDaggerHits;
        }
        if($numDaggerHits > 0) WriteLog("Player " . $defPlayer . " lost " . $numDaggerHits . " life from " . CardLink("OUT142", "OUT142"));
        LoseHealth($numDaggerHits, $defPlayer);
        break;
      case "OUT151": case "OUT152": case "OUT153":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        break;
      case "OUT162": case "OUT163": case "OUT164":
        if(IsHeroAttackTarget())
        {
          AddDecisionQueue("CHOOSECARD", $mainPlayer, $CID_BloodRotPox . "," . $CID_Frailty . "," . $CID_Inertia);
          AddDecisionQueue("PUTPLAY", $defPlayer, "-", 1);
        }
        break;
      case "OUT183":
        if(IsHeroAttackTarget())
        {
          AddCurrentTurnEffect($cardID, $defPlayer);
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "OUT189": case "OUT190": case "OUT191":
        if(IsHeroAttackTarget())
        {
          AddCurrentTurnEffect($cardID, $defPlayer);
          AddNextTurnEffect($cardID, $defPlayer);
          $char = &GetPlayerCharacter($defPlayer);
          $char[1] = 3;
        }
        break;
      case "OUT198": case "OUT199": case "OUT200":
        SetArsenalFacing("UP", $defPlayer);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS:type=DR");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to destroy", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
        break;
      case "OUT201": case "OUT202": case "OUT203":
        if(IsHeroAttackTarget())
        {
          $hand = &GetHand($defPlayer);
          if(count($hand) >= 4) PummelHit($defPlayer);
        }
        break;
      case "OUT204": case "OUT205": case "OUT206":
        PlayAura("DYN244", $mainPlayer);//Ponder
        break;
      default: break;
    }
  }

  function CodexOfFrailty($player)
  {
    $otherPlayer = ($player == 1 ? 2 : 1);
    $conditionPlayerMet = false;
    $conditionOtherPlayerMet = false;
    if(!ArsenalFull($player) && SearchDiscard($player, "AA") != "")
    {
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card from your graveyard.");
      MZMoveCard($player, "MYDISCARD:type=AA", "MYARS,GY,DOWN");
      $conditionPlayerMet = true;
    }
    if(!ArsenalFull($otherPlayer) && SearchDiscard($otherPlayer, "AA") != "")
    {
      AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card from your graveyard.");
      MZMoveCard($otherPlayer, "MYDISCARD:type=AA", "MYARS,GY,DOWN");
      $conditionOtherPlayerMet = true;
    }
    if($conditionPlayerMet) {
      AddDecisionQueue("FINDINDICES", $player, "HAND");
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card from your hand to discard.");
      AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $player, "-", 1);
      AddDecisionQueue("DISCARDCARD", $player, "HAND-".$player, 1);   
    }
    if($conditionOtherPlayerMet) {
      AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
      AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card from your hand to discard.");
      AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $otherPlayer, "-", 1);
      AddDecisionQueue("DISCARDCARD", $otherPlayer, "HAND-".$player, 1);   
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
      case "OUT030": case "OUT031": case "OUT032":
      case "OUT033": case "OUT034": case "OUT035":
      case "OUT036": case "OUT037": case "OUT038":
      case "OUT039": case "OUT040": case "OUT041":
      case "MST106": case "MST107": case "MST108": 
      case "MST109": case "MST110": case "MST111": 
      case "MST112": case "MST113": case "MST114": 
      case "MST115": case "MST116": case "MST117": 
      case "MST118": case "MST119": case "MST120": 
      case "MST121": case "MST122": case "MST123": 
      case "MST124": case "MST125": case "MST126":
        return true;
      default:
        return false;
    }
  }

  function ThrowWeapon($subtype)
  {
    global $currentPlayer, $CCS_HitThisLink;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:subtype=" . $subtype);
    AddDecisionQueue("REMOVEINDICESIFACTIVECHAINLINK", $currentPlayer, "<-", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
    AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
    AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
    AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "1-", 1);
    AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, "-DAMAGE", 1);
    AddDecisionQueue("DEALDAMAGE", $otherPlayer, "<-", 1);
    AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
    AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
    AddDecisionQueue("ONHITEFFECT", $otherPlayer, "<-", 1);
    AddDecisionQueue("PASSPARAMETER", $currentPlayer, "1", 1);
    AddDecisionQueue("SETCOMBATCHAINSTATE", $currentPlayer, $CCS_HitThisLink, 1);
  }

  function DamageDealtBySubtype($subtype)
  {
    global $chainLinks, $chainLinkSummary;
    $damage = 0;
    for($i=0; $i<count($chainLinks); ++$i)
    {
      if(CardSubType($chainLinks[$i][0]) == $subtype) $damage += $chainLinkSummary[$i*ChainLinkSummaryPieces()];
    }
    return $damage;
  }

  function NumAttackReactionsPlayed()
  {
    global $combatChain, $mainPlayer, $layers;
    $numReactions = 0;
    for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces())
    {
      if($combatChain[$i+1] == $mainPlayer && (CardType($combatChain[$i]) == "AR" || GetAbilityType($combatChain[$i]) == "AR")) ++$numReactions;
    }
    for($i=0; $i<count($layers); $i+=LayerPieces())
    {
      if($layers[$i+1] != $mainPlayer) continue;
      if(CardType($layers[$i]) == "AR" || GetAbilityType($layers[$i]) == "AR") ++$numReactions;
    }
    return $numReactions;
  }

  function TrapTriggered($cardID)
  {
    global $mainPlayer, $defPlayer;
    $char = &GetPlayerCharacter($defPlayer);
    $characterID = ShiyanaCharacter($char[0], $defPlayer);
    if($char[1] == 2 && $characterID == "OUT091" || $characterID == "OUT092")
    {
      WriteLog("Riptide deals 1 damage from a trap.");
      DamageTrigger($mainPlayer, 1, "DAMAGE", $characterID);
    }
  }

  function LookAtTopCard($player, $source, $showHand=false)
  {
    $otherPlayer = ($player == 1 ? 2 : 1);
    AddDecisionQueue("PASSPARAMETER", $player, "ELSE");
    AddDecisionQueue("SETDQVAR", $player, "1");
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose target hero");
    AddDecisionQueue("BUTTONINPUT", $player, "Target_Opponent,Target_Yourself");
    AddDecisionQueue("EQUALPASS", $player, "Target_Opponent");
    AddDecisionQueue("WRITELOG", $player, "Shows your top deck", 1);
    AddDecisionQueue("DECKCARDS", $player, "0", 1);
    AddDecisionQueue("SETDQVAR", $player, "1", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, CardName($source) . " shows the top of your deck is <1>", 1);
    AddDecisionQueue("OK", $player, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "{1}");
    AddDecisionQueue("NOTEQUALPASS", $player, "ELSE");
    AddDecisionQueue("WRITELOG", $otherPlayer, "Shows opponent's top deck", 1);
    if($showHand) AddDecisionQueue("SHOWHANDWRITELOG", $otherPlayer, "-", 1);
    AddDecisionQueue("DECKCARDS", $otherPlayer, "0", 1);
    AddDecisionQueue("SETDQVAR", $otherPlayer, "1", 1);
    AddDecisionQueue("SETDQCONTEXT", $otherPlayer, CardName($source) . " shows the top of their deck is <1>", 1);
    AddDecisionQueue("OK", $player, "-", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, "-");
  }

  function SpireSnipingAbility($player)
  {
    AddDecisionQueue("PASSPARAMETER", $player, "0,1");
    AddDecisionQueue("MULTIREMOVEDECK", $player, "-");
    AddDecisionQueue("SETDQCONTEXT", "Choose a card to put back on top", 1);
    AddDecisionQueue("CHOOSETOP", $player, "<-");
  }

  function Belch()
  {
    global $currentPlayer;
    if(!CanRevealCards($currentPlayer)) return "";
    $cardRemoved = "";
    $deck = &GetDeck($currentPlayer);
    $cardsToReveal = "";
    for($i=0; $i<count($deck); ++$i)
    {
      if($cardsToReveal != "") $cardsToReveal .= ",";
      $cardsToReveal .= $deck[$i];
      if(CardType($deck[$i]) == "AA")
      {
        $cardRemoved = $deck[$i];
        unset($deck[$i]);
        $deck = array_values($deck);
        break;
      }
    }
    RevealCards($cardsToReveal);
    AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
    return $cardRemoved;
  }

?>
