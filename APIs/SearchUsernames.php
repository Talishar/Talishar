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

// Get search query from GET parameter
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

$response = [
  "users" => []
];

if (strlen($searchQuery) > 0) {
  $conn = GetDBConnection(DBL_SEARCH_USERNAMES);
  if ($conn) {
    // Search handles, current display names, and historical names so mods can
    // find an account by any name it has ever used
    $sql = "SELECT DISTINCT u.usersUid, u.usersEmail, u.displayName FROM users u
            LEFT JOIN name_history h ON h.usersId = u.usersId
            WHERE u.usersUid LIKE ? OR u.displayName LIKE ? OR h.oldName LIKE ? OR h.newName LIKE ?
            LIMIT 20";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
      $searchPattern = "{$searchQuery}%";
      mysqli_stmt_bind_param($stmt, "ssss", $searchPattern, $searchPattern, $searchPattern, $searchPattern);
      mysqli_stmt_execute($stmt);
      $userData = mysqli_stmt_get_result($stmt);

      while ($row = mysqli_fetch_assoc($userData)) {
        if (!empty($row['usersUid'])) {
          $response["users"][] = [
            "username" => $row['usersUid'],
            "displayName" => !empty($row['displayName']) ? $row['displayName'] : $row['usersUid'],
            "email" => $row['usersEmail']
          ];
        }
      }

      mysqli_stmt_close($stmt);
    } else {
      // name_history may not exist yet (pre-migration); fall back to handle-only search
      $sql = "SELECT usersUid, usersEmail FROM users WHERE usersUid LIKE ? LIMIT 20";
      $stmt = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt, $sql)) {
        $searchPattern = "{$searchQuery}%";
        mysqli_stmt_bind_param($stmt, "s", $searchPattern);
        mysqli_stmt_execute($stmt);
        $userData = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($userData)) {
          if (!empty($row['usersUid'])) {
            $response["users"][] = [
              "username" => $row['usersUid'],
              "displayName" => $row['usersUid'],
              "email" => $row['usersEmail']
            ];
          }
        }
        mysqli_stmt_close($stmt);
      }
    }

    mysqli_close($conn);
  }
}

echo json_encode($response);
?>
