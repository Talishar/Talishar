<?php

include_once __DIR__ . "/ReplayLibraries.php";

const MATCH_HISTORY_LIMIT = 20;

function MatchHistoryPath(string $userId): string
{
  return __DIR__ . "/../Replays/.history/$userId.json";
}

function ReadMatchHistory(string $userId): array
{
  if (!IsValidReplayUserId($userId)) return [];
  $entries = json_decode((string)@file_get_contents(MatchHistoryPath($userId)), true);
  if (!is_array($entries)) return [];
  return array_values(array_filter($entries, "is_array"));
}

function DeleteMatchHistory(string $userId): void
{
  if (IsValidReplayUserId($userId)) @unlink(MatchHistoryPath($userId));
}

function AddMatchHistoryEntry(string $userId, array $entry): void
{
  $path = MatchHistoryPath($userId);
  $directory = dirname($path);
  if (!is_dir($directory) && !@mkdir($directory, 0777, true) && !is_dir($directory)) return;
  $handle = @fopen($path, "c+");
  if ($handle === false) return;
  if (!flock($handle, LOCK_EX)) {
    fclose($handle);
    return;
  }
  $entries = json_decode((string)stream_get_contents($handle), true);
  if (!is_array($entries)) $entries = [];
  $entries = array_filter($entries, fn($existing) => is_array($existing) && ($entry["gameGUID"] === "" || ($existing["gameGUID"] ?? "") !== $entry["gameGUID"]));
  array_unshift($entries, $entry);
  ftruncate($handle, 0);
  rewind($handle);
  fwrite($handle, json_encode(array_slice($entries, 0, MATCH_HISTORY_LIMIT), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  fflush($handle);
  flock($handle, LOCK_UN);
  fclose($handle);
}

function RecordMatchHistory(): void
{
  global $winner, $currentTurn, $gameName, $CS_OriginalHero;

  $winningPlayer = intval($winner);
  if ($winningPlayer !== 1 && $winningPlayer !== 2) return;
  if (IsReplay()) return;

  $gameDirectory = __DIR__ . "/../Games/$gameName/";
  if (file_exists($gameDirectory . "snapshotOwnerSeat.txt")) return;
  $gameFileContent = @file_get_contents($gameDirectory . "GameFile.txt");
  if (!is_string($gameFileContent)) return;
  $gameFile = explode("\r\n", $gameFileContent);
  $userIds = array_map(fn($userId) => $userId === "-" ? "" : $userId, [1 => trim($gameFile[9] ?? ""), 2 => trim($gameFile[10] ?? "")]);
  if ($userIds[1] !== "" && $userIds[1] === $userIds[2]) return;
  if (!IsValidReplayUserId($userIds[1]) && !IsValidReplayUserId($userIds[2])) return;
  $displayNames = [
    1 => trim($gameFile[42] ?? "") ?: $userIds[1],
    2 => trim($gameFile[43] ?? "") ?: $userIds[2],
  ];

  $heroes = [];
  foreach ([1, 2] as $player) {
    $character = &GetPlayerCharacter($player);
    $originalHero = GetClassState($player, $CS_OriginalHero);
    $heroes[$player] = $originalHero !== "" && $originalHero !== "-" ? $originalHero : (string)($character[0] ?? "");
  }

  $endedAt = time();
  foreach ([1, 2] as $player) {
    if (!IsValidReplayUserId($userIds[$player])) continue;
    $opponent = $player === 1 ? 2 : 1;
    AddMatchHistoryEntry($userIds[$player], [
      "endedAt" => $endedAt,
      "gameGUID" => trim($gameFile[37] ?? ""),
      "format" => preg_replace('/^shadow/', '', trim($gameFile[3] ?? "")),
      "turns" => intval($currentTurn),
      "won" => $winningPlayer === $player,
      "heroCardId" => $heroes[$player],
      "heroName" => $heroes[$player] === "" ? "" : (string)CardName($heroes[$player]),
      "opponentName" => $displayNames[$opponent],
      "opponentHeroCardId" => $heroes[$opponent],
      "opponentHeroName" => $heroes[$opponent] === "" ? "" : (string)CardName($heroes[$opponent]),
    ]);
  }
}
