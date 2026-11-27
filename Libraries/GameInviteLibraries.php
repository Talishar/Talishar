<?php

include_once __DIR__ . '/SHMOPLibraries.php';
include_once __DIR__ . '/FormatCodes.php';

const GAME_INVITE_TTL_SECONDS = 600;
const GAME_INVITE_LOBBY_OPEN = 0;

function GameInvitesAvailable()
{
  static $available = null;
  if ($available === null) $available = function_exists('apcu_fetch') && apcu_enabled();
  return $available;
}

function GameInviteKey($userId)
{
  return "game_invites_" . (int)$userId;
}

function GameInviteLobbyCache($gameName)
{
  if (!is_numeric($gameName) || (int)$gameName <= 0) return null;
  $cacheArr = ReadCacheArray($gameName);
  if ($cacheArr === null || intval($cacheArr[13] ?? -1) !== GAME_INVITE_LOBBY_OPEN) return null;
  return $cacheArr;
}

function ReadGameInvites($userId)
{
  $invites = apcu_fetch(GameInviteKey($userId));
  return is_array($invites) ? $invites : [];
}

function AddGameInvite($toUserId, $fromUserId, $fromName, $gameName, $format)
{
  if (!GameInvitesAvailable()) return false;
  $invites = ReadGameInvites($toUserId);
  $invites[(int)$fromUserId] = [
    'fromUserId' => (int)$fromUserId,
    'fromName' => (string)$fromName,
    'gameName' => (string)$gameName,
    'format' => (string)$format,
    'sentAt' => time(),
  ];
  return apcu_store(GameInviteKey($toUserId), $invites, GAME_INVITE_TTL_SECONDS);
}

function RemoveGameInvite($toUserId, $fromUserId, $gameName = null)
{
  if (!GameInvitesAvailable()) return;
  $invites = ReadGameInvites($toUserId);
  $fromUserId = (int)$fromUserId;
  if (!isset($invites[$fromUserId])) return;
  if ($gameName !== null && $invites[$fromUserId]['gameName'] !== (string)$gameName) return;
  unset($invites[$fromUserId]);
  if (count($invites) === 0) apcu_delete(GameInviteKey($toUserId));
  else apcu_store(GameInviteKey($toUserId), $invites, GAME_INVITE_TTL_SECONDS);
}

function GetLiveGameInvites($userId)
{
  if (!GameInvitesAvailable()) return [];
  $invites = ReadGameInvites($userId);
  if (count($invites) === 0) return [];
  $now = time();
  $live = [];
  foreach ($invites as $fromUserId => $invite) {
    if ($now - (int)$invite['sentAt'] >= GAME_INVITE_TTL_SECONDS) continue;
    if (GameInviteLobbyCache($invite['gameName']) === null) continue;
    $live[$fromUserId] = $invite;
  }
  if (count($live) !== count($invites)) {
    if (count($live) === 0) apcu_delete(GameInviteKey($userId));
    else apcu_store(GameInviteKey($userId), $live, GAME_INVITE_TTL_SECONDS);
  }
  usort($live, fn($a, $b) => $b['sentAt'] <=> $a['sentAt']);
  return $live;
}

function GameInviteFormat($cacheArr)
{
  return FormatName(intval($cacheArr[12] ?? -1));
}
