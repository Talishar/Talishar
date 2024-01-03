<?php

  function HVYPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer;
    $otherPlayer = $currentPlayer == 1 ? 2 : 1;
    $rv = "";
    switch($cardID) {
      case "HVY013":
        Intimidate();
        return "";
      case "HVY016":
        AddCurrentTurnEffect($cardID . "-" . $additionalCosts, $currentPlayer);
        return "";
      case "HVY023": case "HVY024": case "HVY025":
        if(SearchCurrentTurnEffects("BEATCHEST", $currentPlayer)) Intimidate();
        return "";
      case "HVY041": case "HVY042": case "HVY043":
        if($cardID == "HVY041") $amount = 3;
        else if($cardID == "HVY042") $amount = 2;
        else if($cardID == "HVY043") $amount = 1;
        if(SearchCurrentTurnEffects("BEATCHEST", $currentPlayer)) $amount += 2;
        AddCurrentTurnEffect($cardID . "," . $amount, $currentPlayer);
        return "";
      case "HVY044":
        PlayAura("HVY240", $currentPlayer);//Agility
        PlayAura("TCC105", $currentPlayer);//Might
        return "";
      case "HVY057":
        AskWager($cardID);
        return "";
      case "HVY069":
        PlayAura("TCC105", $currentPlayer);//Might
        PlayAura("TCC107", $currentPlayer);//Vigor
        return "";
      case "HVY133":
        PlayAura("HVY240", $currentPlayer);//Agility
        PlayAura("TCC107", $currentPlayer);//Vigor
        return "";
      case "HVY090": case "HVY091":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "HVY103":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("MODAL", $currentPlayer, "UPTHEANTE", 1);
        return "";
      case "HVY105":
        for($i=0; $i<intval($additionalCosts); ++$i) {
          PlayAlly("HVY134", $currentPlayer);
        }
        return "";
      case "HVY130":
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        return "";
      case "HVY143": case "HVY144": case "HVY145":
        if(GetResolvedAbilityType($cardID, "HAND") == "I") {
          PlayAura("TCC105", $currentPlayer);//Might
          CardDiscarded($currentPlayer, $cardID, source:$cardID);
        }
        return "";
      case "HVY149":
        AskWager($cardID);
        return "";
      case "HVY163": case "HVY164": case "HVY165":
        if(GetResolvedAbilityType($cardID, "HAND") == "I") {
          PlayAura("HVY240", $currentPlayer);//Agility
          CardDiscarded($currentPlayer, $cardID, source:$cardID);
        }
        return "";
      case "HVY169":
        AskWager($cardID);
        return "";
      case "HVY189":
        AskWager($cardID);
        return "";
      case "HVY216": case "HVY217": case "HVY218":
        AskWager($cardID);
        return "";
      case "HVY235":
        AddCurrentTurnEffect($cardID . "-BUFF", $currentPlayer);
        return "";
      case "HVY246":
        if(IsHeroAttackTarget()) {
          $deck = new Deck($otherPlayer);
          if($deck->RemainingCards() > 0) {
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to put on top of their deck");
            AddDecisionQueue("CHOOSETOPOPPONENT", $currentPlayer, $deck->Top(true, 3));
            AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
            AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
            AddDecisionQueue("MULTIBANISH", $otherPlayer, "DECK,TCC," . $currentPlayer);
          }
        }
        return "";
      case "HVY251":
        $xVal = $resourcesPaid/2;
        MZMoveCard($currentPlayer, "MYDECK:maxCost=" . $xVal . ";subtype=Aura", "MYAURAS", may:true);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        if($xVal >= 2) {
          global $CS_NextNAACardGoAgain;
          SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 1);
        }
        return "";
      case "HVY253":
        for($i = 1; $i < 3; $i += 1) {
          $arsenal = &GetArsenal($i);
          for($j = 0; $j < count($arsenal); $j += ArsenalPieces()) {
            AddDecisionQueue("FINDINDICES", $i, "ARSENAL");
            AddDecisionQueue("CHOOSEARSENAL", $i, "<-", 1);
            AddDecisionQueue("REMOVEARSENAL", $i, "-", 1);
            AddDecisionQueue("ADDBOTDECK", $i, "-", 1);
          }
          PlayAura("DYN244", $i);
        }
        return "";
      case "HVY250":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXINDICES," . ($resourcesPaid + 1));
        AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
        AddDecisionQueue("REELIN", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTICHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        Reload();
        return "";
      case "HVY245":
        if($from == "GY") {
          $character = &GetPlayerCharacter($currentPlayer);
          EquipWeapon($currentPlayer,"HVY245");
          $index = FindCharacterIndex($currentPlayer, "HVY245");
          if ($character[$index + 3] == 0) {
            ++$character[$index + 3];
          } else {
            ++$character[$index + 15];
          }
          $index = SearchGetFirstIndex(SearchMultizone($currentPlayer, "MYDISCARD:cardID=HVY245"));
          RemoveGraveyard($currentPlayer, $index);
        }
        return "";
      default: return "";
    }
  }

  function TCCPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID) {
      case "TCC035":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "TCC050":
        $abilityType = GetResolvedAbilityType($cardID);
        $character = &GetPlayerCharacter($currentPlayer);
        $charIndex = FindCharacterIndex($mainPlayer, $cardID);
        if ($abilityType == "A") {
          AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a token to create");
          AddDecisionQueue("MULTICHOOSETEXT", $otherPlayer, "1-Might (+1),Vigor (Resource),Quicken (Go Again)-1");
          AddDecisionQueue("SHOWMODES", $otherPlayer, $cardID, 1);
          AddDecisionQueue("MODAL", $otherPlayer, "JINGLEWOOD", 1);
          PutItemIntoPlayForPlayer("CRU197", $currentPlayer);
          --$character[$charIndex+5];
          }
        return "";
      case "TCC051":
        Draw(1);
        Draw(2);
        return "";
      case "TCC052":
        PlayAura("TCC107", 1);
        PlayAura("TCC107", 2);
        return "";
      case "TCC053":
        PlayAura("TCC105", 1);
        PlayAura("TCC105", 2);
        return "";
      case "TCC054":
        PlayAura("WTR225", 1);
        PlayAura("WTR225", 2);
        return "";
      case "TCC057":
        $numPitch = SearchCount(SearchPitch($currentPlayer)) + SearchCount(SearchPitch($otherPlayer));
        AddCurrentTurnEffect($cardID . "," . ($numPitch*2), $currentPlayer);
        return "";
      case "TCC058": case "TCC062": case "TCC075":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "TCC061":
        MZMoveCard($currentPlayer, "MYDISCARD:class=BARD;type=AA", "MYHAND", may:false, isSubsequent:false);
        return "";
      case "TCC064":
        PlayAura("WTR225", $otherPlayer);
        return "";
      case "TCC065":
        GainHealth(1, $otherPlayer);
        return "";
      case "TCC066": case "TCC067":
        PlayAura("TCC105", $otherPlayer);
        return "";
      case "TCC068":
        Draw($otherPlayer);
        return "";
      case "TCC069":
        MZMoveCard($otherPlayer, "MYDISCARD:type=AA", "MYBOTDECK", may:true);
        return "";
      case "TCC079":
        Draw($currentPlayer);
        return "";
      case "TCC080":
        GainResources($currentPlayer, 1);
        return "";
      case "TCC082":
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
        return "";
      case "TCC086": case "TCC094":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        break;
      default: return "";
    }
  }


  function EVOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer, $layers, $combatChain;
    global $CS_NamesOfCardsPlayed, $CS_NumBoosted, $CS_PlayIndex, $CS_NumItemsDestroyed;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $character = &GetPlayerCharacter($currentPlayer);
    switch($cardID) {
      case "EVO004": case "EVO005":
        PutItemIntoPlayForPlayer("EVO234", $currentPlayer, 2);
        --$character[5];
        return "";
      case "EVO007": case "EVO008":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        --$character[5];
        return "";
      case "EVO009":
        $evoAmt = EvoUpgradeAmount($currentPlayer);
        if($evoAmt >= 3) GiveAttackGoAgain();
        if($evoAmt >= 4) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO010":
        $conditionsMet = CheckIfSingularityConditionsAreMet($currentPlayer);
        if ($conditionsMet != "") return $conditionsMet;
        $char = &GetPlayerCharacter($currentPlayer);
        // We don't want function calls in every iteration check
        $charCount = count($char);
        $charPieces = CharacterPieces();
        if (isSubcardEmpty($char, 0)) $char[10] = $char[0];
        else $char[10] = $char[10] . "," . $char[0];
        $char[0] = "EVO410";
        $char[5] = 999; // Remove the 'Once per Turn' limitation from Teklovossen
        $mechropotentIndex = 0; // we pushed it, so should be the last element
        for ($i = $charCount - $charPieces; $i >= 0; $i -= $charPieces) {
          if($char[$i] != "EVO410") {
            EvoTransformAbility("EVO410", $char[$i], $currentPlayer);
            RemoveCharacterAndAddAsSubcardToCharacter($currentPlayer, $i, $mechropotentIndex);
          }
        }
        PutCharacterIntoPlayForPlayer("EVO410b", $currentPlayer);
        return "";
      case "EVO014":
        MZMoveCard($mainPlayer, "MYBANISH:class=MECHANOLOGIST;type=AA", "MYTOPDECK", isReveal:true);
        AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-", 1);
        return "";
      case "EVO015":
        AddDecisionQueue("GAINRESOURCES", $mainPlayer, "2");
        return "";
      case "EVO016":
        AddCurrentTurnEffectNextAttack($cardID, $mainPlayer);
        return "";
      case "EVO017":
        AddDecisionQueue("GAINACTIONPOINTS", $mainPlayer, "1");
        return "";
      case "EVO030": case "EVO031": case "EVO032": case "EVO033":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "-");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "EVOBREAKER");
        return "Light up the gem when you want the conditional boost effect to trigger";
      case "EVO057":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) > 0) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRITEMS:hasSteamCounter=true&THEIRCHAR:hasSteamCounter=true");
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "MAXCOUNT-" . EvoUpgradeAmount($mainPlayer) . ",MINCOUNT-" . 0 . ",", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose up to " . EvoUpgradeAmount($currentPlayer) . " card" . (EvoUpgradeAmount($mainPlayer) > 1 ? "s" : "") . " to remove a steam counter from." , 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZREMOVESTEAMCOUNTER", $currentPlayer, "<-");
        }
        return "";
      case "EVO058":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($currentPlayer) > 0)
        {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          AddDecisionQueue("PASSPARAMETER", $otherPlayer, EvoUpgradeAmount($currentPlayer), 1);
          AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("APPENDLASTRESULT", $otherPlayer, "-{0}", 1);
          AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "{0}-", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose " . EvoUpgradeAmount($currentPlayer) . " card(s)", 1);
          AddDecisionQueue("MULTICHOOSEHAND", $otherPlayer, "<-", 1);
          AddDecisionQueue("IMPLODELASTRESULT", $otherPlayer, ",", 1);
          AddDecisionQueue("SETDQVAR", $currentPlayer, "1");
          AddDecisionQueue("REVEALHANDCARDS", $otherPlayer, "<-", 1);
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card", 1);
          AddDecisionQueue("SPECIFICCARD", $otherPlayer, "PULSEWAVEPROTOCOLFILTER", 1);
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("ADDCARDTOCHAIN", $otherPlayer, "HAND", 1);
        }
        return "";
      case "EVO059":
        WriteLog("This is a partially manual card. Must block with " . EvoUpgradeAmount($currentPlayer) . " equipment with -1 def counters if able");
        return "";
      case "EVO070":
        if($from == "PLAY") DestroyTopCard($currentPlayer);
        break;
      case "EVO071":
        if($from == "PLAY") {
          $deck = new Deck($currentPlayer);
          $deck->Reveal();
          $pitchValue = PitchValue($deck->Top());
          MZMoveCard($currentPlayer, ("MYBANISH:class=MECHANOLOGIST;subtype=Item;pitch=" . $pitchValue),"MYTOPDECK", may:true, isReveal:true);
        }
        break;
      case "EVO072":
        if($from == "PLAY") {
          MZMoveCard($currentPlayer, "MYHAND:class=MECHANOLOGIST;subtype=Item;maxCost=1", "", may:true);
          AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        }
        break;
      case "EVO073":
        if($from != "PLAY") {
          AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target equipment it cannot be activated until the end of its controller next turn");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDSTASISTURNEFFECT", $otherPlayer, "EVO073-", 1);
        }
        else {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          RemoveItem($currentPlayer, $index);
          $deck = new Deck($currentPlayer);
          $deck->AddBottom($cardID, from:"PLAY");
          AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target equipment it cannot defend this turn");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $currentPlayer, "<-", 1);
          AddDecisionQueue("EQUIPCANTDEFEND", $otherPlayer, "EVO073-B-", 1);
        }
        break;
      case "EVO075":
        if($from == "PLAY") GainResources($currentPlayer, 1);
        return "";
      case "EVO076":
        if($from == "PLAY") GainHealth(2, $currentPlayer);
        return "";
      case "EVO077":
        if($from == "PLAY")
        {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZADDSTEAMCOUNTER", $currentPlayer, "-", 1);
        }
        return "";
      case "EVO079":
        if($currentPlayer == $defPlayer) {
          for($j = CombatChainPieces(); $j < count($combatChain); $j += CombatChainPieces()) {
            if($combatChain[$j+1] != $currentPlayer) continue;
            if(CardType($combatChain[$j]) == "AA" && ClassContains($combatChain[$j], "MECHANOLOGIST", $currentPlayer)) CombatChainPowerModifier($j, 1);
          }
        }
        break;
      case "EVO081": case "EVO082": case "EVO083":
        if($from == "PLAY") {
          MZMoveCard($currentPlayer, "MYDISCARD:pitch=". PitchValue($cardID) .";type=AA;class=MECHANOLOGIST", "MYHAND", may:true, isReveal:true);
        }
        return "";
      case "EVO087": case "EVO088": case "EVO089":
        if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO100":
        $items = SearchDiscard($currentPlayer, subtype: "Item");
        $itemsCount = count(explode(",", $items));
        if ($itemsCount < $resourcesPaid) {
          WriteLog("Player " . $currentPlayer . " would need to banish " . $resourcesPaid . " items from their graveyard but they only have " . $itemsCount . " items in their graveyard.");
          RevertGamestate();
        }
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, $resourcesPaid . "-" . $items . "-" . $resourcesPaid, 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "HYPERSCRAPPER");
        return "";
      case "EVO101":
        $numScrap = 0;
        $costAry = explode(",", $additionalCosts);
        for($i=0; $i<count($costAry); ++$i) if($costAry[$i] == "SCRAP") ++$numScrap;
        if($numScrap > 0) GainResources($currentPlayer, $numScrap * 2);
        return "";
      case "EVO102": case "EVO103": case "EVO104":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO105": case "EVO106": case "EVO107":
        if(GetClassState($currentPlayer, $CS_NumItemsDestroyed) > 0) AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "EVO108": case "EVO109": case "EVO110":
        if($additionalCosts == "SCRAP") PlayAura("WTR225", $currentPlayer);
        return "";
      case "EVO126": case "EVO127": case "EVO128":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO129": case "EVO130": case "EVO131":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO132": case "EVO133": case "EVO134":
        if($additionalCosts == "SCRAP") {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZADDSTEAMCOUNTER", $currentPlayer, "-", 1);
        }
        return "";
      case "EVO135": case "EVO136": case "EVO137":
        if($additionalCosts == "SCRAP") GainResources($currentPlayer, 1);
        return "";
      case "EVO140":
        for($i=0; $i<$resourcesPaid; $i+=2) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO141":
        if(GetClassState($mainPlayer, $CS_NumItemsDestroyed) > 0) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO143":
        if ($resourcesPaid == 0) return;
        AddDecisionQueue("MULTIZONEINDICES", $otherPlayer, "MYCHAR:type=E");
        AddDecisionQueue("PREPENDLASTRESULT", $otherPlayer, "MAXCOUNT-" . $resourcesPaid/3 . ",MINCOUNT-" . $resourcesPaid/3 . ",");
        AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose " . $resourcesPaid/3 . " equipment for the effect of " . CardLink("EVO143", "EVO143") . ".");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-", 1);
        AddDecisionQueue("MZSWITCHPLAYER", $currentPlayer, "<-", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "MEGANETICLOCKWAVE");
        return "";
      case "EVO144":
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:hasSteamCounter=true&THEIRCHAR:hasSteamCounter=true&MYITEMS:hasSteamCounter=true&MYCHAR:hasSteamCounter=true");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an equipment, item, or weapon. Remove all steam counters from it.");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVESTEAMCOUNTER", $currentPlayer, "-", 1);
        AddDecisionQueue("SYSTEMFAILURE", $currentPlayer, "<-", 1);
        return "";
      case "EVO145":
        $indices = SearchMultizone($currentPlayer, "MYITEMS:class=MECHANOLOGIST;maxCost=1");
        $indices = str_replace("MYITEMS-", "", $indices);
        $num = SearchCount($indices);
        $num = $resourcesPaid < $num ? $resourcesPaid : $num;
        AddDecisionQueue("MULTICHOOSEITEMS", $currentPlayer, $num . "-" . $indices . "-" . $num);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SYSTEMRESET");
        return "";
      case "EVO146":
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
        AddDecisionQueue("MODAL", $currentPlayer, "FABRICATE", 1);
        return "";
      case "EVO153": case "EVO154": case "EVO155":
        if(GetClassState($currentPlayer, $CS_NumBoosted) >= 2) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO156": case "EVO157": case "EVO158":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO222": case "EVO223": case "EVO224":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        MZMoveCard($currentPlayer, "MYBANISH:sameName=ARC036", "", may:true);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        return "";
      case "EVO225": case "EVO226": case "EVO227":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO228": case "EVO229": case "EVO230":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a Hyper Driver to get a steam counter", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:sameName=ARC036");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZADDSTEAMCOUNTER", $currentPlayer, "-", 1);
        return "";
      case "EVO235":
        $options = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "AA");
        if($options != "") {
          AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
          AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, -1, 1);
        }
        return "";
      case "EVO237":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:"EVO237") >= 6) {
          $items = SearchMultizone($currentPlayer, "THEIRITEMS&MYITEMS");
          if($items != "") {
            $items = explode(",", $items);
            $destroyedItem = $items[GetRandom(0, count($items) - 1)];
            $destroyedItemID = GetMZCard($currentPlayer, $destroyedItem);
            WriteLog(CardLink("EVO237", "EVO237") . " destroys " . CardLink($destroyedItemID, $destroyedItemID) . ".");
            MZDestroy($currentPlayer, $destroyedItem);
          }
        }
        return "";
      case "EVO238":
        PlayAura("WTR075", $currentPlayer, number:$resourcesPaid);
        return "";
      case "EVO239":
        $cardsPlayed = explode(",", GetClassState($currentPlayer, $CS_NamesOfCardsPlayed));
        for($i=0; $i<count($cardsPlayed); ++$i) {
          if(CardName($cardsPlayed[$i]) == "Wax On") {
            PlayAura("CRU075", $currentPlayer);
            break;
          }
        }
        return "";
      case "EVO240":
        if(ArsenalHasFaceDownCard($otherPlayer)) {
          SetArsenalFacing("UP", $otherPlayer);
          if (SearchArsenal($otherPlayer, type:"DR") != "") {
            DestroyArsenal($otherPlayer);
            AddCurrentTurnEffect($cardID, $currentPlayer);
          }
        }
        return "";
      case "EVO242":
        $xVal = $resourcesPaid/2;
        PlayAura("ARC112", $currentPlayer, $xVal);
        if($xVal >= 6) {
          DiscardRandom($otherPlayer);
          DiscardRandom($otherPlayer);
          DiscardRandom($otherPlayer);
        }
        return "";
      case "EVO245":
        Draw($currentPlayer);
        if(IsRoyal($currentPlayer)) Draw($currentPlayer);
        PrependDecisionQueue("OP", $currentPlayer, "BANISHHAND", 1);
        if(SearchCount(SearchHand($currentPlayer, pitch:1)) >= 2) {
          PrependDecisionQueue("ELSE", $currentPlayer, "-");
          PitchCard($currentPlayer, "MYHAND:pitch=1");
          PitchCard($currentPlayer, "MYHAND:pitch=1");
          PrependDecisionQueue("NOPASS", $currentPlayer, "-");
          PrependDecisionQueue("YESNO", $currentPlayer, "if you want to pitch 2 red cards");
        }
        return "";
      case "EVO246": PutPermanentIntoPlay($currentPlayer, $cardID);
        return "";
      case "EVO247":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO248":
        MZChooseAndDestroy($currentPlayer, "THEIRALLY:subtype=Angel");
        return "";
      case "EVO410":
        if (IsHeroAttackTarget()) PummelHit($otherPlayer);
        return "";
      case "EVO434":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO435":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO436":
        AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
        return "";
      case "EVO437":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=W");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon to attack an additional time");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "ADDITIONALUSE", 1);
        return "";
      case "EVO446":
        Draw($currentPlayer);
        MZMoveCard($currentPlayer, "MYHAND", "MYTOPDECK", silent:true);
        return "";
      case "EVO447":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card with Crank to get a steam counter", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:hasCrank=true");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZADDSTEAMCOUNTER", $currentPlayer, "-", 1);
        return "";
      case "EVO448":
        MZMoveCard($currentPlayer, "MYHAND:subtype=Item;maxCost=1", "MYITEMS", may:true);
        return "";
      case "EVO449":
        PlayAura("WTR225", $currentPlayer);
        return "";
      default: return "";
    }
  }

  function PhantomTidemawDestroy($player = -1, $index = -1)
  {
    global $mainPlayer;
    if($player == -1) $player = $mainPlayer;
    if($index == -1) $index = GetAuraIndex("EVO244", $player);
    if($index > -1) {
      $auras = &GetAuras($player);
      ++$auras[$index+3];
    }
  }

?>
