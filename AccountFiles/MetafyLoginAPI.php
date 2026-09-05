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

$allowed_redirect_uris = [GetMetafyRedirectUri('link')];
$redirect_uri = $allowed_redirect_uris[0];
if (isset($_GET['redirect_uri']) && in_array($_GET['redirect_uri'], $allowed_redirect_uris, true)) {
  $redirect_uri = $_GET['redirect_uri'];
}

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above.
// It will grab the auth code from Metafy and get the tokens via the OAuth client

if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];

  // Per Metafy docs: client_id and client_secret go in the POST body as JSON
  $post_fields = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret
  ];
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, METAFY_TOKEN_URL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);

  $token_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $tokens = json_decode($token_response, true);

  if (is_array($tokens) && isset($tokens['access_token'])) {
    LinkMetafyAccount($tokens, $response);
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

function LinkMetafyAccount($tokens, &$response)
{
  if (!isset($_SESSION['userid'])) {
    $response->error = 'not_authenticated';
    $response->error_description = 'You must be signed in to link a Metafy account.';
    return;
  }
  $userID = $_SESSION['userid'];

  $conn = GetDBConnection(DBL_METAFY_LOGIN_API);
  if (!$conn) {
    $response->error = 'db_error';
    return;
  }

  if (!MetafySaveTokens($conn, $userID, $tokens)) {
    mysqli_close($conn);
    $response->error = 'db_error';
    return;
  }

  $auth = MetafyLoadAuth($conn, $userID);
  if ($auth === null) {
    mysqli_close($conn);
    $response->error = 'db_error';
    return;
  }

  $metafyUserID = MetafyEnsureUserId($auth, $conn);
  if (!empty($metafyUserID)) {
    $_SESSION['metafyID'] = $metafyUserID;
  }

  $sync = MetafySyncCommunities($conn, $auth);
  mysqli_close($conn);

  $response->metafyCommunities = $sync['communities'];
  $response->isMetafySupporter = $sync['isSupporter'];

  if (!$sync['ok']) {
    $response->error = $sync['error'] ?? 'metafy_unavailable';
    $response->error_description = $sync['error'] === 'missing_scope'
      ? 'Metafy did not grant the permissions needed to read your subscription. Please try connecting again.'
      : 'Could not read your Metafy communities right now. Existing status was kept.';
  }
}
