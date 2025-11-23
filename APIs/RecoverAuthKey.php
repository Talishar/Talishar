<?php

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include_once "./AccountFiles/AccountSessionAPI.php";

SetHeaders();
header('Content-Type: application/json; charset=utf-8');

$response = new stdClass();
$response->success = false;
$response->error = '';
$response->authKey = '';

// Check if user is logged in
if (!IsUserLoggedIn()) {
  $response->error = "User not logged in";
  http_response_code(401);
  echo json_encode($response);
  exit;
}

$gameName = TryGet("gameName", "");
$playerID = TryGet("playerID", 0);

// Validate inputs
if (empty($gameName) || !IsGameNameValid($gameName)) {
  $response->error = "Invalid game name";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

if (!is_numeric($playerID) || ($playerID !== "1" && $playerID !== "2")) {
  $response->error = "Invalid player ID";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

// Check if game exists
$gameFolder = "./Games/" . $gameName;
if (!is_dir($gameFolder)) {
  $response->error = "Game does not exist";
  http_response_code(404);
  echo json_encode($response);
  exit;
}

// Check if game file exists
$gameFile = $gameFolder . "/gamestate.txt";
if (!file_exists($gameFile)) {
  $response->error = "Game file not found";
  http_response_code(404);
  echo json_encode($response);
  exit;
}

// Check that this player is part of the game by verifying game is still active
// and the player was one of the original players
$gameStatus = intval(GetCachePiece($gameName, 14));
if ($gameStatus == 0) {
  // Game hasn't started yet, can't recover
  $response->error = "Game has not started";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

// IMPORTANT: Store auth keys in session when game is joined
// In Start.php and JoinGame.php, we already set $_SESSION["p1AuthKey"] and $_SESSION["p2AuthKey"]
// However, if the session was lost, we can't recover it from this endpoint alone.
// The auth keys need to be stored securely when the game is created/joined.

// Try to get the auth key from session (if still available)
$authKey = "";
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) {
  $authKey = $_SESSION["p1AuthKey"];
} else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) {
  $authKey = $_SESSION["p2AuthKey"];
}

// If session auth key exists, return it
if (!empty($authKey)) {
  $response->success = true;
  $response->authKey = $authKey;
  //WriteLog("🔑 Auth key recovered from session for game $gameName, player $playerID");
  echo json_encode($response);
  exit;
}

// If we can't find it in session, we can't recover it from this endpoint
// This is a limitation of the current architecture - auth keys are ephemeral
// To make this more robust, the backend needs to store encrypted auth keys
// in persistent storage (database or file) when games are created
$response->error = "Auth key not found in session. Please rejoin the game or contact support.";
http_response_code(410); // 410 Gone - resource is no longer available
echo json_encode($response);
exit;
