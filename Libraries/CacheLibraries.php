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

/**
 * Get gamestate with APCu caching
 * Caches for 1 second (plenty of time for concurrent requests)
 */
function GetCachedGamestate($gameName) {
  $cacheKey = "gamestate_" . md5($gameName);
  
  // Try APCu if available
  if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    if (function_exists('apcu_fetch')) {
      $cached = @apcu_fetch($cacheKey);
      if ($cached !== false) {
        return $cached;
      }
    }
  }
  
  // Fall back to reading from file
  global $filename;
  if (!isset($filename) || !str_contains($filename, "gamestate.txt")) {
    $filename = "./Games/" . $gameName . "/gamestate.txt";
  }
  
  $content = ReadCache(GamestateID($gameName));
  
  // Store in APCu for subsequent requests (1 second TTL)
  if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    if (function_exists('apcu_store')) {
      @apcu_store($cacheKey, $content, 1);
    }
  }
  
  return $content;
}

/**
 * Invalidate gamestate cache when it changes
 * Call this after WriteGamestate
 */
function InvalidateGamestateCache($gameName) {
  $cacheKey = "gamestate_" . md5($gameName);
  
  if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    if (function_exists('apcu_delete')) {
      @apcu_delete($cacheKey);
    }
  }
}

function UpdateSpectatorPresence($gameName, $userName = 'Anonymous') {
  $now = time();
  // Sanitize username: alphanumeric, underscores, hyphens, max 30 chars
  $userName = preg_replace('/[^a-zA-Z0-9_\-]/', '', substr($userName, 0, 30));
  if ($userName === '') $userName = 'Anonymous';

  if (extension_loaded('apcu') && ini_get('apc.enabled')) {
    $key = 'spectators_' . $gameName;
    $spectators = @apcu_fetch($key);
    if (!is_array($spectators)) $spectators = [];
    // Store as username => timestamp (latest timestamp per user)
    $spectators[$userName] = $now;
    foreach ($spectators as $name => $lastSeen) {
      if ($now - $lastSeen > 60) unset($spectators[$name]);
    }
    @apcu_store($key, $spectators, 120);
    return;
  }
}

function GetActiveSpectators($gameName) {
  $now = time();
  $threshold = 45;
  $names = [];

  if (extension_loaded('apcu') && ini_get('apc.enabled')) {
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
}
