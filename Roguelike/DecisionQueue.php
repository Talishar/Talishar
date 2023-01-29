<?php

include "EncounterDictionary.php";

function ClearPhase($player)
{
  $decisionQueue = &GetZone($player, "DecisionQueue");
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
}

function AddDecisionQueue($phase, $player, $parameter1="-", $parameter2="-", $parameter3="-", $subsequent=0, $makeCheckpoint=0)
{
  $decisionQueue = &GetZone($player, "DecisionQueue");
  array_push($decisionQueue, $phase);
  array_push($decisionQueue, $parameter1);
  array_push($decisionQueue, $parameter2);
  array_push($decisionQueue, $parameter3);
  array_push($decisionQueue, $subsequent);
  array_push($decisionQueue, $makeCheckpoint);
}

function PrependDecisionQueue($phase, $player, $parameter1="-", $parameter2="-", $parameter3="-", $subsequent=0, $makeCheckpoint=0)
{
  $decisionQueue = &GetZone($player, "DecisionQueue");
  array_unshift($decisionQueue, $makeCheckpoint);
  array_unshift($decisionQueue, $subsequent);
  array_unshift($decisionQueue, $parameter3);
  array_unshift($decisionQueue, $parameter2);
  array_unshift($decisionQueue, $parameter1);
  array_unshift($decisionQueue, $phase);
}

  function ProcessDecisionQueue($player)
  {
    ContinueDecisionQueue($player);
  }

  //Must be called with the my/their context
  function ContinueDecisionQueue($player, $lastResult="")
  {
    global $makeCheckpoint;
    $decisionQueue = &GetZone($player, "DecisionQueue");
    if(count($decisionQueue) == 0)
    {
      return;
    }
    $phase = $decisionQueue[0];
    $parameter1 = $decisionQueue[1];
    $parameter2 = $decisionQueue[2];
    $parameter3 = $decisionQueue[3];
    $subsequent = $decisionQueue[4];
    $makeCheckpoint = $decisionQueue[5];
    $return = "PASS";
    ClearPhase($player);
    if($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter1 == "<-" ? $lastResult : $parameter1), $parameter2, $parameter3, $lastResult);
    //if(strval($return) != "NOTSTATIC") ClearPhase($player);
    if(strval($return) == "NOTSTATIC") PrependDecisionQueue($phase, $player, $parameter1, $parameter2, $parameter3, $subsequent, $makeCheckpoint);
    if($parameter1 == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS";//Collapse the rest of the queue if this decision point has invalid parameters
    if(strval($return) != "NOTSTATIC")
    {
      ContinueDecisionQueue($player, $return);
    }
  }

  function DecisionQueueStaticEffect($phase, $player, $parameter1, $parameter2, $parameter3, $lastResult)
  {
    global $numPlayers;
    switch($phase)
    {
      case "SETENCOUNTER":
        $params = explode("-", $parameter1);
        $encounter = &GetZone($player, "Encounter");
        $encounter[0] = $params[0];
        $encounter[1] = $params[1];
        InitializeEncounter($player);
        return 1;
      case "CAMPFIRE":
        switch($lastResult)
        {
          case "Rest":
            $health = &GetZone($player, "Health");
            $gain = (20 - $health[0] > 10 ? 10 : 20 - $health[0]);
            if($gain < 0) $gain = 0;
            $health[0] += $gain;
            WriteLog("You rested and gained " . $gain . " life.");
            break;
          case "Learn":
            WriteLog("You studied and learned a powerful specialization.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR119,DVR008,WTR121");
            break;
          case "Reflect":
            WriteLog("You reflected on the trials of the day, and may remove a card.");
            PrependDecisionQueue("REMOVEDECKCARD", $player, "-");
            PrependDecisionQueue("CHOOSEDECKCARD", $player, "-");
            break;
          default: break;
        }
      case "BATTLEFIELD":
        switch($lastResult)
        {
          case "Loot":
            WriteLog("You've found some equipment to salvage.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR155");
            break;
          case "Pay_Respects":
            WriteLog("You've found a new sense of peace and reflection.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR163");
            break;
          default: break;
        }
      case "LIBRARY":
        switch($lastResult)
        {
          case "Search":
            WriteLog("You searched the library and found an interesting book about fighting techniques.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4));
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4));
            break;
          case "Leave":
            break;
        }
      case "BACKGROUND":
        switch($lastResult)
        {
          case "Cintari_Saber_Background":
            WriteLog("Mighty fine pair of swords there, eh?");
            PrependDecisionQueue("CHOOSECARD", $player, "CRU079");
            PrependDecisionQueue("CHOOSECARD", $player, "CRU080");
            $deck = &GetZone($player, "Deck");
            $character = &GetZone($player, "Character");
            $encounter = &GetZone($player, "Encounter");
            $encounter[7] = "Saber";
            //array_push($character, "CRU079");
            //array_push($character, "CRU080");
            array_push($deck, "EVR060");
            break;
          case "Dawnblade_Background":
            //WriteLog("Beautiful tool there, eh?");
            //PrependDecisionQueue("CHOOSECARD", $player, "WTR115");
            $deck = &GetZone($player, "Deck");
            $character = &GetZone($player, "Character");
            $encounter = &GetZone($player, "Encounter");
            $encounter[7] = "Dawnblade";
            //array_push($character, "WTR115");
            break;
          case "Anothos_Background":
            break;
          case "Titans_Fist_Background":
            break;
        }
      case "STARTADVENTURE":
        switch($lastResult)
        {
          case "Change_your_hero":
            AddDecisionQueue("SETENCOUNTER", $player, "002-PickMode");
            break;
          case "Change_your_bounty":
            AddDecisionQueue("SETENCOUNTER", $player, "003-PickMode");
            break;
          case "Begin_adventure":
            $devTest = false;
            if($devTest) AddDecisionQueue("SETENCOUNTER", $player, "202-PickMode"); //set the above line to true and the last argument of this to your encounter to test it.
            else AddDecisionQueue("SETENCOUNTER", $player, "004-PickMode");
            break;
        }
        return 1;
    case "CHOOSEHERO":
      $heroFileArray = file("Heroes/" . $lastResult . ".txt", FILE_IGNORE_NEW_LINES);
        switch($lastResult)
        {
          case "Dorinthea":
            $health = &GetZone($player, "Health");
            array_push($health, 20);//TODO: Base on hero health
            $character = &GetZone($player, "Character");
            $character = explode(" ", $heroFileArray[0]);//TODO: Support multiple heroes
            $deck = &GetZone($player, "Deck");
            $deck = explode(" ", $heroFileArray[1]);//TODO: Support multiple heroes
            $encounter = &GetZone($player, "Encounter");
            $encounter[3] = "Dorinthea";
            break;
          case "Bravo":
            $health = &GetZone($player, "Health");
            array_push($health, 20);//TODO: Base on hero health
            $character = &GetZone($player, "Character");
            $character = explode(" ", "WTR039 ELE202 ELE204");//TODO: Support multiple heroes
            $deck = &GetZone($player, "Deck");
            $deck = explode(" ", "WTR129 WTR145 WTR201 ARC205 CRU093 MON116 MON283 DVR019 DVR022 DVR009 DVR024 CRU186");//TODO: Support multiple heroes
            $encounter = &GetZone($player, "Encounter");
            $encounter[3] = "Bravo";
            break;
        }
        return 1;
      case "CHOOSEADVENTURE":
        switch($lastResult)
        {
          case "Ira":
          $encounter = &GetZone($player, "Encounter");
          $encounter[4] = "Ira";
          break;
        }
        return 1;
      case "VOLTHAVEN":
        switch($lastResult)
        {
          case "Enter_Stream":
            $health = &GetZone($player, "Health");
            if(rand(0,9) < 3)
            {
              $health[0] -= 3;
              if($health[0] < 0) $health[0] = 1;
              WriteLog("You mistimed your jump and got zapped by the energy.");
            }
            else {
              $health[0] += 5;
              if($health[0] > 20) $health[0] = 20;
              WriteLog("You timed your jump perfectly and feel reinvigorated by the stream of energy.");
            }
            break;
          case "Leave":
            break;
        }
        return 1;
      default:
        return "NOTSTATIC";
    }
  }

?>
