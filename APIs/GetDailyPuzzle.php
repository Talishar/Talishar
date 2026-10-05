<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once __DIR__ . '/../AccountFiles/AccountSessionAPI.php';
include_once __DIR__ . '/../includes/functions.inc.php';
include_once __DIR__ . '/../includes/dbh.inc.php';
include_once __DIR__ . '/../Libraries/PuzzleDaily.php';
include_once __DIR__ . '/../Libraries/PuzzleAnalysis.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();
$userId = IsUserLoggedIn() ? intval(LoggedInUser()) : 0;
session_write_close();
header('Content-Type: application/json');

// Today's puzzle, the player's result, and, once they have finished it, a solution and the hints.
function DailyPuzzleResponse($userId, $autoSchedule)
{
  $date = DailyPuzzleToday();
  $tomorrow = (new DateTimeImmutable($date, new DateTimeZone("UTC")))->modify("+1 day");
  $response = [
    "date" => $date,
    "loggedIn" => $userId > 0,
    "nextIn" => max(0, $tomorrow->getTimestamp() - time()),
    "puzzle" => null
  ];
  $conn = GetDBConnection(DBL_GET_DAILY_PUZZLE);
  if (!$conn) {
    http_response_code(500);
    return ["error" => "Database connection failed"];
  }
  try {
    EnsureDailyPuzzleTables($conn);
    $daily = LoadDailyPuzzle($conn, $date);
    if ($daily === null && $autoSchedule) {
      AutoScheduleDailyPuzzle($conn, $date);
      $daily = LoadDailyPuzzle($conn, $date);
    }
    if ($daily === null) return $response;
    $info = $daily["info"];
    $stats = DailyPuzzleStats($conn, $date);
    $result = $userId > 0 ? LoadDailyResult($conn, $date, $userId) : null;
    $bars = null;
    if ($daily["mode"] === "damage" && is_array($info["bars"] ?? null)) {
      $bars = [
        "bot" => intval($info["bars"]["bot"]),
        "real" => intval($info["bars"]["real"]),
        "best" => max(intval($info["bars"]["real"]), intval($stats["best"] ?? 0))
      ];
    }
    $response["puzzle"] = [
      "number" => DailyPuzzleNumber($conn, $date),
      "mode" => $daily["mode"],
      "format" => (string)($info["format"] ?? ""),
      "hero" => $info["hero"] ?? "",
      "heroName" => $info["heroName"] ?? "",
      "opponentHero" => $info["opponentHero"] ?? "",
      "opponentHeroName" => $info["opponentHeroName"] ?? "",
      "life" => intval($info["life"] ?? 0),
      "difficulty" => $info["difficulty"] ?? "",
      "hintsTotal" => count($info["hints"] ?? []),
      "bars" => $bars
    ];
    $response["stats"] = [
      "players" => $stats["players"],
      "finished" => $stats["finished"],
      "solved" => $stats["solved"],
      "averageStars" => $stats["averageStars"]
    ];
    $response["result"] = $result === null ? null : [
      "finished" => intval($result["finished"]) == 1,
      "solved" => intval($result["solved"]) == 1,
      "stars" => intval($result["stars"]),
      "hints" => intval($result["hints"]),
      "tries" => intval($result["tries"]),
      "damage" => $result["damage"] === null ? null : intval($result["damage"]),
      "rating" => intval($result["rating"])
    ];
    if ($result !== null && intval($result["finished"]) == 1) {
      $response["review"] = [
        "hints" => $info["hints"] ?? [],
        "solution" => PuzzleSolution(json_encode($info["solution"] ?? [])) ?? []
      ];
    }
    if ($daily["mode"] === "damage") $response["leaderboard"] = DailyPuzzleLeaderboard($conn, $date);
    return $response;
  } catch (Throwable $e) {
    error_log("GetDailyPuzzle failed: " . $e->getMessage());
    http_response_code(500);
    return ["error" => "Failed to load the daily puzzle"];
  } finally {
    mysqli_close($conn);
  }
}

// Nobody scheduled today's puzzle: the first request of the day picks one, which needs the engine.
$autoSchedule = DailyPuzzleMissing(DailyPuzzleToday());
if ($autoSchedule) {
  @set_time_limit(60);
  include_once __DIR__ . '/../Libraries/PuzzleEngine.php';
  include_once __DIR__ . '/../Libraries/PuzzleAutoSchedule.php';
}
echo json_encode(DailyPuzzleResponse($userId, $autoSchedule));
