<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once "../includes/dbh.inc.php";
include_once '../Libraries/AdStats.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  header('Allow: POST');
  exit;
}

$body = file_get_contents('php://input', false, null, 0, AD_STATS_MAX_BODY + 1);
if ($body === false || $body === '' || strlen($body) > AD_STATS_MAX_BODY) {
  http_response_code(400);
  exit;
}

$parsed = ParseAdStatsPayload(json_decode($body, true));
if ($parsed === null) {
  http_response_code(400);
  exit;
}
if (count($parsed["pages"]) + count($parsed["slots"]) + count($parsed["bidders"]) == 0) {
  http_response_code(204);
  exit;
}

$conn = GetDBConnection(DBL_SITE_METRICS);
if (!$conn) {
  http_response_code(503);
  exit;
}

try {
  RecordAdStats($conn, $parsed);
} catch (Throwable $e) {
  error_log("SiteMetrics failed: " . $e->getMessage());
}

mysqli_close($conn);
http_response_code(204);
