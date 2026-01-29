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

$userName = LoggedInUserName();
$response->userName = $userName;

$response->patreonInfo = PatreonLink();
$response->isPatreonLinked = isset($_SESSION["patreonAuthenticated"]);

// Check if user is contributor or PvtVoid patron
$contributors = ["sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar", "DKGaming", "Aegisworn", "PvtVoid"];
$response->isContributor = in_array($userName, $contributors);
$response->isPvtVoidPatron = ($userName == "PvtVoid" || isset($_SESSION["isPvtVoidPatron"]));

// Get Metafy info from database
$conn = GetDBConnection();
$sql = "SELECT metafyAccessToken, metafyCommunities FROM users WHERE usersUid=?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, 's', $userName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);
  
  $response->isMetafyLinked = !empty($row['metafyAccessToken']);
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = isset($row['metafyCommunities']) ? json_decode($row['metafyCommunities'], true) : [];
  
  // Debug: log the communities data
  error_log("DEBUG UserProfileAPI - User: $userName, Communities: " . json_encode($response->metafyCommunities));
  
  // Check if user is actually a paid supporter or community owner of Talishar
  $response->isMetafySupporter = false;
  $talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';
  
  // List of paid tier names for Talishar
  $paid_tier_names = array(
    'Fyendal Supporters',
    'Seers of Ophidia',
    'Arknight Shards',
    'Lover of Grandeur',
    'Sponsors of Trōpal-Dhani',
    'Light of Sol Gemini Circle'
  );
  
  if (!empty($response->metafyCommunities)) {
    foreach ($response->metafyCommunities as $community) {
      error_log("DEBUG - Checking community: " . json_encode($community));
      if (isset($community['id']) && $community['id'] === $talishar_community_id) {
        error_log("DEBUG - Found Talishar community");
        // Check if owned (always a supporter if they own the community)
        if (isset($community['type']) && $community['type'] === 'owned') {
          error_log("DEBUG - User owns community");
          $response->isMetafySupporter = true;
          break;
        }
        
        // Check subscription tier name directly - ignore type field
        if (isset($community['subscription_tier'])) {
          $tier_name = '';
          if (is_array($community['subscription_tier'])) {
            $tier_name = $community['subscription_tier']['name'] ?? '';
          } elseif (is_string($community['subscription_tier'])) {
            $tier_name = $community['subscription_tier'];
          }
          
          error_log("DEBUG - Checking tier name: '$tier_name' against paid tiers: " . json_encode($paid_tier_names));
          
          if (!empty($tier_name) && in_array($tier_name, $paid_tier_names, true)) {
            error_log("DEBUG - Tier name is in paid list");
            $response->isMetafySupporter = true;
            break;
          } else {
            error_log("DEBUG - Tier name NOT in paid list, setting as free user");
          }
        }
      }
    }
  }
} else {
  $response->isMetafyLinked = false;
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = [];
  $response->isMetafySupporter = false;
}

mysqli_close($conn);

echo json_encode($response);
exit;

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

function MetafyLink()
{
  global $metafyClientID;
  $client_id = $metafyClientID;
  
  // Check environment variable first, then fall back to detecting by host
  $metafy_dev_mode = getenv('METAFY_DEV_MODE');
  $use_dev = $metafy_dev_mode === 'true' || $metafy_dev_mode === '1';
  if (!$use_dev) {
    $is_local = $_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === 'localhost:8000' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
    $use_dev = $is_local;
  }
  
  // Use new Metafy OAuth endpoint
  $oauth_host = 'https://metafy.gg/auth/authorize';
  
  // Set appropriate redirect URI based on environment
  $redirect_uri = $use_dev ? 'http://localhost:5173/user/profile/linkmetafy' : 'https://talishar.net/user/profile/linkmetafy';
  $response_type = 'code';
  $scope = 'profile community products purchases';
  
  // Create state parameter with redirect URL
  $state = array(
    'redirect_uri' => $redirect_uri
  );
  $state_json = json_encode($state);
  $state_encoded = base64_encode($state_json);
  
  $href = $oauth_host . '?' .
    'response_type=' . urlencode($response_type) .
    '&client_id=' . urlencode($client_id) .
    '&redirect_uri=' . urlencode($redirect_uri) .
    '&scope=' . urlencode($scope) .
    '&state=' . urlencode($state_encoded);
  
  return $href;
}

