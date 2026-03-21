<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

if (!isset($_SESSION["useruid"])) {
  http_response_code(401);
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$useruid = $_SESSION["useruid"];

include_once '../includes/ModeratorList.inc.php';
if (!IsUserModerator($useruid)) {
  http_response_code(403);
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["error" => "Method not allowed"]);
  exit;
}

if (file_exists('../APIKeys/APIKeys.php')) {
  include_once '../APIKeys/APIKeys.php';
}

$talishar_community_id = 'be5e01c0-02d1-4080-b601-c056d69b03f6';
$talishar_client_id = '4gIw_YYtamUjZ0yadyy3gYaL_BJkaRnPOa5SKCLbEPI';
$metafyApiKey = getenv('METAFY_API_KEY') ?: (defined('METAFY_API_KEY') ? METAFY_API_KEY : '');

$modMetafyToken = '';
$conn_mod = GetDBConnection();
if ($conn_mod) {
  $stmt_mod = mysqli_stmt_init($conn_mod);
  if (mysqli_stmt_prepare($stmt_mod, "SELECT metafyAccessToken FROM users WHERE usersUid=? AND metafyAccessToken IS NOT NULL AND metafyAccessToken != '' LIMIT 1")) {
    mysqli_stmt_bind_param($stmt_mod, 's', $useruid);
    mysqli_stmt_execute($stmt_mod);
    $res_mod = mysqli_stmt_get_result($stmt_mod);
    $row_mod = mysqli_fetch_assoc($res_mod);
    mysqli_stmt_close($stmt_mod);
    $modMetafyToken = $row_mod['metafyAccessToken'] ?? '';
  }
  mysqli_close($conn_mod);
}

$all_subscriber_ids = [];
$api_source = '';
$api_error = null;
$max_pages = 50;

$auth_token = !empty($modMetafyToken) ? $modMetafyToken : (!empty($metafyApiKey) ? $metafyApiKey : $talishar_client_id);

// --- Primary: dev.metafy.gg/v1/community/list-community-subscribers ---
$page = 1;

while ($page <= $max_pages) {
  $url = "https://dev.metafy.gg/v1/community/list-community-subscribers?community_id=" . urlencode($talishar_community_id) . "&per_page=100&page=" . intval($page);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $auth_token,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  $raw = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_err = curl_error($ch);
  curl_close($ch);

  if ($http_code !== 200) {
    if ($page === 1) {
      $api_error = "dev.metafy.gg returned HTTP $http_code" . ($curl_err ? " ($curl_err)" : "");
    }
    break;
  }

  $data = json_decode($raw, true);
  $subscribers = $data['subscribers'] ?? $data['data'] ?? $data['members'] ?? $data['users'] ?? [];
  if (empty($subscribers)) {
    if ($page === 1 && empty($all_subscriber_ids)) {
      $raw_preview = substr($raw, 0, 300);
      $api_error = "dev.metafy.gg returned 200 but no subscribers found. Response keys: [" . implode(', ', array_keys($data ?? [])) . "] | Preview: $raw_preview";
    }
    break;
  }

  foreach ($subscribers as $sub) {
    $uid = $sub['user_id'] ?? $sub['id'] ?? null;
    if ($uid) $all_subscriber_ids[] = $uid;
  }

  $api_source = 'dev.metafy.gg/v1/community/list-community-subscribers';
  if (count($subscribers) < 100) break;
  $page++;
}

// --- Fallback: metafy.gg/irk/api/v1/me/community/subscribers ---
if (empty($all_subscriber_ids)) {
  $fallback_error = null;
  $page = 1;

  while ($page <= $max_pages) {
    $url = "https://metafy.gg/irk/api/v1/me/community/subscribers?per_page=100&page=" . intval($page);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $auth_token,
      'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
    $raw = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($http_code !== 200) {
      if ($page === 1) {
        $fallback_error = "metafy.gg/irk returned HTTP $http_code" . ($curl_err ? " ($curl_err)" : "");
      }
      break;
    }

    $data = json_decode($raw, true);
    $subscribers = $data['subscribers'] ?? [];
    if (empty($subscribers)) break;

    foreach ($subscribers as $sub) {
      $uid = $sub['user_id'] ?? $sub['id'] ?? null;
      if ($uid) $all_subscriber_ids[] = $uid;
    }

    $api_source = 'metafy.gg/irk/api/v1/me/community/subscribers (fallback)';
    if (count($subscribers) < 100) break;
    $page++;
  }

  if (empty($all_subscriber_ids) && $fallback_error) {
    $api_error .= " | Fallback: $fallback_error";
  }
}

$all_subscriber_ids = array_unique($all_subscriber_ids);

// Safety: abort if API returned zero subscribers to avoid clearing everyone
if (empty($all_subscriber_ids)) {
  $token_source = !empty($modMetafyToken) ? 'moderator OAuth token' : (!empty($metafyApiKey) ? 'METAFY_API_KEY' : 'legacy client_id (invalid)');
  http_response_code(502);
  echo json_encode([
    "error" => "Could not fetch any subscribers from Metafy. Sync aborted to avoid clearing valid supporters.",
    "apiError" => ($api_error ?? 'No subscribers returned.') . " [auth: $token_source]",
    "hint" => empty($modMetafyToken) && empty($metafyApiKey)
      ? "No valid Metafy token found. Connect your Metafy account (as the Talishar community owner) or set the METAFY_API_KEY environment variable."
      : "Check that the connected Metafy account is the Talishar community owner."
  ]);
  exit;
}

// Cross-reference DB
$conn = GetDBConnection();
if (!$conn) {
  http_response_code(500);
  echo json_encode(["error" => "DB connection failed"]);
  exit;
}

$sql = "SELECT usersId, usersUid, metafyID, metafyCommunities FROM users WHERE metafyCommunities IS NOT NULL AND metafyCommunities != '' AND metafyCommunities != '[]'";
$result = mysqli_query($conn, $sql);

$checked = 0;
$cleared = 0;
$still_active = 0;
$no_metafy_id = 0;
$cleared_users = [];
$skipped_users = [];

while ($row = mysqli_fetch_assoc($result)) {
  $communities = json_decode($row['metafyCommunities'], true);
  if (!is_array($communities)) continue;

  $has_talishar = false;
  foreach ($communities as $c) {
    if (($c['id'] ?? '') === $talishar_community_id) {
      $has_talishar = true;
      break;
    }
  }
  if (!$has_talishar) continue;

  $checked++;
  $metafyID = $row['metafyID'] ?? null;

  if (empty($metafyID)) {
    $no_metafy_id++;
    $skipped_users[] = $row['usersUid'];
    continue;
  }

  if (in_array($metafyID, $all_subscriber_ids)) {
    $still_active++;
    continue;
  }

  // User is no longer a subscriber - remove Talishar community from their list
  $updated_communities = array_values(array_filter($communities, function($c) use ($talishar_community_id) {
    return ($c['id'] ?? '') !== $talishar_community_id;
  }));

  $updated_json = json_encode($updated_communities);
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, 'UPDATE users SET metafyCommunities=? WHERE usersId=?')) {
    mysqli_stmt_bind_param($stmt, 'si', $updated_json, $row['usersId']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $cleared++;
    $cleared_users[] = $row['usersUid'];
  }
}

mysqli_free_result($result);
mysqli_close($conn);

$response = [
  "message" => "Sync complete",
  "apiSource" => $api_source,
  "subscribersFetched" => count($all_subscriber_ids),
  "usersChecked" => $checked,
  "stillActive" => $still_active,
  "cleared" => $cleared,
  "skippedNoMetafyId" => $no_metafy_id,
  "clearedUsers" => $cleared_users,
  "skippedUsers" => $skipped_users
];

if ($api_error) {
  $response["apiWarning"] = $api_error;
}

echo json_encode($response);
