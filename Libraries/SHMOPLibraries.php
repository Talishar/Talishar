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
12 - Current player status (0 = active, 1 = inactive)
13 - Format (see function FormatCode)
14 - Game status (see $MGS_ constants)
15 - Player 1 is chat enabled
16 - Player 2 is chat enabled
*/

// $useRedis = getenv('REDIS_ENABLED') ?? false;
$useRedis = false;
$redisHost = (!empty(getenv("REDIS_HOST")) ? getenv("REDIS_HOST") : "127.0.0.1");
$redisPort = (!empty(getenv("REDIS_PORT")) ? getenv("REDIS_PORT") : "6379");
$redisPassword = (!empty(getenv("REDIS_PASSWORD")) ? getenv("REDIS_PASSWORD") : "");

if ($useRedis) {
  $redis = new Redis();
  $redis->connect($redisHost, $redisPort);
  $redis->auth($redisPassword);
}

function WriteCache($name, $data)
{
  global $useRedis, $redis;
  //DeleteCache($name);
  if ($name == 0) return;
  $serData = trim(serialize(trim($data)));

  if ($useRedis) {
    $redis->set($name, $serData);
  } else {
    $id = shmop_open($name, "c", 0644, 128);
    if ($id == false) {
      exit;
    } else {
      $serData = str_pad($serData, 128, "\0");
      $rv = shmop_write($id, $serData, 0);
    }
  }
}

function WriteGamestateCache($name, $data)
{
  if ($name == 0) return;
  $serData = trim(serialize(trim($data)));
  $gsID = shmop_open(GamestateID($name), "c", 0644, 16384);
  if ($gsID == false) {
    exit;
  } else {
    $serData = str_pad($serData, 16384, "\0");
    $rv = shmop_write($gsID, $serData, 0);
  }
}

function ReadCache($name)
{
  global $useRedis, $redis;
  if ($name == 0) return "";

  $data = "";
  if ($useRedis) {
    $data = RedisReadCache($name);
    if ($data == "" && is_numeric($name)) {
      $data = ShmopReadCache($name);
    }
  } else {
    $data = ShmopReadCache($name);
    // if ($data == "") {
    //   $data = RedisReadCache($name);
    // }
  }

  return unserialize($data);
}

function ShmopReadCache($name)
{
  // If name is empty just return
  if (!trim($name)) {
    return "";
  }
  @$id = shmop_open($name, "a", 0, 0);
  if (empty($id) || $id == false) {
    return "";
  }
  return trim(shmop_read($id, 0, shmop_size($id)));
}

function RedisReadCache($name)
{
  global $redis;
  if (!isset($redis)) $redis = RedisConnect();
  return $data = $redis->get($name);
}

function RedisConnect()
{
  global $redis, $redisHost, $redisPort, $redisPassword;
  $redis = new Redis();
  $redis->connect($redisHost, $redisPort);
  $redis->auth($redisPassword);
  return $redis;
}

function DeleteCache($name)
{
  global $useRedis, $redis;
  if ($useRedis) {
    $redis->del($name);
    $redis->del(GamestateID($name));
  }
  //Always try to delete shmop
  $id = shmop_open($name, "w", 0644, 128);
  if($id) {
    shmop_delete($id);
    shmop_close($id); //shmop_close is deprecated
  }
  $gsID = shmop_open(GamestateID($name), "c", 0644, 16384);
  if($gsID) {
    shmop_delete($gsID);
    shmop_close($gsID); //shmop_close is deprecated
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
  $cacheArray = explode("!", $cacheVal);
  if ($piece >= count($cacheArray)) return "";
  return $cacheArray[$piece];
}

function IncrementCachePiece($gameName, $piece)
{
  $oldVal = GetCachePiece($gameName, $piece);
  SetCachePiece($gameName, $piece, $oldVal + 1);
  return $oldVal + 1;
}

function GamestateUpdated($gameName)
{
  global $currentPlayer;
  $cache = ReadCache($gameName);
  $cacheArr = explode(SHMOPDelimiter(), $cache);
  $cacheArr[0]++;
  $currentTime = round(microtime(true) * 1000);
  $cacheArr[5] = $currentTime;
  WriteCache($gameName, implode(SHMOPDelimiter(), $cacheArr));
}
