<?php

require_once "Types/Mode.php";

function ProcessInput($playerID, int $mode, $buttonInput, $cardID, $chkCount, $chkInput): void
{
  global $gameName, $currentPlayer, $mainPlayer, $turn, $CS_CharacterIndex, $CS_PlayIndex, $decisionQueue, $CS_NextNAAInstant, $skipWriteGamestate, $combatChain, $landmarks;
  global $SET_PassDRStep, $actionPoints, $currentPlayerActivity, $p1PlayerRating, $p2PlayerRating, $redirectPath;
  switch ($mode) {
    case Mode::$PlayCardFromHand:
      $found = HasCard($cardID);
      if ($found >= 0 && IsPlayable($cardID, $turn[0], "HAND", $found)) {
        //Player actually has the card, now do the effect
        //First remove it from their hand
        $hand = &GetHand($playerID);
        unset($hand[$found]);
        $hand = array_values($hand);
        PlayCard($cardID, "HAND");
      }
      break;
    case Mode::$PlayEquipmentAbility:
      $index = $cardID;
      $found = -1;
      $character = &GetPlayerCharacter($playerID);
      if ($index != "") {
        $cardID = $character[$index];
        $found = HasCard($cardID);
      }
      if ($index != -1 && IsPlayable($character[$found], $turn[0], "CHAR", $index)) {
        SetClassState($playerID, $CS_CharacterIndex, $index);
        SetClassState($playerID, $CS_PlayIndex, $index);
        $character = &GetPlayerCharacter($playerID);
        if ($turn[0] == "B") {
          if ($cardID == "MON187") {
            $character[$index + 1] = 0;
            BanishCardForPlayer($cardID, $currentPlayer, "EQUIP", "NA");
          } else $character[$index + 6] = 1; //Else just put it on the combat chain
        } else {
          EquipPayAdditionalCosts($index, "EQUIP");
        }
        PlayCard($cardID, "EQUIP", -1, $index);
      }
      break;
    case Mode::$AddToArsenal:
      $found = HasCard($cardID);
      if ($turn[0] == "ARS" && $found >= 0) {
        $hand = &GetHand($playerID);
        unset($hand[$found]);
        $hand = array_values($hand);
        AddArsenal($cardID, $currentPlayer, "HAND", "DOWN");
        PassTurn();
      }
      break;
    case Mode::$PlayFromArsenal:
      $index = $cardID;
      $arsenal = &GetArsenal($playerID);
      if ($index < count($arsenal)) {
        $cardToPlay = $arsenal[$index];
        if (!IsPlayable($cardToPlay, $turn[0], "ARS", $index)) break; //Card not playable
        $uniqueID = $arsenal[$index + 5];
        RemoveArsenal($playerID, $index);
        PlayCard($cardToPlay, "ARS", -1, -1, $uniqueID);
      }
      break;
    case Mode::$PitchToDeck:
      if ($turn[0] != "PDECK") break;
      $found = PitchHasCard($cardID);
      if ($found >= 0) {
        PitchDeck($currentPlayer, $found);
        PassTurn(); //Resume passing the turn
      }
      break;
    case Mode::$InputNumber:
      if ($turn[0] == "DYNPITCH") {
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case Mode::$ChooseTop:
    case Mode::$ChooseBottom:
      if ($turn[0] == "OPT" || $turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM") {
        $options = explode(",", $turn[2]);
        $found = -1;
        for ($i = 0; $i < count($options); ++$i) {
          if ($options[$i] == $buttonInput) {
            $found = $i;
            break;
          }
        }
        if ($found == -1) break; //Invalid input
        $deck = &GetDeck($playerID);
        if ($mode == Mode::$ChooseTop) {
          array_unshift($deck, $buttonInput);
          WriteLog("Player " . $playerID . " put a card on top of the deck.");
        } elseif ($mode == Mode::$ChooseBottom) {
          array_push($deck, $buttonInput);
          WriteLog("Player " . $playerID . " put a card on the bottom of the deck.");
        }
        unset($options[$found]);
        $options = array_values($options);
        if (count($options) > 0) PrependDecisionQueue($turn[0], $currentPlayer, implode(",", $options));
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case Mode::$PlayItemAbility:
      $index = $cardID; //Overridden to be index instead
      $items = &GetItems($playerID);
      if ($index >= count($items)) break; //Item doesn't exist
      $cardID = $items[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Item not playable
      --$items[$index + 3];
      SetClassState($playerID, $CS_PlayIndex, $index);
      $set = CardSet($cardID);
      PlayCard($cardID, "PLAY", -1, $index, $items[$index + 4]);
      break;
    case Mode::$ChooseCardFromDeck:
      if ($turn[0] == "CHOOSEDECK" || $turn[0] == "MAYCHOOSEDECK") {
        $deck = &GetDeck($playerID);
        $index = $cardID;
        $cardID = $deck[$index];
        unset($deck[$index]);
        $deck = array_values($deck);
        ContinueDecisionQueue($cardID);
      }
      break;
    case Mode::$HandToTop:
      if ($turn[0] == "HANDTOPBOTTOM") {
        $hand = &GetHand($playerID);
        $deck = &GetDeck($playerID);
        $cardID = $hand[$buttonInput];
        array_unshift($deck, $cardID);
        unset($hand[$buttonInput]);
        $hand = array_values($hand);
        ContinueDecisionQueue($cardID);
        WriteLog("Player " . $playerID . " put a card on the top of the deck.");
      }
      break;
    case Mode::$HandToBottom:
      if ($turn[0] == "HANDTOPBOTTOM") {
        $hand = &GetHand($playerID);
        $deck = &GetDeck($playerID);
        $cardID = $hand[$buttonInput];
        array_push($deck, $cardID);
        unset($hand[$buttonInput]);
        $hand = array_values($hand);
        ContinueDecisionQueue($cardID);
        WriteLog("Player " . $playerID . " put a card on the bottom of the deck.");
      }
      break;
    case Mode::$Banish:
      $index = $cardID;
      $banish = &GetBanish($playerID);
      $theirCharacter = &GetPlayerCharacter($playerID == 1 ? 2 : 1);
      $cardID = $banish[$index];
      if ($banish[$index + 1] == "INST") SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
      if ($banish[$index + 1] == "MON212" && TalentContains($theirCharacter[0], "LIGHT", $currentPlayer)) AddCurrentTurnEffect("MON212", $currentPlayer);
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      PlayCard($cardID, "BANISH", -1, $index, $banish[$index + 2]);
      break;
    case Mode::$Deprecated_15:
    case Mode::$Deprecated_18:
    case Mode::$Choose:
      if (count($decisionQueue) > 0) //TODO: Or check all the possibilities?
      {
        $index = $cardID;
        ContinueDecisionQueue($index);
      }
      break;
    case Mode::$ButtonInput:
      if (($turn[0] == "BUTTONINPUT" || $turn[0] == "CHOOSEARCANE" || $turn[0] == "BUTTONINPUTNOPASS" || $turn[0] == "CHOOSEFIRSTPLAYER")) {
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case Mode::$MultiChoose:
      if (!str_starts_with($turn[0], "MULTICHOOSE") && !str_starts_with($turn[0], "MAYMULTICHOOSE")) break;
      $params = explode("-", $turn[2]);
      $maxSelect = intval($params[0]);
      $options = explode(",", $params[1]);
      if(count($params) > 2) $minSelect = intval($params[2]);
      else $minSelect = -1;
      if (count($chkInput) > $maxSelect) {
        WriteLog("You selected " . count($chkInput) . " items, but a maximum of " . $maxSelect . " was allowed. Reverting gamestate prior to that effect.");
        RevertGamestate();
        $skipWriteGamestate = true;
        break;
      }
      if ($minSelect != -1 && count($chkInput) < $minSelect && count($chkInput) < count($options)) {
        WriteLog("You selected " . count($chkInput) . " items, but a minimum of " . $minSelect . " was allowed. Reverting gamestate prior to that effect.");
        RevertGamestate();
        $skipWriteGamestate = true;
        break;
      }
      for ($i = 0; $i < count($chkInput); ++$i) {
        $found = 0;
        for ($j = 0; $j < count($options); ++$j) {
          if ($chkInput[$i] == $options[$j]) {
            $found = 1;
            break;
          }
        }
        if (!$found) {
          WriteLog("You selected option " . $chkInput[$i] . " but that was not one of the original options. Reverting gamestate prior to that effect.");
          RevertGamestate();
          $skipWriteGamestate = true;
          break;
        }
      }
      if (!$skipWriteGamestate) {
        ContinueDecisionQueue($chkInput);
      }
      break;
    case Mode::$YesNo:
      if ($turn[0] == "YESNO" && ($buttonInput == "YES" || $buttonInput == "NO")) ContinueDecisionQueue($buttonInput);
      break;
    case Mode::$PlayCombatChainAbility:
      $index = $cardID; //Overridden to be index instead
      $cardID = $combatChain[$index];
      if (AbilityPlayableFromCombatChain($cardID) && IsPlayable($cardID, $turn[0], "PLAY", $index)) {
        SetClassState($playerID, $CS_PlayIndex, $index);
        PlayCard($cardID, "PLAY", -1);
      }
      break;
    case Mode::$PlayAuraAbility:
      $index = $cardID; //Overridden to be index instead
      $auras = &GetAuras($playerID);
      if ($index >= count($auras)) break; //Item doesn't exist
      $cardID = $auras[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Aura ability not playable
      $auras[$index + 1] = 1; //Set status to used - for now
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1, $index, $auras[$index+6]);
      break;
    case Mode::$ChooseCard:
      if ($turn[0] == "CHOOSECARD") {
        $options = explode(",", $turn[2]);
        $found = -1;
        for ($i = 0; $i < count($options); ++$i) {
          if ($options[$i] == $buttonInput) {
            $found = $i;
            break;
          }
        }
        if ($found == -1) break; //Invalid input
        unset($options[$found]);
        $options = array_values($options);
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case Mode::$PlayAllyAbility:
      $allies = &GetAllies($currentPlayer);
      $index = $cardID; //Overridden to be indexed instead
      if ($index >= count($allies)) break; //Ally doesn't exist
      $cardID = $allies[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Ally not playable
      $allies[$index + 1] = 1;
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1, $index, $allies[$index+5]);
      break;
    case Mode::$PlayLandmarkAbility:
      $index = $cardID;
      if ($index >= count($landmarks)) break; //Landmark doesn't exist
      $cardID = $landmarks[$index];
      if (!IsPlayable($cardID, $turn[0], "PLAY", $index)) break; //Landmark not playable
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1);
      break;
    case Mode::$ChainSetting:
      include "MenuFiles/ParseGamefile.php";
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $params = explode("-", $buttonInput);
      if($playerID == 1) $userID = $p1id;
      else $userID = $p2id;
      ChangeSetting($playerID, $params[0], $params[1], $userID);
      break;
    case Mode::$PlayFromHandByIndex:
      $found = $cardID;
      if ($found >= 0) {
        //Player actually has the card, now do the effect
        //First remove it from their hand
        $hand = &GetHand($playerID);
        if($found >= count($hand)) break;
        $cardID = $hand[$found];
        if(!IsPlayable($cardID, $turn[0], "HAND", $found)) break;
        unset($hand[$found]);
        $hand = array_values($hand);
        PlayCard($cardID, "HAND");
      }
      break;
    case Mode::$Pass:
      if (CanPassPhase($turn[0])) {
        PassInput(false);
      }
      break;
    case Mode::$BreakCombatChain:
      if($currentPlayer == $mainPlayer) {
        ResetCombatChainState();
        ProcessDecisionQueue();
      }
      break;
    case Mode::$PassBlocksAndReactions:
      ChangeSetting($playerID, $SET_PassDRStep, 1);
      if (CanPassPhase($turn[0])) {
        PassInput(false);
      }
      break;
    case Mode::$ToggleEquipment:
      $index = $buttonInput;
      $char = &GetPlayerCharacter($playerID);
      $char[$index + 9] = ($char[$index + 9] == "1" ? "0" : "1");
      break;
    case Mode::$TogglePermanent:
      $input = explode("-", $buttonInput);
      $index = (int) $input[1];
      switch($input[0])
      {
        case "ITEMS":
          $zone = &GetItems($playerID);
          $offset = 5;
          break;
        case "AURAS":
        default:
          $zone = &GetAuras($playerID);
          $offset = 7;
          break;
      }
      $zone[$index + $offset] = ($zone[$index + $offset] == "1" ? "0" : "1");
      break;
    case Mode::$ToggleOpponentPermanent:
      $input = explode("-", $buttonInput);
      $index = (int) $input[1];
      switch($input[0])
      {
        case "ITEMS":
          $zone = &GetItems($playerID == 1 ? 2 : 1);
          $offset = 6;
          break;
        case "AURAS":
        default:
          $zone = &GetAuras($playerID == 1 ? 2 : 1);
          $offset = 8;
          break;
      }
      $zone[$index + $offset] = ($zone[$index + $offset] == "1" ? "0" : "1");
      break;
    case Mode::$Undo:
      RevertGamestate();
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " undid their last action.");
      break;
    case Mode::$CancelBlock:
      RevertGamestate("preBlockBackup.txt");
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " cancel their blocks.");
      break;
    case Mode::$ManualAddActionPoint:
      WriteLog("Player " . $playerID . " manually add 1 action point.");
      ++$actionPoints;
      break;
    case Mode::$RevertToPriorTurn:
      RevertGamestate($buttonInput);
      WriteLog("Player " . $playerID . " revert back to a prior turn.");
      break;
    case Mode::$ManualSubActionPoint:
      if ($actionPoints > 0) {
        WriteLog("Player " . $playerID . " manually subtract 1 action point.");
        --$actionPoints;
      }
      break;
    case Mode::$ManualSubHealthPoint:
      WriteLog("Player " . $playerID . " manually subtract 1 health point from themselves.");
      LoseHealth(1, $playerID);
      break;
    case Mode::$ManualAddHealthPoint:
      WriteLog("Player " . $playerID . " manually add 1 health point to themselves.");
      $health = &GetHealth($playerID);
      $health += 1;
      break;
    case Mode::$ManualSubOpponentHealthPoint:
      WriteLog("Player " . $playerID . " manually substract 1 health point their opponent.");
      LoseHealth(1, ($playerID == 1 ? 2 : 1));
      break;
    case Mode::$ManualAddOpponentHealthPoint:
      WriteLog("Player " . $playerID . " manually add 1 health point their opponent.");
      $health = &GetHealth($playerID == 1 ? 2 : 1);
      $health += 1;
      break;
    case Mode::$ManualDrawCard:
      WriteLog("Player " . $playerID . " manually draw a card for themselves.");
      Draw($playerID);
      break;
    case Mode::$ManualOpponentDrawCard:
      WriteLog("Player " . $playerID . " manually draw a card for their opponent.");
      Draw(($playerID == 1 ? 2 : 1));
      break;
    case Mode::$ManualAddCard:
      WriteLog("Player " . $playerID . " manually add a card to their hand.");
      $hand = &GetHand($playerID);
      array_push($hand, $cardID);
      break;
    case Mode::$ManualAddResource:
      WriteLog("Player " . $playerID . " manually add a resource to their pool.");
      $resources = &GetResources($playerID);
      $resources[0] += 1;
      break;
    case Mode::$ManualOpponentAddResource:
      WriteLog("Player " . $playerID . " manually add a resource to their opponent's pool.");
      $resources = &GetResources($playerID == 1 ? 2 : 1);
      $resources[0] += 1;
      break;
    case Mode::$ManualOpponentSubResource:
      WriteLog("Player " . $playerID . " manually removed a resource from their opponent's pool.");
      $resources = &GetResources($playerID == 1 ? 2 : 1);
      $resources[0] -= 1;
      break;
    case Mode::$ManualSubResource:
      WriteLog("Player " . $playerID . " manually removed a resource from their pool.");
      $resources = &GetResources($playerID);
      $resources[0] -= 1;
      break;
    case Mode::$QuickRematch:
      if($turn[0] != "OVER") break;
      $otherPlayer = ($playerID == 1 ? 2 : 1);
      $char = &GetPlayerCharacter($otherPlayer);
      if ($char[0] != "DUMMY") {
        AddDecisionQueue("YESNO", $otherPlayer, "if you want a Quick Rematch?");
        AddDecisionQueue("NOPASS", $otherPlayer, "-", 1);
        AddDecisionQueue("QUICKREMATCH", $otherPlayer, "-", 1);
        AddDecisionQueue("OVER", $playerID, "-");
      } else {
        AddDecisionQueue("QUICKREMATCH", $otherPlayer, "-", 1);
      }
      ProcessDecisionQueue();
      break;
    case Mode::$MainMenu:
      header("Location: " . $redirectPath . "/MainMenu.php");
      exit;
    case Mode::$Concede:
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $conceded = true;
      if(!IsGameOver()) PlayerLoseHealth($playerID, GetHealth($playerID));
      break;
    case Mode::$ReportBug:
      $bugCount = 0;
      $folderName = "./BugReports/" . $gameName . "-" . $bugCount;
      while ($bugCount < 5 && file_exists($folderName)) {
        ++$bugCount;
        $folderName = "./BugReports/" . $gameName . "-" . $bugCount;
      }
      if ($bugCount == 5) {
        WriteLog("Bug report file is temporarily full for this game. Please use the discord to report further bugs.");
      }
      mkdir($folderName, 0700, true);
      copy("./Games/$gameName/gamestate.txt", $folderName . "/gamestate.txt");
      copy("./Games/$gameName/gamestateBackup.txt", $folderName . "/gamestateBackup.txt");
      copy("./Games/$gameName/gamelog.txt", $folderName . "/gamelog.txt");
      WriteLog("Thank you for reporting a bug. To describe what happened, please report it on the discord server with the game number for reference ($gameName).");
      break;
    case Mode::$Rematch:
      if($turn[0] != "OVER") break;
      $otherPlayer = ($playerID == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if you want a Rematch?");
      AddDecisionQueue("REMATCH", $otherPlayer, "-", 1);
      ProcessDecisionQueue();
      break;
    case Mode::$CurrentPlayerInactive:
      $char = &GetPlayerCharacter($playerID == 1 ? 2 : 1);
      if ($char[0] != "DUMMY") {
        $currentPlayerActivity = 2;
        WriteLog("The current player is inactive.");
      }
      break;
    case Mode::$CurrentPlayerActive:
      $char = &GetPlayerCharacter($playerID == 1 ? 2 : 1);
      if ($char[0] != "DUMMY") {
        $currentPlayerActivity = 0;
        WriteLog("The current player is active again.");
      }
      break;
    case Mode::$ClaimVictory:
      if($currentPlayerActivity == 2)
      {
        include_once "./includes/dbh.inc.php";
        include_once "./includes/functions.inc.php";
        $otherPlayer = ($playerID == 1 ? 2 : 1);
        if(!IsGameOver()) PlayerLoseHealth($otherPlayer, GetHealth($otherPlayer));
        WriteLog("The opponent forfeit due to inactivity.");
      }
      break;
    case Mode::$ThumpUp:
      if($playerID == 1 && $p1PlayerRating != 0) break;
      if($playerID == 2 && $p2PlayerRating != 0) break;
      include "MenuFiles/ParseGamefile.php";
      AddRating(($playerID == 1 ? 2 : 1), "green");
      if ($playerID == 1) $p1PlayerRating = 1;
      if ($playerID == 2) $p2PlayerRating = 1;
      break;
    case Mode::$ThumpDown:
      if($playerID == 1 && $p1PlayerRating != 0) break;
      if($playerID == 2 && $p2PlayerRating != 0) break;
      include "MenuFiles/ParseGamefile.php";
      AddRating(($playerID == 1 ? 2 : 1), "red");
      if ($playerID == 1) $p1PlayerRating = 2;
      if ($playerID == 2) $p2PlayerRating = 2;
      break;
    case Mode::$GrantBadge:
      include "MenuFiles/ParseGamefile.php";
      include_once "./includes/dbh.inc.php";
      include_once "./includes/functions.inc.php";
      $myName = ($playerID == 1 ? $p1uid : $p2uid);
      $theirName = ($playerID == 1 ? $p2uid : $p1uid);
      if($playerID == 1) $userID = $p1id;
      else $userID = $p2id;
      if($userID != "")
      {
        AwardBadge($userID, 3);
        WriteLog($myName . " gave a badge to " . $theirName);
      }
      break;

    case Mode::$None:
    case Mode::$Deprecated_1:
    default:
      break;
  }
}
