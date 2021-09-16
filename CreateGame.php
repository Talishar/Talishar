<?php

  include "HostFiles/Redirector.php";

  $deck=$_GET["deck"];
  $decklink=$_GET["fabdb"];

  $gcFile = fopen("HostFiles/GameIDCounter.txt", "r+");
  $attemptCount = 0;

  while(!flock($gcFile, LOCK_EX) && $attemptCount < 30) {  // acquire an exclusive lock
    sleep(1);
    ++$attemptCount;
  }
  if($attemptCount == 30)
  {
    header("Location: " . $redirectorPath . "MainMenu.php");//We never actually got the lock
  }
  $counter = intval(fgets($gcFile));
  $gameName = hash("sha256", $counter);
  ftruncate($gcFile, 0);
  rewind($gcFile);
  fwrite($gcFile, $counter+1);
  flock($gcFile, LOCK_UN);    // release the lock
  fclose($gcFile);

  if (!file_exists("Games/" . $gameName))
  {
    mkdir("Games/" . $gameName, 0700, true);
  }

  header("Location: " . $redirectorPath . "JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink");


?>
