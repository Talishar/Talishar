<?php

include "CardDictionary.php";
include "CoreLogic.php";

function PummelHit($player = -1, $passable = false, $fromDQ = false)
{
  global $defPlayer;
  if($player == -1) $player = $defPlayer;
  if($fromDQ)
  {
    PrependDecisionQueue("DISCARDCARD", $player, "HAND", 1);
    PrependDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
    if($passable) PrependDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
    else PrependDecisionQueue("CHOOSEHAND", $player, "<-", 1);
    PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard", 1);
    PrependDecisionQueue("FINDINDICES", $player, "HAND", ($passable ? 1 : 0));
  }
  else {
    AddDecisionQueue("FINDINDICES", $player, "HAND", ($passable ? 1 : 0));
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard", 1);
    if($passable) AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
    AddDecisionQueue("DISCARDCARD", $player, "HAND", 1);
  }
}

function HandToTopDeck($player)
{
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
  AddDecisionQueue("MULTIADDTOPDECK", $player, "-", 1);
}

function BottomDeck($player="", $mayAbility=false, $shouldDraw=false)
{
  global $currentPlayer;
  if($player == "") $player = $currentPlayer;
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("SETDQCONTEXT", $player, "Put_a_card_from_your_hand_on_the_bottom_of_your_deck.");
  if($mayAbility) AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
  else AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("REMOVEMYHAND", $player, "-", 1);
  AddDecisionQueue("ADDBOTDECK", $player, "-", 1);
  AddDecisionQueue("WRITELOG", $player, "A card was put on the bottom of the deck", 1);
  if($shouldDraw) AddDecisionQueue("DRAW", $player, "-", 1);
}

function BottomDeckMultizone($player, $zone1, $zone2, $isMandatory = false, $context = "Choose a card to sink (or Pass)")
{
  AddDecisionQueue("MULTIZONEINDICES", $player, $zone1 . "&" . $zone2, 1);
  AddDecisionQueue("SETDQCONTEXT", $player, $context, 1);
  AddDecisionQueue($isMandatory ? "CHOOSEMULTIZONE" : "MAYCHOOSEMULTIZONE", $player, "<-", 1);
  AddDecisionQueue("MZREMOVE", $player, "-", 1);
  AddDecisionQueue("ADDBOTDECK", $player, "-", 1);
}

function AddCurrentTurnEffectNextAttack($cardID, $player, $from = "", $uniqueID = -1)
{
  global $combatChain, $layers;
  if(count($layers) > 0 && CardType($layers[0]) == "AA") {
      AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
  } else if(count($combatChain) > 0) AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
  else AddCurrentTurnEffect($cardID, $player, $from, $uniqueID);
}

function AddCurrentTurnEffect($cardID, $player, $from = "", $uniqueID = -1)
{
  global $currentTurnEffects, $combatChain;
  $card = explode("-", $cardID)[0];
  if(CardType($card) == "A" && count($combatChain) > 0 && IsCombatEffectActive($cardID) && !IsCombatEffectPersistent($cardID) && $from != "PLAY") {
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
  if(CardType($card) == "A" && count($combatChain) > 0 && !IsCombatEffectPersistent($cardID) && $from != "PLAY") {
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
  for($i = 0; $i < count($afterResolveEffects); $i += CurrentTurnEffectPieces()) {
    array_push($currentTurnEffects, $afterResolveEffects[$i]);
    array_push($currentTurnEffects, $afterResolveEffects[$i+1]);
    array_push($currentTurnEffects, $afterResolveEffects[$i+2]);
    array_push($currentTurnEffects, $afterResolveEffects[$i+3]);
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
  for($i = 0; $i < count($currentTurnEffectsFromCombat); $i += CurrentTurnEffectPieces()) {
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+1]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+2]);
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i+3]);
  }
  $currentTurnEffectsFromCombat = [];
}

function RemoveCurrentTurnEffect($index)
{
  global $currentTurnEffects;
  unset($currentTurnEffects[$index+3]);
  unset($currentTurnEffects[$index+2]);
  unset($currentTurnEffects[$index+1]);
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
    case "EVR033": return 6;
    case "EVR034": return 5;
    case "EVR035": return 4;
    case "UPR000": return 3;
    case "UPR088": return 4;
    case "UPR221": return 4;
    case "UPR222": return 3;
    case "UPR223": return 2;
    default: return 1;
  }
}

function AddNextTurnEffect($cardID, $player, $uniqueID = -1, $numTurns = 1)
{
  global $nextTurnEffects;
  array_push($nextTurnEffects, $cardID);
  array_push($nextTurnEffects, $player);
  array_push($nextTurnEffects, $uniqueID);
  array_push($nextTurnEffects, CurrentTurnEffectUses($cardID));
  array_push($nextTurnEffects, $numTurns);
}

function IsCombatEffectLimited($index)
{
  global $currentTurnEffects, $combatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $CCS_AttackUniqueID;
  if(count($combatChain) == 0 || $currentTurnEffects[$index + 2] == -1) return false;
  $attackSubType = CardSubType($combatChain[0]);
  if(DelimStringContains($attackSubType, "Ally")) {
    $allies = &GetAllies($mainPlayer);
    if(count($allies) < $combatChainState[$CCS_WeaponIndex] + 5) return false;
    if($allies[$combatChainState[$CCS_WeaponIndex] + 5] != $currentTurnEffects[$index + 2]) return true;
  } else {
    return $combatChainState[$CCS_AttackUniqueID] != $currentTurnEffects[$index + 2];
  }
  return false;
}

function PrependLayer($cardID, $player, $parameter, $target = "-", $additionalCosts = "-", $uniqueID = "-")
{
    global $layers;
    array_push($layers, $cardID);
    array_push($layers, $player);
    array_push($layers, $parameter);
    array_push($layers, $target);
    array_push($layers, $additionalCosts);
    array_push($layers, $uniqueID);
    array_push($layers, GetUniqueId($cardID, $player));
    return count($layers);//How far it is from the end
}

function AddLayer($cardID, $player, $parameter, $target = "-", $additionalCosts = "-", $uniqueID = "-")
{
  global $layers, $dqState;
  //Layers are on a stack, so you need to push things on in reverse order
  array_unshift($layers, GetUniqueId($cardID, $player));
  array_unshift($layers, $uniqueID);
  array_unshift($layers, $additionalCosts);
  array_unshift($layers, $target);
  array_unshift($layers, $parameter);
  array_unshift($layers, $player);
  array_unshift($layers, $cardID);
  if($cardID == "TRIGGER")
  {
    $orderableIndex = intval($dqState[8]);
    if($orderableIndex == -1) $dqState[8] = 0;
    else $dqState[8] += LayerPieces();
  }
  else $dqState[8] = -1;//If it's not a trigger, it's not orderable
  return count($layers);//How far it is from the end
}

function AddDecisionQueue($phase, $player, $parameter, $subsequent = 0, $makeCheckpoint = 0)
{
  global $decisionQueue;
  if(count($decisionQueue) == 0) $insertIndex = 0;
  else {
    $insertIndex = count($decisionQueue) - DecisionQueuePieces();
    if(!IsGamePhase($decisionQueue[$insertIndex])) //Stack must be clear before you can continue with the step
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

function IsDecisionQueueActive()
{
  global $dqState;
  return $dqState[0] == "1";
}

function ProcessDecisionQueue()
{
  global $turn, $decisionQueue, $dqState;
  if($dqState[0] != "1") {
    if(count($turn) < 3) $turn[2] = "-";
    $dqState[0] = "1"; //If the decision queue is currently active/processing
    $dqState[1] = $turn[0];
    $dqState[2] = $turn[1];
    $dqState[3] = $turn[2];
    $dqState[4] = "-"; //DQ helptext initial value
    $dqState[5] = "-"; //Decision queue multizone indices
    $dqState[6] = "0"; //Damage dealt
    $dqState[7] = "0"; //Target
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
  $dqState[8] = "-1"; //Orderable index (what layer after which triggers can be reordered)
  $decisionQueue = [];
  if(($turn[0] == "D" || $turn[0] == "A") && count($combatChain) == 0) {
    $currentPlayer = $mainPlayer;
    $turn[0] = "M";
  }
}

function ShouldHoldPriorityNow($player)
{
  global $layerPriority, $layers;
  if($layerPriority[$player - 1] != "1") return false;
  if($layers[count($layers)-LayerPieces()] == "ENDPHASE") return false;
  $currentLayer = $layers[count($layers) - LayerPieces()];
  $layerType = CardType($currentLayer);
  if(HoldPrioritySetting($player) == 3 && $layerType != "AA" && $layerType != "W") return false;
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
    default: return false;
  }
}

//Must be called with the my/their context
function ContinueDecisionQueue($lastResult = "")
{
  global $decisionQueue, $turn, $currentPlayer, $mainPlayerGamestateStillBuilt, $makeCheckpoint, $otherPlayer;
  global $layers, $layerPriority, $dqVars, $dqState, $CS_AbilityIndex, $CS_AdditionalCosts, $mainPlayer, $CS_LayerPlayIndex;
  global $CS_ResolvingLayerUniqueID, $makeBlockBackup, $defPlayer;
  if(count($decisionQueue) == 0 || IsGamePhase($decisionQueue[0])) {
    if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
    else if(count($decisionQueue) > 0 && $currentPlayer != $decisionQueue[1]) {
      UpdateGameState($currentPlayer);
    }
    if(count($decisionQueue) == 0 && count($layers) > 0) {
      $priorityHeld = 0;
      if($mainPlayer == 1) {
        if(ShouldHoldPriorityNow(1)) {
          AddDecisionQueue("INSTANT", 1, "-");
          $priorityHeld = 1;
          $layerPriority[0] = 0;
        }
        if(ShouldHoldPriorityNow(2)) {
          AddDecisionQueue("INSTANT", 2, "-");
          $priorityHeld = 1;
          $layerPriority[1] = 0;
        }
      } else {
        if(ShouldHoldPriorityNow(2)) {
          AddDecisionQueue("INSTANT", 2, "-");
          $priorityHeld = 1;
          $layerPriority[1] = 0;
        }
        if(ShouldHoldPriorityNow(1)) {
          AddDecisionQueue("INSTANT", 1, "-");
          $priorityHeld = 1;
          $layerPriority[0] = 0;
        }
      }
      if($priorityHeld) {
        ContinueDecisionQueue("");
      } else {
        if(RequiresDieRoll($layers[0], explode("|", $layers[2])[0], $layers[1])) {
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
        $layerUniqueID = array_shift($layers);
        SetClassState($player, $CS_ResolvingLayerUniqueID, $layerUniqueID);
        $params = explode("|", $parameter);
        if($currentPlayer != $player) {
          if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
          else UpdateGameState($currentPlayer);
          $currentPlayer = $player;
          $otherPlayer = $currentPlayer == 1 ? 2 : 1;
          BuildMyGamestate($currentPlayer);
        }
        $layerPriority[0] = ShouldHoldPriority(1);
        $layerPriority[1] = ShouldHoldPriority(2);
        if($cardID == "ENDTURN") EndStep();
        else if($cardID == "ENDPHASE") FinishTurnPass();
        else if($cardID == "RESUMETURN") $turn[0] = "M";
        else if($cardID == "LAYER") ProcessLayer($player, $parameter);
        else if($cardID == "FINALIZECHAINLINK") FinalizeChainLink($parameter);
        else if($cardID == "ATTACKSTEP") {
          $turn[0] = "B";
          $currentPlayer = $defPlayer;
          $makeBlockBackup = 1;
        }
        else if($cardID == "DEFENDSTEP") { $turn[0] = "A"; $currentPlayer = $mainPlayer; }
        else if($cardID == "TRIGGER") {
          ProcessTrigger($player, $parameter, $uniqueID, $target, $additionalCosts, $params[0]);
          ProcessDecisionQueue();
        } else {
          SetClassState($player, $CS_AbilityIndex, isset($params[2]) ? $params[2] : "-"); //This is like a parameter to PlayCardEffect and other functions
          PlayCardEffect($cardID, $params[0], $params[1], $target, $additionalCosts, isset($params[3]) ? $params[3] : "-1", isset($params[2]) ? $params[2] : -1);  
          ClearDieRoll($player);
        }
      }
    } else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPLAY") {
      if($currentPlayer != $decisionQueue[1]) {
        $currentPlayer = $decisionQueue[1];
        $otherPlayer = $currentPlayer == 1 ? 2 : 1;
        BuildMyGamestate($currentPlayer);
      }
      $params = explode("|", $decisionQueue[2]);
      CloseDecisionQueue();
      if($params[2] == "") $params[2] = 0;
      if($turn[0] == "B" && count($layers) == 0) { //If a layer is not created
        PlayCardEffect($params[0], $params[1], $params[2], "-", $params[3], $params[4]);
      } else {
        //params 3 = ability index
        //params 4 = Unique ID
        $additionalCosts = GetClassState($currentPlayer, $CS_AdditionalCosts);
        if($additionalCosts == "") $additionalCosts = "-";
        $layerIndex = count($layers) - GetClassState($currentPlayer, $CS_LayerPlayIndex);
        $layers[$layerIndex + 2] = $params[1] . "|" . $params[2] . "|" . $params[3] . "|" . $params[4];
        $layers[$layerIndex + 4] = $additionalCosts;
        ProcessDecisionQueue();
        return;
      }
    } else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPAYING") {
      $player = $decisionQueue[1];
      $params = explode("-", $decisionQueue[2]); //Parameter
      if($lastResult == "") $lastResult = 0;
      CloseDecisionQueue();
      if($currentPlayer != $player) {
        $currentPlayer = $player;
        $otherPlayer = $currentPlayer == 1 ? 2 : 1;
        BuildMyGamestate($currentPlayer);
      }
      PlayCard($params[0], $params[1], $lastResult, $params[2], isset($params[4]) ? $params[4] : -1);
    } else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECHAINLINK") {
      CloseDecisionQueue();
      ResolveChainLink();
    } else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECOMBATDAMAGE") {
      $parameter = $decisionQueue[2];
      if($parameter != "-") $damageDone = $parameter;
      else $damageDone = $dqState[6];
      CloseDecisionQueue();
      ResolveCombatDamage($damageDone);
    } else if(count($decisionQueue) > 0 && $decisionQueue[0] == "PASSTURN") {
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
  if(count($dqVars) > 0) {
    if(str_contains($parameter, "{0}")) $parameter = str_replace("{0}", $dqVars[0], $parameter);
    if(str_contains($parameter, "<0>")) $parameter = str_replace("<0>", CardLink($dqVars[0], $dqVars[0]), $parameter);
    if(count($dqVars) > 1 && str_contains($parameter, "{1}")) $parameter = str_replace("{1}", $dqVars[1], $parameter);
  }
  if(count($dqVars) > 1) $parameter = str_replace("<1>", CardLink($dqVars[1], $dqVars[1]), $parameter);
  $subsequent = array_shift($decisionQueue);
  $makeCheckpoint = array_shift($decisionQueue);
  $turn[0] = $phase;
  $turn[1] = $player;
  $currentPlayer = $player;
  $turn[2] = ($parameter == "<-" ? $lastResult : $parameter);
  $return = "PASS";
  if($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter == "<-" ? $lastResult : $parameter), $lastResult);
  if($parameter == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS"; //Collapse the rest of the queue if this decision point has invalid parameters
  if(is_array($return) || strval($return) != "NOTSTATIC") {
    if($phase != "SETDQCONTEXT") $dqState[4] = "-"; //Clear out context for static states -- context only persists for one choice
    ContinueDecisionQueue($return);
  } else {
    if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGameState();
  }
}

function ProcessLayer($player, $parameter)
{
  switch ($parameter) {
    case "PHANTASM":
      PhantasmLayer();
      break;
    case "MIRAGE":
      MirageLayer();
      break;
    default: break;
  }
}

function AddOnHitTrigger($cardID)
{
  global $mainPlayer;
  switch ($cardID) {
    case "WTR083": case "WTR084": case "WTR085":
    case "WTR110": case "WTR111": case "WTR112":
    case "WTR115": case "WTR167": case "WTR168": case "WTR169":
    case "ARC011": case "ARC012": case "ARC013":
    case "ARC018": case "ARC020": case "ARC021": case "ARC022":
    case "ARC043": case "ARC045":
    case "ARC060": case "ARC061": case "ARC062":
    case "ARC066": case "ARC067": case "ARC068":
    case "ARC069": case "ARC070": case "ARC071":
    case "ARC077": case "ARC080": case "ARC159":
    case "ARC164": case "ARC165": case "ARC166":
    case "ARC161": case "ARC179": case "ARC180": case "ARC181":
    case "ARC182": case "ARC183": case "ARC184":
    case "ARC185": case "ARC186": case "ARC187":
    case "ARC194": case "ARC195": case "ARC196":
    case "CRU060": case "CRU061": case "CRU062":
    case "CRU066": case "CRU067": case "CRU068":
    case "CRU069": case "CRU070": case "CRU071":
    case "CRU072": case "CRU074": case "CRU106": case "CRU107": case "CRU108":
    case "CRU109": case "CRU110": case "CRU111": case "CRU123":
    case "CRU129": case "CRU130": case "CRU131":
    case "CRU132": case "CRU133": case "CRU134":
    case "CRU142": case "CRU148": case "CRU149": case "CRU150":
    case "CRU151": case "CRU152": case "CRU153":
    case "CRU180": case "CRU183": case "CRU184": case "CRU185":
    case "MON004":
    case "MON007":
    case "MON008": case "MON009": case "MON010":
    case "MON014": case "MON015": case "MON016":
    case "MON017": case "MON018": case "MON019":
    case "MON020": case "MON021": case "MON022":
    case "MON023": case "MON024": case "MON025":
    case "MON026": case "MON027": case "MON028":
    case "MON155":
    case "MON042": case "MON043": case "MON044":
    case "MON048": case "MON049": case "MON050":
    case "MON246":
    case "MON269": case "MON270": case "MON271":
    case "MON275": case "MON276": case "MON277":
    case "MON072": case "MON073": case "MON074":
    case "MON078": case "MON079": case "MON080":
    case "MON198":
    case "MON206": case "MON207": case "MON208":
    case "ELE001": case "ELE002":
    case "ELE004":
    case "ELE013": case "ELE014": case "ELE015":
    case "ELE209": case "ELE210": case "ELE211":
    case "ELE005": case "ELE006": case "ELE205":
    case "ELE206": case "ELE207": case "ELE208":
    case "ELE036":
    case "ELE216": case "ELE217": case "ELE218":
    case "ELE148": case "ELE149": case "ELE150":
    case "ELE157": case "ELE158": case "ELE159":
    case "EVR021": case "EVR038": case "EVR039": case "EVR040":
    case "EVR044": case "EVR045": case "EVR046": case "EVR088":
    case "EVR094": case "EVR095": case "EVR096":
    case "EVR097": case "EVR098": case "EVR099":
    case "EVR104": case "EVR105":
    case "EVR110": case "EVR111": case "EVR112":
    case "EVR113": case "EVR114": case "EVR115": case "EVR138": case "EVR156":
    case "UPR024": case "UPR025": case "UPR026":
    case "UPR411": case "UPR413": case "UPR416":
    case "UPR048": case "UPR051": case "UPR052": case "UPR053":
    case "UPR054": case "UPR055": case "UPR056":
    case "UPR075": case "UPR076": case "UPR077":
    case "UPR081": case "UPR082": case "UPR083":
    case "UPR161": case "UPR087": case "UPR093":
    case "UPR100": case "UPR187": case "UPR188":
    case "DYN047": case "DYN050": case "DYN051": case "DYN052": case "DYN067":
    case "DYN107": case "DYN108": case "DYN109": case "DYN115": case "DYN116":
    case "DYN117": case "DYN118": case "DYN119": case "DYN121":
    case "DYN120": case "DYN122":
    case "DYN124": case "DYN125": case "DYN126": case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135": case "DYN136": case "DYN137": case "DYN138":
    case "DYN139": case "DYN140": case "DYN141": case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
    case "DYN153": case "DYN154": case "DYN156": case "DYN157": case "DYN158": case "DYN162": case "DYN163": case "DYN164":
    case "OUT005": case "OUT006":
    case "OUT007": case "OUT008":
    case "OUT009": case "OUT010":
    case "OUT012": case "OUT013":
    case "OUT024": case "OUT025": case "OUT026":
    case "OUT036": case "OUT037": case "OUT038":
    case "OUT039": case "OUT040": case "OUT041":
    case "OUT051": case "OUT053":
    case "OUT059": case "OUT060": case "OUT061":
    case "OUT062": case "OUT063": case "OUT064":
    case "OUT068": case "OUT069": case "OUT070":
    case "OUT071": case "OUT072": case "OUT073":
    case "OUT080": case "OUT081": case "OUT082":
    case "OUT101": case "OUT118": case "OUT119": case "OUT120":
    case "OUT124": case "OUT125": case "OUT126":
    case "OUT136": case "OUT137": case "OUT138":
    case "OUT142": case "OUT151": case "OUT152": case "OUT153":
    case "OUT162": case "OUT163": case "OUT164": case "OUT183":
    case "OUT189": case "OUT190": case "OUT191":
    case "OUT198": case "OUT199": case "OUT200":
    case "OUT201": case "OUT202": case "OUT203":
    case "OUT204": case "OUT205": case "OUT206":
    case "DTD082": case "DTD083": case "DTD084":
    case "DTD135": case "DTD172": case "DTD173": case "DTD174":
    case "DTD193": case "DTD226": case "DTD227":
    case "TCC088": case "TCC016": case "TCC050": case "TCC083":
    case "EVO006": case "EVO054":
    case "EVO055": case "EVO056": case "EVO138":
    case "EVO150": case "EVO151": case "EVO152":
    case "EVO186": case "EVO187": case "EVO188":
    case "EVO189": case "EVO190": case "EVO191":
    case "EVO198": case "EVO199": case "EVO200":
    case "EVO201": case "EVO202": case "EVO203":
    case "EVO216": case "EVO217": case "EVO218":
    case "EVO236": case "EVO241":
    case "HVY012": case "HVY050": case "HVY071": case "HVY072": case "HVY073":
    case "HVY074": case "HVY075": case "HVY076":
    case "HVY208": case "HVY213": case "HVY214": case "HVY215":
    case "HVY225": case "HVY226": case "HVY227":
    case "HVY249":
    case "AKO013":
    case "MST003":
    case "MST173": case "MST174": case "MST175":
    case "MST233":
      AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "ONHITEFFECT");
      break;
    case "CRU054": 
      if(ComboActive($cardID)) AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "ONHITEFFECT");
      break;
    case "ELE003":
      if(SearchCurrentTurnEffects($cardID, $mainPlayer)) AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "ONHITEFFECT");
      break;
    case "MST103":
      if(NumAttackReactionsPlayed() > 2 && IsHeroAttackTarget()) AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "ONHITEFFECT");
      break;
    case "MST104":
    case "MST106": case "MST107": case "MST108": 
    case "MST109": case "MST110": case "MST111": 
    case "MST112": case "MST113": case "MST114": 
    case "MST115": case "MST116": case "MST117": 
    case "MST118": case "MST119": case "MST120": 
    case "MST121": case "MST122": case "MST123": 
    case "MST124": case "MST125": case "MST126":
    case "MST191": case "MST192":
    case "MST194": case "MST195": case "MST196":
    case "MST206": case "MST207": case "MST208":
      if(IsHeroAttackTarget()) AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "ONHITEFFECT");
      break;
    default:
      break;
  }
}

function AddCrushEffectTrigger($cardID)
{
  global $mainPlayer;
  switch ($cardID) {
    case "WTR043":
    case "WTR044":
    case "WTR045":
    case "WTR048": case "WTR049": case "WTR050":
    case "WTR057": case "WTR058": case "WTR059":
    case "WTR060": case "WTR061": case "WTR062":
    case "WTR063": case "WTR064": case "WTR065":
    case "WTR066": case "WTR067": case "WTR068":
    case "CRU026":
    case "CRU027":
    case "CRU032": case "CRU033": case "CRU034":
    case "CRU035": case "CRU036": case "CRU037":
    case "DTD203":
    case "TCC039": case "TCC044":
      AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "CRUSHEFFECT");
      break;
    default:
      break;
  }
}

function AddTowerEffectTrigger($cardID)
{
  global $mainPlayer;
  switch ($cardID) {
    case "TCC034": case "HVY062":
    case "TCC036": case "HVY064":
      AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "TOWEREFFECT");
    break;
  default:
    break;
  }
}

function AddCardEffectHitTrigger($cardID) // Effects that do not gives it's effect to the attack so still triggers when Stamp Confidance is in the arena
{
  global $mainPlayer, $combatChain, $defPlayer;
  if(SearchCurrentTurnEffects("MST079-HITPREVENTION", $defPlayer)) return false;
  $effects = explode(',', $cardID);
  switch ($effects[0]) {
    case "ARC170-1": case "ARC171-1": case "ARC172-1":
    case "CRU084-2":
    case "MON218":
    case "ELE151-HIT": case "ELE152-HIT": case "ELE153-HIT":
    case "ELE163": case "ELE164": case "ELE165":
    case "ELE173": 
    case "ELE198": case "ELE199": case "ELE200":
    case "EVR066-1": case "EVR067-1": case "EVR068-1":
    case "EVR170-1": case "EVR171-1": case "EVR172-1":
    case "DVR008-1": 
    case "OUT140": case "OUT188_1":
    case "AAZ004":
    case "DTD229-HIT": 
      AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "EFFECTHITEFFECT");
      break;
    case "ELE066-HIT":
      AddLayer("TRIGGER", $mainPlayer, "ELE066", "ELE066-TRIGGER", "EFFECTHITEFFECT");
      break;  
    default:
      break;
  }
}

function AddEffectHitTrigger($cardID) // Effects that gives effect to the attack (keywords "attack gains/gets")
{
  global $mainPlayer, $Card_LifeBanner, $Card_ResourceBanner, $layers;
  $effects = explode(',', $cardID);
  switch ($effects[0]) {
    case "WTR129": case "WTR130": case "WTR131":
    case "WTR147": case "WTR148": case "WTR149":
    case "WTR206": case "WTR207": case "WTR208":
    case "WTR209": case "WTR210": case "WTR211":
    case "CRU124": 
    case "CRU145": case "CRU146": case "CRU147":
    case "MON034": 
    case "MON081": case "MON082": case "MON083":
    case "MON110": case "MON111": case "MON112":
    case "MON193": case "MON299": case "MON300": case "MON301":
    case "ELE005": case "ELE019": case "ELE020": case "ELE021":
    case "ELE022": case "ELE023": case "ELE024":
    case "ELE035-2": case "ELE037-2": case "ELE047": case "ELE048": case "ELE049":
    case "ELE092-BUFF":
    case "ELE195": case "ELE196": case "ELE197":
    case "ELE205": case "ELE215":
    case "EVR047-1": case "EVR048-1": case "EVR049-1":
    case "EVR161-1": case "EVR164": case "EVR165": case "EVR166":
    case "DYN028": case "DYN071": case "DYN155":
    case "DYN185-HIT": case "DYN186-HIT": case "DYN187-HIT":
    case "OUT021": case "OUT022": case "OUT023":
    case "OUT105": case "OUT112": case "OUT113":
    case "OUT114": case "OUT143":
    case "OUT158": case "OUT165":  case "OUT166": case "OUT167": 
    case "DTD051": case "DTD052":
    case "DTD066": case "DTD067": case "DTD068":
    case "DTD080-2":
    case "DTD080-3":
    case "DTD207":
    case $Card_LifeBanner:
    case $Card_ResourceBanner:
    case "EVO155": case "EVO434":
    case "HVY090": case "HVY091": case "HVY136":
    case "HVY099":
      AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "EFFECTHITEFFECT");
      break;
    case "MST105-HIT": case "MST162-HIT":
      AddLayer("TRIGGER", $mainPlayer, substr($cardID, 0, 6), $cardID, "EFFECTHITEFFECT");
      break;
    default:
      break;
  }
}

function ProcessMainCharacterHitEffect($cardID, $player, $target) {
  global $combatChain, $mainPlayer, $layers;
  $character = &GetPlayerCharacter($player);
  if(CardType($target) == "AA" && SearchCurrentTurnEffects("OUT108", $mainPlayer, count($layers) <= LayerPieces())) return true;
  switch ($cardID) {
    case "WTR076": case "WTR077":
      KatsuHit();
      break;
    case "WTR117":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Refraction_Bolters_to_get_Go_Again");
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, "MYCHAR-$index", 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      AddDecisionQueue("OP", $player, "GIVEATTACKGOAGAIN", 1);
      AddDecisionQueue("WRITELOG", $player, "Refraction Bolters was destroyed", 1);
      break;
    case "WTR079":
      Draw($player);
      break;
    case "ARC152":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Vest_of_the_First_Fist_to_gain_2_resources");
      AddDecisionQueue("NOPASS", $player, "");
      AddDecisionQueue("PASSPARAMETER", $player, "MYCHAR-" . $index, 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      AddDecisionQueue("GAINRESOURCES", $player, 2, 1);
      break;
    case "CRU053":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Breeze_Rider_Boots");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $player, $character[$index], 1);
      break;
    case "ELE062": case "ELE063":
      PlayAura("ELE109", $player);
      break;
    case "EVR037":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "to_destroy_Mask_of_the_Pouncing_Lynx");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("FINDINDICES", $player, "MASKPOUNCINGLYNX", 1);
      AddDecisionQueue("MAYCHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("MULTIBANISH", $player, "DECK,TT", 1);
      AddDecisionQueue("SETDQVAR", $player, "0", 1);
      AddDecisionQueue("WRITELOG", $player, "<0> was banished", 1);
      AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
      break;
    case "HVY097":
      $hand = &GetHand($player);
      $resources = &GetResources($player);
      if(TypeContains($combatChain[0], "W", $mainPlayer) && (Count($hand) > 0 || $resources[0] > 0))
      {
        AddDecisionQueue("YESNO", $player, "if you want to pay 1 to create a " . CardLink("HVY242", "HVY242"), 0, 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("WRITELOG", $player, CardLink($cardID, $cardID) . " created a " . CardLink("HVY242", "HVY242") . " token ", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "HVY242", 1);
        AddDecisionQueue("PUTPLAY", $player, "-", 1);
      }
      break;
    default:
      break;
  }
}

function ProcessItemsEffect($cardID, $player, $target, $uniqueID)
{
  global $layers, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if(CardType($target) == "AA" && SearchCurrentTurnEffects("OUT108", $player, count($layers) <= LayerPieces())) return true;
  switch ($cardID) {
    case "DYN094":
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_" . CardLink($cardID, $cardID) . "_and_a_defending_equipment?");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("SEARCHCOMBATCHAIN", $player, "E", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a defending equipment to destroy", 1);
      AddDecisionQueue("CHOOSECARDID", $player, "<-", 1);
      AddDecisionQueue("POWDERKEG", $player, "-", 1);
      break;
    case "EVO074":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      DestroyItemForPlayer($player, $index);
      AddDecisionQueue("PASSPARAMETER", $player, "0");
      AddDecisionQueue("SETDQVAR", $player, "0");
      for($i = 0; $i < 2; ++$i) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRITEMS&MYITEMS", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZDESTROY", $player, "-");
        AddDecisionQueue("INCDQVARIFNOTPASS", $player, "0");
      }
      AddDecisionQueue("SPECIFICCARD", $otherPlayer, "TICKTOCKCLOCK");
      break;
    case "EVO084": case "EVO085": case "EVO086":
      if($cardID == "EVO084") $amount = 4;
      else if($cardID == "EVO085") $amount = 3;
      else $amount = 2;
      DamageTrigger($otherPlayer, $amount, "DAMAGE", $cardID);
      DestroyItemForPlayer($player, SearchItemsForUniqueID($uniqueID, $player));
      break;
    case "EVO098":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    default:
      break;
  }
}

function ProcessTrigger($player, $parameter, $uniqueID, $target="-", $additionalCosts="-", $from="-")
{
  global $combatChain, $CS_NumNonAttackCards, $CS_ArcaneDamageDealt, $CS_NumRedPlayed, $CS_DamageTaken, $EffectContext, $CS_PlayIndex, $CombatChain;
  global $CID_BloodRotPox, $CID_Inertia, $CID_Frailty, $totalBlock, $totalAttack, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer;
  global $CS_DamagePrevention;
  $items = &GetItems($player);
  $auras = &GetAuras($player);
  $parameter = ShiyanaCharacter($parameter);
  $EffectContext = $parameter;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if($additionalCosts == "ONHITEFFECT") { ProcessHitEffect($target, $combatChain[2]); return; }
  if($additionalCosts == "CRUSHEFFECT") { ProcessCrushEffect($target); return; }
  if($additionalCosts == "TOWEREFFECT") { ProcessTowerEffect($target); return; }
  if($additionalCosts == "EFFECTHITEFFECT") {
    if(EffectHitEffect($target, $combatChain[2])) {
      $index = FindCurrentTurnEffectIndex($player, $target);
      if($index != -1) RemoveCurrentTurnEffect($index);
    }
    return;
  }
  if($additionalCosts == "MAINCHARHITEFFECT")  { ProcessMainCharacterHitEffect($parameter, $player, $target); return; }
  if($additionalCosts == "ITEMHITEFFECT") { ProcessItemsEffect($parameter, $player, $target, $uniqueID); return; }
  switch($parameter) {
    case "HEAVE":
      Heave();
      break;
    case "WTR000":
      if(PlayerHasLessHealth($player)) GainHealth(1, $player);
      break;
    case "WTR001": case "WTR002": case "RVD001":
      Intimidate();
      break;
    case "WTR046":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR047":
      Draw($player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR054": case "WTR055": case "WTR056":
      if($parameter == "WTR054") $amount = 3;
      else if($parameter == "WTR055") $amount = 2;
      else $amount = 1;
      BlessingOfDeliveranceDestroy($amount);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR069": case "WTR070": case "WTR071":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR072": case "WTR073": case "WTR074":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR075":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "WTR076": case "WTR077":
      KatsuHit();
      break;
    case "WTR079":
      Draw($player);
      break;
    case "WTR117":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Refraction_Bolters");
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, "MYCHAR-$index", 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      AddDecisionQueue("OP", $player, "GIVEATTACKGOAGAIN", 1);
      AddDecisionQueue("WRITELOG", $player, "Refraction Bolters was destroyed", 1);
      break;
    case "WTR119":
      Draw($mainPlayer);
      break;
    case "ARC000":
      Opt($parameter, 2);
      break;
    case "ARC007":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      --$items[$index + 1];
      GainResources($player, 2);
      if($items[$index + 1] <= 0) DestroyItemForPlayer($player, $index);
      break;
    case "ARC035":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      --$items[$index + 1];
      if($items[$index + 1] <= 0) DestroyItemForPlayer($player, $index);
      break;
    case "ARC075": case "ARC076":
      ViseraiPlayCard($target);
      break;
    case "ARC106": case "ARC107": case "ARC108":
      if($parameter == "ARC106") $amount = 3;
      else if($parameter == "ARC107") $amount = 2;
      else $amount = 1;
      WriteLog(CardLink($parameter, $parameter) . " created $amount runechants");
      PlayAura("ARC112", $player, $amount);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ARC112":
      DealArcane(1, 1, "RUNECHANT", "ARC112", player:$player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ARC162":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "CRU000":
      PlayAura("ARC112", $player);
      break;
    case "CRU007":
      AddDecisionQueue("SPECIFICCARD", $player, "BEASTWITHIN");
      break;
    case "CRU008":
      Intimidate();
      break;
    case "CRU028":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "CRU029": case "CRU030": case "CRU031":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "CRU038": case "CRU039": case "CRU040":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "CRU051": case "CRU052":
      DestroyCurrentWeapon();
      break;
    case "CRU075":
      $index = SearchAurasForUniqueID($uniqueID, $player);
      if($auras[$index+2] == 0) {
        DestroyAuraUniqueID($player, $uniqueID);
      } else {
        --$auras[$index+2];
      }
      break;
    case "CRU097":
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYCHAR:type=C&THEIRCHAR:type=C");
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose which hero to copy");
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
      AddDecisionQueue("CHANGESHIYANA", $player, "<-", 1);
      AddDecisionQueue("APPENDLASTRESULT", $player, "-SHIYANA", 1);
      AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $player, "<-", 1);
      break;
    case "CRU099":
      PutItemIntoPlayForPlayer($target, $player);
      RemoveBanish($player, SearchBanishForCard($player, $target));
      break;
    case "CRU102":
      AddDecisionQueue("DRAW", $player, "-", 1);
      MZMoveCard($player, "MYHAND", "MYTOPDECK", silent:true);
      break;
    case "CRU126":
      TrapTriggered($parameter);
      AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
      AddDecisionQueue("NOPASS", $mainPlayer, $parameter, 1);
      AddDecisionQueue("PAYRESOURCES", $mainPlayer, "1", 1);
      AddDecisionQueue("ELSE", $mainPlayer, "-");
      AddDecisionQueue("TRIPWIRETRAP", $mainPlayer, "-", 1);
      break;
    case "CRU127":
      TrapTriggered($parameter);
      AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_avoid_taking_2_damage", 1, 1);
      AddDecisionQueue("NOPASS", $mainPlayer, $parameter, 1);
      AddDecisionQueue("PAYRESOURCES", $mainPlayer, "1", 1);
      AddDecisionQueue("ELSE", $mainPlayer, "-");
      AddDecisionQueue("TAKEDAMAGE", $mainPlayer, "2-".$parameter, 1);
        break;
    case "CRU128":
      TrapTriggered($parameter);
      if(!IsAllyAttacking()) {
        AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_avoid_your_attack_getting_-2", 1, 1);
        AddDecisionQueue("NOPASS", $mainPlayer, $parameter, 1);
        AddDecisionQueue("PAYRESOURCES", $mainPlayer, "1", 1);
        AddDecisionQueue("ELSE", $mainPlayer, "-");
        AddDecisionQueue("ATTACKMODIFIER", $player, "-2", 1);
      }
      else {
        AddDecisionQueue("ATTACKMODIFIER", $mainPlayer, "-2", 1);
      }
      break;
    case "CRU142":
      if(GetClassState($player, $CS_NumNonAttackCards) > 0) PlayAura("ARC112", $player);
      if(GetClassState($player, $CS_ArcaneDamageDealt) > 0) PlayAura("ARC112", $player);
      break;
    case "CRU144":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "CRU161":
      AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_1_to_give_+1_arcane_damage");
      AddDecisionQueue("NOPASS", $player, "-", 1, 1);
      AddDecisionQueue("PAYRESOURCES", $player, "1", 1);
      AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
      AddDecisionQueue("BUFFARCANEPREVLAYER", $player, "CRU161", 1);
      AddDecisionQueue("CHARFLAGDESTROY", $player, FindCharacterIndex($player, "CRU161"), 1);
      break;
    case "CRU179":
      $gamblersGlovesIndex = FindCharacterIndex($player, "CRU179");
      if($additionalCosts)
      {
        PrependDecisionQueue("REROLLDIE", $target, "1", 1);
        PrependDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
        PrependDecisionQueue("PASSPARAMETER", $player, $gamblersGlovesIndex, 1);
        PrependDecisionQueue("NOPASS", $player, "-");
        PrependDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Gambler's_Gloves_to_reroll_the_result");
      }
      else
      {
        AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Gambler's_Gloves_to_reroll_the_result");
        AddDecisionQueue("NOPASS", $player, "-");
        AddDecisionQueue("PASSPARAMETER", $player, $gamblersGlovesIndex, 1);
        AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
        AddDecisionQueue("REROLLDIE", $target, "1", 1);
      }
      break;
    case "BLOODDEBT":
      $numBloodDebt = SearchCount(SearchBanish($mainPlayer, "", "", -1, -1, "", "", true));
      $char = &GetPlayerCharacter($mainPlayer);
      if($char[0] == "DTD564" && +$char[1] == 2) { $deck = new Deck($mainPlayer); for($i=0; $i<$numBloodDebt; ++$i) $deck->BanishTop(); return; }
      $health = &GetHealth($mainPlayer);
      if($numBloodDebt > 0) {
        if($health > 13 && $health - $numBloodDebt <= 13)
        {
          $numBloodDebt -= ($health - 13);
          $health = 13;
          if(SearchInventoryForCard($mainPlayer, "DTD164") != "")
          {
            AddDecisionQueue("YESNO", $mainPlayer, "if you want to transform into Levia Consumed");
            AddDecisionQueue("NOPASS", $mainPlayer, "-");
            AddDecisionQueue("PASSPARAMETER", $mainPlayer, $numBloodDebt, 1);
            AddDecisionQueue("TRANSFORMHERO", $mainPlayer, "DTD564", 1);
            AddDecisionQueue("ELSE", $mainPlayer, "-");
          }
        }
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $numBloodDebt, 1);
        AddDecisionQueue("OP", $mainPlayer, "LOSEHEALTH", 1);
        AddDecisionQueue("WRITELOG", $mainPlayer, "Player $mainPlayer lost $numBloodDebt life from Blood Debt", 1);
      }
      break;
    case "MON012":
      DealArcane(1, 0, "STATIC", $parameter, false, $player);
      break;
    case "MON089": 
      $hand = &GetHand($player);
      $resources = &GetResources($player);
      if(Count($hand) > 0 || $resources[0] > 0)
      {
        if($player == $defPlayer) {
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
          AddDecisionQueue("BUTTONINPUT", $player, "0,1");
          AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
          AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
          AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
          if(!SearchCurrentTurnEffects("MON089", $player)) AddDecisionQueue("ADDCURRENTEFFECT", $player, "MON089", 1);
        }
        else {
          AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_1_to_gain_an_action_point", 0, 1);
          AddDecisionQueue("NOPASS", $player, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $player, 1, 1);
          AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
          AddDecisionQueue("GAINACTIONPOINTS", $player, "1", 1);
          AddDecisionQueue("WRITELOG", $player, "Gained_an_action_point_from_" . CardLink("MON089", "MON089"), 1);
        }
      }
      break;
    case "MON122":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("CHARREADYORPASS", $player, $index);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Hooves_of_the_Shadowbeast_to_gain_an_action_point", 1);
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("GAINACTIONPOINTS", $player, 1, 1);
      AddDecisionQueue("WRITELOG", $player, "Gained_an_action_point_from_Hooves_of_the_Shadowbeast", 1);
      break;
    case "MON186":
      $deck = new Deck($player);
      $deck->BanishTop(banishedBy:$parameter);
      break;
    case "MON241": case "MON242": case "MON243":
    case "MON244": case "RVD005": case "RVD006":
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
      AddDecisionQueue("BUTTONINPUT", $player, "0,1");
      AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
      AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $player, $parameter, 1);
      AddDecisionQueue("PASSPARAMETER", $player, 2, 1);
      AddDecisionQueue("COMBATCHAINCHARACTERDEFENSEMODIFIER", $player, $target, 1);
      break;
    case "ELE025": case "ELE026": case "ELE027":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ELE028": case "ELE029": case "ELE030":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ELE062": case "ELE063":
      PlayAura("ELE110", $player);
      break;
    case "ELE004":
      for($i = 1; $i < count($combatChain); $i += CombatChainPieces()) if($combatChain[$i] == $player) PlayAura("ELE111", $player);
      break;
    case "ELE109":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ELE110":
      WriteLog(CardLink($parameter, $parameter) . " grants go again");
      GiveAttackGoAgain();
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
    case "ELE175":
      AddDecisionQueue("YESNO", $player, "do_you_want_to_pay_1_to_give_your_action_go_again", 0, 1);
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, 1, 1);
      AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
      AddDecisionQueue("GIVEACTIONGOAGAIN", $player, $target, 1);
      break;
    case "ELE203":
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
      AddDecisionQueue("BUTTONINPUT", $player, "0,1");
      AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
      AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $player, "ELE203", 1);
      break;
    case "ELE206": case "ELE207": case "ELE208":
      AddCurrentTurnEffect($parameter, $player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "ELE215":
      DestroyArsenal($target, effectController:$player);
      DiscardHand($target, false);
      break;
    case "ELE226":
      DealArcane(1, 0, "PLAYCARD", $combatChain[0]);
      break;
    case "EVR018":
      PlayAura("ELE111", $player);
      break;
    case "EVR069":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      --$items[$index+1];
      if($items[$index+1] < 0) DestroyItemForPlayer($player, $index);
      break;
    case "EVR071":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      if($items[$index+1] > 0) {
        AddDecisionQueue("SETDQCONTEXT", $player, "Do you want to remove a Steam Counter from " . CardLink($items[$index], $items[$index]) . " and keep it in play?", 1);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_remove_a_Steam_Counter_and_keep_" . CardLink($items[$index], $items[$index]), 1);
        AddDecisionQueue("REMOVECOUNTERITEMORDESTROY", $player, $index, 1);
      }
      else {
        DestroyItemForPlayer($player, $index);
        WriteLog(CardLink($items[$index], $items[$index]) . " was destroyed");
      }
      break;
    case "EVR107": case "EVR108": case "EVR109":
      $index = SearchAurasForUniqueID($uniqueID, $player);
      if($index == -1) break;
      $auras = &GetAuras($player);
      if($auras[$index+2] == 0) DestroyAuraUniqueID($player, $uniqueID);
      else {
        --$auras[$index+2];
        PlayAura("ARC112", $player);
      }
      break;
    case "EVR120": case "UPR102": case "UPR103":
      PlayAura("ELE111", $otherPlayer);
      break;
    case "EVR131": case "EVR132": case "EVR133":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "EVR141":
      PlayAura("MON104", $player);
      break;
    case "RVD015":
      $deck = new Deck($player);
      if($deck->Reveal() && ModifiedAttackValue($deck->Top(), $player, "DECK", source:"RVD015") < 6) {
        $card = $deck->AddBottom($deck->Top(remove:true), "DECK");
        WriteLog(CardLink("RVD015", "RVD015") . " put " . CardLink($card, $card) . " on the bottom of your deck");
      }
      break;
    case "UPR005":
      DealArcane(1, 1, "STATIC", $combatChain[0], false, $mainPlayer);
      break;
    case "UPR054": case "UPR055": case "UPR056":
    case "UPR075": case "UPR076": case "UPR077":
    case "UPR081": case "UPR082": case "UPR083":
      $numDraconicLinks = NumDraconicChainLinks();
      MZMoveCard($mainPlayer, "MYHAND:type=AA;maxCost=" . ($numDraconicLinks > 0 ? $numDraconicLinks - 1 : -2), "MYBANISH,HAND,TT", may:true);
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYBANISH", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "LASTMZINDEX", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GETUNIQUEID", 1);
      AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $mainPlayer, $parameter . ",HIT", 1);
      break;
    case "UPR095":
      if(GetClassState($player, $CS_DamageTaken) > 0) MZMoveCard($player, "MYDISCARD:isSameName=UPR101", "MYHAND", may:true);
      break;
    case "UPR096":
      if(GetClassState($player, $CS_NumRedPlayed) > 1 && CanRevealCards($player)) {
        MZMoveCard($player, "MYDECK:isSameName=UPR101", "MYHAND", may:true);
        AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
      }
      return "";
    case "UPR140":
      $index = SearchAurasForUniqueID($uniqueID, $player);
      if($index == -1) break;
      $auras = &GetAuras($player);
      --$auras[$index+2];
      PayOrDiscard($target, 2, true);
      if($auras[$index+2] == 0) {
        WriteLog(CardLink($auras[$index], $auras[$index]) . " is destroyed");
        DestroyAura($player, $index);
      }
      break;
    case "UPR141": case "UPR142": case "UPR143":
      if($parameter == "UPR141") $numFrostbite = 4;
      else if($parameter == "UPR142") $numFrostbite = 3;
      else $numFrostbite = 2;
      PlayAura("ELE111", $target, $numFrostbite);
      break;
    case "UPR176": case "UPR177": case "UPR178":
      $i = SearchAurasForUniqueID($uniqueID, $player);
      if($i == -1) break;
      $auras = &GetAuras($player);
      if($auras[$i] == "UPR176") $numOpt = 3;
      else if($auras[$i] == "UPR177") $numOpt = 2;
      else $numOpt = 1;
      for($j = 0; $j < $numOpt; ++$j) PlayerOpt($player, 1);
      AddDecisionQueue("DRAW", $player, "-", 1);
      DestroyAura($player, $i);
      break;
    case "UPR182":
      BottomDeckMultizone($player, "MYHAND", "MYARS");
      AddDecisionQueue("DRAW", $player, "-", 1);
      break;
    case "UPR190":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "UPR191": case "UPR192": case "UPR193":
      ChooseToPay($player, $parameter, "0,2");
      AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
      AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $player, "2", 1);
      break;
    case "UPR194": case "UPR195": case "UPR196":
      if(PlayerHasLessHealth($player)) GainHealth(1, $player);
      break;
    case "UPR203": case "UPR204": case "UPR205":
      ChooseToPay($player, $parameter, "0,1");
      AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
      break;
    case "UPR218": case "UPR219": case "UPR220":
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "UPR406":
      $deck = new Deck($player);
      if($deck->Reveal(3)) {
        $cards = explode(",", $deck->Top(amount:3));
        $numRed = 0;
        for($j = 0; $j < count($cards); ++$j) if(PitchValue($cards[$j]) == 1) ++$numRed;
        if($numRed > 0) DealArcane($numRed * 2, 2, "ABILITY", $combatChain[0], false, $player);
      }
      break;
    case "UPR407":
      $deck = new Deck($player);
      if($deck->Reveal(2)) {
        $cards = explode(",", $deck->Top(amount:2));
        $numRed = 0;
        for($j = 0; $j < count($cards); ++$j) if(PitchValue($cards[$j]) == 1) ++$numRed;
        if($numRed > 0) {
          $otherPlayer = ($player == 1 ? 2 : 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
          AddDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
          AddDecisionQueue("MODDEFCOUNTER", $otherPlayer, (-1 * $numRed), 1);
          AddDecisionQueue("DESTROYEQUIPDEF0", $player, "-", 1);
        }
      }
      break;
    case "UPR408":
      $deck = new Deck($player);
      if($deck->Reveal(1)) {
        if(PitchValue($deck->Top()) == 1) {
          $otherPlayer = ($player == 1 ? 2 : 1);
          AddDecisionQueue("SHOWHANDWRITELOG", $otherPlayer, "<-", 1);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("CHOOSETHEIRHAND", $player, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("MULTIBANISH", $otherPlayer, "HAND,-", 1);
        }
      }
      break;
    case "UPR409":
      DealArcane(1, 2, "PLAYCARD", $combatChain[0], false, $mainPlayer, true, true);
      DealArcane(1, 2, "PLAYCARD", $combatChain[0], false, $mainPlayer, true, false);
      break;
    case "DYN006":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("CHARREADYORPASS", $player, $index);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Beaten_Trackers_to_gain_an_action_point", 1);
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("GAINACTIONPOINTS", $player, 1, 1);
      AddDecisionQueue("WRITELOG", $player, "Player_" . $player . "_gained_an_action_point_from_" . CardLink("DYN006", "DYN006"), 1);
      break;
    case "DYN008":
      GainResources($player, 1);
      break;
    case "DYN009":
      $deck = new Deck($player);
      if($deck->Reveal() && ModifiedAttackValue($deck->Top(), $player, "DECK", source:"DYN009") >= 6) {
        Draw($player);
        WriteLog(CardLink($parameter, $parameter) . " drew a card");
      }
      break;
		case "DYN010": case "DYN011": case "DYN012":
      $index = SearchGetLastIndex(SearchMultizone($player, "MYDISCARD:cardID=" . $parameter));
      RemoveGraveyard($player, $index);
      $deck = new Deck($player);
      $deck->AddBottom($parameter, "GY");
      break;
    case "DYN093":
      $targetIndex = SearchItemsForUniqueID($target, $player);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_move_a_steam_counter_to_" . CardLink($items[$targetIndex], $items[$targetIndex]));
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $uniqueID . "," . $target, 1);
      AddDecisionQueue("SPECIFICCARD", $player, "PLASMAMAINLINE", 1);
      break;
		case "DYN101": case "DYN102": case "DYN103":
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS:isSameName=ARC036");
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a Hyper Driver to get a steam counter", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZADDCOUNTER", $player, "-", 1);
      break;
    case "ARC036": case "DYN110":
    case "DYN111": case "DYN112":
    case "EVO234":
      AddDecisionQueue("HYPERDRIVER", $player, $uniqueID, 1);
      break;
    case "DYN113": case "DYN114":
      AddDecisionQueue("DECKCARDS", $otherPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $player, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to sink <0>" , 1);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_sink_the_opponent's_card", 1);
      AddDecisionQueue("NOPASS", $player, $parameter, 1);
      AddDecisionQueue("WRITELOG", $player, "<b>Arakni</b> sunk the top card", 1);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
      AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
      AddDecisionQueue("ELSE", $player, "-");
      AddDecisionQueue("WRITELOG", $player, "<b>Arakni</b> left the top card there", 1);
      break;
    case "DTD133": case "DTD134":
        AddDecisionQueue("YESNO", $player, "if you want to pay 1 life for Vynnset");
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
        AddDecisionQueue("OP", $player, "LOSEHEALTH", 1);
        if(!SearchCurrentTurnEffects($parameter, $player)) { //The effect only apply to one event of damage. Anti-duplicate.
          AddDecisionQueue("ADDCURRENTEFFECT", $player, $parameter, 1);
        }
      break;
    case "DYN152":
      $deck = new Deck($player);
      if($deck->Reveal()) {
        if(CardSubType($deck->Top()) == "Arrow") {
          if(IsAllyAttacking()) {
            $allyIndex = "THEIRALLY-" . $combatChainState[$CCS_WeaponIndex];
            AddDecisionQueue("PASSPARAMETER", $player, $allyIndex, 1);
          }
          else AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRCHAR:type=C", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target to deal 1 damage");
          AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZDAMAGE", $player, "1,DAMAGE," . $parameter, 1);
          WriteLog(CardLink($parameter, $parameter) . " deals 1 damage");
        }
        else {
          WriteLog("The card was put on the bottom of your deck");
          $deck->AddBottom($deck->Top(remove:true), "DECK");
        }
      }
      break;
    case "DYN153":
      $deck = new Deck($player);
      if(!$deck->Empty() && !ArsenalFull($player)) AddArsenal($deck->Top(remove:true), $player, "DECK", "UP");
      break;
    case "DYN214":
      PlayAura("MON104", $player);
      break;
    case "DYN217":
      Draw($player);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "DYN244":
      Draw($player, false);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "OUT000":
      $rand = GetRandom(1, 3);
      switch($rand) {
        case 1: $auraCreated = "OUT236"; break;
        case 2: $auraCreated = "OUT235"; break;
        case 3: $auraCreated = "OUT234"; break;
        default: break;
      }
      WriteLog(CardLink("OUT000","OUT000") . " created a " . CardLink($auraCreated, $auraCreated));
      PlayAura($auraCreated, $otherPlayer);
      break;
    case "OUT091": case "OUT092":
      SuperReload();
      break;
    case "OUT097":
      $arsenal = &GetArsenal($player);
      AddDecisionQueue("YESNO", $player, "if you want to pay 1 to put an aim counter on the arrow");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PAYRESOURCES", $player, "1", 1);
      AddDecisionQueue("PASSPARAMETER", $player, count($arsenal)-ArsenalPieces(), 1);
      AddDecisionQueue("ADDAIMCOUNTER", $player, "-", 1);
      break;
    case "OUT099":
      LookAtTopCard($player, "OUT099");
      break;
    case "OUT102":
      AddCurrentTurnEffect($parameter, $mainPlayer);
      TrapTriggered($parameter);
      break;
    case "OUT103":
      $hand = &GetHand($mainPlayer);
      $numDraw = count($hand) - 1;
      DiscardHand($mainPlayer);
      for($i=0; $i<$numDraw; ++$i) Draw($mainPlayer);
      WriteLog("Attacker discarded their hand and drew $numDraw cards");
      TrapTriggered($parameter);
      break;
    case "OUT104":
      $deck = new Deck($mainPlayer);
      $topDeck = $deck->Top(remove:true);
      AddGraveyard($topDeck, $mainPlayer, "DECK");
      $numName = SearchCount(SearchMultizone($mainPlayer, "MYDISCARD:isSameName=" . $topDeck));
      LoseHealth($numName, $mainPlayer);
      WriteLog(Cardlink($topDeck, $topDeck) . " put into discard. Player $mainPlayer lost $numName life");
      TrapTriggered($parameter);
      break;
    case "OUT106":
      AddDecisionQueue("FINDINDICES", $mainPlayer, "EQUIP");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
      AddDecisionQueue("MODDEFCOUNTER", $mainPlayer, "-1", 1);
      WriteLog("Trap triggered and puts a -1 counter on an equipment");
      TrapTriggered($parameter);
      break;
    case "OUT107":
      $deck = new Deck($mainPlayer);
      $rv = "put  ";
      for($i=0; $i<2; ++$i)
      {
        $cardRemoved = $deck->Top(remove:true);
        AddGraveyard($cardRemoved, $mainPlayer, "DECK");
        if($i == 0) $rv .= Cardlink($cardRemoved, $cardRemoved);
        else $rv .= " and " . Cardlink($cardRemoved, $cardRemoved) . " into the graveyard";
      }
      WriteLog($rv);
      TrapTriggered($parameter);
      break;
    case "OUT108":
      AddCurrentTurnEffect($parameter, $mainPlayer);
      if(!IsAllyAttacking()) TrapTriggered($parameter);
      break;
    case "OUT171":
      PlayAura($CID_BloodRotPox, $mainPlayer);
      TrapTriggered($parameter);
      break;
    case "OUT172":
      PlayAura($CID_Frailty, $mainPlayer);
      TrapTriggered($parameter);
      break;
    case "OUT173":
      PlayAura($CID_Inertia, $mainPlayer);
      TrapTriggered($parameter);
      break;
    case "OUT174":
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
      AddDecisionQueue("BUTTONINPUT", $player, "0,1");
      AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
      AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $player, $parameter . "-BB", 1);
      AddDecisionQueue("PASSPARAMETER", $player, 1, 1);
      AddDecisionQueue("COMBATCHAINCHARACTERDEFENSEMODIFIER", $player, $target, 1);
      break;
      case "OUT185":
        MZMoveCard($mainPlayer, "MYDISCARD:type=A;maxCost=" . CachedTotalAttack() . "&MYDISCARD:type=AA;maxCost=" . CachedTotalAttack(), "MYTOPDECK", may:true);
      break;
    case "DTD000":
      $deck = new Deck($player);
      if($deck->Reveal() && PitchValue($deck->Top()) == 2)
      {
        AddDecisionQueue("YESNO", $player, "if_you_want_to_put_the_card_in_your_soul");
        AddDecisionQueue("NOPASS", $player, "-");
        AddDecisionQueue("PASSPARAMETER", $player, "MYDECK-0", 1);
        AddDecisionQueue("MZADDZONE", $player, "MYSOUL,DECK", 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
        AddDecisionQueue("WRITELOG", $player, "Added to soul by Light of Sol", 1);
      }
      break;
    case "DTD001": case "DTD002":
      MZMoveCard($player, "MYDECK:subtype=Figment", "MYPERMANENTS", may:true);
      AddDecisionQueue("PLAYABILITY", $player, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
      break;
    case "DTD047":
      Charge();
      break;
    case "DTD200":
      global $mainPlayer;
      Intimidate($mainPlayer);
      break;
    case $CID_BloodRotPox:
      $hand = &GetHand($player);
      $resources = &GetResources($player);
      if(Count($hand) > 0 || $resources[0] > 0)
      {
        AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_3_to_avoid_taking_2_damage", 0, 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "3", 1);
        AddDecisionQueue("PAYRESOURCES", $player, "3", 1);
        AddDecisionQueue("ELSE", $player, "-");
      }
      AddDecisionQueue("TAKEDAMAGE", $player, "2-OUT234", 1);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case $CID_Inertia:
      $deck = new Deck($player);
      WriteLog("Processing the end of turn effect of Inertia.");
      for ($i = 0; $i < count(GetArsenal($player)) + count(GetHand($player)); $i++) {
        BottomDeckMultizone($player, "MYHAND", "MYARS", true, "Choose a card from your hand or arsenal to add to the bottom of your deck");
      }
      AddDecisionQueue("WRITELOG", $player, ("The cards and arsenal of Player " . $player . " was put on the bottom of their deck."));
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case $CID_Frailty:
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "DTD233":
      global $CS_NextNAACardGoAgain;
      SetClassState($player, $CS_NextNAACardGoAgain, 1);
      DestroyAuraUniqueID($player, $uniqueID);
      break;
    case "DTD564":
      $banish = &GetBanish($player);
      for($i=count($banish)-BanishPieces(); $i>=0; $i-=BanishPieces()) {
        if($banish[$i+1] == "DTD564") { TurnBanishFaceDown($player, $i); break; }
      }
      break;
    case "TCC019": case "TCC022": case "TCC026":
      $deck = new Deck($player);
      if($deck->Reveal()) {
        if(!SubtypeContains($deck->Top(), "Evo", $player)) {
          WriteLog("The card was put on the bottom of your deck");
          $deck->AddBottom($deck->Top(remove:true), "DECK");
        }
      }
      break;
    case "TCC030":
      Draw($otherPlayer);
      break;
    case "TCC031":
      PlayAura("HVY242", $otherPlayer);
      break;
    case "TCC032":
      PlayAura("HVY241", $otherPlayer);
      break;
    case "TCC098": case "TCC102":
      BanishCardForPlayer("DYN065", $player, "-", "NT");
      break;
    case "TCC033":
      PlayAura("WTR225", $otherPlayer);
      break;//Quicken
    case "TCC060": case "TCC063": case "TCC076":
      ChooseToPay($player, $parameter, "0,3");
      AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "1", 1); // Technically wrong, it should be +1 for each opposing heroes
      break;
    case "EVO013":
    case "EVO105": case "EVO106": case "EVO107":
    case "EVO111": case "EVO112": case "EVO113":
    case "EVO114": case "EVO115": case "EVO116":
    case "EVO117": case "EVO118": case "EVO119":
    case "EVO120": case "EVO121": case "EVO122":
    case "EVO123": case "EVO124": case "EVO125":
    case "EVO141": //Galvanize
      MZChooseAndDestroy($player, "MYITEMS", may:true, context:"Choose an item to galvanize");
      AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
      break;
    case "EVO073":
    {
      AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose target equipment it cannot be activated until the end of its controller next turn");
      AddDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
      AddDecisionQueue("ADDSTASISTURNEFFECT", $otherPlayer, "EVO073-", 1);
    }
      break;
    case "HVY648":
      if(IsAllyAttacking()){
        WriteLog("<span style='color:red;'>No damage is dealt because there is no attacking hero when allies attack.</span>");
      }
      else {
        $index = FindCharacterIndex($player, "HVY648");
        CharacterChooseSubcard($player, $index, isMandatory:false);
        AddDecisionQueue("ADDDISCARD", $player, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRITEMS:minCost=0;maxCost=1", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose an item to gain control.", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZOP", $player, "GAINCONTROL", 1);
      }
      break;
    case "HVY001": case "HVY002":
      PlayAura("HVY241", $player); //Might
      break;
    case "HVY008":
      $num6Block = 0;
      for($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
        if(ModifiedAttackValue($combatChain[$i], $player, "CC", "HVY008") >= 6) ++$num6Block;
      }
      if($num6Block) {
        PlayAura("HVY241", $player);//Might
        WriteLog(CardLink("HVY008", "HVY008") . " created a " . CardLink("HVY241", "HVY241") . " token");
      }
      break;
    case "HVY020": case "HVY021": case "HVY022":
      $deck = new Deck($player);
      if($deck->Reveal() && ModifiedAttackValue($deck->Top(), $player, "DECK", source:$parameter) < 6) {
        $card = $deck->AddBottom($deck->Top(remove:true), "DECK");
        WriteLog(CardLink($parameter, $parameter) . " put " . CardLink($card, $card) . " on the bottom of your deck");
      }
      break;
    case "HVY052":
      Clash($parameter, effectController:$player);
      break;
    case "HVY053":
      AddCurrentTurnEffect("HVY053," . CachedTotalAttack(), $mainPlayer);
      break;
    case "HVY054":
      $yellowPitchCards = 0;
      for($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
        if(PitchValue($combatChain[$i]) == 2) ++$yellowPitchCards;
      }
      if($yellowPitchCards >= 2) {
        PutItemIntoPlayForPlayer("DYN243", $player, effectController:$player);
        WriteLog(CardLink("HVY054", "HVY054") . " created a Gold token");
      }
      break;
    case "HVY059":
      PutItemIntoPlayForPlayer("DYN243", $player, effectController:$player);
      WriteLog(CardLink($parameter, $parameter) . " created a Gold Token for Player ". $player);
      break;
    case "HVY061":
      Clash($parameter, effectController:$player);
      break;
    case "HVY077": case "HVY078": case "HVY079":
      PlayAura("HVY241", $player); //Vigor
      WriteLog(CardLink($parameter, $parameter) . " created a Might Token for Player ". $player);
      break;
    case "HVY080": case "HVY081": case "HVY082":
      PlayAura("HVY242", $player); //Vigor
      WriteLog(CardLink($parameter, $parameter) . " created a Vigor Token for Player ". $player);
      break;
    case "HVY104":
      AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRARS", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose which card you want to destroy from their arsenal", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZDESTROY", $player, false, 1);
      break;
    case "HVY016":
      $banish = &GetBanish($player);
      $hand = &GetHand($player);
      for($i = count($banish) - BanishPieces(); $i >= 0; $i -= BanishPieces()) {
        if($banish[$i+1] == "NOFEAR") {
          array_push($hand, $banish[$i]);
          RemoveBanish($player, $i);
        }
      }
      break;
    case "HVY142":
      if(CountAura("HVY241", $player) > 0) MZMoveCard($player, "MYDISCARD:type=AA", "MYTOPDECK", may:true);
      break;
    case "HVY161":
      if(IsAllyAttacking()) {
        WriteLog("<span style='color:red;'>No damage is dealt because there is no attacking hero when allies attack.</span>");
      }
      else if(CountAura("HVY240", $player) > 0) {
        WriteLog(CardLink($parameter, $parameter) . " deals 1 damage");
        DealDamageAsync($otherPlayer, 1, "DAMAGE", $parameter);
      }
      break;
    case "HVY181":
      if(CountAura("HVY242", $player) > 0) GainHealth(1, $player);
      break;
    case "HVY162":
    case "HVY137": case "HVY138": case "HVY139":
    case "HVY141":
    case "HVY157": case "HVY158": case "HVY159":
    case "HVY177": case "HVY178": case "HVY179":
    case "HVY182":
    case "HVY239":
      Clash($parameter, effectController:$player);
      break;
    case "HVY207":
      PlayAura("HVY240", $player);//Agility
      PlayAura("HVY241", $player);//Might
      PlayAura("HVY242", $player);//Vigor
      WriteLog(CardLink($parameter, $parameter) . " created an " . CardLink("HVY240", "HVY240") . ", " . CardLink("HVY241", "HVY241") . " and " . CardLink("HVY242", "HVY242") . " tokens.");
      break;
    case "HVY210":
      MZMoveCard($player, "MYARS", "MYBOTDECK", may:true, silent:true);
      AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
      AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $player, "2", 1);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
      break;
    case "AKO005":
      $index = FindCharacterIndex($player, $parameter);
      AddDecisionQueue("CHARREADYORPASS", $player, $index);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_Hiden_Tanner_to_gain_2_Might tokens", 1);
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("PLAYAURA", $player, "HVY241-2", 1);
      AddDecisionQueue("WRITELOG", $player, "Player_" . $player . "_gained_2_Might_tokens_from_" . CardLink("AKO005", "AKO005"), 1);
      break;
    case "MST027":
      AddDecisionQueue("YESNO", $player, "if you want " . CardLink("MST027", "MST027") . " to gain Ward 3");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("ADDCURRENTEFFECT", $player, "MERIDIANWARD", 1);    
      break;
    case "MST040": case "MST041": case "MST042":
      if(SearchPitchForColor($player, 3) > 0) PlayAura("MON104", $player);
      break;
    case "MST075":
      $index = GetCombatChainIndex($parameter, $player);
      $chainCard = $CombatChain->Card($index);
      $chainCard->ModifyDefense(3);
      break;
    case "MST137": case "MST138": case "MST139":
      AddCurrentTurnEffect($parameter, $player, "PLAY");
      break;
    case "MST140": case "MST141": case "MST142": 
      if(SearchAura($player, class:"ILLUSIONIST") < 0) PlayAura("MON104", $player, numAttackCounters:1);
      else PlayAura("MON104", $player);
      break;
    case "MST155": case "MST156": case "MST157":
      AddCurrentTurnEffect($parameter."-INST", $player, "PLAY");
      break;
    case "MST050":
      AddPlayerHand("DYN065", $player, $parameter);
      break;
    case "MST160":
      MZMoveCard($player, "MYDISCARD:comboOnly=true", "MYBOTDECK");
      break; 
    case "MST066":
      MZMoveCard($player, "MYDECK:isSameName=MST499", "MYHAND", may:true);
      AddDecisionQueue("SHUFFLEDECK", $player, "-");
      break;
    case "MST190":
      if(HasIncreasedAttack()) {
        AddCurrentTurnEffect($parameter, $otherPlayer);
      }
      break;
    case "AKO019": case "MST203": case "MST204": case "MST205":
      AddCurrentTurnEffect($parameter, $player, "CC");
      IncrementClassState($player, $CS_DamagePrevention, 1);
      break;  
    case "ASB003":
      Charge();
      AddDecisionQueue("ALLCARDPITCHORPASS", $player, "2", 1);
      AddDecisionQueue("DRAW", $player, "-", 1);
      break;   
    case "ASB006":
      Charge();
      AddDecisionQueue("ALLCARDPITCHORPASS", $player, "2", 1);
      AddDecisionQueue("PLAYAURA", $player, "WTR225-1", 1); // Quicken
      break;
    case "ROS033":
      AddCurrentTurnEffect($parameter, $player);
      break;
    default: break;
  }
}

function GetDQHelpText()
{
  global $dqState;
  if(count($dqState) < 5) return "-";
  return $dqState[4];
}

function FinalizeAction()
{
  global $currentPlayer, $mainPlayer, $actionPoints, $turn, $combatChain, $defPlayer, $makeBlockBackup, $mainPlayerGamestateStillBuilt;
  if(!$mainPlayerGamestateStillBuilt) UpdateGameState(1);
  BuildMainPlayerGamestate();
  if($turn[0] == "M") {
    if(count($combatChain) > 0) //Means we initiated a chain link
    {
      $turn[0] = "B";
      $currentPlayer = $defPlayer;
      $turn[2] = "";
      $makeBlockBackup = 1;
    } else {
      if($actionPoints > 0 || ShouldHoldPriority($mainPlayer)) {
        $turn[0] = "M";
        $currentPlayer = $mainPlayer;
        $turn[2] = "";
      } else {
        $currentPlayer = $mainPlayer;
        BeginTurnPass();
      }
    }
  } else if($turn[0] == "A") {
    $currentPlayer = $mainPlayer;
    $turn[2] = "";
  } else if($turn[0] == "D") {
    $turn[0] = "A";
    $currentPlayer = $mainPlayer;
    $turn[2] = "";
  } else if($turn[0] == "B") {
    $turn[0] = "B";
  }
  return 0;
}

function IsReactionPhase()
{
  global $turn, $dqState;
  if($turn[0] == "A" || $turn[0] == "D") return true;
  if(count($dqState) >= 2 && ($dqState[1] == "A" || $dqState[1] == "D")) return true;
  return false;
}

//Return whether priority should be held for the player by default/settings
function ShouldHoldPriority($player, $layerCard = "")
{
  global $mainPlayer;
  $prioritySetting = HoldPrioritySetting($player);
  if($prioritySetting == 0 || $prioritySetting == 1) return 1;
  if(($prioritySetting == 2 || $prioritySetting == 3) && $player != $mainPlayer) return 1;
  return 0;
}

function GiveAttackGoAgain()
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
}

function TopDeckToArsenal($player)
{
  $deck = new Deck($player);
  if(ArsenalFull($player) || $deck->Empty()) return;
  AddArsenal($deck->Top(remove:true), $player, "DECK", "DOWN");
}

function DiscardHand($player, $mainPhase=true)
{
  $hand = &GetHand($player);
  for($i = count($hand)-HandPieces(); $i>=0; $i-=HandPieces()) {
    DiscardCard($player, $i, mainPhase:$mainPhase);
  }
}

function Opt($cardID, $amount)
{
  global $currentPlayer;
  PlayerOpt($currentPlayer, $amount);
}

function PlayerOpt($player, $amount, $optKeyword = true)
{
  $char = GetPlayerCharacter($player);
  $heroID = ShiyanaCharacter($char[0]);
  $heroStatus = $char[1];
  AddDecisionQueue("FINDINDICES", $player, "DECKTOPXREMOVE," . $amount);
  AddDecisionQueue("OPT", $player, "<-", 1);
  if($heroID == "HER117" && $heroStatus < 3) AddDecisionQueue("BLAZE", $player, $amount, 1);
}

function BanishRandom($player, $source)
{
  $hand = &GetHand($player);
  if(count($hand) == 0) return "";
  $index = GetRandom() % count($hand);
  $banished = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  BanishCardForPlayer($banished, $player, "HAND");
  return $banished;
}

function DiscardRandom($player = "", $source = "", $effectController = "")
{
  global $currentPlayer;
  if($player == "") $player = $currentPlayer;
  if($effectController == "") $effectController = $currentPlayer;
  $hand = &GetHand($player);
  if(count($hand) == 0) return "";
  if(count($hand) > 1) $index = GetRandom(0, count($hand)-1);
  else $index = 0;
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  CardDiscarded($player, $discarded, $source);
  AddGraveyard($discarded, $player, "HAND", $effectController);
  DiscardedAtRandomEffects($player, $discarded, $source);
  return $discarded;
}

function DiscardedAtRandomEffects($player, $discarded, $source) {
  global $mainPlayer;
  if(SearchCurrentTurnEffects("DYN009", $player) && ModifiedAttackValue($discarded, $player, "GY", "HAND") >= 6) {
    $index = SearchGetFirstIndex(SearchMultizone($player, "MYDISCARD:cardID=" . $discarded));
    RemoveGraveyard($player, $index);
    BanishCardForPlayer($discarded, $player, "GY", "-", $player);
    AddLayer("TRIGGER", $player, "DYN009");
  }
  $character = GetPlayerCharacter($player);
  $index = FindCharacterIndex($player, "DYN006");
  if($index >= 0 && IsCharacterAbilityActive($player, $index, checkGem:true) && $player == $mainPlayer && ModifiedAttackValue($discarded, $player, "GY", "HAND") >= 6) {
    AddLayer("TRIGGER", $player, $character[$index]);
  }
  $index = FindCharacterIndex($player, "AKO005");
  if($index >= 0 && IsCharacterAbilityActive($player, $index, checkGem:true) && $player == $mainPlayer && ModifiedAttackValue($discarded, $player, "GY", "HAND") >= 6) {
    AddLayer("TRIGGER", $player, $character[$index]);
  }
  switch($discarded) {
    case "DYN008": AddLayer("TRIGGER", $player, $discarded); break;
    case "DYN010": case "DYN011": case "DYN012": AddLayer("TRIGGER", $player, $discarded); break;
    default: break;
  }
}

function DiscardCard($player, $index, $source="", $effectController="", $mainPhase=true)
{
  $hand = &GetHand($player);
  $discarded = RemoveHand($player, $index);
  CardDiscarded($player, $discarded, $source, mainPhase:$mainPhase);
  AddGraveyard($discarded, $player, "HAND", $effectController);
  return $discarded;
}

function CardDiscarded($player, $discarded, $source = "", $mainPhase = true)
{
  global $CS_Num6PowDisc, $mainPlayer, $layers;
  AddEvent("DISCARD", $discarded);
  $modifiedAttack = ModifiedAttackValue($discarded, $player, "HAND", $source);
  if($modifiedAttack >= 6) {
    $character = &GetPlayerCharacter($player);
    $characterID = ShiyanaCharacter($character[0]);
    if(($characterID == "WTR001" || $characterID == "WTR002" || $characterID == "RVD001") && $character[1] == 2 && $player == $mainPlayer && $mainPhase) { //Rhinar
      AddLayer("TRIGGER", $mainPlayer, $character[0]);
    }
    else if(($characterID == "HVY001" || $characterID == "HVY002") && $character[1] == 2 && $player == $mainPlayer && $mainPhase) { //Kayo, Armed and Dangerous
      AddLayer("TRIGGER", $mainPlayer, $character[0]);
      $character[1] = 1;
    }
    IncrementClassState($player, $CS_Num6PowDisc);
  }
  if($discarded == "CRU008" && $source != "" && ClassContains($source, "BRUTE", $mainPlayer) && CardType($source) == "AA") {
    WriteLog(CardLink("CRU008", "CRU008") . " intimidated because it was discarded by a Brute attack action card.");
    AddLayer("TRIGGER", $mainPlayer, $discarded);
  }
  WriteLog(CardLink($discarded, $discarded). " was discarded");
}

function ModifiedAttackValue($cardID, $player, $from, $source="")
{
  global $combatChainState, $CS_Num6PowBan;
  if($cardID == "") return -1;
  $attack = AttackValue($cardID);
  if($cardID == "MON191") return SearchPitchForNumCosts($player) * 2;
  else if($cardID == "EVR138") return FractalReplicationStats("Attack");
  else if($cardID == "DYN216") return CountAura("MON104", $player);
  else if($cardID == "DTD107") return GetClassState($player, $CS_Num6PowBan) > 0 ? 6 : 0;
  else if($cardID == "DYN492b") return SearchCurrentTurnEffects("DYN089-UNDER", $player) > 0 ? 6 : 5;
  if($from != "CC") {
    $char = &GetPlayerCharacter($player);
    $characterID = ShiyanaCharacter($char[0]);
    if(($characterID == "HVY001" || $characterID == "HVY002") && $char[1] < 3 && CardType($cardID) == "AA") ++$attack;
  }
  else {
    // effect that only affect CC
    $attack += EffectDefenderAttackModifiers($cardID);
  }
  $attack += ItemsAttackModifiers($cardID, $player, $from);
  return $attack;
}

function Intimidate($player="")
{
  global $currentPlayer, $defPlayer, $CS_HaveIntimidated;
  IncrementClassState($currentPlayer, $CS_HaveIntimidated);
  if (!ShouldAutotargetOpponent($currentPlayer) && $player == "") {
    AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=C&THEIRCHAR:type=C", 1);
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose hero to intimidate.", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
    AddDecisionQueue("INTIMIDATE", $currentPlayer, "-" , 1);
    return;
  }

  if ($player != "") AddDecisionQueue("INTIMIDATE", $currentPlayer, $player , 1);
  else AddDecisionQueue("INTIMIDATE", $currentPlayer, $defPlayer , 1);
}

function DestroyFrozenArsenal($player)
{
  $arsenal = &GetArsenal($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  for($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    if($arsenal[$i + 4] == "1") {
      DestroyArsenal($player, effectController:$otherPlayer);
    }
  }
}

function CanGainAttack($cardID)
{
  global $combatChain, $mainPlayer;
  if(SearchCurrentTurnEffects("OUT102", $mainPlayer)) return false;
  return !SearchCurrentTurnEffects("CRU035", $mainPlayer) || CardType($combatChain[0]) != "AA";
}

function IsWeaponGreaterThanTwiceBasePower()
{
  global $combatChain, $mainPlayer, $CS_NumCharged, $CS_NumYellowPutSoul;
  if(count($combatChain) == 0) return false;
  if(TypeContains($combatChain[0], "W", $mainPlayer) && CachedTotalAttack() > (AttackValue($combatChain[0]) * 2)) return true;
  $char = &GetPlayerCharacter($mainPlayer);
  if($char[CharacterPieces()] == "MON031" && GetClassState($mainPlayer, $CS_NumCharged) > 0) return true;
  if($char[CharacterPieces()] == "DTD046" && GetClassState($mainPlayer, $CS_NumYellowPutSoul) > 0) return true;
  return false;
}

function HasEnergyCounters($array, $index)
{
  switch($array[$index]) {
    case "WTR150": case "UPR166": case "HER117": return $array[$index+2] > 0;
    default: return false;
  }
}

function HasAttackCounters($zone, $array, $index)
{
  switch($zone) {
    case "AURAS": return $array[$index+3] > 0;
    default: return false;
  }
}

function IsEnergyCounters($cardID){
  switch($cardID) {
    case "WTR150": case "UPR166": case "HER117": return true;
    default: return false;
  }
}

function HasHauntCounters($cardID){
  switch($cardID) {
    case "UPR151": return true;
    default: return false;
  }
}

function HasVerseCounters($cardID){
  switch($cardID) {
    case "EVR107": case "EVR108": case "EVR109": return true;
    default: return false;
  }
}

function HasDoomCounters($cardID){
  switch($cardID) {
    case "DYN175": case "DTD170": return true;
    default: return false;
  }
}

function HasRustCounters($cardID){
  switch($cardID) {
    case "CRU177": return true;
    default: return false;
  }
}

function HasFlowCounters($cardID){
  switch($cardID) {
    case "ELE117": case "ELE146": case "ELE175": 
    case "UPR138":
    case "ROS033":
      return true;
    default: return false;
  }
}

function HasFrostCounters($cardID){
  switch($cardID) {
    case "UPR140":
      return true;
    default: return false;
  }
}

function HasBalanceCounters($cardID){
  switch($cardID) {
    case "CRU075":
      return true;
    default: return false;
  }
}

function HasBindCounters($cardID){
  switch($cardID) {
    case "ELE224": return true;
    default: return false;
  }
}

function HasSteamCounter($array, $index, $player)
{
  if (CardType($array[$index]) == 'E') return EquipmentsUsingSteamCounter($array[$index]);
  if (ClassContains($array[$index], "MECHANOLOGIST", $player)) {
    if($array[$index] == "DYN492a") return false;
    if (CardType($array[$index]) == 'W') return $array[$index+2] > 0;
    if (SubtypeContains($array[$index], "Item", $player)) return $array[$index+1] > 0;
  }
  return false;
}

function IsHeroActive($player) {
  $char = &GetPlayerCharacter($player);
  if ($char[1] == 2) return true;
  return false;
}
