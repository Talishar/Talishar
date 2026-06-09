-- Add matchResultWebhookUrl column to users table
SET @col = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'matchResultWebhookUrl');
SET @sql = IF(@col = 0,
    'ALTER TABLE `users` ADD COLUMN `matchResultWebhookUrl` VARCHAR(2048) DEFAULT NULL',
    'SELECT ''matchResultWebhookUrl already exists''');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
