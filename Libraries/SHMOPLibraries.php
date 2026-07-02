<?php

/*
1 - Update Number
2 - P1 Last Connection Time
3 - P2 Last Connection Time
4 - Player 1 status
5 - Player 2 status
6 - Last gamestate update (time)
7 - P1 Hero
8 - P2 Hero
9 - Game visibility (1 = public, 0 = private)
10 - Is Replay
11 - Number P2 disconnects
12 - Not Used - Current player status (0 = active, 2 = inactive)
13 - Format (see function FormatCode)
14 - Game status (see $MGS_ constants)
15 - Player 1 is chat enabled
16 - Player 2 is chat enabled
17 - currentPlayer Inactive
*/

function WriteCache($name, $data)
{
  if ($name == 0) return;
  $serData = serialize(trim($data));
  // 0666: segments must stay writable even if first created by a root CLI
  // script — 0644 left Apache unable to update the cache (game stuck forever).
  $id = @shmop_open($name, "c", 0666, 128);
  if ($id == false) {
    exit;
  } else {
    $serData = str_pad($serData, 128, "\0");
    $rv = shmop_write($id, $serData, 0);
  }
}

function WriteGamestateCache($name, $data)
{
  if ($name == 0) return;
  $serData = serialize(trim($data));
  $needed = strlen($serData) + 16; // payload + seqlock header
  $key = GamestateID($name);

  $size = 32768;
  $seq = 0;
  $existing = @shmop_open($key, "a", 0, 0);
  if ($existing !== false) {
    $size = shmop_size($existing);
    $head = shmop_read($existing, 0, 16);
    if (strlen($head) === 16 && ctype_digit($head)) $seq = (int)$head;
    if ($size < $needed) {
      shmop_delete($existing);
      $size = max(32768, 1 << (int)ceil(log($needed + 1, 2)));
      $seq = 0;
    }
  } else if ($needed >= $size) {
    $size = 1 << (int)ceil(log($needed + 1, 2));
  }

  $gsID = @shmop_open($key, "c", 0666, $size);
  if ($gsID == false) {
    exit;
  }
  $writeSeq = $seq + ($seq % 2 === 0 ? 1 : 2); // next odd: write in progress
  shmop_write($gsID, sprintf("%016d", $writeSeq), 0);
  shmop_write($gsID, str_pad($serData, $size - 16, "\0"), 16);
  shmop_write($gsID, sprintf("%016d", $writeSeq + 1), 0); // even: stable
}

function ReadGamestateCache($name)
{
  $key = GamestateID($name);
  for ($try = 0; $try < 10; $try++) {
    $id = @shmop_open($key, "a", 0, 0);
    if ($id === false) return "";
    $size = shmop_size($id);
    $head = shmop_read($id, 0, 16);
    if (strlen($head) !== 16 || !ctype_digit($head)) {
      $raw = trim(shmop_read($id, 0, $size));
      $un = @unserialize($raw);
      return $un === false ? "" : $un;
    }
    $s1 = (int)$head;
    if ($s1 % 2 === 1) { usleep(1000); continue; } // write in progress
    $raw = trim(shmop_read($id, 16, $size - 16));
    $s2 = (int)shmop_read($id, 0, 16);
    if ($s1 !== $s2) { usleep(1000); continue; } // torn — retry
    $un = @unserialize($raw);
    return $un === false ? "" : $un;
  }
  return "";
}

function ReadCache($name)
{
  if ($name == 0) return "";
  $data = ShmopReadCache($name);
  if (empty($data)) return "";
  $unserialized = @unserialize($data);
  return $unserialized === false ? "" : $unserialized;
}

function ShmopReadCache($name)
{
  if (!trim($name)) {
    return "";
  }
  @$id = shmop_open($name, "a", 0, 0);
  if (empty($id) || $id == false) {
    return "";
  }
  return trim(shmop_read($id, 0, shmop_size($id)));
}

function DeleteCache($name)
{
  //Always try to delete shmop
  $id = @shmop_open($name, "w", 0666, 128);
  if($id) {
    shmop_delete($id);
  }
  $gsID = @shmop_open(GamestateID($name), "c", 0666, 32768);
  if($gsID) {
    shmop_delete($gsID);
  }
}

function SHMOPDelimiter()
{
  return "!";
}

function GamestateID($gameName)
{
  return $gameName + 1000000;
}

function SetCachePiece($name, $piece, $value)
{
  $piece -= 1;
  $cacheVal = ReadCache($name);
  if ($cacheVal == "") return;
  $cacheArray = explode("!", $cacheVal);
  $cacheArray[$piece] = $value;
  WriteCache($name, implode("!", $cacheArray));
}

// Batched SetCachePiece: applies several pieceW with a single shmop read-modify-write
function SetCachePieces($name, $pieces)
{
  $cacheVal = ReadCache($name);
  if ($cacheVal == "") return;
  $cacheArray = explode("!", $cacheVal);
  foreach ($pieces as $piece => $value) {
    $cacheArray[$piece - 1] = $value;
  }
  WriteCache($name, implode("!", $cacheArray));
}

function GetCachePiece($name, $piece)
{
  $piece -= 1;
  $cacheVal = ReadCache($name);
  if (empty($cacheVal)) return "";
  $cacheArray = explode("!", $cacheVal);
  if ($piece >= count($cacheArray) || $piece < 0) return "";
  return $cacheArray[$piece];
}

function ReadCacheArray($name)
{
  $cacheVal = ReadCache($name);
  if (empty($cacheVal)) return null;
  return explode("!", $cacheVal);
}

function IncrementCachePiece($gameName, $piece)
{
  $idx = $piece - 1;
  $cacheVal = ReadCache($gameName);
  if ($cacheVal == "") return 0;
  $cacheArray = explode("!", $cacheVal);
  $newVal = (int)($cacheArray[$idx] ?? 0) + 1;
  $cacheArray[$idx] = $newVal;
  WriteCache($gameName, implode("!", $cacheArray));
  return $newVal;
}

function GamestateUpdated($gameName, $resetTimer = true)
{
  if (function_exists('FlushLogBuffer')) FlushLogBuffer();
  $cache = ReadCache($gameName);
  $cacheArr = explode("!", $cache);
  $cacheArr[0] = (int)$cacheArr[0] + 1;
  if ($resetTimer) {
    $currentTime = round(microtime(true) * 1000);
    $cacheArr[5] = $currentTime;
  }
  WriteCache($gameName, implode("!", $cacheArr));
}
