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

function VerifyPuzzleCandidatesResponse($candidateID)
{
  $conn = GetDBConnection(DBL_VERIFY_PUZZLE_CANDIDATES);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  try {
    EnsurePuzzleCandidatesTable($conn);
    $verified = VerifyPuzzleCandidate($conn, $candidateID);
    if ($verified === null) {
      http_response_code(404);
      return ["error" => "This candidate has no recorded winning line."];
    }
    return $verified;
  } catch (Throwable $e) {
    error_log("VerifyPuzzleCandidates failed: " . $e->getMessage());
    http_response_code(500);
    return ["error" => "Failed to verify the puzzle candidate"];
  } finally {
    mysqli_close($conn);
  }
}

@set_time_limit(60);
echo json_encode(VerifyPuzzleCandidatesResponse(intval((ReadJsonBody() ?? [])["candidateId"] ?? 0)));
