<?php

function Await($player, $function, $returnName="LASTRESULT", $lastResultName="LASTRESULT", $subsequent=1, ...$args) {
  AddDecisionQueue("SETDQVAR", $player, $lastResultName, $subsequent);
  foreach ($args as $key => $value) {
    AddDecisionQueue("PASSPARAMETER", $player, $value, $subsequent);
    AddDecisionQueue("SETDQVAR", $player, $key, $subsequent);
  }
  AddDecisionQueue("AWAIT", $player, $function, $subsequent);
  AddDecisionQueue("SETDQVAR", $player, $returnName, $subsequent);
}

function dummyfunction($player) {
	global $dqVars;
	$lastResult = GamestateUnsanitize($dqVars["LASTRESULT"]);
	$a = $dqVars["a"];
	$b = $dqVars["b"];
  WriteLog("HERE in DUMMY: $lastResult - $a - $b");
}

function AwaitLogic($player, $function) {
  // does this function already exist?
  $functionAwait = $function . "Await";
  if (function_exists($functionAwait)) $functionAwait($player);
  // is this a card specific function?
	$card = GetClass($function, $player);
  if ($card != "-") return $card->SpecificLogic();
  WriteLog("$function does not appear to exist as an Await function yet");
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