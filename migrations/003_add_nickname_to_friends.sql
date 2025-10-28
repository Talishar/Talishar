-- Add nickname column to friends table
-- This allows users to set custom nicknames for their friends
-- Nickname is optional and limited to 50 characters

ALTER TABLE friends ADD COLUMN nickname VARCHAR(50) DEFAULT NULL AFTER status;

-- Add index for faster queries if needed
CREATE INDEX idx_nickname ON friends(nickname);
