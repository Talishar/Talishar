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
      case "ARC113": case "ARC114": case "ARC115": case "ARC116": case "ARC117": return 0;
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

  function ARCWizardPlayAbility($cardID, $from, $resourcesPaid)
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
        return "";
      case "ARC115":
        AddArcaneBonus(1, $currentPlayer);
        return "Crucible of Aetherweave gives the next card that deals arcane damage +1.";
      case "ARC116":
        SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
        return "Storm Striders lets you play your next Wizard non-attack action as though it were an instant.";
      case "ARC117":
        GainResources($currentPlayer, 3);
        return "Robe of Rapture gives 3 resources.";
      case "ARC118":
        $damage = GetClassState($otherPlayer, $CS_ArcaneDamageTaken);
        DealArcane($damage, 1, "PLAYCARD", $cardID);
        return "Blazing Aether did damage equal to the prior arcane damage this turn (" . $damage . ").";
      case "ARC119":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "A", 1);
        AddDecisionQueue("ALLCARDCLASSORPASS", $currentPlayer, "WIZARD", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to banish <1> with Sonic Boom.");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_banish_the_card", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,ARC119-{0}", 1);
        return "";
      case "ARC120":
        $damage = ArcaneDamage($cardID) + GetClassState($currentPlayer, $CS_NextArcaneBonus) * 2;
        DealArcane(ArcaneDamage($cardID) + GetClassState($currentPlayer, $CS_NextArcaneBonus), 1, "PLAYCARD", $cardID);//Basically this just applies the bonus twice
        return "Forked Lightning deals " . $damage . " arcane damage.";
      case "ARC121":
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID, 1);
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARD", $currentPlayer, "-", 1);
        return "";
      case "ARC122":
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Buff_Arcane,Buff_Arcane,Draw_card,Draw_card");
        AddDecisionQueue("TOMEOFAETHERWIND", $currentPlayer, "-", 1);
        return "Tome of Aetherwind allowed drawing cards and/or increasing arcane damage.";
      case "ARC123": case "ARC124": case "ARC125":
        AddArcaneBonus(2, $currentPlayer);
        return "Absorb in Aether gives the next card that deals arcane damage +2.";
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
        return "Stir the Aetherwinds gives your next arcane +$buff and lets you play your next Wizard non-attack action as though it were an instant.";
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
        return "Index lets you rearrange the cards of your deck.";
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
        DealArcane(ArcaneDamage($cardID), 1, "PLAYCARD", $cardID);
        return "";
      default: return "";
    }

  }

  function ARCWizardHitEffect($cardID)
  {

  }

  //OpposingOnly -- 0=Opposing hero only, 1=Any Hero, 2=Any Target
  function DealArcane($damage, $OpposingOnly=0, $type="PLAYCARD", $source="NA", $fromQueue=false, $player=0)
  {
    global $currentPlayer;
    if($player == 0) $player = $currentPlayer;
    $damage += CurrentEffectArcaneModifier($source);
    if($fromQueue)
    {
      PrependDecisionQueue("DEALARCANE", $player, $damage . "-" . $source . "-" . $type, 1);
      PrependDecisionQueue("CHOOSEHERO", $player, $OpposingOnly);
    }
    else
    {
      if($type == "PLAYCARD" && SearchCharacterActive($player, "CRU161"))
      {
        AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_1_to_give_+1_arcane_damage");
        AddDecisionQueue("NOPASS", $player, "-", 1, 1);//Create cancel point
        AddDecisionQueue("PAYRESOURCES", $player, "1", 1);
        AddDecisionQueue("BUFFARCANE", $player, "1", 1);
        AddDecisionQueue("CHARFLAGDESTROY", $player, FindCharacterIndex($player, "CRU161"), 1);
      }
      if($OpposingOnly == 2)
      {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $source);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET," . $OpposingOnly);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>");
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      }
      else AddDecisionQueue("CHOOSEHERO", $player, $OpposingOnly);
      AddDecisionQueue("DEALARCANE", $player, $damage . "-" . $source . "-" . $type, 1);
    }
  }

  function GetArcaneTargetIndices($player, $opposingOnly)
  {
    $otherPlayer = ($player == 1 ? 2 : 1);
    $rv = "THEIRCHAR-0";
    if($opposingOnly == 2)
    {
      $allies = &GetAllies($otherPlayer);
      for($i=0; $i<count($allies); $i+=AllyPieces())
      {
        $rv .= ",THEIRALLY-" . $i;
      }
    }
    return $rv;
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
    if($barrierArray[2] > 0 || $barrierArray[1] >= 2) array_push($choiceArray, 2);
    if($barrierArray[3] > 0 || $total >= 3) array_push($choiceArray, 3);
    for($i=4; $i<=$max; ++$i)
    {
      if($i <= $total) array_push($choiceArray, $i);
    }
    return implode(",", $choiceArray);
  }

?>
