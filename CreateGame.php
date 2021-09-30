<?php

  include "HostFiles/Redirector.php";
  include "Libraries/HTTPLibraries.php";

  $deck=TryGET("deck");
  $decklink=TryGET("fabdb");
  $deckTestMode=TryGET("deckTestMode");

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
  //$gameName = hash("sha256", $counter);
  $gameName = $counter;
  ftruncate($gcFile, 0);
  rewind($gcFile);
  fwrite($gcFile, $counter+1);
  flock($gcFile, LOCK_UN);    // release the lock
  fclose($gcFile);

  if (!file_exists("Games/" . $gameName))
  {
    mkdir("Games/" . $gameName, 0700, true);
  }

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "w");
  if($deckTestMode == "deckTestMode")
  {
    fwrite($gameFile, "1\r\n2\r\n5");
    copy("Dummy.txt","./Games/" . $gameName . "/p2Deck.txt");
  }
  else
  {
    fwrite($gameFile, "1\r\n2\r\n0");
  }
  fclose($gameFile);

  header("Location: JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink");
  //header("Location: " . $redirectPath . "JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink");

  
?>
