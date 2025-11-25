-- Migration: Add lastActivity column to users table for online status tracking
-- This column tracks when a user was last active, used to determine if they're online

ALTER TABLE `users` 
ADD COLUMN `lastActivity` TIMESTAMP NULL DEFAULT NULL AFTER `lastAuthKey`;

-- Set existing users to NULL (unknown activity state)
-- New activity tracking will update this column on subsequent API calls
