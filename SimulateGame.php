<?php

ob_start();
include "WriteLog.php";
include "WriteReplay.php";
include "GameLogic.php";
include "GameTerms.php";
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "Libraries/StatFunctions.php";
include "Libraries/UILibraries.php";
include "Libraries/PlayerSettings.php";
include "Libraries/NetworkingLibraries.php";
include "AI/CombatDummy.php";
include "AI/PlayerMacros.php";
include "Libraries/HTTPLibraries.php";
require_once("Libraries/CoreLibraries.php");
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "APIKeys/APIKeys.php";
ob_end_clean();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}

$gameName = "r" . $gameName;

copy("./Games/" . $gameName . "/startGamestate.txt", "./Games/" . $gameName . "/gamestate.txt");

include "ParseGamestate.php";
$otherPlayer = $currentPlayer == 1 ? 2 : 1;
$skipWriteGamestate = true;
$mainPlayerGamestateStillBuilt = 0;
$makeCheckpoint = 0;
$makeBlockBackup = 0;
$MakeStartTurnBackup = false;
$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
$conceded = false;
$randomSeeded = false;

ProcessInput($playerID, $mode, $buttonInput, $cardID, $chkCount, $chkInput);
ProcessMacros();
CacheCombatResult();


include "WriteGamestate.php";

?>

Something is wrong with the XAMPP installation :-(
