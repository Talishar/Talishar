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
      default: return "";
    }
  }

  function MONWarriorCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON031": return "Sword";
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
      default: return 3;
    }
  }

  function MONWarriorBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON029": case "MON030": case "MON031": return 0;
      case "MON057": case "MON058": case "MON059": return 2;
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
      default: return 0;
    }
  }

  function MONWarriorPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $myClassState, $CS_NumCharged, $combatChain, $currentPlayer, $mySoul, $myCharacter;
    switch($cardID)
    {
      case "MON029": case "MON030":
        $baseAV = AttackValue($combatChain[0]);
        $currentAttack = 0;
        $currentDefense = 0;
        EvaluateCombatChain($currentAttack, $totalDefense);
        if($currentAttack > $baseAV) { GiveAttackGoAgain(); $rv = "Boltyn gave the current attack Go Again."; }
        else { $rv = "Boltyn did not give the current attack Go Again."; }
        return $rv;
      case "MON033":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-1");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEMYSOUL", $currentPlayer, "-", 1);
        AddDecisionQueue("BEACONOFVICTORY", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-2", 1);
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-", 1);
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
      default: return "";
    }
  }

  function MONWarriorHitEffect($cardID)
  {
    global $mainClassState, $CS_NumCharged, $mainPlayer;
    switch($cardID)
    {
      case "MON042": case "MON043": case "MON044":
        if($mainClassState[$CS_NumCharged] > 0) { MainDrawCard(); }
        break;
      case "MON048": case "MON049": case "MON050":
        if($mainClassState[$CS_NumCharged] > 0) { AddSoul($cardID, $mainPlayer, "CC"); }
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

?>

