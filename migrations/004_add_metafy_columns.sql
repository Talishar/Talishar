-- Metafy OAuth Integration
-- Adds columns to store Metafy OAuth tokens and community data

-- Add Metafy columns to users table
-- These columns store OAuth tokens and community information for Metafy integration

ALTER TABLE users ADD metafyAccessToken VARCHAR(500) DEFAULT NULL COMMENT 'Metafy OAuth access token';
ALTER TABLE users ADD metafyRefreshToken VARCHAR(500) DEFAULT NULL COMMENT 'Metafy OAuth refresh token';
ALTER TABLE users ADD metafyCommunities LONGTEXT DEFAULT NULL COMMENT 'JSON-encoded array of communities user supports on Metafy';

-- Add indexes for efficient queries if needed in future
ALTER TABLE users ADD INDEX idx_metafy_access_token (metafyAccessToken);
