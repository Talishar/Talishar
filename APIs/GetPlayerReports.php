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

$conn = GetDBConnection(DBL_GET_PLAYER_REPORTS);
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$query = "
  SELECT r.reportId, r.gameName, r.reporterPlayerID, reporter.usersUid AS reporterName,
    r.reportedName, r.reportedUserId, reported.isBanned AS reportedIsBanned,
    r.reason, r.note, r.createdAt, counts.reportCount
  FROM player_reports r
  LEFT JOIN users reporter ON reporter.usersId = r.reporterUserId
  LEFT JOIN users reported ON reported.usersId = r.reportedUserId
  JOIN (
    SELECT reportedName, COUNT(*) AS reportCount
    FROM player_reports
    WHERE createdAt >= NOW() - INTERVAL ? DAY
    GROUP BY reportedName
  ) counts ON counts.reportedName = r.reportedName
  WHERE r.createdAt >= NOW() - INTERVAL ? DAY
  ORDER BY r.createdAt DESC
  LIMIT 200
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $days, $days);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
mysqli_close($conn);

$reports = [];
foreach ($rows as $row) {
  $reports[] = [
    'reportId' => (int)$row['reportId'],
    'gameName' => $row['gameName'],
    'reporterName' => $row['reporterName'] ?? null,
    'reportedName' => $row['reportedName'],
    'reportedHasAccount' => $row['reportedUserId'] !== null,
    'reportedIsBanned' => (int)($row['reportedIsBanned'] ?? 0) === 1,
    'reason' => $row['reason'],
    'note' => $row['note'],
    'createdAt' => $row['createdAt'],
    'reportCount' => (int)$row['reportCount'],
  ];
}

echo json_encode([
  "status" => "success",
  "days" => $days,
  "reports" => $reports,
]);
