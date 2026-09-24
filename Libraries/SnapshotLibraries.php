<?php

// Snapshots share the numbered replay storage and its favorite/cleanup quota.
function SavePlayableSnapshot(string $gameName, int $playerID): array
{
  $gamePath = "./Games/$gameName/";
  $gameFile = @file($gamePath . "GameFile.txt", FILE_IGNORE_NEW_LINES);
  $gamestate = @file_get_contents($gamePath . "gamestate.txt");
  if (!is_array($gameFile) || count($gameFile) < 44 || !is_string($gamestate) || $gamestate === "") {
    return ["success" => false, "message" => "The current game state is unavailable."];
  }
  // Saved entries must never expose auth keys for the original live game.
  $lines = explode("\r\n", $gamestate);
  $chainLinks = filter_var($lines[56] ?? null, FILTER_VALIDATE_INT);
  if ($chainLinks === false || $chainLinks < 0 || count($lines) < 76 + $chainLinks) {
    return ["success" => false, "message" => "The current game state is incomplete."];
  }
  if (trim($lines[61 + $chainLinks]) !== "1") {
    return ["success" => false, "message" => "Snapshots can only be saved during an active game."];
  }
  $lines[58 + $chainLinks] = bin2hex(random_bytes(32));
  $lines[59 + $chainLinks] = bin2hex(random_bytes(32));
  $gamestate = implode("\r\n", $lines);
  $gameFile[7] = $lines[58 + $chainLinks];
  $gameFile[8] = $lines[59 + $chainLinks];
  $gameFile[14] = "";
  $gameFile[21] = "";

  $owner = trim($gameFile[$playerID === 1 ? 9 : 10] ?? "");
  if (!IsValidReplayUserId($owner) || $owner === "-") {
    return ["success" => false, "message" => "Log in to save a snapshot."];
  }

  $root = "./Replays/$owner/";
  if (!is_dir($root) && !@mkdir($root, 0700, true)) {
    return ["success" => false, "message" => "Snapshot storage could not be created."];
  }
  $tiers = json_decode(trim($gameFile[$playerID === 1 ? 38 : 39] ?? ""), true);
  $maxSlots = GetMaxReplaySlotsForTiers(is_array($tiers) ? $tiers : [], IsUserContributor($owner));
  $saved = glob($root . "[0-9]*", GLOB_ONLYDIR) ?: [];
  $oldest = null;
  foreach ($saved as $directory) {
    $metadata = json_decode((string)@file_get_contents($directory . "/replayMetadata.json"), true);
    if (!is_array($metadata) || ($metadata["favorite"] ?? false) !== true) {
      if ($oldest === null || (int)basename($directory) < (int)basename($oldest)) $oldest = $directory;
    }
  }
  if (count($saved) >= $maxSlots && $oldest === null) {
    return ["success" => false, "message" => "All saved replay and snapshot slots are favorites. Remove a favorite to save a snapshot."];
  }

  $counter = (int)trim((string)@file_get_contents($root . "counter.txt"));
  if ($counter < 1) $counter = 1;
  while (is_dir($root . $counter)) ++$counter;
  $path = $root . $counter . "/";
  if (!@mkdir($path, 0700)) {
    return ["success" => false, "message" => "Snapshot storage could not be created."];
  }

  $p1Character = &GetPlayerCharacter(1);
  $p2Character = &GetPlayerCharacter(2);
  $metadata = [
    "type" => "snapshot",
    "savedByPlayerID" => $playerID,
    "p1DisplayName" => trim($gameFile[42] ?? ""),
    "p2DisplayName" => trim($gameFile[43] ?? ""),
    "p1HeroCardId" => $p1Character[0] ?? "",
    "p2HeroCardId" => $p2Character[0] ?? "",
    "p1HeroName" => isset($p1Character[0]) ? CardName($p1Character[0]) : "",
    "p2HeroName" => isset($p2Character[0]) ? CardName($p2Character[0]) : "",
    "favorite" => false,
    "savedAt" => time()
  ];
  if (
    @file_put_contents($path . "gamestate.txt", $gamestate, LOCK_EX) === false ||
    @file_put_contents($path . "GameFile.txt", implode("\r\n", $gameFile) . "\r\n", LOCK_EX) === false ||
    @file_put_contents($path . "replayMetadata.json", json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX) === false ||
    @file_put_contents($root . "counter.txt", (string)($counter + 1), LOCK_EX) === false
  ) {
    deleteDir($path);
    return ["success" => false, "message" => "Snapshot files could not be saved."];
  }

  if (count($saved) >= $maxSlots && $oldest !== null) deleteDir(rtrim($oldest, "/\\") . "/");
  return ["success" => true, "snapshotNumber" => $counter, "message" => "Snapshot #$counter saved."];
}

function IsSnapshotDirectory(string $path): bool
{
  $metadata = json_decode((string)@file_get_contents(rtrim($path, "/\\") . "/replayMetadata.json"), true);
  return is_array($metadata) && ($metadata["type"] ?? "") === "snapshot";
}
