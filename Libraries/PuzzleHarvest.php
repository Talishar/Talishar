<?php

include_once __DIR__ . "/PuzzleGame.php";

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
    kind TINYINT UNSIGNED NOT NULL DEFAULT 0,
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
    proof VARCHAR(1024) NOT NULL DEFAULT '',
    winning_line MEDIUMBLOB NULL,
    gamestate MEDIUMBLOB NOT NULL,
    solution TEXT NULL,
    baseline TEXT NULL,
    PRIMARY KEY (id),
    KEY status_created (status, created_at)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
  if (!mysqli_query($conn, $sql)) {
    error_log("Failed to create puzzle_candidates table: " . mysqli_error($conn));
  }
  $columns = mysqli_query($conn, "SHOW COLUMNS FROM puzzle_candidates");
  $existing = [];
  while ($columns && ($column = mysqli_fetch_assoc($columns))) $existing[$column["Field"]] = true;
  $missing = [
    "meta" => "ADD COLUMN meta VARCHAR(512) NOT NULL DEFAULT '' AFTER note",
    "proof" => "ADD COLUMN proof VARCHAR(1024) NOT NULL DEFAULT '' AFTER meta",
    "winning_line" => "ADD COLUMN winning_line MEDIUMBLOB NULL AFTER proof",
    "solution" => "ADD COLUMN solution TEXT NULL",
    "kind" => "ADD COLUMN kind TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER format",
    "baseline" => "ADD COLUMN baseline TEXT NULL"
  ];
  foreach ($missing as $name => $definition) {
    if (!isset($existing[$name])) mysqli_query($conn, "ALTER TABLE puzzle_candidates $definition");
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

// Concede (100002) or a win claimed over an inactive opponent (100007) by either player during this turn.
function PuzzleTurnConceded($gameDirectory, $winner, $turn)
{
  $commands = @file($gameDirectory . "commandfile.txt", FILE_IGNORE_NEW_LINES);
  $conceded = false;
  foreach (is_array($commands) ? $commands : [] as $command) {
    $mode = explode(" ", $command)[1] ?? "";
    if (rtrim($command) === "$winner StartTurn $turn 0") $conceded = false;
    else if ($mode === "100002" || $mode === "100007") $conceded = true;
  }
  return $conceded;
}

function PuzzleTurnStartState($turnPlayer)
{
  global $gameName, $currentTurn;
  $content = ReadRollbackSnapshot("./Games/$gameName/beginTurnGamestate.txt");
  if ($content === false) return null;
  $lines = explode("\r\n", $content);
  if (count($lines) < 60) return null;
  if (trim($lines[54]) != $turnPlayer || trim($lines[41]) != $currentTurn) return null;
  return $content;
}

// $player is the seat the solver plays; $life is the life the puzzle is about (the opponent's for lethal,
// the solver's own for survive).
function InsertPuzzleCandidate($logKey, $kind, $content, $player, $life, $meta, $winningLine)
{
  global $gameName, $currentTurn;
  $lines = explode("\r\n", $content);
  $offset = ($player - 1) * 18;
  $opponentOffset = (2 - $player) * 18;
  $hero = explode(" ", trim($lines[3 + $offset]))[0];
  $opponentHero = explode(" ", trim($lines[3 + $opponentOffset]))[0];
  $gameCache = ReadCacheArray(intval($gameName));
  $format = (string)($gameCache[12] ?? "");

  include_once __DIR__ . "/../includes/dbh.inc.php";
  $conn = GetDBConnection($logKey);
  if (!$conn) return;
  try {
    EnsurePuzzleCandidatesTable($conn);
    $sql = "INSERT INTO puzzle_candidates (game_name, format, kind, turn_number, player, hero, opponent_hero, opponent_life,
      hand_count, opponent_hand_count, meta, winning_line, gamestate) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    $gameNumber = intval($gameName);
    $turnNumber = intval($currentTurn);
    $handCount = PuzzleZoneCount($lines[1 + $offset]);
    $opponentHandCount = PuzzleZoneCount($lines[1 + $opponentOffset]);
    $meta = json_encode($meta);
    $winningLine = $winningLine === null ? null : gzcompress(json_encode($winningLine), 6);
    $compressed = gzcompress($content, 6);
    mysqli_stmt_bind_param($stmt, "isiiissiiisss", $gameNumber, $format, $kind, $turnNumber, $player, $hero, $opponentHero,
      $life, $handCount, $opponentHandCount, $meta, $winningLine, $compressed);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  } catch (Throwable $e) {
    error_log("InsertPuzzleCandidate failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}

function HarvestPuzzleCandidate($winner, $conceded)
{
  global $gameName, $mainPlayer, $currentTurn;
  if ($conceded || ($winner != 1 && $winner != 2) || $mainPlayer != $winner) return;
  $loser = $winner == 1 ? 2 : 1;
  if (GetHealth($loser) > 0) return;
  if (AreGlobalStatsDisabled(1) || AreGlobalStatsDisabled(2)) return;
  if (PuzzleTurnConceded("./Games/$gameName/", $winner, $currentTurn)) return;
  $content = PuzzleTurnStartState($winner);
  if ($content === null) return;
  $healths = explode(" ", trim(explode("\r\n", $content)[0]));

  include_once __DIR__ . "/PuzzleVerify.php";
  $line = ExtractPuzzleLine("./Games/$gameName/", $winner, $currentTurn);
  InsertPuzzleCandidate(DBL_HARVEST_PUZZLE_CANDIDATE, PUZZLE_KIND_LETHAL, $content, $winner,
    intval($healths[$loser - 1] ?? 0), PuzzleTurnMeta($winner, $loser), $line);
}

const PUZZLE_SURVIVE_MAX_LIFE = 4;

// A turn the defender only just lived through: undefended the attack was lethal, they blocked, and they ended on
// very little life. This runs at the end of every turn, so the cheap checks come first.
function HarvestSurvivePuzzleCandidate()
{
  global $gameName, $mainPlayer, $defPlayer, $currentTurn, $p1IsAI, $p2IsAI;
  if ($p1IsAI == "1" || $p2IsAI == "1" || IsGameOver()) return;
  $life = intval(GetHealth($defPlayer));
  if ($life < 1 || $life > PUZZLE_SURVIVE_MAX_LIFE) return;
  $meta = PuzzleTurnMeta($mainPlayer, $defPlayer);
  if ($meta["cardsPlayed"] < 2 || $meta["blocked"] < 1 || $meta["threatened"] < $life + $meta["dealt"]) return;
  if (AreGlobalStatsDisabled(1) || AreGlobalStatsDisabled(2)) return;
  if (PuzzleTurnConceded("./Games/$gameName/", $mainPlayer, $currentTurn)) return;
  $content = PuzzleTurnStartState($mainPlayer);
  if ($content === null) return;
  $startLife = intval(explode(" ", trim(explode("\r\n", $content)[0]))[$defPlayer - 1] ?? 0);
  if ($startLife <= $life || $meta["threatened"] < $startLife) return;

  include_once __DIR__ . "/PuzzleVerify.php";
  $script = ExtractPuzzleLine("./Games/$gameName/", $mainPlayer, $currentTurn);
  $line = ExtractPuzzleLine("./Games/$gameName/", $defPlayer, $currentTurn, $mainPlayer);
  if ($script === null || $line === null || count($line) == 0) return;
  InsertPuzzleCandidate(DBL_HARVEST_SURVIVE_PUZZLE, PUZZLE_KIND_SURVIVE, $content, $defPlayer, $startLife, $meta,
    ["line" => $line, "script" => $script]);
}
