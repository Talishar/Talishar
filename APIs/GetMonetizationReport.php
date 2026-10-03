<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';
include_once '../Libraries/AdStats.php';

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
  "slots" => [],
  "pages" => [],
  "bidders" => [],
  "events" => [],
  "daily" => []
];

$conn = GetDBConnection(DBL_GET_MONETIZATION_REPORT);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

function AdReportRows($conn, $sql, $since)
{
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $since);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    foreach ($row as $key => $value) {
      if (is_numeric($value) && !in_array($key, ["page", "placement", "device", "bidder", "day"], true)) $row[$key] = (int)$value;
    }
    $rows[] = $row;
  }
  mysqli_stmt_close($stmt);
  return $rows;
}

try {
  EnsureAdStatsTables($conn);

  $response["slots"] = AdReportRows($conn, "SELECT page, placement, device, SUM(mounts) AS mounts, SUM(seen) AS seen,
      SUM(visible_ms) AS visibleMs, SUM(requests) AS requests, SUM(filled) AS filled, SUM(viewable) AS viewable,
      SUM(clicks) AS clicks, SUM(prebid_wins) AS prebidWins, SUM(prebid_micros) AS prebidMicros,
      SUM(GREATEST(prebid_micros, fill_bid_micros)) AS estMicros, SUM(priced_fills) AS pricedFills
    FROM ad_slot_stats WHERE day >= ? GROUP BY page, placement, device", $since);

  $response["pages"] = AdReportRows($conn, "SELECT page, device, SUM(views) AS views, SUM(visible_ms) AS visibleMs,
      SUM(adblock_views) AS adblockViews
    FROM ad_page_stats WHERE day >= ? GROUP BY page, device", $since);

  $response["bidders"] = AdReportRows($conn, "SELECT bidder, device, SUM(bids) AS bids, SUM(wins) AS wins,
      SUM(win_micros) AS winMicros
    FROM ad_bidder_stats WHERE day >= ? GROUP BY bidder, device", $since);

  $response["events"] = AdReportRows($conn, "SELECT page, placement, device, event, SUM(count) AS count
    FROM ad_event_stats WHERE day >= ? GROUP BY page, placement, device, event", $since);

  $daily = [];
  $emptyDay = ["views" => 0, "estMicros" => 0, "unpricedFills" => 0, "displayImpressions" => 0,
    "videoImpressions" => 0, "videoViewable" => 0, "videoStarts" => 0, "videoCompletes" => 0, "rewardedShows" => 0];
  $mergeDaily = function ($rows) use (&$daily, $emptyDay) {
    foreach ($rows as $row) {
      $key = $row["day"] . "|" . $row["device"];
      $daily[$key] = array_merge($daily[$key] ?? $emptyDay, $row);
    }
  };
  $mergeDaily(AdReportRows($conn, "SELECT day, device, SUM(views) AS views FROM ad_page_stats
    WHERE day >= ? GROUP BY day, device", $since));
  $mergeDaily(AdReportRows($conn, "SELECT day, device,
      SUM(IF(placement = 'video' OR placement LIKE '%reward%', 0, GREATEST(prebid_micros, fill_bid_micros))) AS estMicros,
      SUM(IF(placement = 'video' OR placement LIKE '%reward%', 0, GREATEST(0, CAST(filled AS SIGNED) - CAST(priced_fills AS SIGNED)))) AS unpricedFills,
      SUM(IF(placement = 'video' OR placement LIKE '%reward%', 0, filled)) AS displayImpressions,
      SUM(IF(placement = 'video', filled, 0)) AS videoImpressions,
      SUM(IF(placement = 'video', viewable, 0)) AS videoViewable
    FROM ad_slot_stats WHERE day >= ? GROUP BY day, device", $since));
  $mergeDaily(AdReportRows($conn, "SELECT day, device,
      SUM(IF(placement = 'video' AND event = 'started', count, 0)) AS videoStarts,
      SUM(IF(placement = 'video' AND event = 'complete', count, 0)) AS videoCompletes,
      SUM(IF(event = 'shown', count, 0)) AS rewardedShows
    FROM ad_event_stats WHERE day >= ? GROUP BY day, device", $since));
  ksort($daily);
  $response["daily"] = array_values($daily);
} catch (Throwable $e) {
  error_log("GetMonetizationReport failed: " . $e->getMessage());
  http_response_code(500);
  $response["error"] = "Failed to load ad report";
}

mysqli_close($conn);
echo json_encode($response);
