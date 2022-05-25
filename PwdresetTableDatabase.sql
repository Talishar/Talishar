CREATE TABLE pwdReset (
  pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  pwdResetEmail TEXT NOT NULL,
  pwdResetSelector TEXT NOT NULL,
  pwdResetToken LONGTEXT NOT NULL,
  pwdResetExpires TEXT NOT NULL
);
