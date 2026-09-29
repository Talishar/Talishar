-- winning_line: gzcompress'd JSON of the winner's inputs on the harvested turn, undos removed.
-- proof: JSON result of replaying that line against the puzzle bot (APIs/VerifyPuzzleCandidates.php),
-- e.g. {"v":1,"status":"proven","life":9,...}; empty until the candidate has been checked.
-- Note: the PHP code also adds these columns on demand (EnsurePuzzleCandidatesTable).

ALTER TABLE puzzle_candidates ADD COLUMN proof VARCHAR(1024) NOT NULL DEFAULT '' AFTER meta;
ALTER TABLE puzzle_candidates ADD COLUMN winning_line MEDIUMBLOB NULL AFTER proof;
