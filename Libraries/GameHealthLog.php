<?php

const GAME_HEALTH_LOG_FILE = "healthlog.txt";
const GAME_HEALTH_FAST_UNDO_MS = 3000;
const GAME_HEALTH_MAX_UNDO_MS = 30000;
const GAME_HEALTH_UNDO_DEPTH = 20;
const GAME_HEALTH_SUSPECT_WINDOW = 4;
const GAME_HEALTH_FIX_KINDS = ["life", "ap", "resources", "draws", "cards", "arsenal", "bugs"];

function GameHealthFixKind($mode)
{
  static $kinds = [
    10002 => "ap", 10004 => "ap",
    10005 => "life", 10006 => "life", 10007 => "life", 10008 => "life",
    10009 => "draws", 10010 => "draws",
    10011 => "cards",
    10012 => "resources", 10013 => "resources", 10014 => "resources", 10015 => "resources",
    10016 => "arsenal", 10017 => "arsenal",
    100003 => "bugs"
  ];
  return $kinds[$mode] ?? null;
}

function IsGameHealthLoggedMode($mode)
{
  if ($mode < 10000) return !IsModeAsync($mode);
  return GameHealthFixKind($mode) !== null;
}

function IsGameHealthPromptPhase($phase)
{
  static $skip = [
    "M" => true, "A" => true, "D" => true, "B" => true, "P" => true, "INSTANT" => true,
    "PDECK" => true, "ENDPHASE" => true, "STARTTURN" => true, "OVER" => true, "CHOOSEFIRSTPLAYER" => true
  ];
  return $phase !== "" && !isset($skip[$phase]);
}

function GameHealthTriggerSetContext($parameter)
{
  $cardIDs = [];
  foreach (explode(",", $parameter) as $layer) {
    $parts = explode("|", $layer);
    $cardID = $parts[0] == "USURPED" ? ($parts[2] ?? "") : $parts[0];
    if ($cardID !== "" && $cardID !== "-") $cardIDs[$cardID] = true;
  }
  $cardIDs = array_keys($cardIDs);
  sort($cardIDs);
  return count($cardIDs) > 0 ? implode("+", $cardIDs) : "-";
}

function GameHealthPromptContext($phase)
{
  global $EffectContext, $decisionQueue, $turn;
  if ($phase == "ARS") return "-";
  if ($phase == "ORDERTRIGGERS") return GameHealthTriggerSetContext((string)($turn[2] ?? ""));
  if (GetDQHelpText() == "Choose_a_target_for_the_attack") return "ATTACKTARGET";
  $dqCount = count($decisionQueue ?? []);
  $dqPieces = DecisionQueuePieces();
  for ($i = 0; $i < $dqCount; $i += $dqPieces) {
    if ($decisionQueue[$i] == "RESUMEPAYING") return explode("-", (string)($decisionQueue[$i + 2] ?? ""), 2)[0] ?: "-";
  }
  $context = trim((string)$EffectContext);
  return $context === "" ? "-" : $context;
}

function WriteGameHealthLine($player, $mode, $context)
{
  global $turn, $gameName, $lastPlayed, $SET_DisableFabInsights;
  if (($player != 1 && $player != 2) || IsReplay()) return;
  if (SettingValue($player, $SET_DisableFabInsights, "0") == "1") return;

  $phase = substr((string)($turn[0] ?? ""), 0, 48);
  $context = substr(str_replace(["\t", "\r", "\n"], " ", $context), 0, 96);
  $line = (int)(microtime(true) * 1000) . "\t$player\t$mode\t$phase\t$context\t" . trim((string)($lastPlayed[0] ?? "")) . "\n";
  @file_put_contents(__DIR__ . "/../Games/$gameName/" . GAME_HEALTH_LOG_FILE, $line, FILE_APPEND);
}

function LogGameHealthInput($player, $mode, $buttonInput = "")
{
  global $turn;
  $mode = (int)$mode;
  if (!IsGameHealthLoggedMode($mode)) return;

  $phase = (string)($turn[0] ?? "");
  $context = "-";
  if ($mode == 27) $context = (string)$buttonInput ?: "-";
  else if ($mode < 10000 && IsGameHealthPromptPhase($phase)) $context = GameHealthPromptContext($phase);
  WriteGameHealthLine($player, $mode, $context);
}

// Undos are logged where the engine acts on them: 10000 for a revert (context
// "request" when it only asked the opponent), 100016 for an accepted request
// (logged for the requester), 10003 for a revert to an earlier turn or link.
function LogGameHealthUndo($player, $mode, $context = "-")
{
  WriteGameHealthLine($player, $mode, $context);
}

function SummarizeGameHealthLog($lines)
{
  $misclicks = [];
  $cards = [];
  $undoStacks = [1 => [], 2 => []];
  $undoRequests = [];
  $recentCards = [];

  foreach ($lines as $line) {
    $parts = explode("\t", $line);
    if (count($parts) != 6) continue;
    [$ms, $player, $mode, $phase, $context, $lastPlayed] = $parts;
    $ms = (int)$ms;
    $player = (int)$player;
    $mode = (int)$mode;
    if ($player != 1 && $player != 2) continue;

    if ($lastPlayed !== "" && $lastPlayed !== "-") {
      $recentCards[] = $lastPlayed;
      if (count($recentCards) > GAME_HEALTH_SUSPECT_WINDOW) array_shift($recentCards);
      if (!isset($cards[$lastPlayed])) $cards[$lastPlayed] = ["fixed" => 0] + array_fill_keys(GAME_HEALTH_FIX_KINDS, 0);
    }

    if ($mode < 10000) {
      $key = null;
      if (IsGameHealthPromptPhase($phase)) {
        $key = "$phase\t$mode\t$context";
        if (!isset($misclicks[$key])) $misclicks[$key] = [$phase, $mode, $context, 0, 0, 0, 0];
        ++$misclicks[$key][3];
      }
      $undoStacks[$player][] = [$key, $ms];
      if (count($undoStacks[$player]) > GAME_HEALTH_UNDO_DEPTH) array_shift($undoStacks[$player]);
    }
    else if ($mode == 10000 && $context == "request") {
      $undoRequests[$player] = $ms;
    }
    else if ($mode == 10000 || $mode == 100016) {
      $undoMs = $mode == 100016 ? ($undoRequests[$player] ?? $ms) : $ms;
      unset($undoRequests[$player]);
      $undone = array_pop($undoStacks[$player]);
      if ($undone === null || $undone[0] === null) continue;
      $gap = max(0, $undoMs - $undone[1]);
      ++$misclicks[$undone[0]][4];
      if ($gap < GAME_HEALTH_FAST_UNDO_MS) ++$misclicks[$undone[0]][5];
      $misclicks[$undone[0]][6] += min($gap, GAME_HEALTH_MAX_UNDO_MS);
    }
    else if ($mode == 10003) {
      $undoStacks = [1 => [], 2 => []];
      $undoRequests = [];
    }
    else {
      $kind = GameHealthFixKind($mode);
      if ($kind === null) continue;
      foreach (array_unique($recentCards) as $cardID) {
        ++$cards[$cardID][$kind];
        $cards[$cardID]["fixed"] = 1;
      }
    }
  }

  $cardRows = [];
  foreach ($cards as $cardID => $card) {
    $row = [(string)$cardID, 1, $card["fixed"]];
    foreach (GAME_HEALTH_FIX_KINDS as $kind) $row[] = $card[$kind];
    $cardRows[] = $row;
  }
  return [array_values($misclicks), $cardRows];
}

function EnsureGameHealthTables($conn)
{
  $fixColumns = implode("", array_map(fn($kind) => "$kind INT UNSIGNED NOT NULL DEFAULT 0,\n", GAME_HEALTH_FIX_KINDS));
  $tables = [
    "CREATE TABLE IF NOT EXISTS game_health_misclicks (
      day DATE NOT NULL,
      phase VARCHAR(48) NOT NULL,
      input_mode INT UNSIGNED NOT NULL,
      context VARCHAR(96) NOT NULL,
      inputs INT UNSIGNED NOT NULL DEFAULT 0,
      undos INT UNSIGNED NOT NULL DEFAULT 0,
      fast_undos INT UNSIGNED NOT NULL DEFAULT 0,
      undo_ms BIGINT UNSIGNED NOT NULL DEFAULT 0,
      PRIMARY KEY (day, phase, input_mode, context)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS game_health_cards (
      day DATE NOT NULL,
      card_id VARCHAR(96) NOT NULL,
      games INT UNSIGNED NOT NULL DEFAULT 0,
      fix_games INT UNSIGNED NOT NULL DEFAULT 0,
      $fixColumns
      PRIMARY KEY (day, card_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS game_health_games (
      day DATE NOT NULL PRIMARY KEY,
      games INT UNSIGNED NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
  ];
  foreach ($tables as $sql) mysqli_query($conn, $sql);
}

function GameHealthUpsert($conn, $table, $keyColumns, $valueColumns, $rows, $types)
{
  if (count($rows) == 0) return;
  sort($rows);
  $columns = array_merge(["day"], $keyColumns, $valueColumns);
  $placeholder = "(" . implode(",", array_fill(0, count($columns), "?")) . ")";
  $updates = implode(", ", array_map(fn($column) => "$column = $column + VALUES($column)", $valueColumns));
  $day = gmdate("Y-m-d");
  foreach (array_chunk($rows, 200) as $chunk) {
    $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES "
      . implode(",", array_fill(0, count($chunk), $placeholder))
      . " ON DUPLICATE KEY UPDATE $updates";
    $params = [];
    foreach ($chunk as $row) array_push($params, $day, ...$row);
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, str_repeat("s" . $types, count($chunk)), ...$params);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

function RecordGameHealth($conn, $misclicks, $cards)
{
  $writes = [
    ["game_health_misclicks", ["phase", "input_mode", "context"], ["inputs", "undos", "fast_undos", "undo_ms"], $misclicks, "sisiiii"],
    ["game_health_cards", ["card_id"], array_merge(["games", "fix_games"], GAME_HEALTH_FIX_KINDS), $cards, "s" . str_repeat("i", 2 + count(GAME_HEALTH_FIX_KINDS))],
    ["game_health_games", [], ["games"], [[1]], "i"]
  ];
  foreach ($writes as $write) {
    try {
      GameHealthUpsert($conn, ...$write);
    } catch (mysqli_sql_exception $e) {
      if ($e->getCode() != 1146) throw $e;
      EnsureGameHealthTables($conn);
      GameHealthUpsert($conn, ...$write);
    }
  }
}

function FlushGameHealthLog($gameName)
{
  $path = __DIR__ . "/../Games/$gameName/" . GAME_HEALTH_LOG_FILE;
  if (!file_exists($path)) return;
  $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  @unlink($path);
  if (!$lines) return;

  [$misclicks, $cards] = SummarizeGameHealthLog($lines);
  if (count($misclicks) == 0 && count($cards) == 0) return;

  include_once __DIR__ . "/../includes/dbh.inc.php";
  $conn = GetDBConnection(DBL_FLUSH_GAME_HEALTH_LOG);
  if (!$conn) return;
  try {
    mysqli_query($conn, "SET SESSION innodb_lock_wait_timeout = 3");
    RecordGameHealth($conn, $misclicks, $cards);
  } catch (Throwable $e) {
    error_log("FlushGameHealthLog failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}

function GameHealthWilsonLow($hits, $total)
{
  if ($total <= 0) return 0.0;
  $z = 1.96;
  $share = $hits / $total;
  $denominator = 1 + $z * $z / $total;
  $centre = $share + $z * $z / (2 * $total);
  $margin = $z * sqrt(($share * (1 - $share) + $z * $z / (4 * $total)) / $total);
  return max(0.0, ($centre - $margin) / $denominator);
}
