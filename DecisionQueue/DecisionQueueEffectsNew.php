<?php

function Await($player, $card, ...$args) {
  AddDecisionQueue("SETDQVAR", $player, "LASTRESULT", 1);
  foreach ($args as $key => $value) {
    AddDecisionQueue("PASSPARAMETER", $player, $value, 1);
    AddDecisionQueue("SETDQVAR", $player, $key, 1);
  }
  AddDecisionQueue("SPECIFICCARDNEW", $player, $card, 1);
}

function dummyfunction($player) {
	global $dqVars;
	$lastResult = GamestateUnsanitize($dqVars["LASTRESULT"]);
	$a = $dqVars["a"];
	$b = $dqVars["b"];
  WriteLog("HERE in DUMMY: $lastResult - $a - $b");
}

$SpecificFunctions = [
  "DUMMY" => "dummyfunction",
];

function SpecificCardLogicNew($player, $card) {
  global $SpecificFunctions;
  if (isset($SpecificFunctions[$card])) return $SpecificFunctions[$card]($player);
	$card = GetClass($card, $player);
  return $card->SpecificLogic();
}