<?php

  ob_start();
  include "HostFiles/Redirector.php";
  include "Libraries/HTTPLibraries.php";
  include "Libraries/SHMOPLibraries.php";
  ob_end_clean();

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
    $gameStatus = 4;//ReadyToStart
    copy("Dummy.txt","./Games/" . $gameName . "/p2Deck.txt");
  }
  else
  {
    $gameStatus = 0;//Initial
  }
  $firstPlayerChooser = "";
  $firstPlayer = 1;
  $p1Key = hash("sha256", rand() . rand());
  $p2Key = hash("sha256", rand() . rand() . rand());
  $p1uid = "-";
  $p2uid = "-";
  $p1id = "-";
  $p2id = "-";

  $filename = "./Games/" . $gameName . "/GameFile.txt";
  $gameFileHandler = fopen($filename, "w");
  include "MenuFiles/WriteGamefile.php";
  WriteGameFile();

  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "w");
  fclose($handler);

  $currentTime = round(microtime(true) * 1000);
  WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1");//Initialize SHMOP cache for this game

  header("Location: JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink&set=$set&decksToTry=$decksToTry");

?>
