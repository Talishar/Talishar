<?php


  function UPRNinjaCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR044": case "UPR045": return "C";
      case "UPR046": return "W";
      case "UPR047": return "E";
      case "UPR048": return "AA";
      case "UPR049": return "AA";
      case "UPR051": case "UPR052": case "UPR053": return "AA";
      case "UPR054": case "UPR055": case "UPR056": return "AA";
      case "UPR057": case "UPR058": case "UPR059": return "A";
      case "UPR063": case "UPR064": case "UPR065": return "AA";
      case "UPR069": case "UPR070": case "UPR071": return "AA";
      case "UPR075": case "UPR076": case "UPR077": return "AA";
      case "UPR160": return "AA";
      case "UPR161": return "AA";
      default: return "";
    }
  }

  function UPRNinjaCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR046": return "Sword";
      case "UPR047": return "Arms";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRNinjaCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR048": return 0;
      case "UPR049": return 1;
      case "UPR051": case "UPR052": case "UPR053": return 2;
      case "UPR054": case "UPR055": case "UPR056": return 1;
      case "UPR057": case "UPR058": case "UPR059": return 0;
      case "UPR063": case "UPR064": case "UPR065": return 1;
      case "UPR069": case "UPR070": case "UPR071": return 0;
      case "UPR075": case "UPR076": case "UPR077": return 0;
      case "UPR160": return 0;
      case "UPR161": return 1;
      default: return 0;
    }
  }

  function UPRNinjaPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR048": return 1;
      case "UPR049": return 1;
      case "UPR051": case "UPR054": case "UPR057": case "UPR063": case "UPR069": case "UPR075": return 1;
      case "UPR052": case "UPR055": case "UPR058": case "UPR064": case "UPR070": case "UPR076": return 2;
      case "UPR053": case "UPR056": case "UPR059": case "UPR065": case "UPR071": case "UPR077": return 3;
      case "UPR160": return 1;
      case "UPR161": return 1;
      default: return 0;
    }
  }

  function UPRNinjaBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR047": return 0;
      case "UPR048": return 3;
      case "UPR049": return 2;
      case "UPR051": case "UPR052": case "UPR053": return 2;
      case "UPR054": case "UPR055": case "UPR056": return 2;
      case "UPR057": case "UPR058": case "UPR059": return 3;
      case "UPR063": case "UPR064": case "UPR065": return 3;
      case "UPR069": case "UPR070": case "UPR071": return 3;
      case "UPR075": case "UPR076": case "UPR077": return 2;
      case "UPR160": return 2;
      case "UPR161": return 3;
      default: return -1;
    }
  }

  function UPRNinjaAttackValue($cardID)
  {
    switch($cardID)
    {
      case "UPR046": return 3;
      case "UPR048": return 3;
      case "UPR049": return 3;
      case "UPR051": return 5;
      case "UPR052": return 4;
      case "UPR053": return 3;
      case "UPR054": return 4;
      case "UPR055": return 3;
      case "UPR056": return 2;
      case "UPR063": return 4;
      case "UPR064": return 3;
      case "UPR065": return 2;
      case "UPR069": return 3;
      case "UPR070": return 2;
      case "UPR071": return 1;
      case "UPR075": return 3;
      case "UPR076": return 2;
      case "UPR077": return 1;
      case "UPR160": return 1;
      case "UPR161": return 3;
      default: return 0;
    }
  }

  function UPRNinjaPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    $rv = "";
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
        return "Spreading Flames buffs your draconic attacks this turn.";
      case "UPR057": case "UPR058": case "UPR059":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        return "";
      default: return "";
    }
  }

  function UPRNinjaHitEffect($cardID)
  {
    global $mainPlayer, $combatChainState, $CCS_NumHits;
    switch($cardID)
    {
      case "UPR048":
        if(NumPhoenixFlameChainLinks() >= 3)
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
      case "UPR161":
        WriteLog($combatChainState[$CCS_NumHits]);
        if($combatChainState[$CCS_NumHits] >= 2)
        {
          $deck = &GetDeck($mainPlayer);
          if(CardType($deck[0]) == "AA")
          {
            BanishCardForPlayer($deck[0], $mainPlayer, "DECK", "NT");
            array_shift($deck);
          }
        }
        break;
      default: break;
    }
  }

?>
