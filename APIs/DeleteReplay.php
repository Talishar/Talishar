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
  $response->error = "You must be logged in to delete replays.";
  ExitJsonResponse($response, 401);
}

$request = ReadJsonBody() ?: [];
$rawReplayNumber = $request["replayNumber"] ?? null;
$replayNumber = ParsePositiveReplayNumber($rawReplayNumber);
if ($replayNumber === null) {
  $response->error = "Invalid or missing replayNumber.";
  ExitJsonResponse($response, 400);
}

$replayPath = UserReplayPath($userId, $replayNumber, false);
if (!is_dir($replayPath) || is_link($replayPath)) {
  $response->error = "Replay not found.";
  ExitJsonResponse($response, 404);
}

function DeleteReplayDirectory(string $directory): bool
{
  $entries = scandir($directory);
  if ($entries === false) return false;

  foreach ($entries as $entry) {
    if ($entry === "." || $entry === "..") continue;
    $path = $directory . DIRECTORY_SEPARATOR . $entry;
    if (is_link($path) || is_file($path)) {
      if (!unlink($path)) return false;
    } elseif (is_dir($path)) {
      if (!DeleteReplayDirectory($path)) return false;
    } else {
      return false;
    }
  }

  return rmdir($directory);
}

if (!DeleteReplayDirectory($replayPath)) {
  $response->error = "Failed to delete replay.";
  ExitJsonResponse($response, 500);
}

$response->success = true;
$response->replayNumber = $replayNumber;
WriteJsonResponse($response);
