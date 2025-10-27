<?php

ob_start();
include "./HostFiles/Redirector.php";
include "./Libraries/HTTPLibraries.php";
ob_end_clean();
SetHeaders();

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";
include_once './Libraries/CSRFLibraries.php';

session_start();

if (!isset($_SESSION["useruid"])) {
  echo json_encode(["status" => "error", "message" => "Please login to view this page."]);
  http_response_code(401);
  exit;
}
$useruid = $_SESSION["useruid"];
if ($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "LaustinSpayce" && $useruid != "bavverst" && $useruid != "Star_Seraph" && $useruid != "Tower" && $useruid != "PvtVoid" && $useruid != "Aegisworn") {
  echo json_encode(["status" => "error", "message" => "You must log in to use this page."]);
  http_response_code(403);
  exit;
}

// Validate CSRF token for POST requests (optional for API - credentials in cookies provide protection)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!validateCSRFToken($_POST['csrf_token'])) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "CSRF token validation failed"]);
        http_response_code(403);
        exit;
    }
}

$playerToBan = TryPOST("playerToBan", "");
$ipToBan = TryPOST("ipToBan", "");
$playerNumberToBan = TryPOST("playerNumberToBan", "");

$result = ["status" => "success"];

if ($playerToBan != "") {
  file_put_contents('./HostFiles/bannedPlayers.txt', $playerToBan . "\r\n", FILE_APPEND | LOCK_EX);
  BanPlayer($playerToBan);
  $result["message"] = "Player $playerToBan has been banned.";
}
if ($ipToBan != "") {
  $gameName = $ipToBan;
  include './MenuFiles/ParseGamefile.php';
  $ipToBan = $playerNumberToBan == "1" ? $hostIP : $joinerIP;
  file_put_contents('./HostFiles/bannedIPs.txt', $ipToBan . "\r\n", FILE_APPEND | LOCK_EX);
  $result["message"] = "IP $ipToBan has been banned.";
}

// Return JSON response for API calls
header('Content-Type: application/json');
echo json_encode($result);

