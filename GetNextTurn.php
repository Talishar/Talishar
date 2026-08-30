<?php

/**
 * GetNextTurn.php
 *
 * HTTP endpoint for polling game state updates.
 * This is a thin wrapper around BuildGameStateResponse().
 *
 * Note: The primary game state delivery mechanism is now SSE (GetUpdateSSE.php).
 * This endpoint remains for backwards compatibility and fallback scenarios.
 */

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include "WriteLog.php";
include_once "./Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "./Assets/MetafyDictionary.php";
include_once "./AccountFiles/AccountSessionAPI.php";
include_once "Libraries/CacheLibraries.php";
include_once "includes/dbh.inc.php";
include_once "includes/functions.inc.php";
include_once "includes/MetafyHelper.php";
include_once "Libraries/FriendLibraries.php";
include_once 'GameLogic.php';
include_once "GameTerms.php";
include_once "Libraries/UILibraries.php";
include_once "Libraries/StatFunctions.php";
include_once "Libraries/PlayerSettings.php";
include_once "Libraries/GameAuthLibraries.php";
include_once "BuildGameState.php";
include_once "BuildPlayerInputPopup.php";

// Set CORS headers
SetHeaders();

header('Content-Type: application/json; charset=utf-8');

// Validate game name
$gameName = $_GET["gameName"] ?? "";
if (!IsGameNameValid($gameName)) {
  echo json_encode(["errorMessage" => "Invalid game name."]);
  exit;
}

// Validate player ID
$playerID = TryGet("playerID", 3);
$playerID = filter_var($playerID, FILTER_VALIDATE_INT);
if (!in_array($playerID, [1, 2, 3], true)) {
  echo json_encode(["errorMessage" => "Invalid player ID."]);
  exit;
}

$cacheArr = null;

// Get auth key
$authKey = TryGet("authKey", "");
$lastUpdate = intval(TryGet("lastUpdate", 0));

if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

// CRITICAL: Capture all needed session data upfront and release the session lock immediately.
// PHP sessions use exclusive file locks - if we hold the lock while processing game state,
// all other requests from this user will be blocked, causing session deadlock.
$sessionData = [];
$sessionData['userLoggedIn'] = IsUserLoggedIn();
$sessionData['userName'] = $sessionData['userLoggedIn'] ? LoggedInUserName() : null;
// Display name for spectator lists etc.; userName stays the handle for identity checks
$sessionData['displayName'] = $sessionData['userLoggedIn'] ? LoggedInDisplayName() : null;
$sessionData['isPvtVoidPatron'] = isset($_SESSION["isPvtVoidPatron"]);

// Capture all Patreon campaign session IDs before releasing session
$sessionData['patreonCampaigns'] = [];
foreach(PatreonCampaign::cases() as $campaign) {
  $sessionID = $campaign->SessionID();
  $sessionData['patreonCampaigns'][$sessionID] = isset($_SESSION[$sessionID]);
}

// Resolve spectator authorization server-side. The polling fallback must not
// depend on the frontend having loaded and forwarded its friend list first.
$sessionData['friendList'] = [];
$viewerUserId = $playerID == 3 && $sessionData['userLoggedIn'] ? LoggedInUser() : null;

// Release the session lock NOW - before any file I/O or processing
if (session_status() === PHP_SESSION_ACTIVE) {
  session_write_close();
}

if ($playerID == 3 && (!$sessionData['userLoggedIn'] || empty($sessionData['userName']))) {
  http_response_code(401);
  echo json_encode(["errorMessage" => "Authentication required to spectate."]);
  exit;
}
if (is_numeric($viewerUserId)) {
  $sessionData['friendList'] = GetUserFriendUsernames((int)$viewerUserId);
}
$sessionData['viewerColorblindMode'] = LoadViewerColorblindMode($viewerUserId);
$sessionData['friendSet'] = !empty($sessionData['friendList']) ? array_flip($sessionData['friendList']) : [];

$isGamePlayer = $playerID == 1 || $playerID == 2;
$currentTime = round(microtime(true) * 1000);
$cacheArr ??= ReadCacheArray($gameName) ?? [];

$gameIsReplay = ($cacheArr[9] ?? "") === "1";
if ($isGamePlayer && !$gameIsReplay) {
  list($gameP1Key, $gameP2Key, $gameP1Uid, $gameP2Uid) = ReadGameFileSeatAuth($gameName, "./");
  $authKeyCandidates = [$authKey, $_COOKIE["lastAuthKey"] ?? ""];
  $resolvedAuthKey = ResolveGameAuthKey($playerID, $authKeyCandidates, $gameP1Key, $gameP2Key, $gameP1Uid, $gameP2Uid, $sessionData['userName']);
  if ($resolvedAuthKey === null) {
    if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
      echo json_encode(["errorMessage" => "Game no longer exists on the server."]);
      exit;
    }
    echo json_encode(["errorMessage" => "Invalid Authkey"]);
    exit;
  }
  $authKey = $resolvedAuthKey;
}

// Track player connection status
if ($isGamePlayer) {
  $playerStatus = intval($cacheArr[$playerID + 2] ?? "");
  if ($playerStatus === -1) WriteLog("🔌Player $playerID has connected.");
  SetCachePieces($gameName, [$playerID + 1 => $currentTime, $playerID + 3 => "0"]);
  if ($playerStatus > 0) {
    WriteLog("🔌Player $playerID has reconnected.");
  }
}

if ($playerID == 3) {
  UpdateSpectatorPresence($gameName, $sessionData['displayName']);
}

// Check cache value for updates (optional optimization)
$cacheVal = intval($cacheArr[0] ?? "");
if ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
    echo json_encode(["errorMessage" => "Game no longer exists on the server."]);
    exit;
  }
  echo "0";
  exit;
}

// Build and return the game state
$response = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, true, false, $cacheArr);

if (is_string($response)) {
  // Error occurred
  echo json_encode(["errorMessage" => $response]);
  exit;
}

echo json_encode($response);
exit;
