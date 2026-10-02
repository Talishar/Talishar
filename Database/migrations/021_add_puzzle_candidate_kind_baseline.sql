-- kind: 0 = "win this turn" (harvested from the winner's last turn), 1 = "survive this turn" (harvested from a turn
-- the defender only just lived through; player is then the defender and opponent_life is the defender's own life).
-- A survive row's winning_line is gzcompress'd JSON {"line": defender inputs, "script": attacker inputs}.
-- baseline: JSON from the bot playing the solver's side at the proven life, plus the cards the real line used,
-- e.g. {"v":1,"life":9,"real":{"played":[],"pitched":[],"blocked":[]},"bot":{"won":false,"damage":6,...}}.
-- Note: the PHP code also adds these columns on demand (EnsurePuzzleCandidatesTable).

ALTER TABLE puzzle_candidates ADD COLUMN kind TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER format;
ALTER TABLE puzzle_candidates ADD COLUMN baseline TEXT NULL;
