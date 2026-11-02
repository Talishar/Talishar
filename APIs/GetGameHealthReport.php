<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/GameHealthLog.php';
include_once '../GeneratedCode/GeneratedFunctions/GeneratedCardName.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

const GAME_HEALTH_REPORT_ROWS = 300;
const GAME_HEALTH_REPORT_CONTEXTS = 5;

$days = intval($_GET["days"] ?? 7);
if (!in_array($days, [1, 7, 30, 90], true)) $days = 7;
$since = gmdate("Y-m-d", time() - ($days - 1) * 86400);

$response = [
  "days" => $days,
  "since" => $since,
  "games" => 0,
  "inputs" => 0,
  "undos" => 0,
  "fastUndos" => 0,
  "misclicks" => [],
  "cards" => []
];

function GameHealthRows($conn, $sql, $since)
{
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $since);
  mysqli_stmt_execute($stmt);
  $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
  mysqli_stmt_close($stmt);
  return $rows;
}

function GameHealthContextName($context)
{
  $name = GeneratedCardName($context);
  if ($name != "") return [$name, true];
  if (str_contains($context, "+")) {
    return [implode(" + ", array_map(fn($cardID) => GeneratedCardName($cardID) ?: $cardID, explode("+", $context))), false];
  }
  return [$context, false];
}

$conn = GetDBConnection(DBL_GET_GAME_HEALTH_REPORT);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

try {
  $games = GameHealthRows($conn, "SELECT SUM(games) AS games FROM game_health_games WHERE day >= ?", $since);
  $response["games"] = (int)($games[0]["games"] ?? 0);

  $groups = [];
  $misclickRows = GameHealthRows($conn, "SELECT phase, input_mode, context, SUM(inputs) AS inputs, SUM(undos) AS undos,
      SUM(fast_undos) AS fastUndos, SUM(undo_ms) AS undoMs
    FROM game_health_misclicks WHERE day >= ? GROUP BY phase, input_mode, context", $since);
  foreach ($misclickRows as $row) {
    $inputs = (int)$row["inputs"];
    $undos = (int)$row["undos"];
    $fastUndos = (int)$row["fastUndos"];
    $response["inputs"] += $inputs;
    $response["undos"] += $undos;
    $response["fastUndos"] += $fastUndos;
    $key = $row["phase"] . "|" . $row["input_mode"];
    if (!isset($groups[$key])) {
      $groups[$key] = [
        "phase" => $row["phase"],
        "mode" => (int)$row["input_mode"],
        "inputs" => 0,
        "undos" => 0,
        "fastUndos" => 0,
        "undoMs" => 0,
        "contexts" => []
      ];
    }
    $groups[$key]["inputs"] += $inputs;
    $groups[$key]["undos"] += $undos;
    $groups[$key]["fastUndos"] += $fastUndos;
    $groups[$key]["undoMs"] += (int)$row["undoMs"];
    if ($undos > 0 && $row["context"] !== "-") {
      $groups[$key]["contexts"][] = ["context" => $row["context"], "inputs" => $inputs, "undos" => $undos, "fastUndos" => $fastUndos];
    }
  }

  $misclicks = [];
  foreach ($groups as $group) {
    if ($group["undos"] == 0) continue;
    usort($group["contexts"], fn($a, $b) => [$b["fastUndos"], $b["undos"]] <=> [$a["fastUndos"], $a["undos"]]);
    $group["contexts"] = array_map(function ($context) {
      [$context["contextName"], $context["isCard"]] = GameHealthContextName($context["context"]);
      return $context;
    }, array_slice($group["contexts"], 0, GAME_HEALTH_REPORT_CONTEXTS));
    $group["avgUndoMs"] = (int)round($group["undoMs"] / $group["undos"]);
    $group["score"] = round(GameHealthWilsonLow($group["fastUndos"], $group["inputs"]), 5);
    unset($group["undoMs"]);
    $misclicks[] = $group;
  }
  usort($misclicks, fn($a, $b) => [$b["score"], $b["fastUndos"]] <=> [$a["score"], $a["fastUndos"]]);
  $response["misclicks"] = array_slice($misclicks, 0, GAME_HEALTH_REPORT_ROWS);

  $fixSums = implode(", ", array_map(fn($kind) => "SUM($kind) AS $kind", GAME_HEALTH_FIX_KINDS));
  $cardRows = GameHealthRows($conn, "SELECT card_id, SUM(games) AS games, SUM(fix_games) AS fixGames, $fixSums
    FROM game_health_cards WHERE day >= ? GROUP BY card_id HAVING fixGames > 0", $since);
  $cards = [];
  foreach ($cardRows as $row) {
    $card = [
      "cardID" => $row["card_id"],
      "cardName" => GeneratedCardName($row["card_id"]) ?: $row["card_id"],
      "games" => (int)$row["games"],
      "fixGames" => (int)$row["fixGames"]
    ];
    foreach (GAME_HEALTH_FIX_KINDS as $kind) $card[$kind] = (int)$row[$kind];
    $card["score"] = round(GameHealthWilsonLow($card["fixGames"], $card["games"]), 5);
    $cards[] = $card;
  }
  usort($cards, fn($a, $b) => [$b["score"], $b["fixGames"]] <=> [$a["score"], $a["fixGames"]]);
  $response["cards"] = array_slice($cards, 0, GAME_HEALTH_REPORT_ROWS);
} catch (mysqli_sql_exception $e) {
  if ($e->getCode() != 1146) {
    error_log("GetGameHealthReport failed: " . $e->getMessage());
    http_response_code(500);
    $response["error"] = "Failed to load game health stats";
  }
}

mysqli_close($conn);
echo json_encode($response);
