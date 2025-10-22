<?php

ob_start();
include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
ob_end_clean();
SetHeaders();

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION["useruid"])) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$useruid = $_SESSION["useruid"];

$modList = ["OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "bavverst", "Tower", "PvtVoid", "Aegisworn"];
if (!in_array($useruid, $modList)) {
  http_response_code(403);
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

$response = [
  "bannedPlayers" => [],
  "bannedIPs" => [],
  "recentAccounts" => []
];

$bannedPlayersFile = "../HostFiles/bannedPlayers.txt";
if (file_exists($bannedPlayersFile)) {
  $bannedPlayers = file($bannedPlayersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $response["bannedPlayers"] = $bannedPlayers ? array_values(array_filter($bannedPlayers)) : [];
}

$bannedIPsFile = "../HostFiles/bannedIPs.txt";
if (file_exists($bannedIPsFile)) {
  $bannedIPs = file($bannedIPsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $response["bannedIPs"] = $bannedIPs ? array_values(array_filter($bannedIPs)) : [];
}

$conn = GetDBConnection();
if ($conn) {
  $sql = "SELECT usersUid FROM users ORDER BY usersId DESC LIMIT 20";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $userData = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_array($userData, MYSQLI_NUM)) {
      if (!empty($row[0])) {
        array_push($response["recentAccounts"], $row[0]);
      }
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
}

echo json_encode($response);
?>