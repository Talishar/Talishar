<?php

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $chatText=$_GET["chatText"];


  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "a");
  $output = "<span style='color:<PLAYER" . $playerID . "COLOR>;'>Player " . $playerID . ": </span>" . $chatText;
  fwrite($handler, $output . "\r\n");
  fclose($handler);
?>