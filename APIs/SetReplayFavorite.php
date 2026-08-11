<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";

SetHeaders();

$response = new stdClass();
$userId = $_SESSION["useruid"] ?? "";
session_write_close();

if ($userId === "" || !preg_match('/^[A-Za-z0-9_-]+$/', $userId)) {
  $response->error = "You must be logged in to update replay favorites.";
  http_response_code(401);
  echo json_encode($response);
  exit;
}

$_POST = json_decode(file_get_contents('php://input'), true) ?: [];
$replayNumber = $_POST["replayNumber"] ?? null;
$favorite = $_POST["favorite"] ?? null;
if (!is_numeric($replayNumber) || !is_bool($favorite)) {
  $response->error = "Invalid replay favorite request.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$replayPath = "../Replays/$userId/" . (int)$replayNumber . "/";
if (!is_dir($replayPath) || !file_exists($replayPath . "origGamestate.txt")) {
  $response->error = "Replay not found.";
  http_response_code(404);
  echo json_encode($response);
  exit;
}

$metadataPath = $replayPath . "replayMetadata.json";
$metadata = file_exists($metadataPath) ? json_decode(file_get_contents($metadataPath), true) : [];
if (!is_array($metadata)) $metadata = [];
$metadata["favorite"] = $favorite;
if (file_put_contents($metadataPath, json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
  $response->error = "Failed to update replay favorite.";
  http_response_code(500);
  echo json_encode($response);
  exit;
}

$response->success = true;
$response->favorite = $favorite;
echo json_encode($response);
