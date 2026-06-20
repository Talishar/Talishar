<?php

// APCu function stubs for IDE/linter support (actual functions provided by APCu extension)
if (!function_exists('apcu_fetch')) {
  function apcu_fetch($key) { return false; }
  function apcu_store($key, $var, $ttl = 0) { return false; }
  function apcu_delete($key) { return false; }
  function apcu_exists($key) { return false; }
  function apcu_cache_info() { return []; }
}

/**
 * CacheLibraries.php - In-memory caching using APCu
 * 
 * Reduces repeated parsing of identical gamestate data
 * Falls back to normal operation if APCu not available
 * 
 * Expected impact: 30-40% latency reduction on GetNextTurn
 * 
 * Install APCu (Recommended for Performance)
 * Install the APCu PHP extension on your production server:
 *   sudo apt-get install php-apcu
 *   sudo systemctl restart php-fpm
 */

function _apcuAvailable(): bool {
  static $v = null;
  if ($v === null) {
    $v = extension_loaded('apcu') && ini_get('apc.enabled') && function_exists('apcu_fetch');
  }
  return $v;
}

/**
 * Get gamestate with APCu caching
 * Caches for 1 second (plenty of time for concurrent requests)
 */
function GetCachedGamestate($gameName) {
  $cacheKey = "gamestate_" . $gameName;

  if (_apcuAvailable()) {
    $cached = @apcu_fetch($cacheKey);
    if ($cached !== false) {
      return $cached;
    }
  }

  $content = ReadGamestateCache($gameName);

  if (_apcuAvailable()) {
    @apcu_store($cacheKey, $content, 1);
  }

  return $content;
}

/**
 * Invalidate gamestate cache when it changes
 * Call this after WriteGamestate
 */
function InvalidateGamestateCache($gameName) {
  if (!_apcuAvailable()) return;
  @apcu_delete("gamestate_" . $gameName);
}

function UpdateSpectatorPresence($gameName, $userName = 'Anonymous') {
  if (!_apcuAvailable()) return;
  $now = time();
  // Sanitize username: alphanumeric, underscores, hyphens, max 30 chars
  $userName = preg_replace('/[^a-zA-Z0-9_\-]/', '', substr($userName, 0, 30));
  if ($userName === '') $userName = 'Anonymous';

  $key = 'spectators_' . $gameName;
  $spectators = @apcu_fetch($key);
  if (!is_array($spectators)) $spectators = [];
  $spectators[$userName] = $now;
  foreach ($spectators as $name => $lastSeen) {
    if ($now - $lastSeen > 60) unset($spectators[$name]);
  }
  @apcu_store($key, $spectators, 120);
}

function GetActiveSpectators($gameName) {
  if (!_apcuAvailable()) return ['count' => 0, 'names' => []];
  $now = time();
  $threshold = 45;
  $names = [];

  $key = 'spectators_' . $gameName;
  $spectators = @apcu_fetch($key);
  if (!is_array($spectators)) return ['count' => 0, 'names' => []];
  foreach ($spectators as $name => $lastSeen) {
    if ($now - $lastSeen <= $threshold) {
      $names[] = is_string($name) ? $name : 'Anonymous';
    }
  }
  return ['count' => count($names), 'names' => $names];
}
