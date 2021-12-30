<?php


  function ARCGenericCardType($cardID)
  {
    switch($cardID)
    {
      case "ARC000": return "R";
      case "ARC150": case "ARC151": case "ARC152": case "ARC153": case "ARC154": return "E";
      case "ARC155": case "ARC156": case "ARC157": case "ARC158": return "E";
      case "ARC159": return "AA";
      case "ARC160": return "I";
      case "ARC161": return "AA";
      case "ARC162": case "ARC163": return "A";
      case "ARC164": case "ARC165": case "ARC166": return "AA";
      case "ARC167": case "ARC168": case "ARC169": return "A";
      case "ARC170": case "ARC171": case "ARC172": return "A";
      case "ARC173": case "ARC174": case "ARC175": return "I";
      case "ARC176": case "ARC177": case "ARC178": return "AA";
      case "ARC179": case "ARC180": case "ARC181": return "AA";
      case "ARC182": case "ARC183": case "ARC184": return "AA";
      case "ARC185": case "ARC186": case "ARC187": return "AA";
      case "ARC188": case "ARC189": case "ARC190": return "AA";
      case "ARC191": case "ARC192": case "ARC193": return "AA";
      case "ARC194": case "ARC195": case "ARC196": return "AA";
      case "ARC197": case "ARC198": case "ARC199": return "AA";
      case "ARC200": case "ARC201": case "ARC202": return "DR";
      case "ARC203": case "ARC204": case "ARC205": return "A";
      case "ARC206": case "ARC207": case "ARC208": return "A";
      case "ARC209": case "ARC210": case "ARC211": return "A";
      case "ARC212": case "ARC213": case "ARC214": return "A";
      case "ARC215": case "ARC216": case "ARC217": return "A";
      default: return "";
    }
  }

  function ARCGenericCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ARC000": return "Gem";
      case "ARC150": return "Head";
      case "ARC151": return "Head";
      case "ARC152": return "Chest";
      case "ARC153": return "Arms";
      case "ARC154": return "Legs";
      case "ARC155": return "Head";
      case "ARC156": return "Chest";
      case "ARC157": return "Arms";
      case "ARC158": return "Legs";
      case "ARC162": return "Aura";
      case "ARC163": return "Item";
      case "ARC167": case "ARC168": case "ARC169": return "Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ARCGenericCardCost($cardID)
  {
    switch($cardID)
    {
      case "ARC159": return 2;
      case "ARC160": return 1;
      case "ARC161": return 2;
      case "ARC164": case "ARC165": case "ARC166": return 1;
      case "ARC167": case "ARC168": case "ARC169": return 2;
      case "ARC173": case "ARC174": case "ARC175": return 1;
      case "ARC176": case "ARC177": case "ARC178": return 1;
      case "ARC179": case "ARC180": case "ARC181": return 2;
      case "ARC185": case "ARC186": case "ARC187": return 2;
      case "ARC188": case "ARC189": case "ARC190": return 1;
      case "ARC194": case "ARC195": case "ARC196": return 2;
      case "ARC197": case "ARC198": case "ARC199": return 1;
      case "ARC203": case "ARC204": case "ARC205": return 1;
      case "ARC206": case "ARC207": case "ARC208": return 1;
      default: return 0;
    }
  }

  function ARCGenericPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ARC000": return 3;
      case "ARC159": return 1;
      case "ARC160": return 2;
      case "ARC161": return 3;
      case "ARC162": return 1;
      case "ARC163": return 3;
      case "ARC164": case "ARC167": case "ARC170": case "ARC173": case "ARC176": case "ARC179": case "ARC182": case "ARC185": case "ARC188": return 1;
      case "ARC191": case "ARC194": case "ARC197": case "ARC200": case "ARC203": case "ARC206": case "ARC209": case "ARC212": case "ARC215": return 1;
      case "ARC165": case "ARC168": case "ARC171": case "ARC174": case "ARC177": case "ARC180": case "ARC183": case "ARC186": case "ARC189": return 2;
      case "ARC192": case "ARC195": case "ARC198": case "ARC201": case "ARC204": case "ARC207": case "ARC210": case "ARC213": case "ARC216": return 2;
      case "ARC166": case "ARC169": case "ARC172": case "ARC175": case "ARC178": case "ARC181": case "ARC184": case "ARC187": case "ARC190": return 3;
      case "ARC193": case "ARC196": case "ARC199": case "ARC202": case "ARC205": case "ARC208": case "ARC211": case "ARC214": case "ARC217": return 3;
      default: return 0;
    }
  }

  function ARCGenericBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ARC000": return 0;
      case "ARC150": return 1;
      case "ARC151": case "ARC152": case "ARC153": case "ARC154": return 0;
      case "ARC155": case "ARC156": case "ARC157": case "ARC158": return 0;
      case "ARC159": return 3;
      case "ARC160": return 0;
      case "ARC163": return 0;
      case "ARC173": case "ARC174": case "ARC175": return 0;
      case "ARC200": return 4;
      case "ARC201": return 3;
      case "ARC202": return 2;
      case "ARC203": case "ARC204": case "ARC205": return 3;
      case "ARC215": case "ARC216": case "ARC217": return 3;
      default: return 2;
    }
  }

  function ARCGenericAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ARC159": return 6;
      case "ARC161": return 4;
      case "ARC179": case "ARC194": return 6;
      case "ARC176": case "ARC180": case "ARC185": case "ARC191": case "ARC195": return 5;
      case "ARC164": case "ARC177": case "ARC181": case "ARC186": case "ARC188": case "ARC192": case "ARC196": case "ARC197": return 4;
      case "ARC165": case "ARC178": case "ARC182": case "ARC187": case "ARC189": case "ARC193": case "ARC198": return 3;
      case "ARC166": case "ARC183": case "ARC190": case "ARC199": return 2;
      case "ARC184": return 1;
      default: return 0;
    }
  }


  function ARCGenericPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $myClassState, $CS_NumMoonWishPlayed, $myHealth, $myDeck;
    global $CS_NextNAACardGoAgain, $CS_ArcaneDamagePrevention;
    switch($cardID)
    {
      case "ARC151":
        Opt($cardID, 2);
        return "Talismanic Lens lets you Opt 2.";
      case "ARC153":
        $pitchValue = 0;
        if(count($myDeck) > 0)
        {
          $pitchValue = PitchValue($myDeck[0]);
          $rv = "Bracers of Belief revealed " . $myDeck[0] . " and gives the next attack action card +" . (3 - $pitchValue) . ".";
        }
        else { $rv = "There are no cards in deck for Bracers of Belief to reveal, so the next attack gets +3."; }
        $bonus = 3 - $pitchValue;
        if($bonus > 0) AddCurrentTurnEffect("AAPLUS" . $bonus, $currentPlayer);
        return $rv;
      case "ARC154":
        $myClassState[$CS_NextNAACardGoAgain] = 1;
        return "Mage Master Boots gives your next non-attack action card this turn Go Again.";
      case "ARC160":
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Buff_attack_actions,Go_again,Attack_actions_from_arsenal,Banish_and_draw");
        AddDecisionQueue("ARTOFWAR", $currentPlayer, "-", 1);
        return "";
      case "ARC162":
        return "Chains of Eminence is currently a manual resolve card. Name the card in chat, and enforce not playing it manually.";
      case "ARC164": case "ARC165": case "ARC166":
        if(IHaveLessHealth()) { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret = "Life for a Life gained Go Again."; }
        return $ret;
      case "ARC170": case "ARC171": case "ARC172":
        $rv = "Plunder makes your next attack action that hits draw a card";
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        if($from == "ARS")
        {
          AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
          $rv .= " and gives your next attack action card +" . EffectAttackModifier($cardID . "-2") . ".";
        }
        else { $rv .= "."; }
        return  $rv;
      case "ARC173": case "ARC174": case "ARC175":
        if($cardID == "ARC173") $prevent = 6;
        else if($cardID == "ARC174") $prevent = 5;
        else $prevent = 4;
        $deck = GetDeck($currentPlayer);
        if(count($deck) > 0)
        {
          $revealed = $deck[0];
          $prevent -= PitchValue($revealed);
        }
        IncrementClassState($currentPlayer, $CS_ArcaneDamagePrevention, $prevent);
        return "Eirina's Prayer reveals " . CardLink($revealed, $revealed) . " and prevents the next " . $prevent . " arcane damage.";
      case "ARC182": case "ARC183": case "ARC184":
        if($from == "ARS") { $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1; $ret = "Fervent Forerunner gained Go Again."; }
        return $ret;
      case "ARC185": case "ARC186": case "ARC187":
        ++$myClassState[$CS_NumMoonWishPlayed];
        return "";
      case "ARC191": case "ARC192": case "ARC193":
        $deck = GetDeck($currentPlayer);
        if(count($deck) == 0) return "Your deck is empty. Ravenous Rabble does not get negative attack.";
        $pitchVal = PitchValue($deck[0]);
        SetCCAttackModifier(0, -$pitchVal);
        return "Ravenous Rabble reveals " . CardLink($deck[0], $deck[0]) . " and gets -" . $pitchVal . " attack.";
      case "ARC200": case "ARC201": case "ARC202": Opt($cardID, 1); return "Fate Foreseen allows you to Opt 1.";
      case "ARC203": case "ARC204": case "ARC205":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Come to Fight gives your next attack action card +" . EffectAttackModifier($cardID) . ".";
      case "ARC206": case "ARC207": case "ARC208":
        $rv = "Force Sight gives your next attack action card +" . EffectAttackModifier($cardID);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if($from == "ARS") { Opt($cardID, 2); $rv .= " and lets you opt 2."; }
        else { $rv .= "."; }
        return $rv;
      case "ARC209": case "ARC210": case "ARC211":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if($cardID == "ARC209") $cost = 0;
        else if($cardID == "ARC210") $cost = 1;
        else $cost = 2;
        return "Lead the Charge makes you gain an action point the next time you play an action card with cost $cost or greater.";
      case "ARC212": case "ARC213": case "ARC214":
        if($cardID == "ARC212") $health = 3;
        else if($cardID == "ARC213") $health = 2;
        else $health = 1;
        PlayerGainHealth($health, $myHealth);
        $rv = "Sun Kiss gained $health health";
        if($myClassState[$CS_NumMoonWishPlayed] > 0) { MyDrawCard(); $rv .= " and drew a card."; }
        else $rv .= ".";
        return $rv;
      case "ARC215": case "ARC216": case "ARC217":
        if($cardID == "ARC215") $opt = 4;
        else if($cardID == "ARC216") $opt = 3;
        else $opt = 2;
        Opt($cardID, $opt);
        return "Whisper of the Oracle lets you opt " . $opt . ".";
      default: return "";
    }
  }

  function ARCGenericHitEffect($cardID)
  {
    global $mainPlayer, $mainHealth, $CS_NextNAAInstant, $defPlayer;
    switch($cardID)
    {
      case "ARC159": DestroyArsenal($defPlayer); break;
      case "ARC164": case "ARC165": case "ARC166": PlayerGainHealth(1, $mainHealth); break;
      case "ARC161": AddCurrentTurnEffect($cardID, $mainPlayer); break;
      case "ARC179": case "ARC180": case "ARC181":
        AddDecisionQueue("FINDINDICES", $mainPlayer, "MAINDISCARDNAA");
        AddDecisionQueue("MAYCHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYDISCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
        break;
      case "ARC182": case "ARC183": case "ARC184": OptMain(2); break;
      case "ARC185": case "ARC186": case "ARC187":
        AddDecisionQueue("FINDINDICES", $mainPlayer, $cardID);
        AddDecisionQueue("CHOOSEDECK", $mainPlayer, "<-", 1);
        AddDecisionQueue("ADDMYHAND", $mainPlayer, "-", 1);
        AddDecisionQueue("REVEALCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
        break;
      case "ARC194": case "ARC195": case "ARC196":
        SetClassState($mainPlayer, $CS_NextNAAInstant, 1);
        break;
      default: break;
    }
  }

  function ArtOfWarResolvePlay($userInput)
  {
    global $currentPlayer, $combatChain;
      for($i=0; $i<count($userInput); ++$i)
      {
        switch($userInput[$i])
        {
          case "Buff_attack_actions":
            WriteLog("Art of War gives attack action cards +1 attack and block this turn.");
            AddCurrentTurnEffect("ARC160-1", $currentPlayer);
            break;
          case "Go_again":
            WriteLog("Art of War gives the next attack action card this turn Go Again.");
            if(count($combatChain) > 0)
            {
              AddCurrentTurnEffectFromCombat("ARC160-3", $currentPlayer);
            }
            else
            {
              AddCurrentTurnEffect("ARC160-3", $currentPlayer);
            }
            break;
          case "Attack_actions_from_arsenal":
            WriteLog("Art of War makes it possible to block with attack actions from arsenal.");
            AddCurrentTurnEffect("ARC160-2", $currentPlayer);
            break;
          case "Banish_and_draw":
            WriteLog("Art of War allows you to banish a card and draw 2.");
            PrependDecisionQueue("DRAW", $currentPlayer, "-", 1);
            PrependDecisionQueue("DRAW", $currentPlayer, "-", 1);
            PrependDecisionQueue("BANISH", $currentPlayer, "-", 1);
            PrependDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
            PrependDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
            PrependDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDAA");
            break;
          default: break;
        }
      }
  }

?>

