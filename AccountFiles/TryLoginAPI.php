<?php
include_once './AccountSessionAPI.php';

include_once '../Assets/patreon-php-master/src/OAuth.php';
include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';
include_once '../APIKeys/APIKeys.php';

SetHeaders();


if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$response = new stdClass();
$response->isUserLoggedIn = IsUserLoggedIn();
if($response->isUserLoggedIn) {
  $response->loggedInUserID = LoggedInUser();
  $response->loggedInUserName = LoggedInUserName();
  $response->isPatron = IsLoggedInUserPatron();
  $response->timestamp = time();
  $metafyID = LoggedInMetafyID();
  $response->metafyID = $metafyID;
  $response->metafyHash = ($metafyID != "") ? hash('sha256', $metafyID . $FaBBazaarSalt . $response->timestamp) : "";
}

echo(json_encode($response));

exit;

?>
