<?php

/**
 * MetafyHelper.php - Helper functions for Metafy community tier integration
 * Note: This file should be included after APIKeys.php and dbh.inc.php
 */

/**
 * Get the Metafy communities for a user
 * @param $userName The username to get Metafy communities for
 * @return array Array of community tier names the user belongs to
 */
function GetUserMetafyCommunities($userName)
{   

  /* // Test mode: hardcode tiers for local testing
  if ($userName === "PvtVoid") {
    return ['Fyendal Supporters', 'Seers of Ophidia', 'Arknight Shards', 'Light of Sol Gemini Circle', 'Lover of Grandeur', 'Sponsors of Trōpal-Dhani'];
  } */
  if (IsDevEnvironment()) return [];
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
