<?php

include_once './AccountSessionAPI.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';

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
  $post_fields = array(
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json'
  ));
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
    SaveMetafyTokensAndID($access_token, $refresh_token);
    
    // Fetch user's communities
    FetchAndSaveMetafyCommunities($access_token, $response);
  } else {
    $error_msg = isset($tokens['error']) ? $tokens['error'] : 'Failed to get access token';
    $error_description = isset($tokens['error_description']) ? $tokens['error_description'] : 'No description';
    $response->error = $error_msg;
    $response->error_description = $error_description;
  }
  
  $response->message = 'ok';
} else {
  $response->error = 'no code set';
}

echo (json_encode($response));

function SaveMetafyTokensAndID($accessToken, $refreshToken)
{
  if (!isset($_SESSION['userid'])) {
    return;
  }
  $userID = $_SESSION['userid'];

  // Fetch the user's Metafy profile to get their Metafy user ID
  $metafyUserID = null;
  $ch = curl_init('https://metafy.gg/irk/api/v1/me');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
  ));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  $profileResponse = curl_exec($ch);
  $profileHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($profileHttpCode === 200 && !empty($profileResponse)) {
    $profileData = json_decode($profileResponse, true);
    $metafyUserID = $profileData['user']['id'] ?? null;
  }

  $conn = GetDBConnection();
  $sql = 'UPDATE users SET metafyAccessToken=?, metafyRefreshToken=?, metafyID=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssss', $accessToken, $refreshToken, $metafyUserID, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}

function FetchAndSaveMetafyCommunities($access_token, &$response)
{
  if (!isset($_SESSION['userid'])) {
    $response->debug_communities = 'no session userid';
    return;
  }
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  
  $all_communities = array();
  
  $community_url = 'https://metafy.gg/irk/api/v1/me/community';
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $community_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $community_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  
  $community_data = json_decode($community_response, true);
  
  // Add owned community if user has one
  if ($http_code === 200 && isset($community_data['community'])) {
    $community_info = array(
      'id' => $community_data['community']['id'] ?? null,
      'title' => $community_data['community']['title'] ?? null,
      'description' => $community_data['community']['description'] ?? null,
      'logo_url' => $community_data['community']['logo_url'] ?? null,
      'cover_url' => $community_data['community']['cover_url'] ?? null,
      'url' => $community_data['community']['url'] ?? null,
      'tiers' => $community_data['community']['tiers'] ?? [],
      'type' => 'owned'
    );
    $all_communities[] = $community_info;
  }
  
  // 2. Fetch all joined communities (memberships)
  $memberships_url = 'https://metafy.gg/irk/api/v1/me/community/memberships?per_page=100';
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $memberships_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $memberships_response = curl_exec($ch);
  $memberships_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  
  $memberships_data = json_decode($memberships_response, true);
  
  // Build a set of already-added community IDs to avoid duplicates
  $added_community_ids = array_filter(array_column($all_communities, 'id'));

  // Process all joined communities (memberships).
  // The memberships response includes all tier options for the community but NOT which tier the user is on.
  // For each community, call GET /me/purchases/communities/{id} with the user token — returns has_access + tier_id.
  // Resolve tier_id → tier name using the tiers array in the membership object.
  if ($memberships_http_code === 200 && isset($memberships_data['communities'])) {
    foreach ($memberships_data['communities'] as $community) {
      $community_id = $community['id'] ?? null;
      if (!$community_id) continue;
      if (in_array($community_id, $added_community_ids)) continue; // skip duplicates
      $added_community_ids[] = $community_id;

      // Build a tier_id → name lookup from the membership object's tiers list
      $tier_map = array();
      foreach (($community['tiers'] ?? []) as $tier) {
        if (!empty($tier['id']) && !empty($tier['name'])) {
          $tier_map[$tier['id']] = $tier['name'];
        }
      }

      // Ask Metafy: does this user have an active paid subscription to this community, and which tier?
      $purchase_url = 'https://metafy.gg/irk/api/v1/me/purchases/communities/' . urlencode($community_id);
      $ch_pur = curl_init($purchase_url);
      curl_setopt($ch_pur, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_pur, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
      ));
      curl_setopt($ch_pur, CURLOPT_USERAGENT, 'Talishar-App');
      $pur_response = curl_exec($ch_pur);
      $pur_http_code = curl_getinfo($ch_pur, CURLINFO_HTTP_CODE);
      curl_close($ch_pur);

      $community_info = array(
        'id' => $community_id,
        'title' => $community['title'] ?? null,
        'description' => $community['description'] ?? null,
        'logo_url' => $community['logo_url'] ?? null,
        'cover_url' => $community['cover_url'] ?? null,
        'url' => $community['url'] ?? null,
        'type' => 'supported'
      );

      if ($pur_http_code === 200 && !empty($pur_response)) {
        $pur_data = json_decode($pur_response, true);
        $has_access = $pur_data['community']['has_access'] ?? false;
        $tier_id    = $pur_data['community']['tier_id'] ?? null;
        if ($has_access && $tier_id && isset($tier_map[$tier_id])) {
          $community_info['metafy_tier'] = $tier_map[$tier_id];
        }
      }

      $all_communities[] = $community_info;
    }
  }
  
  // Save all communities to database
  $communities_json = json_encode($all_communities);
  
  $sql = 'UPDATE users SET metafyCommunities=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $communities_json, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  
  mysqli_close($conn);
}

?>
