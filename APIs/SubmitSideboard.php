<?php

include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";

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

$response->status = "OK";

WriteGameFile();
GamestateUpdated($gameName);

echo json_encode($response);
