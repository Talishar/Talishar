<?php

include_once './AccountSessionAPI.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';

SetHeaders();
CheckSession();

$response = new stdClass();

if (!isset($_SESSION['userid'])) {
  $response->error = 'User not logged in';
  http_response_code(401);
  echo json_encode($response);
  exit;
}

$userID = $_SESSION['userid'];
$conn = GetDBConnection();

// Initialize response
$response->message = 'Metafy communities refreshed successfully';

try {
  // Get the stored Metafy access token
  $sql = 'SELECT metafyAccessToken FROM users WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    throw new Exception('Database query failed');
  }

  mysqli_stmt_bind_param($stmt, 's', $userID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);

  if (!$row || empty($row['metafyAccessToken'])) {
    $response->error = 'No Metafy account linked. Please connect to Metafy first.';
    http_response_code(400);
    echo json_encode($response);
    exit;
  }

  $access_token = $row['metafyAccessToken'];

  // Fetch and save updated communities
  FetchAndSaveMetafyCommunities($access_token, $response);
  http_response_code(200);
} catch (Exception $e) {
  // Handle gracefully - on dev environments, Metafy columns might not exist
  if (strpos($e->getMessage(), 'Unknown column') !== false) {
    $response->message = 'Metafy not configured in this environment';
    http_response_code(200); // Still return 200 so UI doesn't error
  } else {
    $response->error = $e->getMessage();
    http_response_code(500);
  }
}

mysqli_close($conn);
echo json_encode($response);
exit;

/**
 * Fetch user's Metafy communities and save to database
 * This function mirrors the logic from MetafyLoginAPI.php
 */
function FetchAndSaveMetafyCommunities($access_token, &$response)
{
  if (!isset($_SESSION['userid'])) {
    $response->error = 'User session invalid';
    return;
  }
  
  $userID = $_SESSION['userid'];
  $conn = GetDBConnection();
  
  $all_communities = [];
  
  // 1. Fetch owned community if user has one
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
  
  // Build a set of already-added community IDs to avoid duplicates
  $added_community_ids = array_filter(array_column($all_communities, 'id'));

  // Process all joined communities (memberships)
  if ($memberships_http_code === 200 && isset($memberships_data['communities'])) {
    foreach ($memberships_data['communities'] as $community) {
      $community_id = $community['id'] ?? null;
      if (!$community_id) continue;
      if (in_array($community_id, $added_community_ids)) continue;
      $added_community_ids[] = $community_id;

      // Build a tier_id → name lookup from the membership object's tiers list
      $tier_map = [];
      foreach (($community['tiers'] ?? []) as $tier) {
        if (!empty($tier['id']) && !empty($tier['name'])) {
          $tier_map[$tier['id']] = $tier['name'];
        }
      }

      // Check user's access and tier for this community
      $purchase_url = 'https://metafy.gg/irk/api/v1/me/purchases/communities/' . urlencode($community_id);
      $ch_pur = curl_init($purchase_url);
      curl_setopt($ch_pur, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_pur, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
      ]);
      curl_setopt($ch_pur, CURLOPT_USERAGENT, 'Talishar-App');
      $pur_response = curl_exec($ch_pur);
      $pur_http_code = curl_getinfo($ch_pur, CURLINFO_HTTP_CODE);
      curl_close($ch_pur);

      $community_info = [
        'id' => $community_id,
        'title' => $community['title'] ?? null,
        'description' => $community['description'] ?? null,
        'logo_url' => $community['logo_url'] ?? null,
        'cover_url' => $community['cover_url'] ?? null,
        'url' => $community['url'] ?? null,
        'type' => 'supported'
      ];

      if ($pur_http_code === 200 && !empty($pur_response)) {
        $pur_data = json_decode($pur_response, true);
        $has_access = $pur_data['community']['has_access'] ?? false;
        $tier_id    = $pur_data['community']['tier_id'] ?? null;
        if ($has_access && $tier_id && isset($tier_map[$tier_id])) {
          $community_info['metafy_tier'] = $tier_map[$tier_id];
        }
      }

      $all_communities[] = $community_info;
    }
  }
  
  // Save all communities to database
  $communities_json = json_encode($all_communities);
  
  $sql = 'UPDATE users SET metafyCommunities=? WHERE usersid=?';
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $communities_json, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
  
  mysqli_close($conn);
}
