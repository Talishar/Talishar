<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
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

[$_POST, $userId, $conn] = InitializeAuthenticatedJsonApi(DBL_FRIEND_LIST_API);

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

    $resolvedUser = ResolveOtherUserByUsername($friendUsername, $userId);
    if ($resolvedUser['error'] !== null) {
      $messages = [
        'missing' => "Friend username is required",
        'not_found' => "User not found",
        'self' => "Cannot add yourself as a friend"
      ];
      $statusCode = $resolvedUser['error'] === 'not_found' ? 404 : 400;
      SetJsonError($response, $messages[$resolvedUser['error']], $statusCode);
      break;
    }

    $friend = $resolvedUser['user'];

    // Add friend
    $result = AddFriend($userId, $friend['usersId']);
    if (ApplyActionResult($response, $result)) $response->friend = $friend;
    break;

  case 'removeFriend':
    $friendUserId = $_POST['friendUserId'] ?? '';
    
    if (empty($friendUserId) || !is_numeric($friendUserId)) {
      SetJsonError($response, "Invalid friend user ID");
      break;
    }
    
    $result = RemoveFriend($userId, $friendUserId);
    ApplyActionResult($response, $result);
    break;

  case 'searchUsers':
    $searchTerm = $_POST['searchTerm'] ?? '';
    $limit = $_POST['limit'] ?? 10;
    
    if (empty($searchTerm)) {
      SetJsonError($response, "Search term is required");
      break;
    }
    
    // Validate and sanitize limit
    $limit = min((int)$limit, 50);
    $limit = max($limit, 1);
    
    $users = SearchUsers($searchTerm, $limit);
    
    // Get friends list ONCE instead of per-user filtering
    $userFriends = GetUserFriends($userId);
    $friendIdSet = [];
    foreach ($userFriends as $friend) {
      $friendIdSet[(int)$friend['friendUserId']] = true;
    }
    $currentUserId = (int)$userId;
    
    // Filter out current user and already-friends
    $filteredUsers = array_filter($users, function ($user) use ($currentUserId, $friendIdSet) {
      $candidateId = (int)$user['usersId'];
      return $candidateId !== $currentUserId && !isset($friendIdSet[$candidateId]);
    });
    
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
      SetJsonError($response, "Invalid requester user ID");
      break;
    }
    
    $result = AcceptFriendRequest($userId, $requesterUserId);
    ApplyActionResult($response, $result);
    break;

  case 'rejectRequest':
    $requesterUserId = $_POST['requesterUserId'] ?? '';
    
    if (empty($requesterUserId) || !is_numeric($requesterUserId)) {
      SetJsonError($response, "Invalid requester user ID");
      break;
    }
    
    $result = RejectFriendRequest($userId, $requesterUserId);
    ApplyActionResult($response, $result);
    break;

  case 'getSentRequests':
    $sentRequests = GetSentRequests($userId);
    $response->sentRequests = $sentRequests;
    $response->success = true;
    break;

  case 'cancelRequest':
    $recipientUserId = $_POST['recipientUserId'] ?? '';
    
    if (empty($recipientUserId) || !is_numeric($recipientUserId)) {
      SetJsonError($response, "Invalid recipient user ID");
      break;
    }
    
    $result = CancelFriendRequest($userId, $recipientUserId);
    ApplyActionResult($response, $result);
    break;

  case 'updateNickname':
    $friendUserId = $_POST['friendUserId'] ?? '';
    $nickname = $_POST['nickname'] ?? '';
    
    if (empty($friendUserId) || !is_numeric($friendUserId)) {
      SetJsonError($response, "Invalid friend user ID");
      break;
    }
    
    $result = UpdateFriendNickname($userId, $friendUserId, $nickname);
    ApplyActionResult($response, $result);
    break;

  default:
    SetJsonError($response, "Invalid action");
}

// Apply smart caching headers based on action type
// This reduces unnecessary database hits when clients refetch the same data
switch ($action) {
  case 'getFriends':
  case 'getPendingRequests':
  case 'getSentRequests':
    header('Cache-Control: private, max-age=120');
    break;
  case 'searchUsers':
    // Search results can be cached briefly (60s) since new users don't appear constantly
    header('Cache-Control: private, max-age=60');
    break;
  case 'addFriend':
  case 'removeFriend':
  case 'acceptRequest':
  case 'rejectRequest':
  case 'cancelRequest':
  case 'updateNickname':
    // Mutations should not be cached
    header('Cache-Control: no-cache, no-store, must-revalidate');
    break;
  default:
    header('Cache-Control: no-cache, no-store, must-revalidate');
    break;
}

// Always close connection and return response
if ($conn && $conn !== false) {
  $conn->close();
}

header('Content-Type: application/json');
WriteJsonResponse($response);
exit;
