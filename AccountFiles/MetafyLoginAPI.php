<?php

include_once './AccountSessionAPI.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';

SetHeaders();

$response = new stdClass();

$client_id = $metafyClientID ?? '';
$client_secret = $metafyClientSecretReact ?? '';
$redirect_uri = 'https://talishar.net/user/profile/linkmetafy';

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above.
// It will grab the auth code from Metafy and get the tokens via the OAuth client

if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];
  
  // Exchange the code for tokens
  $token_url = 'https://api.metafy.gg/oauth2/token';
  
  $post_fields = array(
    'grant_type' => 'authorization_code',
    'code' => $code,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $token_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
  if (!isset($_SESSION['userid'])) return;
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  $sql = 'UPDATE users SET metafyAccessToken=?, metafyRefreshToken=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'sss', $accessToken, $refreshToken, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}

function FetchAndSaveMetafyCommunities($access_token)
{
  if (!isset($_SESSION['userid'])) return;
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  
  // Fetch communities from Metafy API
  $communities_url = 'https://api.metafy.gg/v1/user/communities';
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $communities_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  
  $communities_response = curl_exec($ch);
  curl_close($ch);
  
  $communities_data = json_decode($communities_response, true);
  
  if (isset($communities_data['data'])) {
    $communities_json = json_encode($communities_data['data']);
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

?>
