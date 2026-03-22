-- Add metafyID column to users table
-- This stores the user's Metafy user ID, retrieved from the Metafy /me API and cached locally.
SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'metafyID');
SET @sql = IF(@col = 0, 'ALTER TABLE `users` ADD COLUMN `metafyID` VARCHAR(128) DEFAULT NULL COMMENT ''Cached Metafy user ID from the Metafy /me API''', 'SELECT ''metafyID already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
