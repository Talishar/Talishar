<?php

include_once __DIR__ . '/CacheLibraries.php';

if (!defined('ROLLBACK_STATE_TTL')) define('ROLLBACK_STATE_TTL', 300);

// Temporary checkpoints share one entry so each write keeps the whole active
// undo history alive. Missing entries do not fall back to old snapshot files.

function RollbackStateKey($directory)
{
  return 'talishar_rollback_v1_' . basename(rtrim($directory, '/\\'));
}

function IsRollbackSnapshot($filename)
{
  return in_array(basename($filename), [
    'gamestateBackup.txt', 'beginTurnGamestate.txt', 'lastTurnGamestate.txt',
    'preBlockBackup.txt', 'startChainLinkGamestate.txt'
  ], true) || preg_match('/^(?:gamestateBackup|replayStep)_\d+\.txt$/D', basename($filename)) === 1;
}

function ReadRollbackStates($directory)
{
  $states = _apcuAvailable() ? @apcu_fetch(RollbackStateKey($directory)) : false;
  return is_array($states) ? $states : [];
}

function StoreRollbackStates($directory, $states)
{
  if (!_apcuAvailable() || !is_dir($directory)) return false;
  return @apcu_store(RollbackStateKey($directory), $states, ROLLBACK_STATE_TTL);
}

function ReadRollbackSnapshot($filename)
{
  if (!IsRollbackSnapshot($filename)) return @file_get_contents($filename);
  $states = ReadRollbackStates(dirname($filename));
  return $states[basename($filename)] ?? false;
}

function RollbackSnapshotExists($filename)
{
  return IsRollbackSnapshot($filename) ? ReadRollbackSnapshot($filename) !== false : is_file($filename);
}

function WriteRollbackSnapshot($filename, $content)
{
  if (!IsRollbackSnapshot($filename)) return file_put_contents($filename, $content) !== false;
  $directory = dirname($filename);
  $states = ReadRollbackStates($directory);
  $states[basename($filename)] = $content;
  return StoreRollbackStates($directory, $states);
}

function ExportRollbackSnapshot($source, $destination)
{
  $content = ReadRollbackSnapshot($source);
  return $content !== false && file_put_contents($destination, $content) !== false;
}

function PushUndoState($directory, $content)
{
  $states = ReadRollbackStates($directory);
  if (($states['gamestateBackup_0.txt'] ?? false) === $content) return StoreRollbackStates($directory, $states);
  for ($i = MAX_UNDO_BACKUPS - 1; $i > 0; --$i) {
    $source = 'gamestateBackup_' . ($i - 1) . '.txt';
    $target = "gamestateBackup_$i.txt";
    if (isset($states[$source])) $states[$target] = $states[$source];
    else unset($states[$target]);
  }
  $states['gamestateBackup_0.txt'] = $content;
  return StoreRollbackStates($directory, $states);
}

function ConsumeUndoStates($directory, $stepsBack, $restoredState)
{
  $states = ReadRollbackStates($directory);
  for ($i = 0; $i < MAX_UNDO_BACKUPS; ++$i) {
    $source = 'gamestateBackup_' . ($i + $stepsBack) . '.txt';
    $target = "gamestateBackup_$i.txt";
    if ($i + $stepsBack < MAX_UNDO_BACKUPS && isset($states[$source])) $states[$target] = $states[$source];
    else unset($states[$target]);
  }
  $states['gamestateBackup.txt'] = $restoredState;
  return StoreRollbackStates($directory, $states);
}

function RotateTurnRollbackState($directory)
{
  $states = ReadRollbackStates($directory);
  if (isset($states['beginTurnGamestate.txt'])) {
    $states['lastTurnGamestate.txt'] = $states['beginTurnGamestate.txt'];
  } else {
    unset($states['lastTurnGamestate.txt']);
  }
  unset($states['beginTurnGamestate.txt']);
  return StoreRollbackStates($directory, $states);
}

function ResetUndoStates($directory)
{
  $states = ReadRollbackStates($directory);
  foreach (array_keys($states) as $filename) {
    if (preg_match('/^gamestateBackup(?:_\d+)?\.txt$/D', $filename)
      || in_array($filename, ['preBlockBackup.txt', 'startChainLinkGamestate.txt'], true)) {
      unset($states[$filename]);
    }
  }
  return StoreRollbackStates($directory, $states);
}

function DeleteRollbackStates($gameName)
{
  if (_apcuAvailable()) @apcu_delete('talishar_rollback_v1_' . $gameName);
}
