<?php

include "./Libraries/HTTPLibraries.php";
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";

session_start();

if (!isset($_SESSION["useruid"])) {
  echo ("Please login to view this page.");
  exit;
}
$useruid = $_SESSION["useruid"];
if ($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "PvtVoid") {
  echo ("You must log in to use this page.");
  exit;
}

$playerToBan = TryGET("playerToBan", "");
$ipToBan = TryGET("ipToBan", "");

if ($playerToBan != "") {
  file_put_contents('./HostFiles/bannedPlayers.txt', $playerToBan . "\r\n", FILE_APPEND | LOCK_EX);
}
if ($ipToBan != "") {
  $gameName = $ipToBan;
  include './MenuFiles/ParseGamefile.php';
  file_put_contents('./HostFiles/bannedIPs.txt', $hostIP . "\r\n", FILE_APPEND | LOCK_EX);
}


header("Location: ./zzModPage.php");
