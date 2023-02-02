<?php
include_once './AccountSessionAPI.php';

include_once '../Assets/patreon-php-master/src/OAuth.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Database/ConnectionManager.php';
include_once './AccountDatabaseAPI.php';


if (isset($_POST["submit"])) {

  $username = $_POST["userID"];
  $password = $_POST["password"];
  $rememberMe = isset($_POST["rememberMe"]);

  try {
    AttemptPasswordLogin($username, $password, $rememberMe);
  } catch (\Exception $e) { }

} else {
	header("location: ../Login.php");
  exit();
}


$response = new stdClass();
$response->isUserLoggedIn = IsUserLoggedIn();
if($response->isUserLoggedIn)
{
  $response->loggedInUserID = LoggedInUser();
  $response->loggedInUserName = LoggedInUserName();
}

echo(json_encode($response));

exit;

?>
