<?php

include "EncounterPriorityValues.php";
include "EncounterPriorityLogic.php";
include "EncounterPlayLogic.php";
include_once "PlayerMacros.php";

function EncounterAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $mainPlayer, $mainPlayerGamestateStillBuilt, $actionPoints;
  $AIDebug = false;
  $currentPlayerIsAI = ($currentPlayer == 2 && IsEncounterAI($p2CharEquip[0])) ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    $isBowActive = false;
    for($logicCount=0; $logicCount<=30 && $currentPlayerIsAI; ++$logicCount)
    {
      global $turn;
      FixHand($currentPlayer);
      $hand = &GetHand($currentPlayer);
      $character = &GetPlayerCharacter($currentPlayer);
      $arsenal = &GetArsenal($currentPlayer);
      $resources = &GetResources($currentPlayer);
      $items = &GetItems($currentPlayer);
      $allies = &GetAllies($currentPlayer);
      $banish = &GetBanish($currentPlayer);
      CacheCombatResult();
      if(count($decisionQueue) > 0)
      {
        global $EffectContext;
        if($EffectContext == "bloodrot_pox")
        {
          ContinueDecisionQueue("NO");
          continue;
        }
        if($decisionQueue[0] == "SHIVER")
        {
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[1]);
        }
        if($isBowActive)
        {
          $optionIndex = 0;
          $index = 0;
          $largestIndex = 0;

          for($i = 0; $i < count($hand); ++$i)
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
        else if($turn[0] == "INPUTCARDNAME")
        {
          if($AIDebug) WriteLog("AI Branch - Input Arcane");
          ProcessInput($currentPlayer, 30, "-", 0, 0, "-", false, "Crouching Tiger");
        }
        else
        {
          if($AIDebug) WriteLog("AI Branch - DQ First Option");
          $options = explode(",", $turn[2]);
          $choice = $options[0];
          if($turn[0] == "CHOOSEDECK" || $turn[0] == "MAYCHOOSEDECK") {
            $deck = &GetDeck($currentPlayer);
            $choice = $deck[$choice*DeckPieces()];
          }
          ContinueDecisionQueue($choice);
        }
      }
      else if($turn[0] == "B")
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Block");
        $found = false;
        $skippedReserved = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          if(CardIsBlockable($storedPriorityNode)) {
            // Ira reservation: skip cards needed for the offensive chain when
            // the remaining damage is survivable. Falls through to a lower-
            // priority unreserved blocker if one exists.
            if ($character[0] === "ira_crimson_haze"
                && $storedPriorityNode[1] == "Hand"
                && IraIsCardReservedForOffense($storedPriorityNode[0], $currentPlayer)
                && IraReservationSurvivable($currentPlayer)) {
              $skippedReserved = true;
              continue;
            }
            $found = true;
          }
        }
        $health = &GetHealth($currentPlayer);
        if(ShouldBlock($found, $storedPriorityNode))
        {
          BlockCardAttempt($storedPriorityNode);
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer && $actionPoints > 0)
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Action");
        $iraAttackMode = (IsFirstTurn() && $mainPlayer == $currentPlayer)
          ? true
          : IraIsInAttackMode($currentPlayer);
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          if(CardIsPlayable($storedPriorityNode, $hand, $resources))
          {
            if($storedPriorityNode[0] != "Hand" || count($hand) > 1)
            {
              if ($storedPriorityNode[1] == "Hand" && CardCost($storedPriorityNode[0]) > 0) {
                 if (!IraCanFundCostlyAttack($storedPriorityNode, $hand, $resources)) continue;
               }
                && $storedPriorityNode[1] == "Hand"
                && PitchValue($storedPriorityNode[0]) == 3
                && !HasGoAgain($storedPriorityNode[0])) {
                continue;
              }
              $found = true;
            }
          }
        }
        if($found == true && $storedPriorityNode[3] != 0)
        {
          if(CardSubtype($storedPriorityNode[0]) == "Bow" ) $isBowActive = true;
          PlayCardAttempt($storedPriorityNode);
          CacheCombatResult();
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "A" && $mainPlayer == $currentPlayer)
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Reaction");
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          if(ReactionCardIsPlayable($storedPriorityNode, $hand, $resources)) $found = true;
        }
        if($found == true && $storedPriorityNode[3] != 0)
        {
          PlayCardAttempt($storedPriorityNode);
          CacheCombatResult();
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "D" && $mainPlayer != $currentPlayer)
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Reaction");
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
          if(ReactionCardIsPlayable($storedPriorityNode, $hand, $resources)) $found = true;
        }
        $threatened = CachedTotalPower() - CachedTotalBlock();
        if($found == true && ShouldBlock($found, $storedPriorityNode) && $storedPriorityNode[3] != 0)
        {
          PlayCardAttempt($storedPriorityNode);
          CacheCombatResult();
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "P" && $mainPlayer == $currentPlayer)
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Pitch");
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
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
      else if($turn[0] == "DYNPITCH" && $mainPlayer == $currentPlayer)
      {
        $options = explode(",", $turn[2] ?? "");
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Pitch");
        $bestIdx = -1;
        $bestPriority = 0;
        foreach ($priortyArray as $node) {
          if ($node[1] != "Hand") continue;
          if ($node[3] <= $bestPriority) continue;
          if (!in_array((string)$node[2], $options, true)) continue;
          $bestIdx = $node[2];
          $bestPriority = $node[3];
        }
        if ($bestIdx >= 0) {
          ContinueDecisionQueue((string)$bestIdx);
        } else if (count($options) > 0 && $options[0] !== "") {
          ContinueDecisionQueue($options[0]);
        } else {
          PassInput();
        }
      }
      else if($turn[0] == "PDECK")
      {
        $pitch = &GetPitch($currentPlayer);
        ProcessInput($currentPlayer, 6, "", $pitch[0], 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "ARS" && $mainPlayer == $currentPlayer)
      {
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "ToArsenal");
        $found = false;
        while (count($priortyArray) > 0 && !$found) {
          $storedPriorityNode = $priortyArray[count($priortyArray)-1];
          array_pop($priortyArray);
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
      else if($turn[0] == "OPT" && $mainPlayer == $currentPlayer)
      {
        $options = explode(",", $turn[2]);
        ProcessInput($currentPlayer, 107, $options[0], 0, 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "HANDTOPBOTTOM" && $mainPlayer == $currentPlayer)
      {
        $options = explode(",", $turn[2]);
        ProcessInput($currentPlayer, 12, $options[0], 0, 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "CHOOSEBOTTOM")
      {
        if($AIDebug) WriteLog("AI Branch - Hand Choose Bottom");
        $options = explode(",", $turn[2]);
        ContinueDecisionQueue($options[0]);
      }
      else
      {
        if($AIDebug) WriteLog("AI Branch - Pass");
        PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
      if($logicCount == 30 && $currentPlayerIsAI)
      {
        for($i=0; $i<=30 && $currentPlayerIsAI; ++$i)
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
  global $p2IsAI;
  return $p2IsAI == "1";
}

function IraReservationSurvivable($playerID)
{
  $health     = &GetHealth($playerID);
  $threatened = CachedTotalPower() - CachedTotalBlock();
  return ($health - $threatened) > 3;
}

function ShouldBlock($found, $storedPriorityNode)
{
  global $currentPlayer;
  $health     = &GetHealth($currentPlayer);
  $threatened = CachedTotalPower() - CachedTotalBlock();

  if (!$found || $threatened <= 0) return false;

  if ($storedPriorityNode[1] == "Character" && NumEquipBlock() == 0 && HaveUnblockedEquip($currentPlayer)) {
    return true;
  }

  if ($threatened >= $health) return true;

  if ($health <= 3) return true;

  if (IsFirstTurn()) return true;

  $character = &GetPlayerCharacter($currentPlayer);
  if ($character[0] === "ira_crimson_haze"
      && $storedPriorityNode[1] == "Hand"
      && IraIsCardReservedForOffense($storedPriorityNode[0], $currentPlayer)
      && IraReservationSurvivable($currentPlayer)) {
    return false;
  }

  $cardBlockValue = BlockValue($storedPriorityNode[0]);
  if ($cardBlockValue <= 0) return false;

  if ($cardBlockValue > $threatened + 1) return false;

  $goAgain = DoesAttackHaveGoAgain();

  if ($goAgain && CachedTotalBlock() > 0) {
    $damageAfterThisBlock = $threatened - $cardBlockValue;
    if ($damageAfterThisBlock <= 2) return false;
  }

  $opponent          = ($currentPlayer == 1) ? 2 : 1;
  $opponentHandSize  = count(GetHand($opponent));
  $opponentHasArsenal = !ArsenalEmpty($opponent);
  $moreAttacksComing = $goAgain || $opponentHandSize > 0 || $opponentHasArsenal;

  $cardAttack     = PowerValue($storedPriorityNode[0]);
  $cardPitch      = PitchValue($storedPriorityNode[0]);
  $offensiveValue = max($cardAttack, $cardPitch);
  $safeHP         = $health > 10;

  if ($threatened <= 2 && $offensiveValue >= 3 && $safeHP && !$moreAttacksComing) {
    return false;
  }

  return true;
}

function IsFirstTurn()
  global $mainPlayer, $firstPlayer, $currentTurn;
  return $mainPlayer == $firstPlayer && $currentTurn == 0;
}
