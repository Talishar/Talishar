<?php

include_once './AccountSessionAPI.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';
include_once '../includes/MetafyHelper.php';

SetHeaders();
CheckSession();

$response = new stdClass();

$client_id = $metafyClientID ?? '';
$client_secret = $metafyClientSecret ?? '';
// Must exactly match the redirect URI registered in the Metafy OAuth app and used in the authorization URL
$redirect_uri = 'https://talishar.net/user/profile/linkmetafy';

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above.
// It will grab the auth code from Metafy and get the tokens via the OAuth client

if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];
  
  // Exchange the code for tokens using the correct Metafy endpoint
  $token_url = 'https://metafy.gg/irk/oauth/token';
  
  // Per Metafy docs: client_id and client_secret go in the POST body as JSON
  $post_fields = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret
  ];
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $token_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);
  
  $tokens = json_decode($token_response, true);
  
  if (isset($tokens['access_token'])) {
    $access_token = $tokens['access_token'];
    $refresh_token = $tokens['refresh_token'] ?? '';
    
    // Fetch the user's Metafy ID and save tokens + ID
    $metafyUserID = SaveMetafyTokensAndID($access_token, $refresh_token);
    
    // Fetch user's communities
    FetchAndSaveMetafyCommunities($access_token, $response);
    if (!empty($metafyUserID)) {
      $_SESSION['metafyID'] = $metafyUserID;
    }
  } else {
    $error_msg = $tokens['error'] ?? 'Failed to get access token';
    $error_description = $tokens['error_description'] ?? 'No description';
    $response->error = $error_msg;
    $response->error_description = $error_description;
  }
  
  if (!isset($response->error)) {
    $response->message = 'ok';
  }
} else {
  $response->error = 'no code set';
}

echo json_encode($response);

function SaveMetafyTokensAndID($accessToken, $refreshToken)
{
  if (!isset($_SESSION['userid'])) {
    return null;
  }
  $userID = $_SESSION['userid'];

  // Fetch the user's Metafy profile to get their Metafy user ID
  $metafyUserID = null;
  $ch = curl_init('https://metafy.gg/irk/api/v1/me');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  $profileResponse = curl_exec($ch);
  $profileHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($profileHttpCode === 200 && !empty($profileResponse)) {
    $profileData = json_decode($profileResponse, true);
    $metafyUserID = $profileData['user']['id'] ?? null;
  }

  $conn = GetDBConnection(DBL_METAFY_LOGIN_API);
  $sql = 'UPDATE users SET metafyAccessToken=?, metafyRefreshToken=?, metafyID=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssss', $accessToken, $refreshToken, $metafyUserID, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
  if (!empty($metafyUserID)) {
    $_SESSION['metafyID'] = $metafyUserID;
  }
  return $metafyUserID;
}

function FetchAndSaveMetafyCommunities($access_token, &$response)
{
  if (!isset($_SESSION['userid'])) {
    $response->debug_communities = 'no session userid';
    return;
  }
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection(DBL_METAFY_LOGIN_API);

  $stored_by_id = [];
  $stmt_stored = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt_stored, 'SELECT metafyCommunities FROM users WHERE usersid=? LIMIT 1')) {
    mysqli_stmt_bind_param($stmt_stored, 'i', $userID);
    mysqli_stmt_execute($stmt_stored);
    $res_stored = mysqli_stmt_get_result($stmt_stored);
    $row_stored = mysqli_fetch_assoc($res_stored);
    mysqli_stmt_close($stmt_stored);
    $stored_by_id = IndexMetafyCommunitiesById(json_decode($row_stored['metafyCommunities'] ?? '', true));
  }

  $all_communities = [];

  $community_result   = MetafyApiGet('https://metafy.gg/irk/api/v1/me/community', $access_token);
  $community_response = $community_result['body'];
  $http_code          = $community_result['code'];

  $community_data = json_decode($community_response, true);

  // Add owned community if user has one
  if ($http_code === 200 && isset($community_data['community'])) {
    $community_info = [
      'id' => $community_data['community']['id'] ?? null,
      'title' => $community_data['community']['title'] ?? null,
      'description' => $community_data['community']['description'] ?? null,
      'logo_url' => $community_data['community']['logo_url'] ?? null,
      'cover_url' => $community_data['community']['cover_url'] ?? null,
      'url' => $community_data['community']['url'] ?? null,
      'tiers' => $community_data['community']['tiers'] ?? [],
      'type' => 'owned'
    ];
    $all_communities[] = $community_info;
  } elseif ($http_code !== 404) {
    foreach ($stored_by_id as $stored_community) {
      if (($stored_community['type'] ?? null) === 'owned') {
        $all_communities[] = $stored_community;
      }
    }
  }

  // 2. Fetch all joined communities (memberships)
  $memberships_url = 'https://metafy.gg/irk/api/v1/me/community/memberships?per_page=100';

  $memberships_result    = MetafyApiGet($memberships_url, $access_token);
  $memberships_response  = $memberships_result['body'];
  $memberships_http_code = $memberships_result['code'];

  $memberships_data = json_decode($memberships_response, true);

  if ($memberships_http_code !== 200 || !isset($memberships_data['communities'])) {
    $response->error = 'metafy_unavailable';
    $response->error_description = 'Could not read your Metafy communities right now. Existing status was kept.';
    mysqli_close($conn);
    return;
  }

  // Build a set of already-added community IDs to avoid duplicates
  $added_community_ids = array_filter(array_column($all_communities, 'id'));

  // Process all joined communities (memberships).
  // The memberships response includes all tier options for the community but NOT which tier the user is on.
  // For each community, call GET /me/purchases/communities/{id} with the user token — returns has_access + tier_id.
  // Resolve tier_id → tier name using the tiers array in the membership object.
  foreach ($memberships_data['communities'] as $community) {
    $community_id = $community['id'] ?? null;
    if (!$community_id) continue;
    if (in_array($community_id, $added_community_ids)) continue; // skip duplicates
    $added_community_ids[] = $community_id;

    // Build a tier_id → name lookup from the membership object's tiers list
    $tier_map = [];
    foreach (($community['tiers'] ?? []) as $tier) {
      if (!empty($tier['id']) && !empty($tier['name'])) {
        $tier_map[$tier['id']] = $tier['name'];
      }
    }

    $purchase_url  = 'https://metafy.gg/irk/api/v1/me/purchases/communities/' . urlencode($community_id);
    $pur_result    = MetafyApiGet($purchase_url, $access_token);
    $pur_response  = $pur_result['body'];
    $pur_http_code = $pur_result['code'];

    $community_info = [
      'id' => $community_id,
      'title' => $community['title'] ?? null,
      'description' => $community['description'] ?? null,
      'logo_url' => $community['logo_url'] ?? null,
      'cover_url' => $community['cover_url'] ?? null,
      'url' => $community['url'] ?? null,
      'type' => 'supported'
    ];

    if ($pur_http_code === 200 && !empty($pur_response)) {
      $pur_data = json_decode($pur_response, true);
      $has_access = $pur_data['community']['has_access'] ?? false;
      $tier_id    = $pur_data['community']['tier_id'] ?? null;
      if ($has_access && $tier_id && isset($tier_map[$tier_id])) {
        $community_info['metafy_tier'] = $tier_map[$tier_id];
      }
    } elseif (!empty($stored_by_id[$community_id]['metafy_tier'])) {
      $community_info['metafy_tier'] = $stored_by_id[$community_id]['metafy_tier'];
    }

    $all_communities[] = $community_info;
  }

  // Save all communities to database
  $communities_json = json_encode($all_communities);

  $metafyUserID = null;
  $row_id = null;
  $conn2 = GetDBConnection(DBL_METAFY_LOGIN_API);
  $stmt_chk = mysqli_stmt_init($conn2);
  if (mysqli_stmt_prepare($stmt_chk, "SELECT metafyID FROM users WHERE usersid=? LIMIT 1")) {
    mysqli_stmt_bind_param($stmt_chk, 'i', $userID);
    mysqli_stmt_execute($stmt_chk);
    $res_chk = mysqli_stmt_get_result($stmt_chk);
    $row_chk = mysqli_fetch_assoc($res_chk);
    mysqli_stmt_close($stmt_chk);
    $metafyUserID = $row_chk['metafyID'] ?? null;
  }
  mysqli_close($conn2);

  if (empty($metafyUserID)) {
    $ch_me = curl_init('https://metafy.gg/irk/api/v1/me');
    curl_setopt($ch_me, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_me, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch_me, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token, 'Content-Type: application/json']);
    curl_setopt($ch_me, CURLOPT_USERAGENT, 'Talishar-App');
    $me_raw  = curl_exec($ch_me);
    $me_code = curl_getinfo($ch_me, CURLINFO_HTTP_CODE);
    curl_close($ch_me);
    if ($me_code === 200) {
      $me_data      = json_decode($me_raw, true);
      $metafyUserID = $me_data['user']['id'] ?? null;
    }
  }

  $sql = 'UPDATE users SET metafyCommunities=?' . (!empty($metafyUserID) ? ', metafyID=?' : '') . ' WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    if (!empty($metafyUserID)) {
      mysqli_stmt_bind_param($stmt, 'sss', $communities_json, $metafyUserID, $userID);
    } else {
      mysqli_stmt_bind_param($stmt, 'ss', $communities_json, $userID);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
}

