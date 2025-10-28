<?php
include 'functions.inc.php';

// First we check if the form was submitted.
if (isset($_POST['reset-request-submit'])) {

  $selector = bin2hex(random_bytes(8));
  $token = random_bytes(32);

  $url = "https://www.talishar.net/game/CreateNewPassword.php?selector=" . $selector . "&validator=" . bin2hex($token);
  // $url = "www.localhost/FaBOnline/CreateNewPassword.php?selector=" . $selector . "&validator=" . bin2hex($token);

  $expires = date("U") + 1800;

  require 'dbh.inc.php';

  // Then we grab the e-mail the user submitted from the form.
  $userEmail = $_POST["email"];

  // Finally we delete any existing entries.
  $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("There was an error!");
  } else {
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
  }

  // Here we then insert the info we have regarding the token into the database. This means that we have something we can use to check if it is the correct user that tries to change their password.
  $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("There was an error!");
  } else {
    // Here we also hash the token to make it unreadable, in case a hacker accessess our database.
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
    mysqli_stmt_execute($stmt);
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conn);

  // Send e-mail
  $emailSent = sendEmail($userEmail, $url);
  
  if (!$emailSent) {
    error_log("WARNING: Email send function returned false for $userEmail");
  }

  // Finally we send them back to a page telling them to check their e-mail.
  header("Location: ../ResetPassword.php?reset=success");
} else {
  header("Location: ../MainMenu.php");
  exit();
}
