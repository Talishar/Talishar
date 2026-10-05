<?php

// Picks the daily puzzle when nobody scheduled one. Needs the engine loaded (Libraries/PuzzleEngine.php).
include_once __DIR__ . "/PuzzlePlay.php";
include_once __DIR__ . "/PuzzleDaily.php";

const PUZZLE_AUTO_POOL = 500;
const PUZZLE_AUTO_CHECKS = 6;
const PUZZLE_AUTO_LOCK = "talishar_daily_puzzle";
const PUZZLE_AUTO_LOCK_WAIT = 20;

function PuzzleAutoAnalysis($row)
{
  $content = @gzuncompress($row["gamestate"]);
  if ($content === false) return null;
  $proof = CurrentPuzzleProof($row["proof"], $row["kind"]);
  $baseline = CurrentPuzzleBaseline($row["baseline"]);
  $proven = ($proof["status"] ?? "") === "proven";
  $steps = $proven ? json_decode($row["solution"] ?? "", true) : null;
  $analysis = AnalyzePuzzlePosition($content, intval($row["player"]), json_decode($row["meta"] ?? "", true), $proof, $baseline,
    intval($row["kind"]), $steps);
  return [
    "id" => intval($row["id"]),
    "kind" => intval($row["kind"]),
    "checked" => $proof !== null && (!$proven || $baseline !== null),
    "proven" => $proven,
    "filtered" => $analysis["filtered"],
    "interest" => $analysis["rubric"]["percent"],
    "potential" => $analysis["rubric"]["potential"]
  ];
}

function PuzzleAutoRows($conn, $where, $types = "", ...$values)
{
  return DailyPuzzleQuery($conn, "SELECT id, kind, player, meta, proof, baseline, solution, gamestate FROM puzzle_candidates
    WHERE status = " . PUZZLE_CANDIDATE_NEW . " AND winning_line IS NOT NULL $where", $types, ...$values);
}

function PuzzleAutoBetter($a, $b)
{
  if ($b === null) return true;
  if ($a["filtered"] != $b["filtered"]) return !$a["filtered"];
  return $a["interest"] > $b["interest"];
}

// The most interesting proven position among the latest ones. Unchecked positions are checked in order of what they
// could reach, and only while one of them could still beat the best checked one, at most a few per day.
function PickDailyPuzzleCandidate($conn)
{
  $ranked = [];
  foreach (PuzzleAutoRows($conn, "ORDER BY id DESC LIMIT " . PUZZLE_AUTO_POOL) as $row) {
    $entry = PuzzleAutoAnalysis($row);
    if ($entry !== null && (!$entry["checked"] || $entry["proven"])) $ranked[] = $entry;
  }
  $best = null;
  foreach ($ranked as $entry) if ($entry["checked"] && PuzzleAutoBetter($entry, $best)) $best = $entry;

  $unchecked = array_values(array_filter($ranked, fn($entry) => !$entry["checked"]));
  usort($unchecked, fn($a, $b) => $b["potential"] <=> $a["potential"] ?: $b["id"] <=> $a["id"]);
  foreach (array_slice($unchecked, 0, PUZZLE_AUTO_CHECKS) as $entry) {
    if ($best !== null && !$best["filtered"] && $entry["potential"] <= $best["interest"]) break;
    VerifyPuzzleCandidate($conn, $entry["id"]);
    $row = PuzzleAutoRows($conn, "AND id = ?", "i", $entry["id"])[0] ?? null;
    $entry = $row === null ? null : PuzzleAutoAnalysis($row);
    if ($entry !== null && $entry["proven"] && PuzzleAutoBetter($entry, $best)) $best = $entry;
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
