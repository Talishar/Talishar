<?php

include_once '../Libraries/HTTPLibraries.php';
include_once "./AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";

SetHeaders();

$response = new stdClass();

// Check if user is logged in
if (!IsUserLoggedIn()) {
  $response->success = false;
  $response->message = "You must be logged in to delete your account.";
  echo json_encode($response);
  exit;
}

// Get the logged-in user ID and username
$userID = LoggedInUser();
$userName = LoggedInUserName();

// Get JSON input
$_POST = json_decode(file_get_contents('php://input'), true);
$confirmationUsername = isset($_POST["confirmationUsername"]) ? $_POST["confirmationUsername"] : "";

// Verify user entered correct username as confirmation
if ($confirmationUsername !== $userName) {
  $response->success = false;
  $response->message = "Username confirmation does not match. Account deletion cancelled.";
  echo json_encode($response);
  exit;
}

try {
  $conn = GetDBConnection();
  
  if (!$conn) {
    throw new Exception("Failed to connect to database.");
  }
  
  // Start transaction
  mysqli_begin_transaction($conn);
  
  // Delete from completedgame table (has foreign key constraints referencing users)
  $sql = "DELETE FROM completedgame WHERE WinningPID=? OR LosingPID=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    throw new Exception("Error preparing completedgame delete statement: " . mysqli_error($conn));
  }
  
  mysqli_stmt_bind_param($stmt, "ss", $userID, $userID);
  
  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception("Error deleting completed games: " . mysqli_stmt_error($stmt));
  }
  
  mysqli_stmt_close($stmt);
  
  // Delete from favoritedeck table
  $sql = "DELETE FROM favoritedeck WHERE usersId=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  
  // Delete from users table using the correct column name 'usersId'
  $sql = "DELETE FROM users WHERE usersId=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    throw new Exception("Error preparing delete statement: " . mysqli_error($conn));
  }
  
  mysqli_stmt_bind_param($stmt, "s", $userID);
  
  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception("Error deleting user account: " . mysqli_stmt_error($stmt));
  }
  
  $affectedRows = mysqli_stmt_affected_rows($stmt);
  mysqli_stmt_close($stmt);
  
  if ($affectedRows === 0) {
    throw new Exception("User account not found in database.");
  }
  
  // Commit transaction
  mysqli_commit($conn);
  mysqli_close($conn);
  
  // Clear the session
  ClearLoginSession();
  
  $response->success = true;
  $response->message = "Account deleted successfully. Your session has been cleared.";
  
} catch (Exception $e) {
  // Rollback on error
  if (isset($conn)) {
    @mysqli_rollback($conn);
    @mysqli_close($conn);
  }
  
  $response->success = false;
  $response->message = "Error deleting account: " . $e->getMessage();
}

echo json_encode($response);
exit;
?>
