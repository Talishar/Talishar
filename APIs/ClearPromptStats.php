<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/PromptLog.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  header('Allow: POST');
  echo json_encode(["error" => "POST required"]);
  exit;
}

$conn = GetDBConnection(DBL_CLEAR_PROMPT_STATS);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

try {
  EnsurePromptStatsTable($conn);
  $result = mysqli_query($conn, "SELECT COALESCE(SUM(count), 0) AS answers FROM prompt_stats");
  $answersCleared = (int)(mysqli_fetch_assoc($result)["answers"] ?? 0);
  mysqli_query($conn, "DELETE FROM prompt_stats");
  echo json_encode(["success" => true, "answersCleared" => $answersCleared]);
} catch (Throwable $e) {
  error_log("ClearPromptStats failed: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(["error" => "Failed to clear prompt stats"]);
}

mysqli_close($conn);
