<?php

include '../Libraries/HTTPLibraries.php';

SetHeaders();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function SendLobbyRefreshError($status, $message)
{
  http_response_code($status);
  echo json_encode(["error" => $message]);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  header('Allow: POST, OPTIONS');
  SendLobbyRefreshError(405, "Method not allowed");
}

$requestData = json_decode(file_get_contents('php://input'), true);
if (!is_array($requestData)) {
  SendLobbyRefreshError(400, "Invalid JSON request body");
}

if (!array_key_exists("gameName", $requestData) || !is_numeric($requestData["gameName"])) {
  SendLobbyRefreshError(400, "Invalid game name");
}
if (!array_key_exists("playerID", $requestData) || !is_numeric($requestData["playerID"])) {
  SendLobbyRefreshError(400, "Invalid player ID");
}

$gameName = $requestData["gameName"];
$playerID = (int)$requestData["playerID"];
if ($playerID < 1 || $playerID > 3) {
  SendLobbyRefreshError(400, "Invalid player ID");
}
$isLobbyPlayer = $playerID === 1 || $playerID === 2;

$lastUpdate = $requestData["lastUpdate"] ?? null;
if ($lastUpdate !== null && $lastUpdate !== "NaN" && !is_numeric($lastUpdate)) {
  SendLobbyRefreshError(400, "Invalid last update value");
}

include "../CardDictionary.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/CacheLibraries.php";
include_once "../Libraries/LegalHeroesHelper.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/functions.inc.php";
include_once "../includes/MatchupHelpers.php";
include_once "../includes/ModeratorList.inc.php";
include_once "../Libraries/ValidationLibraries.php";

session_start();

if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($requestData["authKey"])) $authKey = $requestData["authKey"];
$lastAuthKey = $_SESSION["lastAuthKey"] ?? null;
$spectatorLoggedIn = $playerID !== 3 || (IsUserLoggedIn() && !empty(LoggedInUserName()));

session_write_close();

$response = new stdClass();

if (!IsGameNameValid($gameName)) {
  SendLobbyRefreshError(400, "Invalid game name");
}

if (!file_exists("../Games/" . $gameName . "/")) {
  SendLobbyRefreshError(404, "Game file does not exist");
}

if (!$spectatorLoggedIn) {
  SendLobbyRefreshError(401, "Authentication required to spectate.");
}

// Reject invalid player credentials before touching heartbeat state or entering the long poll.
if ($isLobbyPlayer) {
  $gameFilePath = "../Games/" . $gameName . "/GameFile.txt";
  $authFile = @fopen($gameFilePath, "r");
  if ($authFile === false) {
    SendLobbyRefreshError(404, "Game file does not exist");
  }
  if (!flock($authFile, LOCK_SH)) {
    fclose($authFile);
    SendLobbyRefreshError(503, "Lobby is temporarily unavailable");
  }
  for ($line = 0; $line < 7; ++$line) fgets($authFile);
  $p1AuthKey = trim((string)fgets($authFile));
  $p2AuthKey = trim((string)fgets($authFile));
  flock($authFile, LOCK_UN);
  fclose($authFile);

  if (!validateGameAuthKey($playerID, $authKey ?? null, $p1AuthKey, $p2AuthKey)) {
    SendLobbyRefreshError(403, "Authentication failed");
  }
}

if ($lastUpdate == "NaN") $lastUpdate = 0;
if ($lastUpdate > 10000000) $lastUpdate = 0;


include "../WriteLog.php";
include "../HostFiles/Redirector.php";
include "../Libraries/UILibraries.php";
include_once "../Libraries/SHMOPLibraries.php";

$currentTime = (int)(microtime(true) * 1000);

$longPollDeadline = $currentTime + 8000;
$otherP = $playerID == 1 ? 2 : 1;
$myTimeIdx   = $isLobbyPlayer ? $playerID : null;
$oppTimeIdx  = $otherP;
$oppStatIdx  = $otherP + 2;
$kickPlayerTwo = false;
$sideboardWasReset = false;

$cacheArr = ReadCacheArray($gameName);
if ($cacheArr && $isLobbyPlayer) {
  $cacheArr[$myTimeIdx] = $currentTime;
  WriteCache($gameName, implode("!", $cacheArr));
}
$cacheVal = $cacheArr ? (int)($cacheArr[0] ?? 0) : 0;
if ($cacheVal > 10000000) {
  SetCachePiece($gameName, 1, 1);
  $lastUpdate = 0;
  $cacheVal = 1;
}

$sleepUs = 50000;
while ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  usleep($sleepUs);
  $sleepUs = min((int)($sleepUs * 1.5), 200000);
  $currentTime = (int)(microtime(true) * 1000);

  $cacheArr = ReadCacheArray($gameName);
  if (!$cacheArr) break;
  $cacheVal     = (int)$cacheArr[0];
  $oppLastTime  = $cacheArr[$oppTimeIdx] ?? "";
  $oppStatus    = strval($cacheArr[$oppStatIdx] ?? "");

  if ($isLobbyPlayer) {
    $myLastTime = $cacheArr[$myTimeIdx] ?? "";
    if ($myLastTime !== "" && ($currentTime - (int)$myLastTime) > LOBBY_DISCONNECT_TIMEOUT_MS) break;

    $cacheArr[$myTimeIdx] = $currentTime;
    WriteCache($gameName, implode("!", $cacheArr));

    $inLobbyPhase = ((int)($cacheArr[13] ?? 0)) < 5;

    if ($inLobbyPhase && $oppStatus !== "-1" && $oppLastTime !== "") {
      if (($currentTime - (int)$oppLastTime) > LOBBY_DISCONNECT_TIMEOUT_MS && $oppStatus === "0") {
        $cacheArr[$oppStatIdx] = "-1";
        if ($otherP == 2) $cacheArr[$otherP + 5] = "";
        WriteCache($gameName, implode("!", $cacheArr));
        GamestateUpdated($gameName);
        $kickPlayerTwo = true;
        break;
      }
    }
  }

  if ($currentTime >= $longPollDeadline) break;
}

include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

// Spectators carry no key of their own; validateGameAuthKey passes them through.
if (!validateGameAuthKey($playerID, $authKey ?? null, $p1Key, $p2Key)) {
  SendLobbyRefreshError(403, "Authentication failed");
}

if ($kickPlayerTwo && $gameStatus < $MGS_GameStarted) {
  // $playerID is the polling player, so the one who disconnected is the other one.
  $disconnectedPlayer = ($playerID == 1 ? 2 : 1);
  $kickSignal = GetCachePiece($gameName, 17);
  $wasChoosingSideboard = $gameStatus >= $MGS_P2Sideboard && $gameStatus < $MGS_GameStarted;

  if ($wasChoosingSideboard) {
    $originalDeck = "../Games/" . $gameName . "/p" . $playerID . "DeckOrig.txt";
    $activeDeck = "../Games/" . $gameName . "/p" . $playerID . "Deck.txt";
    if (file_exists($originalDeck)) {
      copy($originalDeck, $activeDeck);
      $sideboardWasReset = true;
    }
  }

  if ($disconnectedPlayer != 2 || $kickSignal !== "kicked") {
    WriteLog($wasChoosingSideboard ? "Your opponent has left the lobby." : "🔌 Your opponent has disconnected.", path: "../");
  }

  if ($disconnectedPlayer == 2 && $kickSignal !== "kicked") {
    $numP2Disconnects = IncrementCachePiece($gameName, 11);
    if ($numP2Disconnects >= 3) {
      WriteLog("This lobby is now hidden due to inactivity. Type in chat to unhide the lobby.");
    }
  }

  $gameStatus = $MGS_Initial;
  SetCachePiece($gameName, 14, $gameStatus);

  if ($disconnectedPlayer == 2) {
    if (file_exists("../Games/" . $gameName . "/p2Deck.txt")) unlink("../Games/" . $gameName . "/p2Deck.txt");
    if (file_exists("../Games/" . $gameName . "/p2DeckOrig.txt")) unlink("../Games/" . $gameName . "/p2DeckOrig.txt");

    $p2Data = [];
    $p2uid = "";
    $p2DisplayName = "";
    $p2id = "";
    $p2SideboardSubmitted = "0";
    $p1SideboardSubmitted = "0";
  } else {
    SetCachePiece($gameName, 7, "");
    $p1uid = "-";
    $p1DisplayName = "";
    $p1id = "";
    $p1SideboardSubmitted = "0";
    $p2SideboardSubmitted = "0";
  }

  WriteGameFile();
}

$response = new stdClass();
$response->sideboardWasReset = $sideboardWasReset;

if ($lastUpdate != 0 && $cacheVal < $lastUpdate) {
  // Stale-state: cache hasn't advanced beyond what client already has.
  // Sleep briefly so clients don't tight-loop polling the server.
  usleep(500000); // 500ms
  $response->lastUpdate = GetCachePiece($gameName, 1);
  echo json_encode($response);
  exit;
} else if ($gameStatus == $MGS_GameStarted) {
  $response->lastUpdate = "1";
  $response->isMainGameReady = true;
  $response->isSideboarding = false;
  $response->mySideboardSubmitted = true;
  $response->opponentSideboardSubmitted = true;
  if(IsUserLoggedIn() && ($lastAuthKey == null || $lastAuthKey !== $authKey)) StoreLastGameInfo(LoggedInUser(), $gameName, $playerID, $authKey);
  echo json_encode($response);
  exit;
} else {
  $cacheArr = ReadCacheArray($gameName);

  // Detect if player 2 was kicked: they are polling but the game slot is empty again
  if ($playerID == 2 && ($p2uid === "" || $p2uid === "-") && $gameStatus == $MGS_Initial) {
    $kickSignal = $cacheArr[16] ?? "";
    if ($kickSignal === "kicked") {
      SetCachePiece($gameName, 17, "");
      $response->wasKicked = true;
      echo json_encode($response);
      exit;
    }
  }

  $response->lastUpdate = $cacheArr[0] ?? "";
  if ($gameStatus == $MGS_ChooseFirstPlayer) {
    $response->amIChoosingFirstPlayer = ($playerID == $firstPlayerChooser);
  }

  $response->visibility = $visibility;
  $response->isPrivateLobby = ($visibility != "public");
  if ($playerID == 1 && $gameStatus < $MGS_Player2Joined) {
    $response->format = $format;
    $response->gameDescription = $gameDescription;

    $lobbyCreatedAt = intval($cacheArr[5] ?? "");
    $warningAlreadySent = $cacheArr[11] ?? ""; 
    if ($lobbyCreatedAt > 0 && $warningAlreadySent != "1"
        && ($currentTime - $lobbyCreatedAt) > 600000) { // 10 minutes in ms
      SetCachePiece($gameName, 12, "1");
      WriteLog("⏳ This lobby has been open for over 10 minutes with no opponent. If you keep having trouble finding a match, try creating a new game.", path: "../");
      GamestateUpdated($gameName);
    }
  }

  $response->gameLog = JSONLog($gameName, $playerID, "../");

  $response->playAudio = ($playerID == 1 && $gameStatus == $MGS_ChooseFirstPlayer ? 1 : 0);

  $otherHero = "CardBack";
  $otherPlayer = $otherP; // $otherP already computed above
  $otherUid = ($playerID == 1 ? $p2uid : $p1uid);
  $otherSeatOccupied = ($otherUid !== "" && $otherUid !== "-");
  $deckFile = "../Games/" . $gameName . "/p" . $otherPlayer . "Deck.txt";
  if ($otherSeatOccupied && file_exists($deckFile)) {
    $handler = fopen($deckFile, "r");
    $firstLine = trim(fgets($handler));
    fclose($handler);
    if ($firstLine !== '') {
      $spacePos = strpos($firstLine, ' ');
      $otherHero = $spacePos !== false ? substr($firstLine, 0, $spacePos) : $firstLine;
    }
  }
  $response->theirHero = $otherHero;
  $response->theirHeroName = CardName($otherHero);

  $theirName = ($playerID == 1 ? $p2DisplayName : $p1DisplayName);
  if ($theirName == '-' || $theirName == '') $theirName = "Player " . ($playerID == 1 ? 2 : 1);
  $contentCreator = ContentCreators::tryFrom($playerID == 1 ? $p2ContentCreatorID : $p1ContentCreatorID);
  if ($contentCreator !== null) {
    $nameColor = $contentCreator->NameColor();
    $overlayURL = $contentCreator->HeroOverlayURL($otherHero);
    $channelLink = $contentCreator->ChannelLink();
  } else {
    $nameColor = $overlayURL = $channelLink = "";
  }

  $response->theirName = $theirName;
  $response->theirNameColor = $nameColor;
  $response->theirOverlayUrl = $overlayURL;
  $response->theirChannelLink = $channelLink;

  // Patron/supporter info for opponent
  $theirUid = ($playerID == 1 ? $p2uid : $p1uid);
  $response->theirIsContributor = IsUserContributor($theirUid);
  $response->theirIsPatron = ($playerID == 1 ? $p2IsPatron : $p1IsPatron) ?: "";
  $response->theirIsPvtVoidPatron = ($theirUid === "PvtVoid");
  $response->theirMetafyTiers = ($playerID == 1 ? $p2MetafyTiers : $p1MetafyTiers) ?: [];

  $response->submitSideboard = $playerID == 1 ? ($gameStatus == $MGS_ReadyToStart ? "block" : "none") : ($gameStatus == $MGS_P2Sideboard ? "block" : "none");

  $response->myPriority = true;
  if ($gameStatus == $MGS_ChooseFirstPlayer) $response->myPriority = $playerID == $firstPlayerChooser;
  else if ($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $response->myPriority = false;
  else if ($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $response->myPriority = false;

  $response->isMainGameReady = ($gameStatus == $MGS_ReadyToStart && $p1SideboardSubmitted == "1" && $p2SideboardSubmitted == "1");
  $response->isSideboarding = $gameStatus > $MGS_ChooseFirstPlayer && $gameStatus < $MGS_GameStarted;
  $response->mySideboardSubmitted = ($playerID == 1 ? $p1SideboardSubmitted == "1" : $p2SideboardSubmitted == "1");
  $response->opponentSideboardSubmitted = ($playerID == 1 ? $p2SideboardSubmitted == "1" : $p1SideboardSubmitted == "1");
  $opponentIsAI = ($playerID == 1 ? $p2IsAI == "1" : $p1IsAI == "1");
  $response->isOpponentAI = $opponentIsAI;
  if($p1IsAI || $p2IsAI) {
    $response->canSubmitSideboard =($gameStatus > $MGS_ChooseFirstPlayer && ($playerID == 1 ? $p1SideboardSubmitted == "0" : $p2SideboardSubmitted == "0"));
  }
  else $response->canSubmitSideboard = ($gameStatus > $MGS_ChooseFirstPlayer && $gameStatus != $MGS_ReadyToStart);
  $response->canUnreadySideboard = (
    $gameStatus > $MGS_ChooseFirstPlayer &&
    $gameStatus < $MGS_ReadyToStart &&
    ($playerID == 1 ? $p1SideboardSubmitted == "1" : $p2SideboardSubmitted == "1")
  );

  $decklink = ($playerID == 1 ? $p1DeckLink : $p2DeckLink);
  $matchups = ($playerID == 1 ? $p1Matchups : $p2Matchups);
  // Transform matchups to ensure turn order preferences are standardized
  $matchups = TransformMatchupsWithTurnOrder($matchups);
  $response->myDeckLink = $decklink;
  $response->matchups = $matchups;
  $response->legalHeroes = GetLegalHeroes($format);

  // If both players have enabled chat, is true, else false
  $p1ChatStatus = intval($cacheArr[14] ?? "");
  $p2ChatStatus = intval($cacheArr[15] ?? "");
  $response->chatEnabled = ($p1ChatStatus == 1 && $p2ChatStatus == 1 ? true : false);
  if($playerID == 1) $response->chatInvited = ($p1ChatStatus == 0 && $p2ChatStatus == 1);
  else if($playerID == 2) $response->chatInvited = ($p2ChatStatus == 0 && $p1ChatStatus == 1);

  // Typing indicator — same APCu key used by ChatTyping.php.
  // Piggybacking on the existing lobby poll costs zero extra requests.
  if ($response->chatEnabled && ($playerID == 1 || $playerID == 2)) {
    $typingCacheKey = "typing_" . md5($gameName) . "_player_" . $otherP;
    $opponentIsTyping = false;
    if (extension_loaded('apcu') && ini_get('apc.enabled')) {
      $opponentIsTyping = @apcu_fetch($typingCacheKey) !== false;
    }
    $response->opponentIsTyping = $opponentIsTyping;
  } else {
    $response->opponentIsTyping = false;
  }

  echo json_encode($response);
  exit;
}
