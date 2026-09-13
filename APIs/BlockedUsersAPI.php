<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";

// Set headers BEFORE any output to ensure CORS headers are sent
SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  ExitJsonResponse(["error" => "Method not allowed. Use POST."], 405);
}

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../Libraries/BlockedUserLibraries.php";
include_once "../Libraries/FriendLibraries.php";

[$_POST, $userId, $conn] = InitializeAuthenticatedJsonApi(DBL_BLOCKED_USERS_API);

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

    $resolvedUser = ResolveOtherUserByUsername($blockedUsername, $userId);
    if ($resolvedUser['error'] !== null) {
      $messages = [
        'missing' => "Username is required",
        'not_found' => "User not found",
        'self' => "Cannot block yourself"
      ];
      $statusCode = $resolvedUser['error'] === 'not_found' ? 404 : 400;
      SetJsonError($response, $messages[$resolvedUser['error']], $statusCode);
      break;
    }

    $blockedUser = $resolvedUser['user'];

    // Block user
    $result = BlockUser($userId, $blockedUser['usersId']);
    if (ApplyActionResult($response, $result)) $response->blockedUser = $blockedUser;
    break;

  case 'unblockUser':
    $blockedUserId = $_POST['blockedUserId'] ?? '';
    
    if (empty($blockedUserId) || !is_numeric($blockedUserId)) {
      SetJsonError($response, "Invalid blocked user ID");
      break;
    }
    
    $result = UnblockUser($userId, $blockedUserId);
    ApplyActionResult($response, $result);
    break;

  default:
    SetJsonError($response, "Invalid action");
}

// Apply smart caching headers based on action type
// This reduces unnecessary database hits when clients refetch blocked users
switch ($action) {
  case 'getBlockedUsers':
    // Reduces database load from repeated refetches
    header('Cache-Control: private, max-age=120');
    break;
  case 'blockUser':
  case 'unblockUser':
    header('Cache-Control: no-cache, no-store, must-revalidate');
    break;
  default:
    header('Cache-Control: no-cache, no-store, must-revalidate');
    break;
}

WriteJsonResponse($response);
exit;
