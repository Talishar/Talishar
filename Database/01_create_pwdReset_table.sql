-- Password Reset Database Schema
-- Run this SQL script in your MySQL database to create the pwdReset table

CREATE TABLE IF NOT EXISTS pwdReset (
  pwdResetId INT AUTO_INCREMENT PRIMARY KEY,
  pwdResetEmail VARCHAR(255) NOT NULL,
  pwdResetSelector VARCHAR(255) NOT NULL UNIQUE,
  pwdResetToken VARCHAR(255) NOT NULL,
  pwdResetExpires BIGINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (pwdResetEmail),
  INDEX idx_selector (pwdResetSelector),
  INDEX idx_expires (pwdResetExpires)
);

-- The following columns are used:
-- pwdResetId: Unique identifier for each reset request
-- pwdResetEmail: Email address requesting the reset
-- pwdResetSelector: Public identifier for the reset link (hex encoded)
-- pwdResetToken: Hashed security token (matched against validator from email link)
-- pwdResetExpires: Unix timestamp when the token expires (30 minutes from creation)
-- created_at: When this record was created

-- Indexes for performance:
-- idx_email: For looking up existing resets by email
-- idx_selector: For validating reset links
-- idx_expires: For cleaning up expired tokens
