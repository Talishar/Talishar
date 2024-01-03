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

$authKey = $_GET["authKey"];

session_start();

if ($authKey == "") $authKey = $_COOKIE["lastAuthKey"];

$targetAuthKey = "";
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $targetAuthKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $targetAuthKey = $_SESSION["p2AuthKey"];
if ($targetAuthKey != "" && $authKey != $targetAuthKey) exit;

$uid = "-";
if (isset($_SESSION['useruid'])) $uid = $_SESSION['useruid'];
$displayName = ($uid != "-" ? $uid : "Player " . $playerID);

$chatText = "";
if (tryGet("quickChat")) {
  $chatText = parseQuickChat($_GET["quickChat"]);
} else {
  $chatText = htmlspecialchars($_GET["chatText"]);
}

//array for contributors
$contributors = array("sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar", "DKGaming", "PvtVoid");

//its sort of sloppy, but it this will fail if you're in the contributors array because we want to give you the contributor icon, not the patron icon.
if (isset($_SESSION["isPatron"]) && isset($_SESSION['useruid']) && !in_array($_SESSION['useruid'], $contributors)) $displayName = "<img title='Patron' style='margin-bottom:-2px; margin-right:-4px; height:18px;' src='./images/patronHeart.webp' /> " . $displayName;

//This is the code for Contributor's icon.
if (isset($_SESSION['useruid']) && in_array($_SESSION['useruid'], $contributors)) $displayName = "<img title='Contributor' style='margin-bottom:-2px; margin-right:-4px; height:18px;' src='./images/copper.webp' /> " . $displayName;

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "a");
$output = "<span style='font-weight:bold; color:<PLAYER" . $playerID . "COLOR>;'>" . $displayName . ": </span>" . $chatText;
fwrite($handler, $output . "\r\n");
if (GetCachePiece($gameName, 11) >= 3) fwrite($handler, "The lobby is reactivated.\r\n");
fclose($handler);

GamestateUpdated($gameName);
if ($playerID == 1) SetCachePiece($gameName, 11, 0);

function parseQuickChat($inputEnum)
{
  switch($inputEnum) {
    case "1": return "Hello";
    case "2": return "Want to Chat?";
    case "3": return "Can I undo?";
    case "4": return "Do you want to undo?";
    case "5": return "Yes";
    case "6": return "No";
    case "7": return "Thanks!";
    case "8": return "Thinking... Please bear with me!";
    case "9": return "Good game!";
    case "10": return "Good luck, have fun";
    case "11": return "Sorry!";
    case "12": return "I think there's a bug";
    case "13": return "Got to go";
    case "14": return "Be right back";
    case "15": return "Are you there?";
    case "16": return "Whoops!";
    case "17": return "No problem!";
    default: return "";
  };
}
