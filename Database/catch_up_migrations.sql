-- ============================================================
-- Talishar Database Migrations - Catch-Up Script
-- ============================================================
-- Run this once if you have an existing database and are seeing
-- "Unknown column" errors. All statements use IF NOT EXISTS so
-- it is safe to run multiple times.
--
-- Usage (Docker):
--   docker exec -i talishar-mysql-server-1 \
--     mysql -uroot -psecret fabonline < Database/catch_up_migrations.sql
--
-- Usage (phpMyAdmin):
--   Open phpMyAdmin → select fabonline DB → SQL tab → paste & run.
-- ============================================================

-- Migration 001: favoritedeck primary key (001_update_favoritedeck_pk.sql)
-- (No IF NOT EXISTS equivalent for PK changes; skip if already applied)

-- Each block checks INFORMATION_SCHEMA before altering, so this script is safe to re-run.

-- Migration: Add lastActivity column
SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'lastActivity');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `lastActivity` TIMESTAMP NULL DEFAULT NULL', 'SELECT ''lastActivity already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Migration: Add systemMessage column (add_system_message_column.sql)
SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'systemMessage');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `systemMessage` TEXT DEFAULT NULL', 'SELECT ''systemMessage already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Migration: Add Metafy OAuth columns (METAFY_DATABASE_SETUP.sql)
SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'metafyAccessToken');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `metafyAccessToken` VARCHAR(500) DEFAULT NULL', 'SELECT ''metafyAccessToken already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'metafyRefreshToken');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `metafyRefreshToken` VARCHAR(500) DEFAULT NULL', 'SELECT ''metafyRefreshToken already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'metafyCommunities');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `metafyCommunities` LONGTEXT DEFAULT NULL', 'SELECT ''metafyCommunities already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Migration 003: Add metafyID column (003_add_metafy_id_column.sql)
SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'metafyID');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `metafyID` VARCHAR(128) DEFAULT NULL', 'SELECT ''metafyID already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add Metafy index (skip if already exists)
SET @idx = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND INDEX_NAME = 'idx_metafy_access_token');
SET @sql = IF(@idx = 0, 'ALTER TABLE `users` ADD INDEX `idx_metafy_access_token` (`metafyAccessToken`)', 'SELECT ''idx_metafy_access_token already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- private_messages table (create_private_messaging.sql)
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
