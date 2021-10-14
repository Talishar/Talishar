<?php

include "CardDictionary.php";
include "CoreLogic.php";

function BlessingOfDeliveranceDestroy($amount)
{
  global $mainPlayer, $mainDeck, $mainHealth;
  $log = "Blessing of Deliverance revealed ";
  $lifegain = 0;
  for($i=0; $i<$amount; ++$i)
  {
    if(count($mainDeck) > $i)
    {
      $log .= $mainDeck[$i] . ($i < 2 ? "," : "") . " ";
      if(CardCost($mainDeck[$i]) >= 3) ++$lifegain;
    }
  }
  $log .= "and gained " . $lifegain . " life.";
  $mainHealth += $lifegain;
  return $log;
}

function EmergingPowerDestroy($cardID)
{
  global $mainPlayer;
  $log = "Emerging Power gives the next Guardian attack this turn +" . EffectAttackModifier($cardID) . ".";
  AddCurrentTurnEffect($cardID, $mainPlayer);
  return $log;
}

function PummelHit()
{
  global $defHand, $defPlayer;
  if(count($defHand) > 0)
  {
    AddDecisionQueue("CHOOSEHAND", $defPlayer, GetDefHandIndices());
    AddDecisionQueue("DISCARDMYHAND", $defPlayer, "-", 1);
  }
}

function KatsuHit($index)
{
  global $mainPlayer;
  AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-1");
  AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
  AddDecisionQueue("DISCARDMYHAND", $mainPlayer, "-", 1);
  AddDecisionQueue("FINDINDICES", $mainPlayer, "WTR076-2", 1);
  AddDecisionQueue("CHOOSEDECK", $mainPlayer, "<-", 1);
  AddDecisionQueue("BANISH", $mainPlayer, "TT", 1);
  AddDecisionQueue("EXHAUSTCHARACTER", $mainPlayer, $index, 1);
}

function LordOfWindIndices()
{
  $array = [];
  $indices = SearchMyDiscardForCard("WTR107", "WTR108", "WTR109");
  if($indices != "") array_push($array, $indices);
  $indices = SearchMyDiscardForCard("WTR110", "WTR111", "WTR112");
  if($indices != "") array_push($array, $indices);
  $indices = SearchMyDiscardForCard("WTR83");
  if($indices != "") array_push($array, $indices);
  return implode(",", $array);
}

function NaturesPathPilgrimageHit()
{
  global $mainPlayer, $mainDeck;
  $deck = &GetDeck($mainPlayer);
  if(!ArsenalFull($mainPlayer) && count($deck) > 0)
  {
    $type = CardType($deck[0]);
    if($type == "A" || $type == "AA")
    {
      AddArsenal($deck[0], $mainPlayer, "DECK", "DOWN");
      array_shift($deck);
    }
  }
}

function UnifiedDecreePlayEffect()
{
  global $myDeck, $mainPlayer;
  if(count($myDeck) == 0) return;
  WriteLog("Unified Decree reveals " . $myDeck[0] . ".");
  if(CardType($myDeck[0]) == "AR")
  {
    BanishCardForPlayer($myDeck[0], $mainPlayer, "DECK", "TCC");
    array_shift($myDeck);
  }
}

function BottomDeck()
{
  global $myHand, $playerID;
  if(count($myHand) > 0)
  {
    AddDecisionQueue("MAYCHOOSEHAND", $playerID, GetMyHandIndices());
    AddDecisionQueue("DISCARDMYHAND", $playerID, "-", 1);
    AddDecisionQueue("ADDBOTTOMMYDECK", $playerID, "-", 1);
  }
}

function BottomDeckDraw()
{
  global $myHand, $playerID;
  if(count($myHand) > 0)
  {
    BottomDeck();
    AddDecisionQueue("DRAW", $playerID, "-", 1);
  }
}

function AddCurrentTurnEffect($cardID, $player)
{
  global $currentTurnEffects;
  array_push($currentTurnEffects, $cardID);
  array_push($currentTurnEffects, $player);
}

//This is needed because if you add a current turn effect from combat, it could get deleted as part of the combat resolution
function AddCurrentTurnEffectFromCombat($cardID, $player)
{
  global $currentTurnEffectsFromCombat;
  array_push($currentTurnEffectsFromCombat, $cardID);
  array_push($currentTurnEffectsFromCombat, $player);
}

function CopyCurrentTurnEffectsFromCombat()
{
  global $currentTurnEffects, $currentTurnEffectsFromCombat;
  for($i=0; $i<count($currentTurnEffectsFromCombat); $i += 2)
  {
    array_push($currentTurnEffects, $currentTurnEffectsFromCombat[$i], $currentTurnEffectsFromCombat[$i+1]);
  }
  $currentTurnEffectsFromCombat = [];
}

function RemoveCurrentTurnEffect($index)
{
  global $currentTurnEffects;
  unset($currentTurnEffects[$index+1]);
  unset($currentTurnEffects[$index]);
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentTurnEffectPieces()
{
  return 2;
}

function AddNextTurnEffect($cardID, $player)
{
  global $nextTurnEffects;
  array_push($nextTurnEffects, $cardID);
  array_push($nextTurnEffects, $player);
}

function HasEffect($cardID)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i += CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $cardID) return true;
  }
  return false;
}

function AddDecisionQueue($phase, $player, $parameter, $subsequent=0, $makeCheckpoint=0)
{
  global $decisionQueue;
  array_push($decisionQueue, $phase);
  array_push($decisionQueue, $player);
  array_push($decisionQueue, $parameter);
  array_push($decisionQueue, $subsequent);
  array_push($decisionQueue, $makeCheckpoint);
}

function PrependDecisionQueue($phase, $player, $parameter, $subsequent=0, $makeCheckpoint=0)
{
  global $decisionQueue;
  array_unshift($decisionQueue, $makeCheckpoint);
  array_unshift($decisionQueue, $subsequent);
  array_unshift($decisionQueue, $parameter);
  array_unshift($decisionQueue, $player);
  array_unshift($decisionQueue, $phase);
}

  function ProcessDecisionQueue()
  {
    global $turn, $decisionQueue;
    $count = count($turn);
    if(count($turn) < 3) $turn[2] = "";
    array_unshift($turn, "", "", "");
    ContinueDecisionQueue("");
  }

  //Must be called with the my/their context
  function ContinueDecisionQueue($lastResult="")
  {
    global $decisionQueue, $turn, $currentPlayer, $mainPlayerGamestateBuilt, $makeCheckpoint, $otherPlayer, $p2ClassState, $myClassState, $theirClassState;
    if(count($decisionQueue) == 0 || $decisionQueue[0] == "RESUMEPLAY" || $decisionQueue[0] == "RESUMEPAYING")
    {
      if($mainPlayerGamestateBuilt) UpdateMainPlayerGameState();
      else if(count($decisionQueue) > 0 && $currentPlayer != $decisionQueue[1]) { UpdateGameState($currentPlayer); }
      array_shift($turn);
      array_shift($turn);
      array_shift($turn);
      if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPLAY")
      {
        if($currentPlayer != $decisionQueue[1])
        {
          $currentPlayer = $decisionQueue[1];
          $otherPlayer = $currentPlayer == 1 ? 2 : 1;
          BuildMyGamestate($currentPlayer);
        }
        $decisionQueue = [];
        PlayCardEffect($turn[2], $turn[3], $turn[4]);
      }
      else if(count($decisionQueue) > 0 && $decisionQueue[0] == "RESUMEPAYING")
      {
        $params = explode("-", $decisionQueue[2]);//Parameter
        $decisionQueue = [];
        if($lastResult == "") $lastResult = 0;
        PlayCard($params[0], $params[1], $lastResult);
      }
      else
      {
        FinalizeAction();
      }
      return;
    }
    $phase = array_shift($decisionQueue);
    $player = array_shift($decisionQueue);
    $parameter = array_shift($decisionQueue);
    $subsequent = array_shift($decisionQueue);
    $makeCheckpoint = array_shift($decisionQueue);
    $turn[0] = $phase;
    $turn[1] = $player;
    $currentPlayer = $player;
    $turn[2] = ($parameter == "<-" ? $lastResult : $parameter);
    $return = "PASS";
    if($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter == "<-" ? $lastResult : $parameter), $lastResult);
    if($parameter == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS";//Collapse the rest of the queue if this decision point has invalid parameters
    if(is_array($return) || strval($return) != "NOTSTATIC")
    {
      ContinueDecisionQueue($return);
    }
    else
    {
      if($mainPlayerGamestateBuilt) UpdateMainPlayerGameState();
    }
  }

  function FinalizeAction()
  {
    global $currentPlayer, $mainPlayer, $otherPlayer, $actionPoints, $turn, $combatChain, $defPlayer, $makeBlockBackup, $mainPlayerGamestateStillBuilt;
    if(!$mainPlayerGamestateStillBuilt) UpdateGameState(1);
    BuildMainPlayerGamestate();
    if($turn[0] == "M")
    {
      if(count($combatChain) > 0)//Means we initiated a chain link
      {
        $turn[0] = "B";
        $currentPlayer = $defPlayer;
        $turn[2] = "";
        $makeBlockBackup = 1;
      }
      else {
        if($actionPoints > 0)
        {
          $turn[0] = "M";
          $currentPlayer = $mainPlayer;
          $turn[2] = "";
        }
        else
        {
          $currentPlayer = $mainPlayer;
          BeginTurnPass();
        }
      }
    }
    else if($turn[0] == "A")
    {
      $turn[0] = "D";
      $currentPlayer = $defPlayer;
      $turn[2] = "";
    }
    else if($turn[0] == "D")
    {
      $turn[0] = "A";
      $currentPlayer = $mainPlayer;
      $turn[2] = "";
    }
    else if($turn[0] == "B")
    {
      $turn[0] = "B";
    }
    return 0;
  }

function GiveAttackGoAgain()
{
  global $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
}

function DefenderTopDeckToArsenal()
{
  global $defPlayer;
  TopDeckToArsenal($defPlayer);
}

function MainTopDeckToArsenal()
{
  global $mainPlayer;
  TopDeckToArsenal($mainPlayer);
}

function TopDeckToArsenal($player)
{
  $deck = &GetDeck($player);
  if(ArsenalFull($player) || count($deck) == 0) return;//Already something there
  AddArsenal(array_shift($deck), $player, "DECK", "DOWN");
  WriteLog("The top card of player " . $player . "'s deck was put in their arsenal.");
}

function DefenderArsenalToBottomOfDeck()
{
  global $defArsenal, $defDeck;
  array_push($defDeck, $defArsenal);
  $defArsenal = "";
}

function ArsenalToBottomDeck($player)
{
  //TODO: Allow to choose arsenal slot
  $arsenal = &GetArsenal($player);
  if(count($arsenal) == 0) return;
  $index = 0;
  AddBottomDeck($arsenal[$index], $player, "ARS");
  for($i=$index+ArsenalPieces()-1; $i>=$index; --$i)
  {
    unset($arsenal[$i]);
  }
  $arsenal = array_values($arsenal);
}

function DestroyArsenal($player)
{
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    AddGraveyard($arsenal[$i], $player, "ARS");
  }
  $arsenal = [];
}

function DiscardHand($player)
{
  $hand = &GetHand($player);
  for($i=0; $i<count($hand); $i+=HandPieces())
  {
    AddGraveyard($hand[$i], $player, "HAND");
  }
  $hand = [];
}

function Opt($cardID, $amount)
{
  global $myDeck, $turn, $currentPlayer;
  if($amount <= 0) return;
  $cards = "";
  for($i=0; $i<$amount; ++$i)
  {
    $cards .= array_shift($myDeck);
    if($i < $amount-1) $cards .= ",";
  }
  AddDecisionQueue("OPT", $currentPlayer, $cards);
}

function OptMain($amount)
{
  global $mainDeck, $turn, $mainPlayer;
  if($amount <= 0) return;
  $cards = "";
  for($i=0; $i<$amount; ++$i)
  {
    $cards .= array_shift($mainDeck);
    if($i < $amount-1) $cards .= ",";
  }
  AddDecisionQueue("OPT", $mainPlayer, $cards);
}

function DiscardRandom()
{
  global $playerID,$myHand,$myDiscard,$myCharacter, $myClassState, $CS_Num6PowDisc, $mainPlayer, $currentPlayer;
  if(count($myHand) == 0) return;
  $index = rand() % count($myHand);
  $discarded = $myHand[$index];
  unset($myHand[$index]);
  $myHand = array_values($myHand);
  AddGraveyard($discarded, $currentPlayer, "HAND");
  if(AttackValue($discarded) >= 6)
  {
    if(($myCharacter[0] == "WTR001" || $myCharacter[0] == "WTR002") && $playerID == $mainPlayer) {//Rhinar
      WriteLog("Rhinar Intimidated.");
      Intimidate();
    }
    ++$myClassState[$CS_Num6PowDisc];
  }
  UpdateGameState($currentPlayer);
  return $discarded;
};

function DefDiscardRandom()
{
  global $defHand,$defDiscard;
  if(count($defHand) == 0) return;
  $index = rand() % count($defHand);
  array_push($defDiscard, $defHand[$index]);
  //TODO: other discard stuff
  unset($defHand[$index]);
  $defHand = array_values($defHand);
};

function Intimidate()
{
  global $defPlayer, $theirHand, $currentPlayer;//For now we'll assume you can only intimidate the opponent
  if(count($theirHand) == 0) return;//Nothing to do if they have no hand
  $otherPlayer == ($currentPlayer == 1 ? 2 : 1);
  $index = rand() % count($theirHand);
  BanishCardForPlayer($theirHand[$index], $otherPlayer, "HAND", "INT");
  unset($theirHand[$index]);
  $theirHand = array_values($theirHand);
  WriteLog("Intimidate triggered " . count($theirHand));
  UpdateGameState($currentPlayer);
}

//Deprecated: Use BanishCard in CardSetters instead
function Banish($player, $cardID, $from)
{
  global $playerID, $myBanish, $theirBanish;
  if($playerID == $player)
  {
    array_push($myBanish, $cardID);
    array_push($myBanish, $from);
  }
  else
  {
    array_push($theirBanish, $cardID);
    array_push($theirBanish, $from);
  }
  //TODO: Banish stuff, e.g. Levia
}

?>

