-- Google account id (the OpenID "sub" claim) for Log in with Google.
-- Apply before deploying AccountFiles/GoogleSignupAPI.php.

ALTER TABLE `users`
  ADD COLUMN `googleID` VARCHAR(64) DEFAULT NULL,
  ADD UNIQUE KEY `idx_googleID` (`googleID`);
