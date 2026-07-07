-- Per-deck cosmetic customization: playmat/card back chosen per favorited deck,
-- plus per-card alt art picks scoped to a specific deck.

ALTER TABLE favoritedeck
  ADD COLUMN cardBack VARCHAR(32) NOT NULL DEFAULT '0',
  ADD COLUMN playmat VARCHAR(32) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS deck_alt_arts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usersId INT NOT NULL,
  -- Collation must match favoritedeck.decklink exactly for the FK below to be valid.
  decklink VARCHAR(128) NOT NULL COLLATE utf8mb4_0900_ai_ci,
  cardId VARCHAR(64) NOT NULL,
  altPath VARCHAR(128) NOT NULL,
  UNIQUE KEY idx_user_deck_card (usersId, decklink, cardId),
  FOREIGN KEY (decklink, usersId) REFERENCES favoritedeck(decklink, usersId) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
