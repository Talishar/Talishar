<?php


include "../CardDictionary.php";
include '../Libraries/HTTPLibraries.php';
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/CacheLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/functions.inc.php";
include_once "../includes/MatchupHelpers.php";

SetHeaders();

session_start();

$_POST = json_decode(file_get_contents('php://input'), true);
if($_POST == null) exit;
$gameName = $_POST["gameName"];
$playerID = $_POST["playerID"];
$lastUpdate = $_POST["lastUpdate"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];

session_write_close();

$response = new stdClass();

if (!IsGameNameValid($gameName)) {
  $response->error = "Invalid game name";
  echo (json_encode($response));
  exit;
}

if (!file_exists("../Games/" . $gameName . "/")) {
  $response->error = "Game file does not exist";
  echo (json_encode($response));
  exit;
}

if ($lastUpdate == "NaN") $lastUpdate = 0;
if ($lastUpdate > 10000000) $lastUpdate = 0;


include "../WriteLog.php";
include "../HostFiles/Redirector.php";
include "../Libraries/UILibraries.php";
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
$sleepMs = 50; // Exponential backoff start
while ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  usleep(intval($sleepMs * 1000));
  $sleepMs = min($sleepMs * 1.5, 500); // Exponential backoff capped at 500ms
  $currentTime = round(microtime(true) * 1000);
  $cacheVal = GetCachePiece($gameName, 1);
  SetCachePiece($gameName, $playerID + 1, $currentTime);
  ++$count;
  if ($count == 100) break;
  $otherP = $playerID == 1 ? 2 : 1;
  $oppLastTime = GetCachePiece($gameName, $otherP + 1);
  $oppStatus = strval(GetCachePiece($gameName, $otherP + 3));

  if($oppStatus != "-1" && $oppLastTime != "") {
    if(($currentTime - $oppLastTime) > 8000 && $oppStatus == "0") {
      WriteLog("🔌Player $otherP has disconnected.", path: "../");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "-1");
      if ($otherP == 2) SetCachePiece($gameName, $otherP + 6, "");
      $kickPlayerTwo = true;
      include "./APIParseGamefile.php";
      include "../MenuFiles/WriteGamefile.php";
      $p1SideboardSubmitted = "0";
      WriteGameFile();
    }
  }
}

include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  $response->error = "Invalid auth key";
  echo (json_encode($response));
  exit;
}

if ($kickPlayerTwo) {
  $numP2Disconnects = IncrementCachePiece($gameName, 11);
  if ($numP2Disconnects >= 3) {
    WriteLog("This lobby is now hidden due to inactivity. Type in chat to unhide the lobby.");
  }
  if (file_exists("../Games/" . $gameName . "/p2Deck.txt")) unlink("../Games/" . $gameName . "/p2Deck.txt");
  if (file_exists("../Games/" . $gameName . "/p2DeckOrig.txt")) unlink("../Games/" . $gameName . "/p2DeckOrig.txt");
  $gameStatus = $MGS_Initial;
  SetCachePiece($gameName, 14, $gameStatus);
  $p2Data = [];
  $p2uid = "";
  $p2id = "";
  $p2SideboardSubmitted = "0";
  WriteGameFile();
}

$response = new stdClass();

if ($lastUpdate != 0 && $cacheVal < $lastUpdate) {
  $response->lastUpdate = GetCachePiece($gameName, 1);
  echo json_encode($response);
  exit;
} else if ($gameStatus == $MGS_GameStarted) {
  $response->lastUpdate = "1";
  $response->isMainGameReady = true;
  if(IsUserLoggedIn()) StoreLastGameInfo(LoggedInUser(), $gameName, $playerID, $authKey);
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
    fclose($handler);
    
    if (is_array($otherCharacter) && count($otherCharacter) > 0) {
      $otherHero = $otherCharacter[0];
    } else {
      $otherHero = "CardBack";
    }
  }
  $response->theirHero = $otherHero;
  $response->theirHeroName = CardName($otherHero);

  $theirName = ($playerID == 1 ? $p2uid : $p1uid);
  if ($theirName == '-') $theirName = "Player " . ($playerID == 1 ? 2 : 1);
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

  $response->isMainGameReady = ($gameStatus == $MGS_ReadyToStart && $p1SideboardSubmitted == "1" && $p2SideboardSubmitted == "1");
  if($p1IsAI || $p2IsAI) {
    $response->canSubmitSideboard =($gameStatus > $MGS_ChooseFirstPlayer && ($playerID == 1 ? $p1SideboardSubmitted == "0" : $p2SideboardSubmitted == "0"));
  }
  else $response->canSubmitSideboard = ($gameStatus > $MGS_ChooseFirstPlayer && $gameStatus != $MGS_ReadyToStart);

  $decklink = ($playerID == 1 ? $p1DeckLink : $p2DeckLink);
  $matchups = ($playerID == 1 ? $p1Matchups : $p2Matchups);
  // Transform matchups to ensure turn order preferences are standardized
  $matchups = TransformMatchupsWithTurnOrder($matchups);
  $response->myDeckLink = $decklink;
  $response->matchups = $matchups;

  // If both players have enabled chat, is true, else false
  $p1ChatStatus = intval(GetCachePiece($gameName, 15));
  $p2ChatStatus = intval(GetCachePiece($gameName, 16));
  $response->chatEnabled = ($p1ChatStatus == 1 && $p2ChatStatus == 1 ? true : false);
  if($playerID == 1) $response->chatInvited = ($p1ChatStatus == 0 && $p2ChatStatus == 1);
  else if($playerID == 2) $response->chatInvited = ($p2ChatStatus == 0 && $p1ChatStatus == 1);

  echo json_encode($response);
  exit;
}
