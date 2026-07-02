-- Banned IPs Table Schema
-- Moves IP bans from HostFiles/bannedIPs.txt (wiped on deploy/container
-- recreation) into the database so they persist.
-- Note: the PHP code also creates this table on demand (EnsureBannedIPsTable),
-- so running this migration manually is optional.

CREATE TABLE IF NOT EXISTS banned_ips (
  ip VARCHAR(45) NOT NULL PRIMARY KEY,
  bannedBy VARCHAR(255) DEFAULT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
