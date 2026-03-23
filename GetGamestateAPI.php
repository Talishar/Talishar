<?php

/**
 * GetGamestateAPI.php
 *
 * Thin internal API endpoint: accepts game/player/auth parameters, builds the full
 * game state JSON, and returns it. Intended to be called via loopback curl from the
 * long-lived GetUpdateSSE.php process so that all heavy memory (ParseGamestate globals,
 * CardDictionary, etc.) is fully reclaimed when this short-lived process exits.
 *
 * It deliberately omits connection-tracking, spectator-presence updates, and the
 * "return 0 if no update" optimisation — those concerns belong to the caller.
 */

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include "Libraries/CacheLibraries.php";
include "WriteLog.php";
include_once "./Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "./Assets/MetafyDictionary.php";
include_once "./AccountFiles/AccountSessionAPI.php";
include_once "includes/dbh.inc.php";
include_once "includes/MetafyHelper.php";

include_once 'GameLogic.php';
include_once "GameTerms.php";
include_once "Libraries/UILibraries.php";
include_once "Libraries/StatFunctions.php";
include_once "Libraries/PlayerSettings.php";

include_once "BuildGameState.php";
include_once "BuildPlayerInputPopup.php";

header('Content-Type: application/json; charset=utf-8');

// Validate inputs
$gameName = $_GET["gameName"] ?? "";
if (!IsGameNameValid($gameName)) {
  echo json_encode(["errorMessage" => "Invalid game name."]);
  exit;
}

$playerID = TryGet("playerID", 3);
if (!is_numeric($playerID)) {
  echo json_encode(["errorMessage" => "Invalid player ID."]);
  exit;
}

// Spectator permission check
if ($playerID == 3 && GetCachePiece($gameName, 9) != "1") {
  http_response_code(403);
  exit;
}

$authKey = TryGet("authKey", "");
if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

// Capture session data then release the lock immediately.
$sessionData = [];
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
$sessionData['userLoggedIn'] = IsUserLoggedIn();
$sessionData['userName'] = $sessionData['userLoggedIn'] ? LoggedInUserName() : null;
$sessionData['isPvtVoidPatron'] = isset($_SESSION["isPvtVoidPatron"]);

$sessionData['patreonCampaigns'] = [];
foreach (PatreonCampaign::cases() as $campaign) {
  $sessionID = $campaign->SessionID();
  $sessionData['patreonCampaigns'][$sessionID] = isset($_SESSION[$sessionID]);
}

$sessionData['friendList'] = [];
$friendsListParam = TryGet("friendsList", "");
if (!empty($friendsListParam)) {
  $sessionData['friendList'] = json_decode($friendsListParam, true) ?? [];
}

if (session_status() === PHP_SESSION_ACTIVE) {
  session_write_close();
}

// Build and return game state
$response = BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData, true);

if (is_string($response)) {
  echo json_encode(["errorMessage" => $response]);
  exit;
}

echo json_encode($response);
