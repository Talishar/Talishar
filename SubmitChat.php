<?php

error_reporting(E_ALL);

// Start output buffering to catch any accidental output
ob_start();

session_start();

// CRITICAL: Capture all needed session data immediately and release the lock
// PHP sessions use exclusive file locks - holding the lock during file I/O
// blocks all other requests from this user, causing session deadlock.
$sessionP1AuthKey = $_SESSION["p1AuthKey"] ?? null;
$sessionP2AuthKey = $_SESSION["p2AuthKey"] ?? null;
$sessionUserUid = $_SESSION['useruid'] ?? null;
$sessionIsPatron = isset($_SESSION["isPatron"]);
$sessionIsPvtVoidPatron = isset($_SESSION["isPvtVoidPatron"]);

// Release session lock NOW - before any file operations
session_write_close();

include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include_once "includes/dbh.inc.php";
include_once "includes/MetafyHelper.php";
SetHeaders();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  http_response_code(400);
  die("Invalid game name.");
}
$playerID = intval($_GET["playerID"]);
if($playerID !== 1 && $playerID !== 2) {
  http_response_code(400);
  die("Invalid player ID.");
}

$authKey = $_GET["authKey"] ?? "";
if ($authKey === "" && ($playerID == 1 || $playerID == 2)) {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

// Load game file to get Metafy tiers - this populates $p1MetafyTiers and $p2MetafyTiers
include "MenuFiles/ParseGamefile.php";

$allowedOrigins = ['https://talishar.net', 'https://www.talishar.net'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // If it's a cross-origin request from an unauthorized site
    if (!empty($origin)) {
        http_response_code(403);
        die("CORS policy: Origin not allowed: $origin");
    }
}

$targetAuthKey = "";
if ($playerID == 1 && $p1Key !== null) $targetAuthKey = $p1Key;
else if ($playerID == 2 && $p2Key !== null) $targetAuthKey = $p2Key;
if ($authKey !== $targetAuthKey) {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
  if ($authKey !== $targetAuthKey) {
    http_response_code(403);
    die("Invalid auth key: " . htmlspecialchars($authKey));
  }
}

$uid = "-";
if ($sessionUserUid !== null) $uid = $sessionUserUid;
if($uid == "starmorgs") exit;
$displayName = ($uid != "-" ? $uid : "Player " . $playerID);

$chatText = "";
if (tryGet("quickChat")) {
  $chatText = parseQuickChat($_GET["quickChat"]);
} elseif (isset($_GET["chatText"]) && $_GET["chatText"] !== "") {
  $chatText = htmlspecialchars($_GET["chatText"]);
}

// Don't write anything if there's no actual message
if ($chatText === "") {
  http_response_code(403);
  die("No message.");
}

//array for contributors
$contributors = ["sugitime", "OotTheMonk", "LaustinSpayce", "Tower", "Etasus", "Aegisworn", "PvtVoid"];

// List of mod usernames - should match frontend list
$modUsernames = ["OotTheMonk", "LaustinSpayce", "Tower", "PvtVoid", "Aegisworn"];

// Get Metafy tiers for this player live from DB so stale game file values don't affect chat badges
$metafyTiers = [];
if ($uid !== '-') {
  $liveTiers = GetMetafyTiersFromDatabase($uid);
  if (!empty($liveTiers)) {
    $metafyTiers = $liveTiers;
  } else {
    // Fallback to game file value (already loaded via ParseGamefile.php)
    $metafyTiers = ($playerID == 1 ? $p1MetafyTiers : $p2MetafyTiers) ?? [];
  }
} else {
  $metafyTiers = ($playerID == 1 ? $p1MetafyTiers : $p2MetafyTiers) ?? [];
}
if (!is_array($metafyTiers)) $metafyTiers = [];

// Check for Metafy badges first - if user has Metafy badges, only show those
$hasMetafyBadges = false;
if(!empty($metafyTiers)) {
  $metafyBadgeHtml = '';
  foreach($metafyTiers as $tier) {
    $tierImage = GetMetafyTierImage($tier);
    if($tierImage) {
      $metafyBadgeHtml .= "<a href='https://www.metafy.gg' target='_blank' rel='noopener noreferrer'><img alt='' title='I am a Metafy Supporter of Talishar 💖' style='margin-bottom:3px; height:16px;' src='" . $tierImage . "'/></a>";
    }
  }
  if(!empty($metafyBadgeHtml)) {
    $displayName = $metafyBadgeHtml . $displayName;
    $hasMetafyBadges = true;
  }
}

// Only show Patreon badges if user doesn't have Metafy badges
if(!$hasMetafyBadges) {
  //its sort of sloppy, but it this will fail if you're in the contributors array because we want to give you the contributor icon, not the patron icon.
  if($sessionIsPatron && $sessionUserUid !== null && !in_array($sessionUserUid, $contributors)) {
    $displayName = "<a href='https://metafy.gg/@Talishar' target='_blank' rel='noopener noreferrer'><img title='I am a Metafy Supporter of Talishar 💖' style='margin-bottom:3px; height:16px;' src='./images/patronHeart.webp' /></a>" . $displayName;
  }

  //This is the code for PvtVoid Patreon
  if($sessionIsPvtVoidPatron || $sessionUserUid !== null && in_array($sessionUserUid, ["PvtVoid"])) {
    $displayName = "<a href='https://metafy.gg/@Talishar' target='_blank' rel='noopener noreferrer'><img title='I am a Metafy Supporter of Talishar 💖' style='margin-bottom:3px; height:16px;' src='./images/patronEye.webp'/></a>" . $displayName;
  }
}

//This is the code for Contributor's icon.
if($sessionUserUid !== null && in_array($sessionUserUid, $contributors)) {
  $displayName = "<a href='https://metafy.gg/@Talishar' target='_blank' rel='noopener noreferrer'><img title='I am a developer of Talishar!' style='margin-bottom:3px; height:16px;' src='./images/copper.webp' /></a>" . $displayName;
}

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "a");
// Check if user is a mod - use gold color (#f0d666) for mods, otherwise use player color
$isMod = $sessionUserUid !== null && in_array($sessionUserUid, $modUsernames);
$chatColor = $isMod ? "#a58703ff" : "<PLAYER" . $playerID . "COLOR>";
$output = "<span style='font-weight:bold; color:" . $chatColor . ";'>" . $displayName . ": </span>" . $chatText;
if ($handler) {
  fwrite($handler, $output . "\r\n");
  if (GetCachePiece($gameName, 11) >= 3) fwrite($handler, "The lobby is reactivated.\r\n");
  fclose($handler);
}

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
    case "14": return "Rematch?";
    case "15": return "Sorry!";
    case "16": return "Thanks!";
    case "17": return "Thanks for the game!";
    case "18": return "That was really cool!";
    case "19": return "Thinking... Please bear with me!";
    case "20": return "Want to Chat?";
    case "21": return "Whoops!";
    case "22": return "Yes";
    default: return "";
  };
}

function GetMetafyTierImage($tierName) {
  $tierImages = [
    'Fyendal Supporters' => './images/fyendal.webp',
    'Seers of Ophidia' => './images/ophidia.webp',
    'Arknight Shards' => './images/arknight.webp',
    'Lover of Grandeur' => './images/grandeur.webp',
    'Sponsors of Trōpal-Dhani' => './images/tropal.webp',
    'Light of Sol Gemini Circle' => './images/lightofsol.webp'
  ];
  return $tierImages[$tierName] ?? null;
}

