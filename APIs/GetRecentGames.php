<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/MetafyHelper.php";
include_once "../Libraries/RecentGameLibraries.php";
include_once "../GeneratedCode/GeneratedFunctions/GeneratedCardName.php";

SetHeaders();

$response = new stdClass();
$response->games = [];
$response->ttlSeconds = RECENT_GAME_TTL_SECONDS;
$userId = ReplaySessionUserId();
session_write_close();

if (!IsValidReplayUserId($userId)) {
  $response->loggedIn = false;
  WriteJsonResponse($response);
  exit;
}

$response->loggedIn = true;
$slotStatus = RecentGameSlotStatus($userId, GetMetafyTiersFromDatabase($userId));
$response->slotsFull = $slotStatus["slotsFull"];
$response->maxSlots = $slotStatus["maxSlots"];
$response->usedSlots = $slotStatus["usedSlots"];
$response->capacity = $slotStatus["capacity"];
$entries = ReadRecentGameIndex($userId);
if (count($entries) > $slotStatus["capacity"]) {
  TrimRecentGameIndex($userId, $slotStatus["capacity"]);
  $entries = array_slice($entries, 0, $slotStatus["capacity"]);
}
$savedReplayNumbers = SavedReplayNumbersByGameGUID($userId);
foreach ($entries as $entry) {
  $game = new stdClass();
  $game->token = $entry["token"];
  $game->endedAt = (int)($entry["endedAt"] ?? 0);
  $game->expiresAt = $game->endedAt + RECENT_GAME_TTL_SECONDS;
  $game->playerID = ($entry["p1uid"] ?? "") === $userId ? 1 : 2;
  $game->format = (string)($entry["format"] ?? "");
  foreach (["p1", "p2"] as $player) {
    $heroCardId = (string)($entry[$player . "HeroCardId"] ?? "");
    $game->{$player . "DisplayName"} = (string)($entry[$player . "DisplayName"] ?? "");
    $game->{$player . "HeroCardId"} = $heroCardId;
    $game->{$player . "HeroName"} = RecentGameEntryHeroName($entry, $player);
  }
  $gameGUID = (string)($entry["gameGUID"] ?? "");
  $game->savedReplayNumber = $gameGUID !== "" ? ($savedReplayNumbers[$gameGUID] ?? null) : null;
  $response->games[] = $game;
}

WriteJsonResponse($response);
