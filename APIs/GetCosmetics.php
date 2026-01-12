<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/HTTPLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../Assets/MetafyDictionary.php";

session_start();

SetHeaders();

$response = new stdClass();
$response->cardBacks = [];
$response->playmats = [];

// Define default playmat IDs (kept in sync with frontend PLAYER_PLAYMATS)
$defaultPlaymatIds = [0, 1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 30, 31, 32, 33, 34, 35, 36];

//Add default card back
$cardBack = new stdClass();
$cardBack->name = "Default";
$cardBack->id = 0;
array_push($response->cardBacks, $cardBack);

//Add default playmats
foreach ($defaultPlaymatIds as $playmatId) {
  $playmat = new stdClass();
  $playmat->id = $playmatId;
  $playmat->name = GetPlaymatName($playmatId);
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
      $playmat->id = trim($playmats[$i]);
      $playmat->name = GetPlaymatName($playmat->id);
      if (!empty($playmat->id)) {
        array_push($response->playmats, $playmat);
      }
    }
    }
  }

  // Add Metafy community benefits
  $conn = GetDBConnection();
  $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    $userName = LoggedInUserName();
    mysqli_stmt_bind_param($stmt, 's', $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($row && !empty($row['metafyCommunities'])) {
      $communities = json_decode($row['metafyCommunities'], true);
      if (is_array($communities)) {
        foreach ($communities as $community) {
          $communityId = $community['id'] ?? null;
          if ($communityId) {
            // Check if this community ID matches any Metafy community
            foreach(MetafyCommunity::cases() as $metafyCommunity) {
              if ($metafyCommunity->value === $communityId) {
                // Add card backs
                $cardBacks = $metafyCommunity->CardBacks();
                if (!empty($cardBacks)) {
                  $cardBackIds = explode(",", $cardBacks);
                  for($i = 0; $i < count($cardBackIds); ++$i) {
                    $cardBack = new stdClass();
                    $cardBack->name = $metafyCommunity->CommunityName() . (count($cardBackIds) > 1 ? " " . ($i + 1) : "");
                    $cardBack->id = trim($cardBackIds[$i]);
                    array_push($response->cardBacks, $cardBack);
                  }
                }
                // Add playmats
                $playmats = $metafyCommunity->Playmats();
                if (!empty($playmats)) {
                  $playmatIds = explode(",", $playmats);
                  for($i = 0; $i < count($playmatIds); ++$i) {
                    $playmat = new stdClass();
                    $playmat->id = trim($playmatIds[$i]);
                    $playmat->name = GetPlaymatName($playmat->id);
                    array_push($response->playmats, $playmat);
                  }
                }
                break;
              }
            }
          }
        }
      }
    }
  }
  mysqli_close($conn);
}

// Sort playmats alphabetically by name
usort($response->playmats, function($a, $b) {
  return strcmp($a->name, $b->name);
});

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
      return "The Pits";
    case 5:
      return "Savage Lands";
    case 6:
      return "Solana";
    case 7:
      return "Ironsong Determination";
    case 8:
      return "Volcor";
    case 9:
      return "Data Doll";
    case 10:
      return "Korshem";
    case 11:
      return "Dynasty";
    case 12:
      return "Everfest";
    case 13:
      return "Find Center";
    case 14:
      return "Part The Mistveil";
    case 15:
      return "Rosetta";
    case 16:
      return "Bare Fangs AHS";
    case 17:
      return "Erase Face AHS";
    case 18:
      return "Dusk Till Dawn AHS";
    case 19:
      return "Exude Confidence AHS";
    case 20:
      return "Command and Conquer AHS";
    case 21:
      return "Swarming Gloomveil AHS";
    case 22:
      return "Three Floating";
    case 23:
      return "Man Sant";
    case 24:
      return "The Table Pit";
    case 25:
      return "Steelfur";
    case 26:
      return "Flesh And Bad";
    case 27:
      return "Fabled Brazil";
    case 28:
      return "New Horizons";
    case 29:
      return "Silvaris Garden";
    case 30:
      return "Candleheim";
    case 31:
      return "Isenloft";
    case 32:
      return "Volthaven";
    case 33:
      return "High Seas";
    case 34:
      return "High Seas Necro";
    case 35:
      return "The Hunted";
    case 36:
      return "Deathmatch Arena";
    case 37:
      return "Talishar Dark";
    case 38:
      return "Talishar Red";
    case 39:
      return "Talishar Blue";
    case 40:
      return "Talishar Green";
    case 41:
      return "Talishar Purple";
    default:
      return "N/A";
  }
}
