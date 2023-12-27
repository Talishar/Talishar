<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/HTTPLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";

session_start();

SetHeaders();

$response = new stdClass();
$response->cardBacks = [];

//Add default card back
$cardBack = new stdClass();
$cardBack->name = "Default";
$cardBack->id = 0;
array_push($response->cardBacks, $cardBack);

$response->playmats = [];
if(IsUserLoggedIn()) {
  foreach(PatreonCampaign::cases() as $campaign) {
    if(isset($_SESSION[$campaign->SessionID()]) || (isset($_SESSION["useruid"]) && $campaign->IsTeamMember($_SESSION["useruid"]))) {
      //Check card backs first
      $cardBacks = $campaign->CardBacks();
      $cardBacks = explode(",", $cardBacks);
      for($i = 0; $i < count($cardBacks); ++$i) {
        $cardBack = new stdClass();
        $cardBack->name = $campaign->CampaignName() . (count($cardBacks) > 1 ? " " . $i + 1 : "");
        $cardBack->id = $cardBacks[$i];
        array_push($response->cardBacks, $cardBack);
      }
    }
  }

  for ($i = 0; $i < 16; ++$i) {
    if($i == 7) continue;
    $playmat = new stdClass();
    $playmat->id = $i;
    $playmat->name = GetPlaymatName($i);
    array_push($response->playmats, $playmat);
  }
}

session_write_close();
echo json_encode($response);

function GetPlaymatName($id)
{
  switch ($id) {
    case 0:
      return "plain";
    case 1:
      return "demonastery";
    case 2:
      return "metrix";
    case 3:
      return "misteria";
    case 4:
      return "pits";
    case 5:
      return "savage";
    case 6:
      return "solana";
    case 7:
      return "volcor";
    case 8:
      return "training-dummy";
    case 9:
      return "aria";
    case 10:
      return "Bare-Fangs-AHS";
    case 11:
      return "Erase-Face-AHS";
    case 12:
      return "Dusk-Till-Dawn-AHS";
    case 13:
      return "Exude-Confidence-AHS";
    case 14:
      return "Command-and-Conquer-AHS";
    case 15:
      return "Swarming-Gloomveil-AHS";
    default:
      return "N/A";
  }
}
