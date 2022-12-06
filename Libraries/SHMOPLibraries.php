<?php

$useRedis = false;

if ($useRedis) {
  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);
}

function WriteCache($name, $data)
{
  global $useRedis, $redis;
  //DeleteCache($name);
  if ($name == 0) return;
  $serData = serialize($data);

  if ($useRedis) {
    $redis->set($name, $serData);
  } else {
    $id = shmop_open($name, "c", 0644, 128);
    if ($id == false) {
      exit;
    } else {
      $rv = shmop_write($id, $serData, 0);
    }
  }
}

function ReadCache($name)
{
  global $useRedis, $redis;
  if ($name == 0) return "";

  if ($useRedis) {
    $data = $redis->get($name);
  } else {
    @$id = shmop_open($name, "a", 0, 0);
    if (empty($id) || $id == false) {
      return "";
    }
    $data = shmop_read($id, 0, shmop_size($id));
    $data = preg_replace_callback('!s:(\d+):"(.*?)";!', function ($match) {
      return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
    }, $data);
  }

  return unserialize($data);
}

function DeleteCache($name)
{
  global $useRedis, $redis;
  if ($useRedis) {
    $redis->delete($name);
  } else {
    $id = shmop_open($name, "w", 0644, 128);
    if ($id) {
      shmop_delete($id);
      shmop_close($id); //shmop_close is deprecated
    }
  }
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
  $cacheArray = explode("!", $cacheVal);
  if ($piece >= count($cacheArray)) return "";
  return $cacheArray[$piece];
}

function GamestateUpdated($gameName)
{
  global $currentPlayer;
  SetCachePiece($gameName, 1, (intval(GetCachePiece($gameName, 1)) + 1));
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 6, $currentTime);
  SetCachePiece($gameName, 9, $currentPlayer);
}
