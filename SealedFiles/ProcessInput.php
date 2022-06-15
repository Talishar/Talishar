<?php

  error_reporting(E_ALL);

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  //We should also have some information on the type of command
  $mode = $_GET["mode"];
  $buttonInput = isset($_GET["buttonInput"]) ? $_GET["buttonInput"] : "";//The player that is the target of the command - e.g. for changing health total
  $cardID = isset($_GET["cardID"]) ? $_GET["cardID"] : "";
  $chkCount = isset($_GET["chkCount"]) ? $_GET["chkCount"] : 0;
  $chkInput = [];
  for($i=0; $i<$chkCount; ++$i)
  {
    $chk = isset($_GET[("chk" . $i)]) ? $_GET[("chk" . $i)] : "";
    if($chk != "") array_push($chkInput, $chk);
  }

  //First we need to parse the game state from the file
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "DecisionQueue.php";
  include "../WriteLog.php";
  include 'BoosterGenerator.php';
  include 'DraftLogic.php';
  include 'WTRTierList.php';
  $makeCheckpoint = 0;

  ProcessCommand($playerID, $mode, $cardID);

  //Now we can process the command
  function ProcessCommand($playerID, $mode, $cardID)
  {
    switch($mode) {
      case 1: //CHOOSECARD
        $myDQ = &GetZone($playerID, "DecisionQueue");
        if($myDQ[0] != "CHOOSECARD") break;
        $options = explode(",", $myDQ[1]);
        $found = -1;
        for($i=0; $i<count($options) && $found == -1; ++$i)
        {
          if($cardID == $options[$i]) $found = $i;
        }
        if($found >= 0) {
          //Player actually has the card, now do the effect
          //First remove it from their hand
          unset($options[$found]);
          $options = array_values($options);
        }
        ClearPhase($playerID);
        ContinueDecisionQueue($playerID, $cardID . "-" . implode(",", $options));
        break;
      case 99: //Pass
        PassInput();
        break;
      case 10000:
        RevertGamestate();
        $skipWriteGamestate = true;
        WriteLog("Player " . $playerID . " undid their last action.");
        break;
      case 10001:
        RevertGamestate("preBlockBackup.txt");
        $skipWriteGamestate = true;
        WriteLog("Player " . $playerID . " undid their blocks.");
        break;
    }
  }

  $changeMade = true;
  while($changeMade)
  {
    $changeMade = false;
    for($i=1; $i<=$numPlayers; ++$i)
    {
      if($i == $playerID) continue;
      $dq = &GetZone($i, "DecisionQueue");
      if(count($dq) > 0)
      {
        if($dq[0] == "CHOOSECARD")
        {
          $options = explode(",", $dq[1]);
          $pick = GetWTRPick($options);
          ProcessCommand($i, 1, $pick);
          $changeMade = true;
        }
      }
    }
  }

  include "WriteGamestate.php";

  if($makeCheckpoint) MakeGamestateBackup();

  header("Location: " . $redirectPath . "/WTRDraftFiles/NextPick.php?gameName=$gameName&playerID=" . $playerID);

  exit;

?>
