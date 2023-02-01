<?php

include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";

session_start();
$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = $_POST["gameName"];
$playerID = $_POST["playerID"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$submission = $_POST["submission"];

include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  echo ("Invalid Auth Key");
  exit;
}

$submission = json_decode($submission);
$character = $submission->hero;
$hands = implode(" ", $submission->hands);
if($hands != "") $character .= " " . $hands;
if($submission->head != "") $character .= " " . $submission->head;
if($submission->chest != "") $character .= " " . $submission->chest;
if($submission->arms != "") $character .= " " . $submission->arms;
if($submission->legs != "") $character .= " " . $submission->legs;

$filename = "../Games/" . $gameName . "/p" . $playerID . "Deck.txt";
$deckFile = fopen($filename, "w");
fwrite($deckFile, implode(" ", $character) . "\r\n");
fwrite($deckFile, implode(" ", $submission->deck));
fclose($deckFile);

if ($playerID == 2) {
  $gameStatus = $MGS_ReadyToStart;
} else {
  $gameStatus = $MGS_GameStarted;
}
WriteGameFile();
GamestateUpdated($gameName);


?>
