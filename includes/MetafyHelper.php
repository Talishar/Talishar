<?php

/**
 * MetafyHelper.php - Helper functions for Metafy OAuth + community tier integration
 * Note: This file should be included after APIKeys.php and dbh.inc.php
 *
 * Metafy access tokens expire two hours after they are issued (`expires_in: 7200`),
 * so any call made with a stored token has to be ready to refresh it. Every request
 * made on a user's behalf goes through MetafyAuthedGet(), which refreshes and retries
 * in one place : hand-rolled curl calls against a stored token will start failing a
 * couple of hours after the user links their account.
 *
 * API surface (https://dev.metafy.gg/api-reference/v1):
 *   GET /v1/me                                    scope: profile
 *   GET /v1/me/community                          scope: community
 *   GET /v1/me/community/memberships              scope: community
 *   GET /v1/me/purchases/communities/{id}         scope: purchases
 */

if (!defined('METAFY_API_BASE')) {
  define('METAFY_API_BASE', 'https://metafy.gg/irk/api');
  define('METAFY_TOKEN_URL', 'https://metafy.gg/irk/oauth/token');
  define('METAFY_TALISHAR_COMMUNITY_ID', 'be5e01c0-02d1-4080-b601-c056d69b03f6');
  // Scopes the account-linking OAuth app requests. Supporter detection needs
  // `community` (membership list) and `purchases` (active subscription check).
  define('METAFY_OAUTH_SCOPES', 'profile community products purchases');
  // Refresh this many seconds before the recorded expiry, so a token cannot lapse
  // mid-request between our check and Metafy's.
  define('METAFY_TOKEN_EXPIRY_BUFFER', 120);
}

/**
 * Build the OAuth authorization URL for linking a Metafy account to a Talishar account.
 *
 * This is the app that must be used for anything supporter-related: it requests the
 * `community` and `purchases` scopes. The separate "Sign in with Metafy" app only asks
 * for `profile` and its tokens can never answer whether someone is subscribed.
 */
if (!function_exists('MetafyLink')) {
  function MetafyLink()
  {
    global $metafyClientID;
    if (empty($metafyClientID)) return null;

    // Check environment variable first, then fall back to detecting by host
    $metafy_dev_mode = getenv('METAFY_DEV_MODE');
    $use_dev = $metafy_dev_mode === 'true' || $metafy_dev_mode === '1';
    if (!$use_dev) {
      $host = $_SERVER['HTTP_HOST'] ?? '';
      $use_dev = $host === 'localhost' || $host === 'localhost:8000' || strpos($host, '127.0.0.1') !== false;
    }

    $redirect_uri = $use_dev
      ? 'http://localhost:5173/user/profile/linkmetafy'
      : 'https://talishar.net/user/profile/linkmetafy';

    $state = base64_encode(json_encode(['redirect_uri' => $redirect_uri]));

    // rawurlencode so the scope separator is %20, as the Metafy OAuth docs specify.
    return 'https://metafy.gg/auth/authorize?' .
      'response_type=code' .
      '&client_id=' . rawurlencode($metafyClientID) .
      '&redirect_uri=' . rawurlencode($redirect_uri) .
      '&scope=' . rawurlencode(METAFY_OAUTH_SCOPES) .
      '&state=' . rawurlencode($state);
  }
}

if (!function_exists('IsDevEnvironment')) {
  function IsDevEnvironment() {
    $domain = getenv("DOMAIN");
    if ($domain === "localhost") return true;
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') return true;
    return false;
  }
}

/**
 * GET a Metafy API endpoint with a raw access token.
 * Retries transient failures (connection error / timeout / 5xx) so a single blip
 * does not end up persisted as "this user has nothing".
 * Returns ['code' => int, 'body' => string]. Code 0 means the request never completed.
 */
if (!function_exists('MetafyApiGet')) {
  function MetafyApiGet($url, $accessToken, $timeout = 8, $retries = 1)
  {
    $code = 0;
    $body = '';

    for ($attempt = 0; $attempt <= $retries; $attempt++) {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
      ]);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
      $body = curl_exec($ch);
      $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($body === false) $body = '';

      $isTransient = ($code === 0 || $code >= 500);
      if (!$isTransient) break;
      if ($attempt < $retries) usleep(300000); // 300ms before retrying
    }

    return ['code' => $code, 'body' => $body];
  }
}

/**
 * Exchange a refresh token for a fresh access token.
 * Returns the decoded token response, or null if the refresh token is dead.
 */
if (!function_exists('MetafyOAuthRefresh')) {
  function MetafyOAuthRefresh($refreshToken)
  {
    global $metafyClientID, $metafyClientSecret;
    if (empty($refreshToken) || empty($metafyClientID) || empty($metafyClientSecret)) return null;

    $ch = curl_init(METAFY_TOKEN_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      'grant_type'    => 'refresh_token',
      'refresh_token' => $refreshToken,
      'client_id'     => $metafyClientID,
      'client_secret' => $metafyClientSecret,
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Talishar-App');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200 || empty($raw)) return null;

    $tokens = json_decode($raw, true);
    if (!is_array($tokens) || empty($tokens['access_token'])) return null;
    return $tokens;
  }
}

/**
 * The expiry/scope columns arrived in migration 016. Tolerate their absence so a PHP
 * deploy that lands before the migration degrades instead of breaking the profile page.
 */
if (!function_exists('MetafyHasTokenMetaColumns')) {
  function MetafyHasTokenMetaColumns($conn)
  {
    static $hasColumns = null;
    if ($hasColumns !== null) return $hasColumns;
    $hasColumns = false;
    $res = @mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'metafyTokenExpires'");
    if ($res) {
      $hasColumns = mysqli_num_rows($res) > 0;
      mysqli_free_result($res);
    }
    return $hasColumns;
  }
}

/**
 * Load a user's Metafy auth state. Look up by numeric usersId or by username.
 * Returns null if the user does not exist.
 *
 * The returned array is passed by reference through MetafyAuthedGet()/MetafySyncCommunities()
 * so a refreshed token is visible to every later call in the same request.
 */
if (!function_exists('MetafyLoadAuth')) {
  function MetafyLoadAuth($conn, $usersId = null, $userName = null)
  {
    if (!$conn) return null;

    $columns = 'usersId, metafyAccessToken, metafyRefreshToken, metafyCommunities, metafyID';
    if (MetafyHasTokenMetaColumns($conn)) {
      $columns .= ', metafyTokenExpires, metafyScopes, metafyLastSync';
    }

    if ($usersId !== null && $usersId !== '') {
      $sql   = "SELECT $columns FROM users WHERE usersId=? LIMIT 1";
      $param = $usersId;
    } elseif ($userName !== null && $userName !== '') {
      $sql   = "SELECT $columns FROM users WHERE usersUid=? LIMIT 1";
      $param = $userName;
    } else {
      return null;
    }

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) return null;
    mysqli_stmt_bind_param($stmt, 's', $param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    if (!$row) return null;

    $communities = json_decode($row['metafyCommunities'] ?? '', true);
    $expiresRaw  = $row['metafyTokenExpires'] ?? null;

    return [
      'usersId'       => $row['usersId'],
      'access_token'  => $row['metafyAccessToken']  ?? '',
      'refresh_token' => $row['metafyRefreshToken'] ?? '',
      'expires_at'    => !empty($expiresRaw) ? strtotime($expiresRaw) : null,
      'scopes'        => $row['metafyScopes'] ?? null,
      'metafy_id'     => $row['metafyID'] ?? null,
      'communities'   => is_array($communities) ? $communities : [],
      'last_sync'     => !empty($row['metafyLastSync']) ? strtotime($row['metafyLastSync']) : null,
      'refresh_failed' => false,
    ];
  }
}

/**
 * Persist a token response from the OAuth token endpoint.
 * Metafy returns a rotated refresh token; dropping it would strand the account after
 * the next expiry, so keep the old one only when the response omits it.
 */
if (!function_exists('MetafySaveTokens')) {
  function MetafySaveTokens($conn, $usersId, $tokens, $fallbackRefreshToken = '')
  {
    if (!$conn || empty($usersId) || empty($tokens['access_token'])) return false;

    $accessToken  = $tokens['access_token'];
    $refreshToken = $tokens['refresh_token'] ?? $fallbackRefreshToken;
    $expiresIn    = isset($tokens['expires_in']) ? intval($tokens['expires_in']) : 0;
    $scopes       = $tokens['scope'] ?? null;

    if (MetafyHasTokenMetaColumns($conn)) {
      $expiresAt = $expiresIn > 0 ? date('Y-m-d H:i:s', time() + $expiresIn) : null;
      $sql  = 'UPDATE users SET metafyAccessToken=?, metafyRefreshToken=?, metafyTokenExpires=?, metafyScopes=? WHERE usersId=?';
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) return false;
      mysqli_stmt_bind_param($stmt, 'sssss', $accessToken, $refreshToken, $expiresAt, $scopes, $usersId);
    } else {
      $sql  = 'UPDATE users SET metafyAccessToken=?, metafyRefreshToken=? WHERE usersId=?';
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) return false;
      mysqli_stmt_bind_param($stmt, 'sss', $accessToken, $refreshToken, $usersId);
    }

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
  }
}

/**
 * Refresh the access token in $auth and write it back to the DB.
 * Only ever attempts once per request: a refresh token Metafy has rejected will not
 * start working again a few milliseconds later.
 */
if (!function_exists('MetafyTryRefresh')) {
  function MetafyTryRefresh(&$auth, $conn)
  {
    if (!empty($auth['refresh_failed'])) return false;
    if (empty($auth['refresh_token'])) {
      $auth['refresh_failed'] = true;
      return false;
    }

    $tokens = MetafyOAuthRefresh($auth['refresh_token']);
    if ($tokens === null) {
      $auth['refresh_failed'] = true;
      return false;
    }

    MetafySaveTokens($conn, $auth['usersId'], $tokens, $auth['refresh_token']);

    $auth['access_token']  = $tokens['access_token'];
    $auth['refresh_token'] = $tokens['refresh_token'] ?? $auth['refresh_token'];
    $auth['expires_at']    = isset($tokens['expires_in'])
      ? time() + intval($tokens['expires_in'])
      : null;
    if (!empty($tokens['scope'])) $auth['scopes'] = $tokens['scope'];

    return true;
  }
}

/**
 * GET a Metafy API path on the user's behalf, refreshing the access token when needed.
 * $path is relative to METAFY_API_BASE, e.g. '/v1/me/community/memberships'.
 *
 * Returns ['code' => int, 'body' => string]. A 401 that survives a refresh means the
 * user has to re-authorize; a 403 means the token is valid but lacks the scope.
 */
if (!function_exists('MetafyAuthedGet')) {
  function MetafyAuthedGet(&$auth, $conn, $path, $timeout = 8)
  {
    if (empty($auth['access_token'])) return ['code' => 401, 'body' => ''];

    // Refresh ahead of a known expiry rather than burning a request on a guaranteed 401.
    if (!empty($auth['expires_at']) && $auth['expires_at'] - METAFY_TOKEN_EXPIRY_BUFFER <= time()) {
      MetafyTryRefresh($auth, $conn);
    }

    $result = MetafyApiGet(METAFY_API_BASE . $path, $auth['access_token'], $timeout);

    // Unknown expiry (pre-migration tokens) or Metafy revoked early: refresh and retry once.
    if ($result['code'] === 401 && MetafyTryRefresh($auth, $conn)) {
      $result = MetafyApiGet(METAFY_API_BASE . $path, $auth['access_token'], $timeout);
    }

    return $result;
  }
}

/**
 * Resolve and cache the user's Metafy user id. Returns the id or null.
 */
if (!function_exists('MetafyEnsureUserId')) {
  function MetafyEnsureUserId(&$auth, $conn)
  {
    if (!empty($auth['metafy_id'])) return $auth['metafy_id'];

    $me = MetafyAuthedGet($auth, $conn, '/v1/me', 5);
    if ($me['code'] !== 200 || empty($me['body'])) return null;

    $data     = json_decode($me['body'], true);
    $metafyId = $data['user']['id'] ?? null;
    if (empty($metafyId)) return null;

    $auth['metafy_id'] = $metafyId;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, 'UPDATE users SET metafyID=? WHERE usersId=?')) {
      mysqli_stmt_bind_param($stmt, 'ss', $metafyId, $auth['usersId']);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
    return $metafyId;
  }
}

/**
 * Keys a stored community list by community id, so previously known values can be
 * used as a fallback when a follow-up Metafy call fails.
 */
if (!function_exists('IndexMetafyCommunitiesById')) {
  function IndexMetafyCommunitiesById($communities)
  {
    $indexed = [];
    if (!is_array($communities)) return $indexed;
    foreach ($communities as $community) {
      if (!is_array($community)) continue;
      $id = $community['id'] ?? null;
      if ($id) $indexed[$id] = $community;
    }
    return $indexed;
  }
}

/**
 * Build a tier_id => tier name lookup from a community object's `tiers` array,
 * falling back to what we stored for that community on a previous sync.
 */
if (!function_exists('MetafyBuildTierMap')) {
  function MetafyBuildTierMap($community, $storedCommunity = null)
  {
    $map = [];
    foreach (($community['tiers'] ?? []) as $tier) {
      if (!empty($tier['id']) && !empty($tier['name'])) $map[$tier['id']] = $tier['name'];
    }
    if (empty($map) && is_array($storedCommunity)) {
      foreach (($storedCommunity['tiers'] ?? []) as $tier) {
        if (!empty($tier['id']) && !empty($tier['name'])) $map[$tier['id']] = $tier['name'];
      }
    }
    return $map;
  }
}

/**
 * Check whether the user holds an active paid subscription to a community.
 * Returns ['ok' => bool, 'has_access' => bool, 'tier_id' => ?string].
 * `ok` is false when the call could not be completed, which says nothing about
 * whether the subscription exists : callers must not treat that as "cancelled".
 */
if (!function_exists('MetafyCheckSubscription')) {
  function MetafyCheckSubscription(&$auth, $conn, $communityId)
  {
    $result = MetafyAuthedGet($auth, $conn, '/v1/me/purchases/communities/' . urlencode($communityId));
    $code   = $result['code'];

    // A token that survived a refresh and still cannot read purchases is under-scoped.
    // Record it so read paths can prompt for a re-link instead of failing silently.
    if ($code === 401 || $code === 403) $auth['needs_reauth'] = true;

    // 404 is a definitive "no active subscription", not a failure.
    if ($code === 404) {
      return ['ok' => true, 'has_access' => false, 'tier_id' => null, 'code' => $code];
    }
    if ($code !== 200 || empty($result['body'])) {
      return ['ok' => false, 'has_access' => false, 'tier_id' => null, 'code' => $code];
    }

    $data = json_decode($result['body'], true);
    if (!is_array($data) || !isset($data['community'])) {
      return ['ok' => false, 'has_access' => false, 'tier_id' => null, 'code' => $code];
    }

    return [
      'ok'         => true,
      'has_access' => !empty($data['community']['has_access']),
      'tier_id'    => $data['community']['tier_id'] ?? null,
      'code'       => $code,
    ];
  }
}

/**
 * Rebuild a user's community list from Metafy and persist it.
 *
 * Returns:
 *   ok           - the authoritative membership list was fetched; the stored list is current
 *   communities  - the list as it now stands (unchanged from storage when ok is false)
 *   isSupporter  - whether the Talishar community is in that list
 *   needsReauth  - the stored token is dead or under-scoped; the user must re-link
 *   error        - 'no_access_token' | 'token_expired' | 'missing_scope' | 'metafy_unavailable'
 *
 * The Talishar community is checked directly against the purchases endpoint rather than
 * only when it shows up under memberships. A paid subscriber who is not listed as a
 * community *member* still has an active subscription, and previously that user was
 * never recognised as a supporter no matter how many times they hit refresh.
 */
if (!function_exists('MetafySyncCommunities')) {
  function MetafySyncCommunities($conn, &$auth)
  {
    $stored      = is_array($auth['communities'] ?? null) ? $auth['communities'] : [];
    $storedById  = IndexMetafyCommunitiesById($stored);
    $result = [
      'ok'          => false,
      'communities' => $stored,
      'isSupporter' => IsTalisharMetafySupporter($stored),
      'needsReauth' => false,
      'error'       => null,
    ];

    if (empty($auth['access_token'])) {
      $result['needsReauth'] = true;
      $result['error']       = 'no_access_token';
      return $result;
    }

    $all = [];

    // --- Community owned by this account (coaches/creators) ---
    $owned = MetafyAuthedGet($auth, $conn, '/v1/me/community');
    if ($owned['code'] === 401 || $owned['code'] === 403) {
      // Without the community scope nothing below can succeed. Keep what we already
      // knew and tell the caller to send the user back through OAuth.
      $result['needsReauth'] = true;
      $result['error']       = $owned['code'] === 403 ? 'missing_scope' : 'token_expired';
      return $result;
    }
    if ($owned['code'] === 200 && !empty($owned['body'])) {
      $ownedData = json_decode($owned['body'], true);
      if (isset($ownedData['community'])) {
        $all[] = [
          'id'          => $ownedData['community']['id'] ?? null,
          'title'       => $ownedData['community']['title'] ?? null,
          'description' => $ownedData['community']['description'] ?? null,
          'logo_url'    => $ownedData['community']['logo_url'] ?? null,
          'cover_url'   => $ownedData['community']['cover_url'] ?? null,
          'url'         => $ownedData['community']['url'] ?? null,
          'tiers'       => $ownedData['community']['tiers'] ?? [],
          'type'        => 'owned'
        ];
      }
    } elseif ($owned['code'] !== 404) {
      // Call failed outright (404 legitimately means "owns no community").
      foreach ($storedById as $storedCommunity) {
        if (($storedCommunity['type'] ?? null) === 'owned') $all[] = $storedCommunity;
      }
    }

    // --- Joined communities, across every page ---
    $memberships   = [];
    $membershipsOk = false;
    $page          = 1;
    $maxPages      = 20;

    while ($page <= $maxPages) {
      $res = MetafyAuthedGet($auth, $conn, '/v1/me/community/memberships?per_page=100&page=' . $page);
      if ($res['code'] === 401 || $res['code'] === 403) {
        $result['needsReauth'] = true;
        $result['error']       = $res['code'] === 403 ? 'missing_scope' : 'token_expired';
        return $result;
      }
      if ($res['code'] !== 200 || empty($res['body'])) break;

      $data = json_decode($res['body'], true);
      if (!is_array($data) || !isset($data['communities'])) break;

      foreach ($data['communities'] as $community) {
        if (!empty($community['id'])) $memberships[] = $community;
      }

      // Only a run that reaches the last page is complete. A page that fails halfway
      // through would otherwise look like "these are all their communities" and drop
      // everything after it.
      $totalPages = intval($data['meta']['pagination']['total_pages'] ?? 1);
      if ($page >= $totalPages) {
        $membershipsOk = true;
        break;
      }
      $page++;
    }

    // The membership list is the only source for which communities this user belongs to.
    // If it did not come back cleanly, nothing may be persisted: writing a short list
    // here is exactly what silently strips people of supporter status.
    if (!$membershipsOk) {
      $result['error'] = 'metafy_unavailable';
      return $result;
    }

    $seenSet = array_fill_keys(array_filter(array_column($all, 'id')), true);

    foreach ($memberships as $community) {
      $communityId = $community['id'];
      if (isset($seenSet[$communityId])) continue;
      $seenSet[$communityId] = true;

      $storedCommunity = $storedById[$communityId] ?? null;
      $tierMap         = MetafyBuildTierMap($community, $storedCommunity);

      $info = [
        'id'          => $communityId,
        'title'       => $community['title'] ?? null,
        'description' => $community['description'] ?? null,
        'logo_url'    => $community['logo_url'] ?? null,
        'cover_url'   => $community['cover_url'] ?? null,
        'url'         => $community['url'] ?? null,
        // Kept so a later sync can still name the user's tier even if this
        // community stops appearing in the membership list.
        'tiers'       => $community['tiers'] ?? ($storedCommunity['tiers'] ?? []),
        'type'        => 'supported'
      ];

      $subscription = MetafyCheckSubscription($auth, $conn, $communityId);
      if ($subscription['ok']) {
        $info['has_access'] = $subscription['has_access'];
        if ($subscription['has_access'] && $subscription['tier_id'] && isset($tierMap[$subscription['tier_id']])) {
          $info['metafy_tier'] = $tierMap[$subscription['tier_id']];
        } elseif ($subscription['has_access'] && !empty($storedCommunity['metafy_tier'])) {
          // Subscribed, but the tier id is one we cannot name yet : keep the last known name.
          $info['metafy_tier'] = $storedCommunity['metafy_tier'];
        }
      } else {
        // The check failed, so it says nothing about whether they still have access.
        // Keep what we already knew instead of dropping their perks.
        if (isset($storedCommunity['has_access']))  $info['has_access']  = $storedCommunity['has_access'];
        if (!empty($storedCommunity['metafy_tier'])) $info['metafy_tier'] = $storedCommunity['metafy_tier'];
      }

      $all[] = $info;
    }

    // --- Talishar subscription, checked directly ---
    // Independent of the membership list: an active subscription is what grants
    // supporter perks, and `has_access` is the only authoritative answer for that.
    if (!isset($seenSet[METAFY_TALISHAR_COMMUNITY_ID])) {
      $subscription = MetafyCheckSubscription($auth, $conn, METAFY_TALISHAR_COMMUNITY_ID);
      $storedTalishar = $storedById[METAFY_TALISHAR_COMMUNITY_ID] ?? null;

      if ($subscription['ok'] && $subscription['has_access']) {
        $tierMap = MetafyBuildTierMap([], $storedTalishar);
        $info = [
          'id'          => METAFY_TALISHAR_COMMUNITY_ID,
          'title'       => $storedTalishar['title'] ?? 'Talishar',
          'description' => $storedTalishar['description'] ?? null,
          'logo_url'    => $storedTalishar['logo_url'] ?? null,
          'cover_url'   => $storedTalishar['cover_url'] ?? null,
          'url'         => $storedTalishar['url'] ?? null,
          'tiers'       => $storedTalishar['tiers'] ?? [],
          'type'        => 'supported',
          'has_access'  => true,
        ];
        if ($subscription['tier_id'] && isset($tierMap[$subscription['tier_id']])) {
          $info['metafy_tier'] = $tierMap[$subscription['tier_id']];
        } elseif (!empty($storedTalishar['metafy_tier'])) {
          $info['metafy_tier'] = $storedTalishar['metafy_tier'];
        }
        $all[] = $info;
      } elseif (!$subscription['ok'] && $storedTalishar !== null) {
        // Could not verify : do not revoke on a failed call.
        $all[] = $storedTalishar;
      }
    }

    MetafyEnsureUserId($auth, $conn);
    MetafySaveCommunities($conn, $auth['usersId'], $all, $auth['metafy_id'] ?? null);

    $auth['communities']   = $all;
    $result['ok']          = true;
    $result['communities'] = $all;
    $result['isSupporter'] = IsTalisharMetafySupporter($all);
    return $result;
  }
}

if (!function_exists('MetafySaveCommunities')) {
  function MetafySaveCommunities($conn, $usersId, $communities, $metafyId = null)
  {
    if (!$conn || empty($usersId)) return false;
    $json = json_encode($communities);

    $sync = MetafyHasTokenMetaColumns($conn) ? ', metafyLastSync=CURRENT_TIMESTAMP' : '';

    // Keep metafyID in step with the community list so the moderator sync can match
    // this account against Metafy's subscriber roster.
    if (!empty($metafyId)) {
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, "UPDATE users SET metafyCommunities=?, metafyID=?$sync WHERE usersId=?")) return false;
      mysqli_stmt_bind_param($stmt, 'sss', $json, $metafyId, $usersId);
    } else {
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, "UPDATE users SET metafyCommunities=?$sync WHERE usersId=?")) return false;
      mysqli_stmt_bind_param($stmt, 'ss', $json, $usersId);
    }

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
  }
}

/**
 * Cheap, self-healing supporter check for read paths like the profile endpoint.
 *
 * A single call to the purchases endpoint answers "is this account subscribed to
 * Talishar right now", which is the only thing supporter perks depend on. Someone who
 * subscribes after linking their account gets picked up here without having to find
 * the "Refresh your Metafy connection" link, and a lapsed subscription drops off.
 *
 * Throttled by metafyLastSync so a profile page load costs at most one Metafy request
 * every $throttleSeconds. Returns true when the stored community list changed.
 */
if (!function_exists('MetafyQuickSupporterCheck')) {
  function MetafyQuickSupporterCheck($conn, &$auth, $throttleSeconds = 900)
  {
    if (empty($auth['access_token'])) return false;
    if (!MetafyScopesAreSufficient($auth['scopes'] ?? null)) return false;
    if (!empty($auth['last_sync']) && (time() - $auth['last_sync']) < $throttleSeconds) return false;

    $subscription = MetafyCheckSubscription($auth, $conn, METAFY_TALISHAR_COMMUNITY_ID);
    if (!$subscription['ok']) {
      // A failed call says nothing about the subscription, so stored data stands. Still
      // record the attempt: without it a broken token retries on every single page load.
      MetafyTouchLastSync($conn, $auth['usersId']);
      $auth['last_sync'] = time();
      return false;
    }

    $communities  = is_array($auth['communities'] ?? null) ? $auth['communities'] : [];
    $wasSupporter = IsTalisharMetafySupporter($communities);
    $hasAccess    = $subscription['has_access'];

    if ($hasAccess === $wasSupporter) {
      // Nothing changed, but record the attempt so we do not re-check on every page load.
      MetafyTouchLastSync($conn, $auth['usersId']);
      $auth['last_sync'] = time();
      return false;
    }

    $communities = MetafyApplyTalisharAccess($communities, $hasAccess);

    MetafySaveCommunities($conn, $auth['usersId'], $communities, $auth['metafy_id'] ?? null);
    $auth['communities'] = $communities;
    $auth['last_sync']   = time();
    return true;
  }
}

/**
 * Record the outcome of a Talishar subscription check on the community list.
 * The entry itself is kept either way so the profile page can still show the community;
 * `has_access` is what perks are gated on.
 */
if (!function_exists('MetafyApplyTalisharAccess')) {
  function MetafyApplyTalisharAccess($communities, $hasAccess)
  {
    if (!is_array($communities)) $communities = [];
    $found = false;

    foreach ($communities as $index => $community) {
      if (($community['id'] ?? null) === METAFY_TALISHAR_COMMUNITY_ID) {
        $communities[$index]['has_access'] = $hasAccess;
        $found = true;
        break;
      }
    }

    if (!$found && $hasAccess) {
      $communities[] = [
        'id'         => METAFY_TALISHAR_COMMUNITY_ID,
        'title'      => 'Talishar',
        'type'       => 'supported',
        'has_access' => true,
        'tiers'      => [],
      ];
    }

    return array_values($communities);
  }
}

if (!function_exists('MetafyTouchLastSync')) {
  function MetafyTouchLastSync($conn, $usersId)
  {
    if (!$conn || empty($usersId) || !MetafyHasTokenMetaColumns($conn)) return;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, 'UPDATE users SET metafyLastSync=CURRENT_TIMESTAMP WHERE usersId=?')) {
      mysqli_stmt_bind_param($stmt, 's', $usersId);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
  }
}

/**
 * True when the stored scope string is missing a scope supporter detection depends on.
 * A token issued by the "Sign in with Metafy" login app only carries `profile`.
 */
if (!function_exists('MetafyScopesAreSufficient')) {
  function MetafyScopesAreSufficient($scopes)
  {
    if (empty($scopes)) return true; // unknown (pre-migration) : let the API decide
    $granted = preg_split('/[\s,]+/', strtolower(trim($scopes)), -1, PREG_SPLIT_NO_EMPTY);
    return in_array('community', $granted, true) && in_array('purchases', $granted, true);
  }
}

if (!function_exists('GetMetafyTiersFromDatabase')) {
  function GetMetafyTiersFromDatabase($userName)
  {
    if (IsDevEnvironment()) return [];
    $conn = GetDBConnection(DBL_METAFY_HELPER);
    if(!$conn) return [];
    $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_close($conn);
      return [];
    }

    mysqli_stmt_bind_param($stmt, 's', $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if (!$row || empty($row['metafyCommunities'])) {
      return [];
    }

    $communities = json_decode($row['metafyCommunities'], true);
    if (!is_array($communities)) {
      return [];
    }

    $tiers = [];

    foreach ($communities as $community) {
      $communityId = $community['id'] ?? null;

      if ($communityId === METAFY_TALISHAR_COMMUNITY_ID) {
        $tierName = null;

        // Check stored metafy_tier field (stored during MetafySyncCommunities)
        if (!empty($community['metafy_tier']) && is_string($community['metafy_tier'])) {
          $tierName = $community['metafy_tier'];
        }
        // Legacy: Check subscription_tier field
        elseif (isset($community['subscription_tier']) && is_array($community['subscription_tier'])) {
          $tierName = $community['subscription_tier']['name'] ?? null;
        } elseif (isset($community['subscription_tier']) && is_string($community['subscription_tier'])) {
          $tierName = $community['subscription_tier'];
        }

        if ($tierName) {
          $tiers[] = $tierName;
        }
        break;
      }
    }

    return $tiers;
  }
}

if (!function_exists('GetMetafyCommunitiesFromDatabase')) {
  function GetMetafyCommunitiesFromDatabase($userName)
  {
    if (IsDevEnvironment()) return [];
    $conn = GetDBConnection(DBL_METAFY_HELPER);
    if (!$conn) return [];
    $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_close($conn);
      return [];
    }

    mysqli_stmt_bind_param($stmt, 's', $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if (!$row || empty($row['metafyCommunities'])) {
      return [];
    }

    $communities = json_decode($row['metafyCommunities'], true);
    return is_array($communities) ? $communities : [];
  }
}

/**
 * Whether the stored community list says this account supports Talishar.
 *
 * `has_access` comes from the purchases endpoint and is the authoritative answer, but
 * entries written before it was recorded do not carry the key at all, and those are still
 * treated as supporters, so nobody loses their perks waiting for the next sync. Only an
 * explicit false, meaning we asked Metafy and it said no, revokes.
 */
if (!function_exists('IsTalisharMetafySupporter')) {
  function IsTalisharMetafySupporter($communities)
  {
    if (!is_array($communities)) return false;
    foreach ($communities as $community) {
      if (($community['id'] ?? null) !== METAFY_TALISHAR_COMMUNITY_ID) continue;
      return !array_key_exists('has_access', $community) || $community['has_access'] !== false;
    }
    return false;
  }
}

function IsValidMetafyTier($tierName)
{
  static $supportedTiersMap = null;
  if ($supportedTiersMap === null) {
    $supportedTiersMap = array_flip([
      'Fyendal Supporters',
      'Seers of Ophidia',
      'Arknight Shards',
      'Lover of Grandeur',
      'Sponsors of Trōpal-Dhani',
      'Light of Sol Gemini Circle',
    ]);
  }
  return isset($supportedTiersMap[$tierName]);
}

// Replay save slots granted per Metafy tier, on top of the base patron allotment.
// Higher tiers grant more slots as a subscriber incentive.
function GetMaxReplaySlotsForTiers($metafyTiers)
{
  $tierSlotMap = [
    'Fyendal Supporters' => 5,
    'Seers of Ophidia' => 8,
    'Arknight Shards' => 10,
    'Light of Sol Gemini Circle' => 12,
    'Lover of Grandeur' => 15,
    'Sponsors of Trōpal-Dhani' => 20,
  ];
  $maxSlots = MAX_REPLAYS_SAVED;
  if (!is_array($metafyTiers)) return $maxSlots;
  foreach ($metafyTiers as $tierName) {
    if (isset($tierSlotMap[$tierName]) && $tierSlotMap[$tierName] > $maxSlots) {
      $maxSlots = $tierSlotMap[$tierName];
    }
  }
  return $maxSlots;
}

?>
