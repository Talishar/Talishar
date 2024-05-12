<?php

function MSTHitEffect($cardID, $from)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt;
  $deck = new Deck($defPlayer);
  $discard = new Discard($defPlayer);
  switch ($cardID){
    case "MST003":
      if($from != "OUT139") AddCurrentTurnEffect($cardID, $mainPlayer);
      else AddCurrentTurnEffectNextAttack($cardID, $mainPlayer);
      break;
    case "MST103":
      $count = count(GetDeck($defPlayer));
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to banish from their hand", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("BONDSOFAGONY", $mainPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $defPlayer, "DECKTOPXINDICES," . $count);
      AddDecisionQueue("DECKCARDS", $defPlayer, "<-", 1);
      AddDecisionQueue("REELINLOOK", $defPlayer, "-", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, CardLink($cardID, $cardID) . " shows the your opponents deck are:", 1);
      AddDecisionQueue("MULTISHOWCARDSTHEIRDECK", $mainPlayer, "<-", 1);
      AddDecisionQueue("SHUFFLE", $defPlayer, "-", 1);
      break;
    case "MST104":
      if(IsHeroAttackTarget())
      {
        LookAtHand($defPlayer);
        $pitchValue = PitchValue($deck->Top());
        $deck->BanishTop("Source-".$cardID, banishedBy:$cardID);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND:pitch=" . $pitchValue);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,Source-" . $cardID .",". $cardID, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "MST106": case "MST107": case "MST108": 
      if(IsHeroAttackTarget())
      {
        $deck->BanishTop(banishedBy:$cardID);
      }
      break;
    case "MST109": case "MST110": case "MST111": 
      if(IsHeroAttackTarget())
      {
        $deck->BanishTop("Source-".$cardID, banishedBy:$cardID);
        if($discard->NumCards() > 0) MZMoveCard($mainPlayer, "THEIRDISCARD", "THEIRBANISH,GY,Source-" . $cardID . "," . $cardID);
      }
      break;
    case "MST112": case "MST113": case "MST114": 
      if(IsHeroAttackTarget() && NumAttackReactionsPlayed() > 1)
      {
        $deck->BanishTop(banishedBy:$cardID);
        $deck->BanishTop(banishedBy:$cardID);
      }
      break;
    case "MST115": case "MST116": case "MST117": 
      if(IsHeroAttackTarget())
      {
        $deck = new Deck($defPlayer);
        $deck->BanishTop(banishedBy:$cardID);
        if($discard->NumCards() > 0) MZMoveCard($mainPlayer, "THEIRDISCARD", "THEIRBANISH,GY,GY,Source-" . $cardID . "," . $cardID, false, true);
      }
      break;
    case "MST118": case "MST119": case "MST120": 
    case "MST121": case "MST122": case "MST123": 
    case "MST124": case "MST125": case "MST126":
      if(IsHeroAttackTarget())
      {
        $deck->BanishTop(banishedBy:$cardID);
      }
      break;
    case "MST173": case "MST174": case "MST175":
      BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $cardID);
      break;
    case "MST191":
      $hand = GetHand($mainPlayer);
      if(count($hand) > 0) {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "HAND");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from your hand to discard.");
        AddDecisionQueue("CHOOSEHAND", $mainPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $mainPlayer, "HAND-".$mainPlayer, 1);   
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from your hand to discard.", 1);
        AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $defPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $defPlayer, "HAND-".$defPlayer, 1);   
      }
      break;
    case "MST192":
      LookAtHand($defPlayer);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND:maxDef=-1");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to discard", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND," . $mainPlayer, 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
      break;
    case "MST233":
      $trapsArr = explode(",",SearchDiscard($mainPlayer, subtype:"Trap"));
      if(count($trapsArr) >= 3) {
        AddDecisionQueue("FINDINDICES", $mainPlayer, "MULTITRAPSBANISH");
        AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, "3-", 1);
        AddDecisionQueue("APPENDLASTRESULT", $mainPlayer, "-3", 1);
        AddDecisionQueue("MULTICHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "MURKYWATER", 1);
      }
      break;
    default:
      break;
  }
}
function AKOHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt;
  switch ($cardID) {
    case "AKO013":
      if(IsHeroAttackTarget()) {
        SetArsenalFacing("UP", $defPlayer);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS:type=AA;maxAttack=" . $combatChainState[$CCS_DamageDealt]-1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to BANISH", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "-", 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    default:
      break;
  }
}
  function TCCHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "TCC088":
        if(ComboActive()) DamageTrigger($defPlayer, damage:1, type:"DAMAGE", source:$cardID);
        break;
      case "TCC016":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        break;
      case "TCC050":
        $charIndex = FindCharacterIndex($mainPlayer, $cardID);
        DestroyCharacter($mainPlayer, $charIndex);
        break;
      case "TCC083":
        AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
        break;
      default: break;
    }
  }

  function EVOHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "EVO006":
        if(IsHeroAttackTarget()) {
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYITEMS:hasCrank=true");
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card with Crank to get a steam counter", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZADDCOUNTER", $mainPlayer, "-", 1);
        }
        break;
      case "EVO054":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) >= 1) {
          global $combatChain, $CombatChain;
          $defendingCardsArr = explode(",", GetChainLinkCards($defPlayer, exclCardTypes: "C"));
          rsort($defendingCardsArr);
          foreach ($defendingCardsArr as $defendingCard) {
            if (CardType($combatChain[$defendingCard]) == "E") {
              WriteLog(CardLink("EVO054", "EVO054") . " destroyed " . CardLink($combatChain[$defendingCard], $combatChain[$defendingCard]) . ".");
              $charID = FindCharacterIndex($defPlayer, $combatChain[$defendingCard]);
              DestroyCharacter($defPlayer, $charID);
              $CombatChain->Remove($defendingCard);
            }
            else {
              WriteLog(CardLink("EVO054", "EVO054") . " destroyed " . CardLink($combatChain[$defendingCard], $combatChain[$defendingCard]) . ".");
              AddGraveyard($combatChain[$defendingCard], $defPlayer, "CC");
              $CombatChain->Remove($defendingCard);
            }
          }
        }
        break;
      case "EVO055":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) >= 1) PummelHit();
        break;
      case "EVO056":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) >= 1) DestroyArsenal($defPlayer, effectController:$mainPlayer);
        break;
      case "EVO138":
        if(IsHeroAttackTarget())
        {
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYBANISH:maxCost=1;subtype=Item&THEIRBANISH:maxCost=1;subtype=Item");
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to put into play");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
          AddDecisionQueue("PUTPLAY", $mainPlayer, "0", 1);
        }
        break;
      case "EVO150": case "EVO151": case "EVO152":
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:hasSteamCounter=true&THEIRCHAR:hasSteamCounter=true");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an equipment, item, or weapon. Remove all steam counters from it.");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVECOUNTER", $mainPlayer, "-", 1);
        break;
      case "EVO186": case "EVO187": case "EVO188":
      case "EVO189": case "EVO190": case "EVO191":
        PlayerOpt($mainPlayer, 1);
        break;
      case "EVO198": case "EVO199": case "EVO200":
      case "EVO201": case "EVO202": case "EVO203":
        MZMoveCard($mainPlayer, "MYHAND:subtype=Item;maxCost=1", "", may:true);
        AddDecisionQueue("PUTPLAY", $mainPlayer, "0", 1);
        break;
      case "EVO216": case "EVO217": case "EVO218":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        break;
      case "EVO236":
        if(IsHeroAttackTarget()) {
          $deck = new Deck($defPlayer);
          if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
            $deck->BanishTop(banishedBy:$cardID);
            AddDecisionQueue("SEARCHCOMBATCHAIN", $mainPlayer, "-");
            AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card to banish");
            AddDecisionQueue("CHOOSECARDID", $mainPlayer, "<-", 1);
            AddDecisionQueue("ALREADYDEAD", $mainPlayer, "-", 1);
          }
          break;
        case "EVO241":
        if(!IsAllyAttackTarget()) {
          PlayAura("DTD232", $defPlayer);
          PlayAura("WTR225", $defPlayer);
        }
        break;
      default: break;
    }
  }

function HVYHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $currentTurnEffects;;
  switch ($cardID) {
    case "HVY012":
      for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
        if ($currentTurnEffects[$i] == $cardID) {
          RemoveCurrentTurnEffect($i);
          break;
        }
      }
      break;
    case "HVY050":
      if(IsHeroAttackTarget()) {
        Clash($cardID, $mainPlayer);
      }
      break;
    case "HVY071": case "HVY072": case "HVY073":
      if(IsHeroAttackTarget() && HasIncreasedAttack()) {
        MZChooseAndDestroy($mainPlayer, "THEIRARS");
      }
      break;
    case "HVY074": case "HVY075": case "HVY076":
      if(IsHeroAttackTarget() && HasIncreasedAttack()) PummelHit();
      break;
    case "HVY208":
      if(IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:type=T;cardID=DYN243");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL", 1);
        AddDecisionQueue("ELSE", $mainPlayer, "-");
        AddDecisionQueue("DEAL1DAMAGE", $defPlayer, $cardID, 1);
      }
      break;
    case "HVY213": case "HVY214": case "HVY215":
      if(SearchCurrentTurnEffects($cardID, $mainPlayer, true)) {
        PlayAura("HVY240", $mainPlayer); //Agility
        PlayAura("HVY241", $mainPlayer); //Might
        PlayAura("HVY242", $mainPlayer); //Vigor
      }
      break;
    case "HVY225": case "HVY226": case "HVY227":
      PutItemIntoPlayForPlayer("DYN243", $mainPlayer, effectController:$mainPlayer);//Gold
      return "";
    case "HVY249":
      if (HasAimCounter() && IsHeroAttackTarget()) {
        $defPlayerHand = &GetHand($defPlayer);
        $defPlayerDiscardNum = count($defPlayerHand) - 1;
        for ($i = 0; $i < $defPlayerDiscardNum; ++$i) {
          PummelHit();
        }
        break;
      }
    default:
      break;
  }
}

?>
