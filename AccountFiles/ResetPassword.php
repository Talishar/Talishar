<?php

include_once '../Libraries/HTTPLibraries.php';
include_once './AccountSessionAPI.php';
include_once '../includes/dbh.inc.php';

$_POST = json_decode(file_get_contents('php://input'), true);

$selector = isset($_POST['selector']) ? $_POST['selector'] : null;
$validator = isset($_POST['validator']) ? $_POST['validator'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$passwordRepeat = isset($_POST['passwordRepeat']) ? $_POST['passwordRepeat'] : null;

SetHeaders();
$response = new stdClass();

if (empty($selector) || empty($validator) || empty($password) || empty($passwordRepeat)) 
{
  $response->error = "One or more required fields is empty.";
  echo(json_encode($response));
  exit;
} 
else if ($password != $passwordRepeat) {
  $response->error = "New passwords not equal";
  echo (json_encode($response));
  exit();
}

$currentDate = date('U');


  $conn = GetDBConnection();
  $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    $response->error = "There was an error finding your password reset selector.";
    echo (json_encode($response));
  	mysqli_close($conn);
    exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if (!$row = mysqli_fetch_assoc($result)) {
    $response->error = "You need to resubmit your reset request.";
    echo (json_encode($response));
  	mysqli_close($conn);
    exit();
  }

  $tokenCheck = password_verify($validator, $row['pwdResetToken']);

  // Then if they match we grab the users e-mail from the database.
  if ($tokenCheck === false) {
    $response->error = "Your reset token does not match.";
    echo (json_encode($response));
  	mysqli_close($conn);
    exit();
  }

  // Before we get the users info from the user table we need to store the token email for later.
  $tokenEmail = $row['pwdResetEmail'];

  // Here we query the user table to check if the email we have in our pwdReset table exists.
  $sql = "SELECT * FROM users WHERE usersEmail=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    $response->error = "There was an error preparing the email query.";
    echo (json_encode($response));
  	mysqli_close($conn);
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if (!$row = mysqli_fetch_assoc($result)) {
    $response->error = "Your email was not found in the database.";
    echo (json_encode($response));
  	mysqli_close($conn);
    exit();
  } else {

    // Finally we update the users table with the newly created password.
    $sql = "UPDATE users SET usersPwd=? WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      $response->error = "There was an issue resetting your password.";
      echo (json_encode($response));
    	mysqli_close($conn);
      exit();
    } else {
      $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
      mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
      mysqli_stmt_execute($stmt);

      // Then we delete any leftover tokens from the pwdReset table.
      $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        $response->error = "There was an issue deleting the reset request.";
        echo (json_encode($response));
      	mysqli_close($conn);
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
        mysqli_stmt_execute($stmt);
        $response->message = "Success!";
        echo (json_encode($response));
      	mysqli_close($conn);
        exit();
      }
    }
  }