<?php

include "CardDictionary.php";
include "CoreLogic.php";

function PummelHit($player = -1)
{
  global $defPlayer;
  if ($player == -1) $player = $defPlayer;
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard", 1);
  AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
  AddDecisionQueue("ADDDISCARD", $player, "HAND", 1);
}

function HandToTopDeck($player)
{
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
  AddDecisionQueue("MULTIADDTOPDECK", $player, "-", 1);
}

function RandomHandBottomDeck($player)
{
  $hand = &GetHand($player);
  if (count($hand) == 0) return;
  $index = rand() % count($hand);
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  $deck = &GetDeck($player);
  array_push($deck, $discarded);
}

function BottomDeck()
{
  global $currentPlayer;
  $hand = GetHand($currentPlayer);
  if (count($hand) > 0) {
    AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Put_a_card_from_your_hand_on_the_bottom_of_your_deck.");
    AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
    AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("ADDBOTTOMMYDECK", $currentPlayer, "-", 1);
  }
}

function MayBottomDeck()
{
  global $currentPlayer;
  $hand = GetHand($currentPlayer);
  if (count($hand) > 0) {
    AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You_may_put_a_card_from_your_hand_on_the_bottom_of_your_deck.");
    AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
    AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("ADDBOTTOMMYDECK", $currentPlayer, "-", 1);
  }
}

function MayBottomDeckDraw()
{
  global $currentPlayer;
  $hand = GetHand($currentPlayer);
  if (count($hand) > 0) {
    MayBottomDeck();
    AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
  }
}

function BottomDeckMultizone($player, $zone1, $zone2)
{
  AddDecisionQueue("FINDINDICES", $player, "SEARCHMZ," . $zone1 . "|" . $zone2, 1);
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to sink (or click the Pass button)", 1);
  AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
  AddDecisionQueue("MULTIZONEREMOVE", $player, "-", 1);
  AddDecisionQueue("ADDBOTTOMMYDECK", $player, "-", 1);
}

function BottomDeckMultizoneDraw($player, $zone1, $zone2)
{
  BottomDeckMultizone($player, $zone1, $zone2);
  AddDecisionQueue("DRAW", $player, "-", 1);
}

function AddCurrentTurnEffect($cardID, $player, $from = "", $uniqueID = -1)
{
  global $currentTurnEffects, $combatChain;
  $card = explode("-", $cardID)[0];
  if (CardType($card) == "A" && count($combatChain) > 0 && !IsCombatEffectPersistent($cardID) && $from != "PLAY") {
    AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
    return;
  }
  array_push($currentTurnEffects, $cardID);
  array_push($currentTurnEffects, $player);
  array_push($currentTurnEffects, $uniqueID);
  array_push($currentTurnEffects, CurrentTurnEffectUses($cardID));
}

function AddAfterResolveEffect($cardID, $player, $from = "", $uniqueID = -1)
{
  global $afterResolveEffects, $combatChain;
  $card = explode("-", $cardID)[0];
  if (CardType($card) == "A" && count($combatChain) > 0 && !IsCombatEffectPersistent($cardID) && $from != "PLAY") {
    AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
    return;
  }
  array_push($afterResolveEffects, $cardID);
  array_push($afterResolveEffects, $player);
  array_push($afterResolveEffects, $uniqueID);
  array_push($afterResolveEffects, CurrentTurnEffectUses($cardID));
}

function CopyCurrentTurnEffectsFromAfterResolveEffects()
{
  global $currentTurnEffects, $afterResolveEffects;
  for ($i = 0; $i < count($afterResolveEffects); $i += CurrentTurnEffectPieces()) {
    array_push($currentTurnEffects, $afterResolveEffects[$i]);
    array_push($currentTurnEffects, $afterResolveEffects[$i + 1]);
    array_push($currentTurnEffects, $afterResolveEffects[$i + 2]);
    array_push($currentTurnEffects, $afterResolveEffects[$i + 3]);
  }
  $afterResolveEffects = [];
}

//This is needed because if you add a current turn effect from combat, it could get deleted as part of the combat resolution
function AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID = -1)
{
  global $currentTurnEffectsFromCombat;
  array_push($currentTurnEffectsFromCombat, $cardID);
  array_push($currentTurnEffectsFromCombat, $player);
  array_push($currentTurnEffectsFromCombat, $uniqueID);
  array_push($currentTurnEffectsFromCombat, CurrentTurnEffectUses($cardID));
}

function CopyCurrentTurnEffectsFromCombat()
{
  global $currentTurnEffects, $currentTurnEffectsFromCombat;
  for ($i = 0; $i < count($currentTurnEffectsFromCombat); $i += CurrentTurnEffectPieces()) {
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i + 1]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i + 2]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i + 3]);
  }
  $currentTurnEffectsFromCombat = [];
}

function RemoveCurrentTurnEffect($index)
{
  global $currentTurnEffects;
  unset($currentTurnEffects[$index + 3]);
  unset($currentTurnEffects[$index + 2]);
  unset($currentTurnEffects[$index + 1]);
  unset($currentTurnEffects[$index]);
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentTurnEffectPieces()
{
  return 4;
}

function CurrentTurnEffectUses($cardID)
{
  switch ($cardID) {
    case "UPR000":
      return 3;
    case "UPR088":
      return 4;
    case "UPR221": return 4;
    case "UPR222": return 3;
    case "UPR223": return 2;
    default:
      return 1;
  }
}

function AddNextTurnEffect($cardID, $player, $uniqueID = -1)
{
  global $nextTurnEffects;
  array_push($nextTurnEffects, $cardID);
  array_push($nextTurnEffects, $player);
  array_push($nextTurnEffects, $uniqueID);
  array_push($nextTurnEffects, CurrentTurnEffectUses($cardID));
}

function IsCombatEffectLimited($index)
{
  global $currentTurnEffects, $combatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $CCS_AttackUniqueID;
  if (count($combatChain) == 0 || $currentTurnEffects[$index + 2] == -1) return false;
  $attackSubType = CardSubType($combatChain[0]);
  if (DelimStringContains($attackSubType, "Ally")) {
    $allies = &GetAllies($mainPlayer);
    if (count($allies) < $combatChainState[$CCS_WeaponIndex] + 5) return false;
    if ($allies[$combatChainState[$CCS_WeaponIndex] + 5] != $currentTurnEffects[$index + 2]) return true;
  } else {
    return $combatChainState[$CCS_AttackUniqueID] != $currentTurnEffects[$index + 2];
  }
  return false;
}

function HasEffect($cardID)
{
  global $currentTurnEffects;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i] == $cardID) return true;
  }
  return false;
}

function AddLayer($cardID, $player, $parameter, $target = "-", $additionalCosts = "-", $uniqueID = "-")
{
  global $layers;
  //Layers are on a stack, so you need to push things on in reverse order
  array_unshift($layers, $uniqueID);
  array_unshift($layers, $additionalCosts);
  array_unshift($layers, $target);
  array_unshift($layers, $parameter);
  array_unshift($layers, $player);
  array_unshift($layers, $cardID);
}

function AddDecisionQueue($phase, $player, $parameter, $subsequent = 0, $makeCheckpoint = 0)
{
  global $decisionQueue;
  if (count($decisionQueue) == 0) $insertIndex = 0;
  else {
    $insertIndex = count($decisionQueue) - DecisionQueuePieces();
    if (!IsGamePhase($decisionQueue[$insertIndex])) //Stack must be clear before you can continue with the step
    {
      $insertIndex = count($decisionQueue);
    }
  }

  $parameter = str_replace(" ", "_", $parameter);
  array_splice($decisionQueue, $insertIndex, 0, $phase);
  array_splice($decisionQueue, $insertIndex + 1, 0, $player);
  array_splice($decisionQueue, $insertIndex + 2, 0, $parameter);
  array_splice($decisionQueue, $insertIndex + 3, 0, $subsequent);
  array_splice($decisionQueue, $insertIndex + 4, 0, $makeCheckpoint);
}

function PrependDecisionQueue($phase, $player, $parameter, $subsequent = 0, $makeCheckpoint = 0)
{
  global $decisionQueue;
  $parameter = str_replace(" ", "_", $parameter);
  array_unshift($decisionQueue, $makeCheckpoint);
  array_unshift($decisionQueue, $subsequent);
  array_unshift($decisionQueue, $parameter);
  array_unshift($decisionQueue, $player);
  array_unshift($decisionQueue, $phase);
}

function ProcessDecisionQueue()
{
  global $turn, $decisionQueue, $dqState;
  if ($dqState[0] != "1") {
    $count = count($turn);
    if (count($turn) < 3) $turn[2] = "-";
    $dqState[0] = "1"; //If the decision queue is currently active/processing
    $dqState[1] = $turn[0];
    $dqState[2] = $turn[1];
    $dqState[3] = $turn[2];
    $dqState[4] = "-"; //DQ helptext initial value
    $dqState[5] = "-"; //Decision queue multizone indices
    $dqState[6] = "0"; //Damage dealt
    $dqState[7] = "0"; //Target
    //array_unshift($turn, "-", "-", "-");
  }
  ContinueDecisionQueue("");
}

function CloseDecisionQueue()
{
  global $turn, $decisionQueue, $dqState, $combatChain, $currentPlayer, $mainPlayer;
  $dqState[0] = "0";
  $turn[0] = $dqState[1];
  $turn[1] = $dqState[2];
  $turn[2] = $dqState[3];
  $dqState[4] = "-"; //Clear the context, just in case
  $dqState[5] = "-"; //Clear Decision queue multizone indices
  $dqState[6] = "0"; //Damage dealt
  $dqState[7] = "0"; //Target
  $decisionQueue = [];
  if (($turn[0] == "D" || $turn[0] == "A") && count($combatChain) == 0) {
    $currentPlayer = $mainPlayer;
    $turn[0] = "M";
  }
}

function ShouldHoldPriorityNow($player)
{
  global $layerPriority, $layers;
  if ($layerPriority[$player - 1] != "1") return false;
  $currentLayer = $layers[count($layers) - LayerPieces()];
  $layerType = CardType($currentLayer);
  if (HoldPrioritySetting($player) == 3 && $layerType != "AA" && $layerType != "W") return false;
  return true;
}

function IsGamePhase($phase)
{
  switch ($phase) {
    case "RESUMEPAYING":
    case "RESUMEPLAY":
    case "RESOLVECHAINLINK":
    case "RESOLVECOMBATDAMAGE":
    case "PASSTURN":
      return true;
    default:
      return false;
  }
}

//Must be called with the my/their context
function ContinueDecisionQueue($lastResult = "")
{
  global $decisionQueue, $turn, $currentPlayer, $mainPlayerGamestateStillBuilt, $makeCheckpoint, $otherPlayer, $CS_LayerTarget;
  global $layers, $layerPriority, $dqVars, $dqState, $CS_AbilityIndex, $CS_AdditionalCosts, $lastPlayed, $CS_LastDynCost;
  if (count($decisionQueue) == 0 || IsGamePhase($decisionQueue[0])) {
    if ($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
    else if (count($decisionQueue) > 0 && $currentPlayer != $decisionQueue[1]) {
      UpdateGameState($currentPlayer);
    }
    if (count($decisionQueue) == 0 && count($layers) > 0) {
      $priorityHeld = 0;
      if ($currentPlayer == 1) {
        if (ShouldHoldPriorityNow(1)) {
          AddDecisionQueue("INSTANT", 1, "-");
          $priorityHeld = 1;
          $layerPriority[0] = 0;
        }
        if (ShouldHoldPriorityNow(2)) {
          AddDecisionQueue("INSTANT", 2, "-");
          $priorityHeld = 1;
          $layerPriority[1] = 0;
        }
      } else {
        if (ShouldHoldPriorityNow(2)) {
          AddDecisionQueue("INSTANT", 2, "-");
          $priorityHeld = 1;
          $layerPriority[1] = 0;
        }
        if (ShouldHoldPriorityNow(1)) {
          AddDecisionQueue("INSTANT", 1, "-");
          $priorityHeld = 1;
          $layerPriority[0] = 0;
        }
      }
      if ($priorityHeld) {
        ContinueDecisionQueue("");
      } else {
        if (RequiresDieRoll($layers[0], explode("|", $layers[2])[0], $layers[1])) {
          RollDie($layers[1]);
          ContinueDecisionQueue("");
          return;
        }
        CloseDecisionQueue();
        $cardID = array_shift($layers);
        $player = array_shift($layers);
        $parameter = array_shift($layers);
        $target = array_shift($layers);
        $additionalCosts = array_shift($layers);
        $uniqueID = array_shift($layers);
        $params = explode("|", $parameter);
        if ($currentPlayer != $player) {
          if ($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
          else UpdateGameState($currentPlayer);
          $currentPlayer = $player;
          $otherPlayer = $currentPlayer == 1 ? 2 : 1;
          BuildMyGamestate($currentPlayer);
        }
        $layerPriority[0] = ShouldHoldPriority(1);
        $layerPriority[1] = ShouldHoldPriority(2);
        if ($cardID == "ENDTURN") FinishTurnPass();
        else if ($cardID == "RESUMETURN") $turn[0] = "M";
        else if ($cardID == "LAYER") ProcessLayer($player, $parameter);
        else if ($cardID == "FINALIZECHAINLINK") FinalizeChainLink($parameter);
        else if ($cardID == "TRIGGER") {
          ProcessTrigger($player, $parameter, $uniqueID);
          ProcessDecisionQueue();
        } else {
          SetClassState($player, $CS_AbilityIndex, $params[2]); //This is like a parameter to PlayCardEffect and other functions
          PlayCardEffect($cardID, $params[0], $params[1], $target, $additionalCosts, $params[3]);
          ClearDieRoll($player);
        }
      }
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPLAY") {
      if ($currentPlayer != $decisionQueue[1]) {
        $currentPlayer = $decisionQueue[1];
        $otherPlayer = $currentPlayer == 1 ? 2 : 1;
        BuildMyGamestate($currentPlayer);
      }
      $params = explode("|", $decisionQueue[2]);
      CloseDecisionQueue();
      if ($turn[0] == "B" && count($layers) == 0) //If a layer is not created
      {
        PlayCardEffect($params[0], $params[1], $params[2], "-", $params[3], $params[4]);
      } else {
        //params 3 = ability index
        //params 4 = Unique ID
        $layerTarget = GetClassState($currentPlayer, $CS_LayerTarget);
        $additionalCosts = GetClassState($currentPlayer, $CS_AdditionalCosts);
        if ($layerTarget == "") $layerTarget = "-";
        if ($additionalCosts == "") $additionalCosts = "-";
        AddLayer($params[0], $currentPlayer, $params[1] . "|" . $params[2] . "|" . $params[3] . "|" . $params[4], $layerTarget, $additionalCosts);
        ProcessDecisionQueue();
        return;
      }
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPAYING") {
      $player = $decisionQueue[1];
      $params = explode("-", $decisionQueue[2]); //Parameter
      if ($lastResult == "") $lastResult = 0;
      CloseDecisionQueue();
      if ($currentPlayer != $player) {
        $currentPlayer = $player;
        $otherPlayer = $currentPlayer == 1 ? 2 : 1;
        BuildMyGamestate($currentPlayer);
      }
      SetClassState($currentPlayer, $CS_LastDynCost, $lastResult);
      PlayCard($params[0], $params[1], $lastResult, $params[2]);
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECHAINLINK") {
      CloseDecisionQueue();
      ResolveChainLink();
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECOMBATDAMAGE") {
      $parameter = $decisionQueue[2];
      if ($parameter != "-") $damageDone = $parameter;
      else $damageDone = $dqState[6];
      CloseDecisionQueue();
      ResolveCombatDamage($damageDone);
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "PASSTURN") {
      CloseDecisionQueue();
      PassTurn();
    } else {
      CloseDecisionQueue();
      FinalizeAction();
    }
    return;
  }
  $phase = array_shift($decisionQueue);
  $player = array_shift($decisionQueue);
  $parameter = array_shift($decisionQueue);
  //WriteLog($phase . " " . $player . " " . $parameter . " " . $lastResult);//Uncomment this to visualize decision queue execution
  $parameter = str_replace("{I}", $dqState[5], $parameter);
  if (count($dqVars) > 0) {
    if (str_contains($parameter, "{0}")) $parameter = str_replace("{0}", $dqVars[0], $parameter);
    if (str_contains($parameter, "<0>")) $parameter = str_replace("<0>", CardLink($dqVars[0], $dqVars[0]), $parameter);
  }
  if (count($dqVars) > 1) $parameter = str_replace("<1>", CardLink($dqVars[1], $dqVars[1]), $parameter);
  $subsequent = array_shift($decisionQueue);
  $makeCheckpoint = array_shift($decisionQueue);
  $turn[0] = $phase;
  $turn[1] = $player;
  $currentPlayer = $player;
  $turn[2] = ($parameter == "<-" ? $lastResult : $parameter);
  $return = "PASS";
  if ($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter == "<-" ? $lastResult : $parameter), $lastResult);
  if ($parameter == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS"; //Collapse the rest of the queue if this decision point has invalid parameters
  if (is_array($return) || strval($return) != "NOTSTATIC") {
    if ($phase != "SETDQCONTEXT") $dqState[4] = "-"; //Clear out context for static states -- context only persists for one choice
    ContinueDecisionQueue($return);
  } else {
    if ($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
  }
}

function ProcessLayer($player, $parameter)
{
  switch ($parameter) {
    case "PHANTASM":
      PhantasmLayer();
      break;
    default:
      break;
  }
}

function ProcessTrigger($player, $parameter, $uniqueID)
{
  global $combatChain;

  $resources = &GetResources($player);
  $items = &GetItems($player);
  $character = &GetPlayerCharacter($player);
  $auras = &GetAuras($player);

  if(CardType($parameter) == "C")
  {
    $otherPlayer = ($player == 1 ? 2 : 1);
    $otherCharacter = &GetPlayerCharacter($otherPlayer);
    if(SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $player)) {
      $parameter = $otherCharacter[0];
    }
  }

  switch ($parameter) {
    case "WTR001": case "WTR002": case "RVD001":
      WriteLog(CardLink($parameter, $parameter) . " Intimidates.");
      Intimidate();
      break;
    case "WTR047":
      Draw($player);
      WriteLog(CardLink($parameter, $parameter) . " Show Time! drew a card.");
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR054": case "WTR055": case "WTR056":
      if($parameter == "WTR054") $amount = 3;
      else if($parameter == "WTR055") $amount = 2;
      else $amount = 1;
      BlessingOfDeliveranceDestroy($amount);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR075":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR117":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Refraction_Bolters_to_give_your_attack_Go_Again");
      AddDecisionQueue("REFRACTIONBOLTERS", $player, $index, 1);
      break;
    case "ARC007":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      --$items[$index + 1];
      $resources[0] += 2;
      if ($items[$index + 1] <= 0) DestroyMainItem($index);
      WriteLog(CardLink($parameter, $parameter) . " produced 2 resources.");
      break;
    case "ARC035":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      WriteLog(CardLink($parameter, $parameter) . " lost a steam counter and remain in play.");
      --$items[$index + 1];
      if ($items[$index + 1] <= 0) DestroyMainItem($index);
      break;
    case "ARC112":
      DealArcane(1, 1, "RUNECHANT", "ARC112", player:$player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ARC152":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Vest_of_the_First_Fist_to_gain_2_resources");
      AddDecisionQueue("VESTOFTHEFIRSTFIST", $player, $index, 1);
      break;
    case "ARC162":
      DestroyAuraUniqueID($player, $uniqueID);
      WriteLog(CardLink($parameter, $parameter) . " was destroyed at the beginning of your action phase.");
      break;
    case "CRU000":
      PlayAura("ARC112", $player);
      break;
    case "CRU053":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Breeze_Rider_Boots_to_give_your_Combo_attacks_Go_Again");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $player, $character[$index], 1);
      break;
    case "CRU075":
      $index = FindCharacterIndex($player, $parameter);
      if ($auras[$index + 2] == 0) {
        DestroyAuraUniqueID($player, $uniqueID);
      } else {
        --$auras[$index + 2];
      }
      break;
    case "MON122":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("CHARREADYORPASS", $player, $index);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Hooves_of_the_Shadowbeast_to_gain_an_action_point", 1);
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1); //Operates off last result
      AddDecisionQueue("GAINACTIONPOINTS", $player, 1, 1);
      break;
    case "ELE109":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ELE111":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ELE174":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("YESNO", $player, "destroy_mark_of_lightning_to_have_the_attack_deal_1_damage");
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("DEALDAMAGE", $otherPlayer, 1 . "-" . $combatChain[0] . "-" . "COMBAT", 1);
      break;
    case "EVR018":
      PlayAura("ELE111", $player);
      break;
    case "EVR037":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Mask_of_the_Pouncing_Lynx_to_tutor_a_card");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("FINDINDICES", $player, "MASKPOUNCINGLYNX", 1);
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("MULTIBANISH", $player, "DECK,TT", 1);
      AddDecisionQueue("SHOWBANISHEDCARD", $player, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
      break;
    case "EVR069":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      WriteLog(CardLink($parameter, $parameter) . " lost a steam counter and remain in play.");
      --$items[$index + 1];
      if ($items[$index + 1] < 0) DestroyMainItem($index);
      break;
    case "EVR071":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      WriteLog(CardLink($parameter, $parameter) . " lost a steam counter and remain in play.");
      --$items[$index + 1];
      if ($items[$index + 1] < 0) DestroyMainItem($index);
      break;
    case "EVR107":
    case "EVR108":
    case "EVR109":
      $index = SearchAurasForUniqueID($uniqueID, $player);
      if ($index == -1) break;
      $auras = &GetAuras($player);
      if ($auras[$index + 2] == 0) {
        WriteLog("Runeblood Invocation is destroyed.");
        DestroyAura($player, $index);
      } else {
        --$auras[$index + 2];
        PlayAura("ARC112", $player);
      }
      break;
    default:
      break;
  }
}

function GetDQHelpText()
{
  global $dqState;
  if (count($dqState) < 5) return "-";
  return $dqState[4];
}

function FinalizeAction()
{
  global $currentPlayer, $mainPlayer, $actionPoints, $turn, $combatChain, $defPlayer, $makeBlockBackup, $mainPlayerGamestateStillBuilt;
  if (!$mainPlayerGamestateStillBuilt) UpdateGameState(1);
  BuildMainPlayerGamestate();
  if ($turn[0] == "M") {
    if (count($combatChain) > 0) //Means we initiated a chain link
    {
      $turn[0] = "B";
      $currentPlayer = $defPlayer;
      $turn[2] = "";
      $makeBlockBackup = 1;
    } else {
      if ($actionPoints > 0 || ShouldHoldPriority($mainPlayer)) {
        $turn[0] = "M";
        $currentPlayer = $mainPlayer;
        $turn[2] = "";
      } else {
        $currentPlayer = $mainPlayer;
        BeginTurnPass();
      }
    }
  } else if ($turn[0] == "A") {
    $turn[0] = "D";
    $currentPlayer = $defPlayer;
    $turn[2] = "";
  } else if ($turn[0] == "D") {
    $turn[0] = "A";
    $currentPlayer = $mainPlayer;
    $turn[2] = "";
  } else if ($turn[0] == "B") {
    $turn[0] = "B";
  }
  return 0;
}

function IsReactionPhase()
{
  global $turn, $dqState;
  if ($turn[0] == "A" || $turn[0] == "D") return true;
  if (count($dqState) >= 2 && ($dqState[1] == "A" || $dqState[1] == "D")) return true;
  return false;
}

//Return whether priority should be held for the player by default/settings
function ShouldHoldPriority($player, $layerCard = "")
{
  global $mainPlayer;
  $prioritySetting = HoldPrioritySetting($player);
  if ($prioritySetting == 0 || $prioritySetting == 1) return 1;
  if (($prioritySetting == 2 || $prioritySetting == 3) && $player != $mainPlayer) return 1;
  return 0;
}

function GiveAttackGoAgain()
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
}

function DefenderTopDeckToArsenal()
{
  global $defPlayer;
  TopDeckToArsenal($defPlayer);
}

function MainTopDeckToArsenal()
{
  global $mainPlayer;
  TopDeckToArsenal($mainPlayer);
}

function TopDeckToArsenal($player)
{
  $deck = &GetDeck($player);
  if (!ArsenalEmpty($player) || count($deck) == 0) return; //Already something there
  AddArsenal(array_shift($deck), $player, "DECK", "DOWN");
  WriteLog("The top card of player " . $player . "'s deck was put in their arsenal.");
}

function DefenderArsenalToBottomOfDeck()
{
  global $defPlayer;
  ArsenalToBottomDeck($defPlayer);
}

function ArsenalToBottomDeck($player)
{
  //TODO: Allow to choose arsenal slot
  $arsenal = &GetArsenal($player);
  if (count($arsenal) == 0) return;
  $index = 0;
  AddBottomDeck($arsenal[$index], $player, "ARS");
  for ($i = $index + ArsenalPieces() - 1; $i >= $index; --$i) {
    unset($arsenal[$i]);
  }
  $arsenal = array_values($arsenal);
}

function DestroyArsenal($player)
{
  $arsenal = &GetArsenal($player);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    WriteLog(CardLink($arsenal[$i], $arsenal[$i]) . " was destroyed from the arsenal.");
    AddGraveyard($arsenal[$i], $player, "ARS");
  }
  $arsenal = [];
}

function DiscardHand($player)
{
  $hand = &GetHand($player);
  for ($i = 0; $i < count($hand); $i += HandPieces()) {
    AddGraveyard($hand[$i], $player, "HAND");
  }
  $hand = [];
}

function DiscardIndex($player, $index)
{
  $hand = &GetHand($player);
  AddGraveyard($hand[$index], $player, "HAND");
  unset($hand[$index]);
  $hand = array_values($hand);
}

function Opt($cardID, $amount)
{
  global $currentPlayer;
  PlayerOpt($currentPlayer, $amount);
}

function OptMain($amount)
{
  global $mainPlayer;
  PlayerOpt($mainPlayer, $amount);
}

function PlayerOpt($player, $amount)
{
  AddDecisionQueue("FINDINDICES", $player, "DECKTOPX," . $amount);
  AddDecisionQueue("MULTIREMOVEDECK", $player, "-", 1);
  AddDecisionQueue("OPT", $player, "<-", 1);
}

function DiscardRandom($player = "", $source = "")
{
  global $currentPlayer;
  if ($player == "") $player = $currentPlayer;
  $hand = &GetHand($player);
  if (count($hand) == 0) return "";
  $index = rand() % count($hand);
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  AddGraveyard($discarded, $player, "HAND");
  WriteLog(CardLink($discarded, $discarded) . " was randomly discarded.");
  CardDiscarded($player, $discarded, $source);

  return $discarded;
};

function CardDiscarded($player, $discarded, $source = "")
{
  global $CS_Num6PowDisc, $mainPlayer;
  if (AttackValue($discarded) >= 6) {
    $character = &GetPlayerCharacter($player);
    if (($character[0] == "WTR001" || $character[0] == "WTR002" || $character[0] == "RVD001" || SearchCurrentTurnEffects("WTR001-SHIYANA", $mainPlayer) || SearchCurrentTurnEffects("WTR002-SHIYANA", $mainPlayer) || SearchCurrentTurnEffects("RVD001-SHIYANA", $mainPlayer)) && $character[1] == 2 && $player == $mainPlayer) { //Rhinar
      AddLayer("TRIGGER", $mainPlayer, $character[0]);
    }
    IncrementClassState($player, $CS_Num6PowDisc);
  }
  if ($discarded == "CRU008" && $source != "" && ClassContains($source, "BRUTE", $mainPlayer) && CardType($source) == "AA") {
    WriteLog("Massacre Intimidated because it was discarded by a Brute attack action card..");
    Intimidate();
  }
}

function DefDiscardRandom()
{
  global $defPlayer;
  $hand = &GetHand($defPlayer);
  if (count($hand) == 0) return;
  $index = rand() % count($hand);
  AddGraveyard($hand[$index], $defPlayer, "HAND");
  unset($hand[$index]);
  $hand = array_values($hand);
};

function Intimidate()
{
  global $defPlayer; //For now we'll assume you can only intimidate the opponent
  //$otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $hand = &GetHand($defPlayer);
  if (count($hand) == 0) {
    WriteLog("Intimidate did nothing because there are no cards in hand.");
    return;
  } //Nothing to do if they have no hand
  $index = rand() % count($hand);
  BanishCardForPlayer($hand[$index], $defPlayer, "HAND", "INT");
  unset($hand[$index]);
  $hand = array_values($hand);
  WriteLog("Intimidate banished a card.");
}

//Deprecated: Use BanishCard in CardSetters instead
function Banish($player, $cardID, $from)
{
  BanishCardForPlayer($cardID, $player, $from);
}

function RemoveCard($player, $index)
{
  $hand = &GetHand($player);
  $cardID = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  return $cardID;
}

function RemoveFromPitch($player, $index)
{
  $pitch = &GetPitch($player);
  $cardID = $pitch[$index];
  unset($pitch[$index]);
  $pitch = array_values($pitch);
  return $cardID;
}

function RemoveFromArsenal($player, $index)
{
  $arsenal = &GetArsenal($player);
  $cardID = $arsenal[$index];
  RemoveArsenalEffects($player, $cardID);
  for ($i = $index + ArsenalPieces() - 1; $i >= $index; --$i) {
    unset($arsenal[$i]);
  }
  $arsenal = array_values($arsenal);
  return $cardID;
}

function DestroyFrozenArsenal($player)
{
  $arsenal = &GetArsenal($player);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    if ($arsenal[$i + 4] == "1") {
      DestroyArsenal($player);
    }
  }
}
