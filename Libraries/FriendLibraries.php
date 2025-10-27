<?php

/**
 * FriendLibraries.php
 * Helper functions for friend list management
 */

/**
 * Get all accepted friends for a user
 * @param int $userId
 * @return array List of friend user IDs and names
 */
function GetUserFriends($userId) {
  global $conn;
  
  if (!is_numeric($userId)) {
    return [];
  }
  
  $query = "
    SELECT f.friendUserId, u.usersUid, u.usersId
    FROM friends f
    JOIN users u ON f.friendUserId = u.usersId
    WHERE f.userId = ? AND f.status = 'accepted'
    ORDER BY u.usersUid ASC
  ";
  
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $friends = [];
  while ($row = $result->fetch_assoc()) {
    $friends[] = [
      'friendUserId' => $row['usersId'],
      'username' => $row['usersUid']
    ];
  }
  
  $stmt->close();
  return $friends;
}

/**
 * Check if two users are friends
 * @param int $userId
 * @param int $friendUserId
 * @return bool
 */
function AreFriends($userId, $friendUserId) {
  global $conn;
  
  if (!is_numeric($userId) || !is_numeric($friendUserId)) {
    return false;
  }
  
  $query = "SELECT 1 FROM friends WHERE userId = ? AND friendUserId = ? AND status = 'accepted' LIMIT 1";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return false;
  }
  
  $stmt->bind_param("ii", $userId, $friendUserId);
  $stmt->execute();
  $result = $stmt->get_result();
  $isFriend = $result->num_rows > 0;
  $stmt->close();
  
  return $isFriend;
}

/**
 * Send a friend request (one-way pending request)
 * @param int $userId
 * @param int $friendUserId
 * @return array ['success' => bool, 'message' => string]
 */
function AddFriend($userId, $friendUserId) {
  global $conn;
  
  if (!is_numeric($userId) || !is_numeric($friendUserId)) {
    return ['success' => false, 'message' => 'Invalid user ID'];
  }
  
  if ($userId === $friendUserId) {
    return ['success' => false, 'message' => 'Cannot add yourself as a friend'];
  }
  
  // Check if friend exists
  $checkQuery = "SELECT usersId FROM users WHERE usersId = ? LIMIT 1";
  $stmt = $conn->prepare($checkQuery);
  $stmt->bind_param("i", $friendUserId);
  $stmt->execute();
  $result = $stmt->get_result();
  $userExists = $result->num_rows > 0;
  $stmt->close();
  
  if (!$userExists) {
    return ['success' => false, 'message' => 'User not found'];
  }
  
  // Check if already friends
  if (AreFriends($userId, $friendUserId)) {
    return ['success' => false, 'message' => 'Already friends with this user'];
  }
  
  // Check if pending request already exists in either direction
  $checkPendingQuery = "SELECT 1 FROM friends WHERE (userId = ? AND friendUserId = ?) OR (userId = ? AND friendUserId = ?) LIMIT 1";
  $stmt = $conn->prepare($checkPendingQuery);
  $stmt->bind_param("iiii", $userId, $friendUserId, $friendUserId, $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  $hasPendingRequest = $result->num_rows > 0;
  $stmt->close();
  
  if ($hasPendingRequest) {
    return ['success' => false, 'message' => 'Friend request already sent or pending'];
  }
  
  // Send one-way pending friend request
  $insertQuery = "INSERT INTO friends (userId, friendUserId, status) VALUES (?, ?, 'pending')";
  
  $stmt = $conn->prepare($insertQuery);
  if (!$stmt) {
    return ['success' => false, 'message' => 'Database error'];
  }
  
  $stmt->bind_param("ii", $userId, $friendUserId);
  $stmt->execute();
  $stmt->close();
  
  return ['success' => true, 'message' => 'Friend request sent successfully'];
}

/**
 * Remove a friend (bidirectional)
 * @param int $userId
 * @param int $friendUserId
 * @return array ['success' => bool, 'message' => string]
 */
function RemoveFriend($userId, $friendUserId) {
  global $conn;
  
  if (!is_numeric($userId) || !is_numeric($friendUserId)) {
    return ['success' => false, 'message' => 'Invalid user ID'];
  }
  
  // Delete both directions
  $deleteQuery = "DELETE FROM friends WHERE (userId = ? AND friendUserId = ?) OR (userId = ? AND friendUserId = ?)";
  $stmt = $conn->prepare($deleteQuery);
  if (!$stmt) {
    return ['success' => false, 'message' => 'Database error'];
  }
  
  $stmt->bind_param("iiii", $userId, $friendUserId, $friendUserId, $userId);
  $stmt->execute();
  $affectedRows = $stmt->affected_rows;
  $stmt->close();
  
  if ($affectedRows === 0) {
    return ['success' => false, 'message' => 'Friendship not found'];
  }
  
  return ['success' => true, 'message' => 'Friend removed successfully'];
}

/**
 * Accept a friend request (converts pending to accepted bidirectionally)
 * @param int $userId The user accepting the request
 * @param int $requesterUserId The user who sent the request
 * @return array ['success' => bool, 'message' => string]
 */
function AcceptFriendRequest($userId, $requesterUserId) {
  global $conn;
  
  if (!is_numeric($userId) || !is_numeric($requesterUserId)) {
    return ['success' => false, 'message' => 'Invalid user ID'];
  }
  
  if ($userId === $requesterUserId) {
    return ['success' => false, 'message' => 'Invalid request'];
  }
  
  // Check if pending request exists (requester sent request to user)
  $checkQuery = "SELECT friendshipId FROM friends WHERE userId = ? AND friendUserId = ? AND status = 'pending' LIMIT 1";
  $stmt = $conn->prepare($checkQuery);
  $stmt->bind_param("ii", $requesterUserId, $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows === 0) {
    $stmt->close();
    return ['success' => false, 'message' => 'Friend request not found'];
  }
  
  $row = $result->fetch_assoc();
  $friendshipId = $row['friendshipId'];
  $stmt->close();
  
  // Update the pending request to accepted
  $updateQuery = "UPDATE friends SET status = 'accepted' WHERE friendshipId = ?";
  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param("i", $friendshipId);
  $stmt->execute();
  $stmt->close();
  
  // Add reverse direction as accepted
  $insertQuery = "INSERT INTO friends (userId, friendUserId, status) VALUES (?, ?, 'accepted')";
  $stmt = $conn->prepare($insertQuery);
  $stmt->bind_param("ii", $userId, $requesterUserId);
  $stmt->execute();
  $stmt->close();
  
  return ['success' => true, 'message' => 'Friend request accepted'];
}

/**
 * Get pending friend requests for a user
 * @param int $userId
 * @return array List of pending requests
 */
function GetPendingRequests($userId) {
  global $conn;
  
  if (!is_numeric($userId)) {
    return [];
  }
  
  $query = "
    SELECT f.friendshipId, f.userId as requesterUserId, u.usersUid as requesterUsername, f.createdAt
    FROM friends f
    JOIN users u ON f.userId = u.usersId
    WHERE f.friendUserId = ? AND f.status = 'pending'
    ORDER BY f.createdAt DESC
  ";
  
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $requests = [];
  while ($row = $result->fetch_assoc()) {
    $requests[] = [
      'friendshipId' => $row['friendshipId'],
      'requesterUserId' => $row['requesterUserId'],
      'requesterUsername' => $row['requesterUsername'],
      'createdAt' => $row['createdAt']
    ];
  }
  
  $stmt->close();
  return $requests;
}

/**
 * Reject a friend request
 * @param int $userId The user rejecting the request
 * @param int $requesterUserId The user who sent the request
 * @return array ['success' => bool, 'message' => string]
 */
function RejectFriendRequest($userId, $requesterUserId) {
  global $conn;
  
  if (!is_numeric($userId) || !is_numeric($requesterUserId)) {
    return ['success' => false, 'message' => 'Invalid user ID'];
  }
  
  // Delete the pending request
  $deleteQuery = "DELETE FROM friends WHERE userId = ? AND friendUserId = ? AND status = 'pending'";
  $stmt = $conn->prepare($deleteQuery);
  $stmt->bind_param("ii", $requesterUserId, $userId);
  $stmt->execute();
  $affectedRows = $stmt->affected_rows;
  $stmt->close();
  
  if ($affectedRows === 0) {
    return ['success' => false, 'message' => 'Friend request not found'];
  }
  
  return ['success' => true, 'message' => 'Friend request rejected'];
}

/**
 * Find a user by username
 * @param string $username
 * @return array|null User data or null if not found
 */
function FindUserByUsername($username) {
  global $conn;
  
  $username = trim($username);
  if (strlen($username) === 0) {
    return null;
  }
  
  $query = "SELECT usersId, usersUid FROM users WHERE usersUid = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return null;
  }
  
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows === 0) {
    $stmt->close();
    return null;
  }
  
  $user = $result->fetch_assoc();
  $stmt->close();
  
  return [
    'usersId' => $user['usersId'],
    'username' => $user['usersUid']
  ];
}

/**
 * Get sent friend requests from a user
 * @param int $userId
 * @return array List of sent requests
 */
function GetSentRequests($userId) {
  global $conn;
  
  if (!is_numeric($userId)) {
    return [];
  }
  
  $query = "
    SELECT f.friendshipId, f.friendUserId, u.usersUid as recipientUsername, f.createdAt
    FROM friends f
    JOIN users u ON f.friendUserId = u.usersId
    WHERE f.userId = ? AND f.status = 'pending'
    ORDER BY f.createdAt DESC
  ";
  
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $requests = [];
  while ($row = $result->fetch_assoc()) {
    $requests[] = [
      'friendshipId' => $row['friendshipId'],
      'recipientUserId' => $row['friendUserId'],
      'recipientUsername' => $row['recipientUsername'],
      'createdAt' => $row['createdAt']
    ];
  }
  
  $stmt->close();
  return $requests;
}

/**
 * Cancel a sent friend request
 * @param int $userId The user who sent the request
 * @param int $recipientUserId The recipient of the request
 * @return array ['success' => bool, 'message' => string]
 */
function CancelFriendRequest($userId, $recipientUserId) {
  global $conn;
  
  if (!is_numeric($userId) || !is_numeric($recipientUserId)) {
    return ['success' => false, 'message' => 'Invalid user IDs'];
  }
  
  if ($userId == $recipientUserId) {
    return ['success' => false, 'message' => 'Cannot cancel request to yourself'];
  }
  
  // Check if request exists and is pending
  $query = "SELECT friendshipId FROM friends WHERE userId = ? AND friendUserId = ? AND status = 'pending'";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return ['success' => false, 'message' => 'Database error'];
  }
  
  $stmt->bind_param("ii", $userId, $recipientUserId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows === 0) {
    $stmt->close();
    return ['success' => false, 'message' => 'No pending request found'];
  }
  
  $stmt->close();
  
  // Delete the pending request
  $deleteQuery = "DELETE FROM friends WHERE userId = ? AND friendUserId = ? AND status = 'pending'";
  $deleteStmt = $conn->prepare($deleteQuery);
  if (!$deleteStmt) {
    return ['success' => false, 'message' => 'Database error'];
  }
  
  $deleteStmt->bind_param("ii", $userId, $recipientUserId);
  $deleteStmt->execute();
  $deleteStmt->close();
  
  return ['success' => true, 'message' => 'Friend request cancelled'];
}

/**
 * Search for users by partial username match
 * @param string $searchTerm
 * @param int $limit
 * @return array List of matching users
 */
function SearchUsers($searchTerm, $limit = 10) {
  global $conn;
  
  $searchTerm = trim($searchTerm);
  if (strlen($searchTerm) < 2) {
    return [];
  }
  
  $searchPattern = '%' . $searchTerm . '%';
  $query = "SELECT usersId, usersUid FROM users WHERE usersUid LIKE ? LIMIT ?";
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    return [];
  }
  
  $stmt->bind_param("si", $searchPattern, $limit);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = [
      'usersId' => $row['usersId'],
      'username' => $row['usersUid']
    ];
  }
  
  $stmt->close();
  return $users;
}
