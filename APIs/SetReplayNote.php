<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/ReplayLibraries.php";
include_once "../Libraries/SnapshotLibraries.php";

SetHeaders();

const REPLAY_NOTE_MAX_LENGTH = 1000;

$response = new stdClass();
$userId = ReplaySessionUserId();
session_write_close();

if (!IsValidReplayUserId($userId)) {
  $response->error = "You must be logged in to edit save notes.";
  ExitJsonResponse($response, 401);
}

$request = ReadJsonBody() ?: [];
$replayNumber = ParsePositiveReplayNumber($request["replayNumber"] ?? null);
$note = $request["note"] ?? null;
if ($replayNumber === null || !is_string($note)) {
  $response->error = "Invalid save note request.";
  ExitJsonResponse($response, 400);
}

$note = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', str_replace("\r\n", "\n", $note)));
if (mb_strlen($note, "UTF-8") > REPLAY_NOTE_MAX_LENGTH) {
  $response->error = "Notes can be at most " . REPLAY_NOTE_MAX_LENGTH . " characters.";
  ExitJsonResponse($response, 400);
}

$replayPath = UserReplayPath($userId, $replayNumber);
if (!is_dir($replayPath) || !(file_exists($replayPath . "origGamestate.txt") || (IsSnapshotDirectory($replayPath) && file_exists($replayPath . "gamestate.txt")))) {
  $response->error = "Save not found.";
  ExitJsonResponse($response, 404);
}

$metadataPath = $replayPath . "replayMetadata.json";
$metadata = file_exists($metadataPath) ? json_decode(file_get_contents($metadataPath), true) : [];
if (!is_array($metadata)) $metadata = [];
if ($note === "") unset($metadata["note"]);
else $metadata["note"] = $note;
if (file_put_contents($metadataPath, json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
  $response->error = "Failed to save the note.";
  ExitJsonResponse($response, 500);
}

$response->success = true;
$response->replayNumber = $replayNumber;
$response->note = $note;
WriteJsonResponse($response);
