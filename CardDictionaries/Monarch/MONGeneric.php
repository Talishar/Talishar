<?php


  function MONGenericCardType($cardID)
  {
    switch($cardID)
    {
      case "MON238": case "MON239": case "MON240": case "MON241": case "MON242": case "MON243": case "MON244": return "E";
      case "MON245": case "MON246": case "MON247": return "AA";
      case "MON248": case "MON249": case "MON250": return "AA";
      case "MON251": case "MON252": case "MON253": return "AA";
      case "MON254": case "MON255": case "MON256": return "AA";
      case "MON257": case "MON258": case "MON259": return "DR";
      case "MON260": case "MON261": case "MON262": return "A";
      case "MON263": case "MON264": case "MON265": return "AA";
      case "MON266": case "MON267": case "MON268": return "AA";
      case "MON269": case "MON270": case "MON271": return "AA";
      case "MON272": case "MON273": case "MON274": return "AA";
      case "MON275": case "MON276": case "MON277": return "AA";
      case "MON278": case "MON279": case "MON280": return "AA";
      case "MON281": case "MON282": case "MON283": return "AA";
      case "MON284": case "MON285": case "MON286": return "AA";
      case "MON287": case "MON288": case "MON289": return "AA";
      case "MON290": case "MON291": case "MON292": return "AA";
      case "MON293": case "MON294": case "MON295": return "AA";
      case "MON296": case "MON297": case "MON298": return "A";
      case "MON299": case "MON300": case "MON301": return "A";
      case "MON302": return "A";
      case "MON303": case "MON304": case "MON305": return "I";
      case "MON400": case "MON401": case "MON402": return "E";
      default: return "";
    }
  }

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

  function MONGenericPitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON238": case "MON239": case "MON240": case "MON241": case "MON242": case "MON243": case "MON244": return 0;
      case "MON245": case "MON246": return 1;
      case "MON247": return 3;
      case "MON248": case "MON251": case "MON254": case "MON257": case "MON260": case "MON263": case "MON266": case "MON269": return 1;
      case "MON272": case "MON275": case "MON278": case "MON281": case "MON284": case "MON287": case "MON290": case "MON293": return 1;
      case "MON296": case "MON299": case "MON303": return 1;
      case "MON249": case "MON252": case "MON255": case "MON258": case "MON261": case "MON264": case "MON267": case "MON270": return 2;
      case "MON273": case "MON276": case "MON279": case "MON282": case "MON285": case "MON288": case "MON291": case "MON294": return 2;
      case "MON297": case "MON300": case "MON304": return 2;
      case "MON250": case "MON253": case "MON256": case "MON259": case "MON262": case "MON265": case "MON268": case "MON271": return 3;
      case "MON274": case "MON277": case "MON280": case "MON283": case "MON286": case "MON289": case "MON292": case "MON295": return 3;
      case "MON298": case "MON301": case "MON305": return 3;
      case "MON302": return 2;
      case "MON400": case "MON401": case "MON402": return 0;
      default: return 0;
    }
  }

  function MONGenericBlockValue($cardID)
  {
    switch($cardID)
    {      case "MON238": case "MON239": case "MON240": case "MON241": case "MON242": case "MON243": case "MON244": return 0;
      case "MON245": case "MON247": return 2;
      case "MON248": case "MON249": case "MON250": return 2;
      case "MON251": case "MON252": case "MON253": return 2;
      case "MON254": case "MON255": case "MON256": return 2;
      case "MON257": return 4;
      case "MON258": return 3;
      case "MON259": return 2;
      case "MON260": case "MON261": case "MON262": return 2;
      case "MON263": case "MON264": case "MON265": return 2;
      case "MON266": case "MON267": case "MON268": return 2;
      case "MON269": case "MON270": case "MON271": return 2;
      case "MON272": case "MON273": case "MON274": return 2;
      case "MON275": case "MON276": case "MON277": return 2;
      case "MON278": case "MON279": case "MON280": return 2;
      case "MON281": case "MON282": case "MON283": return 2;
      case "MON284": case "MON285": case "MON286": return 2;
      case "MON287": case "MON288": case "MON289": return 2;
      case "MON290": case "MON291": case "MON292": return 2;
      case "MON293": case "MON294": case "MON295": return 2;
      case "MON296": case "MON297": case "MON298": return 2;
      case "MON299": case "MON300": case "MON301": return 2;
      case "MON302": case "MON303": case "MON304": case "MON305": return 0;
      case "MON400": case "MON401": case "MON402": return 0;
      default: return 3;
    }
  }

  function MONGenericAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON245": return 4;
      case "MON246": return 6;
      case "MON247": return 0;
      case "MON248": case "MON278": case "MON281": case "MON284": return 6;
      case "MON249": case "MON279": case "MON282": case "MON285": case "MON287": case "MON293": return 5;
      case "MON250": case "MON251": case "MON254": case "MON263": case "MON280": case "MON283":
      case "MON286": case "MON288": case "MON294": return 4;
      case "MON252": case "MON255": case "MON264": case "MON266": case "MON269": case "MON272":
      case "MON275": case "MON289": case "MON290": case "MON295": return 3;
      case "MON253": case "MON256": case "MON265": case "MON267": case "MON270": case "MON273": case "MON276": case "MON291": return 2;
      case "MON268": case "MON271": case "MON274": case "MON277": case "MON292": return 1;
      default: return 0;
    }
  }


  function MONGenericPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $actionPoints, $currentPlayer, $myResources, $theirHand, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $combatChain, $myClassState, $CS_PlayIndex;
    $rv = "";
    switch($cardID)
    {
    //MON Generic
    case "MON238": $myResources[0] += 1; return "Blood Drop Brocade gave you 1 resource.";
    case "MON239": AddCurrentTurnEffect($cardID, $currentPlayer); return "Stubby Hammerers gives your attack action cards with less than 3 power +1 attack this turn.";
    case "MON240": $actionPoints += 2; return "Time Skippers gives you two action points.";
    case "MON245":
      if($from == "PLAY")
      {
        $combatChain[5] += 2;
      }
      return "Exude Confidence is a partially manual card. Restrict play of instants and defense reactions manually. Use the Revert Gamestate button under the Stats menu if necessary.";
    case "MON251": case "MON252": case "MON253":
      HandToTopDeck($currentPlayer);
      AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, "-", 1);
      return "";
    case "MON260": case "MON261": case "MON262":
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "2_Attack,Go_again");
      AddDecisionQueue("CAPTAINSCALL", $currentPlayer, $cardID, 1);
      return "";
    case "MON263": case "MON264": case "MON265":
      if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Adrenaline Rush gets +3."; }
      return $rv;
    case "MON266": case "MON267": case "MON268":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MON266-1");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MON266-2", 1);
      AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("REVEALCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      return "Belittle let you choose a card in hand to tutor Minnowism.";
    case "MON272": case "MON273": case "MON274":
      $ret = "Their hand is:";
      for($i=0; $i<count($theirHand); ++$i) { if($i>0) $ret .= ", "; $ret .= $theirHand[$i]; }
      $ret .= (count($theirHand) == 0 ? "Nothing." : ".");
      if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret .= " Frontline Scout gained Go Again."; }
      return $ret;
    case "MON278": case "MON279": case "MON280":
      if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Pound for Pound gets Dominate."; }
      return $rv;
    case "MON281": case "MON282": case "MON283":
      if($from == "PLAY")
      {
        $combatChain[$myClassState[$CS_PlayIndex] + 6] += 3;
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Rally the Rearguard gained +3 defense.";
      }
      return $rv;
    case "MON296": case "MON297": case "MON298":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Minnowism added " . EffectAttackModifier($cardID) . " to your next attack action card with power 3 or less.";
    case "MON299": case "MON300": case "MON301":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Warmonger's Recital gives your next attack action card +" . EffectAttackModifier($cardID) . " and if it hits, put it on the bottom of your deck.";
    case "MON303": case "MON304": case "MON305":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("CHOOSEDISCARDCANCEL", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
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
      if($combatChain[$i+1] == $defPlayer && AttackValue($combatChain[$i]) >= CachedTotalAttack()) $found = true;
    }
    return $found;
  }

?>
