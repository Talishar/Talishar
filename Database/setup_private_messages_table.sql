-- Create private_messages table for the messaging system
-- Run this SQL on the fabonline2 database

CREATE TABLE IF NOT EXISTS `private_messages` (
  `messageId` INT AUTO_INCREMENT PRIMARY KEY,
  `fromUserId` INT NOT NULL,
  `toUserId` INT NOT NULL,
  `message` LONGTEXT,
  `gameLink` VARCHAR(500),
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `isRead` BOOLEAN DEFAULT FALSE,
  INDEX `idx_from_to` (`fromUserId`, `toUserId`),
  INDEX `idx_to_unread` (`toUserId`, `isRead`),
  INDEX `idx_created` (`createdAt`),
  FOREIGN KEY (`fromUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE,
  FOREIGN KEY (`toUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE
);
