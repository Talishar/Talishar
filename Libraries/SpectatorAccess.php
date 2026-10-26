<?php

include_once __DIR__ . '/../includes/dbh.inc.php';
include_once __DIR__ . '/../includes/ModeratorList.inc.php';

const SPECTATE_MIN_GAMES = 10;
const SPECTATE_MIN_ACCOUNT_DAYS = 7;
const SPECTATE_RECHECK_SECONDS = 300;
const SPECTATE_LOCKED_MESSAGE = "Spectating is not available for this account yet.";

function CanUserSpectate(): bool
{
  if (!IsUserLoggedIn()) return false;
  if (!empty($_SESSION['_canSpectate'])) return true;
  if (IsUserModerator(LoggedInUserName())) return true;

  $now = time();
  if ($now - ($_SESSION['_canSpectateCheckedAt'] ?? 0) < SPECTATE_RECHECK_SECONDS) return false;

  $progress = GetSpectateProgress(intval(LoggedInUser()));
  if ($progress === null) return false;
  $_SESSION['_canSpectateCheckedAt'] = $now;
  $_SESSION['_spectateProgress'] = $progress;
  if ($progress['days'] < SPECTATE_MIN_ACCOUNT_DAYS || $progress['games'] < SPECTATE_MIN_GAMES) return false;
  unset($_SESSION['_spectateProgress']);
  $_SESSION['_canSpectate'] = 1;
  return true;
}

// Call after CanUserSpectate() returned false; null when the user is logged out or the check failed.
function SpectateProgressResponse(): ?array
{
  $progress = $_SESSION['_spectateProgress'] ?? null;
  if (!IsUserLoggedIn() || !is_array($progress)) return null;
  return [
    'days' => $progress['days'],
    'minDays' => SPECTATE_MIN_ACCOUNT_DAYS,
    'games' => $progress['games'],
    'minGames' => SPECTATE_MIN_GAMES,
  ];
}

// Replays (cache piece 9) are finished games, so anyone logged in may watch them.
function CanSpectateGame(array $cacheArr): bool
{
  return ($cacheArr[9] ?? "") === "1" || CanUserSpectate();
}

// A NULL createdAt is an account older than the column, so it counts as old enough.
// Both counts are capped at their threshold.
function GetSpectateProgress(int $userId): ?array
{
  if ($userId <= 0) return ['days' => 0, 'games' => 0];
  $conn = GetDBConnection();
  if ($conn === false) return null;
  try {
    $stmt = $conn->prepare(
      "SELECT IF(u.createdAt IS NULL, ?, TIMESTAMPDIFF(DAY, u.createdAt, NOW())) AS days,
              COALESCE(SUM(h.qualifyingGames), 0) AS games
       FROM users u LEFT JOIN hero_mastery h ON h.userId = u.usersId
       WHERE u.usersId = ?
       GROUP BY u.usersId"
    );
    $minDays = SPECTATE_MIN_ACCOUNT_DAYS;
    $stmt->bind_param("ii", $minDays, $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) return ['days' => 0, 'games' => 0];
    return [
      'days' => max(0, min(SPECTATE_MIN_ACCOUNT_DAYS, intval($row["days"]))),
      'games' => min(SPECTATE_MIN_GAMES, intval($row["games"])),
    ];
  } catch (Throwable $e) {
    error_log("GetSpectateProgress failed: " . $e->getMessage());
    return null;
  } finally {
    mysqli_close($conn);
  }
}
