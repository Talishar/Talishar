<?php

  function MONGenericPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-")
  {
    global $currentPlayer, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $combatChain, $CS_PlayIndex;
    $rv = "";
    switch($cardID)
    {
      case "MON238": GainResources($currentPlayer, 1); return "";
      case "MON239": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
      case "MON240": GainActionPoints(2, $currentPlayer); return "";
      case "MON245":
        if($from == "PLAY") $combatChain[5] += 2;
        return "";
      case "MON251": case "MON252": case "MON253":
        if($additionalCosts != "-") AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
        return "";
      case "MON260": case "MON261": case "MON262":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("MODAL", $currentPlayer, $cardID, 1);
        return "";
      case "MON263": case "MON264": case "MON265":
        if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Gets +3 power"; }
        return $rv;
      case "MON266": case "MON267": case "MON268":
        if(DelimStringContains($additionalCosts, "BELITTLE") && CanRevealCards($currentPlayer)) {
          MZMoveCard($currentPlayer, "MYDECK:sameName=MON296", "MYHAND", may:true, isReveal:true);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "MON272": case "MON273": case "MON274":
        if(!IsAllyAttackTarget()) {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "-", 1);
        }
        if($from == "ARS") GiveAttackGoAgain();
        return "";
      case "MON278": case "MON279": case "MON280":
        if(IHaveLessHealth()) { AddCurrentTurnEffect($cardID, $currentPlayer); $rv = "Gains Dominate."; }
        return $rv;
      case "MON281": case "MON282": case "MON283":
        if($from == "PLAY") {
          $combatChain[GetClassState($currentPlayer, $CS_PlayIndex) + 6] += 3;
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "MON296": case "MON297": case "MON298":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON299": case "MON300": case "MON301":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON303": MZMoveCard($currentPlayer, "MYDISCARD:type=AA;maxCost=2", "MYTOPDECK"); return;
      case "MON304": MZMoveCard($currentPlayer, "MYDISCARD:type=AA;maxCost=1", "MYTOPDECK"); return;
      case "MON305": MZMoveCard($currentPlayer, "MYDISCARD:type=AA;maxCost=0", "MYTOPDECK"); return;
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
    for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces()) {
      if(!IsAllyAttackTarget() && $combatChain[$i+1] == $defPlayer && AttackValue($combatChain[$i]) >= CachedTotalAttack()) $found = true;
    }
    return $found;
  }

?>
