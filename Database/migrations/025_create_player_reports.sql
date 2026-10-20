-- In-game player reports, with the optional reason and note the reporter leaves for moderators.
-- One row per reporting seat per game; a second report from the same seat updates the first.

CREATE TABLE IF NOT EXISTS `player_reports` (
  `reportId` INT AUTO_INCREMENT PRIMARY KEY,
  `gameName` VARCHAR(32) NOT NULL,
  `reporterPlayerID` TINYINT NOT NULL,
  `reporterUserId` INT NULL DEFAULT NULL,
  `reportedName` VARCHAR(64) NOT NULL,
  `reportedUserId` INT NULL DEFAULT NULL,
  `reason` VARCHAR(20) NULL DEFAULT NULL,
  `note` VARCHAR(500) NULL DEFAULT NULL,
  `createdAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_report` (`gameName`, `reporterPlayerID`),
  INDEX `idx_created` (`createdAt`),
  INDEX `idx_reported_user` (`reportedUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
