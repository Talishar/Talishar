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

$path = "../Games";

session_start();
SetHeaders();
$conn = GetDBConnection(DBL_GET_GAME_LIST);

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

// If player is actually banned, return empty game list
if(IsUserLoggedIn() && IsBannedPlayer(LoggedInUserName())) {
  closedir($handle);
  echo json_encode($response);
  exit;
}

// Get banned players list for filtering
$bannedPlayers = GetBannedPlayers();

// Get blocked users list for filtering
$blockedUserNames = [];
$friendUserNames = [];
if(IsUserLoggedIn()) {
  $userId = LoggedInUser();
  $now = time();
  $cacheTTL = 300; // 5 minutes

  // Blocked users — refresh at most every 60 seconds per session
  if (!isset($_SESSION['_blockedCache']) || ($now - ($_SESSION['_blockedCacheAt'] ?? 0)) > $cacheTTL) {
    if ($conn) {
      $query = "SELECT u.usersUid FROM blocked_users b
                JOIN users u ON b.blockedUserId = u.usersId WHERE b.userId = ?
                UNION
                SELECT u.usersUid FROM blocked_users b
                JOIN users u ON b.userId = u.usersId WHERE b.blockedUserId = ?";
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
    }
    $_SESSION['_blockedCache'] = $blockedUserNames;
    $_SESSION['_blockedCacheAt'] = $now;
  } else {
    $blockedUserNames = $_SESSION['_blockedCache'];
  }

  // Friends list — refresh at most every 60 seconds per session
  if (!isset($_SESSION['_friendNamesCache']) || ($now - ($_SESSION['_friendNamesCacheAt'] ?? 0)) > $cacheTTL) {
    $friends = GetUserFriends($userId);
    $friendUserNames = array_map(function($friend) { return $friend['username']; }, $friends);
    $_SESSION['_friendNamesCache'] = $friendUserNames;
    $_SESSION['_friendNamesCacheAt'] = $now;
  } else {
    $friendUserNames = $_SESSION['_friendNamesCache'];
  }
}
// Release the session file lock before the filesystem loop
session_write_close();

if(IsUserLoggedIn()) {
  $lastGameName = SessionLastGameName();
  if($lastGameName != "") {
    $gameStatus = GetCachePiece($lastGameName, 14);
    if($gameStatus != "" && $gameStatus != 99) {
      $playerID = SessionLastGamePlayerID();
      $otherP = $playerID == 1 ? 2 : 1;
      $oppStatus = strval(GetCachePiece($lastGameName, $otherP + 3));
      if($oppStatus != "-1") {
        $response->LastGameName = $lastGameName;
        $response->LastPlayerID = $playerID;
        $response->LastAuthKey = SessionLastAuthKey();
      }
    }
  }
}

$gameInProgressCount = 0;
if ($handle = opendir($path)) {
  $checkFileCreationTime = random_int(1, 1000) == 42;
  while (false !== ($folder = readdir($handle))) {
    if ('.' === $folder) continue;
    if ('..' === $folder) continue;
    $gameToken = $folder;
    $folder = $path . "/" . $folder . "/";
    $gs = $folder . "gamestate.txt";
    $currentTime = round(microtime(true) * 1000);
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
        if (file_exists($gameFilePath)) {
          // Read only the two username lines instead of loading the whole file
          $fh = fopen($gameFilePath, "r");
          if ($fh) {
            for ($i = 0; $i < 9; $i++) { if (fgets($fh) === false) break; }
            $gameCreator = trim((string)fgets($fh));  // line 10: p1uid
            $p2Username  = trim((string)fgets($fh));  // line 11: p2uid
            fclose($fh);
          }
        }
        
        // Determine if this game should be shown
        $showGame = false;
        if($visibility == "1") {
          // Public game
          $showGame = true;
        } else if($visibility == "2") {
          // Friends-only game - show if user is a friend of either player
          $showGame = IsUserLoggedIn() && (in_array($gameCreator, $friendUserNames) || in_array($p2Username, $friendUserNames));
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
        if(in_array($gameCreator, $blockedUserNames) || in_array($p2Username, $blockedUserNames)) {
          continue;
        }
        
        $gameInProgress = new stdClass();
        $gameInProgress->p1Hero = $cacheArr[6] ?? "";   // piece 7
        $gameInProgress->p2Hero = $cacheArr[7] ?? "";   // piece 8
        $gameInProgress->secondsSinceLastUpdate = intval(($currentTime - $lastGamestateUpdate) / 1000);
        $gameInProgress->gameName = $gameToken;
        $gameInProgress->format = $cacheArr[12] ?? "";  // piece 13
        $gameInProgress->gameCreator = $gameCreator;
        $gameInProgress->p2Username = $p2Username;
        $gameInProgress->visibility = $visibility;
        
        if($gameInProgress->p2Hero != "DUMMY" && $gameInProgress->p2Hero != "") array_push($response->gamesInProgress, $gameInProgress);
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
    if (file_exists($gf)) {
      $lastRefresh = intval(GetCachePiece($gameName, 2)); //Player 1 last connection time
      if ($lastRefresh != "" && $currentTime - $lastRefresh < 500) {
        include 'APIParseGamefile.php';
        $status = $gameStatus;
        UnlockGamefile();
      } else if ($lastRefresh == "" || $currentTime - $lastRefresh > 900000) //1 hour
      {
        deleteDirectory($folder);
        DeleteCache($gameToken);
      }
      if($status == 0 && intval(GetCachePiece($gameName, 11)) < 3) {
        $visibility = GetCachePiece($gameName, 9);
        
        // Determine if this game should be shown
        $showGame = false;
        if($visibility == "1") {
          // Public game
          $showGame = true;
        } else if($visibility == "2") {
          // Friends-only game - show if user is a friend of the creator
          $showGame = IsUserLoggedIn() && in_array($p1uid, $friendUserNames);
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
        if(in_array($p1uid, $blockedUserNames)) {
          continue;
        }
        
        $openGame = new stdClass();
        if($format != "compcc" && $format != "compblitz" && $format != "compllcc" && $format != "compsage") $openGame->p1Hero = GetCachePiece($gameName, 7);
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
        
        $description = ($gameDescription == "" ? "Game #" . $gameName : $gameDescription);
        $openGame->format = $format;
        $openGame->formatName = $formatName;
        $openGame->description = $description;
        $openGame->gameName = $gameToken;
        $openGame->gameCreator = $p1uid;
        $openGame->visibility = $visibility;
        if($isShadowBanned) {
          if($format == "shadowblitz" || $format == "shadowcc") array_push($response->openGames, $openGame);
        } else {
          if($format != "shadowblitz" && $format != "shadowcc") array_push($response->openGames, $openGame);
        }
      }
    }
  }
  $response->gameInProgressCount = $gameInProgressCount;
  closedir($handle);
  echo json_encode($response);
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    $dirContents = scandir($dir);
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

