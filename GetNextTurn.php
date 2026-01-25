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
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";
include_once "./Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "./Assets/MetafyDictionary.php";
include_once "./AccountFiles/AccountSessionAPI.php";
include_once "Libraries/CacheLibraries.php";
include_once "includes/dbh.inc.php";
include_once "includes/MetafyHelper.php";
include_once "BuildGameState.php";
include_once "BuildPlayerInputPopup.php";

// Set CORS headers
SetHeaders();

header('Content-Type: application/json; charset=utf-8');

// Validate game name
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo json_encode(["errorMessage" => "Invalid game name."]);
  exit;
}

// Validate player ID
$playerID = TryGet("playerID", 3);
if (!is_numeric($playerID)) {
  echo json_encode(["errorMessage" => "Invalid player ID."]);
  exit;
}

// Check spectator permission
if ($playerID == 3 && GetCachePiece($gameName, 9) != "1") {
  header('HTTP/1.0 403 Forbidden');
  exit;
}

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
$sessionData['isPvtVoidPatron'] = isset($_SESSION["isPvtVoidPatron"]);

// Capture all Patreon campaign session IDs before releasing session
$sessionData['patreonCampaigns'] = [];
foreach(PatreonCampaign::cases() as $campaign) {
  $sessionID = $campaign->SessionID();
  $sessionData['patreonCampaigns'][$sessionID] = isset($_SESSION[$sessionID]);
}

// Release the session lock NOW - before any file I/O or processing
if (session_status() === PHP_SESSION_ACTIVE) {
  session_write_close();
}

$isGamePlayer = $playerID == 1 || $playerID == 2;
$currentTime = round(microtime(true) * 1000);

// Track player/spectator connection status
if ($isGamePlayer) {
  $playerStatus = intval(GetCachePiece($gameName, $playerID + 3));
  if ($playerStatus == "-1") WriteLog("🔌Player $playerID has connected.");
  SetCachePiece($gameName, $playerID + 1, $currentTime);
  SetCachePiece($gameName, $playerID + 3, "0");
  if ($playerStatus > 0) {
    WriteLog("🔌Player $playerID has reconnected.");
    SetCachePiece($gameName, $playerID + 3, "0");
  }
} else if ($playerID == 3) {
  // Track spectators
  $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
  $sessionKey = md5($clientIp . '|' . $userAgent);
  $spectatorUsername = $sessionData['userName'];
  TrackSpectator($gameName, $sessionKey, $spectatorUsername);
}

// Check if game file exists
if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
  echo json_encode(["errorMessage" => "Game no longer exists on the server."]);
  exit;
}

// Check cache value for updates (optional optimization)
$cacheVal = intval(GetCachePiece($gameName, 1));
if ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  echo "0";
  exit;
}

// Build and return the game state
$response = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, true);

if (is_string($response)) {
  // Error occurred
  echo json_encode(["errorMessage" => $response]);
  exit;
}

echo json_encode($response);
exit;
