<?php

include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';

if (isset($_POST["submit"])) {

  $username = $_POST["userID"];
  $pwd = $_POST["password"];
  $rememberMe = isset($_POST["rememberMe"]);

  require_once "../includes/dbh.inc.php";
  require_once '../includes/functions.inc.php';

  loginUser($username, $pwd, $rememberMe);

} else {
	header("location: ../Login.php");
    exit();
}
