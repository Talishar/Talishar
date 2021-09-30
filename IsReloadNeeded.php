<?php

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  include "HostFiles/Redirector.php";
  include "Libraries/SHMOPLibraries.php";
  include "ParseGamestate.php";

  $count = 0;
  $cacheVal = ReadCache($gameName);
  while($playerID == $cacheVal)
  {
    usleep(10000);//10 milliseconds
    $cacheVal = ReadCache($gameName);
    ++$count;
    if($count == 1000) break;
  }

  if($playerID != $cacheVal) { WriteCache($gameName, $playerID); echo "1"; }
  else echo "0";

?>

