<?php

const PUZZLE_CANDIDATE_NEW = 0;
const PUZZLE_CANDIDATE_USED = 1;
const PUZZLE_CANDIDATE_REJECTED = 2;

function EnsurePuzzleCandidatesTable($conn)
{
  $sql = "CREATE TABLE IF NOT EXISTS puzzle_candidates (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    game_name INT UNSIGNED NOT NULL,
    format VARCHAR(16) NOT NULL,
    turn_number SMALLINT UNSIGNED NOT NULL,
    player TINYINT UNSIGNED NOT NULL,
    hero VARCHAR(64) NOT NULL,
    opponent_hero VARCHAR(64) NOT NULL,
    opponent_life SMALLINT NOT NULL,
    hand_count TINYINT UNSIGNED NOT NULL,
    opponent_hand_count TINYINT UNSIGNED NOT NULL,
    status TINYINT UNSIGNED NOT NULL DEFAULT 0,
    note VARCHAR(255) NOT NULL DEFAULT '',
    meta VARCHAR(512) NOT NULL DEFAULT '',
    gamestate MEDIUMBLOB NOT NULL,
    PRIMARY KEY (id),
    KEY status_created (status, created_at)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
  if (!mysqli_query($conn, $sql)) {
    error_log("Failed to create puzzle_candidates table: " . mysqli_error($conn));
  }
  $columns = mysqli_query($conn, "SHOW COLUMNS FROM puzzle_candidates LIKE 'meta'");
  if ($columns && mysqli_num_rows($columns) == 0) {
    mysqli_query($conn, "ALTER TABLE puzzle_candidates ADD COLUMN meta VARCHAR(512) NOT NULL DEFAULT '' AFTER note");
  }
}

function PuzzleTurnMeta($winner, $loser)
{
  global $TurnStats_DamageThreatened, $TurnStats_DamageDealt, $TurnStats_CardsPlayedOffense, $TurnStats_CardsPitched;
  global $TurnStats_ResourcesUsed, $TurnStats_CardsBlocked, $TurnStats_DamageBlocked;
  $winnerStats = &GetTurnStats($winner);
  $winnerBase = GetStatTurnIndex($winner) * TurnStatPieces();
  $loserStats = &GetTurnStats($loser);
  $loserBase = GetStatTurnIndex($loser) * TurnStatPieces();
  return [
    "threatened" => intval($winnerStats[$winnerBase + $TurnStats_DamageThreatened] ?? 0),
    "dealt" => intval($winnerStats[$winnerBase + $TurnStats_DamageDealt] ?? 0),
    "cardsPlayed" => intval($winnerStats[$winnerBase + $TurnStats_CardsPlayedOffense] ?? 0),
    "pitched" => intval($winnerStats[$winnerBase + $TurnStats_CardsPitched] ?? 0),
    "resourcesUsed" => intval($winnerStats[$winnerBase + $TurnStats_ResourcesUsed] ?? 0),
    "blocked" => intval($loserStats[$loserBase + $TurnStats_DamageBlocked] ?? 0),
    "cardsBlocked" => intval($loserStats[$loserBase + $TurnStats_CardsBlocked] ?? 0),
    "overkill" => max(0, -intval(GetHealth($loser)))
  ];
}

function PuzzleZoneCount($line)
{
  $line = trim($line);
  return $line === "" ? 0 : count(explode(" ", $line));
}

function HarvestPuzzleCandidate($winner, $conceded)
{
  global $gameName, $mainPlayer, $currentTurn;
  if ($conceded || ($winner != 1 && $winner != 2) || $mainPlayer != $winner) return;
  $loser = $winner == 1 ? 2 : 1;
  if (GetHealth($loser) > 0) return;
  if (AreGlobalStatsDisabled(1) || AreGlobalStatsDisabled(2)) return;

  $content = @file_get_contents("./Games/$gameName/beginTurnGamestate.txt");
  if ($content === false) return;
  $lines = explode("\r\n", $content);
  if (count($lines) < 60) return;
  if (trim($lines[54]) != $winner || trim($lines[41]) != $currentTurn) return;

  $offset = ($winner - 1) * 18;
  $opponentOffset = ($loser - 1) * 18;
  $healths = explode(" ", trim($lines[0]));
  $hero = explode(" ", trim($lines[3 + $offset]))[0];
  $opponentHero = explode(" ", trim($lines[3 + $opponentOffset]))[0];
  $gameCache = ReadCacheArray(intval($gameName));
  $format = (string)($gameCache[12] ?? "");

  include_once __DIR__ . "/../includes/dbh.inc.php";
  $conn = GetDBConnection(DBL_HARVEST_PUZZLE_CANDIDATE);
  if (!$conn) return;
  try {
    EnsurePuzzleCandidatesTable($conn);
    $sql = "INSERT INTO puzzle_candidates (game_name, format, turn_number, player, hero, opponent_hero, opponent_life,
      hand_count, opponent_hand_count, meta, gamestate) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    $gameNumber = intval($gameName);
    $turnNumber = intval($currentTurn);
    $opponentLife = intval($healths[$loser - 1] ?? 0);
    $handCount = PuzzleZoneCount($lines[1 + $offset]);
    $opponentHandCount = PuzzleZoneCount($lines[1 + $opponentOffset]);
    $meta = json_encode(PuzzleTurnMeta($winner, $loser));
    $compressed = gzcompress($content, 6);
    mysqli_stmt_bind_param($stmt, "isiissiiiss", $gameNumber, $format, $turnNumber, $winner, $hero, $opponentHero,
      $opponentLife, $handCount, $opponentHandCount, $meta, $compressed);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  } catch (Throwable $e) {
    error_log("HarvestPuzzleCandidate failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}
