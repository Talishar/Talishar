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


$_POST = json_decode(file_get_contents('php://input'), true);

$username = $_POST["userID"];
$password = $_POST["password"];
$rememberMe = isset($_POST["rememberMe"]);

try {
  PasswordLogin($username, $password, $rememberMe, true);
} catch (\Exception $e) { }


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
