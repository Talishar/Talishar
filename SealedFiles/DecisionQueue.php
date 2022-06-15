<?php

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
    if($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter1 == "<-" ? $lastResult : $parameter1), $parameter2, $parameter3, $lastResult);
    if(strval($return) != "NOTSTATIC") ClearPhase($player);
    if($parameter1 == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS";//Collapse the rest of the queue if this decision point has invalid parameters
    if(is_array($return) || strval($return) != "NOTSTATIC")
    {
      ContinueDecisionQueue($player, $return);
    }
  }

  function DecisionQueueStaticEffect($phase, $player, $parameter1, $parameter2, $parameter3, $lastResult)
  {
    global $numPlayers;
    switch($phase)
    {
      case "DRAFTPASS":
        $results = explode("-", $lastResult);
        $chosen = $results[0];
        $remaining = $results[1];
        $packData = &GetZone($player, "PackData");
        ++$packData[1];
        $chosenCards = &GetZone($player, "ChosenCards");
        array_push($chosenCards, $chosen);
        if($remaining != "")
        {
          $nextPlayer = ($parameter1 == "L" ? $player - 1 : $player + 1);
          if($nextPlayer == 0) $nextPlayer = $numPlayers;
          else if($nextPlayer > $numPlayers) $nextPlayer = 1;
          AddDecisionQueue("CHOOSECARD", $nextPlayer, $remaining);
          AddDecisionQueue("DRAFTPASS", $nextPlayer, $parameter1);
        }
        if($packData[0] < 3 && AllPlayersFinished())
        {
          for($i=1; $i<=$numPlayers; ++$i)
          {
            $packData = &GetZone($i, "PackData");
            $booster = implode(",", explode(" ", GenerateWTRBooster()));
            AddDecisionQueue("CHOOSECARD", $i, $booster);
            AddDecisionQueue("DRAFTPASS", $i, ($parameter1 == "L" ? "R" : "L"));
            ++$packData[0];
            $packData[1] = 1;
          }
        }
        return 1;
      default:
        return "NOTSTATIC";
    }
  }

?>