<?php

include_once '../Libraries/HTTPLibraries.php';
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../includes/dbh.inc.php";
include_once "../includes/functions.inc.php";
require_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
require_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once '../APIKeys/APIKeys.php';

SetHeaders();

$_POST = json_decode(file_get_contents('php://input'), true);
$deckbuilderType = TryPOST("deckbuilder", "");
$deckbuilderID = TryPOST("user", "");
$apiKey = TryPOST("apiKey", "");

if(!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();

$response = new stdClass();

if(IsUserLoggedIn()) {
  if(str_contains($deckbuilderType, "fabrary"))
  {
     if($apiKey == $fabraryOutgoingKey)
     {
       storeFabraryId(LoggedInUser(), $deckbuilderID);
       $response->message = "Linked successfully to Talishar user: " . LoggedInUser();
     }
     else $response->message = "Invalid fabrary auth key";
  }
  else $response->message = "Unrecognized deckbuilder";
}
else $response->message = "User not logged in";
echo (json_encode($response));

exit;
