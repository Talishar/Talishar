<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
session_write_close();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

header('Content-Type: application/json');

if (!isset($_SESSION["useruid"])) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$useruid = $_SESSION["useruid"];

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
  http_response_code(400);
  echo json_encode(["error" => "Missing action"]);
  exit;
}

$action = $input['action'];

// Non-moderators can only acknowledge their own messages
if (!IsUserModerator($useruid) && $action !== 'acknowledge') {
  http_response_code(403);
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

$conn = GetDBConnection(DBL_SYSTEM_MESSAGE_API);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

switch ($action) {
  case 'sendToPlayer':
    handleSendToPlayer($conn, $input);
    break;
  case 'sendToAll':
    handleSendToAll($conn, $input);
    break;
  case 'acknowledge':
    handleAcknowledge($conn, $useruid);
    break;
  default:
    http_response_code(400);
    echo json_encode(["error" => "Invalid action"]);
    break;
}

mysqli_close($conn);
exit;

function handleSendToPlayer($conn, $input) {
  if (!isset($input['username']) || !isset($input['message'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing username or message"]);
    return;
  }

  $username = trim($input['username']);
  $message = trim($input['message']);

  if (empty($username) || empty($message)) {
    http_response_code(400);
    echo json_encode(["error" => "Username and message cannot be empty"]);
    return;
  }

  if (strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode(["error" => "Message too long (max 2000 characters)"]);
    return;
  }

  $expiresAt = ComputeSystemMessageExpiry($input);

  $sql = "UPDATE users SET systemMessage = ?, systemMessageExpiresAt = ? WHERE usersUid = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
    return;
  }

  mysqli_stmt_bind_param($stmt, "sss", $message, $expiresAt, $username);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt) === 0) {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
    mysqli_stmt_close($stmt);
    return;
  }

  mysqli_stmt_close($stmt);
  echo json_encode(["success" => true, "message" => "System message sent to $username"]);
}

function handleAcknowledge($conn, $useruid) {
  $sql = "UPDATE users SET systemMessage = NULL, systemMessageExpiresAt = NULL WHERE usersUid = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
    return;
  }

  mysqli_stmt_bind_param($stmt, "s", $useruid);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  echo json_encode(["success" => true]);
}

function handleSendToAll($conn, $input) {
  if (!isset($input['message'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing message"]);
    return;
  }

  $message = trim($input['message']);

  if (empty($message)) {
    http_response_code(400);
    echo json_encode(["error" => "Message cannot be empty"]);
    return;
  }

  if (strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode(["error" => "Message too long (max 2000 characters)"]);
    return;
  }

  $expiresAt = ComputeSystemMessageExpiry($input);

  $sql = "UPDATE users SET systemMessage = ?, systemMessageExpiresAt = ? WHERE isBanned = 0";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
    return;
  }

  mysqli_stmt_bind_param($stmt, "ss", $message, $expiresAt);
  mysqli_stmt_execute($stmt);
  $affected = mysqli_stmt_affected_rows($stmt);
  mysqli_stmt_close($stmt);

  echo json_encode(["success" => true, "message" => "System message sent to $affected users"]);
}

// Allowed expiry windows, in hours. Absent/invalid/non-positive values mean "never expires".
function ComputeSystemMessageExpiry($input) {
  $allowedHours = [1, 6, 24, 72, 168];

  if (!isset($input['expiresInHours'])) {
    return null;
  }

  $hours = $input['expiresInHours'];

  if (!is_numeric($hours) || !in_array((int)$hours, $allowedHours, true)) {
    return null;
  }

  return date('Y-m-d H:i:s', time() + ((int)$hours * 3600));
}

?>
