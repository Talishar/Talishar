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

// The engine keeps its state in globals and resolves includes from the game root.
chdir(__DIR__ . "/..");
include_once "WriteLog.php";
include_once "GameLogic.php";
include_once "GameTerms.php";
include_once "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include_once "Libraries/StatFunctions.php";
include_once "Libraries/UILibraries.php";
include_once "Libraries/PlayerSettings.php";
include_once "Libraries/NetworkingLibraries.php";
include_once "Libraries/CacheLibraries.php";
include_once "Libraries/PromptLog.php";
include_once "includes/MetafyHelper.php";
include_once "AI/CombatDummy.php";
include_once "Libraries/HTTPLibraries.php";
include_once "Libraries/ReplayLibraries.php";
require_once "Libraries/CoreLibraries.php";
include_once "APIKeys/APIKeys.php";
include_once "Libraries/ValidationLibraries.php";
include_once "Libraries/PuzzleHarvest.php";
include_once "Libraries/PuzzleVerify.php";

function VerifyPuzzleCandidatesResponse($candidateID)
{
  $conn = GetDBConnection(DBL_VERIFY_PUZZLE_CANDIDATES);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  try {
    EnsurePuzzleCandidatesTable($conn);
    $proof = VerifyPuzzleCandidate($conn, $candidateID);
    if ($proof === null) {
      http_response_code(404);
      return ["error" => "This candidate has no recorded winning line."];
    }
    return ["proof" => $proof];
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
