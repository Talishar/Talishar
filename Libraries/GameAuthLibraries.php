<?php

/**
 * Account-based authKey recovery
 */

function ResolvePresentedGameAuth($requestedPlayerID, $authKey, $p1Key, $p2Key): ?array
{
  if (!is_string($authKey) || $authKey === '') return null;

  $requestedPlayerID = (int)$requestedPlayerID;
  $seats = $requestedPlayerID === 1 || $requestedPlayerID === 2
    ? [$requestedPlayerID]
    : [1, 2];

  foreach ($seats as $playerID) {
    $gameAuthKey = $playerID === 1 ? $p1Key : $p2Key;
    if (is_string($gameAuthKey) && $gameAuthKey !== '' && hash_equals($gameAuthKey, $authKey)) {
      return [$playerID, $authKey];
    }
  }

  return null;
}

function ResolveStoredAccountGameAuth(
  $requestedGameName,
  $requestedPlayerID,
  $accountGameName,
  $accountPlayerID,
  $accountAuthKey,
  $p1Key,
  $p2Key
): ?array {
  $requestedGameName = trim((string)$requestedGameName);
  if ($requestedGameName === '' || !ctype_digit($requestedGameName)) return null;
  if ((int)$requestedGameName !== (int)$accountGameName) return null;

  $accountPlayerID = (int)$accountPlayerID;
  if ($accountPlayerID !== 1 && $accountPlayerID !== 2) return null;

  $requestedPlayerID = (int)$requestedPlayerID;
  if ($requestedPlayerID !== 0 && $requestedPlayerID !== $accountPlayerID) return null;

  $gameAuthKey = $accountPlayerID === 1 ? $p1Key : $p2Key;
  if (!is_string($accountAuthKey) || $accountAuthKey === '') return null;
  if (!is_string($gameAuthKey) || $gameAuthKey === '') return null;
  if (!hash_equals($gameAuthKey, $accountAuthKey)) return null;

  return [$accountPlayerID, $accountAuthKey];
}

/**
 * Read only the auth-key portion of GameFile.txt.
 */
function ReadGameFileSeatAuth($gameName, string $gamesDirectory): ?array
{
  $gameName = trim((string)$gameName);
  if ($gameName === '' || !ctype_digit($gameName) || (int)$gameName <= 0) return null;

  $path = rtrim($gamesDirectory, '/\\') . DIRECTORY_SEPARATOR . $gameName . DIRECTORY_SEPARATOR . 'GameFile.txt';
  $head = @file_get_contents($path, false, null, 0, 8192);
  if (!is_string($head)) return null;

  $lines = explode("\n", $head, 12);
  if (count($lines) < 9) return null;

  return [
    trim($lines[7]),
    trim($lines[8]),
  ];
}
