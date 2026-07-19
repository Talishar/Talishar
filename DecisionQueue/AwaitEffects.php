<?php

// Leaving this here for posterity, will be needed for compiled code
// function Await2($player, $function, $acceptNames=[], $returnNames=["LASTRESULT"], $lastResultName="LASTRESULT", $subsequent=1, ...$args) {
//   AddDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
//   foreach ($args as $key => $value) {
//     AddDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
//     AddDecisionQueue("SETDQVAR", $player, $key, $subsequent);
//   }
//   if (count($acceptNames) > 0) AddDecisionQueue("PASSPARAMETER", $player, implode("|", $acceptNames), $subsequent);
//   AddDecisionQueue("AWAIT", $player, $function, $subsequent);
//   if ($lastResultName == "FINAL") AddDecisionQueue("CLEARDQVARs", $player, "-");
//   else AddDecisionQueue("SETDQVAR", $player, implode("|", $returnNames), $subsequent);
// }

function Await($player, $function="",  $returnName="LASTRESULT", $lastResultName="LASTRESULT", $subsequent=1, $final=false, $prepend=false, ...$args) {
  if (!$prepend) {
    AddDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
    foreach ($args as $key => $value) {
      AddDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
      AddDecisionQueue("SETDQVAR", $player, $key, $subsequent);
    }
    if ($function != "") AddDecisionQueue("AWAIT", $player, $function, $subsequent);
    if ($final) {
      AddDecisionQueue("CLEARDQVARS", $player, "-");
      AddDecisionQueue("ELSE", $player, "-");
      AddDecisionQueue("CLEARDQVARS", $player, "-");
    }
    else AddDecisionQueue("SETDQVAR", $player, $returnName, $subsequent);
  }
  else {
    if ($final) {
      PrependDecisionQueue("CLEARDQVARS", $player, "-");
      PrependDecisionQueue("ELSE", $player, "-");
      PrependDecisionQueue("CLEARDQVARS", $player, "-");
    }
    else PrependDecisionQueue("SETDQVAR", $player, $returnName, $subsequent);
    if ($function != "") PrependDecisionQueue("AWAIT", $player, $function, $subsequent);
    foreach ($args as $key => $value) {
      PrependDecisionQueue("SETDQVAR", $player, $key, $subsequent);
      PrependDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
    }
    PrependDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
  }
}


function AwaitLogic($player, $function) {
  global $dqVars;
  // WriteLog("Await executing $function");
  // foreach ($dqVars as $key => $value) WriteLog("$key => $value"); //uncommment to view await processing
  // does this function already exist?
  $functionAwait = $function . "Await";
  if (function_exists($functionAwait)) return $functionAwait($player);
  // is this a card specific function?
	$card = GetClass($function, $player);
  if ($card != "-") return $card->SpecificLogic();
  WriteLog("$function does not appear to exist as an Await function yet");
  return "PASS";
}

function MultiChooseDeckAwait($player) {
  global $dqVars;
  $notSubsequent = $dqVars["notSubsequent"] ?? false;
  $maxNumber = $dqVars["maxNumber"] ?? 1;
  $minNumber = $dqVars["minNumber"] ?? 0;
  $indices = $dqVars["indices"] ?? "";
  $param = "$maxNumber-$indices-$minNumber";
  PrependDecisionQueue("MULTICHOOSEDECK", $player, $param, !$notSubsequent);
}

function SetLastResultAwait($player, $key) {
  global $dqVars;
  return $dqVars[$key];
}

function DeckTopCardsAwait($player) {
  global $dqVars;
  $deck = new Deck($player);
  return $deck->Top(amount: $dqVars["number"] ?? 1);
}

function RevealCardsAwait($player) {
  global $dqVars;
  $revealed = RevealCards($dqVars["cardIDs"], $player);
  return $revealed ? "REVEALED" : "PASS";
}

function MultiRemoveDeckAwait($player) {
  global $dqVars;
  $deck = new Deck($player);
  return $deck->Remove($dqVars["indices"]);
}

function MultiAddHandAwait($player) {
  global $dqVars;
  $cards = explode(",", $dqVars["cardIDs"]);
  $loud = $dqVars["log"] ?? "1";
  $hand = &GetHand($player);
  $log = "";
  $count = count($cards);
  $lastIdx = $count - 1;
  for ($i = 0; $i < $count; ++$i) {
    if ($loud === "1") {
      if ($log !== "") $log .= ", ";
      if ($i !== 0 && $i === $lastIdx) $log .= "and ";
      $log .= CardLink($cards[$i], $cards[$i]);
    }
    $hand[] = $cards[$i];
  }
  if ($log !== "") WriteLog("$log added to hand");
  return $dqVars["cardIDs"];
}

function ShuffleDeckAwait($player) {
  global $dqVars;
  $parameter = $dqVars["parameter"] ?? "-";
  $deck = new Deck($player);
  $deck->Shuffle($parameter);
}

function MultiTargetIndicesAwait($player) {
  global $dqVars;
  $currentTargets = explode(",", $dqVars["currentTargets"] ?? "");
  $rvOrig = explode(",", SearchMultizone($player, $dqVars["search"]));
  $rv = [];
  $currentTargetsFlip = array_flip($currentTargets);
  //remove any choices that have already been targeted
  foreach ($rvOrig as $ind) {
    if (!isset($currentTargetsFlip[CleanTarget($player, $ind)])) $rv[] = $ind;
  }
  $rv = implode(",", $rv);
  return $rv == "" ? "PASS" : $rv;
}

function MultiChooseIndicesAwait($player) {
  global $dqVars;
  $currentChoices = explode(",", $dqVars["currentChoices"] ?? "");
  $rvOrig = explode(",", SearchMultizone($player, $dqVars["search"]));
  $rv = [];
  $currentChoicesFlip = array_flip($currentChoices);
  //remove any choices that have already been targeted
  foreach ($rvOrig as $ind) {
    if (!isset($currentChoicesFlip[$ind])) $rv[] = $ind;
  }
  $rv = implode(",", $rv);
  return $rv == "" ? "PASS" : $rv;
}

function MultiZoneIndicesAwait($player) {
  global $dqVars;
  $search = $dqVars["search"];
  return MultiZoneIndices($player, $search);
}

function ChooseMultiZoneAwait($player) {
  global $dqVars;
  $may = $dqVars["may"] ?? false;
  $indices = $dqVars["indices"] ?? "";
  if ($indices == "" || $indices == "PASS") return "PASS";
  $notSubsequent = $dqVars["notSubsequent"] ?? false;
  if ($may)
    PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, $indices, !$notSubsequent);
  else
    PrependDecisionQueue("CHOOSEMULTIZONE", $player, $indices, !$notSubsequent);
  PrependDecisionQueue("SETDQCONTEXT", $player, $dqVars["context"] ?? "Choose a card", !$notSubsequent);
}

function DiscardAwait($player) {
  global $dqVars;
  $MZIndex = $dqVars["MZIndex"] ?? "-";
  $source = $dqVars["source"] ?? "";
  $from = "HAND";
  $effectController = $dqVars["effectController"] ?? "";
  $cardController = $player;
  if ($MZIndex != "-")
    $index = explode("-", $MZIndex)[1] ?? -1;
  else $index = $dqVars["index"] ?? -1;
  if ($index != -1) {
    $Hand = new Hand($player);
    $cardID = $Hand->Remove($index);
    CardDiscarded($player, $cardID, $source);
    AddGraveyard($cardID, $player, $from, $effectController, $cardController);
  }
}

function MZRemoveAwait($player) {
  global $dqVars;
  $MZIndex = $dqVars["MZIndex"];
  $parameter = $dqVars["parameter"] ?? "-";
  return MZRemove($player, $MZIndex, $parameter);
}

function MZBanishAwait($player) {
  global $dqVars;
  $MZIndex = $dqVars["MZIndex"];
  $parameter = $dqVars["parameter"] ?? "-";
  return MZBanish($player, $parameter, $MZIndex);
}

function SetLayerTargetAwait($player) {
  global $dqVars, $Stack;
  $cardID = $dqVars["layerID"] ?? "-";
  $targ = $dqVars["index"] ?? "-";
  if ($cardID == "-" || $targ == "-") return "PASS";
  $targetPlayer = substr($targ, 0, 5) == "THEIR" ? ($player == 1 ? 2 : 1) : $player;
  WriteLog("Player " . $targetPlayer . "'s " . GetMZCardLink($targetPlayer, $targ) . " was targeted");
  $cleanTarget = CleanTarget($player, $targ);
  $numLayers = $Stack->NumLayers();
  for ($i = 0; $i < $numLayers; ++$i) {
    $Layer = $Stack->Card($i, true);
    if ($Layer->ID() == $cardID) {
      $Layer->AddTarget($cleanTarget);
      return $Layer->Target();
    }
  }
  return $cleanTarget;
}

function DealDamageAwait($player) {
  global $dqVars;
  $target = $dqVars["target"] ?? "THEIRCHAR-0";
  $damage = $dqVars["damage"] ?? 1;
  $source = $dqVars["source"] ?? "-";
  $type = $dqVars["type"] ?? "DAMAGE";
  $playerSource = $dqVars["playerSource"] ?? $player;

  $targetArr = explode("-", $target);
  $targetPlayer = $targetArr[0] == "MYCHAR" || $targetArr[0] == "MYALLY" ? $player : ($player == 1 ? 2 : 1);
  if ($targetArr[0] == "THEIRALLY" || $targetArr[0] == "MYALLY") {
    return DamageAlly($targetPlayer, $targetArr[1], $damage, $type);
  } else {
    PrependDecisionQueue("TAKEDAMAGE", $targetPlayer, "$damage-$source-$type-$playerSource");
    if (SearchCurrentTurnEffects("cap_of_quick_thinking", $targetPlayer)) DoCapQuickThinking($targetPlayer, $damage);
    $Character = new PlayerCharacter($targetPlayer);
    $Solray = $Character->FindCardID("solray_plating");
    if ($Solray != "" && $Solray->IsActive()) DoSolrayPlating($targetPlayer, $damage);
    DoQuell($targetPlayer, $damage);
    if (SearchCurrentTurnEffects("morlock_hill_blue", $targetPlayer, true) && $damage >= GetHealth($targetPlayer)) PreventLethal($targetPlayer, $damage);
  }
  return $damage;
}

Function YesNoAwait($player) {
  global $dqVars;
  $context = $dqVars["context"] ?? "-";
  $message = $dqVars["message"] ?? "-";
  $noPass = $dqVars["noPass"] ?? true;
  if ($noPass) PrependDecisionQueue("NOPASS", $player, "-", 1);
  PrependDecisionQueue("YESNO", $player, $message, 1);
  PrependDecisionQueue("SETDQCONTEXT", $player, $context, 1);
}


// Use this one during the resolution of an effect for clearer UI
function PayResourcesEffectAwait($player) {
  global $dqVars;
  $amount = $dqVars["amount"];
  PrependDecisionQueue("PAYRESOURCESEFFECT", $player, $amount, 1);  
  PrependDecisionQueue("PASSPARAMETER", $player, $amount, 1);  
}

function PayResourcesAwait($player) {
  global $dqVars;
  $amount = $dqVars["amount"];
  PrependDecisionQueue("PAYRESOURCES", $player, $amount, 1);
  PrependDecisionQueue("PASSPARAMETER", $player, $amount, 1);
}

function PlayAuraAwait($player) {
  global $dqVars;
  $cardID = strtolower($dqVars["cardID"]);
  $number = $dqVars["number"] ?? 1;
  $isToken = $dqVars["isToken"] ?? false;
  $rogueHeronSpecial = $dqVars["rogueHeronSpecial"] ?? false;
  $numPowerCounters = $dqVars["numPowerCounters"] ?? 0;
  $from = $dqVars["from"] ?? "-";
  $additionalCosts = $dqVars["additionalCosts"] ?? "-";
  $effectController = $dqVars["effectController"] ?? "-";
  $effectSource = $dqVars["effectSource"] ?? "-";
  PlayAura($cardID, $player, $number, $isToken, $rogueHeronSpecial, $numPowerCounters, $from, $additionalCosts, $effectController, $effectSource);
}

function CardChoicesAwait($player) {
  global $dqVars;
  $context = $dqVars["context"] ?? "";
  PrependDecisionQueue("BUTTONINPUT", $player, $dqVars["choices"], 1);
  if ($context != "") PrependDecisionQueue("SETDQCONTEXT", $player, $context);
}

function ResolveGoesWhereAwait($player) {
  global $dqVars;
  $cardID = $dqVars["cardID"] ?? "-";
  $goesWhere = $dqVars["goesWhere"] ?? "-";
  $from = $dqVars["from"] ?? "-";
  $effectController = $dqVars["effectController"] ?? "";
  $modifier = $dqVars["modifier"] ?? "NA";
  ResolveGoesWhere($goesWhere, $cardID, $player, $from, $effectController, $modifier);
}

function MZDestroyAwait($player) {
  global $dqVars;
  $MZInd = $dqVars["MZInd"] ?? "";
  $effectController = $dqVars["effectController"] ?? "";
  $allArsenal = $dqVars["allArsenal"] ?? true;
  MZDestroy($player, $MZInd, $effectController, $allArsenal);
}

function SharpenAwait($player) {
  global $dqVars;
  $MZindex = $dqVars["MZIndex"];
  $num = intval($dqVars["num"]) ?? 1;
  Sharpen($MZindex, $player, $num);
}

function ElseAwait($player) {
  PrependDecisionQueue("ELSE", $player, "-");
}

function AddCurrentTurnEffectAwait($player) {
  global $dqVars;
  $effectID = $dqVars["effectID"];
  $from = $dqVars["from"] ?? "-";
  $uniqueID = $dqVars["uniqueID"] ?? -1;
  AddCurrentTurnEffect($effectID, $player, $from, $uniqueID);
}

function ChooseTextAwait($player) {
  global $dqVars;
  $may = $dqVars["may"] ?? false;
  $choices = $dqVars["choices"];
  $numChoices = $dqVars["numChoices"] ?? 0;
  if ($numChoices == 0)
    $numChoices = substr_count($choices, ',') + 1;
  $maxChoices = $dqVars["maxChoices"] ?? $numChoices;
  $minChoices = $dqVars["minChoices"] ?? $numChoices;
  $choices = $dqVars["choices"];
  $modal = $dqVars["modal"] ?? "";
  $context = $dqVars["context"] ?? "";
  if ($context == "" && $modal != "") {
    if ($maxChoices == $minChoices)
      $context = "Choose $maxChoices modes for " . CardLink($modal);
    else
      $context = "Choose between $minChoices and $maxChoices modes for " . CardLink($modal);
  }
  if ($modal != "") PrependDecisionQueue("SHOWMODES", $player, $modal, 1);
  PrependDecisionQueue("MULTICHOOSETEXT", $player, "$maxChoices-$choices-$minChoices");
  if ($context != "") PrependDecisionQueue("SETDQCONTEXT", $player, $context);
}

function IncrementAwait($player) {
  global $dqVars;
  $num = intval($dqVars["num"]);
  $by = intval($dqVars["by"] ?? 1);
  return $num + $by;
}

function SetModesAwait($player) {
  global $dqVars, $CS_AdditionalCosts;
  SetClassState($player, $CS_AdditionalCosts, $dqVars["modes"]);
}

function AddTopDeckAwait($player) {
  global $dqVars;
  $cardID = $dqVars["cardID"];
  $from = $dqVars["from"] ?? "GY";
  $deckIndexModifier = $dqVars["deckIndexModifier"] ?? 0;
  $Deck = new Deck($player);
  return $Deck->AddTop($cardID, $from, $deckIndexModifier);
}

function ResolveGoAgainAwait($player) {
  global $dqVars;
  $cardID = $dqVars["cardID"];
  $from = $dqVars["from"];
  $additionalCosts = $dqVars["additionalCosts"];
  $uniqueID = $dqVars["uniqueID"];
  ResolveGoAgain($cardID, $player, $from, additionalCosts: $additionalCosts, uniqueID:$uniqueID);
}

function AfterResolveEffectsAwait($player) {
  CopyCurrentTurnEffectsFromAfterResolveEffects();
}

function ShowCardAwait($player) {
  global $dqVars;
  $cardID = $dqVars["cardID"];
  WriteLog(CardLink($cardID) . " was chosen");
}

function AddTriggerAwait($player) {
  global $dqVars;
  $additional = $dqVars["additional"] ?? "";
  $target = $dqVars["target"] ?? "";
  $cardID = $dqVars["cardID"];
  $uniqueID = $dqVars["uniqueID"] ?? "-";
  $parameter = "$cardID|$additional|$uniqueID";
  PrependDecisionQueue("ADDTRIGGER", $player, $parameter, 1);
  PrependDecisionQueue("PASSPARAMETER", $player, $target, 1);
}

function MZTapAwait($player) {
  global $dqVars;
  $MZInd = $dqVars["MZIndex"];
  $tapState = $dqVars["tapState"] ?? 1;
  Tap($MZInd, $player, $tapState);
}

function AQTargetingAwait($player) {
  global $dqVars, $defPlayer;
  $targets = explode(",", $dqVars["target"] ?? "THEIRCHAR-0");
  $cleanedTargets = [];
  foreach($targets as $target) {
    $cleanTarget = CleanTarget($player, $target);
    $cleanedTargets[] = $cleanTarget;
    $obj = CleanTargetToObject($player, $cleanTarget);
    if (HasSpectra($obj->CardID())) {
      AddLayer("TRIGGER", $defPlayer, "SPECTRA", "-", "-", $obj->UniqueID());
    }
  }
  $targets = implode(",", $cleanedTargets);
  return $targets;
}

function AddAttackQueueAwait($player) {
  global $dqVars, $defPlayer;
  $targets = $dqVars["target"];
  $cardID = $dqVars["cardID"];
  $from = $dqVars["from"] ?? "-";
  $uniqueID = $dqVars["uniqueID"] ?? "-";
  $zone = $dqVars["zone"] ?? "-";
  $resourcesPaid = $dqVars["resourcesPaid"]  ?? 0;
  switch($zone) {
    case "MYCHAR":
      $Character = new PlayerCharacter($player);
      $CharacterCard = $Character->FindCardUID($uniqueID);
      $index = $CharacterCard->Index();
      break;
    case "MYALLY":
      $Allies = new Allies($player);
      $AllyCard = $Allies->FindCardUID($uniqueID);
      $index = $AllyCard->Index();
    default:
      $index = -1;
      break;
  }
  $parameter = "$from|$resourcesPaid|$index|$uniqueID|$zone";	
  AddAttackQueue($cardID, $player, $targets, $parameter, $uniqueID);
}

function CheckAttackQueueAwait($player) {
  ResolveAttackQueue();
}