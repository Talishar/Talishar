<?php

include_once './AccountSessionAPI.php';
include_once '../APIKeys/APIKeys.php';
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/HTTPLibraries.php';
include_once '../Libraries/FriendLibraries.php';
include_once '../includes/MetafyHelper.php';
include_once '../Assets/patreon-php-master/src/OAuth.php';
include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';

CheckSession();

$response = new stdClass();

$client_id = $googleClientID ?? '';
$client_secret = $googleClientSecret ?? '';
$redirect_uri = GetGoogleRedirectUri($_GET['redirect_uri'] ?? '');

if ($client_id === '' || $client_secret === '') {
  $response->error = 'Google login is not configured';
}
else if (empty($_GET['code'])) {
  $response->error = 'No authorization code provided';
}
else {
  $tokens = ExchangeGoogleCode($_GET['code'], $client_id, $client_secret, $redirect_uri);
  $claims = isset($tokens['id_token']) ? ReadGoogleIdToken($tokens['id_token'], $client_id) : null;

  if ($claims === null) {
    $response->error = $tokens['error'] ?? 'Failed to verify Google account';
    if (isset($tokens['error_description'])) $response->error_description = $tokens['error_description'];
  }
  else {
    $result = FindOrCreateGoogleUser($claims);
    if (isset($result['error'])) {
      $response->error = $result['error'];
    }
    else {
      $row = $result['user'];
      session_regenerate_id(false);
      $_SESSION['userid'] = $row['usersId'];
      $_SESSION['useruid'] = $row['usersUid'];
      $_SESSION['useremail'] = $row['usersEmail'];
      $_SESSION['patreonEnum'] = $row['patreonEnum'];
      $_SESSION['isBanned'] = $row['isBanned'];
      $_SESSION['metafyID'] = $row['metafyID'] ?? '';
      $_SESSION['rust_counters'] = intval($row['rust_counters'] ?? 0);
      $_SESSION['displayName'] = $row['displayName'] ?? '';
      if (!empty($row['metafyCommunities'])
        && IsTalisharMetafySupporter(json_decode($row['metafyCommunities'], true))) {
        $_SESSION['isPatron'] = '1';
      }
      LogIPHistory($row['usersId']);

      if (!empty($row['patreonAccessToken'])) {
        try {
          PatreonLogin($row['patreonAccessToken']);
        } catch (\Throwable $e) {
          error_log('GoogleSignupAPI: PatreonLogin failed: ' . $e->getMessage());
        }
      }

      ApplyRememberMeCookie($row['usersId']);

      $response->message = 'ok';
      $response->isUserLoggedIn = true;
      $response->loggedInUserID = $row['usersId'];
      $response->loggedInUserName = $row['usersUid'];
      $response->isPatron = IsLoggedInUserPatron() ? '1' : '0';
      if (!empty($result['created'])) $response->created = true;
    }
  }
}

session_write_close();

SetHeaders();
echo json_encode($response);
exit;

function GetGoogleRedirectUri($requested)
{
  $allowed = [
    'https://talishar.net/auth/google-signup',
    'http://localhost:5173/auth/google-signup',
  ];
  $configured = trim((string)getenv('GOOGLE_LOGIN_REDIRECT_URI'));
  if ($configured !== '') array_unshift($allowed, $configured);
  return in_array($requested, $allowed, true) ? $requested : $allowed[0];
}

function ExchangeGoogleCode($code, $client_id, $client_secret, $redirect_uri)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
  ]));
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  $body = curl_exec($ch);
  curl_close($ch);
  $tokens = is_string($body) ? json_decode($body, true) : null;
  return is_array($tokens) ? $tokens : [];
}

function ReadGoogleIdToken($idToken, $client_id)
{
  $parts = explode('.', $idToken);
  if (count($parts) !== 3) return null;
  $payload = base64_decode(strtr($parts[1], '-_', '+/'), true);
  $claims = $payload === false ? null : json_decode($payload, true);
  if (!is_array($claims)) return null;
  if (!in_array($claims['iss'] ?? '', ['https://accounts.google.com', 'accounts.google.com'], true)) return null;
  if (($claims['aud'] ?? '') !== $client_id) return null;
  if (intval($claims['exp'] ?? 0) < time()) return null;
  if (empty($claims['sub'])) return null;
  return $claims;
}

function LoadGoogleUserRow($conn, $column, $value)
{
  $sql = "SELECT * FROM users WHERE $column=? LIMIT 1";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) return null;
  mysqli_stmt_bind_param($stmt, 's', $value);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  return $row ?: null;
}

function GoogleUsernameTaken($conn, $username)
{
  return LoadGoogleUserRow($conn, 'usersUid', $username) !== null || IsBannedPlayer($username);
}

function PickGoogleUsername($conn, $claims)
{
  $base = '';
  foreach (['given_name', 'name'] as $key) {
    $base = preg_replace('/[^A-Za-z0-9]/', '', (string)($claims[$key] ?? ''));
    if ($base !== '') break;
  }
  if ($base === '') $base = 'Player';
  $base = substr($base, 0, 16);

  if (!GoogleUsernameTaken($conn, $base)) return $base;
  for ($i = 0; $i < 20; $i++) {
    $candidate = $base . random_int(1000, 9999);
    if (!GoogleUsernameTaken($conn, $candidate)) return $candidate;
  }
  return $base . bin2hex(random_bytes(4));
}

function FindOrCreateGoogleUser($claims)
{
  $conn = GetDBConnection(DBL_GOOGLE_SIGNUP_API);
  if (!$conn) return ['error' => 'Database unavailable, please try again'];

  $googleID = (string)$claims['sub'];
  $email = (string)($claims['email'] ?? '');
  $emailVerified = ($claims['email_verified'] ?? false) === true || ($claims['email_verified'] ?? '') === 'true';

  try {
    $row = LoadGoogleUserRow($conn, 'googleID', $googleID);
    if ($row !== null) return ['user' => $row];

    $existing = $email !== '' ? LoadGoogleUserRow($conn, 'usersEmail', $email) : null;
    if ($existing !== null) {
      if (!$emailVerified) {
        return ['error' => 'An account with this email already exists. Log in with your username and password.'];
      }
      if (!empty($existing['googleID'])) {
        return ['error' => 'This Talishar account is already linked to a different Google account.'];
      }
      $stmt = mysqli_prepare($conn, 'UPDATE users SET googleID=? WHERE usersId=?');
      mysqli_stmt_bind_param($stmt, 'si', $googleID, $existing['usersId']);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      $existing['googleID'] = $googleID;
      return ['user' => $existing];
    }

    if (IsIPBanned()) {
      return ['error' => 'Unable to create an account at this time. Please try again later.'];
    }

    $username = PickGoogleUsername($conn, $claims);
    $hashedPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, 'INSERT INTO users (usersUid, usersEmail, usersPwd, googleID) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $username, $email, $hashedPassword, $googleID);
    mysqli_stmt_execute($stmt);
    $newID = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    $row = LoadGoogleUserRow($conn, 'usersId', (string)$newID);
    return $row !== null ? ['user' => $row, 'created' => true] : ['error' => 'Failed to create account'];
  } catch (\Throwable $e) {
    error_log('GoogleSignupAPI: ' . $e->getMessage());
    return ['error' => 'Failed to create or update user account'];
  } finally {
    mysqli_close($conn);
  }
}
