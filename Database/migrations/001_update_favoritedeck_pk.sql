-- Drop the old primary key
ALTER TABLE `favoritedeck` DROP PRIMARY KEY;

-- Add the new composite primary key
ALTER TABLE `favoritedeck` ADD PRIMARY KEY (`decklink`, `usersId`);
