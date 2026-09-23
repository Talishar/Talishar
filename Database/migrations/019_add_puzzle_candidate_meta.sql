-- Stats of the winning turn, captured when the candidate is harvested:
-- {"threatened","dealt","cardsPlayed","pitched","resourcesUsed","blocked","cardsBlocked","overkill"}.
-- Rows harvested before this column existed keep an empty string.
-- Note: the PHP code also adds this column on demand (EnsurePuzzleCandidatesTable).

ALTER TABLE puzzle_candidates ADD COLUMN meta VARCHAR(512) NOT NULL DEFAULT '' AFTER note;
