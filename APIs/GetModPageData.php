<?php

include_once __DIR__ . '/../includes/ApiBootstrap.php';

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

$useruid = RequireModeratorSession();

$response = [
  "topSpectators" => null,
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
  try {
    $spectatorResult = mysqli_query($conn, "SELECT usersUid AS username, numSpectates AS gameCount
      FROM users WHERE numSpectates > 0
      ORDER BY numSpectates DESC, usersUid ASC LIMIT 20");
    if ($spectatorResult) {
      $response['topSpectators'] = [];
      while ($row = mysqli_fetch_assoc($spectatorResult)) {
        $row['gameCount'] = (int)$row['gameCount'];
        $response['topSpectators'][] = $row;
      }
      mysqli_free_result($spectatorResult);
    } else {
      $response['topSpectators'] = null;
    }
  } catch (Exception $e) {
    $response['topSpectators'] = null;
    error_log('GetModPageData spectator totals: ' . $e->getMessage());
  }

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

  EnsureBannedPlayersTable($conn);
  $sql = "SELECT name FROM banned_players ORDER BY createdAt";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $banNameData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($banNameData, MYSQLI_NUM)) {
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

  EnsureBanEvaderDismissalsTable($conn);
  $linkedAccounts = [];
  $sql = "SELECT DISTINCT u2.usersUid, h1.ip, u1.usersUid, h2.lastSeen
          FROM users u1
          JOIN ip_history h1 ON h1.usersId = u1.usersId
          JOIN ip_history h2 ON h2.ip = h1.ip AND h2.usersId != h1.usersId
          JOIN users u2 ON u2.usersId = h2.usersId
          LEFT JOIN ban_evader_dismissals d ON d.usersId = u2.usersId AND d.ip = h1.ip
          WHERE u1.isBanned = 1 AND u2.isBanned = 0 AND d.usersId IS NULL
          LIMIT 2000";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $linkData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($linkData, MYSQLI_NUM)) {
      $linkedAccounts[] = ["username" => $row[0], "ip" => $row[1], "linkedTo" => $row[2], "lastSeen" => $row[3]];
    }
    mysqli_stmt_close($stmt);
  }
  $sql = "SELECT DISTINCT u.usersUid, b.ip, h.lastSeen
          FROM users u
          JOIN ip_history h ON h.usersId = u.usersId
          JOIN banned_ips b ON b.ip = h.ip
          LEFT JOIN ban_evader_dismissals d ON d.usersId = u.usersId AND d.ip = h.ip
          WHERE u.isBanned = 0 AND d.usersId IS NULL
          LIMIT 2000";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $linkData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($linkData, MYSQLI_NUM)) {
      $linkedAccounts[] = ["username" => $row[0], "ip" => $row[1], "linkedTo" => "banned IP", "lastSeen" => $row[2]];
    }
    mysqli_stmt_close($stmt);
  }
  $response["linkedAccounts"] = $linkedAccounts;

  $sharedIPs = array_values(array_unique(array_column($linkedAccounts, "ip")));
  $ipAccountCounts = [];
  foreach (array_chunk($sharedIPs, 500) as $chunk) {
    $placeholders = implode(",", array_fill(0, count($chunk), "?"));
    $stmt = $conn->prepare("SELECT ip, COUNT(*) FROM ip_history WHERE ip IN ($placeholders) GROUP BY ip");
    if ($stmt) {
      $stmt->bind_param(str_repeat("s", count($chunk)), ...$chunk);
      $stmt->execute();
      $countData = $stmt->get_result();
      while ($row = $countData->fetch_array(MYSQLI_NUM)) {
        $ipAccountCounts[$row[0]] = (int)$row[1];
      }
      $stmt->close();
    }
  }
  $response["ipAccountCounts"] = count($ipAccountCounts) > 0 ? $ipAccountCounts : new stdClass();

  mysqli_close($conn);
}

$response["bannedPlayers"] = array_values($bannedPlayers);
$response["bannedIPs"] = array_values($bannedIPs);

echo json_encode($response);
?>
