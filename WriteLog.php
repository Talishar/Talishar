<?php

include_once __DIR__ . '/Libraries/LiveGameLog.php';

// Log lines are buffered per request and flushed once per destination.
// FlushLogBuffer() runs before GamestateUpdated() bumps the change counter, so
// the live APCu log is published before SSE/polling rebuilds the game state.
// Per-file content is hard-capped at LOG_BUFFER_FLUSH_THRESHOLD
if (!defined('LOG_BUFFER_FLUSH_THRESHOLD')) {
  define('LOG_BUFFER_FLUSH_THRESHOLD', 65536); // bytes, per filename
}
$GLOBALS['_logMemoryReserve'] = null;
$logWriteBuffer = [];

function LogBufferAppend($filename, $line, $requireExists)
{
  global $logWriteBuffer;
  if (!isset($GLOBALS['_logMemoryReserve'])) {
    $GLOBALS['_logMemoryReserve'] = str_repeat('x', 524288);
  }
  static $registered = false;
  if (!$registered) {
    register_shutdown_function('FlushLogBuffer');
    $registered = true;
  }
  if (!isset($logWriteBuffer[$filename])) {
    $logWriteBuffer[$filename] = ["requireExists" => $requireExists, "content" => "", "size" => 0];
  }
  static $memLimitBytes = null;
  if ($memLimitBytes === null) {
    $raw = ini_get('memory_limit');
    $n = (int)$raw;
    $unit = strtolower(substr(trim($raw), -1));
    $memLimitBytes = match($unit) { 'g' => $n * 1073741824, 'm' => $n * 1048576, 'k' => $n * 1024, default => $n };
  }
  if ($memLimitBytes > 0 && ($memLimitBytes - memory_get_usage()) < 2097152) {
    FlushLogBufferEntry($filename);
  }
  $entry = &$logWriteBuffer[$filename];
  $entry["content"] .= $line;
  $entry["size"] += strlen($line);
  if ($entry["size"] >= LOG_BUFFER_FLUSH_THRESHOLD) {
    FlushLogBufferEntry($filename);
  }
}

function FlushLogBufferEntry($filename)
{
  global $logWriteBuffer;
  if (!isset($logWriteBuffer[$filename])) return;
  $entry = &$logWriteBuffer[$filename];
  if ($entry["content"] === "") return;
  $isLiveLog = basename($filename) === 'gamelog.txt';
  if (!$isLiveLog && $entry["requireExists"] && !file_exists($filename)) {
    $entry["content"] = "";
    $entry["size"] = 0;
    return;
  }
  if ($isLiveLog) {
    if (!AppendLiveGameLog(dirname($filename), $entry["content"])) {
      error_log('Could not append live game log: ' . $filename);
    }
  } else {
    @file_put_contents($filename, $entry["content"], FILE_APPEND);
  }
  $entry["content"] = "";
  $entry["size"] = 0;
}

function FlushLogBuffer()
{
  global $logWriteBuffer;
  // Free the emergency reserve so this shutdown handler has memory to work with
  unset($GLOBALS['_logMemoryReserve']);
  if (empty($logWriteBuffer)) return;
  foreach ($logWriteBuffer as $filename => $entry) {
    FlushLogBufferEntry($filename);
  }
  $logWriteBuffer = [];
}

function WriteLog($text, $playerColor = 0, $highlight=false, $path="./", $highlightColor="brown")
{
  global $gameName;
  switch ($highlightColor) {
    case "darkpurple": $highlightColor = "#1c0333"; break;
    case "darkgreen": $highlightColor = "#005900"; break;
  }
  if ($playerColor === 0) {
    $output = $highlight
      ? "<p style='background: $highlightColor;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>$text</span></p>"
      : $text;
  } else {
    $inner = $highlight
      ? "<p style='background: $highlightColor;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>$text</span></p>"
      : $text;
    $output = "<span style='color:<PLAYER{$playerColor}COLOR>;'>$inner</span>";
  }
  $line = "$output\r\n";
  $basePath = "{$path}Games/$gameName/";
  $GLOBALS['replayLogLines'] = ($GLOBALS['replayLogLines'] ?? "") . $line;
  LogBufferAppend("{$basePath}gamelog.txt", $line, true);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    LogBufferAppend("{$basePath}fullGamelog.txt", $line, false);
  }
}

function ClearLog($n=500)
{
  global $gameName;
  FlushLogBuffer();
  TrimLiveGameLog("./Games/$gameName", $n);
}

// Used when people rematch and start a new lobby.
function TruncateLogAboveMarker($markers)
{
  global $gameName;
  FlushLogBuffer();
  TruncateLiveGameLogAboveMarker("./Games/$gameName", $markers);
}

function WriteSystemMessage($text, $path="./")
{
  global $gameName;
  $line = "$text\r\n";
  $basePath = "{$path}Games/$gameName/";
  $GLOBALS['replayLogLines'] = ($GLOBALS['replayLogLines'] ?? "") . $line;
  LogBufferAppend("{$basePath}gamelog.txt", $line, true);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    LogBufferAppend("{$basePath}fullGamelog.txt", $line, false);
  }
}

function JSONLog($gameName, $playerID, $path="./")
{
  return LogForViewer(ReadLogWindow($gameName, $path)[1], $playerID);
}

// The log tail and its logical byte offset, used by SSE delta delivery.
function ReadLogWindow($gameName, $path="./")
{
  FlushLogBuffer();
  return LiveGameLogWindow("{$path}Games/$gameName");
}

function LogForViewer($line, $playerID)
{
  $red = "#cb0202";
  $blue = "#128ee5";
  $player1Color = ($playerID === 1 || $playerID === 3) ? $blue : $red;
  $player2Color = ($playerID === 2) ? $blue : $red;
  return str_replace(["\r\n", "<PLAYER1COLOR>", "<PLAYER2COLOR>"], ["<br>", $player1Color, $player2Color], $line);
}
