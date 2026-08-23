<?php

include_once __DIR__ . '/CacheLibraries.php';
include_once __DIR__ . '/HeroMastery.php';

const FEATURED_CACHE_KEY = 'featured_game_pick';
const FEATURED_CACHE_TTL = 30;          // seconds a pick is reused server-wide
const FEATURED_MAX_CANDIDATES = 8;      // caps how many players the query touches
const FEATURED_MAX_IDLE_SECONDS = 90;   // a stalled game is not worth watching
const FEATURED_MIN_SPECTATORS = 3;      // a crowd qualifies on its own
const FEATURED_SPECTATOR_FLOOR = 1;     // ...otherwise one watcher plus two veterans
const FEATURED_MASTERY_FLOOR = 5;       // mastery level 5 is 35+ games on that hero

function FeaturedGameQualifies(int $spectators, int $masteryFloor, int $secondsIdle): bool
{
  if ($secondsIdle > FEATURED_MAX_IDLE_SECONDS) return false;
  if ($spectators >= FEATURED_MIN_SPECTATORS) return true;
  return $spectators >= FEATURED_SPECTATOR_FLOOR && $masteryFloor >= FEATURED_MASTERY_FLOOR;
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

function SelectFeaturedGame(array $candidates)
{
  if (!_apcuAvailable()) return null;

  $cached = @apcu_fetch(FEATURED_CACHE_KEY);
  if (is_array($cached)) return $cached['pick'];

  $shortlist = [];
  foreach ($candidates as $candidate) {
    if (intval($candidate['spectators']) < FEATURED_SPECTATOR_FLOOR) continue;
    if (intval($candidate['secondsIdle']) > FEATURED_MAX_IDLE_SECONDS) continue;
    if (intval($candidate['p1id']) <= 0 || intval($candidate['p2id']) <= 0) continue;
    $shortlist[] = $candidate;
  }
  usort($shortlist, function ($a, $b) {
    return intval($b['spectators']) <=> intval($a['spectators']);
  });
  $shortlist = array_slice($shortlist, 0, FEATURED_MAX_CANDIDATES);

  $pick = null;
  if (!empty($shortlist)) {
    $pairs = [];
    foreach ($shortlist as $candidate) {
      $pairs[] = [$candidate['p1id'], $candidate['p1Hero']];
      $pairs[] = [$candidate['p2id'], $candidate['p2Hero']];
    }
    $levels = FeaturedMasteryLevels($pairs);

    $bestScore = -1;
    foreach ($shortlist as $candidate) {
      $spectators = intval($candidate['spectators']);
      $secondsIdle = intval($candidate['secondsIdle']);
      $masteryFloor = min(
        $levels[intval($candidate['p1id']) . ':' . $candidate['p1Hero']] ?? 0,
        $levels[intval($candidate['p2id']) . ':' . $candidate['p2Hero']] ?? 0
      );
      if (!FeaturedGameQualifies($spectators, $masteryFloor, $secondsIdle)) continue;

      $score = FeaturedGameScore($spectators, $masteryFloor);
      if ($score > $bestScore) {
        $bestScore = $score;
        $pick = [
          'gameName' => (string)$candidate['gameName'],
          'spectators' => $spectators,
          'masteryLevel' => $masteryFloor,
        ];
      }
    }
  }

  @apcu_store(FEATURED_CACHE_KEY, ['pick' => $pick], FEATURED_CACHE_TTL);
  return $pick;
}
