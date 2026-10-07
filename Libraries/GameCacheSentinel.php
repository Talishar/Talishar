<?php

// Discovery only: the game data remains in shmop. No expiry is needed because
// DeleteCache removes the sentinel and the listing prunes missing shmop records.
const GAME_CACHE_SENTINEL_PREFIX = 'talishar_game_shm_v1_';

function GameCacheSentinelsAvailable()
{
  return extension_loaded('apcu') && ini_get('apc.enabled')
    && function_exists('apcu_add') && function_exists('apcu_delete');
}

function PublishGameCacheSentinel($gameName)
{
  if (!GameCacheSentinelsAvailable()) return;
  if (!preg_match('/^[1-9][0-9]*$/D', (string)$gameName)) return;
  // add avoids replacing the entry on every heartbeat, but repopulates it
  // automatically on the next write after an APCu eviction or cache clear.
  @apcu_add(GAME_CACHE_SENTINEL_PREFIX . $gameName, true, 0);
}

function DeleteGameCacheSentinel($gameName)
{
  if (!GameCacheSentinelsAvailable()) return;
  @apcu_delete(GAME_CACHE_SENTINEL_PREFIX . $gameName);
}

function APCuGameSentinelTokens()
{
  $pattern = '/^' . preg_quote(GAME_CACHE_SENTINEL_PREFIX, '/') . '[1-9][0-9]*$/D';
  foreach (new APCUIterator($pattern, APC_ITER_KEY) as $entry) {
    yield substr($entry['key'], strlen(GAME_CACHE_SENTINEL_PREFIX));
  }
}
