<?php

// Serializes Find match so two players searching at once are paired instead of
// both hosting. Every decision runs under one APCu lock, on a queue kept in APCu
// next to the game list scan cache. The queue holds:
// hosts: players told to host who are still creating their lobby (gameName null)
//        or whose lobby is too new for the game list scan to have picked up.
// claims: lobbies just handed to a joiner, hidden from other searchers for a moment.
// Without APCu there is no queue: searchers join a fitting lobby or host one.
// Needs SHMOPLibraries.php, CacheLibraries.php and FindMatchLibraries.php.

const FIND_MATCH_QUEUE_KEY = 'find_match_queue';
const FIND_MATCH_QUEUE_LOCK_KEY = 'find_match_queue_lock';
const FIND_MATCH_HOST_CREATING_MS = 20000;
const FIND_MATCH_HOST_LISTED_MS = 120000;
const FIND_MATCH_CLAIM_MS = 10000;
const FIND_MATCH_WAIT_RETRY_MS = 1000;

// Runs $fn(&$queue) under the queue lock and saves the queue afterwards.
// Returns null when APCu is unavailable.
function WithFindMatchQueue(callable $fn)
{
  if (!_apcuAvailable()) return null;
  $locked = false;
  for ($try = 0; $try < 400 && !$locked; ++$try) {
    $locked = apcu_add(FIND_MATCH_QUEUE_LOCK_KEY, 1, 5);
    if (!$locked) usleep(5000);
  }
  try {
    $queue = apcu_fetch(FIND_MATCH_QUEUE_KEY);
    if (!is_array($queue)) $queue = [];
    $queue['hosts'] = is_array($queue['hosts'] ?? null) ? $queue['hosts'] : [];
    $queue['claims'] = is_array($queue['claims'] ?? null) ? $queue['claims'] : [];
    PruneFindMatchQueue($queue, round(microtime(true) * 1000));
    $result = $fn($queue);
    apcu_store(FIND_MATCH_QUEUE_KEY, $queue, 600);
    return $result;
  } finally {
    if ($locked) apcu_delete(FIND_MATCH_QUEUE_LOCK_KEY);
  }
}

function FindMatchLobbyStillOpen($gameName)
{
  $cache = ReadCacheArray($gameName);
  return $cache !== null && ($cache[7] ?? "") === "" && ($cache[13] ?? "0") === "0";
}

function PruneFindMatchQueue(&$queue, $now)
{
  foreach ($queue['hosts'] as $ticket => $host) {
    $age = $now - intval($host['at'] ?? 0);
    $gameName = $host['gameName'] ?? null;
    $expired = $gameName === null ? $age > FIND_MATCH_HOST_CREATING_MS : ($age > FIND_MATCH_HOST_LISTED_MS || !FindMatchLobbyStillOpen($gameName));
    if ($expired) unset($queue['hosts'][$ticket]);
  }
  foreach ($queue['claims'] as $gameName => $claim) {
    if ($now > intval($claim['until'] ?? 0)) unset($queue['claims'][$gameName]);
  }
}

function FindMatchHostAsLobby($host)
{
  return [
    'gameName' => (string)($host['gameName'] ?? ''),
    'p1uid' => $host['user'],
    'visibility' => "1",
    'format' => $host['format'],
    'hero' => $host['hero'] ?? '',
    'description' => $host['preference'] ?? '',
  ];
}

// One Find match step: ['action' => 'join', 'games' => [...]], ['action' => 'wait', 'retryMs' => n]
// or ['action' => 'host', 'ticket' => t]. $fits lists the fitting lobbies from the game list scan, oldest first;
// $exclude lists lobbies this search already failed to join.
function FindMatchQueueStep($viewer, $joiner, $matchFormat, $fits, $exclude = [])
{
  $step = WithFindMatchQueue(function (&$queue) use ($viewer, $joiner, $matchFormat, $fits, $exclude) {
    $now = round(microtime(true) * 1000);
    $userName = $joiner['userName'];
    foreach ($queue['hosts'] as $ticket => $host) {
      if ($host['user'] === $userName) unset($queue['hosts'][$ticket]);
    }

    $claimedByOthers = function ($gameName) use (&$queue, $userName) {
      $claim = $queue['claims'][(string)$gameName] ?? null;
      return $claim !== null && $claim['user'] !== $userName;
    };

    $games = [];
    $mustWait = false;
    foreach ($queue['hosts'] as $host) {
      if ($host['format'] !== $matchFormat) continue;
      if (!OpenGameVisibleToViewer($viewer, $host['user'], "1", $host['format'])) continue;
      if (count(FindMatchLobbyBlockers($joiner, FindMatchHostAsLobby($host))) > 0) continue;
      if ($host['gameName'] === null) {
        $mustWait = true;
        continue;
      }
      if (in_array(intval($host['gameName']), $exclude, true) || $claimedByOthers($host['gameName'])) continue;
      $games[] = intval($host['gameName']);
    }
    foreach ($fits as $gameName) {
      if (!in_array(intval($gameName), $games, true) && !$claimedByOthers($gameName)) $games[] = intval($gameName);
    }

    if (count($games) > 0) {
      sort($games);
      $games = array_slice($games, 0, 3);
      $queue['claims'][(string)$games[0]] = ['user' => $userName, 'until' => $now + FIND_MATCH_CLAIM_MS];
      return ['action' => 'join', 'games' => $games];
    }
    if ($mustWait) return ['action' => 'wait', 'retryMs' => FIND_MATCH_WAIT_RETRY_MS];

    $ticket = bin2hex(random_bytes(12));
    $queue['hosts'][$ticket] = [
      'user' => $userName,
      'format' => $matchFormat,
      'hero' => $joiner['heroCardId'],
      'preference' => $joiner['preferenceText'],
      'at' => $now,
      'gameName' => null,
    ];
    return ['action' => 'host', 'ticket' => $ticket];
  });
  if ($step !== null) return $step;
  return count($fits) > 0 ? ['action' => 'join', 'games' => array_slice($fits, 0, 3)] : ['action' => 'host', 'ticket' => ''];
}

// Called by JoinGame.php once the host's lobby exists.
function FindMatchQueueAttachGame($ticket, $userName, $gameName)
{
  $ticket = (string)$ticket;
  if ($ticket === '' || strval($userName) === '') return;
  WithFindMatchQueue(function (&$queue) use ($ticket, $userName, $gameName) {
    if (($queue['hosts'][$ticket]['user'] ?? null) !== $userName) return;
    $queue['hosts'][$ticket]['gameName'] = strval($gameName);
    $queue['hosts'][$ticket]['at'] = round(microtime(true) * 1000);
  });
}

function FindMatchQueueCancel($ticket, $userName)
{
  $ticket = (string)$ticket;
  if ($ticket === '') return;
  WithFindMatchQueue(function (&$queue) use ($ticket, $userName) {
    if (($queue['hosts'][$ticket]['user'] ?? null) === $userName) unset($queue['hosts'][$ticket]);
  });
}
