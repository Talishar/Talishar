<?php

include "CardDictionary.php";

function BlessingOfDeliveranceDestroy($amount)
{
  global $playerID, $mainPlayer, $mainDeck, $mainHealth;
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
  $log = "Emerging Power gives the next Guardian attack this turn +" . AttackValue($cardID) . ".";
  AddCurrentTurnEffect($cardID, $mainPlayer);
  return $log;
}

function PummelHit()
{
  global $defHand, $defPlayer;
  if(count($defHand) > 0)
  {
    AddDecisionQueue("DISCARD", $defPlayer);
  }
}

function AddCurrentTurnEffect($cardID, $player)
{
  global $currentTurnEffects;
  array_push($currentTurnEffects, $cardID);
  array_push($currentTurnEffects, $player);
}

function AddNextTurnEffect($cardID, $player)
{
  global $nextTurnEffects;
  array_push($nextTurnEffects, $cardID);
  array_push($nextTurnEffects, $player);
}

function AddDecisionQueue($card, $player)
{
  global $decisionQueue;
  array_push($decisionQueue, $card);
  array_push($decisionQueue, $player);
}

function DefenderArsenalToBottomOfDeck()
{
  global $defArsenal, $defDeck;
  array_push($defDeck, $defArsenal);
  $defArsenal = "";
}

function DefenderArsenalToDiscard()
{
  global $defArsenal;
  $defArsenal = "";
  //TODO: Add to discard
}

function Opt($cardID, $amount)
{
  global $playerID, $myDeck, $turn;
  if($amount <= 0) return;
  $cards = "";
  for($i=0; $i<$amount; ++$i)
  {
    $cards .= array_shift($myDeck);
    if($i < $amount-1) $cards .= ",";
  }
  UpdateGameState($playerID);
  $turn[2] = $turn[0];
  $turn[3] = $cardID;
  $turn[4] = $turn[1];
  $turn[0] = "OPT";
  $turn[5] = $cards;
  $turn[1] = $playerID;
  BuildMainPlayerGameState();
}

function DiscardRandom()
{
  global $playerID,$myHand,$myDiscard,$myCharacter, $myClassState, $CS_Num6PowDisc;
  if(count($myHand) == 0) return;
  $index = rand() % count($myHand);
  array_push($myDiscard, $myHand[$index]);
  //TODO: Intimidate if Rhinar, other discard stuff
  if(AttackValue($myHand[$index]) >= 6)
  {
    if($myCharacter[0] == "WTR001" || $myCharacter[0] == "WTR002") {//Rhinar
      WriteLog("Rhinar Intimidated.");
      Intimidate();
    }
    ++$myClassState[$CS_Num6PowDisc];
  }
  unset($myHand[$index]);
  $myHand = array_values($myHand);
  UpdateGameState($playerID);
};

function Intimidate()
{
  global $playerID, $defPlayer, $theirHand;//For now we'll assume you can only intimidate the opponent
  if(count($theirHand) == 0) return;//Nothing to do if they have no hand
  $index = rand() % count($theirHand);
  Banish($defPlayer, $theirHand[$index], "INT");
  unset($theirHand[$index]);
  $theirHand = array_values($theirHand);
  WriteLog("Intimidate triggered " . count($theirHand));
  UpdateGameState($playerID);
}

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

