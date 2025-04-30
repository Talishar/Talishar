<?php

// Define constants for repeated values
const DEFAULT_LIFE_TOTAL = "20 20";
const DEFAULT_COMBAT_CHAIN_STATE = "0 0 0 0 0 0 0 GY NA 0 0 0 0 0 0 0 NA 0 0 -1 -1 NA 0 0 0 -1 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0 0";
const DEFAULT_DECISION_QUEUE_STATE = "0 - - -";
const DEFAULT_EFFECT_CONTEXT = "-";

// Initialize output buffering once at the start
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
include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";
include "ParseGamestate.php";
include "StartEffects.php";
ob_end_clean();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];

if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) exit;

session_start();
if($playerID == 1 && isset($_SESSION["p1AuthKey"])) { $targetKey = $p1Key; $authKey = $_SESSION["p1AuthKey"]; }
else if($playerID == 2 && isset($_SESSION["p2AuthKey"])) { $targetKey = $p2Key; $authKey = $_SESSION["p2AuthKey"]; }
if ($authKey != $targetKey) { echo("Invalid auth key"); exit; }

//Initialize global variables
$p1Inventory = [];
$p2Inventory = [];

// Initialize the initial state of the game
$gamePath = "./Games/" . $gameName . "/";
$gamestateFile = $gamePath . "gamestate.txt";
$handler = fopen($gamestateFile, "w");

// Write initial game state
$initialState = [
    DEFAULT_LIFE_TOTAL . "\r\n", // Player life totals
    "\r\n", // Landmarks
    "0\r\n", // Game winner
    "$firstPlayer\r\n", // First Player
    "1\r\n", // Current Player
    "1\r\n", // Current Turn
    "M 1\r\n", // What phase/player is active
    "1\r\n", // Action points
    "\r\n", // Combat Chain
    DEFAULT_COMBAT_CHAIN_STATE . "\r\n", // Combat Chain State
    "\r\n", // Current Turn Effects
    "\r\n", // Current Turn Effects From Combat
    "\r\n", // Next Turn Effects
    "\r\n", // Decision Queue
    "0\r\n", // Decision Queue Variables
    DEFAULT_DECISION_QUEUE_STATE . "\r\n", // Decision Queue State
    "\r\n", // Layers
    "\r\n", // Layer Priority
    "1\r\n", // What player's turn it is
    "\r\n", // Last Played Card
    "0\r\n", // Number of prior chain links this turn
    "\r\n", // Chain Link Summaries
    $p1Key . "\r\n", // Player 1 auth key
    $p2Key . "\r\n", // Player 2 auth key
    "0\r\n", // Permanent unique ID counter
    "0\r\n", // Game status
    "\r\n", // Animations
    "0\r\n", // Current Player activity status
    "0\r\n", // Player1 Rating
    "0\r\n", // Player2 Rating
    "0\r\n", // Player 1 total time
    "0\r\n", // Player 2 total time
    time() . "\r\n", // Last update time
    $roguelikeGameID . "\r\n", // Roguelike game id
    "\r\n", // Events
    DEFAULT_EFFECT_CONTEXT . "\r\n", // Effect Context
    implode(" ", $p1Inventory) . "\r\n", // p1 Inventory
    implode(" ", $p2Inventory) . "\r\n", // p2 Inventory
    $p1IsAI . "\r\n", // Is player 1 AI
    $p2IsAI . "\r\n" // Is player 2 AI
];

fwrite($handler, implode("", $initialState));
fclose($handler);

// Initialize player states
$p1DeckFile = $gamePath . "p1Deck.txt";
$p2DeckFile = $gamePath . "p2Deck.txt";

$handler = fopen($gamestateFile, "a");
$p1DeckHandler = fopen($p1DeckFile, "r");
initializePlayerState($handler, $p1DeckHandler, 1);
fclose($p1DeckHandler);

$p2DeckHandler = fopen($p2DeckFile, "r");
initializePlayerState($handler, $p2DeckHandler, 2);
fclose($p2DeckHandler);
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

//Update the game file to show that the game has started and other players can join to spectate
$gameStatus = $MGS_GameStarted;
WriteGameFile();

exit;

