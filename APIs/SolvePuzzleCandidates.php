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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["error" => "POST required"]);
  exit;
}

include_once __DIR__ . '/../Libraries/PuzzleEngine.php';
include_once "Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "Assets/MetafyDictionary.php";
include_once "Libraries/FriendLibraries.php";
include_once "Libraries/SpectatorAccess.php";
include_once "BuildGameState.php";
include_once "BuildPlayerInputPopup.php";
include_once "Libraries/PuzzleSolver.php";

const PUZZLE_SOLVE_BUDGET = 45;
const PUZZLE_SOLVE_PER_CANDIDATE = 40;
const PUZZLE_SOLVE_MIN_LEFT = 5;
const PUZZLE_SOLVE_POOL = 500;

// Unsolved new candidates, newest first.
function PuzzleUnsolvedCandidates($conn)
{
  $result = mysqli_query($conn, "SELECT id, solve FROM puzzle_candidates WHERE status = " . PUZZLE_CANDIDATE_NEW .
    " ORDER BY id DESC LIMIT " . PUZZLE_SOLVE_POOL);
  $ids = [];
  while ($result && ($row = mysqli_fetch_assoc($result))) {
    if (CurrentPuzzleSolve($row["solve"]) === null) $ids[] = intval($row["id"]);
  }
  return $ids;
}

// One candidate when $candidateID is set, otherwise the next unsolved ones until the time budget runs out.
function SolvePuzzleCandidatesResponse($candidateID)
{
  $conn = GetDBConnection(DBL_SOLVE_PUZZLE_CANDIDATES);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  try {
    EnsurePuzzleCandidatesTable($conn);
    $deadline = microtime(true) + PUZZLE_SOLVE_BUDGET;
    $queue = $candidateID > 0 ? [$candidateID] : PuzzleUnsolvedCandidates($conn);
    $solved = [];
    foreach ($queue as $id) {
      $left = $deadline - microtime(true);
      if ($left < PUZZLE_SOLVE_MIN_LEFT && count($solved) > 0) break;
      $solve = SolvePuzzleCandidate($conn, $id, max(PUZZLE_SOLVE_MIN_LEFT, min(PUZZLE_SOLVE_PER_CANDIDATE, $left)));
      if ($solve === null) continue;
      unset($solve["steps"]);
      $solved[] = ["id" => $id] + $solve;
    }
    return ["solved" => $solved, "remaining" => $candidateID > 0 ? null : count(PuzzleUnsolvedCandidates($conn))];
  } catch (Throwable $e) {
    error_log("SolvePuzzleCandidates failed: " . $e->getMessage());
    http_response_code(500);
    return ["error" => "Failed to solve puzzle candidates"];
  } finally {
    mysqli_close($conn);
  }
}

@set_time_limit(120);
@ini_set("memory_limit", "512M");
echo json_encode(SolvePuzzleCandidatesResponse(intval((ReadJsonBody() ?? [])["candidateId"] ?? 0)));
