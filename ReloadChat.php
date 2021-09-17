<?php

  include "WriteLog.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $lastUpdate=$_GET["lastUpdate"];

  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $time = filemtime($filename);
  while($lastUpdate >= $time)
  {
    sleep(1);
    clearstatcache();
    $time = filemtime($filename);
  }
  echo(filemtime($filename));
  EchoLog($gameName, $playerID);

?>