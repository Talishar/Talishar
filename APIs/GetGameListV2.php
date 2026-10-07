<?php

// Deploy this endpoint with GameCacheSentinel.php, GameListScan.php and the
// updated SHMOPLibraries.php. GetGameList.php remains unchanged for comparison.
// Existing games appear after their next successful WriteCache call; cache
// clears or evictions repopulate the same way. APCu discovery requires APCu
// and APCUIterator, otherwise this endpoint returns HTTP 503.

include_once "../Libraries/SHMOPLibraries.php";
include "../Libraries/HTTPLibraries.php";
include "../HostFiles/Redirector.php";
include "../AccountFiles/AccountSessionAPI.php";
require_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once "../AccountFiles/AccountDatabaseAPI.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/BlockedUserLibraries.php';
include_once '../Libraries/FriendLibraries.php';
include_once '../Libraries/FeaturedGameLibraries.php';
include_once '../Libraries/GameListScan.php';

$path = "../Games";

$useGameCacheSentinels = true;
if ($useGameCacheSentinels && (!GameCacheSentinelsAvailable() || !class_exists('APCUIterator'))) {
  SetHeaders();
  header('Content-Type: application/json; charset=utf-8');
  http_response_code(503);
  echo json_encode(['error' => 'APCu game discovery is unavailable']);
  exit;
}

session_start();
SetHeaders();
$conn = null;

if(!IsUserLoggedIn()) {
  if(isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}
$response = new stdClass();
$response->gamesInProgress = [];
$response->openGames = [];
$canSeeQueue = IsUserLoggedIn();
$response->canSeeQueue = $canSeeQueue;

$isShadowBanned = false;
if(isset($_SESSION["isBanned"])) $isShadowBanned = (intval($_SESSION["isBanned"]) == 1 ? true : false);
else if(IsUserLoggedIn()) $isShadowBanned = IsBannedPlayer(LoggedInUserName());
if(!$isShadowBanned) $isShadowBanned = IsIPBanned();

// If player is actually banned, return empty game list
if(IsUserLoggedIn() && IsBannedPlayer(LoggedInUserName())) {
  echo json_encode($response);
  exit;
}

// Get banned players list for filtering
$bannedPlayers = GetBannedPlayers();

// Get blocked users list for filtering
$blockedUserNames = [];
$friendUserNames = [];
$hiddenByFriendNames = [];
$friendUserSet = []; 
$blockedUserSet = []; 
$hiddenByFriendSet = [];
if(IsUserLoggedIn()) {
  $userId = LoggedInUser();
  $now = time();
  $cacheTTL = 300; // 5 minutes
  $refreshBlockedUsers = !isset($_SESSION['_blockedCache']) || ($now - ($_SESSION['_blockedCacheAt'] ?? 0)) > $cacheTTL;
  $refreshFriends = !isset($_SESSION['_friendNamesCache']) || ($now - ($_SESSION['_friendNamesCacheAt'] ?? 0)) > $cacheTTL;
  if ($refreshBlockedUsers || $refreshFriends) {
    $conn = GetDBConnection(DBL_GET_GAME_LIST);
  }

  // Blocked users — refresh at most every five minutes per session.
  if ($refreshBlockedUsers) {
    if ($conn) {
      $query = "SELECT u.usersUid FROM blocked_users b
                JOIN users u ON b.blockedUserId = u.usersId WHERE b.userId = ?
                UNION
                SELECT u.usersUid FROM blocked_users b
                JOIN users u ON b.userId = u.usersId WHERE b.blockedUserId = ?";
      try {
        $stmt = $conn->prepare($query);
        if ($stmt) {
          $stmt->bind_param("ii", $userId, $userId);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
            $blockedUserNames[] = $row['usersUid'];
          }
          $stmt->close();
        }
      } catch (\Exception $e) {
        error_log("GetGameList: blocked users query failed: " . $e->getMessage());
      }
    }
    $_SESSION['_blockedCache'] = $blockedUserNames;
    $_SESSION['_blockedCacheAt'] = $now;
  } else {
    $blockedUserNames = $_SESSION['_blockedCache'];
  }

  // Friends list — refresh at most every five minutes per session.
  if ($refreshFriends) {
    $friends = GetUserFriends($userId);
    $friendUserNames = array_column($friends, 'username');
    $hiddenByFriendNames = GetFriendsHidingGamesFromFriends($friends);
    $_SESSION['_friendNamesCache'] = $friendUserNames;
    $_SESSION['_friendHiddenGamesCache'] = $hiddenByFriendNames;
    $_SESSION['_friendNamesCacheAt'] = $now;
  } else {
    $friendUserNames = $_SESSION['_friendNamesCache'];
    $hiddenByFriendNames = $_SESSION['_friendHiddenGamesCache'] ?? [];
  }

  $blockedUserSet = array_flip($blockedUserNames);
  $friendUserSet = array_flip($friendUserNames);
  $hiddenByFriendSet = array_flip($hiddenByFriendNames);
}
if ($conn) {
  mysqli_close($conn);
  $conn = null;
}
// Release the session file lock before scanning games.
session_write_close();

if(IsUserLoggedIn()) {
  $lastGameName = SessionLastGameName();
  if($lastGameName != "") {
    $lastGameArr = ReadCacheArray($lastGameName);
    $gameStatus = $lastGameArr[13] ?? "";
    if($gameStatus != "" && $gameStatus != 99) {
      $playerID = SessionLastGamePlayerID();
      $otherP = $playerID == 1 ? 2 : 1;
      $oppStatus = strval($lastGameArr[$otherP + 2] ?? "");
      if($oppStatus != "-1") {
        $response->LastGameName = $lastGameName;
        $response->LastPlayerID = $playerID;
        $response->LastAuthKey = SessionLastAuthKey();
      }
    }
  }
}

$currentTime = round(microtime(true) * 1000);
$scan = GetGameListScan($path, $currentTime, $useGameCacheSentinels);
if ($scan !== null) {
  $featuredCandidates = [];
  foreach ($scan['inProgress'] as [$gameToken, $lastGamestateUpdate, $visibility, $p1Hero, $p2Hero, $gameFormat, $gameCreator, $p2Username, $p1ShownName, $p2ShownName, $p1AccountId, $p2AccountId, $spectatorCount]) {
    if ($visibility == "2" && !($canSeeQueue && (isset($friendUserSet[$gameCreator]) || isset($friendUserSet[$p2Username])))) continue;
    if (isset($bannedPlayers[strtolower($gameCreator)]) || isset($bannedPlayers[strtolower($p2Username)])) continue;
    if (isset($blockedUserSet[$gameCreator]) || isset($blockedUserSet[$p2Username])) continue;
    if (isset($hiddenByFriendSet[$gameCreator]) || isset($hiddenByFriendSet[$p2Username])) continue;

    $gameInProgress = new stdClass();
    $gameInProgress->p1Hero = $p1Hero;
    $gameInProgress->p2Hero = $p2Hero;
    $gameInProgress->secondsSinceLastUpdate = intval(($currentTime - $lastGamestateUpdate) / 1000);
    $gameInProgress->gameName = $gameToken;
    $gameInProgress->format = $gameFormat;
    $gameInProgress->gameCreator = $p1ShownName;
    $gameInProgress->p2Username = $p2ShownName;
    $gameInProgress->visibility = $visibility;
    $gameInProgress->spectatorCount = $spectatorCount;
    $response->gamesInProgress[] = $gameInProgress;
    if ($visibility == "1") {
      $featuredCandidates[] = [
        'gameName' => $gameToken,
        'spectators' => $spectatorCount,
        'secondsIdle' => $gameInProgress->secondsSinceLastUpdate,
        'p1id' => $p1AccountId,
        'p2id' => $p2AccountId,
        'p1Hero' => $p1Hero,
        'p2Hero' => $p2Hero,
      ];
    }
  }

  foreach ($scan['open'] as [$gameToken, $visibility, $p1uid, $hasHero, $p1Hero, $openFormat, $formatName, $description, $creatorName]) {
    if ($visibility == "2" && !($canSeeQueue && isset($friendUserSet[$p1uid]))) continue;
    if (isset($bannedPlayers[strtolower($p1uid)])) continue;
    if (isset($blockedUserSet[$p1uid])) continue;
    if (isset($hiddenByFriendSet[$p1uid])) continue;

    $openGame = new stdClass();
    if ($hasHero) $openGame->p1Hero = $p1Hero;
    $openGame->format = $openFormat;
    $openGame->formatName = $formatName;
    $openGame->description = $description;
    $openGame->gameName = $gameToken;
    $openGame->gameCreator = $creatorName;
    $openGame->visibility = $visibility;
    if ($isShadowBanned) {
      if ($openFormat == "shadowblitz" || $openFormat == "shadowcc") $response->openGames[] = $openGame;
    } else {
      if ($openFormat != "shadowblitz" && $openFormat != "shadowcc") $response->openGames[] = $openGame;
    }
  }
  $response->gameInProgressCount = $scan['inProgressCount'];

  $visibleGames = [];
  foreach($response->gamesInProgress as $game) $visibleGames[(string)$game->gameName] = true;
  $response->featuredGames = [];
  foreach(SelectFeaturedGames($featuredCandidates) as $featured) {
    if(!isset($visibleGames[$featured['gameName']])) continue;
    $featuredGame = new stdClass();
    $featuredGame->gameName = $featured['gameName'];
    $featuredGame->masteryLevel = $featured['masteryLevel'];
    $featuredGame->spectators = $featured['spectators'];
    $response->featuredGames[] = $featuredGame;
  }
  if(!empty($response->featuredGames)) {
    $response->featuredGame = $response->featuredGames[0]->gameName;
    $response->featuredMasteryLevel = $response->featuredGames[0]->masteryLevel;
    $response->featuredSpectators = $response->featuredGames[0]->spectators;
  }

  echo json_encode($response);
}

