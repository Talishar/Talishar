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

if (!isset($_SESSION['userName']) || !isset($_SESSION['userid'])) {
  http_response_code(401);
  $response->error = 'not_authenticated';
  echo json_encode($response);
  exit;
}

$userName = $_SESSION['userName'];
$userID   = $_SESSION['userid'];

// Fetch stored access token from DB
$conn = GetDBConnection(DBL_REFRESH_METAFY_COMMUNITIES);
$sql = "SELECT metafyAccessToken, metafyRefreshToken, metafyCommunities FROM users WHERE usersUid=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  http_response_code(500);
  $response->error = 'db_error';
  echo json_encode($response);
  exit;
}
mysqli_stmt_bind_param($stmt, 's', $userName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row    = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$accessToken = $row['metafyAccessToken'] ?? null;

if (empty($accessToken)) {
  http_response_code(400);
  $response->error = 'no_access_token';
  $response->message = 'No Metafy account linked. Please connect via the OAuth link first.';
  echo json_encode($response);
  exit;
}

$stored_communities = json_decode($row['metafyCommunities'] ?? '', true);
$stored_by_id = IndexMetafyCommunitiesById($stored_communities);

// --- Fetch owned community ---
$all_communities = [];

$community_result = MetafyApiGet('https://metafy.gg/irk/api/v1/me/community', $accessToken);
$community_response  = $community_result['body'];
$community_http_code = $community_result['code'];

// If token is expired/invalid, tell the frontend to re-auth via OAuth
if (in_array($community_http_code, [401, 403], true)) {
  http_response_code(401);
  $response->error = 'token_expired';
  $response->message = 'Metafy access token expired or missing required permissions. Please re-connect your Metafy account.';
  echo json_encode($response);
  exit;
}

if ($community_http_code === 200 && !empty($community_response)) {
  $community_data = json_decode($community_response, true);
  if (isset($community_data['community'])) {
    $all_communities[] = [
      'id'          => $community_data['community']['id'] ?? null,
      'title'       => $community_data['community']['title'] ?? null,
      'description' => $community_data['community']['description'] ?? null,
      'logo_url'    => $community_data['community']['logo_url'] ?? null,
      'cover_url'   => $community_data['community']['cover_url'] ?? null,
      'url'         => $community_data['community']['url'] ?? null,
      'tiers'       => $community_data['community']['tiers'] ?? [],
      'type'        => 'owned'
    ];
  }
} elseif ($community_http_code !== 404) {
  foreach ($stored_by_id as $stored_community) {
    if (($stored_community['type'] ?? null) === 'owned') {
      $all_communities[] = $stored_community;
    }
  }
}

// --- Fetch memberships (supported communities) ---
$memberships_result = MetafyApiGet('https://metafy.gg/irk/api/v1/me/community/memberships?per_page=100', $accessToken);
$memberships_response  = $memberships_result['body'];
$memberships_http_code = $memberships_result['code'];

if (in_array($memberships_http_code, [401, 403], true)) {
  http_response_code(401);
  $response->error = 'token_expired';
  $response->message = 'Metafy access token expired or missing required permissions. Please re-connect your Metafy account.';
  echo json_encode($response);
  exit;
}

// The memberships list is the only authoritative source for which communities this
// user supports. If it did not come back cleanly, nothing may be persisted: writing
// a short list here is exactly what silently strips people of supporter status.
$memberships_data = $memberships_http_code === 200 && !empty($memberships_response)
  ? json_decode($memberships_response, true)
  : null;

if (!is_array($memberships_data) || !isset($memberships_data['communities'])) {
  mysqli_close($conn);
  http_response_code(503);
  $response->error   = 'metafy_unavailable';
  $response->message = 'Could not reach Metafy right now. Your existing status was kept, please try again in a moment.';
  echo json_encode($response);
  exit;
}

$added_community_ids = array_filter(array_column($all_communities, 'id'));

foreach ($memberships_data['communities'] as $community) {
  $community_id = $community['id'] ?? null;
  if (!$community_id) continue;
  if (in_array($community_id, $added_community_ids)) continue;
  $added_community_ids[] = $community_id;

  $tier_map = [];
  foreach (($community['tiers'] ?? []) as $tier) {
    if (!empty($tier['id']) && !empty($tier['name'])) {
      $tier_map[$tier['id']] = $tier['name'];
    }
  }

  // Check active paid subscription for this community
  $purchase_url = 'https://metafy.gg/irk/api/v1/me/purchases/communities/' . urlencode($community_id);
  $pur_result    = MetafyApiGet($purchase_url, $accessToken);
  $pur_response  = $pur_result['body'];
  $pur_http_code = $pur_result['code'];

  $community_info = [
    'id'          => $community_id,
    'title'       => $community['title'] ?? null,
    'description' => $community['description'] ?? null,
    'logo_url'    => $community['logo_url'] ?? null,
    'cover_url'   => $community['cover_url'] ?? null,
    'url'         => $community['url'] ?? null,
    'type'        => 'supported'
  ];

  if ($pur_http_code === 200 && !empty($pur_response)) {
    $pur_data   = json_decode($pur_response, true);
    $has_access = $pur_data['community']['has_access'] ?? false;
    $tier_id    = $pur_data['community']['tier_id'] ?? null;
    if ($has_access && $tier_id && isset($tier_map[$tier_id])) {
      $community_info['metafy_tier'] = $tier_map[$tier_id];
    }
  } elseif (!empty($stored_by_id[$community_id]['metafy_tier'])) {
    // This call failed, so it says nothing about whether they still have access.
    // Keep the tier we already knew about instead of dropping their perks.
    $community_info['metafy_tier'] = $stored_by_id[$community_id]['metafy_tier'];
  }

  $all_communities[] = $community_info;
}

// --- Resolve this user's Metafy ID (from DB, or live if not yet cached) ---
$talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';

$user_metafy_id = null;
$stmt_id = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt_id, "SELECT metafyID FROM users WHERE usersUid=?")) {
  mysqli_stmt_bind_param($stmt_id, 's', $userName);
  mysqli_stmt_execute($stmt_id);
  $result_id = mysqli_stmt_get_result($stmt_id);
  $row_id    = mysqli_fetch_assoc($result_id);
  mysqli_stmt_close($stmt_id);
  $user_metafy_id = $row_id['metafyID'] ?? null;
}

if (empty($user_metafy_id)) {
  $ch_me = curl_init('https://metafy.gg/irk/api/v1/me');
  curl_setopt($ch_me, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch_me, CURLOPT_TIMEOUT, 5);
  curl_setopt($ch_me, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch_me, CURLOPT_USERAGENT, 'Talishar-App');
  $me_raw  = curl_exec($ch_me);
  $me_code = curl_getinfo($ch_me, CURLINFO_HTTP_CODE);
  curl_close($ch_me);

  if ($me_code === 200) {
    $me_data        = json_decode($me_raw, true);
    $user_metafy_id = $me_data['user']['id'] ?? null;
    if ($user_metafy_id) {
      $stmt_save = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt_save, 'UPDATE users SET metafyID=? WHERE usersid=?')) {
        mysqli_stmt_bind_param($stmt_save, 'ss', $user_metafy_id, $userID);
        mysqli_stmt_execute($stmt_save);
        mysqli_stmt_close($stmt_save);
      }
    }
  }
}

$is_metafy_supporter = false;
foreach ($all_communities as $community) {
  if (isset($community['id']) && $community['id'] === $talishar_community_id) {
    $is_metafy_supporter = true;
    break;
  }
}

// --- Persist refreshed data to DB ---
$communities_json = json_encode($all_communities);
$stmt_update = mysqli_stmt_init($conn);
// Always write metafyID alongside communities if we have it, to keep them in sync
if (!empty($user_metafy_id)) {
  if (mysqli_stmt_prepare($stmt_update, 'UPDATE users SET metafyCommunities=?, metafyID=? WHERE usersid=?')) {
    mysqli_stmt_bind_param($stmt_update, 'sss', $communities_json, $user_metafy_id, $userID);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
  }
} else {
  if (mysqli_stmt_prepare($stmt_update, 'UPDATE users SET metafyCommunities=? WHERE usersid=?')) {
    mysqli_stmt_bind_param($stmt_update, 'ss', $communities_json, $userID);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
  }
}

mysqli_close($conn);

$response->message          = 'ok';
$response->metafyCommunities = $all_communities;
$response->isMetafySupporter = $is_metafy_supporter;
echo json_encode($response);
