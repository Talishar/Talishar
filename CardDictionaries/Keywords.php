<?php

  function HasCrank($cardID, $player)
  {
    $char = &GetPlayerCharacter($player);
    if(CardNameContains($cardID, "Hyper Driver", $player) && (SearchCharacterForCard($player, "EVO004") || SearchCharacterForCard($player, "EVO005")) && $char[1] < 3) return true;
    switch($cardID) {
      case "EVO070": case "EVO071": case "EVO072":
      case "EVO074":
      case "EVO078": case "EVO079": case "EVO080":
      case "EVO081": case "EVO082": case "EVO083":
      case "EVO084": case "EVO085": case "EVO086":
      case "EVO087": case "EVO088": case "EVO089":
      case "EVO090": case "EVO091": case "EVO092":
      case "EVO093": case "EVO094": case "EVO095":
      case "EVO096": case "EVO097": case "EVO098":
      case "AIO026":
        return true;
      default: return false;
    }
  }

  function Crank($player, $index, $mainPhase="True")
  {
    PrependDecisionQueue("OP", $player, "DOCRANK-MainPhase". $mainPhase, 1);
    PrependDecisionQueue("PASSPARAMETER", $player, $index, 1);
    PrependDecisionQueue("NOPASS", $player, "-");
    PrependDecisionQueue("DOCRANK", $player, "if you want to Crank");
    PrependDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to Crank", 1);
  }

  function DoCrank($player, $index, $mainPhase=true)
  {
    global $CS_NumCranked;
    $items = &GetItems($player);
    if($items[$index+1] <= 0) return;
    --$items[$index+1];
    if($mainPhase) GainActionPoints(1, $player);
    WriteLog("Player $player cranked");
    IncrementClassState($player, $CS_NumCranked);
    if(CardName($items[$index]) == "Hyper Driver" && ($items[$index+1] <= 0)) DestroyItemForPlayer($player, $index);
  }

  function ProcessTowerEffect($cardID)
  {
    global $CombatChain, $mainPlayer, $defPlayer, $layers;
    if(!IsHeroAttackTarget()) return;
    if(CardType($cardID) == "AA" && SearchCurrentTurnEffects("OUT108", $mainPlayer, count($layers) <= LayerPieces())) return true;
    switch($cardID)
    {
      case "TCC034": case "HVY062":
        AddDecisionQueue("FINDINDICES", $defPlayer, "EQUIP1", 1);
        AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
        AddDecisionQueue("DESTROYCHARACTER", $defPlayer, "-", 1);
        break;
      case "TCC036": case "HVY064":
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
    for($i=1; $i<=2; ++$i) {
      $deck = new Deck($i);
      if($deck->Reveal()) {
        $power = $deck->Empty() ? 0 : ModifiedAttackValue($deck->Top(), $i, "DECK", source:$cardID);
        if(!TypeContains($deck->Top(), "AA")) $power = ""; //If you reveal a card with {p} and the opponent reveals a card without {p}, you win the clash.
        if($i == 1) $p1Power = $power;
        else $p2Power = $power;
      }
    }
    //DQVAR 0 = Winner
    if($p1Power >= 0 && ($p1Power > $p2Power || $p2Power == "")) {
      $dqVars[0] = 1;
      VictorAbility(2, $cardID, $effectController);
    }
    else if($p2Power >= 0 && ($p2Power > $p1Power || $p1Power == "")) {
      $dqVars[0] = 2;
      VictorAbility(1, $cardID, $effectController);
    }
    else {
      $dqVars[0] = 0;
      VictorAbility($mainPlayer, $cardID, $effectController);
      VictorAbility($defPlayer, $cardID, $effectController);
    }
  }

  function WonClashAbility($playerID, $cardID, $effectController="") {
    global $mainPlayer, $CS_NumClashesWon, $combatChainState, $CCS_WeaponIndex, $dqVars, $defPlayer;
    WriteLog("Player " . $playerID . " won the Clash");
    $numClashesWon = GetClassState($playerID, $CS_NumClashesWon) + 1;
    SetClassState($playerID, $CS_NumClashesWon, $numClashesWon);
    $deck = new Deck($playerID);
    switch ($deck->Top()) {
      case "HVY059":
      case "HVY077": case "HVY078": case "HVY079":
      case "HVY080": case "HVY081": case "HVY082":
        AddLayer("TRIGGER", $playerID, $deck->Top());
        break;
      default:
        break;
    }
    switch($cardID)
    {
      case "HVY050":
        if ($playerID == $mainPlayer) DestroyTopCardOpponent($playerID);
        else {
          $character = &GetPlayerCharacter($mainPlayer);
          $index = $combatChainState[$CCS_WeaponIndex];
          --$character[$index + 3];
        }
        break;
      case "HVY052":
        if($playerID == $defPlayer) AddCurrentTurnEffect($cardID, $defPlayer, "CC");
        break;
      case "HVY061": break;
      case "HVY061-0": break;
      case "HVY061-1":
        if($playerID == 1) {
          PutItemIntoPlayForPlayer("DYN243", $playerID, effectController:$defPlayer);
          PlayAura("HVY241", $playerID); //Might
          PlayAura("HVY242", $playerID); //Vigor
        }
        break;
      case "HVY061-2":
        if($playerID == 2) {
          PutItemIntoPlayForPlayer("DYN243", $playerID, effectController:$defPlayer);
          PlayAura("HVY241", $playerID); //Might
          PlayAura("HVY242", $playerID); //Vigor
        }
        break;
      case "HVY137": case "HVY138": case "HVY139":
        PlayAura("HVY241", $playerID);//Might
        break;
      case "HVY141":
        PlayAura("HVY241", $playerID);//Might
        break;
      case "HVY157": case "HVY158": case "HVY159":
        PlayAura("HVY240", $playerID);//Agility
        break;
      case "HVY162":
        PlayAura("HVY240", $playerID);
        break;
      case "HVY177": case "HVY178": case "HVY179":
        PlayAura("HVY242", $playerID);//Vigor
        break;
      case "HVY182": case "HVY183": case "HVY184":
        PlayAura("HVY242", $playerID);//Vigor
        break;
      case "HVY239":
        PutItemIntoPlayForPlayer("DYN243", $playerID, effectController:$effectController);
        break;
      default: break;
    }
    }

  function VictorAbility($playerID, $cardID, $effectController="") {
    $otherPlayer = ($playerID == 1 ? 2 : 1);
    $char = &GetPlayerCharacter($playerID);
    $hero = ShiyanaCharacter($char[0], $playerID);
    if(($hero == "HVY047" || $hero == "HVY048") && CountItem("DYN243", $playerID) > 0 && $char[1] == 2) {
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
      if(SearchCharacterAlive($playerID, "HVY051")) {
        PrependDecisionQueue("MZDESTROY", $playerID, "-", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $playerID, "<-", 1);
        PrependDecisionQueue("MULTIZONEINDICES", $playerID, "MYITEMS:isSameName=DYN243&MYCHAR:cardID=HVY051", 1);
      } else PrependDecisionQueue("FINDANDDESTROYITEM", $playerID, "DYN243-1", 1);
      PrependDecisionQueue("REMOVECURRENTTURNEFFECT", $playerID, $hero."-2", 1);
      PrependDecisionQueue("NOPASS", $playerID, "-", 1);
      PrependDecisionQueue("YESNO", $playerID, "if_you_want_to_destroy_1_" . CardLink("DYN243", "DYN243") ."_to_clash_again", 1);
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
    AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID . "!PLAY", 1);

    AddOnWagerEffects();
  }

  function AddOnWagerEffects($canPass = true) {
    global $currentPlayer, $CCS_WagersThisLink;
    AddDecisionQueue("INCREMENTCOMBATCHAINSTATE", $currentPlayer, $CCS_WagersThisLink, $canPass);
    $char = &GetPlayerCharacter($currentPlayer);
    $cardID = ShiyanaCharacter($char[0]);
    if($char[1] == 2 && ($cardID == "HVY045" || $cardID == "HVY046")) {
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, $canPass ? 1 : 0);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to pay 2 for <0> to give overpower and +1?", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "-", 1, 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, 2, 1);
      AddDecisionQueue("PAYRESOURCES", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
    }
  }

  function ResolveWagers() {
    global $mainPlayer, $defPlayer, $combatChainState, $CCS_DamageDealt, $currentTurnEffects, $EffectContext, $combatChain;
    $wonWager = $combatChainState[$CCS_DamageDealt] > 0 ? $mainPlayer : $defPlayer;
    $lostWager = $wonWager == $mainPlayer ? $defPlayer : $mainPlayer;
    $numWagersWon = 0;
    $amount = 1;
    if(isset($combatChain[0])) $EffectContext = $combatChain[0];
    if(SearchCurrentTurnEffects("HVY176", $wonWager)) $amount += CountCurrentTurnEffects("HVY176", $wonWager);
    for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
      $hasWager = true;
      if(isset($currentTurnEffects[$i])) {
        switch($currentTurnEffects[$i]) {
          case "HVY055":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY241", $wonWager, $amount);//Might
            PlayAura("HVY242", $wonWager, $amount);//Vigor
            break;
          case "HVY057":
            RemoveCurrentTurnEffect($i);
            PutItemIntoPlayForPlayer("DYN243", $wonWager, number:$amount, effectController:$mainPlayer);//Gold
            PlayAura("HVY241", $wonWager, $amount);//Might
            PlayAura("HVY242", $wonWager, $amount);//Vigor
            break;
          case "HVY083": case "HVY084": case "HVY085":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY242", $wonWager, $amount);//Vigor
            break;
          case "HVY086": case "HVY087": case "HVY088":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY241", $wonWager, $amount);//Might
            break;
          case "HVY098":
            RemoveCurrentTurnEffect($i);
            PutItemIntoPlayForPlayer("DYN243", $wonWager, number:$amount, effectController:$mainPlayer);//Gold
            break;
          case "HVY103-1":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY240", $wonWager, $amount);//Agility
            break;
          case "HVY103-2":
            RemoveCurrentTurnEffect($i);
            PutItemIntoPlayForPlayer("DYN243", $wonWager, number:$amount, effectController:$mainPlayer);//Gold
            break;
          case "HVY103-3":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY242", $wonWager, $amount);//Vigor
            break;
          case "HVY124": case "HVY125": case "HVY126":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY240", $wonWager, $amount);//Agility
            break;
          case "HVY130": case "HVY131": case "HVY132":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY242", $wonWager, $amount);//Vigor
            break;
          case "HVY149": case "HVY150": case "HVY151":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY241", $wonWager, $amount);//Might
            break;
          case "HVY169": case "HVY170": case "HVY171":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY240", $wonWager, $amount);//Agility
            break;
          case "HVY189": case "HVY190": case "HVY191":
            RemoveCurrentTurnEffect($i);
            PlayAura("HVY242", $wonWager, $amount);//Vigor
            break;
          case "HVY216": case "HVY217": case "HVY218":
            RemoveCurrentTurnEffect($i);
            PutItemIntoPlayForPlayer("DYN243", $wonWager, number:$amount, effectController:$mainPlayer);//Gold
            break;
          case "HVY235": case "HVY236": case "HVY237":
            RemoveCurrentTurnEffect($i);
            PutItemIntoPlayForPlayer("DYN243", $wonWager, number:$amount, effectController:$mainPlayer);//Gold
            break;
          case "ROS244":
            RemoveCurrentTurnEffect($i);
            Draw($wonWager);
            PummelHit($lostWager);
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
      if($char[1] == 2 && ($hero == "HVY092" || $hero == "HVY093")) {
        PutItemIntoPlayForPlayer("DYN243", $mainPlayer, effectController:$mainPlayer);//Gold
        WriteLog(CardLink($hero, $hero) . " wins the favor of the crowd!");
      }
    }
  }

  function HasUniversal($cardID)
  {
    switch($cardID) {
      case "HVY216": case "HVY217": case "HVY218": return true;
      default: break;
    }
    return false;
  }

  function Transcend($player, $cardID, $from)
  {
    global $currentPlayer, $CS_Transcended;
    $otherplayer = $player == 1 ? 2 : 1;
    SetClassState($player, $CS_Transcended, 1);
    if(SearchCharacterAlive($player, "MST048")) {
      AddDecisionQueue("YESNO", $player, "if you want to gain a resource");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("GAINRESOURCES", $player, "1", 1);
    }
    if(substr($from, 0, 5) == "THEIR") AddPlayerHand($cardID, $otherplayer, "-");
    else AddPlayerHand($cardID, $player, "-");
  }

  /**
   * Decompose is a keyword added in ROS. This function is meant to support future instances of
   * decompose that may have different requirements in the number of earth banishes and action
   * banishes.
   *
   * The result of "NOPASS" should be used to add the bonus effects. SPECIFICCARD dq events can be added right after calling decompose to run if the decompose succeeded.
   */
  function Decompose($player, $earthBanishes, $actionBanishes) {
    $totalBanishes = $earthBanishes + $actionBanishes;

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

    // This is a MAY ability.
    if($countInDiscard >= $totalBanishes && $earthCountInDiscard >= $earthBanishes) {

      AddDecisionQueue("YESNO", $player, "if_you_want_to_Decompose");
      AddDecisionQueue("NOPASS", $player, "-", 1);

      // Earth Banishes
      for($i = 0; $i < $earthBanishes; $i++) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYDISCARD:talent=EARTH", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . ($earthBanishes - $i) . " earth card(s) to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZBANISH", $player, "GY,-," . $player, 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
      }

      // Action banishes.
      for($i = 0; $i < $actionBanishes; $i++) {
        AddDecisionQueue("GETCARDSFORDECOMPOSE", $player, "MYDISCARD:type=A&MYDISCARD:type=AA", 1); // Modified MULTIZONEINDICES so if there are no actions it can be sent to the next dq and it will revert gamestate. Can't use "PASS" because YESNO "PASS" result is already present.
        AddDecisionQueue("REVERTGAMESTATEIFNULL", $player, "There aren't any more action cards! Try selecting different earth cards.", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . ($actionBanishes - $i) . " action card(s) to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZBANISH", $player, "GY,-," . $player, 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
      }
      return true;
    }
    else {
      return false;
    }
  }
