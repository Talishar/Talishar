<?php


include '../Libraries/HTTPLibraries.php';
include_once "../Libraries/PlayerSettings.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";

session_start();

$gameName = $_POST["gameName"];
$playerID = $_POST["playerID"];
$lastUpdate = $_POST["lastUpdate"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];

session_write_close();

if (!IsGameNameValid($gameName)) {
  echo(json_encode(new stdClass()));
  exit;
}

if(!file_exists("../Games/" . $gameName . "/")) { echo(json_encode(new stdClass())); exit; }

if($lastUpdate == "NaN") $lastUpdate = 0;
if ($lastUpdate > 10000000) $lastUpdate = 0;


include "../WriteLog.php";
include "../CardDictionary.php";
include "../HostFiles/Redirector.php";
include "../Libraries/UILibraries2.php";
include "../Libraries/SHMOPLibraries.php";

$currentTime = round(microtime(true) * 1000);
SetCachePiece($gameName, $playerID + 1, $currentTime);

$count = 0;
$cacheVal = GetCachePiece($gameName, 1);
if ($cacheVal > 10000000) {
  SetCachePiece($gameName, 1, 1);
  $lastUpdate = 0;
}
$kickPlayerTwo = false;
while ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  usleep(100000); //100 milliseconds
  $currentTime = round(microtime(true) * 1000);
  $cacheVal = GetCachePiece($gameName, 1);
  SetCachePiece($gameName, $playerID + 1, $currentTime);
  ++$count;
  if ($count == 100) break;
  $otherP = ($playerID == 1 ? 2 : 1);
  $oppLastTime = GetCachePiece($gameName, $otherP + 1);
  $oppStatus = strval(GetCachePiece($gameName, $otherP + 3));

  if ($oppStatus != "-1" && $oppLastTime != "") {
    if (($currentTime - $oppLastTime) > 8000 && $oppStatus == "0") {
      WriteLog("Player $otherP has disconnected.", path:"../");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "-1");
      SetCachePiece($gameName, $otherP + 6, "");
      $kickPlayerTwo = true;
    }
  }
}

include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  echo(json_encode(new stdClass()));
  exit;
}

if ($kickPlayerTwo) {
  if($oppStatus != "-1" && ($format == "compcc" || $format == "compblitz"))
  {
      //This happens when player 2 "dodges" -- add logging?
  }
  if (file_exists("../Games/" . $gameName . "/p2Deck.txt")) unlink("./Games/" . $gameName . "/p2Deck.txt");
  if (file_exists("../Games/" . $gameName . "/p2DeckOrig.txt")) unlink("./Games/" . $gameName . "/p2DeckOrig.txt");
  $gameStatus = $MGS_Initial;
  $p2Data = [];
  WriteGameFile();
}

$response = new stdClass();

if ($lastUpdate != 0 && $cacheVal < $lastUpdate) {
  $response->lastUpdate = GetCachePiece($gameName, 1);
  echo json_encode($response);
  exit;
} else if ($gameStatus == $MGS_GameStarted) {
  $response->lastUpdate = "1";
  echo json_encode($response);
  exit;
} else {

  $response->lastUpdate = GetCachePiece($gameName, 1);
  if ($gameStatus == $MGS_ChooseFirstPlayer) {
    $response->amIChoosingFirstPlayer = ($playerID == $firstPlayerChooser);
  }

  if ($playerID == 1 && $gameStatus < $MGS_Player2Joined) {
    $response->isPrivateLobby = ($visibility == "private");
  }


  $response->gameLog = JSONLog($gameName, $playerID, "../");

  $response->playAudio = ($playerID == 1 && $gameStatus == $MGS_ChooseFirstPlayer ? 1 : 0);

  $otherHero = "CardBack";
  $otherPlayer = $playerID == 1 ? 2 : 1;
  $deckFile = "../Games/" . $gameName . "/p" . $otherPlayer . "Deck.txt";
  if (file_exists($deckFile)) {
    $handler = fopen($deckFile, "r");
    $otherCharacter = GetArray($handler);
    $otherHero = $otherCharacter[0];
    fclose($handler);
  }
  $response->theirHero = $otherHero;
  $response->theirName = CardName($otherHero);

  $theirName = ($playerID == 1 ? $p2uid : $p1uid);
  if($theirName == '-') $theirName = "Player " . ($playerID == 1 ? 2 : 1);
  $contentCreator = ContentCreators::tryFrom(($playerID == 1 ? $p2ContentCreatorID : $p1ContentCreatorID));
  $nameColor = ($contentCreator != null ? $contentCreator->NameColor() : "");
  $overlayURL = ($contentCreator != null ? $contentCreator->HeroOverlayURL($otherHero) : "");
  $channelLink = ($contentCreator != null ? $contentCreator->ChannelLink() : "");

  $response->theirName = $theirName;
  $response->theirNameColor = $nameColor;
  $response->theirOverlayUrl = $overlayURL;
  $response->theirChannelLink = $channelLink;

  $response->submitSideboard = ($playerID == 1 ? ($gameStatus == $MGS_ReadyToStart ? "block" : "none") : ($gameStatus == $MGS_P2Sideboard ? "block" : "none"));

  $response->myPriority = true;
  if ($gameStatus == $MGS_ChooseFirstPlayer) $response->myPriority = ($playerID == $firstPlayerChooser ? true : false);
  else if ($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $response->myPriority = false;
  else if ($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $response->myPriority = false;

  echo json_encode($response);
  exit;
}
