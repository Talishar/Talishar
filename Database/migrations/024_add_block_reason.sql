-- Optional reason and note a player leaves for moderators when blocking someone.
-- Apply before deploying the code that writes these columns.

ALTER TABLE `blocked_users`
  ADD COLUMN `reason` VARCHAR(20) NULL DEFAULT NULL AFTER `blockedUserId`,
  ADD COLUMN `note` VARCHAR(300) NULL DEFAULT NULL AFTER `reason`;
