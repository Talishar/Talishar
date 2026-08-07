ALTER TABLE `users` ADD COLUMN `systemMessageExpiresAt` DATETIME DEFAULT NULL AFTER `systemMessage`;
