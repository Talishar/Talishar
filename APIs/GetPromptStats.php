<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/PromptLog.php';
include_once '../GeneratedCode/GeneratedCardDictionaries.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

$days = intval($_GET["days"] ?? 7);
if (!in_array($days, [1, 7, 30, 90], true)) $days = 7;
$since = gmdate("Y-m-d", time() - ($days - 1) * 86400);

$response = [
  "days" => $days,
  "since" => $since,
  "totalAnswers" => 0,
  "prompts" => []
];

$conn = GetDBConnection(DBL_GET_PROMPT_STATS);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

try {
  EnsurePromptStatsTable($conn);
  $sql = "SELECT phase, context, answer, SUM(count) AS answered, SUM(total_ms) AS totalMs,
      SUM(IF(options = 1, count, 0)) AS forced, SUM(IF(identical = 1, count, 0)) AS identical
    FROM prompt_stats WHERE day >= ? GROUP BY phase, context, answer";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $since);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $prompts = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $key = $row["phase"] . "|" . $row["context"];
    if (!isset($prompts[$key])) {
      $name = GeneratedCardName($row["context"]);
      $prompts[$key] = [
        "phase" => $row["phase"],
        "context" => $row["context"],
        "contextName" => $name != "" ? $name : ($row["context"] == "ATTACKTARGET" ? "Attack target" : $row["context"]),
        "isCard" => $name != "",
        "count" => 0,
        "forced" => 0,
        "identical" => 0,
        "totalMs" => 0,
        "answers" => []
      ];
    }
    $answered = (int)$row["answered"];
    $prompts[$key]["count"] += $answered;
    if (IsMandatoryChoicePhase($row["phase"])) $prompts[$key]["forced"] += (int)$row["forced"];
    $prompts[$key]["identical"] += (int)$row["identical"];
    $prompts[$key]["totalMs"] += (int)$row["totalMs"];
    $prompts[$key]["answers"][] = ["answer" => $row["answer"], "count" => $answered];
    $response["totalAnswers"] += $answered;
  }
  mysqli_stmt_close($stmt);

  foreach ($prompts as &$prompt) {
    usort($prompt["answers"], fn($a, $b) => $b["count"] <=> $a["count"]);
    $prompt["avgMs"] = $prompt["count"] > 0 ? (int)round($prompt["totalMs"] / $prompt["count"]) : 0;
    unset($prompt["totalMs"]);
  }
  unset($prompt);
  $prompts = array_values($prompts);
  usort($prompts, fn($a, $b) => $b["count"] <=> $a["count"]);
  $response["prompts"] = array_slice($prompts, 0, 500);
} catch (Throwable $e) {
  error_log("GetPromptStats failed: " . $e->getMessage());
  http_response_code(500);
  $response["error"] = "Failed to load prompt stats";
}

mysqli_close($conn);
echo json_encode($response);
