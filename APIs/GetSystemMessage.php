<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

include_once "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/dbh.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
session_write_close();

header('Content-Type: application/json');

if (!IsUserLoggedIn()) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$userName = LoggedInUserName();

$conn = GetDBConnection(DBL_GET_SYSTEM_MESSAGE);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$sql = "SELECT systemMessage, systemMessageExpiresAt FROM users WHERE usersUid = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
  http_response_code(500);
  echo json_encode(["error" => "Database error"]);
  mysqli_close($conn);
  exit;
}

mysqli_stmt_bind_param($stmt, 's', $userName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$hasMessage = $row && !empty($row['systemMessage']);
$isExpired = $hasMessage && !empty($row['systemMessageExpiresAt'])
  && strtotime($row['systemMessageExpiresAt']) <= time();

if ($isExpired) {
  $clearStmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($clearStmt, "UPDATE users SET systemMessage = NULL, systemMessageExpiresAt = NULL WHERE usersUid = ?")) {
    mysqli_stmt_bind_param($clearStmt, 's', $userName);
    mysqli_stmt_execute($clearStmt);
    mysqli_stmt_close($clearStmt);
  }
}

mysqli_close($conn);

$response = new stdClass();
$response->systemMessage = ($hasMessage && !$isExpired) ? $row['systemMessage'] : null;

echo json_encode($response);

?>
