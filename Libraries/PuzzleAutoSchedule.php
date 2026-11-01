<?php

// Picks the daily puzzle when nobody scheduled one. Needs the engine loaded (Libraries/PuzzleEngine.php).
include_once __DIR__ . "/PuzzlePlay.php";
include_once __DIR__ . "/PuzzleDaily.php";
include_once __DIR__ . "/PuzzleSolver.php";

const PUZZLE_AUTO_POOL = 500;
const PUZZLE_AUTO_MIN_INTEREST = 65;
const PUZZLE_AUTO_LOCK = "talishar_daily_puzzle";
const PUZZLE_AUTO_LOCK_WAIT = 20;

function PuzzleAutoAnalysis($row)
{
  $content = @gzuncompress($row["gamestate"]);
  $solve = CurrentPuzzleSolve($row["solve"]);
  if ($content === false || ($solve["status"] ?? "") !== "sound") return null;
  $analysis = PuzzleApplySolve(AnalyzePuzzlePosition($content, intval($row["player"]), json_decode($row["meta"] ?? "", true),
    CurrentPuzzleProof($row["proof"], $row["kind"]), CurrentPuzzleBaseline($row["baseline"]), intval($row["kind"]), $solve["steps"]), $solve);
  return [
    "id" => intval($row["id"]),
    "kind" => intval($row["kind"]),
    "filtered" => $analysis["filtered"],
    "interest" => $analysis["rubric"]["percent"]
  ];
}

// Only positions the solver proved, above the quality floor, can fill a day. Solving happens from the mod page, so a day
// with nothing solved stays empty rather than getting an unchecked position.
function PickDailyPuzzleCandidate($conn)
{
  EnsurePuzzleCandidatesTable($conn);
  $best = null;
  $rows = DailyPuzzleQuery($conn, "SELECT id, kind, player, meta, proof, baseline, solve, gamestate FROM puzzle_candidates
    WHERE status = " . PUZZLE_CANDIDATE_NEW . " AND solve IS NOT NULL ORDER BY id DESC LIMIT " . PUZZLE_AUTO_POOL);
  foreach ($rows as $row) {
    $entry = PuzzleAutoAnalysis($row);
    if ($entry === null || $entry["filtered"] || $entry["interest"] < PUZZLE_AUTO_MIN_INTEREST) continue;
    if ($best === null || $entry["interest"] > $best["interest"]) $best = $entry;
  }
  return $best;
}

// Fills $date with the best candidate unless it already has a puzzle. Concurrent requests wait for the first one.
function AutoScheduleDailyPuzzle($conn, $date)
{
  $locked = DailyPuzzleQuery($conn, "SELECT GET_LOCK(?, ?) AS locked", "si", PUZZLE_AUTO_LOCK, PUZZLE_AUTO_LOCK_WAIT);
  if (intval($locked[0]["locked"] ?? 0) != 1) return null;
  try {
    if (LoadDailyPuzzle($conn, $date) !== null) return null;
    $pick = PickDailyPuzzleCandidate($conn);
    if ($pick === null) return null;
    $mode = $pick["kind"] == PUZZLE_KIND_SURVIVE ? "survive" : "lethal";
    $setup = BuildPuzzleSetup($conn, $pick["id"], $mode);
    if ($setup === null || !$setup["proven"]) return null;
    $setup["auto"] = true;
    DailyPuzzleQuery($conn, "INSERT IGNORE INTO puzzle_daily (puzzle_date, candidate_id, mode, info) VALUES (?, ?, ?, ?)", "siss",
      $date, $setup["candidateId"], $setup["mode"], json_encode($setup));
    DailyPuzzleQuery($conn, "UPDATE puzzle_candidates SET status = ? WHERE id = ?", "ii", PUZZLE_CANDIDATE_USED, $setup["candidateId"]);
    return $setup;
  } finally {
    DailyPuzzleQuery($conn, "SELECT RELEASE_LOCK(?) AS released", "s", PUZZLE_AUTO_LOCK);
  }
}
