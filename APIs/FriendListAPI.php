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
$conn = GetDBConnection();

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$action = $_POST['action'] ?? '';
$response = new stdClass();

switch ($action) {
  case 'getFriends':
    $friends = GetUserFriends($userId);
    $response->friends = $friends;
    $response->success = true;
    break;

  case 'addFriend':
    $friendUsername = $_POST['friendUsername'] ?? '';
    
    if (empty($friendUsername)) {
      http_response_code(400);
      $response->error = "Friend username is required";
      break;
    }
    
    // Find friend by username
    $friend = FindUserByUsername($friendUsername);
    if (!$friend) {
      http_response_code(404);
      $response->error = "User not found";
      break;
    }
    
    // Don't allow adding yourself
    if ($friend['usersId'] == $userId) {
      http_response_code(400);
      $response->error = "Cannot add yourself as a friend";
      break;
    }
    
    // Add friend
    $result = AddFriend($userId, $friend['usersId']);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
      $response->friend = $friend;
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  case 'removeFriend':
    $friendUserId = $_POST['friendUserId'] ?? '';
    
    if (empty($friendUserId) || !is_numeric($friendUserId)) {
      http_response_code(400);
      $response->error = "Invalid friend user ID";
      break;
    }
    
    $result = RemoveFriend($userId, $friendUserId);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  case 'searchUsers':
    $searchTerm = $_POST['searchTerm'] ?? '';
    $limit = $_POST['limit'] ?? 10;
    
    if (empty($searchTerm)) {
      http_response_code(400);
      $response->error = "Search term is required";
      break;
    }
    
    $users = SearchUsers($searchTerm, $limit);
    
    // Filter out current user and already-friends
    $userFriends = GetUserFriends($userId);
    $friendIds = array_map(fn($f) => $f['friendUserId'], $userFriends);
    
    $filteredUsers = array_filter($users, fn($user) => 
      $user['usersId'] != $userId && !in_array($user['usersId'], $friendIds)
    );
    
    $response->users = array_values($filteredUsers);
    $response->success = true;
    break;

  case 'getPendingRequests':
    $requests = GetPendingRequests($userId);
    $response->requests = $requests;
    $response->success = true;
    break;

  case 'acceptRequest':
    $requesterUserId = $_POST['requesterUserId'] ?? '';
    
    if (empty($requesterUserId) || !is_numeric($requesterUserId)) {
      http_response_code(400);
      $response->error = "Invalid requester user ID";
      break;
    }
    
    $result = AcceptFriendRequest($userId, $requesterUserId);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  case 'rejectRequest':
    $requesterUserId = $_POST['requesterUserId'] ?? '';
    
    if (empty($requesterUserId) || !is_numeric($requesterUserId)) {
      http_response_code(400);
      $response->error = "Invalid requester user ID";
      break;
    }
    
    $result = RejectFriendRequest($userId, $requesterUserId);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  case 'getSentRequests':
    $sentRequests = GetSentRequests($userId);
    $response->sentRequests = $sentRequests;
    $response->success = true;
    break;

  case 'cancelRequest':
    $recipientUserId = $_POST['recipientUserId'] ?? '';
    
    if (empty($recipientUserId) || !is_numeric($recipientUserId)) {
      http_response_code(400);
      $response->error = "Invalid recipient user ID";
      break;
    }
    
    $result = CancelFriendRequest($userId, $recipientUserId);
    if ($result['success']) {
      $response->success = true;
      $response->message = $result['message'];
    } else {
      http_response_code(400);
      $response->error = $result['message'];
    }
    break;

  case 'updateNickname':
    $friendUserId = $_POST['friendUserId'] ?? '';
    $nickname = $_POST['nickname'] ?? '';
    
    if (empty($friendUserId) || !is_numeric($friendUserId)) {
      http_response_code(400);
      $response->error = "Invalid friend user ID";
      break;
    }
    
    $result = UpdateFriendNickname($userId, $friendUserId, $nickname);
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
