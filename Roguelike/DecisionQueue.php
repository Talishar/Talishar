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
        WriteLog($parameter1);
        $params = explode("-", $parameter1);
        $encounter = &GetZone($player, "Encounter");
        $encounter[0] = $params[0];
        $encounter[1] = $params[1];
        InitializeEncounter($player, $encounter[0], $encounter[1]);
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
