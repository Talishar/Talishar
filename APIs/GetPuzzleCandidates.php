<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/PuzzleHarvest.php';
include_once '../Libraries/PuzzleAnalysis.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

$emptyOpponentHand = ($_GET["emptyOpponentHand"] ?? "0") === "1";
$raiseLife = ($_GET["raiseLife"] ?? "0") === "1";

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

  $sql = "SELECT id, created_at, format, turn_number, player, hero, opponent_hero, status, meta, gamestate
    FROM puzzle_candidates ORDER BY id DESC LIMIT 300";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $content = @gzuncompress($row["gamestate"]);
    if ($content === false) continue;
    $meta = json_decode($row["meta"] ?? "", true);
    $response["candidates"][] = [
      "id" => (int)$row["id"],
      "createdAt" => $row["created_at"],
      "format" => $row["format"],
      "turn" => (int)$row["turn_number"],
      "hero" => $row["hero"],
      "heroName" => GeneratedCardName($row["hero"]),
      "opponentHero" => $row["opponent_hero"],
      "opponentHeroName" => GeneratedCardName($row["opponent_hero"]),
      "status" => (int)$row["status"]
    ] + AnalyzePuzzlePosition($content, (int)$row["player"], $meta, $emptyOpponentHand, $raiseLife);
  }
} catch (Throwable $e) {
  error_log("GetPuzzleCandidates failed: " . $e->getMessage());
  http_response_code(500);
  $response["error"] = "Failed to load puzzle candidates";
}

mysqli_close($conn);
echo json_encode($response);
