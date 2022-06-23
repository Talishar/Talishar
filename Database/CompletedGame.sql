-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 23, 2022 at 01:34 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fabonline`
--

-- --------------------------------------------------------

--
-- Table structure for table `completedgame`
--

CREATE TABLE `completedgame` (
  `CompletionTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `WinningHero` char(6) NOT NULL,
  `LosingHero` char(6) NOT NULL,
  `WinningPID` int(11) DEFAULT NULL,
  `LosingPID` int(11) DEFAULT NULL,
  `WinnerHealth` int(11) DEFAULT NULL,
  `NumTurns` int(11) NOT NULL,
  `Format` int(11) DEFAULT NULL,
  `GameID` int(22) NOT NULL
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `completedgame`
--
ALTER TABLE `completedgame`
  MODIFY `GameID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `completedgame`
--
ALTER TABLE `completedgame`
  ADD CONSTRAINT `FK_LOSING_PLAYER` FOREIGN KEY (`LosingPID`) REFERENCES `users` (`usersId`),
  ADD CONSTRAINT `FK_WINNING_PLAYER` FOREIGN KEY (`WinningPID`) REFERENCES `users` (`usersId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
