<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";

SetHeaders();

$response = new stdClass();
$userId = $_SESSION["useruid"] ?? "";
session_write_close();

if ($userId === "" || !preg_match('/^[A-Za-z0-9_-]+$/', $userId)) {
  $response->error = "You must be logged in to delete replays.";
  http_response_code(401);
  echo json_encode($response);
  exit;
}

$request = json_decode(file_get_contents('php://input'), true) ?: [];
$rawReplayNumber = $request["replayNumber"] ?? null;
if (
  !(is_int($rawReplayNumber) || (is_string($rawReplayNumber) && ctype_digit($rawReplayNumber))) ||
  (int)$rawReplayNumber < 1
) {
  $response->error = "Invalid or missing replayNumber.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$replayNumber = (int)$rawReplayNumber;
$replayPath = "../Replays/$userId/$replayNumber";
if (!is_dir($replayPath) || is_link($replayPath)) {
  $response->error = "Replay not found.";
  http_response_code(404);
  echo json_encode($response);
  exit;
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
  http_response_code(500);
  echo json_encode($response);
  exit;
}

$response->success = true;
$response->replayNumber = $replayNumber;
echo json_encode($response);
