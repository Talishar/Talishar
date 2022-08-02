CREATE TABLE users (
  usersId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  usersUid varchar(128) NOT NULL,
  usersEmail varchar(128) NOT NULL,
  usersPwd varchar(128) NOT NULL
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
COMMIT;
