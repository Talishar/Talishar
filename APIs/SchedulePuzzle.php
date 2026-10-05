<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once __DIR__ . '/../includes/functions.inc.php';
include_once __DIR__ . '/../includes/dbh.inc.php';
include_once __DIR__ . '/../includes/ModeratorList.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

include_once __DIR__ . '/../Libraries/PuzzleEngine.php';
include_once __DIR__ . '/../Libraries/PuzzleAutoSchedule.php';

const PUZZLE_SCHEDULE_PAST_DAYS = 14;
const PUZZLE_SCHEDULE_FUTURE_DAYS = 60;

function PuzzleScheduleWindow($conn)
{
  $today = new DateTimeImmutable(DailyPuzzleToday(), new DateTimeZone("UTC"));
  return [
    "today" => DailyPuzzleToday(),
    "days" => DailyPuzzleSchedule($conn, $today->modify("-" . PUZZLE_SCHEDULE_PAST_DAYS . " days")->format("Y-m-d"),
      $today->modify("+" . PUZZLE_SCHEDULE_FUTURE_DAYS . " days")->format("Y-m-d"))
  ];
}

function SchedulePuzzleResponse($request)
{
  $conn = GetDBConnection(DBL_SCHEDULE_PUZZLE);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  try {
    EnsurePuzzleCandidatesTable($conn);
    EnsureDailyPuzzleTables($conn);
    $action = $_SERVER['REQUEST_METHOD'] === 'POST' ? (string)($request["action"] ?? "") : "list";
    if ($action === "list" && LoadDailyPuzzle($conn, DailyPuzzleToday()) === null) AutoScheduleDailyPuzzle($conn, DailyPuzzleToday());
    if ($action === "schedule") {
      $date = (string)($request["date"] ?? "");
      if ($date === "") $date = NextFreeDailyPuzzleDate($conn, DailyPuzzleToday());
      if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date < DailyPuzzleToday()) {
        return ["error" => "Pick today or a later day."] + PuzzleScheduleWindow($conn);
      }
      if (LoadDailyPuzzle($conn, $date) !== null) {
        return ["error" => "$date already has a puzzle."] + PuzzleScheduleWindow($conn);
      }
      $setup = BuildPuzzleSetup($conn, intval($request["candidateId"] ?? 0), (string)($request["mode"] ?? "lethal"));
      if ($setup === null || !$setup["proven"]) {
        return ["error" => "Only a proven puzzle can be scheduled."] + PuzzleScheduleWindow($conn);
      }
      $info = json_encode($setup);
      DailyPuzzleQuery($conn, "INSERT INTO puzzle_daily (puzzle_date, candidate_id, mode, info) VALUES (?, ?, ?, ?)", "siss",
        $date, $setup["candidateId"], $setup["mode"], $info);
      DailyPuzzleQuery($conn, "UPDATE puzzle_candidates SET status = ? WHERE id = ?", "ii", PUZZLE_CANDIDATE_USED, $setup["candidateId"]);
      return ["scheduled" => $date] + PuzzleScheduleWindow($conn);
    } else if ($action === "remove") {
      $date = (string)($request["date"] ?? "");
      if ($date < DailyPuzzleToday()) {
        return ["error" => "Past puzzles cannot be removed."] + PuzzleScheduleWindow($conn);
      }
      $daily = LoadDailyPuzzle($conn, $date);
      if ($daily !== null) {
        DailyPuzzleQuery($conn, "DELETE FROM puzzle_daily WHERE puzzle_date = ?", "s", $date);
        $stillUsed = DailyPuzzleQuery($conn, "SELECT 1 FROM puzzle_daily WHERE candidate_id = ? LIMIT 1", "i", $daily["candidate_id"]);
        if (count($stillUsed) == 0) {
          $status = empty($daily["info"]["auto"]) ? PUZZLE_CANDIDATE_NEW : PUZZLE_CANDIDATE_REJECTED;
          DailyPuzzleQuery($conn, "UPDATE puzzle_candidates SET status = ? WHERE id = ?", "ii", $status, $daily["candidate_id"]);
        }
      }
    } else if ($action !== "list") {
      http_response_code(400);
      return ["error" => "Unknown action"];
    }
    return PuzzleScheduleWindow($conn);
  } catch (Throwable $e) {
    error_log("SchedulePuzzle failed: " . $e->getMessage());
    http_response_code(500);
    return ["error" => "Failed to update the puzzle schedule"];
  } finally {
    mysqli_close($conn);
  }
}

@set_time_limit(60);
echo json_encode(SchedulePuzzleResponse(ReadJsonBody() ?? []));
