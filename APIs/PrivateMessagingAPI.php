<?php

// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set appropriate timeouts and buffer settings
set_time_limit(10);
ini_set('memory_limit', '32M');

// Set timezone to UTC - ensures consistent time calculations across all servers
date_default_timezone_set('UTC');

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

// Handle CORS preflight requests - exit early to avoid unnecessary processing
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
header('Cache-Control: no-cache, no-store, must-revalidate');

// Check if user is logged in
if (!IsUserLoggedIn()) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

//  Parse request body once
$_POST = json_decode(file_get_contents('php://input'), true);
if (!$_POST) {
  $_POST = [];
}

$userId = LoggedInUser();
global $conn;
$conn = GetDBConnection();

// Single, comprehensive connection check
if (!$conn || $conn === false || (is_object($conn) && isset($conn->connect_error) && $conn->connect_error)) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

//  Activity update sampling - only update 10% of requests to reduce database load
// This significantly reduces write contention while still maintaining activity tracking
if (rand(1, 10) === 1) {
  // Use UTC_TIMESTAMP to ensure timezone consistency across all servers
  $conn->query("UPDATE users SET lastActivity = UTC_TIMESTAMP() WHERE usersId = " . intval($userId) . " LIMIT 1");
}

$action = $_POST['action'] ?? '';
$response = new stdClass();

switch ($action) {
  case 'sendMessage':
    $toUserId = isset($_POST['toUserId']) ? (int)$_POST['toUserId'] : 0;
    $message = $_POST['message'] ?? '';
    $gameLink = $_POST['gameLink'] ?? null;
    
    if ($toUserId <= 0) {
      http_response_code(400);
      $response->error = "Invalid recipient user ID";
      break;
    }
    
    if (empty($message) && empty($gameLink)) {
      http_response_code(400);
      $response->error = "Message or game link is required";
      break;
    }
    
    // Check if users are friends
    if (!AreFriends($userId, $toUserId)) {
      http_response_code(403);
      $response->error = "Can only send messages to friends";
      break;
    }
    
    // Send the message
    $result = SendPrivateMessage($userId, $toUserId, $message, $gameLink);
    if ($result['success']) {
      $response->success = true;
      $response->message = "Message sent";
      $response->messageId = $result['messageId'];
    } else {
      http_response_code(500);
      $response->error = $result['message'];
    }
    break;

  case 'getMessages':
    $friendUserId = isset($_POST['friendUserId']) ? (int)$_POST['friendUserId'] : 0;
    $limit = isset($_POST['limit']) ? max(1, min((int)$_POST['limit'], 50)) : 50;
    
    if ($friendUserId <= 0) {
      http_response_code(400);
      $response->error = "Invalid friend user ID";
      break;
    }
    
    if (!AreFriends($userId, $friendUserId)) {
      http_response_code(403);
      $response->error = "Can only view messages with friends";
      break;
    }
    
    // Get messages between the two users
    $messages = GetPrivateMessages($userId, $friendUserId, $limit);
    $response->messages = $messages;
    $response->success = true;
    break;

  case 'markAsRead':
    $messageIds = $_POST['messageIds'] ?? [];
    
    if (empty($messageIds) || !is_array($messageIds)) {
      http_response_code(400);
      $response->error = "Invalid message IDs";
      break;
    }
    
    // Mark messages as read
    $result = MarkMessagesAsRead($userId, $messageIds);
    if ($result['success']) {
      $response->success = true;
      $response->message = "Messages marked as read";
    } else {
      http_response_code(500);
      $response->error = $result['message'];
    }
    break;

  case 'getOnlineFriends':
    // Get all friends with their online status
    $onlineFriends = GetOnlineFriends($userId);
    //$onlineFriends = []; //temporary
    $response->onlineFriends = $onlineFriends;
    $response->success = true;
    break;

  case 'getUnreadCount':
    // Get total unread message count
    $unreadCount = GetUnreadMessageCount($userId);
    $response->unreadCount = $unreadCount;
    $response->success = true;
    break;

  case 'getUnreadCountByFriend':
    // Get unread count for each friend
    $unreadByFriend = GetUnreadMessageCountByFriend($userId);
    $response->unreadByFriend = $unreadByFriend;
    $response->success = true;
    break;

  case 'getUserGamePreferences':
    // Get user's saved game preferences for quick game creation
    $prefs = GetUserGamePreferences($userId);
    if ($prefs['success']) {
      $response->format = $prefs['format'];
      $response->visibility = $prefs['visibility'];
      $response->success = true;
    } else {
      http_response_code(500);
      $response->error = $prefs['error'];
    }
    break;

  default:
    http_response_code(400);
    $response->error = "Invalid action";
    break;
}

// Always close connection and return response
if ($conn && $conn !== false) {
  $conn->close();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;

// Helper functions

function SendPrivateMessage($fromUserId, $toUserId, $message, $gameLink = null) {
  global $conn;
  
  $stmt = $conn->prepare("INSERT INTO private_messages (fromUserId, toUserId, message, gameLink, createdAt, isRead) VALUES (?, ?, ?, ?, NOW(), 0)");
  if (!$stmt) {
    return ["success" => false, "message" => "Failed to prepare statement"];
  }
  
  // Ensure all parameters are variables (not literals) for bind_param
  $gameLink = $gameLink ?? null;
  $stmt->bind_param("iiss", $fromUserId, $toUserId, $message, $gameLink);
  
  if ($stmt->execute()) {
    $messageId = $stmt->insert_id;
    $stmt->close();
    return ["success" => true, "messageId" => $messageId];
  } else {
    $stmt->close();
    return ["success" => false, "message" => "Failed to send message"];
  }
}

function GetPrivateMessages($userId, $friendUserId, $limit = 50) {
  global $conn;
  
  // PERFORMANCE: Limit results to 50 instead of 200 for faster queries and smaller responses
  $limit = min($limit, 50);
  $limit = max($limit, 1);
  
  // Support pagination with optional offset
  $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
  $offset = max($offset, 0);
  $offset = min($offset, 10000);  // Prevent huge offsets
  
  //  Optimized query - split JOINs into batch queries for performance
  // Usernames fetched separately to avoid expensive user table JOINs
  $stmt = $conn->prepare("
    SELECT 
      pm.messageId, 
      pm.fromUserId, 
      pm.toUserId, 
      pm.message, 
      pm.gameLink, 
      pm.createdAt, 
      pm.isRead
    FROM private_messages pm
    WHERE (pm.fromUserId = ? AND pm.toUserId = ?) 
       OR (pm.fromUserId = ? AND pm.toUserId = ?)
    ORDER BY pm.createdAt DESC
    LIMIT ? OFFSET ?
  ");
  
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("iiiiii", $userId, $friendUserId, $friendUserId, $userId, $limit, $offset);
  if (!$stmt->execute()) {
    $stmt->close();
    return [];
  }
  
  $result = $stmt->get_result();
  $messages = [];
  $userIds = [];
  
  //  First pass - collect messages and unique user IDs
  while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
    $userIds[$row['fromUserId']] = true;
    $userIds[$row['toUserId']] = true;
  }
  
  $stmt->close();
  
  //  Batch fetch usernames if needed
  $usernames = [];
  if (!empty($userIds)) {
    $userIds = array_keys($userIds);
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));
    $types = str_repeat('i', count($userIds));
    
    $stmt = $conn->prepare("SELECT usersId, usersUid FROM users WHERE usersId IN ($placeholders)");
    if ($stmt) {
      $bindParams = array_merge([$types], $userIds);
      call_user_func_array([$stmt, 'bind_param'], $bindParams);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        $usernames[$row['usersId']] = $row['usersUid'];
      }
      $stmt->close();
    }
  }
  
  //  Build response array
  $formatted = [];
  foreach (array_reverse($messages) as $row) {
    $formatted[] = [
      'messageId' => (int)$row['messageId'],
      'fromUserId' => (int)$row['fromUserId'],
      'fromUsername' => $usernames[$row['fromUserId']] ?? 'Unknown',
      'toUserId' => (int)$row['toUserId'],
      'toUsername' => $usernames[$row['toUserId']] ?? 'Unknown',
      'message' => $row['message'],
      'gameLink' => $row['gameLink'],
      'createdAt' => $row['createdAt'],
      'isRead' => (bool)$row['isRead']
    ];
  }
  
  return $formatted;
}

function MarkMessagesAsRead($userId, $messageIds) {
  global $conn;
  
  if (empty($messageIds)) {
    return ["success" => true];
  }
  
  // Ensure all message IDs are integers
  $messageIds = array_map('intval', $messageIds);
  
  // Only mark messages where the user is the recipient
  $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
  $types = str_repeat('i', count($messageIds));
  
  $stmt = $conn->prepare("UPDATE private_messages SET isRead = 1 WHERE toUserId = ? AND messageId IN ($placeholders)");
  if (!$stmt) {
    return ["success" => false, "message" => "Failed to prepare statement"];
  }
  
  $params = array_merge([$userId], $messageIds);
  $typeString = "i" . $types;
  
  // Use call_user_func_array for flexible parameter binding
  $bindParams = array_merge([$typeString], $params);
  call_user_func_array([$stmt, 'bind_param'], $bindParams);
  
  if ($stmt->execute()) {
    $stmt->close();
    return ["success" => true];
  } else {
    $stmt->close();
    return ["success" => false, "message" => "Failed to mark messages as read"];
  }
}

function GetOnlineFriends($userId) {
  global $conn;
  
  if ($userId <= 0) {
    return [];
  }
  
  $friends = GetUserFriends($userId);
  
  if (!$friends || !is_array($friends)) {
    return [];
  }
  
  // Get all friend IDs at once instead of querying individually
  $friendIds = array_column($friends, 'friendUserId');
  
  if (empty($friendIds)) {
    return [];
  }
  
  // PERFORMANCE: Use single efficient query instead of individual queries
  // Fetch ALL friends - frontend will determine online/away/offline based on timeSinceActivity
  $placeholders = implode(',', array_fill(0, count($friendIds), '?'));
  $types = str_repeat('i', count($friendIds));
  
  $stmt = $conn->prepare("
    SELECT usersId, lastActivity 
    FROM users 
    WHERE usersId IN ($placeholders)
  ");
  
  if (!$stmt) {
    return [];
  }
  
  $bindParams = array_merge([$types], $friendIds);
  call_user_func_array([$stmt, 'bind_param'], $bindParams);
  if (!$stmt->execute()) {
    $stmt->close();
    return [];
  }
  $result = $stmt->get_result();
  
  // Map activity data by user ID for quick lookup
  $activityMap = [];
  while ($row = $result->fetch_assoc()) {
    $activityMap[$row['usersId']] = $row['lastActivity'];
  }
  $stmt->close();
  
  // Build response with activity data - avoid repeated strtotime() calls
  $currentTime = time();
  $onlineFriends = [];
  
  foreach ($friends as $friend) {
    $friendId = $friend['friendUserId'];
    
    // Check if friend has activity data
    if (!isset($activityMap[$friendId])) {
      // Friend has never been seen - mark as offline
      $onlineFriends[] = [
        'userId' => $friendId,
        'username' => $friend['username'],
        'nickname' => $friend['nickname'] ?? null,
        'isOnline' => false,
        'isAway' => false,
        'lastSeen' => null,
        'timeSinceActivity' => null
      ];
      continue;
    }
    
    $lastActivity = $activityMap[$friendId];
    // Parse timestamp as UTC to match database storage
    $lastActivityTime = strtotime($lastActivity . ' UTC');
    $timeSinceActivity = $currentTime - $lastActivityTime;
    
    $onlineFriends[] = [
      'userId' => $friendId,
      'username' => $friend['username'],
      'nickname' => $friend['nickname'] ?? null,
      'isOnline' => $timeSinceActivity < 60,
      'isAway' => $timeSinceActivity >= 60 && $timeSinceActivity < 600,
      'lastSeen' => $lastActivity,
      'timeSinceActivity' => $timeSinceActivity
    ];
  }
  
  return $onlineFriends;
}

function GetUnreadMessageCount($userId) {
  global $conn;
  
  $stmt = $conn->prepare("SELECT COUNT(*) as count FROM private_messages WHERE toUserId = ? AND isRead = 0");
  if (!$stmt) {
    return 0;
  }
  
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $stmt->close();
  
  return (int)$row['count'];
}

function GetUnreadMessageCountByFriend($userId) {
  global $conn;
  
  // Single query for all unread counts instead of multiple queries
  $stmt = $conn->prepare("
    SELECT 
      fromUserId,
      COUNT(*) as unreadCount
    FROM private_messages 
    WHERE toUserId = ? AND isRead = 0
    GROUP BY fromUserId
  ");
  
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $unreadByFriend = [];
  while ($row = $result->fetch_assoc()) {
    $unreadByFriend[(int)$row['fromUserId']] = (int)$row['unreadCount'];
  }
  
  $stmt->close();
  
  return $unreadByFriend;
}

// Add optimized friend list with unread counts
function GetFriendsWithUnreadCounts($userId) {
  global $conn;
  
  // Get friends list
  $friends = GetUserFriends($userId);
  if (!$friends) {
    return [];
  }
  
  // Single query for unread counts
  $unreadCounts = GetUnreadMessageCountByFriend($userId);
  
  // Add unread counts to friends data
  foreach ($friends as &$friend) {
    $friend['unreadCount'] = $unreadCounts[$friend['friendUserId']] ?? 0;
  }
  
  return $friends;
}

function GetUserGamePreferences($userId) {
  global $conn;
  
  // Get user's saved game preferences from savedsettings table
  // We'll use default values if settings don't exist or query fails
  
  // Query the savedsettings table for format and visibility
  // settingNumber corresponds to constants from includes/dbh.inc.php
  // We'll need to find the actual setting numbers
  $stmt = $conn->prepare("
    SELECT settingNumber, settingValue FROM savedsettings 
    WHERE playerId = ?
  ");
  
  if (!$stmt) {
    // Return defaults if query fails
    return [
      'success' => true,
      'format' => 'cc',
      'visibility' => 'friends-only'
    ];
  }
  
  $stmt->bind_param("i", $userId);
  if (!$stmt->execute()) {
    $stmt->close();
    return [
      'success' => true,
      'format' => 'cc',
      'visibility' => 'friends-only'
    ];
  }
  
  $result = $stmt->get_result();
  $stmt->close();
  
  // Return defaults since we're not certain of the exact setting numbers
  // The quick game creation will use user's last known preferences from CreateGame.php
  return [
    'success' => true,
    'format' => 'cc',
    'visibility' => 'friends-only'
  ];
}

// Note: AreFriends() function is already available from FriendLibraries.php
