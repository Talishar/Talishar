<?php

include "Libraries/HTTPLibraries.php";
include_once "Libraries/SHMOPLibraries.php";

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];

if ($playerID == 3) {
  echo ("Spectators cannot submit sideboard.");
  exit;
}

$playerCharacter = $_GET["playerCharacter"];
$playerDeck = $_GET["playerDeck"];
$authKey = $_GET["authKey"];

include "WriteLog.php";
include "HostFiles/Redirector.php";
include "CardDictionary.php";

include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey !== $targetAuth) {
  // Failsafe: Use game file's auth key if mismatch (lost on page refresh)
  $authKey = $targetAuth;
}

if ($playerCharacter != "" && $playerDeck != "") //If they submitted before loading even finished, use the deck as it existed before
{
  $char = explode(",", $playerCharacter);
  $charCount = count($char);
  $numHands = 0;
  $numEquip = 0;
  for ($i = 0; $i < $charCount; ++$i) {
    $card = $char[$i];
    if (CardSubType($card) == "Off-Hand") {
      ++$numHands;
    } else {
      $cardType = CardType($card);
      if ($cardType == "W") {
        if (Is1H($card)) ++$numHands;
        else $numHands += 2;
      } elseif ($cardType == "E") {
        ++$numEquip;
      }
    }
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
  $ccFormats = ["cc" => 1, "compcc" => 1, "llcc" => 1, "compllcc" => 1, "futurecc" => 1, "gage" => 1];
  $blitzFormats = ["blitz" => 1, "commoner" => 1, "sage" => 1, "compsage" => 1, "futuresage" => 1];
  if ($deckCount < 60 && isset($ccFormats[$format])) {
    WriteLog("Unable to submit player " . $playerID . "'s deck. " . $deckCount . " cards selected is under the legal minimum.");
    header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
    exit;
  }
  if ($deckCount < 40 && isset($blitzFormats[$format])) {
    WriteLog("Unable to submit player " . $playerID . "'s deck. " . $deckCount . " cards selected is under the legal minimum.");
    header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
    exit;
  }

  $filteredDeck = [];
  for ($i = 0; $i < $deckCount; ++$i) {
    $cardType = CardType($playerDeck[$i]);
    if ($cardType !== "" && $cardType !== "C" && $cardType !== "E" && $cardType !== "W") {
      $filteredDeck[] = $playerDeck[$i];
    }
  }
  $playerDeck = $filteredDeck;
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

if ($gameStarted) {
  header("Location: {$redirectPath}/Start.php?gameName={$gameName}&playerID={$playerID}");
} else {
  header("Location: {$redirectPath}/GameLobby.php?gameName={$gameName}&playerID={$playerID}");
}
