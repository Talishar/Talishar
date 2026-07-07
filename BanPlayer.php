<?php

include "./HostFiles/Redirector.php";
include "./Libraries/HTTPLibraries.php";
SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";
include_once './Libraries/CSRFLibraries.php';
include_once './includes/ModeratorList.inc.php';

session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (!isset($_SESSION["useruid"])) {
  echo json_encode(["status" => "error", "message" => "Please login to view this page."]);
  http_response_code(401);
  exit;
}
$useruid = $_SESSION["useruid"];
if (!IsUserModerator($useruid)) {
  echo json_encode(["status" => "error", "message" => "You must log in to use this page."]);
  http_response_code(403);
  exit;
}

// Validate CSRF token for POST requests (optional for API - credentials in cookies provide protection)
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!validateCSRFToken($_POST['csrf_token'])) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "CSRF token validation failed"]);
        http_response_code(403);
        exit;
    }
}
    */

// Handle both form-encoded and JSON POST data
$postData = $_POST;
if (empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        $postData = $jsonData ?? [];
    }
}

function TryPOSTData($key, $default = "", $data = []) {
    return $data[$key] ?? $default;
}

$playerToBan = trim(TryPOSTData("playerToBan", "", $postData));
$ipToBan = trim(TryPOSTData("ipToBan", "", $postData));
$playerNumberToBan = trim(TryPOSTData("playerNumberToBan", "", $postData));
$directIPToBan = trim(TryPOSTData("directIPToBan", "", $postData));
$usernameToDelete = trim(TryPOSTData("usernameToDelete", "", $postData));

$result = ["status" => "success"];

if ($playerToBan != "") {
  // Database is the source of truth; the txt file is a legacy copy that
  // does not survive deploys/container restarts.
  $bannedUid = BanPlayer($playerToBan);
  @file_put_contents('./HostFiles/bannedPlayers.txt', $bannedUid . "\r\n", FILE_APPEND | LOCK_EX);
  if ($bannedUid != $playerToBan) $result["message"] = "Player $playerToBan (account: $bannedUid) has been banned.";
  else $result["message"] = "Player $playerToBan has been banned.";
}
if ($ipToBan != "") {
  $gameName = $ipToBan;
  include './MenuFiles/ParseGamefile.php';
  $ipToBan = $playerNumberToBan == "1" ? $hostIP : $joinerIP;
  if (BanIP($ipToBan, $useruid)) {
    @file_put_contents('./HostFiles/bannedIPs.txt', $ipToBan . "\r\n", FILE_APPEND | LOCK_EX);
    $result["message"] = "IP $ipToBan has been banned.";
  } else {
    $result["status"] = "error";
    $result["message"] = "Failed to save banned IP to database";
  }
}

if ($directIPToBan != "") {
  if (filter_var($directIPToBan, FILTER_VALIDATE_IP) === false) {
    $result["status"] = "error";
    $result["message"] = "\"$directIPToBan\" is not a valid IP address.";
  } else if (BanIP($directIPToBan, $useruid)) {
    @file_put_contents('./HostFiles/bannedIPs.txt', $directIPToBan . "\r\n", FILE_APPEND | LOCK_EX);
    $result["message"] = "IP $directIPToBan has been banned.";
  } else {
    $result["status"] = "error";
    $result["message"] = "Failed to save banned IP to database";
  }
}

if ($usernameToDelete != "") {
  // Username deletion is now handled by DeleteAccountAPI.php
  // This endpoint should not receive usernameToDelete
  $result["status"] = "error";
  $result["message"] = "Please use the proper delete account endpoint.";
}

// Return JSON response for API calls
header('Content-Type: application/json');
echo json_encode($result);

