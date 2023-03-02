<?php

include "EncounterPriorityValues.php";
include "EncounterPriorityLogic.php";
include "EncounterPlayLogic.php";

function EncounterAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $mainPlayer, $mainPlayerGamestateStillBuilt, $combatChain;
  $currentPlayerIsAI = ($currentPlayer == 2 && IsEncounterAI($p2CharEquip[0])) ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    $isBowActive = false;
    for($logicCount=0; $logicCount<=10 && $currentPlayerIsAI; ++$logicCount)
    {
      global $turn;
      $hand = &GetHand($currentPlayer);
      $character = &GetPlayerCharacter($currentPlayer);
      $arsenal = &GetArsenal($currentPlayer);
      $resources = &GetResources($currentPlayer);
      $items = &GetItems($currentPlayer);
      $allies = &GetAllies($currentPlayer);
      //LogHandArray($hand);
      if(count($decisionQueue) > 0)
      {
        if($isBowActive)//was the last action a bow action?
        {
          $optionIndex = 0;
          $index = 0;
          $largestIndex = 0;

          for($i = 0; $i < count($hand); ++$i)//find the highest priority arrow and choose it
          {
            if(CardSubtype($hand[0]) == "Arrow")
            {
              if(GetPriority($hand[$largestIndex], $character[0], 2) <= GetPriority($hand[$i], $character[0], 2))
              {
                $largestIndex = $i;
                $optionIndex = $index;
              }
              ++$index;
            }
          }
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[$optionIndex]);
        }
        else
        {
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[0]);//Just pick the first option
        }
      }
      else if($turn[0] == "B")//The player is attacking the AI
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, "Block"); //Generate the priority values array. Found in EncounterPriorityLogic.php
        //LogPriorityArray($priortyArray);
        $found = false;
        while (count($priortyArray) > 0 && !$found) { //Grabs items from the array until it finds one it can play.
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray); //grabs the last item in the array (highest priority), and removes it from the array, storing it in $storedPriorityNode
          //WriteLog("CardID=" . $storedPriorityNode[0] . ", Where=" . $storedPriorityNode[1] . ", Index=" . $storedPriorityNode[2] . ", Priority=" . $storedPriorityNode[3]);
          if(CardIsBlockable($storedPriorityNode)) $found = true; //If the card can be played/blocked with/activated. Found in EncounterPlayLogic.php
        }
        $health = &GetHealth($currentPlayer);
        //If something was found, that thing is able to block (not prio 0), and either the attack is lethal or the AI wants to block with it efficiently, it attempts to block. Otherwise it passes.
        if($found == true && $storedPriorityNode[3] != 0 &&
((CachedTotalAttack() - CachedTotalBlock() >= $health && $storedPriorityNode[3] != 0) || (CachedTotalAttack() - CachedTotalBlock() >= BlockValue($storedPriorityNode[0]) && 2.1 <= $storedPriorityNode[3] && $storedPriorityNode[3] <= 2.9)))
        {
          BlockCardAttempt($storedPriorityNode); //attempts to play the card. Found in EncounterPlayLogic.php;
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer)//AIs turn
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, "Action");
        //LogPriorityArray($priortyArray);
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          //WriteLog("CardID=" . $storedPriorityNode[0] . ", Where=" . $storedPriorityNode[1] . ", Index=" . $storedPriorityNode[2] . ", Priority=" . $storedPriorityNode[3]);
          if(CardIsPlayable($storedPriorityNode, $hand, $resources)) $found = true;
        }
        if($found == true && $storedPriorityNode[3] != 0)
        {
          if(CardSubtype($storedPriorityNode[0]) == "Bow" ) $isBowActive = true;
          PlayCardAttempt($storedPriorityNode);
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "A" && $mainPlayer == $currentPlayer)//attack reaction phase
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, "Reaction");
        //LogPriorityArray($priortyArray);
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          //WriteLog("CardID=" . $storedPriorityNode[0] . ", Where=" . $storedPriorityNode[1] . ", Index=" . $storedPriorityNode[2] . ", Priority=" . $storedPriorityNode[3]);
          if(ReactionCardIsPlayable($storedPriorityNode, $hand, $resources)) $found = true;
        }
        if($found == true && $storedPriorityNode[3] != 0)
        {
          PlayCardAttempt($storedPriorityNode);
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "P" && $mainPlayer == $currentPlayer)//pitch phase
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, "Pitch");
        //LogPriorityArray($priortyArray);
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          //WriteLog("CardID=" . $storedPriorityNode[0] . ", Where=" . $storedPriorityNode[1] . ", Index=" . $storedPriorityNode[2] . ", Priority=" . $storedPriorityNode[3]);
          if(CardIsPitchable($storedPriorityNode)) $found = true;
        }
        if($found == true && $storedPriorityNode[3] != 0)
        {
          PitchCardAttempt($storedPriorityNode);
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "PDECK" && $mainPlayer == $currentPlayer)//choosing which card to bottom from pitch
      {
        $pitch = &GetPitch($currentPlayer);
        ProcessInput($currentPlayer, 6, "", $pitch[0], 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "ARS" && $mainPlayer = $currentPlayer)//choose a card to arsenal
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, "ToArsenal");
        //LogPriorityArray($priortyArray);
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          //WriteLog("CardID=" . $storedPriorityNode[0] . ", Where=" . $storedPriorityNode[1] . ", Index=" . $storedPriorityNode[2] . ", Priority=" . $storedPriorityNode[3]);
          if(CardIsArsenalable($storedPriorityNode)) $found = true;
        }
        if($found == true && $storedPriorityNode[3] != 0)
        {
          ArsenalCardAttempt($storedPriorityNode);
        }
        else
        {
          PassInput();
        }
      }
      else
      {
        PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
      if($logicCount == 10 && $currentPlayerIsAI)
      {
        for($i=0; $i<=10 && $currentPlayerIsAI; ++$i)
        {
          PassInput();
          $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
        }
      }
    }
  }
}

function IsEncounterAI($enemyHero)
{
  return str_contains($enemyHero, "ROGUE");
}

function LogPriorityArray($priorityArray)
{
  for($i = 0; $i < count($priorityArray); ++$i)
  {
    WriteLog("[" . $priorityArray[$i][0] . "," . $priorityArray[$i][1] . "," . $priorityArray[$i][2] . "," . $priorityArray[$i][3] . "]");
  }
}

function LogHandArray($hand)
{
  $rv = "Hand=[";
  for($i = 0; $i < count($hand); ++$i)
  {
    if($i != 0) $rv.=",";
    $rv.=$hand[$i];
  }
  WriteLog($rv . "]");
}
?>
