<?php

include_once './AccountSessionAPI.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';

CheckSession();

$response = new stdClass();

// Use the "Talishar Login" app credentials for signup/login flow
$client_id = $metafyLoginClientID ?? '';
$client_secret = $metafyLoginClientSecret ?? '';
$redirect_uri = 'https://talishar.net/auth/metafy-signup';

// Exchange the authorization code for tokens
if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];
  
  // Exchange the code for tokens using the correct Metafy endpoint
  $token_url = 'https://metafy.gg/irk/oauth/token';
  
  $post_fields = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri
  ];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
  ]);
  // Use HTTP Basic Authentication for client credentials (Metafy requirement)
  curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $token_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);

  $tokens = json_decode($token_response, true);

  if (isset($tokens['access_token'])) {
    $access_token = $tokens['access_token'];
    $refresh_token = $tokens['refresh_token'] ?? '';

    // Fetch user profile from Metafy API to get email/username
    $user_profile = GetMetafyUserProfile($access_token);

    if ($user_profile && isset($user_profile['id'])) {
      // Create or find user account
      $userID = CreateOrUpdateMetafyUser($user_profile, $access_token, $refresh_token);

      if ($userID) {
        // Log the user in — regenerate session ID once at login to prevent fixation.
        // Must be done BEFORE writing session data so the new ID carries the data.
        // Do NOT pass true (delete old file); keep old file so the browser continuing
        // to use the old ID (e.g. if Set-Cookie is stripped by a proxy) still works.
        session_regenerate_id(false);
        $_SESSION['userid'] = $userID;
        // Get the actual username from database (prefer existing username over Metafy username)
        $existingUsername = GetExistingUsername($userID);
        $_SESSION['useruid'] = $existingUsername ?? ($user_profile['username'] ?? $user_profile['email'] ?? $userID);
        $_SESSION['isPatron'] = CheckIfMetafySupporter($userID);

        $response->message = 'ok';
        $response->redirect = '/game/MainMenu.php';
        $response->isUserLoggedIn = true;
        $response->loggedInUserID = $userID;
        $response->loggedInUserName = $_SESSION['useruid'];
        $response->isPatron = $_SESSION['isPatron'];
      }
      else {
        $response->error = 'Failed to create or update user account';
      }
    }
    else {
      $response->error = 'Failed to fetch user profile from Metafy';
    }
  }
  else {
    $error_msg = $tokens['error'] ?? 'Failed to get access token';
    $error_description = $tokens['error_description'] ?? 'No description';
    $response->error = $error_msg;
    $response->error_description = $error_description;
  }
}
else {
  $response->error = 'No authorization code provided';
}

session_write_close();

// Return JSON response for frontend to handle
SetHeaders();
echo json_encode($response);
exit;

/**
 * Fetch user profile from Metafy API
 */
function GetMetafyUserProfile($access_token)
{
  $url = 'https://metafy.gg/irk/api/v1/me';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);

  if ($http_code === 200) {
    $profile = json_decode($response, true);
    // Metafy API returns user data nested under 'user' key
    if (isset($profile['user'])) {
      $user_data = $profile['user'];
      // Prefer 'name' field for proper capitalization, fall back to 'slug' if not available
      if (isset($user_data['name']) && !empty($user_data['name'])) {
        $user_data['username'] = $user_data['name'];
      }
      elseif (isset($user_data['slug']) && !isset($user_data['username'])) {
        $user_data['username'] = $user_data['slug'];
      }
      return $user_data;
    }
    else {
      return null;
    }
  }

  return null;
}

/**
 * Create a new user account or update existing Metafy user
 */
function CreateOrUpdateMetafyUser($user_profile, $access_token, $refresh_token)
{
  $conn = GetDBConnection();

  // Try to find existing user by email
  $email = $user_profile['email'] ?? '';
  $username = $user_profile['username'] ?? 'metafy_user_' . substr($user_profile['id'], 0, 8);
  $metafy_id = $user_profile['id'] ?? '';

  // Check if user exists by email
  if (!empty($email)) {
    $sql = "SELECT usersid FROM users WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 's', $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);

      if ($row) {
        // User exists, update Metafy tokens
        $userID = $row['usersid'];
        UpdateMetafyTokens($userID, $access_token, $refresh_token, $metafy_id);
        FetchAndSaveMetafyCommunities($userID, $access_token);
        mysqli_close($conn);
        return $userID;
      }
    }
  }

  // Check if username already exists, generate unique one if needed
  $base_username = $username;
  $counter = 1;
  while (UsernameExists($username, $conn)) {
    $username = $base_username . $counter;
    $counter++;
  }

  // Create new user account
  $hashedPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (usersUid, usersEmail, usersPwd, metafyAccessToken, metafyRefreshToken, metafyID) 
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssssss', $username, $email, $hashedPassword, $access_token, $refresh_token, $metafy_id);

    if (mysqli_stmt_execute($stmt)) {
      $userID = mysqli_insert_id($conn);
      mysqli_stmt_close($stmt);

      // Fetch and save communities for new user
      FetchAndSaveMetafyCommunities($userID, $access_token);

      mysqli_close($conn);
      return $userID;
    }
    else {
      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($conn);
  return null;
}

/**
 * Check if username exists
 */
function UsernameExists($username, $conn)
{
  $sql = "SELECT usersid FROM users WHERE usersUid=?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ? true : false;
  }
  return false;
}

/**
 * Update Metafy tokens for existing user
 */
function UpdateMetafyTokens($userID, $access_token, $refresh_token, $metafy_id)
{
  $conn = GetDBConnection();
  $sql = "UPDATE users SET metafyAccessToken=?, metafyRefreshToken=?, metafyID=? WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssss', $access_token, $refresh_token, $metafy_id, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
}

/**
 * Fetch and save communities for a user
 */
function FetchAndSaveMetafyCommunities($userID, $access_token)
{
  // Reuse the logic from MetafyLoginAPI.php
  include_once '../includes/dbh.inc.php';

  $conn = GetDBConnection();
  $all_communities = [];

  // 1. Fetch the authenticated user's owned community (if they are a coach/creator)
  $community_url = 'https://metafy.gg/irk/api/v1/me/community';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $community_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $community_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $community_data = json_decode($community_response, true);

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
  }

  // 2. Fetch all joined communities (memberships)
  $memberships_url = 'https://metafy.gg/irk/api/v1/me/community/memberships?per_page=100';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $memberships_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $memberships_response = curl_exec($ch);
  $memberships_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $memberships_data = json_decode($memberships_response, true);

  if ($memberships_http_code === 200 && isset($memberships_data['communities'])) {
    foreach ($memberships_data['communities'] as $community) {
      $community_id = $community['id'] ?? null;

      if (!$community_id) {
        continue;
      }

      // Check if user has an active paid subscription to this community
      $purchase_url = 'https://metafy.gg/irk/api/v1/me/purchases/communities/' . urlencode($community_id);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $purchase_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
      ]);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

      $purchase_response = curl_exec($ch);
      $purchase_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      $purchase_data = json_decode($purchase_response, true);

      if ($purchase_http_code === 200 && isset($purchase_data['community']['has_access']) && $purchase_data['community']['has_access'] === true) {
        $community_info = [
          'id' => $community_id,
          'title' => $community['title'] ?? null,
          'description' => $community['description'] ?? null,
          'logo_url' => $community['logo_url'] ?? null,
          'cover_url' => $community['cover_url'] ?? null,
          'url' => $community['url'] ?? null,
          'tiers' => $community['tiers'] ?? [],
          'type' => 'supported'
        ];
        $all_communities[] = $community_info;
      }
    }
  }
  
  // Save all communities to database
  if (count($all_communities) > 0) {
    $communities_json = json_encode($all_communities);
    $sql = 'UPDATE users SET metafyCommunities=? WHERE usersid=?';
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 'ss', $communities_json, $userID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
  }
  
  mysqli_close($conn);
}

/**
 * Check if user is a Metafy supporter (has Talishar supporter tier)
 */
function CheckIfMetafySupporter($userID)
{
  $conn = GetDBConnection();
  $sql = "SELECT metafyCommunities FROM users WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($row && !empty($row['metafyCommunities'])) {
      $communities = json_decode($row['metafyCommunities'], true);
      if (is_array($communities)) {
        // Check if Talishar community (UUID: be5e01c0-02d1-4080-b601-c056d69b03f6) is in the list
        foreach($communities as $community) {
          if(isset($community['id']) && $community['id'] === 'be5e01c0-02d1-4080-b601-c056d69b03f6') {
            return "1";
          }
        }
      }
    }
  }
  
  mysqli_close($conn);
  return "0";
}

/**
 * Get existing username from database
 */
function GetExistingUsername($userID)
{
  $conn = GetDBConnection();
  $sql = "SELECT usersUid FROM users WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($row && isset($row['usersUid'])) {
      mysqli_close($conn);
      return $row['usersUid'];
    }
  }
  
  mysqli_close($conn);
  return null;
}

