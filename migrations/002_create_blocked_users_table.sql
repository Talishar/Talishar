-- Blocked Users Table Schema
-- This table stores personal block relationships between users
-- When user A blocks user B, they won't see each other's open games
-- The relationship is one-directional (A blocking B doesn't mean B blocks A)

CREATE TABLE IF NOT EXISTS blocked_users (
  blockId INT AUTO_INCREMENT PRIMARY KEY,
  userId INT NOT NULL,
  blockedUserId INT NOT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  -- Foreign keys to users table
  FOREIGN KEY (userId) REFERENCES users(usersId) ON DELETE CASCADE,
  FOREIGN KEY (blockedUserId) REFERENCES users(usersId) ON DELETE CASCADE,
  
  -- Prevent duplicate blocks (user can't block themselves or create duplicate block entries)
  UNIQUE KEY unique_block (userId, blockedUserId),
  CHECK (userId != blockedUserId),
  
  -- Indexes for fast queries
  INDEX idx_user_id (userId),
  INDEX idx_blocked_user_id (blockedUserId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
