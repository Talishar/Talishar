<?php

include "EncounterPriorityLogic.php";
include "EncounterPlayLogic.php";
include_once "PlayerMacros.php";

function EncounterAI()
{
  global $currentPlayer, $decisionQueue, $mainPlayer, $mainPlayerGamestateStillBuilt, $actionPoints;
  $AIDebug = false;
  //$AIDebug = true;
  $currentPlayerIsAI = IsPlayerAI($currentPlayer);
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    $isBowActive = false;
    $deckPieces = DeckPieces();
    $logicLimit = 30;
    for($logicCount=0; $logicCount<=$logicLimit && IsPlayerAI($currentPlayer) && !IsGameOver(); ++$logicCount)
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
      //LogHandArray($hand);
      CacheCombatResult();
      if(count($decisionQueue) > 0)
      {
        if($AIDebug) WriteLog("AI Branch - Decision Queue");
        global $EffectContext;
        if($EffectContext == "bloodrot_pox")
        {
          if($AIDebug) WriteLog("AI Branch - Bloodrot");
          ContinueDecisionQueue("NO");
        }
        else if($decisionQueue[0] == "SHIVER")
        {
          if($AIDebug) WriteLog("AI Branch - Shiver");
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[1]);
        }
        else if($isBowActive)//was the last action a bow action?
        {
          if($AIDebug) WriteLog("AI Branch - Bow Active");
          $optionIndex = 0;
          $index = 0;
          $largestIndex = 0;
          $handCount = count($hand);
          for($i = 0; $i < $handCount; ++$i)//find the highest priority arrow and choose it
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
          if($AIDebug) WriteLog("AI Branch -  Decision Policy");
          $isYesNo = $turn[0] == "YESNO" || $turn[0] == "DOCRANK";
          $options = $isYesNo ? ["YES", "NO"] : explode(",", $turn[2]);
          $decisionContext = $isYesNo ? $turn[2] : ($EffectContext ?? "");
          $choice = BotChooseDecisionOption($turn[0], $options, $currentPlayer, $decisionContext);
          //Some things automatically adjust the data
          if($turn[0] == "CHOOSEDECK" || $turn[0] == "MAYCHOOSEDECK") {
            $deck = &GetDeck($currentPlayer);
            $choice = $deck[$choice * $deckPieces];
          }
          ContinueDecisionQueue($choice);
        }
      }
      else if($turn[0] == "B")//The player is attacking the AI
      {
        if($AIDebug) WriteLog("AI Branch - Block");
        $blocker = BotNextPlannedDefender(BotBestBlockPlan($currentPlayer));
        if($blocker !== null) BotDefendWith($blocker);
        else PassInput();
        ProcessMacros();
        $currentPlayerIsAI = IsPlayerAI($currentPlayer);
        continue;
      }
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer && $actionPoints > 0)//AIs turn
      {
        if($AIDebug) WriteLog("AI Branch - AI's Turn");
        if (BotShouldPreserveOpeningHandForPlayer($currentPlayer)) {
          PassInput();
          ProcessMacros();
          $currentPlayerIsAI = IsPlayerAI($currentPlayer);
          continue;
        }
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Action");
        //LogPriorityArray($priortyArray);
        $found = false;
        while ($priortyArray !== [] && !$found) {
          $storedPriorityNode = array_pop($priortyArray);
          if(CardIsPlayable($storedPriorityNode, $hand, $resources))
          {
            //Only attempt to play the card if you have excess resources compared to what needs to be saved
            if($storedPriorityNode[0] != "Hand" || count($hand) > 1)
            {
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
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer)//out of action points, last window to use an instant speed hero ability
      {
        if($AIDebug) WriteLog("AI Branch - End of Turn Hero Ability");
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Action");
        $found = false;
        while ($priortyArray !== [] && !$found) {
          $storedPriorityNode = array_pop($priortyArray);
          if($storedPriorityNode[1] != "Character" || $storedPriorityNode[2] != 0) continue;
          if($storedPriorityNode[3] <= BotEndOfTurnAbilityThreshold()) continue;
          if(CardIsPlayable($storedPriorityNode, $hand, $resources)) $found = true;
        }
        if($found == true)
        {
          PlayCardAttempt($storedPriorityNode);
          CacheCombatResult();
        }
        else
        {
          PassInput();
        }
      }
      else if($turn[0] == "A" && $mainPlayer == $currentPlayer)//attack reaction phase
      {
        if($AIDebug) WriteLog("AI Branch - Attack Reactions");
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Reaction");
        //LogPriorityArray($priortyArray);
        $found = false;
        while ($priortyArray !== [] && !$found) {
          $storedPriorityNode = array_pop($priortyArray);
          if(ReactionCardIsPlayable($storedPriorityNode, $hand, $resources)) $found = true;
          //WriteLog("CardID=" . $storedPriorityNode[0] . ", Where=" . $storedPriorityNode[1] . ", Index=" . $storedPriorityNode[2] . ", Priority=" . $storedPriorityNode[3] . ", Found=" . $found);
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
      else if($turn[0] == "D" && $mainPlayer != $currentPlayer)//Defense reaction phase
      {
        if($AIDebug) WriteLog("AI Branch - Defense Reactions");
        $reaction = BotNextPlannedDefender(BotBestReactionPlan($currentPlayer));
        if($reaction !== null) BotDefendWith($reaction);
        else PassInput();
        ProcessMacros();
        $currentPlayerIsAI = IsPlayerAI($currentPlayer);
        continue;
      }
      else if($turn[0] == "P")//pitch phase - the AI also pays here while defending, e.g. for a taxed defense reaction
      {
        if($AIDebug) WriteLog("AI Branch - Pitch");
        $pitchIndex = BotChoosePitchIndex($currentPlayer, max(1, intval($resources[1] ?? 0) - intval($resources[0] ?? 0)));
        if($pitchIndex !== null)
        {
          PitchCardAttempt([$hand[$pitchIndex], "Hand", $pitchIndex, 1]);
          ProcessMacros();
          $currentPlayerIsAI = IsPlayerAI($currentPlayer);
          continue;
        }
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "Pitch");
        //LogPriorityArray($priortyArray);
        $found = false;
        while ($priortyArray !== [] && !$found) {
          $storedPriorityNode = array_pop($priortyArray);
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
      else if($turn[0] == "PDECK")//choosing which card to bottom from pitch
      {
        if($AIDebug) WriteLog("AI Branch - Pitch Deck");
        $pitch = &GetPitch($currentPlayer);
        ProcessInput($currentPlayer, 6, "", $pitch[0], 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "ARS" && $mainPlayer == $currentPlayer)//choose a card to arsenal
      {
        if($AIDebug) WriteLog("AI Branch - Choose Arsenal");
        $priortyArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, "ToArsenal");
        //LogPriorityArray($priortyArray);
        $found = false;
        while ($priortyArray !== [] && !$found) {
          $storedPriorityNode = array_pop($priortyArray);
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
      else if($turn[0] == "OPT" && $mainPlayer == $currentPlayer)
      {
        if($AIDebug) WriteLog("AI Branch - Opt");
        $options = explode(",", $turn[2]);
        ProcessInput($currentPlayer, 107, $options[0], 0, 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "HANDTOPBOTTOM"  && $mainPlayer == $currentPlayer)
      {
        if($AIDebug) WriteLog("AI Branch - Hand Top/Bottom");
        $options = explode(",", $turn[2]);
        ProcessInput($currentPlayer, 12, $options[0], 0, 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "CHOOSEBOTTOM"){
        if($AIDebug) WriteLog("AI Branch - Hand Choose Bottom");
        $options = explode(",", $turn[2]);
        ContinueDecisionQueue(BotChooseDecisionOption($turn[0], $options, $currentPlayer, "bottom"));
      }
      else
      {
        if($AIDebug) WriteLog("AI Branch - Pass");
        PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = IsPlayerAI($currentPlayer);
      if($logicCount == $logicLimit && $currentPlayerIsAI && !IsGameOver() )
      {
        for($i=0; $i<=30 && IsPlayerAI($currentPlayer) && !IsGameOver(); ++$i)
        {
          PassInput();
          $currentPlayerIsAI = IsPlayerAI($currentPlayer);
        }
      }
    }
  }
}

function BotNextPlannedDefender($plan)
{
  static $refused = [];
  if ($plan === null || $plan["score"] <= -1000000) return null;
  foreach ($plan["set"] as $card) {
    $key = $card["zone"] . ":" . $card["index"] . ":" . $card["id"] . ":" . CachedTotalBlock();
    if (isset($refused[$key])) continue;
    $refused[$key] = true;
    return $card;
  }
  return null;
}

function BotDefendWith($card)
{
  $zone = match ($card["zone"]) { "HAND" => "Hand", "CHAR" => "Character", default => "Arsenal" };
  $node = [$card["id"], $zone, $card["index"], 1];
  if ($zone == "Arsenal" || CardType($card["id"]) == "DR") PlayCardAttempt($node);
  else BlockCardAttempt($node);
}

function ShouldBlock($found, $storedPriorityNode)
{
  if(!$found || CachedTotalPower() - CachedTotalBlock() == 0) return false;
  return floatval($storedPriorityNode[3]) > 0;
}

