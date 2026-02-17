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
$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/user/profile/linkmetafy';

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
  }
  mysqli_close($conn);
}

function FetchAndSaveMetafyCommunities($access_token, &$response)
{
  if (!isset($_SESSION['userid'])) {
    return;
  }
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  
  $all_communities = array();
  
  // List of paid tier names - ONLY used for Talishar community restrictions
  $talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';
  $paid_tier_names = array(
    'Fyendal Supporters',
    'Seers of Ophidia',
    'Arknight Shards',
    'Lover of Grandeur',
    'Sponsors of Trōpal-Dhani',
    'Light of Sol Gemini Circle'
  );
  
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
  
  // Process all joined communities (memberships)
  if ($memberships_http_code === 200 && isset($memberships_data['communities'])) {
    foreach ($memberships_data['communities'] as $community) {
      $community_id = $community['id'] ?? null;
      
      if (!$community_id) {
        continue;
      }
      
      // For each community, determine if it's a supporter community
      // If the user is in the memberships list, they have access
      $community_type = 'supported'; // All communities from memberships are supporter communities
      
      // Get the subscription tier if available
      $subscription_tier = null;
      if (isset($community['tiers']) && is_array($community['tiers']) && count($community['tiers']) > 0) {
        // Use the first tier as the subscription tier (Metafy doesn't specify which tier the user has in memberships)
        $subscription_tier = $community['tiers'][0];
      }
      
      // ONLY apply tier name restrictions for Talishar community
      if ($community_id === $talishar_community_id) {
        $tier_name = '';
        if ($subscription_tier && isset($subscription_tier['name'])) {
          $tier_name = $subscription_tier['name'];
        }
        if (!empty($tier_name) && !in_array($tier_name, $paid_tier_names, true)) {
          // Not a paid Talishar tier, skip this community
          continue;
        }
        // If no tier_name found for Talishar, skip it (not a paid supporter)
        if (empty($tier_name)) {
          continue;
        }
      }
      
      // Add community to list
      $community_info = array(
        'id' => $community_id,
        'title' => $community['title'] ?? null,
        'description' => $community['description'] ?? null,
        'logo_url' => $community['logo_url'] ?? null,
        'cover_url' => $community['cover_url'] ?? null,
        'url' => $community['url'] ?? null,
        'tiers' => $community['tiers'] ?? [],
        'subscription_tier' => $subscription_tier,
        'type' => $community_type
      );
      $all_communities[] = $community_info;
    }
  }
  
  // Save all communities to database
  $communities_json = json_encode($all_communities);
  $response->debug_metafy = array(
    'communities_count' => count($all_communities),
    'communities_list' => array_map(function($c) { return array('id' => $c['id'], 'title' => $c['title'], 'type' => $c['type']); }, $all_communities)
  );
  
  $sql = 'UPDATE users SET metafyCommunities=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $communities_json, $userID);
    $result = mysqli_stmt_execute($stmt);
    $response->debug_metafy['sql_result'] = $result ? 'success' : 'failed';
    mysqli_stmt_close($stmt);
  } else {
    $response->debug_metafy['sql_result'] = 'prepare_failed: ' . mysqli_error($conn);
  }
  
  mysqli_close($conn);
}

?>
