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
if($uid == "starmorgs") exit;
$displayName = ($uid != "-" ? $uid : "Player " . $playerID);

$chatText = "";
if (tryGet("quickChat")) {
  $chatText = parseQuickChat($_GET["quickChat"]);
} else {
  $chatText = htmlspecialchars($_GET["chatText"]);
}

//array for contributors
$contributors = array("sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar", "DKGaming", "Aegisworn");

//its sort of sloppy, but it this will fail if you're in the contributors array because we want to give you the contributor icon, not the patron icon.
if(isset($_SESSION["isPatron"]) && isset($_SESSION['useruid']) && !in_array($_SESSION['useruid'], $contributors)) {
  $displayName = "<a href='https://linktr.ee/Talishar' target='_blank' rel='noopener noreferrer'><img title='I am a patron of Talishar!' style='margin-bottom:3px; height:16px;' src='./images/patronHeart.webp' /></a>" . $displayName;
}
//This is the code for Contributor's icon.
if(isset($_SESSION['useruid']) && in_array($_SESSION['useruid'], $contributors)) {
  $displayName = "<a href='https://linktr.ee/Talishar' target='_blank' rel='noopener noreferrer'><img title='I am a contributor to Talishar!' style='margin-bottom:3px; height:16px;' src='./images/copper.webp' /></a>" . $displayName;
}

//This is the code for PvtVoid Patreon
if(isset($_SESSION["isPvtVoidPatron"]) || isset($_SESSION['useruid']) && in_array($_SESSION['useruid'], array("PvtVoid"))) {
  $displayName = "<a href='https://linktr.ee/Talishar' target='_blank' rel='noopener noreferrer'><img title='I am a patron of PvtVoid!' style='margin-bottom:3px; height:16px;' src='./images/patronEye.webp'/></a>" . $displayName;
}

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
    case "2": return "Good luck, have fun";
    case "3": return "Are you there?";
    case "4": return "Be right back";
    case "5": return "Can I undo?";
    case "6": return "Do you want to undo?";
    case "7": return "Good game!";
    case "8": return "Got to go";
    case "9": return "I think there is a bug";
    case "10": return "No";
    case "11": return "No problem!";
    case "12": return "Okay!";
    case "13": return "Refresh the page";
    case "14": return "Sorry!";
    case "15": return "Thanks!";
    case "16": return "Thinking... Please bear with me!";
    case "17": return "Want to Chat?";
    case "18": return "Whoops!";
    case "19": return "Yes";
    default: return "";
  };
}
