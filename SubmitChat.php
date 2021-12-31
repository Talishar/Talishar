<?php

  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $chatText=htmlspecialchars($_GET["chatText"]);


  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "a");
  $output = "<span style='color:<PLAYER" . $playerID . "COLOR>;'>Player " . $playerID . ": </span>" . $chatText;
  fwrite($handler, $output . "\r\n");
  fclose($handler);
?>