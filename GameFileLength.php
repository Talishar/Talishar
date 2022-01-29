<?php

  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }

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
  flock($gameFile, LOCK_UN);    // release the lock
  fclose($gameFile);

  include "MenuFiles/ParseGamefile.php";
  include "MenuFiles/WriteGamefile.php";//If there's still only one player, rewrite the value so it will have a more recent timestamp


  echo $gameStatus;

?>

