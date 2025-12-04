/**
 * Metafy Integration Database Setup
 * 
 * This script adds the required columns to the users table for Metafy OAuth integration.
 * 
 * Run this script in your MySQL client or database management tool (phpMyAdmin, etc.)
 * 
 * Usage:
 * 1. Open phpMyAdmin or your MySQL client
 * 2. Select your Talishar database
 * 3. Paste this entire script into the SQL query box
 * 4. Click "Go" or "Execute"
 */

-- Add Metafy OAuth access token column
ALTER TABLE users 
ADD COLUMN metafyAccessToken VARCHAR(500) DEFAULT NULL 
COMMENT 'Metafy OAuth access token for API authentication';

-- Add Metafy OAuth refresh token column
ALTER TABLE users 
ADD COLUMN metafyRefreshToken VARCHAR(500) DEFAULT NULL 
COMMENT 'Metafy OAuth refresh token for token renewal';

-- Add Metafy communities data column (stores JSON)
ALTER TABLE users 
ADD COLUMN metafyCommunities LONGTEXT DEFAULT NULL 
COMMENT 'JSON-encoded array of communities the user supports';

-- Add index for faster lookups if needed
ALTER TABLE users 
ADD INDEX idx_metafy_access_token (metafyAccessToken);

-- Verify the columns were added (optional - just for checking)
-- DESCRIBE users;
