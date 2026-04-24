-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2022 at 02:11 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `completedgame` (
  `CompletionTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `WinningHero` char(6) NOT NULL,
  `LosingHero` char(6) NOT NULL,
  `WinningPID` int(11) DEFAULT NULL,
  `LosingPID` int(11) DEFAULT NULL,
  `WinnerHealth` int(11) DEFAULT NULL,
  `FirstPlayer` tinyint(4) DEFAULT NULL,
  `NumTurns` int(11) NOT NULL,
  `Format` int(11) DEFAULT NULL,
  `GameID` int(22) NOT NULL,
  `WinnerDeck` varchar(1000) DEFAULT NULL,
  `LoserDeck` varchar(1000) DEFAULT NULL,
  `lastAuthKey` varchar(128) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------
--
-- Table structure for table `favoritedeck`
--
CREATE TABLE `favoritedeck` (
  `decklink` varchar(128) NOT NULL,
  `usersId` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `hero` varchar(8) NOT NULL,
  `format` varchar(32) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------
--
-- Table structure for table `pwdreset`
--
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `usersId` int(11) NOT NULL AUTO_INCREMENT,
  `usersUid` varchar(128) NOT NULL,
  `usersEmail` varchar(128) NOT NULL,
  `usersPwd` varchar(128) NOT NULL,
  `rememberMeToken` varchar(64) DEFAULT NULL,
  `patreonAccessToken` varchar(64) DEFAULT NULL,
  `patreonRefreshToken` varchar(64) DEFAULT NULL,
  `fabraryId` varchar(64) DEFAULT NULL,
  `fabdbId` varchar(64) DEFAULT NULL,
  `patreonEnum` varchar(64) DEFAULT NULL,
  `isBanned` tinyint(1) NOT NULL DEFAULT 0,
  `lastLoggedIP` varchar(32) DEFAULT NULL,
  `lastGameName` int(11) NOT NULL DEFAULT 0,
  `lastPlayerId` int(11) NOT NULL DEFAULT 0,
  `lastAuthKey` varchar(128) DEFAULT NULL,
  `numSpectates` int(11) NOT NULL DEFAULT 0,
  `lastActivity` TIMESTAMP NULL DEFAULT NULL,
  `systemMessage` TEXT DEFAULT NULL,
  `metafyAccessToken` VARCHAR(500) DEFAULT NULL,
  `metafyRefreshToken` VARCHAR(500) DEFAULT NULL,
  `metafyCommunities` LONGTEXT DEFAULT NULL,
  `metafyID` VARCHAR(128) DEFAULT NULL,
  PRIMARY KEY (`usersId`),
  KEY `usersUid` (`usersUid`),
  KEY `idx_metafy_access_token` (`metafyAccessToken`),
  KEY `idx_rememberMeToken` (`rememberMeToken`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `completedgame`
--
ALTER TABLE `completedgame`
ADD PRIMARY KEY (`GameID`),
  ADD KEY `FK_WINNING_PLAYER` (`WinningPID`),
  ADD KEY `FK_LOSING_PLAYER` (`LosingPID`);
--
-- Indexes for table `favoritedeck`
--
ALTER TABLE `favoritedeck`
ADD PRIMARY KEY (`decklink`, `usersId`),
  ADD KEY `usersId` (`usersId`);

-- --------------------------------------------------------
--
-- Table structure for table `private_messages`
--
CREATE TABLE IF NOT EXISTS `private_messages` (
  `messageId` INT AUTO_INCREMENT PRIMARY KEY,
  `fromUserId` INT NOT NULL,
  `toUserId` INT NOT NULL,
  `message` LONGTEXT,
  `gameLink` VARCHAR(500),
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `isRead` BOOLEAN DEFAULT FALSE,
  INDEX `idx_from_to` (`fromUserId`, `toUserId`),
  INDEX `idx_to_unread` (`toUserId`, `isRead`),
  INDEX `idx_created` (`createdAt`),
  FOREIGN KEY (`fromUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE,
  FOREIGN KEY (`toUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------
--
-- Table structure for table `friends`
--
CREATE TABLE IF NOT EXISTS friends (
  friendshipId INT AUTO_INCREMENT PRIMARY KEY,
  userId INT NOT NULL,
  friendUserId INT NOT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('accepted', 'pending', 'blocked') DEFAULT 'accepted',
  nickname VARCHAR(50) DEFAULT NULL,
  FOREIGN KEY (userId) REFERENCES users(usersId) ON DELETE CASCADE,
  FOREIGN KEY (friendUserId) REFERENCES users(usersId) ON DELETE CASCADE,
  UNIQUE KEY unique_friendship (userId, friendUserId),
  CHECK (userId != friendUserId),
  INDEX idx_user_id (userId),
  INDEX idx_friend_user_id (friendUserId),
  INDEX idx_status (status),
  INDEX idx_nickname (nickname),
  INDEX idx_user_status_created (userId, status, createdAt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `blocked_users`
--
CREATE TABLE IF NOT EXISTS blocked_users (
  blockId INT AUTO_INCREMENT PRIMARY KEY,
  userId INT NOT NULL,
  blockedUserId INT NOT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (userId) REFERENCES users(usersId) ON DELETE CASCADE,
  FOREIGN KEY (blockedUserId) REFERENCES users(usersId) ON DELETE CASCADE,
  UNIQUE KEY unique_block (userId, blockedUserId),
  CHECK (userId != blockedUserId),
  INDEX idx_user_id (userId),
  INDEX idx_blocked_user_id (blockedUserId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `savedsettings`
--
CREATE TABLE IF NOT EXISTS `savedsettings` (
  `playerId` VARCHAR(32) NOT NULL,
  `settingNumber` VARCHAR(32) NOT NULL,
  `settingValue` TEXT,
  PRIMARY KEY (`playerId`, `settingNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `completedgame`
--
ALTER TABLE `completedgame`
MODIFY `GameID` int(22) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 96;


--
-- Constraints for table `completedgame`
--
ALTER TABLE `completedgame`
ADD CONSTRAINT `FK_LOSING_PLAYER` FOREIGN KEY (`LosingPID`) REFERENCES `users` (`usersId`),
  ADD CONSTRAINT `FK_WINNING_PLAYER` FOREIGN KEY (`WinningPID`) REFERENCES `users` (`usersId`);
COMMIT;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `carddefinition`
--

CREATE TABLE `carddefinition` (
  `cardID` varchar(16) NOT NULL,
  `hasGoAgain` int(11) NOT NULL DEFAULT 0,
  `playAbility` varchar(512) DEFAULT NULL,
  `hitEffect` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `carddefinition`
--
ALTER TABLE `carddefinition`
  ADD PRIMARY KEY (`cardID`);

--
-- Table structure for table `blocklist`
--
CREATE TABLE `blocklist` (
  `blockingPlayer` int(11) NOT NULL,
  `blockedPlayer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `blocklist`
--
ALTER TABLE `blocklist`
  ADD PRIMARY KEY (`blockingPlayer`,`blockedPlayer`),
  ADD KEY `blockingPlayer` (`blockingPlayer`);
