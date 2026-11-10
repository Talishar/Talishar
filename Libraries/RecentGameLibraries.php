<?php

include_once __DIR__ . "/ReplayLibraries.php";

const RECENT_GAME_TTL_SECONDS = 86400;
const RECENT_GAMES_PER_USER = 3;
const RECENT_GAMES_FREE_USER_SLOTS_FULL = 1;
const RECENT_GAME_MARKER_FILENAME = "recentGameStashed.txt";

function RecentGamesRoot(): string
{
  return __DIR__ . "/../Replays/.recent/";
}

function RecentGamePackPath(string $token): string
{
  return RecentGamesRoot() . "games/$token.gz";
}

function RecentGameIndexPath(string $userId): string
{
  return RecentGamesRoot() . "users/$userId.json";
}

function IsValidRecentGameToken($token): bool
{
  return is_string($token) && strlen($token) === 32 && ctype_xdigit($token);
}

function RecentGameMarkerPath(string $gameDirectory): string
{
  return rtrim($gameDirectory, "/\\") . "/" . RECENT_GAME_MARKER_FILENAME;
}

function ClearRecentGameMarker(string $gameDirectory): void
{
  @unlink(RecentGameMarkerPath($gameDirectory));
}

function RecentGameHeroFromGamestate(array $lines, int $lineIndex): string
{
  $hero = strtok(trim($lines[$lineIndex] ?? ""), " ");
  return is_string($hero) && $hero !== "-" ? $hero : "";
}

function RecentGameHeroName(string $cardID): string
{
  if ($cardID === "") return "";
  if (function_exists("CardName") && function_exists("GetClass")) return (string)CardName($cardID);
  if ($cardID === "DUMMY") return "Practice Dummy";
  if ($cardID === "DUMMYDISHONORED") return "Dishonored Hero";
  if (function_exists("GeneratedCardName")) return (string)GeneratedCardName($cardID);
  return "";
}

function RecentGameSlotStatus(string $userId, $metafyTiers): array
{
  include_once __DIR__ . "/../includes/MetafyHelper.php";
  $isContributor = IsUserContributor($userId);
  $maxSlots = GetMaxReplaySlotsForTiers(is_array($metafyTiers) ? $metafyTiers : [], $isContributor);
  $usedSlots = count(glob(__DIR__ . "/../Replays/$userId/[0-9]*", GLOB_ONLYDIR) ?: []);
  $slotsFull = $usedSlots >= $maxSlots;
  $isFreeUser = !$isContributor && $maxSlots <= MAX_REPLAYS_SAVED;
  return [
    "maxSlots" => $maxSlots,
    "usedSlots" => $usedSlots,
    "slotsFull" => $slotsFull,
    "capacity" => $slotsFull && $isFreeUser ? RECENT_GAMES_FREE_USER_SLOTS_FULL : RECENT_GAMES_PER_USER
  ];
}

function TrimRecentGameIndex(string $userId, int $capacity): void
{
  $dropped = UpdateRecentGameIndex($userId, fn(array $entries) => [array_slice($entries, 0, $capacity), array_slice($entries, $capacity)]);
  foreach ($dropped as $droppedEntry) ReleaseRecentGame($droppedEntry, $userId);
}

function StashRecentGame(string $gameDirectory): ?string
{
  $gameDirectory = rtrim($gameDirectory, "/\\") . "/";
  if (file_exists($gameDirectory . RECENT_GAME_MARKER_FILENAME)) return null;
  if (file_exists($gameDirectory . "replaySource.json") || file_exists($gameDirectory . "replayCommands.txt")) return null;

  $gameFileContent = @file_get_contents($gameDirectory . "GameFile.txt");
  if (!is_string($gameFileContent)) return null;
  $gameFile = explode("\r\n", $gameFileContent);
  $p1uid = trim($gameFile[9] ?? "");
  $p2uid = trim($gameFile[10] ?? "");
  $userIds = array_values(array_unique(array_filter([$p1uid, $p2uid], "IsValidReplayUserId")));
  if (count($userIds) === 0) return null;

  $initialState = @file_get_contents($gameDirectory . "origGamestate.txt");
  $commandFile = @file_get_contents($gameDirectory . "commandfile.txt");
  $pointers = ReplayStatePointers($gameDirectory);
  if (!is_string($initialState) || !is_string($commandFile) || count($pointers) === 0) return null;

  $files = [
    "origGamestate.txt" => $initialState,
    "commandfile.txt" => $commandFile,
  ];
  foreach ($pointers as $pointer) {
    $compressed = @file_get_contents(ReplayStateFilename($gameDirectory, $pointer));
    $gamestate = is_string($compressed) ? @gzdecode($compressed) : false;
    if (!is_string($gamestate)) return null;
    $files["replayState_$pointer.txt"] = $gamestate;
  }
  foreach (glob($gameDirectory . "turn_*-*_Gamestate.txt") ?: [] as $source) {
    $basename = basename($source);
    if (!preg_match('/^turn_[12]-\d+_Gamestate\.txt$/', $basename)) continue;
    $content = @file_get_contents($source);
    if (is_string($content)) $files[$basename] = $content;
  }
  foreach ([REPLAY_LOG_FILENAME, REPLAY_LOG_INDEX_FILENAME] as $basename) {
    $content = @file_get_contents($gameDirectory . $basename);
    if (is_string($content)) $files[$basename] = $content;
  }

  $initialLines = explode("\r\n", $initialState, 23);
  $p1Hero = RecentGameHeroFromGamestate($initialLines, 3);
  $p2Hero = RecentGameHeroFromGamestate($initialLines, 21);
  $endedAt = time();
  $summary = [
    "endedAt" => $endedAt,
    "gameGUID" => trim($gameFile[37] ?? ""),
    "format" => trim($gameFile[3] ?? ""),
    "p1uid" => $p1uid,
    "p2uid" => $p2uid,
    "p1DisplayName" => trim($gameFile[42] ?? "") ?: $p1uid,
    "p2DisplayName" => trim($gameFile[43] ?? "") ?: $p2uid,
    "p1HeroCardId" => $p1Hero,
    "p2HeroCardId" => $p2Hero,
    "p1HeroName" => RecentGameHeroName($p1Hero),
    "p2HeroName" => RecentGameHeroName($p2Hero),
    "stateCount" => count($pointers),
  ];

  $packed = gzencode(serialize(["summary" => $summary, "files" => $files]), 6);
  if ($packed === false) return null;

  $gamesDirectory = RecentGamesRoot() . "games/";
  if (!is_dir($gamesDirectory) && !@mkdir($gamesDirectory, 0777, true) && !is_dir($gamesDirectory)) return null;
  $token = bin2hex(random_bytes(16));
  $packPath = RecentGamePackPath($token);
  $temporaryPath = $packPath . ".tmp";
  if (@file_put_contents($temporaryPath, $packed, LOCK_EX) === false || !@rename($temporaryPath, $packPath)) {
    @unlink($temporaryPath);
    return null;
  }
  @file_put_contents($gameDirectory . RECENT_GAME_MARKER_FILENAME, $token, LOCK_EX);

  $entry = $summary + ["token" => $token];
  foreach ($userIds as $userId) {
    $metafyTiers = json_decode(trim($gameFile[$userId === $p1uid ? 38 : 39] ?? ""), true);
    $capacity = RecentGameSlotStatus($userId, $metafyTiers)["capacity"];
    $dropped = UpdateRecentGameIndex($userId, function (array $entries) use ($entry, $capacity) {
      array_unshift($entries, $entry);
      return [array_slice($entries, 0, $capacity), array_slice($entries, $capacity)];
    });
    foreach ($dropped as $droppedEntry) ReleaseRecentGame($droppedEntry, $userId);
  }

  if (random_int(1, 25) === 1) PruneExpiredRecentGames();
  return $token;
}

function UpdateRecentGameIndex(string $userId, callable $mutate): array
{
  $usersDirectory = RecentGamesRoot() . "users/";
  if (!is_dir($usersDirectory) && !@mkdir($usersDirectory, 0777, true) && !is_dir($usersDirectory)) return [];
  $handle = @fopen(RecentGameIndexPath($userId), "c+");
  if ($handle === false) return [];
  if (!flock($handle, LOCK_EX)) {
    fclose($handle);
    return [];
  }
  $entries = json_decode((string)stream_get_contents($handle), true);
  if (!is_array($entries)) $entries = [];
  $entries = array_values(array_filter($entries, fn($entry) => is_array($entry) && IsValidRecentGameToken($entry["token"] ?? null)));
  [$entries, $removed] = $mutate($entries);
  ftruncate($handle, 0);
  rewind($handle);
  fwrite($handle, json_encode(array_values($entries), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  fflush($handle);
  flock($handle, LOCK_UN);
  fclose($handle);
  return $removed;
}

function ReadRecentGameIndex(string $userId): array
{
  if (!IsValidReplayUserId($userId)) return [];
  $entries = json_decode((string)@file_get_contents(RecentGameIndexPath($userId)), true);
  if (!is_array($entries)) return [];
  $now = time();
  $live = [];
  foreach ($entries as $entry) {
    if (!is_array($entry) || !IsValidRecentGameToken($entry["token"] ?? null)) continue;
    if ($now - (int)($entry["endedAt"] ?? 0) >= RECENT_GAME_TTL_SECONDS) continue;
    if (!file_exists(RecentGamePackPath($entry["token"]))) continue;
    $live[] = $entry;
  }
  return $live;
}

function ReleaseRecentGame(array $entry, string $releasingUserId): void
{
  $token = $entry["token"] ?? null;
  if (!IsValidRecentGameToken($token)) return;
  foreach ([$entry["p1uid"] ?? "", $entry["p2uid"] ?? ""] as $userId) {
    if ($userId === $releasingUserId || !IsValidReplayUserId($userId)) continue;
    foreach (ReadRecentGameIndex($userId) as $otherEntry) {
      if (($otherEntry["token"] ?? "") === $token) return;
    }
  }
  @unlink(RecentGamePackPath($token));
}

function PruneExpiredRecentGames(): void
{
  $cutoff = time() - RECENT_GAME_TTL_SECONDS;
  foreach (glob(RecentGamesRoot() . "games/*") ?: [] as $filename) {
    $modified = @filemtime($filename);
    if ($modified !== false && $modified < $cutoff) @unlink($filename);
  }
  foreach (glob(RecentGamesRoot() . "restore-*", GLOB_ONLYDIR) ?: [] as $directory) {
    $modified = @filemtime($directory);
    if ($modified !== false && $modified < time() - 3600) RemoveReplayDirectory($directory);
  }
  foreach (glob(RecentGamesRoot() . "users/*.json") ?: [] as $filename) {
    $modified = @filemtime($filename);
    if ($modified !== false && $modified < $cutoff) @unlink($filename);
  }
}

function ReadRecentGamePack(string $token): ?array
{
  if (!IsValidRecentGameToken($token)) return null;
  $packed = @file_get_contents(RecentGamePackPath($token));
  $serialized = is_string($packed) ? @gzdecode($packed) : false;
  if (!is_string($serialized)) return null;
  $pack = @unserialize($serialized, ["allowed_classes" => false]);
  if (!is_array($pack) || !is_array($pack["files"] ?? null) || !is_array($pack["summary"] ?? null)) return null;
  return $pack;
}

function RestoreRecentGameDirectory(array $pack): ?string
{
  $directory = RecentGamesRoot() . "restore-" . bin2hex(random_bytes(8)) . "/";
  if (!@mkdir($directory, 0777, true)) return null;
  foreach ($pack["files"] as $basename => $content) {
    if (!is_string($basename) || !is_string($content)) continue;
    if (preg_match('/^replayState_(\d+)\.txt$/', $basename, $matches)) {
      $compressed = gzencode($content, 3);
      if ($compressed === false || file_put_contents(ReplayStateFilename($directory, (int)$matches[1]), $compressed) === false) {
        RemoveReplayDirectory($directory);
        return null;
      }
      continue;
    }
    $isKnownFile = in_array($basename, ["origGamestate.txt", "commandfile.txt", REPLAY_LOG_FILENAME, REPLAY_LOG_INDEX_FILENAME], true)
      || preg_match('/^turn_[12]-\d+_Gamestate\.txt$/', $basename) === 1;
    if (!$isKnownFile) continue;
    if (file_put_contents($directory . $basename, $content) === false) {
      RemoveReplayDirectory($directory);
      return null;
    }
  }
  return $directory;
}


function RecentGameEntryHeroName(array $entry, string $player): string
{
  $name = (string)($entry[$player . "HeroName"] ?? "");
  if ($name !== "") return $name;
  $cardID = (string)($entry[$player . "HeroCardId"] ?? "");
  return RecentGameHeroName($cardID) ?: $cardID;
}

function SavedReplayNumbersByGameGUID(string $userId): array
{
  $numbers = [];
  foreach (glob(__DIR__ . "/../Replays/$userId/[0-9]*/replayMetadata.json") ?: [] as $metadataPath) {
    $metadata = json_decode((string)@file_get_contents($metadataPath), true);
    $gameGUID = is_array($metadata) ? (string)($metadata["gameGUID"] ?? "") : "";
    if ($gameGUID !== "") $numbers[$gameGUID] = (int)basename(dirname($metadataPath));
  }
  return $numbers;
}

function StashRecentGameBeforeDelete(string $gameDirectory): void
{
  try {
    StashRecentGame($gameDirectory);
  } catch (Throwable $e) {
    error_log("StashRecentGame failed: " . $e->getMessage());
  }
}
