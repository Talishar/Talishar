<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../AccountFiles/AccountDatabaseAPI.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
SetHeaders();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);
$decklink = TryPOST("decklink", "");
$heroID = TryPOST("heroID", "");

if (empty($decklink)) {
  $response->success = false;
  $response->message = "Deck link is required.";
  echo json_encode($response);
  exit;
}

session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (!isset($_SESSION["userid"])) {
  $response->success = false;
  $response->message = "You must be logged in to update favorite decks.";
  echo json_encode($response);
  exit;
}

$userID = $_SESSION["userid"];

// Update the hero for the favorite deck
try {
  $conn = GetDBConnection();
  if (!$conn) {
    $response->success = false;
    $response->message = "Database connection failed.";
    echo json_encode($response);
    exit;
  }

  $sql = "UPDATE favoritedeck SET hero = ? WHERE decklink = ? AND usersId = ?";
  $stmt = mysqli_stmt_init($conn);
  
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "sss", $heroID, $decklink, $userID);
    if (mysqli_stmt_execute($stmt)) {
      if (mysqli_stmt_affected_rows($stmt) > 0) {
        $response->success = true;
        $response->message = "Deck hero updated successfully.";
      } else {
        $response->success = false;
        $response->message = "Deck not found or hero not changed.";
      }
    } else {
      $response->success = false;
      $response->message = "Failed to update deck.";
    }
    mysqli_stmt_close($stmt);
  } else {
    $response->success = false;
    $response->message = "Database query failed.";
  }
  
  mysqli_close($conn);
} catch (Exception $e) {
  $response->success = false;
  $response->message = "An error occurred: " . $e->getMessage();
}

echo json_encode($response);
?>
