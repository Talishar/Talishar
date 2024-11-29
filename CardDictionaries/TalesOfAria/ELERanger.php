<?php

  function ELERangerPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "ELE031": case "ELE032":
        if(DelimStringContains($additionalCosts, "LIGHTNING")) { $rv .= "The next attack gains go again."; AddCurrentTurnEffect("ELE031-1", $currentPlayer); }
        if(DelimStringContains($additionalCosts, "ICE")) { if($rv != "") $rv .= " "; $rv .= "The opponent gets a Frostbite."; PlayAura("ELE111", $otherPlayer, effectController: $currentPlayer); }
        return $rv;
      case "ELE033":
        LoadArrow($currentPlayer);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode", 1);
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "1_Attack,Dominate", 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
        AddDecisionQueue("MODAL", $currentPlayer, "SHIVER", 1);
        return "";
      case "ELE034":
        LoadArrow($currentPlayer);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode", 1);
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "1_Attack,Go_again", 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
        AddDecisionQueue("MODAL", $currentPlayer, "VOLTAIRE", 1);
        return "";
      case "ELE035":
        AddCurrentTurnEffect($cardID . "-1", $otherPlayer);
        return "";
      case "ELE037":
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        if(DelimStringContains($additionalCosts, "ICE") && DelimStringContains($additionalCosts, "LIGHTNING")) {
          AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
        }
        return "";
      case "ELE214":
        $arsenal = &GetArsenal($currentPlayer);
        for($i=0; $i < count($arsenal); $i+=ArsenalPieces()) {
          AddPlayerHand($arsenal[$i], $currentPlayer, "ARS");
        }
        $arsenal = [];
        MZMoveCard($currentPlayer, "MYHAND", "MYARS,HAND,DOWN", silent:true);
        return "";
      case "ELE215":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE219": case "ELE220": case "ELE221":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        Reload();
        return "";
      default: return "";
    }
  }

  function ELERangerHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_AttackFused, $mainPlayer;
    switch($cardID)
    {
      case "ELE036":
        if(IsHeroAttackTarget() && $combatChainState[$CCS_AttackFused]) DamageTrigger($defPlayer, NumEquipment($defPlayer), "ATTACKHIT", $cardID);
        break;
      case "ELE216": case "ELE217": case "ELE218":
        if(HasIncreasedAttack()) Reload($mainPlayer);
        break;
      default: break;
    }
  }

  function Fuse($cardID, $player, $elements)
  {
    if(!CanRevealCards($player)) { WriteLog("Cannot fuse because you cannot reveal cards"); return; }
    $elementArray = explode(",", $elements);
    $elementText = "";
    $isAndOrFuse = IsAndOrFuse($cardID);
    for($i=0; $i<count($elementArray); ++$i)
    {
      $element = $elementArray[$i];
      $subsequent = ($i > 0 && !$isAndOrFuse) ? 1 : 0;
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND:talent=" . $element, $subsequent);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose which ". ucfirst(strtolower($element))." card to reveal for Fusion", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
      AddDecisionQueue("REVEALCARDS", $player, "-", 1);
      if($isAndOrFuse) {
        AddDecisionQueue("AFTERFUSE", $player, $cardID . "-" . $element, 1);
        if($i > 0) $elementText .= " and/or ";
      }
      else if($i > 0) $elementText .= " and ";
      $elementText .= $element;
    }
    if(!$isAndOrFuse) {
      $elements = implode(",", $elementArray);
      AddDecisionQueue("AFTERFUSE", $player, $cardID . "-" . $elements, 1);
    }
  }

  function IsAndOrFuse($cardID)
  {
    switch($cardID)
    {
      case "ELE091": case "ELE092": case "ELE093": return true;
      default: return false;
    }
  }

  function FuseAbility($cardID, $player, $element)
  {
    global $CS_NextNAAInstant, $CS_PlayCCIndex, $CombatChain;
    $otherPlayer = ($player == 2 ? 1 : 2);
    $set = CardSet($cardID);
    switch($cardID)
    {
      case "ELE004": AddCurrentTurnEffect($cardID, $otherPlayer); break;
      case "ELE007": case "ELE008": case "ELE009":
        if (!IsAllyAttacking()) {
          PayOrDiscard($otherPlayer, 2, true);
        }
        break;
      case "ELE010": case "ELE011": case "ELE012":
        $index = GetClassState($player, $CS_PlayCCIndex);
        $CombatChain->Card($index)->ModifyDefense(2);
        break;
      case "ELE016": case "ELE017": case "ELE018": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE019": case "ELE020": case "ELE021": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE022": case "ELE023": case "ELE024": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE025": case "ELE026": case "ELE027": PlayAura("ELE111", $otherPlayer, effectController: $player); break;
      case "ELE028": case "ELE029": case "ELE030": PlayAura("WTR075", $player); break;
      case "ELE035": AddCurrentTurnEffect($cardID . "-2", $player); break;
      case "ELE038": case "ELE039": case "ELE040": AddCurrentTurnEffect($cardID, $otherPlayer); break;
      case "ELE041": case "ELE042": case "ELE043":
        SearchCharacterAddUses($player, 1, "W", "Bow");
        SearchCharacterAddEffect($player, "INSTANT", "W", "Bow");
        break;
      case "ELE044": case "ELE045": case "ELE046": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE047": case "ELE048": case "ELE049": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE050": case "ELE051": case "ELE052": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE053": case "ELE054": case "ELE055": GiveAttackGoAgain(); break;
      case "ELE056": case "ELE057": case "ELE058": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE059": case "ELE060": case "ELE061": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE065": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE066": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE070": case "ELE071": case "ELE072": DealArcane(1, 0, "PLAYCARD", $cardID); break;
      case "ELE073": case "ELE074": case "ELE075": DealArcane(1, 0, "PLAYCARD", $cardID); break;
      case "ELE076": case "ELE077": case "ELE078": SetClassState($player, $CS_NextNAAInstant, 1); break;
      case "ELE079": case "ELE080": case "ELE081":
        PrependDecisionQueue("WRITELOG", $player, "Card chosen: <0>", 1);
        PrependDecisionQueue("SETDQVAR", $player, "0", 1);
        PrependDecisionQueue("MZREMOVE", $player, "-", 1);
        PrependDecisionQueue("MZADDZONE", $player, "MYBOTDECK,GY,DOWN", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        PrependDecisionQueue("MULTIZONEINDICES", $player, "MYDISCARD:type=AA");
        break;
      case "ELE082": case "ELE083": case "ELE084": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE085": case "ELE086": case "ELE087": AddCurrentTurnEffect($cardID . "-FUSE", $player); break;
      case "ELE088": DealArcane(3, 0, "PLAYCARD", $cardID); break;
      case "ELE089": DealArcane(2, 0, "PLAYCARD", $cardID); break;
      case "ELE090": DealArcane(1, 0, "PLAYCARD", $cardID); break;
      case "ELE091":
        if(DelimStringContains($element, "LIGHTNING")) AddCurrentTurnEffect($cardID . "-GA", $player);
        if(DelimStringContains($element, "EARTH")) AddCurrentTurnEffect($cardID . "-BUFF", $player);
        break;
      case "ELE092":
        if(DelimStringContains($element, "LIGHTNING")) AddCurrentTurnEffect($cardID . "-BUFF", $player);
        if(DelimStringContains($element, "ICE")) AddCurrentTurnEffect($cardID . "-DOM", $player);
        break;
      case "ELE093":
        if(DelimStringContains($element, "ICE")) ExposedToTheElementsIce($player);
        if(DelimStringContains($element, "EARTH")) ExposedToTheElementsEarth($player);
        break;
      case "ELE094": case "ELE095": case "ELE096":
        $index = GetClassState($player, $CS_PlayCCIndex);
        $CombatChain->Card($index)->ModifyPower(2);
        break;
      case "ELE097": case "ELE098": case "ELE099": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE100": case "ELE101": case "ELE102": GiveAttackGoAgain(); break;
      default: break;
    }
  }

  function PayOrDiscard($player, $amount, $fromDQ=true, $passable=false)
  {
    $targetHand = &GetHand($player);
    if (count($targetHand) > 0) {
      if ($fromDQ) {
        PrependDecisionQueue("DISCARDCARD", $player, "HAND", 1);
        PrependDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
        PrependDecisionQueue("CHOOSEHAND", $player, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard", 1);
        PrependDecisionQueue("FINDINDICES", $player, "HAND", 1);
        PrependDecisionQueue("ELSE", $player, "-");
        PrependDecisionQueue("PAYRESOURCES", $player, "-", 1);
        PrependDecisionQueue("PASSPARAMETER", $player, $amount, 1);
        PrependDecisionQueue("NOPASS", $player, "-", ($passable ? 1 : 0), 1);
        PrependDecisionQueue("YESNO", $player, "if_you_want_to_pay_" . $amount . "_to_avoid_discarding", ($passable ? 1 : 0), 1);
        PrependDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to pay $amount to avoid discarding");
      } else {
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to pay $amount to avoid discarding");
        AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_" . $amount . "_to_avoid_discarding", ($passable ? 1 : 0), 1);
        AddDecisionQueue("NOPASS", $player, "-", ($passable ? 1 : 0), 1);
        AddDecisionQueue("PASSPARAMETER", $player, $amount, 1);
        AddDecisionQueue("PAYRESOURCES", $player, "-", 1);
        AddDecisionQueue("ELSE", $player, "-");
        AddDecisionQueue("FINDINDICES", $player, "HAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard", 1);
        AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
        AddDecisionQueue("DISCARDCARD", $player, "HAND", 1);
        PummelHit($player, $passable=true, fromDQ:false);
      }
    }
  }

  function HasFusion($cardID)
  {
    switch($cardID)
    {
      case "ELE004": return "ICE";
      case "ELE005": return "EARTH,ICE";
      case "ELE006": return "EARTH";
      case "ELE007": case "ELE008": case "ELE009": return "ICE";
      case "ELE010": case "ELE011": case "ELE012": return "EARTH";
      case "ELE013": case "ELE014": case "ELE015": return "EARTH";
      case "ELE016": case "ELE017": case "ELE018": return "ICE";
      case "ELE019": case "ELE020": case "ELE021": return "EARTH";
      case "ELE022": case "ELE023": case "ELE024": return "ICE";
      case "ELE025": case "ELE026": case "ELE027": return "ICE";
      case "ELE028": case "ELE029": case "ELE030": return "EARTH";
      case "ELE035": return "ICE";
      case "ELE036": return "LIGHTNING";
      case "ELE037": return "ICE,LIGHTNING";
      case "ELE038": case "ELE039": case "ELE040": return "ICE";
      case "ELE041": case "ELE042": case "ELE043": return "LIGHTNING";
      case "ELE044": case "ELE045": case "ELE046": return "ICE";
      case "ELE047": case "ELE048": case "ELE049": return "LIGHTNING";
      case "ELE050": case "ELE051": case "ELE052": return "ICE";
      case "ELE053": case "ELE054": case "ELE055": return "LIGHTNING";
      case "ELE056": case "ELE057": case "ELE058": return "ICE";
      case "ELE059": case "ELE060": case "ELE061": return "LIGHTNING";
      case "ELE064": return "EARTH,LIGHTNING";
      case "ELE065": return "LIGHTNING";
      case "ELE066": return "EARTH";
      case "ELE067": case "ELE068": case "ELE069": return "EARTH";
      case "ELE070": case "ELE071": case "ELE072": return "LIGHTNING";
      case "ELE073": case "ELE074": case "ELE075": return "LIGHTNING";
      case "ELE076": case "ELE077": case "ELE078": return "LIGHTNING";
      case "ELE079": case "ELE080": case "ELE081": return "EARTH";
      case "ELE082": case "ELE083": case "ELE084": return "EARTH";
      case "ELE085": case "ELE086": case "ELE087": return "EARTH";
      case "ELE088": case "ELE089": case "ELE090": return "LIGHTNING";
      case "ELE091": return "EARTH,LIGHTNING";
      case "ELE092": return "ICE,LIGHTNING";
      case "ELE093": return "EARTH,ICE";
      case "ELE094": case "ELE095": case "ELE096": return "EARTH";
      case "ELE097": case "ELE098": case "ELE099": return "ICE";
      case "ELE100": case "ELE101": case "ELE102": return "LIGHTNING";
      case "UPR104": return "ICE";
      case "UPR105": return "ICE";
      case "UPR106": case "UPR107": case "UPR108": return "ICE";
      case "UPR109": return "ICE";
      case "UPR110": case "UPR111": case "UPR112": return "ICE";
      case "UPR113": case "UPR114": case "UPR115": return "ICE";
      case "UPR116": case "UPR117": case "UPR118": return "ICE";
      case "UPR119": case "UPR120": case "UPR121": return "ICE";
      case "UPR122": case "UPR123": case "UPR124": return "ICE";
      case "AJV020": return "ICE";
      default: return "";
    }
  }

  function CurrentTurnFuseEffects($player, $element)
  {
    global $currentTurnEffects;
    $costModifier = 0;
    for($i=count($currentTurnEffects)-CurrentTurnEffectsPieces(); $i>=0; $i-=CurrentTurnEffectsPieces())
    {
      $remove = 0;
      if($player == $currentTurnEffects[$i+1]) {
        switch($currentTurnEffects[$i]) {
          case "UPR141": case "UPR142": case "UPR143":
            if($element == "ICE") {
              $otherPlayer = ($player == 1 ? 2 : 1);
              AddLayer("TRIGGER", $player, $currentTurnEffects[$i], $otherPlayer);
              $remove = 1;
            }
            break;
          default: break;
        }
        if($remove == 1) RemoveCurrentTurnEffect($i);
      }
    }
    return $costModifier;
  }

  function AuraFuseEffects($player, $element)
  {
    $auras = &GetAuras($player);
    $otherPlayer = $player == 1 ? 2 : 1;
    for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces()) {
      switch($auras[$i]) {
        case "UPR140":
          if($element == "ICE") AddLayer("TRIGGER", $player, $auras[$i], $otherPlayer, uniqueID:$auras[$i+6]);
          break;
        default: break;
      }
    }
  }

?>
