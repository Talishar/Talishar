<?php

  include "HostFiles/Redirector.php";
  include "Libraries/HTTPLibraries.php";

  $deck=TryGET("deck");
  $decklink=TryGET("fabdb");
  $deckTestMode=TryGET("deckTestMode");
  $format=TryGET("format");
  $visibility=TryGET("visibility");
  $set=TryGET("set");
  $decksToTry = TryGet("decksToTry");

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

  $p1Data = [1];
  $p2Data = [2];
  if($deckTestMode == "deckTestMode")
  {
    $gameStatus = 5;
    copy("Dummy.txt","./Games/" . $gameName . "/p2Deck.txt");
  }
  else
  {
    $gameStatus = 0;
  }
  include "MenuFiles/WriteGamefile.php";

  header("Location: JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink&set=$set&decksToTry=$decksToTry");

?>
