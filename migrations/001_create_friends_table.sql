-- Friends Table Schema
-- This table stores friendship relationships between users
-- Design allows for bidirectional friendships (A->B and B->A as separate rows for easier querying)

CREATE TABLE IF NOT EXISTS friends (
  friendshipId INT AUTO_INCREMENT PRIMARY KEY,
  userId INT NOT NULL,
  friendUserId INT NOT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('accepted', 'pending', 'blocked') DEFAULT 'accepted',
  
  -- Foreign keys to users table
  FOREIGN KEY (userId) REFERENCES users(usersId) ON DELETE CASCADE,
  FOREIGN KEY (friendUserId) REFERENCES users(usersId) ON DELETE CASCADE,
  
  -- Prevent duplicate friendships (user can't friend themselves or create duplicate entries)
  UNIQUE KEY unique_friendship (userId, friendUserId),
  CHECK (userId != friendUserId),
  
  -- Indexes for fast queries
  INDEX idx_user_id (userId),
  INDEX idx_friend_user_id (friendUserId),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
