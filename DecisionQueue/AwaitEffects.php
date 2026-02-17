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

function Await($player, $function,  $returnName="LASTRESULT", $lastResultName="LASTRESULT", $subsequent=1, $final=false, ...$args) {
  AddDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
  foreach ($args as $key => $value) {
    AddDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
    AddDecisionQueue("SETDQVAR", $player, $key, $subsequent);
  }
  AddDecisionQueue("AWAIT", $player, $function, $subsequent);
  if ($final) {
    AddDecisionQueue("CLEARDQVARS", $player, "-");
    AddDecisionQueue("ELSE", $player, "-");
    AddDecisionQueue("CLEARDQVARS", $player, "-");
  }
  else AddDecisionQueue("SETDQVAR", $player, $returnName, $subsequent);
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
  $hand = &GetHand($player);
  $log = "";
  for ($i = 0; $i < count($cards); ++$i) {
    if ($parameter == "1") {
      if ($log != "") $log .= ", ";
      if ($i != 0 && $i == count($cards) - 1) $log .= "and ";
      $log .= CardLink($cards[$i], $cards[$i]);
    }
    array_push($hand, $cards[$i]);
  }
  if ($log != "") WriteLog("$log added to hand");
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
  //remove any choices that have already been targeted
  foreach ($rvOrig as $ind) {
    if (!in_array(CleanTarget($player, $ind), $currentTargets)) array_push($rv, $ind);
  }
  $rv = implode(",", $rv);
  return $rv == "" ? "PASS" : $rv;
}

function ChooseMultiZoneAwait($player) {
  global $dqVars;
  $may = $dqVars["may"] ?? false;
  $indices = $dqVars["indices"] ?? "";
  $notSubsequent = $dqVars["notSubsequent"] ?? false;
  if ($may)
    PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, $indices, !$notSubsequent);
  else
    PrependDecisionQueue("CHOOSEMULTIZONE", $player, $indices, !$notSubsequent);
  PrependDecisionQueue("SETDQCONTEXT", $player, $dqVars["context"] ?? "Choose a card", !$notSubsequent);
}

function SetLayerTargetAwait($player) {
  global $dqVars, $Stack;
  $cardID = $dqVars["layerID"] ?? "-";
  $targ = $dqVars["index"] ?? "-";
  if ($cardID == "-" || $targ == "-") return "PASS";
  $targetPlayer = substr($targ, 0, 5) == "THEIR" ? ($player == 1 ? 2 : 1) : $player;
  WriteLog("Player " . $targetPlayer . "'s " . GetMZCardLink($targetPlayer, $targ) . " was targeted");
  $cleanTarget = CleanTarget($player, $targ);
  for ($i = 0; $i < $Stack->NumLayers(); ++$i) {
    $Layer = $Stack->Card($i, true);
    if ($Layer->ID() == $cardID) 
      $Layer->AddTarget($cleanTarget);
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
  if ($target[0] == "THEIRALLY" || $target[0] == "MYALLY") {
    return DamageAlly($targetPlayer, $target[1], $damage, $type);
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
  $context = $dqVars["context"];
  PrependDecisionQueue("NOPASS", $player, "-", 1);
  PrependDecisionQueue("YESNO", $player, $context);
}

function PlayAuraAwait($player) {
  global $dqVars;
  $cardID = $dqVars["cardID"];
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