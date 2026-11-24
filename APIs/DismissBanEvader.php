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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  ExitJsonResponse(["error" => "Method not allowed. Use POST."], 405);
}

$moderator = RequireModeratorSession();
session_write_close();

$postData = ReadPostData();
$username = trim((string)TryPOSTData("username", "", $postData));
$ips = TryPOSTData("ips", [], $postData);
if ($username === "") ExitJsonResponse(["error" => "Missing username."], 400);
if (!is_array($ips)) ExitJsonResponse(["error" => "Invalid IP list."], 400);
$ips = array_values(array_unique(array_filter(
  array_map(fn($ip) => trim((string)$ip), $ips),
  fn($ip) => $ip !== "" && strlen($ip) <= 45
)));
if (count($ips) === 0 || count($ips) > 1000) ExitJsonResponse(["error" => "Invalid IP list."], 400);

$conn = GetDBConnection(DBL_DISMISS_BAN_EVADER);
if (!$conn) ExitJsonResponse(["error" => "Database connection failed"], 500);

$stmt = $conn->prepare("SELECT usersId FROM users WHERE usersUid = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$row = $stmt->get_result()->fetch_array(MYSQLI_NUM);
$stmt->close();
if (!$row) {
  mysqli_close($conn);
  ExitJsonResponse(["error" => "Player not found."], 404);
}
$usersId = (int)$row[0];

EnsureBanEvaderDismissalsTable($conn);
$stmt = $conn->prepare("INSERT INTO ban_evader_dismissals (usersId, ip, dismissedBy) VALUES (?, ?, ?)
  ON DUPLICATE KEY UPDATE dismissedBy = VALUES(dismissedBy), dismissedAt = CURRENT_TIMESTAMP");
foreach ($ips as $ip) {
  $stmt->bind_param("iss", $usersId, $ip, $moderator);
  $stmt->execute();
}
$stmt->close();
mysqli_close($conn);

echo json_encode(["success" => true, "dismissed" => count($ips)]);
