<?php

const PROMPT_LOG_FILE = "promptlog.txt";
const PROMPT_LOG_MAX_MS = 30000;
const PROMPT_LOG_MAX_OPTIONS = 10;

function IsLoggedPromptPhase($phase)
{
  static $skip = [
    "M" => true, "A" => true, "D" => true, "B" => true, "P" => true, "INSTANT" => true,
    "PDECK" => true, "ENDPHASE" => true, "STARTTURN" => true, "OVER" => true, "CHOOSEFIRSTPLAYER" => true
  ];
  return $phase !== "" && !isset($skip[$phase]);
}

function PromptAnswerModes()
{
  static $modes = [
    4 => true, 7 => true, 8 => true, 9 => true, 11 => true, 12 => true, 13 => true, 16 => true, 17 => true,
    19 => true, 20 => true, 23 => true, 29 => true, 30 => true, 99 => true, 107 => true, 109 => true, 110 => true
  ];
  return $modes;
}

function IsMandatoryChoicePhase($phase)
{
  static $notMandatory = ["CHOOSETOP" => true, "CHOOSEBOTTOM" => true, "CHOOSETOPOPPONENT" => true, "CHOOSEARSENALCANCEL" => true,
    "CHOOSEHANDCANCEL" => true, "CHOOSEDISCARDCANCEL" => true];
  if (isset($notMandatory[$phase])) return false;
  return str_starts_with($phase, "CHOOSE") || str_starts_with($phase, "BUTTONINPUT") || $phase == "CHOOSENUMBER" || $phase == "NUMBERINPUT";
}

function PromptOptions($phase, $parameter)
{
  if ($phase == "YESNO") return ["YES", "NO"];
  if (str_starts_with($phase, "MULTICHOOSE") || str_starts_with($phase, "MAYMULTICHOOSE")) {
    $parameter = explode("-", $parameter)[1] ?? "";
  }
  $options = [];
  foreach (explode(",", str_replace(";", ",", $parameter)) as $option) {
    if ($option === "" || $option === "-" || str_starts_with($option, "MAXCOUNT-") || str_starts_with($option, "MINCOUNT-")) continue;
    $options[] = $option;
  }
  return $options;
}

function PromptOptionsIdentical($player, $phase, $options)
{
  if (count($options) < 2 || $phase == "YESNO" || str_starts_with($phase, "BUTTONINPUT")) return 0;
  $cardIDs = [];
  foreach ($options as $option) {
    if (preg_match('/^(MY|THEIR)[A-Z]+-\d/', $option)) $cardIDs[GetMZCard($player, $option)] = true;
    else if (is_numeric($option)) {
      if (!str_contains($phase, "HAND") || str_contains($phase, "THEIRHAND")) return 0;
      $cardIDs[GetMZCard($player, "MYHAND-$option")] = true;
    }
    else $cardIDs[$option] = true;
    if (count($cardIDs) > 1) return 0;
  }
  return 1;
}

function PromptAnswerLabel($mode, $buttonInput, $cardID, $chkInput, $optionCount, $submission = null)
{
  switch ($mode) {
    case 4: return "ARSENAL";
    case 7: return "NUMBER " . intval($buttonInput);
    case 8: case 12: return "TOP";
    case 9: case 13: return "BOTTOM";
    case 16:
      if (preg_match('/^((MY|THEIR)[A-Z]+)-/', (string)$cardID, $matches)) return "CHOSE " . $matches[1];
      return "CHOSE";
    case 17: return substr(str_replace(["\t", "\r", "\n"], " ", (string)$buttonInput), 0, 64);
    case 19:
      $chosen = count($chkInput);
      if ($chosen == 0) return "NONE";
      return $chosen >= $optionCount ? "ALL" : "SOME";
    case 20: return $buttonInput == "YES" ? "YES" : "NO";
    case 30: return "NAMED";
    case 99: return "PASS";
    case 107:
      $top = count($submission->cardListTop ?? []);
      $bottom = count($submission->cardListBottom ?? []);
      if ($bottom == 0) return "ALL TOP";
      return $top == 0 ? "ALL BOTTOM" : "SPLIT";
    case 109: case 110: return "ORDERED";
    default: return "CHOSE";
  }
}

function LogPromptAnswer($player, $mode, $buttonInput = "", $cardID = "", $chkInput = [], $submission = null)
{
  global $turn, $EffectContext, $gameName, $SET_DisableFabInsights;
  if (($player != 1 && $player != 2) || !isset(PromptAnswerModes()[(int)$mode]) || IsReplay()) return;
  $phase = $turn[0] ?? "";
  if (!IsLoggedPromptPhase($phase)) return;
  if ($mode == 99 && !CanPassPhase($phase)) return;
  if (SettingValue($player, $SET_DisableFabInsights, "0") == "1") return;

  $options = PromptOptions($phase, (string)($turn[2] ?? ""));
  $optionCount = count($options);
  $identical = PromptOptionsIdentical($player, $phase, $options);
  $answer = PromptAnswerLabel((int)$mode, $buttonInput, $cardID, $chkInput, $optionCount, $submission);
  $context = $phase == "ARS" ? "-" : trim((string)$EffectContext);
  if ($context === "") $context = "-";

  $shownAt = intval(GetCachePiece($gameName, 6));
  $elapsed = $shownAt > 0 ? (int)(microtime(true) * 1000) - $shownAt : 0;
  $elapsed = max(0, min(PROMPT_LOG_MAX_MS, $elapsed));

  $fields = [$phase, substr($context, 0, 96), $answer, min($optionCount, PROMPT_LOG_MAX_OPTIONS), $identical, $elapsed];
  $line = implode("\t", array_map(fn($field) => str_replace(["\t", "\r", "\n"], " ", (string)$field), $fields)) . "\n";
  @file_put_contents("./Games/$gameName/" . PROMPT_LOG_FILE, $line, FILE_APPEND);
}

function EnsurePromptStatsTable($conn)
{
  $sql = "CREATE TABLE IF NOT EXISTS prompt_stats (
    day DATE NOT NULL,
    phase VARCHAR(32) NOT NULL,
    context VARCHAR(96) NOT NULL,
    answer VARCHAR(64) NOT NULL,
    options TINYINT UNSIGNED NOT NULL,
    identical TINYINT UNSIGNED NOT NULL,
    count INT UNSIGNED NOT NULL DEFAULT 0,
    total_ms BIGINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (day, phase, context, answer, options, identical)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
  if (!mysqli_query($conn, $sql)) {
    error_log("Failed to create prompt_stats table: " . mysqli_error($conn));
  }
}

function FlushPromptLog($gameName)
{
  $path = "./Games/$gameName/" . PROMPT_LOG_FILE;
  if (!file_exists($path)) return;
  $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  @unlink($path);
  if (!$lines) return;

  $rows = [];
  foreach ($lines as $line) {
    $parts = explode("\t", $line);
    if (count($parts) != 6) continue;
    [$phase, $context, $answer, $options, $identical, $elapsed] = $parts;
    $key = "$phase\t$context\t$answer\t$options\t$identical";
    if (!isset($rows[$key])) $rows[$key] = [$phase, $context, $answer, (int)$options, (int)$identical, 0, 0];
    ++$rows[$key][5];
    $rows[$key][6] += (int)$elapsed;
  }
  if (count($rows) == 0) return;

  include_once __DIR__ . "/../includes/dbh.inc.php";
  $conn = GetDBConnection(DBL_FLUSH_PROMPT_LOG);
  if (!$conn) return;
  try {
    EnsurePromptStatsTable($conn);
    $day = gmdate("Y-m-d");
    foreach (array_chunk(array_values($rows), 200) as $chunk) {
      $placeholders = implode(",", array_fill(0, count($chunk), "(?,?,?,?,?,?,?,?)"));
      $sql = "INSERT INTO prompt_stats (day, phase, context, answer, options, identical, count, total_ms) VALUES $placeholders
        ON DUPLICATE KEY UPDATE count = count + VALUES(count), total_ms = total_ms + VALUES(total_ms)";
      $params = [];
      foreach ($chunk as $row) array_push($params, $day, ...$row);
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, str_repeat("ssssiiii", count($chunk)), ...$params);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
  } catch (Throwable $e) {
    error_log("FlushPromptLog failed: " . $e->getMessage());
  }
  mysqli_close($conn);
}
