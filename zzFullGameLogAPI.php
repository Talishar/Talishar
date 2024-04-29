<?php

  include "Libraries/HTTPLibraries.php";

  $gameName = $_GET["gameName"];
  if (!IsGameNameValid($gameName)) {
    echo ("Invalid game name.");
    exit;
  }
  $filename = "./Games/" . $gameName . "/fullGamelog.txt";
  if(!file_exists($filename)) {
    echo ("There is no full game log for this game.");
    exit;
  }

  echo(implode("<BR>", explode("\r\n", file_get_contents($filename))));

?>
