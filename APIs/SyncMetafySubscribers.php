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
// Same API key used by RefreshMetafyCommunities.php to authenticate against the subscriber endpoint.
// This is the Talishar app client_id used as Bearer — NOT the OAuth client_id from APIKeys.php.
$talishar_client_id = '4gIw_YYtamUjZ0yadyy3gYaL_BJkaRnPOa5SKCLbEPI';

$all_subscriber_ids = [];
$api_source = '';
$api_error  = null;
$max_pages  = 50;

if (!empty($talishar_client_id)) {
  $page = 1;

  while ($page <= $max_pages) {
    $url = "https://metafy.gg/irk/api/v1/me/community/subscribers?per_page=100&page=" . intval($page);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $talishar_client_id,
      'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
    $raw      = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($http_code !== 200) {
      $api_error = "metafy.gg/irk returned HTTP $http_code" . ($curl_err ? " ($curl_err)" : "");
      break;
    }

    $data        = json_decode($raw, true);
    $subscribers = $data['subscribers'] ?? [];
    if (empty($subscribers)) {
      if ($page === 1 && empty($all_subscriber_ids)) {
        $raw_preview = substr($raw, 0, 400);
        $api_error = "metafy.gg/irk returned 200 but no subscribers found. Response keys: [" . implode(', ', array_keys($data ?? [])) . "] | Preview: $raw_preview";
      }
      break;
    }

    foreach ($subscribers as $sub) {
      $uid = $sub['user_id'] ?? $sub['id'] ?? null;
      if ($uid) {
        $all_subscriber_ids[] = $uid;
        $uname = strtolower($sub['username'] ?? $sub['slug'] ?? $sub['name'] ?? '');
        if (!empty($uname)) {
          $subscriber_usernames[$uname] = $uid;
        }
      }
    }

    $api_source = 'metafy.gg/irk/api/v1/me/community/subscribers';
    if (count($subscribers) < 100) break;
    $page++;
  }
}

$all_subscriber_ids  = array_unique($all_subscriber_ids);
$subscriber_usernames = $subscriber_usernames ?? [];

// Safety: abort if API returned zero subscribers to avoid clearing everyone.
// Note: use HTTP 200 so nginx does not intercept and strip CORS headers from the response.
if (empty($all_subscriber_ids)) {
  echo json_encode([
    "error" => "Could not fetch any subscribers from Metafy. Sync aborted to avoid clearing valid supporters.",
    "apiError" => $api_error ?? 'No subscribers returned.',
  ]);
  exit;
}

// Cross-reference DB
$conn = GetDBConnection(DBL_SYNC_METAFY_SUBSCRIBERS);
if (!$conn) {
  echo json_encode(["error" => "DB connection failed"]);
  exit;
}

$sql = "SELECT usersId, usersUid, metafyID, metafyCommunities, metafyAccessToken FROM users WHERE metafyCommunities IS NOT NULL AND metafyCommunities != '' AND metafyCommunities != '[]'";
$result = mysqli_query($conn, $sql);

$checked = 0;
$cleared = 0;
$still_active = 0;
$no_metafy_id = 0;
$backfilled = 0;
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
    if (!empty($row['metafyAccessToken'])) {
      $ch_me = curl_init('https://metafy.gg/irk/api/v1/me');
      curl_setopt($ch_me, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch_me, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch_me, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $row['metafyAccessToken'], 'Content-Type: application/json']);
      curl_setopt($ch_me, CURLOPT_USERAGENT, 'Talishar-App');
      $me_raw  = curl_exec($ch_me);
      $me_code = curl_getinfo($ch_me, CURLINFO_HTTP_CODE);
      curl_close($ch_me);
      if ($me_code === 200) {
        $me_data  = json_decode($me_raw, true);
        $metafyID = $me_data['user']['id'] ?? null;
        if (!empty($metafyID)) {
          $stmt_bf = mysqli_stmt_init($conn);
          if (mysqli_stmt_prepare($stmt_bf, 'UPDATE users SET metafyID=? WHERE usersId=?')) {
            mysqli_stmt_bind_param($stmt_bf, 'si', $metafyID, $row['usersId']);
            mysqli_stmt_execute($stmt_bf);
            mysqli_stmt_close($stmt_bf);
            $backfilled++;
          }
        }
      }
    }

    if (empty($metafyID) && !empty($subscriber_usernames)) {
      $talishar_username_lower = strtolower($row['usersUid']);
      if (isset($subscriber_usernames[$talishar_username_lower])) {
        $metafyID = $subscriber_usernames[$talishar_username_lower];
        $stmt_bf = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt_bf, 'UPDATE users SET metafyID=? WHERE usersId=?')) {
          mysqli_stmt_bind_param($stmt_bf, 'si', $metafyID, $row['usersId']);
          mysqli_stmt_execute($stmt_bf);
          mysqli_stmt_close($stmt_bf);
          $backfilled++;
        }
      }
    }

    if (empty($metafyID)) {
      $no_metafy_id++;
      $skipped_users[] = $row['usersUid'];
      continue;
    }
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
  "backfilled" => $backfilled,
  "clearedUsers" => $cleared_users,
  "skippedUsers" => $skipped_users
];

if ($api_error) {
  $response["apiWarning"] = $api_error;
}

echo json_encode($response);
