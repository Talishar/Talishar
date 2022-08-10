<?php

require_once 'vendor/autoload.php';

$id_token = $_POST['credential'];

$CLIENT_ID = "1089547347578-lqbvoqb0u3grqi89ibjmti2bc9lq37pr.apps.googleusercontent.com";

$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  require_once 'functions.inc.php';
  echo json_encode($payload);
  // loginUser($payload["email"]);
} else {
    echo "Goodbye payload";
  // Invalid ID token
}

// if (isset($_POST["submit"])) {

//   // First we get the form data from the URL
//   $username = $_POST["uid"];
//   $pwd = $_POST["pwd"];
//   $rememberMe = isset($_POST["rememberMe"]);

//   // Then we run a bunch of error handlers to catch any user mistakes we can (you can add more than I did)
//   // These functions can be found in functions.inc.php

//   include_once '../Header.php';
//   require_once "dbh.inc.php";
//   require_once 'functions.inc.php';

//   // Left inputs empty
//   if (emptyInputLogin($username, $pwd) === true) {
//     header("location: ../Login.php?error=emptyinput");
// 		exit();
//   }

//   // If we get to here, it means there are no user errors

//   // Now we insert the user into the database
//   loginUser($username, $pwd, $rememberMe);

// } else {
// 	header("location: ../Login.php");
//     exit();
// }