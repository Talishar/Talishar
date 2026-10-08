<?php

include_once __DIR__ . '/CacheLibraries.php';

// A separate entry from the game-discovery sentinel. Limits are configurable
// before including this library. Only writes refresh TTL; missing logs are empty.
if (!defined('LIVE_GAME_LOG_TTL')) define('LIVE_GAME_LOG_TTL', 5 * 60);
if (!defined('LIVE_GAME_LOG_MAX_BYTES')) define('LIVE_GAME_LOG_MAX_BYTES', 1024 * 1024);
if (!defined('LIVE_GAME_LOG_READ_BYTES')) define('LIVE_GAME_LOG_READ_BYTES', 131072);
if (!defined('LIVE_GAME_LOG_LOCK_WAIT')) define('LIVE_GAME_LOG_LOCK_WAIT', 1);

function LiveGameLogKey($directory)
{
  return 'talishar_game_log_v1_' . basename(rtrim($directory, '/\\'));
}

function ReadLiveGameLog($directory)
{
  $record = _apcuAvailable() ? @apcu_fetch(LiveGameLogKey($directory)) : false;
  return is_array($record) && is_string($record['content'] ?? null)
    && is_int($record['offset'] ?? null) ? $record : ['content' => '', 'offset' => 0];
}

function BoundLiveGameLog($record)
{
  $drop = strlen($record['content']) - LIVE_GAME_LOG_MAX_BYTES;
  if ($drop > 0) {
    $tail = substr($record['content'], $drop);
    if (($newline = strpos($tail, "\n")) !== false) {
      $drop += $newline + 1;
      $tail = substr($tail, $newline + 1);
    }
    $record['offset'] += $drop;
    $record['content'] = $tail;
  }
  return $record;
}

function UpdateLiveGameLog($directory, $update)
{
  if (!_apcuAvailable() || !is_dir($directory)) return false;
  $lock = @fopen(rtrim($directory, '/\\') . '/log.lock', 'c');
  $locked = $lock !== false && AcquireLiveGameLogLock($lock);
  if (!$locked) error_log('Live game log updated without lock: ' . $directory);
  try {
    $record = BoundLiveGameLog($update(ReadLiveGameLog($directory)));
    return @apcu_store(LiveGameLogKey($directory), $record, LIVE_GAME_LOG_TTL);
  } finally {
    if ($locked) flock($lock, LOCK_UN);
    if ($lock !== false) fclose($lock);
  }
}

function AcquireLiveGameLogLock($lock)
{
  $deadline = hrtime(true) + (int)(LIVE_GAME_LOG_LOCK_WAIT * 1e9);
  while (!flock($lock, LOCK_EX | LOCK_NB, $wouldBlock)) {
    if (!$wouldBlock || hrtime(true) >= $deadline) return false;
    usleep(2000);
  }
  return true;
}

function AppendLiveGameLog($directory, $content)
{
  return UpdateLiveGameLog($directory, function ($record) use ($content) {
    $record['content'] .= $content;
    return $record;
  });
}

function ReplaceLiveGameLog($directory, $content = '')
{
  if (function_exists('FlushLogBuffer')) FlushLogBuffer();
  return UpdateLiveGameLog($directory, fn($record) => ['content' => $content, 'offset' => 0]);
}

function TrimLiveGameLog($directory, $lineCount)
{
  return UpdateLiveGameLog($directory, function ($record) use ($lineCount) {
    $excess = substr_count($record['content'], "\n") - max(0, (int)$lineCount);
    $cut = 0;
    for ($i = 0; $i < $excess; ++$i) {
      $newline = strpos($record['content'], "\n", $cut);
      if ($newline === false) break;
      $cut = $newline + 1;
    }
    $record['content'] = substr($record['content'], $cut);
    $record['offset'] += $cut;
    return $record;
  });
}

function TruncateLiveGameLogAboveMarker($directory, $markers)
{
  return UpdateLiveGameLog($directory, function ($record) use ($markers) {
    $lines = explode("\n", $record['content']);
    $keepFrom = count($lines);
    for ($i = count($lines) - 1; $i >= 0; --$i) {
      foreach ($markers as $marker) {
        if (strpos($lines[$i], $marker) !== false) {
          $keepFrom = $i;
          break 2;
        }
      }
    }
    $record['content'] = implode("\n", array_slice($lines, $keepFrom));
    $record['offset'] = 0;
    return $record;
  });
}

function LiveGameLogWindow($directory)
{
  $record = ReadLiveGameLog($directory);
  $content = $record['content'];
  $drop = max(0, strlen($content) - LIVE_GAME_LOG_READ_BYTES);
  $content = substr($content, $drop);
  if ($drop > 0 && ($newline = strpos($content, "\n")) !== false) {
    $drop += $newline + 1;
    $content = substr($content, $newline + 1);
  }
  return [$record['offset'] + $drop, $content];
}

function ExportLiveGameLog($directory, $destination)
{
  if (function_exists('FlushLogBuffer')) FlushLogBuffer();
  $record = ReadLiveGameLog($directory);
  return file_put_contents($destination, $record['content'], LOCK_EX) !== false;
}

function DeleteLiveGameLog($gameName)
{
  if (_apcuAvailable()) @apcu_delete('talishar_game_log_v1_' . $gameName);
}
