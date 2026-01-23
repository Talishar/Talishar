<?php

/**
 * GetMetafyGuides.php
 * Standalone endpoint to fetch Metafy community guides
 * Accessible from anywhere (main menu, learn page, etc.)
 */

session_start();
session_write_close();

include "./Libraries/HTTPLibraries.php";

// CORS headers
SetHeaders();

$response = new stdClass();

// Get pagination parameters
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$perPage = isset($_GET["per_page"]) ? intval($_GET["per_page"]) : 20;

// Ensure valid pagination values
$page = max(1, $page);
$perPage = min(max(1, $perPage), 100);

// Talishar community ID
$talisharCommunityId = "be5e01c0-02d1-4080-b601-c056d69b03f6";

// Metafy API key (can be set via environment variable or directly)
$metafyApiKey = getenv('METAFY_API_KEY') ?: (defined('METAFY_API_KEY') ? METAFY_API_KEY : 'api-x6S0fEVeEiIbmZGeHwL5fQA3sD0gD-dqZuMMKc-kXaI');

// Fetch guides from Metafy API using direct Bearer token authentication
if (!empty($metafyApiKey)) {
  $allGuides = [];
  $allMeta = null;
  
  // First, fetch owner's guides from /v1/me/guides
  $curlHandle = curl_init();
  $ownerGuidesUrl = "https://metafy.gg/irk/api/v1/me/guides?page=" . $page . "&per_page=" . $perPage;
  
  curl_setopt_array($curlHandle, [
    CURLOPT_URL => $ownerGuidesUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
      "Accept: application/json",
      "Authorization: Bearer " . $metafyApiKey
    ]
  ]);
  
  $ownerResponseBody = curl_exec($curlHandle);
  $ownerHttpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
  curl_close($curlHandle);
  
  error_log("Metafy API Request (Owner): " . $ownerGuidesUrl);
  error_log("Metafy API Response Code (Owner): " . $ownerHttpCode);
  
  if ($ownerHttpCode === 200 && $ownerResponseBody) {
    $ownerResponse = json_decode($ownerResponseBody);
    if ($ownerResponse && isset($ownerResponse->guides)) {
      $allGuides = array_merge($allGuides, $ownerResponse->guides);
      $allMeta = $ownerResponse->meta;
    }
  }
  
  // Then, fetch team/community guides from /v1/me/community/team/guides
  $curlHandle = curl_init();
  $teamGuidesUrl = "https://metafy.gg/irk/api/v1/me/community/team/guides?page=" . $page . "&per_page=" . $perPage;
  
  curl_setopt_array($curlHandle, [
    CURLOPT_URL => $teamGuidesUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
      "Accept: application/json",
      "Authorization: Bearer " . $metafyApiKey
    ]
  ]);
  
  $teamResponseBody = curl_exec($curlHandle);
  $teamHttpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
  curl_close($curlHandle);
  
  error_log("Metafy API Request (Team): " . $teamGuidesUrl);
  error_log("Metafy API Response Code (Team): " . $teamHttpCode);
  
  if ($teamHttpCode === 200 && $teamResponseBody) {
    $teamResponse = json_decode($teamResponseBody);
    if ($teamResponse && isset($teamResponse->guides)) {
      $allGuides = array_merge($allGuides, $teamResponse->guides);
    }
  }
  
  // Build response with combined guides
  $response->guides = $allGuides;
  if ($allMeta) {
    // Update pagination to reflect combined results
    $response->meta = $allMeta;
    $response->meta->pagination->total_guides = count($allGuides);
  } else {
    $response->meta = new stdClass();
    $response->meta->pagination = new stdClass();
    $response->meta->pagination->current_page = $page;
    $response->meta->pagination->per_page = count($allGuides);
    $response->meta->pagination->total_pages = 1;
    $response->meta->pagination->total_guides = count($allGuides);
  }
} else {
  $response->error = "Metafy API key not configured";
  $response->guides = [];
  $response->meta = new stdClass();
  $response->meta->pagination = new stdClass();
  $response->meta->pagination->current_page = $page;
  $response->meta->pagination->per_page = $perPage;
  $response->meta->pagination->total_pages = 1;
}

// Debug: Add API key status to response
$response->_debug = new stdClass();
$response->_debug->has_api_key = !empty($metafyApiKey);
$response->_debug->api_key_prefix = substr($metafyApiKey, 0, 10) . "...";

echo json_encode($response);
