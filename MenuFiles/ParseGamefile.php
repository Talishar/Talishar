<?php

  if(!function_exists("GetArray"))
  {
    function GetArray($handler)
    {
      if(!$handler) return false;
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

  // Bulk read: one stream call replaces ~30 sequential fgets+trim calls.
  $lines = explode("\r\n", stream_get_contents($gameFileHandler));
  $li = 0;

  $line = $lines[$li++] ?? '';
  $p1Data = ($line !== '') ? explode(' ', $line) : [];
  $line = $lines[$li++] ?? '';
  $p2Data = ($line !== '') ? explode(' ', $line) : [];
  $gameStatus           = $lines[$li++] ?? '';
  $format               = $lines[$li++] ?? '';
  $visibility           = $lines[$li++] ?? '';
  $firstPlayerChooser   = $lines[$li++] ?? '';
  $firstPlayer          = $lines[$li++] ?? '';
  $p1Key                = $lines[$li++] ?? '';
  $p2Key                = $lines[$li++] ?? '';
  $p1uid                = $lines[$li++] ?? '';
  $p2uid                = $lines[$li++] ?? '';
  $p1id                 = $lines[$li++] ?? '';
  $p2id                 = $lines[$li++] ?? '';
  $gameDescription      = $lines[$li++] ?? '';
  $hostIP               = $lines[$li++] ?? '';
  $p1IsPatron           = $lines[$li++] ?? '';
  $p2IsPatron           = $lines[$li++] ?? '';
  $p1DeckLink           = $lines[$li++] ?? '';
  $p2DeckLink           = $lines[$li++] ?? '';
  $p1IsChallengeActive  = $lines[$li++] ?? '';
  $p2IsChallengeActive  = $lines[$li++] ?? '';
  $joinerIP             = $lines[$li++] ?? '';
  $deprecated           = $lines[$li++] ?? '';//Deprecated
  $p1Matchups           = json_decode($lines[$li++] ?? '');
  $p2Matchups           = json_decode($lines[$li++] ?? '');
  $p1deckbuilderID      = $lines[$li++] ?? '';
  $p2deckbuilderID      = $lines[$li++] ?? '';
  $roguelikeGameID      = $lines[$li++] ?? '';
  $p1StartingHealth     = $lines[$li++] ?? '';
  $p1ContentCreatorID   = $lines[$li++] ?? '';
  $p2ContentCreatorID   = $lines[$li++] ?? '';
  $p1SideboardSubmitted = $lines[$li++] ?? '';
  $p2SideboardSubmitted = $lines[$li++] ?? '';
  $p1StartingEquipment  = json_decode($lines[$li++] ?? '');
  $p2StartingEquipment  = json_decode($lines[$li++] ?? '');
  $p1IsAI               = $lines[$li++] ?? '';
  $p2IsAI               = $lines[$li++] ?? '';
  $gameGUID             = $lines[$li++] ?? '';
  $p1MetafyTiers        = json_decode($lines[$li++] ?? '', true);
  $p2MetafyTiers        = json_decode($lines[$li++] ?? '', true);
  if (!is_array($p1MetafyTiers)) $p1MetafyTiers = [];
  if (!is_array($p2MetafyTiers)) $p2MetafyTiers = [];
  $p1MetafyCommunities  = json_decode($lines[$li++] ?? '', true);
  $p2MetafyCommunities  = json_decode($lines[$li++] ?? '', true);
  if (!is_array($p1MetafyCommunities)) $p1MetafyCommunities = [];
  if (!is_array($p2MetafyCommunities)) $p2MetafyCommunities = [];
  unset($lines, $li, $line);

  $MGS_Initial = 0;
  $MGS_Player2Joined = 1;
  $MGS_ChooseFirstPlayer = 2;
  $MGS_P2Sideboard = 3;
  $MGS_ReadyToStart = 4;
  $MGS_GameStarted = 5;
  $MGS_GameOver = 99;

  $FORMAT_CompCC = 1;
  $FORMAT_CompBlitz = 3;
  $FORMAT_CompLL = 13;
  $FORMAT_CompSage = 15;

  if(!function_exists("UnlockGamefile"))
  {
    function UnlockGamefile()
    {
      global $gameFileHandler;
      fclose($gameFileHandler);
    }
  }
/*
  if(isset($playerID) && $gameStatus == $MGS_GameStarted)
  {
    $ipTarget = ($playerID == 1 ? $hostIP : $joinerIP);
    if($ipTarget != "" && $_SERVER['REMOTE_ADDR'] != $ipTarget)
    {
      $hackFileName = "./BugReports/PossibleHackAttempts.txt";
      $hackHandler = fopen($hackFileName, "a");
      date_default_timezone_set('America/Chicago');
      $errorDate = date('m/d/Y h:i:s a');
      fwrite($hackHandler, "Hack Attempt? $errorDate Request: " . $_SERVER['REQUEST_URI'] . " Game name: $gameName IP: " . $_SERVER['REMOTE_ADDR'] . " Target IP: $ipTarget PlayerID: $playerID" . "\r\n");
      fclose($hackHandler);
    }
  }
*/
?>
