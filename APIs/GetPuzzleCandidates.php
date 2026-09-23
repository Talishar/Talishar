<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/PuzzleHarvest.php';
include_once '../GeneratedCode/GeneratedCardDictionaries.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

$response = [
  "total" => 0,
  "candidates" => []
];

$conn = GetDBConnection(DBL_GET_PUZZLE_CANDIDATES);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

try {
  EnsurePuzzleCandidatesTable($conn);
  $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM puzzle_candidates");
  $response["total"] = (int)(mysqli_fetch_assoc($result)["total"] ?? 0);

  $sql = "SELECT id, created_at, format, turn_number, player, hero, opponent_hero, opponent_life, hand_count, opponent_hand_count, status
    FROM puzzle_candidates ORDER BY id DESC LIMIT 300";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $response["candidates"][] = [
      "id" => (int)$row["id"],
      "createdAt" => $row["created_at"],
      "format" => $row["format"],
      "turn" => (int)$row["turn_number"],
      "player" => (int)$row["player"],
      "hero" => $row["hero"],
      "heroName" => GeneratedCardName($row["hero"]),
      "opponentHero" => $row["opponent_hero"],
      "opponentHeroName" => GeneratedCardName($row["opponent_hero"]),
      "opponentLife" => (int)$row["opponent_life"],
      "handCount" => (int)$row["hand_count"],
      "opponentHandCount" => (int)$row["opponent_hand_count"],
      "status" => (int)$row["status"]
    ];
  }
} catch (Throwable $e) {
  error_log("GetPuzzleCandidates failed: " . $e->getMessage());
  http_response_code(500);
  $response["error"] = "Failed to load puzzle candidates";
}

mysqli_close($conn);
echo json_encode($response);
