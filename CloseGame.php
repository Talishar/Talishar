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
include "Libraries/SHMOPLibraries.php";
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

$gameToken = TryPOST("gameToClose", "");
$folder = "./Games/" . $gameToken;

deleteDirectory($folder);
DeleteCache($gameToken);

// Return JSON response for API calls
header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Game $gameToken has been closed."]);


function deleteDirectory($dir)
{
  if (!file_exists($dir)) {
    return true;
  }

  if (!is_dir($dir)) {
    $handler = fopen($dir, "w");
    fwrite($handler, "");
    fclose($handler);
    return unlink($dir);
  }

  foreach (scandir($dir) as $item) {
    if ($item == '.' || $item == '..') {
      continue;
    }
    if (!deleteDirectory($dir . "/" . $item)) {
      return false;
    }
  }
  return rmdir($dir);
}
