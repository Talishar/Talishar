<?php

require_once '../Assets/patreon-php-master/src/OAuth.php';
require_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
require_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../Libraries/HTTPLibraries.php";

SetHeaders();

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$response = new stdClass();

if (IsUserLoggedIn()) {
  $conn = GetDBConnection();
  $sql = "SELECT * FROM users where usersUid='" . LoggedInUserName() . "'";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ("ERROR");
    $response->error = "Error loading patreon token";
    echo (json_encode($response));
    mysqli_close($conn);
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $access_token = $row["patreonAccessToken"];

  try {
    $response = PatreonLogin($access_token);
  } catch (\Exception $e) {
    $response->error = "There was a problem parsing the patreon info";
  }
} else {
  $response->error = "User not logged in";
}

mysqli_close($conn);
echo (json_encode($response));
