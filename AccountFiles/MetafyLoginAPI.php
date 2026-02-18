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
  $response->tokens = $tokens;
  
  if (isset($tokens['access_token'])) {
    $access_token = $tokens['access_token'];
    $refresh_token = $tokens['refresh_token'] ?? '';
    $response->access_token = $access_token;
    $response->refresh_token = $refresh_token;
    
    // Fetch the user's Metafy ID and save tokens + ID
    SaveMetafyTokensAndID($access_token, $refresh_token);
    
    // Fetch user's communities
    FetchAndSaveMetafyCommunities($access_token, $response, $tokens['access_token']);
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

function FetchAndSaveMetafyCommunities($access_token, &$response, $fresh_token = null)
{
  if (!isset($_SESSION['userid'])) {
    $response->debug_communities = 'no session userid';
    return;
  }
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  
  $all_communities = array();
  $talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';
  
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

  // Process all joined communities (memberships) — these are social/free follows
  if ($memberships_http_code === 200 && isset($memberships_data['communities'])) {
    foreach ($memberships_data['communities'] as $community) {
      $community_id = $community['id'] ?? null;
      if (!$community_id) continue;
      if (in_array($community_id, $added_community_ids)) continue; // skip duplicates
      $added_community_ids[] = $community_id;

      $community_info = array(
        'id' => $community_id,
        'title' => $community['title'] ?? null,
        'description' => $community['description'] ?? null,
        'logo_url' => $community['logo_url'] ?? null,
        'cover_url' => $community['cover_url'] ?? null,
        'url' => $community['url'] ?? null,
        'type' => 'supported'
      );
      $all_communities[] = $community_info;
    }
  }

  // 3. Check paid Talishar subscription using client_id as Bearer (per Metafy docs for community owner endpoints)
  // This endpoint lists all active paid subscribers to the Talishar community
  $talishar_client_id = '4gIw_YYtamUjZ0yadyy3gYaL_BJkaRnPOa5SKCLbEPI';
  $subscribers_url = 'https://metafy.gg/irk/api/v1/me/community/subscribers?per_page=100';

  // Get this user's metafyID directly from the Metafy /me endpoint using the fresh token
  $user_metafy_id = null;
  $token_to_use = $fresh_token ?? $access_token;
  $ch_me = curl_init('https://metafy.gg/irk/api/v1/me');
  curl_setopt($ch_me, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch_me, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $token_to_use,
    'Content-Type: application/json'
  ));
  curl_setopt($ch_me, CURLOPT_USERAGENT, 'Talishar-App');
  $me_response = curl_exec($ch_me);
  $me_http_code = curl_getinfo($ch_me, CURLINFO_HTTP_CODE);
  curl_close($ch_me);
  if ($me_http_code === 200) {
    $me_data = json_decode($me_response, true);
    $user_metafy_id = $me_data['user']['id'] ?? null;
    // Also update DB with the metafyID
    if ($user_metafy_id) {
      $stmt_id = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt_id, 'UPDATE users SET metafyID=? WHERE usersid=?')) {
        mysqli_stmt_bind_param($stmt_id, 'ss', $user_metafy_id, $userID);
        mysqli_stmt_execute($stmt_id);
        mysqli_stmt_close($stmt_id);
      }
    }
  }
  $response->debug_me = array('http_code' => $me_http_code, 'user_id' => $user_metafy_id);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $subscribers_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $talishar_client_id,
    'Content-Type: application/json'
  ));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $subscribers_response = curl_exec($ch);
  $subscribers_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $response->debug_talishar_sub = array(
    'http_code' => $subscribers_http_code,
    'user_metafy_id' => $user_metafy_id,
    'raw' => $subscribers_response
  );

  if ($subscribers_http_code === 200 && !empty($subscribers_response)) {
    $subscribers_data = json_decode($subscribers_response, true);
    $is_talishar_subscriber = false;

    if (isset($subscribers_data['subscribers']) && is_array($subscribers_data['subscribers'])) {
      foreach ($subscribers_data['subscribers'] as $sub) {
        if ($user_metafy_id && ($sub['user_id'] ?? null) === $user_metafy_id) {
          $is_talishar_subscriber = true;
          // Capture the subscriber's tier name
          $subscriber_tier_name = $sub['tier']['name'] ?? ($sub['subscription_tier']['name'] ?? null);
          break;
        }
      }
    }

    if ($is_talishar_subscriber) {
      $already_added = false;
      foreach ($all_communities as &$c) {
        if (($c['id'] ?? null) === $talishar_community_id) {
          // Update existing entry with tier name if we have it
          if (!empty($subscriber_tier_name)) {
            $c['metafy_tier'] = $subscriber_tier_name;
          }
          $already_added = true;
          break;
        }
      }
      unset($c);
      if (!$already_added) {
        $all_communities[] = array(
          'id' => $talishar_community_id,
          'title' => 'Talishar',
          'description' => 'Flesh and Blood TCG — get exclusive card backs, playmats, and alt arts.',
          'logo_url' => null,
          'url' => 'https://talishar.net',
          'type' => 'supported',
          'metafy_tier' => $subscriber_tier_name ?? null
        );
      }
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
