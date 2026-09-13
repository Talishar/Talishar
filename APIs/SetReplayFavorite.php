<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/ReplayLibraries.php";

SetHeaders();

$response = new stdClass();
$userId = ReplaySessionUserId();
session_write_close();

if (!IsValidReplayUserId($userId)) {
  $response->error = "You must be logged in to update replay favorites.";
  ExitJsonResponse($response, 401);
}

$_POST = ReadJsonBody() ?: [];
$replayNumber = $_POST["replayNumber"] ?? null;
$favorite = $_POST["favorite"] ?? null;
if (!is_numeric($replayNumber) || !is_bool($favorite)) {
  $response->error = "Invalid replay favorite request.";
  ExitJsonResponse($response, 400);
}

$replayPath = UserReplayPath($userId, (int)$replayNumber);
if (!is_dir($replayPath) || !file_exists($replayPath . "origGamestate.txt")) {
  $response->error = "Replay not found.";
  ExitJsonResponse($response, 404);
}

$metadataPath = $replayPath . "replayMetadata.json";
$metadata = file_exists($metadataPath) ? json_decode(file_get_contents($metadataPath), true) : [];
if (!is_array($metadata)) $metadata = [];
$metadata["favorite"] = $favorite;
if (file_put_contents($metadataPath, json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
  $response->error = "Failed to update replay favorite.";
  ExitJsonResponse($response, 500);
}

$response->success = true;
$response->favorite = $favorite;
WriteJsonResponse($response);
