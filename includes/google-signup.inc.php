<?php

if (isset($_POST["submit"])) {

  // First we get the form data from the URL
  $username = $_POST["uid"];
  $email = $_POST["email"];

  // Then we run a bunch of error handlers to catch any user mistakes we can (you can add more than I did)
  // These functions can be found in functions.inc.php

  require_once "dbh.inc.php";
  require_once 'functions.inc.php';

  // // We set the functions "!== false" since "=== true" has a risk of giving us the wrong outcome
  // if (emptyInputSignup($username, $email) !== false) {
  //   header("location: ../GoogleSignup.php?error=emptyinput");
	// 	exit();
  // }
	// // Proper username chosen
  // if (invalidUid($uid) !== false) {
  //   header("location: ../GoogleSignup.php?error=invaliduid");
	// 	exit();
  // }
  // // Proper email chosen
  // if (invalidEmail($email) !== false) {
  //   header("location: ../GoogleSignup.php?error=invalidemail");
	// 	exit();
  // }

  // Is the username taken already
  if (uidExists($conn, $username) !== false) {
    header("location: ../GoogleSignup.php?error=usernametaken");
		exit();
  }

  // If we get to here, it means there are no user errors

  // Now we insert the user into the database
  createGoogleUser($conn, $username, $email);

} else {
	header("location: ../GoogleSignup.php");
    exit();
}
