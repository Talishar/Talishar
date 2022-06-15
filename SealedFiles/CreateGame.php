<?php

  include '../Libraries/HTTPLibraries.php';
  include '../Libraries/IOLibraries.php';

  $numPlayers = 1;

  //First get your game ID
  $gcFile = fopen("HostFiles/Counter.txt", "r+");
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
  $gameName = $counter;
  ftruncate($gcFile, 0);
  rewind($gcFile);
  fwrite($gcFile, $counter+1);
  flock($gcFile, LOCK_UN);    // release the lock
  fclose($gcFile);

  //Create directory
  if (!file_exists("Games/" . $gameName))
  {
    mkdir("Games/" . $gameName, 0700, true);
  }

  //Read game state metadata info
  $metadataFile = fopen("GamestateMetadata.txt", "r");
  $zones = [];
  while(!feof($metadataFile))
  {
    $array = GetArray($metadataFile);
    $zones[count($zones)] = $array;
  }
  fclose($metadataFile);
  //Create game state file
  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "w");
  fwrite($gameFile, $numPlayers . "\r\n");
  for($i=0; $i<$numPlayers; ++$i)//Decision Queue for each player
  {
    fwrite($gameFile, "\r\n");
  }

  for($i=0;$i<count($zones); ++$i)
  {
    //The name doesn't matter for this, we're just creating placeholder zones for ParseGamestate to read from
    if($zones[$i][0] == "GL") fwrite($gameFile, "\r\n");
    else if($zones[$i][0] == "PP")
    {
      for($i=0; $i<$numPlayers; ++$i)
      {
        fwrite($gameFile, "\r\n");
      }
    }
  }
  fclose($gameFile);

  //Create game log
  $logFile = fopen("./Games/" . $gameName . "/gamelog.txt", "w");
  fclose($logFile);

  header("Location: InitializeGame.php?gameName=$gameName");

?>
