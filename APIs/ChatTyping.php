<?php

include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";
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

// Optional: typing=0 means the player explicitly stopped typing
$isTyping = true;
if (isset($_GET['typing'])) {
  $isTyping = $_GET['typing'] !== '0' && $_GET['typing'] !== 'false';
}

$cacheKey = "typing_" . md5($gameName) . "_player_" . $playerID;

if (extension_loaded('apcu') && ini_get('apc.enabled')) {
  if (function_exists('apcu_store')) {
    if ($isTyping) {
      @apcu_store($cacheKey, true, 5);
    } else {
      @apcu_delete($cacheKey);
    }
  }
} else {
  // Fallback: file-based cache
  $gameDir = "../Games/" . $gameName;
  if (!is_dir($gameDir)) {
    @mkdir($gameDir, 0755, true);
  }

  $typingFile = $gameDir . "/typing_p" . $playerID . ".txt";
  if ($isTyping) {
    $expiryTime = time() + 5;
    file_put_contents($typingFile, $expiryTime);
  } else {
    @unlink($typingFile);
  }
}

$response->success = true;
echo json_encode($response);
