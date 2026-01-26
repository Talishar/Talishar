<?php

include "../HostFiles/Redirector.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../CardDictionary.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../Assets/MetafyDictionary.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";

// Set headers immediately after includes
SetHeaders();


$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = TryPOST("gameName", 0);
$playerID = TryPOST("playerID", 0);

$response = new stdClass();
session_write_close();

if (!file_exists("../Games/" . $gameName . "/GameFile.txt")) {
  echo (json_encode(new stdClass()));
  exit;
}

include "./APIParseGamefile.php";

$response->p1Name = $p1uid;
$response->p2Name = $p2uid;
$contributors = ["sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar", "DKGaming", "Aegisworn", "PvtVoid"];
$response->p1IsPatron = $p1IsPatron == "" ? false : true;
$response->p1IsContributor = in_array($response->p1Name, $contributors);
$response->p2IsPatron = $p2IsPatron == "" ? false : true;
$response->p2IsContributor = in_array($response->p2Name, $contributors);
$response->p1IsPvtVoidPatron = $response->p1Name == "PvtVoid" || ($playerID == 1 && isset($_SESSION["isPvtVoidPatron"]));
$response->p2IsPvtVoidPatron = $response->p2Name == "PvtVoid" || ($playerID == 2 && isset($_SESSION["isPvtVoidPatron"]));
$response->roguelikeGameID = $roguelikeGameID;

$response->altArts = [];

//Get Alt arts
if(!AltArtsDisabled($playerID))
{
  foreach(PatreonCampaign::cases() as $campaign) {
    if(isset($_SESSION[$campaign->SessionID()]) || (IsUserLoggedIn() && $campaign->IsTeamMember(LoggedInUserName()))) {
      $altArts = $campaign->AltArts($playerID);
      $altArts = explode(",", $altArts);
      for($i = 0; $i < count($altArts); ++$i) {
        $arr = explode("=", $altArts[$i]);
        $altArt = new stdClass();
        $altArt->name = $campaign->CampaignName() . (count($cardBacks) > 1 ? " " . $i + 1 : "");
        $altArt->cardId = $arr[0];
        $altArt->altPath = $arr[1];
        array_push($response->altArts, $altArt);
      }
    }
  }

  // Add Metafy community alt arts
  if (IsUserLoggedIn()) {
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
                  // Add alt arts
                  $altArts = $metafyCommunity->AltArts();
                  if (!empty($altArts)) {
                    $altArtIds = $altArts;//explode(",", $altArts);
                    for($i = 0; $i < count($altArtIds); ++$i) {
                      $arr = explode("=", trim($altArtIds[$i]));
                      if (count($arr) === 2) {
                        $altArt = new stdClass();
                        $altArt->name = $metafyCommunity->CommunityName();
                        $altArt->cardId = trim($arr[0]);
                        $altArt->altPath = trim($arr[1]);
                        array_push($response->altArts, $altArt);
                      }
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
}

echo json_encode($response);

exit;
