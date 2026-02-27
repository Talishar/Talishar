<?php
const UNDO_DECLINE_LIMIT = 3; // Maximum number of undo requests that can be declined before blocking further requests
const MAX_REPLAYS_SAVED = 3;

function deleteDir(string $dirPath): void {
  //https://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it
  if (! is_dir($dirPath)) {
    throw new InvalidArgumentException("$dirPath must be a directory");
  }
  if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
    $dirPath .= '/';
  }
  $files = glob($dirPath . '*', GLOB_MARK);
  foreach ($files as $file) {
    if (is_dir($file)) {
      deleteDir($file);
    } else {
      unlink($file);
    }
  }
  rmdir($dirPath);
}

function ProcessInput($playerID, $mode, $buttonInput, $cardID, $chkCount, $chkInput, $isSimulation = false, $inputText = "")
{
  global $gameName, $currentPlayer, $mainPlayer, $turn, $CS_CharacterIndex, $CS_PlayIndex, $decisionQueue, $CS_NextNAAInstant, $skipWriteGamestate, $combatChain, $landmarks;
  global $SET_PassDRStep, $actionPoints, $currentPlayerActivity, $redirectPath, $CS_PlayedAsInstant;
  global $dqState, $layers, $CS_ArsenalFacing, $CCS_HasAimCounter, $combatChainState, $CCS_NumPowerCounters;
  global $roguelikeGameID, $CS_SkipAllRunechants, $numMode;
  $otherPlayer = $playerID == 1 ? 2 : 1;
  switch ($mode) {
    case 0:
    case 1:
    case 2: //DEPRECATED
    case 18:
    case 28:
      break;
    case 3: //Play equipment/hero ability
      $index = intval($cardID);
      $character = &GetPlayerCharacter($playerID);
      $zone = -1;
      if ($index >= 0 && $index < count($character) && IsPlayable($character[$index], $turn[0], "CHAR", $index)) {
        SetClassState($playerID, $CS_CharacterIndex, $index);
        SetClassState($playerID, $CS_PlayIndex, $index);
        $cardID = $character[$index];
        if ($turn[0] == "B") $character[$index + 6] = 1;
        elseif ($turn[0] == "D" && canBeAddedToChainDuringDR($cardID)) {
          $character[$index + 1] = 1;
          $character[$index + 6] = 1;
        }
        else $zone = "MYCHAR";
        PlayCard($cardID, "EQUIP", -1, $index, $character[$index + 11], zone: $zone);
      } else {
        echo("Play equipment ability " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 4: //Add something to your arsenal
      $found = HasCard($cardID);
      if ($turn[0] == "ARS" && $found >= 0) {
        $hand = &GetHand($playerID);
        unset($hand[$found]);
        $hand = array_values($hand);
        AddArsenal($cardID, $currentPlayer, "HAND", "DOWN");
        PassTurn();
      } else {
        echo($cardID . " " . $turn[0] . "<BR>");
        echo("Add to arsenal " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 5: //Card Played from Arsenal
      $index = $cardID;
      $arsenal = &GetArsenal($playerID);
      if ($index < count($arsenal)) {
        $cardToPlay = $arsenal[$index];
        if (!IsPlayable($cardToPlay, $turn[0], "ARS", $index)) break;
        $uniqueID = $arsenal[$index + 5];
        $facing = $arsenal[$index + 1];
        if (SubtypeContains($cardToPlay, "Arrow")) SetClassState($playerID, $CS_ArsenalFacing, $facing);
        if ($arsenal[$index + 3] > 0 && CardSubType($cardToPlay) == "Arrow") $combatChainState[$CCS_HasAimCounter] = 1;
        if ($arsenal[$index + 6] > 0) $combatChainState[$CCS_NumPowerCounters] = $arsenal[$index + 6];
        if(!IsStaticType(CardType($cardToPlay, "ARS"), "ARS", $cardToPlay)) RemoveArsenal($playerID, $index);
        PlayCard($cardToPlay, "ARS", -1, -1, $uniqueID, zone: "MYARS", facing:$facing);
      } else {
        echo("Play from arsenal " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 6: //Pitch Deck
      if ($turn[0] != "PDECK") break;
      $found = SearchPitchForCard($currentPlayer, $cardID);
      if ($found >= 0) {
        PitchDeck($currentPlayer, $found);
        AddEvent("ADDBOTDECK", $currentPlayer);
        PassTurn(); //Resume passing the turn
      } else {
        echo("Pitch deck " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 7: //Number input
      if ($turn[0] == "DYNPITCH" || $turn[0] == "CHOOSENUMBER") {
        ContinueDecisionQueue($buttonInput);
      } else {
        echo("Number input " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 8:
    case 9: //OPT, CHOOSETOP, CHOOSEBOTTOM
      if ($turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM") {
        $options = explode(",", $turn[2]);
        $found = -1;
        $optionsCount = count($options);
        for ($i = 0; $i < $optionsCount; ++$i) {
          if ($options[$i] == $buttonInput) {
            $found = $i;
            break;
          }
        }
        if ($found == -1) break; //Invalid input
        $deck = new Deck($playerID);
        switch ($mode) {
          case 8:
            $deck->AddTop($buttonInput);
            WriteLog("Player " . $playerID . " put a card on top of the deck");
            break;
          case 9:
            $deck->AddBottom($buttonInput);
            WriteLog("Player " . $playerID . " put a card on the bottom of the deck");
            break;
        }
        array_splice($options, $found, 1);
        if (count($options) > 0) PrependDecisionQueue($turn[0], $currentPlayer, implode(",", $options));
        ContinueDecisionQueue($buttonInput);
      } else {
        echo("Opt " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 10: //Item ability
      $index = $cardID; //Overridden to be index instead
      $items = &GetItems($playerID);
      if ($index >= count($items)) break; //Item doesn't exist
      $cardID = $items[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Item not playable
      --$items[$index + 3];
      SetClassState($playerID, $CS_PlayIndex, $index);
      $set = CardSet($cardID);
      PlayCard($cardID, "PLAY", -1, $index, $items[$index + 4], zone: "MYITEMS");
      break;
    case 11: //CHOOSEDECK
      if ($turn[0] == "CHOOSEDECK" || $turn[0] == "MAYCHOOSEDECK" || $turn[0] == "CHOOSETHEIRDECK") {
        $player = ($turn[0] == "CHOOSETHEIRDECK") ? $playerID == 1 ? 2 : 1 : $playerID;
        $deck = new Deck($player);
        $index = $cardID;
        $cardID = $deck->Remove($index);
        ContinueDecisionQueue($cardID);
      }
      break;
    case 12: //HANDTOP
      if ($turn[0] == "HANDTOPBOTTOM") {
        $hand = &GetHand($playerID);
        $deck = new Deck($playerID);
        $deck->AddTop($buttonInput);
        $index = SearchHandForCard($playerID, $buttonInput);
        if (str_contains($index, ",")) $index = intval(explode(",", $index)[0]);
        array_splice($hand, $index, 1);
        ContinueDecisionQueue($buttonInput);
        WriteLog("Player " . $playerID . " put a card on the top of the deck.");
      }
      break;
    case 13: //HANDBOTTOM
      if ($turn[0] == "HANDTOPBOTTOM") {
        $hand = &GetHand($playerID);
        $deck = new Deck($playerID);
        $deck->AddBottom($buttonInput);
        $index = SearchHandForCard($playerID, $buttonInput);
        if (str_contains($index, ",")) $index = intval(explode(",", $index)[0]);
        array_splice($hand, $index, 1);
        ContinueDecisionQueue($buttonInput);
        WriteLog("Player " . $playerID . " put a card on the bottom of the deck.");
      }
      break;
    case 14: //Banish
      $index = $cardID;
      $banish = &GetBanish($playerID);
      $theirChar = &GetPlayerCharacter($otherPlayer);
      if ($index < 0 || $index >= count($banish)) {
        echo("Banish Index " . $index . " Invalid Input<BR>");
        return false;
      }
      $cardID = $banish[$index];
      $mod = $banish[$index + 1];
      if (!IsPlayable($cardID, $turn[0], "BANISH", $index)) break;
      // this line is causing issues with meld cards, I don't know what function it serves
      // these effects seem to work without it
      // if ($mod == "INST") SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
      if ($mod == "spew_shadow_red" && TalentContains($theirChar[0], "LIGHT", $currentPlayer)) AddCurrentTurnEffect("spew_shadow_red", $currentPlayer);
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      if (CanPlayAsInstant($cardID, $index, "BANISH")) SetClassState($currentPlayer, $CS_PlayedAsInstant, "1");
      if (!PlayableFromBanish($cardID, $mod, true)) SearchCurrentTurnEffects("blasmophet_levia_consumed", $currentPlayer, true);
      if (str_contains($mod, "shadowrealm_horror_red")) {
        $currentPlayerBanish = new Banish($currentPlayer);
        $currentPlayerBanish->UnsetBanishModifier($mod);
        $effectIndex = SearchCurrentTurnEffectsForUniqueID($mod);
        if ($effectIndex != -1) RemoveCurrentTurnEffect($effectIndex);
      }
      if($mod == "blossoming_spellblade_red") AddCurrentTurnEffect("blossoming_spellblade_red", $currentPlayer, uniqueID:$cardID);
      PlayCard($cardID, "BANISH", -1, $index, $banish[$index + 2], zone: "MYBANISH", mod:$mod);
      break;
    case 15: // Their Banish
      $index = $cardID;
      $theirBanish = &GetBanish($otherPlayer);
      $theirChar = &GetPlayerCharacter($otherPlayer);
      if ($index < 0 || $index >= count($theirBanish)) {
        echo("Banish Index " . $index . " Invalid Input<BR>");
        return false;
      }
      $cardID = $theirBanish[$index];
      if (!IsPlayable($cardID, $turn[0], "THEIRBANISH", $index)) break;
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      PlayCard($cardID, "THEIRBANISH", -1, $index, $theirBanish[$index + 2], zone: "THEIRBANISH", mod: $theirBanish[$index + 1]);
      break;
    case 16: 
      if (count($decisionQueue) > 0) {
        $index = $cardID;
        $isValid = false;
        $validInputs = explode(",", $turn[2]);
        $validInputsCount = count($validInputs);
        for ($i = 0; $i < $validInputsCount; ++$i) {
          if ($validInputs[$i] == $index) $isValid = true;
        }
        if ($isValid) ContinueDecisionQueue($index);
      }
      break;
    case 17: //BUTTONINPUT
      if ($turn[0] == "BUTTONINPUT" || $turn[0] == "CHOOSEARCANE" || $turn[0] == "BUTTONINPUTNOPASS" || $turn[0] == "CHOOSEFIRSTPLAYER") {
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 19: //MULTICHOOSE X - multi choice CHOOSEMULTIZONE
      if ($turn[0] == "CHOOSEMULTIZONE" || $turn[0] == "MAYCHOOSEMULTIZONE") {
        $options = explode(",", $turn[2]);
        $limit1 = explode("-", $options[0]);
        $limit2 = explode("-", $options[1]);

        // either or both limit options exists this is just a safe-guard way to handle it
        $maxSelect = 0;
        $minSelect = 0;
        $limitOffset = 0;
        switch ($limit1[0]) {
          case "MAXCOUNT":
            $maxSelect = intval($limit1[1]);
            $limitOffset++;
            break;
          case "MINCOUNT":
            $minSelect = intval($limit1[1]);
            $limitOffset++;
            break;
          default:
            break;
        }
        switch ($limit2[0]) {
          case "MAXCOUNT":
            $maxSelect = intval($limit2[1]);
            $limitOffset++;
            break;
          case "MINCOUNT":
            $minSelect = intval($limit2[1]);
            $limitOffset++;
            break;
          default:
            break;
        }

        $selectionCount = count($chkInput);
        if ($maxSelect < $selectionCount) { // we won't revert the gamestate as may the opponent is requested to choose (meganetic_lockwave_blue)
          WriteLog("Player " . $playerID . " selected " . $selectionCount . " items, but a maximum of " . $maxSelect . " is allowed.", highlight: true);
          $skipWriteGamestate = true;
          break;
        } else if ($selectionCount < $minSelect) {
          WriteLog("Player " . $playerID . " selected " . $selectionCount . " items, but a minimum of " . $maxSelect . " is requested.", highlight: true);
          $skipWriteGamestate = true;
          break;
        }

        $input = "";
        $chkInputCount = count($chkInput);
        $optionsCount = count($options);
        for ($i = 0; $i < $chkInputCount; ++$i) {
          $index = intval($chkInput[$i]);
          if ($index < 0 || $index >= $optionsCount) {
            WriteLog($selectionCount);
            WriteLog("An unvalid option was selected. Please try selecting the items again, if you feel experienced a bug please report it.", highlight: true);
            $skipWriteGamestate = true;
            break;
          }
          if ($input != "") $input .= ",";
          $input .= $options[$index + $limitOffset];
        }
        if (!$skipWriteGamestate) {
          ContinueDecisionQueue($input);
        }
        break;
      } else if (substr($turn[0], 0, 11) != "MULTICHOOSE" && substr($turn[0], 0, 14) != "MAYMULTICHOOSE") break;
      $params = explode("-", $turn[2]);
      $maxSelect = intval($params[0]);
      $options = explode(",", $params[1]);
      $minSelect = (count($params) > 2) ? intval($params[2]) : -1;
      if (count($chkInput) > $maxSelect) {
        WriteLog("You selected " . count($chkInput) . " items, but a maximum of " . $maxSelect . " is allowed. Reverting gamestate prior to that effect.", highlight: true);
        RevertGamestate();
        $skipWriteGamestate = true;
        break;
      }
      $chkInputCount = count($chkInput);
      if ($minSelect != -1 && $chkInputCount < $minSelect && $chkInputCount < count($options)) {
        WriteLog("You selected " . $chkInputCount . " items, but a minimum of " . $minSelect . " is requested. Reverting gamestate prior to that effect.", highlight: true);
        RevertGamestate();
        $skipWriteGamestate = true;
        break;
      }
      $input = [];
      $chkInputCount = count($chkInput);
      $optionsCount = count($options);
      for ($i = 0; $i < $chkInputCount; ++$i) {
        if ($chkInput[$i] < 0 || $chkInput[$i] >= $optionsCount) {
          WriteLog("You selected option " . $chkInput[$i] . " but that was not one of the original options. Reverting gamestate prior to that effect.", highlight: true);
          RevertGamestate();
          $skipWriteGamestate = true;
          break;
        } else {
          array_push($input, $options[$chkInput[$i]]);
        }
      }
      if (!$skipWriteGamestate) {
        ContinueDecisionQueue($input);
      }
      break;
    case 20: //YESNO
      if (($turn[0] == "YESNO" || $turn[0] == "DOCRANK") && ($buttonInput == "YES" || $buttonInput == "NO")) ContinueDecisionQueue($buttonInput);
      break;
    case 21: //Combat chain ability
      $index = $cardID; //Overridden to be index instead
      if ($index >= count($combatChain)) break; //Combat chain index doesn't exist
      $cardID = $combatChain[$index];
      if (AbilityPlayableFromCombatChain($cardID) && IsPlayable($cardID, $turn[0], "PLAY", $index)) {
        SetClassState($playerID, $CS_PlayIndex, $index);
        $card = GetClass($cardID, $currentPlayer);
        if ($card != "-") $card->PayAdditionalCosts("CC", $index);
        else CombatChainPayAdditionalCosts($index, "PLAY");
        PlayCard($cardID, "PLAY", -1, -1, $combatChain[$index + 7], zone: "CC");
      }
      break;
    case 22: //Aura ability
      $index = $cardID; //Overridden to be index instead
      $auras = &GetAuras($playerID);
      if ($index >= count($auras)) break; //Item doesn't exist
      $cardID = $auras[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Aura ability not playable
      $names = GetAbilityNames($cardID, $index);
      if ($names == "") $auras[$index + 1] = 1; //Set status to used - for now
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1, $index, $auras[$index + 6], zone: "MYAURAS");
      break;
    case 23: //CHOOSECARD
      if ($turn[0] == "CHOOSECARD" || $turn[0] == "MAYCHOOSECARD") {
        $options = explode(",", $turn[2]);
        $found = -1;
        $optionsCount = count($options);
        for ($i = 0; $i < $optionsCount; ++$i) {
          if ($options[$i] == $buttonInput) {
            $found = $i;
            break;
          }
        }
        if ($found == -1) break; //Invalid input
        array_splice($options, $found, 1);
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 24: //Ally Ability
      $allies = &GetAllies($currentPlayer);
      $index = $cardID; //Overridden to be index instead
      if ($index >= count($allies)) break; //Ally doesn't exist
      $cardID = $allies[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Ally not playable
      $allies[$index + 1] = 1;
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1, $index, $allies[$index + 5], "MYALLY");
      break;
    case 25: //Landmark Ability
      $index = $cardID;
      if ($index >= count($landmarks)) break; //Landmark doesn't exist
      $cardID = $landmarks[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Landmark not playable
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1, zone: "LANDMARK");
      break;
    case 26: //Change setting
      $userID = "";
      if (!$isSimulation) {
        include "MenuFiles/ParseGamefile.php";
        include_once "./includes/dbh.inc.php";
        include_once "./includes/functions.inc.php";
        $userID = ($playerID == 1) ? $p1id : $p2id;
      }
      $params = explode("-", $buttonInput);
      ChangeSetting($playerID, $params[0], $params[1], $userID);
      break;
    case 27: //Play card from hand by index
      $found = $cardID;
      if ($found >= 0) {
        //Player actually has the card, now do the effect
        //First remove it from their hand
        $hand = &GetHand($playerID);
        if ($found >= count($hand)) break;
        $cardID = $hand[$found];
        if (!IsPlayable($cardID, $turn[0], "HAND", $found)) break;
        array_splice($hand, $found, 1);
        PlayCard($cardID, "HAND", zone: "MYHAND", index: $found);
      }
      break;
    case 29: //CHOOSETOPOPPONENT
      if ($turn[0] == "CHOOSETOPOPPONENT") {
        $options = explode(",", $turn[2]);
        $found = -1;
        $optionsCount = count($options);
        for ($i = 0; $i < $optionsCount; ++$i) {
          if ($options[$i] == $buttonInput) {
            $found = $i;
            break;
          }
        }
        if ($found == -1) break; //Invalid input
        $deck = new Deck($otherPlayer);
        $deck->AddTop($buttonInput);
        array_splice($options, $found, 1);
        if (count($options) > 0) {
          PrependDecisionQueue($turn[0], $currentPlayer, implode(",", $options));
        }
        ContinueDecisionQueue($buttonInput);
      } else {
        echo("Choose top opponent " . $turn[0] . " Invalid Input<BR>");
        return false;
      }
      break;
    case 30: //String input
      $cardName = CardName(strtoupper($inputText));
      if ($cardName != "") $inputText = $cardName;
      if ($turn[2] == "head_leads_the_tail_red" && $inputText == "Head Leads the Tail") //Validate the name
      {
        WriteLog(CardLink($turn[2], $turn[2]) . " cannot name itself, your must name another card", highlight: true);
        break;
      }
      ContinueDecisionQueue(GamestateSanitize($inputText));
      break;
    case 31: //Move layer deeper
      $index = $buttonInput;
      if ($index >= $dqState[8]) break;
      $layer = [];
      $layerPieces = LayerPieces();
      for ($i = $index; $i < $index + $layerPieces; ++$i) array_push($layer, $layers[$i]);
      $counter = 0;
      for ($i = $index + $layerPieces; $i < $index + $layerPieces * 2; ++$i) {
        $layers[$i - $layerPieces] = $layers[$i];
        $layers[$i] = $layer[$counter++];
      }
      break;
    case 32: //Move layer up
      $index = $buttonInput;
      if ($index == 0) break;
      $layer = [];
      $layerPieces = LayerPieces();
      for ($i = $index; $i < $index + $layerPieces; ++$i) array_push($layer, $layers[$i]);
      $counter = 0;
      for ($i = $index - $layerPieces; $i < $index; ++$i) {
        $layers[$i + $layerPieces] = $layers[$i];
        $layers[$i] = $layer[$counter++];
      }
      break;
    case 33: //Fully re-order layers
      break;
    case 34: //Permanent ability
      $index = $cardID; //Overridden to be index instead
      $permanents = &GetPermanents($playerID);
      if ($index >= count($permanents)) break; //Permanent doesn't exist
      $cardID = $permanents[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Permanent not playable
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1, $index, zone: "MYPERM");
      break;
    case 35: //Play card from deck
      $index = $cardID; //Overridden to be index instead
      $deck = &GetDeck($playerID);
      if ($index >= count($deck)) break;
      $cardID = $deck[$index];
      if (!IsPlayable($cardID, $turn[0], "DECK", $index)) break;
      unset($deck[$index]);
      $deck = array_values($deck);
      PlayCard($cardID, "DECK", zone: "MYDECK");
      break;
    case 36: //Play card from graveyard
      $index = $cardID;
      $discard = &GetDiscard($playerID);
      $theirChar = &GetPlayerCharacter($playerID == 1 ? 2 : 1);
      if ($index < 0 || $index >= count($discard)) {
        echo("Graveyard Index " . $index . " Invalid Input<BR>");
        return false;
      }
      $cardID = $discard[$index];
      if (!IsPlayable($cardID, $turn[0], "GY", $index)) break;
      if ($discard[$index + 1] == "INST") SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      if (CanPlayAsInstant($cardID, $index, "GY")) SetClassState($currentPlayer, $CS_PlayedAsInstant, "1");
      if (HasSuspense($cardID)) SearchCurrentTurnEffects("cries_of_encore_red", $currentPlayer, 1);
      PlayCard($cardID, "GY", -1, $index, $discard[$index + 2] ?? -1, zone: "MYDISCARD");
      break;
    case 37: // Their Arsenal
      $index = $cardID;
      $theirArs = &GetArsenal($otherPlayer);
      if ($index < 0 || $index >= count($theirArs)) {
        echo ("Arsenal Index " . $index . " Invalid Input<BR>");
        return false;
      }
      $cardID = $theirArs[$index];
      $uniqueID = $theirArs[$index + 5];
      if (!IsPlayable($cardID, $turn[0], "THEIRARS", $index))
        break;
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      RemoveArsenal($otherPlayer, $index);
      PlayCard($cardID, "THEIRARS", -1, $index, $uniqueID, zone: "THEIRARS");
      break;
    case 38: // Past chain link, $from=="COMBATCHAINATTACKS"
      $index = $cardID; //Overridden to be index instead
      $combatChainAttacks = GetCombatChainAttacks();
      $cardID = $combatChainAttacks[$index];
      if (AbilityPlayableFromCombatChain($cardID) && IsPlayable($cardID, $turn[0], "COMBATCHAINATTACKS", intdiv($index, ChainLinksPieces()))) {
        SetClassState($playerID, $CS_PlayIndex, $index);
        $card = GetClass($cardID, $currentPlayer);
        if ($card != "-")
          $card->PayAdditionalCosts("COMBATCHAINATTACKS", $index);
        else
          CombatChainPayAdditionalCosts($index, "COMBATCHAINATTACKS");
        PlayCard($cardID, "COMBATCHAINATTACKS", -1, -1, "-", zone: "COMBATCHAINATTACKS");
      }
      break;
    case 99: //Pass
      if (CanPassPhase($turn[0])) {
        if (count($layers) == LayerPieces() && $layers[0] == "RESOLUTIONSTEP") {
          PassInput(false, true);
        }
        else
          PassInput(false);
      }
      break;
    case 100: //Break Chain
      WriteLog("Player $playerID passes priority in the Resolution Step");
      PassInput(false, doublePass: true);
      break;
    case 101: //Pass block and Reactions
      ChangeSetting($playerID, $SET_PassDRStep, 1);
      if (CanPassPhase($turn[0])) {
        PassInput(false);
      }
      break;
    case 102: //Toggle equipment Active
      $index = $buttonInput;
      $charCard = new CharacterCard($index, $playerID);
      $charCard->ToggleGem();
      break;
    case 103: //Toggle my permanent Active
      $input = explode("-", $buttonInput);
      $index = $input[1];
      $Card = GetPermanent($input[0], $index, $playerID);
      $Card->ToggleGem();
      break;
    case 104: //Toggle other player permanent Active
      $input = explode("-", $buttonInput);
      $index = $input[1];
      $Card = GetPermanent($input[0], $index, $playerID == 1 ? 2 : 1);
      $Card->ToggleGem($playerID);
      break;
    case 105: //Skip all runechants
      SetClassState($playerID, $CS_SkipAllRunechants, 1);
      break;
    case 10000: //Undo
      $format = GetCachePiece($gameName, 13);
      $char = &GetPlayerCharacter($otherPlayer);
      if (($format != 1 && $format != 3 && $format != 13 && $format != 15) || IsPlayerAI($otherPlayer) || $turn[0] == "P" || AlwaysAllowUndo($otherPlayer)) {
        RevertGamestate();
        $skipWriteGamestate = true;
        WriteLog("Player " . $playerID . " undid their last action");
      }
      else {
        //It's competitive queue, so we must request confirmation
        // Check if opponent has declined too many undo requests already
        $opponentDeclinePiece = $otherPlayer == 1 ? 17 : 18;
        $opponentDeclineCount = intval(GetCachePiece($gameName, $opponentDeclinePiece));
        if ($opponentDeclineCount >= UNDO_DECLINE_LIMIT) {
          AddEvent("UNDODENIEDNOTICE", $playerID);
        }
        else {
          WriteLog("Player " . $playerID . " requests to undo the last action");
          AddEvent("REQUESTUNDO", $playerID);
        }
      }
      break;
    case 10001:
      RevertGamestate("preBlockBackup.txt");
      $skipWriteGamestate = true;
      break;
    case 10002:
      WriteLog("Player " . $playerID . " manually added 1 action point", highlight: true);
      ++$actionPoints;
      break;
    case 10003: //Undo/Revert to prior turn
      $format = GetCachePiece($gameName, 13);
      $char = &GetPlayerCharacter($otherPlayer);
      if (($format != 1 && $format != 3 && $format != 13 && $format != 15) || IsPlayerAI($otherPlayer) || $turn[0] == "P" || AlwaysAllowUndo($otherPlayer)) {
        RevertGamestate($buttonInput);
        WriteLog("Player " . $playerID . " reverted back to a prior turn");
      }
      else {
        //It's competitive queue, so we must request confirmation
        // Check if opponent has declined too many undo requests already
        $opponentDeclinePiece = $otherPlayer == 1 ? 17 : 18;
        $opponentDeclineCount = intval(GetCachePiece($gameName, $opponentDeclinePiece));
        if ($opponentDeclineCount >= UNDO_DECLINE_LIMIT) {
          WriteLog("Player " . $playerID . " requested to undo but opponent has declined too many undo requests this turn");
        }
        else {
          WriteLog("Player " . $playerID . " requests to undo the last action");
          if ($buttonInput == "beginTurnGamestate.txt")
            AddEvent("REQUESTTHISTURNUNDO", $playerID);
          else if ($buttonInput == "lastTurnGamestate.txt")
            AddEvent("REQUESTLASTTURNUNDO", $playerID);
        }
      }
      break;
    case 10004:
      if ($actionPoints > 0) {
        WriteLog("Player " . $playerID . " manually subtracted 1 action point.", highlight: true);
        --$actionPoints;
      }
      break;
    case 10005:
      WriteLog("Player " . $playerID . " manually subtracted 1 life from themself", highlight: true);
      PlayerLoseHealth(1, $playerID);
      break;
    case 10006:
      WriteLog("Player " . $playerID . " manually added 1 life to themself", highlight: true);
      $health = &GetHealth($playerID);
      $health += 1;
      break;
    case 10007:
      $targetPlayer = $playerID == 1 ? 2 : 1;
      if (IsPlayerAI($targetPlayer)) {
        WriteLog("Manually subtracting 1 life from AI opponent");
        $health = &GetHealth($targetPlayer);
        --$health;
      }
      else
        WriteLog("Subtracting life from your opponent is not allowed");
      break;
    case 10008:
      WriteLog("Player " . $playerID . " manually added 1 life to their opponent", highlight: true);
      $health = &GetHealth($playerID == 1 ? 2 : 1);
      $health += 1;
      break;
    case 10009:
      WriteLog("Player " . $playerID . " manually drew a card for themself", highlight: true);
      Draw($playerID, false);
      break;
    case 10010:
      WriteLog("Player " . $playerID . " manually drew a card for their opponent", highlight: true);
      Draw(($playerID == 1 ? 2 : 1), false);
      break;
    case 10011:
      $cardList = explode(";", $cardID);
      foreach ($cardList as $cardID) {
        $cardID = trim($cardID);
        if (str_contains($cardID, "|")) {
          $cardIDParts = explode("|", $cardID);
          $num = $cardIDParts[1];
          $cardID = $cardIDParts[0];
        }
        else
          $num = 1;
        if (SetIDtoCardID(strtoupper($cardID)) != "")
          $cardID = SetIDtoCardID(strtoupper($cardID));
        $cardID = str_replace(" ", "_", $cardID);
        $splitCard = explode("_", $cardID);
        $color = end($splitCard);
        switch ($color) {
          case "r":
            $cardID .= "ed";
            break;
          case "y":
            $cardID .= "ellow";
            break;
          case "b":
            $cardID .= "lue";
            break;
          default:
            break;
        }
        if (CardName($cardID) == "") {
          if (CardName($cardID . "_red") != "")
            $cardID .= "_red";
          elseif (CardName($cardID . "_yellow") != "")
            $cardID .= "_yellow";
          elseif (CardName($cardID . "_blue") != "")
            $cardID .= "_blue";
        }
        if (TypeContains($cardID, "C")) {
          WriteLog("Player " . $playerID . " transformed their hero", highlight: true);
          $char = &GetPlayerCharacter($playerID);
          $char[0] = $cardID;
        }
        elseif (CardType($cardID) == "E" || CardType($cardID) == "W") {
          if ($num == "inv") {
            WriteLog("Player " . $playerID . " manually added a card to their inventory", highlight: true);
            $inventory = &GetInventory($playerID);
            array_push($inventory, $cardID);
          }
          else {
            WriteLog("Player " . $playerID . " manually equipped a card", highlight: true);
            EquipEquipment($playerID, $cardID);
          }
        }
        elseif (!TypeContains($cardID, "T") && !TypeContains($cardID, "Macro")) {
          if ($num == "banish") {
            WriteLog("Player " . $playerID . " manually added a card to their banish", highlight: true);
            BanishCardForPlayer($cardID, $playerID, "MANUAL");
          }
          elseif ($num == "grave") {
            WriteLog("Player " . $playerID . " manually added a card to their graveyard", highlight: true);
            AddGraveyard($cardID, $playerID, "MANUAL");
          }
          elseif ($num == "deck") {
            WriteLog("Player " . $playerID . " manually added a card to the top of their deck", highlight: true);
            AddTopDeck($cardID, $playerID, "MANUAL");
          }
          elseif ($num == "inv") {
            WriteLog("Player " . $playerID . " manually added a card to their inventory", highlight: true);
            $inventory = &GetInventory($playerID);
            array_push($inventory, $cardID);
          }
          else {
            WriteLog("Player " . $playerID . " manually added a card to their hand", highlight: true);
            $hand = &GetHand($playerID);
            array_push($hand, $cardID);
          }
        }
        else {
          WriteLog("Player " . $playerID . " manually created a token", highlight: true);
          if (SubtypeContains($cardID, "Aura"))
            PlayAura($cardID, $playerID, $num, from: "MANUAL");
          elseif (SubtypeContains($cardID, "Item"))
            PutItemIntoPlayForPlayer($cardID, $playerID, number: $num, from: "MANUAL");
          elseif (SubtypeContains($cardID, "Landmark"))
            PlayLandmark($cardID, $playerID, "MANUAL");
          else
            PutPermanentIntoPlay($playerID, $cardID, from: "MANUAL");
        }
      }
      break;
    case 10012:
      WriteLog("Player " . $playerID . " manually added a resource to their pool", highlight: true);
      $resources = &GetResources($playerID);
      $resources[0] += 1;
      break;
    case 10013:
      WriteLog("Player " . $playerID . " manually added a resource to their opponent's pool", highlight: true);
      $resources = &GetResources($playerID == 1 ? 2 : 1);
      $resources[0] += 1;
      break;
    case 10014:
      WriteLog("Player " . $playerID . " manually removed a resource from their opponent's pool", highlight: true);
      $resources = &GetResources($playerID == 1 ? 2 : 1);
      $resources[0] -= 1;
      break;
    case 10015:
      WriteLog("Player " . $playerID . " manually removed a resource from their pool", highlight: true);
      $resources = &GetResources($playerID);
      $resources[0] -= 1;
      break;
    case 10016:
      WriteLog("Player " . $playerID . " manually removed their arsenal", highlight: true);
      $cardID = RemoveArsenal($playerID, 0);
      AddGraveyard($cardID, $playerID, "ARS");
      break;
    case 10018:
      if (!IsReplay()) {
        WriteLog("Turn hopping only works in replays");
      }
      else {
        global $filepath;
        if (str_contains($cardID, "-")) {
          $turnPlayer = explode("-", $cardID)[0];
          $turnNumber = explode("-", $cardID)[1];
        }
        else {
          $turnPlayer = $playerID;
          $turnNumber = $cardID;
        }
        if (!is_numeric($turnPlayer) || !is_numeric($turnNumber)) {
          WriteLog("$cardID cannot be parsed as a turn number", highlight: true);
          break;
        }
        $backupFname = "turn_$turnPlayer-$turnNumber" . "_Gamestate.txt";
        $backupLoc = $filepath . $backupFname;
        if (!file_exists($backupLoc)) {
          WriteLog("Cannot find turn backup for player $turnPlayer's turn $turnNumber", highlight: true);
          break;
        }
        //update the command pointer
        $filename = $filepath . "replayCommands.txt";
        $commands = file($filename);
        $pointer = -1;
        for ($i = 0; $i < count($commands); $i += 1) {
          $line = $commands[$i];
          $params = explode(" ", $line);
          $loadedPlayer = $params[0] ?? "";
          $loadedMode = $params[1] ?? "";
          $loadedTurn = $params[2] ?? "";
          if ($loadedMode == "StartTurn" && $loadedPlayer == $turnPlayer && $loadedTurn == $turnNumber) {
            $pointer = $i;
          }
        }
        if ($pointer != -1) {
          // $pointer += 1;
          $commands[0] = "$pointer\r\n";
          file_put_contents($filename, $commands);
        }
        else {
          WriteLog("Could not find the turn in the command file!", highlight: true);
          break;
        }
        //load the gamestate
        RevertGamestate($backupFname);
        WriteLog("Moving to player $turnPlayer's turn $turnNumber", highlight: true);
      }
      break;
    case 10019:
      global $AIHasInfiniteHP;
      $infiniteMode = ($numMode == 1) ? "1" : "0";
      $AIHasInfiniteHP = $infiniteMode;
      WriteLog("AI infinite HP toggled to " . ($infiniteMode ? "ON" : "OFF"), highlight: true);
      break;
    case 100000: //Quick Rematch
      if ($isSimulation)
        return;
      if ($turn[0] != "OVER")
        break;
      $otherPlayer = $playerID == 1 ? 2 : 1;
      $char = &GetPlayerCharacter($otherPlayer);
      if (!IsPlayerAI($otherPlayer)) {
        AddDecisionQueue("YESNO", $otherPlayer, "if you want a <b>Quick Rematch</b>?");
        AddDecisionQueue("NOPASS", $otherPlayer, "-", 1);
        AddDecisionQueue("QUICKREMATCH", $otherPlayer, "-", 1);
        AddDecisionQueue("OVER", $playerID, "-");
      }
      else {
        AddDecisionQueue("QUICKREMATCH", $otherPlayer, "-", 1);
      }
      ProcessDecisionQueue();
      break;
    case 100001: //Main Menu
      if ($isSimulation)
        return;
      header("Location: {$redirectPath}/MainMenu.php");
      exit;
    case 100002: //Concede
      if ($isSimulation)
        return;
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $conceded = true;
      if (!IsGameOver()) {
        WriteLog("Player $playerID conceded the game.");
        PlayerWon($playerID == 1 ? 2 : 1, true);
      }
      break;
    case 100003: //Report Bug
      if ($isSimulation)
        return;
      ReportBug();
      break;
    case 100004: //Full Rematch
      if ($isSimulation)
        return;
      if ($turn[0] != "OVER")
        break;
      $otherPlayer = $playerID == 1 ? 2 : 1;
      WriteLog("Player $playerID sent a rematch invitation.", highlight: true, highlightColor: "darkblue");
      AddDecisionQueue("YESNO", $otherPlayer, "if you want a <b>Rematch</b>?");
      AddDecisionQueue("REMATCH", $otherPlayer, "-");
      ProcessDecisionQueue();
      break;
    case 100007: //Claim Victory when opponent is inactive
      if ($isSimulation)
        return;
      // WriteLog("JERE: $currentPlayerActivity");
      // if ($currentPlayerActivity == 1) {
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $otherPlayer = $playerID == 1 ? 2 : 1;
      if (!IsGameOver())
        PlayerWon($playerID);
      WriteLog("🚩The opponent forfeit due to inactivity.");
      // }
      break;
    case 100010: //Grant badge
      if ($isSimulation)
        return;
      include "MenuFiles/ParseGamefile.php";
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $myName = ($playerID == 1 ? $p1uid : $p2uid);
      $theirName = ($playerID == 1 ? $p2uid : $p1uid);
      $userID = ($playerID == 1) ? $p1id : $p2id;
      if ($userID != "") {
        AwardBadge($userID, 3);
        WriteLog($myName . " gave a badge to " . $theirName);
      }
      break;
    case 100011: //Resume adventure (roguelike)
      if ($roguelikeGameID == "")
        break;
      header("Location: {$redirectPath}/Roguelike/ContinueAdventure.php?gameName={$roguelikeGameID}&playerID=1&health=" . GetHealth(1));
      break;
    case 100012: //Create Replay
      if (!file_exists("./Games/" . $gameName . "/origGamestate.txt")) {
        WriteLog("Failed to create replay; original gamestate file failed to create.");
        return true;
      }
      //getting player ids
      $filename = "./Games/" . $gameName . "/GameFile.txt";
      if (!file_exists($filename))
        break;
      $gameFile = file($filename);
      $p1id = trim($gameFile[9]);
      $p2id = trim($gameFile[10]);
      // end getting player IDs
      $pid = ($playerID == 1 ? $p1id : $p2id);
      $path = "./Replays/" . $pid . "/";
      if ($pid == "") {
        WriteLog("You cannot save replays while not logged in!", highlight: true);
        break;
      }
      if (!file_exists($path)) {
        mkdir($path, 0777, true);
      }
      if (!file_exists($path . "counter.txt"))
        $counter = 1;
      else {
        $counterFile = fopen($path . "counter.txt", "r");
        $counter = fgets($counterFile);
        fclose($counterFile);
      }
      $replayPath = $path . $counter;
      $gamePath = "./Games/" . $gameName;
      mkdir($replayPath, 0777, true);
      copy("$gamePath/origGamestate.txt", "$replayPath/origGamestate.txt");
      copy("$gamePath/commandfile.txt", "$replayPath/commandfile.txt");

      for ($player = 1; $player < 3; ++$player) {
        $turnNum = 1;
        $turnBackupFileSource = "$gamePath/turn_$player-$turnNum" . "_Gamestate.txt";
        $turnBackupFileDest = "$replayPath/turn_$player-$turnNum" . "_Gamestate.txt";
        if (!file_exists($turnBackupFileSource)) { //player who goes second doesn't get a "turn 1"
          ++$turnNum;
          $turnBackupFileSource = "$gamePath/turn_$player-$turnNum" . "_Gamestate.txt";
          $turnBackupFileDest = "$replayPath/turn_$player-$turnNum" . "_Gamestate.txt";
        }
        while (file_exists($turnBackupFileSource)) {
          copy($turnBackupFileSource, $turnBackupFileDest);
          ++$turnNum;
          $turnBackupFileSource = "$gamePath/turn_$player-$turnNum" . "_Gamestate.txt";
          $turnBackupFileDest = "$replayPath/turn_$player-$turnNum" . "_Gamestate.txt";
        }
      }
      WriteLog("Player " . $playerID . " saved this game as their replay # $counter.");
      $counterFile = fopen($path . "counter.txt", "w");
      fwrite($counterFile, $counter + 1);
      fclose($counterFile);
      $filecount = count(glob($path . "*"));
      if ($filecount > MAX_REPLAYS_SAVED + 1) {
        $minCounter = INF;
        foreach (glob($path . "*") as $dirName) {
          $dirArr = explode("/", $dirName);
          $dirNum = end($dirArr);
          if (is_numeric($dirNum) && intval($dirNum) < $minCounter)
            $minCounter = intval($dirNum);
        }
        if (!is_infinite($minCounter)) {
          WriteLog("You've reached the maximum number of saved replays, deleting your oldest ($minCounter)", highlight: true);
          deleteDir($path . $minCounter . "/");
        }
      }
      break;
    case 100013: //Enable Spectate
      SetCachePiece($gameName, 9, "1");
      break;
    case 100014: //Report Player
      if ($isSimulation)
        return;
      $reportCount = 0;
      $folderName = "./BugReports/" . $gameName . "-" . $reportCount;
      while ($reportCount < 5 && file_exists($folderName)) {
        ++$reportCount;
        $folderName = "./BugReports/" . $gameName . "-" . $reportCount;
      }
      if ($reportCount == 3) {
        WriteLog("⚠️ Report file is full for this game. Please use discord for further reports.", highlight: true);
      }
      mkdir($folderName, 0700, true);
      copy("./Games/$gameName/gamestate.txt", $folderName . "/gamestate.txt");
      if (file_exists("./Games/$gameName/gamestateBackup.txt")) copy("./Games/$gameName/gamestateBackup.txt", $folderName . "/gamestateBackup.txt");
      copy("./Games/$gameName/gamelog.txt", $folderName . "/gamelog.txt");
      copy("./Games/$gameName/beginTurnGamestate.txt", $folderName . "/beginTurnGamestate.txt");
      copy("./Games/$gameName/lastTurnGamestate.txt", $folderName . "/lastTurnGamestate.txt");
      WriteLog("🚨Thank you for reporting a player. The chat log has been saved on the server. Please report it to a mod on Discord with the game number for reference ($gameName).", highlight: true);
      break;
    case 100015: //Request to enable chat
      include "MenuFiles/ParseGamefile.php";
      $myName = ($playerID == 1 ? $p1uid : $p2uid);
      switch ($playerID) {
        case 1:
          SetCachePiece($gameName, 15, 1);
          break;
        case 2:
          SetCachePiece($gameName, 16, 1);
          break;
      }
      if (GetCachePiece($gameName, 15) != 1 || GetCachePiece($gameName, 16) != 1) {
        AddEvent("REQUESTCHAT", $playerID);
        if (IsPlayerAI(2))
          WriteLog("🤖 The dummy beeps at you");
        else
          WriteLog("🗣️ Player " . $playerID . " wants to enable chat");
      }
      break;
    case 100016://Confirm Undo
      RevertGamestate();
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " allowed undoing the last action");
      break;
    case 100017://Decline Undo
      WriteLog("Player " . $playerID . " declined the undo request");
      // Increment the decline counter for THIS player (who is declining)
      $declineCounterPiece = $playerID == 1 ? 17 : 18;
      $currentDeclineCount = intval(GetCachePiece($gameName, $declineCounterPiece));
      SetCachePiece($gameName, $declineCounterPiece, $currentDeclineCount + 1);
      break;
    case 100018://Confirm this turn undo
      RevertGamestate("beginTurnGamestate.txt");
      WriteLog("Player " . $playerID . " reverted to the start of the turn");
      break;
    case 100019://Confirm last turn undo
      RevertGamestate("lastTurnGamestate.txt");
      WriteLog("Player " . $playerID . " reverted to the last turn");
      break;
    case 100020://Decline chat request
      WriteLog("🚫 Player " . $playerID . " declined the invitation to chat");
      break;
    case "OPT": // should only show up in replays
      $deck = new Deck($playerID);
      $cardListTop = explode(",", $buttonInput);
      $cardListBottom = explode(",", $cardID);
      $deck->Opt($cardListTop, $cardListBottom);
      $topCount = count($cardListTop);
      $bottomCount = count($cardListBottom);
      $topMessage = $topCount . " card" . ($topCount > 1 ? "s" : "") . " on top";
      $bottomMessage = $bottomCount . " card" . ($bottomCount > 1 ? "s" : "") . " on the bottom";
      WriteLog("Player " . $playerID . " has put " . $topMessage . " and " . $bottomMessage . " of their deck.");
      ContinueDecisionQueue();
      break;
    case "REORDER": // should only show up in replays
      $cardList = explode(",", $buttonInput);
      foreach ($cardList as $card) {
        $index = -1;
        for ($i = 0; $i < count($layers); $i += LayerPieces()) {
          if ($layers[$i] == "PRETRIGGER" && $layers[$i + 1] == $playerID && $layers[$i + 2] == $card) {
            $index = $i;
          }
        }
        if ($index != -1) {
          $pretrigger = array_slice($layers, $index, LayerPieces());
          $pretrigger[0] = "TRIGGER";
          array_splice($layers, $index, LayerPieces());
          $layers = array_merge($pretrigger, $layers);
        }
      }
      for ($i = 0; $i < count($layers); $i += LayerPieces()) {
        if ($layers[$i] == "PRETRIGGER" && $layers[$i + 1] == $playerID) {
          WriteLog("Something went wrong with adding triggers and we missed adding " . $layers[$i + 2] . " to the stack", highlight: true);
          $layers[$i] = "TRIGGER";
        }
      }
      ContinueDecisionQueue();
      break;
    case "SETTINGS": // should only show up in replays
      $settingID = $buttonInput;
      $settingValue = $cardID;
      $userID = "";
      ChangeSetting($playerID, $settingID, $settingValue);
      break;
    default:
      break;
  }
  return true;
}

function IsModeAsync($mode)
{
  static $asyncModes = [
  26 => true, 102 => true, 103 => true, 104 => true, 10000 => true,
  10003 => true, 100000 => true, 100001 => true, 100002 => true,
  100003 => true, 100004 => true, 100007 => true, 100010 => true,
  100012 => true, 100015 => true, 100016 => true, 100017 => true,
  100018 => true, 100019 => true, 100020 => true
  ];
  return isset($asyncModes[$mode]);
}

function IsModeAllowedForSpectators($mode)
{
  static $spectatorModes = [100001 => true];
  return isset($spectatorModes[$mode]);
}

function HasCard($cardID)
{
  global $currentPlayer;
  $cardType = CardType($cardID);
  if ($cardType == "C" || $cardType == "E" || $cardType == "W") {
    $character = &GetPlayerCharacter($currentPlayer);
    $charCount = count($character);
    $characterPieces = CharacterPieces();
    for ($i = 0; $i < $charCount; $i += $characterPieces) {
      if ($character[$i] == $cardID)
        return $i;
    }
  }
  else {
    $hand = &GetHand($currentPlayer);
    $handCount = count($hand);
    for ($i = 0; $i < $handCount; ++$i) {
      if ($hand[$i] == $cardID)
        return $i;
    }
  }
  return -1;
}

function PassInput($autopass = true, $doublePass = false)
{
  global $turn, $currentPlayer, $mainPlayer, $layers;
  $layerPieces = LayerPieces();
  // WriteLog($turn[0] . " " . $turn[2]);//Uncomment this to visualize decision PassInput execution
  if (isset($turn[2]) && str_contains($turn[2], "PRELAYER")) {
    $currPreLayers = 0;
    $preLayers = GetPreLayers();
    $preLayersCount = count($preLayers);
    $layersCount = count($layers);
    for ($i = 0; $i < $preLayersCount; $i += $layerPieces) {
      if ($preLayers[$i + 1] == $currentPlayer)
        ++$currPreLayers;
    }
    $addedTriggers = [];
    for ($i = $layersCount - $layerPieces; $i >= 0; $i -= $layerPieces) {
      if ($layers[$i] == "PRETRIGGER" && $layers[$i + 1] == $currentPlayer) {
        $pretrigger = array_slice($layers, $i, $layerPieces);
        $pretrigger[0] = "TRIGGER";
        array_splice($layers, $i, $layerPieces);
        $addedTriggers = array_merge($pretrigger, $addedTriggers);
      }
    }
    $layers = array_merge($addedTriggers, $layers);
  }
  if ($turn[0] == "B") {
    $uniqueID = SearchCurrentTurnEffects("meganetic_lockwave_blue", $mainPlayer, returnUniqueID: true);
    if ($uniqueID != -1) {
      $playerChar = &GetPlayerCharacter($currentPlayer);
      $charID = FindCharacterIndex($currentPlayer, $uniqueID);
      if ($playerChar[$charID + 6] != 1 && BlockValue($playerChar[$charID]) >= 0) {
        WriteLog("Player " . $currentPlayer . " must block with " . CardLink($uniqueID, $uniqueID) . " due to the effect of " . CardLink("meganetic_lockwave_blue", "meganetic_lockwave_blue") . ".");
        return;
      }
    }
  }
  $passOptions = ["ENDPHASE", "MAYMULTICHOOSETEXT", "MAYCHOOSECOMBATCHAIN", "MAYCHOOSEMULTIZONE", "MAYMULTICHOOSEHAND",
    "MAYCHOOSEHAND", "MAYCHOOSEDISCARD", "MAYCHOOSEARSENAL", "MAYCHOOSEPERMANENT", "MAYCHOOSEDECK", "MAYCHOOSEMYSOUL",
    "INSTANT", "MULTISHOWCARDSDECK", "OK", "", "MULTISHOWCARDSTHEIRDECK", "MAYCHOOSECARD", "STARTTURN", "MAYCHOOSEHANDHEAVE"];
  if (in_array($turn[0], $passOptions)) {
    ContinueDecisionQueue("PASS");
  }
  elseif ($turn[0] == "YESNO") {
    ContinueDecisionQueue("NO");
  }
  elseif ($turn[0] == "CHOOSEARCANE") {
    ContinueDecisionQueue("0");
  }
  elseif ($turn[0] == "ORDERTRIGGERS") {
    $layersCount = count($layers);
    for ($i = 0; $i < $layersCount; $i += $layerPieces) {
      if ($layers[$i] == "PRETRIGGER" && $layers[$i + 1] == $currentPlayer)
        $layers[$i] = "TRIGGER";
    }
    ContinueDecisionQueue();
  }
  else {
    switch ($autopass) {
      case true:
        WriteLog("Player " . $currentPlayer . " auto-passed");
        break;
      default:
        WriteLog("Player " . $currentPlayer . " passed");
        break;
    }
    if (Pass($turn, $currentPlayer)) {
      switch ($turn[0]) {
        case "M":
          BeginTurnPass();
          break;
        default:
          PassTurn();
          break;
      }
    }
    if (HoldPrioritySetting($currentPlayer) != 4 || $doublePass) {
      // without this line the turn player needs to pass twice to break the chain
      // but including the line makes auto-passers automatically pass through the resolution step
      // for now only turn enable this line if you aren't on always pass
      if (count($layers) == $layerPieces && $layers[0] == "RESOLUTIONSTEP" && $currentPlayer == $mainPlayer)
        PassInput($autopass);
    }
  }
}

function Pass(&$turn, &$currentPlayer)
{
  global $mainPlayer, $defPlayer, $layers;
  if ($turn[0] == "M" || $turn[0] == "ARS") {
    return 1;
  }
  else if ($turn[0] == "B") {
    AddLayer("DEFENDSTEP", $mainPlayer, "-");
    OnBlockResolveEffects();
    ProcessDecisionQueue();
  }
  else if ($turn[0] == "A") {
    if (count($turn) >= 3 && $turn[2] == "D") {
      return BeginChainLinkResolution();
    }
    else {
      $currentPlayer = $defPlayer;
      $turn[0] = "D";
      $turn[2] = "A";
    }
  }
  else if ($turn[0] == "D") {
    if (count($turn) >= 3 && $turn[2] == "A") {
      return BeginChainLinkResolution();
    }
    else {
      $currentPlayer = $mainPlayer;
      $turn[0] = "A";
      $turn[2] = "D";
    }
  }
  return 0;
}

function BeginChainLinkResolution()
{
  global $mainPlayer, $turn;
  $turn[0] = "M";
  ChainLinkBeginResolutionEffects();
  AddDecisionQueue("RESOLVECHAINLINK", $mainPlayer, "-");
  ProcessDecisionQueue();
}

function NuuStaticAbility($banishedBy)
{
  global $mainPlayer, $defPlayer, $chainLinks;
  $prevLink = $chainLinks[count($chainLinks) - 1];
  $prevLinkCount = count($prevLink);
  $chainLinksPieces = ChainLinksPieces();
  if ($prevLinkCount > 0) {
    for ($i = 0; $i < $prevLinkCount; $i += $chainLinksPieces) {
      if ($defPlayer == $prevLink[$i + 1]) {
        $originalID = GetCardIDBeforeTransform($prevLink[$i]);
        $cardType = CardType($prevLink[$i]);
        if ($cardType === "E" && CardType($originalID) === "A" && $prevLink[$i] !== "teklovossen_the_mechropotentb" && $prevLink[$i] !== "nitro_mechanoidb") {
          WriteLog(CardLink($prevLink[$i], $prevLink[$i]) . " was banished.");
          BanishCardForPlayer($originalID, $defPlayer, "CC", "Source-" . $banishedBy, $mainPlayer);
          $index = FindCharacterIndex($defPlayer, $prevLink[$i]);
          DestroyCharacter($defPlayer, $index, wasBanished: true);
        }
        if (DelimStringContains($cardType, "A") || $cardType === "AA") {
          WriteLog(CardLink($prevLink[$i], $prevLink[$i]) . " was banished.");
          BanishCardForPlayer($prevLink[$i], $defPlayer, "CC", "Source-" . $banishedBy, $banishedBy);
          $chainLinks[count($chainLinks) - 1][$i + 2] = 0;
        }
      }
    }
  }
}

function ChainLinkBeginResolutionEffects()
{
  global $combatChain, $mainPlayer, $defPlayer, $CCS_CombatDamageReplaced, $combatChainState, $CCS_WeaponIndex, $CID_BloodRotPox, $CS_Transcended;
  if (TypeContains($combatChain[0], "W", $mainPlayer)) {
    $mainCharacterEffects = &GetMainCharacterEffects($mainPlayer);
    $index = $combatChainState[$CCS_WeaponIndex];
    $mainCharacterEffectsCount = count($mainCharacterEffects);
    $characterEffectPieces = CharacterEffectPieces();
    for ($i = 0; $i < $mainCharacterEffectsCount; $i += $characterEffectPieces) {
      if ($mainCharacterEffects[$i] == $index) {
        switch ($mainCharacterEffects[$i + 1]) {
          //CR 2.1 - 6.5.4. Standard-replacement: Third, each player applies any active standard-replacement effects they control
          //CR 2.1 - 6.5.5. Prevention: Fourth, each player applies any active prevention effects they control
          case "shatter_yellow":
            $pendingDamage = CachedTotalPower() - CachedTotalBlock();
            AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Currently $pendingDamage damage would be dealt. Do you want to destroy a defending equipment instead?");
            AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_destroy_a_blocking_equipment_instead_of_dealing_damage");
            AddDecisionQueue("NOPASS", $mainPlayer, "-");
            AddDecisionQueue("PASSPARAMETER", $mainPlayer, "1", 1);
            AddDecisionQueue("SETCOMBATCHAINSTATE", $mainPlayer, $CCS_CombatDamageReplaced, 1);
            AddDecisionQueue("FINDINDICES", $defPlayer, "SHATTER,$pendingDamage", 1);
            AddDecisionQueue("CHOOSETHEIRCHARACTER", $mainPlayer, "<-", 1);
            AddDecisionQueue("DESTROYCHARACTER", $defPlayer, "-", 1);
            break;
          case "gauntlets_of_iron_will":
            RemoveCurrentTurnEffect($i);
            break;
          default:
            break;
        }
      }
    }
  }
}

function ResolveChainLink()
{
  global $combatChain, $combatChainState, $mainPlayer, $defPlayer, $CCS_CombatDamageReplaced, $CCS_LinkTotalPower;
  BuildMainPlayerGameState();

  $totalPower = 0;
  $totalDefense = 0;
  EvaluateCombatChain($totalPower, $totalDefense);

  $combatChainState[$CCS_LinkTotalPower] = $totalPower;

  LogCombatResolutionStats($totalPower, $totalDefense);
  $targets = explode(",", GetAttackTarget());
  //not strictly accurate, the attacker should get to order the damage, but this fixes most problems
  $reorderedTargets = [];

  foreach ($targets as $target) {
    if ($target != "THEIRCHAR-0")
      array_push($reorderedTargets, $target);
  }
  if (in_array("THEIRCHAR-0", $targets))
    array_push($reorderedTargets, "THEIRCHAR-0");
  AddDecisionQueue("CHECKALLYDEATH", $mainPlayer, "-", 1);

  for ($i = 0; $i < count($reorderedTargets); ++$i) {
    // foreach(explode(",", $targets) as $target) {
    $target = explode("-", $reorderedTargets[$i]);
    if ($target[0] == "THEIRALLY") {
      $index = $target[1];
      if ($index != "") { //check to make sure target is still there
        $allies = &GetAllies($defPlayer);
        $totalPower += CurrentEffectDamageModifiers($mainPlayer, $combatChain[0], "COMBAT");
        if ($totalPower > 0)
          $totalPower += CombatChainDamageModifiers($mainPlayer, $combatChain[0], "COMBAT");
        $totalPower = AllyDamagePrevention($defPlayer, $index, $totalPower, "COMBAT", $combatChain[0]);
        if ($totalPower < 0)
          $totalPower = 0;
        if ($index < count($allies)) {
          $allies[$index + 2] = intval($allies[$index + 2]) - $totalPower;
          if ($totalPower > 0)
            AllyDamageTakenAbilities($defPlayer, $index);
          DamageDealtAbilities($mainPlayer, $totalPower, "COMBAT", $combatChain[0]);
        }
        AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "$totalPower,ALLY");
      }
      else
        AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "0,ALLY");
    }
    else {
      $damage = $combatChainState[$CCS_CombatDamageReplaced] == 1 ? 0 : $totalPower - $totalDefense;
      DamageTrigger($defPlayer, $damage, "COMBAT", $combatChain[0], $mainPlayer); //Include prevention
      // $damageDone = $totalPower-$totalDefense > 0 ? $totalPower-$totalDefense : 0;
      // if ($i > 0 && $i == count($targets) - 1 && !IsGameOver()) ResolveCombatDamage($damageDone, damageTarget: "HERO");
      // else AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "-,HERO");
      AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "-,HERO");
    }
  }
  ProcessDecisionQueue();
}

function ResolveCombatDamage($damageDone, $damageTarget = "HERO")
{
  global $combatChain, $combatChainState, $currentPlayer, $mainPlayer, $currentTurnEffects;
  global $CCS_DamageDealt, $CCS_HitsWithWeapon, $EffectContext, $CS_HitsWithWeapon, $CS_DamageDealt, $CS_PowDamageDealt;
  global $CS_HitsWithSword, $CCS_CurrentAttackGainedGoAgain, $CCS_ChainLinkHitEffectsPrevented, $defPlayer, $CS_HitsWDawnblade;
  global $CS_HitCounter;
  $wasHit = $damageDone > 0;
  $cardID = $combatChain[0];
  if (SearchLayersForPhase("FINALIZECHAINLINK") == -1) {
    PrependLayer("FINALIZECHAINLINK", $mainPlayer, "0");
  }
  WriteLog("Combat resolved with " . ($wasHit ? "a hit for $damageDone damage" : "no hit"));
  if (DoesAttackHaveGoAgain())
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;

  // Track damage for non-ally cards
  if (!DelimStringContains(CardSubtype($cardID), "Ally")) {
    SetClassState($mainPlayer, $CS_DamageDealt, GetClassState($mainPlayer, $CS_DamageDealt) + $damageDone);
    if (!IsHeroAttackTarget())
      SetClassState($mainPlayer, $CS_PowDamageDealt, GetClassState($mainPlayer, $CS_PowDamageDealt) + $damageDone);
  }
  if ($wasHit) {
    LogPlayCardStats($mainPlayer, $cardID, "CC", "HIT");
    $combatChainState[$CCS_DamageDealt] = $damageDone;
    IncrementClassState($mainPlayer, $CS_HitCounter);
    // Handle weapon hit effects
    if (IsWeaponAttack()) {
      ++$combatChainState[$CCS_HitsWithWeapon];
      IncrementClassState($mainPlayer, $CS_HitsWithWeapon);
      if ($cardID == "dawnblade")
        IncrementClassState($mainPlayer, $CS_HitsWDawnblade);
      if (SubtypeContains($cardID, "Sword", $mainPlayer))
        IncrementClassState($mainPlayer, $CS_HitsWithSword);
      if (SearchDynamicCurrentTurnEffectsIndex("war_cry_of_bellona_yellow-DMG", $defPlayer) != -1) {
        $index = SearchDynamicCurrentTurnEffectsIndex("war_cry_of_bellona_yellow-DMG", $defPlayer);
        $params = explode(",", $currentTurnEffects[$index]);
        $amount = $params[1] ?? 0;
        $uniqueID = $params[2] ?? "-";
        if ($damageDone <= $amount && $uniqueID == $combatChain[8]) {
          AddLayer("TRIGGER", $defPlayer, "war_cry_of_bellona_yellow", $amount);
          RemoveCurrentTurnEffect($index);
        }
      }
    }
    if (!HitEffectsArePrevented($cardID)) {
      $count = count($combatChain);
      $pieces = CombatChainPieces();
      for ($i = 0; $i < $count; $i += $pieces) {
        if ($combatChain[$i + 1] == $mainPlayer) {
          $EffectContext = $combatChain[$i];
          AddOnHitTrigger($combatChain[$i], $combatChain[$i + 8]);
          if ($damageDone >= 4 && IsHeroAttackTarget())
            AddCrushEffectTrigger($combatChain[$i]);
          if (CachedTotalPower() >= 13)
            AddTowerEffectTrigger($combatChain[$i]);
        }
      }

      if (IsHeroAttackTarget()) {
        $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
        CheckHitContracts($mainPlayer, $otherPlayer);
      }

      $count = count($currentTurnEffects);
      $pieces = CurrentTurnEffectsPieces();
      for ($i = $count - $pieces; $i >= 0; $i -= $pieces) {
        if (IsCombatEffectActive($currentTurnEffects[$i])) {
          if ($currentTurnEffects[$i + 1] == $mainPlayer) {
            AddEffectHitTrigger($currentTurnEffects[$i], source: $combatChain[0], target: $damageTarget); // Effects that gives effect to the attack
          }
        }
      }

      if ($damageTarget == "HERO") {
        $DefChar = new PlayerCharacter($defPlayer);
        for ($i = 0; $i < $DefChar->NumCards(); ++$i) {
          $CharCard = $DefChar->Card($i, true);
          AddCharacterGetHitTrigger($CharCard->CardID(), "CURRENTATTACK");
        }
      }

      $currentTurnEffects = array_values($currentTurnEffects);
      $targetPlayer = $damageTarget == "HERO" ? $defPlayer : -1;
      MainCharacterHitTrigger($cardID, $targetPlayer);
      MainCharacterHitEffects();
      ArsenalHitEffects();
      AuraHitEffects($cardID);
      ItemHitTrigger($cardID);
      AttackDamageAbilitiesTrigger($damageDone);
      CombatChainHitEffects($combatChain[0], $damageTarget);


      foreach (explode(",", $combatChain[10]) as $effectSetID) {
        $effect = ConvertToCardID($effectSetID);
        if (IsCombatEffectActive($effect) && !$combatChainState[$CCS_ChainLinkHitEffectsPrevented]) {
          AddEffectHitTrigger($effect, source: $combatChain[0], target: $damageTarget); // Effects that do gives their effect to the attack
        }
      }

      $count = count($currentTurnEffects);
      $pieces = CurrentTurnEffectsPieces();
      for ($i = $count - $pieces; $i >= 0; $i -= $pieces) {
        if ($currentTurnEffects[$i] == "celestial_kimono")
          AddLayer("TRIGGER", $currentTurnEffects[$i + 1], "celestial_kimono");
        if (IsCombatEffectActive($currentTurnEffects[$i]) && $currentTurnEffects[$i + 1] == $mainPlayer && !$combatChainState[$CCS_ChainLinkHitEffectsPrevented]) {
          AddCardEffectHitTrigger($currentTurnEffects[$i]); // Effects that do not gives it's effect to the attack
        }
      }
    }

    if (IsHeroAttackTarget()) {
      $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
      if (CheckMarked($otherPlayer)) {
        WriteLog("Player " . $otherPlayer . " loses their mark");
        RemoveMark($otherPlayer);
      }
    }
  }
  else {
    NonHitEffects($cardID);
  }
  $character = &GetPlayerCharacter($mainPlayer);
  $charID = $character[0];
  $charID = ShiyanaCharacter($charID);
  $currentPlayer = $mainPlayer;
  ProcessDecisionQueue(); //Any combat related decision queue logic should be main player gamestate
  MakeGamestateBackup();
}

function FinalizeChainLink($chainClosed = false)
{
  global $turn, $actionPoints, $combatChain, $mainPlayer, $currentPlayer, $combatChainState, $actionPoints, $CCS_DamageDealt;
  global $mainClassState, $CS_AttacksWithWeapon, $CCS_GoesWhereAfterLinkResolves, $CS_LastAttack, $CCS_LinkTotalPower, $CS_NumSwordAttacks, $chainLinks, $chainLinkSummary;
  global $CS_AnotherWeaponGainedGoAgain, $CCS_HitThisLink, $CS_ModalAbilityChoosen, $CS_NumSpectralShieldAttacks, $CombatChain;
  global $layerPriority;
  BuildMainPlayerGameState();
  if (DoesAttackHaveGoAgain() && !$chainClosed) {
    if (SearchCurrentTurnEffects("arc_lightning_yellow", $currentPlayer)) {
      $count = CountCurrentTurnEffects("arc_lightning_yellow", $currentPlayer);
      for ($i = 0; $i < $count; $i++) {
        AddLayer("TRIGGER", $currentPlayer, "arc_lightning_yellow");
      }
    }
    GainActionPoints(1, $mainPlayer);
    if ($combatChain[0] == "dawnblade_resplendent" && SearchCharacterActive($mainPlayer, "dorinthea_quicksilver_prodigy"))
      DoriQuicksilverProdigyEffect();
    if (TypeContains($combatChain[0], "W", $mainPlayer) && GetClassState($mainPlayer, $CS_AnotherWeaponGainedGoAgain) == "-")
      SetClassState($mainPlayer, $CS_AnotherWeaponGainedGoAgain, $combatChain[0]);
  }
  array_push($chainLinkSummary, $combatChainState[$CCS_DamageDealt]);
  array_push($chainLinkSummary, $combatChainState[$CCS_LinkTotalPower]);
  array_push($chainLinkSummary, TalentOverride($combatChain[0] ?? "", $mainPlayer));
  array_push($chainLinkSummary, ClassOverride($combatChain[0] ?? "", $mainPlayer));
  array_push($chainLinkSummary, SerializeCurrentAttackNames());
  $numHitsOnLink = ($combatChainState[$CCS_DamageDealt] > 0 ? 1 : 0);
  $numHitsOnLink += intval($combatChainState[$CCS_HitThisLink]);
  array_push($chainLinkSummary, $numHitsOnLink);
  array_push($chainLinkSummary, LinkBasePower());
  array_push($chainLinkSummary, GetClassState($mainPlayer, $CS_ModalAbilityChoosen));
  array_push($chainLinkSummary, ColorOverride($combatChain[0] ?? "", $mainPlayer));
  
  ResolveWagers($chainClosed);
  if (!$chainClosed) {
    ResolutionStepEffectTriggers();
    ResolutionStepCharacterTriggers();
    ResolutionStepAttackTriggers();
    ResolutionStepBlockTriggers();
  }
  

  array_push($chainLinks, []);
  $CLIndex = count($chainLinks) - 1;
  for ($i = 1; $i < count($combatChain); $i += CombatChainPieces()) {
    $cardType = CardType($combatChain[$i - 1]);
    if ($cardType != "W" || $cardType != "E" || $cardType != "C") {
      $params = explode(",", GoesWhereAfterResolving($combatChain[$i - 1], "COMBATCHAIN", $combatChain[$i]));
      $goesWhere = $params[0];
      if ($i == 1 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] != "GY") $goesWhere = $combatChainState[$CCS_GoesWhereAfterLinkResolves];
      ResolveGoesWhere($goesWhere, $combatChain[$i - 1], $combatChain[$i], "CC", "", count($params) > 1 ? $params[1] : "NA");
    }
    array_push($chainLinks[$CLIndex], $combatChain[$i - 1]); //Card ID
    array_push($chainLinks[$CLIndex], $combatChain[$i]); //Player ID
    array_push($chainLinks[$CLIndex], ($goesWhere == "GY" && $combatChain[$i + 1] != "PLAY" ? "1" : "0")); //Still on chain? 1 = yes, 0 = no
    array_push($chainLinks[$CLIndex], $combatChain[$i + 1]); //From
    array_push($chainLinks[$CLIndex], $combatChain[$i + 4]); //Power Modifier
    array_push($chainLinks[$CLIndex], $combatChain[$i + 5]); //Defense Modifier
    array_push($chainLinks[$CLIndex], "-"); //Added On-hits (comma separated)
    array_push($chainLinks[$CLIndex], $combatChain[$i + 8]); //Original card ID, differs from CardID in case of copies
    array_push($chainLinks[$CLIndex], $combatChain[$i + 7]); //Origin unique ID
    array_push($chainLinks[$CLIndex], $combatChain[$i + 10]); //number of times used
  }

  //Clean up combat effects that were used and are one-time
  CleanUpCombatEffects();
  CopyCurrentTurnEffectsFromCombat();
  ChainLinkResolvedEffects();

  //Don't change state until the end, in case it changes what effects are active
  if ($CombatChain->HasCurrentLink()) {
    if (TypeContains($combatChain[0], "W", $mainPlayer) && !$chainClosed) {
      ++$mainClassState[$CS_AttacksWithWeapon];
      if (CardSubtype($combatChain[0]) == "Sword") ++$mainClassState[$CS_NumSwordAttacks];
    }
    elseif (IsWeaponAttack()) ++$mainClassState[$CS_AttacksWithWeapon];
    if (CardName($combatChain[0]) == "Spectral Shield") ++$mainClassState[$CS_NumSpectralShieldAttacks];
    SetClassState($mainPlayer, $CS_LastAttack, $combatChain[0]);
  }
  $combatChain = [];
  if ($chainClosed) {
    ResetCombatChainState();
    $turn[0] = "M";
    FinalizeAction();
  } else {
    ResetChainLinkState();
  }
  ProcessDecisionQueue();
  //when on always pass priority, ENDTURN will be added to the stack before the resolution step finishes
  //in that case just skip the resolution step?
  if (!$chainClosed && SearchLayersForPhase("ENDTURN") == -1) {
    PrependLayer("RESOLUTIONSTEP", $mainPlayer, "-");
  }
}

function CleanUpCombatEffects($weaponSwap = false, $isSpectraTarget = false)
{
  global $currentTurnEffects, $combatChainState, $CCS_DamageDealt, $combatChain, $chainLinks;
  $effectsToRemove = [];
  $CLIndex = count($chainLinks) - 1;
  isset($combatChain[10]) ? $addedEffects = $combatChain[10] : $addedEffects = "-";
  $currentTurnEffectsCount = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  for ($i = $currentTurnEffectsCount - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    $effectArr = explode(",", $currentTurnEffects[$i]);
    if (IsCombatEffectActive($effectArr[0], $isSpectraTarget) && !IsCombatEffectLimited($i) && !IsCombatEffectPersistent($effectArr[0]) && !AdministrativeEffect($effectArr[0]) && !IsLayerContinuousBuff($effectArr[0])) {
      if ($weaponSwap && EffectHasBlockModifier($effectArr[0])) continue;
      --$currentTurnEffects[$i + 3];
      if ($currentTurnEffects[$i + 3] == 0) array_push($effectsToRemove, $i);
      if (AddedOnHit($currentTurnEffects[$i])) {
        //adding onhits after chain resolution
        if ($addedEffects == "-") $addedEffects = ConvertToSetID($currentTurnEffects[$i]);
        else $addedEffects .= "," . ConvertToSetID($currentTurnEffects[$i]);
      }
    }
    if (ExtractCardID($currentTurnEffects[$i]) == "crouching_tiger") array_push($effectsToRemove, $i);
    switch ($currentTurnEffects[$i]) {
      case "rally_the_rearguard_red":
      case "rally_the_rearguard_yellow":
      case "rally_the_rearguard_blue":
      case "rally_the_coast_guard_red":
      case "rally_the_coast_guard_yellow":
      case "rally_the_coast_guard_blue":
        array_push($effectsToRemove, $i);
        break;
      case "tarpit_trap_yellow":
        if ($combatChainState[$CCS_DamageDealt] > 0 && CardType($combatChain[0]) == "AA") array_push($effectsToRemove, $i);
        break;
      default:
        break;
    }
  }
  if (isset($chainLinks[$CLIndex])) {
    if (isset($chainLinks[$CLIndex][6])) $chainLinks[$CLIndex][6] = $addedEffects;
  }
  $countEffectsToRemove = count($effectsToRemove);
  for ($i = 0; $i < $countEffectsToRemove; ++$i) {
    RemoveCurrentTurnEffect($effectsToRemove[$i]);
  }
}

function BeginTurnPass()
{
  global $mainPlayer, $layers;

  // Only attempt to end turn if no triggers remain on stack
  if (empty($layers)) {
    WriteLog("Player $mainPlayer passed priority. Attempting to end turn.");
    AddLayer("ENDTURN", $mainPlayer, "-");
  }  
  ProcessDecisionQueue();
}

function EndStep()
{
  global $mainPlayer, $turn;
  $turn[0] = "ENDPHASE";
  AddLayer("ENDPHASE", $mainPlayer, "-");
  MainCharacterBeginEndPhaseTriggers();
  AuraBeginEndPhaseTriggers();
  OpponentsAuraBeginEndPhaseTriggers();
  BeginEndPhaseEffectTriggers();
  if (HeaveIndices() != "") AddLayer("TRIGGER", $mainPlayer, "HEAVE");
  UndoIntimidate(1);
  UndoIntimidate(2);
  RemoveBanishedCardFromGraveyard();
  UndoShiyanaBaseLife();
}

function UndoShiyanaBaseLife() // Technically not a End Step Trigger but it's the last time she'll remember what she changed into
{
  global $mainPlayer, $defPlayer;
  $mainChar = GetPlayerCharacter($mainPlayer);
  $defChar = GetPlayerCharacter($defPlayer);
  if ($defChar[0] == "shiyana_diamond_gemini" && SearchCurrentTurnEffects($mainChar[0] . "-SHIYANA", $defPlayer)) {
    $lifeDifference = GeneratedCharacterHealth($mainChar[0]) - GeneratedCharacterHealth("shiyana_diamond_gemini");
    if ($lifeDifference > 0) PlayerLoseHealth($lifeDifference, $defPlayer);
    elseif ($lifeDifference < 0) GainHealth(abs($lifeDifference), $defPlayer, true, false);
  }
}

function UndoIntimidate($player)
{
  global $defPlayer;
  $banish = &GetBanish($player);
  $hand = &GetHand($player);
  $banishCount = count($banish);
  $banishPieces = BanishPieces();
  for ($i = $banishCount - $banishPieces; $i >= 0; $i -= $banishPieces) {
    if ($banish[$i + 1] == "INT") {
      array_push($hand, $banish[$i]);
      RemoveBanish($player, $i);
      continue;
    }
    if ($banish[$i + 1] == "NOFEAR" && SearchLayersForCardID("no_fear_red") == -1) {
      AddLayer("TRIGGER", $player, "no_fear_red", "-");
    }
    if ($banish[$i + 1] == "STONERAIN" && SearchLayersForCardID("stone_rain_red") == -1) {
      AddLayer("TRIGGER", $defPlayer, "stone_rain_red", "-");
    }
  }
}

function RemoveBanishedCardFromGraveyard() //Already Dead code
{
  global $defPlayer;
  $banish = &GetBanish($defPlayer);
  $banishCount = count($banish);
  $banishPieces = BanishPieces();
  for ($i = $banishCount - $banishPieces; $i >= 0; $i -= $banishPieces) {
    if ($banish[$i + 1] == "REMOVEGRAVEYARD") {
      $index = SearchGetFirstIndex(SearchMultizone($defPlayer, "MYDISCARD:cardID=" . $banish[$i]));
      RemoveGraveyard($defPlayer, $index);
    }
  }
}

//4.4.1. Players do not get priority during the End Phase
//CR 2.0 4.4.2.- Beginning of the end phase
function FinishTurnPass()
{
  global $mainPlayer, $turn;
  if ($turn[0] == "OVER") return;
  ClearLog();
  ResetCombatChainState();
  
  // Process end phase abilities
  QuellEndPhase(1);
  QuellEndPhase(2);
  ItemEndTurnAbilities();
  AuraBeginEndPhaseAbilities();
  ArsenalBeginEndPhaseAbilities();
  MainCharacterBeginEndPhaseAbilities();
  LandmarkBeginEndPhaseAbilities();
  BeginEndPhaseEffects();
  PermanentBeginEndPhaseEffects();
  
  AddDecisionQueue("PASSTURN", $mainPlayer, "-");
  ProcessDecisionQueue();
}

function PassTurn()
{
  global $playerID, $currentPlayer, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt;
  if (!$mainPlayerGamestateStillBuilt) {
    BuildMainPlayerGameState();
  }
  $MainHand = GetHand($mainPlayer);
  $otherPlayer = $playerID == 1 ? 2 : 1;
  if (EndTurnPitchHandling($playerID) && EndTurnPitchHandling($otherPlayer)) {
    if (count($MainHand) > 0 && !ArsenalFull($mainPlayer) && $turn[0] != "ARS") {
      $currentPlayer = $mainPlayer;
      $turn[0] = "ARS";
    } else {
      FinalizeTurn();
    }
  }
}

function FinalizeTurn()
{
  global $currentPlayer, $currentTurn, $turn, $combatChain, $actionPoints, $mainPlayer, $defPlayer, $currentTurnEffects, $nextTurnEffects;
  global $mainHand, $defHand, $currentTurnEffectsFromCombat, $mainCharacter, $defCharacter, $mainResources, $defResources;
  global $mainAuras, $firstPlayer, $lastPlayed, $layerPriority, $EffectContext;
  global $MakeStartTurnBackup;
  $extraTurn = SearchCurrentTurnEffects("standing_ovation_blue", $mainPlayer);
  $EffectContext = "-";
  ResetStolenCards();
  LogEndTurnStats($mainPlayer);
  CurrentEffectEndTurnAbilities();
  AuraEndTurnAbilities();
  AllyEndTurnAbilities();
  MainCharacterEndTurnAbilities();
  ItemBeginEndTurnAbilities();
  //4.4.3a Allies life totals are reset
  AllyBeginEndTurnEffects();
  //4.4.3b The turn player may put a card from their hand face down into an empty arsenal zone they own
  ArsenalEndTurn($mainPlayer);
  ArsenalEndTurn($defPlayer);
  //4.4.3c Each player puts all cards in their pitch zone (if any) on the bottom of their deck in any order.The order cards are put on the bottom of the deck this way is hidden information
  //Reset characters/equipment
  $countMainChar = count($mainCharacter);
  $countDefChar = count($defCharacter);
  $charPieces = CharacterPieces();
  for ($i = $countMainChar - $charPieces + 1; $i >= 1; $i -= $charPieces) {
    if ($mainCharacter[$i - 1] == "talishar_the_lost_prince" && $mainCharacter[$i + 1] >= 3) $mainCharacter[$i] = 0; //Destroy Talishar if >= 3 rust counters
    if ($mainCharacter[$i + 6] == 1) {
      DestroyCharacter($mainPlayer, $i-1); //Destroy if it was flagged for destruction
    }
    if ($mainCharacter[$i] != 0) {
      if ($mainCharacter[$i] != 4) $mainCharacter[$i] = 2;
      $mainCharacter[$i + 4] = CharacterNumUsesPerTurn($mainCharacter[$i - 1]);
    }
  }
  for ($i = $countDefChar - $charPieces + 1; $i >= 1; $i -= $charPieces) {
    if (isset($defCharacter[$i + 6]) && $defCharacter[$i + 6] == 1) {
      DestroyCharacter($defPlayer, $i-1); //Destroy if it was flagged for destruction
    }
    elseif (isset($defCharacter[$i]) && ($defCharacter[$i] == 1 || $defCharacter[$i] == 2)) {
      if ($defCharacter[$i] != 4) $defCharacter[$i] = 2;
      $defCharacter[$i + 4] = CharacterNumUsesPerTurn($defCharacter[$i - 1]);
    }
  }
  //Reset Auras
  $countAuras = count($mainAuras);
  $auraPieces = AuraPieces();
  for ($i = 0; $i < $countAuras; $i += $auraPieces) {
    $mainAuras[$i + 1] = 2;
  }
  //4.4.3d All players lose all action points and resources.
  $mainResources[0] = 0;
  $mainResources[1] = 0;
  $defResources[0] = 0;
  $defResources[1] = 0;
  $lastPlayed = [];
  // 4.4.3e The turn player draws cards until the number of cards in their hand is equal to their hero's intellect
  if ($mainPlayer == $firstPlayer && $currentTurn == 0 || $extraTurn)//Defender draws up on turn 1
  {
    $toDraw = CharacterIntellect($defCharacter[0]) - count($defHand);
    for ($i = 0; $i < $toDraw; ++$i) {
      Draw($defPlayer, false, false);
    }
  }
  $toDraw = CharacterIntellect($mainCharacter[0]) - count($mainHand) + CurrentEffectIntellectModifier() + AuraIntellectModifier();
  for ($i = 0; $i < $toDraw; ++$i) {
    Draw($mainPlayer, false, false);
  }
  ResetMainClassState();
  ResetCharacterEffects();
  UnsetTurnBanish();
  AuraEndTurnCleanup();
  DoGamestateUpdate();
  //Update all the player neutral stuff
  if ($mainPlayer == $firstPlayer && !$extraTurn) $currentTurn += 1;
  $turn[0] = "M";
  $turn[2] = "";
  $turn[3] = "";
  // Reset undo decline counters for both players at the start of each turn
  SetCachePiece($GLOBALS['gameName'], 17, 0); // Reset player 1 undo decline counter
  SetCachePiece($GLOBALS['gameName'], 18, 0); // Reset player 2 undo decline counter
  $actionPoints = 1;
  $combatChain = [];
  $currentTurnEffectsFromCombat = [];
  $currentTurnEffects = [];
  $nextTurnEffectsCount = count($nextTurnEffects);
  $nextTurnPieces = NextTurnPieces();
  for ($i = $nextTurnEffectsCount - $nextTurnPieces; $i >= 0; $i -= $nextTurnPieces) {
    switch ($nextTurnEffects[$i + 4]) {
      case 1:
        for ($j = 0; $j < $nextTurnPieces; ++$j) {
          if ($j < CurrentTurnEffectsPieces())
            array_push($currentTurnEffects, $nextTurnEffects[$i + $j]);
          unset($nextTurnEffects[$i + $j]);
        }
        break;
      default:
        --$nextTurnEffects[$i + 4];
        break;
    }
  }
  $nextTurnEffects = array_values($nextTurnEffects);
  // this is needed to reset the defending player's class state even if their turn is getting skipped
  $defPlayer = $mainPlayer;
  $mainPlayer = ($mainPlayer == 1 ? 2 : 1);
  $currentPlayer = $mainPlayer;
  BuildMainPlayerGameState();
  ResetMainClassState();

  if ($extraTurn) {
    $defPlayer = $mainPlayer;
    $mainPlayer = ($mainPlayer == 1 ? 2 : 1);
    $currentPlayer = $mainPlayer;
    BuildMainPlayerGameState();
  }
  //Start of turn effects
  if ($mainPlayer == 1) StatsStartTurn();
  AddLayer("STARTTURN", $mainPlayer, $mainPlayer);
  StartTurnAbilities();
  $MakeStartTurnBackup = true;
  $layerPriority[0] = ShouldHoldPriority(1);
  $layerPriority[1] = ShouldHoldPriority(2);
  if (!$extraTurn) WriteLog("Player $mainPlayer's turn $currentTurn has begun.");
  else WriteLog("Player $mainPlayer's extra turn $currentTurn has begun.");
  DoGamestateUpdate();
  ProcessDecisionQueue();
}

function PlayCard($cardID, $from, $dynCostResolved = -1, $index = -1, $uniqueID = -1, $zone=-1, $facing=0, $mod="-")
{
  // CR 5.1 - Play a Card (includes cost declaration, targeting, and resolution setup)
  global $playerID, $turn, $currentPlayer, $actionPoints, $layers, $CombatChain;
  global $CS_NumActionsPlayed, $CS_NumNonAttackCards, $CS_NumPlayedFromBanish, $CS_DynCostResolved;
  global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layerPriority, $CS_NumWizardNonAttack, $lastPlayed, $CS_PlayIndex, $CS_NumBluePlayed;
  global $decisionQueue, $CS_AbilityIndex, $CS_NumRedPlayed, $CS_PlayUniqueID, $CS_LayerPlayIndex, $CS_LastDynCost, $CS_NumCardsPlayed, $CS_NamesOfCardsPlayed, $CS_NumLightningPlayed;
  global $CS_PlayedAsInstant, $mainPlayer, $EffectContext, $combatChainState, $CCS_GoesWhereAfterLinkResolves, $CS_NumAttacks, $CCS_NumInstantsPlayedByAttackingPlayer;
  global $CCS_NextInstantBouncesAura, $CS_ActionsPlayed, $CS_AdditionalCosts, $CS_NumInstantPlayed, $CS_NumWateryGrave;
  global $CS_NumDraconicPlayed, $CS_TunicTicks, $CCS_NumUsedInReactions, $CCS_NumReactionPlayedActivated, $CS_NumStealthAttacks;
  global $CS_NumCannonsActivated, $chainLinks, $CS_PlayedNimblism, $CS_NumAttackCardsBlocked, $CS_NumCostedCardsPlayed, $CCS_AttackCost;
  global $CS_NumWeaponsActivated;

  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $resources = &GetResources($currentPlayer);
  $pitch = &GetPitch($currentPlayer);
  $dynCostResolved = intval($dynCostResolved);
  
  // Track priority for both players
  $layerPriority[0] = ShouldHoldPriority(1);
  $layerPriority[1] = ShouldHoldPriority(2);
  
  // Determine card properties and game state
  $cardType = CardType($cardID);
  $layersCount = count($layers);
  $playingCard = $turn[0] != "P" && ($turn[0] != "B" || $layersCount > 0);  
  $mod = "";
  
  // Cache frequently accessed class state values to reduce GetClassState() calls
  $cachedPlayIndex = GetClassState($currentPlayer, $CS_PlayIndex);
  $cachedAbilityIndex = GetClassState($currentPlayer, $CS_AbilityIndex);
  $cachedLastDynCost = GetClassState($currentPlayer, $CS_LastDynCost);
  $cachedPlayUniqueID = GetClassState($currentPlayer, $CS_PlayUniqueID);
  $cachedAdditionalCosts = GetClassState($currentPlayer, $CS_AdditionalCosts);
  $cachedLayerPlayIndex = GetClassState($currentPlayer, $CS_LayerPlayIndex);
  
  //manual tunic ticking
  if (ManualTunicSetting($currentPlayer) && $playingCard && $cardID == "fyendals_spring_tunic" && GetClassState($currentPlayer, $CS_TunicTicks) == 0) {
    $character = &GetPlayerCharacter($currentPlayer);
    $cardIndex = FindCharacterIndex($currentPlayer, $cardID);
    // Tunic can be ticked max 3 times per turn
    if ($character[$cardIndex + 2] < 3) {
      ++$character[$cardIndex + 2];
      IncrementClassState($currentPlayer, $CS_TunicTicks);
      return;
    }
  }
  if ($playingCard) {
    $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from) || DelimStringContains($cardType, "I") && $turn[0] != "M";

    //cards with more complicated logic to figure out whether they can be used with an open chain
    $resolutionIndex = SearchLayersForPhase("RESOLUTIONSTEP");
    if ($resolutionIndex != -1 && !$canPlayAsInstant) {
      //block the shortcut if they can't attack
      $blockShortcut = match ($cardID) {
        "teklo_plasma_pistol", "plasma_barrel_shot" => str_contains(GetAbilityNames($cardID, $index, $from), "Attack"),
        default => false,
      };
      
      $isAnAction = $from != "PLAY" && DelimStringContains($cardType, "A") && !GoesOnCombatChain($turn[0], $cardID, $from, $currentPlayer);
      $isNotAbility = GetAbilityTypes($cardID, $index, $from) == "" || GetAbilityNames($cardID, $index, $from) == "-,Action";
      if ($isAnAction && $isNotAbility && !HasMeld($cardID)) {
        // Return card to its zone since it can't be played without paying
        switch ($from) {
          case "HAND":
            AddPlayerHand($cardID, $currentPlayer, "HAND", index: ($index >= 0 ? $index : -1));
            break;
          case "ARS":
            AddArsenal($cardID, $currentPlayer, "ARS", $facing);
            break;
        }
        $layerPriority[$currentPlayer - 1] = "0";
        ProcessInput($currentPlayer, 99, "", $cardID, 0, "");
        return "";
      }
      elseif (GetResolvedAbilityType($cardID, $from) == "A" && !$blockShortcut) {
        if ($from == "HAND") AddPlayerHand($cardID, $currentPlayer, "HAND", index: ($index >= 0 ? $index : -1)); //card is still getting removed from hand, just put it back
        if ($from == "PLAY") {
          // reset the status
          if (SubtypeContains($cardID, "Ally")) {
            $allies = &GetAllies($currentPlayer);
            $allies[$index + 1] = 2;
          }
          elseif (SubtypeContains($cardID, "Item")) {
            $items = &GetItems($currentPlayer);
            ++$items[$index + 3]; // give it back a use
          }
        }
        $layerPriority[$currentPlayer - 1] = "0";
        ProcessInput($currentPlayer, 99, "", $cardID, 0, "");
        return "";
      }
    }
  }
  if ($dynCostResolved == -1) {
    //CR 5.1.1 Play a Card (CR 2.0) - Layer Created
    $abilityType = (IsStaticType($cardType, $from, $cardID)) ? GetResolvedAbilityType($cardID, $from) : "-";
    if ($playingCard) {
      // handle modal elsewhere
      if (GetAbilityTypes($cardID, $index, $from) == "") {
        if (CardType($cardID, $from) == "AA" && $abilityType == "-" || $abilityType == "AA") EndResolutionStep();
      }
      SetClassState($currentPlayer, $CS_AbilityIndex, $index);
      $layerIndex = AddLayer($cardID, $currentPlayer, $from, "-", "-", $uniqueID);
      SetClassState($currentPlayer, $CS_LayerPlayIndex, $layerIndex);
      if ($layersCount > 0) {
        $lastLayerIndex = count($layers) - LayerPieces();
        if ($layers[$lastLayerIndex] == "ENDTURN") $layers[$lastLayerIndex] = "RESUMETURN"; //Means the defending player played something, so the end turn attempt failed
      }
    }
    //CR 5.1.2 Announce (CR 2.0)
    if ($from == "ARS") {
      WriteLog("Player " . $currentPlayer . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID) . " from arsenal", $turn[0] != "P" ? $currentPlayer : 0);
    }
    else if ($from == "THEIRARS") {
      WriteLog("Player " . $currentPlayer . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID) . " from their opponnent's arsenal", $turn[0] != "P" ? $currentPlayer : 0);
    }
    else if ($from == "DECK" && (SearchCharacterActive($currentPlayer, "dash_io") || SearchCharacterActive($currentPlayer, "dash_database"))) {
      WriteLog("Player " . $currentPlayer . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID) . " from the top of their deck", $turn[0] != "P" ? $currentPlayer : 0);
    }
    else if ($turn[0] != "B") WriteLog("Player " . $currentPlayer . " " . PlayTerm($turn[0], $from, $cardID) . " " . CardLink($cardID, $cardID), $turn[0] != "P" ? $currentPlayer : 0);
    if ($turn[0] == "B" && TypeContains($cardID, "E", $currentPlayer)) {
      SetClassState($currentPlayer, $CS_PlayUniqueID, $uniqueID);
      $cachedPlayUniqueID = $uniqueID;
    }
    LogPlayCardStats($currentPlayer, $cardID, $from);
    if ($playingCard) {
      ClearAdditionalCosts($currentPlayer);
      // don't create backups in the resolution step to allow for rewinding if the shortcut is rejected
      if ($layers[0] != "RESOLUTIONSTEP" || (CardType($cardID) != "A" && $abilityType != "A")) MakeGamestateBackup();
      $lastPlayed = [];
      $lastPlayed[0] = $cardID;
      $lastPlayed[1] = $currentPlayer;
      $lastPlayed[2] = $cardType;
      $lastPlayed[3] = "-";
      SetClassState($currentPlayer, $CS_PlayUniqueID, $uniqueID);
      $cachedPlayUniqueID = $uniqueID;
    }
  }
  if ($turn[0] == "A" || $turn[0] == "D" && $currentPlayer == $mainPlayer) {
    ++$combatChainState[$CCS_NumUsedInReactions];
  }
  if ($turn[0] != "P") {
    if ($dynCostResolved >= 0) {
      // OPTIMIZATION: Cache fealty searches to avoid searching twice in same condition
      if ($playingCard && TypeContains($cardID, "AA") && GetResolvedAbilityName($cardID, $from) != "Ability") {
        $hasFealty = SearchCurrentTurnEffects("fealty", $currentPlayer);
        if ($hasFealty && !SearchCurrentTurnEffects("fealty-ATTACK", $currentPlayer)) {
          AddCurrentTurnEffect("fealty-ATTACK", $currentPlayer);
        }
      }
      SetClassState($currentPlayer, $CS_DynCostResolved, $dynCostResolved);
      $baseCost = $from == "PLAY" || $from == "EQUIP" || $from == "COMBATCHAINATTACKS" ? AbilityCost($cardID) : (CardCost($cardID, $from) + SelfCostModifier($cardID, $from));
      if ($from == "GY" && ($cardID == "graven_call" || $cardID == "graven_gaslight")) $baseCost = 0; // hardcoding for now as the card is weird
      if(HasMeld($cardID) && $cachedAdditionalCosts == "Both") $baseCost += $baseCost;
      if (!$playingCard) $resources[1] += $dynCostResolved;
      else {
        $isAlternativeCostPaid = IsAlternativeCostPaid($cardID, $from);
        if ($isAlternativeCostPaid) {
          $baseCost = 0;
          AddAdditionalCost($currentPlayer, "ALTERNATIVECOST");
        }
        $resources[1] += ($dynCostResolved > 0 ? $dynCostResolved + SelfCostModifier($cardID, $from) : $baseCost) + CurrentEffectCostModifiers($cardID, $from) + AuraCostModifier($cardID, $from) + CharacterCostModifier($cardID, $from, $baseCost) + BanishCostModifier($from, $index, $baseCost);
        if ($isAlternativeCostPaid && $resources[1] > 0) WriteLog("<span style='color:red;'>Alternative costs do not offset additional costs.</span>");
      }
      if ($resources[1] < 0) $resources[1] = 0;
      LogResourcesUsedStats($currentPlayer, $resources[1]);
    } else {
      $dqCopy = $decisionQueue;
      $decisionQueue = [];
      //CR 5.1.3 Declare Costs Begin (CR 2.0)
      $resources[1] = 0;
      $dynCost = "";
      $isNuu = SearchCurrentTurnEffects("nuu_alluring_desire", $currentPlayer) || SearchCurrentTurnEffects("nuu", $currentPlayer);
      $nuuActive = $isNuu && ColorContains($cardID, 3, $otherPlayer);
      if ($playingCard) $dynCost = DynamicCost($cardID); //CR 5.1.3a Declare variable cost (CR 2.0)
      if ($playingCard) AddPrePitchDecisionQueue($cardID, $from, $index, $facing); //CR 5.1.3b,c Declare additional/optional costs (CR 2.0)
      if ($dynCost != "" || $dynCost == 0 && substr($from, 0, 5) != "THEIR") {
        AddDecisionQueue("DYNPITCH", $currentPlayer, $dynCost);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_LastDynCost);
        if (TypeContains($cardID, "AA", $currentPlayer)) AddDecisionQueue("SETCOMBATCHAINSTATE", $currentPlayer, $CCS_AttackCost);
      }
      //CR 5.1.4. Declare Modes and Targets
      //CR 5.1.4a Declare targets for resolution abilities
      if ($turn[0] != "B" || ($layersCount > 0 && $layers[0] != "")) GetLayerTarget($cardID, $from);
      //CR 5.1.4b Declare target of attack
      if ($turn[0] == "M" && $actionPoints > 0) AddDecisionQueue("GETTARGETOFATTACK", $currentPlayer, $cardID . "," . $from);
      if ($dynCost == "") AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
      else AddDecisionQueue("GETCLASSSTATE", $currentPlayer, $CS_LastDynCost);
      AddDecisionQueue("RESUMEPAYING", $currentPlayer, $cardID . "-" . $from . "-" . $index . "-" . $uniqueID . "-" . $zone);
      $decisionQueue = array_merge($decisionQueue, $dqCopy);
      ProcessDecisionQueue();
      //MISSING CR 5.1.3d Decide if action that can be played as instant will be
      //MISSING CR 5.1.3e Decide order of costs to be paid
      return;
    }
  } else if ($turn[0] == "P") {
    $pitchValue = PitchValue($cardID);
    $resources[0] += $pitchValue;
    array_push($pitch, $cardID);
    if (CardCaresAboutPitch($turn[3])) AddAdditionalCost($currentPlayer, $cardID);
    PitchAbility($cardID);
  }
  //CR 2.0 5.1.7. Pay Asset-Costs
  if ($resources[0] < $resources[1] || (CardCareAboutChiPitch($cardID) && ($turn[0] != "B" || $layersCount > 0))) {
    if ($turn[0] != "P") {
      $turn[2] = $turn[0];
      $turn[3] = $cardID;
      $turn[4] = $from;
      $turn[5] = $index;
      $turn[6] = $uniqueID;
      $turn[7] = $zone;
    }
    $turn[0] = "P";
    return; //We know we need to pitch more, short circuit here
  }
  $resources[0] -= $resources[1];
  $resourcesPaid = GetClassState($currentPlayer, $CS_DynCostResolved);
  $resources[1] = 0;
  if ($turn[0] == "P") {
    $turn[0] = $turn[2];
    $cardID = $turn[3];
    $from = $turn[4];
    $index = $turn[5] ?? -1;
    $uniqueID = $turn[6] ?? -1;
    $zone = $turn[7] ?? -1;    
    $playingCard = $turn[0] != "P" && ($turn[0] != "B" || count($layers) > 0);
  }
  if ($cachedLastDynCost != 0 && DynamicCost($cardID) != "") WriteLog(CardLink($cardID, $cardID) . " was played with a cost of " . $cachedLastDynCost . ".");
  $cardType = CardType($cardID);
  $abilityType = "";
  $playType = $cardType;
  $EffectContext = $cardID;
  PlayerMacrosCardPlayed();
  if ($zone == "MYCHAR") EquipPayAdditionalCosts($index);
  if ($zone == "MYALLY") AllyPayAdditionalCosts($index, $from);
  //We've paid resources, now pay action points if applicable
  if ($playingCard) {
    $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from, true) || (DelimStringContains($cardType, "I") && $turn[0] != "M");
    if (ActionsThatDoArcaneDamage($cardID, $currentPlayer) || ActionsThatDoXArcaneDamage($cardID)) {
      if(!HasMeld($cardID) && (GetResolvedAbilityType($cardID, $from) == "A" || GetResolvedAbilityType($cardID, $from) == "") || (HasMeld($cardID) && ($cachedAdditionalCosts != "Life" && $cachedAdditionalCosts != "Null")))
      {
        AssignArcaneBonus($currentPlayer);
      }
      else ClearNextCardArcaneBuffs($currentPlayer, $cardID, $from);
    }
    else ClearNextCardArcaneBuffs($currentPlayer, $cardID, $from);
    SetClassState($currentPlayer, $CS_PlayedAsInstant, "0");
    IncrementClassState($currentPlayer, $CS_NumCardsPlayed);
    if (TypeContains($cardID, "W", $currentPlayer) && IsActivated($cardID, $from))
      IncrementClassState($currentPlayer, $CS_NumWeaponsActivated);
    if (HasWateryGrave($cardID) && $from == "GY") IncrementClassState($currentPlayer, $CS_NumWateryGrave);
    if (CardName($cardID) == "Nimblism") IncrementClassState($currentPlayer, $CS_PlayedNimblism);
    //gone in a flash is the active chainlink
    $goneActive = $CombatChain->HasCurrentLink() && $CombatChain->AttackCard()->ID() == "gone_in_a_flash_red";
    if($goneActive && DelimStringContains(CardType($cardID), "I") && $currentPlayer == $mainPlayer) {
      if(SearchCurrentTurnEffects("gone_in_a_flash_red", $mainPlayer, true)) {
        AddLayer("TRIGGER", $mainPlayer, "gone_in_a_flash_red");
      }
    }
    if (SearchCurrentTurnEffects("lightning_greaves", $mainPlayer) && DelimStringContains(CardType($cardID), "I")) {
      // check whether lightning greaves has been activated *before* the card is played
      AddCurrentTurnEffect("lightning_greaves", $currentPlayer, "", $cardID);
    }
    $hero = GetPlayerCharacter($currentPlayer)[0];
    $HeroCard = new CharacterCard(0, $currentPlayer);
    if ($cardID == "goldkiss_rum" && $hero == "scurv_stowaway" && $HeroCard->Status() == 2) {
      AddLayer("TRIGGER", $currentPlayer, $hero);
    }
    $activeAttackID = $CombatChain->HasCurrentLink() ? $CombatChain->AttackCard()->ID() : "";
    $isFireVein = ($activeAttackID == "obsidian_fire_vein" || $activeAttackID == "obsidian_fire_vein_r");
    if($activeAttackID && $isFireVein && $currentPlayer == $mainPlayer) {
      $hasFireVeinEffect = SearchCurrentTurnEffects("obsidian_fire_vein", $currentPlayer) || SearchCurrentTurnEffects("obsidian_fire_vein_r", $currentPlayer);
      if (!$hasFireVeinEffect) {
        if (!IsStaticType($cardType, $from, $cardID) && (TalentContains($cardID, "DRACONIC", $currentPlayer)) && GetResolvedAbilityType($cardID, $from) != "I") {
          AddCurrentTurnEffect($activeAttackID, $currentPlayer);
          GiveAttackGoAgain();
        }
      }
    }

    // Cached card cost
    $cardCost = CardCost($cardID, $from);

    if (IsActivated($cardID, $from)) {
      $playType = GetResolvedAbilityType($cardID, $from);
      $abilityType = $playType;
      PayAbilityAdditionalCosts($cardID, $cachedAbilityIndex, $from, $index);
      ActivateAbilityEffects();
      //modal activated attacks wrapping up resolution step
      if (GetAbilityTypes($cardID, $index, $from) != "" && $abilityType == "AA") {
        $cachedLayerPlayIndex -= EndResolutionStep();
        SetClassState($currentPlayer, $CS_LayerPlayIndex, $cachedLayerPlayIndex);
      }
    } else {
      $currentNamesPlayed = GetClassState($currentPlayer, $CS_NamesOfCardsPlayed);
      switch ($currentNamesPlayed) {
        case "-":
          SetClassState($currentPlayer, $CS_NamesOfCardsPlayed, $cardID);
          break;
        default:
          SetClassState($currentPlayer, $CS_NamesOfCardsPlayed, $currentNamesPlayed . "," . $cardID);
          break;
      }
      if (DelimStringContains($cardType, "A") || $cardType == "AA"){
        $currentActionsPlayed = GetClassState($currentPlayer, $CS_ActionsPlayed);
        switch ($currentActionsPlayed) {
          case "-":
            SetClassState($currentPlayer, $CS_ActionsPlayed, $cardID);
            break;
          default:
            SetClassState($currentPlayer, $CS_ActionsPlayed, $currentActionsPlayed . "," . $cardID);
            break;
        }
      }
      
      //modal played attacks wrapping up resolution step
      if (GetAbilityTypes($cardID, $index, $from) != "") {
        $playType = GetResolvedAbilityType($cardID, $from);
        if ($playType == "AA") {
          $cachedLayerPlayIndex -= EndResolutionStep();
          SetClassState($currentPlayer, $CS_LayerPlayIndex, $cachedLayerPlayIndex);
        }
      }
      $remorselessCount = CountCurrentTurnEffects("remorseless_red-DMG", $playerID);
      if ((DelimStringContains($cardType, "A") || $cardType == "AA") && $remorselessCount > 0 && GetAbilityTypes($cardID, from: $from) == "") {
        WriteLog("Player " . $playerID . " lost " . $remorselessCount . " life to " . CardLink("remorseless_red", "remorseless_red"));
        LoseHealth($remorselessCount, $playerID);
      } elseif ((DelimStringContains($cardType, "A") || $cardType == "AA") && $remorselessCount > 0) {
        $remorselessAbilityType = GetResolvedAbilityType($cardID, $from); // Cache to avoid calling 3 times
        if ($remorselessAbilityType == "" || $remorselessAbilityType == "AA" || $remorselessAbilityType == "A") {
          WriteLog("Player " . $playerID . " lost " . $remorselessCount . " life to " . CardLink("remorseless_red", "remorseless_red"));
          LoseHealth($remorselessCount, $playerID);
        }
      }
      if (CardNameContains($cardID, "Moon Wish", $currentPlayer)) AddCurrentTurnEffect("moon_wish_red-GA", $currentPlayer);

      // Cache values for Illusionist checks
      $classContainsIllusionist = ClassContains($cardID, "ILLUSIONIST", $currentPlayer);
      $subTypeContainsAura = DelimStringContains(CardSubType($cardID), "Aura");

      if ($classContainsIllusionist && $subTypeContainsAura && $cardCost <= 2 && SearchCurrentTurnEffects("vengeful_apparition_red-INST", $currentPlayer, true)) {
        AddCurrentTurnEffect("vengeful_apparition_red", $currentPlayer);
      }
      if ($classContainsIllusionist && $subTypeContainsAura && $cardCost <= 1 && SearchCurrentTurnEffects("vengeful_apparition_yellow-INST", $currentPlayer, true)) {
        AddCurrentTurnEffect("vengeful_apparition_yellow", $currentPlayer);
      }
      if ($classContainsIllusionist && $subTypeContainsAura && $cardCost <= 0 && SearchCurrentTurnEffects("vengeful_apparition_blue-INST", $currentPlayer, true)) {
        AddCurrentTurnEffect("vengeful_apparition_blue", $currentPlayer);
      }
      if ($subTypeContainsAura) {
        SearchCurrentTurnEffects("fluttersteps", $currentPlayer, true);
      }

      CombatChainPlayAbility($cardID);
      ItemPlayAbilities($cardID, $from);
    }
    if (EffectPlayCardRestricted($cardID, $playType, $from, true)) return;
    $resolvedAbilityName = GetResolvedAbilityName($cardID, $from); // Cache to avoid calling twice
    if (TalentContains($cardID, "DRACONIC", $currentPlayer) && $from != "EQUIP" && $from != "PLAY" && $resolvedAbilityName != "Ability") {
      IncrementClassState($currentPlayer, $CS_NumDraconicPlayed);
      SearchCurrentTurnEffects("fealty", $currentPlayer, remove:true);
    }
    if ($cardCost > 0 && $from != "EQUIP" && $from != "PLAY" && $resolvedAbilityName != "Ability") {
      IncrementClassState($currentPlayer, $CS_NumCostedCardsPlayed);
    }
    if (HasStealth($cardID)) {
      $stealthAbilityType = GetResolvedAbilityType($cardID, $from); // Cache to avoid calling twice
      if ($stealthAbilityType == "AA" || $stealthAbilityType == "") {
        IncrementClassState($currentPlayer, piece: $CS_NumStealthAttacks);
      }
    }
    if (SubtypeContains($cardID, "Cannon", $currentPlayer) && IsStaticType($cardType, $from, $cardID)) {
      IncrementClassState($currentPlayer, piece: $CS_NumCannonsActivated);
    }
    if (!IsStaticType($cardType, $from, $cardID) && $playType == "AA" && SearchCurrentTurnEffects("current_funnel_blue", $currentPlayer, true)) {
      GiveAttackGoAgain();
    }
    if (DelimStringContains($playType, "A") || DelimStringContains($playType, "AA")) {
      // there's a bug here where the $index is getting reset if you need to pitch, and I can't figure out why
      if($index == -1) $index = $cachedPlayIndex;
      if($from == "BANISH") $mod = GetBanishModifier($index);
      if ($actionPoints > 0) {
        if(!$canPlayAsInstant) --$actionPoints;
        elseif(GetResolvedAbilityType($cardID, $from) == "AA") --$actionPoints;
        elseif(!$canPlayAsInstant && !IsMeldInstantName($cachedAdditionalCosts) 
        && (GetResolvedAbilityType($cardID, $from) == "A" && !InstantMod($mod))) {
          --$actionPoints;
        }
      }
      if (DelimStringContains($cardType, "A") && $abilityType == "" && GetResolvedAbilityType($cardID, $from) != "I") {
        IncrementClassState($currentPlayer, $CS_NumNonAttackCards);
        if (ClassContains($cardID, "WIZARD", $currentPlayer)) {
          IncrementClassState($currentPlayer, $CS_NumWizardNonAttack);
        }
      }
      if (GetResolvedAbilityType($cardID, $from) != "I") IncrementClassState($currentPlayer, $CS_NumActionsPlayed);
    }
    if ($from == "BANISH" || $from == "THEIRBANISH") IncrementClassState($currentPlayer, $CS_NumPlayedFromBanish);
    if (HasBloodDebt($cardID)) IncrementClassState($currentPlayer, $CS_NumBloodDebtPlayed);
    if (ColorContains($cardID, 1, $currentPlayer) && $from != "PLAY" && GetResolvedAbilityType($cardID, $from) != "I") IncrementClassState($currentPlayer, $CS_NumRedPlayed);
    if (ColorContains($cardID, 3, $currentPlayer) && $from != "PLAY" && GetResolvedAbilityType($cardID, $from) != "I") IncrementClassState($currentPlayer, $CS_NumBluePlayed);
    if (TalentContains($cardID, "LIGHTNING", $currentPlayer) && $from != "EQUIP" && $from != "PLAY" && GetResolvedAbilityType($cardID, $from) != "I") {
      IncrementClassState($currentPlayer, $CS_NumLightningPlayed);
    }
    if(DelimStringContains($cardType, "I")) {
      if(!HasMeld($cardID)) IncrementClassState($currentPlayer, $CS_NumInstantPlayed);
      elseif($from != "MELD") IncrementClassState($currentPlayer, $CS_NumInstantPlayed);
    }
    if(DelimStringContains($cardType, "AR") || DelimStringContains($abilityType, "AR")) {
      if(isset($combatChainState[$CCS_NumReactionPlayedActivated])) ++$combatChainState[$CCS_NumReactionPlayedActivated];
    }
    if ($cardType == "AA" && (GetResolvedAbilityType($cardID, $from) == "" || GetResolvedAbilityType($cardID, $from) == "AA")) {
      IncrementClassState($currentPlayer, $CS_NumAttackCards); //Played or blocked
    } 
    if (($CombatChain->HasCurrentLink() || IsLayerStep()) && $from != "EQUIP" && $from != "PLAY" && DelimStringContains($playType, "I") && GetResolvedAbilityType($cardID, $from) != "I" && $mainPlayer == $currentPlayer) {
      ++$combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer];
      if ($combatChainState[$CCS_NextInstantBouncesAura] == 1) {
        if (IsLayerStep()) {
          if (count($chainLinks) > 0) $triggeredID = $chainLinks[count($chainLinks) - 1][0];
          else $triggeredID = "-";
        }
        else $triggeredID = $CombatChain->AttackCard()->ID();
        $combatChainState[$CCS_NextInstantBouncesAura] = 0;
        if ($triggeredID != "-") {
          $context = "Blast to Oblivion trigger: Choose an aura to return to its owner's hand (or pass)";
          $search = "THEIRAURAS:minCost=0;maxCost=1&THEIRAURAS:type=T&MYAURAS:minCost=0;maxCost=1&MYAURAS:type=T";
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $context);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("ADDTRIGGER", $currentPlayer, $triggeredID, 1);
        }
      }
    }
    AddCharacterPlayCardTrigger($cardID, $playType, $from);
    if (class_exists($cardID)) {
      $card = new $cardID($currentPlayer);
      $card->PayAdditionalCosts($from, $index);
    }
    PayAdditionalCosts($cardID, $from, index: $index);
    if (!IsStaticType($cardType, $from, $cardID)) ResetCardPlayed($cardID, $from);
  }
  if ($turn[0] == "B" && $cardType == "AA" && (GetResolvedAbilityType($cardID, $from) == "AA" || GetResolvedAbilityType($cardID, $from) == "")) {
    IncrementClassState($currentPlayer, $CS_NumAttackCards); //Played or blocked
    IncrementClassState($currentPlayer, $CS_NumAttackCardsBlocked); //Played or blocked
  }
  if ($from == "BANISH") {
    $banish = new Banish($currentPlayer);
    $banish->Remove($cachedPlayIndex);
  } else if ($from == "THEIRBANISH") {
    $banish = new Banish($otherPlayer);
    $banish->Remove($cachedPlayIndex);
    $combatChainState[$CCS_GoesWhereAfterLinkResolves] == "THEIRDISCARD";
  } else if ($from == "GY" && !ActivatedFromGraveyard($cardID)) {
    $discard = new Discard($currentPlayer);
    $discard->Remove($cachedPlayIndex);
  }
  if ($turn[0] != "B" || (count($layers) > 0 && $layers[0] != "")) {
    if ($playType == "AA") IncrementClassState($currentPlayer, $CS_NumAttacks);
    MainCharacterPlayCardAbilities($cardID, $from);
    AuraPlayAbilities($cardID, $from);
    CardPlayTrigger($cardID, $from);
    if (!IsStaticType($cardType, $from, $cardID)) {
      CurrentEffectPlayAbility($cardID, $from);
    }
    if (SubtypeContains($cardID, "Evo", $currentPlayer, $uniqueID)) EvoOnPlayHandling($currentPlayer);
    
  }
  AddDecisionQueue("RESUMEPLAY", $currentPlayer, $cardID . "|" . $from . "|" . $resourcesPaid . "|" . $cachedAbilityIndex . "|" . $cachedPlayUniqueID . "|" . $zone);
  ProcessDecisionQueue();
}

function InstantMod($mod)
{
  return match ($mod) {
    "INST" => true,
    "sonic_boom_yellow" => true,
    default => false
  };
}

function PlayCardSkipCosts($cardID, $from)
{
  global $layers, $turn;
  $cardType = CardType($cardID);
  // this is now handled earlier
  // GetTargetOfAttack($cardID); // Not sure why this is needed (2x GetTargetOfAttack), but it works....
  // if (($turn[0] == "M" || $turn[0] == "ATTACKWITHIT") && $cardType == "AA") GetTargetOfAttack($cardID);
  if ($turn[0] != "B" || (count($layers) > 0 && $layers[0] != "")) {
    GetLayerTarget($cardID, $from);
  }
  PlayCardEffect($cardID, $from, "Skipped");
}

function GetLayerTarget($cardID, $from)
{
  global $currentPlayer, $defPlayer, $layers, $CombatChain, $mainPlayer;
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->GetLayerTarget($from);
  switch ($cardID) {
    case "rout_red":
    case "singing_steelblade_yellow":
    case "overpower_red":
    case "overpower_yellow":
    case "overpower_blue":
    case "glint_the_quicksilver_blue":
    case "biting_blade_red":
    case "biting_blade_yellow":
    case "biting_blade_blue":
    case "stroke_of_foresight_red":
    case "stroke_of_foresight_yellow":
    case "stroke_of_foresight_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINATTACKS:type=W&ACTIVEATTACK:type=W");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a weapon attack");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "rattle_bones_red":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:type=AA;class=RUNEBLADE");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target Runeblade attack action card");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "aetherize_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "LAYER:type=I;maxCost=1");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "blinding_beam_red":
    case "blinding_beam_yellow":
    case "blinding_beam_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "CCAA");
      AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "memorial_ground_red":
    case "memorial_ground_yellow":
    case "memorial_ground_blue":
      $maxCost = 2;
      if ($cardID == "memorial_ground_yellow") $maxCost = 1;
      elseif ($cardID == "memorial_ground_blue") $maxCost = 0;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:maxCost=" . $maxCost . ";type=AA");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "sow_tomorrow_red":
    case "sow_tomorrow_yellow":
    case "sow_tomorrow_blue":
      if($cardID == "sow_tomorrow_red") $minCost = 0;
      else $minCost = ($cardID == "sow_tomorrow_yellow") ? 1 : 2;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:type=A;talent=EARTH,ELEMENTAL;minCost=" . $minCost . "&MYDISCARD:type=AA;talent=EARTH,ELEMENTAL;minCost=" . $minCost);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target action card");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "lightning_press_red":
    case "lightning_press_yellow":
    case "lightning_press_blue":
      $botLayer = $layers[count($layers) - LayerPieces()];
      if (CardType($botLayer) == "AA" && CardCost($botLayer) <= 1) {
        // targetting attack layer
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "-");
      }
      elseif(!ShouldHoldPriority($currentPlayer) && ShouldAutotargetOpponent($currentPlayer)) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINLINK:maxCost=1;type=AA");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINATTACKS:maxCost=1;type=AA&COMBATCHAINLINK:maxCost=1;type=AA");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      }
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "scour_blue":
      global $CS_LastDynCost;
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("GETCLASSSTATE", $currentPlayer, $CS_LastDynCost);
      AddDecisionQueue("SETSCOURDQVAR", $currentPlayer, "1");
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1");
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARCANETARGET,{1}", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target hero for <0>", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SCOURTARGETTING", 1);
      break;
    case "rewind_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "NAACARDLAYER");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "silken_form": //Invocations must target Ash
    case "invoke_dracona_optimai_red":
    case "invoke_tomeltai_red":
    case "invoke_dominia_red":
    case "invoke_azvolai_red":
    case "invoke_cromai_red":
    case "invoke_kyloria_red":
    case "invoke_miragai_red":
    case "invoke_nekria_red":
    case "invoke_ouvia_red":
    case "invoke_themai_red":
    case "invoke_vynserakai_red":
    case "invoke_yendurai_red":
    case "skittering_sands_red":
    case "skittering_sands_yellow":
    case "skittering_sands_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYPERM:subtype=Ash");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an Ash to transform");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "sand_cover_red": //sand cover
    case "sand_cover_yellow":
    case "sand_cover_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYPERM:subtype=Ash");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an Ash to grant ward");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "helios_mitre":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DAMAGEPREVENTIONTARGET");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a damage source for " . CardLink($cardID, $cardID));
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "oasis_respite_red":
    case "oasis_respite_yellow":
    case "oasis_respite_blue":
      if (!ShouldAutotargetOpponent($currentPlayer)) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=C&THEIRCHAR:type=C");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a hero to grant respite");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      }
      else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYCHAR-0",1);
      }
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "shred_red":
    case "shred_yellow":
    case "shred_blue":
      $pastChoices = GetPastChainLinkCards($defPlayer, asMZInd: true, blockingClass: "ASSASSIN");
      if (ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer)) {
        $currentChoices =  GetChainLinkCards($defPlayer, asMZInd:true);
      }
      else $currentChoices = "";
      if ($currentChoices == "") $choices = $pastChoices;
      elseif ($pastChoices == "") $choices = $currentChoices;
      else $choices = "$pastChoices,$currentChoices";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a defending card to shred");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $choices);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "platinum_amulet_blue":
      if ($from == "PLAY"){
        $numOptions = explode(",", GetChainLinkCards($defPlayer, "", "C"));
        $options = [];
        foreach ($numOptions as $num) array_push($options, "COMBATCHAINLINK-$num");
        $options = implode(",", $options);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a defending card to buff");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $options, 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      }
      break;
    case "prism_awakener_of_sol":
    case "prism_advent_of_thrones":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYPERM:subtype=Figment");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a figment to awaken");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "celestial_reprimand_red":
    case "celestial_reprimand_yellow":
    case "celestial_reprimand_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "CCDEFLESSX,999");
      AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "cleansing_light_red":
    case "cleansing_light_yellow":
    case "cleansing_light_blue":
      if($cardID == "cleansing_light_red") $targetPitch = 1;
      else if($cardID == "cleansing_light_yellow") $targetPitch = 2;
      else if($cardID == "cleansing_light_blue") $targetPitch = 3;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRAURAS:pitch=" . $targetPitch . "&MYAURAS:pitch=" . $targetPitch);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target aura");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "pass_over_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRDISCARD");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target card");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "preserve_tradition_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:type=A&MYDISCARD:type=AA");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target action card");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "just_a_nick_red":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "COMBATCHAIN");
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID);
      break;
    case "astral_etchings_red":
    case "astral_etchings_yellow":
    case "astral_etchings_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasWard=true");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target aura");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "sanctuary_of_aria":
      if($from != "HAND"){
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DAMAGEPREVENTIONTARGET");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a damage source for " . CardLink($cardID, $cardID));
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      }
      break;
    case "dragonscaler_flight_path":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINATTACKS:talent=DRACONIC&ACTIVEATTACK:talent=DRACONIC");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a draconic attack");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "affirm_loyalty_red":
    case "endear_devotion_red":
    case "blistering_blade_red":
    case "dynastic_dedication_red":
    case "imperial_intent_red":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINATTACKS:subtype=Dagger&ACTIVEATTACK:subtype=Dagger");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a dagger attack");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "a_drop_in_the_ocean_blue":
    case "path_well_traveled_blue":
    case "the_grain_that_tips_the_scale_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "COMBATCHAINATTACKS&ACTIVEATTACK");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an attack (pass to target an attack in layer step)");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "scrub_the_deck_blue":
      $context = "Choose whose deck to scrub";
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYCHAR-0,THEIRCHAR-0");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $context, 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      break;
    case "midas_touch_yellow":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRALLY&MYALLY");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an ally to destroy");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "arcane_compliance_blue":
      $indices = explode(",", SearchLayersCardType("A", "AA"));
      $formattedIndices = [];
      foreach ($indices as $index) array_push($formattedIndices, "LAYER-$index");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, implode(",", $formattedIndices));
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an action to block arcane buffs on");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);  
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    default:
      break;
  }
  $targetType = PlayRequiresTarget($cardID, $from);
  if ($targetType != -1) {
    AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID);
    AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for <0>");
    AddDecisionQueue("FINDINDICES", $currentPlayer, "ARCANETARGET," . $targetType);
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for <0>");
    AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
    AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
  }
}

function AddPrePitchDecisionQueue($cardID, $from, $index = -1, $facing="-")
{
  global $currentPlayer, $CS_NumActionsPlayed, $CS_AdditionalCosts, $turn, $combatChainState, $CCS_EclecticMag, $CS_NextWizardNAAInstant, $CS_NextNAAInstant;
  global $actionPoints, $mainPlayer, $currentTurnEffects;
  $cardType = CardType($cardID);
  $mod = "";
  if($from == "BANISH") $mod = GetBanishModifier($index);
  if (IsStaticType($cardType, $from, $cardID)) {
    $names = GetAbilityNames($cardID, $index, $from);
    if ($names != "") {
      $names = str_replace("-,", "", $names);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which ability to activate");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
      AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
    }
  }
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->AddPrePitchDecisionQueue($from, $index, $facing);
  if(HasMeld($cardID)) {
    $names = explode(" // ", CardName($cardID));
    $countCurrentTurnEffects = count($currentTurnEffects);
    $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
    for ($i = $countCurrentTurnEffects - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
      if ($currentTurnEffects[$i + 1] == $currentPlayer) {
        $effectArr = explode(",", $currentTurnEffects[$i]);
        $effectID = $effectArr[0];
        switch ($effectID) {
          case "censor_red":
            if (!SearchCurrentTurnEffects("amnesia_red", $currentPlayer)) {
              if (GamestateSanitize($names[0]) == $effectArr[1]) {
                $names[0] = "-";
              }
              elseif (GamestateSanitize($names[1]) == $effectArr[1]) {
                $names[1] = "-";
              }
            }
            break;
          default:
            break;
        }
      }
    }
    if (!SearchCurrentTurnEffects("amnesia_red", $currentPlayer) && $from == "HAND") {
      if (NameBlocked($names[0], $index, $from, nameGiven:true)) $names[0] = "-";
      if (NameBlocked($names[1], $index, $from, nameGiven:true)) $names[1] = "-";
    }
    if (DelimStringContains($cardType, "A") && SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
      $names[0] = "-";
      // $option = $names[1];
    } elseif (
      !IsInstantMod($mod) 
      && $cardType != "I"
      && (!$combatChainState[$CCS_EclecticMag]
      && (GetClassState($currentPlayer, $CS_NextWizardNAAInstant) == 0 || !ClassContains($cardID, "WIZARD", $currentPlayer))
      && GetClassState($currentPlayer, $CS_NextNAAInstant) == 0
      && ($actionPoints < 1 || $currentPlayer != $mainPlayer || $turn[0] == "INSTANT" || $turn[0] == "A" || SearchLayersForPhase("RESOLUTIONSTEP") != -1)
      || SearchCurrentTurnEffects("WarmongersWar", $currentPlayer))
    ) {
        $names[0] = "-";
    }
    if ($names[0] == "-" && $names[1] == "-") {
      WriteLog("Both sides of the meld card are blocked, reverting play", highlight: true);
      RevertGamestate();
    }
    elseif ($names[0] == "-") $option = $names[1];
    elseif ($names[1] == "-") $option = $names[0];
    else $option = $names[0].",".$names[1].",Both";
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which halves to activate");
    AddDecisionQueue("BUTTONINPUT", $currentPlayer, $option);
    AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
    AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
    AddDecisionQueue("MELDTARGETTING", $currentPlayer, $cardID, 1);
  }
  switch ($cardID) {
    case "lord_of_wind_blue":
      if (ComboActive($cardID)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "1", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("LORDOFWIND", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      }
      break;
    case "moon_wish_red":
    case "moon_wish_yellow":
    case "moon_wish_blue":
      HandToTopDeck($currentPlayer);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "moon_wish_red", 1);
      break;
    case "cash_in_yellow":
      if (CountItem("copper", $currentPlayer) >= 4) //Copper
      {
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_4_" . CardLink("copper", "copper"), 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "copper-4", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "cash_in_yellow", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, CardLink("copper", "copper") . "_alternative_cost_was_paid.", 1);
      }
      if (CountItem("silver", $currentPlayer) >= 2) //Silver
      {
        AddDecisionQueue("SEARCHCURRENTEFFECTPASS", $currentPlayer, "cash_in_yellow");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_2_" . CardLink("silver", "silver"), 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "silver-2", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "cash_in_yellow", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, CardLink("silver", "silver") . "_alternative_cost_was_paid.", 1);
      }
      if (CountItem("gold", $currentPlayer) >= 1) 
      {
        AddDecisionQueue("SEARCHCURRENTEFFECTPASS", $currentPlayer, "cash_in_yellow");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_1_" . CardLink("gold", "gold"), 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        $goldIndices = GetGoldIndices($currentPlayer);
        if (str_contains($goldIndices, "MYCHAR")) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, $goldIndices, 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        } else
          AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "gold-1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "cash_in_yellow", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, CardLink("gold", "gold") . "_alternative_cost_was_paid.", 1);
      }
      break;
    case "soul_reaping_red":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHAND");
      AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SOULREAPING", 1);
      break;
    case "rise_above_red":
    case "rise_above_yellow":
    case "rise_above_blue":
      HandToTopDeck($currentPlayer);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "rise_above_red", 1);
      break;
    case "life_of_the_party_red":
    case "life_of_the_party_yellow":
    case "life_of_the_party_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "LIFEOFPARTY");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a {{element|Crazy Brew|4}} 🎉");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    //Windups
    case "mighty_windup_red":
    case "mighty_windup_yellow":
    case "mighty_windup_blue":
    case "agile_windup_red":
    case "agile_windup_yellow":
    case "agile_windup_blue":
    case "vigorous_windup_red":
    case "vigorous_windup_yellow":
    case "vigorous_windup_blue":
    case "ripple_away_blue":
    case "fruits_of_the_forest_red":
    case "fruits_of_the_forest_yellow":
    case "fruits_of_the_forest_blue":
    case "trip_the_light_fantastic_red":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_blue":
    case "under_the_trap_door_blue":
    case "reapers_call_red":
    case "reapers_call_yellow":
    case "reapers_call_blue":
    case "tip_off_red":
    case "tip_off_yellow":
    case "tip_off_blue":
    case "deny_redemption_red":
    case "bam_bam_yellow":
    case "outside_interference_blue":
    case "fearless_confrontation_blue":
      $names = GetAbilityNames($cardID, $index, $from);
      $names = str_replace("-,", "", $names);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      } elseif ($names != "" && $from == "HAND") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose to play the ability or attack");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      } else {
        AddDecisionQueue("SETABILITYTYPEATTACK", $currentPlayer, $cardID);
      }
      AddDecisionQueue("NOTEQUALPASS", $currentPlayer, "Ability");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
      AddDecisionQueue("CONVERTLAYERTOABILITY", $currentPlayer, $cardID, 1);
      break;
    case "double_down_red":
      AddDecisionQueue("COUNTITEM", $currentPlayer, "gold"); 
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1");
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_1_" . CardLink("gold", "gold"), 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      $goldIndices = GetGoldIndices($currentPlayer);
      if (str_contains($goldIndices, "MYCHAR")) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $goldIndices, 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      } else AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "gold-1", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "double_down_red-PAID", 1);
      break;
    case "10000_year_reunion_red":
      $count = CountAuraPowerCounters($currentPlayer);
      if ($from != "PLAY" && $count >= 3) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasPowerCounters=true");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to remove a -1 Power Counter (or pass)");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("MZOP", $currentPlayer, "REMOVEPOWERCOUNTER", 1);
        for ($i = 0; $i < 2; $i++) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYAURAS:hasPowerCounters=true", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to remove a -1 Power Counter", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $currentPlayer, "REMOVEPOWERCOUNTER", 1);
        }
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      }
      break;
    case "blaze_firemind":
      $char = &GetPlayerCharacter($currentPlayer);
      $numCounters = $char[2];
      $costChoices = "0";
      for ($i = 1; $i <= $numCounters; ++$i) {
        $costChoices .= "," . $i;
      }
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how much you want to pay");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $costChoices);
      AddDecisionQueue("ADDCURRENTEFFECTLASTRESULT", $currentPlayer, "blaze_firemind-", 1);
      AddDecisionQueue("BLAZEPAYCOST", $currentPlayer, "<-", 1);
      break;
    case "haunting_rendition_red":
    case "mental_block_blue":
      AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
      AddDecisionQueue("CONVERTLAYERTOABILITY", $currentPlayer, $cardID, 1);
      break;
    case "chorus_of_the_amphitheater_red":
    case "chorus_of_the_amphitheater_yellow":
    case "chorus_of_the_amphitheater_blue":
    case "arcane_twining_red":
    case "arcane_twining_yellow":
    case "arcane_twining_blue":
    case "photon_splicing_red":
    case "photon_splicing_yellow":
    case "photon_splicing_blue":
    case "burn_bare":
      $names = GetAbilityNames($cardID, $index, $from);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      } elseif ($names != "" && $from == "HAND" && $names != "-,Action"){
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose to play the ability or the action");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      } else{
        AddDecisionQueue("SETABILITYTYPEACTION", $currentPlayer, $cardID);
      }
      AddDecisionQueue("NOTEQUALPASS", $currentPlayer, "Action", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for <0>", 1);
      $targetType = 2;
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARCANETARGET,$targetType", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for <0>", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      AddDecisionQueue("ELSE", $currentPlayer, "-");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
      AddDecisionQueue("CONVERTLAYERTOABILITY", $currentPlayer, $cardID, 1);
      break;
    case "war_cry_of_themis_yellow":
      $names = GetAbilityNames($cardID, $index, $from);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      } elseif ($names != "" && $from == "HAND"){
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose to play the ability or the action");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      } else{
        AddDecisionQueue("SETABILITYTYPEACTION", $currentPlayer, $cardID);
      }
      // fix this later
      break;
    case "shelter_from_the_storm_red":
      $names = GetAbilityNames($cardID, $index, $from);
      if ($names == "Ability,Defense Reaction") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose to play the defense reaction or ability");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      } 
      elseif ($names == "-,Defense Reaction") {
        AddDecisionQueue("SETABILITYTYPEDEFENSEREACTION", $currentPlayer, $cardID);
      }
      else {
        AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      }
      AddDecisionQueue("NOTEQUALPASS", $currentPlayer, "Ability");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
      AddDecisionQueue("CONVERTLAYERTOABILITY", $currentPlayer, $cardID, 1);
      break;
    case "war_cry_of_bellona_yellow":
      $names = GetAbilityNames($cardID, $index, $from);
      if ($names == "Ability,Attack Reaction") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose to play the attack reaction or ability");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      } 
      elseif ($names == "-,Attack Reaction") {
        AddDecisionQueue("SETABILITYTYPEATTACKREACTION", $currentPlayer, $cardID);
      }
      else {
        AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      }
      break;
    case "clap_em_in_irons_blue":
      $context = "Choose an a target to " . CardLink($cardID, $cardID);
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYALLY:class=PIRATE&THEIRALLY:class=PIRATE&MYCHAR:type=C;class=PIRATE&THEIRCHAR:type=C;class=PIRATE");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $context, 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      break;
    case "light_up_the_leaves_red":
      $names = GetAbilityNames($cardID, $index, $from);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        AddDecisionQueue("SETABILITYTYPEABILITY", $currentPlayer, $cardID);
      } elseif ($names != "" && $from == "HAND" && $names != "-,Action"){
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose to play the ability or the action");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      } else{
        AddDecisionQueue("SETABILITYTYPEACTION", $currentPlayer, $cardID);
      }
      AddDecisionQueue("NOTEQUALPASS", $currentPlayer, "Action", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for <0>", 1);
      $targetType = 2;
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARCANETARGET,$targetType", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for <0>", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);

      AddDecisionQueue("ELSE", $currentPlayer, "-");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
      // discarding an extra earth card
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an {{element|Earth|" . GetElementColorCode("EARTH") . "}} Card to discard", 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDTALENT,EARTH,NOPASS", 1);
      AddDecisionQueue("REVERTGAMESTATEIFNULL", $currentPlayer, "You don't have any earth cards in hand to discard!", 1);
      AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-" . $currentPlayer, 1);
      // targetting a source
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DAMAGEPREVENTIONTARGET", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a damage source for " . CardLink($cardID, $cardID), 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);

      AddDecisionQueue("CONVERTLAYERTOABILITY", $currentPlayer, $cardID, 1);
      break;
    default:
      break;
  }
}

function GetTargetOfAttack($cardID = "")
{
  global $mainPlayer, $combatChainState, $CCS_AttackTarget, $currentTurnEffects;
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  $numTargets = 0;
  $currentTargets = $combatChainState[$CCS_AttackTarget];
  if (!str_contains($currentTargets, "THEIRCHAR-0")) {
    $targets = "THEIRCHAR-0";
    ++$numTargets;
  }
  else {
    $targets = "";
  }
  if (CanOnlyTargetHeroes($cardID)) {
    if ($targets == "") WriteLog("Something weird happened, please submit a bug report", highlight:true);
    $combatChainState[$CCS_AttackTarget] = $targets;
  } else {
    $auras = &GetAuras($defPlayer);
    $mandatoryTargets = [];
    $countAuras = count($auras);
    $auraPieces = AuraPieces();
    for ($i = 0; $i < $countAuras; $i += $auraPieces) {
      $targIndex = "THEIRAURAS-$i";
      if (HasSpectra($auras[$i]) && !str_contains($currentTargets, $targIndex)) {
        $targets = $targets == "" ? $targIndex : "$targets,$targIndex";
        ++$numTargets;
        if ($auras[$i] == "arc_light_sentinel_yellow") array_push($mandatoryTargets, "THEIRAURAS-$i");
      }
    }
    $allies = &GetAllies($defPlayer);
    $countAllies = count($allies);
    $allyPieces = AllyPieces();
    for ($i = 0; $i < $countAllies; $i += $allyPieces) {
      $targIndex = "THEIRALLY-$i";
      if (!str_contains($currentTargets, $targIndex)) {
        $targets = $targets == "" ? $targIndex : "$targets,$targIndex";
        ++$numTargets;
        if ($allies[$i] == "chum_friendly_first_mate_yellow") {
          $countCurrentTurnEffects = count($currentTurnEffects);
          $currentTurnEffectPieces = CurrentTurnEffectPieces();
          for ($j = 0; $j < $countCurrentTurnEffects; $j += $currentTurnEffectPieces) {
            if ($currentTurnEffects[$j+1] == $mainPlayer && $currentTurnEffects[$j] == "chum_friendly_first_mate_yellow") {
              if ($currentTurnEffects[$j+2] == $allies[$i+5]) {
                array_push($mandatoryTargets, "THEIRALLY-$i");
              }
            }
          }
        }
      }
    }
    if (count($mandatoryTargets) > 0) {
      $targets = implode(",", $mandatoryTargets);
    }
    if ($numTargets > 1) {
      PrependDecisionQueue("PROCESSATTACKTARGET", $mainPlayer, "-");
      PrependDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $targets);
      PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target for the attack");
    } elseif ($numTargets == 1) {
      PrependDecisionQueue("PROCESSATTACKTARGET", $mainPlayer, "-");
      PrependDecisionQueue("PASSPARAMETER", $mainPlayer, $targets);
      PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target for the attack");
    }
    else WriteLog("There are no additional targets to attack!");
  }
  AddDecisionQueue("TRUCE", $mainPlayer, "-");
}

function PayAbilityAdditionalCosts($cardID, $index, $from="-", $zoneIndex=-1)
{
  global $currentPlayer;
  if (class_exists($cardID)) {
    $card = new $cardID($currentPlayer);
    $ret = $card->PayAbilityAdditionalCosts($index, $from, $zoneIndex);
    return $ret;
  }
  switch ($cardID) {
    case "great_library_of_solana":
      for ($i = 0; $i < 2; ++$i) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDPITCH,2");
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-" . $currentPlayer, 1);
      }
      break;
    case "arakni_black_widow":
    case "arakni_funnel_web":
    case "arakni_orb_weaver":
    case "arakni_redback":
    case "arakni_tarantula":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDCLASS,ASSASSIN,NOPASS");
      AddDecisionQueue("REVERTGAMESTATEIFNULL", $currentPlayer, "You don't have any assassin cards in hand to discard!", 1);
      AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-" . $currentPlayer, 1);
      break;
    case "kunai_of_retribution":
    case "kunai_of_retribution_r":
      $character = GetPlayerCharacter($currentPlayer);
      $uniqueID = $character[$index + 11];
      AddCurrentTurnEffect("$cardID-$uniqueID", $currentPlayer);
      break;
    case "chum_friendly_first_mate_yellow":
    case "anka_drag_under_yellow":
      $allies = GetAllies($currentPlayer);
      if (GetResolvedAbilityType($cardID, $from) == "I") {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDWATERYGRAVE,-,NOPASS");
        AddDecisionQueue("REVERTGAMESTATEIFNULL", $currentPlayer, "You don't have any watery grave cards in hand to discard!", 1);
        AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-" . $currentPlayer, 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $allies[$zoneIndex + 5], 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      }
      break;
    case "moray_le_fay_yellow":
      if (GetResolvedAbilityType($cardID, $from) == "I") {
        $context = "Choose an ally to receive ".CardLink($cardID, $cardID)."'s blessing";
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYALLY&THEIRALLY");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $context, 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      }
      break;
    case "kelpie_tangled_mess_yellow":
      if (GetResolvedAbilityType($cardID, $from) == "A") {
        $context = "Choose an a target to tangle";
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYALLY&THEIRALLY&MYCHAR:type=C&THEIRCHAR:type=C");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $context, 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      }
      break;
    default:
      break;
  }
}

function PayAdditionalCosts($cardID, $from, $index="-")
{
  global $currentPlayer, $CS_AdditionalCosts, $CS_CharacterIndex, $CS_PlayIndex, $CombatChain, $CS_NumBluePlayed, $combatChain, $combatChainState;
  global $layers, $CS_DynCostResolved, $mainPlayer, $defPlayer, $mainPlayerGamestateStillBuilt, $myStateBuiltFor;
  global $combatChain, $chainLinks;
  $cardSubtype = CardSubType($cardID);
  if ($from == "PLAY" && DelimStringContains($cardSubtype, "Item")) {
    PayItemAbilityAdditionalCosts($cardID, $from);
    return;
  } else if ($from == "PLAY" && DelimStringContains($cardSubtype, "Aura")) {
    PayAuraAbilityAdditionalCosts($cardID, $from);
    return;
  } else if ($from == "EQUIP") {
    switch ($cardID) {
      case "evo_command_center_yellow_equip":
      case "evo_engine_room_yellow_equip":
      case "evo_smoothbore_yellow_equip":
      case "evo_thruster_yellow_equip":
      case "evo_data_mine_yellow_equip":
      case "evo_battery_pack_yellow_equip":
      case "evo_cogspitter_yellow_equip":
      case "evo_charging_rods_yellow_equip":
        CharacterChooseSubcard($currentPlayer, GetClassState($currentPlayer, $CS_PlayIndex), fromDQ: true, actionName:"destroy");
        AddDecisionQueue("ADDDISCARD", $currentPlayer, "-", 1);
        break;
      default:
        break;
    }
  }
  if (HasBoost($cardID, $currentPlayer) && $cardID != "twin_drive_red") Boost($cardID);
  $fuseType = HasFusion($cardID);
  if ($fuseType != "") Fuse($cardID, $currentPlayer, $fuseType);
  if (HasScrap($cardID)) Scrap($currentPlayer);
  if (RequiresDiscard($cardID)) {
    $discarded = DiscardRandom($currentPlayer, $cardID);
    if ($discarded == "") {
      WriteLog("You do not have a card to discard. Reverting gamestate.", highlight: true);
      RevertGamestate();
      return;
    }
    SetClassState($currentPlayer, $CS_AdditionalCosts, $discarded);
  }
  if (RequiresBanish($cardID)) {
    $banished = BanishRandom($currentPlayer, $cardID);
    if ($banished == "") {
      WriteLog("You do not have a card to banish. Reverting gamestate.", highlight: true);
      RevertGamestate();
      return;
    }
    SetClassState($currentPlayer, $CS_AdditionalCosts, $banished);
  }
  if (HasBeatChest($cardID)) {
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to beat chest");
    AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDMINPOWER,6");
    AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
    AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "BEATCHEST", 1);
  }
  switch ($cardID) {
    case "enlightened_strike_red":
      $hand = &GetHand($currentPlayer);
      if (count($hand) == 0) {
        WriteLog("You do not have a card to sink. Reverting gamestate.", highlight: true);
        RevertGamestate();
        return;
      }
      BottomDeck();
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Draw_a_Card,Buff_Power,Go_Again");
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "demolition_crew_red":
    case "demolition_crew_yellow":
    case "demolition_crew_blue":
      $indices = SearchHand($currentPlayer, "", "", -1, 2);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to reveal");
      AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
      AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-");
      break;
    case "flock_of_the_feather_walkers_red":
    case "flock_of_the_feather_walkers_yellow":
    case "flock_of_the_feather_walkers_blue":
      $indices = SearchHand($currentPlayer, "", "", 1, 0);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to reveal");
      AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
      AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-");
      break;
    case "nimble_strike_red":
    case "nimble_strike_yellow":
    case "nimble_strike_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:isSameName=nimblism_red");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBANISH,GY,-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "regurgitating_slog_red":
    case "regurgitating_slog_yellow":
    case "regurgitating_slog_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:isSameName=sloggism_red");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBANISH,GY,-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "steelblade_supremacy_red":
    case "ironsong_determination_yellow":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "WEAPON");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose_target_weapon");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "teklo_plasma_pistol":
      $abilityType = GetResolvedAbilityType($cardID);
      if ($abilityType == "AA") {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_CharacterIndex);
        $character[$index + 2] = 0;
      }
      break;
    case "skullbone_crosswrap":
      if (ArsenalHasFaceDownCard($currentPlayer)) {
        SetArsenalFacing("UP", $currentPlayer);
      }
      break;
    case "tome_of_aetherwind_red":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 2 modes");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Buff_Arcane,Buff_Arcane,Draw_card,Draw_card-2");
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "art_of_war_yellow":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 2 modes");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Buff_your_attack_action_cards_this_turn,Your_next_attack_action_card_gains_go_again,Defend_with_attack_action_cards_from_arsenal,Banish_an_attack_action_card_to_draw_2_cards-2");
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "shiyana_diamond_gemini":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
        PayAdditionalCosts($otherCharacter[0], $from);
      }
      break;
    case "plasma_barrel_shot":
      $abilityType = GetResolvedAbilityType($cardID);
      if ($abilityType == "AA") {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_CharacterIndex);
        $character[$index + 2] = 0;
      }
      break;
    case "ser_boltyn_breaker_of_dawn":
    case "boltyn":
      BanishFromSoul($currentPlayer);
      break;
    case "beacon_of_victory_yellow":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "SOULINDICES");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many cards to banish from your soul");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
      AddDecisionQueue("WRITELASTRESULT", $currentPlayer, CardLink($cardID, $cardID)." was paid with an additional cost of ", 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "GETINDICES,", 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIBANISHSOUL", $currentPlayer, "-", 1);
      break;
    case "v_of_the_vanguard_yellow":
      AddDecisionQueue("VOFTHEVANGUARD", $currentPlayer, "-");
      break;
    case "bolt_of_courage_red":
    case "bolt_of_courage_yellow":
    case "bolt_of_courage_blue":
    case "cross_the_line_red":
    case "cross_the_line_yellow":
    case "cross_the_line_blue":
    case "engulfing_light_red":
    case "engulfing_light_yellow":
    case "engulfing_light_blue":
    case "express_lightning_red":
    case "express_lightning_yellow":
    case "express_lightning_blue":
    case "take_flight_red":
    case "take_flight_yellow":
    case "take_flight_blue":
      Charge();
      break;
    case "celestial_cataclysm_yellow":
      BanishFromSoul($currentPlayer);
      BanishFromSoul($currentPlayer);
      BanishFromSoul($currentPlayer);
      break;
    case "just_a_nick_red":
      if (LinkBasePower() <= 1 && CardType($CombatChain->AttackCard()->ID()) == "AA" && HasStealth($combatChain[0])) $modalities = "Buff_Power,Gain_On-Hit,Both";
      elseif (LinkBasePower() <= 1 && CardType($CombatChain->AttackCard()->ID()) == "AA") $modalities = "Buff_Power";
      else $modalities = "Gain_On-Hit";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $modalities);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "endless_maw_red":
    case "endless_maw_yellow":
    case "endless_maw_blue":
    case "writhing_beast_hulk_red":
    case "writhing_beast_hulk_yellow":
    case "writhing_beast_hulk_blue":
    case "convulsions_from_the_bellows_of_hell_red":
    case "convulsions_from_the_bellows_of_hell_yellow":
    case "convulsions_from_the_bellows_of_hell_blue":
    case "dread_screamer_red":
    case "dread_screamer_yellow":
    case "dread_screamer_blue":
      if (RandomBanish3GY($cardID) > 0) AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "boneyard_marauder_red":
    case "boneyard_marauder_yellow":
    case "boneyard_marauder_blue":
    case "hungering_slaughterbeast_red":
    case "hungering_slaughterbeast_yellow":
    case "hungering_slaughterbeast_blue":
    case "unworldly_bellow_red":
    case "unworldly_bellow_yellow":
    case "unworldly_bellow_blue":
      RandomBanish3GY($cardID);
      break;
    case "shadow_of_ursur_blue":
      MZMoveCard($currentPlayer, "MYHAND:bloodDebtOnly=true", "MYBANISH,HAND,-", may: true);
      AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "maul_yellow":
      if (LinkBasePower() <= 1 && CardNameContains($combatChain[0], "Crouching Tiger", $currentPlayer)) $modalities = "Buff_Power,Gain_On-Hit,Both";
      elseif (LinkBasePower() <= 1 && CardType($combatChain[0]) == "AA") $modalities = "Buff_Power";
      else $modalities = "Gain_On-Hit";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $modalities);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "consuming_aftermath_red":
    case "consuming_aftermath_yellow":
    case "consuming_aftermath_blue":
      MZMoveCard($currentPlayer, "MYHAND", "MYBANISH,HAND,-", may: true);
      AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "SHADOW", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "soul_harvest_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "GY");
      AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "6-", 1);
      AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1, 1);
      AddDecisionQueue("VALIDATECOUNT", $currentPlayer, "6", 1);
      AddDecisionQueue("SOULHARVEST", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,-", 1);
      break;
    case "rouse_the_ancients_blue":
      if (CanRevealCards($currentPlayer)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHANDAA");
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which cards to reveal", 1);
        AddDecisionQueue("MAYMULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ROUSETHEANCIENTS", $currentPlayer, "-", 1);
      }
      break;
    case "seek_horizon_red":
    case "seek_horizon_yellow":
    case "seek_horizon_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which cards to put on top of your deck (or pass)", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "MYTOPDECK", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "captains_call_red":
    case "captains_call_yellow":
    case "captains_call_blue":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Buff_Power,Go_Again");
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "belittle_red":
    case "belittle_yellow":
    case "belittle_blue":
      if (CanRevealCards($currentPlayer)) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to reveal for Belittle");
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:type=AA;maxAttack=3");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GETCARDID", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "BELITTLE", 1);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      }
      break;
    case "rally_the_rearguard_red":
    case "rally_the_rearguard_yellow":
    case "rally_the_rearguard_blue":
    case "rally_the_coast_guard_red":
    case "rally_the_coast_guard_yellow":
    case "rally_the_coast_guard_blue":
      if ($from == "PLAY" || $from == "COMBATCHAINATTACKS") {
        $hand = &GetHand($currentPlayer);
        if (count($hand) == 0) {
          WriteLog("This ability requires a discard as an additional cost, but you have no cards to discard. Reverting gamestate prior to the card declaration.", highlight: true);
          RevertGamestate();
        }
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        AddCurrentTurnEffect($cardID, $currentPlayer, "CC", $combatChain[$index + 7]);
        if ($from == "PLAY") ++$combatChain[$index + 11];
        else ++$chainLinks[$index][9];
        MZMoveCard($currentPlayer, "MYHAND", "MYDISCARD", silent: true);
      }
      break;
    case "lexi_livewire":
    case "lexi":
      if (ArsenalHasFaceDownCard($currentPlayer)) {
        $cardFlipped = SetArsenalFacing("UP", $currentPlayer);
        AddAdditionalCost($currentPlayer, TalentOverride($cardFlipped, $currentPlayer));
        WriteLog(CardLink($cardID, $cardID) . " turns " . CardLink($cardFlipped, $cardFlipped) . " face up.");
      }
      break;
    case "crown_of_seeds":
      FaceDownArsenalBotDeck($currentPlayer);
      break;
    case "plume_of_evergrowth":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:type=I;talent=EARTH&MYDISCARD:type=A;talent=EARTH&MYDISCARD:type=AA;talent=EARTH");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose {{element|Earth|" . GetElementColorCode("EARTH") . "}} action card or {{element|Earth|" . GetElementColorCode("EARTH") . "}} instant card");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "tome_of_harvests_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
      AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
      break;
    case "deep_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
      AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
      break;
    case "twin_twisters_red":
    case "twin_twisters_yellow":
    case "twin_twisters_blue":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Hit_Effect,1_Attack");
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "blood_on_her_hands_yellow":
      $numCopper = CountItemByName("Copper", $currentPlayer);
      if ($numCopper == 0) {
        WriteLog("🥉 No copper.");
        return "";
      }
      if ($numCopper > 6) $numCopper = 6;
      $buttons = "";
      for ($i = 0; $i <= $numCopper; ++$i) {
        if ($buttons != "") $buttons .= ",";
        $buttons .= $i;
      }
      $numCopper > 1 ? $modes = "-Buff_Weapon,Buff_Weapon,Go_Again,Go_Again,Attack_Twice,Attack_Twice" : $modes = "-Buff_Weapon,Go_Again,Attack_Twice";
      $numCopper > 1 ? $text = "modes" : $text = "mode";

      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many " . CardLink("copper", "copper") . " to destroy");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $buttons);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "copper-", 1);
      AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-", 1);
      AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
      AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, $modes, 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose {0} " . $text);
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "cash_out_blue":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("FINDINDICES", $currentPlayer, "CASHOUT");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, 1, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CASHOUTCONTINUE", 1);
      break;
    case "knick_knack_bric_a_brac_red":
      $numCopper = CountItem("copper", $currentPlayer);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      if ($numCopper > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Copper to pay");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numCopper + 1));
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "copper-");
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("DIVIDE", $currentPlayer, "4");
        AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
      }
      $numSilver = CountItem("silver", $currentPlayer);
      if ($numSilver > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Silver to pay");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numSilver + 1));
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "silver-");
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("DIVIDE", $currentPlayer, "2");
        AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
      }
      $numGold = CountItem("gold", $currentPlayer);
      if ($numGold > 0) {
        $goldIndices = GetGoldIndices($currentPlayer);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Gold to pay");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numGold + 1));
        if (!str_contains($goldIndices, "MYCHAR")) {
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "gold-");
          AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
          AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
          AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
        }
        else {
          AddDecisionQueue("FINDANDDESTROYGOLD", $currentPlayer, "-", 1);
          AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
        }
      }
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{0}");
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
      break;
    case "burn_away_red":
      MZMoveCard($currentPlayer, "MYDISCARD:isSameName=phoenix_flame_red", "MYBANISH,GY,-", may: true);
      AddDecisionQueue("APPENDCLASSSTATE", $currentPlayer, $CS_AdditionalCosts . "-PHOENIXBANISH", 1);
      break;
    case "uzuri_switchblade":
    case "uzuri":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish with Uzuri", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "HAND,UZURI," . $currentPlayer . ",1", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      break;
    case "trench_of_sunken_treasure":
      FaceDownArsenalBotDeck($currentPlayer);
      break;
    case "flick_knives":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:subtype=Dagger&COMBATCHAINATTACKS:subtype=Dagger;type=AA");
      AddDecisionQueue("REMOVEINDICESIFACTIVECHAINLINK", $currentPlayer, "<-", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if(!ShouldAutotargetOpponent($currentPlayer)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARCANETARGET,0", 1); //Arcane Target isn't used for arcane only. Should be renamed to something else.
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for ".CardLink($cardID, $cardID), 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "{0},", 1);
      }
      else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "THEIRCHAR-0", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "{0},", 1);
      }
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "hurl_red":
    case "hurl_yellow":
    case "hurl_blue":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how much to pay for " . CardLink($cardID, $cardID));
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "0,1");
      AddDecisionQueue("PAYRESOURCES", $currentPlayer, "<-", 1);
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
      AddDecisionQueue("APPENDCLASSSTATE", $currentPlayer, $CS_AdditionalCosts . "-PAY1", 1);
      AddDecisionQueue("WRITELOG", $currentPlayer, "Paid extra resource to throw a dagger", 1);
      break;
    case "mask_of_malicious_manifestations":
      BottomDeckMultizone($currentPlayer, "MYHAND", "MYARS", true, "Choose a card from your hand or arsenal to add on the bottom of your deck");
      break;
    case "looking_for_a_scrap_red":
    case "looking_for_a_scrap_yellow":
    case "looking_for_a_scrap_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:maxAttack=1;minAttack=1");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer . ",1", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("APPENDCLASSSTATE", $currentPlayer, $CS_AdditionalCosts . "-BANISH1ATTACK", 1);
      break;
    case "beckoning_light_red":
    case "spirit_of_war_red":
    case "beaming_bravado_red":
    case "beaming_bravado_yellow":
    case "beaming_bravado_blue":
    case "glaring_impact_red":
    case "glaring_impact_yellow":
    case "glaring_impact_blue":
    case "light_the_way_red":
    case "light_the_way_yellow":
    case "light_the_way_blue":
      Charge();
      AddDecisionQueue("ALLCARDPITCHORPASS", $currentPlayer, "2", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "lumina_lance_yellow":
      $soul = &GetSoul($currentPlayer);
      if (!TalentContains($CombatChain->AttackCard()->ID(), "LIGHT", $currentPlayer)) break;
      $numModes = count($soul) / SoulPieces() < 3 ? count($soul) / SoulPieces() : 3;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose up to $numModes modes");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "$numModes-+2_Attack,Draw_on_hit,Go_again_on_hit");
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "LUMINALANCECOST", 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "expendable_limbs_blue":
      $banish = &GetBanish($currentPlayer);
      $index = count($banish) - BanishPieces();
      if (ModifiedPowerValue($banish[$index], $currentPlayer, "BANISH") >= 6) $banish[$index + 1] = "NT";
      break;
    case "blood_dripping_frenzy_blue":
      $banishedCards = BanishHand($currentPlayer);
      SetClassState($currentPlayer, $CS_AdditionalCosts, $banishedCards);
      break;
    case "scrap_trader_red":
      Scrap($currentPlayer);
      break;
    case "moonshot_yellow":
      global $CS_DynCostResolved;
      $xVal = GetClassState($currentPlayer, $CS_DynCostResolved) / 2;
      if (SearchCount(SearchMultizone($currentPlayer, "MYITEMS:isSameName=hyper_driver_red")) < $xVal) {
        WriteLog("You do not have enough Hyper Drivers. Reverting gamestate.", highlight: true);
        RevertGamestate();
        return;
      }
      for ($i = 0; $i < $xVal; ++$i) MZChooseAndDestroy($currentPlayer, "MYITEMS:isSameName=hyper_driver_red");
      break;
    case "twin_drive_red":
      $amountBoostChoices = "0,1,2";
      if (SearchCurrentTurnEffects("evo_speedslip_blue", $currentPlayer, true)) $amountBoostChoices = "0,1,2,3";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many times you want to activate boost on " . CardLink($cardID, $cardID));
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $amountBoostChoices);
      AddDecisionQueue("OP", $currentPlayer, "BOOST-" . $cardID, 1);
      break;
    case "fabricate_red":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 2;");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Equip_Proto_equipment,Evo_permanents_get_+1_block,Put_this_under_an_Evo_permanent,Banish_an_Evo_and_draw_a_card-2");
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "teklovossen_the_mechropotent":
    case "nitro_mechanoida":
      if ($from == "EQUIP") {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_CharacterIndex);
        CharacterChooseSubcard($currentPlayer, $index, count: $cardID == "teklovossen_the_mechropotent" ? 2 : 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "EQUIP,-", 1);
      }
      break;
    case "no_fear_red":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "no_fear_red");
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which cards to banish", 1);
      AddDecisionQueue("MAYMULTICHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "NOFEAR", 1);
      break;
    case "the_golden_son_yellow":
      if (CountItemByName("Gold", $currentPlayer) > 0) {
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_the_additional_cost_of_1_" . CardLink("gold", "gold"), 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        $goldIndices = GetGoldIndices($currentPlayer);
        if (str_contains($goldIndices, "MYCHAR")) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, $goldIndices, 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZDESTROY", $currentPlayer, "-", 1);
        } else AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "gold-1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      }
      break;
    case "kassai_of_the_golden_sand":
    case "kassai":
      if ($from == "EQUIP") {
        MZMoveCard($currentPlayer, "MYDISCARD:pitch=1", "MYBANISH,GY,-");
        MZMoveCard($currentPlayer, "MYDISCARD:pitch=1", "MYBANISH,GY,-");
        MZMoveCard($currentPlayer, "MYDISCARD:pitch=2", "MYBANISH,GY,-");
        MZMoveCard($currentPlayer, "MYDISCARD:pitch=2", "MYBANISH,GY,-");
      }
      break;
    case "hood_of_red_sand":
      MZMoveCard($currentPlayer, "MYDISCARD:pitch=1", "MYBANISH,GY");
      MZMoveCard($currentPlayer, "MYDISCARD:pitch=2", "MYBANISH,GY");
      break;
    case "up_the_ante_blue":
      global $CS_LastDynCost;
      $dynCost = GetClassState($currentPlayer, $CS_LastDynCost);
      $max = $dynCost > 4 ? 4 : $dynCost + 1;
      $modalities = "Wager_Agility,Wager_Gold,Wager_Vigor,Buff_Attack";
      if ($max < 4) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $max == 1 ? "Choose 1 mode" : "Choose " . $max . " modes");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, $max . "-" . $modalities . "-" . $max);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      } else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      }
      break;
    case "raise_an_army_yellow":
      $numGold = CountItemByName("Gold", $currentPlayer);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Gold to pay");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numGold + 1));
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, 0, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "RAISEANARMY", 1);
      break;
    case "graven_call":
    case "graven_gaslight":
      if ($from == "GY") {
        //mark which specific graven call was activated
        $graveyard = GetDiscard($currentPlayer);
        $layerIndex = SearchLayersForPhase($cardID);
        $layers[$layerIndex+3] = $graveyard[$index + 1];
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "silver-2", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-", 1);
      }
      break;
    case "sacred_art_undercurrent_desires_blue":
      $modalities = "Create_a_Fang_Strike_and_Slither,Banish_up_to_2_cards_in_an_opposing_hero_graveyard,Transcend";
      $numModes = GetClassState($currentPlayer, $CS_NumBluePlayed) > 1 ? 3 : 1;
      if ($numModes == 1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 1 mode");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "$numModes-$modalities-$numModes");
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      } else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      }
      break;
    case "sacred_art_immortal_lunar_shrine_blue":
      $modalities = "Create_2_Spectral_Shield,Put_a_+1_counter_on_each_aura_with_ward_you_control,Transcend";
      $numModes = GetClassState($currentPlayer, $CS_NumBluePlayed) > 1 ? 3 : 1;
      if ($numModes == 1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 1 mode");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "$numModes-$modalities-$numModes");
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      } else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      }
      break;
    case "sacred_art_jade_tiger_domain_blue":
      $modalities = "Create_2_Crouching_Tigers,Crouching_Tigers_Get_+1_this_turn,Transcend";
      $numModes = GetClassState($currentPlayer, $CS_NumBluePlayed) > 1 ? 3 : 1;
      if ($numModes == 1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 1 mode");
        AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "$numModes-$modalities-$numModes");
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      } else {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
        AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      }
      break;
    case "longdraw_half_glove":
      $myHand = &GetHand($currentPlayer);
      $myArsenal = &GetArsenal($currentPlayer);
      if(count($myHand) + count($myArsenal) < 2) {
        WriteLog("No card in hand/arsenal to pay the cost of " . CardLink($cardID, $cardID) . ". Reverting the gamestate.", highlight:true);
        RevertGamestate();
      }
      MZMoveCard($currentPlayer, "MYHAND&MYARS", "MYBOTDECK", silent: true);
      MZMoveCard($currentPlayer, "MYHAND&MYARS", "MYBOTDECK", silent: true);
      break;
    case "visit_the_golden_anvil_blue":
      $numGold = CountItem("gold", $currentPlayer);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Gold to pay");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numGold + 1));
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, 0, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "GOLDENANVIL", 1);
      break;
    case "shadowrealm_horror_red":
      $uid = $layers[count($layers) - LayerPieces() + 6];
      $num6Banished = RandomBanish3GY($cardID, $cardID);
      if ($num6Banished > 0) AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      if ($num6Banished > 1) AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
      if ($num6Banished > 2) AddCurrentTurnEffect($cardID . "-3", $currentPlayer, uniqueID:$uid);
      break;
    case "saving_grace_yellow":
      Charge();
      break;
    case "hidden_agenda":
      if (ArsenalHasFaceDownCard($currentPlayer)) {
        SetArsenalFacing("UP", $currentPlayer);
      }
      break;
    case "oscilio_constella_intelligence":
    case "oscilio":
      if(SearchCount(SearchMultiZone($currentPlayer, "MYHAND:type=I")) == 0) {
        WriteLog("No instant card in hand pay the discard cost of " . CardLink($cardID, $cardID) . ". Reverting the gamestate.", highlight:true);
        RevertGamestate();
      }
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      MZMoveCard($currentPlayer, "MYHAND:type=I", "MYDISCARD," . $currentPlayer);
      break;
    case "seeds_of_tomorrow_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
      AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
      break;
    case "tarantula_toxin_red":
      if (SubtypeContains($combatChain[0], "Dagger") && HasStealth($combatChain[0]) && NumCardsBlocking() > 0) $modalities = "Buff_Power,Reduce_Block,Both";
      elseif (SubtypeContains($combatChain[0], "Dagger")) $modalities = "Buff_Power";
      elseif (HasStealth($combatChain[0]) && NumCardsBlocking() > 0) $modalities = "Reduce_Block";
      else {
        WriteLog("A previous chain link was targeted");
        break;
      }
      $numOptions = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "", "C");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $modalities);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      AddDecisionQueue("NOTEQUALPASS", $currentPlayer, "Buff_Power", 1);
      AddDecisionQueue("ELSE", $currentPlayer, "-");
      if ($numOptions != "") {
        $numOptions = explode(",", $numOptions);
        $options = [];
        foreach ($numOptions as $num) array_push($options, "COMBATCHAINLINK-$num");
        $options = implode(",", $options);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a defending card to shred", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $options, 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      }
      break;
    case "two_sides_to_the_blade_red":
      if (SubtypeContains($combatChain[0], "Dagger", $currentPlayer) && HasStealth($combatChain[0]) && TypeContains($combatChain[0], "AA", $currentPlayer)) $modalities = "Buff_Dagger,Buff_Stealth";
      elseif (SubtypeContains($combatChain[0], "Dagger", $currentPlayer)) $modalities = "Buff_Dagger";
      elseif (TypeContains($combatChain[0], "AA", $currentPlayer) && HasStealth($combatChain[0])) $modalities = "Buff_Stealth";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $modalities);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "long_whisker_loyalty_red":
      $modalities = (SubtypeContains($combatChain[0], "Dagger")) ? "Buff_Power,Additional_Attack,Mark" : "Additional_Attack,Mark";
      $numModes = min(count(explode(",", $modalities)), NumDraconicChainLinks());
      if ($numModes > 0) {
        if ($numModes < 3) {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, $numModes == 1 ? "Choose 1 mode" : "Choose " . $numModes . " modes");
          AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, $numModes . "-" . $modalities . "-" . $numModes);
          AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
          AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
        } else {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, $modalities);
          AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
          AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
        }
      }
      break;
    case "danger_digits":
    case "throw_dagger_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:subtype=Dagger&COMBATCHAINATTACKS:subtype=Dagger;type=AA");
      AddDecisionQueue("REMOVEINDICESIFACTIVECHAINLINK", $currentPlayer, "<-", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "war_cry_of_themis_yellow":
      if (GetResolvedAbilityType($cardID, $from) == "I")   
      {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SOULINDICES");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many cards to banish from your soul");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "GETINDICES,", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIBANISHSOUL", $currentPlayer, "-", 1);
      }
      break;
    case "war_cry_of_bellona_yellow":
      if (GetResolvedAbilityType($cardID, $from) == "I")   
      {
        $soul = GetSoul($currentPlayer);
        if (count($soul) > 0) {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "SOULINDICES");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many cards to banish from your soul");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
          AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "GETINDICES,", 1);
          AddDecisionQueue("FINDINDICES", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIBANISHSOUL", $currentPlayer, "-", 1);
        }
        else {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, 0);
          AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        }
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRCHAR:type=W&THEIRAURAS:type=W", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target weapon", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$cardID", 1);
        AddDecisionQueue("CONVERTLAYERTOABILITY", $currentPlayer, $cardID, 1);
      }  
      break;
    case "skyward_serenade_yellow":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 2;");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Create_an_Embodiment_of_Lightning,Search_for_Skyzyk,Buff_your_next_attack-2");
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      break;
    case "barbed_barrage_red":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to pay 3 to choose an additional attack target?");
      AddDecisionQueue("YESNO", $currentPlayer, "", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, 3, 1);
      AddDecisionQueue("PAYRESOURCES", $currentPlayer, "", 1);
      AddDecisionQueue("ADDITIONALATTACKTARGET", $currentPlayer, $cardID, 1);
      break;
    case "thespian_charm_yellow":
      $modes = "3-Destroy_a_Might_or_Vigor,Cheer,Bounce_an_aura";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of options");
      AddDecisionQueue("MAYMULTICHOOSETEXT", $currentPlayer, $modes);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      break;
    case "liars_charm_yellow":
      $modes = "3-Steal_a_Toughness_or_Vigor,Boo,Remove_hero_abilities";
      $targets = "MYCHAR-0,THEIRCHAR-0";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of options");
      AddDecisionQueue("MAYMULTICHOOSETEXT", $currentPlayer, $modes, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID, 1);
      AddDecisionQueue("MODENOTCHOSENPASS", $currentPlayer, "Remove_hero_abilities", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Target a hero to lose abilities", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $targets, 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "numbskull_charm_yellow":
      $modes = "3-Destroy_a_Confidence_or_Might,Cheer,Pitch_top_card";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of options");
      AddDecisionQueue("MAYMULTICHOOSETEXT", $currentPlayer, $modes);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      break;
    case "cheaters_charm_yellow":
      $modes = "3-Steal_a_Confidence_or_Toughness,Boo,Deal_2_damage";
      $targets = ShouldAutotargetOpponent($currentPlayer) ? "THEIRCHAR-0" : "MYCHAR-0,THEIRCHAR-0";
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of options");
      AddDecisionQueue("MAYMULTICHOOSETEXT", $currentPlayer, $modes);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts);
      AddDecisionQueue("SHOWMODES", $currentPlayer, $cardID);
      AddDecisionQueue("MODENOTCHOSENPASS", $currentPlayer, "Deal_2_damage", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Target a deal 2 damage 2", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $targets, 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, $cardID, 1);
      break;
    case "hyper_scrapper_blue":
      $items = SearchDiscard($currentPlayer, subtype: "Item");
      $resourcesPaid = GetClassState($currentPlayer, $CS_DynCostResolved);
      AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, $resourcesPaid . "-" . $items . "-" . $resourcesPaid, 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "HYPERSCRAPPER");
    default:
      break;
  }
}

function PlayCardEffect($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1)
{
  global $turn, $combatChain, $currentPlayer, $mainPlayer, $defPlayer, $combatChainState, $CCS_AttackPlayedFrom, $CS_PlayIndex;
  global $CS_CharacterIndex, $CS_PlayCCIndex;
  global $CCS_WeaponIndex, $EffectContext, $CCS_AttackFused, $CCS_AttackUniqueID, $CS_NumLess3PowAAPlayed, $layers;
  global $CS_NumDragonAttacks, $CS_NumAttackCardsAttacked, $CS_NumIllusionistAttacks, $CS_NumIllusionistActionCardAttacks;
  global $SET_PassDRStep, $CS_NumBlueDefended, $CS_AdditionalCosts, $CombatChain, $CS_NumTimesAttacked;
  global $currentTurnEffects, $CCS_GoesWhereAfterLinkResolves, $CCS_AttackTarget, $CCS_AttackTargetUID;
  global $landmarks;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $cardType = CardType($cardID);
  if (isset($layers[0]) && $layers[0] == "CLOSINGCHAIN") {
    WriteLog("You cannot play Non-Attack Actions with an open chain, closing the chain");
    ResetCombatChainState();
  }
  if ($additionalCosts == "-" || $additionalCosts == "") $additionalCosts = GetClassState($currentPlayer, $CS_AdditionalCosts);
  if ($layerIndex > -1) SetClassState($currentPlayer, $CS_PlayIndex, $layerIndex);
  $index = SearchForUniqueID($uniqueID, $currentPlayer);
  if ($cardID == "teklo_plasma_pistol" || $cardID == "plasma_barrel_shot") $index = FindCharacterIndex($currentPlayer, $cardID);
  if ($currentPlayer == $mainPlayer && CardClass($cardID) == "MECHANOLOGIST" && CardType($cardID) == "AA") {
    $index = FindCharacterIndex($currentPlayer, "teklovossen_the_mechropotent");
    if ($index != -1) {
      GiveAttackGoAgain();
      WriteLog(CardLink("teklovossen_the_mechropotent", "teklovossen_the_mechropotent") . " grants the attack go again.");
    }
  }
  if ($index > -1) SetClassState($currentPlayer, $CS_PlayIndex, $index);
  $definedCardType = CardType($cardID);
  $definedCardSubType = CardSubType($cardID);
  //Figure out where it goes
  $openedChain = false;
  $chainClosed = false;
  $skipDRResolution = false;
  $targetArr = explode(",", $combatChainState[$CCS_AttackTarget]);
  $uidArr = explode(",", $combatChainState[$CCS_AttackTargetUID]);
  if(GoesOnCombatChain($turn[0], $cardID, $from, $currentPlayer)) {
    for ($i = count($targetArr) - 1; $i >= 0; --$i) {
      if (explode("-", $targetArr[$i])[0] == "THEIRAURAS") {
        // remove spectra cards from target
        $ind = SearchAurasForUniqueID($uidArr[$i], $defPlayer);
        if ($ind == -1) {
          unset($targetArr[$i]);
          unset($uidArr[$i]);
          $targetArr = array_values($targetArr);
          $uidArr = array_values($uidArr);
        }
      }
    }
    $combatChainState[$CCS_AttackTarget] = count($targetArr) > 0 ? implode(",", $targetArr) : "NA";
    $combatChainState[$CCS_AttackTargetUID] = count($uidArr) > 0 ? implode(",", $uidArr) : "-";
  }
  $isBlock = ($turn[0] == "B" && count($layers) == 0); //This can change over the course of the function; for example if a phantasm gets popped
  if ($isBlock && $cardID == "nitro_mechanoidc") {
    $Items = new Items($currentPlayer);
    $Mechanoid = $Items->FindCard($cardID);
    $Mechanoid->ToggleOnChain(1);
  }
  if(canBeAddedToChainDuringDR($cardID) && $turn[0] == "D") $isBlock = true;
  if(GoesOnCombatChain($turn[0], $cardID, $from, $currentPlayer)) {
    if ($from == "PLAY" && $uniqueID != "-1" && $index == -1 && count($combatChain) == 0 && !DelimStringContains(CardSubType($cardID), "Item")) {
      WriteLog(CardLink($cardID, $cardID) . " does not resolve because it is no longer in play.");
      return;
    }
    if ((in_array("FINALIZECHAINLINK", $layers) || count($combatChain) == 0) && (DelimStringContains($definedCardType, "DR") || DelimStringContains($definedCardType, "AR"))) {
      WriteLog(CardLink($cardID, $cardID) . " does not resolve because the combat chain closed.");
      AddGraveyard($cardID, $currentPlayer, $from, $currentPlayer);
      ContinueDecisionQueue();
      return;
    }
    if ($definedCardType == "DR" && $from == "HAND" && CachedDominateActive() && CachedNumDefendedFromHand() >= 1 && NumDefendedFromHand() >= 1) {
      $discard = new Discard($currentPlayer);
      $discard->Add($cardID, "LAYER");
      WriteLog(CardLink($cardID, $cardID) . " fails to resolve because dominate is active and there is already a card defending from hand.");
      $skipDRResolution = true;
    }
    if ($definedCardType == "DR" && SearchCurrentTurnEffects("confidence", $mainPlayer) && NumNonBlocksDefending() >= 2 && IsCombatEffectActive("confidence")) {
      $discard = new Discard($currentPlayer);
      $discard->Add($cardID, "LAYER");
      WriteLog(CardLink($cardID, $cardID) . " fails to resolve because confidence is active and there are already 2 non-block card defending.");
      $skipDRResolution = true;
    }
    // dreacts that can only defend specific things
    switch ($cardID) {
      case "put_in_context_blue":
        if (LinkBasePower() > 3) {
          $discard = new Discard($currentPlayer);
          $discard->Add($cardID, "LAYER");
          $skipDRResolution = true;
        }
        break;
      default:
        break;
    }
    if (!$isBlock && CardType($cardID) == "AR") {
      $goesWhere = GoesWhereAfterResolving($cardID, $from, $currentPlayer, additionalCosts: $additionalCosts);
      ResolveGoesWhere($goesWhere, $cardID, $currentPlayer, $from);
      if ($target != "-") {
        $missingTarget = false;
        switch ($cardID) {
          case "just_a_nick_red":
            $targetUID = explode("-", $target)[1];
            if ($CombatChain->AttackCard()->UniqueID() != $targetUID) $missingTarget = true;
            break;
          default:
            break;
        }
      }
    }
    if ($resourcesPaid != "Skipped") {
      switch ($cardID) {
        case "flick_knives": //cards that go on the combat chain but need to keep track of targets
        case "danger_digits":
        case "throw_dagger_blue":
        case "shred_red":
        case "shred_yellow":
        case "shred_blue":
        case "tarantula_toxin_red":
        case "platinum_amulet_blue":
        case "rage_baiters":
        case "weeping_battleground_red":
        case "weeping_battleground_yellow":
        case "weeping_battleground_blue":
        case "display_of_craftsmanship_red":
        case "display_of_craftsmanship_yellow":
        case "display_of_craftsmanship_blue":
          break;
        default:
          $target = ($combatChainState[$CCS_AttackTarget] == "" || $combatChainState[$CCS_AttackTarget] == "NA") ? "MISSINGTARGET" : GetMZCards($currentPlayer, GetAttackTarget());
          break;
      }
    }
    if ($target == "MISSINGTARGET") { //if only spectra was targeted
      $goesWhere = GoesWhereAfterResolving($cardID, $from, $currentPlayer, additionalCosts: $additionalCosts);
      // if(CardType($cardID) != "T" && CardType($cardID) != "Macro" && $from != "PLAY") { //Don't need to add to anywhere if it's a token
        // ResolveGoesWhere($goesWhere, $cardID, $currentPlayer, "LAYER");
      // }
      // remove any buff associated with the played attack
      for ($i = count(value: $currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
        if (IsCombatEffectActive($currentTurnEffects[$i], $cardID) && !IsCombatEffectLimited($i) && !IsCombatEffectPersistent($currentTurnEffects[$i])) {
          RemoveCurrentTurnEffect($i);
        }
      }
    }
    if (!$skipDRResolution && $target != "" && $target != "MISSINGTARGET") {
      $index = AddCombatChain($cardID, $currentPlayer, $from, $resourcesPaid, $uniqueID);
      if ($index == 0) {//if adding an attacking card
        $count = count($currentTurnEffects);
        $pieces = CurrentTurnEffectPieces();
        for ($i = $count - $pieces; $i >= 0; $i -= $pieces) {
          if (IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
            if ($currentTurnEffects[$i] == "cheating_scoundrel_red")
              AddOnWagerEffects();
            elseif (IsLayerContinuousBuff($currentTurnEffects[$i]) && $currentTurnEffects[$i + 1] == $mainPlayer) {
              $CombatChain->AttackCard()->AddBuff(ConvertToSetID($currentTurnEffects[$i]));
              RemoveCurrentTurnEffect($i);
            }
          }
        }
      }
    }
    if ($index <= 0 && !$skipDRResolution) {
      ChangeSetting($defPlayer, $SET_PassDRStep, 0);
      $combatChainState[$CCS_AttackPlayedFrom] = $from;
      $chainClosed = $target == "MISSINGTARGET";
      if ($chainClosed) CloseCombatChain();
      $powerValue = (TypeContains( $cardID, "W", $currentPlayer)) ? GeneratedPowerValue($cardID) : PowerValue($cardID, $mainPlayer, "CC", $index);
      if (EffectAttackRestricted($cardID, $definedCardType, $from, true)) return;
      $combatChainState[$CCS_AttackUniqueID] = $uniqueID;
      if ($definedCardType == "AA" && $powerValue < 3) IncrementClassState($currentPlayer, $CS_NumLess3PowAAPlayed);
      if ($definedCardType == "AA" && (GetResolvedAbilityType($cardID) == "" || GetResolvedAbilityType($cardID) == "AA") && (SearchCharacterActive($currentPlayer, "kayo_berserker_runt") || (SearchCharacterActive($currentPlayer, "shiyana_diamond_gemini") && SearchCurrentTurnEffects("kayo_berserker_runt-SHIYANA", $currentPlayer))) && $powerValue >= 6) KayoStaticAbility($cardID);
      $openedChain = true;
      if ($definedCardType != "AA") $combatChainState[$CCS_WeaponIndex] = GetClassState($currentPlayer, $CS_PlayIndex);
      if ($additionalCosts != "-" && HasFusion($cardID)) $combatChainState[$CCS_AttackFused] = 1;
      //Add attack step layer prior to anything that triggers in the attack step
      $cardType = $definedCardType;
      if (GetResolvedAbilityType($cardID, $from) != "") $cardType = GetResolvedAbilityType($cardID, $from);
      if (!$chainClosed && $cardType == "AA") AddLayer("ATTACKSTEP", $mainPlayer, "-"); //I haven't added this for weapon. I don't think it's needed yet.
      // If you attacked an aura with Spectra
      if (!$chainClosed && (DelimStringContains($definedCardType, "AA") || DelimStringContains($definedCardType, "W") || DelimStringContains($definedCardSubType, "Ally") || DelimStringContains($definedCardSubType, "Aura"))) {
        ArsenalAttackAbilities();
        OnAttackEffects($cardID);
      }
      if (!$chainClosed && ($definedCardType == "AA" || GetResolvedAbilityType($cardID, $from) == "AA")) {
        IncrementClassState($currentPlayer, $CS_NumTimesAttacked);
        if (DelimStringContains(CardSubType($cardID), "Dragon")) IncrementClassState($currentPlayer, $CS_NumDragonAttacks);
        if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer)) IncrementClassState($currentPlayer, $CS_NumIllusionistAttacks);
        if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer) && $definedCardType == "AA") IncrementClassState($currentPlayer, $CS_NumIllusionistActionCardAttacks);
        AuraAttackAbilities($cardID);
        CharacterAttackAbilities($cardID);
      }
    } else { //On chain, but not index 0
      if ($definedCardType == "DR" && !$skipDRResolution) {
        OnDefenseReactionResolveEffects($from, $cardID);
        if (ColorContains($cardID, 3, $defPlayer)) IncrementClassState($defPlayer, $CS_NumBlueDefended);
      }
    }
    switch ($cardID) { //cards that add themselves as blocking
      case "quickdodge_flexors":
        if ($turn[0] != "B") {
          OnBlockEffects($index, "EQUIP");
          OnBlockResolveEffects($cardID);
        }
        break;
      default:
        break;
    }
    SetClassState($currentPlayer, $CS_PlayCCIndex, $index);
  } else if ($from != "PLAY" && $from != "EQUIP" && $from != "COMBATCHAINATTACKS") {
    $cardSubtype = CardSubType($cardID);
    if (DelimStringContains($cardSubtype, "Aura")) PlayAura($cardID, $currentPlayer, from: $from, additionalCosts: $additionalCosts);
    else if (DelimStringContains($cardSubtype, "Ally")) PlayAlly($cardID, $currentPlayer, from: $from);
    else if (DelimStringContains($cardSubtype, "Item")) PutItemIntoPlayForPlayer($cardID, $currentPlayer, from: $from);
    else if ($cardSubtype == "Landmark") PlayLandmark($cardID, $currentPlayer, $from);
    else if (DelimStringContains($cardSubtype, "Figment")) PutPermanentIntoPlay($currentPlayer, $cardID, from: $from);
    else if (DelimStringContains($cardSubtype, "Evo")) EvoHandling($cardID, $currentPlayer, $from);
    else if ($definedCardType != "C" && $definedCardType != "E" && $definedCardType != "W" && $definedCardType != "Macro") {
      $goesWhere = GoesWhereAfterResolving($cardID, $from, $currentPlayer, additionalCosts: $additionalCosts);
      ResolveGoesWhere($goesWhere, $cardID, $currentPlayer, $from);
    }
  }
  //Resolve Effects
  if (!$isBlock && !$skipDRResolution) {
    CurrentEffectPlayOrActivateAbility($cardID, $from);
    if ($from != "PLAY") {
      ArsenalPlayCardAbilities($cardID);
      CharacterPlayCardAbilities($cardID, $from);
    }
    if (!$chainClosed || $definedCardType == "AA") {
      if ($from == "PLAY" && DelimStringContains(CardSubType($cardID), "Ally")) AllyAttackAbilities($cardID);
      if ($from == "PLAY" && DelimStringContains(CardSubType($cardID), "Ally")) SpecificAllyAttackAbilities($cardID);
      if ($definedCardType == "AA" || GetResolvedAbilityType($cardID, $from) == "AA"){
        $treasureID = SearchLandmarksForID("treasure_island");
        if (IsHeroAttackTarget() && $treasureID != -1 && SearchCurrentTurnEffects("treasure_island", $mainPlayer, true)) {
          WriteLog("More gold discovered on ".CardLink("treasure_island", "treasure_island")."!");
          $landmarks[$treasureID + 3]++;
        }
      }
    }
    $EffectContext = $cardID;
    $playText = "";
    if (!$chainClosed) {
      if (IsModular($cardID)) $additionalCosts = $uniqueID; //to track which one to remove
      $playText = PlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      if ($definedCardType == "AA" && (GetResolvedAbilityType($cardID, $from) == "AA" || GetResolvedAbilityType($cardID, $from) == "")) IncrementClassState($currentPlayer, $CS_NumAttackCardsAttacked); //Played or blocked
    }
    CurrentEffectAfterPlayOrActivateAbility();
    if ($from != "EQUIP" && $from != "PLAY" && $from != "COMBATCHAINATTACKS") WriteLog("Resolving play ability of " . CardLink($cardID, $cardID) . ($playText != "" ? ": " : ".") . $playText);
    else if ($from == "EQUIP" || $from == "PLAY" || $from == "COMBATCHAINATTACKS") WriteLog("Resolving activated ability of " . CardLink($cardID, $cardID) . ($playText != "" ? ": " : ".") . $playText);
    if (!$openedChain) {
      ResolveGoAgain($cardID, $currentPlayer, $from, additionalCosts: $additionalCosts, uniqueID:$uniqueID);
    }
    CopyCurrentTurnEffectsFromAfterResolveEffects();
    CacheCombatResult();
    if (!$isBlock) ProcessAllMirage();
    if ($target == "MISSINGTARGET") CleanUpCombatEffects(isSpectraTarget: true);
  }
  if ($CS_CharacterIndex != -1 && CanPlayAsInstant($cardID)) RemoveCharacterEffects($currentPlayer, GetClassState($currentPlayer, $CS_CharacterIndex), "INSTANT");
  //Now determine what needs to happen next
  SetClassState($currentPlayer, $CS_PlayIndex, -1);
  SetClassState($currentPlayer, $CS_CharacterIndex, -1);
  ProcessDecisionQueue();
}

function ProcessAttackTarget($spectraTargets)
{
  global $defPlayer;
  foreach ($spectraTargets as $specTarg) {
    DestroyAuraUniqueID($defPlayer, $specTarg);
  }
  if (GetAttackTarget() == "") { //no attack targets left
    CloseCombatChain();
    return true;
  }
  return false;
}

function WriteGamestate()
{
  global $gameName, $playerHealths;
  global $p1Hand, $p1Deck, $p1CharEquip, $p1Resources, $p1Arsenal, $p1Items, $p1Auras, $p1Discard, $p1Pitch, $p1Banish;
  global $p1ClassState, $p1CharacterEffects, $p1Soul, $p1CardStats, $p1TurnStats, $p1Allies, $p1Permanents, $p1Settings;
  global $p2Hand, $p2Deck, $p2CharEquip, $p2Resources, $p2Arsenal, $p2Items, $p2Auras, $p2Discard, $p2Pitch, $p2Banish;
  global $p2ClassState, $p2CharacterEffects, $p2Soul, $p2CardStats, $p2TurnStats, $p2Allies, $p2Permanents, $p2Settings;
  global $landmarks, $winner, $firstPlayer, $currentPlayer, $currentTurn, $turn, $actionPoints, $combatChain, $combatChainState;
  global $currentTurnEffects, $currentTurnEffectsFromCombat, $nextTurnEffects, $decisionQueue, $dqVars, $dqState;
  global $layers, $layerPriority, $mainPlayer, $lastPlayed, $chainLinks, $chainLinkSummary, $p1Key, $p2Key;
  global $permanentUniqueIDCounter, $inGameStatus, $animations, $currentPlayerActivity;
  global $p1TotalTime, $p2TotalTime, $lastUpdateTime;
  
  $filename = "./Games/" . $gameName . "/gamestate.txt";
  $handler = fopen($filename, "w");

  $lockTries = 0;
  while (!flock($handler, LOCK_EX) && $lockTries < 10) {
    usleep(100000); //100ms
    ++$lockTries;
  }

  if ($lockTries == 10) {
    fclose($handler);
    exit;
  }

  // Build entire output string before writing (reduces I/O operations)
  $output = [];

  $output[] = implode(" ", $playerHealths);

  //Player 1
  $output[] = implode(" ", $p1Hand);
  $output[] = implode(" ", $p1Deck);
  $output[] = implode(" ", $p1CharEquip);
  $output[] = implode(" ", $p1Resources);
  $output[] = implode(" ", $p1Arsenal);
  $output[] = implode(" ", $p1Items);
  $output[] = implode(" ", $p1Auras);
  $output[] = implode(" ", $p1Discard);
  $output[] = implode(" ", $p1Pitch);
  $output[] = implode(" ", $p1Banish);
  $output[] = implode(" ", $p1ClassState);
  $output[] = implode(" ", $p1CharacterEffects);
  $output[] = implode(" ", $p1Soul);
  $output[] = implode(" ", $p1CardStats);
  $output[] = implode(" ", $p1TurnStats);
  $output[] = implode(" ", $p1Allies);
  $output[] = implode(" ", $p1Permanents);
  $output[] = implode(" ", $p1Settings);

  //Player 2
  $output[] = implode(" ", $p2Hand);
  $output[] = implode(" ", $p2Deck);
  $output[] = implode(" ", $p2CharEquip);
  $output[] = implode(" ", $p2Resources);
  $output[] = implode(" ", $p2Arsenal);
  $output[] = implode(" ", $p2Items);
  $output[] = implode(" ", $p2Auras);
  $output[] = implode(" ", $p2Discard);
  $output[] = implode(" ", $p2Pitch);
  $output[] = implode(" ", $p2Banish);
  $output[] = implode(" ", $p2ClassState);
  $output[] = implode(" ", $p2CharacterEffects);
  $output[] = implode(" ", $p2Soul);
  $output[] = implode(" ", $p2CardStats);
  $output[] = implode(" ", $p2TurnStats);
  $output[] = implode(" ", $p2Allies);
  $output[] = implode(" ", $p2Permanents);
  $output[] = implode(" ", $p2Settings);

  //Game State
  $output[] = implode(" ", $landmarks);
  $output[] = $winner;
  $output[] = $firstPlayer;
  $output[] = $currentPlayer;
  $output[] = $currentTurn;
  $output[] = implode(" ", $turn);
  $output[] = $actionPoints;
  $output[] = implode(" ", $combatChain);
  $output[] = implode(" ", $combatChainState);
  $output[] = implode(" ", $currentTurnEffects);
  $output[] = implode(" ", $currentTurnEffectsFromCombat);
  $output[] = implode(" ", $nextTurnEffects);
  $output[] = implode(" ", $decisionQueue);
  $output[] = implode(" ", $dqVars);
  $output[] = implode(" ", $dqState);
  $output[] = implode(" ", $layers);
  $output[] = implode(" ", $layerPriority);
  $output[] = $mainPlayer;
  $output[] = implode(" ", $lastPlayed);
  $output[] = count($chainLinks);
  for ($i = 0; $i < count($chainLinks); ++$i) {
    $output[] = implode(" ", $chainLinks[$i]);
  }
  $output[] = implode(" ", $chainLinkSummary);
  $output[] = $p1Key;
  $output[] = $p2Key;
  $output[] = $permanentUniqueIDCounter;
  $output[] = $inGameStatus; //Game status
  $output[] = implode(" ", $animations); //Animations
  $output[] = $currentPlayerActivity; //Current Player activity status
  $output[] = ""; //Unused
  $output[] = ""; //Unused
  $output[] = $p1TotalTime; //Player 1 total time
  $output[] = $p2TotalTime; //Player 2 total time
  $output[] = $lastUpdateTime; //Last update time
  
  // Single write operation with all data at once
  fwrite($handler, implode("\r\n", $output) . "\r\n");
  fclose($handler);
}

function AddEvent($type, $value)
{
  global $events;
  $events[] = $type;
  $events[] = $value;
}

function ReportBug()
{
  global $gameName;
  $bugCount = 0;
  $folderName = "./BugReports/" . $gameName . "-" . $bugCount;
  while ($bugCount < 5 && file_exists($folderName)) {
    ++$bugCount;
    $folderName = "./BugReports/" . $gameName . "-" . $bugCount;
  }
  if ($bugCount == 3) {
    WriteLog("⚠️ Bug report file is full for this game. Please use Discord to report further bugs.", highlight: true);
  }
  mkdir($folderName, 0700, true);
  
  // Copy main game files
  $filesToCopy = [
    "gamestate.txt",
    "gamestateBackup.txt",
    "gamelog.txt",
    "beginTurnGamestate.txt",
    "lastTurnGamestate.txt"
  ];
  
  foreach ($filesToCopy as $file) {
    $sourcePath = "./Games/$gameName/$file";
    if (file_exists($sourcePath)) {
      copy($sourcePath, "$folderName/$file");
    }
  }
  
  // Copy numbered backup files
  for ($i = 0; $i <= 4; $i++) {
    $sourcePath = "./Games/$gameName/gamestateBackup_{$i}.txt";
    if (file_exists($sourcePath)) {
      copy($sourcePath, "$folderName/gamestateBackup_{$i}.txt");
    }
  }
  
  WriteLog("🐛 Thank you for reporting a bug. Please report it on Discord with the game number as reference ($gameName).");
}

function EndResolutionStep()
{
  $layerIndex = 0;
  $resolutionIndex = SearchLayersForPhase("RESOLUTIONSTEP");
  if ($resolutionIndex != -1) {
    NegateLayer("LAYER-$resolutionIndex", "-");
    $layerIndex += LayerPieces();
  }
  $resolutionIndex = SearchLayersForPhase("CLOSINGCHAIN");
  if ($resolutionIndex != -1) {
    NegateLayer("LAYER-$resolutionIndex", "-");
    $layerIndex += LayerPieces();
  }
  UnsetChainLinkBanish();
  return $layerIndex;
}