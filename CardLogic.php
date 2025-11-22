<?php

include "CardDictionary.php";
include "CoreLogic.php";

function PummelHit($player = -1, $passable = false, $fromDQ = false, $context = "Choose a card to discard", $effectController="-")
{
  global $defPlayer;
  if ($player == -1) $player = $defPlayer;
  if ($fromDQ) {
    if ($effectController == "-") PrependDecisionQueue("DISCARDCARD", $player, "HAND", 1);
    else PrependDecisionQueue("DISCARDCARD", $player, "HAND-$effectController", 1);
    PrependDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
    if ($passable) PrependDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
    else PrependDecisionQueue("CHOOSEHAND", $player, "<-", 1);
    PrependDecisionQueue("SETDQCONTEXT", $player, $context, 1);
    PrependDecisionQueue("FINDINDICES", $player, "HAND", ($passable ? 1 : 0));
  } else {
    AddDecisionQueue("FINDINDICES", $player, "HAND", ($passable ? 1 : 0));
    AddDecisionQueue("SETDQCONTEXT", $player, $context, 1);
    if ($passable) AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
    if ($effectController == "-") AddDecisionQueue("DISCARDCARD", $player, "HAND", 1);
    else AddDecisionQueue("DISCARDCARD", $player, "HAND-$effectController", 1);
  }
}

function HandToTopDeck($player)
{
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
  AddDecisionQueue("MULTIADDTOPDECK", $player, "-", 1);
}

function BottomDeck($player = "", $mayAbility = false, $shouldDraw = false)
{
  global $currentPlayer;
  if ($player == "") $player = $currentPlayer;
  AddDecisionQueue("FINDINDICES", $player, "HAND");
  AddDecisionQueue("SETDQCONTEXT", $player, "Put_a_card_from_your_hand_on_the_bottom_of_your_deck.");
  if ($mayAbility) AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
  else AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
  AddDecisionQueue("REMOVEMYHAND", $player, "-", 1);
  AddDecisionQueue("ADDBOTDECK", $player, "-", 1);
  if ($shouldDraw) AddDecisionQueue("DRAW", $player, "-", 1);
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
  if (IsLayerStep()) {
    AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
  } else if (count($combatChain) > 0) AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
  else AddCurrentTurnEffect($cardID, $player, $from, $uniqueID);
}

function AddCurrentTurnEffect($cardID, $player, $from = "", $uniqueID = -1)
{
  global $currentTurnEffects, $combatChain;
  if ($cardID == "BEATCHEST") {
    CharacterBeatChestTrigger($player);
    if (SearchCurrentTurnEffects("BEATCHEST", $player)) { // don't duplicate the icon
      return;
    }
  }
  $card = explode("-", $cardID)[0];
  if (CardType($card) == "A" && !CanPlayAsInstant($cardID, -1, $from) && count($combatChain) > 0 && IsCombatEffectActive($cardID) && !IsCombatEffectPersistent($cardID) && $from != "PLAY") {
    AddCurrentTurnEffectFromCombat($cardID, $player, $uniqueID);
    return;
  }
  array_push($currentTurnEffects, $cardID);
  array_push($currentTurnEffects, $player);
  array_push($currentTurnEffects, $uniqueID);
  array_push($currentTurnEffects, CurrentTurnEffectUses($cardID));
}

function AddEffectToCurrentAttack($cardID) {
  global $combatChain;
  if ($combatChain[10] == "-") $combatChain[10] = ConvertToSetID($cardID); //saving them as set ids saves space
  else $combatChain[10] .= "," . ConvertToSetID($cardID);
}

function AddEffectToPastAttack($index, $cardID) {
  global $chainLinks;
  if ($chainLinks[$index][6] == "-") $chainLinks[$index][6] = ConvertToSetID($cardID);
  else $chainLinks[$index][6] .= ConvertToSetID($cardID);
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
  $effectID = ExtractCardID($cardID);
  switch ($effectID) {
    case "steadfast_red":
      return 6;
    case "steadfast_yellow":
      return 5;
    case "steadfast_blue":
      return 4;
    case "blood_of_the_dracai_red":
      return 3;
    case "uprising_red":
      return 4;
    case "oasis_respite_red":
      return 4;
    case "oasis_respite_yellow":
      return 3;
    case "oasis_respite_blue":
      return 2;
    case "art_of_the_dragon_blood_red":
      return 3;
    case "shelter_from_the_storm_red":
    case "calming_breeze_red":
      return 3;
    case "give_no_quarter_blue":
      return 2;
    case "light_up_the_leaves_red":
      return 6;
    default:
      return 1;
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
  if (count($combatChain) == 0 || $currentTurnEffects[$index + 2] == -1) return false;
  if ($currentTurnEffects[$index] == "horrors_of_the_past_yellow") return false;
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

function PopLayer()
{
  global $layers;
  for ($i=0; $i < LayerPieces(); $i++) { 
    array_pop($layers);
  }
  return count($layers);//How far it is from the end
}

function AddLayer($cardID, $player, $parameter, $target = "-", $additionalCosts = "-", $uniqueID = "-", $layerUID = "-", $skipOrdering = false)
{
  global $layers, $dqState;
  $layerUID = $layerUID == "-" ? GetUniqueId($cardID, $player) : $layerUID;
  $skipOrdering = in_array($parameter, ["runechant", "seismic_surge"]) || $skipOrdering;
  if ($cardID == "TRIGGER" && !$skipOrdering) { // put triggers into "pre-layers" where they can be ordered
    array_unshift($layers, $layerUID);
    array_unshift($layers, $uniqueID);
    array_unshift($layers, $additionalCosts);
    array_unshift($layers, $target);
    array_unshift($layers, $parameter);
    array_unshift($layers, $player);
    array_unshift($layers, "PRETRIGGER");
  }
  else {
    //Layers are on a stack, so you need to push things on in reverse order
    array_unshift($layers, $layerUID);
    array_unshift($layers, $uniqueID);
    array_unshift($layers, $additionalCosts);
    array_unshift($layers, $target);
    array_unshift($layers, $parameter);
    array_unshift($layers, $player);
    array_unshift($layers, $cardID);
  }

  return count($layers);//How far it is from the end
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

function IsDecisionQueueActive()
{
  global $dqState;
  return $dqState[0] == "1";
}

function ProcessDecisionQueue()
{
  global $turn, $dqState;
  if ($dqState[0] != "1") {
    if (count($turn) < 3) $turn[2] = "-";
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

function CloseDecisionQueue($skip=false)
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
  if(!$skip) $dqState[8] = "-1"; //Orderable index (what layer after which triggers can be reordered)
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
  if ($layers[count($layers) - LayerPieces()] == "ENDPHASE") return false;
  if ($layers[count($layers) - LayerPieces()] == "STARTTURN") return false;
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

function AddTriggersToStack()
{
  global $layers, $mainPlayer, $defPlayer;
  $preLayers = GetPreLayers();
  if (count($preLayers) > 0) {
    $mainPreLayers = 0;
    $defPreLayers = 0;
    for ($i = 0; $i < count($preLayers); $i += LayerPieces()) {
      if ($preLayers[$i+1] == $mainPlayer) ++$mainPreLayers;
      else ++$defPreLayers;
    }
    if ($mainPreLayers > 0 && $defPreLayers > 0 && HoldPrioritySetting($mainPlayer) != 4 && !IsPlayerAI($mainPlayer)) {
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Whose triggers do you want to resolve first?");
      AddDecisionQueue("BUTTONINPUT", $mainPlayer, "Mine,Theirs", 1);
    }
    else AddDecisionQueue("PASSPARAMETER", $mainPlayer, "Theirs");
    AddDecisionQueue("TRIGGERORDERING", $mainPlayer, "-", 1);
  }
}

//Must be called with the my/their context
function ContinueDecisionQueue($lastResult = "")
{
  global $decisionQueue, $turn, $currentPlayer, $makeCheckpoint, $otherPlayer;
  global $layers, $layerPriority, $dqVars, $dqState, $CS_AbilityIndex, $CS_AdditionalCosts, $mainPlayer, $CS_LayerPlayIndex;
  global $CS_ResolvingLayerUniqueID, $makeBlockBackup, $defPlayer;
  
  for ($player = 1; $player < 3; ++$player) {
    $health = &GetHealth($player);
    if ($health <= 0 && !IsGameOver()) {
      PlayerWon(($player == 1 ? 2 : 1));
    }
  }

  if (count($decisionQueue) == 0 || IsGamePhase($decisionQueue[0])) {
    if (count($decisionQueue) > 0 && $currentPlayer != $decisionQueue[1]) {
    }
    $preLayers = GetPreLayers();
    if (count($decisionQueue) == 0 && count($preLayers) > 0) {
      AddTriggersToStack();
      ProcessDecisionQueue();
      return;
    }
    if (count($decisionQueue) == 0 && count($layers) > 0) {
      $priorityHeld = 0;
      if ($mainPlayer == 1) {
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
        $layerUniqueID = array_shift($layers);
        SetClassState($player, $CS_ResolvingLayerUniqueID, $layerUniqueID);
        $params = explode("|", $parameter);
        if ($currentPlayer != $player) {
          $currentPlayer = $player;
          $otherPlayer = $currentPlayer == 1 ? 2 : 1;
          BuildMyGamestate($currentPlayer);
        }
        $layerPriority[0] = ShouldHoldPriority(1);
        $layerPriority[1] = ShouldHoldPriority(2);

        if ($cardID == "ENDTURN") EndStep();
        else if ($cardID == "ENDPHASE") FinishTurnPass();
        else if ($cardID == "RESUMETURN") $turn[0] = "M";
        else if ($cardID == "LAYER") ProcessLayer($player, $parameter, $target, $additionalCosts);
        else if ($cardID == "FINALIZECHAINLINK") FinalizeChainLink($parameter);
        else if ($cardID == "RESOLUTIONSTEP") {
          ResetCombatChainState();
          ProcessDecisionQueue();
        }
        else if ($cardID == "CLOSINGCHAIN") {
          WriteLog("I didn't think this code was reachable, please submit a bug report");
          ResetCombatChainState();
          ProcessDecisionQueue();
        }
        else if ($cardID == "ATTACKSTEP") {
          $turn[0] = "B";
          $currentPlayer = $defPlayer;
          $makeBlockBackup = 1;
        } else if ($cardID == "DEFENDSTEP") {
          $turn[0] = "A";
          $currentPlayer = $mainPlayer;
          BeginningReactionStepEffects();
          ProcessDecisionQueue();
        } else if ($cardID == "TRIGGER") {
          ProcessTrigger($player, $parameter, $uniqueID, $target, $additionalCosts, $params[0]);
          ProcessDecisionQueue();
        }
        else if ($cardID == "PRETRIGGER") {
          WriteLog("This block should not have been reached, please submit a bug report");
          ProcessTrigger($player, $parameter, $uniqueID, $target, $additionalCosts, $params[0]);
          ProcessDecisionQueue();
        }
        else if ($cardID == "MELD") {
          ProcessMeld($player, $parameter, $cardID, target:$target);
          ProcessDecisionQueue();
        }
        else if ($cardID == "ABILITY") {
          ProcessAbility($player, $parameter, $uniqueID, $target, $additionalCosts, $params[0]);
          ProcessDecisionQueue();
        }
        else if ($cardID == "STARTTURN") {
          StartActionPhaseAbilities();
          ProcessDecisionQueue();
        }
        else {
          SetClassState($player, $CS_AbilityIndex, isset($params[2]) ? $params[2] : "-"); //This is like a parameter to PlayCardEffect and other functions
          PlayCardEffect($cardID, $params[0], isset($params[1]) ? $params[1] : 0, $target, $additionalCosts, isset($params[3]) ? $params[3] : "-1", isset($params[2]) ? $params[2] : -1);
          ClearDieRoll($player);
        }
        //main player should hold priority in resolution step always
        if (count($layers) == LayerPieces() && $layers[0] == "RESOLUTIONSTEP") $layerPriority[$mainPlayer - 1] = "1";
      }
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPLAY") {
      if ($currentPlayer != $decisionQueue[1]) {
        $currentPlayer = $decisionQueue[1];
        $otherPlayer = $currentPlayer == 1 ? 2 : 1;
        BuildMyGamestate($currentPlayer);
      }
      $params = explode("|", $decisionQueue[2]);
      CloseDecisionQueue(true);
      if ($params[2] == "") $params[2] = 0;
      if ($turn[0] == "B" && count($layers) == 0) { //If a layer is not created
        PlayCardEffect($params[0], $params[1], $params[2], "-", $params[3], $params[4]);
      } else {
        //params 3 = ability index
        //params 4 = Unique ID
        $additionalCosts = GetClassState($currentPlayer, $CS_AdditionalCosts);
        if ($additionalCosts == "") $additionalCosts = "-";
        $layerIndex = count($layers) - GetClassState($currentPlayer, $CS_LayerPlayIndex);
        if ($layers[$layerIndex] != "ABILITY") $layers[$layerIndex + 2] = $params[1] . "|" . $params[2] . "|" . $params[3] . "|" . $params[4] . "|" . $params[5];
        $layers[$layerIndex + 4] = $additionalCosts;
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
      PlayCard($params[0], $params[1], $lastResult, $params[2], isset($params[3]) ? $params[3] : -1, isset($params[4]) ? $params[4] : -1);
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECHAINLINK") {
      CloseDecisionQueue();
      ResolveChainLink();
    } else if (count($decisionQueue) > 0 && $decisionQueue[0] == "RESOLVECOMBATDAMAGE") {
      $parameters = explode(",", $decisionQueue[2]);
      if ($parameters[0] != "-") $damageDone = $parameters[0];
      else $damageDone = $dqState[6];
      CloseDecisionQueue();
      if(!IsGameOver()) ResolveCombatDamage($damageDone, $parameters[1]);
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
  if (count($dqVars) > 0) {
    if (str_contains($parameter, "{0}")) $parameter = str_replace("{0}", $dqVars[0], $parameter);
    if (str_contains($parameter, "<0>")) $parameter = str_replace("<0>", CardLink($dqVars[0], $dqVars[0]), $parameter);
    if (count($dqVars) > 1 && str_contains($parameter, "{1}")) $parameter = str_replace("{1}", $dqVars[1], $parameter);
    if (count($dqVars) > 2 && str_contains($parameter, "{2}")) $parameter = str_replace("{2}", $dqVars[2], $parameter);
  }
  if (count($dqVars) > 1) {
    $parameter = str_replace("<1>", CardLink($dqVars[1], $dqVars[1]), $parameter);
  }  
  if (count($dqVars) > 2) {
    $parameter = str_replace("<2>", CardLink($dqVars[2], $dqVars[2]), $parameter);
  }  
  $subsequent = array_shift($decisionQueue);
  $makeCheckpoint = array_shift($decisionQueue);
  if (count($layers) > 0 && $layers[0] == "RESOLUTIONSTEP" && $player == $mainPlayer && $phase == "INSTANT") {
    // turn player can play actions in the resolution step
    // still a little buggy, chain closes if turn player passes here
    $phase = "M";
  }
  $turn[0] = $phase;
  $turn[1] = $player;
  $currentPlayer = $player;
  $turn[2] = ($parameter == "<-" ? $lastResult : $parameter);
  $return = "PASS";
  if ($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, $parameter == "<-" ? $lastResult : $parameter, $lastResult);
  if ($parameter == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS"; //Collapse the rest of the queue if this decision point has invalid parameters
  if (is_array($return) || strval($return) != "NOTSTATIC") {
    if ($phase != "SETDQCONTEXT") $dqState[4] = "-"; //Clear out context for static states -- context only persists for one choice
    ContinueDecisionQueue($return);
  } else {
  }
}

function ProcessLayer($player, $parameter, $target = "-", $additionalCosts = "-", $uniqueID = "-")
{
  switch ($parameter) {
    case "PHANTASM":
      PhantasmLayer($additionalCosts);
      break;
    case "MIRAGE":
      MirageLayer();
      break;
    default:
      break;
  }
}

// For "when this is played" triggers
function CardPlayTrigger($cardID, $from)
{
  global $mainPlayer;
  switch ($cardID) {
      case "scar_for_a_scar_red":
      case "scar_for_a_scar_yellow":
      case "scar_for_a_scar_blue":
      case "life_for_a_life_red":
      case "life_for_a_life_yellow":
      case "life_for_a_life_blue":
      case "blow_for_a_blow_red":
      case "pound_for_pound_red":
      case "pound_for_pound_yellow":
      case "pound_for_pound_blue":
      case "adrenaline_rush_red":
      case "adrenaline_rush_yellow":
      case "adrenaline_rush_blue":
      case "wounded_bull_red":
      case "wounded_bull_yellow":
      case "wounded_bull_blue":
        AddLayer("TRIGGER", $mainPlayer, $cardID);
        break;
      default:
        break;
    }
}

function AddOnHitTrigger($cardID, $uniqueID = -1, $source = "-", $targetPlayer = "-", $check = false): bool
{
  global $mainPlayer, $combatChain, $layers, $CS_NumAuras, $CS_NumCharged, $CS_SuspensePoppedThisTurn, $CS_HitsWDawnblade;
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  // Can this check be generalized to use a function to check for all hit prevention effects?
  if (CardType($cardID) == "AA" && (SearchAuras("stamp_authority_blue", 1) || SearchAuras("stamp_authority_blue", 2))) return false;
  if (CardType($cardID) == "AA" && SearchCurrentTurnEffects("gallow_end_of_the_line_yellow", $mainPlayer)) return false;
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") return $card->AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check);
  switch ($cardID) {
    case "snatch_red":
    case "snatch_yellow":
    case "snatch_blue":
    case "pedal_to_the_metal_red":
    case "pedal_to_the_metal_yellow":
    case "pedal_to_the_metal_blue":
    case "cognition_nodes_blue":
    case "over_loop_red":
    case "over_loop_yellow":
    case "over_loop_blue":
    case "red_in_the_ledger_red":
    case "endless_arrow_red":
    case "hamstring_shot_red":
    case "hamstring_shot_yellow":
    case "hamstring_shot_blue":
    case "salvage_shot_red":
    case "salvage_shot_yellow":
    case "salvage_shot_blue":
    case "nebula_blade":
    case "arknight_ascendancy_red":
    case "life_for_a_life_red":
    case "life_for_a_life_yellow":
    case "life_for_a_life_blue":
    case "pursuit_of_knowledge_blue":
    case "cadaverous_contraband_red":
    case "cadaverous_contraband_yellow":
    case "cadaverous_contraband_blue":
    case "fervent_forerunner_red":
    case "fervent_forerunner_yellow":
    case "fervent_forerunner_blue":
    case "moon_wish_red":
    case "moon_wish_yellow":
    case "moon_wish_blue":
    case "rifting_red":
    case "rifting_yellow":
    case "rifting_blue":
    case "soulbead_strike_red":
    case "soulbead_strike_yellow":
    case "soulbead_strike_blue":
    case "torrent_of_tempo_red":
    case "torrent_of_tempo_yellow":
    case "torrent_of_tempo_blue":
    case "bittering_thorns_yellow":
    case "whirling_mist_blossom_yellow":
    case "high_speed_impact_red":
    case "high_speed_impact_yellow":
    case "high_speed_impact_blue":
    case "combustible_courier_red":
    case "combustible_courier_yellow":
    case "combustible_courier_blue":
    case "remorseless_red":
    case "pathing_helix_red":
    case "pathing_helix_yellow":
    case "pathing_helix_blue":
    case "sleep_dart_red":
    case "sleep_dart_yellow":
    case "sleep_dart_blue":
    case "dread_triptych_blue":
    case "consuming_volition_red":
    case "consuming_volition_yellow":
    case "consuming_volition_blue":
    case "meat_and_greet_red":
    case "meat_and_greet_yellow":
    case "meat_and_greet_blue":
    case "coax_a_commotion_red":
    case "promise_of_plenty_red":
    case "promise_of_plenty_yellow":
    case "promise_of_plenty_blue":
    case "herald_of_erudition_yellow":
    case "herald_of_judgment_yellow":
    case "herald_of_triumph_red":
    case "herald_of_triumph_yellow":
    case "herald_of_triumph_blue":
    case "herald_of_protection_red":
    case "herald_of_protection_yellow":
    case "herald_of_protection_blue":
    case "herald_of_ravages_red":
    case "herald_of_ravages_yellow":
    case "herald_of_ravages_blue":
    case "herald_of_rebirth_red":
    case "herald_of_rebirth_yellow":
    case "herald_of_rebirth_blue":
    case "herald_of_tenacity_red":
    case "herald_of_tenacity_yellow":
    case "herald_of_tenacity_blue":
    case "wartune_herald_red":
    case "wartune_herald_yellow":
    case "wartune_herald_blue":
    case "galaxxi_black":
    case "nourishing_emptiness_red":
    case "brandish_red":
    case "brandish_yellow":
    case "brandish_blue":
    case "overload_red":
    case "overload_yellow":
    case "overload_blue":
    case "illuminate_red":
    case "illuminate_yellow":
    case "illuminate_blue":
    case "rising_solartide_red":
    case "rising_solartide_yellow":
    case "rising_solartide_blue":
    case "soul_harvest_blue":
    case "lunartide_plunderer_red":
    case "lunartide_plunderer_yellow":
    case "lunartide_plunderer_blue":
    case "oldhim_grandfather_of_eternity":
    case "oldhim":
    case "endless_winter_red":
    case "entangle_red":
    case "entangle_yellow":
    case "entangle_blue":
    case "awakening_blue":
    case "tear_asunder_blue":
    case "embolden_red":
    case "embolden_yellow":
    case "embolden_blue":
    case "light_it_up_yellow":
    case "frost_fang_red":
    case "frost_fang_yellow":
    case "frost_fang_blue":
    case "icy_encounter_red":
    case "icy_encounter_yellow":
    case "icy_encounter_blue":
    case "pulverize_red":
    case "spring_tidings_yellow":
    case "ride_the_tailwind_red":
    case "ride_the_tailwind_yellow":
    case "ride_the_tailwind_blue":
    case "battering_bolt_red":
    case "fatigue_shot_red":
    case "fatigue_shot_yellow":
    case "fatigue_shot_blue":
    case "timidity_point_red":
    case "timidity_point_yellow":
    case "timidity_point_blue":
    case "drowning_dire_red":
    case "drowning_dire_yellow":
    case "drowning_dire_blue":
    case "reek_of_corruption_red":
    case "reek_of_corruption_yellow":
    case "reek_of_corruption_blue":
    case "fractal_replication_red":
    case "bingo_red":
    case "dustup_red":
    case "dustup_yellow":
    case "dustup_blue":
    case "kyloria":
    case "nekria":
    case "vynserakai":
    case "phoenix_form_red":
    case "engulfing_flamewave_red":
    case "engulfing_flamewave_yellow":
    case "engulfing_flamewave_blue":
    case "mounting_anger_red":
    case "mounting_anger_yellow":
    case "mounting_anger_blue":
    case "rising_resentment_red":
    case "rising_resentment_yellow":
    case "rising_resentment_blue":
    case "soaring_strike_red":
    case "soaring_strike_yellow":
    case "soaring_strike_blue":
    case "take_the_tempo_red":
    case "liquefy_red":
    case "stoke_the_flames_red":
    case "erase_face_red":
    case "vipox_red":
    case "flex_claws_red":
    case "flex_claws_yellow":
    case "flex_claws_blue":
    case "jubeel_spellbane":
    case "urgent_delivery_red":
    case "urgent_delivery_yellow":
    case "urgent_delivery_blue":
    case "heat_seeker_red":
    case "immobilizing_shot_red":
    case "drill_shot_red":
    case "drill_shot_yellow":
    case "drill_shot_blue":
    case "hemorrhage_bore_red":
    case "hemorrhage_bore_yellow":
    case "hemorrhage_bore_blue":
    case "infiltrate_red":
    case "infect_red":
    case "infect_yellow":
    case "infect_blue":
    case "sedate_red":
    case "sedate_yellow":
    case "sedate_blue":
    case "wither_red":
    case "wither_yellow":
    case "wither_blue":
    case "wander_with_purpose_yellow":
    case "be_like_water_red":
    case "be_like_water_yellow":
    case "be_like_water_blue":
    case "deadly_duo_red":
    case "deadly_duo_yellow":
    case "deadly_duo_blue":
    case "barbed_undertow_red":
    case "infecting_shot_red":
    case "infecting_shot_yellow":
    case "infecting_shot_blue":
    case "sedation_shot_red":
    case "sedation_shot_yellow":
    case "sedation_shot_blue":
    case "withering_shot_red":
    case "withering_shot_yellow":
    case "withering_shot_blue":
    case "plunge_red":
    case "plunge_yellow":
    case "plunge_blue":
    case "death_touch_red":
    case "death_touch_yellow":
    case "death_touch_blue":
    case "amnesia_red":
    case "humble_red":
    case "humble_yellow":
    case "humble_blue":
    case "cut_down_to_size_red":
    case "cut_down_to_size_yellow":
    case "cut_down_to_size_blue":
    case "destructive_deliberation_red":
    case "destructive_deliberation_yellow":
    case "destructive_deliberation_blue":
    case "lay_to_rest_red":
    case "lay_to_rest_yellow":
    case "lay_to_rest_blue":
    case "flail_of_agony":
    case "hungering_demigon_red":
    case "hungering_demigon_yellow":
    case "hungering_demigon_blue":
    case "nasreth_the_soul_harrower":
    case "censor_red":
    case "mischievous_meeps_red":
    case "under_loop_red":
    case "jinglewood_smash_hit":
    case "bittering_thorns_red":
    case "banksy":
    case "heist_red":
    case "spring_a_leak_red":
    case "spring_a_leak_yellow":
    case "spring_a_leak_blue":
    case "data_link_red":
    case "data_link_yellow":
    case "data_link_blue":
    case "dive_through_data_red":
    case "dive_through_data_yellow":
    case "dive_through_data_blue":
    case "expedite_red":
    case "expedite_yellow":
    case "expedite_blue":
    case "metex_red":
    case "metex_yellow":
    case "metex_blue":
    case "under_loop_yellow":
    case "under_loop_blue":
    case "already_dead_red":
    case "intoxicating_shot_blue":
    case "millers_grindstone":
    case "pay_up_red":
    case "performance_bonus_red":
    case "performance_bonus_yellow":
    case "performance_bonus_blue":
    case "judge_jury_executioner_red":
    case "strength_rules_all_red":
    case "beckoning_mistblade":
    case "biting_breeze_red":
    case "biting_breeze_yellow":
    case "biting_breeze_blue":
    case "murky_water_red":
    case "earth_form_red":
    case "earth_form_yellow":
    case "earth_form_blue":
    case "lightning_form_red":
    case "lightning_form_yellow":
    case "lightning_form_blue":
    case "splintering_deadwood_red":
    case "splintering_deadwood_yellow":
    case "splintering_deadwood_blue":
    case "summit_the_unforgiving":
    case "devotion_never_dies_red":
    case "strike_gold_red":
    case "strike_gold_yellow":
    case "strike_gold_blue":
    case "blow_for_a_blow_red":
    case "bittering_thorns_blue":
      if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
      return true;
    case "dawnblade":
      //checking whether dawnblade *will* trigger is a little trickier
      if(GetClassState($mainPlayer, $CS_HitsWDawnblade) == 2) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return false;
      }
      elseif(GetClassState($mainPlayer, $CS_HitsWDawnblade) == 1) return true;
      else return false;
    case "breaking_point_red":
      if(IsHeroAttackTarget() && RuptureActive()) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "command_and_conquer_red":
    case "searing_shot_red":
    case "searing_shot_yellow":
    case "searing_shot_blue":
    case "persuasive_prognosis_blue":
    case "art_of_desire_body_red":
    case "art_of_desire_soul_yellow":
    case "art_of_desire_mind_blue":
    case "bonds_of_attraction_red":
    case "bonds_of_attraction_yellow":
    case "bonds_of_attraction_blue":
    case "double_trouble_red":
    case "double_trouble_yellow":
    case "double_trouble_blue":
    case "bonds_of_memory_red":
    case "bonds_of_memory_yellow":
    case "bonds_of_memory_blue":
    case "desires_of_flesh_red":
    case "desires_of_flesh_yellow":
    case "desires_of_flesh_blue":
    case "impulsive_desire_red":
    case "impulsive_desire_yellow":
    case "impulsive_desire_blue":
    case "minds_desire_red":
    case "minds_desire_yellow":
    case "minds_desire_blue":
    case "rowdy_locals_blue":
    case "the_weakest_link_red":
    case "blanch_red":
    case "blanch_yellow":
    case "blanch_blue":
    case "factfinding_mission_red":
    case "factfinding_mission_yellow":
    case "factfinding_mission_blue":
    case "static_shock_red":
    case "static_shock_yellow":
    case "snuff_out_red":
    case "cut_through_the_facade_red":
    case "hand_behind_the_pen_red":
    case "smash_up_red":
    case "tongue_tied_red":
    case "splatter_skull_red":
    case "mark_the_prey_red":
    case "mark_the_prey_yellow":
    case "mark_the_prey_blue":
    case "tag_the_target_red":
    case "tag_the_target_yellow":
    case "tag_the_target_blue":
    case "trap_and_release_red":
    case "trap_and_release_yellow":
    case "trap_and_release_blue":
    case "pursue_to_the_edge_of_oblivion_red":
    case "pursue_to_the_pits_of_despair_red":
    case "conqueror_of_the_high_seas_red":
    case "cogwerx_dovetail_red":
    case "cloud_city_steamboat_red":
    case "cloud_city_steamboat_yellow":
    case 'cloud_city_steamboat_blue':
    case "cogwerx_zeppelin_red":
    case "cogwerx_zeppelin_yellow":
    case "cogwerx_zeppelin_blue":
    case "hms_barracuda_yellow":
    case "hms_kraken_yellow":
    case "hms_marlin_yellow":
    case "pilfer_the_wreck_red":
    case "pilfer_the_wreck_yellow":
    case "pilfer_the_wreck_blue":
    case "crash_down_the_gates_red":
    case "crash_down_the_gates_yellow":
    case "crash_down_the_gates_blue":
    case "undercover_acquisition_red":
    case "jack_be_nimble_red":
    case "jack_be_quick_red":
    case "money_or_your_life_red":
    case "money_or_your_life_yellow":
    case "money_or_your_life_blue":
    case "bam_bam_yellow":
    case "wreck_havoc_red":
    case "wreck_havoc_yellow":
    case "wreck_havoc_blue":
    case "send_packing_yellow":
    case "stab_wound_blue":
    case "old_leather_and_vim_red":
    case "uplifting_performance_blue":
    case "offensive_behavior_blue":
    case "spew_obscenities_yellow":
    case "eradicate_yellow":
    case "regicide_blue":
    case "leave_no_witnesses_red":
    case "surgical_extraction_blue":
    case "plunder_the_poor_red":
    case "plunder_the_poor_yellow":
    case "plunder_the_poor_blue":
    case "rob_the_rich_red":
    case "rob_the_rich_yellow":
    case "rob_the_rich_blue":
    case "annihilate_the_armed_red":
    case "annihilate_the_armed_yellow":
    case "annihilate_the_armed_blue":
    case "fleece_the_frail_red":
    case "fleece_the_frail_yellow":
    case "fleece_the_frail_blue":
    case "nix_the_nimble_red":
    case "nix_the_nimble_yellow":
    case "nix_the_nimble_blue":
    case "sack_the_shifty_red":
    case "sack_the_shifty_yellow":
    case "sack_the_shifty_blue":
    case "slay_the_scholars_red":
    case "slay_the_scholars_yellow":
    case "slay_the_scholars_blue":
      if (IsHeroAttackTarget()) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "shake_down_red":
      if(NumAttackReactionsPlayed() > 0 && IsHeroAttackTarget()) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "annihilator_engine_red":
    case "terminator_tank_red":
    case "war_machine_red":
      if (IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) >= 1) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "bolt_of_courage_red":
    case "bolt_of_courage_yellow":
    case "bolt_of_courage_blue":
    case "engulfing_light_red":
    case "engulfing_light_yellow":
    case "engulfing_light_blue":
      if (GetClassState($mainPlayer, $CS_NumCharged) > 0) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "thump_red":
    case "thump_yellow":
    case "thump_blue":
    case "concuss_red":
    case "concuss_yellow":
    case "concuss_blue":
    case "command_respect_red":
    case "command_respect_yellow":
    case "command_respect_blue":
      if(HasIncreasedAttack() && IsHeroAttackTarget()){
          if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
          return true;
        }
        break;
    case "boltn_shot_red":
    case "boltn_shot_yellow":
    case "boltn_shot_blue":
      if(HasIncreasedAttack()){
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "down_but_not_out_red":
    case "down_but_not_out_yellow":
    case "down_but_not_out_blue":
      if (SearchCurrentTurnEffects($cardID, $mainPlayer)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "hunters_klaive":
    case "hunters_klaive_r":
    case "mark_of_the_huntsman":
    case "mark_of_the_huntsman_r":
    case "spiders_bite":
    case "nerve_scalpel":
    case "nerve_scalpel_r":
    case "orbitoclast":
    case "orbitoclast_r":
    case "scale_peeler":
    case "scale_peeler_r":
      if (IsHeroAttackTarget() || $targetPlayer != "-") {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT", $uniqueID);
        return true;
      }
      break;
    case "find_center_blue":
    case "break_tide_yellow":
    case "winds_of_eternity_blue":
    case "tiger_swipe_red": 
    case "mauling_qi_red":
    case "spinning_wheel_kick_red":
    case "spinning_wheel_kick_yellow":
    case "spinning_wheel_kick_blue":
    case "mugenshi_release_yellow":
    case "hurricane_technique_yellow":
    case "pounding_gale_red":
    case "whelming_gustwave_red":
    case "whelming_gustwave_yellow":
    case "whelming_gustwave_blue":
    case "rushing_river_red":
    case "rushing_river_yellow":
    case "rushing_river_blue":
      if (ComboActive($cardID)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "one_two_punch_red":
    case "one_two_punch_yellow":
    case "one_two_punch_blue": 
    case "recoil_red":
    case "recoil_yellow":
    case "recoil_blue":
    case "enact_vengeance_red":
    case "vengeance_never_rests_blue":
      if (ComboActive($cardID) && IsHeroAttackTarget()) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "winters_wail":
      if (SearchCurrentTurnEffects($cardID, $mainPlayer)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "blacktek_whisperers":
      if(IsHeroAttackTarget() && ClassContains($combatChain[0], "ASSASSIN", $mainPlayer)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "bonds_of_agony_blue":
      if (NumAttackReactionsPlayed() > 2 && IsHeroAttackTarget()) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "kiss_of_death_red":
      if (IsHeroAttackTarget() || $targetPlayer == $defPlayer) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
    case "dishonor_blue":
      if(IsHeroAttackTarget() && HasAttackName("Surging Strike") && HasAttackName("Descendent Gustwave") && HasAttackName("Bonds of Ancestry"))
      {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "pain_in_the_backside_red":
      if (IsHeroAttackTarget()) {
        if (!$check) {
          $subtype = "Dagger";
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYCHAR:subtype=" . $subtype . "&COMBATCHAINATTACKS:subtype=$subtype;type=AA");
          AddDecisionQueue("REMOVEINDICESIFACTIVECHAINLINK", $mainPlayer, "<-", 1);
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "choose_a_dagger_to_poke_with", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("SHOWSELECTEDTARGET", $mainPlayer, "-", 1);
          AddDecisionQueue("ADDTRIGGER", $mainPlayer, "$cardID|ONHITEFFECT", "<-", 1);
        }
        return true;
      }
      break;
    case "runic_reclamation_red":
      if (IsHeroAttackTarget()) {
        if (!$check) {
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRAURAS");
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("SHOWSELECTEDTARGET", $mainPlayer, "-", 1);
          AddDecisionQueue("ADDTRIGGER", $mainPlayer, "$cardID|ONHITEFFECT", "<-", 1);
        }
        return true;
      }
      break;
    case "stone_rain_red":
      if (IsHeroAttackTarget() && HasAimCounter()) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "mark_of_the_black_widow_red":
    case "mark_of_the_black_widow_yellow":
    case "mark_of_the_black_widow_blue":
    case "mark_of_the_funnel_web_red":
    case "mark_of_the_funnel_web_yellow":
    case "mark_of_the_funnel_web_blue":
      if (IsHeroAttackTarget() && CheckMarked($defPlayer)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "burning_blade_dance_red":
    case "hot_on_their_heels_red":
    case "mark_with_magma_red":
      if (IsHeroAttackTarget() && NumDraconicChainLinks() > 1) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "art_of_the_dragon_claw_red":
    case "art_of_the_dragon_scale_red":
      if (IsHeroAttackTarget() && SearchCurrentTurnEffects($cardID, $mainPlayer)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "walk_the_plank_red":
    case "walk_the_plank_yellow":
    case "walk_the_plank_blue":
      $defChar = GetPlayerCharacter($defPlayer);
      if (IsHeroAttackTarget() && ClassContains($defChar[0], "PIRATE", $defPlayer)) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "swarming_gloomveil_red":
      if(IsHeroAttackTarget() && GetClassState($mainPlayer, $CS_NumAuras) >= 3) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    case "standing_ovation_blue":
      if (GetClassState($mainPlayer, $CS_SuspensePoppedThisTurn) >= 3) {
        if (!$check) AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "ONHITEFFECT");
        return true;
      }
      break;
    default:
      break;
  }
  return false;
}

function AddCrushEffectTrigger($cardID)
{
  global $mainPlayer, $defPlayer;
  if (CardType($cardID) == "AA" && (SearchAuras("stamp_authority_blue", 1) || SearchAuras("stamp_authority_blue", 2))) return false;
  if (SearchCurrentTurnEffects("leave_a_dent_blue", $mainPlayer) && ClassContains($cardID, "GUARDIAN", $mainPlayer)) {
    AddLayer("TRIGGER", $mainPlayer, $cardID, "leave_a_dent_blue", "CRUSHEFFECT");
  }
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") return $card->AddCrushEffectTrigger();
  switch ($cardID) {
    case "crippling_crush_red":
    case "spinal_crush_red":
    case "cranial_crush_blue":
    case "disable_red":
    case "disable_yellow":
    case "disable_blue":
    case "buckling_blow_red":
    case "buckling_blow_yellow":
    case "buckling_blow_blue":
    case "cartilage_crush_red":
    case "cartilage_crush_yellow":
    case "cartilage_crush_blue":
    case "crush_confidence_red":
    case "crush_confidence_yellow":
    case "crush_confidence_blue":
    case "debilitate_red":
    case "debilitate_yellow":
    case "debilitate_blue":
    case "mangle_red":
    case "righteous_cleansing_yellow":
    case "crush_the_weak_red":
    case "crush_the_weak_yellow":
    case "crush_the_weak_blue":
    case "chokeslam_red":
    case "chokeslam_yellow":
    case "chokeslam_blue":
    case "star_struck_yellow":
    case "boulder_drop_yellow":
    case "boulder_drop_blue":
    case "boulder_drop_red":
    case "put_em_in_their_place_red":
    case "batter_to_a_pulp_red":
    case "grind_them_down_red": case "grind_them_down_yellow": case "grind_them_down_blue":
    case "flatten_the_field_red": case "flatten_the_field_yellow": case "flatten_the_field_blue":
    case "knock_em_off_their_feet_red":
    case "break_stature_yellow":
    case "headbutt_blue":
    case "fault_line_red":
    case "hostile_encroachment_red":
    case "renounce_grandeur_red":
      AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "CRUSHEFFECT");
      break;
    case "blinding_of_the_old_ones_red": 
    case "smelting_of_the_old_ones_red": 
    case "disenchantment_of_the_old_ones_red":
    case "annexation_of_grandeur_yellow":
    case "annexation_of_the_forge_yellow":
    case "annexation_of_all_things_known_yellow":
      $defChar = GetPlayerCharacter($defPlayer);
      if (ClassContains($defChar[0], "GUARDIAN", $defPlayer)) {
        AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "CRUSHEFFECT");
      }
      break;
    default:
      break;
  }
  return false;
}

function AddTowerEffectTrigger($cardID)
{
  global $mainPlayer;
  if (CardType($cardID) == "AA" && (SearchAuras("stamp_authority_blue", 1) || SearchAuras("stamp_authority_blue", 2))) return false;
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") return $card->AddTowerHitTrigger();
  switch ($cardID) {
    case "colossal_bearing_red":
    case "smack_of_reality_red":
      AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "TOWEREFFECT");
      break;
    default:
      break;
  }
  return false;
}

function AddCardEffectHitTrigger($cardID, $sourceID = "-", $targetPlayer = "-") // Effects that do not gives it's effect to the attack so still triggers when Stamp Confidance is in the arena
{
  global $mainPlayer, $defPlayer, $CombatChain, $combatChain;
  $source = $sourceID != "-" ? $sourceID : $CombatChain->AttackCard()->ID();
  if (SearchCurrentTurnEffects("dense_blue_mist_blue-HITPREVENTION", $defPlayer)) return false;
  $effects = explode(',', $cardID);
  $parameter = explode("-", $effects[0])[0];
  $mode = explode("-", $effects[0])[1] ?? "-";
  $card = GetClass($parameter, $mainPlayer);
  if ($card != "-") $card->AddCardEffectHitTrigger($source, $targetPlayer, $mode);
  switch ($effects[0]) {
    case "spoils_of_war_red-2":
    case "eclipse_existence_blue":
    case "ice_quake_red-HIT":
    case "ice_quake_yellow-HIT":
    case "ice_quake_blue-HIT":
    case "shock_charmers":
    case "electrify_red":
    case "electrify_yellow":
    case "electrify_blue":
    case "outland_skirmish_red-1":
    case "outland_skirmish_yellow-1":
    case "outland_skirmish_blue-1":
    case "smashing_good_time_red-1":
    case "smashing_good_time_yellow-1":
    case "smashing_good_time_blue-1":
    case "glistening_steelblade_yellow-1":
    case "premeditate_red-1":
    case "target_totalizer":
    case "hack_to_reality_yellow-HIT":
    case "regain_composure_blue":
      AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      break;
    case "chill_to_the_bone_red":
    case "chill_to_the_bone_yellow":
    case "chill_to_the_bone_blue":
      if (IsHeroAttackTarget()) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "mask_of_shifting_perspectives":
      if (SubtypeContains($source, "Dagger")) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "plunder_run_red-1": //triggers that won't apply on flick
    case "plunder_run_yellow-1":
    case "plunder_run_blue-1":
      if (TypeContains($source, "AA")) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "burn_up__shock_red":
    case "imperial_seal_of_command_red-HIT":
      if (IsHeroAttackTarget()) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "force_of_nature_blue-HIT":
      AddLayer("TRIGGER", $mainPlayer, "force_of_nature_blue", "force_of_nature_blue-TRIGGER", "EFFECTHITEFFECT", $source);
      break;
    case "succumb_to_temptation_yellow":
      if (CardType($CombatChain->AttackCard()->ID()) == "AA" && ClassContains($CombatChain->AttackCard()->ID(), "RUNEBLADE", $mainPlayer) && IsHeroAttackTarget()) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "savor_bloodshed_red-HIT":
      if((IsHeroAttackTarget() || $targetPlayer == $defPlayer) && CheckMarked($defPlayer)) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "poisoned_blade_red":
    case "poisoned_blade_yellow":
    case "poisoned_blade_blue":
      if(IsHeroAttackTarget() && (SubtypeContains($CombatChain->AttackCard()->ID(), "Dagger") || SubtypeContains($sourceID, "Dagger"))) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
    default:
      break;
  }
}

function AddEffectHitTrigger($cardID, $source="-", $fromCombat=true, $target="-"): bool // Effects that gives effect to the attack (keywords "attack gains/gets")
{
  global $mainPlayer, $Card_LifeBanner, $Card_ResourceBanner, $layers, $defPlayer, $combatChain;
  $effects = explode(',', $cardID);
  $parameter = explode("-", $effects[0])[0];
  if (CardType($source) == "AA" && (SearchAuras("stamp_authority_blue", 1) || SearchAuras("stamp_authority_blue", 2))) return false;
  if (CardType($source) == "AA" && SearchCurrentTurnEffects("gallow_end_of_the_line_yellow", $mainPlayer)) return false;
  if (class_exists($effects[0])) {
    $card = new $effects[0]($mainPlayer);
    return $card->AddEffectHitTrigger($source, $fromCombat, $target, $parameter);
  }
  switch ($effects[0]) {
    case "warriors_valor_red":
    case "warriors_valor_yellow":
    case "warriors_valor_blue":
    case "natures_path_pilgrimage_red":
    case "natures_path_pilgrimage_yellow":
    case "natures_path_pilgrimage_blue":
    case "pummel_red":
    case "pummel_yellow":
    case "pummel_blue":
    case "razor_reflex_red":
    case "razor_reflex_yellow":
    case "razor_reflex_blue":
    case "poison_the_tips_yellow":
    case "mauvrion_skies_red":
    case "mauvrion_skies_yellow":
    case "mauvrion_skies_blue":
    case "lumina_ascension_yellow":
    case "seek_enlightenment_red":
    case "seek_enlightenment_yellow":
    case "seek_enlightenment_blue":
    case "dusk_path_pilgrimage_red":
    case "dusk_path_pilgrimage_yellow":
    case "dusk_path_pilgrimage_blue":
    case "shadow_puppetry_red":
    case "warmongers_recital_red":
    case "warmongers_recital_yellow":
    case "warmongers_recital_blue":
    case "oaken_old_red":
    case "mulch_red":
    case "mulch_yellow":
    case "mulch_blue":
    case "snow_under_red":
    case "snow_under_yellow":
    case "snow_under_blue":
    case "frost_lock_blue-2":
    case "ice_storm_red-2":
    case "buzz_bolt_red":
    case "buzz_bolt_yellow":
    case "buzz_bolt_blue":
    case "flashfreeze_red-BUFF":
    case "shock_striker_red":
    case "shock_striker_yellow":
    case "shock_striker_blue":
    case "tear_asunder_blue":
    case "seek_and_destroy_red":
    case "twin_twisters_red-1":
    case "twin_twisters_yellow-1":
    case "twin_twisters_blue-1":
    case "life_of_the_party_red-1":
    case "life_of_the_party_yellow-1":
    case "life_of_the_party_blue-1":
    case "high_striker_red":
    case "high_striker_yellow":
    case "high_striker_blue":
    case "buckle_blue":
    case "cleave_red":
    case "dead_eye_yellow":
    case "runic_reaping_red-HIT":
    case "runic_reaping_yellow-HIT":
    case "runic_reaping_blue-HIT":
    case "melting_point_red":
    case "concealed_blade_blue":
    case "toxic_tips":
    case "beckoning_light_red":
    case "spirit_of_war_red":
    case "light_the_way_red":
    case "light_the_way_yellow":
    case "light_the_way_blue":
    case "lumina_lance_yellow-2":
    case "lumina_lance_yellow-3":
    case "ironsong_versus":
    case $Card_LifeBanner:
    case $Card_ResourceBanner:
    case "smash_and_grab_red":
    case "evo_command_center_yellow_equip":
    case "kassai_of_the_golden_sand":
    case "kassai":
    case "hood_of_red_sand":
      AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      break;
    case "talk_a_big_game_blue":
    case "lace_with_bloodrot_red":
    case "lace_with_frailty_red":
    case "lace_with_inertia_red":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      break;
    case "just_a_nick_red-HIT":
    case "maul_yellow-HIT":
      AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "two_sides_to_the_blade_red-ATTACK":
    case "long_whisker_loyalty_red-MARK":
    case "twist_and_turn_red":
    case "twist_and_turn_yellow":
    case "twist_and_turn_blue":
    case "hunt_a_killer_red":
    case "hunt_a_killer_yellow":
    case "hunt_a_killer_blue":
    case "sworn_vengeance_red":
    case "sworn_vengeance_yellow":
    case "sworn_vengeance_blue":
      AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "searing_gaze_red":
    case "stabbing_pain_red":
      if (IsHeroAttackTarget() && NumDraconicChainLinks() > 1) AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "gold_baited_hook":
    case "avast_ye_blue":
    case "heave_ho_blue":
    case "yo_ho_ho_blue":
    case "bam_bam_yellow":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "drop_the_anchor_red":
      if ($target == "HERO") AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "take_a_stab_red":
    case "take_a_stab_yellow":
    case "take_a_stab_blue":
      if (IsHeroAttackTarget() && CheckMarked($defPlayer)) AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "scar_tissue_red": //should trigger when attacking a hero or flicking at a hero
    case "scar_tissue_yellow":
    case "scar_tissue_blue":
    case "toxicity_red":
    case "toxicity_yellow":
    case "toxicity_blue":
    case "spike_with_bloodrot_red":
    case "spike_with_frailty_red":
    case "spike_with_inertia_red":
    case "mask_of_perdition":
      if (IsHeroAttackTarget() || $target == $defPlayer) AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      break;  
    case "arakni_black_widow-HIT":
      // trigger cases: 1. stealth AA hit, 2. active chain chelicera hit, 3. flicked kiss
      if (TypeContains($source, "AA", $mainPlayer) && !$fromCombat || (IsHeroAttackTarget() && $fromCombat)) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "arakni_funnel_web-HIT":
      // trigger cases: 1. stealth AA hit, 2. active chain chelicera hit, 3. flicked kiss
      if (TypeContains($source, "AA", $mainPlayer) && !$fromCombat || (IsHeroAttackTarget() && $fromCombat)) {
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    case "big_game_trophy_shot_yellow":
      if (CardNameContains($combatChain[0], "Harpoon", $mainPlayer, true) && IsHeroAttackTarget()){
        AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      }
      break;
    case "loot_the_hold_blue":
    case "loot_the_arsenal_blue":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    case "legacy_of_ikaru_blue":
      AddLayer("TRIGGER", $mainPlayer, $parameter, $cardID, "EFFECTHITEFFECT");
      break;
    default:
      break;
  }
  return false;
}

function AddCharacterPlayCardTrigger($cardID, $playType, $from)
{
  global $mainPlayer;
  $otherPlayer = $mainPlayer == 1 ? 2 : 1;
  $mainChar = GetPlayerCharacter($mainPlayer);
  for ($i = 0; $i < count($mainChar); $i += CharacterPieces()) {
    switch ($mainChar[$i]) {
      default:
        break;
    }
  }
  $otherChar = GetPlayerCharacter($otherPlayer);
  for ($i = 0; $i < count($otherChar); $i += CharacterPieces()) {
    switch ($otherChar[$i]) {
      case "leap_frog_vocal_sac":
      case "leap_frog_slime_skin":
      case "leap_frog_gloves":
      case "leap_frog_leggings":
        if ($playType == "AR" && SearchCharacterActive($otherPlayer, $otherChar[$i], checkGem: true)) {
          AddLayer("TRIGGER", $otherPlayer, $otherChar[$i]);
        }
        break;
      default:
        break;
    }
  }
}

function ProcessMainCharacterHitEffect($cardID, $player, $target)
{
  global $combatChain, $mainPlayer, $layers, $defPlayer;
  $character = &GetPlayerCharacter($player);
  if (CardType($target) == "AA" && SearchCurrentTurnEffects("tarpit_trap_yellow", $mainPlayer, count($layers) <= LayerPieces())) {
    WriteLog("Hit effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
    return true;
  }
  switch ($cardID) {
    case "katsu_the_wanderer":
    case "katsu":
      KatsuHit();
      break;
    case "refraction_bolters":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($cardID, $cardID)."_to_get_Go_Again");
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, "MYCHAR-$index", 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      AddDecisionQueue("OP", $player, "GIVEATTACKGOAGAIN", 1);
      AddDecisionQueue("WRITELOG", $player, Cardlink($cardID, $cardID)." was destroyed", 1);
      break;
    case "mask_of_momentum":
      Draw($player);
      break;
    case "vest_of_the_first_fist":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($cardID, $cardID)."_to_gain_2_resources");
      AddDecisionQueue("NOPASS", $player, "");
      AddDecisionQueue("PASSPARAMETER", $player, "MYCHAR-" . $index, 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      AddDecisionQueue("GAINRESOURCES", $player, 2, 1);
      break;
    case "breeze_rider_boots":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($cardID, $cardID));
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $character[$index], 1);
      break;
    case "briar_warden_of_thorns":
    case "briar":
      PlayAura("embodiment_of_earth", $player);
      break;
    case "mask_of_the_pouncing_lynx":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if you want to destroy ".Cardlink($cardID, $cardID));
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("FINDINDICES", $player, "MASKPOUNCINGLYNX", 1);
      AddDecisionQueue("MAYCHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("MULTIBANISH", $player, "DECK,TT", 1);
      AddDecisionQueue("SETDQVAR", $player, "0", 1);
      AddDecisionQueue("WRITELOG", $player, "<0> was banished.", 1);
      AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
      break;
    case "grains_of_bloodspill":
      $hand = &GetHand($player);
      $resources = &GetResources($player);
      if (TypeContains($combatChain[0], "W", $mainPlayer) && (Count($hand) > 0 || $resources[0] > 0)) {
        AddDecisionQueue("YESNO", $player, "if you want to pay 1 to create a " . CardLink("vigor", "vigor"), 0, 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("WRITELOG", $player, CardLink($cardID, $cardID) . " created a " . CardLink("vigor", "vigor") . " token ", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "vigor", 1);
        AddDecisionQueue("PUTPLAY", $player, "-", 1);
      }
      break;
    case "aether_crackers":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($cardID, $cardID)."_to_deal_one_arcane");
      AddDecisionQueue("NOPASS", $player, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("DEALARCANE", $player, "1" . "-" . "aether_crackers" . "-" . "TRIGGER", 1);
      AddDecisionQueue("WRITELOG", $player, Cardlink($cardID, $cardID) . " were destroyed", 1);
      break;
    case "arakni_marionette":
    case "arakni_web_of_deceit":
      GiveAttackGoAgain();
      break;
    case "arakni_tarantula":
      WriteLog(CardLink($cardID, $cardID) . "'s venom saps 1 life from " . $defPlayer);
      LoseHealth(1, $defPlayer);
      break;
    case "cindra_dracai_of_retribution":
    case "cindra":
    case "fang_dracai_of_blades":
    case "fang":
      PlayAura("fealty", $player);
      break;
    case "blood_splattered_vest":
      AddDecisionQueue("YESNO", $player, "to_add_a_stain_counter_to_".Cardlink($cardID, $cardID));
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("SPECIFICCARD", $player, "BLOODSPATTEREDVEST", 1);
      break;
    case "okana_scar_wraps":
      $openHands = true;
      $char = GetPlayerCharacter($player);
      for ($i = CharacterPieces(); $i < count($char); $i += CharacterPieces()) {
        if (TypeContains($char[$i], "W", $player) || SubtypeContains($char[$i], "Off-Hand")) {
          if ($char[$i + 1] != 0) $openHands = false;
        }
      }
      $index = SearchCharacterForCards($cardID, $player);
      if (SearchBanishForCard($player, "edge_of_autumn") != -1 && $openHands) {
        AddDecisionQueue("SETDQCONTEXT", $player, "equip_a_banished_edge_of_autumn");
        if ($index == "" || IsCharacterActive($player, $index)) {
         AddDecisionQueue("YESNO", $player, "-", 1);
         AddDecisionQueue("NOPASS", $player, "-", 1);
        }
        AddDecisionQueue("SPECIFICCARD", $player, "SCARWRAPS", "-", 1);
      }
      break;
    case "robe_of_autumns_fall":
      $index = FindCharacterIndex($player, $cardID);
      AddDecisionQueue("YESNO", $player, "if you want to destroy ".Cardlink($cardID, $cardID));
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
      AddDecisionQueue("GAINRESOURCES", $player, 1, 1);
      break;
    default:
      break;
  }
}

function ProcessItemsEffect($cardID, $player, $target, $uniqueID)
{
  global $layers, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  $otherPlayer = $player == 1 ? 2 : 1;
  if (CardType($target) == "AA" && SearchCurrentTurnEffects("tarpit_trap_yellow", $player, count($layers) <= LayerPieces())) {
    WriteLog("Hit effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
    return true;
  }
  switch ($cardID) {
    case "powder_keg_blue":
      AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_" . CardLink($cardID, $cardID) . "_and_a_defending_equipment?");
      AddDecisionQueue("NOPASS", $player, "-");
      AddDecisionQueue("SEARCHCOMBATCHAIN", $player, "E", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a defending equipment to destroy", 1);
      AddDecisionQueue("CHOOSECARDID", $player, "<-", 1);
      AddDecisionQueue("POWDERKEG", $player, "-", 1);
      break;
    case "tick_tock_clock_red":
      $index = SearchItemsForUniqueID($uniqueID, $player);
      DestroyItemForPlayer($player, $index);
      AddDecisionQueue("PASSPARAMETER", $player, "0");
      AddDecisionQueue("SETDQVAR", $player, "0");
      for ($i = 0; $i < 2; ++$i) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRITEMS&MYITEMS", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose an item to destroy");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZDESTROY", $player, "-");
        AddDecisionQueue("INCDQVARIFNOTPASS", $player, "0");
      }
      AddDecisionQueue("SPECIFICCARD", $otherPlayer, "TICKTOCKCLOCK");
      break;
    case "boom_grenade_red":
    case "boom_grenade_yellow":
    case "boom_grenade_blue":
      if ($cardID == "boom_grenade_red") $amount = 4;
      else if ($cardID == "boom_grenade_yellow") $amount = 3;
      else $amount = 2;
      DamageTrigger($otherPlayer, $amount, "DAMAGE", $cardID);
      DestroyItemForPlayer($player, SearchItemsForUniqueID($uniqueID, $player));
      break;
    case "autosave_script_blue":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    default:
      break;
  }
}

function ProcessAbility($player, $parameter, $uniqueID, $target = "-", $additionalCosts = "-", $from = "-")
{
  global $CS_DamagePrevention, $combatChain, $CS_AdditionalCosts, $mainPlayer;
  $otherPlayer = $player == 1 ? 2 : 1;
  $card = GetClass($parameter, $player);
  if ($card != "-") return $card->ProcessAbility($uniqueID, $target, $additionalCosts, $from);
  switch ($parameter) {
    case "mighty_windup_red":
    case "mighty_windup_yellow":
    case "mighty_windup_blue":
      PlayAura("might", $player, isToken:true, effectController:$player, effectSource:$parameter);
      break;
    case "agile_windup_red":
    case "agile_windup_yellow":
    case "agile_windup_blue":
      PlayAura("agility", $player, isToken:true, effectController:$player, effectSource:$parameter);
      break;
    case "vigorous_windup_red":
    case "vigorous_windup_yellow":
    case "vigorous_windup_blue":
      PlayAura("vigor", $player, isToken:true, effectController:$player, effectSource:$parameter);
      break;
    case "ripple_away_blue":
      AddCurrentTurnEffect($parameter, $player, $from);
      break;
    case "fruits_of_the_forest_red":
    case "fruits_of_the_forest_yellow":
    case "fruits_of_the_forest_blue":
      GainHealth(2, $player);
      break;
    case "trip_the_light_fantastic_red":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_blue":
      AddCurrentTurnEffect($parameter, $player);
      IncrementClassState($player, $CS_DamagePrevention, 2);
      WriteLog(CardLink($parameter, $parameter) . " is preventing the next 2 damage.");
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
      AddCurrentTurnEffect($parameter, $player, from: "ABILITY");
      break;
    case "haunting_rendition_red":
    case "mental_block_blue":
      AddCurrentTurnEffect($parameter, $player);
      IncrementClassState($player, $CS_DamagePrevention, 2);
      break;
    
    case "under_the_trap_door_blue":
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYDISCARD:subtype=Trap");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZREMOVE", $player, "-", 1);
      AddDecisionQueue("BANISHCARD", $player, "DISCARD,TT", 1);
      AddDecisionQueue("UNDERTRAPDOOR", $player, "<-", 1);
      break;
    case "reapers_call_red":
    case "reapers_call_yellow":
    case "reapers_call_blue":
    case "tip_off_red":
    case "tip_off_yellow":
    case "tip_off_blue":
      MarkHero($otherPlayer);
      break;
    case "shelter_from_the_storm_red":
      AddCurrentTurnEffect($parameter, $player);
      break;
    case "warcry_of_themis_yellow":
      // leave this as is for now, additional costs seem to not be tracking properly
      break;
    case "warcry_of_bellona_yellow":
      // $params = explode("-", $target);
      // $uniqueID = $params[1];
      // AddCurrentTurnEffect($parameter."-DMG,".$additionalCosts.",".$uniqueID, $player);
      break;
    case "deny_redemption_red":
      AddCurrentTurnEffect($parameter, $player);
      break;
    case "bam_bam_yellow":
      AddCurrentTurnEffect($parameter, $player);
      break;
    case "burn_bare":
      if ($combatChain[10] != "PHANTASM") AddLayer("LAYER", $player, "PHANTASM", $combatChain[0], $parameter);
      break;
    case "outside_interference_blue":
      if (CanRevealCards($player)) {
        $inventory = &GetInventory($player);
        $choices = [];
        foreach ($inventory as $cardID) {
          if (TalentContains($cardID, "REVILED", $player) && TypeContains($cardID, "AA")) {
            array_push($choices, $cardID);
          };
        }
        if (count($choices) == 0) {
          WriteLog("Player " . $player . " doesn't have any reviled attacks");
          return;
        }
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to add to hand");
        AddDecisionQueue("MAYCHOOSECARD", $player, implode(",", $choices), 1);
        AddDecisionQueue("APPENDLASTRESULT", $player, "-INVENTORY", 1);
        AddDecisionQueue("ADDHANDINVENTORY", $player, "<-", 1);
        AddDecisionQueue("REVEALCARDS", $player, "<-", 1);
      }
      else WriteLog("You cannot reveal a card from your inventory");
      break;
    case "fearless_confrontation_blue":
      AddCurrentTurnEffect($parameter, $mainPlayer);
      break;
    case "light_up_the_leaves_red":
      AddCurrentTurnEffect($parameter, $player, uniqueID:$target);
      break;
    default:
      break;
  }
  return "";
}

function ProcessTrigger($player, $parameter, $uniqueID, $target = "-", $additionalCosts = "-", $from = "-")
{
  global $combatChain, $CS_NumNonAttackCards, $CS_ArcaneDamageDealt, $CS_NumRedPlayed, $CS_DamageTaken, $EffectContext, $CombatChain, $CCS_GoesWhereAfterLinkResolves;
  global $CID_BloodRotPox, $CID_Inertia, $CID_Frailty, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer, $CS_NumEarthBanished;
  global $CS_DamagePrevention, $chainLinks, $currentTurnEffects;
  global $landmarks;
  $items = &GetItems($player);
  $auras = &GetAuras($player);
  $parameter = ShiyanaCharacter($parameter);
  $EffectContext = $parameter;
  $otherPlayer = $player == 1 ? 2 : 1;
  if ($additionalCosts == "ONHITEFFECT") {
    ProcessHitEffect($parameter, $combatChain[2] ?? "-", $uniqueID, target:$target);
    return;
  }
  elseif ($additionalCosts == "CRUSHEFFECT") {
    ProcessCrushEffect($target);
    return;
  }
  elseif ($additionalCosts == "TOWEREFFECT") {
    ProcessTowerEffect($target);
    return;
  }
  elseif ($additionalCosts == "EFFECTHITEFFECT") {
    if(isset($combatChain) && count($combatChain) > 2) {
      if (EffectHitEffect($target, $combatChain[2], $uniqueID, effectSource:$combatChain[0])) {
        $index = FindCurrentTurnEffectIndex($player, $target);
        if ($index != -1) {
          // Remove "-string" and "string-" suffixes from the effect ID
          $effectID = preg_replace('/-[^-]*$/', '', $currentTurnEffects[$index]);
          LogPlayCardStats($player, $effectID, "CC", "HIT");
          RemoveCurrentTurnEffect($index);
        }
      }
    }
    return;
  }
  elseif ($additionalCosts == "MAINCHARHITEFFECT") {
    ProcessMainCharacterHitEffect($parameter, $player, $target);
    return;
  }
  elseif ($additionalCosts == "ITEMHITEFFECT") {
    ProcessItemsEffect($parameter, $player, $target, $uniqueID);
    return;
  }
  elseif ($additionalCosts == "ATTACKTRIGGER") {
    ProcessAttackTrigger($parameter, $player, $target, $uniqueID);
  }
  else {
    if (class_exists($parameter)) {
      $card = new $parameter($player);
      return $card->ProcessTrigger($uniqueID, $target, $additionalCosts, $from);
    }
    switch ($parameter) {
      case "HEAVE":
        Heave();
        break;
      case "heart_of_fyendal_blue":
        if (PlayerHasLessHealth($player)) GainHealth(1, $player);
        break;
      case "rhinar_reckless_rampage":
      case "rhinar":
        Intimidate();
        break;
      case "forged_for_war_yellow":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "show_time_blue":
        Draw($player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "blessing_of_deliverance_red":
      case "blessing_of_deliverance_yellow":
      case "blessing_of_deliverance_blue":
        if ($parameter == "blessing_of_deliverance_red") $amount = 3;
        else if ($parameter == "blessing_of_deliverance_yellow") $amount = 2;
        else $amount = 1;
        BlessingOfDeliveranceDestroy($amount);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "emerging_power_red":
      case "emerging_power_yellow":
      case "emerging_power_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "stonewall_confidence_red":
      case "stonewall_confidence_yellow":
      case "stonewall_confidence_blue":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "seismic_surge":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "mask_of_momentum":
        Draw($player);
        break;
      case "steelblade_supremacy_red":
        Draw($mainPlayer);
        break;
      case "scar_for_a_scar_red":
      case "scar_for_a_scar_yellow":
      case "scar_for_a_scar_blue":
      case "life_for_a_life_red":
      case "life_for_a_life_yellow":
      case "life_for_a_life_blue":
      case "blow_for_a_blow_red":
      case "blow_for_a_blow_yellow":
      case "blow_for_a_blow_blue":
        if(PlayerHasLessHealth($mainPlayer)) {
          WriteLog(CardLink($parameter, $parameter) . " gains Go Again!");
          GiveAttackGoAgain();
        }
        break;
      case "pound_for_pound_red":
      case "pound_for_pound_yellow":
      case "pound_for_pound_blue":
        if(PlayerHasLessHealth($mainPlayer)) {
          WriteLog(CardLink($parameter, $parameter) . " gains Dominate!");
          AddCurrentTurnEffect($parameter, $player);
        }
        break;
      case "adrenaline_rush_red":
      case "adrenaline_rush_yellow":
      case "adrenaline_rush_blue":
        if(PlayerHasLessHealth($mainPlayer)) {
          WriteLog(CardLink($parameter, $parameter) . " gains +3 Power!");
          AddCurrentTurnEffect($parameter, $player);
        }
        break;
      case "wounded_bull_red":
      case "wounded_bull_yellow":
      case "wounded_bull_blue":
        if(PlayerHasLessHealth($mainPlayer)) {
          WriteLog(CardLink($parameter, $parameter) . " gains +1 Power!");
          AddCurrentTurnEffect($parameter, $player);
        }
        break;
      case "ridge_rider_shot_red":
      case "ridge_rider_shot_yellow":
      case "ridge_rider_shot_blue":
        Opt($parameter, 1);
        break;
      case "eye_of_ophidia_blue":
        Opt($parameter, 2);
        break;
      case "teklo_core_blue":
        $index = SearchItemsForUniqueID($uniqueID, $player);
        --$items[$index + 1];
        GainResources($player, 2);
        if ($items[$index + 1] <= 0) DestroyItemForPlayer($player, $index);
        break;
      case "dissipation_shield_yellow":
        $index = SearchItemsForUniqueID($uniqueID, $player);
        --$items[$index + 1];
        if ($items[$index + 1] <= 0) DestroyItemForPlayer($player, $index);
        break;
      case "viserai_rune_blood":
      case "viserai":
        ViseraiPlayCard($target);
        break;
      case "bloodspill_invocation_red":
      case "bloodspill_invocation_yellow":
      case "bloodspill_invocation_blue":
        if ($parameter == "bloodspill_invocation_red") $amount = 3;
        else if ($parameter == "bloodspill_invocation_yellow") $amount = 2;
        else $amount = 1;
        WriteLog(CardLink($parameter, $parameter) . " created $amount runechants");
        PlayAura("runechant", $player, $amount);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "runechant":
        DealArcane(1, 1, "RUNECHANT", "runechant", player: $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "runechant_batch":
        $uniqueIDs = explode(",", $uniqueID);
        foreach ($uniqueIDs as $uid) {
          DealArcane(1, 1, "RUNECHANT", "runechant", player: $player);
          DestroyAuraUniqueID($player, $uid);
        }
        break;
      case "chains_of_eminence_red":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "arknight_shard_blue":
        PlayAura("runechant", $player);
        break;
      case "beast_within_yellow":
        AddDecisionQueue("SPECIFICCARD", $player, "BEASTWITHIN");
        break;
      case "massacre_red":
        Intimidate();
        break;
      case "stamp_authority_blue":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "towering_titan_red":
      case "towering_titan_yellow":
      case "towering_titan_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "emerging_dominance_red":
      case "emerging_dominance_yellow":
      case "emerging_dominance_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;  
      case "zephyr_needle":
      case "zephyr_needle_r":
        EvaluateCombatChain($totalPower, $totalBlock, secondNeedleCheck: true);
        for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
          $uid = $combatChain[$i + 2] == "EQUIP" ? $combatChain[$i + 8] : $combatChain[$i + 7];
          $blockVal = (intval(ModifiedBlockValue($combatChain[$i], $defPlayer, "CC", "", $uid)) + BlockModifier($combatChain[$i], "CC", 0, $i) + $combatChain[$i + 6]);
          if ($totalBlock > 0 && ($blockVal > $totalPower) && $combatChain[$i + 1] == $defPlayer) {
            DestroyCurrentWeapon();
          }
        }
        break;
      case "zen_state":
      case "preach_modesty_red":
        $index = SearchAurasForUniqueID($uniqueID, $player);
        if ($auras[$index + 2] == 0) {
          DestroyAuraUniqueID($player, $uniqueID);
        } else {
          --$auras[$index + 2];
        }
        break;
      case "shiyana_diamond_gemini":
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYCHAR:type=C&THEIRCHAR:type=C");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose which hero to copy");
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
        AddDecisionQueue("CHANGESHIYANA", $player, "<-", 1);
        AddDecisionQueue("APPENDLASTRESULT", $player, "-SHIYANA", 1);
        AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", $player, "<-", 1);
        break;
      case "data_doll_mkii":
        $index = SearchBanishForCard($player, $target);
        if($index == -1) break;
        PutItemIntoPlayForPlayer($target, $player);
        RemoveBanish($player, $index);
        break;
      case "viziertronic_model_i":
        AddDecisionQueue("DRAW", $player, "-", 1);
        MZMoveCard($player, "MYHAND", "MYTOPDECK", silent:true);
        $hand = GetHand($player);
        if (count($hand) == 0) {
          AddDecisionQueue("DECKCARDS", $player, "0", 1);
          AddDecisionQueue("SETDQVAR", $player, "1", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "you drew <1> and placed it back on top", 1);
          AddDecisionQueue("OK", $player, "-", 1);
          AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "-");
        }
        break;
      case "hyper_x3":
        $banish = GetBanish($player);
        $index = SearchBanishForUID($player, $target);
        if ($index == -1) WriteLog(CardLink($parameter, $parameter)."'s trigger fails");
        else {
          EquipmentBoostEffect($player, "hyper_x3", $banish[$index]);
          RemoveBanish($player, $index);
        }
        break;
      case "bios_update_red":
        $banish = GetBanish($player);
        $index = SearchBanishForUID($player, $target);
        if ($index == -1) WriteLog(CardLink($parameter, $parameter)."'s trigger fails");
        else {
          PutItemIntoPlayForPlayer($banish[$index], $player);
          RemoveBanish($player, $index);
        }
        break;
      case "tripwire_trap_red":
        TrapTriggered($parameter);
        if (!IsAllyAttacking()) {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
          AddDecisionQueue("NOPASS", $mainPlayer, $parameter, 1);
          AddDecisionQueue("PAYRESOURCES", $mainPlayer, "1", 1);
          AddDecisionQueue("ELSE", $mainPlayer, "-");
          AddDecisionQueue("TRIPWIRETRAP", $mainPlayer, "-", 1);
        }
        else { //if there is no attacking hero, the trap just goes off
          AddDecisionQueue("TRIPWIRETRAP", $mainPlayer, "-", 1);
        }
        break;
      case "pitfall_trap_yellow":
        TrapTriggered($parameter);
        AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_avoid_taking_2_damage", 1, 1);
        AddDecisionQueue("NOPASS", $mainPlayer, $parameter, 1);
        AddDecisionQueue("PAYRESOURCES", $mainPlayer, "1", 1);
        AddDecisionQueue("ELSE", $mainPlayer, "-");
        AddDecisionQueue("TAKEDAMAGE", $mainPlayer, "2-" . $parameter, 1);
        break;
      case "rockslide_trap_blue":
        TrapTriggered($parameter);
        if (!IsAllyAttacking()) {
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_pay_1_to_avoid_your_attack_getting_-2", 1, 1);
          AddDecisionQueue("NOPASS", $mainPlayer, $parameter, 1);
          AddDecisionQueue("PAYRESOURCES", $mainPlayer, "1", 1);
          AddDecisionQueue("ELSE", $mainPlayer, "-");
          AddDecisionQueue("POWERMODIFIER", $player, "-2", 1);
        } else {
          AddDecisionQueue("POWERMODIFIER", $mainPlayer, "-2", 1);
        }
        break;
      case "dread_triptych_blue":
        if (GetClassState($player, $CS_NumNonAttackCards) > 0) PlayAura("runechant", $player);
        if (GetClassState($player, $CS_ArcaneDamageDealt) > 0) PlayAura("runechant", $player);
        break;
      case "runeblood_barrier_yellow":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "metacarpus_node":
        AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_1_to_give_+1_arcane_damage");
        AddDecisionQueue("NOPASS", $player, "-", 1, 1);
        AddDecisionQueue("PAYRESOURCES", $player, "1", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
        AddDecisionQueue("BUFFARCANEPREVLAYER", $player, "metacarpus_node", 1);
        AddDecisionQueue("CHARFLAGDESTROY", $player, FindCharacterIndex($player, "metacarpus_node"), 1);
        break;
      case "BLOODDEBT":
        $numBloodDebt = SearchCount(SearchBanish($mainPlayer, "", "", -1, -1, "", "", true));
        $totalBloodDebt = $numBloodDebt;
        $char = &GetPlayerCharacter($mainPlayer);
        if ($char[0] == "blasmophet_levia_consumed" && +$char[1] == 2) {
          $deck = new Deck($mainPlayer);
          for ($i = 0; $i < $numBloodDebt; ++$i) $deck->BanishTop();
          return;
        }
        $health = &GetHealth($mainPlayer);
        if ($numBloodDebt > 0) {
          if ($health > 13 && $health - $numBloodDebt <= 13) {
            $numBloodDebt -= ($health - 13);
            $health = 13;
            if (SearchInventoryForCard($mainPlayer, "levia_redeemed") != "") {
              AddDecisionQueue("YESNO", $mainPlayer, "if you want to transform into ".CardLink("blasmophet_levia_consumed", "blasmophet_levia_consumed"));
              AddDecisionQueue("NOPASS", $mainPlayer, "-");
              AddDecisionQueue("PASSPARAMETER", $mainPlayer, $numBloodDebt, 1);
              AddDecisionQueue("TRANSFORMHERO", $mainPlayer, "blasmophet_levia_consumed", 1);
              AddDecisionQueue("ELSE", $mainPlayer, "-");
            }
          }
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, $numBloodDebt, 1);
          AddDecisionQueue("OP", $mainPlayer, "LOSEHEALTH", 1);
          AddDecisionQueue("WRITELOG", $mainPlayer, "Player $mainPlayer lost $totalBloodDebt life due to Blood Debt ", 1);
        }
        break;
      case "merciful_retribution_yellow":
        DealArcane(1, 0, "STATIC", $parameter, false, $player);
        $index = SearchDiscardForUniqueID($additionalCosts, $player);
        if ($additionalCosts != "-" && $index != -1) {
          $graveyard = GetDiscard($player);
          $cardID = $graveyard[$index];
          if (TalentContains($cardID, "LIGHT")) {
            AddSoul($cardID, $player, "DISCARD");
            RemoveGraveyard($player, $index);
          }
        }
        break;
      case "phantasmal_footsteps":
        $hand = &GetHand($player);
        $resources = &GetResources($player);
        if (Count($hand) > 0 || $resources[0] > 0) {
          if ($player == $defPlayer) {
            AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
            AddDecisionQueue("BUTTONINPUT", $player, "0,1");
            AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
            AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
            AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
            if (!SearchCurrentTurnEffects("phantasmal_footsteps", $player)) AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "phantasmal_footsteps", 1);
          } else {
            AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_1_to_gain_an_action_point", 0, 1);
            AddDecisionQueue("NOPASS", $player, "-", 1);
            AddDecisionQueue("PASSPARAMETER", $player, 1, 1);
            AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
            AddDecisionQueue("GAINACTIONPOINTS", $player, "1", 1);
            AddDecisionQueue("WRITELOG", $player, "Gained_an_action_point_from_" . CardLink("phantasmal_footsteps", "phantasmal_footsteps"), 1);
          }
        }
        break;
      case "hooves_of_the_shadowbeast":
        $index = FindCharacterIndex($player, $parameter);
        AddDecisionQueue("CHARREADYORPASS", $player, $index);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($parameter, $parameter)."_to_gain_an_action_point", 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
        AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
        AddDecisionQueue("GAINACTIONPOINTS", $player, 1, 1);
        AddDecisionQueue("WRITELOG", $player, "Gained_an_action_point_from_".Cardlink($parameter, $parameter), 1);
        break;
      case "soul_shackle":
        $deck = new Deck($player);
        $deck->BanishTop(banishedBy: $parameter);
        break;
      case "ironhide_helm":
      case "ironhide_plate":
      case "ironhide_gauntlet":
      case "ironhide_legs":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
        AddDecisionQueue("BUTTONINPUT", $player, "0,1");
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $parameter, 1);
        AddDecisionQueue("PASSPARAMETER", $player, 2, 1);
        AddDecisionQueue("COMBATCHAINCHARACTERDEFENSEMODIFIER", $player, $target, 1);
        break;
      case "seeds_of_agony_red":
      case "seeds_of_agony_yellow":
      case "seeds_of_agony_blue":
        WriteLog(CardLink($parameter, $parameter) . " dealt 1 damage.");
        DealArcane(1, 2, "PLAYCARD", $parameter, false, $player, resolvedTarget: $target);
        break;
      case "endless_winter_red":
        for ($i = 1; $i < count($combatChain); $i += CombatChainPieces()) if ($combatChain[$i] == $player) PlayAura("frostbite", $player, effectController: $mainPlayer);
        break;
      case "emerging_avalanche_red":
      case "emerging_avalanche_yellow":
      case "emerging_avalanche_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "strength_of_sequoia_red":
      case "strength_of_sequoia_yellow":
      case "strength_of_sequoia_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "briar_warden_of_thorns":
      case "briar":
        PlayAura("embodiment_of_lightning", $player);
        break;
      case "bramble_spark_red":
      case "bramble_spark_yellow":
      case "bramble_spark_blue":
        DealArcane(1, 0, "PLAYCARD", $CombatChain->AttackCard()->ID(), true, resolvedTarget:$target);
        break;
      case "embodiment_of_earth":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "embodiment_of_lightning":
        WriteLog(CardLink($parameter, $parameter) . " grants go again");
        GiveAttackGoAgain();
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "frostbite":
        $location = $additionalCosts == "EQUIP" ? "EQUIP" : "AURAS";
        DestroyAuraUniqueID($player, $uniqueID, $location);
        break;
      case "mark_of_lightning":
        $index = FindCharacterIndex($player, $parameter);
        AddDecisionQueue("YESNO", $player, "destroy_".Cardlink($parameter, $parameter)."_to_have_the_attack_deal_1_damage");
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
        AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, 1 . "-" . $combatChain[0] . "-" . "TRIGGER", 1);
        AddDecisionQueue("DEALDAMAGE", $otherPlayer, "MYCHAR-0", 1);
        break;
      case "channel_thunder_steppe_yellow":
        if ($additionalCosts == "CHANNEL") {
          ChannelTalent($target, "LIGHTNING");
        }
        else {
          AddDecisionQueue("YESNO", $player, "do_you_want_to_pay_1_to_give_your_action_go_again", 0, 1);
          AddDecisionQueue("NOPASS", $player, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $player, 1, 1);
          AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
          AddDecisionQueue("GIVEACTIONGOAGAIN", $player, $target, 1);
        }
        break;
      case "rampart_of_the_rams_head":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
        AddDecisionQueue("BUTTONINPUT", $player, "0,1");
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "rampart_of_the_rams_head", 1);
        break;
      case "embolden_red":
      case "embolden_yellow":
      case "embolden_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "seek_and_destroy_red": case "plan_for_the_worst_blue":
        DestroyArsenal($target, effectController: $player);
        DiscardHand($target, false);
        break;
      case "sting_of_sorcery_blue":
        if(count($combatChain) > 0) DealArcane(1, 0, "PLAYCARD", $combatChain[0]);
        break;
      case "stalagmite_bastion_of_isenloft":
        PlayAura("frostbite", $mainPlayer, effectController: $defPlayer);
        break;
      case "blizzard_bolt_red":
      case "blizzard_bolt_yellow":
      case "blizzard_bolt_blue":
        PlayAura("frostbite", $target, effectController:$player);
        break;
      case "chilling_icevein_red":
      case "chilling_icevein_yellow":
      case "chilling_icevein_blue":
        PayOrDiscard($target, 1);
        break;
      case "dissolution_sphere_yellow":
        $index = SearchItemsForUniqueID($uniqueID, $player);
        --$items[$index + 1];
        if ($items[$index + 1] < 0) DestroyItemForPlayer($player, $index);
        break;
      case "signal_jammer_blue":
        $index = SearchItemsForUniqueID($uniqueID, $player);
        if ($items[$index + 1] > 0 && GetItemGemState($mainPlayer, $items[$index], $index) == 0) --$items[$index + 1];
        elseif($items[$index + 1] > 0) {
          AddDecisionQueue("YESNO", $player, "if_you_want_to_remove_a_Steam_Counter_and_keep_" . CardLink($items[$index], $items[$index]) . "_and_keep_it_in_play?", 1);
          AddDecisionQueue("REMOVECOUNTERITEMORDESTROY", $player, $index, 1);
        } else {
          WriteLog(CardLink($items[$index], $items[$index]) . " was destroyed");
          DestroyItemForPlayer($player, $index);
        }
        break;
      case "runeblood_incantation_red":
      case "runeblood_incantation_yellow":
      case "runeblood_incantation_blue":
        $index = SearchAurasForUniqueID($uniqueID, $player);
        if ($index == -1) break;
        $auras = &GetAuras($player);
        if ($auras[$index + 2] == 0) DestroyAuraUniqueID($player, $uniqueID);
        else {
          --$auras[$index + 2];
          PlayAura("runechant", $player);
        }
        break;
      case "iyslander":
      case "iyslander_stormbind":
        PlayAura("frostbite", $otherPlayer, effectController: $player);
        break;
      case "pyroglyphic_protection_red":
      case "pyroglyphic_protection_yellow":
      case "pyroglyphic_protection_blue":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "haze_bending_blue":
        PlayAura("spectral_shield", $player);
        break;
      case "pack_call_yellow":
        $deck = new Deck($player);
        if ($deck->Reveal() && ModifiedPowerValue($deck->Top(), $player, "DECK", source: "pack_call_yellow") < 6) {
          $card = $deck->AddBottom($deck->Top(remove: true), "DECK");
          WriteLog(CardLink("pack_call_yellow", "pack_call_yellow") . " put " . CardLink($card, $card) . " on the bottom of your deck");
        }
        break;
      case "dromai":
      case "dromai_ash_artist":
        WriteLog(CardLink($parameter, $parameter) . " creates an " . CardLink("ash", "ash"));
        PutPermanentIntoPlay($player, "ash");
        break;
      case "burn_them_all_red":
        DealArcane(1, 1, "STATIC", $combatChain[0], false, $mainPlayer);
        break;
      case "mounting_anger_red":
      case "mounting_anger_yellow":
      case "mounting_anger_blue":
      case "rising_resentment_red":
      case "rising_resentment_yellow":
      case "rising_resentment_blue":
      case "soaring_strike_red":
      case "soaring_strike_yellow":
      case "soaring_strike_blue":
        $numDraconicLinks = NumDraconicChainLinks();
        MZMoveCard($mainPlayer, "MYHAND:type=AA;maxCost=" . ($numDraconicLinks > 0 ? $numDraconicLinks - 1 : -2), "MYBANISH,HAND,TT", may: true);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYBANISH", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "LASTMZINDEX", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "GETUNIQUEID", 1);
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $mainPlayer, $parameter . ",HIT", 1);
        break;
      case "flameborn_retribution_red":
        if (GetClassState($player, $CS_DamageTaken) > 0) MZMoveCard($player, "MYDISCARD:isSameName=phoenix_flame_red", "MYHAND", may: true);
        break;
      case "flamecall_awakening_red":
        if (GetClassState($player, $CS_NumRedPlayed) > 1 && CanRevealCards($player)) {
          MZMoveCard($player, "MYDECK:isSameName=phoenix_flame_red", "MYHAND", may: true);
          AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
        }
        return "";
      case "insidious_chill_blue":
        $index = SearchAurasForUniqueID($uniqueID, $player);
        if ($index == -1) break;
        $auras = &GetAuras($player);
        --$auras[$index + 2];
        PayOrDiscard($target, 2, true);
        if ($auras[$index + 2] == 0) {
          WriteLog(CardLink($auras[$index], $auras[$index]) . " is destroyed");
          DestroyAura($player, $index);
        }
        break;
      case "isenhowl_weathervane_red":
      case "isenhowl_weathervane_yellow":
      case "isenhowl_weathervane_blue":
        if ($parameter == "isenhowl_weathervane_red") $numFrostbite = 4;
        else if ($parameter == "isenhowl_weathervane_yellow") $numFrostbite = 3;
        else $numFrostbite = 2;
        PlayAura("frostbite", $target, $numFrostbite, effectController: $player);
        break;
      case "read_the_ripples_red":
      case "read_the_ripples_yellow":
      case "read_the_ripples_blue":
        $i = SearchAurasForUniqueID($uniqueID, $player);
        if ($i == -1) break;
        $auras = &GetAuras($player);
        if ($auras[$i] == "read_the_ripples_red") $numOpt = 3;
        else if ($auras[$i] == "read_the_ripples_yellow") $numOpt = 2;
        else $numOpt = 1;
        for ($j = 0; $j < $numOpt; ++$j) PlayerOpt($player, 1);
        AddDecisionQueue("DRAW", $player, "-", 1);
        DestroyAura($player, $i);
        break;
      case "crown_of_providence":
        BottomDeckMultizone($player, "MYHAND", "MYARS");
        AddDecisionQueue("DRAW", $player, "-", 1);
        break;
      case "fog_down_yellow":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "flex_red":
      case "flex_yellow":
      case "flex_blue":
        ChooseToPay($player, $parameter, "0,2");
        AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
        AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $player, "2", 1);
        break;
      case "fyendals_fighting_spirit_red":
      case "fyendals_fighting_spirit_yellow":
      case "fyendals_fighting_spirit_blue":
        if (PlayerHasLessHealth($player)) GainHealth(1, $player);
        break;
      case "brothers_in_arms_red":
      case "brothers_in_arms_yellow":
      case "brothers_in_arms_blue":
        ChooseToPay($player, $parameter, "0,1");
        AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
        break;
      case "sigil_of_protection_red":
      case "sigil_of_protection_yellow":
      case "sigil_of_protection_blue":
      case "sigil_of_brilliance_yellow":
      case "sigil_of_earth_blue":
      case "sigil_of_lightning_blue":
      case "sigil_of_the_arknight_blue":
      case "sigil_of_deadwood_blue":
      case "sigil_of_temporal_manipulation_blue":
      case "sigil_of_forethought_blue":
      case "sigil_of_cycles_blue":
      case "sigil_of_fyendal_blue":
        DestroyAuraUniqueID($player, $uniqueID); //destroy sigils at start of action phase
        break;
      case "dracona_optimai":
        $deck = new Deck($player);
        if ($deck->Reveal(3)) {
          $cards = explode(",", $deck->Top(amount: 3));
          $numRed = 0;
          for ($j = 0; $j < count($cards); ++$j) if (PitchValue($cards[$j]) == 1) ++$numRed;
          if ($numRed > 0) DealArcane($numRed * 2, 2, "ABILITY", $combatChain[0], false, $player);
        }
        break;
      case "tomeltai":
        $deck = new Deck($player);
        if ($deck->Reveal(2)) {
          $cards = explode(",", $deck->Top(amount: 2));
          $numRed = 0;
          for ($j = 0; $j < count($cards); ++$j) if (PitchValue($cards[$j]) == 1) ++$numRed;
          if ($numRed > 0) {
            $otherPlayer = $player == 1 ? 2 : 1;
            AddDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
            AddDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
            AddDecisionQueue("MODDEFCOUNTER", $otherPlayer, (-1 * $numRed), 1);
            AddDecisionQueue("DESTROYEQUIPDEF0", $player, "-", 1);
          }
        }
        break;
      case "dominia":
        $deck = new Deck($player);
        if ($deck->Reveal(1)) {
          if (PitchValue($deck->Top()) == 1) {
            $otherPlayer = $player == 1 ? 2 : 1;
            AddDecisionQueue("SHOWHANDWRITELOG", $otherPlayer, "<-", 1);
            AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
            AddDecisionQueue("CHOOSETHEIRHAND", $player, "<-", 1);
            AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
            AddDecisionQueue("MULTIBANISH", $otherPlayer, "HAND,-", 1);
          }
        }
        break;
      case "azvolai":
        $targetArr = explode(",", $target);
        DealArcane(1, 2, "PLAYCARD", $combatChain[0], false, $mainPlayer, resolvedTarget:$targetArr[0], useUIDs:true);
        if (isset($targetArr[1])) DealArcane(1, 2, "PLAYCARD", $combatChain[0], false, $mainPlayer, resolvedTarget:$targetArr[1], useUIDs:true);
        break;
      case "beaten_trackers":
        $index = FindCharacterIndex($player, $parameter);
        AddDecisionQueue("CHARREADYORPASS", $player, $index);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($parameter, $parameter)."_to_gain_an_action_point", 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
        AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
        AddDecisionQueue("GAINACTIONPOINTS", $player, 1, 1);
        AddDecisionQueue("WRITELOG", $player, "Player_" . $player . "_gained_an_action_point_from_" . CardLink("beaten_trackers", "beaten_trackers"), 1);
        break;
      case "skull_crack_red":
        GainResources($player, 1);
        break;
      case "berserk_yellow":
        $deck = new Deck($player);
        if ($deck->Reveal() && ModifiedPowerValue($deck->Top(), $player, "DECK", source: "berserk_yellow") >= 6) {
          Draw($player);
          WriteLog(CardLink($parameter, $parameter) . " drew a card");
        }
        break;
      case "reincarnate_red":
      case "reincarnate_yellow":
      case "reincarnate_blue":
        $index = SearchGetLastIndex(SearchMultizone($player, "MYDISCARD:cardID=" . $parameter));
        RemoveGraveyard($player, $index);
        $deck = new Deck($player);
        $deck->AddBottom($parameter, "GY");
        break;
      case "plasma_mainline_red":
        $targetIndex = SearchItemsForUniqueID($target, $player);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_move_a_steam_counter_to_" . CardLink($items[$targetIndex], $items[$targetIndex]));
        AddDecisionQueue("NOPASS", $player, "-");
        AddDecisionQueue("PASSPARAMETER", $player, $uniqueID . "," . $target, 1);
        AddDecisionQueue("SPECIFICCARD", $player, "PLASMAMAINLINE", 1);
        break;
      case "crankshaft_red":
      case "crankshaft_yellow":
      case "crankshaft_blue":
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS:isSameName=hyper_driver_red");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a ".Cardlink("hyper_driver", "hyper_driver")." to get a steam counter", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $player, "-", 1);
        break;
      case "hyper_driver_red":
      case "hyper_driver_yellow":
      case "hyper_driver_blue":
      case "hyper_driver":
        AddDecisionQueue("HYPERDRIVER", $player, $uniqueID, 1);
        break;
      case "arakni_huntsman":
      case "arakni":
        AddDecisionQueue("DECKCARDS", $otherPlayer, "0", 1);
        AddDecisionQueue("SETDQVAR", $player, "0", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose if you want to sink <0>", 1);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_sink_the_opponent's_card", 1);
        AddDecisionQueue("NOPASS", $player, $parameter, 1);
        AddDecisionQueue("WRITELOG", $player, "<b>Arakni</b> sunk the top card", 1);
        AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
        AddDecisionQueue("ADDBOTDECK", $otherPlayer, "Skip", 1);
        AddDecisionQueue("ELSE", $player, "-");
        AddDecisionQueue("WRITELOG", $player, "<b>Arakni</b> left the top card there", 1);
        break;
      case "vynnset_iron_maiden":
      case "vynnset":
        AddDecisionQueue("YESNO", $player, "if you want to pay 1 life for " . CardLink($parameter, $parameter), 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
        AddDecisionQueue("OP", $player, "LOSEHEALTH", 1);
        if (!SearchCurrentTurnEffects($parameter, $player)) { //The effect only apply to one event of damage. Anti-duplicate.
          AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $parameter, 1);
          $message = CardLink($parameter, $parameter) . "'s pain makes a runechant unpreventable!";
          AddDecisionQueue("WRITELOG", $player, "<b>$message</b>", 1);
        }
        break;
      case "hornets_sting":
        $deck = new Deck($player);
        if ($deck->Reveal()) {
          if (CardSubType($deck->Top()) == "Arrow") {
            if (IsAllyAttacking()) {
              $allyIndex = "THEIRALLY-" . $combatChainState[$CCS_WeaponIndex];
              AddDecisionQueue("PASSPARAMETER", $player, $allyIndex, 1);
            } else AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRCHAR:type=C", 1);
            AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target to deal 1 damage");
            AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
            AddDecisionQueue("MZDAMAGE", $player, "1,DAMAGE," . $parameter, 1);
            WriteLog(CardLink($parameter, $parameter) . " deals 1 damage");
          } else {
            WriteLog("The card was put on the bottom of your deck");
            $deck->AddBottom($deck->Top(remove: true), "DECK");
          }
        }
        break;
      case "heat_seeker_red":
        $deck = new Deck($player);
        if (!$deck->Empty() && !ArsenalFull($player)) AddArsenal($deck->Top(remove: true), $player, "DECK", "UP");
        break;
      case "wave_of_reality":
        PlayAura("spectral_shield", $player);
        break;
      case "tome_of_aeo_blue":
        Draw($player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "crown_of_dominion":
        PutItemIntoPlayForPlayer("gold", $player, mainPhase:"False", effectController: $player);
        WriteLog(CardLink($parameter, $parameter) . " created a Gold token");
        break;
      case "ponder":
        Draw($player, false);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "plague_hive_yellow":
        $rand = GetRandom(1, 3);
        switch ($rand) {
          case 1:
            $auraCreated = "inertia";
            break;
          case 2:
            $auraCreated = "frailty";
            break;
          case 3:
            $auraCreated = "bloodrot_pox";
            break;
          default:
            break;
        }
        WriteLog(CardLink("plague_hive_yellow", "plague_hive_yellow") . " created a " . CardLink($auraCreated, $auraCreated));
        PlayAura($auraCreated, $otherPlayer, effectController: $player);
        break;
      case "cyclone_roundhouse_yellow":
        $cardsToBanish = array();
        for ($i = 0; $i < count($chainLinks); $i++) {
          if (count($chainLinks[$i]) == ChainLinksPieces()) continue;
          $defendingCardIndices = GetDefendingCardsFromCombatChainLink($chainLinks[$i], $defPlayer);
          if (count($defendingCardIndices) > 0) {
            $randKeys = array_rand($defendingCardIndices, 1);
            $index = $defendingCardIndices[$randKeys];
            $chainLinks[$i][$index + 2] = "0";
            array_push($cardsToBanish, $chainLinks[$i][$index]);
          }
        }
        $defendingCards = GetChainLinkCards($defPlayer);
        if ($defendingCards != "") {
          $defendingCards = explode(",", $defendingCards);
          $randomIndex = GetRandom(0, count($defendingCards) - 1);
          AddDecisionQueue("PASSPARAMETER", $defPlayer, $defendingCards[$randomIndex]);
          AddDecisionQueue("REMOVECOMBATCHAIN", $defPlayer, "-", 1);
          array_push($cardsToBanish, $combatChain[$defendingCards[$randomIndex]]);
        }
        for ($i = 0; $i < count($cardsToBanish); $i++)
          BanishCardForPlayer($cardsToBanish[$i], $defPlayer, "CC");
        for ($i = 0; $i < count($cardsToBanish); $i++) {
          AddDecisionQueue("PASSPARAMETER", $defPlayer, $cardsToBanish[$i]);
          AddDecisionQueue("REMOVECOMBATCHAIN", $defPlayer, "-", 1);
          AddDecisionQueue("MULTIBANISH", $defPlayer, "CC,-", 1);
        }
        break;
      case "riptide_lurker_of_the_deep":
      case "riptide":
        SuperReload();
        break;
      case "crows_nest":
        $arsenal = &GetArsenal($player);
        AddDecisionQueue("YESNO", $player, "if you want to pay 1 to put an aim counter on the arrow");
        AddDecisionQueue("NOPASS", $player, "-");
        AddDecisionQueue("PAYRESOURCES", $player, "1", 1);
        AddDecisionQueue("PASSPARAMETER", $player, count($arsenal) - ArsenalPieces(), 1);
        AddDecisionQueue("ADDAIMCOUNTER", $player, "-", 1);
        break;
      case "wayfinders_crest":
        LookAtTopCard($player, "wayfinders_crest");
        break;
      case "buzzsaw_trap_blue":
        AddCurrentTurnEffect($parameter, $mainPlayer);
        TrapTriggered($parameter);
        break;
      case "collapsing_trap_blue":
        $hand = &GetHand($mainPlayer);
        $numDraw = count($hand) - 1;
        DiscardHand($mainPlayer);
        Draw($mainPlayer, num:$numDraw);
        if ($numDraw > 0) WriteLog("Player $mainPlayer discarded their hand and drew $numDraw cards");
        else WriteLog("Player $mainPlayer discarded their hand.");
        TrapTriggered($parameter);
        break;
      case "spike_pit_trap_blue":
        $deck = new Deck($mainPlayer);
        if (!$deck->Empty()) {
          $topDeck = $deck->Top(remove: true);
          AddGraveyard($topDeck, $mainPlayer, "DECK");
          $numName = SearchCount(SearchMultizone($mainPlayer, "MYDISCARD:isSameName=" . $topDeck));
          LoseHealth($numName, $mainPlayer);
          WriteLog(Cardlink($topDeck, $topDeck) . " was put in the graveyard. Player $mainPlayer lost $numName life");
        }
        else WriteLog("No card from deck to put into graveyayrd");
        TrapTriggered($parameter);
        break;
      case "boulder_trap_yellow":
        AddDecisionQueue("FINDINDICES", $mainPlayer, "EQUIP");
        AddDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
        AddDecisionQueue("MODDEFCOUNTER", $mainPlayer, "-1", 1);
        WriteLog(CardLink($parameter, $parameter) . " triggered and puts a -1 counter on an equipment");
        TrapTriggered($parameter);
        break;
      case "pendulum_trap_yellow":
        $deck = new Deck($mainPlayer);
        $rv = "put  ";
        for ($i = 0; $i < 2; ++$i) {
          $cardRemoved = $deck->Top(remove: true);
          AddGraveyard($cardRemoved, $mainPlayer, "DECK");
          if ($i == 0) $rv .= Cardlink($cardRemoved, $cardRemoved);
          else $rv .= " and " . Cardlink($cardRemoved, $cardRemoved) . " into the graveyard";
        }
        WriteLog($rv);
        TrapTriggered($parameter);
        break;
      case "tarpit_trap_yellow":
        AddCurrentTurnEffect($parameter, $mainPlayer);
        if (!IsAllyAttacking()) TrapTriggered($parameter);
        break;
      case "virulent_touch_red":
      case "virulent_touch_yellow":
      case "virulent_touch_blue":
        WriteLog(CardLink($parameter, $parameter) . " creates a ".CardLink($CID_BloodRotPox, $CID_BloodRotPox)." from being blocked from hand.");
        PlayAura($CID_BloodRotPox, $defPlayer, effectController:$mainPlayer);
        break;
      case "bloodrot_trap_red":
        PlayAura($CID_BloodRotPox, $mainPlayer, effectController: $defPlayer);
        TrapTriggered($parameter);
        break;
      case "frailty_trap_red":
        PlayAura($CID_Frailty, $mainPlayer, effectController: $defPlayer);
        TrapTriggered($parameter);
        break;
      case "inertia_trap_red":
        PlayAura($CID_Inertia, $mainPlayer, effectController: $defPlayer);
        TrapTriggered($parameter);
        break;
      case "vambrace_of_determination":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
        AddDecisionQueue("BUTTONINPUT", $player, "0,1");
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $parameter . "-BB", 1);
        AddDecisionQueue("PASSPARAMETER", $player, 1, 1);
        AddDecisionQueue("COMBATCHAINCHARACTERDEFENSEMODIFIER", $player, $target, 1);
        break;
      case "give_and_take_red":
        MZMoveCard($mainPlayer, "MYDISCARD:type=A;maxCost=" . CachedTotalPower()-1 . "&MYDISCARD:type=AA;maxCost=" . CachedTotalPower()-1, "MYTOPDECK", may: true);
        break;
      case "light_of_sol_yellow":
        $deck = new Deck($player);
        if ($deck->Reveal() && ColorContains($deck->Top(), 2, $player)) {
          AddDecisionQueue("YESNO", $player, "if_you_want_to_put_the_card_in_your_soul");
          AddDecisionQueue("NOPASS", $player, "-");
          AddDecisionQueue("PASSPARAMETER", $player, "MYDECK-0", 1);
          AddDecisionQueue("MZADDZONE", $player, "MYSOUL,DECK", 1);
          AddDecisionQueue("MZREMOVE", $player, "-", 1);
          AddDecisionQueue("WRITELOG", $player, "Added to soul by Light of Sol", 1);
        }
        break;
      case "prism_awakener_of_sol":
      case "prism_advent_of_thrones":
        MZMoveCard($player, "MYDECK:subtype=Figment", "MYPERMANENTS", may: true);
        AddDecisionQueue("PLAYABILITY", $player, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
        break;
      case "figment_of_ravages_yellow":
        DealArcane(1, 2, "PLAYCARD", "figment_of_ravages_yellow", false, $player, resolvedTarget: $target);
        break;
      case "suraya_archangel_of_knowledge":
        AddDecisionQueue("FINDINDICES", $player, "SOUL");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to banish");
        AddDecisionQueue("MAYCHOOSEMYSOUL", $player, "<-", 1);
        AddDecisionQueue("MULTIBANISHSOUL", $player, "-", 1);
        AddDecisionQueue("THREATENARCANE", $player, "$parameter,$target", 1);
        break;
      case "soulbond_resolve":
        Charge();
        break;
      case "banneret_of_courage_yellow":
        PlayAura("courage", $player);
        break;
      case "banneret_of_gallantry_yellow":
        PlayAura("quicken", $player);
        break;
      case "banneret_of_protection_yellow":
        PlayAura("spellbane_aegis", $player);
        break;
      case "banneret_of_resilience_yellow":
        AddCurrentTurnEffect("banneret_of_resilience_yellow", $player);
        break;
      case "banneret_of_salvation_yellow":
        AddCurrentTurnEffect("banneret_of_salvation_yellow", $player);
        break;
      case "banneret_of_vigor_yellow":
        AddCurrentTurnEffect("banneret_of_vigor_yellow", $player);
        break;
      case "scowling_flesh_bag":
        global $mainPlayer;
        $targetPlayer = str_contains($target, "THEIR") ? $mainPlayer : $player;
        Intimidate($targetPlayer);
        break;
      case $CID_BloodRotPox:
        $hand = &GetHand($player);
        $resources = &GetResources($player);
        if (Count($hand) > 0 || $resources[0] > 0) {
          AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_3_to_avoid_taking_2_damage", 0, 1);
          AddDecisionQueue("NOPASS", $player, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $player, "3", 1);
          AddDecisionQueue("PAYRESOURCES", $player, "3", 1);
          AddDecisionQueue("ELSE", $player, "-");
        }
        AddDecisionQueue("TAKEDAMAGE", $player, "2-bloodrot_pox", 1);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case $CID_Inertia:
        $deck = new Deck($player);
        WriteLog("Processing the end of turn effect of " . CardLink("inertia", "inertia") . ".");
        for ($i = 0; $i < count(GetArsenal($player)) + count(GetHand($player)); $i++) {
          BottomDeckMultizone($player, "MYHAND", "MYARS", true, "Choose a card from your hand or arsenal to add on the bottom of your deck");
        }
        AddDecisionQueue("WRITELOG", $player, ("The cards and arsenal of Player " . $player . " was put on the bottom of their deck."));
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case $CID_Frailty:
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "eloquence":
        global $CS_NextNAACardGoAgain;
        SetClassState($player, $CS_NextNAACardGoAgain, 1);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "blasmophet_levia_consumed":
        $banish = &GetBanish($player);
        for ($i = count($banish) - BanishPieces(); $i >= 0; $i -= BanishPieces()) {
          if ($banish[$i + 1] == "blasmophet_levia_consumed") {
            TurnBanishFaceDown($player, $i);
            break;
          }
        }
        break;
      case "firewall_red":
      case "firewall_yellow":
      case "firewall_blue":
        $deck = new Deck($player);
        if ($deck->Reveal()) {
          if (!SubtypeContains($deck->Top(), "Evo", $player)) {
            WriteLog("The card was put on the bottom of your deck");
            $deck->AddBottom($deck->Top(remove: true), "DECK");
          }
        }
        break;
      case "big_bertha_red":
      case "big_bertha_yellow":
      case "big_bertha_blue":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a Hyper Driver to get a steam counter", 1);
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS:isSameName=hyper_driver_red");
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $player, "-", 1);
        break;
      case "civic_peak":
        Draw($otherPlayer);
        break;
      case "civic_duty":
        PlayAura("vigor", $otherPlayer, effectController:$player);
        break;
      case "civic_guide":
        PlayAura("might", $otherPlayer, effectController:$player);
        break;
      case "tiger_eye_reflex_yellow":
      case "tiger_eye_reflex_blue":
        BanishCardForPlayer("crouching_tiger", $player, "-", "NT");
        break;
      case "civic_steps":
        PlayAura("quicken", $otherPlayer, effectController:$player);
        break;
      case "crowd_control_red":
      case "crowd_control_yellow":
      case "crowd_control_blue":
        ChooseToPay($player, $parameter, "0,3");
        AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "1", 1); // Technically wrong, it should be +1 for each opposing heroes
        break;
      case "adaptive_plating":
      case "ratchet_up_red":
      case "ratchet_up_yellow":
      case "ratchet_up_blue":
      case "soup_up_red":
      case "soup_up_yellow":
      case "soup_up_blue":
      case "torque_tuned_red":
      case "torque_tuned_yellow":
      case "torque_tuned_blue":
      case "cognition_field_red":
      case "cognition_field_yellow":
      case "cognition_field_blue":
      case "infuse_alloy_red":
      case "infuse_alloy_yellow":
      case "infuse_alloy_blue":
      case "infuse_titanium_red":
      case "infuse_titanium_yellow":
      case "infuse_titanium_blue":
      case "steel_street_hoons_blue": //Galvanize
        MZChooseAndDestroy($player, "MYITEMS", may: true, context: "Choose an item to galvanize for " . CardLink($parameter, $parameter) . " effect");
        AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
        break;
      case "golden_skywarden_yellow":
        $myItems = GetItems($player);
        $maxRepeats = count($myItems);
        // check to make sure it's still there before giving it a buff
        $foundSkywarden = false;
        for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
          if ($combatChain[$i] == "golden_skywarden_yellow") $foundSkywarden = true;
        }
        for ($i = 0; $i < count($myItems); $i += ItemPieces()) {
          if ($myItems[$i] == "golden_cog") ++$maxRepeats; // you can galvanize the gold made by glavanizing a cog
        }
        for ($i = 0; $i < $maxRepeats; $i += itemPieces()) {
          AddDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose an item to galvanize for " . CardLink($parameter, $parameter) . " effect", 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("GOLDENSKYWARDEN", $player, $target, 1);
          AddDecisionQueue("MZDESTROY", $player, "-", 1);
          AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
          if ($foundSkywarden) AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "1", 1);
        }
        break;
      case "teeth_of_the_cog_red":
      case "teeth_of_the_cog_yellow":
      case "teeth_of_the_cog_blue":
      case "tough_old_wrench_red":
      case "tough_old_wrench_yellow":
      case "tough_old_wrench_blue":
        MZChooseAndDestroy($player, "MYITEMS", may: true, context: "Choose an item to galvanize for " . CardLink($parameter, $parameter) . " effect");
        AddDecisionQueue("PASSPARAMETER", $player, "golden_cog", 1);
        AddDecisionQueue("PUTPLAY", $player, "0", 1);
        break;
      case "stasis_cell_blue":
        $index = SearchCharacterForUniqueID($target, $otherPlayer);
        if ($index != -1) {
          AddDecisionQueue("PASSPARAMETER", $otherPlayer, $index);
          AddDecisionQueue("ADDSTASISTURNEFFECT", $otherPlayer, "stasis_cell_blue-", 1);
        }
        break;
      case "evo_magneto_blue_equip":
        if (IsAllyAttacking()) {
          WriteLog("<span style='color:red;'>No damage is dealt because there is no attacking hero when allies attack.</span>");
        } else {
          $index = FindCharacterIndex($player, "evo_magneto_blue_equip");
          if ($index != -1) {
            CharacterChooseSubcard($player, $index, isMandatory: false);
            AddDecisionQueue("ADDDISCARD", $player, "CHAR", 1);
            AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRITEMS:minCost=0;maxCost=1", 1);
            AddDecisionQueue("SETDQCONTEXT", $player, "Choose an item to gain control.", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
            AddDecisionQueue("MZOP", $player, "GAINCONTROL", 1);
          }
        }
        break;
      case "fast_and_furious_red":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose an item with cranked to get a steam counter", 1);
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS:hasCrank=true");
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $player, "-", 1);
        break;
      case "kayo_armed_and_dangerous":
      case "kayo":
      case "canopy_shelter_blue":
      case "apex_bonebreaker":
        PlayAura("might", $player);
        WriteLog(CardLink($parameter, $parameter) . " created a " . CardLink("might", "might") . " token");
        break;
      case "pack_call_red":
      case "pack_call_blue":
        $deck = new Deck($player);
        if ($deck->Reveal() && ModifiedPowerValue($deck->Top(), $player, "DECK", source: $parameter) < 6) {
          $card = $deck->AddBottom($deck->Top(remove: true), "DECK");
          WriteLog(CardLink($parameter, $parameter) . " put " . CardLink($card, $card) . " on the bottom of your deck");
        }
        break;
      case "stonewall_impasse":
        Clash($parameter, effectController: $player);
        break;
      case "gauntlets_of_iron_will":
        if ($CombatChain->HasCurrentLink()) AddCurrentTurnEffect("gauntlets_of_iron_will," . CachedTotalPower(), $mainPlayer);
        break;
      case "golden_glare":
        $yellowPitchCards = 0;
        for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
          if (PitchValue($combatChain[$i]) == 2) ++$yellowPitchCards;
        }
        if ($yellowPitchCards >= 2) {
          PutItemIntoPlayForPlayer("gold", $player, effectController: $player);
          WriteLog(CardLink("golden_glare", "golden_glare") . " created a ".CardLink("gold", "gold")." token");
        }
        break;
      case "the_golden_son_yellow":
        PutItemIntoPlayForPlayer("gold", $player, effectController: $player);
        WriteLog(CardLink($parameter, $parameter) . " created a ".CardLink("gold", "gold")." token for Player " . $player);
        break;
      case "trounce_red":
        Clash($parameter, effectController: $player);
        break;
      case "thunk_red":
      case "thunk_yellow":
      case "thunk_blue":
        PlayAura("might", $player); 
        WriteLog(CardLink($parameter, $parameter) . " created a ".CardLink("might", "might")." token for Player " . $player);
        break;
      case "wallop_red":
      case "wallop_yellow":
      case "wallop_blue":
        PlayAura("vigor", $player); 
        WriteLog(CardLink($parameter, $parameter) . " created a ".CardLink("vigor", "vigor")." token for Player " . $player);
        break;
      case "commanding_performance_red":
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose which card you want to destroy from their arsenal", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZDESTROY", $player, false, 1);
        break;
      case "no_fear_red":
        $banish = &GetBanish($player);
        $hand = &GetHand($player);
        for ($i = count($banish) - BanishPieces(); $i >= 0; $i -= BanishPieces()) {
          if ($banish[$i + 1] == "NOFEAR") {
            array_push($hand, $banish[$i]);
            RemoveBanish($player, $i);
          }
        }
        break;
      case "wall_of_meat_and_muscle_red":
        if (CountAura("might", $player) > 0) MZMoveCard($player, "MYDISCARD:type=AA", "MYTOPDECK", may: true);
        break;
      case "run_into_trouble_red":
        if (IsAllyAttacking()) {
          WriteLog("<span style='color:red;'>No damage is dealt because there is no attacking hero when allies attack.</span>");
        } else if (CountAura("agility", $player) > 0) {
          WriteLog(CardLink($parameter, $parameter) . " deals 1 damage");
          DealDamageAsync($otherPlayer, 1, "DAMAGE", $parameter);
        }
        break;
      case "hearty_block_red":
        if (CountAura("vigor", $player) > 0) GainHealth(1, $player);
        break;
      case "test_of_agility_red":
      case "clash_of_might_red":
      case "clash_of_might_yellow":
      case "clash_of_might_blue":
      case "test_of_might_red":
      case "clash_of_agility_red":
      case "clash_of_agility_yellow":
      case "clash_of_agility_blue":
      case "clash_of_vigor_red":
      case "clash_of_vigor_yellow":
      case "clash_of_vigor_blue":
      case "test_of_vigor_red":
      case "test_of_strength_red":
      case "clash_of_mountains_red":
      case "clash_of_mountains_yellow":
      case "clash_of_mountains_blue":
      case "clash_of_bravado_yellow":
      case "test_of_iron_grip_red":
      case "daily_grind_blue":
      case "clash_of_heads_yellow":
      case "clash_of_chests_yellow":
      case "clash_of_arms_yellow":
      case "clash_of_legs_yellow":
      case "clash_of_shields_yellow":
        Clash($parameter, effectController: $player);
        break;
      case "nasty_surprise_blue":
        PlayAura("agility", $player);
        PlayAura("might", $player);
        PlayAura("vigor", $player);
        WriteLog(CardLink($parameter, $parameter) . " created an " . CardLink("agility", "agility") . ", " . CardLink("might", "might") . " and " . CardLink("vigor", "vigor") . " tokens.");
        break;
      case "standing_order_red":
        MZMoveCard($player, "MYARS", "MYBOTDECK", may: true, silent: true);
        AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
        AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $player, "2", 1);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
        break;
      case "hide_tanner":
        $index = FindCharacterIndex($player, $parameter);
        AddDecisionQueue("CHARREADYORPASS", $player, $index);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink($parameter, $parameter)."_to_gain_2_".CardLink("might", "might")."_tokens", 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, $index, 1);
        AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
        AddDecisionQueue("PLAYAURA", $player, "might-2", 1);
        AddDecisionQueue("WRITELOG", $player, "Player_" . $player . "_gained_2_".Cardlink("might", "might")."_tokens_from_" . CardLink("hide_tanner", "hide_tanner"), 1);
        break;
      case "nuu_alluring_desire":
      case "nuu":
        NuuStaticAbility($target);
        break;
      case "meridian_pathway":
        AddDecisionQueue("YESNO", $player, "if_you_want_" . CardLink("meridian_pathway", "meridian_pathway") . "_to_gain_Ward_3");
        AddDecisionQueue("NOPASS", $player, "-");
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "MERIDIANWARD", 1);
        break;
      case "waning_vengeance_red":
      case "waning_vengeance_yellow":
      case "waning_vengeance_blue":
        if (SearchPitchForColor($player, 3) > 0) PlayAura("spectral_shield", $player);
        break;
      case "attune_with_cosmic_vibrations_blue":
        $index = GetCombatChainIndex($parameter, $player);
        $chainCard = $CombatChain->Card($index);
        $chainCard->ModifyDefense(3);
        break;
      case "second_tenet_of_chi_moon_blue":
        Draw($player, effectSource:$parameter);
        WriteLog(CardLink($parameter, $parameter) . " draw a card.");
        break;
      case "essence_of_ancestry_body_red":
      case "essence_of_ancestry_soul_yellow":
      case "essence_of_ancestry_mind_blue":
        $numAuras = CountControlledAuras($player);
        if ($numAuras == 0) {
          AddCurrentTurnEffect($parameter, $player, "PLAY");
        }
        break;
      case "haunting_specter_red":
      case "haunting_specter_yellow":
      case "haunting_specter_blue":
        if (SearchAura($player, class: "ILLUSIONIST") < 0) PlayAura("spectral_shield", $player, numPowerCounters: 1);
        else PlayAura("spectral_shield", $player);
        break;
      case "vengeful_apparition_red":
      case "vengeful_apparition_yellow":
      case "vengeful_apparition_blue":
        AddCurrentTurnEffect($parameter . "-INST", $player, "PLAY");
        break;
      case "stride_of_reprisal":
        AddPlayerHand("crouching_tiger", $player, $parameter);
        break;
      case "mask_of_wizened_whiskers":
        MZMoveCard($player, "MYDISCARD:comboOnly=true", "MYBOTDECK");
        break;
      case "traverse_the_universe":
        MZMoveCard($player, "MYDECK:isSameName=MST099_inner_chi_blue", "MYHAND", may: true);
        AddDecisionQueue("SHUFFLEDECK", $player, "-");
        break;
      case "stonewall_gauntlet":
        if (HasIncreasedAttack()) {
          AddCurrentTurnEffect($parameter, $otherPlayer);
        }
        break;
      case "battlefront_bastion_blue":
      case "battlefront_bastion_red":
      case "battlefront_bastion_yellow":
        AddCurrentTurnEffect($parameter, $player, "CC");
        IncrementClassState($player, $CS_DamagePrevention, 1);
        break;
      case "stone_rain_red":
        $otherPlayer = $player == 1 ? 2 : 1;
        $banish = &GetBanish($otherPlayer);
        $hand = &GetHand($otherPlayer);
        for ($i = count($banish) - BanishPieces(); $i >= 0; $i -= BanishPieces()) {
          if ($banish[$i + 1] == "STONERAIN") {
            array_push($hand, $banish[$i]);
            RemoveBanish($otherPlayer, $i);
          }
        }
        break;
      case "helm_of_halos_grace":
        Charge();
        AddDecisionQueue("ALLCARDPITCHORPASS", $player, "2", 1);
        AddDecisionQueue("DRAW", $player, "-", 1);
        break;
      case "bracers_of_bellonas_grace":
        Charge();
        AddDecisionQueue("ALLCARDPITCHORPASS", $player, "2", 1);
        AddDecisionQueue("PLAYAURA", $player, "courage", 1);
        break;
      case "warpath_of_winged_grace":
        Charge();
        AddDecisionQueue("ALLCARDPITCHORPASS", $player, "2", 1);
        AddDecisionQueue("PLAYAURA", $player, "quicken-1", 1); 
        break;
      case "arc_lightning_yellow":
        DealArcane(1, 2, "PLAYCARD", "arc_lightning_yellow");
        break;
      case "verdance_thorn_of_the_rose": 
      case "verdance":
        if(GetCharacterGemState($player, $parameter) != 0) {
          AddDecisionQueue("YESNO", $player, "if you want " . CardLink($parameter, $parameter) . " to deal arcane damage");
          AddDecisionQueue("NOPASS", $player, "-");
        }
        if (str_contains($target, "THEIRCHAR")) {
          $target = "THEIRCHARUID-" . explode("-", $target)[1];
        }
        if (str_contains($target, "MYCHAR")) {
          $target = "MYCHARUID-" . explode("-", $target)[1];
        }
        AddDecisionQueue("VERDANCE", $player, "$parameter,$target", 1);
        break;
      case "felling_of_the_crown_red":
        Decompose($player, "FELLINGOFTHECROWN");
        break;
      case "plow_under_yellow":
        Decompose($player, "PLOWUNDER");
        break;
      case "summers_fall_red":
      case "summers_fall_yellow":
      case "summers_fall_blue":
        $params = explode("-", $target);
        $zone = substr($params[0], 0, 5);
        if($zone == "THEIR") {
          $index = SearchAurasForUniqueID(isset($params[1]) ? $params[1] : -1, $otherPlayer);
        }
        else {
          $index = SearchAurasForUniqueID(isset($params[1]) ? $params[1] : -1, $player);
          $zone = "MY";
        }
        if($target == "NONE") {
          Decompose($player, "SUMMERSFALL", $target);
        }
        elseif($index >= 0) {
          Decompose($player, "SUMMERSFALL", $zone . "AURAS-" . $index);
        }
        else {
          WriteLog(CardLink($parameter, $parameter) . " layer fails as there are no remaining targets for the targeted effect.");
        }
        break;
      case "blossoming_decay_red":
      case "blossoming_decay_yellow":
      case "blossoming_decay_blue":
        Decompose($player, "BLOSSOMINGDECAY");
        break;
      case "cadaverous_tilling_red":
      case "cadaverous_tilling_yellow":
      case "cadaverous_tilling_blue":
        Decompose($player, "CADAVEROUSTILLING");
        break;
      case "channel_the_millennium_tree_red":
        if ($additionalCosts == "CHANNEL") {
          ChannelTalent($target, "EARTH");
        }
        else AddCurrentTurnEffect($parameter, $player);
        break;
      case "earths_embrace_blue":
        PlayAura("embodiment_of_earth", $player);
        if(GetClassState($player, $CS_NumEarthBanished) == 0) DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "harvest_season_red":
      case "harvest_season_yellow":
      case "harvest_season_blue":
        $numHealthPointsGained = match ($parameter) {"harvest_season_red" => 3, "harvest_season_yellow" => 2, "harvest_season_blue" => 1};
        DestroyAuraUniqueID($player, $uniqueID);
        GainHealth($numHealthPointsGained, $player);
        break;
      case "strong_yield_red":
      case "strong_yield_yellow":
      case "strong_yield_blue":
        DestroyAuraUniqueID($player, $uniqueID);
        AddCurrentTurnEffect($parameter, $player, "PLAY");
        break;
      case "flash_of_brilliance":
        $hand = SearchHand($player, talent: "LIGHTNING");
        if (count(explode(",", $hand)) > 0) {
          AddDecisionQueue("FINDINDICES", $player, "HAND", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose a lightning card from your hand to discard.", 1);
          MZMoveCard($player, "MYHAND:talent=LIGHTNING", "MYDISCARD", may:true, logText:"Card discarded: <0>", isSubsequent:true);
          AddDecisionQueue("SETDQCONTEXT", $player, "Return an Aura to your hand.", 1);
          MZMoveCard($player, "MYAURAS", "MYHAND", logText:"Aura returned: <0>", isSubsequent:true);
        }
        break;
      case "gone_in_a_flash_red":
        AddDecisionQueue("YESNO", $mainPlayer, "if you want to return ".CardLink("gone_in_a_flash_red", "gone_in_a_flash_red")." to your hand?");
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        AddDecisionQueue("GONEINAFLASH", $mainPlayer, "-", 1);
        break;
      case "channel_lightning_valley_yellow":
        if ($additionalCosts == "CHANNEL") {
          ChannelTalent($target, "LIGHTNING");
        }
        else {
          WriteLog(CardLink($parameter, $parameter) . " draws a card");
          Draw($player);
        }
        break;
      case "blast_to_oblivion_red":
      case "blast_to_oblivion_yellow":
      case "blast_to_oblivion_blue":
        $otherPlayer = $player == 1 ? 2 : 1;
        $targetedPlayer = intval(explode("-", $target)[0]);
        $notTargetedPlayer = $targetedPlayer == 1 ? 2 : 1;
        $uID = explode("-", $target)[1];
        $auras = &GetAuras($targetedPlayer);
        for ($i = 0; $i < count($auras); $i += AuraPieces()) {
          if ($auras[$i + 6] == $uID) {
            $cardID = $auras[$i];
            $cardOwner = substr($auras[$i+9], 0, 5) == "THEIR" ? $notTargetedPlayer : $targetedPlayer;
            $lastResult = RemoveAura($targetedPlayer, $i);
            AddPlayerHand($cardID, $cardOwner, "-");
            return $lastResult;
          }
        }
        WriteLog("The target for " . CardLink($parameter, $parameter) . " has been removed, effect fizzling");
        break;
      case "electromagnetic_somersault_red":
      case "electromagnetic_somersault_yellow":
      case "electromagnetic_somersault_blue":
        if (count($chainLinks) > 0) { //only do this if the chain wasn't forced closed
          $prevLink = $chainLinks[count($chainLinks) - 1];
          $indices = array();
          $index = -1;
          for ($i = 0; $i < count($prevLink); $i += ChainLinksPieces()) {
            if ($target == $prevLink[$i+7] && $prevLink[$i+2] == 1) {
              array_push($indices, $i);
            }
          }
          if (count($indices) == 1) {
            $index = $indices[0];
          }
          else if (count($indices) > 1) { //if there are two copies of the same card on the link, assume the player chose their own card
            // fix later
            foreach ($indices as $i) {
              if ($prevLink[$i + 1] == $player) $index = $i; 
            }
            if ($index == -1) $index = $indices[0];
          }
          if ($index != -1)
          {
            $player = $prevLink[$index + 1];
            // if it was played from an opponent's zone
            if (DelimStringContains($prevLink[$index+3], "THEIR", true)) {
              $player = $player == 1 ? 2 : 1;
            }
            AddPlayerHand($target, $player, "CC");
            $chainLinks[count($chainLinks) - 1][$index + 2] = 0;
          }
        }
        break;
      case "face_purgatory":
        if(!IsAllyAttacking()) PummelHit($otherPlayer);
        Draw($player);
        break;
      case "malefic_incantation_red":
      case "malefic_incantation_yellow":
      case "malefic_incantation_blue":
        // this is the only place that it will destroy if there are no counters. may need to refactor if anything ever can remove counters arbitrarly.
        $index = SearchAurasForUniqueID($uniqueID, $player);
        if ($index == -1) break;
        $auras = &GetAuras($player);
        --$auras[$index + 2];
        PlayAura("runechant", $player);
        if ($auras[$index + 2] == 0) DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "arcane_cussing_red":
      case "arcane_cussing_yellow":
      case "arcane_cussing_blue":
        $numRunechantsCreated = match ($parameter) {"arcane_cussing_red" => 3, "arcane_cussing_yellow" => 2, "arcane_cussing_blue" => 1};
        PlayAura("runechant", $player, $numRunechantsCreated);
        break;
      case "aether_bindings_of_the_third_age":
        WriteLog(CardLink("aether_bindings_of_the_third_age", "aether_bindings_of_the_third_age") . " <b>amp 1</b>");
        $index = -1;
        for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
          if (explode(",", $currentTurnEffects[$i])[0] == "aether_bindings_of_the_third_age" && $currentTurnEffects[$i + 1] == $player) $index = $i;
        }
        if ($index == -1) AddCurrentTurnEffect("aether_bindings_of_the_third_age,1", $player);
        else {
          $num = explode(",", $currentTurnEffects[$index])[1];
          $num = is_numeric($num) ? intval($num) + 1 : 1;
          $currentTurnEffects[$index] = "aether_bindings_of_the_third_age,$num";
        }
        break;
      case "sigil_of_aether_blue":
        if($target != "-") DealArcane(1, 2, "STATIC", "sigil_of_aether_blue", false, $player, resolvedTarget:$target);
        else DestroyAuraUniqueID($player, $uniqueID); //destroy sigils at start of action phase
        break;
      case "truce_blue":
        if($target == "truce_blue-1") {
        WriteLog("🤝 Congrats! You didn't kill each other!");
        DestroyAuraUniqueID($defPlayer, $uniqueID);
        // effect controller performs the instruction first
        GainHealth(3, $defPlayer);
        GainHealth(3, $mainPlayer);
        }
        else {
          DestroyAuraUniqueID($defPlayer, $uniqueID);
          Draw($defPlayer, true);
        }
        break;
      case "hard_knuckle":
        $index = FindCharacterIndex($mainPlayer, "hard_knuckle");
        AddDecisionQueue("YESNO", $mainPlayer, "to_destroy_".Cardlink($parameter, $parameter));
        AddDecisionQueue("NOPASS", $mainPlayer, "-");
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $index, 1);
        AddDecisionQueue("DESTROYCHARACTER", $mainPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $mainPlayer, "hard_knuckle", 1);
        break;
      case "spark_spray_red":
      case "spark_spray_yellow":
      case "spark_spray_blue":
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $mainPlayer, $parameter, 1);
        break;
      case "heavy_industry_surveillance":
        $deck = GetDeck($player);
        if (isset($deck[0])) {
          $topCard = $deck[0];
          AddDecisionQueue("DECKCARDS", $defPlayer, "0");
          AddDecisionQueue("YESNO", $defPlayer, "if_you_want_to_banish_the_top_card_of_your_deck_with_" . CardLink($parameter, $parameter), 1);
          AddDecisionQueue("NOPASS", $defPlayer, "-", 1);
          AddDecisionQueue("PARAMDELIMTOARRAY", $defPlayer, "0", 1);
          AddDecisionQueue("MULTIREMOVEDECK", $defPlayer, "0", 1);
          AddDecisionQueue("MULTIBANISH", $defPlayer, "DECK,-", 1);
          AddDecisionQueue("SETDQVAR", $defPlayer, "0", 1);
          AddDecisionQueue("WRITELOG", $defPlayer, "<0> was banished.", 1);
          if (ClassContains($topCard, "MECHANOLOGIST", $player)) {
            AddDecisionQueue("ADDCURRENTTURNEFFECT", $defPlayer, "heavy_industry_surveillance", 1);
          }
        }
        break;
      case "heavy_industry_ram_stop":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose how much to pay for " . CardLink($parameter, $parameter));
        AddDecisionQueue("BUTTONINPUT", $player, "0,1");
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("LESSTHANPASS", $player, "1", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $parameter, 1);
        break;
      case "brutus_summa_rudis":
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYCHAR:type=C&THEIRCHAR:type=C", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose which hero win the clash", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("BRUTUS", $player, $target, 1);
        break;
      case "jarl_vetreidi":
        FrostBiteExposed($otherPlayer, $player);
        break;
      case "unforgetting_unforgiving_red":
        if(!IsAllyAttacking() && SearchCharacter($otherPlayer, hasNegCounters: true) != "") {
          $search = "MYDECK:cardID=mangle_red";
          $fromMod = "Deck,NT"; //pull it out of the deck, playable "Next Turn"
          AddDecisionQueue("YESNO", $player, "if_you_want_to_search_for_a_".CardLink("mangle_red", "mangle_red") ."_and_banish_it");
          AddDecisionQueue("NOPASS", $player, "-");
          AddDecisionQueue("MULTIZONEINDICES", $player, $search, 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZBANISH", $player, $fromMod, 1);
          AddDecisionQueue("MZREMOVE", $player, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $player, "-", 1);
        }
        break;
      case "crumble_to_eternity_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "ollin_ice_cap":
        PlayAura("frostbite", $mainPlayer, effectController: $defPlayer);
        break;
      case "tectonic_crust":
        PlayAura("seismic_surge", $defPlayer, effectController: $defPlayer);
        break;
      case "root_bound_trunks":
        PlayAura("embodiment_of_earth", $defPlayer, effectController: $defPlayer);
        break;
      case "schism_of_chaos_blue":
        AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-");
        AddDecisionQueue("ADDARSENALFROMDECK", $mainPlayer, "DECK-DOWN");
        AddDecisionQueue("SHUFFLEDECK", $defPlayer, "-");
        AddDecisionQueue("ADDARSENALFROMDECK", $defPlayer, "DECK-DOWN");
        break;
      case "mask_of_deceit":
        $char = &GetPlayerCharacter($player);
        if (CheckMarked($mainPlayer) && !IsAllyAttacking()) AddDecisionQueue("CHOOSECARD", $player, "arakni_black_widow,arakni_funnel_web,arakni_orb_weaver,arakni_redback,arakni_tarantula,arakni_trap_door");
        else AddDecisionQueue("PASSPARAMETER", $player, -1);
        AddDecisionQueue("CHAOSTRANSFORM", $player, $char[0], 1);
        break;
      case "bite_red":
      case "bite_yellow":
      case "bite_blue":
        ThrowWeapon("Dagger", $parameter, target:$target);
        break;
      case "hunted_or_hunter_red":
        WriteLog("The Hunter has become the hunted");
        LoseHealth(1, $mainPlayer);
        if (!IsAllyAttacking()) TrapTriggered($parameter);
        break;
      case "blood_runs_deep_red":
        ThrowWeapon("Dagger", "blood_runs_deep_red");
        ThrowWeapon("Dagger", "blood_runs_deep_red");
        break;
      case "ignite_red":
        AddCurrentTurnEffect("ignite_red", $player);
        break;
      case "prowess_of_agility_blue":
        $index = SearchAurasForUniqueID($uniqueID, $player);
        AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink("prowess_of_agility_blue","prowess_of_agility_blue")."_and_draw");
        AddDecisionQueue("NOPASS", $player, "-");
        AddDecisionQueue("PASSPARAMETER", $player, "$index", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $player, "MYAURAS-", 1);
        AddDecisionQueue("MZDESTROY", $player, "-", 1);
        AddDecisionQueue("DRAW", $player, "-", 1);
      case "kabuto_of_imperial_authority":
        AddCurrentTurnEffect($parameter, $mainPlayer);
        break;
      case "sharpened_senses_yellow":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "smoke_out_red":
        MarkHero($mainPlayer);
        break;
      case "fealty":
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "leap_frog_vocal_sac":
      case "leap_frog_slime_skin":
      case "leap_frog_gloves":
      case "leap_frog_leggings":
        AddDecisionQueue("YESNO", $player, "if_you_want_to_add_".Cardlink($parameter,$parameter)."_to_active_chain_link");
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("LEAPFROG", $player, $parameter, 1);
        break;
      case "lair_of_the_spider_red":
      case "den_of_the_spider_red":
        WriteLog("The Hunter stumbles into the spider");
        MarkHero($mainPlayer);
        if (!IsAllyAttacking()) TrapTriggered($parameter);
        break;
      case "thick_hide_hunter_yellow":
        DiscardRandom();
        break;
      case "chain_reaction_yellow":
        $arsenal = &GetArsenal($player);
        for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
          if (CardType($arsenal[$i]) == "A" && $arsenal[$i + 1] == "DOWN"){
            AddDecisionQueue("YESNO", $player, "if_you_want_to_turn_your_arsenal_face_up");
            AddDecisionQueue("NOPASS", $player, "-");
            AddDecisionQueue("TURNARSENALFACEUP", $player, $i, 1);
            AddDecisionQueue("PASSPARAMETER", $player, $arsenal[$i + 5], 1);
            AddDecisionQueue("CHAINREACTION", $player, "-", 1);
          }
        }
        if (!IsAllyAttacking()) TrapTriggered($parameter);
        break;
      case "war_cry_of_bellona_yellow":
        $attackGoAgain = DoesAttackHaveGoAgain() && HasWard($combatChain[0], $mainPlayer);
        DealDamageAsync($mainPlayer, $target, "DAMAGE", "war_cry_of_bellona_yellow");
        if ($attackGoAgain) GainActionPoints(1, $mainPlayer); //handles the attack getting destroyed, LKI applies
        break;
      case "douse_in_runeblood_red":
        $startingRunechants = CountAura("runechant", $player);
        PlayAura("runechant", $player, GetClassState($player, $CS_NumNonAttackCards), isToken:true);
        if (CountAura("runechant", $player) - $startingRunechants >= 3) GiveAttackGoAgain();
        break;
      case "ring_of_roses_yellow":
        GainHealth(1, $player);
        break;
      case "null_time_zone_blue":
        AddDecisionQueue("INPUTCARDNAME", $player, "-");
        AddDecisionQueue("SETDQVAR", $player, "0");
        AddDecisionQueue("WRITELOG", $player, "📣<b>{0}</b> was chosen");
        AddDecisionQueue("NULLTIMEZONE", $player, SearchItemForLastIndex($parameter, $player).",{0}");
        break;
      case "zap_clappers":
        if (CanRevealCards($player) && !IsAllyAttacking()) {
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose an instant to reveal", 1);
          AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND:type=I");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
          AddDecisionQueue("REVEALCARDS", $player, "-", 1);
          AddDecisionQueue("DEALARCANE", $player, "1-zap_clappers-TRIGGER", 1);
        }
        break;
      case "starlight_striders":
        if (CanRevealCards($player)) {
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose an instant to reveal", 1);
          AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND:type=I");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
          AddDecisionQueue("REVEALCARDS", $player, "-", 1);
          AddDecisionQueue("PLAYAURA", $player, "embodiment_of_lightning", 1);
        }
        break;
      case "channel_mount_heroic_red":
        ChannelTalent($target, "EARTH");
        break;
      case "channel_lake_frigid_blue":
      case "channel_mount_isen_blue":
        ChannelTalent($target, "ICE");
        break;
      case "terra":
        TerraEndPhaseAbility($parameter, $player);
        break;
      case "riches_of_tropal_dhani_yellow":
        PutItemIntoPlayForPlayer("gold", $player);      
        break;
      case "hoist_em_up_red":
        $inds = GetUntapped($defPlayer, "MYALLY");
        if (strlen($inds) > 0) {
          AddDecisionQueue("SETDQCONTEXT", $defPlayer, "choose an ally to tap (or pass)");
          AddDecisionQueue("PASSPARAMETER", $defPlayer, $inds, 1);
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $defPlayer, "<-", 1);
          AddDecisionQueue("MZTAP", $defPlayer, "<-", 1);
          AddDecisionQueue("PASSPARAMETER", $defPlayer, $target, 1);
          AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $defPlayer, 1, 1);
        }
        break;
      case "puffin_hightail":
      case "puffin":
        Draw($player, effectSource:$parameter);
        WriteLog("Player " . $player . " drew a card from " . CardLink($parameter, $parameter));
        break;
      case "marlynn_treasure_hunter":
      case "marlynn":
        LoadArrow($player);
        break;
      case "scurv_stowaway":
        GainResources($player, 1);
        break;
      case "sunken_treasure_blue":
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRDISCARD&MYDISCARD");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to turn face-down");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZOP", $player, "TURNDISCARDFACEDOWN", 1);
        AddDecisionQueue("SPECIFICCARD", $player, "SUNKENTREASURE", 1);
        break;
      case "breaker_helm_protos":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a Hyper Driver to discard (or pass)");
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND:isSameName=hyper_driver_red", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
        AddDecisionQueue("ADDDISCARD", $player, "-", 1);
        AddDecisionQueue("DRAW", $player, "", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "breaker_helm_protos", 1);
        break;
      case "drive_brake":
        $char = &GetPlayerCharacter($player);
        if ($char[$target + 4] < 0) ++$char[$target + 4];
        break;
      case "fist_pump":
        $char = &GetPlayerCharacter($player);
        $index = SearchCharacterIndexSubtype($player, "Wrench");
        AddCurrentTurnEffect($parameter, $player, "", $char[$index + 11]);
        break;
      case "treasure_island":
        $treasureID = SearchLandmarksForID("treasure_island");
        if ($treasureID != -1) {
          $numGold = min($additionalCosts, $landmarks[$treasureID + 3]);
          $landmarks[$treasureID + 3] -= $numGold;
          PutItemIntoPlayForPlayer("gold", $player, number:$numGold, isToken:true);
          WriteLog("Player $player plundered $numGold " . CardLink("gold", "gold") . " from " . CardLink("treasure_island", "treasure_island"));
        }
        break;
      case "fiddlers_green_red": case "fiddlers_green_yellow": case "fiddlers_green_blue":
        $healthGain = match ($parameter) {
          "fiddlers_green_red" => 3,
          "fiddlers_green_yellow" => 2,
          "fiddlers_green_blue" => 1,
        };
        GainHealth($healthGain, $player);
        break;
      case "sirens_of_safe_harbor_red": case "sirens_of_safe_harbor_yellow": case "sirens_of_safe_harbor_blue":
        GainHealth(1, $player);
        break;
      case "sea_legs_yellow":
        PutItemIntoPlayForPlayer("goldkiss_rum", $player, effectController:$player, isToken:true);
        break;
      case "fools_gold_yellow":
        PutItemIntoPlayForPlayer("gold", $player, effectController:$player, isToken:true);
        break;
      case "draw_a_crowd_blue":
        AddCurrentTurnEffect($parameter, $player);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "promising_terrain_blue":
        DestroyAuraUniqueID($player, $uniqueID);
        $numSeismic = CountAura("seismic_surge", $player);
        if ($numSeismic >=3) {
          Draw($player, effectSource:$parameter);
          GainHealth(1, $player);
        }
        break;
      case "surface_shaking_blue":
        $numSeismic = CountAura("seismic_surge", $player);
        AddDecisionQueue("FINDINDICES", $player, "HAND");
        AddDecisionQueue("PREPENDLASTRESULT", $player, $numSeismic . "-", 1);
        AddDecisionQueue("MULTICHOOSEHAND", $player, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $player, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $player, "SURFACESHAKING", 1);
        DestroyAuraUniqueID($player, $uniqueID);
        break;
      case "crash_and_bash_red":
      case "crash_and_bash_yellow":
      case "crash_and_bash_blue":
        if (CanRevealCards($player)) {
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card with crush to reveal", 1);
          AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND:hasCrush=true");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZOP", $player, "GETCARDID", 1);
          AddDecisionQueue("REVEALCARDS", $player, "-", 1);
          AddDecisionQueue("PLAYAURA", $player, "seismic_surge", 1);
        }
        break;
      case "loan_shark_yellow":
        DestroyAuraUniqueID($player, $uniqueID);
        WriteLog("Resolving " . CardLink($parameter, $parameter) . " ability");
        PummelHit($player, true, context:"Choose a card to discard (or pass and lose 2 health)");
        AddDecisionQueue("NOTEQUALPASS", $player, "PASS");
        AddDecisionQueue("PASSPARAMETER", $player, "2", 1);
        AddDecisionQueue("OP", $mainPlayer, "PLAYERLOSEHEALTH", 1);
        break;
      case "riddle_with_regret_red":
        WriteLog("You are riddled with the regret of $additionalCosts auras");
        LoseHealth($additionalCosts, $player);
        if($additionalCosts >= 3) {
          $controller = str_contains($uniqueID, "MYAURAS") ? $player : $otherPlayer;
          $uniqueID = str_contains($uniqueID, "-") ? explode("-", $uniqueID)[1] : "-";
          DestroyAuraUniqueID($controller, $uniqueID);
        }
        break;
      case "escalate_bloodshed_red":
        Draw($player, effectSource:$parameter);
        break;
      case "return_fire_red":
        MZMoveCard($player, "MYHAND:subtype=Arrow", "MYBANISH,HAND,RETURNFIRE", may:true, DQContext:"Choose an arrow to banish (or pass)", passSearch:false);
        break;
      case "cogwerx_tinker_rings":
        PutItemIntoPlayForPlayer("golden_cog", $player);
        break;
      case "washed_up_wave":
      case "blood_in_the_water_red":
        $hand = &GetHand($player);
        $handCount = count($hand);
        if ($handCount == 0) {
          AddDecisionQueue("PASSPARAMETER", $player, "MYDECK-0", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to destroy from the top of your deck (or pass)", 1);
        } 
        else {
          AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND", 1);
          AddDecisionQueue("APPENDLASTRESULT", $player, ",MYDECK-0", 1);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card to discard from your hand or destroy from the top of your deck (or pass)", 1);
        }
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("SETDQVAR", $player, "0", 1);
        AddDecisionQueue("MZADDZONE", $player, "MYDISCARD,{0}", 1);
        AddDecisionQueue("MZSETDQVAR", $player, "0", 1);
        AddDecisionQueue("WRITELOG", $player, "Card chosen: <0>", 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
        AddDecisionQueue("ALLCARDWATERYGRAVEORPASS", $player, "<-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, $target, 1);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $player, "2", 1);
        break;
      case "pinion_sentry_blue":
        $inds = GetUntapped($player, "MYITEMS", "subtype=Cog");
        if(empty($inds)) break;
        AddDecisionQueue("PASSPARAMETER", $player, $inds);
        AddDecisionQueue("SETDQCONTEXT", $player, "Tap a cog to create a ".CardLink("golden_cog", "golden_cog")." (or pass)", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZTAP", $player, "<-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "golden_cog", 1);
        AddDecisionQueue("PUTPLAY", $player, "0", 1);
        break;
      case "on_the_horizon_red":
      case "on_the_horizon_yellow":
      case "on_the_horizon_blue":
      case "helmsmans_peak":
        LookAtTopCard($player, $parameter, setPlayer:$player);
        break;
      case "lost_in_transit_yellow":
        AddDecisionQueue("YESNO", $player, "if you want to remove a gold counter from " . CardLink("treasure_island", "treasure_island"), 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("REMOVETREASUREISLANDCOUNTER", $player, 1, 1);
        break;
      case "entangling_shot_red":
      case "nettling_shot_red":
        $zone = explode("-", $target)[0];
        $uid = explode("-", $target)[1];
        $otherPlayer = $player == 1 ? 2 : 1;
        switch ($zone) {
          case "THEIRALLY":
            $MZIndex = "$zone-" . SearchAlliesForUniqueID($uid, $otherPlayer);
            break;
          case "MYALLY":
            $MZIndex = "$zone-" . SearchAlliesForUniqueID($uid, $player);
            break;
          case "THEIRCHAR":
            $MZIndex = "$zone-" . SearchCharacterForUniqueID($uid, $otherPlayer);
            break;
          case "THEIRCHARUID":
            $MZIndex = "$zone-" . SearchCharacterForUniqueID($uid, $otherPlayer);
            break;
          case "MYCHAR":
            $MZIndex = "$zone-" . SearchCharacterForUniqueID($uid, $player);
            break;
          case "MYCHARUID":
            $MZIndex = "$zone-" . SearchCharacterForUniqueID($uid, $player);
            break;
        }
        Tap($MZIndex, $player);
        break;
      case "light_fingers":
        if (!IsAllyAttacking()) {
          AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "THEIRITEMS:type=T;cardID=gold");
          AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $defPlayer, "GAINCONTROL", 1);
        }
        break;
      case "anka_drag_under_yellow":
        PummelHit($player, context: "Discard a card to " . CardLink("anka_drag_under_yellow", "anka_drag_under_yellow") . " effect.");
        break;
      case "WATERYGRAVE":
        $grave = &GetDiscard($player);
        $index = SearchDiscardForUniqueID($target, $player);
        if ($index != -1) {
          $grave[$index + 2] = "DOWN";
        }
        break;
      case "tricorn_of_saltwater_death":
        if (SearchHand($player, hasWateryGrave: true) != "") {
            AddDecisionQueue("FINDINDICES", $player, "HANDWATERYGRAVE,-,NOPASS");
            AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card with watery grave to discard");
            AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
            AddDecisionQueue("MULTIREMOVEHAND", $player, "-", 1);
            AddDecisionQueue("DISCARDCARD", $player, "HAND-" . $player, 1);
            AddDecisionQueue("DRAW", $player, "-", 1);
        }
        break;
    case "light_it_up_yellow":
        AddCurrentTurnEffect("light_it_up_yellow", $player);
        AddNextTurnEffect("light_it_up_yellow", $player);
        break;
      case "jolly_bludger_yellow":
        // for some reason GetItems is overwriting the turn player's items
        // $items = GetItems($defPlayer);
        // I'm adjusting MZOP-GAINCONTROL to just skip if nothing is passed to it
        // so we don't need to explicitly truncate the number of loops
        $reps = $target;//min($target, intdiv(count($items), ItemPieces()));
        for ($i = 0; $i < $reps; ++$i) {
          AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRITEMS", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZOP", $player, "GAINCONTROL", 1);
        }
        break;
      case "valda_brightaxe":
      case "valda_seismic_impact":
        PlayAura("seismic_surge", $otherPlayer, $additionalCosts, effectSource:$parameter);
        break;
      case "lyath_goldmane":
      case "lyath_goldmane_vile_savant":
        PlayAura("might", $player, isToken:true, effectController:$player, effectSource:$parameter);
        break;
      case "kayo_underhanded_cheat":
      case "kayo_strong_arm":
        PlayAura("vigor", $player, isToken:true, effectController:$player, effectSource:$parameter);
        break;
      case "pleiades":
      case "pleiades_superstar":
        PlayAura("confidence", $player, isToken:true, effectController:$player, effectSource:$parameter);
        break;
      case "tuffnut":
      case "tuffnut_bumbling_hulkster":
        PlayAura("toughness", $player, isToken:true, effectController:$player, effectSource:$parameter);
        break;
      case "dig_in_red":
      case "dig_in_yellow":
      case "dig_in_blue":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a number of resources to pay");
        AddDecisionQueue("CHOOSENUMBER", $player, "0,1,2,3", 1);
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $player, "DIGIN,$parameter", 1);
        break;
      case "base_of_the_mountain":
        $search = "MYHAND:type=AA&MYHAND:type=A";
        $fromMod = "Hand,MOUNTAIN";
        $choices = SearchMultizone($player, $search);
        if ($choices != "") {
          $numChoices = count(explode(",", $choices));
          for ($i = 0; $i < $numChoices; ++$i) {
            AddDecisionQueue("MULTIZONEINDICES", $player, $search, 1);
            AddDecisionQueue("SETDQCONTEXT", $player, "Banish an action from hand. (Cards will be added as blocking in the order you banish them)", 1);
            AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
            AddDecisionQueue("MZBANISH", $player, $fromMod, 1);
            AddDecisionQueue("MZREMOVE", $player, "-", 1);
          }
          AddDecisionQueue("SPECIFICCARD", $player, "MOUNTAINBASE", 1);
          AddDecisionQueue("ELSE", $player, "-");
          AddDecisionQueue("SPECIFICCARD", $player, "MOUNTAINBASE", 1);
        }
        break;
      case "dimenxxional_crossroads_yellow":
        DealArcane(1, 0, "PLAYCARD", "dimenxxional_crossroads_yellow", resolvedTarget:$target);
        break;
      case "pec_perfect_red":
        Clash($parameter, $player);
        break;
      case "ley_line_of_the_old_ones_blue":
        if ($uniqueID == "-") PlayAura("seismic_surge", $player, isToken:true, effectController:$player, effectSource:$parameter);
        else if (CountAura("seismic_surge", $player) == 0) DestroyAuraUniqueID($player, explode("-", $uniqueID)[1]);
        break;
      case "sunkwater_lookout":
      case "sunkwater_exoshell":
      case "sunkwater_pincers":
      case "sunkwater_scalers":
        $arsenal = GetArsenal($player);
        $inds = [];
        for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
          if ($arsenal[$i + 1] == "UP") array_push($inds, "MYARS-$i");
        }
        if (count($inds) > 0) {
          $context = "Choose a face up card in your arsenal to sink";
          AddDecisionQueue("PASSPARAMETER", $player, implode(",", $inds), 1);
          AddDecisionQueue("SETDQCONTEXT", $player, $context, 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZREMOVE", $player, "-", 1);
          AddDecisionQueue("ADDBOTDECK", $player, "-", 1);
          AddDecisionQueue("DRAW", $player, "-", 1);
          CombatChainDefenseModifier($target, 1);
        }
        break;
      case "call_for_backup_red":
        $names = [];
        $discard = GetDiscard($player);
        for ($i = 0; $i < count($discard); $i += DiscardPieces()) {
          if (!isFaceDownMod($discard[$i + 2])) {
            $cardName = NameOverride($discard[$i], $player);
            if (TypeContains($discard[$i], "AA") && $cardName != "") {
              if (!in_array($cardName, $names)) {
                array_push($names, $cardName);
              }
            }
          }
        }
        $numToChoose = count($names) >= 2 ? 2 : 1;
        AddDecisionQueue("FINDINDICES", $player, "MYDISCARDATTACKS");
        AddDecisionQueue("PREPENDLASTRESULT", $player, "$numToChoose-", 1);
        AddDecisionQueue("APPENDLASTRESULT", $player, "-$numToChoose", 1);
        AddDecisionQueue("MULTICHOOSEDISCARD", $player, "<-", 1);
        if ($numToChoose == 2) AddDecisionQueue("VALIDATEALLDIFFERENTNAME", $player, "DISCARD,$numToChoose", 1);
        AddDecisionQueue("IMPLODELASTRESULT", $player, ",THEIRDISCARD-", 1);
        AddDecisionQueue("PREPENDLASTRESULT", $player, "THEIRDISCARD-", 1);
        AddDecisionQueue("SETDQVAR", $player, "0", 1);
        AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose a card to banish (other card will be put on top of deck)", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-", 1);
        AddDecisionQueue("BACKUP", $player, "{0}", 1);
        break;
      case "alpha_instinct_blue":
        PlayAura("might", $player, isToken:true, effectController:$player, effectSource: $parameter);
        break;
      case "decimator_great_axe":
        $zone = explode("-", $target)[0];
        $uid = explode("-", $target)[1];
        AddCurrentTurnEffect($parameter, $player, '', $uid);
        break;
      case "halo_of_lumina_light":
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYBANISH:pitch=2;subtype=Aura");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a yellow aura to put into play");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
        AddDecisionQueue("PUTPLAY", $player, "0", 1);
        break;
      case "talisman_of_cremation_blue":
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card name to banish with Talisman of Cremation");
        AddDecisionQueue("INPUTCARDNAME", $player, "-");
        AddDecisionQueue("SETDQVAR", $player, "0", 1);
        AddDecisionQueue("WRITELOG", $player, "<b>📣{0}</b> is being cremated!", 1);
        AddDecisionQueue("SPECIFICCARD", $player, "CREMATION", 1);
        $index = SearchItemsForUniqueID($uniqueID, $player);
        if ($index != -1) DestroyItemForPlayer($player, $index);
        break;
      default:
        break;
    }
  }
}

function ProcessAttackTrigger($cardID, $player, $target="-", $uniqueID = -1)
{
  global $mainPlayer, $defPlayer, $combatChain, $CS_SeismicSurgesCreated, $CS_NumSeismicSurgeDestroyed, $CS_DamageDealt, $CS_ArcaneDamageDealt;
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") $card->ProcessAttackTrigger($target, $uniqueID);
  switch($cardID) {
    case "phantasmaclasm_red":
      if(IsHeroAttackTarget()) {
        AddDecisionQueue("SHOWHANDWRITELOG", $defPlayer, "<-", 1);
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
        AddDecisionQueue("CHOOSETHEIRHAND", $player, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
        AddDecisionQueue("SETDQVAR", $player, "0", 1);
        AddDecisionQueue("WRITELOG", $player, "<0> was put on the bottom of the deck.", 1);
        AddDecisionQueue("ADDBOTDECK", $defPlayer, "Skip", 1);
        AddDecisionQueue("DRAW", $defPlayer, "-");
        }
      break;
    case "emissary_of_moon_red":
    case "emissary_of_tides_red":
    case "emissary_of_wind_red":
      AddDecisionQueue("MULTIZONEINDICES", $player, "MYHAND");
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose which cards to put on the bottom of your deck (or pass)", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZADDZONE", $player, "MYBOTDECK", 1);
      AddDecisionQueue("MZREMOVE", $player, "-", 1);
      if ($cardID == "emissary_of_moon_red") AddDecisionQueue("DRAW", $player, "-", 1);
      elseif ($cardID == "emissary_of_tides_red") AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $cardID, 1);
      elseif ($cardID == "emissary_of_wind_red") AddDecisionQueue("OP", $player, "GIVEATTACKGOAGAIN", 1);
      break;
    case "second_strike_red":
    case "second_strike_yellow":
    case "second_strike_blue":
      if ((GetClassState($player, $CS_DamageDealt) + GetClassState($player, $CS_ArcaneDamageDealt)) > 0) {
        AddCurrentTurnEffect($cardID, $player);
      }
      if (GetClassState($player, $CS_DamageDealt) + GetClassState($player, $CS_ArcaneDamageDealt) > 0) GiveAttackGoAgain();
      break;
    case "unsheathed_red":
      CacheCombatResult();
      if (IsWeaponGreaterThanTwiceBasePower()) GiveAttackGoAgain(); // borrowing ideas from merciless battleaxe (merciless_battleaxe) and shift the tide of battle (shift_the_tide_of_battle_yellow)
      break;
    case "mocking_blow_red":
    case "mocking_blow_yellow":
    case "mocking_blow_blue":
      if(PlayerHasLessHealth($defPlayer)) {
        BOO($mainPlayer);
      }
      break;
    case "bask_in_your_own_greatness_red":
    case "bask_in_your_own_greatness_yellow":
    case "bask_in_your_own_greatness_blue":
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a number of resources to pay");
      AddDecisionQueue("CHOOSENUMBER", $player, "0,1,2,3", 1);
      AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
      AddDecisionQueue("SPECIFICCARD", $player, "BASK,$cardID", 1);
      break;
    case "overcrowded_blue":
      $uniqueAuras = [];
      for($player = 1; $player < 3; ++$player) {
        $auras = GetAuras($player);
        for($i = 0; $i < count($auras); $i += AuraPieces()) {
          $name = NameOverride($auras[$i], $player);
          if (TypeContains($auras[$i], "T") && !in_array($name, $uniqueAuras)) {
            array_push($uniqueAuras, $name);
          }
        }
        $character = GetPlayerCharacter($player);
        for($i = 0; $i < count($character); $i += CharacterPieces()) {
          $name = NameOverride($character[$i], $player);
          if (TypeContains($character[$i], "T") && !in_array($name, $uniqueAuras) && SubtypeContains($character[$i], "Aura", $player)) {
            array_push($uniqueAuras, $name);
          }
        }
      }
      $index = -1;
      if ($target == "-") $index = 0;
      else {
        for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
          if ($combatChain[$i+7] == $target) $index = $i;
        }
      }
      if ($index != -1) {
        $combatChain[$index + 5] += count($uniqueAuras);
        $combatChain[$index + 6] += count($uniqueAuras);
      }
      break;
    case "hostile_encroachment_red":
      Draw($defPlayer, effectSource:$cardID);
      break;
    case "aftershock_red":
    case "aftershock_yellow":
    case "aftershock_blue":
      if (GetClassState($player, $CS_SeismicSurgesCreated) > 0 || CountAura("seismic_surge", $player) > 0 || GetClassState($player, $CS_NumSeismicSurgeDestroyed) > 0) {
        PlayAura("seismic_surge", $player, isToken:true, effectController:$player, effectSource:$cardID);
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
        //may be needed if the player is on always pass priority. This is causing the resolution step to sometimes get skipped if the attacking player is on always hold priority
        ResetCombatChainState();
        BeginTurnPass();
      }
    }
  } else if ($turn[0] == "A") {
    $currentPlayer = $mainPlayer;
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
  global $mainPlayer, $layers;
  $prioritySetting = HoldPrioritySetting($player);
  if ($player == $mainPlayer && count($layers) == LayerPieces() && $layers[0] == "RESOLUTIONSTEP") return 1;
  if ($prioritySetting == 0 || $prioritySetting == 1) return 1;
  if (($prioritySetting == 2 || $prioritySetting == 3) && $player != $mainPlayer) return 1;
  return 0;
}

function GiveAttackGoAgain()
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
}

function GiveAttackDominate()
{
  global $combatChainState, $CCS_CachedDominateActive;
  $combatChainState[$CCS_CachedDominateActive] = 1;
}

function TopDeckToArsenal($player)
{
  $deck = new Deck($player);
  if (ArsenalFull($player) || $deck->Empty()) return;
  AddArsenal($deck->Top(remove: true), $player, "DECK", "DOWN");
}

function DiscardHand($player, $mainPhase = true)
{
  $hand = &GetHand($player);
  for ($i = count($hand) - HandPieces(); $i >= 0; $i -= HandPieces()) {
    DiscardCard($player, $i, mainPhase: $mainPhase);
  }
}

function Opt($cardID, $amount)
{
  global $currentPlayer;
  PlayerOpt($currentPlayer, $amount);
}

function PlayerOpt($player, $amount, $optKeyword = true)
{
  global $CS_CardsInDeckBeforeOpt;
  $char = GetPlayerCharacter($player);
  $heroID = ShiyanaCharacter($char[0]);
  $heroStatus = $char[1];
  $deck = new Deck($player);
  SetClassState($player, $CS_CardsInDeckBeforeOpt, $deck->RemainingCards());
  AddDecisionQueue("FINDINDICES", $player, "DECKTOPXREMOVE," . $amount);
  AddDecisionQueue("OPT", $player, "<-", 1);
  if ($optKeyword && $heroID == "blaze_firemind" && $heroStatus < 3) AddDecisionQueue("BLAZE", $player, $amount, 1);
}

function BanishRandom($player, $source)
{
  $hand = &GetHand($player);
  if (count($hand) == 0) return "";
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
  if ($player == "") $player = $currentPlayer;
  if ($effectController == "") $effectController = $currentPlayer;
  $hand = &GetHand($player);
  if (count($hand) == 0) return "";
  if (count($hand) > 1) $index = GetRandom(0, count($hand) - 1);
  else $index = 0;
  $discarded = $hand[$index];
  unset($hand[$index]);
  $hand = array_values($hand);
  CardDiscarded($player, $discarded, $source);
  AddGraveyard($discarded, $player, "HAND", $effectController);
  DiscardedAtRandomEffects($player, $discarded, $source);
  return $discarded;
}

function DiscardedAtRandomEffects($player, $discarded, $source)
{
  global $mainPlayer;
  if (SearchCurrentTurnEffects("berserk_yellow", $player) && ModifiedPowerValue($discarded, $player, "GY", "HAND") >= 6) {
    $index = SearchGetFirstIndex(SearchMultizone($player, "MYDISCARD:cardID=" . $discarded));
    RemoveGraveyard($player, $index);
    BanishCardForPlayer($discarded, $player, "GY", "-", $player);
    AddLayer("TRIGGER", $player, "berserk_yellow");
  }
  $character = GetPlayerCharacter($player);
  $index = FindCharacterIndex($player, "beaten_trackers");
  if ($index >= 0 && IsCharacterAbilityActive($player, $index, checkGem: true) && $player == $mainPlayer && ModifiedPowerValue($discarded, $player, "GY", "HAND") >= 6) {
    AddLayer("TRIGGER", $player, $character[$index]);
  }
  $index = FindCharacterIndex($player, "hide_tanner");
  if ($index >= 0 && IsCharacterAbilityActive($player, $index, checkGem: true) && $player == $mainPlayer && ModifiedPowerValue($discarded, $player, "GY", "HAND") >= 6) {
    AddLayer("TRIGGER", $player, $character[$index]);
  }
  switch ($discarded) {
    case "skull_crack_red":
      AddLayer("TRIGGER", $player, $discarded);
      break;
    case "reincarnate_red":
    case "reincarnate_yellow":
    case "reincarnate_blue":
      AddLayer("TRIGGER", $player, $discarded);
      break;
    default:
      break;
  }
}

function DiscardCard($player, $index, $source = "", $effectController = "", $mainPhase = true)
{
  $hand = &GetHand($player);
  $discarded = RemoveHand($player, $index);
  CardDiscarded($player, $discarded, $source, mainPhase: $mainPhase);
  AddGraveyard($discarded, $player, "HAND", $effectController);
  return $discarded;
}

function CardDiscarded($player, $discarded, $source = "", $mainPhase = true)
{
  global $CS_Num6PowDisc, $mainPlayer;
  AddEvent("DISCARD", $discarded);
  $modifiedAttack = ModifiedPowerValue($discarded, $player, "HAND", $source);
  if ($modifiedAttack >= 6) {
    $character = &GetPlayerCharacter($player);
    $characterID = ShiyanaCharacter($character[0]);
    if (($characterID == "rhinar_reckless_rampage" || $characterID == "rhinar" || $characterID == "rhinar") && $character[1] == 2 && $player == $mainPlayer && $mainPhase) {
      AddLayer("TRIGGER", $mainPlayer, $character[0]);
    } else if (($characterID == "kayo_armed_and_dangerous" || $characterID == "kayo") && $character[1] == 2 && $player == $mainPlayer && $mainPhase) {
      AddLayer("TRIGGER", $mainPlayer, $character[0]);
      $character[1] = 1;
    }
    IncrementClassState($player, $CS_Num6PowDisc);
  }
  switch ($discarded) {
    case "massacre_red":
      if ($source != "" && ClassContains($source, "BRUTE", $mainPlayer) && CardType($source) == "AA") {
        WriteLog(CardLink("massacre_red", "massacre_red") . " intimidated because it was discarded by a Brute attack action card.");
        AddLayer("TRIGGER", $mainPlayer, $discarded);
      }
      break;
    case "alpha_instinct_blue":
      if (HasBeatChest($source)) AddLayer("TRIGGER", $player, $discarded);
      break;
    default:
      break;
  }
  
  WriteLog(CardLink($discarded, $discarded) . " was discarded");
}

function ModifiedPowerValue($cardID, $player, $from, $source = "", $index=-1)
{
  global $CS_Num6PowBan, $CombatChain, $currentTurnEffects;
  if ($cardID == "") return 0;
  $power = PowerValue($cardID, $player, $from);
  if ($cardID == "mutated_mass_blue") $power = SearchPitchForNumCosts($player) * 2;
  else if ($cardID == "fractal_replication_red") $power = FractalReplicationStats("Power");
  else if ($cardID == "spectral_procession_red") $power = CountAura("spectral_shield", $player);
  else if ($cardID == "diabolic_offering_blue") $power = GetClassState($player, $CS_Num6PowBan) > 0 ? 6 : 0;
  
  if ($index != -1) {
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
      if ($currentTurnEffects[$i] == "kayo_underhanded_cheat" || $currentTurnEffects[$i] == "kayo_strong_arm") {
        if ($currentTurnEffects[$i + 2] == $CombatChain->Card($index)->UniqueID()) {
          $power = 6;
        }
      }
    }
  }
  else if ($cardID == "nitro_mechanoidb") {
    $ind = SearchCharacterForCards($player, "nitro_mechanoida");
    $power = 5;
    if ($ind != "") {
      $char = GetPlayerCharacter($player);
      $subcards = $char[$ind+10];
      foreach($subcards as $subcard) {
        switch ($subcard) {
          case "galvanic_bender":
            ++$power;
            break;
          default:
            break;
        }
      }
    }
  }
  if ($from != "CC") {
    $char = &GetPlayerCharacter($player);
    $characterID = ShiyanaCharacter($char[0]);
    if (($characterID == "kayo_armed_and_dangerous" || $characterID == "kayo") && $char[1] < 3 && CardType($cardID) == "AA") ++$power;
    $power += ItemsPowerModifiers($cardID, $player, $from);
  } else {
    $power += EffectDefenderPowerModifiers($cardID);
  }
  return $power;
}

function ModifiedBlockValue($cardID, $player, $from, $source="", $uniqueID=-1)
{
  global $currentTurnEffects;
  if ($cardID == "") return 0;
  $block = BlockValue($cardID);
  if ($from == "CC") {
    if ($cardID == "quickdodge_flexors" && SearchCurrentTurnEffects("quickdodge_flexors", $player)) $block = 2;
    $char = GetPlayerCharacter($player);
    if ($char[1] < 3) {
      switch ($char[0]) {
        case "lyath_goldmane":
        case "lyath_goldmane_vile_savant":
          $block = ceil($block / 2);
          break;
        default:
          break;
      }
    }
  }
  if ($uniqueID != -1) {
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
      if ($currentTurnEffects[$i + 2] == $uniqueID) {
        switch ($currentTurnEffects[$i]) {
          case "decimator_great_axe":
            $block = ceil($block / 2);
            break;
          default:
            break;
        }
      }
    }
  }
  return $block;
}

function Intimidate($player = "")
{
  global $currentPlayer, $defPlayer, $CS_HaveIntimidated;
  IncrementClassState($currentPlayer, $CS_HaveIntimidated);
  if (!ShouldAutotargetOpponent($currentPlayer) && $player == "") {
    AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=C&THEIRCHAR:type=C", 1);
    AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose hero to intimidate.", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
    AddDecisionQueue("INTIMIDATE", $currentPlayer, "-", 1);
    return;
  }

  if ($player != "") AddDecisionQueue("INTIMIDATE", $currentPlayer, $player, 1);
  else AddDecisionQueue("INTIMIDATE", $currentPlayer, $defPlayer, 1);
}

function GamblersGlovesReroll($player, $target){
  $gamblersGlovesIndex = FindCharacterIndex($player, "gamblers_gloves");
  AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_".Cardlink("gamblers_gloves", "gamblers_gloves")."_to_reroll_the_result");
  AddDecisionQueue("NOPASS", $player, "-");
  AddDecisionQueue("PASSPARAMETER", $player, $gamblersGlovesIndex, 1);
  AddDecisionQueue("DESTROYCHARACTER", $player, "-", 1);
  AddDecisionQueue("REROLLDIE", $target, "1", 1);
}

function DestroyFrozenArsenal($player)
{
  $arsenal = &GetArsenal($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    if ($arsenal[$i + 4] == "1") {
      DestroyArsenal($player, effectController: $otherPlayer);
    }
  }
}

function CanGainAttack($cardID)
{
  global $combatChain, $mainPlayer;
  if (SearchCurrentTurnEffects("buzzsaw_trap_blue", $mainPlayer)) return false;
  return !SearchCurrentTurnEffects("chokeslam_red", $mainPlayer) || CardType($combatChain[0]) != "AA";
}

function CanGainBlock($cardID) {
  global $CombatChain, $mainPlayer;
  if ($CombatChain->AttackCard()->ID() == "smash_with_big_rock_yellow") return false;
  if (SearchCurrentTurnEffects("beat_of_the_ironsong_blue-BLOCK", $mainPlayer)) return false;
  return true;
}

function IsWeaponGreaterThanTwiceBasePower()
{
  global $combatChain, $mainPlayer, $CS_NumCharged, $CS_NumYellowPutSoul;
  if (count($combatChain) == 0) return false;
  if (TypeContains($combatChain[0], "W", $mainPlayer) && CachedTotalPower() > (PowerValue($combatChain[0], $mainPlayer, "CC") * 2)) return true;
  $char = &GetPlayerCharacter($mainPlayer);
  if (isset($char[CharacterPieces()]) && $char[CharacterPieces()] == "raydn_duskbane" && GetClassState($mainPlayer, $CS_NumCharged) > 0) return true;
  if (isset($char[CharacterPieces()]) && $char[CharacterPieces()] == "beaming_blade" && GetClassState($mainPlayer, $CS_NumYellowPutSoul) > 0) return true;
  return false;
}

function HasEnergyCounters($array, $index)
{
  switch ($array[$index]) {
    case "fyendals_spring_tunic":
    case "alluvion_constellas":
    case "blaze_firemind":
    case "geyser_of_seismic_stirrings_red":
    case "geyser_of_seismic_stirrings_yellow":
    case "geyser_of_seismic_stirrings_blue":
      return $array[$index + 2] > 0;
    default:
      return false;
  }
}

function HasPowerCounters($zone, $array, $index)
{
  switch ($zone) {
    case "AURAS":
      return $array[$index + 3] > 0;
    default:
      return false;
  }
}

function IsEnergyCounters($cardID)
{
  switch ($cardID) {
    case "fyendals_spring_tunic":
    case "alluvion_constellas":
    case "blaze_firemind":
      return true;
    default:
      return false;
  }
}

function HasHauntCounters($cardID)
{
  switch ($cardID) {
    case "ghostly_touch":
      return true;
    default:
      return false;
  }
}

function HasVerseCounters($cardID)
{
  switch ($cardID) {
    case "runeblood_incantation_red":
    case "runeblood_incantation_yellow":
    case "runeblood_incantation_blue":
    case "malefic_incantation_red":
    case "malefic_incantation_yellow":
    case "malefic_incantation_blue":
      return true;
    default:
      return false;
  }
}

function HasDoomCounters($cardID)
{
  switch ($cardID) {
    case "looming_doom_blue":
    case "chains_of_mephetis_blue":
      return true;
    default:
      return false;
  }
}

function HasRustCounters($cardID)
{
  switch ($cardID) {
    case "talishar_the_lost_prince":
      return true;
    default:
      return false;
  }
}

function HasFlowCounters($cardID)
{
  switch ($cardID) {
    case "channel_mount_heroic_red":
    case "channel_lake_frigid_blue":
    case "channel_thunder_steppe_yellow":
    case "channel_the_bleak_expanse_blue":
    case "channel_the_millennium_tree_red":
    case "channel_lightning_valley_yellow":
    case "channel_mount_isen_blue":
      return true;
    default:
      return false;
  }
}

function HasFrostCounters($cardID)
{
  switch ($cardID) {
    case "insidious_chill_blue":
      return true;
    default:
      return false;
  }
}

function HasBalanceCounters($cardID)
{
  switch ($cardID) {
    case "zen_state":
    case "preach_modesty_red":
      return true;
    default:
      return false;
  }
}

function HasBindCounters($cardID)
{
  switch ($cardID) {
    case "spellbound_creepers":
      return true;
    default:
      return false;
  }
}

function HasStainCounters($cardID)
{
  switch ($cardID) {
    case "blood_splattered_vest":
      return true;
    default:
      return false;
  }
}

function HasGoldCounters($cardID)
{
  switch ($cardID) {
    case "treasure_island":
      return true;
    default:
      return false;
  }
}

function HasSteamCounter($array, $index, $player)
{
  if (CardType($array[$index]) == 'E') return EquipmentsUsingSteamCounter($array[$index]);
  if (ClassContains($array[$index], "MECHANOLOGIST", $player)) {
    if ($array[$index] == "nitro_mechanoida") return false;
    if (CardType($array[$index]) == 'W') return $array[$index + 2] > 0;
    if (SubtypeContains($array[$index], "Item", $player)) return $array[$index + 1] > 0;
  }
  return false;
}

function ProcessMeld($player, $parameter, $additionalCosts="", $target="-")
{
  // handles running the left side of meld cards
  global $CS_ArcaneDamageDealt, $CS_HealthGained, $CS_AdditionalCosts, $CS_ArcaneDamageTaken;
  $otherPlayer = $player == 1 ? 2 : 1;
  switch ($parameter) {
    case "thistle_bloom__life_yellow":
      PlayAura("runechant", $player, GetClassState($player, $CS_HealthGained));
      break;
    case "arcane_seeds__life_red":
      PlayAura("runechant", $player);
      PlayAura("runechant", $player);
      break;
    case "vaporize__shock_yellow":
      $arcaneDamageDealt = GetClassState($player, $CS_ArcaneDamageDealt);
      AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRAURAS:minCost=0;maxCost=" . $arcaneDamageDealt . "&MYAURAS:minCost=0;maxCost=" . $arcaneDamageDealt, 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose an aura with cost $arcaneDamageDealt or less to destroy, or pass (tokens are chosen next)", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      for($i=0; $i<$arcaneDamageDealt; ++$i) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "THEIRAURAS:type=T&MYAURAS:type=T");
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a token aura to destroy, or pass", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZDESTROY", $player, "-", 1);
      }
      break;
    case "burn_up__shock_red":
      AddCurrentTurnEffect($parameter, $player);
      break;
    case "rampant_growth__life_yellow":
      $ampAmount = GetClassState($player, $CS_HealthGained);
      AddCurrentTurnEffect($parameter . "," . $ampAmount, $player, "ABILITY");
      WriteLog(CardLink($parameter, $parameter) . " <b>amp " . $ampAmount . "</b>");
      break;
    case "pulsing_aether__life_red":
      $meldState = (GetClassState($player, $CS_AdditionalCosts) == "Both") ? "I,A" : "A";
      DealArcane(4, 2, "PLAYCARD", $parameter, player:$player, meldState:$meldState, resolvedTarget:$target);
      break;
    case "null__shock_yellow":
      if (GetClassState($player, $CS_ArcaneDamageDealt) > 0) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "LAYER:type=I;minCost=0;maxCost=".GetClassState($player, $CS_ArcaneDamageDealt)-1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("NEGATE", $player, "<-", 1);
      }
      break;
    case "comet_storm__shock_red":
      $stormTarget = str_contains($target, ",") ? explode(",", $target)[0] : $target;
      $meldState = (GetClassState($player, $CS_AdditionalCosts) == "Both") ? "I,A" : "A";
      DealArcane(5, 2, "PLAYCARD", $parameter, player:$player, meldState: $meldState, resolvedTarget:$stormTarget);
      break;
    case "regrowth__shock_blue":
      if (GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0) {
        MZMoveCard($player, "MYDISCARD:type=AA;minCost=0;maxCost=" .GetClassState($otherPlayer, $CS_ArcaneDamageTaken) -1, "MYHAND", DQContext: "Choose an attack action card with cost less than " . GetClassState($player, $CS_ArcaneDamageDealt)-1);
      }
      break;
    case "consign_to_cosmos__shock_yellow":
      $arcDamage = GetClassState($player, $CS_ArcaneDamageDealt);
      for ($i = 0; $i < $arcDamage; ++$i) {
        AddDecisionQueue("MULTIZONEINDICES", $player, "MYDISCARD:type=I&MYDISCARD:subtype=Aura&THEIRDISCARD:type=I&THEIRDISCARD:subtype=Aura", 1);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose an instant or aura to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZBANISH", $player, "-", 1);
        AddDecisionQueue("MZREMOVE", $player, "-", 1);
      }
      break;
    case "everbloom__life_blue":
      $maxCost = GetClassState($player, $CS_HealthGained) - 1;
      if ($maxCost >= 0) {
        $indices = SearchMultizone($player, "THEIRDISCARD:type=AA;maxCost=$maxCost&THEIRDISCARD:type=A;maxCost=$maxCost&MYDISCARD:type=AA;maxCost=$maxCost&MYDISCARD:type=A;maxCost=$maxCost");
        $indicesArr = explode(",", $indices);
        $lastCard = $indicesArr[count($indicesArr) - 1] ?? "-";
        if (!(GetClassState($player, $CS_AdditionalCosts) == "Both" || $additionalCosts == "MELD") && $lastCard != "-" && GetMZCard($player, $lastCard) == $parameter) {
          //removing itself from the list of choices
          $indices = implode(",", array_slice($indicesArr, 0, -1));
        }
        if ($indices != "") {
          AddDecisionQueue("PASSPARAMETER", $player, $indices);
          AddDecisionQueue("SETDQCONTEXT", $player, "Choose an action to put on the bottom of the owners deck", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
          AddDecisionQueue("MZADDTOBOTDECK", $player, "-", 1);
          AddDecisionQueue("SETDQVAR", $player, "0", 1);
          AddDecisionQueue("WRITELOG", $player, "⤵️ <0> was put on the bottom of the deck.", 1);
        }
      }
      break;
    default:
      break;
  }
  ResolveGoAgain($parameter, $player, "MELD", additionalCosts: $additionalCosts);
  $goesWhere = GoesWhereEffectsModifier($parameter, "MELD", $player);
  $goesWhere = $goesWhere == -1 ? "GY" : $goesWhere;
  if(GetClassState($player, $CS_AdditionalCosts) == "Both" || $additionalCosts == "MELD") ResolveGoesWhere($goesWhere, $parameter, $player, "MELD"); //Only needs to be handled specifically here when playing both side of a Meld card
}
