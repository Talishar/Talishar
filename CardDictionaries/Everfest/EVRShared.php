<?php

  function EVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "EVR121": return 3;
      case "EVR157": return 1;
      case "EVR173": case "EVR174": case "EVR175": return 0;
      case "EVR178": return 0;
      case "EVR187": return 0;
      default: return 0;
    }
  }

  function EVRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "EVR121": return "I";
      case "EVR157": return "I";
      case "EVR173": case "EVR174": case "EVR175": return "I";
      case "EVR178": return "DR";
      case "EVR187": return "I";
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR160": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      case "EVR167": case "EVR168": case "EVR169": return true;
      case "EVR170": case "EVR171": case "EVR172": return true;
      case "EVR178": return true;
      case "EVR190": return true;
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "EVR160": return -1;
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain;
    switch($cardID)
    {
      case "EVR019": return HasCrush($attackID);
      case "EVR160": return true;
      case "EVR164": case "EVR165": case "EVR166": return true;
      default: return false;
    }
  }

  function EVRCardType($cardID)
  {
    switch($cardID)
    {
      case "EVR011": case "EVR012": case "EVR013": return "AA";
      case "EVR017": return "C";
      case "EVR019": return "C";
      case "EVR027": case "EVR028": case "EVR029": return "AA";
      case "EVR063": case "EVR064": case "EVR065": return "AR";
      case "EVR088": return "AA";
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
      case "EVR178": return "A";
      case "EVR187": return "A";
      case "EVR190": return "A";
      default: return "";
    }
  }

  function EVRCardSubtype($cardID)
  {
    switch($cardID)
    {
      case "EVR088": return "Arrow";
      case "EVR121": return "Staff";
      case "EVR155": return "Off-Hand";
      case "EVR178": case "EVR187": case "EVR190": return "Item";
      default: return "";
    }
  }

  function EVRCardCost($cardID)
  {
    switch($cardID)
    {
      case "EVR011": case "EVR012": case "EVR013": return 2;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR027": case "EVR028": case "EVR029": return 7;
      case "EVR063": case "EVR064": case "EVR065": return 0;
      case "EVR088": return 2;
      case "EVR120": return 0;
      case "EVR121": return 0;
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
      case "EVR178": return 0;
      case "EVR187": return 0;
      case "EVR190": return 0;
      default: return 0;
    }
  }

  function EVRPitchValue($cardID)
  {
    switch($cardID)
    {
      case "EVR011": return 1;
      case "EVR012": return 2;
      case "EVR013": return 3;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR027": return 1;
      case "EVR028": return 2;
      case "EVR029": return 3;
      case "EVR063": return 1;
      case "EVR064": return 2;
      case "EVR065": return 3;
      case "EVR088": return 1;
      case "EVR120": return 0;
      case "EVR121": return 0;
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
      case "EVR178": return 3;
      case "EVR187": return 3;
      case "EVR190": return 2;
      default: return 3;
    }
  }

  function EVRBlockValue($cardID)
  {
    switch($cardID)
    {
      case "EVR011": case "EVR012": case "EVR013": return -1;
      case "EVR017": return 0;
      case "EVR019": return 0;
      case "EVR120": return 0;
      case "EVR121": return 0;
      case "EVR155": return -1;
      case "EVR156": case "EVR157": return 3;
      case "EVR159": return 2;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR164": case "EVR165": case "EVR166": return 2;
      case "EVR167": case "EVR168": case "EVR169": return 2;
      case "EVR170": case "EVR171": case "EVR172": return 2;
      case "EVR173": case "EVR174": case "EVR175": return -1;
      case "EVR178": return -1;
      case "EVR187": return -1;
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
    global $currentPlayer, $combatChain, $CS_PlayIndex;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
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
      case "EVR121":
        DealArcane(1, 1, "ABILITY", $cardID);
        AddDecisionQueue("KRAKENAETHERVEIN", $currentPlayer, "-");
        return "";
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
      case "EVR173": case "EVR174": case "EVR175":
        if($cardID == "EVR173") $opt = 3;
        else if($cardID == "EVR174") $opt = 2;
        else if($cardID == "EVR175") $opt = 1;
        Opt($cardID, $opt);
        AddDecisionQueue("EVENBIGGERTHANTHAT", $currentPlayer, "-");
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
      default: return "";
    }
  }

  function EVRHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
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


?>
