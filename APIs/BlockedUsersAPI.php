<?php

ob_start();
include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
ob_end_clean();
SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../Libraries/BlockedUserLibraries.php";
include_once "../Libraries/FriendLibraries.php";

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

$_POST = json_decode(file_get_contents('php://input'), true);
if (!$_POST) {
  $_POST = [];
}

$userId = LoggedInUser();
global $conn;
$conn = GetDBConnection();

if (!$conn || $conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$action = $_POST['action'] ?? '';
$response = new stdClass();

switch ($action) {
  case 'getBlockedUsers':
    $blockedUsers = GetBlockedUsers($userId);
    $response->blockedUsers = $blockedUsers;
    $response->success = true;
    break;

  case 'blockUser':
    $blockedUsername = $_POST['blockedUsername'] ?? '';
    
    if (empty($blockedUsername)) {
      http_response_code(400);
      $response->error = "Username is required";
      break;
    }
    
    // Find user by username
    $blockedUser = FindUserByUsername($blockedUsername);
    if (!$blockedUser) {
      http_response_code(404);
      $response->error = "User not found";
      break;
    }
    
    // Don't allow blocking yourself
    if ($blockedUser['usersId'] == $userId) {
      http_response_code(400);
      $response->error = "Cannot block yourself";
      break;
    }
    
    // Block user
    $result = BlockUser($userId, $blockedUser['usersId']);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
      $response->blockedUser = $blockedUser;
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  case 'unblockUser':
    $blockedUserId = $_POST['blockedUserId'] ?? '';
    
    if (empty($blockedUserId) || !is_numeric($blockedUserId)) {
      http_response_code(400);
      $response->error = "Invalid blocked user ID";
      break;
    }
    
    $result = UnblockUser($userId, $blockedUserId);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  default:
    http_response_code(400);
    $response->error = "Invalid action";
}

echo json_encode($response);
exit;
