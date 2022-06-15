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
      case "UPR050": return "AR";
      case "UPR051": case "UPR052": case "UPR053": return "AA";
      case "UPR054": case "UPR055": case "UPR056": return "AA";
      case "UPR057": case "UPR058": case "UPR059": return "A";
      case "UPR060": case "UPR061": case "UPR062": return "AA";
      case "UPR063": case "UPR064": case "UPR065": return "AA";
      case "UPR066": case "UPR067": case "UPR068": return "AA";
      case "UPR069": case "UPR070": case "UPR071": return "AA";
      case "UPR072": case "UPR073": case "UPR074": return "AA";
      case "UPR075": case "UPR076": case "UPR077": return "AA";
      case "UPR078": case "UPR079": case "UPR080": return "AA";
      case "UPR081": case "UPR082": case "UPR083": return "AA";
      case "UPR158": return "E";
      case "UPR159": return "E";
      case "UPR160": return "AA";
      case "UPR161": return "AA";
      case "UPR162": case "UPR163": case "UPR164": return "AR";
      default: return "";
    }
  }

  function UPRNinjaCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR046": return "Sword";
      case "UPR047": return "Arms";
      case "UPR158": return "Arms";
      case "UPR159": return "Legs";
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
      case "UPR050": return 1;
      case "UPR051": case "UPR052": case "UPR053": return 2;
      case "UPR054": case "UPR055": case "UPR056": return 1;
      case "UPR057": case "UPR058": case "UPR059": return 0;
      case "UPR060": case "UPR061": case "UPR062": return 0;
      case "UPR063": case "UPR064": case "UPR065": return 1;
      case "UPR066": case "UPR067": case "UPR068": return 1;
      case "UPR069": case "UPR070": case "UPR071": return 0;
      case "UPR072": case "UPR073": case "UPR074": return 2;
      case "UPR075": case "UPR076": case "UPR077": return 0;
      case "UPR078": case "UPR079": case "UPR080": return 0;
      case "UPR081": case "UPR082": case "UPR083": return 1;
      case "UPR160": return 0;
      case "UPR161": return 1;
      case "UPR162": case "UPR163": case "UPR164": return 1;
      default: return 0;
    }
  }

  function UPRNinjaPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR048": return 1;
      case "UPR049": return 1;
      case "UPR050": return 1;
      case "UPR051": case "UPR054": case "UPR057": case "UPR060": case "UPR063": case "UPR066": case "UPR069": case "UPR072": case "UPR075": case "UPR078": case "UPR081": return 1;
      case "UPR052": case "UPR055": case "UPR058": case "UPR061": case "UPR064": case "UPR067": case "UPR070": case "UPR073": case "UPR076": case "UPR079": case "UPR082": return 2;
      case "UPR053": case "UPR056": case "UPR059": case "UPR062": case "UPR065": case "UPR068": case "UPR071": case "UPR074": case "UPR077": case "UPR080": case "UPR083": return 3;
      case "UPR160": return 1;
      case "UPR161": return 1;
      case "UPR162": return 1;
      case "UPR163": return 2;
      case "UPR164": return 3;
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
      case "UPR050": return 3;
      case "UPR051": case "UPR052": case "UPR053": return 2;
      case "UPR054": case "UPR055": case "UPR056": return 2;
      case "UPR057": case "UPR058": case "UPR059": return 3;
      case "UPR060": case "UPR061": case "UPR062": return 2;
      case "UPR063": case "UPR064": case "UPR065": return 3;
      case "UPR066": case "UPR067": case "UPR068": return 2;
      case "UPR069": case "UPR070": case "UPR071": return 3;
      case "UPR072": case "UPR073": case "UPR074": return 2;
      case "UPR075": case "UPR076": case "UPR077": return 2;
      case "UPR078": case "UPR079": case "UPR080": return 2;
      case "UPR081": case "UPR082": case "UPR083": return 2;
      case "UPR158": return 2;
      case "UPR159": return 0;
      case "UPR160": return 2;
      case "UPR161": return 3;
      case "UPR162": case "UPR163": case "UPR164": return 3;
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
      case "UPR050": return 1;
      case "UPR051": return 5;
      case "UPR052": return 4;
      case "UPR053": return 3;
      case "UPR054": return 4;
      case "UPR055": return 3;
      case "UPR056": return 2;
      case "UPR060": return 3;
      case "UPR061": return 2;
      case "UPR062": return 1;
      case "UPR063": return 4;
      case "UPR064": return 3;
      case "UPR065": return 2;
      case "UPR066": return 4;
      case "UPR067": return 3;
      case "UPR068": return 2;
      case "UPR069": return 3;
      case "UPR070": return 2;
      case "UPR071": return 1;
      case "UPR072": return 5;
      case "UPR073": return 4;
      case "UPR074": return 3;
      case "UPR075": return 3;
      case "UPR076": return 2;
      case "UPR077": return 1;
      case "UPR078": return 3;
      case "UPR079": return 2;
      case "UPR080": return 1;
      case "UPR081": return 4;
      case "UPR082": return 3;
      case "UPR083": return 2;
      case "UPR160": return 1;
      case "UPR161": return 3;
      case "UPR162": return 3;
      case "UPR163": return 2;
      case "UPR164": return 1;
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
      case "UPR050":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CCDEFLESSX," . NumDraconicChainLinks()-1);
        AddDecisionQueue("CCFILTERTYPE", $currentPlayer, "E");
        AddDecisionQueue("CCFILTERPLAYER", $currentPlayer, $currentPlayer);
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVECOMBATCHAIN", $currentPlayer, "-");
        AddDecisionQueue("MULTIBANISH", ($currentPlayer == 1 ? 2 : 1), "CC,-");
        return "";
      case "UPR057": case "UPR058": case "UPR059":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        return "";
      case "UPR159":
        GiveAttackGoAgain();
        return "Tide Flippers gave the attack Go Again.";
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
      case "UPR054": case "UPR055": case "UPR056":
      case "UPR075": case "UPR076": case "UPR077":
      case "UPR081": case "UPR082": case "UPR083":
        AddDecisionQueue("FINDINDICES", $mainPlayer, "HANDAAMAXCOST," . (NumDraconicChainLinks()-1));
        AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $mainPlayer, "HAND,TT", 1);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "{I}", 1);
        AddDecisionQueue("MZGETUNIQUEID", $mainPlayer, "-", 1);
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $mainPlayer, $cardID . ",HIT", 1);
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
