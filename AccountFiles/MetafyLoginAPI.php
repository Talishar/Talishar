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
$redirect_uri = 'https://talishar.net/user/profile/linkmetafy';

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above.
// It will grab the auth code from Metafy and get the tokens via the OAuth client

if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];
  
  // Exchange the code for tokens using the correct Metafy endpoint
  $token_url = 'https://metafy.gg/irk/oauth/token';
  
  $post_fields = array(
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded'
  ));
  // Use HTTP Basic Authentication for client credentials (Metafy requirement)
  curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $token_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);
  
  $tokens = json_decode($token_response, true);
  $response->tokens = $tokens;
  
  if (isset($tokens['access_token'])) {
    $access_token = $tokens['access_token'];
    $refresh_token = $tokens['refresh_token'] ?? '';
    $response->access_token = $access_token;
    $response->refresh_token = $refresh_token;
    
    // Save the access and refresh tokens for this user
    SaveMetafyTokens($access_token, $refresh_token);
    
    // Fetch user's communities
    FetchAndSaveMetafyCommunities($access_token);
  } else {
    $response->error = isset($tokens['error']) ? $tokens['error'] : 'Failed to get access token';
  }
  
  $response->message = 'ok';
} else {
  $response->error = 'no code set';
}

echo (json_encode($response));

function SaveMetafyTokens($accessToken, $refreshToken)
{
  if (!isset($_SESSION['userid'])) {
    return;
  }
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  $sql = 'UPDATE users SET metafyAccessToken=?, metafyRefreshToken=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'sss', $accessToken, $refreshToken, $userID);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  } else {
  }
  mysqli_close($conn);
}

function FetchAndSaveMetafyCommunities($access_token)
{
  if (!isset($_SESSION['userid'])) return;
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  
  $all_communities = array();
  
  // 1. Fetch the authenticated user's owned community (if they are a coach/creator)
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
    error_log('[MetafyLoginAPI] Found owned community: ' . ($community_info['title'] ?? 'Unknown'));
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
  
  error_log('[MetafyLoginAPI] Memberships fetch HTTP Code: ' . $memberships_http_code);
  
  $memberships_data = json_decode($memberships_response, true);
  
  // Process all joined communities
  if ($memberships_http_code === 200 && isset($memberships_data['communities'])) {
    foreach ($memberships_data['communities'] as $community) {
      // The new endpoint doesn't include type, so we mark all as 'supported'
      // since we already got owned communities from the /me/community endpoint above
      $community_info = array(
        'id' => $community['id'] ?? null,
        'title' => $community['title'] ?? null,
        'description' => $community['description'] ?? null,
        'logo_url' => $community['logo_url'] ?? null,
        'cover_url' => $community['cover_url'] ?? null,
        'url' => $community['url'] ?? null,
        'tiers' => $community['tiers'] ?? [],
        'type' => 'supported'
      );
      $all_communities[] = $community_info;
      error_log('[MetafyLoginAPI] Found joined community: ' . ($community_info['title'] ?? 'Unknown'));
    }
  } else {
    error_log('[MetafyLoginAPI] No joined communities found or API error');
  }
  
  // Save all communities to database
  if (count($all_communities) > 0) {
    $communities_json = json_encode($all_communities);
    $sql = 'UPDATE users SET metafyCommunities=? WHERE usersid=?';
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 'ss', $communities_json, $userID);
      $result = mysqli_stmt_execute($stmt);
      error_log('[MetafyLoginAPI] Communities save result: ' . ($result ? 'SUCCESS' : 'FAILED') . ' (' . count($all_communities) . ' communities)');
      mysqli_stmt_close($stmt);
    }
  } else {
    error_log('[MetafyLoginAPI] No communities found (owned or supported)');
  }
  
  mysqli_close($conn);
}

?>
