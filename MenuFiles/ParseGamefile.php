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
  if(!file_exists($filename)) exit;
  $gameFileHandler = fopen($filename, "r+");

  $lockTries = 0;
  if(!$gameFileHandler) { exit; }//Game does not exist

  while(!flock($gameFileHandler, LOCK_EX) && $lockTries < 10)
  {
    usleep(100000);//100ms
    ++$lockTries;
  }
  if($lockTries == 10) exit;

  $p1Data = GetArray($gameFileHandler);
  $p2Data = GetArray($gameFileHandler);
  $gameStatus = trim(fgets($gameFileHandler));
  $format = trim(fgets($gameFileHandler));
  $visibility = trim(fgets($gameFileHandler));
  $firstPlayerChooser = trim(fgets($gameFileHandler));
  $firstPlayer = trim(fgets($gameFileHandler));
  $p1Key = trim(fgets($gameFileHandler));
  $p2Key = trim(fgets($gameFileHandler));
  $p1uid = trim(fgets($gameFileHandler));
  $p2uid = trim(fgets($gameFileHandler));
  $p1id = trim(fgets($gameFileHandler));
  $p2id = trim(fgets($gameFileHandler));
  $gameDescription = trim(fgets($gameFileHandler));
  $hostIP = trim(fgets($gameFileHandler));
  $p1IsPatron = trim(fgets($gameFileHandler));
  $p2IsPatron = trim(fgets($gameFileHandler));
  $p1DeckLink = trim(fgets($gameFileHandler));
  $p2DeckLink = trim(fgets($gameFileHandler));
  $p1IsChallengeActive = trim(fgets($gameFileHandler));
  $p2IsChallengeActive = trim(fgets($gameFileHandler));

  $MGS_Initial = 0;
  $MGS_Player2Joined = 1;
  $MGS_ChooseFirstPlayer = 2;
  $MGS_P2Sideboard = 3;
  $MGS_ReadyToStart = 4;
  $MGS_GameStarted = 5;

  if(!function_exists("UnlockGamefile"))
  {
    function UnlockGamefile()
    {
      global $gameFileHandler;
      fclose($gameFileHandler);
    }
  }

?>
