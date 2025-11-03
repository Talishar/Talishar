<?php

include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";
SetHeaders();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$inactivePlayer = $_GET["inactivePlayer"];

$authKey = $_GET["authKey"];

session_start();

if ($authKey == "") $authKey = $_COOKIE["lastAuthKey"];

$targetAuthKey = "";
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $targetAuthKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $targetAuthKey = $_SESSION["p2AuthKey"];
if ($targetAuthKey != "" && $authKey != $targetAuthKey) exit;

// Write system message for inactivity - simple one-liner using WriteSystemMessage
WriteSystemMessage("⌛Player " . intval($inactivePlayer) . " is inactive.");

// Do NOT call GamestateUpdated here as it resets the inactivity timer
// The message will be included in the next natural GetNextTurn poll

?>
