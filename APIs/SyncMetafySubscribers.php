<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../APIKeys/APIKeys.php";
include_once "../includes/MetafyHelper.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
header('Content-Type: application/json');

if (!isset($_SESSION["useruid"])) {
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$useruid = $_SESSION["useruid"];
session_write_close();

set_time_limit(300);

include_once '../includes/ModeratorList.inc.php';
if (!IsUserModerator($useruid)) {
  echo json_encode(["error" => "Not authorized"]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["error" => "Method not allowed"]);
  exit;
}

// When true, users who cannot be matched against the roster at all are revoked
// instead of skipped. Email matching makes this rarely necessary.
$input = json_decode(file_get_contents('php://input'), true);
$clear_unmatched = !empty($input['clearNoMetafyId']);

$conn = GetDBConnection(DBL_SYNC_METAFY_SUBSCRIBERS);
if (!$conn) {
  echo json_encode(["error" => "DB connection failed"]);
  exit;
}

$modAuth = MetafyLoadAuth($conn, null, $useruid);
if ($modAuth === null || empty($modAuth['access_token'])) {
  mysqli_close($conn);
  echo json_encode([
    "error" => "Could not fetch any subscribers from Metafy. Sync aborted to avoid clearing valid supporters.",
    "apiError" => "No Metafy account linked to this moderator. Connect your Metafy account via the profile page.",
  ]);
  exit;
}

$subscriber_ids    = [];
$subscriber_emails = [];
$api_error         = null;
$api_source        = '';
$page              = 1;
$max_pages         = 50;

while ($page <= $max_pages) {
  $res = MetafyAuthedGet($modAuth, $conn, '/v1/me/community/subscribers?per_page=100&page=' . $page, 15);

  if ($res['code'] === 401 || $res['code'] === 403) {
    $api_error = $res['code'] === 403
      ? "Metafy returned HTTP 403: the linked account is missing the `community` scope, or does not own the Talishar community."
      : "Metafy returned HTTP 401 and the token could not be renewed (please re-link your Metafy account on the profile page).";
    break;
  }
  if ($res['code'] !== 200 || empty($res['body'])) {
    $api_error = "Metafy returned HTTP " . $res['code'];
    break;
  }

  $data        = json_decode($res['body'], true);
  $subscribers = $data['subscribers'] ?? null;
  if (!is_array($subscribers)) {
    $api_error = "Metafy returned 200 but no subscribers array. Response keys: ["
      . implode(', ', array_keys(is_array($data) ? $data : [])) . "]";
    break;
  }

  foreach ($subscribers as $sub) {
    $uid = $sub['user_id'] ?? $sub['id'] ?? null;
    if ($uid) $subscriber_ids[$uid] = true;
    $email = strtolower(trim($sub['email'] ?? ''));
    if ($email !== '') $subscriber_emails[$email] = true;
  }

  $api_source = '/v1/me/community/subscribers';
  $total_pages = intval($data['meta']['pagination']['total_pages'] ?? 1);
  if ($page >= $total_pages) break;
  $page++;
}

// Safety: never reconcile against an empty roster, that would revoke everyone.
if (empty($subscriber_ids) && empty($subscriber_emails)) {
  mysqli_close($conn);
  echo json_encode([
    "error" => "Could not fetch any subscribers from Metafy. Sync aborted to avoid clearing valid supporters.",
    "apiError" => $api_error ?? 'No subscribers returned.',
  ]);
  exit;
}

// Only accounts that have some Metafy connection can be reconciled.
$sql = "SELECT usersId, usersUid, usersEmail, metafyID, metafyCommunities
        FROM users
        WHERE (metafyID IS NOT NULL AND metafyID != '')
           OR (metafyCommunities IS NOT NULL AND metafyCommunities != '' AND metafyCommunities != '[]')";
$result = mysqli_query($conn, $sql);

$checked        = 0;
$granted        = 0;
$still_active   = 0;
$cleared        = 0;
$unmatched      = 0;
$granted_users  = [];
$cleared_users  = [];
$skipped_users  = [];

while ($row = mysqli_fetch_assoc($result)) {
  $communities  = json_decode($row['metafyCommunities'] ?? '', true);
  if (!is_array($communities)) $communities = [];
  $wasSupporter = IsTalisharMetafySupporter($communities);

  $metafyID = $row['metafyID'] ?? '';
  $email    = strtolower(trim($row['usersEmail'] ?? ''));

  $matchedById    = $metafyID !== '' && isset($subscriber_ids[$metafyID]);
  $matchedByEmail = $email !== ''    && isset($subscriber_emails[$email]);
  $isSubscriber   = $matchedById || $matchedByEmail;

  // Only a known metafyID makes a *negative* result meaningful: a Talishar email that
  // does not appear in the roster may simply differ from the one used on Metafy, which
  // is not evidence that the subscription lapsed. Email is a positive match only.
  if (!$isSubscriber && $metafyID === '') {
    $unmatched++;
    if (!$clear_unmatched) {
      if ($wasSupporter) $skipped_users[] = $row['usersUid'];
      continue;
    }
  }

  $checked++;

  if ($isSubscriber === $wasSupporter) {
    if ($wasSupporter) $still_active++;
    continue;
  }

  $updated = MetafyApplyTalisharAccess($communities, $isSubscriber);
  if (!MetafySaveCommunities($conn, $row['usersId'], $updated)) continue;

  if ($isSubscriber) {
    $granted++;
    $granted_users[] = $row['usersUid'];
  } else {
    $cleared++;
    $cleared_users[] = $row['usersUid'];
  }
}

mysqli_free_result($result);
mysqli_close($conn);

$response = [
  "message"            => "Sync complete",
  "apiSource"          => $api_source,
  "subscribersFetched" => count($subscriber_ids),
  "subscriberEmails"   => count($subscriber_emails),
  "usersChecked"       => $checked,
  "stillActive"        => $still_active,
  "granted"            => $granted,
  "cleared"            => $cleared,
  "unmatched"          => $unmatched,
  "forcedUnmatchedClear" => $clear_unmatched,
  "grantedUsers"       => $granted_users,
  "clearedUsers"       => $cleared_users,
  "skippedUsers"       => $skipped_users
];

if ($api_error) {
  $response["apiWarning"] = $api_error;
}

echo json_encode($response);
