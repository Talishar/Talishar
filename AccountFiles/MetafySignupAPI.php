<?php

include_once './AccountSessionAPI.php';
include_once './AccountDatabaseAPI.php';
include_once '../Database/ConnectionManager.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';
include_once '../Libraries/FriendLibraries.php';
include_once '../includes/MetafyHelper.php';

CheckSession();

$response = new stdClass();

// Use the "Talishar Login" app credentials for signup/login flow
$client_id = $metafyLoginClientID ?? '';
$client_secret = $metafyLoginClientSecret ?? '';
$redirect_uri = 'https://talishar.net/auth/metafy-signup';

// Exchange the authorization code for tokens
if (isset($_GET['code']) && !empty($_GET['code'])) {
  $code = $_GET['code'];
  
  // Exchange the code for tokens using the correct Metafy endpoint
  $token_url = 'https://metafy.gg/irk/oauth/token';
  
  $post_fields = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri
  ];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $token_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
  ]);
  // Use HTTP Basic Authentication for client credentials (Metafy requirement)
  curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $token_response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);

  $tokens = json_decode($token_response, true);

  if (isset($tokens['access_token'])) {
    $access_token = $tokens['access_token'];
    $refresh_token = $tokens['refresh_token'] ?? '';

    // Fetch user profile from Metafy API to get email/username
    $user_profile = GetMetafyUserProfile($access_token);

    if ($user_profile && isset($user_profile['id'])) {
      // Create or find user account.
      // $access_token/$refresh_token are deliberately NOT passed on and NOT stored: they
      // come from the "Talishar Login" OAuth app and carry only the `profile` scope, so
      // they cannot read communities or purchases. Writing them over the account-linking
      // token (a different app, holding `community` and `purchases`) is what left paying
      // supporters unable to refresh their status. Only the Metafy user id is kept.
      $userID = CreateOrUpdateMetafyUser($user_profile);

      if ($userID) {
        // Log the user in. Regenerate the session ID once at login to prevent fixation,
        // before writing session data so the new ID carries it. Do NOT pass true (delete
        // old file): keeping it means a browser still using the old ID (e.g. if Set-Cookie
        // is stripped by a proxy) continues to work.
        session_regenerate_id(false);
        $_SESSION['userid'] = $userID;
        // Get the actual username from database (prefer existing username over Metafy username)
        $existingUsername = GetExistingUsername($userID);
        $_SESSION['useruid'] = $existingUsername ?? ($user_profile['username'] ?? $user_profile['email'] ?? $userID);
        $_SESSION['isPatron'] = CheckIfMetafySupporter($userID);
        $_SESSION['metafyID'] = $user_profile['id'] ?? '';
        $_SESSION['displayName'] = GetExistingDisplayName($userID) ?? '';

        ApplyRememberMeCookie($userID);

        $response->message = 'ok';
        $response->redirect = '/'; // React app home (legacy MainMenu.php no longer exists)
        $response->isUserLoggedIn = true;
        $response->loggedInUserID = $userID;
        $response->loggedInUserName = $_SESSION['useruid'];
        $response->isPatron = $_SESSION['isPatron'];
      }
      else {
        $response->error = 'Failed to create or update user account';
      }
    }
    else {
      $response->error = 'Failed to fetch user profile from Metafy';
    }
  }
  else {
    $error_msg = $tokens['error'] ?? 'Failed to get access token';
    $error_description = $tokens['error_description'] ?? 'No description';
    $response->error = $error_msg;
    $response->error_description = $error_description;
  }
}
else {
  $response->error = 'No authorization code provided';
}

session_write_close();

// Return JSON response for frontend to handle
SetHeaders();
echo json_encode($response);
exit;

/**
 * Fetch user profile from Metafy API
 */
function GetMetafyUserProfile($access_token)
{
  $url = 'https://metafy.gg/irk/api/v1/me';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ]);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl_error = curl_error($ch);
  curl_close($ch);

  if ($http_code === 200) {
    $profile = json_decode($response, true);
    // Metafy API returns user data nested under 'user' key
    if (isset($profile['user'])) {
      $user_data = $profile['user'];
      // Prefer 'name' field for proper capitalization, fall back to 'slug' if not available
      if (isset($user_data['name']) && !empty($user_data['name'])) {
        $user_data['username'] = $user_data['name'];
      }
      elseif (isset($user_data['slug']) && !isset($user_data['username'])) {
        $user_data['username'] = $user_data['slug'];
      }
      return $user_data;
    }
    else {
      return null;
    }
  }

  return null;
}

/**
 * Create a new user account or update existing Metafy user
 */
function CreateOrUpdateMetafyUser($user_profile)
{
  $conn = GetDBConnection(DBL_METAFY_SIGNUP_API);

  // Try to find existing user by email
  $email = $user_profile['email'] ?? '';
  $username = $user_profile['username'] ?? 'metafy_user_' . substr($user_profile['id'], 0, 8);
  $metafy_id = $user_profile['id'] ?? '';

  // Check if user exists by email
  if (!empty($email)) {
    $sql = "SELECT usersid FROM users WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 's', $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);

      if ($row) {
        // User exists: record the Metafy id, leave any linked OAuth tokens alone.
        $userID = $row['usersid'];
        UpdateMetafyUserId($conn, $userID, $metafy_id);
        RefreshLinkedMetafyStatus($conn, $userID);
        mysqli_close($conn);
        return $userID;
      }
    }
  }

  // Check if username already exists, generate unique one if needed
  $base_username = $username;
  $counter = 1;
  while (UsernameExists($username, $conn) || IsBannedPlayer($username)) {
    $username = $base_username . $counter;
    $counter++;
  }

  // Create new user account
  $hashedPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (usersUid, usersEmail, usersPwd, metafyID)
          VALUES (?, ?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssss', $username, $email, $hashedPassword, $metafy_id);

    if (mysqli_stmt_execute($stmt)) {
      $userID = mysqli_insert_id($conn);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $userID;
    }
    else {
      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($conn);
  return null;
}

function RefreshLinkedMetafyStatus($conn, $userID)
{
  $auth = MetafyLoadAuth($conn, $userID);
  if ($auth === null || empty($auth['access_token'])) return;
  MetafyQuickSupporterCheck($conn, $auth);
}

function UsernameExists($username, $conn)
{
  $sql = "SELECT usersid FROM users WHERE usersUid=?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ? true : false;
  }
  return false;
}

function UpdateMetafyUserId($conn, $userID, $metafy_id)
{
  if (empty($metafy_id)) return;
  $sql = "UPDATE users SET metafyID=? WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $metafy_id, $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

function CheckIfMetafySupporter($userID)
{
  $conn = GetDBConnection(DBL_METAFY_SIGNUP_API);
  $sql = "SELECT metafyCommunities FROM users WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);
  
  $isSupporter = false;
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && !empty($row['metafyCommunities'])) {
      $isSupporter = IsTalisharMetafySupporter(json_decode($row['metafyCommunities'], true));
    }
  }

  mysqli_close($conn);
  return $isSupporter ? "1" : "0";
}

function GetExistingUsername($userID)
{
  $conn = GetDBConnection(DBL_METAFY_SIGNUP_API);
  $sql = "SELECT usersUid FROM users WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($row && isset($row['usersUid'])) {
      mysqli_close($conn);
      return $row['usersUid'];
    }
  }

  mysqli_close($conn);
  return null;
}

function GetExistingDisplayName($userID)
{
  $conn = GetDBConnection(DBL_METAFY_SIGNUP_API);
  $sql = "SELECT displayName FROM users WHERE usersid=?";
  $stmt = mysqli_stmt_init($conn);

  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && isset($row['displayName'])) {
      mysqli_close($conn);
      return $row['displayName'];
    }
  }

  mysqli_close($conn);
  return null;
}


