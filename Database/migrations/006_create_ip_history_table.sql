-- IP History Table Schema
-- Records which IPs each account has been seen on (login, game create/join)
-- so moderators can link ban-evading alt accounts to banned accounts.
-- One row per (user, ip) pair; lastSeen/timesSeen are updated on repeat visits.
-- Note: the PHP code also creates this table on demand (EnsureIPHistoryTable),
-- so running this migration manually is optional.

CREATE TABLE IF NOT EXISTS ip_history (
  usersId INT NOT NULL,
  ip VARCHAR(45) NOT NULL,
  firstSeen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  lastSeen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  timesSeen INT NOT NULL DEFAULT 1,
  PRIMARY KEY (usersId, ip),
  INDEX idx_ip (ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
