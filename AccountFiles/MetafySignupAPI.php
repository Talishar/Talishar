<?php

include_once './AccountSessionAPI.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';

CheckSession();

SetHeaders();

$response = new stdClass();

// Use the "Talishar Login" app credentials for signup/login flow
$client_id = $metafyLoginClientID ?? '';
$client_secret = $metafyLoginClientSecret ?? '';
$redirect_uri = 'https://talishar.net/auth/metafy-signup';

// Exchange the authorization code for tokens
if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];
  error_log('[MetafySignupAPI] Received authorization code: ' . substr($code, 0, 10) . '...');
  error_log('[MetafySignupAPI] Redirect URI: ' . $redirect_uri);
  
  // Exchange the code for tokens using the correct Metafy endpoint
  $token_url = 'https://metafy.gg/irk/oauth/token';
  
  $post_fields = array(
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri
  );
  
  error_log('[MetafySignupAPI] Token exchange request: ' . json_encode($post_fields));
  error_log('[MetafySignupAPI] Using client_id: ' . substr($client_id, 0, 10) . '...');
  
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
  
  error_log('[MetafySignupAPI] Token exchange HTTP code: ' . $http_code);
  if ($curl_error) {
    error_log('[MetafySignupAPI] cURL error: ' . $curl_error);
  }
  error_log('[MetafySignupAPI] Token response: ' . $token_response);
  
  $tokens = json_decode($token_response, true);
  
  if (isset($tokens['access_token'])) {
    error_log('[MetafySignupAPI] Successfully obtained access token');
    $access_token = $tokens['access_token'];
    $refresh_token = $tokens['refresh_token'] ?? '';
    
    // Fetch user profile from Metafy API to get email/username
    $user_profile = GetMetafyUserProfile($access_token);
    
    if ($user_profile && isset($user_profile['id'])) {
      error_log('[MetafySignupAPI] User profile retrieved for user ID: ' . $user_profile['id']);
      // Create or find user account
      $userID = CreateOrUpdateMetafyUser($user_profile, $access_token, $refresh_token);
      
      if ($userID) {
        error_log('[MetafySignupAPI] User account created/updated with ID: ' . $userID);
        // Log the user in
        $_SESSION['userid'] = $userID;
        // Get the actual username from database (prefer existing username over Metafy username)
        $existingUsername = GetExistingUsername($userID);
        $_SESSION['useruid'] = $existingUsername ?? ($user_profile['username'] ?? $user_profile['email'] ?? $userID);
        $_SESSION['isPatron'] = CheckIfMetafySupporter($userID);
        
        error_log('[MetafySignupAPI] User logged in successfully: ' . $_SESSION['useruid']);
        
        $response->message = 'ok';
        $response->redirect = '/game/MainMenu.php';
        $response->isUserLoggedIn = true;
        $response->loggedInUserID = $userID;
        $response->loggedInUserName = $_SESSION['useruid'];
        $response->isPatron = $_SESSION['isPatron'];
      } else {
        error_log('[MetafySignupAPI] Failed to create or update user account');
        $response->error = 'Failed to create or update user account';
      }
    } else {
      error_log('[MetafySignupAPI] Failed to fetch user profile from Metafy');
      $response->error = 'Failed to fetch user profile from Metafy';
    }
  } else {
    $error_msg = isset($tokens['error']) ? $tokens['error'] : 'Failed to get access token';
    $error_description = isset($tokens['error_description']) ? $tokens['error_description'] : 'No description';
    error_log('[MetafySignupAPI] Token exchange failed - Error: ' . $error_msg . ', Description: ' . $error_description);
    $response->error = $error_msg;
    $response->error_description = $error_description;
  }
} else {
  error_log('[MetafySignupAPI] No authorization code provided in request. GET params: ' . json_encode($_GET));
  $response->error = 'No authorization code provided';
}

// Return JSON response for frontend to handle
echo json_encode($response);
exit;

/**
 * Fetch user profile from Metafy API
 */
function GetMetafyUserProfile($access_token)
{
  $url = 'https://metafy.gg/irk/api/v1/me';
  
  error_log('[MetafySignupAPI] GetMetafyUserProfile: Fetching user profile from ' . $url);
  error_log('[MetafySignupAPI] GetMetafyUserProfile: Using access token: ' . substr($access_token, 0, 10) . '...');
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);
  
  error_log('[MetafySignupAPI] GetMetafyUserProfile: HTTP Code: ' . $http_code);
  if ($curl_error) {
    error_log('[MetafySignupAPI] GetMetafyUserProfile: cURL error: ' . $curl_error);
  }
  error_log('[MetafySignupAPI] GetMetafyUserProfile: Response: ' . $response);
  
  if ($http_code === 200) {
    $profile = json_decode($response, true);
    // Metafy API returns user data nested under 'user' key
    if (isset($profile['user'])) {
      $user_data = $profile['user'];
      // Map 'slug' to 'username' for consistency
      if (isset($user_data['slug']) && !isset($user_data['username'])) {
        $user_data['username'] = $user_data['slug'];
      }
      error_log('[MetafySignupAPI] GetMetafyUserProfile: Successfully decoded profile for user: ' . ($user_data['username'] ?? $user_data['email'] ?? 'unknown'));
      return $user_data;
    } else {
      error_log('[MetafySignupAPI] GetMetafyUserProfile: Response missing user key');
      return null;
    }
  }
  
  error_log('[MetafySignupAPI] GetMetafyUserProfile: Failed - HTTP Code: ' . $http_code);
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
    } else {
      error_log('[MetafySignupAPI] Failed to insert user: ' . mysqli_error($conn));
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
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
      ));
      curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
      
      $purchase_response = curl_exec($ch);
      $purchase_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      
      $purchase_data = json_decode($purchase_response, true);
      
      if ($purchase_http_code === 200 && isset($purchase_data['community']['has_access']) && $purchase_data['community']['has_access'] === true) {
        $community_info = array(
          'id' => $community_id,
          'title' => $community['title'] ?? null,
          'description' => $community['description'] ?? null,
          'logo_url' => $community['logo_url'] ?? null,
          'cover_url' => $community['cover_url'] ?? null,
          'url' => $community['url'] ?? null,
          'tiers' => $community['tiers'] ?? [],
          'type' => 'supported'
        );
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

?>
