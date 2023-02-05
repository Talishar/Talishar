<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";
include "../Libraries/NetworkingLibraries.php";
include "../GameLogic.php";
include "../GameTerms.php";
include "../Libraries/StatFunctions.php";
include "../Libraries/PlayerSettings.php";
include "../Libraries/UILibraries2.php";
include "../AI/CombatDummy.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/functions.inc.php";
include_once "../MenuFiles/StartHelper.php";

SetHeaders();


$response = new stdClass();
session_start();
$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = $_POST["gameName"];
$playerID = $_POST["playerID"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];
if (!IsGameNameValid($gameName)) {
  $response->error = "Invalid game name.";
  echo json_encode($response);
  exit;
}
$submissionString = $_POST["submission"];

include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  $response->error = "Invalid Auth Key";
  echo json_encode($response);
  exit;
}

$submission = json_decode($submissionString);
$character = $submission->hero;
if (!isset($submission->hands)) $hands = "";
else $hands = implode(" ", $submission->hands);
if ($hands != "") $character .= " " . $hands;
if (isset($submission->head) && $submission->head != "") $character .= " " . $submission->head;
if (isset($submission->chest) && $submission->chest != "") $character .= " " . $submission->chest;
if (isset($submission->arms) && $submission->arms != "") $character .= " " . $submission->arms;
if (isset($submission->legs) && $submission->legs != "") $character .= " " . $submission->legs;

$filename = "../Games/" . $gameName . "/p" . $playerID . "Deck.txt";
$deckFile = fopen($filename, "w");
fwrite($deckFile, $character . "\r\n");
$deck = (isset($submission->deck) ? implode(" ", $submission->deck) : "");
fwrite($deckFile, $deck);
fclose($deckFile);

if ($playerID == 2) {
  $gameStatus = $MGS_ReadyToStart;
}
else {


  //First initialize the initial state of the game
  $filename = "../Games/" . $gameName . "/gamestate.txt";
  $handler = fopen($filename, "w");
  fwrite($handler, "20 20\r\n"); //Player health totals

  //Player 1
  $p1DeckHandler = fopen("../Games/" . $gameName . "/p1Deck.txt", "r");
  initializePlayerState($handler, $p1DeckHandler, 1);
  fclose($p1DeckHandler);

  //Player 2
  $p2DeckHandler = fopen("../Games/" . $gameName . "/p2Deck.txt", "r");
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
  fwrite($handler, "0 0 NA 0 0 0 0 GY NA 0 0 0 0 0 0 0 NA 0 0 -1 -1 NA 0 0 0 -1 0 0 0 0 - 0 0 0\r\n"); //Combat Chain State
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
  fwrite($handler, $roguelikeGameID . "\r\n"); //Last update time
  fclose($handler);

  //Set up log file
  $filename = "../Games/" . $gameName . "/gamelog.txt";
  $filepath = "../Games/" . $gameName . "/";
  $handler = fopen($filename, "w");
  fclose($handler);

  $currentTime = strval(round(microtime(true) * 1000));
  $currentUpdate = GetCachePiece($gameName, 1);
  $p1Hero = GetCachePiece($gameName, 7);
  $p2Hero = GetCachePiece($gameName, 8);
  $currentPlayer = 0;
  $isReplay = 0;
  WriteCache($gameName, ($currentUpdate + 1) . "!" . $currentTime . "!" . $currentTime . "!-1!-1!" . $currentTime . "!"  . $p1Hero . "!" . $p2Hero . "!" . $currentPlayer . "!" . $isReplay . "!0!0"); //Initialize SHMOP cache for this game

  ob_start();
  $filename = "../Games/" . $gameName . "/gamestate.txt";
  include "../ParseGamestate.php";
  include "../StartEffects.php";
  ob_end_clean();

  //Update the game file to show that the game has started and other players can join to spectate
  $gameStatus = $MGS_GameStarted;

}

$response->status = "OK";

WriteGameFile();
GamestateUpdated($gameName);

echo json_encode($response);
