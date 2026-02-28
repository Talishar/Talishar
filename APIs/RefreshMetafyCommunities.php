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
$conn = GetDBConnection();
$sql = "SELECT metafyAccessToken, metafyRefreshToken FROM users WHERE usersUid=?";
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

// --- Fetch owned community ---
$all_communities = [];

$community_url = 'https://metafy.gg/irk/api/v1/me/community';
$ch = curl_init($community_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 8);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer ' . $accessToken,
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
$community_response = curl_exec($ch);
$community_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// If token is expired/invalid, tell the frontend to re-auth via OAuth
if ($community_http_code === 401) {
  http_response_code(401);
  $response->error = 'token_expired';
  $response->message = 'Metafy access token expired. Please re-connect your Metafy account.';
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
}

// --- Fetch memberships (supported communities) ---
$memberships_url = 'https://metafy.gg/irk/api/v1/me/community/memberships?per_page=100';
$ch = curl_init($memberships_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 8);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer ' . $accessToken,
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
$memberships_response = curl_exec($ch);
$memberships_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$added_community_ids = array_filter(array_column($all_communities, 'id'));

if ($memberships_http_code === 200 && !empty($memberships_response)) {
  $memberships_data = json_decode($memberships_response, true);

  if (isset($memberships_data['communities'])) {
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
      $ch_pur = curl_init($purchase_url);
      curl_setopt($ch_pur, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch_pur, CURLOPT_TIMEOUT, 8);
      curl_setopt($ch_pur, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
      ]);
      curl_setopt($ch_pur, CURLOPT_USERAGENT, 'Talishar-App');
      $pur_response  = curl_exec($ch_pur);
      $pur_http_code = curl_getinfo($ch_pur, CURLINFO_HTTP_CODE);
      curl_close($ch_pur);

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
      }

      $all_communities[] = $community_info;
    }
  }
}

// --- Check Talishar subscriber status directly (using client_id as Bearer) ---
$talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';
$is_metafy_supporter   = false;

// Get Metafy user ID (needed for subscriber check)
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

// If no metafyID cached, fetch it live
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

// Check paid subscriber list via Talishar client_id
$talishar_client_id = '4gIw_YYtamUjZ0yadyy3gYaL_BJkaRnPOa5SKCLbEPI';
$ch_sub = curl_init('https://metafy.gg/irk/api/v1/me/community/subscribers?per_page=100');
curl_setopt($ch_sub, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_sub, CURLOPT_TIMEOUT, 5);
curl_setopt($ch_sub, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer ' . $talishar_client_id,
  'Content-Type: application/json'
]);
curl_setopt($ch_sub, CURLOPT_USERAGENT, 'Talishar-App');
$sub_raw  = curl_exec($ch_sub);
$sub_code = curl_getinfo($ch_sub, CURLINFO_HTTP_CODE);
curl_close($ch_sub);

if ($sub_code === 200 && !empty($sub_raw)) {
  $sub_data       = json_decode($sub_raw, true);
  $subscriber_ids = array_column($sub_data['subscribers'] ?? [], 'user_id');
  if ($user_metafy_id && in_array($user_metafy_id, $subscriber_ids)) {
    $is_metafy_supporter = true;
  }
}

// If confirmed subscriber, ensure Talishar community is in the list
if ($is_metafy_supporter) {
  $talishar_in_list = false;
  foreach ($all_communities as $c) {
    if (($c['id'] ?? null) === $talishar_community_id) {
      $talishar_in_list = true;
      break;
    }
  }
  if (!$talishar_in_list) {
    $all_communities[] = [
      'id'          => $talishar_community_id,
      'title'       => 'Talishar',
      'description' => 'Flesh and Blood TCG — get exclusive card backs, playmats, and alt arts.',
      'logo_url'    => null,
      'url'         => 'https://talishar.net',
      'type'        => 'supported'
    ];
  }
} else {
  // Fallback: check communities list for Talishar
  foreach ($all_communities as $community) {
    if (isset($community['id']) && $community['id'] === $talishar_community_id) {
      $is_metafy_supporter = true;
      break;
    }
  }
}

// --- Persist refreshed data to DB ---
$communities_json = json_encode($all_communities);
$stmt_update = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt_update, 'UPDATE users SET metafyCommunities=? WHERE usersid=?')) {
  mysqli_stmt_bind_param($stmt_update, 'ss', $communities_json, $userID);
  mysqli_stmt_execute($stmt_update);
  mysqli_stmt_close($stmt_update);
}

mysqli_close($conn);

$response->message          = 'ok';
$response->metafyCommunities = $all_communities;
$response->isMetafySupporter = $is_metafy_supporter;
echo json_encode($response);
