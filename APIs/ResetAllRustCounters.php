<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../includes/ModeratorList.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  header('Allow: POST');
  exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION["useruid"])) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

if (!IsUserModerator($_SESSION["useruid"])) {
  http_response_code(403);
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

$conn = GetDBConnection(DBL_RESET_ALL_RUST_COUNTERS);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$sql = "UPDATE users
        SET rust_counters = 0, rust_counters_last_played = NULL
        WHERE rust_counters <> 0";

try {
  mysqli_query($conn, $sql);
  $usersReset = mysqli_affected_rows($conn);
  $_SESSION["rust_counters"] = 0;
  echo json_encode([
    "success" => true,
    "usersReset" => $usersReset,
    "message" => "Rust counters reset for $usersReset users"
  ]);
} catch (mysqli_sql_exception $e) {
  error_log("ResetAllRustCounters failed: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(["error" => "Failed to reset rust counters"]);
}

mysqli_close($conn);
exit;
