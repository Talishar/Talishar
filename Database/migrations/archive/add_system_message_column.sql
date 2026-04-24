-- Original file: Database/migrations/add_system_message_column.sql
--
-- Add systemMessage column to users table for moderator system messages
ALTER TABLE `users` ADD COLUMN `systemMessage` TEXT DEFAULT NULL AFTER `lastActivity`;
