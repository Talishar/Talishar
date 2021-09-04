<?php


  function MONTalentCardType($cardID)
  {
    switch($cardID)
    {
      case "MON060": return "E";
      case "MON061": return "E";
      case "MON062": return "AA";
      case "MON063": return "DR";
      case "MON064": return "A";
      case "MON065": return "I";
      case "MON066": case "MON067": case "MON068": return "AA";
      case "MON069": case "MON070": case "MON071": return "I";
      case "MON072": case "MON073": case "MON074": return "AA";
      case "MON075": case "MON076": case "MON077": return "AA";
      case "MON078": case "MON079": case "MON080": return "AA";
      case "MON081": case "MON082": case "MON083": return "A";
      case "MON084": case "MON085": case "MON086": return "I";
      case "MON087": return "I";
      default: return "";
    }
  }

  function MONTalentCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON060": return "Chest";
      case "MON061": return "Head";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONTalentCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON060": return 0;
      case "MON061": return 0;
      case "MON062": return 0;
      case "MON063": return 2;
      case "MON064": return 0;
      case "MON065": return 4;
      case "MON066": case "MON067": case "MON068": return 3;
      case "MON069": case "MON070": case "MON071": return 2;
      case "MON072": case "MON073": case "MON074": return 0;
      case "MON075": case "MON076": case "MON077": return 2;
      case "MON078": case "MON079": case "MON080": return 1;
      case "MON081": case "MON082": case "MON083": return 1;
      case "MON084": case "MON085": case "MON086": return 1;
      case "MON087": return 1;
      default: return 0;
    }
  }

  function MONTalentPitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON060": case "MON061": return 0;
      case "MON062": case "MON063": case "MON064": case "MON065": return 2;
      case "MON066": case "MON069": case "MON072": case "MON075": case "MON078": case "MON081": case "MON084": return 1;
      case "MON067": case "MON070": case "MON073": case "MON076": case "MON079": case "MON082": case "MON085": return 2;
      case "MON068": case "MON071": case "MON074": case "MON077": case "MON080": case "MON083": case "MON086": return 3;
      case "MON087": return 2;
      default: return 0;
    }
  }

  function MONTalentBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON060": return 1;
      case "MON061": return 0;
      case "MON062": return 3;
      case "MON063": return 6;
      case "MON065": return 0;
      case "MON069": case "MON070": case "MON071": return 0;
      case "MON072": case "MON073": case "MON074": return 3;
      case "MON078": case "MON079": case "MON080": return 3;
      case "MON084": case "MON085": case "MON086": return 0;
      case "MON087": return 0;
      default: return 2;
    }
  }

  function MONTalentAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON062": return 7;
      case "MON066": return 6;
      case "MON067": case "MON075": case "MON078": return 5;
      case "MON068": case "MON072": case "MON076": case "MON079": return 4;
      case "MON073": case "MON077": case "MON080": return 3;
      case "MON074": return 2;
      default: return 0;
    }
  }


  function MONTalentPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves, $CS_NumAddedToSoul;
    switch($cardID)
    {
      case "MON061":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
        AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "LIGHT", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "MON064":
        $hand = &GetHand($currentPlayer);
        for($i = 0; $i < count($hand); ++$i)
        {
          AddSoul($hand[$i], $currentPlayer, "HAND");
        }
        $hand = [];
        return "Soul Food put itself and all cards in your hand into your soul.";
      case "MON065":
        MyDrawCard();
        MyDrawCard();
        if(GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0) MyDrawCard();
        return "";
      case "MON066": case "MON067": case "MON068":
        if(count(GetSoul($currentPlayer)) == 0)
        {
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
          $rv = "Invigorating Light will go into your soul after the chain link closes.";
        }
        return $rv;
      case "MON081": case "MON082": case "MON083":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Seek Enlightenment gives your next attack action card +" . EffectAttackModifier($cardID) . " and go in your soul if it hits.";
      default: return "";
    }
  }

  function MONTalentHitEffect($cardID)
  {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "MON072": case "MON073": case "MON074": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON078": case "MON079": case "MON080": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      default: break;
    }
  }

?>

