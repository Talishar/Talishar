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
  $serData = trim(serialize(trim($data)));
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
  $serData = trim(serialize(trim($data)));
  $needed = strlen($serData);
  $key = GamestateID($name);

  $size = 32768;
  $existing = @shmop_open($key, "a", 0, 0);
  if ($existing !== false) {
    $size = shmop_size($existing);
    if ($size < $needed) {
      shmop_delete($existing);
      $size = max(32768, 1 << (int)ceil(log($needed + 1, 2)));
    }
  } else if ($needed >= $size) {
    $size = 1 << (int)ceil(log($needed + 1, 2));
  }

  $gsID = @shmop_open($key, "c", 0666, $size);
  if ($gsID == false) {
    exit;
  } else {
    $serData = str_pad($serData, $size, "\0");
    $rv = shmop_write($gsID, $serData, 0);
  }
}

function ReadCache($name)
{
  if ($name == 0) return "";
  $data = "";
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
  $oldVal = GetCachePiece($gameName, $piece);
  $intVal = (int)$oldVal; // Cast to int to handle empty strings
  SetCachePiece($gameName, $piece, $intVal + 1);
  return $intVal + 1;
}

function GamestateUpdated($gameName, $resetTimer = true)
{
  if (function_exists('FlushLogBuffer')) FlushLogBuffer();
  $cache = ReadCache($gameName);
  $cacheArr = explode(SHMOPDelimiter(), $cache);
  $cacheArr[0]++;
  if ($resetTimer) {
    $currentTime = round(microtime(true) * 1000);
    $cacheArr[5] = $currentTime;
  }
  WriteCache($gameName, implode(SHMOPDelimiter(), $cacheArr));
}
