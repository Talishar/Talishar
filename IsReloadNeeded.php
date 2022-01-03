<?php

  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];

  include "HostFiles/Redirector.php";
  include "Libraries/SHMOPLibraries.php";
  include "ParseGamestate.php";

  $count = 0;
  $cacheVal = ReadCache($gameName);
  while($playerID == $cacheVal)
  {
    usleep(50000);//50 milliseconds
    $cacheVal = ReadCache($gameName);
    ++$count;
    if($count == 500) break;
  }

  if($playerID != $cacheVal) { WriteCache($gameName, $playerID); echo "1"; }
  else echo "0";

?>

