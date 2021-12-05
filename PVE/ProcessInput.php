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
  include "../CardDictionary.php";
  include "PVEDictionary.php";
  $makeCheckpoint = 0;
  $skipWriteGamestate = false;

  ProcessCommand($playerID, $mode, $cardID);

  if($mode != 10000) MakeGamestateBackup();

  //Now we can process the command
  function ProcessCommand($playerID, $mode, $cardID)
  {
    global $buttonInput, $skipWriteGamestate;
    switch($mode) {
      case 0: //Attack
          if(!CheckDeck($mode, $cardID)) return;
          $bossDeck = &GetGlobalZone("BossDeck");
          $cardID = array_shift($bossDeck);
          $resources = &GetGlobalZone("BossResources");
          $resources[1] += CardCost($cardID);
          $combatChain = &GetGlobalZone("CombatChain");
          array_push($combatChain, $cardID);
          array_push($combatChain, 0);
        break;
      case 1: //Pitch
          $turn = &GetGlobalZone("Turn");
          if(!CheckDeck($mode, $cardID)) return;
          $bossDeck = &GetGlobalZone("BossDeck");
          $pitch = &GetGlobalZone("BossPitch");
          $resources = &GetGlobalZone("BossResources");
          $cardID = array_shift($bossDeck);
          $resources[0] += PitchValue($cardID);
          array_push($pitch, $cardID);
          if($turn[1] == "0") ++$turn[2];
        break;
      case 2: //Block
          $turn = &GetGlobalZone("Turn");
          if(!CheckDeck($mode, $cardID)) return;
          $bossDeck = &GetGlobalZone("BossDeck");
          $combatChain = &GetGlobalZone("CombatChain");
          array_push($combatChain, array_shift($bossDeck));
          array_push($combatChain, 0);
          ++$turn[2];
        break;
      case 3: //Close Chain
          $combatChain = &GetGlobalZone("CombatChain");
          $bossDiscard = &GetGlobalZone("BossDiscard");
          $barricades = &GetGlobalZone("Barricades");
          for($i=count($combatChain)-1; $i>=0; --$i)
          {
            $cardID = array_shift($combatChain);
            $counters = array_shift($combatChain);
            if(CardType($cardID) != "B") array_push($bossDiscard, $cardID);
            else
            {
              array_push($barricades, $cardID);
              array_push($barricades, $counters);
            }
          }
        break;
      case 4: //Clear Pitch
          $pitch = &GetGlobalZone("BossPitch");
          $bossDeck = &GetGlobalZone("BossDeck");
          for($i=count($pitch)-1; $i>=0; --$i)
          {
            array_push($bossDeck, array_shift($pitch));
          }
          $resources = &GetGlobalZone("BossResources");
          $resources[0] = 0;
          $resources[1] = 0;
        break;
      case 5: //Flip Stance
        $stance = &GetGlobalZone("Stance");
        $stanceDeck = &GetGlobalZone("StanceDeck");
        if(count($stanceDeck) == 0)
        {
          while(count($stance) > 0) array_unshift($stanceDeck, array_shift($stance));
          AddDecisionQueue("SHUFFLEGLOBALZONE", 1, "StanceDeck");
        }
        AddDecisionQueue("FLIPSTANCE", 1, "-");
        ProcessDecisionQueue(1);
        break;
      case 6: //Add Barricade
          $bossCharacter = &GetGlobalZone("BossCharacter");
          $barricades = &GetGlobalZone("Barricades");
          array_push($barricades, BossBarricade($bossCharacter[0]));
          array_push($barricades, 0);
        break;
      case 7: //Add Barricade to Chain
          $barricades = &GetGlobalZone("Barricades");
          $index = $buttonInput;
          if($index >= count($barricades)) break;
          $barricadeID = $barricades[$index];
          $counters = $barricades[$index+1];
          for($i=$index+1; $i >= $index; --$i)
          {
            unset($barricades[$i]);
          }
          $barricades = array_values($barricades);
          $combatChain = &GetGlobalZone("CombatChain");
          array_push($combatChain, $barricadeID);
          array_push($combatChain, $counters);
        break;
      case 8: //Destroy Barricade
          $barricades = &GetGlobalZone("Barricades");
          $index = $buttonInput;
          if($index >= count($barricades)) break;
          for($i=$index+1; $i >= $index; --$i)
          {
            unset($barricades[$i]);
          }
          $barricades = array_values($barricades);
        break;
      case 9: //Modify Health
        $bossHealth = &GetGlobalZone("BossHealth");
        $bossHealth[0] += $buttonInput;
        break;
      case 10: //Remove from chain
        $combatChain = &GetGlobalZone("CombatChain");
        $index = $buttonInput;
        if($index >= count($combatChain)) break;
        $barricadeID = $combatChain[$index];
        $counters = $combatChain[$index+1];
        for($i=$index+1; $i >= $index; --$i)
        {
          unset($combatChain[$i]);
        }
        $combatChain = array_values($combatChain);
        $barricades = &GetGlobalZone("Barricades");
        array_push($barricades, $barricadeID);
        array_push($barricades, $counters);
        break;
      case 11: //-1 Counter
        $combatChain = &GetGlobalZone("CombatChain");
        $index = $buttonInput;
        if($index >= count($combatChain)) break;
        --$combatChain[$index+1];
        break;
      case 12: //Clear Status
        $bossStatus = &GetGlobalZone("BossStatus");
        $bossStatus[0] = 0;
        break;
      case 13: //Swap Turn
        $turn = &GetGlobalZone("Turn");
        $turn[0] = $turn[0] == "1" ? "0" : "1";
        if($turn[0] == "0") $turn[2] = 0;
        break;
      case 14: //Add counter to boss character card
          $params = explode("_", $buttonInput);
          $bossCharacter = &GetGlobalZone("BossCharacter");
          $bossCharacter[$params[0]+1] += $params[1];
          break;
      case 99: //Pass
        PassInput();
        break;
      case 10000:
        RevertGamestate();
        //$skipWriteGamestate = true;
        //WriteLog("Player " . $playerID . " undid their last action.");
        break;
    }
  }

  if($mode != 10000) include "WriteGamestate.php";

  //WriteCache($gameName, $playerID);

  header("Location: " . $redirectPath . "/PVE/PVETurn.php?gameName=$gameName&playerID=" . $playerID);

  exit;

  function CheckDeck($mode, $cardID)
  {
    $bossDeck = &GetGlobalZone("BossDeck");
    if(count($bossDeck) == 0)
    {
      Recover($mode, $cardID);
      return false;
    }
    return true;
  }

  function MakeGamestateBackup($filename="gamestateBackup.txt")
  {
    global $gameName;
    $filepath = "./Games/" . $gameName . "/";
    copy($filepath . "GameFile.txt", $filepath . $filename);
  }

  function RevertGamestate($filename="gamestateBackup.txt")
  {
    global $gameName;
    $filepath = "./Games/" . $gameName . "/";
    copy($filepath . $filename, $filepath . "GameFile.txt");
  }

?>

