<?php

  function UPRTalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $CS_PlayIndex, $CS_NumRedPlayed;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "UPR084":
        $pitch = &GetPitch($currentPlayer);
        $numRed = 0;
        for($i=0; $i<count($pitch); $i+=PitchPieces()) if(PitchValue($pitch[$i]) == 1) ++$numRed;
        GainResources($currentPlayer, $numRed);
        return "";
      case "UPR085":
        GainResources($currentPlayer, 1);
        return "";
      case "UPR088":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR089":
        Draw($currentPlayer);
        Draw($currentPlayer);
        return "";
      case "UPR090":
        if(RuptureActive()) {
          $deck = new Deck($currentPlayer);
          $num = NumDraconicChainLinks();
          if($deck->Reveal($num)) {
            $cards = explode(",", $deck->Top(amount:$num));
            $numRed = 0;
            for($j = 0; $j < count($cards); ++$j) if(PitchValue($cards[$j]) == 1) ++$numRed;
            if($numRed > 0) {
              AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=C&THEIRCHAR:type=C&MYALLY&THEIRALLY", 1);
              AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target to deal ". $numRed ." damage.");
              AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
              AddDecisionQueue("MZDAMAGE", $currentPlayer, $numRed . ",DAMAGE," . $cardID, 1);
              AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
            }
          }
        }
        return "";
      case "UPR091":
        if(RuptureActive()) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR094":
        if($additionalCosts != "-") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR096":
        AddLayer("TRIGGER", $currentPlayer, $cardID);
        return "";
      case "UPR097":
        if(GetClassState($currentPlayer, $CS_NumRedPlayed) > 1) MZMoveCard($currentPlayer, "MYDISCARD:isSameName=UPR101", "MYHAND");
        return "";
      case "UPR099":
        if(RuptureActive()) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=C&THEIRCHAR:type=C&MYALLY&THEIRALLY", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target to deal 2 damage");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZDAMAGE", $currentPlayer, "2,DAMAGE," . $cardID, 1);
        }
        return "";
      case "UPR136":
        if(ShouldAutotargetOpponent($currentPlayer)) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "Target_Opponent");
          AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "CORONETPEAK", 1);
        } else {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target hero");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Yourself");
          AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "CORONETPEAK", 1);
        }
        return "";
      case "UPR137":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRALLY");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        return "";
      case "UPR141": case "UPR142": case "UPR143":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR144": case "UPR145": case "UPR146":
        if($cardID == "UPR144") $numFrostbites = 3;
        else if($cardID == "UPR145") $numFrostbites = 2;
        else $numFrostbites = 1;
        PlayAura("ELE111", ($currentPlayer == 1 ? 2 : 1), $numFrostbites, effectController: $currentPlayer);
        return "";
      case "UPR147": case "UPR148": case "UPR149":
        if($cardID == "UPR147") $cost = 3;
        else if($cardID == "UPR148") $cost = 2;
        else $cost = 1;
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to pay $cost to prevent an arsenal or ally from being frozen");
        AddDecisionQueue("BUTTONINPUT", $otherPlayer, "0," . $cost, 0, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRALLY&THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        if($from == "ARS") Draw($currentPlayer);
        return "";
      case "UPR183":
        if($target != "-") AddCurrentTurnEffect($cardID, $currentPlayer, $from, GetMZCard($currentPlayer, $target));
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        return "";
      case "UPR191": case "UPR192": case "UPR193":
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to pay to buff Flex", 1);
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "0," . 2, 0, 1);
        AddDecisionQueue("PAYRESOURCES", $currentPlayer, "<-", 1);
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        return "";
      case "UPR194": case "UPR195": case "UPR196":
        if(PlayerHasLessHealth($currentPlayer)) { GainHealth(1, $currentPlayer); }
        return "";
      case "UPR197": case "UPR198": case "UPR199":
        if($cardID == "UPR197") $numCards = 4;
        else if($cardID == "UPR198") $numCards = 3;
        else $numCards = 2;
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, $numCards . "-", 1);
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SIFT", 1);
        return "";
      case "UPR200": case "UPR201": case "UPR202":
        if($cardID == "UPR200") $maxCost = 2;
        else if($cardID == "UPR201") $maxCost = 1;
        else $maxCost = 0;
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer,"MYDISCARD:maxCost=".$maxCost.";type=AA&MYDISCARD:maxCost=".$maxCost.";type=A&THEIRDISCARD:maxCost=".$maxCost.";type=AA&THEIRDISCARD:maxCost=".$maxCost.";type=A");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a graveyard card", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}", 1);
        AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "<1> recurred from Strategic Planning", 1);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR212": case "UPR213": case "UPR214":
        if($from == "ARS") GiveAttackGoAgain();
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-".$currentPlayer, 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "UPR215": case "UPR216": case "UPR217":
        if($cardID == "UPR215") $amount = 3;
        else if($cardID == "UPR216") $amount = 2;
        else $amount = 1;
        GainHealth($amount, $currentPlayer);
        return "";
      case "UPR221": case "UPR222": case "UPR223":
        if($target != "-") AddCurrentTurnEffect($cardID, $currentPlayer, $from, GetMZCard($currentPlayer, $target));
        if(PlayerHasLessHealth($currentPlayer)) GainHealth(1, $currentPlayer);
        return "";
      default: return "";
    }
  }

  function UPRTalentHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "UPR087":
        if(IsHeroAttackTarget() && RuptureActive()) {
          AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
          AddDecisionQueue("MODDEFCOUNTER", $defPlayer, "-1", 1);
          AddDecisionQueue("DESTROYEQUIPDEF0", $mainPlayer, "-", 1);
        }
        break;
      case "UPR093":
        if(IsHeroAttackTarget() && RuptureActive()) DestroyArsenal($defPlayer, effectController:$mainPlayer);
        break;
      case "UPR100":
        MZMoveCard($mainPlayer, "MYDISCARD:isSameName=UPR101", "MYHAND");
        AddDecisionQueue("OP", $mainPlayer, "GIVEATTACKGOAGAIN", 1);
        break;
      case "UPR187":
        if(IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "UPR188":
        if(IsHeroAttackTarget()) {
          $hand = &GetHand($defPlayer);
          LoseHealth(count($hand)/HandPieces(), $defPlayer);
          WriteLog("Player $defPlayer loses " . count($hand)/HandPieces() . " health");
        } 
        break;
      default: break;
    }
  }

  function HasRupture($cardID)
  {
    switch($cardID)
    {
      case "UPR087": case "UPR090": case "UPR091": return true;
      case "UPR093": case "UPR098": case "UPR099": return true;
      default: return false;
    }
  }

  function RuptureActive($beforePlay=false, $notAttack=false)
  {
    global $combatChainState;
    if($notAttack) $target = 4;
    else $target = ($beforePlay ? 3 : 4);
    if(NumChainLinks() >= $target) return true;
    return false;
  }

  function NumDraconicChainLinks()
  {
    global $CombatChain, $mainPlayer, $chainLinkSummary;
    $numLinks = 0;
    for($i=0; $i<count($chainLinkSummary); $i+=ChainLinkSummaryPieces()) {
      if(DelimStringContains($chainLinkSummary[$i+2], "DRACONIC")) ++$numLinks;
    }
    if($CombatChain->HasCurrentLink() && TalentContains($CombatChain->AttackCard()->ID(), "DRACONIC", $mainPlayer)) ++$numLinks;
    return $numLinks;
  }

  function NumChainLinksWithName($name)
  {
    global $mainPlayer, $chainLinkSummary, $combatChain;
    $count = 0;
    for($i=0; $i<count($chainLinkSummary); $i+=ChainLinkSummaryPieces())
    {
      if(ChainLinkNameContains($i, $name)) ++$count;
    }
    $currentAttackNames = GetCurrentAttackNames();
    for($i=0; $i<count($currentAttackNames); ++$i) {
      if($currentAttackNames[$i] == $name) {
        ++$count;
        break;
      }
    }
    return $count;
  }

  function ChainLinkNameContains($index, $name)
  {
    global $mainPlayer, $chainLinkSummary;
    if(SearchCurrentTurnEffects("OUT183", $mainPlayer)) return false;
    if($index >= count($chainLinkSummary)) return false;
    $attackNames = explode(",", $chainLinkSummary[$index+4]);
    for($i=0; $i<count($attackNames); ++$i) {
      $attackName = GamestateUnsanitize($attackNames[$i]);
      if($attackName == $name) return true;
    }
    return false;
  }

  function ThawIndices($player) {
    $iceAfflictions = SearchMultiZoneFormat(SearchAura($player, "", "Affliction", -1, -1, "", "ICE"), "MYAURAS");
    $frostBites = SearchMultiZoneFormat(SearchAurasForCard("ELE111", $player), "MYAURAS");
    $search = CombineSearches($iceAfflictions, $frostBites);
    $myFrozenArsenal = SearchMultiZoneFormat(SearchArsenal($player, frozenOnly:true), "MYARS");
    $search = CombineSearches($search, $myFrozenArsenal);
    $myFrozenAllies = SearchMultiZoneFormat(SearchAllies($player, frozenOnly:true), "MYALLY");
    $search = CombineSearches($search, $myFrozenAllies);
    $myFrozenCharacter = SearchMultiZoneFormat(SearchCharacter($player, frozenOnly:true), "MYCHAR");
    $search = CombineSearches($search, $myFrozenCharacter);
    return $search;
  }

?>
