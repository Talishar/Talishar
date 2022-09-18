<?php


  function ARCWizardCardType($cardID)
  {
    switch($cardID)
    {
      case "ARC113": case "ARC114": return "C";
      case "ARC115": return "W";
      case "ARC116": case "ARC117": return "E";
      case "ARC123": case "ARC124": case "ARC125": return "DR";
      default: return "A";
    }
  }

  function ARCWizardCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ARC115": return "Staff";
      case "ARC116": return "Legs";
      case "ARC117": return "Chest";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ARCWizardCardCost($cardID)
  {
    switch($cardID)
    {
      case "ARC119": return 2;
      case "ARC120": return 3;
      case "ARC121": return 1;
      case "ARC122": return 0;
      case "ARC123": case "ARC124": case "ARC125": return 1;
      case "ARC126": case "ARC127": case "ARC128": return 2;
      case "ARC129": case "ARC130": case "ARC131": return 2;
      case "ARC132": case "ARC133": case "ARC134": return 1;
      case "ARC138": case "ARC139": case "ARC140": return 1;
      case "ARC141": case "ARC142": case "ARC143": return 1;
      case "ARC147": case "ARC148": case "ARC149": return 2;
      default: return 0;
    }
  }

  function ARCWizardPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ARC118": return 1;
      case "ARC119": return 2;
      case "ARC120": return 1;
      case "ARC121": return 2;
      case "ARC122": return 1;
      case "ARC123": case "ARC126": case "ARC129": case "ARC132": case "ARC135": case "ARC138": case "ARC141": case "ARC144": case "ARC147": return 1;
      case "ARC124": case "ARC127": case "ARC130": case "ARC133": case "ARC136": case "ARC139": case "ARC142": case "ARC145": case "ARC148": return 2;
      case "ARC125": case "ARC128": case "ARC131": case "ARC134": case "ARC137": case "ARC140": case "ARC143": case "ARC146": case "ARC149": return 3;
      default: return 0;
    }
  }

  function ARCWizardBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ARC113": case "ARC114": case "ARC115": return -1;
      case "ARC116": case "ARC117": return 0;
      case "ARC122": return 2;
      case "ARC123": return 4;
      case "ARC124": return 3;
      case "ARC125": return 2;
      case "ARC129": case "ARC130": case "ARC131": return 2;
      case "ARC135": case "ARC136": case "ARC137": return 2;
      default: return 3;
    }
  }

  function ARCWizardAttackValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function ARCWizardPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer, $CS_NextArcaneBonus, $CS_NextWizardNAAInstant, $CS_ArcaneDamageTaken;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "ARC113": case "ARC114":
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "A", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with Kano?");
        AddDecisionQueue("YESNO", $currentPlayer, "whether to banish a card with Kano", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,INST", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}");
        AddDecisionQueue("NONECARDTYPEORPASS", $currentPlayer, "A");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Kano shows the top of your deck is <0>");
        AddDecisionQueue("OK", $currentPlayer, "whether to banish a card with Kano", 1);
        return "";
      case "ARC115":
        AddArcaneBonus(1, $currentPlayer);
        return "Gives the next card that deals arcane damage +1.";
      case "ARC116":
        SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
        return "You can play your next Wizard non-attack action as though it were an instant.";
      case "ARC117":
        GainResources($currentPlayer, 3);
        return "Gain 3 resources.";
      case "ARC118":
        $damage = GetClassState($otherPlayer, $CS_ArcaneDamageTaken);
        DealArcane($damage, 0, "PLAYCARD", $cardID);
        return "Deals damage equal to the prior arcane damage this turn (" . $damage . ").";
      case "ARC119":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "A", 1);
        AddDecisionQueue("ALLCARDCLASSORPASS", $currentPlayer, "WIZARD", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to banish <1> with Sonic Boom.", 1);
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_banish_the_card", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,ARC119-{0}", 1);
        AddDecisionQueue("ELSE", $currentPlayer, "-");
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
        AddDecisionQueue("NULLPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sonic Boom shows the top of your deck is <1>", 1);
        AddDecisionQueue("OK", $currentPlayer, "-", 1);
        return "";
      case "ARC120":
        $damage = ArcaneDamage($cardID) +ConsumeArcaneBonus($currentPlayer) * 2; // TODO: Not exactly right. Should be able to target 2 differents heroes.
        DealArcane($damage, 1, "PLAYCARD", $cardID);//Basically this just applies the bonus twice
        return "Deals " . $damage . " arcane damage.";
      case "ARC121":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_tutor_a_card?", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID, 1);
        AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        return "";
      case "ARC122":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("TOMEOFAETHERWIND", $currentPlayer, "-", 1);
        return "";
      case "ARC123": case "ARC124": case "ARC125":
        AddArcaneBonus(2, $currentPlayer);
        return "Gives the next card that deals arcane damage +2.";
      case "ARC126": case "ARC127": case "ARC128":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("OPTX", $currentPlayer, "<-", 1);
        return "";
      case "ARC129": case "ARC130": case "ARC131":
        if($cardID == "ARC129") $buff = 3;
        else if($cardID == "ARC130") $buff = 2;
        else $buff = 1;
        AddArcaneBonus($buff, $currentPlayer);
        SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
        return "Gives your next arcane +$buff and lets you play your next Wizard non-attack action as though it were an instant.";
      case "ARC132": case "ARC133": case "ARC134":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("BUFFARCANE", $currentPlayer, "<-", 1);
        return "";
      case "ARC135": case "ARC136": case "ARC137":
        if($cardID == "ARC135") $count = 5;
        else if($cardID == "ARC136") $count = 4;
        else $count = 3;
        $deck = &GetDeck($currentPlayer);
        $cards = "";
        for($i=0; $i<$count; ++$i)
        {
          if(count($deck) > 0)
          {
            if($cards != "") $cards .= ",";
            $card = array_shift($deck);
            $cards .= $card;
          }
        }
        if($cards != "")
        {
          WriteLog("Choose a card to go on the top of your deck.");
          AddDecisionQueue("CHOOSECARD", $currentPlayer, $cards, 1);
          AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "DECK");
          AddDecisionQueue("REMOVELAST", $currentPlayer, $cards, 1);
          AddDecisionQueue("CHOOSEBOTTOM", $currentPlayer, "<-", 1);
        }
        return "Lets you rearrange the cards of your deck.";
      case "ARC138": case "ARC139": case "ARC140":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID, 1);
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,INST", 1);
        return "";
      case "ARC141": case "ARC142": case "ARC143":
      case "ARC144": case "ARC145": case "ARC146":
      case "ARC147": case "ARC148": case "ARC149":
        DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID);
        return "";
      default: return "";
    }

  }

  function ARCWizardHitEffect($cardID)
  {

  }

  //Parameters:
  //Player = Player controlling the arcane effects
  //target =
  // 0: My Hero + Their Hero
  // 1: Their Hero only
  // 2: Any Target
  // 3: Their Hero + Their Allies
  // 4: My Hero only (For afflications)
  function DealArcane($damage, $target=0, $type="PLAYCARD", $source="NA", $fromQueue=false, $player=0, $mayAbility=false, $limitDuplicates=false, $skipHitEffect=false)
  {
    global $currentPlayer, $CS_ArcaneTargetsSelected;
    if($player == 0) $player = $currentPlayer;
    if($damage > 0) $damage += CurrentEffectArcaneModifier($source);
    if($fromQueue)
    {
      if(!$limitDuplicates)
      {
        PrependDecisionQueue("PASSPARAMETER", $player, "{0}");
        PrependDecisionQueue("SETCLASSSTATE", $player, $CS_ArcaneTargetsSelected);
        PrependDecisionQueue("PASSPARAMETER", $player, "-");
      }
      if(!$skipHitEffect) PrependDecisionQueue("ARCANEHITEFFECT", $player, $source, 1);
      PrependDecisionQueue("DEALARCANE", $player, $damage . "-" . $source . "-" . $type, 1);
      PrependDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if($mayAbility) { PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1); }
      else { PrependDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1); }
      PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>");
      PrependDecisionQueue("FINDINDICES", $player, "ARCANETARGET," . $target);
      PrependDecisionQueue("SETDQVAR", $currentPlayer, "0");
      PrependDecisionQueue("PASSPARAMETER", $currentPlayer, $source);
    }
    else
    {
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $source);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET," . $target);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>");
      if($mayAbility) { AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1); }
      else { AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1); }
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("DEALARCANE", $player, $damage . "-" . $source . "-" . $type, 1);
      if(!$skipHitEffect) AddDecisionQueue("ARCANEHITEFFECT", $player, $source, 1);
      if(!$limitDuplicates)
      {
        AddDecisionQueue("PASSPARAMETER", $player, "-");
        AddDecisionQueue("SETCLASSSTATE", $player, $CS_ArcaneTargetsSelected);
        AddDecisionQueue("PASSPARAMETER", $player, "{0}");
      }
    }
  }

  //Parameters:
  //Player = Player controlling the arcane effects
  //target =
  // 0: My Hero + Their Hero
  // 1: Their Hero only
  // 2: Any Target
  // 3: Their Hero + Their Alliers
  // 4: My Hero only (For afflications)
  function GetArcaneTargetIndices($player, $target)
  {
    global $CS_ArcaneTargetsSelected;
    $otherPlayer = ($player == 1 ? 2 : 1);
    if ($target == 4) return "MYCHAR-0";
    if($target != 3) $rv = "THEIRCHAR-0";
    else $rv = "";
    if(($target == 0 && !ShouldAutotargetOpponent($player)) || $target == 2)
    {
      $rv .= ",MYCHAR-0";
    }
    if($target == 2)
    {
      $theirAllies = &GetAllies($otherPlayer);
      for($i=0; $i<count($theirAllies); $i+=AllyPieces())
      {
        $rv .= ",THEIRALLY-" . $i;
      }
      $myAllies = &GetAllies($player);
      for($i=0; $i<count($myAllies); $i+=AllyPieces())
      {
        $rv .= ",MYALLY-" . $i;
      }
    }
    elseif($target == 3)
    {
      $theirAllies = &GetAllies($otherPlayer);
      for($i=0; $i<count($theirAllies); $i+=AllyPieces())
      {
        if($rv != "") $rv .= ",";
        $rv .= "THEIRALLY-" . $i;
      }
    }
    $targets = explode(",", $rv);
    $targetsSelected = GetClassState($player, $CS_ArcaneTargetsSelected);
    for($i=count($targets)-1; $i>=0; --$i)
    {
      if(DelimStringContains($targetsSelected, $targets[$i])) unset($targets[$i]);
    }
    return implode(",", $targets);
  }

  function CurrentEffectArcaneModifier($source)
  {
    global $currentTurnEffects;
    $modifier = 0;
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
    {
      $effectArr = explode(",", $currentTurnEffects[$i]);
      switch($effectArr[0])
      {
        case "EVR123": $cardType = CardType($source); if($cardType == "A" || $cardType == "AA") $modifier += $effectArr[1]; break;
        default: break;
      }
    }
    return $modifier;
  }

  function ArcaneDamage($cardID)
  {
    switch($cardID)
    {
      case "ARC119": return 3;
      case "ARC120": return 4;
      case "ARC121": return 3;
      case "ARC147": return 5;
      case "ARC126": case "ARC141": case "ARC148": return 4;
      case "ARC127": case "ARC132": case "ARC138": case "ARC142": case "ARC144": case "ARC149": return 3;
      case "ARC128": case "ARC133": case "ARC139": case "ARC143": case "ARC145": return 2;
      case "ARC134": case "ARC140": case "ARC146": return 1;
      //CRU
      case "CRU171": return 4;
      case "CRU168": case "CRU172": case "CRU174": return 3;
      case "CRU169": case "CRU173": case "CRU175": return 2;
      case "CRU170": case "CRU176": return 1;
      //Everfest
      case "EVR134": return 5;
      case "EVR135": return 4;
      case "EVR136": return 3;
      //UPR
      case "UPR179": case "UPR180": case "UPR181":return 1;
      default: return 0;
    }
  }

  function ArcaneBarrierChoices($playerID, $max)
  {
    global $currentTurnEffects;
    $barrierArray = [];
    for($i=0; $i<4; ++$i)//4 is the current max arcane barrier + 1
    {
      $barrierArray[$i] = 0;
    }
    $character = GetPlayerCharacter($playerID);
    $total = 0;
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      if($character[$i+1] == 0) continue;
      switch($character[$i])
      {
        case "ARC005": ++$barrierArray[1]; $total += 1; break;
        case "ARC041": ++$barrierArray[1]; $total += 1; break;
        case "ARC042": ++$barrierArray[1]; $total += 1; break;
        case "ARC079": ++$barrierArray[1]; $total += 1; break;
        case "ARC116": ++$barrierArray[2]; $total += 2; break;
        case "ARC117": ++$barrierArray[1]; $total += 1; break;
        case "ARC150": if(PlayerHasLessHealth($playerID)) { ++$barrierArray[3]; $total += 3; } break;
        case "ARC155": case "ARC156": case "ARC157": case "ARC158": ++$barrierArray[1]; $total += 1; break;
        case "CRU006": ++$barrierArray[2]; $total += 2; break;
        case "CRU102": ++$barrierArray[2]; $total += 2; break;
        case "CRU161": ++$barrierArray[1]; $total += 1; break;
        case "ELE144": ++$barrierArray[1]; $total += 1; break;
        case "EVR103": ++$barrierArray[1]; $total += 1; break;
        case "EVR137": ++$barrierArray[1]; $total += 1; break;
        case "EVR155": ++$barrierArray[1]; $total += 1; break;
        case "UPR152": ++$barrierArray[1]; $total += 1; break;
        case "UPR159": ++$barrierArray[1]; $total += 1; break;
        case "UPR166": ++$barrierArray[1]; $total += 1; break;
        case "UPR167": ++$barrierArray[1]; $total += 1; break;
        default: break;
      }
    }
    $items = GetItems($playerID);
    for($i=0; $i<count($items); $i+=ItemPieces())
    {
      switch($items[$i])
      {
        case "ARC163": ++$barrierArray[1]; $total += 1; break;
        default: break;
      }
    }
    $allies = GetAllies($playerID);
    for($i=0; $i<count($allies); $i+=AllyPieces())
    {
      switch($allies[$i])
      {
        case "UPR042": ++$barrierArray[1]; $total += 1; break;
        default: break;
      }
    }
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces())
    {
      switch($currentTurnEffects[$i])
      {
        case "ARC017": ++$barrierArray[2]; $total += 2; break;
        default: break;
      }
    }
    $choiceArray = [];
    array_push($choiceArray, 0);
    if($barrierArray[1] > 0) array_push($choiceArray, 1);
    if(($max > 1 || $barrierArray[1] == 0) && ($barrierArray[2] > 0 || $barrierArray[1] >= 2)) array_push($choiceArray, 2);
    if(($max > 2 || ($barrierArray[1] == 0 && $barrierArray[2] == 0)) && ($barrierArray[3] > 0 || $total >= 3)) array_push($choiceArray, 3);
    for($i=4; $i<=$max; ++$i)
    {
      if($i <= $total) array_push($choiceArray, $i);
    }
    return implode(",", $choiceArray);
  }

?>
