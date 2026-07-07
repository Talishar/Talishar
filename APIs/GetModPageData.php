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
  "recentAccounts" => [],
  "linkedAccounts" => [],
  "bannedPlayerIPs" => new stdClass(),
  "recentNameChanges" => []
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

  $sql = "SELECT usersUid, displayName FROM users ORDER BY usersId DESC LIMIT 20";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $userData = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_array($userData, MYSQLI_NUM)) {
      if (!empty($row[0])) {
        // Mods see the handle, annotated with the display name when one is set
        $accountLabel = $row[0];
        if (!empty($row[1]) && $row[1] != $row[0]) $accountLabel .= " (aka " . $row[1] . ")";
        array_push($response["recentAccounts"], $accountLabel);
      }
    }

    mysqli_stmt_close($stmt);
  }

  // Recent display-name changes so mods can trace accounts across renames
  $sql = "SELECT u.usersUid, h.oldName, h.newName, h.changedAt
          FROM name_history h
          JOIN users u ON u.usersId = h.usersId
          ORDER BY h.changedAt DESC LIMIT 50";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $nameData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($nameData, MYSQLI_NUM)) {
      $response["recentNameChanges"][] = [
        "username" => $row[0],
        "oldName" => $row[1],
        "newName" => $row[2],
        "changedAt" => $row[3]
      ];
    }
    mysqli_stmt_close($stmt);
  }

  EnsureIPHistoryTable($conn);

  $bannedPlayerIPs = [];
  $sql = "SELECT u.usersUid, GROUP_CONCAT(h.ip ORDER BY h.lastSeen DESC SEPARATOR ',')
          FROM users u
          JOIN ip_history h ON h.usersId = u.usersId
          WHERE u.isBanned = 1
          GROUP BY u.usersId, u.usersUid";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $ipHistData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($ipHistData, MYSQLI_NUM)) {
      if (!empty($row[0]) && !empty($row[1])) {
        $bannedPlayerIPs[strtolower($row[0])] = explode(",", $row[1]);
      }
    }
    mysqli_stmt_close($stmt);
  }
  if (count($bannedPlayerIPs) > 0) $response["bannedPlayerIPs"] = $bannedPlayerIPs;

  $linkedAccounts = [];
  $sql = "SELECT DISTINCT u2.usersUid, h1.ip, u1.usersUid
          FROM users u1
          JOIN ip_history h1 ON h1.usersId = u1.usersId
          JOIN ip_history h2 ON h2.ip = h1.ip AND h2.usersId != h1.usersId
          JOIN users u2 ON u2.usersId = h2.usersId
          WHERE u1.isBanned = 1 AND u2.isBanned = 0
          LIMIT 200";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $linkData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($linkData, MYSQLI_NUM)) {
      $linkedAccounts[$row[0] . "|" . $row[1]] = ["username" => $row[0], "ip" => $row[1], "linkedTo" => $row[2]];
    }
    mysqli_stmt_close($stmt);
  }
  $sql = "SELECT DISTINCT u.usersUid, b.ip
          FROM users u
          JOIN ip_history h ON h.usersId = u.usersId
          JOIN banned_ips b ON b.ip = h.ip
          WHERE u.isBanned = 0
          LIMIT 200";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $linkData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($linkData, MYSQLI_NUM)) {
      $key = $row[0] . "|" . $row[1];
      if (!isset($linkedAccounts[$key])) {
        $linkedAccounts[$key] = ["username" => $row[0], "ip" => $row[1], "linkedTo" => "banned IP"];
      }
    }
    mysqli_stmt_close($stmt);
  }
  $response["linkedAccounts"] = array_values($linkedAccounts);

  mysqli_close($conn);
}

$response["bannedPlayers"] = array_values($bannedPlayers);
$response["bannedIPs"] = array_values($bannedIPs);

echo json_encode($response);
?>