-- Daily counts of decision prompts answered by players, grouped by prompt type,
-- the card that caused it, the answer given, the option count, and whether every
-- option was the same card. Read by the mod page Prompts tab.
-- Note: the PHP code also creates this table on demand (EnsurePromptStatsTable).

CREATE TABLE IF NOT EXISTS prompt_stats (
  day DATE NOT NULL,
  phase VARCHAR(32) NOT NULL,
  context VARCHAR(96) NOT NULL,
  answer VARCHAR(64) NOT NULL,
  options TINYINT UNSIGNED NOT NULL,
  identical TINYINT UNSIGNED NOT NULL,
  count INT UNSIGNED NOT NULL DEFAULT 0,
  total_ms BIGINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (day, phase, context, answer, options, identical)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
