-- Private Messaging System Database Schema
-- This file contains the SQL schema for the private messaging feature

-- Create private_messages table
CREATE TABLE IF NOT EXISTS `private_messages` (
  `messageId` INT NOT NULL AUTO_INCREMENT,
  `fromUserId` INT NOT NULL,
  `toUserId` INT NOT NULL,
  `message` TEXT NOT NULL,
  `gameLink` VARCHAR(500) DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isRead` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`messageId`),
  INDEX `idx_from_user` (`fromUserId`),
  INDEX `idx_to_user` (`toUserId`),
  INDEX `idx_conversation` (`fromUserId`, `toUserId`, `createdAt`),
  INDEX `idx_unread` (`toUserId`, `isRead`),
  FOREIGN KEY (`fromUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE,
  FOREIGN KEY (`toUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add lastActivity column to users table if it doesn't exist
-- This is used to track online status
ALTER TABLE `users` 
  ADD COLUMN IF NOT EXISTS `lastActivity` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Create index for faster online status queries
CREATE INDEX IF NOT EXISTS `idx_last_activity` ON `users`(`lastActivity`);
