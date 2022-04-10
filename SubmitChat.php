<?php

  include "Libraries/HTTPLibraries.php";
  include "Libraries/SHMOPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $chatText=htmlspecialchars($_GET["chatText"]);


  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "a");
  $output = "<span style='font-weight:bold; color:<PLAYER" . $playerID . "COLOR>;'>Player " . $playerID . ": </span>" . $chatText;
  fwrite($handler, $output . "\r\n");
  fclose($handler);

  SetCachePiece($gameName, 1, strval(round(microtime(true) * 1000)));

?>
