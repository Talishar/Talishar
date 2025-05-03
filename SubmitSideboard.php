<?php

include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$playerCharacter = $_GET["playerCharacter"];
$playerDeck = $_GET["playerDeck"];
$authKey = $_GET["authKey"];

include "WriteLog.php";
include "HostFiles/Redirector.php";
include "CardDictionary.php";

include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  echo ("Invalid Auth Key");
  exit;
}

if ($playerCharacter != "" && $playerDeck != "") //If they submitted before loading even finished, use the deck as it existed before
{
  $char = explode(",", $playerCharacter);
  $numHands = 0;
  $numEquip = 0;
  for ($i = 0; $i < count($char); ++$i) {
    if (CardSubType($char[$i]) == "Off-Hand") ++$numHands;
    else if (CardType($char[$i]) == "W") {
      if (Is1H($char[$i])) ++$numHands;
      else $numHands += 2;
    }
    else if (CardType($char[$i]) == "E") ++$numEquip;
  }
  if ($numHands < 1) {
    WriteLog("Unable to submit player " . $playerID . "'s deck. " . $numHands . " weapon currently equipped.");
    header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
    exit;
  }
  if ($numHands > 2) {
    WriteLog("Unable to submit player " . $playerID . "'s deck. " . $numHands . " weapons currently equipped.");
    header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
    exit;
  }

  $playerDeck = explode(",", $playerDeck);
  $deckCount = count($playerDeck);
  if ($deckCount < 60 && ($format == "cc" || $format == "compcc" || $format == "llcc")) {
    WriteLog("Unable to submit player " . $playerID . "'s deck. " . $deckCount . " cards selected is under the legal minimum.");
    header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
    exit;
  }
  if ($deckCount < 40 && ($format == "blitz" || $format == "compblitz" || $format == "commoner" || $format == "llblitz")) {
    WriteLog("Unable to submit player " . $playerID . "'s deck. " . $deckCount . " cards selected is under the legal minimum.");
    header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
    exit;
  }

  for ($i = $deckCount - 1; $i >= 0; --$i) {
    $cardType = CardType($playerDeck[$i]);
    if ($cardType == "" || $cardType == "C" || $cardType == "E" || $cardType == "W") unset($playerDeck[$i]);
  }
  $playerDeck = array_values($playerDeck);
  $filename = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $deckFile = fopen($filename, "w");
  fwrite($deckFile, implode(" ", $char) . "\r\n");
  fwrite($deckFile, implode(" ", $playerDeck));
  fclose($deckFile);
}

if($playerID == 1) $p1SideboardSubmitted = "1";
else if($playerID == 2) $p2SideboardSubmitted = "1";

$gameStarted = false;
if ($p1SideboardSubmitted == "1" && $p2SideboardSubmitted == "1") {
  $gameStatus = $MGS_ReadyToStart;
  SetCachePiece($gameName, 14, $gameStatus);
  $gameStarted = true;
}
WriteGameFile();
GamestateUpdated($gameName);

if ($gameStarted == 1) {
  header("Location: {$redirectPath}/Start.php?gameName={$gameName}&playerID={$playerID}");
} else {
  header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
}
