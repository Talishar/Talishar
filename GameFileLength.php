<?php

  $gameName=$_GET["gameName"];

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "r+");

  $attemptCount = 0;
  while(!flock($gameFile, LOCK_EX) && $attemptCount < 30) {  // acquire an exclusive lock
    sleep(1);
    ++$attemptCount;
  }
  if($attemptCount == 30)
  {
    header("Location: " . $redirectorPath . "MainMenu.php");//We never actually got the lock
  }

  $lineCount = 0;
  while (($buffer = fgets($gameFile, 4096)) !== false) {
     ++$lineCount;
  }

  if($lineCount == 1)
  {
    ftruncate($gameFile, 0);
    rewind($gameFile);
    fwrite($gameFile, 1);//If there's still only one player, rewrite the value so it will have a more recent timestamp
  }

  flock($gameFile, LOCK_UN);    // release the lock
  fclose($gameFile);

  echo $lineCount;

?>

