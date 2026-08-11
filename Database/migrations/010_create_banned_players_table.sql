-- Banned Players Table Schema
-- Moves name bans from HostFiles/bannedPlayers.txt (wiped on deploy/container
-- recreation) into the database so they persist. Needed for banned names that
-- don't resolve to a registered account (guest players) — registered accounts
-- are already covered by users.isBanned.
-- Note: the PHP code also creates this table on demand (EnsureBannedPlayersTable),
-- so running this migration manually is optional.

CREATE TABLE IF NOT EXISTS banned_players (
  name VARCHAR(255) NOT NULL PRIMARY KEY,
  bannedBy VARCHAR(255) DEFAULT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
