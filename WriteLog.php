<?php

// Log lines are buffered in memory and written with one append per file instead
// of an open/write/flush/close cycle per line (game actions emit many lines).
// FlushLogBuffer() runs before GamestateUpdated() bumps the change counter, so
// logs are always on disk before any SSE/polling process rebuilds the game state.
// Per-file content is hard-capped at LOG_BUFFER_FLUSH_THRESHOLD
if (!defined('LOG_BUFFER_FLUSH_THRESHOLD')) {
  define('LOG_BUFFER_FLUSH_THRESHOLD', 65536); // bytes, per filename
}
$logWriteBuffer = [];

function LogBufferAppend($filename, $line, $requireExists)
{
  global $logWriteBuffer;
  static $registered = false;
  if (!$registered) {
    register_shutdown_function('FlushLogBuffer');
    $registered = true;
  }
  if (!isset($logWriteBuffer[$filename])) {
    $logWriteBuffer[$filename] = ["requireExists" => $requireExists, "content" => ""];
  }
  $logWriteBuffer[$filename]["content"] .= $line;
  if (strlen($logWriteBuffer[$filename]["content"]) >= LOG_BUFFER_FLUSH_THRESHOLD) {
    FlushLogBufferEntry($filename);
  }
}

function FlushLogBufferEntry($filename)
{
  global $logWriteBuffer;
  if (!isset($logWriteBuffer[$filename])) return;
  $entry = $logWriteBuffer[$filename];
  if ($entry["content"] === "") return;
  if ($entry["requireExists"] && !file_exists($filename)) {
    $logWriteBuffer[$filename]["content"] = "";
    return;
  }
  @file_put_contents($filename, $entry["content"], FILE_APPEND);
  $logWriteBuffer[$filename]["content"] = "";
}

function FlushLogBuffer()
{
  global $logWriteBuffer;
  if (empty($logWriteBuffer)) return;
  foreach ($logWriteBuffer as $filename => $entry) {
    FlushLogBufferEntry($filename);
  }
  $logWriteBuffer = [];
}

function WriteLog($text, $playerColor = 0, $highlight=false, $path="./", $highlightColor="brown")
{
  global $gameName;
  $filename = "{$path}Games/$gameName/gamelog.txt";
  $playerSpan = ($playerColor != 0 ? "<span style='color:<PLAYER{$playerColor}COLOR>;'>" : "");
  $playerSpanClose = ($playerColor != 0 ? "</span>" : "");
  if($highlight) $output = $playerSpan . "<p style='background: $highlightColor;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>" . $text . "</span></p>" . $playerSpanClose;
  else $output = $playerSpan . $text . $playerSpanClose;
  LogBufferAppend($filename, "$output\r\n", true);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    $filename = "{$path}Games/$gameName/fullGamelog.txt";
    LogBufferAppend($filename, "$output\r\n", false);
  }
}

function ClearLog($n=30)
{
  global $gameName;

  FlushLogBuffer(); // buffered lines must be in the file before we slice it
  $filename = "./Games/$gameName/gamelog.txt";
  $handle = fopen("./Games/$gameName/gamelog.txt", "r");
  $lines = [];
  if ($handle) {
    while (!feof($handle)) {
        $lines[] = fgets($handle);
    }
    fclose($handle);
    $lines = array_slice($lines, -$n);
  }

  $handle = fopen($filename, "w");
  fwrite($handle, implode("", $lines));
  fclose($handle);
}

function WriteSystemMessage($text, $path="./")
{
  global $gameName;
  $filename = "{$path}Games/$gameName/gamelog.txt";
  LogBufferAppend($filename, "$text\r\n", true);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    $filename = "{$path}Games/$gameName/fullGamelog.txt";
    LogBufferAppend($filename, "$text\r\n", false);
  }
}

function JSONLog($gameName, $playerID, $path="./")
{
  FlushLogBuffer(); // make any lines buffered in this process visible to the read
  $response = "";
  $filename = "{$path}Games/$gameName/gamelog.txt";
  clearstatcache(true, $filename); // Clear file stat cache to get fresh file size
  if (!file_exists($filename)) return "";
  $filesize = filesize($filename);
  if ($filesize > 0) {
    $handler = fopen($filename, "r");
    $line = fread($handler, $filesize);
    fclose($handler);
    $red = "#cb0202";
    $blue = "#128ee5";
    $player1Color = $playerID == 1 || $playerID == 3 ? $blue : $red;
    $player2Color = $playerID == 2 ? $blue : $red;
    $response = str_replace(["\r\n", "<PLAYER1COLOR>", "<PLAYER2COLOR>"], ["<br>", $player1Color, $player2Color], $line);
  }
  return $response;
}
