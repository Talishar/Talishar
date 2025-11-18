<?php

include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/CacheLibraries.php";

SetHeaders();

header('Content-Type: application/json; charset=utf-8');
$response = new stdClass();

$gameName = $_GET["gameName"] ?? $_POST["gameName"] ?? null;
if (!IsGameNameValid($gameName)) {
  $response->errorMessage = "Invalid game name.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$playerID = $_GET["playerID"] ?? $_POST["playerID"] ?? null;
if (!is_numeric($playerID) || ($playerID != 1 && $playerID != 2)) {
  $response->errorMessage = "Invalid player ID.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

// Check if opponent is typing
$opponentID = ($playerID == 1) ? 2 : 1;
$typingCacheKey = "typing_" . md5($gameName) . "_player_" . $opponentID;

$isOpponentTyping = false;
if (extension_loaded('apcu') && ini_get('apc.enabled')) {
  if (function_exists('apcu_fetch')) {
    $isOpponentTyping = @apcu_fetch($typingCacheKey) !== false;
  }
} else {
  // Fallback: check file-based cache
  $typingFile = "../Games/" . $gameName . "/typing_p" . $opponentID . ".txt";
  if (file_exists($typingFile)) {
    $expiryTime = intval(file_get_contents($typingFile));
    $isOpponentTyping = $expiryTime > time();
  }
}

$response->opponentIsTyping = $isOpponentTyping;
echo json_encode($response);
