<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once __DIR__ . '/../AccountFiles/AccountSessionAPI.php';
include_once __DIR__ . '/../includes/functions.inc.php';
include_once __DIR__ . '/../includes/dbh.inc.php';
include_once __DIR__ . '/../Libraries/PuzzleDaily.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();
$userId = IsUserLoggedIn() ? intval(LoggedInUser()) : 0;
session_write_close();
header('Content-Type: application/json');

if ($userId <= 0) ExitJsonResponse(["error" => "Log in to rate the daily puzzle."], 401);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') ExitJsonResponse(["error" => "POST required"], 405);

$rating = intval((ReadJsonBody() ?? [])["rating"] ?? 0);
if ($rating < -1 || $rating > 1) ExitJsonResponse(["error" => "Invalid rating"], 400);

$conn = GetDBConnection(DBL_RATE_DAILY_PUZZLE);
if (!$conn) ExitJsonResponse(["error" => "Database connection failed"], 500);
try {
  EnsureDailyPuzzleTables($conn);
  $date = DailyPuzzleToday();
  $result = LoadDailyResult($conn, $date, $userId);
  if ($result === null || intval($result["finished"]) != 1) {
    mysqli_close($conn);
    ExitJsonResponse(["error" => "Finish today's puzzle before rating it."], 400);
  }
  DailyPuzzleQuery($conn, "UPDATE puzzle_results SET rating = ? WHERE puzzle_date = ? AND user_id = ?", "isi", $rating, $date, $userId);
  mysqli_close($conn);
  echo json_encode(["rating" => $rating]);
} catch (Throwable $e) {
  error_log("RateDailyPuzzle failed: " . $e->getMessage());
  ExitJsonResponse(["error" => "Failed to save your rating"], 500);
}
