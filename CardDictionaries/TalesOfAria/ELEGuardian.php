<?php

  function ELEGuardianPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $CS_DamagePrevention;
    $rv = "";
    switch($cardID)
    {
      case "ELE001": case "ELE002":
        $pitchArr = explode(",", $additionalCosts);
        $earthPitched = 0; $icePitched = 0;
        for($i=0; $i<count($pitchArr); ++$i)
        {
          if(TalentContains($pitchArr[$i], "EARTH", $currentPlayer)) $earthPitched = 1;
          if(TalentContains($pitchArr[$i], "ICE", $currentPlayer)) $icePitched = 1;
        }
        $rv = "";
        if($earthPitched)
        {
          IncrementClassState($currentPlayer, $CS_DamagePrevention, 2);
          $rv .= "Prevent the next 2 damage that would be dealt to Oldhim this turn. ";
        }
        if($icePitched)
        {
          if(IsAllyAttacking())
          {
            $rv .= "<span style='color:red;'>No card is put on top because there is no attacking hero when allies attack.</span>";
          }
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
        $pitchArr = explode(",", $additionalCosts);
        $icePitched = 0;
        for($i=0; $i<count($pitchArr); ++$i)
        {
          if(TalentContains($pitchArr[$i], "ICE", $currentPlayer)) $icePitched = 1;
        }
        if($icePitched)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv .= "If this hits, your opponent gains a Frostbite.";
        }
        return $rv;
      case "ELE005":
        if(DelimStringContains($additionalCosts, "ICE") && DelimStringContains($additionalCosts, "EARTH"))
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          WriteLog(CardLink($cardID, $cardID) . " gets +2, Dominate, and discards cards on hit.");
        }
        return "";
      case "ELE006":
        if(DelimStringContains($additionalCosts, "EARTH")) AddDecisionQueue("AWAKENINGTOKENS", $currentPlayer, "-");
        AddDecisionQueue("AWAKENINGTOKENS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "ELE205":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your next Guardian attack +1, Dominate, and discards 2 on hit.";
      case "ELE206": case "ELE207": case "ELE208":
        if(NumNonTokenAura($currentPlayer) > 1) { $rv = "Draws a card."; MyDrawCard(); }
        return $rv;
      default: return "";
    }
  }

  function ELEGuardianHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_AttackFused;
    switch($cardID)
    {
      case "ELE004":
        if(IsHeroAttackTarget())
        {
          AddCurrentTurnEffect($cardID . "-HIT", $defPlayer);
          AddNextTurnEffect($cardID . "-HIT", $defPlayer);
          WriteLog(CardLink($cardID, $cardID) . " makes the defending player get a frostbite when activating an ability until the end of their next turn.");
        }
        break;
      case "ELE013": case "ELE014": case "ELE015":
        if(IsHeroAttackTarget() && $combatChainState[$CCS_AttackFused])
        {
          AddNextTurnEffect($cardID, $defPlayer);
          WriteLog(CardLink($cardID, $cardID) . " gives the opponent's first attack next turn -2.");
        }
        break;
      case "ELE209": case "ELE210": case "ELE211":
        if(IsHeroAttackTarget() && HasIncreasedAttack())
        {
          PummelHit();
        }
        break;
      default: break;
    }
  }


?>
