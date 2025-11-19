<?php

ob_start();
include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include "Libraries/NetworkingLibraries.php";
include "GameLogic.php";
include "GameTerms.php";
include "Libraries/StatFunctions.php";
include "Libraries/PlayerSettings.php";
include "Libraries/UILibraries.php";
include "AI/CombatDummy.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "./MenuFiles/StartHelper.php";
ob_end_clean();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];

if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) exit;

ob_start();
include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";
ob_end_clean();
session_start();
if($playerID == 1 && isset($_SESSION["p1AuthKey"])) { $targetKey = $p1Key; $authKey = $_SESSION["p1AuthKey"]; }
else if($playerID == 2 && isset($_SESSION["p2AuthKey"])) { $targetKey = $p2Key; $authKey = $_SESSION["p2AuthKey"]; }
if (isset($authKey) && isset($targetKey) && $authKey != $targetKey) {
  // Failsafe: Use game file's auth key if mismatch (lost on page refresh)
  $authKey = $targetKey;
}

//Initialize global variables
$p1Inventory = "";
$p2Inventory = "";

//Initialize the initial state of the game
$filename = "./Games/" . $gameName . "/gamestate.txt";
$handler = fopen($filename, "w");
fwrite($handler, "20 20\r\n"); //Player life totals

//Player 1
$p1DeckHandler = fopen("./Games/" . $gameName . "/p1Deck.txt", "r");
initializePlayerState($handler, $p1DeckHandler, 1);
fclose($p1DeckHandler);

//Player 2
$p2DeckHandler = fopen("./Games/" . $gameName . "/p2Deck.txt", "r");
initializePlayerState($handler, $p2DeckHandler, 2);
fclose($p2DeckHandler);

fwrite($handler, "\r\n"); //Landmarks
fwrite($handler, "0\r\n"); //Game winner (0=none, else player ID)
fwrite($handler, "$firstPlayer\r\n"); //First Player
fwrite($handler, "1\r\n"); //Current Player
fwrite($handler, "1\r\n"); //Current Turn
fwrite($handler, "M 1\r\n"); //What phase/player is active
fwrite($handler, "1\r\n"); //Action points
fwrite($handler, "\r\n"); //Combat Chain
fwrite($handler, "0 0 0 0 0 0 0 GY NA 0 0 0 0 0 0 0 NA 0 0 -1 -1 NA 0 0 0 -1 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 -1\r\n"); //Combat Chain State
fwrite($handler, "\r\n"); //Current Turn Effects
fwrite($handler, "\r\n"); //Current Turn Effects From Combat
fwrite($handler, "\r\n"); //Next Turn Effects
fwrite($handler, "\r\n"); //Decision Queue
fwrite($handler, "0\r\n"); //Decision Queue Variables
fwrite($handler, "0 - - -\r\n"); //Decision Queue State
fwrite($handler, "\r\n"); //Layers
fwrite($handler, "\r\n"); //Layer Priority
fwrite($handler, "1\r\n"); //What player's turn it is
fwrite($handler, "\r\n"); //Last Played Card
fwrite($handler, "0\r\n"); //Number of prior chain links this turn
fwrite($handler, "\r\n"); //Chain Link Summaries
fwrite($handler, $p1Key . "\r\n"); //Player 1 auth key
fwrite($handler, $p2Key . "\r\n"); //Player 2 auth key
fwrite($handler, 0 . "\r\n"); //Permanent unique ID counter
fwrite($handler, "0\r\n"); //Game status -- 0 = START, 1 = PLAY, 2 = OVER
fwrite($handler, "\r\n"); //Animations
fwrite($handler, "0\r\n"); //Current Player activity status -- 0 = active, 2 = inactive
fwrite($handler, "0\r\n"); //Player1 Rating - 0 = not rated, 1 = green (positive), 2 = red (negative)
fwrite($handler, "0\r\n"); //Player2 Rating - 0 = not rated, 1 = green (positive), 2 = red (negative)
fwrite($handler, "0\r\n"); //Player 1 total time
fwrite($handler, "0\r\n"); //Player 2 total time
fwrite($handler, time() . "\r\n"); //Last update time
fwrite($handler, $roguelikeGameID . "\r\n"); //Roguelike game id
fwrite($handler, "\r\n");//Events
fwrite($handler, "-\r\n");//Effect Context
fwrite($handler, implode(" ", $p1Inventory) . "\r\n"); //p1 Inventory
fwrite($handler, implode(" ", $p2Inventory) . "\r\n"); //p2 Inventory
fwrite($handler, $p1IsAI . "\r\n");//Is player 1 AI (1 = yes, 0 = no)
fwrite($handler, $p2IsAI . "\r\n");//Is player 2 AI (1 = yes, 0 = no)
fclose($handler);

//Write initial gamestate to memory
$gamestate = file_get_contents("./Games/" . $gameName . "/gamestate.txt");
WriteGamestateCache($gameName, $gamestate);

//Set up log file
$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);

$currentTime = strval(round(microtime(true) * 1000));
$currentUpdate = GetCachePiece($gameName, 1);
$p1Hero = GetCachePiece($gameName, 7);
$p2Hero = GetCachePiece($gameName, 8);
$visibility = GetCachePiece($gameName, 9);
$format = GetCachePiece($gameName, 13);
$p1chatEnabled = GetCachePiece($gameName, 15);
$p2chatEnabled = GetCachePiece($gameName, 16);
$currentPlayer = 0;
$isReplay = 0;
WriteCache($gameName, ($currentUpdate + 1) . "!" . $currentTime . "!" . $currentTime . "!-1!-1!" . $currentTime . "!"  . $p1Hero . "!" . $p2Hero . "!" . $visibility . "!" . $isReplay . "!0!0!" . $format . "!" . $MGS_GameStarted . "!" . $p1chatEnabled . "!" . $p2chatEnabled); //Initialize SHMOP cache for this game

ob_start();
include "ParseGamestate.php";
include "StartEffects.php";
ob_end_clean();
//Update the game file to show that the game has started and other players can join to spectate
$gameStatus = $MGS_GameStarted;
WriteGameFile();

// Return the auth key to the frontend so it can be stored locally
header('Content-Type: application/json');
$response = new stdClass();
$response->success = true;
$response->authKey = ($playerID == 1 ? $p1Key : $p2Key);
echo json_encode($response);

exit;

