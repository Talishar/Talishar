-- Start-of-turn positions from games the winner finished on their own turn.
-- Each row is a real "win this turn" position that a daily puzzle can be built from.
-- status: 0 = new, 1 = used for a puzzle, 2 = rejected by the generator (reason in note).
-- gamestate is the gzcompress'd beginTurnGamestate.txt of the winning turn.
-- Note: the PHP code also creates this table on demand (EnsurePuzzleCandidatesTable).

CREATE TABLE IF NOT EXISTS puzzle_candidates (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  game_name INT UNSIGNED NOT NULL,
  format VARCHAR(16) NOT NULL,
  turn_number SMALLINT UNSIGNED NOT NULL,
  player TINYINT UNSIGNED NOT NULL,
  hero VARCHAR(64) NOT NULL,
  opponent_hero VARCHAR(64) NOT NULL,
  opponent_life SMALLINT NOT NULL,
  hand_count TINYINT UNSIGNED NOT NULL,
  opponent_hand_count TINYINT UNSIGNED NOT NULL,
  status TINYINT UNSIGNED NOT NULL DEFAULT 0,
  note VARCHAR(255) NOT NULL DEFAULT '',
  gamestate MEDIUMBLOB NOT NULL,
  PRIMARY KEY (id),
  KEY status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
