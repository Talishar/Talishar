<?php

  function UPRNinjaPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "UPR044": case "UPR045":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        return "";
      case "UPR047":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR049":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Buffs your draconic attacks this combat chain.";
      case "UPR050":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CCDEFLESSX," . NumDraconicChainLinks()-1);
        AddDecisionQueue("FILTER", $currentPlayer, "CombatChain-exclude-type-E", 1);
        AddDecisionQueue("FILTER", $currentPlayer, "CombatChain-include-player-" . ($player == 1 ? 2 : 1), 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish", 1);
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVECOMBATCHAIN", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", ($currentPlayer == 1 ? 2 : 1), "CC,-", 1);
        return "";
      case "UPR057": case "UPR058": case "UPR059":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
        AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        return "";
      case "UPR060": case "UPR061": case "UPR062":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
      case "UPR159":
        GiveAttackGoAgain();
        return "Gives the attack go again.";
      default: return "";
    }
  }

  function UPRNinjaHitEffect($cardID)
  {
    global $mainPlayer, $combatChainState;
    switch($cardID)
    {
      case "UPR048":
        if(IsHeroAttackTarget() && NumChainLinksWithName("Phoenix Flame") >= 3)
        {
          Draw($mainPlayer);
          Draw($mainPlayer);
          Draw($mainPlayer);
        }
        break;
      case "UPR051": case "UPR052": case "UPR053":
        $deck = &GetDeck($mainPlayer);
        $cardRevealed = RevealCards($deck[0]);
        if($cardRevealed)
        {
          if(CardType($deck[0]) == "AA" && CardCost($deck[0]) < NumDraconicChainLinks())
          {
            BanishCardForPlayer($deck[0], $mainPlayer, "PLAY", "TT");
            WriteLog(CardLink($deck[0], $deck[0]) . " was banished and can be played this turn.");
            array_shift($deck);
          }
        }
        break;
      case "UPR054": case "UPR055": case "UPR056":
      case "UPR075": case "UPR076": case "UPR077":
      case "UPR081": case "UPR082": case "UPR083":
        $numDraconicLinks = NumDraconicChainLinks();
        AddDecisionQueue("FINDINDICES", $mainPlayer, "HANDAAMAXCOST," . ($numDraconicLinks > 0 ? $numDraconicLinks - 1 : -2));
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card to banish", 1);
        AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $mainPlayer, "HAND,TT", 1);
        AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $mainPlayer, "<0> was banished.", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "{I}", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "GETUNIQUEID", 1);
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $mainPlayer, $cardID . ",HIT", 1);
        break;
      case "UPR161":
        $rv = "";
        if(HitsInCombatChain() >= 2)
        {
          $deck = &GetDeck($mainPlayer);
          $rv .= CardLink($deck[0], $deck[0]) . " was banished.";
          if(CardType($deck[0]) == "AA")
          {
            BanishCardForPlayer($deck[0], $mainPlayer, "DECK", "NT");
            $rv .= " It can be played until the end of next turn";
            array_shift($deck);
          }
          else
          {
            BanishCardForPlayer($deck[0], $mainPlayer, "DECK");
            array_shift($deck);
          }
          $rv .= ".";
          WriteLog($rv);
        }
        break;
      default: break;
    }
  }

?>
