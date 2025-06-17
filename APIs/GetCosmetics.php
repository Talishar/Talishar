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
$numDefaultPlaymats = 16;
$response->playmats = [];

//Add default card back
$cardBack = new stdClass();
$cardBack->name = "Default";
$cardBack->id = 0;
array_push($response->cardBacks, $cardBack);

//Add default playmats
for ($i = 0; $i < 16; ++$i) {
  if($i == 7) continue;
  $playmat = new stdClass();
  $playmat->id = $i;
  $playmat->name = GetPlaymatName($i);
  array_push($response->playmats, $playmat);
}

if(IsUserLoggedIn()) {
  foreach(PatreonCampaign::cases() as $campaign) {
    if(isset($_SESSION[$campaign->SessionID()]) || (isset($_SESSION["useruid"]) && $campaign->IsTeamMember($_SESSION["useruid"]))) {
      $cardBacks = $campaign->CardBacks();
      $cardBacks = explode(",", $cardBacks);
      for($i = 0; $i < count($cardBacks); ++$i) {
        $cardBack = new stdClass();
        $cardBack->name = $campaign->CampaignName() . (count($cardBacks) > 1 ? " " . $i + 1 : "");
        $cardBack->id = $cardBacks[$i];
        array_push($response->cardBacks, $cardBack);
      }

    $playmats = $campaign->Playmats();
    $playmats = explode(",", $playmats);
    for($i = 0; $i < count($playmats); ++$i) {
      $playmat = new stdClass();
      $playmat->name = $campaign->CampaignName() . (count($playmats) > 1 ? " " . $i + 1 : "");
      $playmat->id = $playmats[$i];
      if (!empty($playmat->id)) {
        array_push($response->playmats, $playmat);
      }
    }
    }
  }
}

session_write_close();
echo json_encode($response);

function GetPlaymatName($id)
{
  switch ($id) {
    case 0:
      return "Plain";
    case 1:
      return "Demonastery";
    case 2:
      return "Metrix";
    case 3:
      return "Misteria";
    case 4:
      return "Pits";
    case 5:
      return "Savage";
    case 6:
      return "Solana";
    case 7:
      return "Volcor";
    case 8:
      return "Data-Doll";
    case 9:
      return "Aria";
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
    case 16:
      return "FindCenter";
    default:
      return "N/A";
  }
}
