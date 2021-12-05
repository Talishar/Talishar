<?php

  $gameName=$_GET["gameName"];

  $filename = "./Games/" . $gameName . "/GameFile.txt";
  if(!file_exists($filename)) { echo(-1); exit; }
  $gameFile = fopen($filename, "r+");

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
  $status = -1;
  while (($buffer = fgets($gameFile, 4096)) !== false) {
     ++$lineCount;
     if($lineCount == 3) $status = $buffer;
  }

  ftruncate($gameFile, 0);
  rewind($gameFile);
  fwrite($gameFile, "1\r\n2\r\n" . $status);//If there's still only one player, rewrite the value so it will have a more recent timestamp

  flock($gameFile, LOCK_UN);    // release the lock
  fclose($gameFile);

  echo $status;

?>

