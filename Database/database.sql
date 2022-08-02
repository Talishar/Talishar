CREATE TABLE users (
  usersId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  usersUid varchar(128) NOT NULL,
  usersEmail varchar(128) NOT NULL,
  usersPwd varchar(128) NOT NULL,
  rememberMeToken varchar(256) NOT NULL
);

CREATE TABLE `favoritedeck` (
  `decklink` varchar(128) NOT NULL,
  `usersId` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `hero` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `favoritedeck`
  ADD PRIMARY KEY (`decklink`),
  ADD KEY `usersId` (`usersId`);

CREATE TABLE `completedgame` (
  `CompletionTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `WinningHero` char(6) NOT NULL,
  `LosingHero` char(6) NOT NULL,
  `WinningPID` int(11) DEFAULT NULL,
  `LosingPID` int(11) DEFAULT NULL,
  `WinnerHealth` int(11) DEFAULT NULL,
  `NumTurns` int(11) NOT NULL,
  `Format` int(11) DEFAULT NULL,
  `GameID` int(22) NOT NULL,
  `WinnerDeck` varchar(1000) DEFAULT NULL,
  `LoserDeck` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `completedgame`
  ADD PRIMARY KEY (`GameID`),
  ADD KEY `FK_WINNING_PLAYER` (`WinningPID`),
  ADD KEY `FK_LOSING_PLAYER` (`LosingPID`);

ALTER TABLE `completedgame`
  MODIFY `GameID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

ALTER TABLE `completedgame`
  ADD CONSTRAINT `FK_LOSING_PLAYER` FOREIGN KEY (`LosingPID`) REFERENCES `users` (`usersID`),
  ADD CONSTRAINT `FK_WINNING_PLAYER` FOREIGN KEY (`WinningPID`) REFERENCES `users` (`usersID`);
