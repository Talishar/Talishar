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

/**
 * Reuse an encoded response when multiple connections share the same viewer
 * and visibility variant for a game update. Caching the wire representation
 * avoids rebuilding it with json_encode() on every equivalent SSE connection.
 */
function GetCachedGameStateResponse($gameName, $updateNumber, $viewerVariant, $inactive) {
  if (!_apcuAvailable() || !is_string($viewerVariant) || $viewerVariant === '') return false;
  $key = "game_response_{$gameName}_" . hash('sha256', $viewerVariant);
  $cached = @apcu_fetch($key);
  if (!is_array($cached)) return false;
  if (($cached['update'] ?? null) !== (int)$updateNumber) return false;
  if (($cached['inactive'] ?? null) !== (bool)$inactive) return false;
  $response = $cached['response'] ?? false;
  return is_string($response) ? $response : false;
}

function StoreCachedGameStateResponse($gameName, $updateNumber, $viewerVariant, $inactive, $response) {
  if (!_apcuAvailable() || !is_string($viewerVariant) || $viewerVariant === '' || !is_string($response)) return;
  $key = "game_response_{$gameName}_" . hash('sha256', $viewerVariant);
  @apcu_store($key, [
    'update' => (int)$updateNumber,
    'inactive' => (bool)$inactive,
    'response' => $response,
  ], 300);
}

function RecordPerformanceMetric($name, $durationMs, $context = []): void {
  if (strtolower((string)getenv('PERFORMANCE_METRICS_ENABLED')) !== 'true') return;
  $sampleRate = (float)(getenv('PERFORMANCE_METRICS_SAMPLE_RATE') ?: 1.0);
  $sampleRate = max(0.0, min(1.0, $sampleRate));
  if ($sampleRate < 1.0 && mt_rand() / mt_getrandmax() > $sampleRate) return;
  error_log(json_encode([
    'type' => 'performance',
    'metric' => (string)$name,
    'durationMs' => round((float)$durationMs, 3),
    'context' => $context,
  ], JSON_UNESCAPED_SLASHES));
}

function UpdateSpectatorPresence($gameName, $userName = null) {
  if (!_apcuAvailable()) return;
  if (!is_string($userName) || trim($userName) === '') return;
  $now = time();
  // Sanitize username: alphanumeric, underscores, hyphens, max 30 chars
  $userName = preg_replace('/[^a-zA-Z0-9_\-]/', '', substr($userName, 0, 30));
  if ($userName === '' || strcasecmp($userName, 'Anonymous') === 0) return;

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
    if (
      is_string($name)
      && $name !== ''
      && strcasecmp($name, 'Anonymous') !== 0
      && $now - $lastSeen <= $threshold
    ) {
      $names[] = $name;
    }
  }
  return ['count' => count($names), 'names' => $names];
}
