<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');
header('Cache-Control: no-store');

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../Libraries/GameInviteLibraries.php";

if (!IsUserLoggedIn()) ExitJsonResponse(["error" => "Not logged in"], 401);
$userId = (int)LoggedInUser();
$senderName = LoggedInDisplayName() ?: LoggedInUserName();
session_write_close();

if (!GameInvitesAvailable()) ExitJsonResponse(["error" => "Game invites are unavailable"], 503);

$request = ReadJsonBody();
if (!is_array($request)) $request = [];
$action = $request['action'] ?? 'list';

function ReadGameP1AuthKey($gameName)
{
  $handle = @fopen("../Games/" . $gameName . "/GameFile.txt", "r");
  if ($handle === false) return "";
  if (!flock($handle, LOCK_SH)) {
    fclose($handle);
    return "";
  }
  for ($line = 0; $line < 7; ++$line) fgets($handle);
  $p1AuthKey = trim((string)fgets($handle));
  flock($handle, LOCK_UN);
  fclose($handle);
  return $p1AuthKey;
}

switch ($action) {
  case 'list':
    ExitJsonResponse(["invites" => GetLiveGameInvites($userId)]);

  case 'decline':
    $fromUserId = $request['fromUserId'] ?? '';
    if (!is_numeric($fromUserId)) ExitJsonResponse(["error" => "Invalid user ID"], 400);
    $gameName = isset($request['gameName']) && is_numeric($request['gameName']) ? (string)$request['gameName'] : null;
    RemoveGameInvite($userId, (int)$fromUserId, $gameName);
    ExitJsonResponse(["success" => true]);

  case 'send':
    $friendUserId = $request['friendUserId'] ?? '';
    $gameName = $request['gameName'] ?? '';
    $authKey = (string)($request['authKey'] ?? '');
    if (!is_numeric($friendUserId) || (int)$friendUserId <= 0 || (int)$friendUserId === $userId) {
      ExitJsonResponse(["error" => "Invalid friend"], 400);
    }
    if (!IsGameNameValid($gameName)) ExitJsonResponse(["error" => "Invalid game"], 400);
    $friendUserId = (int)$friendUserId;

    $p1AuthKey = ReadGameP1AuthKey($gameName);
    if ($p1AuthKey === "" || $authKey === "" || !hash_equals($p1AuthKey, $authKey)) {
      ExitJsonResponse(["error" => "Only the game's host can send invites"], 403);
    }
    $cacheArr = GameInviteLobbyCache($gameName);
    if ($cacheArr === null) ExitJsonResponse(["error" => "This game is no longer waiting for an opponent"], 409);

    include_once "../includes/dbh.inc.php";
    include_once "../includes/functions.inc.php";
    include_once "../Libraries/FriendLibraries.php";
    include_once "../Libraries/BlockedUserLibraries.php";

    $conn = GetDBConnection(DBL_GAME_INVITE_API);
    if (!$conn) ExitJsonResponse(["error" => "Database connection failed"], 500);
    $friendIds = array_map('intval', array_column(GetUserFriends($userId, $conn), 'friendUserId'));
    $conn->close();
    if (!in_array($friendUserId, $friendIds, true)) ExitJsonResponse(["error" => "You can only invite friends"], 403);
    if (IsBlockedEitherWay($userId, $friendUserId)) ExitJsonResponse(["error" => "You can only invite friends"], 403);

    if (!AddGameInvite($friendUserId, $userId, $senderName, $gameName, GameInviteFormat($cacheArr))) {
      ExitJsonResponse(["error" => "Could not send the invite"], 500);
    }
    ExitJsonResponse(["success" => true]);

  default:
    ExitJsonResponse(["error" => "Invalid action"], 400);
}
