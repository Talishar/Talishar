-- Display name feature: players can customize the name shown to others while
-- usersUid stays the immutable account handle used for logins, bans, and all lookups.

ALTER TABLE users
  ADD COLUMN displayName VARCHAR(50) DEFAULT NULL,
  ADD COLUMN lastNameChange TIMESTAMP NULL DEFAULT NULL,
  ADD UNIQUE KEY idx_displayName (displayName);

-- Audit trail of renames so moderators can trace accounts across name changes.
CREATE TABLE IF NOT EXISTS name_history (
  usersId INT NOT NULL,
  oldName VARCHAR(128) NOT NULL,
  newName VARCHAR(128) NOT NULL,
  changedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_users (usersId),
  INDEX idx_oldName (oldName),
  FOREIGN KEY (usersId) REFERENCES users(usersId) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
