<?php

  function EVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "EVR053": return 1;
      case "EVR103": return 0;
      case "EVR137": return 0;
      case "EVR121": return 3;
      case "EVR157": return 1;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      case "EVR177": return 0;
      case "EVR178": return 0;
      case "EVR181": return 0;
      case "EVR183": case "EVR184": case "EVR185": return 0;
      case "EVR187": return 0;
      case "EVR195": return 3;
      default: return 0;
    }
  }

  function EVRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "EVR053": return "AR";
      case "EVR103": return "A";
      case "EVR137": return "I";
      case "EVR121": return "I";
      case "EVR157": return "I";
      case "EVR173": case "EVR174": case "EVR175": return "I";
      case "EVR177": return "I";
      case "EVR178": return "DR";
      case "EVR181": return "I";
      case "EVR183": return "A";
      case "EVR184": case "EVR185": return "I";
      case "EVR187": return "I";
      case "EVR195": return "A";
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR003": return true;
      case "EVR005": case "EVR006": case "EVR007": return true;
      case "EVR014": case "EVR015": case "EVR016": return true;
      case "EVR030": case "EVR031": case "EVR032": return true;
      case "EVR056": return true;
      case "EVR057": case "EVR058": case "EVR059": return true;
      case "EVR082": case "EVR083": case "EVR084": return true;
      case "EVR089": return true;
      case "EVR106": return true;
      case "EVR160": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR167": case "EVR168": case "EVR169": return true;
      case "EVR170": case "EVR171": case "EVR172": return true;
      case "EVR177": case "EVR178": return true;
      case "EVR181": return true;
      case "EVR188": return true;
      case "EVR190": return true;
      case "EVR191": return true;
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR103": return true;
      case "EVR183": return true;
      case "EVR195": return true;
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "EVR001": return 1;
      case "EVR014": case "EVR015": case "EVR016": return 5;
      case "EVR017": return 2;
      case "EVR021": return -4;
      case "EVR057-1": case "EVR058-1": case "EVR059-1": return 1;
      case "EVR057-2": return 3;
      case "EVR058-2": return 2;
      case "EVR059-2": return 1;
      case "EVR082": return 3;
      case "EVR083": return 2;
      case "EVR084": return 1;
      case "EVR160": return -1;
      case "EVR161-2": return 2;
      case "EVR170-2": return 3;
      case "EVR171-2": return 2;
      case "EVR172-2": return 1;
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain, $CS_AtksWWeapon, $mainPlayer;
    switch($cardID)
    {
      case "EVR001": return CardClass($attackID) == "BRUTE";
      case "EVR014": case "EVR015": case "EVR016": return CardType($attackID) == "AA" && CardClass($attackID) == "BRUTE";
      case "EVR017": return CardCost($attackID) >= 3;
      case "EVR019": return HasCrush($attackID);
      case "EVR021": return true;
      case "EVR057-1": case "EVR058-1": case "EVR059-1":
        $subtype = CardSubType($attackID);
        if($subtype != "Sword" && $subtype != "Dagger") return false;
        return CardType($attackID) == "W" && GetClassState($mainPlayer, $CS_AtksWWeapon) == 0;
      case "EVR057-2": case "EVR058-2": case "EVR059-2":
        $subtype = CardSubType($attackID);
        if($subtype != "Sword" && $subtype != "Dagger") return false;
        return CardType($attackID) == "W" && GetClassState($mainPlayer, $CS_AtksWWeapon) == 1;
      case "EVR082": case "EVR083": case "EVR084": return CardType($attackID) == "AA" && CardClass($attackID) == "MECHANOLOGIST";
      case "EVR160": return true;
      case "EVR161-1": case "EVR161-2": case "EVR161-3": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR170-1": case "EVR171-1": case "EVR172-1": return CardType($attackID) == "AA";
      case "EVR170-2": case "EVR171-2": case "EVR172-2": return CardType($attackID) == "AA";
      default: return false;
    }
  }

  function EVRCardType($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return "R";
      case "EVR001": return "E";
      case "EVR002": return "AA";
      case "EVR003": return "A";
      case "EVR005": case "EVR006": case "EVR007": return "A";
      case "EVR011": case "EVR012": case "EVR013": return "AA";
      case "EVR014": case "EVR015": case "EVR016": return "A";
      case "EVR017": return "C";
      case "EVR018": return "E";
      case "EVR019": return "C";
      case "EVR020": return "E";
      case "EVR021": return "AA";
      case "EVR024": case "EVR025": case "EVR026": return "A";
      case "EVR027": case "EVR028": case "EVR029": return "AA";
      case "EVR030": case "EVR031": case "EVR032": return "A";
      case "EVR037": return "E";
      case "EVR053": return "E";
      case "EVR056": return "A";
      case "EVR057": return "A";
      case "EVR063": case "EVR064": case "EVR065": return "AR";
      case "EVR073": case "EVR074": case "EVR075": return "AA";
      case "EVR082": case "EVR083": case "EVR084": return "A";
      case "EVR088": return "AA";
      case "EVR089": return "A";
      case "EVR103": return "E";
      case "EVR106": return "A";
      case "EVR137": return "E";
      case "EVR120": return "C";
      case "EVR121": return "W";
      case "EVR155": return "E";
      case "EVR156": case "EVR157": return "AA";
      case "EVR159": case "EVR160": return "A";
      case "EVR161": case "EVR162": case "EVR163": return "AA";
      case "EVR164": case "EVR165": case "EVR166": return "A";
      case "EVR167": case "EVR168": case "EVR169": return "A";
      case "EVR170": case "EVR171": case "EVR172": return "A";
      case "EVR173": case "EVR174": case "EVR175": return "I";
      case "EVR177": return "A";
      case "EVR178": return "A";
      case "EVR181": return "A";
      case "EVR183": case "EVR184": case "EVR185": return "A";
      case "EVR187": return "A";
      case "EVR188": return "A";
      case "EVR190": return "A";
      case "EVR191": return "A";
      case "EVR195": return "T";
      default: return "";
    }
  }

  function EVRCardSubtype($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return "Gem";
      case "EVR001": return "Arms";
      case "EVR018": return "Off-Hand";
      case "EVR020": return "Chest";
      case "EVR037": return "Head";
      case "EVR053": return "Head";
      case "EVR088": return "Arrow";
      case "EVR103": return "Arms";
      case "EVR121": return "Staff";
      case "EVR137": return "Head";
      case "EVR155": return "Off-Hand";
      case "EVR177": case "EVR178": case "EVR181": case "EVR183": case "EVR184": case "EVR185": case "EVR187": case "EVR188": case "EVR190": case "EVR191": return "Item";
      case "EVR195": return "Item";
      default: return "";
    }
  }

  function EVRCardCost($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return -1;
      case "EVR001": return 0;
      case "EVR002": return 2;
      case "EVR003": return 0;
      case "EVR005": case "EVR006": case "EVR007": return 0;
      case "EVR011": case "EVR012": case "EVR013": return 2;
      case "EVR014": case "EVR015": case "EVR016": return 0;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR020": return 0;
      case "EVR021": return 10;
      case "EVR024": case "EVR025": case "EVR026": return 6;
      case "EVR027": case "EVR028": case "EVR029": return 7;
      case "EVR030": case "EVR031": case "EVR032": return 2;
      case "EVR037": return 0;
      case "EVR053": return 0;
      case "EVR056": return 0;
      case "EVR057": return 0;
      case "EVR063": case "EVR064": case "EVR065": return 0;
      case "EVR073": case "EVR074": case "EVR075": return 0;
      case "EVR082": case "EVR083": case "EVR084": return 0;
      case "EVR088": return 2;
      case "EVR089": return 0;
      case "EVR103": return 0;
      case "EVR106": return 0;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR137": return 0;
      case "EVR155": return 0;
      case "EVR156": return 1;
      case "EVR157": return 2;
      case "EVR159": return 3;
      case "EVR160": return 1;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR164": case "EVR165": case "EVR166": return 0;
      case "EVR167": case "EVR168": case "EVR169": return 0;
      case "EVR170": case "EVR171": case "EVR172": return 0;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      case "EVR177": case "EVR178": return 0;
      case "EVR181": return 0;
      case "EVR183": case "EVR184": case "EVR185": return 0;
      case "EVR187": return 0;
      case "EVR188": return 0;
      case "EVR190": case "EVR191": return 0;
      case "EVR195": return 0;
      default: return 0;
    }
  }

  function EVRPitchValue($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return 3;
      case "EVR001": return 0;
      case "EVR002": return 1;
      case "EVR003": return 3;
      case "EVR005": return 1;
      case "EVR006": return 2;
      case "EVR007": return 3;
      case "EVR011": case "EVR014": return 1;
      case "EVR012": case "EVR015": return 2;
      case "EVR013": case "EVR016": return 3;
      case "EVR017": return 0;
      case "EVR018": return 0;
      case "EVR019": return 0;
      case "EVR020": return 0;
      case "EVR021": return 1;
      case "EVR024": case "EVR027": case "EVR030": return 1;
      case "EVR025": case "EVR028": case "EVR031": return 2;
      case "EVR026": case "EVR029": case "EVR032": return 3;
      case "EVR037": return 0;
      case "EVR053": return 0;
      case "EVR056": return 1;
      case "EVR057": case "EVR063": return 1;
      case "EVR058": case "EVR064": return 2;
      case "EVR059": case "EVR065": return 3;
      case "EVR073": case "EVR082": return 1;
      case "EVR074": case "EVR083": return 2;
      case "EVR075": case "EVR084": return 3;
      case "EVR088": return 1;
      case "EVR089": return 3;
      case "EVR103": return 0;
      case "EVR106": return 1;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR137": return 0;
      case "EVR155": return 0;
      case "EVR156": case "EVR157": return 1;
      case "EVR159": return 1;
      case "EVR160": return 3;
      case "EVR161": case "EVR164": case "EVR167": case "EVR170": return 1;
      case "EVR162": case "EVR165": case "EVR168": case "EVR171": return 2;
      case "EVR163": case "EVR166": case "EVR169": case "EVR172": return 3;
      case "EVR173": return 1;
      case "EVR174": return 2;
      case "EVR175": return 3;
      case "EVR177": case "EVR178": return 3;
      case "EVR181": return 3;
      case "EVR183": case "EVR184": case "EVR185": return 3;
      case "EVR187": return 3;
      case "EVR188": return 3;
      case "EVR190": case "EVR191": return 2;
      case "EVR195": return 0;
      default: return 3;
    }
  }

  function EVRBlockValue($cardID)
  {
    switch($cardID)
    {
      case "EVR000": return -1;
      case "EVR001": return 1;
      case "EVR011": case "EVR012": case "EVR013": return -1;
      case "EVR017": return 0;
      case "EVR018": return 2;
      case "EVR019": return 0;
      case "EVR020": return 2;
      case "EVR037": return 2;
      case "EVR053": return 1;
      case "EVR103": return 0;
      case "EVR106": return 2;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR137": return 0;
      case "EVR155": return -1;
      case "EVR156": case "EVR157": return 3;
      case "EVR159": return 2;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR164": case "EVR165": case "EVR166": return 2;
      case "EVR167": case "EVR168": case "EVR169": return 2;
      case "EVR170": case "EVR171": case "EVR172": return 2;
      case "EVR173": case "EVR174": case "EVR175": return -1;
      case "EVR177": case "EVR178": return -1;
      case "EVR181": return -1;
      case "EVR183": case "EVR184": case "EVR185": return -1;
      case "EVR187": return -1;
      case "EVR188": return -1;
      case "EVR190": case "EVR191": return -1;
      case "EVR195": return -1;
      default: return 3;
    }
  }

  function EVRAttackValue($cardID)
  {
    switch($cardID)
    {
      case "EVR002": return 8;
      case "EVR011": return 6;
      case "EVR012": return 5;
      case "EVR013": return 4;
      case "EVR021": return 14;
      case "EVR024": case "EVR027": return 10;
      case "EVR025": case "EVR028": return 9;
      case "EVR026": case "EVR029": return 8;
      case "EVR073": return 3;
      case "EVR074": return 2;
      case "EVR075": return 1;
      case "EVR088": return 6;
      case "EVR156": return 5;
      case "EVR157": return 3;
      case "EVR161": return 4;
      case "EVR162": return 3;
      case "EVR163": return 2;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      default: return 0;
    }
  }

  function EVRPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $combatChain, $CS_PlayIndex, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    global $CS_HighestRoll, $CS_NumNonAttackCards, $CS_NumAttackCards;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "EVR003":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Ready to Roll lets you roll an extra die this turn.";
      case "EVR005": case "EVR006": case "EVR007":
        $rv = "High Roller Intimidated";
        if($cardID == "EVR005") $targetHigh = 4;
        else if($cardID == "EVR006") $targetHigh = 5;
        else if($cardID == "EVR007") $targetHigh = 6;
        if(GetClassState($currentPlayer, $CS_HighestRoll) >= $targetHigh)
        {
          Intimidate();
          $rv .= " twice";
        }
        return $rv . ".";
      case "EVR011": case "EVR012": case "EVR013":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Wild Ride discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          GiveAttackGoAgain();
          $rv .= " and got Go Again from discarding a card with 6 or more power";
        }
        $rv .= ".";
        return $rv;
      case "EVR014": case "EVR015": case "EVR016":
        $rv = "Bad Beats - Did nothing.";
        if($cardID == "EVR014") $target = 4;
        else if($cardID == "EVR015") $target = 5;
        else $target = 6;
        $roll = GetDieRoll($currentPlayer);
        if($roll >= $target)
        {
          $rv = "Bad Beats gives the next Brute attack action card +5.";
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return $rv;
      case "EVR030": case "EVR031": case "EVR032":
        if($cardID == "EVR030") $amount = 3;
        else if($cardID == "EVR031") $amount = 2;
        else $amount = 1;
        PlayAura("WTR075", $currentPlayer, $amount);
        return "Seismic Stir created " . $amount . " Seismic Surge tokens.";
      case "EVR053":
        $deck = &GetDeck($currentPlayer);
        $card = array_shift($deck);
        BanishCardForPlayer($deck[0], $currentPlayer, "DECK", "TCC");
        return "Helm of the Sharp Eye banished a card. It is playable to this combat chain.";
      case "EVR056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Oath of Steel gives your weapon +1 each time you attack this turn, but loses all counters at end of turn.";
      case "EVR057": case "EVR058": case "EVR059":
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
        return "";
      case "EVR073": case "EVR074": case "EVR075":
        return "T-Bone is a partially manual card. If you have a boosted card on the combat chain, the opponent must block with an equipment if possible.";
      case "EVR082": case "EVR083": case "EVR084":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR089":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON,Bow");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDMZUSES", $currentPlayer, 2, 1);
        return "Tri-shot gives your bow 2 additional uses.";
      case "EVR103":
        PlayAura("ARC112", $currentPlayer, 2);
        return "Vexing Quillhand created two Runechant tokens.";
      case "EVR106":
        $rv = "";
        if(GetClassState($currentPlayer, $CS_NumNonAttackCards) > 1 && GetClassState($currentPlayer, $CS_NumAttackCards) > 0)
        {
          PlayAura("ARC112", $currentPlayer, 4);
          $rv = "Revel in Runeblood created 4 Runechants.";
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return $rv;
      case "EVR121":
        DealArcane(1, 1, "ABILITY", $cardID);
        AddDecisionQueue("KRAKENAETHERVEIN", $currentPlayer, "-");
        return "";
      case "EVR137":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may choose an Illusionist Aura to destroy and replace.");
        AddDecisionQueue("FINDINDICES", $currentPlayer, "AURACLASS,ILLUSIONIST");
        AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "MYAURAS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CROWNOFREFLECTION", 1);
        AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "<-", 1);
        return "Crown of Reflection let you destroy an aura and play a new one.";
      case "EVR157":
        $rv = "";
        if($from == "PLAY")
        {
          $rv = "Firebreathing gained +1.";
          ++$combatChain[5];
        }
        return $rv;
      case "EVR160":
        Draw(1);
        Draw(2);
        AddNextTurnEffect($cardID, $otherPlayer);
        return "This Round's on Me drew a card for each player and gave attacks targeting you -1.";
      case "EVR161": case "EVR162": case "EVR163":
        $rand = rand(1, 3);
        if($resourcesPaid == 0 || $rand == 1) { WriteLog("Gain +2 life on hit."); AddCurrentTurnEffect("EVR161-1", $currentPlayer); }
        if($resourcesPaid == 0 || $rand == 2) { WriteLog("Gained +2 attack."); AddCurrentTurnEffect("EVR161-2", $currentPlayer); }
        if($resourcesPaid == 0 || $rand == 3) { WriteLog("Gained Go Again."); AddCurrentTurnEffect("EVR161-3", $currentPlayer); }
        return ($resourcesPaid == 0 ? "Party time!" : "");
      case "EVR164": case "EVR165": case "EVR166":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVR167": case "EVR168": case "EVR169":
        if($cardID == "EVR167") $times = 4;
        else if($cardID == "EVR168") $times = 3;
        else if($cardID == "EVR169") $times = 2;
        LookAtHand($otherPlayer);
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        for($i=0; $i<$times; ++$i)
        {
          AddDecisionQueue("PICKACARD", $currentPlayer, "-", 1);
        }
        return "";
      case "EVR170": case "EVR171": case "EVR172":
        $rv = "Smashing Good Time makes your next attack action that hits destroy an item";
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        if($from == "ARS")
        {
          AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
          $rv .= " and gives your next attack action card +" . EffectAttackModifier($cardID . "-2") . ".";
        }
        else { $rv .= "."; }
        return $rv;
      case "EVR173": case "EVR174": case "EVR175":
        if($cardID == "EVR173") $opt = 3;
        else if($cardID == "EVR174") $opt = 2;
        else if($cardID == "EVR175") $opt = 1;
        Opt($cardID, $opt);
        AddDecisionQueue("EVENBIGGERTHANTHAT", $currentPlayer, "-");
        return "";
      case "EVR177":
        $rv = "";
        if($from == "PLAY")
        {
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          PummelHit($otherPlayer);
          PummelHit($otherPlayer);
        }
        else
        {
          $rv = "Amulet of Echoes is a partially manually card. Only activate the ability when the target player has played two or more cards with the same name this turn.";
        }
        return "";
      case "EVR178":
        if($from == "PLAY")
        {
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          AddDecisionQueue("FINDINDICES", $currentPlayer, "EVR178");
          AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDCARDTOCHAIN", $currentPlayer, "DECK", 1);
        }
        return "";
      case "EVR181":
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        }
        return "Healing Potion gained 2 health.";
      case "EVR183":
        if($from == "PLAY"){
          GainHealth(2, $currentPlayer);
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        }
        return "Healing Potion gained 2 health.";
      case "EVR184":
        $rv = "";
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          LookAtHand($otherPlayer);
          $rv = "Potion of Seeing revealed the opponent's hand.";
        }
        return $rv;
      case "EVR185":
        $rv = "";
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          $cards = "";
          $pitch = &GetPitch($currentPlayer);
          while(count($pitch) > 0)
          {
            if($cards != "") $cards .= ",";
            $cards .= array_shift($pitch);
            for($i=1; $i<PitchPieces(); ++$i) array_shift($pitch);
          }
          if($cards != "") AddDecisionQueue("CHOOSETOP", $currentPlayer, $cards);
          $rv = "Potion of Deja Vu put your pitch cards on top of your deck.";
        }
        return $rv;
      case "EVR187":
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          AddDecisionQueue("POTIONOFLUCK", $currentPlayer, "-", 1);
        }
        return "";
      case "EVR195":
        $rv = "";
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          $rv = "Silver drew a card.";
          Draw($currentPlayer);
        }
        return $rv;
      default: return "";
    }
  }

  function EVRHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "EVR021":
        AddNextTurnEffect($cardID, $defPlayer);
        break;
      case "EVR088":
        $hand = &GetHand($defPlayer);
        $cards = "";
        $numDiscarded = 0;
        for($i=count($hand)-HandPieces(); $i>=0; $i-=HandPieces())
        {
          $id = $hand[$i];
          $cardType = CardType($id);
          if($cardType != "A" && $cardType != "AA")
          {
            AddGraveyard($id, $defPlayer, "HAND");
            unset($hand[$i]);
            ++$numDiscarded;
          }
          if($cards != "") $cards .= ",";
          $cards .= $id;
        }
        LoseHealth($numDiscarded, $defPlayer);
        RevealCards($cards);
        WriteLog("Battering Bolt discarded " . $numDiscarded . " and caused the defending player to lose that much health.");
        $hand = array_values($hand);
        break;
      case "EVR156":
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
        AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
        AddDecisionQueue("HANDCARD", $defPlayer, "-", 1);
        AddDecisionQueue("REVEALCARD", $defPlayer, "-", 1);
        AddDecisionQueue("BINGO", $mainPlayer, "-", 1);
        break;
      default: break;
    }
  }

  function HeaveValue($cardID)
  {
    switch($cardID)
    {
      case "EVR021": return 3;
      case "EVR024": case "EVR025": case "EVR026": return 3;
      default: return 0;
    }
  }

  function HeaveIndices()
  {
    global $mainPlayer;
    if(ArsenalFull($mainPlayer)) return "";//Heave does nothing if arsenal is full
    $hand = &GetHand($mainPlayer);
    $heaveIndices = "";
    for($i=0; $i<count($hand); $i+=HandPieces())
    {
      if(HeaveValue($hand[$i]) > 0)
      {
        if($heaveIndices != "") $heaveIndices .= ",";
        $heaveIndices .= $i;
      }
    }
    return $heaveIndices;
  }

  function Heave()
  {
    global $mainPlayer;
    AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may choose to Heave a card or pass.");
    AddDecisionQueue("FINDINDICES", $mainPlayer, "HEAVE");
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1, 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("HEAVE", $mainPlayer, "-", 1);
  }

  function HelmOfSharpEyePlayable()
  {
    global $currentPlayer;
    $character = &GetPlayerCharacter($currentPlayer);
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if(cardType($character[$i]) != "W") continue;
      $baseAttack = AttackValue($character[$i]);
      $buffedAttack = $baseAttack + $character[$i+3] + MainCharacterAttackModifiers($i, true);
      if($buffedAttack > $baseAttack*2) return true;
    }
    return false;
  }

  function BravoStarOfTheShowIndices()
  {
    global $mainPlayer;
    $earth = SearchHand($mainPlayer, "", "", -1, -1, "", "EARTH");
    $ice = SearchHand($mainPlayer, "", "", -1, -1, "", "ICE");
    $lightning = SearchHand($mainPlayer, "", "", -1, -1, "", "LIGHTNING");
    if($earth != "" && $ice != "" && $lightning != "")
    {
      $indices = CombineSearches($earth, $ice);
      $indices = CombineSearches($indices, $lightning);
      $count = SearchCount($indices);
      if($count > 3) $count = 3;
      return $count . "-" . SearchRemoveDuplicates($indices);
    }
    return "";
  }

  //Returns true if it should be destroyed
  function TalismanOfBalanceEndTurn()
  {
    global $mainPlayer, $defPlayer;
    if(ArsenalFull($mainPlayer)) return false;
    $mainArs = &GetArsenal($mainPlayer);
    $defArs = &GetArsenal($defPlayer);
    if(count($mainArs) < count($defArs))
    {
      $deck = &GetDeck($mainPlayer);
      $card = array_shift($deck);
      AddArsenal($card, $mainPlayer, "DECK", "DOWN");
      WriteLog("Talisman of Balance destroyed itself and put a card in your arsenal.");
      return true;
    }
    return false;
  }

  function LifeOfThePartyIndices()
  {
    global $currentPlayer;
    $auras = SearchMultizoneFormat(SearchItemsForCard("WTR162", $currentPlayer), "MYITEMS");
    $handCards = SearchMultizoneFormat(SearchHandForCard($currentPlayer, "WTR162"), "MYHAND");
    return CombineSearches($auras, $handCards);
  }

?>
