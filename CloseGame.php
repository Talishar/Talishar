<?php

ob_start();
include "./HostFiles/Redirector.php";
include "./Libraries/HTTPLibraries.php";
ob_end_clean();
SetHeaders();

include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";
include "Libraries/SHMOPLibraries.php";
include_once './Libraries/CSRFLibraries.php';

session_start();

if (!isset($_SESSION["useruid"])) {
  echo("Please login to view this page.");
  exit;
}
$useruid = $_SESSION["useruid"];
if ($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "LaustinSpayce" && $useruid != "bavverst" && $useruid != "Star_Seraph" && $useruid != "Tower" && $useruid != "PvtVoid" && $useruid != "Aegisworn") {
  echo("You must log in to use this page.");
  exit;
}

// Validate CSRF token for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();
}

$gameToken = TryPOST("gameToClose", "");
$folder = "./Games/" . $gameToken;

deleteDirectory($folder);
DeleteCache($gameToken);

header("Location: ./zzModPage.php");


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
