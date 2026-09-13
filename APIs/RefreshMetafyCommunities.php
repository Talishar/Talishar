<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../includes/MetafyHelper.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$response = new stdClass();

if (!IsUserLoggedIn()) {
  http_response_code(401);
  $response->error = 'not_authenticated';
  $response->message = 'You are not signed in. Please log in and try again.';
  echo json_encode($response);
  exit;
}

$userID = LoggedInUser();

// A full sync makes several calls to Metafy. Nothing below reads or writes the session,
// so release the lock now rather than blocking this user's other requests on it.
session_write_close();

$conn = GetDBConnection(DBL_REFRESH_METAFY_COMMUNITIES);
if (!$conn) {
  http_response_code(500);
  $response->error = 'db_error';
  echo json_encode($response);
  exit;
}

$auth = MetafyLoadAuth($conn, $userID);
if ($auth === null) {
  mysqli_close($conn);
  http_response_code(500);
  $response->error = 'db_error';
  echo json_encode($response);
  exit;
}

if (empty($auth['access_token'])) {
  mysqli_close($conn);
  http_response_code(400);
  $response->error = 'no_access_token';
  $response->message = 'No Metafy account linked. Please connect via the OAuth link first.';
  echo json_encode($response);
  exit;
}

$sync = MetafySyncCommunities($conn, $auth);
mysqli_close($conn);

if ($sync['needsReauth']) {
  http_response_code(401);
  $response->error = $sync['error'];
  $response->message = $sync['error'] === 'missing_scope'
    ? 'Your Metafy connection is missing the permissions needed to read your subscription. Please re-connect your Metafy account.'
    : 'Metafy access token expired and could not be renewed. Please re-connect your Metafy account.';
  $response->metafyCommunities = $sync['communities'];
  $response->isMetafySupporter = $sync['isSupporter'];
  echo json_encode($response);
  exit;
}

if (!$sync['ok']) {
  http_response_code(503);
  $response->error   = 'metafy_unavailable';
  $response->message = 'Could not reach Metafy right now. Your existing status was kept, please try again in a moment.';
  $response->metafyCommunities = $sync['communities'];
  $response->isMetafySupporter = $sync['isSupporter'];
  echo json_encode($response);
  exit;
}

$response->message           = 'ok';
$response->metafyCommunities = $sync['communities'];
$response->isMetafySupporter = $sync['isSupporter'];
echo json_encode($response);
