<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once __DIR__ . '/../AccountFiles/AccountSessionAPI.php';
include_once __DIR__ . '/../includes/functions.inc.php';
include_once __DIR__ . '/../includes/dbh.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();
$userId = IsUserLoggedIn() ? intval(LoggedInUser()) : 0;
$useruid = (string)(LoggedInUserName() ?? "");
session_write_close();
header('Content-Type: application/json');

if ($userId <= 0) {
  http_response_code(401);
  echo json_encode(["error" => "Log in to play the daily puzzle."]);
  exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["error" => "POST required"]);
  exit;
}

include_once __DIR__ . '/../Libraries/PuzzleEngine.php';
include_once __DIR__ . '/../Libraries/PuzzleDaily.php';

// The player's unfinished official game for the day, if it is still there.
function DailyPuzzleResumeGame($gameName, $userId)
{
  if ($gameName <= 0 || !IsPuzzleGame($gameName)) return null;
  $info = ReadPuzzleInfo($gameName);
  if (!empty($info["finished"]) || intval($info["daily"]["userId"] ?? 0) != $userId || !empty($info["daily"]["practice"])) return null;
  $gameFile = @file("./Games/$gameName/GameFile.txt", FILE_IGNORE_NEW_LINES);
  $player = intval($info["player"] ?? 0);
  if (!is_array($gameFile) || ($player != 1 && $player != 2)) return null;
  return ["gameName" => $gameName, "playerID" => $player, "authKey" => trim($gameFile[$player == 1 ? 7 : 8] ?? ""), "practice" => false];
}

// The first game of the day is the official attempt; once it has ended, every new game is practice.
function StartDailyPuzzleResponse($userId, $useruid)
{
  $conn = GetDBConnection(DBL_START_DAILY_PUZZLE);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  try {
    EnsurePuzzleCandidatesTable($conn);
    EnsureDailyPuzzleTables($conn);
    $date = DailyPuzzleToday();
    $daily = LoadDailyPuzzle($conn, $date);
    if ($daily === null) {
      http_response_code(404);
      return ["error" => "There is no puzzle today."];
    }
    $result = LoadDailyResult($conn, $date, $userId);
    $practice = $result !== null && intval($result["finished"]) == 1;
    if ($result !== null && !$practice) {
      $resume = DailyPuzzleResumeGame(intval($result["game_name"]), $userId);
      if ($resume !== null) return $resume;
    }
    $row = LoadPuzzleCandidate($conn, intval($daily["candidate_id"]));
    if ($row === null) {
      http_response_code(404);
      return ["error" => "Today's puzzle is unavailable."];
    }
    if ($result === null) {
      DailyPuzzleQuery($conn, "INSERT IGNORE INTO puzzle_results (puzzle_date, user_id) VALUES (?, ?)", "si", $date, $userId);
    } else if (!$practice && intval($result["game_name"]) > 0) {
      DailyPuzzleQuery($conn, "UPDATE puzzle_results SET tries = tries + 1 WHERE puzzle_date = ? AND user_id = ? AND finished = 0",
        "si", $date, $userId);
    }
    $dailyInfo = ["date" => $date, "number" => DailyPuzzleNumber($conn, $date), "userId" => $userId, "practice" => $practice];
    $game = CreatePuzzleGameFromSetup($daily["info"], $row, $useruid, $userId, $dailyInfo,
      $practice ? 0 : intval($result["hints"] ?? 0));
    if (isset($game["error"])) {
      http_response_code(500);
      return $game;
    }
    if (!$practice) {
      DailyPuzzleQuery($conn, "UPDATE puzzle_results SET game_name = ? WHERE puzzle_date = ? AND user_id = ? AND finished = 0",
        "isi", $game["gameName"], $date, $userId);
    }
    return $game + ["practice" => $practice];
  } catch (Throwable $e) {
    error_log("StartDailyPuzzle failed: " . $e->getMessage());
    http_response_code(500);
    return ["error" => "Failed to start the daily puzzle"];
  } finally {
    mysqli_close($conn);
  }
}

@set_time_limit(30);
echo json_encode(StartDailyPuzzleResponse($userId, $useruid));
