<?php

  function ELEGuardianPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $CS_DamagePrevention;
    $rv = "";
    switch($cardID)
    {
      case "ELE001": case "ELE002":
        if(SearchCardList($additionalCosts, $currentPlayer, talent:"EARTH") != "") IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
        if(SearchCardList($additionalCosts, $currentPlayer, talent:"ICE") != "")
        {
          if(IsAllyAttacking()) $rv .= "<span style='color:red;'>No card is put on top because there is no attacking hero when allies attack.</span>";
          else
          {
            $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
            AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
            AddDecisionQueue("CHOOSEHAND", $otherPlayer, "<-", 1);
            AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
            AddDecisionQueue("MULTIADDTOPDECK", $otherPlayer, "-", 1);
            $rv .= "The opponent must put a card from their hand on top of their deck.";
          }
        }
        return $rv;
      case "ELE003":
        if(SearchCardList($additionalCosts, $currentPlayer, talent:"ICE") != "") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE005":
        if(DelimStringContains($additionalCosts, "ICE") && DelimStringContains($additionalCosts, "EARTH")) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE006":
        $num = GetHealth($currentPlayer == 1 ? 2 : 1) - GetHealth($currentPlayer);
        PlayAura("WTR075", $currentPlayer, $num * (DelimStringContains($additionalCosts, "EARTH") ? 2 : 1));
        MZMoveCard($currentPlayer, "MYDECK:type=AA;class=GUARDIAN;maxCost=" . CountAura("WTR075", $currentPlayer), "MYHAND", may:true, isReveal:true);
        return "";
      case "ELE205":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE206": case "ELE207": case "ELE208":
        if(NumNonTokenAura($currentPlayer) > 1) { $rv = "Drew a card"; Draw($currentPlayer); }
        return $rv;
      default: return "";
    }
  }

  function ELEGuardianHitEffect($cardID)
  {
    global $defPlayer, $mainPlayer, $combatChainState, $CCS_AttackFused;
    switch($cardID)
    {
      case "ELE003":
        if(IsHeroAttackTarget()) {
          PlayAura("ELE111", $defPlayer, effectController: $mainPlayer);
        }
        break;
      case "ELE004":
        if(IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID . "-HIT", $defPlayer);
          AddNextTurnEffect($cardID . "-HIT", $defPlayer);
        }
        break;
      case "ELE005":
        if (IsHeroAttackTarget()) {
          $hand = &GetHand($defPlayer);
          $cards = "";
          for ($i = 0; $i < 2 && count($hand) > 0; ++$i) {
            $index = GetRandom() % count($hand);
            if ($cards != "") $cards .= ",";
            $cards .= $hand[$index];
            unset($hand[$index]);
            $hand = array_values($hand);
          }
          if ($cards != "") AddDecisionQueue("CHOOSEBOTTOM", $defPlayer, $cards);
        }
        break;    
      case "ELE013": case "ELE014": case "ELE015":
        if(IsHeroAttackTarget() && $combatChainState[$CCS_AttackFused]) AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "ELE209": case "ELE210": case "ELE211":
        if(IsHeroAttackTarget() && HasIncreasedAttack()) PummelHit();
        break;
      default: break;
    }
  }