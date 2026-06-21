<?php

  function UPRTalentPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $CS_PlayIndex, $CS_NumRedPlayed;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "flamescale_furnace":
        $pitch = &GetPitch($currentPlayer);
        $numRed = 0;
        $pitchCount = count($pitch);
        for($i=0; $i<$pitchCount; $i+=PitchPieces()) if(PitchValue($pitch[$i]) == 1) ++$numRed;
        GainResources($currentPlayer, $numRed);
        return "";
      case "sash_of_sandikai":
        GainResources($currentPlayer, 1);
        return "";
      case "uprising_red":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "tome_of_firebrand_red":
        Draw($currentPlayer, num:2);
        return "";
      case "red_hot_red":
        if(RuptureActive()) {
          $deck = new Deck($currentPlayer);
          $num = NumDraconicChainLinks();
          if($deck->Reveal($num)) {
            $cards = explode(",", $deck->Top(amount:$num));
            $numRed = 0;
            $cardsCount = count($cards);
            for($j = 0; $j < $cardsCount; ++$j) if(PitchValue($cards[$j]) == 1) ++$numRed;
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
      case "rise_up_red":
        if(RuptureActive()) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "burn_away_red":
        if($additionalCosts != "-") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "flamecall_awakening_red":
        AddLayer("TRIGGER", $currentPlayer, $cardID);
        return "";
      case "searing_touch_red":
        if(RuptureActive()) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRCHAR:type=C&THEIRALLY&MYCHAR:type=C&MY&MYALLY", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target to deal 2 damage");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZDAMAGE", $currentPlayer, "2,DAMAGE," . $cardID, 1);
        }
        return "";
      case "coronet_peak":
        $targ = (str_contains($target, "THEIRCHAR")) ? "Target_Opponent" : "Target_Yourself";
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $targ);
        AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "CORONETPEAK", 1);
        return "";
      case "glacial_horns":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRALLY");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        return "";
      case "isenhowl_weathervane_red": case "isenhowl_weathervane_yellow": case "isenhowl_weathervane_blue":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "arctic_incarceration_red": case "arctic_incarceration_yellow": case "arctic_incarceration_blue":
        $numFrostbites = match($cardID) { "arctic_incarceration_red" => 3, "arctic_incarceration_yellow" => 2, default => 1 };
        PlayAura("frostbite", $currentPlayer == 1 ? 2 : 1, $numFrostbites, effectController: $currentPlayer);
        return "";
      case "cold_snap_red": case "cold_snap_yellow": case "cold_snap_blue":
        $cost = match($cardID) { "cold_snap_red" => 3, "cold_snap_yellow" => 2, default => 1 };
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
      case "helios_mitre":
        if($target != "-") AddCurrentTurnEffect($cardID, $currentPlayer, $from, GetMZCard($currentPlayer, $target));
        AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
        return "";
      case "flex_red": case "flex_yellow": case "flex_blue":
        $hand = &GetHand($currentPlayer);
        $resources = &GetResources($currentPlayer);
        if (count($hand) > 0 || $resources[0] >= 2)
        {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to pay to buff " . CardLink($cardID), 1);
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "0,2", 0, 1);
          AddDecisionQueue("PAYRESOURCES", $currentPlayer, "<-", 1);
        }
        else {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
        }
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
        return "";
      case "fyendals_fighting_spirit_red": case "fyendals_fighting_spirit_yellow": case "fyendals_fighting_spirit_blue":
        if(PlayerHasLessHealth($currentPlayer)) { GainHealth(1, $currentPlayer); }
        return "";
      case "sift_red": case "sift_yellow": case "sift_blue":
        $numCards = match($cardID) { "sift_red" => 4, "sift_yellow" => 3, default => 2 };
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, $numCards . "-", 1);
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SIFT", 1);
        return "";
      case "strategic_planning_red": case "strategic_planning_yellow": case "strategic_planning_blue":
        $maxCost = match($cardID) { "strategic_planning_red" => 2, "strategic_planning_yellow" => 1, default => 0 };
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer,"MYDISCARD:maxCost=".$maxCost.";type=AA&MYDISCARD:maxCost=".$maxCost.";type=A&THEIRDISCARD:maxCost=".$maxCost.";type=AA&THEIRDISCARD:maxCost=".$maxCost.";type=A");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a graveyard card", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}", 1);
        AddDecisionQueue("MZADDZONE", $currentPlayer, "OWNERSBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "<1> recurred from " . CardLink($cardID), 1);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "trade_in_red": case "trade_in_yellow": case "trade_in_blue":
        if($from == "ARS") GiveAttackGoAgain();
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-".$currentPlayer, 1);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "healing_balm_red": case "healing_balm_yellow": case "healing_balm_blue":
        $amount = match($cardID) { "healing_balm_red" => 3, "healing_balm_yellow" => 2, default => 1 };
        GainHealth($amount, $currentPlayer);
        return "";
      default: return "";
    }
  }

  function UPRTalentHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "breaking_point_red":
        if(IsHeroAttackTarget() && RuptureActive()) DestroyArsenal($defPlayer, effectController:$mainPlayer);
        break;
      case "stoke_the_flames_red":
        MZMoveCard($mainPlayer, "MYDISCARD:isSameName=phoenix_flame_red", "MYHAND");
        AddDecisionQueue("OP", $mainPlayer, "GIVEATTACKGOAGAIN", 1);
        break;
      case "erase_face_red":
        if(IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
          AddNextTurnEffect($cardID, $defPlayer);
        }
        break;
      case "vipox_red":
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
    return GeneratedHasRupture($cardID);
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
    $chainLinkSummaryCount = count($chainLinkSummary);
    for($i=0; $i<$chainLinkSummaryCount; $i+=ChainLinkSummaryPieces()) {
      if(DelimStringContains($chainLinkSummary[$i+2], "DRACONIC")) ++$numLinks;
    }
    if($CombatChain->HasCurrentLink() && TalentContains($CombatChain->AttackCard()->ID(), "DRACONIC", $mainPlayer)) ++$numLinks;
    return $numLinks;
  }

  function NumChainLinksWithName($name)
  {
    global $mainPlayer, $chainLinkSummary, $combatChain;
    $count = 0;
    $chainLinkSummaryCount = count($chainLinkSummary);
    for($i=0; $i<$chainLinkSummaryCount; $i+=ChainLinkSummaryPieces())
    {
      if(ChainLinkNameContains($i, $name)) ++$count;
    }
    $currentAttackNames = GetCurrentAttackNames();
    $currentAttackNamesCount = count($currentAttackNames);
    for($i=0; $i<$currentAttackNamesCount; ++$i) {
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
    if(SearchCurrentTurnEffects("amnesia_red", $mainPlayer)) return false;
    if($index >= count($chainLinkSummary)) return false;
    $attackNames = explode(",", $chainLinkSummary[$index+4]);
    $attackNamesCount = count($attackNames);
    for($i=0; $i<$attackNamesCount; ++$i) {
      $attackName = GamestateUnsanitize($attackNames[$i]);
      if($attackName == $name) return true;
    }
    return false;
  }

  function ThawIndices($player) {
    $iceAfflictions = SearchMultiZoneFormat(SearchAura($player, "", "Affliction", -1, -1, "", "ICE"), "MYAURAS");
    $frostbites = SearchMultiZoneFormat(SearchAurasForCard("frostbite", $player), "MYAURAS");
    $search = CombineSearches($iceAfflictions, $frostbites);
    $equipmentSlotFrostbites = SearchMultiZoneFormat(SearchCharacterForCards("frostbite", $player), "MYCHAR");
    $search = CombineSearches($search, $equipmentSlotFrostbites);
    $myFrozenArsenal = SearchMultiZoneFormat(SearchArsenal($player, frozenOnly:true), "MYARS");
    $search = CombineSearches($search, $myFrozenArsenal);
    $myFrozenAllies = SearchMultiZoneFormat(SearchAllies($player, frozenOnly:true), "MYALLY");
    $search = CombineSearches($search, $myFrozenAllies);
    $myFrozenCharacter = SearchMultiZoneFormat(SearchCharacter($player, frozenOnly:true), "MYCHAR");
    $search = CombineSearches($search, $myFrozenCharacter);
    return $search;
  }

