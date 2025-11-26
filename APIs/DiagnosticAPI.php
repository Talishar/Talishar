<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../AccountFiles/AccountSessionAPI.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!IsUserLoggedIn()) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$userId = LoggedInUser();
$conn = GetDBConnection();

if (!$conn || $conn === false) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$response = new stdClass();

// Get the action from query string
$action = $_GET['action'] ?? 'status';

switch ($action) {
  case 'status':
    // Check the lastActivity column status
    $stmt = $conn->prepare("SELECT usersId, usersUid, lastActivity FROM users LIMIT 5");
    if ($stmt) {
      $stmt->execute();
      $result = $stmt->get_result();
      $users = [];
      $currentTime = time();
      while ($row = $result->fetch_assoc()) {
        $lastActivityTime = strtotime($row['lastActivity']);
        $timeSinceActivity = $currentTime - $lastActivityTime;
        $users[] = [
          'usersId' => $row['usersId'],
          'usersUid' => $row['usersUid'],
          'lastActivity' => $row['lastActivity'] ?? 'NULL',
          'lastActivityIsNull' => $row['lastActivity'] === null,
          'lastActivityTime' => $lastActivityTime,
          'timeSinceActivity' => $timeSinceActivity,
          'isOnline' => $timeSinceActivity < 60
        ];
      }
      $stmt->close();
      $response->users = $users;
      $response->currentTime = $currentTime;
    }
    
    // Check current user's lastActivity
    $stmt = $conn->prepare("SELECT usersId, usersUid, lastActivity FROM users WHERE usersId = ?");
    if ($stmt) {
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      if ($row) {
        $response->currentUser = [
          'usersId' => $row['usersId'],
          'usersUid' => $row['usersUid'],
          'lastActivity' => $row['lastActivity'] ?? 'NULL'
        ];
      }
      $stmt->close();
    }
    
    // Count how many users have NULL lastActivity
    $stmt = $conn->prepare("SELECT COUNT(*) as nullCount FROM users WHERE lastActivity IS NULL");
    if ($stmt) {
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $response->usersWithNullActivity = $row['nullCount'];
      $stmt->close();
    }
    
    // Count total users
    $stmt = $conn->prepare("SELECT COUNT(*) as totalCount FROM users");
    if ($stmt) {
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $response->totalUsers = $row['totalCount'];
      $stmt->close();
    }
    
    $response->success = true;
    break;

  case 'updateCurrent':
    // Manually update current user's lastActivity for testing
    $result = $conn->query("UPDATE users SET lastActivity = NOW() WHERE usersId = " . intval($userId) . " LIMIT 1");
    if ($result) {
      $response->message = "Updated current user's lastActivity to NOW()";
      $response->success = true;
    } else {
      $response->error = $conn->error;
      $response->success = false;
    }
    break;

  case 'updateAll':
    // Populate NULL values for testing
    $result = $conn->query("UPDATE users SET lastActivity = NOW() WHERE lastActivity IS NULL");
    if ($result) {
      $affectedRows = $conn->affected_rows;
      $response->message = "Updated $affectedRows users with NULL lastActivity to NOW()";
      $response->affectedRows = $affectedRows;
      $response->success = true;
    } else {
      $response->error = $conn->error;
      $response->success = false;
    }
    break;

  default:
    $response->error = "Unknown action";
    $response->success = false;
}

echo json_encode($response);
exit;
