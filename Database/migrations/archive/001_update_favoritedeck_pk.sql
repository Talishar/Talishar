-- Original file: Database/migrations/001_update_favoritedeck_pk.sql
--
-- Drop the old primary key
ALTER TABLE `favoritedeck` DROP PRIMARY KEY;

-- Add the new composite primary key
ALTER TABLE `favoritedeck` ADD PRIMARY KEY (`decklink`, `usersId`);
