<?php

include_once "EncounterAI.php";

function CombatDummyAI()
{
  global $p2IsAI;
  if ($p2IsAI != "1") return;
  $p2Char = &GetPlayerCharacter(2);
  if ($p2Char[0] == "DUMMY") PracticeDummyAI();
  else EncounterAI();
}

//The practice dummy doesn't use BotLogic at all - it just swings its weapon each turn and never blocks.
function PracticeDummyAI()
{
  global $currentPlayer, $mainPlayer, $actionPoints, $decisionQueue, $turn;
  $currentPlayerIsAI = ($currentPlayer == 2);
  for ($logicCount = 0; $logicCount <= 30 && $currentPlayerIsAI; ++$logicCount) {
    if (IsGameOver()) break;
    if (count($decisionQueue) > 0) {
      $isYesNo = $turn[0] == "YESNO" || $turn[0] == "DOCRANK";
      ContinueDecisionQueue($isYesNo ? "NO" : "0");
    } else if ($turn[0] == "M" && $mainPlayer == $currentPlayer && $actionPoints > 0) {
      $weaponIndex = FindCharacterIndex(2, "wrenchtastic");
      if ($weaponIndex >= 0) ProcessInput($currentPlayer, 3, "", $weaponIndex, 0, "");
      else PassInput();
    } else {
      PassInput();
    }
    ProcessMacros();
    $currentPlayerIsAI = ($currentPlayer == 2);
  }
}

if (!function_exists('IsPlayerAI')) {
	function IsPlayerAI($playerID) {
		global $p2IsAI;
		if($playerID == 2 && ($p2IsAI == "1" || !isset($p2IsAI))) return true;
		return false;
	}
}
