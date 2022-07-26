<?php

  include "./Libraries/HTTPLibraries.php";
  include_once './includes/functions.inc.php';
  include_once "./includes/dbh.inc.php";

  session_start();

  if(!isset($_SESSION["useruid"])) { echo("Please login to view this page."); exit; }
  $useruid= $_SESSION["useruid"];
  if($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "PvtVoid") { echo("You must log in to use this page."); exit; }

  echo("<h1>Banned players:</h1>");
  $banfileHandler = fopen("./HostFiles/bannedPlayers.txt", "r");
  while(!feof($banfileHandler))  {
    $bannedPlayer = fgets($banfileHandler);
    echo($bannedPlayer . "<BR>");
  }
  fclose($banfileHandler);

    echo("<br><br><form  action='./HostFiles/BanPlayer.php'>");
?>
     <label for="playerToBan" style='font-weight:bolder; margin-left:10px;'>Player to ban:</label>
     <input type="text" id="playerToBan" name="playerToBan" value="">
     <input type="submit" value="Ban">
    </form>
