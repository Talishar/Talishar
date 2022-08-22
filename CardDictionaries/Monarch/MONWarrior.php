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
      case "MON029": case "MON030": case "MON031": return -1;
      case "MON057": case "MON058": case "MON059": return 2;
      case "MON105": case "MON106": return -1;
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
    global $CS_NumCharged, $currentPlayer, $CS_AtksWWeapon, $CS_LastAttack;
    global $combatChainState, $CCS_WeaponIndex;
    $rv = "";
    switch($cardID)
    {
      case "MON029": case "MON030":
        if(HasIncreasedAttack()) { GiveAttackGoAgain(); $rv = "Gives the current attack go again."; }
        else { $rv = "Does not give the current attack go again."; }
        return $rv;
      case "MON033":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-1");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEMYSOUL", $currentPlayer, "-", 1);
        AddDecisionQueue("BEACONOFVICTORY", $currentPlayer, "-", 1);
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-2", 1);
          AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
          AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        }
        return "";
      case "MON034":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $character = &GetPlayerCharacter($currentPlayer);
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0)
        {
          for($i=0; $i<count($character); $i+=CharacterPieces())
          {
            if(CardType($character[$i]) == "W")
            {
              if($character[$i+1] != 0) { $character[$i+1] = 2; ++$character[$i+5]; }
            }
          }
          $rv = "Gives each weapon an additional use this turn.";
        }
        return $rv;
      case "MON036": case "MON037": case "MON038":
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0) { GiveAttackGoAgain(); $rv = "Gains go again."; }
        return $rv;
      case "MON054": case "MON055": case "MON056":
        if(GetClassState($currentPlayer, $CS_NumCharged) > 0) { GiveAttackGoAgain(); $rv = "Gains go again."; }
        return $rv;
      case "MON105":
        if(GetClassState($currentPlayer, $CS_LastAttack) != "MON106") return "";
        AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
        return "Gains +1 power until end of turn.";
      case "MON106":
        if(GetClassState($currentPlayer, $CS_LastAttack) != "MON105") return "";
        AddCharacterEffect($currentPlayer, $combatChainState[$CCS_WeaponIndex], $cardID);
        return "Gains +1 power until end of turn.";
      case "MON108":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your weapon attacks this turn +1.";
      case "MON109":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your axe attacks this turn +2 and Dominate.";
      case "MON110": case "MON111": case "MON112":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and lets you attack an additional time if it hits.";
      case "MON113": case "MON114": case "MON115":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your next weapon attack +" . EffectAttackModifier($cardID) . " and gives it +1 when defended by an attack action card.";
      case "MON116": case "MON117": case "MON118":
        if(GetClassState($currentPlayer, $CS_AtksWWeapon) == 0) return "Does nothing because there were no weapon attacks this turn.";
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives your next attack +" . EffectAttackModifier($cardID) . ".";
      default: return "";
    }
  }

  function MONWarriorHitEffect($cardID)
  {
    global $mainClassState, $CS_NumCharged, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
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
    global $mainPlayer;
    $deck = &GetDeck($mainPlayer);
    if(count($deck) == 0) return;
    $cardID = array_shift($deck);
    WriteLog(CardLink("MON034", "MON034") ."'s hit effect:");
    if(!RevealCards($cardID, $mainPlayer)) return;
    if(TalentContains($cardID, "LIGHT", $mainPlayer))
    {
      AddSoul($cardID, $mainPlayer, "DECK");
      GainHealth(1, $mainPlayer);
      WriteLog("It's a Light card, so it goes in the soul and gain 1 health.");
    }
    else
    {
      array_push($deck, $cardID);
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
    global $currentPlayer;
    $hand = &GetHand($currentPlayer);
    if(count($hand) == 0) { WriteLog("No cards in hand to charge."); return; }
    AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
    AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
    AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
    AddDecisionQueue("WRITECARDLOG", $currentPlayer, "This_card_was_charged:_", 1);
    AddDecisionQueue("FINISHCHARGE", $currentPlayer, "This_card_was_charged:_", 1);
  }

  function DQCharge()
  {
    global $currentPlayer;
    $hand = &GetHand($currentPlayer);
    if(count($hand) == 0) { WriteLog("No cards in hand to charge."); return; }
    PrependDecisionQueue("FINISHCHARGE", $currentPlayer, "This_card_was_charged:_", 1);
    PrependDecisionQueue("WRITECARDLOG", $currentPlayer, "This_card_was_charged:_", 1);
    PrependDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
    PrependDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    PrependDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
    PrependDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
  }

  function HaveCharged($player)
  {
    global $CS_NumCharged;
    return GetClassState($player, $CS_NumCharged) > 0;
  }

  function MinervaThemisAbility($player, $index)
  {
    $arsenal = &GetArsenal($player);
    ++$arsenal[$index+3];
    if($arsenal[$index+3] == 3)
    {
      WriteLog(CardLink("MON405", "MON405") . " searched for a specialization card.");
      RemoveArsenal($player, $index);
      BanishCardForPlayer("MON405", $player, "ARS", "-");
      AddDecisionQueue("FINDINDICES", $player, "DECKSPEC");
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
      AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
    }
  }

?>
