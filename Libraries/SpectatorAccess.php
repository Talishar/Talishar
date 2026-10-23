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

  $qualifies = MeetsSpectateRequirements(intval(LoggedInUser()));
  if ($qualifies === null) return false;
  $_SESSION['_canSpectateCheckedAt'] = $now;
  if (!$qualifies) return false;
  $_SESSION['_canSpectate'] = 1;
  return true;
}

// Replays (cache piece 9) are finished games, so anyone logged in may watch them.
function CanSpectateGame(array $cacheArr): bool
{
  return ($cacheArr[9] ?? "") === "1" || CanUserSpectate();
}

// A NULL createdAt is an account older than the column, so it counts as old enough.
function MeetsSpectateRequirements(int $userId): ?bool
{
  if ($userId <= 0) return false;
  $conn = GetDBConnection();
  if ($conn === false) return null;
  try {
    $stmt = $conn->prepare(
      "SELECT u.createdAt IS NULL OR u.createdAt <= NOW() - INTERVAL ? DAY AS oldEnough,
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
    if (!$row) return false;
    return intval($row["oldEnough"]) === 1 && intval($row["games"]) >= SPECTATE_MIN_GAMES;
  } catch (Throwable $e) {
    error_log("MeetsSpectateRequirements failed: " . $e->getMessage());
    return null;
  } finally {
    mysqli_close($conn);
  }
}
