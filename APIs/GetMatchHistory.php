<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/MetafyHelper.php";
include_once "../Libraries/MatchHistory.php";
include_once "../Libraries/RecentGameLibraries.php";
include_once "../GeneratedCode/GeneratedFunctions/GeneratedCardName.php";

SetHeaders();

$response = new stdClass();
$response->games = [];
$userId = ReplaySessionUserId();
session_write_close();

if (!IsValidReplayUserId($userId)) {
  $response->loggedIn = false;
  WriteJsonResponse($response);
  exit;
}

$response->loggedIn = true;
$response->limit = MATCH_HISTORY_LIMIT;
$savedReplayNumbers = SavedReplayNumbersByGameGUID($userId);
$slotStatus = RecentGameSlotStatus($userId, GetMetafyTiersFromDatabase($userId));
$savableRecentGames = $slotStatus["slotsFull"] ? [] : array_slice(ReadRecentGameIndex($userId), 0, $slotStatus["capacity"]);
$savableGameGUIDs = array_flip(array_filter(array_map(fn($entry) => (string)($entry["gameGUID"] ?? ""), $savableRecentGames)));
foreach (ReadMatchHistory($userId) as $entry) {
  $game = new stdClass();
  $game->endedAt = (int)($entry["endedAt"] ?? 0);
  $game->won = (bool)($entry["won"] ?? false);
  $game->format = (string)($entry["format"] ?? "");
  $game->turns = (int)($entry["turns"] ?? 0);
  $game->opponentName = (string)($entry["opponentName"] ?? "");
  foreach (["hero", "opponentHero"] as $prefix) {
    $cardID = (string)($entry[$prefix . "CardId"] ?? "");
    $game->{$prefix . "CardId"} = $cardID;
    $game->{$prefix . "Name"} = (string)($entry[$prefix . "Name"] ?? "") ?: (RecentGameHeroName($cardID) ?: $cardID);
  }
  $gameGUID = (string)($entry["gameGUID"] ?? "");
  $game->savedReplayNumber = $gameGUID !== "" ? ($savedReplayNumbers[$gameGUID] ?? null) : null;
  $game->canSaveReplay = $game->savedReplayNumber === null && $gameGUID !== "" && isset($savableGameGUIDs[$gameGUID]);
  $response->games[] = $game;
}

WriteJsonResponse($response);
