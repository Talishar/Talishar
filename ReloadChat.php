<?php

  include "WriteLog.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $lastUpdate=$_GET["lastUpdate"];

  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $time = filemtime($filename);
  $tries = 0;
  while($lastUpdate >= $time && $tries < 50)
  {
    sleep(1);
    clearstatcache();
    $time = filemtime($filename);
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

?>