<?php

include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Database/ConnectionManager.php';
include_once './AccountDatabaseAPI.php';

if (isset($_POST["submit"])) {

  $username = $_POST["userID"];
  $password = $_POST["password"];
  $rememberMe = isset($_POST["rememberMe"]);

  require_once "../includes/dbh.inc.php";
  require_once '../includes/functions.inc.php';

  AttemptPasswordLogin($username, $password, $rememberMe);

} else {
	header("location: ../Login.php");
  exit();
}
