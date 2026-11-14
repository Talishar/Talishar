<?php

const PENDING_RESULT_FILENAME = "pendingResult.json";
const PENDING_RESULT_WINDOW_MS = 60000;
const TAKEBACK_ANSWER_WINDOW_MS = 30000;
const TAKEBACK_DECLINE_LIMIT = 2;

function PendingResultNowMs(): int
{
  return (int)round(microtime(true) * 1000);
}

function PendingResultDirectory($gameName): string
{
  return __DIR__ . "/../Games/" . $gameName . "/";
}

function PendingResultPath(string $gameDirectory): string
{
  return rtrim($gameDirectory, "/\\") . "/" . PENDING_RESULT_FILENAME;
}

function ReadPendingResultFile(string $path): ?array
{
  $raw = @file_get_contents($path);
  if (!is_string($raw) || $raw === "") return null;
  $pending = json_decode($raw, true);
  return is_array($pending) ? $pending : null;
}

function ReadPendingGameResult(string $gameDirectory): ?array
{
  return ReadPendingResultFile(PendingResultPath($gameDirectory));
}

function WritePendingGameResult(string $gameDirectory, array $pending): bool
{
  $path = PendingResultPath($gameDirectory);
  $json = json_encode($pending);
  if ($json === false) return false;
  $temporaryPath = $path . "." . bin2hex(random_bytes(4)) . ".tmp";
  if (@file_put_contents($temporaryPath, $json) === false) return false;
  if (@rename($temporaryPath, $path)) return true;
  @unlink($temporaryPath);
  return false;
}

function StartPendingGameResult(string $gameDirectory, ?array $result): bool
{
  return WritePendingGameResult($gameDirectory, [
    "deadline" => PendingResultNowMs() + PENDING_RESULT_WINDOW_MS,
    "result" => $result,
    "request" => null,
    "declines" => 0,
  ]);
}

function DiscardPendingGameResult(string $gameDirectory): void
{
  @unlink(PendingResultPath($gameDirectory));
}

function OpenTakebackRequest(array $pending, int $now): ?array
{
  $request = $pending["request"] ?? null;
  if (!is_array($request)) return null;
  return $now - intval($request["at"] ?? 0) < TAKEBACK_ANSWER_WINDOW_MS ? $request : null;
}

function IsPendingGameResultDue(array $pending, int $now): bool
{
  return $now >= intval($pending["deadline"] ?? 0) && OpenTakebackRequest($pending, $now) === null;
}

function FinalizePendingGameResult(string $gameDirectory, bool $force = false): ?array
{
  $path = PendingResultPath($gameDirectory);
  $pending = ReadPendingResultFile($path);
  if ($pending === null) return null;
  if (!$force && !IsPendingGameResultDue($pending, PendingResultNowMs())) return null;
  $claimedPath = $path . "." . bin2hex(random_bytes(4)) . ".sending";
  if (!@rename($path, $claimedPath)) return null;
  $pending = ReadPendingResultFile($claimedPath) ?? $pending;
  if (!$force && !IsPendingGameResultDue($pending, PendingResultNowMs())) {
    @rename($claimedPath, $path);
    return null;
  }
  @unlink($claimedPath);

  $result = $pending["result"] ?? null;
  if (is_array($result)) SendGameResultRequests($result["requests"] ?? []);

  $gameName = basename(rtrim($gameDirectory, "/\\"));
  try {
    include_once __DIR__ . "/GameHealthLog.php";
    FlushGameHealthLog($gameName);
  } catch (Throwable $e) {
    error_log("FinalizePendingGameResult: FlushGameHealthLog threw: " . $e->getMessage());
  }
  try {
    include_once __DIR__ . "/RecentGameLibraries.php";
    StashRecentGame($gameDirectory);
  } catch (Throwable $e) {
    error_log("FinalizePendingGameResult: StashRecentGame threw: " . $e->getMessage());
  }
  return $pending;
}

function SendGameResultRequests($requests): void
{
  global $FaBraryKey, $FaBInsightsKey, $FaBBazaarKey;
  if (empty($requests)) return;
  if (file_exists(__DIR__ . "/../APIKeys/APIKeys.php")) include_once __DIR__ . "/../APIKeys/APIKeys.php";
  $headers = [
    "fabrary" => ["x-api-key: " . $FaBraryKey, "Content-Type: application/json"],
    "insights" => ["Content-Type: application/json", "x-functions-key: " . $FaBInsightsKey],
    "bazaar" => ["Content-Type: application/json", "x-api-key: " . $FaBBazaarKey, "User-Agent: Talishar"],
  ];
  $handles = [];
  foreach ($requests as $request) {
    $service = $request["service"] ?? "";
    if (!isset($headers[$service])) continue;
    $ch = curl_init($request["url"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request["body"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers[$service]);
    $handles[] = $ch;
  }
  executeParallelCurlRequests($handles);
}

function executeParallelCurlRequests($handles): void
{
  if (empty($handles)) return;

  $mh = curl_multi_init();
  foreach ($handles as $ch) {
    curl_multi_add_handle($mh, $ch);
  }

  $running = null;
  do {
    $status = curl_multi_exec($mh, $running);
    if ($running > 0) {
      curl_multi_select($mh, 1.0);
    }
  } while ($running > 0 && $status === CURLM_OK);

  foreach ($handles as $ch) {
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
  }
  curl_multi_close($mh);
}
