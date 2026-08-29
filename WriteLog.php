<?php

// Log lines are buffered in memory and written with one append per file instead
// of an open/write/flush/close cycle per line (game actions emit many lines).
// FlushLogBuffer() runs before GamestateUpdated() bumps the change counter, so
// logs are always on disk before any SSE/polling process rebuilds the game state.
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
  if ($entry["requireExists"] && !file_exists($filename)) {
    $entry["content"] = "";
    $entry["size"] = 0;
    return;
  }
  @file_put_contents($filename, $entry["content"], FILE_APPEND);
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
  LogBufferAppend("{$basePath}gamelog.txt", $line, true);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    LogBufferAppend("{$basePath}fullGamelog.txt", $line, false);
  }
}

function ClearLog($n=500)
{
  global $gameName, $logWriteBuffer;

  if (!empty($logWriteBuffer)) FlushLogBuffer();
  $filename = "./Games/$gameName/gamelog.txt";
  $handle = @fopen($filename, "rb");
  if ($handle === false) return;

  fseek($handle, 0, SEEK_END);
  $filesize = ftell($handle);
  if ($filesize <= 0) {
    fclose($handle);
    return;
  }

  $chunkSize = 65536;
  $offset = $filesize;
  $newlines = 0;
  $tailChunks = [];
  while ($offset > 0 && $newlines <= $n) {
    $readLen = (int)min($chunkSize, $offset);
    $offset -= $readLen;
    fseek($handle, $offset, SEEK_SET);
    $chunk = fread($handle, $readLen);
    if ($chunk === false) {
      fclose($handle);
      return;
    }
    $newlines += substr_count($chunk, "\n");
    $tailChunks[] = $chunk;
  }
  fclose($handle);

  // Whole file scanned and still at or under the cap: nothing to trim.
  if ($offset === 0 && $newlines <= $n) return;

  $tail = count($tailChunks) === 1 ? $tailChunks[0] : implode("", array_reverse($tailChunks));
  $excess = $newlines - $n;
  $cut = 0;
  for ($i = 0; $i < $excess; ++$i) {
    $nl = strpos($tail, "\n", $cut);
    if ($nl === false) break;
    $cut = $nl + 1;
  }
  if ($cut > 0) $tail = substr($tail, $cut);

  $handle = @fopen($filename, "wb");
  if ($handle === false) return;
  fwrite($handle, $tail);
  fclose($handle);
}

// Used when people rematch and start a new lobby
function TruncateLogAboveMarker($markers)
{
  global $gameName;

  FlushLogBuffer(); // buffered lines must be in the file before we slice it
  $filename = "./Games/$gameName/gamelog.txt";
  if (!file_exists($filename)) return;
  $lines = file($filename);
  if ($lines === false) return;

  $keepFrom = count($lines); // no marker found -> keep nothing
  for ($i = count($lines) - 1; $i >= 0; --$i) {
    foreach ($markers as $marker) {
      if (strpos($lines[$i], $marker) !== false) {
        $keepFrom = $i;
        break 2;
      }
    }
  }

  file_put_contents($filename, implode("", array_slice($lines, $keepFrom)));
}

function WriteSystemMessage($text, $path="./")
{
  global $gameName;
  $line = "$text\r\n";
  $basePath = "{$path}Games/$gameName/";
  LogBufferAppend("{$basePath}gamelog.txt", $line, true);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    LogBufferAppend("{$basePath}fullGamelog.txt", $line, false);
  }
}

function JSONLog($gameName, $playerID, $path="./")
{
  global $logWriteBuffer;
  $filename = "{$path}Games/$gameName/gamelog.txt";
  if (!empty($logWriteBuffer)) FlushLogBuffer();

  $maxRead = 131072; // 128 KB cap — prevents OOM when log file grows large
  $handler = @fopen($filename, "rb");
  if ($handler === false) return "";
  fseek($handler, 0, SEEK_END);
  $filesize = ftell($handler);
  if ($filesize <= 0) {
    fclose($handler);
    return "";
  }
  $truncated = $filesize > $maxRead;
  fseek($handler, $truncated ? $filesize - $maxRead : 0, SEEK_SET);
  $line = fread($handler, $truncated ? $maxRead : $filesize);
  fclose($handler);
  if ($truncated && ($nl = strpos($line, "\n")) !== false) {
    $line = substr($line, $nl + 1);
  }

  $red = "#cb0202";
  $blue = "#128ee5";
  $player1Color = ($playerID === 1 || $playerID === 3) ? $blue : $red;
  $player2Color = ($playerID === 2) ? $blue : $red;
  return strtr($line, ["\r\n" => "<br>", "<PLAYER1COLOR>" => $player1Color, "<PLAYER2COLOR>" => $player2Color]);
}
