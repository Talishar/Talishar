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
  $conn = GetDBConnection();
  if ($conn) {
    // Search for usernames containing the search query
    $sql = "SELECT usersUid, usersEmail FROM users WHERE usersUid LIKE ? LIMIT 20";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
      $searchPattern = "%{$searchQuery}%";
      mysqli_stmt_bind_param($stmt, "s", $searchPattern);
      mysqli_stmt_execute($stmt);
      $userData = mysqli_stmt_get_result($stmt);

      while ($row = mysqli_fetch_assoc($userData)) {
        if (!empty($row['usersUid'])) {
          $response["users"][] = [
            "username" => $row['usersUid'],
            "email" => $row['usersEmail']
          ];
        }
      }

      mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
  }
}

echo json_encode($response);
?>
