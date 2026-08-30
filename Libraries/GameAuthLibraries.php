<?php

if (!function_exists('IsRealAccountUid')) {
  function IsRealAccountUid($uid)
  {
    if (!is_string($uid)) return false;
    $uid = trim($uid);
    if ($uid === '' || $uid === '-') return false;
    if ($uid === 'Player 1' || $uid === 'Player 2') return false;
    return true;
  }
}

if (!function_exists('AccountOwnsGameSeat')) {
  function AccountOwnsGameSeat($seatUid, $accountUid)
  {
    if (!IsRealAccountUid($seatUid) || !IsRealAccountUid($accountUid)) return false;
    return strcasecmp(trim($seatUid), trim($accountUid)) === 0;
  }
}

if (!function_exists('GameSeatForAccount')) {
  function GameSeatForAccount($p1uid, $p2uid, $accountUid)
  {
    if (AccountOwnsGameSeat($p1uid, $accountUid)) return 1;
    if (AccountOwnsGameSeat($p2uid, $accountUid)) return 2;
    return 0;
  }
}

if (!function_exists('ReadGameFileSeatAuth')) {
  // Seat auth keys and account uids straight off the game file header, so an
  // endpoint can authenticate before it touches any game state.
  function ReadGameFileSeatAuth($gameName, $pathPrefix = './')
  {
    $empty = ['', '', '', ''];
    if (!is_string($gameName) || trim($gameName) === '') return $empty;
    $path = $pathPrefix . 'Games/' . $gameName . '/GameFile.txt';
    $head = @file_get_contents($path, false, null, 0, 8192);
    if ($head === false) return $empty;
    $lines = explode("\n", $head, 12);
    if (count($lines) < 11) return $empty;
    return [trim($lines[7]), trim($lines[8]), trim($lines[9]), trim($lines[10])];
  }
}

if (!function_exists('ReadGameFileAccountUids')) {
  function ReadGameFileAccountUids($gameName, $pathPrefix = './')
  {
    $seat = ReadGameFileSeatAuth($gameName, $pathPrefix);
    return [$seat[2], $seat[3]];
  }
}

if (!function_exists('ResolveGameAuthKey')) {
  // Returns the effective auth key for the seat, '' for spectators, or null
  // when the caller has no claim to the seat.
  function ResolveGameAuthKey($playerID, $suppliedKeys, $p1Key, $p2Key, $p1uid, $p2uid, $accountUid)
  {
    if (filter_var($playerID, FILTER_VALIDATE_INT) === false) return null;
    $playerID = intval($playerID);
    if ($playerID === 3) return '';
    if ($playerID !== 1 && $playerID !== 2) return null;

    $targetAuth = ($playerID === 1 ? $p1Key : $p2Key);
    if (!is_string($targetAuth) || $targetAuth === '') return null;

    if (!is_array($suppliedKeys)) $suppliedKeys = [$suppliedKeys];
    foreach ($suppliedKeys as $candidate) {
      if (is_string($candidate) && $candidate !== '' && hash_equals($targetAuth, $candidate)) {
        return $targetAuth;
      }
    }

    $seatUid = ($playerID === 1 ? $p1uid : $p2uid);
    if (AccountOwnsGameSeat($seatUid, $accountUid)) return $targetAuth;

    return null;
  }
}

if (!function_exists('CurrentAccountUid')) {
  // Cheap read of the logged in account handle for endpoints that do not
  // otherwise boot the account session. Never starts a session for a visitor
  // who does not already have one.
  function CurrentAccountUid()
  {
    if (session_status() === PHP_SESSION_ACTIVE) return $_SESSION["useruid"] ?? null;
    if (session_status() !== PHP_SESSION_NONE) return null;
    if (empty($_COOKIE[session_name()])) return null;
    @session_start();
    $uid = $_SESSION["useruid"] ?? null;
    session_write_close();
    return $uid;
  }
}
