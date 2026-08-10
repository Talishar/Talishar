<?php

function HeroMasteryMilestones(): array
{
  return [5, 15, 25, 50, 100, 150, 250, 500, 1000];
}

function HeroMasteryLevel(int $games): int
{
  $level = 0;
  foreach (HeroMasteryMilestones() as $threshold) {
    if ($games < $threshold) break;
    ++$level;
  }
  return $level;
}

function HeroMasteryProgress(int $games): array
{
  $milestones = HeroMasteryMilestones();
  $level = HeroMasteryLevel($games);
  $nextThreshold = $level < count($milestones) ? $milestones[$level] : null;
  return [
    "qualifyingGames" => $games,
    "level" => $level,
    "asset" => $level > 0 ? "mastery_" . $level : null,
    "nextThreshold" => $nextThreshold,
    "gamesToNext" => $nextThreshold === null ? null : $nextThreshold - $games,
  ];
}

function IsHeroMasteryFormatEligible($format): bool
{
  $formatCode = intval($format);
  if ($formatCode !== 17) return true;

  // Unrestricted Open games do not count in production, but can be used in local dev
  return filter_var(
    getenv("HERO_MASTERY_ALLOW_OPEN") ?: false,
    FILTER_VALIDATE_BOOLEAN
  );
}

function HeroMasteryEligiblePlayers(bool $conceded, int $winner, int $currentTurn): array
{
  if (!$conceded) return [1, 2];
  $concedingPlayer = $winner === 1 ? 2 : 1;
  if ($currentTurn < 1) return [];
  if ($currentTurn < 2) return [$concedingPlayer === 1 ? 2 : 1];
  return [1, 2];
}

function AwardHeroMastery(bool $conceded = false): void
{
  global $winner, $currentTurn, $gameName, $gameGUID, $p1id, $p2id, $p2IsAI;

  if (($p2IsAI ?? "0") === "1") return;
  $cache = ReadCacheArray(intval($gameName));
  if (!IsHeroMasteryFormatEligible($cache[12] ?? -1)) return;

  $eligiblePlayers = HeroMasteryEligiblePlayers($conceded, intval($winner), intval($currentTurn));
  if (empty($eligiblePlayers)) return;
  $gameKey = "talishar:" . (string)$gameName;

  include_once __DIR__ . '/../includes/dbh.inc.php';
  $conn = GetDBConnection();
  if ($conn === false) return;

  foreach ($eligiblePlayers as $player) {
    $userId = intval($player === 1 ? $p1id : $p2id);
    if ($userId <= 0) continue;
    $character = &GetPlayerCharacter($player);
    $heroId = isset($character[0]) ? SetID($character[0]) : "";
    if ($heroId === "") continue;

    try {
      mysqli_begin_transaction($conn);
      $seed = $conn->prepare("INSERT IGNORE INTO hero_mastery (userId, heroId, qualifyingGames) VALUES (?, ?, 0)");
      $seed->bind_param("is", $userId, $heroId);
      $seed->execute();
      $seed->close();

      $read = $conn->prepare("SELECT qualifyingGames FROM hero_mastery WHERE userId = ? AND heroId = ? FOR UPDATE");
      $read->bind_param("is", $userId, $heroId);
      $read->execute();
      $row = $read->get_result()->fetch_assoc();
      $gamesBefore = intval($row["qualifyingGames"] ?? 0);
      $read->close();
      $gamesAfter = $gamesBefore + 1;

      $award = $conn->prepare("INSERT IGNORE INTO hero_mastery_awards (gameKey, userId, heroId, gamesBefore, gamesAfter) VALUES (?, ?, ?, ?, ?)");
      $award->bind_param("sisii", $gameKey, $userId, $heroId, $gamesBefore, $gamesAfter);
      $award->execute();
      $isNewAward = $award->affected_rows === 1;
      $award->close();

      if ($isNewAward) {
        $update = $conn->prepare("UPDATE hero_mastery SET qualifyingGames = ? WHERE userId = ? AND heroId = ?");
        $update->bind_param("iis", $gamesAfter, $userId, $heroId);
        $update->execute();
        $update->close();
      }
      mysqli_commit($conn);
    } catch (Throwable $e) {
      mysqli_rollback($conn);
      error_log("Hero Mastery award failed for game " . $gameKey . ": " . $e->getMessage());
    }
  }
  mysqli_close($conn);
}
