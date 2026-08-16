-- Metafy access tokens expire two hours after they are issued. Without knowing when
-- a token dies we cannot refresh it ahead of time, and every supporter silently
-- falls back to "not a supporter" once their token lapses.
--
-- metafyScopes records what the stored token is actually allowed to do, so the
-- profile-only token issued by the "Sign in with Metafy" app is never mistaken for
-- the community/purchases token issued by the account-linking app.

ALTER TABLE `users`
  ADD COLUMN `metafyTokenExpires` TIMESTAMP NULL DEFAULT NULL AFTER `metafyRefreshToken`,
  ADD COLUMN `metafyScopes` VARCHAR(255) DEFAULT NULL AFTER `metafyTokenExpires`,
  ADD COLUMN `metafyLastSync` TIMESTAMP NULL DEFAULT NULL AFTER `metafyScopes`;

-- Existing tokens have an unknown issue time and are almost certainly expired.
-- Leaving the expiry NULL means "unknown": the first API call will 401, the refresh
-- token will be used, and the real expiry gets recorded from that point on.
