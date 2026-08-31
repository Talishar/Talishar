<?php

require_once __DIR__ . '/../Libraries/HTTPLibraries.php';
require_once __DIR__ . '/../includes/dbh.inc.php';
require_once __DIR__ . '/../includes/functions.inc.php';
require_once __DIR__ . '/../AccountFiles/AccountSessionAPI.php';
require_once __DIR__ . '/../Libraries/GameAuthLibraries.php';

SetHeaders();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') exit;

$response = new stdClass();
$response->success = false;
$response->error = '';
$response->authKey = '';

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) $body = [];
$input = array_merge($_GET, $_POST, $body);

$gameName = trim((string)($input['gameName'] ?? ''));
$requestedPlayerID = 0;
if (array_key_exists('playerID', $input) && $input['playerID'] !== '') {
  $validatedPlayerID = filter_var($input['playerID'], FILTER_VALIDATE_INT);
  if ($validatedPlayerID !== 1 && $validatedPlayerID !== 2) {
    $response->error = "Invalid player ID";
    http_response_code(400);
    echo json_encode($response);
    exit;
  }
  $requestedPlayerID = $validatedPlayerID;
}

if ($gameName === '' || !ctype_digit($gameName) || (int)$gameName <= 0) {
  $response->error = "Invalid game name";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

if (!IsUserLoggedIn()) {
  $response->error = "User not logged in";
  http_response_code(401);
  echo json_encode($response);
  exit;
}

$accountUserId = LoggedInUser();
$accountGameName = $_SESSION['lastGameName'] ?? '';
$accountPlayerID = $_SESSION['lastPlayerId'] ?? 0;
$accountAuthKey = $_SESSION['lastAuthKey'] ?? '';
$cookieAuthKey = $_COOKIE['lastAuthKey'] ?? '';
session_write_close();

$seatAuth = ReadGameFileSeatAuth($gameName, __DIR__ . '/../Games');
if ($seatAuth === null) {
  $response->error = "Game does not exist";
  http_response_code(404);
  echo json_encode($response);
  exit;
}

$resolvedAuth = ResolvePresentedGameAuth(
  $requestedPlayerID,
  $cookieAuthKey,
  $seatAuth[0],
  $seatAuth[1]
);

if ($resolvedAuth === null) {
  $resolvedAuth = ResolveStoredAccountGameAuth(
    $gameName,
    $requestedPlayerID,
    $accountGameName,
    $accountPlayerID,
    $accountAuthKey,
    $seatAuth[0],
    $seatAuth[1]
  );
}

// A session on another browser may predate this game. Only then perform the
// single account-table lookup and validate the stored fallback again.
if ($resolvedAuth === null) {
  $lastGame = GetLastGameInfo($accountUserId);
  if (is_array($lastGame)) {
    $resolvedAuth = ResolveStoredAccountGameAuth(
      $gameName,
      $requestedPlayerID,
      $lastGame['lastGameName'] ?? '',
      $lastGame['lastPlayerId'] ?? 0,
      $lastGame['lastAuthKey'] ?? '',
      $seatAuth[0],
      $seatAuth[1]
    );
  }
}

if ($resolvedAuth === null) {
  $response->error = "No matching game authentication found on your account";
  http_response_code(403);
  echo json_encode($response);
  exit;
}

[$playerID, $authKey] = $resolvedAuth;

$response->success = true;
$response->authKey = $authKey;
$response->playerID = $playerID;
$response->gameName = (int)$gameName;
echo json_encode($response);
exit;
