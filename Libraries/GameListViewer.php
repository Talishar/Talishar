<?php

// Who is looking at the game list, and which lobbies they are allowed to see.
// Shared by APIs/GetGameListV2.php and APIs/GetMatchmakingStatus.php so the
// open game list and the matchmaking queue always agree.
// Needs an open session (session_start) and the account/friend/block libraries.

function GameListViewerIsShadowBanned()
{
  $isShadowBanned = false;
  if (isset($_SESSION["isBanned"])) $isShadowBanned = (intval($_SESSION["isBanned"]) == 1 ? true : false);
  else if (IsUserLoggedIn()) $isShadowBanned = IsBannedPlayer(LoggedInUserName());
  if (!$isShadowBanned) $isShadowBanned = IsIPBanned();
  return $isShadowBanned;
}

// Blocked users and friends, cached in the session for five minutes.
// Call before session_write_close(): it refreshes the session cache.
function LoadGameListViewer($isShadowBanned)
{
  $viewer = [
    'canSeeQueue' => IsUserLoggedIn(),
    'isShadowBanned' => $isShadowBanned,
    'bannedPlayers' => GetBannedPlayers(),
    'blocked' => [],
    'friends' => [],
    'hiddenByFriend' => [],
  ];
  if (!IsUserLoggedIn()) return $viewer;

  $conn = null;
  $blockedUserNames = [];
  $friendUserNames = [];
  $hiddenByFriendNames = [];
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

  if ($conn) mysqli_close($conn);

  $viewer['blocked'] = array_flip($blockedUserNames);
  $viewer['friends'] = array_flip($friendUserNames);
  $viewer['hiddenByFriend'] = array_flip($hiddenByFriendNames);
  return $viewer;
}

function OpenGameVisibleToViewer($viewer, $p1uid, $visibility, $openFormat)
{
  if ($visibility == "2" && !($viewer['canSeeQueue'] && isset($viewer['friends'][$p1uid]))) return false;
  if (isset($viewer['bannedPlayers'][strtolower($p1uid)])) return false;
  if (isset($viewer['blocked'][$p1uid])) return false;
  if (isset($viewer['hiddenByFriend'][$p1uid])) return false;
  $isShadowLobby = $openFormat == "shadowblitz" || $openFormat == "shadowcc";
  return $viewer['isShadowBanned'] ? $isShadowLobby : !$isShadowLobby;
}
