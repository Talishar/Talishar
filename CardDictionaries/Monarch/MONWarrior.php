<?php


  function MONWarriorCardType($cardID)
  {
    switch($cardID)
    {
      case "MON029": case "MON030": return "C";
      case "MON031": return "W";
      case "MON032": return "AA";
      case "MON033": return "AR";
      case "MON034": return "A";
      case "MON035": return "AA";
      case "MON036": case "MON037": case "MON038": return "AA";
      case "MON039": case "MON040": case "MON041": return "AA";
      case "MON042": case "MON043": case "MON044": return "AA";
      case "MON045": case "MON046": case "MON047": return "AA";
      case "MON048": case "MON049": case "MON050": return "AA";
      case "MON051": case "MON052": case "MON053": return "AA";
      case "MON054": case "MON055": case "MON056": return "AA";
      case "MON057": case "MON058": case "MON059": return "AR";
      case "MON105": case "MON106": return "W";
      case "MON107": case "MON108": return "E";
      case "MON109": return "A";
      case "MON110": case "MON111": case "MON112": return "A";
      case "MON113": case "MON114": case "MON115": return "A";
      case "MON116": case "MON117": case "MON118": return "A";
      case "MON405": return "M";
      default: return "";
    }
  }

  function MONWarriorCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON031": return "Sword";
      case "MON105": return "Axe";
      case "MON106": return "Axe";
      case "MON107": return "Legs";
      case "MON108": return "Arms";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONWarriorCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON032": return 4;
      case "MON035": return 1;
      case "MON036": case "MON037": case "MON038": return 1;
      case "MON039": case "MON040": case "MON041": return 1;
      case "MON042": case "MON043": case "MON044": return 0;
      case "MON045": case "MON046": case "MON047": return 1;
      case "MON048": case "MON049": case "MON050": return 0;
      case "MON051": case "MON052": case "MON053": return 0;
      case "MON054": case "MON055": case "MON056": return 1;
      case "MON057": case "MON058": case "MON059": return 0;
      case "MON109": return 1;
      case "MON110": case "MON111": case "MON112": return 1;
      case "MON113": case "MON114": case "MON115": return 1;
      case "MON116": case "MON117": case "MON118": return 1;
      default: return 0;
    }
  }

  function MONWarriorPitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON029": case "MON030": case "MON031": return 0;
      case "MON032": case "MON033": case "MON034": case "MON035": return 2;
      case "MON036": case "MON039": case "MON042": case "MON045": case "MON048": case "MON051": case "MON054": case "MON057": return 1;
      case "MON037": case "MON040": case "MON043": case "MON046": case "MON049": case "MON052": case "MON055": case "MON058": return 2;
      case "MON105": case "MON106": case "MON107": case "MON108": return 0;
      case "MON109": return 1;
      case "MON110": case "MON113": case "MON116": return 1;
      case "MON111": case "MON114": case "MON117": return 2;
      case "MON405": return "0";
      default: return 3;
    }
  }

  function MONWarriorBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON029": case "MON030": case "MON031": return 0;
      case "MON057": case "MON058": case "MON059": return 2;
      case "MON105": case "MON106": return 0;
      case "MON107": case "MON108": return 1;
      default: return 3;
    }
  }

  function MONWarriorAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON031": return 0;
      case "MON032": return 7;
      case "MON035": return 3;
      case "MON036": case "MON045": return 5;
      case "MON037": case "MON039": case "MON046": case "MON051": case "MON054": return 4;
      case "MON038": case "MON040": case "MON042": case "MON047": case "MON048": case "MON052": case "MON055": return 3;
      case "MON041": case "MON043": case "MON049": case "MON053": case "MON056": return 2;
      case "MON044": case "MON050": return 1;
      case "MON105": case "MON106": return 2;
      default: return 0;
    }
  }

  function MONWarriorPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $myClassState, $CS_NumCharged, $combatChain, $currentPlayer, $mySoul, $myCharacter, $CS_AtksWWeapon, $CS_LastAttack;
    global $combatChainState, $CCS_WeaponIndex;
    switch($cardID)
    {
      case "MON029": case "MON030":
        if(HasIncreasedAttack()) { GiveAttackGoAgain(); $rv = "Boltyn gave the current attack Go Again."; }
        else { $rv = "Boltyn did not give the current attack Go Again."; }
        return $rv;
      case "MON033":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-1");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEMYSOUL", $currentPlayer, "-", 1);
        AddDecisionQueue("BEACONOFVICTORY", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-2", 1);
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        return "";
      case "MON034":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if($myClassState[$CS_NumCharged] > 0)
        {
          for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
          {
            if(CardType($myCharacter[$i]) == "W")
            {
              if($myCharacter[$i+1] != 0) { $myCharacter[$i+1] = 2; ++$myCharacter[$i+5]; }
            }
          }
          $rv = "Lumina Ascension gave each weapon another use this turn.";
        }
        return $rv;
      case "MON036": case "MON037": case "MON038":
        if($myClassState[$CS_NumCharged] > 0) { GiveAttackGoAgain(); $rv = "Battlefield Blitz gained Go Again."; }
        return $rv;
      case "MON054": case "MON055": case "MON056":
        if($myClassState[$CS_NumCharged] > 0) { GiveAttackGoAgain(); $rv = "Take Flight gained Go Again."; }
        return $rv;
      case "MON105":
        if(GetClassState($currentPlayer, $CS_LastAttack) != "MON106") return "";
        AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
        return "Hatchet of Body got +1 attack this turn.";
      case "MON106":
        if(GetClassState($currentPlayer, $CS_LastAttack) != "MON105") return "";
        AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
        return "Hatchet of Mind got +1 attack this turn.";
      case "MON108":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gallantry Gold gives your weapon attacks this turn +1.";
      case "MON109":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Spill Blood gives your axe attacks this turn +2 and Dominate.";
      case "MON110": case "MON111": case "MON112":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Dusk Path Pilgrimage gives your next weapon attack +" . EffectAttackModifier($cardID) . " and lets you attack an additional time if it hits.";
      case "MON113": case "MON114": case "MON115":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Plow through gives your next weapon attack +" . EffectAttackModifier($cardID) . " and gives it +1 when defended by an attack action card.";
      case "MON116": case "MON117": case "MON118":
        if(GetClassState($currentPlayer, $CS_AtksWWeapon) == 0) return "Second Swing did nothing because there were no weapon attacks this turn.";
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Second Swing gives your next attack +" . EffectAttackModifier($cardID) . ".";
      default: return "";
    }
  }

  function MONWarriorHitEffect($cardID)
  {
    global $mainClassState, $CS_NumCharged, $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "MON042": case "MON043": case "MON044":
        if($mainClassState[$CS_NumCharged] > 0) { MainDrawCard(); }
        break;
      case "MON048": case "MON049": case "MON050":
        if($mainClassState[$CS_NumCharged] > 0) { $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; }
        break;
      default: break;
    }
  }

  function LuminaAscensionHit()
  {
    global $mainDeck, $mainPlayer, $mainHealth;
    if(count($mainDeck) == 0) return;
    WriteLog("Lumina Ascension's hit effect revealed " . $mainDeck[0] . ".");
    if(CardTalent($mainDeck[0]) == "LIGHT")
    {
      $cardID = array_shift($mainDeck);
      AddSoul($cardID, $mainPlayer, "DECK");
      PlayerGainHealth(1, $mainHealth);
      WriteLog("The revealed card is Light, so it is added to soul and the main player gains 1 life.");
    }
  }

  function DuskPathPilgrimageHit()
  {
    global $mainCharacter, $combatChainState, $CCS_WeaponIndex;
    if($mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] == 0) return;//Do nothing if it's destroyed
    $mainCharacter[$combatChainState[$CCS_WeaponIndex]+1] = 2;
    ++$mainCharacter[$combatChainState[$CCS_WeaponIndex]+5];
  }

  function Charge()
  {
    global $myHand, $currentPlayer;
    if(count($myHand) == 0) { WriteLog("No cards in hand to Charge."); return; }
    AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
    AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
    AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
    AddDecisionQueue("WRITECARDLOG", $currentPlayer, "This_card_was_charged:_", 1);
    AddDecisionQueue("FINISHCHARGE", $currentPlayer, "This_card_was_charged:_", 1);
  }

  function DQCharge()
  {
    global $myHand, $currentPlayer;
    if(count($myHand) == 0) { WriteLog("No cards in hand to Charge."); return; }
    PrependDecisionQueue("FINISHCHARGE", $currentPlayer, "This_card_was_charged:_", 1);
    PrependDecisionQueue("WRITECARDLOG", $currentPlayer, "This_card_was_charged:_", 1);
    PrependDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
    PrependDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    PrependDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
    PrependDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND");
  }

  function HaveCharged($player)
  {
    global $myClassState, $mainClassState, $CS_NumCharged, $mainPlayerGamestateStillBuilt;
    if($mainPlayerGamestateStillBuilt) return $mainClassState[$CS_NumCharged] > 0;
    else return $myClassState[$CS_NumCharged] > 0;
  }

  function MinervaThemisAbility($player, $index)
  {
    $arsenal = &GetArsenal($player);
    ++$arsenal[$index+3];
    if($arsenal[$index+3] == 3)
    {
      WriteLog("Minerva Themis searched for a specialization card.");
      RemoveArsenal($player, $index);
      BanishCardForPlayer("MON405", $player, "ARS", "-");
      AddDecisionQueue("FINDINDICES", $player, "DECKSPEC");
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
    }
  }

?>

