<?php

const ONLINE_PRESENCE_TTL_SECONDS = 10800;
const ONLINE_PRESENCE_WINDOW_SECONDS = 720;
const ONLINE_PRESENCE_WRITE_INTERVAL_SECONDS = 60;
const ONLINE_PRESENCE_GAME_STARTED = 5;

function OnlinePresenceAvailable()
{
  static $available = null;
  if ($available === null) $available = function_exists('apcu_fetch') && apcu_enabled();
  return $available;
}

function OnlinePresenceKey($userId)
{
  return "online_seen_" . (int)$userId;
}

function MarkUserSeen($userId, $gameName = null)
{
  if (!is_numeric($userId) || (int)$userId <= 0 || !OnlinePresenceAvailable()) return;
  $key = OnlinePresenceKey($userId);
  $now = time();
  $entry = apcu_fetch($key);
  $hasEntry = is_array($entry);
  if ($gameName === null) {
    if ($hasEntry && $now - (int)$entry[0] < ONLINE_PRESENCE_WRITE_INTERVAL_SECONDS) return;
    $gameName = $hasEntry ? $entry[1] : "";
  }
  apcu_store($key, [$now, (string)$gameName], ONLINE_PRESENCE_TTL_SECONDS);
}

function GetUsersOnlineStatus($userIds)
{
  $statuses = [];
  if (!OnlinePresenceAvailable() || !is_array($userIds) || count($userIds) === 0) return $statuses;

  $userIdsByKey = [];
  foreach ($userIds as $userId) {
    if (is_numeric($userId)) $userIdsByKey[OnlinePresenceKey($userId)] = (int)$userId;
  }
  if (count($userIdsByKey) === 0) return $statuses;

  $entries = apcu_fetch(array_keys($userIdsByKey));
  if (!is_array($entries)) return $statuses;

  $now = time();
  foreach ($entries as $key => $entry) {
    if (!is_array($entry) || $now - (int)$entry[0] >= ONLINE_PRESENCE_WINDOW_SECONDS) continue;
    $status = "online";
    $gameName = $entry[1] ?? "";
    if ($gameName !== "" && is_numeric($gameName) && function_exists('ReadCacheArray')) {
      $cacheArr = ReadCacheArray($gameName);
      if ($cacheArr !== null && intval($cacheArr[13] ?? 0) === ONLINE_PRESENCE_GAME_STARTED) $status = "playing";
    }
    $statuses[$userIdsByKey[$key]] = $status;
  }
  return $statuses;
}
