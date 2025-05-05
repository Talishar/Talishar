<?php

include "./Libraries/HTTPLibraries.php";
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";
include "Libraries/SHMOPLibraries.php";

session_start();

if (!isset($_SESSION["useruid"])) {
  echo "Please login to view this page.";
  exit;
}
$useruid = $_SESSION["useruid"];
if ($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "LaustinSpayce" && $useruid != "bavverst" && $useruid != "Star_Seraph" && $useruid != "Tower" && $useruid != "PvtVoid") {
  echo "You must log in to use this page.";
  exit;
}

$gameToken = TryGET("gameToClose", "");
$folder = "./Games/$gameToken";

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
    if (!deleteDirectory("$dir/$item")) {
      return false;
    }
  }
  return rmdir($dir);
}
