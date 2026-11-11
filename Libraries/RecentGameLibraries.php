<?php

include_once __DIR__ . "/ReplayLibraries.php";

const RECENT_GAME_TTL_SECONDS = 86400;
const RECENT_GAMES_PER_USER = 3;
const RECENT_GAMES_FREE_USER_SLOTS_FULL = 1;
const RECENT_GAME_MARKER_FILENAME = "recentGameStashed.txt";
const RECENT_GAME_PACK_HEADER = "TALISHAR_RECENT_GAME_PACK 1";
const RECENT_GAME_PACK_END = "END";
const RECENT_GAME_SOURCE_REQUIRED = 0;
const RECENT_GAME_SOURCE_COMPRESSED = 1;
const RECENT_GAME_SOURCE_OPTIONAL = 2;

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
  if (trim($gameFile[35] ?? "") === "1" || trim($gameFile[36] ?? "") === "1") return null;
  $p1uid = trim($gameFile[9] ?? "");
  $p2uid = trim($gameFile[10] ?? "");
  if ($p1uid !== "" && $p1uid === $p2uid) return null;
  $userIds = array_values(array_unique(array_filter([$p1uid, $p2uid], "IsValidReplayUserId")));
  if (count($userIds) === 0) return null;

  $initialState = @file_get_contents($gameDirectory . "origGamestate.txt");
  $pointers = ReplayStatePointers($gameDirectory);
  if (!is_string($initialState) || !is_file($gameDirectory . "commandfile.txt") || count($pointers) === 0) return null;

  $sources = [
    "origGamestate.txt" => [$gameDirectory . "origGamestate.txt", RECENT_GAME_SOURCE_REQUIRED],
    "commandfile.txt" => [$gameDirectory . "commandfile.txt", RECENT_GAME_SOURCE_REQUIRED],
  ];
  foreach ($pointers as $pointer) {
    $sources["replayState_$pointer.txt"] = [ReplayStateFilename($gameDirectory, $pointer), RECENT_GAME_SOURCE_COMPRESSED];
  }
  foreach (glob($gameDirectory . "turn_*-*_Gamestate.txt") ?: [] as $source) {
    $basename = basename($source);
    if (!preg_match('/^turn_[12]-\d+_Gamestate\.txt$/', $basename)) continue;
    $sources[$basename] = [$source, RECENT_GAME_SOURCE_OPTIONAL];
  }
  foreach ([REPLAY_LOG_FILENAME, REPLAY_LOG_INDEX_FILENAME] as $basename) {
    if (is_file($gameDirectory . $basename)) $sources[$basename] = [$gameDirectory . $basename, RECENT_GAME_SOURCE_OPTIONAL];
  }

  $initialLines = explode("\r\n", $initialState, 23);
  unset($initialState);
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

  $gamesDirectory = RecentGamesRoot() . "games/";
  if (!is_dir($gamesDirectory) && !@mkdir($gamesDirectory, 0777, true) && !is_dir($gamesDirectory)) return null;
  $token = bin2hex(random_bytes(16));
  $packPath = RecentGamePackPath($token);
  $temporaryPath = $packPath . ".tmp";
  $handle = @gzopen($temporaryPath, "wb6");
  if ($handle === false) return null;
  $written = WriteRecentGamePack($handle, $summary, $sources);
  $closed = gzclose($handle);
  if (!$written || !$closed || !@rename($temporaryPath, $packPath)) {
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

function WriteRecentGamePack($handle, array $summary, array $sources): bool
{
  $summaryJson = json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
  if (!is_string($summaryJson) || !WriteRecentGamePackBytes($handle, RECENT_GAME_PACK_HEADER . "\n" . $summaryJson . "\n")) return false;
  foreach ($sources as $basename => [$path, $kind]) {
    $content = @file_get_contents($path);
    if ($kind === RECENT_GAME_SOURCE_COMPRESSED && is_string($content)) $content = @gzdecode($content);
    if (!is_string($content)) {
      if ($kind === RECENT_GAME_SOURCE_OPTIONAL) continue;
      return false;
    }
    if (!WriteRecentGamePackBytes($handle, $basename . " " . strlen($content) . "\n") || !WriteRecentGamePackBytes($handle, $content)) return false;
    unset($content);
  }
  return WriteRecentGamePackBytes($handle, RECENT_GAME_PACK_END . "\n");
}

function WriteRecentGamePackBytes($handle, string $bytes): bool
{
  return $bytes === "" || gzwrite($handle, $bytes) === strlen($bytes);
}

function ReadRecentGamePackBytes($handle, int $length): ?string
{
  $content = "";
  while (strlen($content) < $length) {
    $chunk = gzread($handle, min(1048576, $length - strlen($content)));
    if (!is_string($chunk) || $chunk === "") return null;
    $content .= $chunk;
  }
  return $content;
}

function RestoreRecentGameDirectory(string $token): ?string
{
  if (!IsValidRecentGameToken($token)) return null;
  $handle = @gzopen(RecentGamePackPath($token), "rb");
  if ($handle === false) return null;
  $header = gzgets($handle, strlen(RECENT_GAME_PACK_HEADER) + 2);
  if (!is_string($header) || rtrim($header, "\n") !== RECENT_GAME_PACK_HEADER) {
    gzclose($handle);
    return RestoreLegacyRecentGameDirectory($token);
  }
  $directory = RecentGamesRoot() . "restore-" . bin2hex(random_bytes(8)) . "/";
  if (!@mkdir($directory, 0777, true)) {
    gzclose($handle);
    return null;
  }
  $restored = RestoreRecentGamePackFiles($handle, $directory);
  gzclose($handle);
  if (!$restored) {
    RemoveReplayDirectory($directory);
    return null;
  }
  return $directory;
}

function RestoreRecentGamePackFiles($handle, string $directory): bool
{
  if (gzgets($handle) === false) return false;
  while (($line = gzgets($handle)) !== false) {
    $line = rtrim($line, "\n");
    if ($line === RECENT_GAME_PACK_END) return true;
    if (!preg_match('/^(\S+) (\d+)$/', $line, $matches)) return false;
    $content = ReadRecentGamePackBytes($handle, (int)$matches[2]);
    if ($content === null || !RestoreRecentGameFile($directory, $matches[1], $content)) return false;
    unset($content);
  }
  return false;
}

function RestoreRecentGameFile(string $directory, string $basename, string $content): bool
{
  if (preg_match('/^replayState_(\d+)\.txt$/', $basename, $matches)) {
    $compressed = gzencode($content, 3);
    return $compressed !== false && file_put_contents(ReplayStateFilename($directory, (int)$matches[1]), $compressed) !== false;
  }
  $isKnownFile = in_array($basename, ["origGamestate.txt", "commandfile.txt", REPLAY_LOG_FILENAME, REPLAY_LOG_INDEX_FILENAME], true)
    || preg_match('/^turn_[12]-\d+_Gamestate\.txt$/', $basename) === 1;
  if (!$isKnownFile) return true;
  return file_put_contents($directory . $basename, $content) !== false;
}

function RestoreLegacyRecentGameDirectory(string $token): ?string
{
  $packed = @file_get_contents(RecentGamePackPath($token));
  $serialized = is_string($packed) ? @gzdecode($packed) : false;
  unset($packed);
  if (!is_string($serialized)) return null;
  $pack = @unserialize($serialized, ["allowed_classes" => false]);
  unset($serialized);
  if (!is_array($pack) || !is_array($pack["files"] ?? null) || !is_array($pack["summary"] ?? null)) return null;
  $directory = RecentGamesRoot() . "restore-" . bin2hex(random_bytes(8)) . "/";
  if (!@mkdir($directory, 0777, true)) return null;
  foreach ($pack["files"] as $basename => $content) {
    if (!is_string($basename) || !is_string($content)) continue;
    if (!RestoreRecentGameFile($directory, $basename, $content)) {
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
