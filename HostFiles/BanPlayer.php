<?php

  include "../Libraries/HTTPLibraries.php";
  include_once '../includes/functions.inc.php';
  include_once "../includes/dbh.inc.php";

  session_start();

  if(!isset($_SESSION["useruid"])) { echo("Please login to view this page."); exit; }
  $useruid= $_SESSION["useruid"];
  if($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "PvtVoid") { echo("You must log in to use this page."); exit; }

  $playerToBan=TryGET("playerToBan", "");

  if($playerToBan == "") header("Location: ../zzModPage.php");

  file_put_contents('./bannedPlayers.txt', $playerToBan . "\r\n", FILE_APPEND | LOCK_EX);

  header("Location: ../zzModPage.php");

?>
