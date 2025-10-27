<?php

/**
 * BlockedUserLibraries.php
 * Helper functions for blocked user list management
 */

/**
 * Get all blocked users for a user
 * @param int $userId
 * @return array List of blocked user IDs and names
 */
function GetBlockedUsers($userId) {
  global $conn;
  
  if (!$conn) {
    return [];
  }
  
  if (!is_numeric($userId)) {
    return [];
  }
  
  $query = "
    SELECT b.blockedUserId, u.usersUid, u.usersId
    FROM blocked_users b
    JOIN users u ON b.blockedUserId = u.usersId
    WHERE b.userId = ?
    ORDER BY u.usersUid ASC
  ";
  
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $blockedUsers = [];
  while ($row = $result->fetch_assoc()) {
    $blockedUsers[] = [
      'blockedUserId' => $row['usersId'],
      'username' => $row['usersUid']
    ];
  }
  
  $stmt->close();
  return $blockedUsers;
}

/**
 * Check if a user is blocked by another user
 * @param int $userId
 * @param int $blockedUserId
 * @return bool
 */
function IsUserBlocked($userId, $blockedUserId) {
  global $conn;
  
  if (!$conn) {
    return false;
  }
  
  if (!is_numeric($userId) || !is_numeric($blockedUserId)) {
    return false;
  }
  
  $query = "SELECT 1 FROM blocked_users WHERE userId = ? AND blockedUserId = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return false;
  }
  
  $stmt->bind_param("ii", $userId, $blockedUserId);
  $stmt->execute();
  $result = $stmt->get_result();
  $isBlocked = $result->num_rows > 0;
  $stmt->close();
  
  return $isBlocked;
}

/**
 * Block a user
 * @param int $userId
 * @param int $blockedUserId
 * @return array ['success' => bool, 'message' => string]
 */
function BlockUser($userId, $blockedUserId) {
  global $conn;
  
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }
  
  if (!is_numeric($userId) || !is_numeric($blockedUserId)) {
    return ['success' => false, 'message' => 'Invalid user ID'];
  }
  
  if ($userId === $blockedUserId) {
    return ['success' => false, 'message' => 'Cannot block yourself'];
  }
  
  // Check if user exists
  $checkQuery = "SELECT usersId FROM users WHERE usersId = ? LIMIT 1";
  $stmt = $conn->prepare($checkQuery);
  $stmt->bind_param("i", $blockedUserId);
  $stmt->execute();
  $result = $stmt->get_result();
  $userExists = $result->num_rows > 0;
  $stmt->close();
  
  if (!$userExists) {
    return ['success' => false, 'message' => 'User not found'];
  }
  
  // Check if already blocked
  if (IsUserBlocked($userId, $blockedUserId)) {
    return ['success' => false, 'message' => 'User is already blocked'];
  }
  
  // Insert block record
  $insertQuery = "INSERT INTO blocked_users (userId, blockedUserId) VALUES (?, ?)";
  
  $stmt = $conn->prepare($insertQuery);
  if (!$stmt) {
    return ['success' => false, 'message' => 'Database error'];
  }
  
  $stmt->bind_param("ii", $userId, $blockedUserId);
  $stmt->execute();
  $stmt->close();
  
  return ['success' => true, 'message' => 'User blocked successfully'];
}

/**
 * Unblock a user
 * @param int $userId
 * @param int $blockedUserId
 * @return array ['success' => bool, 'message' => string]
 */
function UnblockUser($userId, $blockedUserId) {
  global $conn;
  
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }
  
  if (!is_numeric($userId) || !is_numeric($blockedUserId)) {
    return ['success' => false, 'message' => 'Invalid user ID'];
  }
  
  // Delete the block record
  $deleteQuery = "DELETE FROM blocked_users WHERE userId = ? AND blockedUserId = ?";
  $stmt = $conn->prepare($deleteQuery);
  if (!$stmt) {
    return ['success' => false, 'message' => 'Database error'];
  }
  
  $stmt->bind_param("ii", $userId, $blockedUserId);
  $stmt->execute();
  $affectedRows = $stmt->affected_rows;
  $stmt->close();
  
  if ($affectedRows === 0) {
    return ['success' => false, 'message' => 'Block record not found'];
  }
  
  return ['success' => true, 'message' => 'User unblocked successfully'];
}

/**
 * Check if any users are blocked in a list
 * @param int $userId
 * @param array $userIds
 * @return array List of blocked user IDs
 */
function GetBlockedUserIds($userId, $userIds = []) {
  global $conn;
  
  if (!is_numeric($userId) || empty($userIds)) {
    return [];
  }
  
  // Build IN clause for user IDs
  $placeholders = implode(',', array_fill(0, count($userIds), '?'));
  $query = "SELECT blockedUserId FROM blocked_users WHERE userId = ? AND blockedUserId IN ($placeholders)";
  
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return [];
  }
  
  // Bind parameters
  $types = 'i' . str_repeat('i', count($userIds));
  $bindParams = array_merge([$types, $userId], $userIds);
  
  call_user_func_array([$stmt, 'bind_param'], $bindParams);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $blockedIds = [];
  while ($row = $result->fetch_assoc()) {
    $blockedIds[] = $row['blockedUserId'];
  }
  
  $stmt->close();
  return $blockedIds;
}
