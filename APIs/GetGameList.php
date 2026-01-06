<?php

include "../Libraries/SHMOPLibraries.php";
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
$conn = GetDBConnection();

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
else if(IsUserLoggedIn()) $isShadowBanned = IsBanned(LoggedInUserName());

// Get blocked users list for filtering
$blockedUserNames = [];
$usersWhoBlockedMe = [];
$friendUserNames = [];
if(IsUserLoggedIn()) {
  $userId = LoggedInUser();
  $blockedUsers = GetBlockedUsers($userId);
  $blockedUserNames = array_map(function($user) { return $user['username']; }, $blockedUsers);
  
  // Also get users who have blocked the current player
  $query = "SELECT u.usersUid FROM blocked_users b JOIN users u ON b.userId = u.usersId WHERE b.blockedUserId = ?";
  if ($conn) {
    $stmt = $conn->prepare($query);
    if ($stmt) {
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        $usersWhoBlockedMe[] = $row['usersUid'];
      }
      $stmt->close();
    }
  }
  
  // Get friends list
  $friends = GetUserFriends($userId);
  $friendUserNames = array_map(function($friend) { return $friend['username']; }, $friends);
}

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
    if (file_exists($gs)) {
      $lastGamestateUpdate = intval(GetCachePiece($gameToken, 6));
      if ($currentTime - $lastGamestateUpdate < 30000) {
        $visibility = GetCachePiece($gameToken, 9);
        $gameInProgressCount += 1;
        
        // Get both player usernames from the GameFile.txt
        $gameFilePath = $folder . "GameFile.txt";
        $gameCreator = "";
        $p2Username = "";
        if (file_exists($gameFilePath)) {
          $lines = file($gameFilePath);
          if (count($lines) >= 11) {
            // p1uid is on line 10 (0-indexed line 9)
            $gameCreator = trim($lines[9]);
            // p2uid is on line 11 (0-indexed line 10)
            $p2Username = trim($lines[10]);
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
        
        // Don't show games from blocked users
        if(in_array($gameCreator, $blockedUserNames) || in_array($p2Username, $blockedUserNames)) {
          continue;
        }
        
        // Don't show games from users who have blocked me
        if(in_array($gameCreator, $usersWhoBlockedMe) || in_array($p2Username, $usersWhoBlockedMe)) {
          continue;
        }
        
        $gameInProgress = new stdClass();
        $gameInProgress->p1Hero = GetCachePiece($gameToken, 7);
        $gameInProgress->p2Hero = GetCachePiece($gameToken, 8);
        $gameInProgress->secondsSinceLastUpdate = intval(($currentTime - $lastGamestateUpdate) / 1000);
        $gameInProgress->gameName = $gameToken;
        $gameInProgress->format = GetCachePiece($gameToken, 13);
        $gameInProgress->gameCreator = $gameCreator;
        $gameInProgress->p2Username = $p2Username;
        
        if($gameInProgress->p2Hero != "DUMMY" && $gameInProgress->p2Hero != "") array_push($response->gamesInProgress, $gameInProgress);
      }
      else if ($currentTime - $lastGamestateUpdate > 300000) //~5 minutes?
      {
        if ($autoDeleteGames) {
          deleteDirectory($folder);
          DeleteCache($gameToken);
        }
        continue;
      }
      if($autoDeleteGames && $checkFileCreationTime) {
        $fileCreationTime = filectime($gs);
        if($fileCreationTime !== false && (time() - $fileCreationTime) > 18000) { //300 minutes
          deleteDirectory($folder);
          DeleteCache($gameToken);
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
        
        // Don't show open games from blocked users
        if(in_array($p1uid, $blockedUserNames)) {
          continue;
        }
        
        // Don't show open games from users who have blocked me
        if(in_array($p1uid, $usersWhoBlockedMe)) {
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

function deleteDirectory($dir)
{
  if (!file_exists($dir)) {
    return true;
  }

  if (!is_dir($dir)) {
    // Only attempt to delete if file exists
    if (file_exists($dir)) {
      return @unlink($dir);
    } else {
      return true;
    }
  }

  $items = @scandir($dir);
  if ($items === false) {
    // Directory cannot be read, treat as deleted or inaccessible
    return true;
  }
  foreach ($items as $item) {
    if ($item == '.' || $item == '..') {
      continue;
    }
    if (!deleteDirectory($dir . "/" . $item)) {
      // Optionally log error here
      continue;
    }
  }

  if (file_exists($dir)) {
    return @rmdir($dir);
  } else {
    return true; // directory already deleted
  }
}

