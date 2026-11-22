INSERT INTO hero_mastery (userId, heroId, qualifyingGames)
  SELECT src.userId, src.targetHeroId, src.games
  FROM (
    SELECT userId, IF(heroId = 'OBK406', 'OBK006', 'OBK007') AS targetHeroId, qualifyingGames AS games
    FROM hero_mastery
    WHERE heroId IN ('OBK406', 'OBK407')
  ) AS src
  ON DUPLICATE KEY UPDATE qualifyingGames = qualifyingGames + src.games;

DELETE FROM hero_mastery WHERE heroId IN ('OBK406', 'OBK407');

UPDATE hero_mastery_awards SET heroId = 'OBK006' WHERE heroId = 'OBK406';
UPDATE hero_mastery_awards SET heroId = 'OBK007' WHERE heroId = 'OBK407';
