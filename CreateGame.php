<?php

  include "HostFiles/Redirector.php";

  $deck=$_GET["deck"];
  $decklink=$_GET["fabdb"];
  $attemptCount = 0;

  header("Location: " . $redirectorPath . "JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink");


?>
