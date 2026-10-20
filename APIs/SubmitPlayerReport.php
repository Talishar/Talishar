<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  ExitJsonResponse(["error" => "Method not allowed. Use POST."], 405);
}

include_once "../includes/dbh.inc.php";
include_once "../Libraries/ModerationLibraries.php";

const PLAYER_REPORT_NOTE_MAX_LENGTH = 500;

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$reporterUserId = isset($_SESSION["userid"]) && is_numeric($_SESSION["userid"]) ? intval($_SESSION["userid"]) : null;
session_write_close();

$postData = ReadPostData();
$gameName = (string)TryPOSTData("gameName", "", $postData);
$playerID = intval(TryPOSTData("playerID", 0, $postData));
$authKey = (string)TryPOSTData("authKey", "", $postData);

if (!IsGameNameValid($gameName)) ExitJsonResponse(["error" => "Invalid game name."], 400);
if ($playerID !== 1 && $playerID !== 2) ExitJsonResponse(["error" => "Only players can report."], 403);

$gameFile = @file("../Games/" . $gameName . "/GameFile.txt", FILE_IGNORE_NEW_LINES);
if ($gameFile === false || count($gameFile) < 11) ExitJsonResponse(["error" => "Game does not exist."], 404);

$playerKey = trim($gameFile[$playerID == 1 ? 7 : 8]);
if ($authKey === "" && isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
if ($playerKey === "" || !hash_equals($playerKey, $authKey)) ExitJsonResponse(["error" => "Authentication failed"], 403);

$reportedName = substr(trim($gameFile[$playerID == 1 ? 10 : 9]), 0, 64);
if ($reportedName === "") $reportedName = "-";
$reason = NormalizeModerationReason(TryPOSTData("reason", null, $postData));
$note = NormalizeModerationNote(TryPOSTData("note", "", $postData), PLAYER_REPORT_NOTE_MAX_LENGTH);

$conn = GetDBConnection(DBL_SUBMIT_PLAYER_REPORT);
if (!$conn) ExitJsonResponse(["error" => "Database connection failed"], 500);

$reportedUserId = null;
if ($reportedName !== "-") {
  $stmt = $conn->prepare("SELECT usersId FROM users WHERE usersUid = ? LIMIT 1");
  $stmt->bind_param("s", $reportedName);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  if ($row) $reportedUserId = (int)$row['usersId'];
}

$stmt = $conn->prepare("
  INSERT INTO player_reports (gameName, reporterPlayerID, reporterUserId, reportedName, reportedUserId, reason, note)
  VALUES (?, ?, ?, ?, ?, ?, ?)
  ON DUPLICATE KEY UPDATE reason = VALUES(reason), note = VALUES(note), createdAt = CURRENT_TIMESTAMP
");
$stmt->bind_param("siisiss", $gameName, $playerID, $reporterUserId, $reportedName, $reportedUserId, $reason, $note);
$stmt->execute();
$stmt->close();
mysqli_close($conn);

echo json_encode(["success" => true]);
