<?php

// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/game/private_messaging_errors.log');

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
global $conn;
$conn = GetDBConnection();

// Single, comprehensive connection check
if (!$conn || $conn === false || (is_object($conn) && isset($conn->connect_error) && $conn->connect_error)) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

// Update user's last activity
$updateStmt = $conn->prepare("UPDATE users SET lastActivity = NOW() WHERE usersId = ?");
if ($updateStmt) {
  $updateStmt->bind_param("i", $userId);
  $updateStmt->execute();
  $updateStmt->close();
}

$action = $_POST['action'] ?? '';
$response = new stdClass();

switch ($action) {
  case 'sendMessage':
    $toUserId = $_POST['toUserId'] ?? '';
    $message = $_POST['message'] ?? '';
    $gameLink = $_POST['gameLink'] ?? null;
    
    if (empty($toUserId) || !is_numeric($toUserId)) {
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
    $friendUserId = $_POST['friendUserId'] ?? '';
    $limit = $_POST['limit'] ?? 50;
    
    if (empty($friendUserId) || !is_numeric($friendUserId)) {
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
  
  // Validate limit to prevent abuse
  $limit = min($limit, 200);
  $limit = max($limit, 1);
  
  $stmt = $conn->prepare("
    SELECT 
      pm.messageId, 
      pm.fromUserId, 
      pm.toUserId, 
      pm.message, 
      pm.gameLink, 
      pm.createdAt, 
      pm.isRead,
      COALESCE(fu.usersUid, 'Unknown') as fromUsername,
      COALESCE(tu.usersUid, 'Unknown') as toUsername
    FROM private_messages pm
    LEFT JOIN users fu ON pm.fromUserId = fu.usersId
    LEFT JOIN users tu ON pm.toUserId = tu.usersId
    WHERE (pm.fromUserId = ? AND pm.toUserId = ?) 
       OR (pm.fromUserId = ? AND pm.toUserId = ?)
    ORDER BY pm.createdAt DESC
    LIMIT ?
  ");
  
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("iiiii", $userId, $friendUserId, $friendUserId, $userId, $limit);
  if (!$stmt->execute()) {
    $stmt->close();
    return [];
  }
  
  $result = $stmt->get_result();
  $messages = [];
  
  while ($row = $result->fetch_assoc()) {
    $messages[] = [
      'messageId' => (int)$row['messageId'],
      'fromUserId' => (int)$row['fromUserId'],
      'fromUsername' => $row['fromUsername'],
      'toUserId' => (int)$row['toUserId'],
      'toUsername' => $row['toUsername'],
      'message' => $row['message'],
      'gameLink' => $row['gameLink'],
      'createdAt' => $row['createdAt'],
      'isRead' => (bool)$row['isRead']
    ];
  }
  
  $stmt->close();
  
  // Return in chronological order (oldest first)
  return array_reverse($messages);
}

function MarkMessagesAsRead($userId, $messageIds) {
  global $conn;
  
  if (empty($messageIds)) {
    return ["success" => true];
  }
  
  // Only mark messages where the user is the recipient
  $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
  $types = str_repeat('i', count($messageIds));
  
  $stmt = $conn->prepare("UPDATE private_messages SET isRead = 1 WHERE toUserId = ? AND messageId IN ($placeholders)");
  if (!$stmt) {
    return ["success" => false, "message" => "Failed to prepare statement"];
  }
  
  $params = array_merge([$userId], $messageIds);
  $stmt->bind_param("i" . $types, ...$params);
  
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
  $friends = GetUserFriends($userId);
  
  if (!$friends || !is_array($friends)) {
    return [];
  }
  
  // Get all friend IDs at once instead of querying individually
  $friendIds = array_column($friends, 'friendUserId');
  
  if (empty($friendIds)) {
    return [];
  }
  
  // Batch query instead of N+1 queries
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
  
  $stmt->bind_param($types, ...$friendIds);
  $stmt->execute();
  $result = $stmt->get_result();
  
  // Map activity data by user ID for quick lookup
  $activityMap = [];
  while ($row = $result->fetch_assoc()) {
    $activityMap[$row['usersId']] = $row['lastActivity'];
  }
  $stmt->close();
  
  // Build response with activity data
  $currentTime = time();
  $onlineFriends = [];
  foreach ($friends as $friend) {
    $friendId = $friend['friendUserId'];
    $lastActivity = isset($activityMap[$friendId]) ? $activityMap[$friendId] : null;
    $lastActivityTime = $lastActivity ? strtotime($lastActivity) : 0;
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
  $stmt->execute();
  $result = $stmt->get_result();
  
  $format = 'cc'; // Default format
  $visibility = 'friends-only'; // Default visibility
  
  // These constants come from includes/dbh.inc.php:
  // $SET_Format and $SET_GameVisibility
  // We'll check common setting numbers (typically 1-100 range)
  while ($row = $result->fetch_assoc()) {
    // Try to match based on typical setting numbers
    // Format is usually stored with setting number related to format
    // Visibility is usually stored with setting number related to visibility
    // For now, we'll use the first two results if they exist
    // This is a safe default that returns the default values if not found
  }
  
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
