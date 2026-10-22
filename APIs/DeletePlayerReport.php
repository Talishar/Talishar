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

RequireModeratorSession();
session_write_close();

$postData = ReadPostData();
$reportId = intval(TryPOSTData("reportId", 0, $postData));
if ($reportId <= 0) ExitJsonResponse(["error" => "Invalid report id."], 400);

$conn = GetDBConnection(DBL_DELETE_PLAYER_REPORT);
if (!$conn) ExitJsonResponse(["error" => "Database connection failed"], 500);

$stmt = $conn->prepare("DELETE FROM player_reports WHERE reportId = ?");
$stmt->bind_param("i", $reportId);
$stmt->execute();
$deleted = $stmt->affected_rows;
$stmt->close();
mysqli_close($conn);

if ($deleted === 0) ExitJsonResponse(["error" => "Report not found."], 404);

echo json_encode(["success" => true]);
