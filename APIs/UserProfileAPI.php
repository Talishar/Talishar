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
include_once "../CardDictionary.php";
include_once "../Libraries/UILibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../includes/ModeratorList.inc.php";
include_once "../includes/MetafyHelper.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$userName = LoggedInUserName();

$response = new stdClass();

$response->userName = $userName;

$response->patreonInfo = PatreonLink();
$response->isPatreonLinked = isset($_SESSION["patreonAuthenticated"]);
$response->isPatreonSupporter = IsLoggedInUserPatron() == true;

$response->isContributor = IsUserContributor($userName);
$response->isPvtVoidPatron = $userName == "PvtVoid" || isset($_SESSION["isPvtVoidPatron"]);

// Get Metafy info from database
$conn = GetDBConnection(DBL_USER_PROFILE_API);
if ($conn === false) {
  $response->rustCounters = 0;
  $response->isMetafyLinked = false;
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = [];
  $response->isMetafySupporter = false;
  $response->metafyNeedsReauth = false;
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}
$sql = "SELECT metafyAccessToken, metafyCommunities, metafyID, usersId, rust_counters,
               (rust_counters > 0 AND (
                 rust_counters_last_played IS NULL OR
                 rust_counters_last_played <= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 7 DAY)
               )) AS rust_counters_expired,
               displayName, lastNameChange
        FROM users WHERE usersUid=?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, 's', $userName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);

  $response->displayName = ($row['displayName'] ?? "") != "" ? $row['displayName'] : $userName;
  $response->hasCustomDisplayName = ($row['displayName'] ?? "") != "";
  $lastNameChange = $row['lastNameChange'] ?? null;
  $nextChangeTime = $lastNameChange !== null ? strtotime($lastNameChange) + 7 * 86400 : 0;
  $response->nextChangeAllowed = $nextChangeTime > time() ? date("c", $nextChangeTime) : null;

  $metafyAccessToken = $row['metafyAccessToken'] ?? null;
  $rustCountersExpired = intval($row['rust_counters_expired'] ?? 0) === 1;
  $response->rustCounters = $rustCountersExpired ? 0 : intval($row['rust_counters'] ?? 0);
  if ($rustCountersExpired) {
    $expireStmt = mysqli_stmt_init($conn);
    $expireSql = "UPDATE users SET rust_counters = 0
                  WHERE usersUid=? AND rust_counters > 0 AND (
                    rust_counters_last_played IS NULL OR
                    rust_counters_last_played <= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 7 DAY)
                  )";
    if (mysqli_stmt_prepare($expireStmt, $expireSql)) {
      mysqli_stmt_bind_param($expireStmt, 's', $userName);
      mysqli_stmt_execute($expireStmt);
      mysqli_stmt_close($expireStmt);
    }
    $_SESSION['rust_counters'] = 0;
  }
  $response->isMetafyLinked = !empty($metafyAccessToken);
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = isset($row['metafyCommunities']) ? json_decode($row['metafyCommunities'], true) : [];
  if (!is_array($response->metafyCommunities)) $response->metafyCommunities = [];
  $response->metafyNeedsReauth = false;

  if (!empty($metafyAccessToken)) {
    // Release the session lock before talking to Metafy. Everything still needed from
    // the session has been read, and holding the lock across a network call would block
    // every other request this user makes until Metafy answers.
    session_write_close();

    $auth = MetafyLoadAuth($conn, $row['usersId'] ?? null);
    if ($auth !== null) {
      // A subscription started since the last check is the whole complaint: someone pays
      // on Metafy and Talishar still shows them as free. One throttled request per user
      // settles it here, so nobody has to know the refresh link exists.
      MetafyQuickSupporterCheck($conn, $auth);
      $response->metafyCommunities = $auth['communities'];

      // A token issued by the "Sign in with Metafy" app cannot read subscriptions.
      // Surface that so the UI can ask the user to re-connect rather than showing
      // a linked account that will never resolve to supporter status. Recorded scopes
      // catch it up front; a 401/403 during the check catches tokens predating them.
      $response->metafyNeedsReauth = !MetafyScopesAreSufficient($auth['scopes'] ?? null)
        || !empty($auth['needs_reauth']);
    }
  }

  $response->isMetafySupporter = IsTalisharMetafySupporter($response->metafyCommunities);
}
else {
  $response->rustCounters = 0;
  $response->isMetafyLinked = false;
  $response->metafyInfo = MetafyLink();
  $response->metafyCommunities = [];
  $response->isMetafySupporter = false;
  $response->metafyNeedsReauth = false;
}

mysqli_close($conn);
if (session_status() === PHP_SESSION_ACTIVE) session_write_close();

if (!isset($response->displayName)) {
  $response->displayName = $userName;
  $response->hasCustomDisplayName = false;
  $response->nextChangeAllowed = null;
}
// Display name changes are a supporter perk (Patreon or Metafy Talishar supporter)
$response->canChangeDisplayName = ($response->isPatreonSupporter || $response->isMetafySupporter || $response->isPvtVoidPatron) == true;

header('Content-Type: application/json');
echo json_encode($response);
exit;

function PatreonLink()
{
  global $patreonClientID, $patreonClientSecret;
  if (empty($patreonClientID) || empty($patreonClientSecret)) {
    return null;
  }
  $client_id = $patreonClientID;
  $client_secret = $patreonClientSecret;

  //$redirect_uri = "https://www.talishar.net/game/PatreonLogin.php";
  $redirect_uri = "https://legacy.talishar.net/game/PatreonLogin.php";
  $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);
  $state = [];
  $state['final_page'] = 'http://fleshandbloodonline.com/FaBOnline/MainMenu.php';
  $state_parameters = '&state=' . urlencode(base64_encode(json_encode($state)));
  $href .= $state_parameters;
  $scope_parameters = '&scope=identity%20identity.memberships';
  $href .= $scope_parameters;
  return $href;
}

// MetafyLink() lives in includes/MetafyHelper.php so the profile page and MetafyAPI
// cannot drift apart on redirect URI or scopes.

