-- When each account was created, for the spectating account-age gate.
-- New signups get the insert time. Existing accounts are backfilled from the
-- first IP they were seen on (ip_history started 2026-07-02); accounts with no
-- IP history stay NULL, which the gate treats as old enough.
-- Apply before deploying the code that reads this column.

ALTER TABLE `users`
  ADD COLUMN `createdAt` TIMESTAMP NULL DEFAULT NULL;

UPDATE `users` u
  JOIN (SELECT usersId, MIN(firstSeen) AS firstSeen FROM ip_history GROUP BY usersId) h
    ON h.usersId = u.usersId
  SET u.createdAt = h.firstSeen;

ALTER TABLE `users`
  MODIFY COLUMN `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;
