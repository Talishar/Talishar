-- One puzzle per UTC day, chosen by a moderator from puzzle_candidates.
-- mode: lethal (win this turn), damage (deal as much as you can), survive (live through the turn).
-- info: JSON frozen at scheduling time (life, heroes, hints, trick, solution, damage bars).
-- Note: the PHP code also creates these tables on demand (EnsureDailyPuzzleTables).

CREATE TABLE IF NOT EXISTS puzzle_daily (
  puzzle_date DATE NOT NULL,
  candidate_id INT UNSIGNED NOT NULL,
  mode VARCHAR(16) NOT NULL,
  info MEDIUMTEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (puzzle_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A player's official attempt at a daily puzzle. The first finished game locks the result; later games are practice.
-- tries counts restarts before the result locked, hints the hints revealed in any game for that day.
-- rating: -1 thumbs down, 0 none, 1 thumbs up.

CREATE TABLE IF NOT EXISTS puzzle_results (
  puzzle_date DATE NOT NULL,
  user_id INT NOT NULL,
  game_name INT UNSIGNED NOT NULL DEFAULT 0,
  hints TINYINT UNSIGNED NOT NULL DEFAULT 0,
  tries SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  finished TINYINT UNSIGNED NOT NULL DEFAULT 0,
  solved TINYINT UNSIGNED NOT NULL DEFAULT 0,
  damage SMALLINT NULL,
  stars TINYINT UNSIGNED NOT NULL DEFAULT 0,
  rating TINYINT NOT NULL DEFAULT 0,
  started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  finished_at TIMESTAMP NULL,
  PRIMARY KEY (puzzle_date, user_id),
  KEY date_damage (puzzle_date, finished, damage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
