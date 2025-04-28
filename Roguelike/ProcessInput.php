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
  include '../Libraries/SHMOPLibraries.php';
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "DecisionQueue.php";
  include "../WriteLog.php";
  include "../Libraries/UILibraries.php";
  include "../CardDictionary.php";
  $makeCheckpoint = 0;

  ProcessCommand($playerID, $mode, $cardID, $buttonInput);

  //Now we can process the command
  function ProcessCommand($playerID, $mode, $cardID, $buttonInput)
  {
    switch($mode)
    {
      case 1:{ //CHOOSECARD OR REMOVEDECKCARD
        $myDQ = &GetZone($playerID, "DecisionQueue");
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
          if($myDQ[0] == "CHOOSECARD"){ //If we're ADDING cards
              if(CardType($cardID) == "E" || CardType($cardID) == "W")
             {
                $char = &GetZone($playerID, "Character");
                array_push($char, $cardID);
              }
              else if(CardSubtype($cardID) == "Power") {
                $deck = &GetZone($playerID, "Deck");
                array_push($deck, $cardID);
              }
              else {
                $deck = &GetZone($playerID, "Deck");
                array_push($deck, $cardID);
              }
              WriteLog("You added " . CardLink($cardID, $cardID) . " to your deck.");
          }
          if($myDQ[0] == "REMOVEDECKCARD"){ //If we're REMOVING cards
              $deck = &GetZone($playerID, "Deck");
              for($i=0; $i<count($deck)-1; ++$i)
              {
                if($deck[$i] == $cardID)
                {
                  unset($deck[$i]);
                  $deck = array_values($deck);
                  continue;
                }
              }
              WriteLog("You removed " . CardLink($cardID, $cardID) . " from your deck.");
          }
          if($myDQ[0] == "REMOVEALLDECKCARD"){ //If we're REMOVING all cards
              $deck = &GetZone($playerID, "Deck");
              for($i=0; $i<count($deck)-1; ++$i)
              {
                if($deck[$i] == $cardID)
                {
                  unset($deck[$i]);
                  $deck = array_values($deck);
                }
              }
              WriteLog("You removed all copies of " . CardLink($cardID, $cardID) . " from your deck.");
          }
          if($myDQ[0] == "SHOP"){
            //WriteLog($cardID);
            $encounter = &GetZone($playerID, "Encounter");
            $cost = getShopCost($cardID);
            //WriteLog("cost: " . $cost . ", total: " . $encounter->gold);
            if($cardID == "CardBack")
            {
              $newShop = $myDQ[1];
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("SHOP", $playerID, $newShop, $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
              break;
            }
            else if($encounter->gold < $cost)
            {
              $newShop = $myDQ[1];
              WriteLog("You cannot afford to buy " . CardLink($cardID, $cardID) . ".");
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("SHOP", $playerID, $newShop, $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
              break;
            }
            else
            {
              if(CardType($cardID) == "E" || CardType($cardID) == "W")
              {
                $char = &GetZone($playerID, "Character");
                array_push($char, $cardID);
              }
              else {
                $deck = &GetZone($playerID, "Deck");
                array_push($deck, $cardID);
              }
              WriteLog("You spent " . $cost . " gold and added " . CardLink($cardID, $cardID) . " to your deck.");
              $encounter->gold -= $cost;
              $newShop = "";
              for($j=0;$j<count($options);++$j){
                if($j != 0) $newShop.=",";
                $newShop.=$options[$j];
              }
              $newShop.=",CardBack";
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              //WriteLog($newShop);
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("SHOP", $playerID, $newShop, $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
              break;
            }
          }
          if($myDQ[0] == "DUPLICATECARD")
          {
            $deck = &GetZone($playerID, "Deck");
            array_push($deck, $cardID);
            array_push($deck, $cardID);
            WriteLog("You added " . CardLink($cardID, $cardID) . " to your deck.");
            WriteLog("You added " . CardLink($cardID, $cardID) . " to your deck.");
          }
          ClearPhase($playerID); //Clear the screen and keep going
          ContinueDecisionQueue($playerID, "");
          break;
      }

      case 2: //BUTTONINPUT
        $myDQ = &GetZone($playerID, "DecisionQueue");
        if($myDQ[0] == "CHOOSECARD"){
          if($buttonInput == "Reroll")
          {
            $encounter = &GetZone($playerID, "Encounter");
            if($encounter->rerolls >= 1) {
              WriteLog("You used a reroll to alter the reward");
              $encounter->rerolls -= 1;
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("CHOOSECARD", $playerID, GetRandomCards($parameterOne), $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
            }
            else {
              WriteLog("You attempted to reroll, but fate is not so kind.");
            }
          }
        }
        else if($myDQ[0] == "REMOVEDECKCARD"){
          if($buttonInput == "Reroll")
          {
            $encounter = &GetZone($playerID, "Encounter");
            if($encounter->rerolls >= 1) {
              WriteLog("You used a reroll to alter the reward");
              $encounter->rerolls -= 1;
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("REMOVEDECKCARD", $playerID, GetRandomCards($parameterOne), $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
            }
            else {
              WriteLog("You attempted to reroll, but fate is not so kind.");
            }
          }
        }
        else if($myDQ[0] == "REMOVEALLDECKCARD"){
          if($buttonInput == "Reroll")
          {
            $encounter = &GetZone($playerID, "Encounter");
            if($encounter->rerolls >= 1) {
              WriteLog("You used a reroll to alter the reward");
              $encounter->rerolls -= 1;
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("REMOVEALLDECKCARD", $playerID, GetRandomCards($parameterOne), $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
            }
            else {
              WriteLog("You attempted to reroll, but fate is not so kind.");
            }
          }
        }
        else if($myDQ[0] == "DUPLICATECARD"){
          if($buttonInput == "Reroll")
          {
            $encounter = &GetZone($playerID, "Encounter");
            if($encounter->rerolls >= 1) {
              WriteLog("You used a reroll to alter the reward");
              $encounter->rerolls -= 1;
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("REMOVEDECKCARD", $playerID, GetRandomCards($parameterOne), $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
            }
            else {
              WriteLog("You attempted to reroll, but fate is not so kind.");
            }
          }
        }
        else if($myDQ[0] == "SHOP"){
          $encounter = &GetZone($playerID, "Encounter");
          $health = &GetZone($playerID, "Health");
          if($buttonInput == "shop_heal"){
            $health = &GetZone($playerID, "Health");
            //WriteLog("\$encounter->costToHeal: ".$encounter->costToHeal);

            $gain = (20 - $health[0] > 5 ? 5 : 20 - $health[0]);
            if($gain < 0) $gain = 0;
            if($gain == 0){
              WriteLog("You are already very healthy. You and the healer enjoy a polite conversation, but there's no need to hire them.");
            }
            else if($encounter->gold >= $encounter->costToHeal){ //If the player's gold is less than or equal to the cost to heal
              WriteLog("The local healer patches your wounds. You feel better prepared for your journey ahead! You heal $gain health.");
              $health[0] += $gain;
              $encounter->gold -= $encounter->costToHeal;
              $encounter->costToHeal += 1;
            }
            else{
              WriteLog("You can't afford the services of a healer. You will have to tend to your wounds another time.");
            }
          }
          else if($buttonInput == "shop_reflect"){
            if($encounter->gold >= $encounter->costToRemove){
              WriteLog("The beggar invites you to sit beside him and join in meditation.");
              PrependDecisionQueue("REMOVEDECKCARD", 1, GetRandomDeckCard(1));
              $encounter->gold -= $encounter->costToRemove;
              $encounter->costToRemove += 1;
            }
            else {
              WriteLog("Unfortunately, you don't have much to spare. Perhaps you'll be able to share good fortune another day.");
            }
          }
          else if($buttonInput == "Reroll")
          {
            if($encounter->rerolls >= 1) {
              WriteLog("You used a reroll to alter the shop");
              $encounter->rerolls -= 1;
              $parameterOne = $myDQ[2];
              $parameterTwo = $myDQ[3];
              ClearPhase($playerID); //Clear the screen and keep going
              PrependDecisionQueue("SHOP", $playerID, GetShop($parameterOne), $parameterOne, $parameterTwo);
              ContinueDecisionQueue($playerID, "");
            }
            else {
              WriteLog("You attempted to reroll, but fate is not so kind.");
            }
          }
          else if($buttonInput == "Leave"){
            WriteLog("You decide to leave the village and carry on with your adventure.");
            ClearPhase(1);
            ContinueDecisionQueue(1, $buttonInput);
          }
        }
        else{
          ClearPhase($playerID);
          ContinueDecisionQueue($playerID, $buttonInput);
        }
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
      default:
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
          // $options = explode(",", $dq[1]);
          // $pick = GetELEPick($options); // !Undefined function
          // ProcessCommand($i, 1, $pick); // !Missing argument
          // $changeMade = true;
        }
      }
    }
  }

  include "WriteGamestate.php";

  if($makeCheckpoint) MakeGamestateBackup();

  header("Location: " . $redirectPath . "/Roguelike/NextEncounter.php?gameName=$gameName&playerID=" . $playerID);

  exit;

?>
