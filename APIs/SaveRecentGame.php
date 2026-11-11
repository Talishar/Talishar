<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/MetafyHelper.php";
include_once "../includes/ModeratorList.inc.php";
include_once "../Libraries/RecentGameLibraries.php";
include_once "../GeneratedCode/GeneratedFunctions/GeneratedCardName.php";

SetHeaders();

$response = new stdClass();
$response->success = false;
$userId = ReplaySessionUserId();
session_write_close();

if (!IsValidReplayUserId($userId)) {
  $response->error = "You must be logged in to save replays.";
  ExitJsonResponse($response, 401);
}

$body = ReadJsonBody() ?: [];
$token = $body["token"] ?? null;
if (!IsValidRecentGameToken($token)) {
  $response->error = "Invalid recent game.";
  ExitJsonResponse($response, 400);
}

$entry = null;
foreach (ReadRecentGameIndex($userId) as $candidate) {
  if ($candidate["token"] === $token) $entry = $candidate;
}
if ($entry === null) {
  $response->error = "This game is no longer available. Recent games are kept for 24 hours.";
  ExitJsonResponse($response, 404);
}

$gameGUID = (string)($entry["gameGUID"] ?? "");
$savedReplayNumbers = SavedReplayNumbersByGameGUID($userId);
if ($gameGUID !== "" && isset($savedReplayNumbers[$gameGUID])) {
  $response->error = "This game is already saved as replay #" . $savedReplayNumbers[$gameGUID] . ".";
  $response->replayNumber = $savedReplayNumbers[$gameGUID];
  ExitJsonResponse($response, 409);
}

$slotStatus = RecentGameSlotStatus($userId, GetMetafyTiersFromDatabase($userId));
if ($slotStatus["slotsFull"]) {
  $response->error = "Your " . $slotStatus["maxSlots"] . " replay slots are full. Delete a saved replay to make room.";
  ExitJsonResponse($response, 409);
}

$restoreDirectory = RestoreRecentGameDirectory($token);
if ($restoreDirectory === null) {
  $response->error = "This game's replay data could not be read.";
  ExitJsonResponse($response, 500);
}

$playerID = ($entry["p1uid"] ?? "") === $userId ? 1 : 2;
$p1Hero = (string)($entry["p1HeroCardId"] ?? "");
$p2Hero = (string)($entry["p2HeroCardId"] ?? "");
$result = SaveReplayFromGameDirectory($restoreDirectory, $userId, $playerID, [
  "p1DisplayName" => (string)($entry["p1DisplayName"] ?? ""),
  "p2DisplayName" => (string)($entry["p2DisplayName"] ?? ""),
  "p1HeroCardId" => $p1Hero,
  "p2HeroCardId" => $p2Hero,
  "p1HeroName" => RecentGameEntryHeroName($entry, "p1"),
  "p2HeroName" => RecentGameEntryHeroName($entry, "p2"),
  "format" => (string)($entry["format"] ?? ""),
  "gameGUID" => $gameGUID
], $slotStatus["maxSlots"], false);
RemoveReplayDirectory($restoreDirectory);

if (!$result["success"]) {
  $response->error = $result["message"];
  ExitJsonResponse($response, 409);
}

$response->success = true;
$response->replayNumber = $result["replayNumber"];
$response->message = $result["message"];
WriteJsonResponse($response);
