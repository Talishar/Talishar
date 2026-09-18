<?php

include_once __DIR__ . '/CacheLibraries.php';
include_once __DIR__ . '/HeroMastery.php';

const FEATURED_CACHE_KEY = 'featured_game_pick';
const FEATURED_CACHE_TTL = 30;          // seconds a pick is reused server-wide
const FEATURED_MAX_PICKS = 3;           // how many matches the list pins at once
const FEATURED_MAX_WATCHED = 8;         // caps the players queried from games that drew a crowd
const FEATURED_MAX_QUIET = 16;          // ...and from games nobody is watching yet
const FEATURED_MAX_IDLE_SECONDS = 90;   // a stalled game is not worth watching
const FEATURED_MIN_SPECTATORS = 3;      // a crowd qualifies on its own
const FEATURED_MASTERY_FLOOR = 5;       // mastery level 5 is 35+ games on that hero

function FeaturedGameQualifies(int $spectators, int $masteryFloor, int $secondsIdle): bool
{
  if ($secondsIdle > FEATURED_MAX_IDLE_SECONDS) return false;
  if ($spectators >= FEATURED_MIN_SPECTATORS) return true;
  return $masteryFloor >= FEATURED_MASTERY_FLOOR;
}

function FeaturedGameScore(int $spectators, int $masteryFloor): int
{
  return ($spectators * 10) + ($masteryFloor * 5);
}

function FeaturedMasteryLevels(array $pairs): array
{
  $levels = [];
  $userIds = [];
  $heroIds = [];
  foreach ($pairs as $pair) {
    $userId = intval($pair[0]);
    $heroId = trim((string)$pair[1]);
    if ($userId <= 0 || $heroId === '') continue;
    $userIds[$userId] = true;
    $heroIds[$heroId] = true;
  }
  if (empty($userIds) || empty($heroIds)) return $levels;

  $userIds = array_keys($userIds);
  $heroIds = array_keys($heroIds);
  $conn = GetDBConnection(DBL_GET_GAME_LIST);
  if (!$conn) return $levels;

  try {
    $userPlaceholders = implode(',', array_fill(0, count($userIds), '?'));
    $heroPlaceholders = implode(',', array_fill(0, count($heroIds), '?'));
    $query = "SELECT userId, heroId, qualifyingGames, displayLevel FROM hero_mastery
              WHERE userId IN ($userPlaceholders) AND heroId IN ($heroPlaceholders)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
      $types = str_repeat('i', count($userIds)) . str_repeat('s', count($heroIds));
      $stmt->bind_param($types, ...array_merge($userIds, $heroIds));
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        $key = intval($row['userId']) . ':' . $row['heroId'];
        $levels[$key] = HeroMasteryFrameLevel(
          HeroMasteryLevel(intval($row['qualifyingGames'])),
          $row['displayLevel']
        );
      }
      $stmt->close();
    }
  } catch (\Exception $e) {
    error_log('FeaturedGame: mastery query failed: ' . $e->getMessage());
  }
  mysqli_close($conn);
  return $levels;
}

function FeaturedShortlist(array $candidates): array
{
  $watched = [];
  $quiet = [];
  foreach ($candidates as $candidate) {
    if (intval($candidate['secondsIdle']) > FEATURED_MAX_IDLE_SECONDS) continue;
    if (intval($candidate['p1id']) <= 0 || intval($candidate['p2id']) <= 0) continue;
    if (intval($candidate['spectators']) > 0) $watched[] = $candidate;
    else $quiet[] = $candidate;
  }
  usort($watched, function ($a, $b) {
    return intval($b['spectators']) <=> intval($a['spectators']);
  });
  usort($quiet, function ($a, $b) {
    return intval($a['secondsIdle']) <=> intval($b['secondsIdle']);
  });
  return array_merge(
    array_slice($watched, 0, FEATURED_MAX_WATCHED),
    array_slice($quiet, 0, FEATURED_MAX_QUIET)
  );
}

function SelectFeaturedGames(array $candidates): array
{
  if (!_apcuAvailable()) return [];

  $cached = @apcu_fetch(FEATURED_CACHE_KEY);
  if (is_array($cached)) return is_array($cached['picks'] ?? null) ? $cached['picks'] : [];

  $picks = [];
  $shortlist = FeaturedShortlist($candidates);
  if (!empty($shortlist)) {
    $pairs = [];
    foreach ($shortlist as $candidate) {
      $pairs[] = [$candidate['p1id'], $candidate['p1Hero']];
      $pairs[] = [$candidate['p2id'], $candidate['p2Hero']];
    }
    $levels = FeaturedMasteryLevels($pairs);

    foreach ($shortlist as $candidate) {
      $spectators = intval($candidate['spectators']);
      $secondsIdle = intval($candidate['secondsIdle']);
      $masteryFloor = min(
        $levels[intval($candidate['p1id']) . ':' . $candidate['p1Hero']] ?? 0,
        $levels[intval($candidate['p2id']) . ':' . $candidate['p2Hero']] ?? 0
      );
      if (!FeaturedGameQualifies($spectators, $masteryFloor, $secondsIdle)) continue;

      $picks[] = [
        'gameName' => (string)$candidate['gameName'],
        'spectators' => $spectators,
        'masteryLevel' => $masteryFloor,
        'score' => FeaturedGameScore($spectators, $masteryFloor),
        'secondsIdle' => $secondsIdle,
      ];
    }

    usort($picks, function ($a, $b) {
      if ($a['score'] !== $b['score']) return $b['score'] <=> $a['score'];
      return $a['secondsIdle'] <=> $b['secondsIdle'];
    });
    $picks = array_slice($picks, 0, FEATURED_MAX_PICKS);
  }

  @apcu_store(FEATURED_CACHE_KEY, ['picks' => $picks], FEATURED_CACHE_TTL);
  return $picks;
}
