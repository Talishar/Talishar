<?php
include_once './AccountSessionAPI.php';

include_once '../Assets/patreon-php-master/src/OAuth.php';
include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Database/ConnectionManager.php';
include_once './AccountDatabaseAPI.php';
include_once '../Libraries/HTTPLibraries.php';

SetHeaders();
$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);

if($_POST == NULL) {
  $response->error = "Parameters were not passed";
  echo json_encode($response);
  exit;
}

$username = $_POST["userID"];
$password = $_POST["password"];
$rememberMe = isset($_POST["rememberMe"]);

try {
  PasswordLogin($username, $password, $rememberMe);
} catch (\Exception $e) {
}

$response->isUserLoggedIn = IsUserLoggedIn();
if($response->isUserLoggedIn) {
  $response->loggedInUserID = LoggedInUser();
  $response->loggedInUserName = LoggedInUserName();
  $response->isPatron = IsLoggedInUserPatron();
}

echo (json_encode($response));

exit;
