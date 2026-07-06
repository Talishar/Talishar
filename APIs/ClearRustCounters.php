<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

$response = new stdClass();

session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (!isset($_SESSION["userid"])) {
  $response->success = false;
  $response->message = "You must be logged in to clear rust counters.";
  echo json_encode($response);
  exit;
}

$userID = $_SESSION["userid"];

$conn = GetDBConnection(DBL_CLEAR_RUST_COUNTERS);
if (!$conn) {
  $response->success = false;
  $response->message = "Database connection failed.";
  echo json_encode($response);
  exit;
}

$sql = "UPDATE users SET rust_counters = 0 WHERE usersId=?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "s", $userID);
  if (mysqli_stmt_execute($stmt)) {
    $response->success = true;
    $response->rustCounters = 0;
  } else {
    $response->success = false;
    $response->message = "Failed to clear rust counters.";
  }
  mysqli_stmt_close($stmt);
} else {
  $response->success = false;
  $response->message = "Database query failed.";
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($response);
exit;
