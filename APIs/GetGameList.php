<?php

include_once "../Libraries/SHMOPLibraries.php";
include "../Libraries/HTTPLibraries.php";
include "../HostFiles/Redirector.php";
include "../CardDictionary.php";
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

$path = "../Games";

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
// Release the session file lock before the filesystem loop
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

$gameInProgressCount = 0;
$featuredCandidates = [];
if ($handle = opendir($path)) {
  $checkFileCreationTime = random_int(1, 1000) == 42;
  $currentTime = round(microtime(true) * 1000);
  while (false !== ($folder = readdir($handle))) {
    if ('.' === $folder) continue;
    if ('..' === $folder) continue;
    $gameToken = $folder;
    $folder = $path . "/" . $folder . "/";
    $gs = $folder . "gamestate.txt";
    if($autoDeleteGames && $checkFileCreationTime) {
      $dirPath = realpath(rtrim($folder, "/"));
      if ($dirPath && is_dir($dirPath)) {
        $lastModified = filemtime($dirPath);
        $ageInSeconds = time() - $lastModified;
        if($ageInSeconds > 18000) { 
          if (deleteDirectory($dirPath)) {
            DeleteCache($gameToken);
            continue;
          } else {
            error_log("Failed to delete directory: " . $dirPath);
          }
      }
      }
    }
    if (file_exists($gs)) {
      // Single shared-memory read; all pieces available as 0-indexed array (piece N = index N-1)
      $cacheArr = ReadCacheArray($gameToken);
      $lastGamestateUpdate = ($cacheArr !== null) ? intval($cacheArr[5] ?? 0) : 0;
      if ($currentTime - $lastGamestateUpdate < 30000) {
        $visibility = $cacheArr[8] ?? "";  // piece 9
        $gameInProgressCount += 1;
        
        // Get both player usernames from the GameFile.txt
        $gameFilePath = $folder . "GameFile.txt";
        $gameCreator = "";
        $p2Username = "";
        $p1AccountId = 0;
        $p2AccountId = 0;
        $p1ShownName = "";
        $p2ShownName = "";
        if (file_exists($gameFilePath)) {
          // Read only the needed lines instead of parsing the whole file
          $fh = fopen($gameFilePath, "r");
          if ($fh) {
            for ($i = 0; $i < 9; $i++) { if (fgets($fh) === false) break; }
            $gameCreator = trim((string)fgets($fh));  // line 10: p1uid
            $p2Username  = trim((string)fgets($fh));  // line 11: p2uid
            $p1AccountId = intval(trim((string)fgets($fh)));  // line 12: p1id
            $p2AccountId = intval(trim((string)fgets($fh)));  // line 13: p2id
            // Skip to the trailing display-name lines (43-44); missing on older game files
            for ($i = 0; $i < 29; $i++) { if (fgets($fh) === false) break; }
            $p1ShownName = trim((string)fgets($fh));  // line 43: p1DisplayName
            $p2ShownName = trim((string)fgets($fh));  // line 44: p2DisplayName
            fclose($fh);
          }
        }
        if ($p1ShownName === "") $p1ShownName = $gameCreator;
        if ($p2ShownName === "") $p2ShownName = $p2Username;
        
        // Determine if this game should be shown
        $showGame = false;
        if($visibility == "1") {
          // Public game
          $showGame = true;
        } else if($visibility == "2") {
          // Friends-only game - show if user is a friend of either player
          $showGame = IsUserLoggedIn() && (isset($friendUserSet[$gameCreator]) || isset($friendUserSet[$p2Username]));
        }

        // Don't show if not visible
        if(!$showGame) {
          continue;
        }

        // Don't show games from banned users
        if(isset($bannedPlayers[strtolower($gameCreator)]) || isset($bannedPlayers[strtolower($p2Username)])) {
          continue;
        }

        // Don't show games from blocked users
        if(isset($blockedUserSet[$gameCreator]) || isset($blockedUserSet[$p2Username])) {
          continue;
        }

        // Don't show games belonging to a friend who hides their games from friends
        if(isset($hiddenByFriendSet[$gameCreator]) || isset($hiddenByFriendSet[$p2Username])) {
          continue;
        }

        $gameInProgress = new stdClass();
        $gameInProgress->p1Hero = $cacheArr[6] ?? "";
        $gameInProgress->p2Hero = $cacheArr[7] ?? "";
        $gameInProgress->secondsSinceLastUpdate = intval(($currentTime - $lastGamestateUpdate) / 1000);
        $gameInProgress->gameName = $gameToken;
        $gameInProgress->format = $cacheArr[12] ?? "";
        // Display names for the UI; the friend/ban/block checks above key off the handles
        $gameInProgress->gameCreator = $p1ShownName;
        $gameInProgress->p2Username = $p2ShownName;
        $gameInProgress->visibility = $visibility;
        $gameInProgress->spectatorCount = GetActiveSpectators($gameToken)['count'];

        if($gameInProgress->p1Hero != "" && $gameInProgress->p2Hero != "DUMMY" && $gameInProgress->p2Hero != "") {
          $response->gamesInProgress[] = $gameInProgress;
          // Only public games can be pinned; a friends-only match is invisible
          // to most of the people the featured slot is meant to reach.
          if($visibility == "1") {
            $featuredCandidates[] = [
              'gameName' => $gameToken,
              'spectators' => $gameInProgress->spectatorCount,
              'secondsIdle' => $gameInProgress->secondsSinceLastUpdate,
              'p1id' => $p1AccountId,
              'p2id' => $p2AccountId,
              'p1Hero' => $gameInProgress->p1Hero,
              'p2Hero' => $gameInProgress->p2Hero,
            ];
          }
        }
      }
      else if ($currentTime - $lastGamestateUpdate > 300000) //~5 minutes?
      {
        if ($autoDeleteGames) {
          deleteDirectory($folder);
          DeleteCache($gameToken);
          continue;
        }
      }
      continue;
    }

    $gf = $folder . "GameFile.txt";
    $gameName = $gameToken;
    $lineCount = 0;
    $status = -1;
    $format = "";
    $gameDescription = "";
    $p1uid = "";
    $p2uid = "";
    $p1DisplayName = "";
    $p2DisplayName = "";
    if (file_exists($gf)) {
      $openCacheArr = ReadCacheArray($gameName);
      $lastRefresh = ($openCacheArr !== null) ? intval($openCacheArr[1] ?? "") : 0; //Player 1 last connection time
      if ($lastRefresh != "" && $currentTime - $lastRefresh < 500) {
        include 'APIParseGamefile.php';
        $status = $gameStatus;
        UnlockGamefile();
      } else if ($lastRefresh == "" || $currentTime - $lastRefresh > 900000) // 15 minutes
      {
        deleteDirectory($folder);
        DeleteCache($gameToken);
      }
      if($status == 0 && intval($openCacheArr[10] ?? "") < 3) {
        $visibility = $openCacheArr[8] ?? "";

        // Determine if this game should be shown
        $showGame = false;
        if($visibility == "1") {
          // Public game
          $showGame = true;
        } else if($visibility == "2") {
          // Friends-only game - show if user is a friend of the creator
          $showGame = IsUserLoggedIn() && isset($friendUserSet[$p1uid]);
        }

        // Don't show if not visible
        if(!$showGame) {
          continue;
        }

        // Don't show open games from banned users
        if(isset($bannedPlayers[strtolower($p1uid)])) {
          continue;
        }

        // Don't show open games from blocked users
        if(isset($blockedUserSet[$p1uid])) {
          continue;
        }

        // Don't show open games from a friend who hides their games from friends
        if(isset($hiddenByFriendSet[$p1uid])) {
          continue;
        }

        $openGame = new stdClass();
        if($format != "compcc" && $format != "compblitz" && $format != "compllcc" && $format != "compsage") $openGame->p1Hero = $openCacheArr[6] ?? "";
        $formatName = "";
        if($format == "commoner") $formatName = "Commoner";
        else if($format == "futurecc") $formatName = "Future CC";
        // else if($format == "openformatblitz") $formatName = "Open Blitz";
        else if($format == "futuresage") $formatName = "Future Silver Age";
        // else if($format == "openformatsage") $formatName = "Open Silver Age";
        else if($format == "clash") $formatName = "Clash";
        else if($format == "llcc") $formatName = "Living Legend CC";
        else if($format == "llblitz") $formatName = "Living Legend Blitz";
        else if($format == "futurell") $formatName = "Future Living Legend";
        // else if($format == "openformatllblitz") $formatName = "Open Living Legend Blitz";
        else if($format == "precon") $formatName = "Preconstructed Deck";
        else if($format == "sage") $formatName = "Silver Age";
        else if($format == "open") $formatName = "Open";
        else if($format == "gage") $formatName = "Golden Age";
        
        $description = ($gameDescription == "" ? "Game #" . $gameName : $gameDescription);
        $openGame->format = $format;
        $openGame->formatName = $formatName;
        $openGame->description = $description;
        $openGame->gameName = $gameToken;
        $openGame->gameCreator = $p1DisplayName !== "" ? $p1DisplayName : $p1uid;
        $openGame->visibility = $visibility;
        if($isShadowBanned) {
          if($format == "shadowblitz" || $format == "shadowcc") $response->openGames[] = $openGame;
        } else {
          if($format != "shadowblitz" && $format != "shadowcc") $response->openGames[] = $openGame;
        }
      }
    }
  }
  $response->gameInProgressCount = $gameInProgressCount;

  // The pick is shared server-wide, so confirm it survived this viewer's own
  // ban, block and friend filtering before pinning it.
  $featured = SelectFeaturedGame($featuredCandidates);
  if($featured !== null) {
    foreach($response->gamesInProgress as $game) {
      if((string)$game->gameName === $featured['gameName']) {
        $response->featuredGame = $featured['gameName'];
        $response->featuredMasteryLevel = $featured['masteryLevel'];
        $response->featuredSpectators = $featured['spectators'];
        break;
      }
    }
  }

  closedir($handle);
  echo json_encode($response);
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return @unlink($dir) || !file_exists($dir);
    }

    $dirContents = @scandir($dir);
    if ($dirContents === false && !is_dir($dir)) return true;
    if ($dirContents === false) return false;
    foreach ($dirContents as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            if (file_exists($path)) {
                @unlink($path); 
            }
        }
    }
    if (!is_dir($dir)) return false;
    return @rmdir($dir) || !is_dir($dir); // Gracefully handle race condition where directory was already deleted
}

