<?php

  function MONGenericCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON238": return "Chest";
      case "MON239": return "Arms";
      case "MON240": return "Legs";
      case "MON241": return "Head";
      case "MON242": return "Chest";
      case "MON243": return "Arms";
      case "MON244": return "Legs";
      case "MON302": return "Item";
      case "MON400": return "Chest";
      case "MON401": return "Arms";
      case "MON402": return "Legs";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONGenericCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON245": return 0;
      case "MON246": return 2;
      case "MON247": return 3;
      case "MON248": case "MON249": case "MON250": return 3;
      case "MON251": case "MON252": case "MON253": return 0;
      case "MON254": case "MON255": case "MON256": return 1;
      case "MON257": case "MON258": case "MON259": return 2;
      case "MON260": case "MON261": case "MON262": return 0;
      case "MON263": case "MON264": case "MON265": return 2;
      case "MON266": case "MON267": case "MON268": return 1;
      case "MON269": case "MON270": case "MON271": return 1;
      case "MON272": case "MON273": case "MON274": return 0;
      case "MON275": case "MON276": case "MON277": return 0;
      case "MON278": case "MON279": case "MON280": return 3;
      case "MON281": case "MON282": case "MON283": return 2;
      case "MON284": case "MON285": case "MON286": return 2;
      case "MON287": case "MON288": case "MON289": return 2;
      case "MON290": case "MON291": case "MON292": return 0;
      case "MON293": case "MON294": case "MON295": return 2;
      case "MON296": case "MON297": case "MON298": return 0;
      case "MON299": case "MON300": case "MON301": return 1;
      case "MON302": return 0;
      case "MON303": case "MON304": case "MON305": return 0;
      case "MON400": case "MON401": case "MON402": return 0;
      default: return 0;
    }
  }

  function MONGenericPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-")
  {
    global $actionPoints, $currentPlayer, $myResources, $theirHand, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $combatChain, $myClassState, $CS_PlayIndex;
    $rv = "";
    switch($cardID)
    {
    //MON Generic
    case "MON238": $myResources[0] += 1; return "Gain 1 resource.";
    case "MON239": AddCurrentTurnEffect($cardID, $currentPlayer); return "Gives your attack action cards with less than 3 power +1 power this turn.";
    case "MON240": $actionPoints += 2; return "Gain 2 action points.";
    case "MON245":
      if($from == "PLAY") {
        $combatChain[5] += 2;
        return " gains 2 power";
      }
      return " restrict play of instants and defense reactions";
    case "MON251": case "MON252": case "MON253":
      if ($additionalCosts != "-") AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, "-", 1);
      return "";
    case "MON260": case "MON261": case "MON262":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("CAPTAINSCALL", $currentPlayer, $cardID, 1);
      return "";
    case "MON263": case "MON264": case "MON265":
      if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Gets +3 power."; }
      return $rv;
    case "MON266": case "MON267": case "MON268":
      if(DelimStringContains($additionalCosts, "BELITTLE") && CanRevealCards($currentPlayer))
      {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON266-2");
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        WriteLog(CardLink($cardID, $cardID) . " let you choose a card in hand to tutor Minnowism.");
      }
      return "";
    case "MON272": case "MON273": case "MON274":
      $ret = "";
      if(!IsAllyAttackTarget()) {
        $ret .= "Their hand is: ";
        for($i=0; $i<count($theirHand); ++$i) { if($i>0) $ret .= ", "; $ret .= CardLink($theirHand[$i], $theirHand[$i]); }
        $ret .= (count($theirHand) == 0 ? "Empty. " : ". ");
      }
      if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret .= CardLink($cardID, $cardID) . " gains go again."; }
      return $ret;
    case "MON278": case "MON279": case "MON280":
      if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Gains Dominate."; }
      return $rv;
    case "MON281": case "MON282": case "MON283":
      if($from == "PLAY")
      {
        $combatChain[$myClassState[$CS_PlayIndex] + 6] += 3;
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Gains +3 defense.";
      }
      return $rv;
    case "MON296": case "MON297": case "MON298":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Adds " . EffectAttackModifier($cardID) . " to your next attack action card with power 3 or less.";
    case "MON299": case "MON300": case "MON301":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next attack action card +" . EffectAttackModifier($cardID) . " and if it hits, put it on the bottom of your deck.";
    case "MON303": case "MON304": case "MON305":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("CHOOSEDISCARDCANCEL", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("SHOWSELECTEDCARD", $currentPlayer, "-", 1);
      return "";
      default: return "";
    }
  }

  function MONGenericHitEffect($cardID)
  {
    global $mainPlayer;
    switch($cardID)
    {
      case "MON246": if(SearchDiscard($mainPlayer, "AA") == "") { AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); } break;
      case "MON269": case "MON270": case "MON271": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
      case "MON275": case "MON276": case "MON277": GiveAttackGoAgain(); break;
      default: break;
    }
  }

  function ExudeConfidenceReactionsPlayable()
  {
    global $combatChain, $defPlayer;
    $found = false;
    for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces())
    {
      if(!IsAllyAttackTarget() && $combatChain[$i+1] == $defPlayer && AttackValue($combatChain[$i]) >= CachedTotalAttack()) $found = true;
    }
    return $found;
  }

?>
