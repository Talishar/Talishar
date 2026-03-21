<?php

include_once '../Libraries/HTTPLibraries.php';
include_once "./AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";
include_once '../includes/ModeratorList.inc.php';

SetHeaders();

$response = new stdClass();

// Check if user is logged in
if (!IsUserLoggedIn()) {
  $response->success = false;
  $response->message = "You must be logged in to delete an account.";
  echo json_encode($response);
  exit;
}

// Get the logged-in user ID and username
$loggedInUserID = LoggedInUser();
$loggedInUserName = LoggedInUserName();
$isModerator = IsUserModerator($loggedInUserName);

// Get JSON input
$_POST = json_decode(file_get_contents('php://input'), true);
$confirmationUsername = isset($_POST["confirmationUsername"]) ? $_POST["confirmationUsername"] : "";

// Determine if this is self-deletion or mod deletion
$isModDeletion = false;
$userIDToDelete = null;
$userNameToDelete = null;

// Check if moderator is deleting someone else's account
if ($isModerator && $confirmationUsername !== $loggedInUserName) {
  // This is a mod deleting another user
  $isModDeletion = true;
  $userNameToDelete = $confirmationUsername;
} else {
  // This is self-deletion - verify the username matches
  if ($confirmationUsername !== $loggedInUserName) {
    $response->success = false;
    $response->message = "Username confirmation does not match. Account deletion cancelled.";
    echo json_encode($response);
    exit;
  }
  $userNameToDelete = $loggedInUserName;
}

try {
  $conn = GetDBConnection();
  
  if (!$conn) {
    throw new Exception("Failed to connect to database.");
  }
  
  // Get the usersId for the username to delete
  $userIdSql = "SELECT usersId FROM users WHERE usersUid = ?";
  $userIdStmt = mysqli_stmt_init($conn);
  
  if (!mysqli_stmt_prepare($userIdStmt, $userIdSql)) {
    throw new Exception("Error preparing statement: " . mysqli_error($conn));
  }
  
  mysqli_stmt_bind_param($userIdStmt, "s", $userNameToDelete);
  
  if (!mysqli_stmt_execute($userIdStmt)) {
    throw new Exception("Error executing statement: " . mysqli_stmt_error($userIdStmt));
  }
  
  $result_ids = mysqli_stmt_get_result($userIdStmt);
  if ($row = mysqli_fetch_assoc($result_ids)) {
    $userIDToDelete = $row['usersId'];
  }
  mysqli_stmt_close($userIdStmt);
  
  if ($userIDToDelete === null) {
    $response->success = false;
    $response->message = "User not found.";
    echo json_encode($response);
    exit;
  }
  
  // Start transaction
  mysqli_begin_transaction($conn);
  
  // Delete from completedgame table (has foreign key constraints referencing users)
  $sql = "DELETE FROM completedgame WHERE WinningPID=? OR LosingPID=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    throw new Exception("Error preparing completedgame delete statement: " . mysqli_error($conn));
  }
  
  mysqli_stmt_bind_param($stmt, "ii", $userIDToDelete, $userIDToDelete);
  
  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception("Error deleting completed games: " . mysqli_stmt_error($stmt));
  }
  
  mysqli_stmt_close($stmt);
  
  // Delete from favoritedeck table
  $sql = "DELETE FROM favoritedeck WHERE usersId=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $userIDToDelete);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  
  // Delete from users table
  $sql = "DELETE FROM users WHERE usersId=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    throw new Exception("Error preparing delete statement: " . mysqli_error($conn));
  }
  
  mysqli_stmt_bind_param($stmt, "i", $userIDToDelete);
  
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
  
  // Only clear the session if it's self-deletion
  if (!$isModDeletion) {
    ClearLoginSession();
  }
  
  $response->success = true;
  if ($isModDeletion) {
    $response->message = "User '$userNameToDelete' and all related data has been deleted from the database.";
  } else {
    $response->message = "Account deleted successfully. Your session has been cleared.";
  }
  
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
