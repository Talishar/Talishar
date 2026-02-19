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
  echo json_encode(new stdClass());
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
$response->isPvtVoidPatron = $userName == "PvtVoid" || isset($_SESSION["isPvtVoidPatron"]);

// Get Metafy info from database
$conn = GetDBConnection();
$sql = "SELECT metafyAccessToken, metafyCommunities, metafyID FROM users WHERE usersUid=?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, 's', $userName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);
  
  $metafyAccessToken = $row['metafyAccessToken'] ?? null;
  $response->isMetafyLinked = !empty($metafyAccessToken);
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = isset($row['metafyCommunities']) ? json_decode($row['metafyCommunities'], true) : [];
    
  // Check if user has an active subscription to the Talishar community via Metafy API
  $response->isMetafySupporter = false;
  $talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';
  
  if (!empty($metafyAccessToken)) {
    // Get this user's metafyID — first from DB, then live from Metafy API if null
    $user_metafy_id = $row['metafyID'] ?? null;
    $metafy_id_source = 'db';

    if (empty($user_metafy_id)) {
      // Fetch live from Metafy /me using stored access token
      $ch_me = curl_init('https://metafy.gg/irk/api/v1/me');
      curl_setopt($ch_me, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch_me, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch_me, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $metafyAccessToken,
        'Content-Type: application/json'
      ]);
      curl_setopt($ch_me, CURLOPT_USERAGENT, 'Talishar-App');
      $me_raw = curl_exec($ch_me);
      $me_code = curl_getinfo($ch_me, CURLINFO_HTTP_CODE);
      curl_close($ch_me);
      $response->debug_me_fetch = ['http_code' => $me_code, 'raw' => substr($me_raw, 0, 500)];
      if ($me_code === 200) {
        $me_data = json_decode($me_raw, true);
        $user_metafy_id = $me_data['user']['id'] ?? null;
        $metafy_id_source = 'live_api';
        // Save it to DB for future calls
        if ($user_metafy_id) {
          $stmt_save = mysqli_stmt_init($conn);
          if (mysqli_stmt_prepare($stmt_save, 'UPDATE users SET metafyID=? WHERE usersUid=?')) {
            mysqli_stmt_bind_param($stmt_save, 'ss', $user_metafy_id, $userName);
            mysqli_stmt_execute($stmt_save);
            mysqli_stmt_close($stmt_save);
          }
        }
      }
    }

    $response->debug_metafy_id = ['id' => $user_metafy_id, 'source' => $metafy_id_source];

    // Check paid Talishar subscription: use client_id as Bearer to call the community subscribers endpoint
    // (per Metafy docs - community owner endpoints authenticate with client_id)
    $talishar_client_id = '4gIw_YYtamUjZ0yadyy3gYaL_BJkaRnPOa5SKCLbEPI';
    $subscribers_url = 'https://metafy.gg/irk/api/v1/me/community/subscribers?per_page=100';

    $ch = curl_init($subscribers_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $talishar_client_id,
      'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

    $api_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response->debug_subscribers = [
      'http_code' => $http_code,
      'user_metafy_id' => $user_metafy_id,
      'raw' => substr($api_response, 0, 800)
    ];

    if ($http_code === 200 && !empty($api_response)) {
      $data = json_decode($api_response, true);
      $subscriber_ids = array_column($data['subscribers'] ?? [], 'user_id');
      $response->debug_subscribers['count'] = count($subscriber_ids);
      $response->debug_subscribers['ids_sample'] = array_slice($subscriber_ids, 0, 5);
      if ($user_metafy_id && in_array($user_metafy_id, $subscriber_ids)) {
        $response->isMetafySupporter = true;
      }
    }

    // If confirmed subscriber, make sure Talishar is in the communities list
    if ($response->isMetafySupporter) {
      $talishar_in_list = false;
      foreach ($response->metafyCommunities as $c) {
        if (($c['id'] ?? null) === $talishar_community_id) {
          $talishar_in_list = true;
          break;
        }
      }
      if (!$talishar_in_list) {
        $response->metafyCommunities[] = [
          'id' => $talishar_community_id,
          'title' => 'Talishar',
          'description' => 'Flesh and Blood TCG — get exclusive card backs, playmats, and alt arts.',
          'logo_url' => null,
          'url' => 'https://talishar.net',
          'type' => 'supported'
        ];
      }
    }
    else {
      // Fallback: check the DB-cached communities for isMetafySupporter
      foreach ($response->metafyCommunities as $community) {
        if (isset($community['id']) && $community['id'] === $talishar_community_id) {
          $response->isMetafySupporter = true;
          break;
        }
      }
    }
  }
}
else {
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
  if (empty($patreonClientID) || empty($patreonClientSecret)) {
    return null;
  }
  $client_id = $patreonClientID;
  $client_secret = $patreonClientSecret;

  //$redirect_uri = "https://www.talishar.net/game/PatreonLogin.php";
  $redirect_uri = "https://legacy.talishar.net/game/PatreonLogin.php";
  $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);
  $state = [];
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
  if (empty($metafyClientID)) {
    return null;
  }
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
  $state = [
    'redirect_uri' => $redirect_uri
  ];
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

