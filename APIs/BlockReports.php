<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

RequireModeratorSession();
session_write_close();

$postData = ReadPostData();
$days = max(1, min(365, intval(TryPOSTData("days", 90, $postData))));
$minBlocks = max(1, min(50, intval(TryPOSTData("minBlocks", 2, $postData))));

$conn = GetDBConnection(DBL_BLOCK_REPORTS);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$summaryQuery = "
  SELECT u.usersId, u.usersUid, u.displayName, u.isBanned,
    COUNT(*) AS blockCount,
    SUM(b.reason = 'rude') AS rude,
    SUM(b.reason = 'insulting') AS insulting,
    SUM(b.reason = 'unsporting') AS unsporting,
    SUM(b.reason = 'other') AS other,
    MAX(b.createdAt) AS lastBlockedAt
  FROM blocked_users b
  JOIN users u ON u.usersId = b.blockedUserId
  WHERE b.createdAt >= NOW() - INTERVAL ? DAY
  GROUP BY u.usersId, u.usersUid, u.displayName, u.isBanned
  HAVING blockCount >= ?
  ORDER BY (SUM(b.reason = 'rude') + SUM(b.reason = 'insulting')) DESC, blockCount DESC, lastBlockedAt DESC
  LIMIT 100
";
$stmt = $conn->prepare($summaryQuery);
$stmt->bind_param("ii", $days, $minBlocks);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$players = [];
foreach ($rows as $row) {
  $players[(int)$row['usersId']] = [
    'usersId' => (int)$row['usersId'],
    'username' => $row['usersUid'],
    'displayName' => $row['displayName'] ?: $row['usersUid'],
    'isBanned' => (int)$row['isBanned'] === 1,
    'blockCount' => (int)$row['blockCount'],
    'reasonCounts' => [
      'rude' => (int)$row['rude'],
      'insulting' => (int)$row['insulting'],
      'unsporting' => (int)$row['unsporting'],
      'other' => (int)$row['other'],
    ],
    'lastBlockedAt' => $row['lastBlockedAt'],
    'notes' => [],
  ];
}

if (!empty($players)) {
  $ids = array_keys($players);
  $placeholders = implode(",", array_fill(0, count($ids), "?"));
  $notesQuery = "
    SELECT b.blockedUserId, b.reason, b.note, b.createdAt, blocker.usersUid AS blockedBy
    FROM blocked_users b
    JOIN users blocker ON blocker.usersId = b.userId
    WHERE b.blockedUserId IN ($placeholders)
      AND b.createdAt >= NOW() - INTERVAL ? DAY
      AND (b.reason IS NOT NULL OR b.note IS NOT NULL)
    ORDER BY b.createdAt DESC
  ";
  $stmt = $conn->prepare($notesQuery);
  $params = array_merge($ids, [$days]);
  $stmt->bind_param(str_repeat("i", count($params)), ...$params);
  $stmt->execute();
  $noteRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  foreach ($noteRows as $noteRow) {
    $id = (int)$noteRow['blockedUserId'];
    if (count($players[$id]['notes']) >= 20) continue;
    $players[$id]['notes'][] = [
      'reason' => $noteRow['reason'],
      'note' => $noteRow['note'],
      'blockedBy' => $noteRow['blockedBy'],
      'createdAt' => $noteRow['createdAt'],
    ];
  }
}

mysqli_close($conn);

echo json_encode([
  "status" => "success",
  "days" => $days,
  "minBlocks" => $minBlocks,
  "players" => array_values($players),
]);
