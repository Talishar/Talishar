<?php

/**
 * MetafyHelper.php - Helper functions for Metafy community tier integration
 * Note: This file should be included after APIKeys.php and dbh.inc.php
 */

/**
 * Check if running in dev environment (define only if not already defined)
 */
if (!function_exists('IsDevEnvironment')) {
  function IsDevEnvironment() {
    $domain = getenv("DOMAIN");
    if ($domain === "localhost") return true;
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') return true;
    return false;
  }
}

/**
 * Get the Metafy communities for a user
 * @param $userName The username to get Metafy communities for
 * @return array Array of community tier names the user belongs to
 */
function GetUserMetafyCommunities($userName)
{   
  // Get access token from database
  $conn = GetDBConnection();
  $sql = "SELECT metafyAccessToken FROM users WHERE usersUid=?";
  $stmt = mysqli_stmt_init($conn);
  
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_close($conn);
    return [];
  }
  
  mysqli_stmt_bind_param($stmt, 's', $userName);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
  
  if (!$row || empty($row['metafyAccessToken'])) {
    return [];
  }
  
  $accessToken = $row['metafyAccessToken'];
  
  // Fetch communities from Metafy API
  return FetchMetafyCommunities($accessToken);
}

/**
 * Fetch communities from Metafy API
 * @param $accessToken The user's Metafy access token
 * @return array Array of community tier names
 */
function FetchMetafyCommunities($accessToken)
{
  $url = 'https://dev.metafy.gg/v1/community/list-joined-communities';
  
  $curl = curl_init();
  $headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
  ];
  
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  
  $response = curl_exec($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $curlError = curl_error($curl);
  curl_close($curl);
  
  if ($httpCode !== 200) {
    WriteLog("Metafy API Error: HTTP Code " . $httpCode . " - " . $curlError);
    return [];
  }
  
  if (empty($response)) {
    return [];
  }
  
  $data = json_decode($response, true);
  
  if (!isset($data['communities']) || !is_array($data['communities'])) {
    return [];
  }
  
  $tiers = [];
  
  // Map Metafy community names to our tier names
  foreach ($data['communities'] as $community) {
    if (isset($community['tier']['name'])) {
      $tierName = $community['tier']['name'];
      
      // Add if it's one of our supported tiers
      if (IsValidMetafyTier($tierName)) {
        $tiers[] = $tierName;
      }
    }
  }
  
  return $tiers;
}

/**
 * Get Metafy tier names for a user from the database (based on stored metafyCommunities).
 * This reads the already-saved community data — no live API call.
 * @param string $userName The username (usersUid) to look up
 * @return array Array of tier name strings (e.g. ['Arknight Shards'])
 */
if (!function_exists('GetMetafyTiersFromDatabase')) {
  function GetMetafyTiersFromDatabase($userName)
  {
    $conn = GetDBConnection();
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
    $talisharCommunityId = 'be5e01c0-02d1-4080-b601-c056d69b03f6';

    foreach ($communities as $community) {
      $communityId = $community['id'] ?? null;

      if ($communityId === $talisharCommunityId) {
        $tierName = null;

        // Check stored metafy_tier field (stored during FetchAndSaveMetafyCommunities)
        if (!empty($community['metafy_tier']) && is_string($community['metafy_tier'])) {
          $tierName = $community['metafy_tier'];
        }
        // Legacy: Check subscription_tier field
        elseif (isset($community['subscription_tier']) && is_array($community['subscription_tier'])) {
          $tierName = $community['subscription_tier']['name'] ?? null;
        } elseif (isset($community['subscription_tier']) && is_string($community['subscription_tier'])) {
          $tierName = $community['subscription_tier'];
        }

        // Fallback: if they are a Talishar supporter (type=supported) but no specific tier name,

        if ($tierName) {
          $tiers[] = $tierName;
        }
        break;
      }
    }

    return $tiers;
  }
}

/**
 * Check if a tier name is one of our supported Metafy tiers
 * @param $tierName The tier name from Metafy API
 * @return bool True if it's a supported tier
 */
function IsValidMetafyTier($tierName)
{
  $supportedTiers = [
    'Fyendal Supporters',
    'Seers of Ophidia',
    'Arknight Shards',
    'Lover of Grandeur',
    'Sponsors of Trōpal-Dhani',
    'Light of Sol Gemini Circle'
  ];
  
  return in_array($tierName, $supportedTiers);
}

?>
