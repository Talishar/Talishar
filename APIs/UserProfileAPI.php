<?php

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../CardDictionary.php";
include_once "../Libraries/UILibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/HTTPLibraries.php";

SetHeaders();

if (!IsUserLoggedIn()) {
  echo (json_encode(new stdClass()));
  exit;
}

$response = new stdClass();
// $response->badges = [];

// $badges = LoadBadges(LoggedInUser());
// for ($i = 0; $i < count($badges); $i += 7) {
//   $badge = new stdClass();
//   $badge->topText = $badges[$i + 3];
//   $badge->bottomText = str_replace("{0}", $badges[$i + 2], $badges[$i + 4]);
//   if ($badges[$i + 6] != "") $badge->link = $badges[$i + 6];
//   $badge->image = $badges[$i + 5];
//   array_push($response->badges, $badge);
// }

$response->userName = LoggedInUserName();

$response->patreonInfo = PatreonLink();
$response->isPatreonLinked = isset($_SESSION["patreonAuthenticated"]);

echo json_encode($response);
exit;

// if (isset($_SESSION["isPatron"])) {
//   echo ("<div class='ContentWindow' style='width: 50%; left:20px; height: calc(90% - 220px); bottom:20px; overflow-y: scroll;'>");
//   echo ("<h1>Your Record</h1>");
//   $forIndividual = true;
//   include_once "zzGameStats.php";
//   echo ("</div>");
// }

function PatreonLink()
{
  global $patreonClientID, $patreonClientSecret;
  $client_id = $patreonClientID;
  $client_secret = $patreonClientSecret;

  //$redirect_uri = "https://www.talishar.net/game/PatreonLogin.php";
  $redirect_uri = "https://legacy.talishar.net/game/PatreonLogin.php";
  $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);
  $state = array();
  $state['final_page'] = 'http://fleshandbloodonline.com/FaBOnline/MainMenu.php';
  $state_parameters = '&state=' . urlencode(base64_encode(json_encode($state)));
  $href .= $state_parameters;
  $scope_parameters = '&scope=identity%20identity.memberships';
  $href .= $scope_parameters;
  return $href;
}
