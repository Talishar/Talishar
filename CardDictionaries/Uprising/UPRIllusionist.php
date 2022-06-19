<?php


  function UPRIllusionistCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return "C";
      case "UPR003": return "W";
      case "UPR004": return "E";
      case "UPR005": return "A";
      case "UPR006": return "A";
      case "UPR007": return "A";
      case "UPR008": return "A";
      case "UPR009": return "A";
      case "UPR010": return "A";
      case "UPR011": return "A";
      case "UPR012": return "A";
      case "UPR013": return "A";
      case "UPR014": return "A";
      case "UPR015": return "A";
      case "UPR016": return "A";
      case "UPR017": return "A";
      case "UPR018": case "UPR019": case "UPR020": return "AA";
      case "UPR021": case "UPR022": case "UPR023": return "AA";
      case "UPR024": case "UPR025": case "UPR026": return "AA";
      case "UPR027": case "UPR028": case "UPR029": return "AA";
      case "UPR030": case "UPR031": case "UPR032": return "AA";
      case "UPR033": case "UPR034": case "UPR035": return "A";
      case "UPR036": case "UPR037": case "UPR038": return "A";
      case "UPR039": case "UPR040": case "UPR041": return "I";
      case "UPR042": case "UPR043": return "T";
      case "UPR151": return "E";
      case "UPR152": return "E";
      case "UPR153": return "AA";
      case "UPR155": case "UPR156": case "UPR157": return "A";
      case "UPR154": return "I";
      case "UPR406": return "-";
      case "UPR407": return "-";
      case "UPR408": return "-";
      case "UPR409": return "-";
      case "UPR410": return "-";
      case "UPR411": return "-";
      case "UPR412": return "-";
      case "UPR413": return "-";
      case "UPR414": return "-";
      case "UPR415": return "-";
      case "UPR416": return "-";
      case "UPR417": return "-";
      case "UPR439": return "-";
      case "UPR440": return "-";
      case "UPR441": return "-";
      case "UPR551": return "-";
      default: return "";
    }
  }

  function UPRIllusionistCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR006": case "UPR007": case "UPR009": case "UPR010": case "UPR011": case "UPR012": case "UPR013": case "UPR014": case "UPR015": case "UPR016": case "UPR017": return "Invocation";
      case "UPR003": return "Scepter";
      case "UPR004": return "Arms";
      case "UPR005": return "Aura";
      case "UPR042": return "Dragon,Ally";
      case "UPR043": return "Ash";
      case "UPR151": return "Arms";
      case "UPR152": return "Legs";
      case "UPR406": return "Dragon,Ally";
      case "UPR407": return "Dragon,Ally";
      case "UPR408": return "Dragon,Ally";
      case "UPR409": return "Dragon,Ally";
      case "UPR410": return "Dragon,Ally";
      case "UPR411": return "Dragon,Ally";
      case "UPR412": return "Dragon,Ally";
      case "UPR413": return "Dragon,Ally";
      case "UPR414": return "Dragon,Ally";
      case "UPR415": return "Dragon,Ally";
      case "UPR416": return "Dragon,Ally";
      case "UPR417": return "Dragon,Ally";
      case "UPR439": case "UPR440": case "UPR441": return "Ash";
      case "UPR551": return "Ally";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRIllusionistCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return -1;
      case "UPR004": return -1;
      case "UPR005": return 0;
      case "UPR006": return 6;
      case "UPR007": return 5;
      case "UPR008": return 4;
      case "UPR009": return 0;
      case "UPR010": return 0;
      case "UPR011": return 1;
      case "UPR012": return 1;
      case "UPR013": return 3;
      case "UPR014": return 2;
      case "UPR015": return 2;
      case "UPR016": return 3;
      case "UPR017": return 1;
      case "UPR018": case "UPR019": case "UPR020": return 1;
      case "UPR021": case "UPR022": case "UPR023": return 1;
      case "UPR024": case "UPR025": case "UPR026": return 0;
      case "UPR027": case "UPR028": case "UPR029": return 2;
      case "UPR030": case "UPR031": case "UPR032": return 1;
      case "UPR033": case "UPR034": case "UPR035": return 1;
      case "UPR036": case "UPR037": case "UPR038": return 0;
      case "UPR039": case "UPR040": case "UPR041": return 0;
      case "UPR042": case "UPR043": return -1;
      case "UPR153": return 3;
      case "UPR154": return 3;
      case "UPR155": case "UPR156": case "UPR157": return 1;
      default: return 0;
    }
  }

  function UPRIllusionistPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR005": return 1;
      case "UPR006": return 1;
      case "UPR007": return 1;
      case "UPR008": return 1;
      case "UPR009": return 1;
      case "UPR010": return 1;
      case "UPR011": return 1;
      case "UPR012": return 1;
      case "UPR013": return 1;
      case "UPR014": return 1;
      case "UPR015": return 1;
      case "UPR016": return 1;
      case "UPR017": return 1;
      case "UPR018": case "UPR021": case "UPR024": case "UPR027": case "UPR030": case "UPR033": case "UPR036": case "UPR039": return 1;
      case "UPR019": case "UPR022": case "UPR025": case "UPR028": case "UPR031": case "UPR034": case "UPR037": case "UPR040": return 2;
      case "UPR020": case "UPR023": case "UPR026": case "UPR029": case "UPR032": case "UPR035": case "UPR038": case "UPR041": return 3;
      case "UPR153": return 1;
      case "UPR154": return 3;
      case "UPR155": return 1;
      case "UPR156": return 2;
      case "UPR157": return 3;
      default: return 0;
    }
  }

  function UPRIllusionistBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR004": return 0;
      case "UPR005": return 3;
      case "UPR006": return 3;
      case "UPR007": return 3;
      case "UPR008": return 3;
      case "UPR009": return 3;
      case "UPR010": return 3;
      case "UPR011": return 3;
      case "UPR012": return 3;
      case "UPR013": return 3;
      case "UPR014": return 3;
      case "UPR015": return 3;
      case "UPR016": return 3;
      case "UPR017": return 3;
      case "UPR018": case "UPR019": case "UPR020": return 3;
      case "UPR021": case "UPR022": case "UPR023": return 3;
      case "UPR024": case "UPR025": case "UPR026": return 3;
      case "UPR027": case "UPR028": case "UPR029": return 3;
      case "UPR030": case "UPR031": case "UPR032": return 3;
      case "UPR033": case "UPR034": case "UPR035": return 2;
      case "UPR036": case "UPR037": case "UPR038": return 2;
      case "UPR039": case "UPR040": case "UPR041": return -1;
      case "UPR151": return 0;
      case "UPR152": return 0;
      case "UPR155": case "UPR156": case "UPR157": return 2;
      default: return -1;
    }
  }

  function UPRIllusionistAttackValue($cardID)
  {
    switch($cardID)
    {
      case "UPR018": return 3;
      case "UPR019": return 2;
      case "UPR020": return 1;
      case "UPR021": return 5;
      case "UPR022": return 4;
      case "UPR023": return 3;
      case "UPR024": return 4;
      case "UPR025": return 3;
      case "UPR026": return 2;
      case "UPR027": return 8;
      case "UPR028": return 7;
      case "UPR029": return 6;
      case "UPR030": return 3;
      case "UPR031": return 2;
      case "UPR032": return 1;
      case "UPR042": return 1;
      case "UPR153": return 13;
      case "UPR406": return 6;
      case "UPR407": return 5;
      case "UPR408": return 4;
      case "UPR409": return 2;
      case "UPR410": return 3;
      case "UPR411": return 4;
      case "UPR412": return 2;
      case "UPR413": return 4;
      case "UPR414": return 1;
      case "UPR415": return 3;
      case "UPR416": return 6;
      case "UPR417": return 3;
      default: return 0;
    }
  }

  function UPRIllusionistPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $CS_PitchedForThisCard, $CS_DamagePrevention;
    $rv = "";
    switch($cardID)
    {
      case "UPR004":
        Transform($currentPlayer, "Ash", "UPR042");
        return "";
      case "UPR006":
        Transform($currentPlayer, "Ash", "UPR406");
        return "";
      case "UPR007":
        Transform($currentPlayer, "Ash", "UPR407");
        return "";
      case "UPR008":
        Transform($currentPlayer, "Ash", "UPR408");
        return "";
      case "UPR009":
        Transform($currentPlayer, "Ash", "UPR409");
        return "";
      case "UPR010":
        Transform($currentPlayer, "Ash", "UPR410");
        return "";
      case "UPR011":
        Transform($currentPlayer, "Ash", "UPR411");
        return "";
      case "UPR012":
        Transform($currentPlayer, "Ash", "UPR412");
        return "";
      case "UPR013":
        Transform($currentPlayer, "Ash", "UPR413");
        return "";
      case "UPR014":
        Transform($currentPlayer, "Ash", "UPR414");
        return "";
      case "UPR015":
        Transform($currentPlayer, "Ash", "UPR415");
        return "";
      case "UPR016":
        Transform($currentPlayer, "Ash", "UPR416");
        return "";
      case "UPR017":
        Transform($currentPlayer, "Ash", "UPR417");
        return "";
      case "UPR018": case "UPR019": case "UPR020":
        Transform($currentPlayer, "Ash", "UPR042", true);
        return "";
      case "UPR030": case "UPR031": case "UPR032":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        return "Sweeping Blow created an Ash token.";
      case "UPR033": case "UPR034": case "UPR035":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        if($cardID == "UPR033") $maxTransform = 3;
        else if($cardID == "UPR034") $maxTransform = 2;
        else $maxTransform = 1;
        for($i=0; $i<$maxTransform; ++$i)
        {
          Transform($currentPlayer, "Ash", "UPR042", true);
        }
        return "";
      case "UPR039":
          TransformPermanent($currentPlayer, "Ash", "UPR439");
        return "";
      case "UPR040":
          TransformPermanent($currentPlayer, "Ash", "UPR440");
        return "";
      case "UPR041":
          TransformPermanent($currentPlayer, "Ash", "UPR441");
        return "";
      case "UPR036": case "UPR037": case "UPR038":
        Transform($currentPlayer, "Ash", "UPR042");
        AddDecisionQueue("MZGETUNIQUEID", $currentPlayer, "-");
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $currentPlayer, $cardID . "," . "HAND");
        return "";
      case "UPR151":
        $gtIndex = FindCharacterIndex($currentPlayer, "UPR151");
        $char = &GetPlayerCharacter($currentPlayer);
        $index = PlayAlly("UPR551", $currentPlayer);
        $allies = &GetAllies($currentPlayer);
        $allies[$index+2] = $char[$gtIndex+2];
        AddCurrentTurnEffect($cardID . "-" . $char[$gtIndex+2], $currentPlayer);
        return "Ghostly Touch animated itself into an Ally.";
      case "UPR154":
        AddCurrentTurnEffect("UPR154", $currentPlayer);
        return "Semblance makes your next illusionist attack lose Phantasm.";
      case "UPR155": case "UPR156": case "UPR157":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Transmogrify modifies the base attack of your next attack action card.";
      case "UPR406":
        $deck = &GetDeck($currentPlayer);
        $numRed = 0;
        $cards = "";
        for($i=0; $i<3 && $i < count($deck); ++$i)
        {
          if(PitchValue($deck[$i]) == 1)
          {
            ++$numRed;
            if($cards != "") $cards .= ",";
            $cards .= $deck[$i];
          }
        }
        $cardsRevealed = RevealCards($cards);
        if($cardsRevealed)
        {
          DealArcane($numRed * 2, 2, "ABILITY", $cardID, false, $currentPlayer);
        }
        return "";
      case "UPR407":
        $deck = &GetDeck($currentPlayer);
        $numRed = 0;
        $cards = "";
        for($i=0; $i<2 && $i < count($deck); ++$i)
        {
          if(PitchValue($deck[$i]) == 1)
          {
            ++$numRed;
            if($cards != "") $cards .= ",";
            $cards .= $deck[$i];
          }
        }
        $cardsRevealed = RevealCards($cards);
        if($numRed > 0 && $cardsRevealed)
        {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDNEGDEFCOUNTER", $otherPlayer, "-", 1);
          if($numRed == 2) AddDecisionQueue("ADDNEGDEFCOUNTER", $otherPlayer, "-", 1);
          AddDecisionQueue("SETDQVAR", $otherPlayer, "0", 1);
          AddDecisionQueue("EQUIPDEFENSE", $otherPlayer, "-", 1);
          AddDecisionQueue("GREATERTHANPASS", $otherPlayer, "1", 1);
          AddDecisionQueue("PASSPARAMETER", $otherPlayer, "{0}", 1);
          AddDecisionQueue("DESTROYCHARACTER", $otherPlayer, "-", 1);
        }
        return "";
      case "UPR408":
        $deck = &GetDeck($currentPlayer);
        if(count($deck) == 0) return "You have no cards in your deck.";
        $wasRevealed = RevealCards($deck[0]);
        if($wasRevealed && PitchValue($deck[0]) == 1)
        {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("MULTIBANISH", $otherPlayer, "HAND,NA", 1);
        }
        return "";
      case "UPR409":
        //TODO: Limit duplicate targets?
        DealArcane(1, 2, "PLAYCARD", $cardID, false, $currentPlayer, true);
        DealArcane(1, 2, "PLAYCARD", $cardID, false, $currentPlayer, true);
        return "";
      case "UPR410":
        GainActionPoints(1);
        return "Cromai gives you an action point.";
      default: return "";
    }
  }

  function UPRIllusionistHitEffect($cardID)
  {
    global $mainPlayer, $combatChainState, $CCS_AttackFused, $CCS_WeaponIndex, $defPlayer;
    switch($cardID)
    {
      case "UPR024": case "UPR025": case "UPR026":
        PutPermanentIntoPlay($mainPlayer, "UPR043");
        Transform($mainPlayer, "Ash", "UPR042", true);
        break;
      case "UPR411":
        $items = &GetItems($defPlayer);
        if(count($items) == 0)
        {
          Draw($mainPlayer);
          WriteLog("Kyloria drew a card.");
        }
        break;
      case "UPR413":
        $index = $combatChainState[$CCS_WeaponIndex];
        $allies = &GetAllies($mainPlayer);
        $allies[$index+2] -= 1;
        $allies[$index+7] -= 1;
        PutPermanentIntoPlay($mainPlayer, "UPR043");
        WriteLog("Nekria got a -1 health counter and created an ash token.");
        break;
      case "UPR416": DealArcane(3, 0, "ABILITY", $cardID, true, $mainPlayer); break;
      default: break;
    }
  }

  function Transform($player, $materialType, $into, $optional=false)
  {
    AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $materialType);
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform", 1);
    if($optional) AddDecisionQueue("MAYCHOOSEPERMANENT", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEPERMANENT", $player, "<-", 1);
    AddDecisionQueue("TRANSFORM", $player, $into, 1);
  }

  function ResolveTransform($player, $materialIndex, $into)
  {
    $materialType = RemovePermanent($player, $materialIndex);
    return PlayAlly($into, $player, $materialType);//Right now transform only happens into allies
  }

  function TransformPermanent($player, $materialType, $into, $optional=false)
  {
    AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $materialType);
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform", 1);
    if($optional) AddDecisionQueue("MAYCHOOSEPERMANENT", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEPERMANENT", $player, "<-", 1);
    AddDecisionQueue("TRANSFORMPERMANENT", $player, $into, 1);
  }

  function ResolveTransformPermanent($player, $materialIndex, $into)
  {
    $materialType = RemovePermanent($player, $materialIndex);
    return PutPermanentIntoPlay($player, $into);
  }

  function GhostlyTouchPhantasmDestroy()
  {
    global $mainPlayer;
    $ghostlyTouchIndex = FindCharacterIndex($mainPlayer, "UPR151");
    if($ghostlyTouchIndex > -1)
    {
      $char = &GetPlayerCharacter($mainPlayer);
      ++$char[$ghostlyTouchIndex+2];
    }
  }

?>
