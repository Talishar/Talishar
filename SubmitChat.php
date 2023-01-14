<?php

include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
SetHeaders();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$chatText = htmlspecialchars($_GET["chatText"]);
$authKey = $_GET["authKey"];

session_start();

if($authKey == "") $authKey = $_COOKIE["lastAuthKey"];

$targetAuthKey = "";
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $targetAuthKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $targetAuthKey = $_SESSION["p2AuthKey"];
if($authKey != $targetAuthKey) exit;

$uid = "-";
if (isset($_SESSION['useruid'])) $uid = $_SESSION['useruid'];
$displayName = ($uid != "-" ? $uid : "Player " . $playerID);

//array for contributors
$contributors = array("sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar");

//its sort of sloppy, but it this will fail if you're in the contributors array because we want to give you the contributor icon, not the patron icon.
if (isset($_SESSION["isPatron"]) && isset($_SESSION['useruid']) && !in_array($_SESSION['useruid'], $contributors)) $displayName = "<img title='Patron' style='margin-bottom:-2px; margin-right:-4px; height:18px;' src='./Images/patronHeart.webp' /> " . $displayName;

//This is the code for Contributor's icon.
if (isset($_SESSION['useruid']) && in_array($_SESSION['useruid'], $contributors)) $displayName = "<img title='Contributor' style='margin-bottom:-2px; margin-right:-4px; height:18px;' src='./Images/copper.webp' /> " . $displayName;

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "a");
$output = "<span style='font-weight:bold; color:<PLAYER" . $playerID . "COLOR>;'>" . $displayName . ": </span>" . $chatText;
fwrite($handler, $output . "\r\n");
fclose($handler);

GamestateUpdated($gameName);
