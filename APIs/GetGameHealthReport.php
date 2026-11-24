<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/GameHealthLog.php';
include_once '../CardDictionary.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

RequireModeratorSession();
session_write_close();

const GAME_HEALTH_REPORT_ROWS = 300;
const GAME_HEALTH_REPORT_CONTEXTS = 5;
const GAME_HEALTH_REPORT_MIN_INPUTS = 20;
const GAME_HEALTH_REPORT_MIN_FIX_GAMES = 5;

$days = intval($_GET["days"] ?? 7);
if (!in_array($days, [1, 7, 30, 90], true)) $days = 7;
$since = gmdate("Y-m-d", time() - ($days - 1) * 86400);
$previousSince = gmdate("Y-m-d", time() - (2 * $days - 1) * 86400);

$response = [
  "days" => $days,
  "since" => $since,
  "previousSince" => null,
  "games" => 0,
  "inputs" => 0,
  "undos" => 0,
  "fastUndos" => 0,
  "minInputs" => GAME_HEALTH_REPORT_MIN_INPUTS,
  "minFixGames" => GAME_HEALTH_REPORT_MIN_FIX_GAMES,
  "misclicks" => [],
  "cards" => []
];

function GameHealthRows($conn, $sql, ...$params)
{
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
  mysqli_stmt_execute($stmt);
  $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
  mysqli_stmt_close($stmt);
  return $rows;
}

const GAME_HEALTH_BOT_ONLY_CARDS = ["wrenchtastic"];

function GameHealthCardName($cardID, $withPitch = false)
{
  $name = (string)CardName($cardID);
  if ($name == "" && preg_match('/^[a-z0-9]+(_[a-z0-9]+)+$/', $cardID)) {
    $name = ucwords(str_replace("_", " ", preg_replace('/_(red|yellow|blue)$/', "", $cardID)));
  }
  if ($name != "" && $withPitch && preg_match('/_(red|yellow|blue)$/', $cardID, $pitch)) $name .= " (" . ucfirst($pitch[1]) . ")";
  return $name;
}

function GameHealthContextName($context, $withPitch = false)
{
  $name = GameHealthCardName($context, $withPitch);
  if ($name != "") return [$name, true];
  if (str_contains($context, "+")) {
    return [implode(" + ", array_map(fn($cardID) => GameHealthCardName($cardID, $withPitch) ?: $cardID, explode("+", $context))), false];
  }
  return [$context, false];
}

function NameGameHealthContexts($contexts)
{
  foreach ($contexts as &$context) {
    [$context["contextName"], $context["isCard"]] = GameHealthContextName($context["context"]);
  }
  unset($context);
  $counts = array_count_values(array_column($contexts, "contextName"));
  foreach ($contexts as &$context) {
    if ($counts[$context["contextName"]] > 1) $context["contextName"] = GameHealthContextName($context["context"], true)[0];
  }
  unset($context);
  $counts = array_count_values(array_column($contexts, "contextName"));
  foreach ($contexts as &$context) {
    if ($counts[$context["contextName"]] > 1) $context["contextName"] .= " [" . $context["context"] . "]";
  }
  unset($context);
  return $contexts;
}

function IsGameHealthReorderSync($phase, $mode)
{
  return $mode == 108 || ($mode == 106 && $phase == "OPT");
}

function IsGameHealthReportedRow($phase, $mode)
{
  return IsGameHealthPromptPhase($phase) && !IsGameHealthReorderSync($phase, $mode) && !IsGameHealthTurnAction($mode);
}

$conn = GetDBConnection(DBL_GET_GAME_HEALTH_REPORT);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

try {
  $games = GameHealthRows($conn, "SELECT SUM(games) AS games, MIN(day) AS firstDay FROM game_health_games WHERE day >= ?", $since);
  $response["games"] = (int)($games[0]["games"] ?? 0);
  if (!empty($games[0]["firstDay"])) $response["since"] = $games[0]["firstDay"];
  $previousGames = GameHealthRows($conn, "SELECT MIN(day) AS firstDay FROM game_health_misclicks WHERE day >= ? AND day < ?", $previousSince, $since);
  if (!empty($previousGames[0]["firstDay"])) $response["previousSince"] = $previousGames[0]["firstDay"];

  $misclickSql = "SELECT phase, input_mode, context, SUM(inputs) AS inputs, SUM(undos) AS undos,
      SUM(fast_undos) AS fastUndos, SUM(undo_ms) AS undoMs
    FROM game_health_misclicks WHERE day >= ? AND day < ? GROUP BY phase, input_mode, context";
  $tomorrow = gmdate("Y-m-d", time() + 86400);

  $previous = [];
  foreach (GameHealthRows($conn, $misclickSql, $previousSince, $since) as $row) {
    if (!IsGameHealthReportedRow($row["phase"], (int)$row["input_mode"])) continue;
    $key = $row["phase"] . "|" . $row["input_mode"];
    $counts = ["inputs" => (int)$row["inputs"], "undos" => (int)$row["undos"], "fastUndos" => (int)$row["fastUndos"]];
    $previous[$key] ??= ["inputs" => 0, "undos" => 0, "fastUndos" => 0, "contexts" => []];
    foreach ($counts as $field => $value) $previous[$key][$field] += $value;
    $previous[$key]["contexts"][$row["context"]] = $counts;
  }

  $groups = [];
  $misclickRows = GameHealthRows($conn, $misclickSql, $since, $tomorrow);
  foreach ($misclickRows as $row) {
    if (!IsGameHealthReportedRow($row["phase"], (int)$row["input_mode"])) continue;
    $inputs = (int)$row["inputs"];
    $undos = (int)$row["undos"];
    $fastUndos = (int)$row["fastUndos"];
    $response["inputs"] += $inputs;
    $response["undos"] += $undos;
    $response["fastUndos"] += $fastUndos;
    $key = $row["phase"] . "|" . $row["input_mode"];
    if (!isset($groups[$key])) {
      $before = $previous[$key] ?? ["inputs" => 0, "undos" => 0, "fastUndos" => 0];
      $groups[$key] = [
        "phase" => $row["phase"],
        "mode" => (int)$row["input_mode"],
        "inputs" => 0,
        "undos" => 0,
        "fastUndos" => 0,
        "undoMs" => 0,
        "prevInputs" => $before["inputs"],
        "prevUndos" => $before["undos"],
        "prevFastUndos" => $before["fastUndos"],
        "contexts" => []
      ];
    }
    $groups[$key]["inputs"] += $inputs;
    $groups[$key]["undos"] += $undos;
    $groups[$key]["fastUndos"] += $fastUndos;
    $groups[$key]["undoMs"] += (int)$row["undoMs"];
    $before = $previous[$key]["contexts"][$row["context"]] ?? ["inputs" => 0, "undos" => 0, "fastUndos" => 0];
    if (($undos > 0 || $before["undos"] > 0) && $row["context"] !== "-" && $row["context"] !== "") {
      $groups[$key]["contexts"][] = [
        "context" => $row["context"],
        "inputs" => $inputs,
        "undos" => $undos,
        "fastUndos" => $fastUndos,
        "prevInputs" => $before["inputs"],
        "prevUndos" => $before["undos"],
        "prevFastUndos" => $before["fastUndos"]
      ];
    }
  }

  $misclicks = [];
  foreach ($groups as $group) {
    if ($group["undos"] == 0 && $group["prevUndos"] == 0) continue;
    usort($group["contexts"], fn($a, $b) => [max($b["fastUndos"], $b["prevFastUndos"]), $b["undos"] + $b["prevUndos"]]
      <=> [max($a["fastUndos"], $a["prevFastUndos"]), $a["undos"] + $a["prevUndos"]]);
    $group["contexts"] = NameGameHealthContexts(array_slice($group["contexts"], 0, GAME_HEALTH_REPORT_CONTEXTS));
    $group["avgUndoMs"] = $group["undos"] > 0 ? (int)round($group["undoMs"] / $group["undos"]) : 0;
    $group["score"] = round(GameHealthWilsonLow($group["fastUndos"], $group["inputs"]), 5);
    unset($group["undoMs"]);
    $misclicks[] = $group;
  }
  usort($misclicks, fn($a, $b) => [$b["inputs"] >= GAME_HEALTH_REPORT_MIN_INPUTS, $b["score"], $b["fastUndos"]]
    <=> [$a["inputs"] >= GAME_HEALTH_REPORT_MIN_INPUTS, $a["score"], $a["fastUndos"]]);
  $response["misclicks"] = array_slice($misclicks, 0, GAME_HEALTH_REPORT_ROWS);

  $previousCards = [];
  foreach (GameHealthRows($conn, "SELECT card_id, SUM(games) AS games, SUM(fix_games) AS fixGames
    FROM game_health_cards WHERE day >= ? AND day < ? GROUP BY card_id", $previousSince, $since) as $row) {
    $previousCards[$row["card_id"]] = [(int)$row["games"], (int)$row["fixGames"]];
  }

  $fixSums = implode(", ", array_map(fn($kind) => "SUM(LEAST($kind, fix_games)) AS $kind", GAME_HEALTH_FIX_KINDS));
  $cardRows = GameHealthRows($conn, "SELECT card_id, SUM(games) AS games, SUM(fix_games) AS fixGames, $fixSums
    FROM game_health_cards WHERE day >= ? GROUP BY card_id HAVING fixGames > 0", $since);
  $cards = [];
  foreach ($cardRows as $row) {
    if (in_array($row["card_id"], GAME_HEALTH_BOT_ONLY_CARDS, true)) continue;
    [$prevGames, $prevFixGames] = $previousCards[$row["card_id"]] ?? [0, 0];
    $card = [
      "cardID" => $row["card_id"],
      "cardName" => GameHealthCardName($row["card_id"]) ?: $row["card_id"],
      "games" => (int)$row["games"],
      "fixGames" => (int)$row["fixGames"],
      "prevGames" => $prevGames,
      "prevFixGames" => $prevFixGames
    ];
    foreach (GAME_HEALTH_FIX_KINDS as $kind) $card[$kind] = (int)$row[$kind];
    $card["score"] = round(GameHealthWilsonLow($card["fixGames"], $card["games"]), 5);
    $cards[] = $card;
  }
  usort($cards, fn($a, $b) => [$b["fixGames"] >= GAME_HEALTH_REPORT_MIN_FIX_GAMES, $b["score"], $b["fixGames"]]
    <=> [$a["fixGames"] >= GAME_HEALTH_REPORT_MIN_FIX_GAMES, $a["score"], $a["fixGames"]]);
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
