<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once __DIR__ . '/../includes/functions.inc.php';
include_once __DIR__ . '/../includes/dbh.inc.php';
include_once __DIR__ . '/../includes/ModeratorList.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

$useruid = RequireModeratorSession();
session_write_close();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["error" => "POST required"]);
  exit;
}

include_once __DIR__ . '/../Libraries/PuzzleEngine.php';

// A moderator test game: unchecked candidates are checked first so the game starts at the proven life.
function CreatePuzzleGameResponse($candidateID, $mode, $useruid)
{
  $conn = GetDBConnection(DBL_CREATE_PUZZLE_GAME);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  $setup = null;
  $row = null;
  try {
    EnsurePuzzleCandidatesTable($conn);
    $setup = BuildPuzzleSetup($conn, $candidateID, $mode);
    $row = $setup === null ? null : LoadPuzzleCandidate($conn, $candidateID);
  } catch (Throwable $e) {
    error_log("CreatePuzzleGame failed: " . $e->getMessage());
  } finally {
    mysqli_close($conn);
  }
  if ($setup === null || $row === null) {
    http_response_code(404);
    return ["error" => "Puzzle candidate not found"];
  }
  $result = CreatePuzzleGameFromSetup($setup, $row, $useruid, 0);
  if (isset($result["error"])) http_response_code(500);
  return $result;
}

@set_time_limit(60);
$request = ReadJsonBody() ?? [];
echo json_encode(CreatePuzzleGameResponse(intval($request["candidateId"] ?? 0), (string)($request["mode"] ?? "lethal"), $useruid));
