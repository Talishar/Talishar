<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/MetafyHelper.php";
include_once "../includes/ModeratorList.inc.php";

SetHeaders();

$response = new stdClass();
$response->replays = [];
$response->maxSlots = MAX_REPLAYS_SAVED;
$response->usedSlots = 0;
$response->favoriteSlots = 0;
$response->nextSlotTier = null;
$userId = $_SESSION["useruid"] ?? "";
session_write_close();

if ($userId === "") {
  $response->loggedIn = false;
  echo json_encode($response);
  exit;
}

if (!preg_match('/^[A-Za-z0-9_-]+$/', $userId)) {
  $response->error = "Invalid user session.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$response->loggedIn = true;
$metafyTiers = GetMetafyTiersFromDatabase($userId);
$isContributor = IsUserContributor($userId);
$response->maxSlots = GetMaxReplaySlotsForTiers($metafyTiers, $isContributor);
$nextSlotTier = GetNextReplaySlotTier($metafyTiers, $isContributor);
if ($nextSlotTier !== null) $response->nextSlotTier = (object)$nextSlotTier;
$replayRoot = "../Replays/$userId/";
if (!is_dir($replayRoot)) {
  echo json_encode($response);
  exit;
}

foreach (scandir($replayRoot) ?: [] as $entry) {
  if (!ctype_digit($entry)) continue;

  $replayPath = $replayRoot . $entry . "/";
  if (!is_dir($replayPath) || !file_exists($replayPath . "origGamestate.txt") || !file_exists($replayPath . "commandfile.txt")) {
    continue;
  }

  $metadata = [];
  $metadataPath = $replayPath . "replayMetadata.json";
  if (file_exists($metadataPath)) {
    $decoded = json_decode(file_get_contents($metadataPath), true);
    if (is_array($decoded)) $metadata = $decoded;
  }

  $replay = new stdClass();
  $replay->replayNumber = (int)$entry;
  $metadataSavedAt = $metadata["savedAt"] ?? null;
  $replay->savedAt = is_numeric($metadataSavedAt)
    ? (int)$metadataSavedAt
    : (filemtime($replayPath . "origGamestate.txt") ?: 0);
  $replay->p1DisplayName = trim((string)($metadata["p1DisplayName"] ?? ""));
  $replay->p2DisplayName = trim((string)($metadata["p2DisplayName"] ?? ""));
  $replay->p1HeroCardId = trim((string)($metadata["p1HeroCardId"] ?? ""));
  $replay->p2HeroCardId = trim((string)($metadata["p2HeroCardId"] ?? ""));
  $replay->p1HeroName = trim((string)($metadata["p1HeroName"] ?? ""));
  $replay->p2HeroName = trim((string)($metadata["p2HeroName"] ?? ""));
  $replay->favorite = ($metadata["favorite"] ?? false) === true;
  if ($replay->favorite) ++$response->favoriteSlots;
  $response->replays[] = $replay;
}

$response->usedSlots = count($response->replays);

usort($response->replays, fn($a, $b) => $b->replayNumber <=> $a->replayNumber);
echo json_encode($response);
