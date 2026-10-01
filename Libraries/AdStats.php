<?php

const AD_STATS_MAX_BODY = 32768;
const AD_STATS_MAX_ROWS = 64;
const AD_STATS_MAX_CPM_MICROS = 50000;
const AD_STATS_MAX_VISIBLE_MS = 3600000;
const AD_STATS_DEVICES = ["desktop", "mobile"];

const AD_STATS_SLOT_FIELDS = [
  "mounts", "seen", "visible_ms", "requests", "filled", "viewable", "clicks",
  "prebid_wins", "prebid_micros", "fill_bid_micros", "priced_fills"
];

function EnsureAdStatsTables($conn)
{
  $tables = [
    "CREATE TABLE IF NOT EXISTS ad_slot_stats (
      day DATE NOT NULL,
      page VARCHAR(24) NOT NULL,
      placement VARCHAR(40) NOT NULL,
      device VARCHAR(8) NOT NULL,
      mounts INT UNSIGNED NOT NULL DEFAULT 0,
      seen INT UNSIGNED NOT NULL DEFAULT 0,
      visible_ms BIGINT UNSIGNED NOT NULL DEFAULT 0,
      requests INT UNSIGNED NOT NULL DEFAULT 0,
      filled INT UNSIGNED NOT NULL DEFAULT 0,
      viewable INT UNSIGNED NOT NULL DEFAULT 0,
      clicks INT UNSIGNED NOT NULL DEFAULT 0,
      prebid_wins INT UNSIGNED NOT NULL DEFAULT 0,
      prebid_micros BIGINT UNSIGNED NOT NULL DEFAULT 0,
      fill_bid_micros BIGINT UNSIGNED NOT NULL DEFAULT 0,
      priced_fills INT UNSIGNED NOT NULL DEFAULT 0,
      PRIMARY KEY (day, page, placement, device)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS ad_page_stats (
      day DATE NOT NULL,
      page VARCHAR(24) NOT NULL,
      device VARCHAR(8) NOT NULL,
      views INT UNSIGNED NOT NULL DEFAULT 0,
      visible_ms BIGINT UNSIGNED NOT NULL DEFAULT 0,
      adblock_views INT UNSIGNED NOT NULL DEFAULT 0,
      PRIMARY KEY (day, page, device)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS ad_bidder_stats (
      day DATE NOT NULL,
      bidder VARCHAR(32) NOT NULL,
      device VARCHAR(8) NOT NULL,
      bids INT UNSIGNED NOT NULL DEFAULT 0,
      wins INT UNSIGNED NOT NULL DEFAULT 0,
      win_micros BIGINT UNSIGNED NOT NULL DEFAULT 0,
      PRIMARY KEY (day, bidder, device)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
  ];
  foreach ($tables as $sql) {
    if (!mysqli_query($conn, $sql)) {
      error_log("Failed to create ad stats table: " . mysqli_error($conn));
    }
  }
}

function AdStatsInt($value, $max)
{
  if (!is_int($value) && !is_float($value)) return 0;
  return (int)max(0, min($max, $value));
}

function AdStatsDevice($value)
{
  return in_array($value, AD_STATS_DEVICES, true) ? $value : null;
}

function AdStatsPage($value)
{
  return is_string($value) && preg_match('/^[a-z][a-z-]{0,23}$/', $value) ? $value : null;
}

function AdStatsPlacement($value)
{
  return is_string($value) && preg_match('/^[a-z0-9][a-z0-9._#-]{0,39}$/', $value) ? $value : null;
}

function AdStatsBidder($value)
{
  if (!is_string($value)) return null;
  $value = strtolower($value);
  return preg_match('/^[a-z0-9][a-z0-9_.-]{0,31}$/', $value) ? $value : null;
}

function ParseAdStatsPayload($payload)
{
  if (!is_array($payload)) return null;
  $parsed = ["pages" => [], "slots" => [], "bidders" => []];

  foreach (array_slice(is_array($payload["p"] ?? null) ? $payload["p"] : [], 0, AD_STATS_MAX_ROWS) as $row) {
    if (!is_array($row) || count($row) != 5) continue;
    $page = AdStatsPage($row[0]);
    $device = AdStatsDevice($row[1]);
    if ($page === null || $device === null) continue;
    $parsed["pages"][] = [$page, $device, AdStatsInt($row[2], 50), AdStatsInt($row[3], AD_STATS_MAX_VISIBLE_MS), AdStatsInt($row[4], 50)];
  }

  foreach (array_slice(is_array($payload["s"] ?? null) ? $payload["s"] : [], 0, AD_STATS_MAX_ROWS) as $row) {
    if (!is_array($row) || count($row) != 3 + count(AD_STATS_SLOT_FIELDS)) continue;
    $page = AdStatsPage($row[0]);
    $placement = AdStatsPlacement($row[1]);
    $device = AdStatsDevice($row[2]);
    if ($page === null || $placement === null || $device === null) continue;
    $mounts = AdStatsInt($row[3], 50);
    $requests = AdStatsInt($row[6], 500);
    $filled = AdStatsInt($row[7], $requests);
    $prebidWins = AdStatsInt($row[10], 500);
    $parsed["slots"][] = [
      $page, $placement, $device,
      $mounts,
      AdStatsInt($row[4], $mounts),
      AdStatsInt($row[5], AD_STATS_MAX_VISIBLE_MS),
      $requests,
      $filled,
      AdStatsInt($row[8], 500),
      AdStatsInt($row[9], 20),
      $prebidWins,
      AdStatsInt($row[11], $prebidWins * AD_STATS_MAX_CPM_MICROS),
      AdStatsInt($row[12], $filled * AD_STATS_MAX_CPM_MICROS),
      AdStatsInt($row[13], $filled)
    ];
  }

  foreach (array_slice(is_array($payload["b"] ?? null) ? $payload["b"] : [], 0, AD_STATS_MAX_ROWS) as $row) {
    if (!is_array($row) || count($row) != 5) continue;
    $bidder = AdStatsBidder($row[0]);
    $device = AdStatsDevice($row[1]);
    if ($bidder === null || $device === null) continue;
    $wins = AdStatsInt($row[3], 500);
    $parsed["bidders"][] = [$bidder, $device, AdStatsInt($row[2], 5000), $wins, AdStatsInt($row[4], $wins * AD_STATS_MAX_CPM_MICROS)];
  }

  return $parsed;
}

function AdStatsUpsert($conn, $table, $keyColumns, $valueColumns, $rows, $types)
{
  if (count($rows) == 0) return;
  $columns = array_merge(["day"], $keyColumns, $valueColumns);
  $placeholder = "(" . implode(",", array_fill(0, count($columns), "?")) . ")";
  $updates = implode(", ", array_map(fn($column) => "$column = $column + VALUES($column)", $valueColumns));
  $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES "
    . implode(",", array_fill(0, count($rows), $placeholder))
    . " ON DUPLICATE KEY UPDATE $updates";
  $day = gmdate("Y-m-d");
  $params = [];
  foreach ($rows as $row) array_push($params, $day, ...$row);
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, str_repeat("s" . $types, count($rows)), ...$params);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

function RecordAdStats($conn, $parsed)
{
  $writes = [
    ["ad_page_stats", ["page", "device"], ["views", "visible_ms", "adblock_views"], $parsed["pages"], "ssiii"],
    ["ad_slot_stats", ["page", "placement", "device"], AD_STATS_SLOT_FIELDS, $parsed["slots"], "sss" . str_repeat("i", count(AD_STATS_SLOT_FIELDS))],
    ["ad_bidder_stats", ["bidder", "device"], ["bids", "wins", "win_micros"], $parsed["bidders"], "ssiii"]
  ];
  foreach ($writes as $write) {
    try {
      AdStatsUpsert($conn, ...$write);
    } catch (mysqli_sql_exception $e) {
      if ($e->getCode() != 1146) throw $e;
      EnsureAdStatsTables($conn);
      AdStatsUpsert($conn, ...$write);
    }
  }
}
