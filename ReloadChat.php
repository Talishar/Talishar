<?php

  include "WriteLog.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $lastUpdate=$_GET["lastUpdate"];

  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $gsFile = "./Games/" . $gameName . "/gamestate.txt";
  if(file_exists($filename))
  {
    $time = filemtime($filename);
    $tries = 0;
    while($lastUpdate >= $time && $tries < 50)
    {
      sleep(1);
      clearstatcache();
      $time = filemtime($filename);
      $gstime = filemtime($gsFile);
      if($gstime > $time) $time = $gstime;
      ++$tries;
    }

    if($tries >= 50)
    {
      echo(filemtime($filename));
    }
    else
    {
      echo(filemtime($filename));
      EchoLog($gameName, $playerID);
    }
  }

?>