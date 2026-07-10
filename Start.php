<?php

ob_start();
include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";
include_once "Libraries/SHMOPLibraries.php";
include "Libraries/NetworkingLibraries.php";
include "GameLogic.php";
include "GameTerms.php";
include "WriteLog.php";
include "Libraries/StatFunctions.php";
include "Libraries/PlayerSettings.php";
include "Libraries/UILibraries.php";
include "AI/CombatDummy.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "./MenuFiles/StartHelper.php";
ob_end_clean();

$gameName = $_GET["gameName"] ?? "";
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];

if ($playerID == 3) {
  echo ("Spectators cannot start a game.");
  exit;
}

$gameDir = "./Games/" . $gameName . "/";

if (!file_exists($gameDir . "GameFile.txt")) exit;

ob_start();
include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";
ob_end_clean();
session_start();
if($playerID == 1 && isset($_SESSION["p1AuthKey"])) { $targetKey = $p1Key; $authKey = $_SESSION["p1AuthKey"]; }
else if($playerID == 2 && isset($_SESSION["p2AuthKey"])) { $targetKey = $p2Key; $authKey = $_SESSION["p2AuthKey"]; }
if (isset($authKey) && isset($targetKey) && $authKey !== $targetKey) {
  // Failsafe: Use game file's auth key if mismatch (lost on page refresh)
  $authKey = $targetKey;
}

//Initialize global variables
$p1Inventory = "";
$p2Inventory = "";

// Write the initial gamestate into a php://memory stream so that:
//   (a) all writes stay in RAM — faster than individual disk fwrite() calls
//   (b) we reuse the buffer directly instead of calling file_get_contents() afterwards
//   (c) initializePlayerState() can still write to the handle normally
$memHandle = fopen("php://memory", "w+");

fwrite($memHandle, "20 20\r\n"); //Player life totals

//Player 1
$p1DeckHandler = fopen($gameDir . "p1Deck.txt", "r");
initializePlayerState($memHandle, $p1DeckHandler, 1);
fclose($p1DeckHandler);

//Player 2
$p2DeckHandler = fopen($gameDir . "p2Deck.txt", "r");
initializePlayerState($memHandle, $p2DeckHandler, 2);
fclose($p2DeckHandler);

// Batch all remaining fixed-format lines into a single fwrite() call.
fwrite($memHandle,
  "\r\n" . //Landmarks
  "0\r\n" . //Game winner (0=none, else player ID)
  "$firstPlayer\r\n" . //First Player
  "1\r\n" . //Current Player
  "0\r\n" . //Current Turn
  "M 1\r\n" . //What phase/player is active
  "1\r\n" . //Action points
  "\r\n" . //Combat Chain
  "0 0 0 0 0 0 0 GY NA 0 0 0 0 0 0 0 NA 0 0 -1 -1 NA 0 0 0 -1 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 -1 0 0 0 0\r\n" . //Combat Chain State
  "\r\n" . //Current Turn Effects
  "\r\n" . //Current Turn Effects From Combat
  "\r\n" . //Next Turn Effects
  "\r\n" . //Decision Queue
  "0\r\n" . //Decision Queue Variables
  "0 - - -\r\n" . //Decision Queue State
  "\r\n" . //Layers
  "\r\n" . //Layer Priority
  "1\r\n" . //What player's turn it is
  "\r\n" . //Last Played Card
  "0\r\n" . //Number of prior chain links this turn
  "\r\n" . //Chain Link Summaries
  $p1Key . "\r\n" . //Player 1 auth key
  $p2Key . "\r\n" . //Player 2 auth key
  "0\r\n" . //Permanent unique ID counter
  "0\r\n" . //Game status -- 0 = START, 1 = PLAY, 2 = OVER
  "\r\n" . //Animations
  "0\r\n" . //Not Used - Current Player activity status -- 0 = active, 2 = inactive
  "0\r\n" . //Player1 Rating - 0 = not rated, 1 = green (positive), 2 = red (negative)
  "0\r\n" . //Player2 Rating - 0 = not rated, 1 = green (positive), 2 = red (negative)
  "0\r\n" . //Player 1 total time
  "0\r\n" . //Player 2 total time
  time() . "\r\n" . //Last update time
  $roguelikeGameID . "\r\n" . //Roguelike game id
  "\r\n" . //Events
  "-\r\n" . //Effect Context
  implode(" ", $p1Inventory) . "\r\n" . //p1 Inventory
  implode(" ", $p2Inventory) . "\r\n" . //p2 Inventory
  $p1IsAI . "\r\n" . //Is player 1 AI (1 = yes, 0 = no)
  $p2IsAI . "\r\n" //Is player 2 AI (1 = yes, 0 = no)
);

// Read the completed gamestate from memory — no disk re-read needed
rewind($memHandle);
$gamestate = stream_get_contents($memHandle);
fclose($memHandle);

// Single disk write replaces the original fopen/~28 fwrite/fclose sequence
file_put_contents($gameDir . "gamestate.txt", $gamestate);

//Write initial gamestate to memory
WriteGamestateCache($gameName, $gamestate);

// Create empty log file
file_put_contents($gameDir . "gamelog.txt", "");

$currentTime = strval(round(microtime(true) * 1000));
$cacheArr = ReadCacheArray($gameName); 
$currentUpdate = $cacheArr[0] ?? "";  
$p1Hero = $cacheArr[6] ?? "";         
$p2Hero = $cacheArr[7] ?? "";          
$visibility = $cacheArr[8] ?? "";      
$format = $cacheArr[12] ?? "";        
$p1chatEnabled = $cacheArr[14] ?? ""; 
$p2chatEnabled = $cacheArr[15] ?? ""; 
$currentPlayer = 0;
$isReplay = 0;
WriteCache($gameName, ($currentUpdate + 1) . "!" . $currentTime . "!" . $currentTime . "!-1!-1!" . $currentTime . "!"  . $p1Hero . "!" . $p2Hero . "!" . $visibility . "!" . $isReplay . "!0!0!" . $format . "!" . $MGS_GameStarted . "!" . $p1chatEnabled . "!" . $p2chatEnabled); //Initialize SHMOP cache for this game

ob_start();
include "ParseGamestate.php";
include "StartEffects.php";
ob_end_clean();
if (ShouldSkipRustCountersForContributors()) {
  WriteLog("No rust counters were accrued because this game includes a Talishar contributor ❤️", highlight: true, highlightColor: "green");
}
elseif (ShouldSkipRustCountersForSupporterGame($p1IsPatron, $p2IsPatron)) {
  WriteLog("No rust counters were accrued because this game includes a Talishar supporter ❤️", highlight: true, highlightColor: "green");
}
AddRustCountersForGameStart($p1id, $p1IsPatron, $p1IsAI, $p2id, $p2IsPatron, $p2IsAI);

$gameStatus = $MGS_GameStarted;
WriteGameFile();

// Set enhanced auth key cookie for fallback recovery
$domain = (!empty(getenv("DOMAIN")) ? getenv("DOMAIN") : "talishar.net");
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$authKeyToReturn = ($playerID == 1 ? $p1Key : $p2Key);

if (!empty($_SESSION["userid"])) {
  StoreLastGameInfo($_SESSION["userid"], $gameName, $playerID, $authKeyToReturn);
}

setcookie("lastAuthKey", $authKeyToReturn, [
  'expires' => time() + (86400 * 7), // 7 days
  'path' => "/",
  'domain' => $domain,
  'secure' => $isSecure,
  'httponly' => true,
  'samesite' => 'Strict'
]);

// Return the auth key to the frontend so it can be stored locally
header('Content-Type: application/json');
echo json_encode(['success' => true, 'authKey' => $authKeyToReturn]);

exit;
