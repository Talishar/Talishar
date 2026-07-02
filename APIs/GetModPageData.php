<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
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

include_once '../includes/ModeratorList.inc.php';
if (!IsUserModerator($useruid)) {
  http_response_code(403);
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

$response = [
  "bannedPlayers" => [],
  "bannedIPs" => [],
  "recentAccounts" => []
];

$bannedPlayers = [];
$bannedPlayersFile = "../HostFiles/bannedPlayers.txt";
if (file_exists($bannedPlayersFile)) {
  $lines = file($bannedPlayersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if ($lines) {
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line != "") $bannedPlayers[strtolower($line)] = $line;
    }
  }
}

$bannedIPs = [];
$bannedIPsFile = "../HostFiles/bannedIPs.txt";
if (file_exists($bannedIPsFile)) {
  $lines = file($bannedIPsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if ($lines) {
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line != "") $bannedIPs[$line] = $line;
    }
  }
}

$conn = GetDBConnection(DBL_GET_MOD_PAGE_DATA);
if ($conn) {
  $sql = "SELECT usersUid FROM users WHERE isBanned = 1 ORDER BY usersUid";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $userData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($userData, MYSQLI_NUM)) {
      if (!empty($row[0])) $bannedPlayers[strtolower($row[0])] = $row[0];
    }
    mysqli_stmt_close($stmt);
  }

  EnsureBannedIPsTable($conn);
  $sql = "SELECT ip FROM banned_ips ORDER BY createdAt";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $ipData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($ipData, MYSQLI_NUM)) {
      if (!empty($row[0])) $bannedIPs[$row[0]] = $row[0];
    }
    mysqli_stmt_close($stmt);
  }

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

$response["bannedPlayers"] = array_values($bannedPlayers);
$response["bannedIPs"] = array_values($bannedIPs);

echo json_encode($response);
?>