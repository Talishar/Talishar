<?php

  include "HostFiles/Redirector.php";

  $gameName=$_GET["gameName"];
  if (!file_exists("Games/" . $gameName))
  {
    mkdir("Games/" . $gameName, 0700, true);
  }

  header("Location: " . $redirectorPath . "JoinGame.php?gameName=$gameName&playerID=1");

  
?>
