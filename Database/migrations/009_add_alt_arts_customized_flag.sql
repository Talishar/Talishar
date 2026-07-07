-- Distinguishes "never touched the deck customization page" (fall back to the
-- legacy full alt-art pool) from "explicitly saved a selection" (use exactly
-- what's in deck_alt_arts, even if that means no alt arts at all).

ALTER TABLE favoritedeck
  ADD COLUMN altArtsCustomized TINYINT(1) NOT NULL DEFAULT 0;
