<?php

include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/CacheLibraries.php";

SetHeaders();

error_log("ChatTyping.php called with GET: " . json_encode($_GET) . " POST: " . json_encode($_POST));

// Handle both GET params and POST JSON body
$gameName = $_GET["gameName"] ?? $_POST["gameName"] ?? null;
$playerID = $_GET["playerID"] ?? $_POST["playerID"] ?? null;
$authKey = $_GET["authKey"] ?? $_POST["authKey"] ?? null;

// If not found in GET/POST, try JSON body
if (!$gameName || !$playerID || !$authKey) {
  $input = json_decode(file_get_contents('php://input'), true);
  error_log("ChatTyping.php trying JSON body: " . json_encode($input));
  if ($input) {
    $gameName = $gameName ?? $input['gameName'] ?? null;
    $playerID = $playerID ?? $input['playerID'] ?? null;
    $authKey = $authKey ?? $input['authKey'] ?? null;
  }
}

error_log("ChatTyping.php parsed: gameName=$gameName, playerID=$playerID, authKey=" . substr($authKey, 0, 8) . "...");

if (!IsGameNameValid($gameName)) {
  echo json_encode(["error" => "Invalid game name."]);
  exit;
}

if (!is_numeric($playerID)) {
  echo json_encode(["error" => "Invalid player ID."]);
  exit;
}

session_start();

// Validate auth key
$targetAuthKey = "";
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $targetAuthKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $targetAuthKey = $_SESSION["p2AuthKey"];
if ($targetAuthKey != "" && $authKey != $targetAuthKey) {
  echo json_encode(["error" => "Unauthorized."]);
  exit;
}

$response = new stdClass();

// Set typing indicator with 3 second expiration
$typingCacheKey = "chat_typing_" . md5($gameName) . "_player_" . $playerID;

try {
  // Store typing status in APCu cache with 3-second TTL
  if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    apcu_store($typingCacheKey, time(), 3);
  }
  
  // Also store in file-based cache as fallback
  $cacheDir = "../Games/" . $gameName;
  if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
    error_log("ChatTyping: Created game directory: $cacheDir");
  }
  
  $typingFile = $cacheDir . "/typing_" . $playerID . ".txt";
  $timestamp = time();
  $writeResult = file_put_contents($typingFile, $timestamp);
  
  error_log("ChatTyping: Wrote typing file $typingFile with timestamp $timestamp, result: $writeResult");
  
  $response->success = true;
  $response->message = "Typing status updated";
  $response->debug = [
    'gameName' => $gameName,
    'playerID' => $playerID,
    'timestamp' => $timestamp,
    'file' => $typingFile,
    'writeResult' => $writeResult
  ];
  
} catch (Exception $e) {
  $response->success = false;
  $response->error = $e->getMessage();
  error_log("ChatTyping error: " . $e->getMessage());
}

echo json_encode($response);
?>
