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
      case "EVR183": return 0;
      case "EVR187": return 0;
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
      case "EVR187": return "I";
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR003": return true;
      case "EVR005": case "EVR006": case "EVR007": return true;
      case "EVR056": return true;
      case "EVR160": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR167": case "EVR168": case "EVR169": return true;
      case "EVR170": case "EVR171": case "EVR172": return true;
      case "EVR177": case "EVR178": return true;
      case "EVR181": return true;
      case "EVR188": return true;
      case "EVR190": return true;
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR103": return true;
      case "EVR183": return true;
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "EVR017": return 2;
      case "EVR021": return -4;
      case "EVR160": return -1;
      case "EVR170-2": return 3;
      case "EVR171-2": return 2;
      case "EVR172-2": return 1;
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain;
    switch($cardID)
    {
      case "EVR017": return CardCost($attackID) >= 3;
      case "EVR019": return HasCrush($attackID);
      case "EVR021": return true;
      case "EVR160": return true;
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
      case "EVR001": return "E";
      case "EVR003": return "A";
      case "EVR005": case "EVR006": case "EVR007": return "A";
      case "EVR011": case "EVR012": case "EVR013": return "AA";
      case "EVR017": return "C";
      case "EVR019": return "C";
      case "EVR021": return "AA";
      case "EVR027": case "EVR028": case "EVR029": return "AA";
      case "EVR053": return "E";
      case "EVR056": return "A";
      case "EVR063": case "EVR064": case "EVR065": return "AR";
      case "EVR088": return "AA";
      case "EVR103": return "E";
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
      case "EVR183": return "A";
      case "EVR187": return "A";
      case "EVR188": return "A";
      case "EVR190": return "A";
      default: return "";
    }
  }

  function EVRCardSubtype($cardID)
  {
    switch($cardID)
    {
      case "EVR003": return "Arms";
      case "EVR053": return "Head";
      case "EVR088": return "Arrow";
      case "EVR103": return "Arms";
      case "EVR121": return "Staff";
      case "EVR137": return "Head";
      case "EVR155": return "Off-Hand";
      case "EVR177": case "EVR178": case "EVR181": case "EVR183": case "EVR187": case "EVR188": case "EVR190": return "Item";
      default: return "";
    }
  }

  function EVRCardCost($cardID)
  {
    switch($cardID)
    {
      case "EVR001": return 0;
      case "EVR003": return 0;
      case "EVR005": case "EVR006": case "EVR007": return 0;
      case "EVR011": case "EVR012": case "EVR013": return 2;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR021": return 10;
      case "EVR027": case "EVR028": case "EVR029": return 7;
      case "EVR053": return 0;
      case "EVR056": return 0;
      case "EVR063": case "EVR064": case "EVR065": return 0;
      case "EVR088": return 2;
      case "EVR103": return 0;
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
      case "EVR183": return 0;
      case "EVR187": return 0;
      case "EVR188": return 0;
      case "EVR190": return 0;
      default: return 0;
    }
  }

  function EVRPitchValue($cardID)
  {
    switch($cardID)
    {
      case "EVR001": return 0;
      case "EVR003": return 3;
      case "EVR005": return 1;
      case "EVR006": return 2;
      case "EVR007": return 3;
      case "EVR011": return 1;
      case "EVR012": return 2;
      case "EVR013": return 3;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR021": return 1;
      case "EVR027": return 1;
      case "EVR028": return 2;
      case "EVR029": return 3;
      case "EVR053": return 0;
      case "EVR056": return 1;
      case "EVR063": return 1;
      case "EVR064": return 2;
      case "EVR065": return 3;
      case "EVR088": return 1;
      case "EVR103": return 0;
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
      case "EVR183": return 3;
      case "EVR187": return 3;
      case "EVR188": return 3;
      case "EVR190": return 2;
      default: return 3;
    }
  }

  function EVRBlockValue($cardID)
  {
    switch($cardID)
    {
      case "EVR001": return 1;
      case "EVR011": case "EVR012": case "EVR013": return -1;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR053": return 1;
      case "EVR103": return 0;
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
      case "EVR183": return -1;
      case "EVR187": return -1;
      case "EVR188": return -1;
      case "EVR190": return -1;
      default: return 3;
    }
  }

  function EVRAttackValue($cardID)
  {
    switch($cardID)
    {
      case "EVR011": return 6;
      case "EVR012": return 5;
      case "EVR013": return 4;
      case "EVR021": return 14;
      case "EVR027": return 10;
      case "EVR028": return 9;
      case "EVR029": return 8;
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
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "EVR003":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Ready to Roll lets you roll an extra die this turn.";
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
      case "EVR053":
        $deck = &GetDeck($currentPlayer);
        $card = array_shift($deck);
        BanishCardForPlayer($deck[0], $currentPlayer, "DECK", "TCC");
        return "Helm of the Sharp Eye banished a card. It is playable to this combat chain.";
      case "EVR056":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Oath of Steel gives your weapon +1 each time you attack this turn, but loses all counters at end of turn.";
      case "EVR103":
        PlayAura("ARC112", $currentPlayer, 2);
        return "Vexing Quillhand created two Runechant tokens.";
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
      case "EVR164": case "EVR165": case "EVR166":
        AddCurrentTurnEffect($cardID, $currentPlayer);
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
      case "EVR187":
        if($from == "PLAY"){
          DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
          AddDecisionQueue("POTIONOFLUCK", $currentPlayer, "-", 1);
        }
        return "";
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

?>
