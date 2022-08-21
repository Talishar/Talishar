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
  `LoserDeck` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `favoritedeck`
--

CREATE TABLE `favoritedeck` (
  `decklink` varchar(128) NOT NULL,
  `usersId` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `hero` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `pwdResetId` int(11) NOT NULL,
  `pwdResetEmail` text NOT NULL,
  `pwdResetSelector` text NOT NULL,
  `pwdResetToken` longtext NOT NULL,
  `pwdResetExpires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usersID` int(11) NOT NULL,
  `usersUid` varchar(128) NOT NULL,
  `usersEmail` varchar(128) NOT NULL,
  `usersPwd` varchar(128) NOT NULL,
  `rememberMeToken` varchar(64) DEFAULT NULL,
  `patreonAccessToken` varchar(64) DEFAULT NULL,
  `patreonRefreshToken` varchar(64) DEFAULT NULL,
  `usersKarma` TINYINT(3) NOT NULL DEFAULT '75',
  `greenThumbs` int(11) NOT NULL DEFAULT '0',
  `redThumbs` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


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
  ADD PRIMARY KEY (`decklink`),
  ADD KEY `usersId` (`usersId`);

--
-- Indexes for table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`pwdResetId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usersID`),
  ADD KEY `usersUid` (`usersUid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `completedgame`
--
ALTER TABLE `completedgame`
  MODIFY `GameID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `pwdResetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usersID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `completedgame`
--
ALTER TABLE `completedgame`
  ADD CONSTRAINT `FK_LOSING_PLAYER` FOREIGN KEY (`LosingPID`) REFERENCES `users` (`usersID`),
  ADD CONSTRAINT `FK_WINNING_PLAYER` FOREIGN KEY (`WinningPID`) REFERENCES `users` (`usersID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 21, 2022 at 09:57 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `badges` (
  `badgeId` int(11) NOT NULL,
  `topText` varchar(128) DEFAULT NULL,
  `bottomText` varchar(128) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`badgeId`, `topText`, `bottomText`, `image`) VALUES
(1, 'Commotion #1', 'Wins: {0}', './concat/WTR175.webp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badgeId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `badgeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;


CREATE TABLE `playerbadge` (
  `playerId` int(11) NOT NULL,
  `badgeId` int(11) NOT NULL,
  `intVariable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playerbadge`
--

--
-- Indexes for table `playerbadge`
--
ALTER TABLE `playerbadge`
  ADD PRIMARY KEY (`playerId`,`badgeId`);
COMMIT;
