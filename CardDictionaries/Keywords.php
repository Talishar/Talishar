<?php

  function HasCrank($cardID, $player)
  {
    $char = &GetPlayerCharacter($player);
    if(CardNameContains($cardID, "Hyper Driver", $player) && (SearchCharacterForCard($player, "maxx_the_hype_nitro") || SearchCharacterForCard($player, "maxx_nitro")) && $char[1] < 3) return true;
    return GeneratedHasCrank($cardID);
  }

  function Crank($player, $index, $mainPhase="True", $zone="MYITEMS")
  {
    global $mainPlayer;
    $MZZone = match($zone) {
      "MYITEMS" => GetItems($player),
      "MYALLY" => GetAllies($player),
      default => GetItems($player)
    };
    $message = "Do you want to Crank your " . CardLink($MZZone[$index], $MZZone[$index]) ."?";
    if ($player != $mainPlayer) $mainPhase = "False"; //you can crank on the opponent's turn, you just won't get an action point
    if ($mainPhase != "True") $message .= " You won't get an action point.";
    PrependDecisionQueue("PASSPARAMETER", $player, "{0}");
    PrependDecisionQueue("OP", $player, "DOCRANK-MainPhase$mainPhase-$zone", 1);
    PrependDecisionQueue("PASSPARAMETER", $player, $index, 1);
    PrependDecisionQueue("NOPASS", $player, "-");
    PrependDecisionQueue("DOCRANK", $player, "-");
    PrependDecisionQueue("SETDQCONTEXT", $player, $message, 1);
    PrependDecisionQueue("SETDQVAR", $player, "0");
  }

  function DoCrank($player, $index, $mainPhase=true, $zone="MYITEMS")
  {
    global $CS_NumCranked;
    if ($zone == "MYALLY") {
      $MZZone = &GetAllies($player);
      $steamInd = 12;
    }
    else {
      $MZZone = &GetItems($player);
      $steamInd = 1;
    }
    if($MZZone[$index+$steamInd] <= 0) return;
    --$MZZone[$index+$steamInd];
    if($mainPhase) GainActionPoints(1, $player);
    WriteLog("Player $player cranked");
    IncrementClassState($player, $CS_NumCranked);
    $char = GetPlayerCharacter($player);
    for ($i = 0; $i < count($char); $i += CharacterPieces()){
      switch ($char[$i]) {
        case "puffin_hightail":
        case "puffin":
          if (GetClassState($player, $CS_NumCranked) == 2 && SearchCharacterActive($player, $char[$i])) AddLayer("TRIGGER", $player, $char[$i]);
          break;
        default:
          break;
      }
    }
    if(CardName($MZZone[$index]) == "Hyper Driver" && ($MZZone[$index+$steamInd] <= 0)) DestroyItemForPlayer($player, $index);
  }

  function ProcessTowerEffect($cardID)
  {
    global $CombatChain, $mainPlayer, $defPlayer, $layers;
    if(!IsHeroAttackTarget()) return;
    if(CardType($cardID) == "AA" && SearchCurrentTurnEffects("tarpit_trap_yellow", $mainPlayer, count($layers) <= LayerPieces())) {
      WriteLog("Tower effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
      return true;
    }
    $card = GetClass($cardID, $mainPlayer);
    if ($card != "-") return $card->ProcessTowerEffect();
    switch($cardID)
    {
      case "colossal_bearing_red":
        AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP1", 1);
        AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
        AddDecisionQueue("DESTROYCHARACTER", $defPlayer, "-", 1);
        break;
      case "smack_of_reality_red":
        MZDestroy($mainPlayer, SearchMultizone($mainPlayer, "THEIRAURAS:type=T"), $mainPlayer);
      default: break;
    }
  }

  function Scrap($player)
  {
    global $CS_AdditionalCosts;
    MZMoveCard($player, "MYDISCARD:type=E&MYDISCARD:subtype=Item&MYDISCARD:subtype=Evo", "MYBANISH,GY,-", may:true);
    AddDecisionQueue("APPENDCLASSSTATE", $player, $CS_AdditionalCosts . "-SCRAP", 1);
  }

  function Clash($cardID, $effectController="")
  {
    if(IsAllyAttacking()){
      WriteLog("<span style='color:red;'>No clash is done because there is no attacking hero when allies attack.</span>");
    } else {
      WriteLog("⚔️CLASH⚔️");
      ClashLogic($cardID, $effectController);
      AddDecisionQueue("WONCLASH", 1, $cardID . "," . $effectController);
    }
  }

  function ClashLogic($cardID, $effectController="")
  {
    global $mainPlayer, $defPlayer, $dqVars;
    $p1Power = ""; $p2Power = "";
    $switched = SearchCurrentTurnEffects("the_old_switcheroo_blue", 1) || SearchCurrentTurnEffects("the_old_switcheroo_blue", 2);
    for($i=1; $i<=2; ++$i) {
      $playerID = $switched ? 3 - $i : $i;
      $deck = new Deck($playerID);
      if($deck->Reveal(1, $switched)) {
        $power = $deck->Empty() ? 0 : ModifiedPowerValue($deck->Top(), $playerID, "DECK", source:$cardID);
        if(!TypeContains($deck->Top(), "AA") && $power == 0) $power = ""; //If you reveal a card with {p} and the opponent reveals a card without {p}, you win the clash.
        if($i == 1) $p1Power = $power;
        else $p2Power = $power;
      }
    }
    //DQVAR 0 = Winner
    if($p1Power >= 0 && ($p1Power > $p2Power || $p2Power == "")) {
      $p2Deck = new Deck(2);
      if (!$p2Deck->Empty() && $p2Deck->Top() == "overturn_the_results_blue") {
        WriteLog("THE RESULTS OF THE CLASH WERE OVERTURNED!");
        Boo(2);
        $dqVars[0] = 2;
        VictorAbility(1, $cardID, $effectController);
      }
      else {
        $dqVars[0] = 1;
        VictorAbility(2, $cardID, $effectController);
      }
    }
    else if($p2Power >= 0 && ($p2Power > $p1Power || $p1Power == "")) {
      $p1Deck = new Deck(1);
      if (!$p1Deck->Empty() && $p1Deck->Top() == "overturn_the_results_blue") {
        WriteLog("THE RESULTS OF THE CLASH WERE OVERTURNED!");
        Boo(1);
        $dqVars[0] = 1;
        VictorAbility(2, $cardID, $effectController);
      }
      else {
        $dqVars[0] = 2;
        VictorAbility(1, $cardID, $effectController);
      }
    }
    else {
      $mainDeck = new Deck($mainPlayer);
      $defDeck = new Deck($defPlayer);
      $overturned = false;
      // the main player will almost always choose the defending player to apply their effect first
      if (!$defDeck->Empty() && $defDeck->Top() == "overturn_the_results_blue") {
        $dqVars[0] = $defPlayer;
        WriteLog("THE RESULTS OF THE CLASH WERE OVERTURNED!");
        Boo($defPlayer);
        $overturned = true;
      }
      if (!$mainDeck->Empty() && $mainDeck->Top() == "overturn_the_results_blue") {
        $dqVars[0] = $mainPlayer;
        WriteLog("THE RESULTS OF THE CLASH WERE OVERTURNED!");
        Boo($mainPlayer);
        $overturned = true;
      }
      if (!$overturned) {
        $dqVars[0] = 0;
        VictorAbility($mainPlayer, $cardID, $effectController);
        VictorAbility($defPlayer, $cardID, $effectController);
      }
      else {
        $loser = $dqVars[0] == 1 ? 2 : 1;
        VictorAbility($loser, $cardID, $effectController);
      }
    }
  }

  function WonClashAbility($playerID, $cardID, $effectController="", $switchedPlayers=[false, false]) {
    global $mainPlayer, $CS_NumClashesWon, $combatChainState, $CCS_WeaponIndex, $dqVars, $defPlayer;
    $otherPlayer = $playerID == 1 ? 2 : 1;
    
    $deck = new Deck($playerID);
    $switched = $switchedPlayers[0] || $switchedPlayers[1];
    if (!$switched) {
      switch ($deck->Top()) {
        case "the_golden_son_yellow":
        case "thunk_red": case "thunk_yellow": case "thunk_blue":
        case "wallop_red": case "wallop_yellow": case "wallop_blue":
          AddLayer("TRIGGER", $playerID, $deck->Top());
          break;
        default:
          $topCard = GetClass($deck->Top(), $effectController);
          if ($topCard != "-") $topCard->WonClashWithAbility($playerID);
          break;
      }
    }
    WriteLog("Player " . $playerID . " won the Clash");
    $numClashesWon = GetClassState($playerID, $CS_NumClashesWon) + 1;
    SetClassState($playerID, $CS_NumClashesWon, $numClashesWon);
    $card = GetClass($cardID, $playerID);
    if ($card != "-") $card->WonClashAbility($playerID, $switched);
    switch($cardID)
    {
      case "millers_grindstone":
        $otherPlayer = $playerID == 1 ? 2 : 1;
        $otherDeck = new Deck($otherPlayer);
        if($otherDeck->Empty()) {
          break;
        }
        if ($playerID == $mainPlayer) DestroyTopCard($otherPlayer);
        else {
          $character = &GetPlayerCharacter($mainPlayer);
          $index = $combatChainState[$CCS_WeaponIndex];
          --$character[$index + 3];
        }
        break;
      case "stonewall_impasse":
        if($playerID == $defPlayer) AddCurrentTurnEffect($cardID, $defPlayer, "CC");
        break;
      case "trounce_red": break;
      case "trounce_red-0": break;
      case "trounce_red-1":
        if($playerID == 1) {
          PutItemIntoPlayForPlayer("gold", $playerID, effectController:$defPlayer);
          PlayAura("might", $playerID); 
          PlayAura("vigor", $playerID); 
        }
        break;
      case "trounce_red-2":
        if($playerID == 2) {
          PutItemIntoPlayForPlayer("gold", $playerID, effectController:$defPlayer);
          PlayAura("might", $playerID); 
          PlayAura("vigor", $playerID); 
        }
        break;
      case "clash_of_might_red": case "clash_of_might_yellow": case "clash_of_might_blue":
        PlayAura("might", $playerID);
        break;
      case "test_of_might_red":
        PlayAura("might", $playerID);
        break;
      case "clash_of_agility_red": case "clash_of_agility_yellow": case "clash_of_agility_blue":
        PlayAura("agility", $playerID);
        break;
      case "test_of_agility_red":
        PlayAura("agility", $playerID);
        break;
      case "clash_of_vigor_red": case "clash_of_vigor_yellow": case "clash_of_vigor_blue":
        PlayAura("vigor", $playerID);
        break;
      case "test_of_vigor_red": case "rising_energy_red": case "rising_energy_yellow":
        PlayAura("vigor", $playerID);
        break;
      case "test_of_strength_red":
        PutItemIntoPlayForPlayer("gold", $playerID, effectController:$effectController);
        break;
      case "clash_of_bravado_yellow":
        AddDecisionQueue("MULTIZONEINDICES", $playerID, "THEIRAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $playerID, "<-", 1);
        AddDecisionQueue("SHOWCHOSENCARD", $playerID, "<-", 1);
        AddDecisionQueue("MZDESTROY", $playerID, "<-", 1);
        break;
      case "test_of_iron_grip_red":
        PummelHit($otherPlayer, effectController:$defPlayer);
        break;
      case "pec_perfect_red":
      case "daily_grind_blue":
        $deck = new Deck($otherPlayer);
        if($deck->Empty()) {
          break;
        }
        DestroyTopCard($otherPlayer);
        break;
      case "clash_of_mountains_red":
      case "clash_of_mountains_yellow":
      case "clash_of_mountains_blue":
        PlayAura("seismic_surge", $playerID, 1, true, effectController:$effectController, effectSource:$cardID);
        break;
      case "clash_of_heads_yellow":
      case "clash_of_chests_yellow":
      case "clash_of_arms_yellow":
      case "clash_of_legs_yellow":
      case "clash_of_shields_yellow":
        $subtype = match ($cardID) {
          "clash_of_heads_yellow" => "Head",
          "clash_of_chests_yellow" => "Chest",
          "clash_of_arms_yellow" => "Arms",
          "clash_of_legs_yellow" => "Legs",
          "clash_of_shields_yellow" => "Off-Hand",
        };
        $index = SearchCharacterIndexSubtype($otherPlayer, $subtype);
        $otherChar = &GetPlayerCharacter($otherPlayer);
        if ($index != -1 && $otherChar[$index + 1] > 1) --$otherChar[$index + 4];
        else {
          if($otherPlayer != $mainPlayer) PlayerLoseHealth(1, $otherPlayer); //Log as self damaged
          else LoseHealth(1, $otherPlayer); //Log as damage
        }
        break;
      default: break;
    }
    if (SearchCurrentTurnEffects("the_old_switcheroo_blue-DISCARD", $playerID, true)) {
      PummelHit($otherPlayer, context: "You fell for the old switcheroo! Discard a card!", effectController:$effectController);
    }
  }

  function VictorAbility($playerID, $cardID, $effectController="") {
    $otherPlayer = $playerID == 1 ? 2 : 1;
    $char = &GetPlayerCharacter($playerID);
    $hero = ShiyanaCharacter($char[0], $playerID);
    $goldIndices = GetGoldIndices($playerID);
    if(($hero == "victor_goldmane_high_and_mighty" || $hero == "victor_goldmane") && CountItem("gold", $playerID) > 0 && $char[1] == 2) {
      $char[1] = 1;
      //This all has to be prepend for the case where it's a Victor mirror, one player wins, then the re-do causes that player to win
      PrependDecisionQueue("CLASH", $effectController, $cardID, 1);
      PrependDecisionQueue("ADDBOTTOMREMOVETOP", $otherPlayer, $hero, 1);
      PrependDecisionQueue("NOTEQUALPASS", $playerID, "Target_Opponent");
      PrependDecisionQueue("PASSPARAMETER", $playerID, "{1}");
      PrependDecisionQueue("CLASH", $effectController, $cardID, 1);
      PrependDecisionQueue("ADDBOTTOMREMOVETOP", $playerID, $hero, 1);
      PrependDecisionQueue("EQUALPASS", $playerID, "Target_Opponent", 1);
      PrependDecisionQueue("SETDQVAR", $playerID, "1");
      PrependDecisionQueue("BUTTONINPUT", $playerID, "Target_Opponent,Target_Yourself", 1);
      PrependDecisionQueue("SETDQCONTEXT", $playerID, "Choose target hero", 1);
      if(str_contains($goldIndices, "MYCHAR")) {
        PrependDecisionQueue("MZDESTROY", $playerID, "-", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $playerID, "<-", 1);
        PrependDecisionQueue("PASSPARAMETER", $playerID, $goldIndices, 1);
      } else PrependDecisionQueue("FINDANDDESTROYITEM", $playerID, "gold-1", 1);
      PrependDecisionQueue("REMOVECURRENTTURNEFFECT", $playerID, $hero."-2", 1);
      PrependDecisionQueue("REMOVECURRENTTURNEFFECT", $otherPlayer, "the_old_switcheroo_blue", 1);
      PrependDecisionQueue("REMOVECURRENTTURNEFFECT", $playerID, "the_old_switcheroo_blue", 1);
      PrependDecisionQueue("NOPASS", $playerID, "-", 1);
      PrependDecisionQueue("YESNO", $playerID, "if_you_want_to_destroy_1_" . CardLink("gold", "gold") ."_to_clash_again", 1);
      PrependDecisionQueue("SETDQVAR", $playerID, "1");
      PrependDecisionQueue("PASSPARAMETER", $playerID, "NO");
    }
  }

  function AskWager($cardID) {
    global $currentPlayer;
    AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID);
    AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to wager for <0>?");
    AddDecisionQueue("YESNO", $currentPlayer, "-");
    AddDecisionQueue("NOPASS", $currentPlayer, "-");
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID . "!PLAY", 1);

    AddOnWagerEffects();
  }

  function AddOnWagerEffects($canPass = true) {
    global $currentPlayer, $CCS_WagersThisLink;
    AddDecisionQueue("INCREMENTCOMBATCHAINSTATE", $currentPlayer, $CCS_WagersThisLink, $canPass);
    $char = &GetPlayerCharacter($currentPlayer);
    $cardID = ShiyanaCharacter($char[0]);
    if($char[1] == 2 && ($cardID == "betsy_skin_in_the_game" || $cardID == "betsy")) {
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, $canPass ? 1 : 0);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to pay 2 for <0> to give overpower and +1?", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "-", 1, 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, 2, 1);
      AddDecisionQueue("PAYRESOURCES", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
    }
  }

  function ResolveWagers($chainClosed=false) {
    global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt, $currentTurnEffects, $EffectContext, $combatChain;
    $wonWager = $combatChainState[$CCS_DamageDealt] > 0 ? $mainPlayer : $defPlayer;
    $lostWager = $wonWager == $mainPlayer ? $defPlayer : $mainPlayer;
    $numWagersWon = 0;
    $amount = 1;
    if(isset($combatChain[0])) $EffectContext = $combatChain[0];
    if(SearchCurrentTurnEffects("double_down_red", $wonWager)) $amount += CountCurrentTurnEffects("double_down_red", $wonWager);
    for($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
      $hasWager = $chainClosed ? false : true;
      if(isset($currentTurnEffects[$i])) {
        switch($currentTurnEffects[$i]) {
          case "good_time_chapeau":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("might", $wonWager, $amount);
              PlayAura("vigor", $wonWager, $amount);
            }
            break;
          case "bet_big_red":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PutItemIntoPlayForPlayer("gold", $wonWager, number:$amount, effectController:$mainPlayer);
              PlayAura("might", $wonWager, $amount);
              PlayAura("vigor", $wonWager, $amount);
            }
            break;
          case "big_bop_red": case "big_bop_yellow": case "big_bop_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("vigor", $wonWager, $amount);
            }
            break;
          case "bigger_than_big_red": case "bigger_than_big_yellow": case "bigger_than_big_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("might", $wonWager, $amount);
            }
            break;
          case "prized_galea":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PutItemIntoPlayForPlayer("gold", $wonWager, number:$amount, effectController:$mainPlayer);
            }
            break;
          case "up_the_ante_blue-1":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("agility", $wonWager, $amount);
            }
            break;
          case "up_the_ante_blue-2":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PutItemIntoPlayForPlayer("gold", $wonWager, number:$amount, effectController:$mainPlayer);
            }
            break;
          case "up_the_ante_blue-3":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("vigor", $wonWager, $amount);
            }
            break;
          case "edge_ahead_red": case "edge_ahead_yellow": case "edge_ahead_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("agility", $wonWager, $amount);
            }
            break;
          case "hold_em_red": case "hold_em_yellow": case "hold_em_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("vigor", $wonWager, $amount);
            }
            break;
          case "wage_might_red": case "wage_might_yellow": case "wage_might_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("might", $wonWager, $amount);
            }
            break;
          case "wage_agility_red": case "wage_agility_yellow": case "wage_agility_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("agility", $wonWager, $amount);
            }
            break;
          case "wage_vigor_red": case "wage_vigor_yellow": case "wage_vigor_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PlayAura("vigor", $wonWager, $amount);
            }
            break;
          case "wage_gold_red": case "wage_gold_yellow": case "wage_gold_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PutItemIntoPlayForPlayer("gold", $wonWager, number:$amount, effectController:$mainPlayer);
            }
            break;
          case "money_where_ya_mouth_is_red": case "money_where_ya_mouth_is_yellow": case "money_where_ya_mouth_is_blue":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              PutItemIntoPlayForPlayer("gold", $wonWager, number:$amount, effectController:$mainPlayer);
            }
            break;
          case "drink_em_under_the_table_red":
            RemoveCurrentTurnEffect($i);
            if (!$chainClosed) {
              Draw($wonWager);
              PummelHit($lostWager);
            }
            break;
          default:
            $hasWager = false;
            break;
        }
      }
      if($hasWager) ++$numWagersWon;
    }
    if($numWagersWon > 0 && $wonWager == $mainPlayer) {
      $char = &GetPlayerCharacter($mainPlayer);
      $hero = ShiyanaCharacter($char[0]);
      if($char[1] == 2 && ($hero == "olympia_prized_fighter" || $hero == "olympia")) {
        PutItemIntoPlayForPlayer("gold", $mainPlayer, effectController:$mainPlayer);
        WriteLog(CardLink($hero, $hero) . " wins the favor of the crowd!");
      }
    }
  }

  function HasUniversal($cardID)
  {
    return GeneratedHasUniversal($cardID);
  }

  function Transcend($player, $cardID, $from)
  {
    global $currentPlayer, $CS_Transcended;
    $otherplayer = $player == 1 ? 2 : 1;
    SetClassState($player, $CS_Transcended, 1);
    if(SearchCharacterAlive($player, "twelve_petal_kasaya")) {
      AddDecisionQueue("YESNO", $player, "if you want to gain a resource");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("GAINRESOURCES", $player, "1", 1);
    }
    if(substr($from, 0, 5) == "THEIR") AddPlayerHand($cardID, $otherplayer, "-");
    else AddPlayerHand($cardID, $player, "-");
  }

  /**
   * Decompose is a keyword added in ROS. Per LSS decompose gives you an option to banishes 2 earth and 1 action card for a bonus effect
   *
   * The result of "NOPASS" should be used to add the bonus effects. SPECIFICCARD dq events can be added right after calling decompose to run if the decompose succeeded.
   */
  function Decompose($player, $specificCardDQ, $target = "") {
    $actionBanishes = 1;
    $earthBanishes = 2;
    $totalBanishes = 3;
    // Only perform the action if we have the minimum # of cards that meet the requirement for total banishes.
    $countInDiscard = SearchCount(
      SearchRemoveDuplicates(
        CombineSearches(
          SearchDiscard($player, talent: "EARTH"),
          CombineSearches(
            SearchDiscard($player, "A"),
            SearchDiscard($player
            , "AA"))
          )
        )
      );
      // Must have the minimum # of earth cards too.
      $earthCountInDiscard = SearchCount(SearchDiscard($player, talent: "EARTH"));
      if($countInDiscard >= $totalBanishes && $earthCountInDiscard >= $earthBanishes) {
        // Earth Banishes
        for($i = 0; $i < $earthBanishes; $i++) {
          AddDecisionQueue("MULTIZONEINDICES", $player, "MYDISCARD:talent=EARTH", 1);
      switch ($earthBanishes - $i) {
        case 1:
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose 1 {{element|Earth|" . GetElementColorCode("EARTH") . "}} card to banish", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
          break;
        default:
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . $earthBanishes . " {{element|Earth|" . GetElementColorCode("EARTH") . "}} cards to banish (or pass)", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
          break;
      }
          AddDecisionQueue("MZBANISH", $player, "GY,-", 1);
          AddDecisionQueue("MZREMOVE", $player, "-", 1);
        }

        // Action banishes.
        for($i = 0; $i < $actionBanishes; $i++) {
          AddDecisionQueue("GETCARDSFORDECOMPOSE", $player, "MYDISCARD:type=A&MYDISCARD:type=AA", 1); // Modified MULTIZONEINDICES so if there are no actions it can be sent to the next dq and it will revert gamestate. Can't use "PASS" because YESNO "PASS" result is already present.
          AddDecisionQueue("REVERTGAMESTATEIFNULL", $player, "There aren't any more action cards! Try selecting different Earth cards.", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . ($actionBanishes - $i) . " action card(s) to banish", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZBANISH", $player, "GY,-", 1);
          AddDecisionQueue("MZREMOVE", $player, "-", 1);
        }
        AddDecisionQueue("SPECIFICCARD", $player, $specificCardDQ . "-" . $target, 1);
      }
      else WriteLog("Insufficient cards to decompose");
      return "";
  }
