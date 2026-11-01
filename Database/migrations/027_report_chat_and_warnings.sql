-- The chat lines leading up to each player report, captured when the report is sent,
-- and a record of warnings moderators send so the Reports tab can mark who was warned.
-- Apply before deploying the code that reads these.

ALTER TABLE `player_reports`
  ADD COLUMN `chatLog` TEXT NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `player_warnings` (
  `warningId` INT AUTO_INCREMENT PRIMARY KEY,
  `userId` INT NOT NULL,
  `moderatorUid` VARCHAR(64) NOT NULL,
  `message` VARCHAR(2000) NOT NULL,
  `createdAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_user_created` (`userId`, `createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
