<?php

  function MONGenericPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-")
  {
    global $currentPlayer, $CombatChain, $ChainLinks, $chainLinkSummary, $CS_PlayIndex;
    switch($cardID)
    {
      case "blood_drop_brocade": GainResources(1, $currentPlayer); return "";
      case "stubby_hammerers": AddCurrentTurnEffect($cardID, $currentPlayer); return "";
      case "time_skippers": GainActionPoints(2, $currentPlayer); return "";
      case "exude_confidence_red":
        switch ($from) {
          case "PLAY":
            $CombatChain->AttackCard()->ModifyPower(2);
            break;
          case "COMBATCHAINATTACKS":
            $linkNum = intdiv(GetClassState($currentPlayer, $CS_PlayIndex), ChainLinksPieces());
            $attack = $ChainLinks->GetLink($linkNum)->AttackCard();
            if ($attack->ID() == $cardID && $attack->StillOnChain()) {
              $chainLinkSummary[$linkNum * ChainLinkSummaryPieces() + 1] += 2;
            }
            break;
        }
        return "";
      case "seek_horizon_red": case "seek_horizon_yellow": case "seek_horizon_blue":
        if($additionalCosts != "-") AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
        return "";
      case "captains_call_red": case "captains_call_yellow": case "captains_call_blue":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("MODAL", $currentPlayer, $cardID, 1);
        return "";
      case "belittle_red": case "belittle_yellow": case "belittle_blue":
        if(DelimStringContains($additionalCosts, "BELITTLE") && CanRevealCards($currentPlayer)) {
          MZMoveCard($currentPlayer, "MYDECK:isSameName=minnowism_red", "MYHAND", may:true, isReveal:true);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "frontline_scout_red": case "frontline_scout_yellow": case "frontline_scout_blue":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "-", 1);
        if($from == "ARS") GiveAttackGoAgain();
        return "";
      case "rally_the_rearguard_red": case "rally_the_rearguard_yellow": case "rally_the_rearguard_blue":
        if($from == "PLAY") {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          //Safety in case it loses the index when more cards are played at instant
          if($index == -1) $index = GetCombatChainIndex($cardID, $currentPlayer); 
          CombatChainDefenseModifier($index, 3);
        }
        return "";
      case "minnowism_red": case "minnowism_yellow": case "minnowism_blue":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "warmongers_recital_red": case "warmongers_recital_yellow": case "warmongers_recital_blue":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "memorial_ground_red": case "memorial_ground_yellow": case "memorial_ground_blue": 
        $params = explode("-", $target);
        $index = SearchdiscardForUniqueID($params[1], $currentPlayer);
        if($index != -1) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYDISCARD-".$index, 1);
          AddDecisionQueue("MZADDZONE", $currentPlayer, "MYTOPDECK", 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        }
        else {
          WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect.");
          return "";
        }
        return "";
      default: return "";
    }
  }

  function MONGenericHitEffect($cardID)
  {
    global $mainPlayer;
    switch($cardID)
    {
      case "nourishing_emptiness_red": if(SearchDiscard($mainPlayer, "AA") == "") { AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); } break;
      case "brandish_red": case "brandish_yellow": case "brandish_blue": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
      case "overload_red": case "overload_yellow": case "overload_blue": GiveAttackGoAgain(); break;
      default: break;
    }
  }

  function ExudeConfidenceRestrictsReactions()
  {
    global $combatChain, $chainLinks, $chainLinkSummary, $CombatChain, $defPlayer;
    if (($combatChain[0] ?? "") == "exude_confidence_red" && IsHeroAttackTarget()) {
      $defendedByStrongCard = false;
      $chainPieces = CombatChainPieces();
      $chainCount = count($combatChain);
      $attackPower = CachedTotalPower();
      for ($i = $chainPieces; $i < $chainCount; $i += $chainPieces) {
        if ($combatChain[$i + 1] != $defPlayer) continue;
        if ($CombatChain->Card($i)->TotalPower() >= $attackPower) {
          $defendedByStrongCard = true;
          break;
        }
      }
      if (!$defendedByStrongCard) return true;
    }

    if (empty($chainLinks)) return false;
    $linkPieces = ChainLinksPieces();
    $summaryPieces = ChainLinkSummaryPieces();
    foreach ($chainLinks as $linkNum => $link) {
      if (($link[0] ?? "") != "exude_confidence_red" || ($link[2] ?? 0) != 1) continue;
      $defendedByStrongCard = false;
      $attackPower = $chainLinkSummary[$linkNum * $summaryPieces + 1] ?? 0;
      $linkCount = count($link);
      for ($i = $linkPieces; $i < $linkCount; $i += $linkPieces) {
        if (($link[$i + 1] ?? null) != $defPlayer) continue;
        $power = ModifiedPowerValue($link[$i], $defPlayer, "CC", source:"exude_confidence_red") + ($link[$i + 4] ?? 0);
        if ($power >= $attackPower) {
          $defendedByStrongCard = true;
          break;
        }
      }
      if (!$defendedByStrongCard) return true;
    }
    return false;
  }

