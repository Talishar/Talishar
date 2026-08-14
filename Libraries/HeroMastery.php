<?php

function HeroMasteryMilestones(): array
{
  return [5, 10, 15, 25, 35, 50, 75, 100, 150, 200, 250, 350, 500, 750, 1000];
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

function HeroMasteryFrameLevel(int $earnedLevel, $displayLevel): int
{
  if ($displayLevel === null || $displayLevel === "") return $earnedLevel;
  $chosen = intval($displayLevel);
  if ($chosen < 0) return $earnedLevel;
  return min($chosen, $earnedLevel);
}

function HeroMasteryProgress(int $games, $displayLevel = null): array
{
  $milestones = HeroMasteryMilestones();
  $level = HeroMasteryLevel($games);
  $nextThreshold = $level < count($milestones) ? $milestones[$level] : null;
  return [
    "qualifyingGames" => $games,
    "level" => $level,
    "displayLevel" => $displayLevel === null ? null : intval($displayLevel),
    "frameLevel" => HeroMasteryFrameLevel($level, $displayLevel),
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

function HeroMasteryGameKey($gameName, $gameGUID): string
{
  $gameIdentifier = trim((string)$gameGUID);
  if ($gameIdentifier === "") $gameIdentifier = (string)$gameName;
  return "talishar:" . $gameIdentifier;
}

function AwardHeroMastery(bool $conceded = false): void
{
  global $winner, $currentTurn, $gameName, $gameGUID, $p1id, $p2id, $p2IsAI, $CS_OriginalHero;

  if (($p2IsAI ?? "0") === "1") return;
  $cache = ReadCacheArray(intval($gameName));
  if (!IsHeroMasteryFormatEligible($cache[12] ?? -1)) return;

  $eligiblePlayers = HeroMasteryEligiblePlayers($conceded, intval($winner), intval($currentTurn));
  if (empty($eligiblePlayers)) return;
  $gameKey = HeroMasteryGameKey($gameName, $gameGUID);

  $awardCandidates = [];
  foreach ($eligiblePlayers as $player) {
    $userId = intval($player === 1 ? $p1id : $p2id);
    if ($userId <= 0) continue;
    $character = &GetPlayerCharacter($player);
    $originalHero = GetClassState($player, $CS_OriginalHero);
    $hero = $originalHero !== "" && $originalHero !== "-"
      ? $originalHero
      : ($character[0] ?? "");
    $heroId = $hero !== "" ? SetID($hero) : "";
    if ($heroId === "") continue;
    $awardCandidates[] = ["userId" => $userId, "heroId" => $heroId];
  }
  if (empty($awardCandidates)) return;

  include_once __DIR__ . '/../includes/dbh.inc.php';
  $conn = GetDBConnection();
  if ($conn === false) return;

  try {
    $seed = $conn->prepare("INSERT IGNORE INTO hero_mastery (userId, heroId, qualifyingGames) VALUES (?, ?, 0)");
    $read = $conn->prepare("SELECT qualifyingGames FROM hero_mastery WHERE userId = ? AND heroId = ? FOR UPDATE");
    $award = $conn->prepare("INSERT IGNORE INTO hero_mastery_awards (gameKey, userId, heroId, gamesBefore, gamesAfter) VALUES (?, ?, ?, ?, ?)");
    $update = $conn->prepare("UPDATE hero_mastery SET qualifyingGames = ? WHERE userId = ? AND heroId = ?");
  } catch (Throwable $e) {
    error_log("Hero Mastery could not prepare award statements: " . $e->getMessage());
    mysqli_close($conn);
    return;
  }

  foreach ($awardCandidates as $candidate) {
    $userId = $candidate["userId"];
    $heroId = $candidate["heroId"];
    try {
      mysqli_begin_transaction($conn);
      $seed->bind_param("is", $userId, $heroId);
      $seed->execute();

      $read->bind_param("is", $userId, $heroId);
      $read->execute();
      $readResult = $read->get_result();
      $row = $readResult->fetch_assoc();
      $gamesBefore = intval($row["qualifyingGames"] ?? 0);
      $readResult->free();
      $gamesAfter = $gamesBefore + 1;

      $award->bind_param("sisii", $gameKey, $userId, $heroId, $gamesBefore, $gamesAfter);
      $award->execute();
      $isNewAward = $award->affected_rows === 1;

      if ($isNewAward) {
        $update->bind_param("iis", $gamesAfter, $userId, $heroId);
        $update->execute();
      }
      mysqli_commit($conn);
    } catch (Throwable $e) {
      mysqli_rollback($conn);
      error_log("Hero Mastery award failed for game " . $gameKey . ": " . $e->getMessage());
    }
  }
  $seed->close();
  $read->close();
  $award->close();
  $update->close();
  mysqli_close($conn);
}
