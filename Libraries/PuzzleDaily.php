<?php

include_once __DIR__ . "/PuzzleGame.php";
include_once __DIR__ . "/../includes/dbh.inc.php";
include_once __DIR__ . "/../includes/DBLogConstants.php";

const PUZZLE_LEADERBOARD_SIZE = 10;

function EnsureDailyPuzzleTables($conn)
{
  $tables = [
    "CREATE TABLE IF NOT EXISTS puzzle_daily (
      puzzle_date DATE NOT NULL,
      candidate_id INT UNSIGNED NOT NULL,
      mode VARCHAR(16) NOT NULL,
      info MEDIUMTEXT NOT NULL,
      created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (puzzle_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS puzzle_results (
      puzzle_date DATE NOT NULL,
      user_id INT NOT NULL,
      game_name INT UNSIGNED NOT NULL DEFAULT 0,
      hints TINYINT UNSIGNED NOT NULL DEFAULT 0,
      tries SMALLINT UNSIGNED NOT NULL DEFAULT 1,
      finished TINYINT UNSIGNED NOT NULL DEFAULT 0,
      solved TINYINT UNSIGNED NOT NULL DEFAULT 0,
      damage SMALLINT NULL,
      stars TINYINT UNSIGNED NOT NULL DEFAULT 0,
      rating TINYINT NOT NULL DEFAULT 0,
      started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      finished_at TIMESTAMP NULL,
      PRIMARY KEY (puzzle_date, user_id),
      KEY date_damage (puzzle_date, finished, damage)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
  ];
  foreach ($tables as $sql) {
    if (!mysqli_query($conn, $sql)) error_log("Failed to create daily puzzle table: " . mysqli_error($conn));
  }
}

function DailyPuzzleToday()
{
  return gmdate("Y-m-d");
}

function DailyPuzzleQuery($conn, $sql, $types = "", ...$values)
{
  $stmt = mysqli_prepare($conn, $sql);
  if ($types !== "") mysqli_stmt_bind_param($stmt, $types, ...$values);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $rows = $result === false ? [] : mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_stmt_close($stmt);
  return $rows;
}

function LoadDailyPuzzle($conn, $date)
{
  $rows = DailyPuzzleQuery($conn, "SELECT puzzle_date, candidate_id, mode, info FROM puzzle_daily WHERE puzzle_date = ?", "s", $date);
  if (count($rows) == 0) return null;
  $rows[0]["info"] = json_decode($rows[0]["info"], true) ?: [];
  return $rows[0];
}

function DailyPuzzleMissing($date)
{
  $conn = GetDBConnection(DBL_GET_DAILY_PUZZLE);
  if (!$conn) return false;
  try {
    EnsureDailyPuzzleTables($conn);
    return LoadDailyPuzzle($conn, $date) === null;
  } catch (Throwable $e) {
    error_log("DailyPuzzleMissing failed: " . $e->getMessage());
    return false;
  } finally {
    mysqli_close($conn);
  }
}

function DailyPuzzleNumber($conn, $date)
{
  return intval(DailyPuzzleQuery($conn, "SELECT COUNT(*) AS number FROM puzzle_daily WHERE puzzle_date <= ?", "s", $date)[0]["number"] ?? 0);
}

function LoadDailyResult($conn, $date, $userId)
{
  return DailyPuzzleQuery($conn, "SELECT * FROM puzzle_results WHERE puzzle_date = ? AND user_id = ?", "si", $date, $userId)[0] ?? null;
}

function DailyPuzzleStats($conn, $date)
{
  $row = DailyPuzzleQuery($conn, "SELECT COUNT(*) AS players, COALESCE(SUM(finished), 0) AS finished,
    COALESCE(SUM(solved), 0) AS solved, AVG(CASE WHEN finished = 1 THEN stars END) AS stars,
    COALESCE(SUM(rating > 0), 0) AS ups, COALESCE(SUM(rating < 0), 0) AS downs, MAX(damage) AS best
    FROM puzzle_results WHERE puzzle_date = ?", "s", $date)[0];
  return [
    "players" => intval($row["players"]),
    "finished" => intval($row["finished"]),
    "solved" => intval($row["solved"]),
    "averageStars" => $row["stars"] === null ? null : round(floatval($row["stars"]), 2),
    "ups" => intval($row["ups"]),
    "downs" => intval($row["downs"]),
    "best" => $row["best"] === null ? null : intval($row["best"])
  ];
}

function DailyPuzzleLeaderboard($conn, $date)
{
  $rows = DailyPuzzleQuery($conn, "SELECT COALESCE(NULLIF(u.displayName, ''), u.usersUid) AS name, r.damage, r.stars, r.hints
    FROM puzzle_results r JOIN users u ON u.usersId = r.user_id
    WHERE r.puzzle_date = ? AND r.finished = 1 AND r.damage IS NOT NULL
    ORDER BY r.damage DESC, r.hints ASC, r.finished_at ASC LIMIT " . PUZZLE_LEADERBOARD_SIZE, "s", $date);
  return array_map(fn($row) => ["name" => $row["name"], "damage" => intval($row["damage"]), "stars" => intval($row["stars"]),
    "hints" => intval($row["hints"])], $rows);
}

function DailyPuzzleProgressConnection()
{
  $conn = GetDBConnection(DBL_DAILY_PUZZLE_PROGRESS);
  if ($conn) EnsureDailyPuzzleTables($conn);
  return $conn;
}

function AddDailyPuzzleTry($date, $userId)
{
  $conn = DailyPuzzleProgressConnection();
  if (!$conn) return;
  try {
    DailyPuzzleQuery($conn, "UPDATE puzzle_results SET tries = tries + 1 WHERE puzzle_date = ? AND user_id = ? AND finished = 0",
      "si", $date, $userId);
  } catch (Throwable $e) {
    error_log("AddDailyPuzzleTry failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}

function SetDailyPuzzleHints($date, $userId, $hints)
{
  $conn = DailyPuzzleProgressConnection();
  if (!$conn) return;
  try {
    DailyPuzzleQuery($conn, "UPDATE puzzle_results SET hints = GREATEST(hints, ?) WHERE puzzle_date = ? AND user_id = ? AND finished = 0",
      "isi", $hints, $date, $userId);
  } catch (Throwable $e) {
    error_log("SetDailyPuzzleHints failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}

// Lethal and survive: three stars without hints, one less per hint, at least one when solved.
// Damage: a star each for reaching the bot, the player in the real game, and the best anyone else has done.
function DailyPuzzleStars($mode, $solved, $damage, $hints, $bars, $best)
{
  if ($mode !== "damage") return $solved ? max(1, 3 - $hints) : 0;
  $damage = intval($damage);
  return intval($damage >= intval($bars["bot"] ?? 0)) + intval($damage >= intval($bars["real"] ?? 0))
    + intval($damage >= max(intval($bars["real"] ?? 0), intval($best)));
}

function RecordDailyPuzzleResult($date, $userId, $solved, $damage)
{
  $conn = DailyPuzzleProgressConnection();
  if (!$conn) return;
  try {
    $daily = LoadDailyPuzzle($conn, $date);
    DailyPuzzleQuery($conn, "INSERT IGNORE INTO puzzle_results (puzzle_date, user_id) VALUES (?, ?)", "si", $date, $userId);
    $result = LoadDailyResult($conn, $date, $userId);
    if ($daily !== null && $result !== null && intval($result["finished"]) == 0) {
      $best = DailyPuzzleQuery($conn, "SELECT MAX(damage) AS best FROM puzzle_results WHERE puzzle_date = ? AND finished = 1 AND user_id <> ?",
        "si", $date, $userId)[0]["best"] ?? 0;
      $stars = DailyPuzzleStars($daily["mode"], $solved, $damage, intval($result["hints"]), $daily["info"]["bars"] ?? [], $best);
      $solved = $solved ? 1 : 0;
      DailyPuzzleQuery($conn, "UPDATE puzzle_results SET finished = 1, solved = ?, damage = ?, stars = ?, finished_at = NOW()
        WHERE puzzle_date = ? AND user_id = ? AND finished = 0", "iiisi", $solved, $damage, $stars, $date, $userId);
    }
  } catch (Throwable $e) {
    error_log("RecordDailyPuzzleResult failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}

function DailyPuzzleSchedule($conn, $from, $to)
{
  $rows = DailyPuzzleQuery($conn, "SELECT puzzle_date, candidate_id, mode, info FROM puzzle_daily
    WHERE puzzle_date BETWEEN ? AND ? ORDER BY puzzle_date", "ss", $from, $to);
  return array_map(function ($row) use ($conn) {
    $info = json_decode($row["info"], true) ?: [];
    return [
      "date" => $row["puzzle_date"],
      "candidateId" => intval($row["candidate_id"]),
      "mode" => $row["mode"],
      "heroName" => $info["heroName"] ?? "",
      "opponentHeroName" => $info["opponentHeroName"] ?? "",
      "life" => intval($info["life"] ?? 0),
      "provenLife" => isset($info["provenLife"]) ? intval($info["provenLife"]) : null,
      "difficulty" => $info["difficulty"] ?? "",
      "interest" => isset($info["interest"]) ? intval($info["interest"]) : null,
      "auto" => !empty($info["auto"]),
      "bars" => $info["bars"] ?? null,
      "stats" => DailyPuzzleStats($conn, $row["puzzle_date"])
    ];
  }, $rows);
}

// The first day from $from on without a puzzle.
function NextFreeDailyPuzzleDate($conn, $from)
{
  $taken = array_column(DailyPuzzleQuery($conn, "SELECT puzzle_date FROM puzzle_daily WHERE puzzle_date >= ?", "s", $from), "puzzle_date");
  $taken = array_flip($taken);
  $date = new DateTimeImmutable($from, new DateTimeZone("UTC"));
  while (isset($taken[$date->format("Y-m-d")])) $date = $date->modify("+1 day");
  return $date->format("Y-m-d");
}
