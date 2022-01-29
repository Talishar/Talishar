<?php

  if(!function_exists("GetArray"))
  {
    function GetArray($handler)
    {
      $line = trim(fgets($handler));
      if($line=="") return [];
      return explode(" ", $line);
    }
  }

  $filename = "./Games/" . $gameName . "/GameFile.txt";

  $handler = fopen($filename, "r");
  $p1Data = GetArray($handler);
  $p2Data = GetArray($handler);
  $gameStatus = trim(fgets($handler));
  $format = trim(fgets($handler));
  $visibility = trim(fgets($handler));

  fclose($handler);

  $MGS_Initial = 0;
  $MGS_Player2Joined = 4;
  $MGS_ReadyToStart = 5;
  $MGS_GameStarted = 6;



?>

