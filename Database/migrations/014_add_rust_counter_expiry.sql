ALTER TABLE `users`
  ADD COLUMN `rust_counters_last_played` TIMESTAMP NULL DEFAULT NULL AFTER `rust_counters`;

-- Existing counters predate last-played tracking. Preserve them for one week
-- unless a moderator explicitly uses the global reset action.
UPDATE `users`
SET `rust_counters_last_played` = CURRENT_TIMESTAMP
WHERE `rust_counters` > 0;
