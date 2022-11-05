<?php
include './Libraries/PlayerSettings.php';

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$authKey = TryGet("authKey", "");
if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];

ob_start();
include "./ParseGamestate.php";
include "./GameLogic.php";
include "./Libraries/UILibraries2.php";
include "./Libraries/StatFunctions.php";
include "./Libraries/PlayerSettings.php";
include "./GameTerms.php";
include "./HostFiles/Redirector.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
ob_end_clean();

$response = new stdClass();
