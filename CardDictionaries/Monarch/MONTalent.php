<?php


  function MONTalentCardType($cardID)
  {
    switch($cardID)
    {
      case "MON000": return "A";
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
      case "MON187": case "MON188": return "E";
      case "MON189": case "MON190": return "I";
      case "MON191": return "AA";
      case "MON192": return "DR";
      case "MON193": case "MON194": return "A";
      case "MON195": case "MON196": case "MON197": return "AA";
      case "MON198": case "MON199": return "AA";
      case "MON200": case "MON201": case "MON202": return "A";
      case "MON203": case "MON204": case "MON205": return "AA";
      case "MON206": case "MON207": case "MON208": return "AA";
      case "MON209": case "MON210": case "MON211": return "AA";
      case "MON212": case "MON213": case "MON214": return "A";
      case "MON215": case "MON216": case "MON217": return "I";
      case "MON218": return "I";
      case "MON219": case "MON220": return "T";
      default: return "";
    }
  }

  function MONTalentCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON000": return "Landmark";
      case "MON060": return "Chest";
      case "MON061": return "Head";
      case "MON187": return "Chest";
      case "MON188": return "Head";
      case "MON219": return "Ally";
      case "MON220": return "Ally";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONTalentCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON000": return 2;
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
      case "MON189": case "MON190": return 0;
      case "MON191": return 1;
      case "MON192": return 2;
      case "MON193": case "MON194": return 0;
      case "MON195": case "MON196": case "MON197": return 3;
      case "MON198": case "MON199": return 6;
      case "MON200": case "MON201": case "MON202": return 2;
      case "MON203": case "MON204": case "MON205": return 1;
      case "MON206": case "MON207": case "MON208": return 3;
      case "MON209": case "MON210": case "MON211": return 2;
      case "MON212": case "MON213": case "MON214": return 2;
      case "MON215": case "MON216": case "MON217": return 0;
      case "MON218": return 0;
      default: return 0;
    }
  }

  function MONTalentPitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON000": return 0;
      case "MON060": case "MON061": return 0;
      case "MON062": case "MON063": case "MON064": case "MON065": return 2;
      case "MON066": case "MON069": case "MON072": case "MON075": case "MON078": case "MON081": case "MON084": return 1;
      case "MON067": case "MON070": case "MON073": case "MON076": case "MON079": case "MON082": case "MON085": return 2;
      case "MON068": case "MON071": case "MON074": case "MON077": case "MON080": case "MON083": case "MON086": return 3;
      case "MON087": return 2;
      case "MON189": case "MON190": case "MON191": return 3;
      case "MON192": case "MON193": case "MON194": return 1;
      case "MON198": return 3;
      case "MON199": return 1;
      case "MON195": case "MON200": case "MON203": case "MON206": case "MON209": case "MON212": case "MON215": return 1;
      case "MON196": case "MON201": case "MON204": case "MON207": case "MON210": case "MON213": case "MON216": return 2;
      case "MON197": case "MON202": case "MON205": case "MON208": case "MON211": case "MON214": case "MON217": return 3;
      case "MON218": return 3;
      default: return 0;
    }
  }

  function MONTalentBlockValue($cardID)
  {
    global $defPlayer;
    switch($cardID)
    {
      case "MON000": return 0;
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
      case "MON187": return 6;
      case "MON188": return 0;
      case "MON189": case "MON190": return 0;
      case "MON191": return SearchPitchForNumCosts($defPlayer) * 2;//Not totally accurate
      case "MON192": return 6;
      case "MON194": return 0;
      case "MON198": case "MON199": return 3;
      case "MON203": case "MON204": case "MON205": return 3;
      case "MON209": case "MON210": case "MON211": return 3;
      case "MON209": case "MON210": case "MON211": return 3;
      case "MON215": case "MON216": case "MON217": return 0;
      case "MON218": return 0;
      default: return 2;
    }
  }

  function MONTalentAttackValue($cardID)
  {
    global $mainPlayer;
    switch($cardID)
    {
      case "MON062": return 7;
      case "MON066": return 6;
      case "MON067": case "MON075": case "MON078": return 5;
      case "MON068": case "MON072": case "MON076": case "MON079": return 4;
      case "MON073": case "MON077": case "MON080": return 3;
      case "MON074": return 2;
      case "MON191": return SearchPitchForNumCosts($mainPlayer) * 2;//Not totally accurate
      case "MON206": return 7;
      case "MON195": case "MON198": case "MON199": case "MON207": return 6;
      case "MON196": case "MON208": case "MON209": return 5;
      case "MON197": case "MON203": case "MON210": return 4;
      case "MON204": case "MON211": return 3;
      case "MON205": return 2;
      case "MON219": case "MON220": return 6;
      default: return 0;
    }
  }


  function MONTalentPlayAbility($cardID, $from, $resourcesPaid, $target="-")
  {
    global $currentPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves, $CS_NumAddedToSoul, $combatChain, $CS_PlayIndex;
    $otherPlayer = $currentPlayer == 1 ? 2 : 1;
    switch($cardID)
    {
      case "MON000":
        DestroyLandmark(GetClassState($currentPlayer, $CS_PlayIndex));
        return "The Great Library of Solana was destroyed.";
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
      case "MON069": case "MON070": case "MON071":
        if($cardID == "MON069") $count = 4;
        else if($cardID == "MON070") $count = 3;
        else $count = 2;
        for($i=0; $i<$count; ++$i)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDATTACKCOUNTERS", $currentPlayer, "1", 1);
        }
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON081": case "MON082": case "MON083":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Seek Enlightenment gives your next attack action card +" . EffectAttackModifier($cardID) . " and go in your soul if it hits.";
      case "MON084": $combatChain[$target+5] -= 3; return "";
      case "MON085": $combatChain[$target+5] -= 2; return "";
      case "MON086": $combatChain[$target+5] -= 1; return "";
      case "MON087":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Ray of Hope gives attacks against Shadow heroes +1 this turn.";
      case "MON188":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "SHADOW", 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "MON189":
        PlayAlly("MON219", $currentPlayer);
        return "Doomsday created a Blasmophet token.";
      case "MON190":
        PlayAlly("MON220", $currentPlayer);
        return "Eclipse created an Ursur token.";
      case "MON192":
        if($from=="BANISH")
        {
          return "Guardian of the Shadowrealm was returned to hand.";
        }
        return;
      case "MON193":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Shadow Puppetry gives your next action card +1, Go Again, and if it hits you may banish the top card of your deck.";
      case "MON194":
        MyDrawCard();
        return "Tome of Torment drew a card.";
      case "MON200": case "MON201": case "MON202":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Howl from Beyond gives your next attack action this turn +" . EffectAttackModifier($cardID) . ".";
      case "MON212": case "MON213": case "MON214":
        if($cardID == "MON212") $maxCost = 2;
        else if($cardID == "MON213") $maxCost = 1;
        else $maxCost = 0;
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON212," . $maxCost);
        AddDecisionQueue("CHOOSEBANISH", $currentPlayer, "<-", 1);
        AddDecisionQueue("BANISHADDMODIFIER", $currentPlayer, "MON212", 1);
        return "Spew Shadow let you play an attack action from your banish zone.";
      case "MON215": case "MON216": case "MON217":
        if($cardID == "MON215") $optAmt = 3;
        else if($cardID == "MON216") $optAmt = 2;
        else $optAmt = 1;
        Opt($cardID, $optAmt);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,NA", 1);
        return "Blood Tribute let you opt $optAmt and banish the top card of your deck.";
      case "MON218":
        $theirCharacter = GetPlayerCharacter($otherPlayer);
        if(CardTalent($theirCharacter[0]) == "LIGHT")
        {
          if(GetHealth($currentPlayer) > GetHealth($otherPlayer))
          {
            AddDecisionQueue("FINDINDICES", $currentPlayer, "GYTYPE,AA");
            AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
            AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
            AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,NA", 1);
          }
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "MON219":
        $otherPlayer = $currentPlayer == 2 ? 1 : 2;
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDTALENT,SHADOW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("PASSPARAMETER", $otherPlayer, "1", 1);
        AddDecisionQueue("MULTIREMOVEMYSOUL", $otherPlayer, "-", 1);
        return "";
      default: return "";
    }
  }

  function MONTalentHitEffect($cardID)
  {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer;
    switch($cardID)
    {
      case "MON072": case "MON073": case "MON074": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON078": case "MON079": case "MON080": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON198":
        $numSoul = count(GetSoul($defPlayer));
        for($i=0; $i<$numSoul; ++$i) BanishFromSoul($defPlayer);
        LoseHealth($numSoul, $defPlayer);
        break;
      case "MON206": case "MON207": case "MON208": BanishFromSoul($defPlayer); $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BANISH"; break;
      default: break;
    }
  }

  function ShadowPuppetryHitEffect()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "TOPDECK");
    AddDecisionQueue("DECKCARDS", $mainPlayer, "<-", 1);
    AddDecisionQueue("REVEALCARDS", $mainPlayer, "-", 1);
    AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_banish_the_card", 1);
    AddDecisionQueue("NOPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("FINDINDICES", $mainPlayer, "TOPDECK", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIBANISH", $mainPlayer, "DECK,-", 1);
  }

  function EndTurnBloodDebt()
  {
    global $mainPlayer, $CS_Num6PowBan;
    $character = &GetPlayerCharacter($mainPlayer);
    if($character[1] == 2 && ($character[0] == "MON119" || $character[0] == "MON120") && GetClassState($mainPlayer, $CS_Num6PowBan) > 0)
    {
      WriteLog("Levia took no blood debt because a card with 6 or more power was banished this turn.");
      return;
    }
    $numBD = SearchCount(SearchBanish($mainPlayer, "", "", -1, -1, "", "", true));
    if($numBD > 0)
    {
      LoseHealth($numBD, $mainPlayer);
      WriteLog("Player $mainPlayer lost $numBD health from Blood Debt at end of turn.");
    }
  }

?>
