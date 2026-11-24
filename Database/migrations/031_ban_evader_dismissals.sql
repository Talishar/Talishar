-- Shared-IP links a moderator has reviewed and cleared from the Possible ban evaders list.
-- An account shows up again only when it is seen on a new IP shared with a banned player.

CREATE TABLE IF NOT EXISTS `ban_evader_dismissals` (
  `usersId` INT NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `dismissedBy` VARCHAR(255) DEFAULT NULL,
  `dismissedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usersId`, `ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
