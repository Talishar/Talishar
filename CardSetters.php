<?php

function BanishCardForPlayer($cardID, $player, $from, $modifier)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) BanishCard($mainBanish, $mainClassState, $cardID, $modifier);
    else BanishCard($defBanish, $defClassState, $cardID, $modifier);
  }
  else
  {
    if($player == $currentPlayer) BanishCard($myBanish, $myClassState, $cardID, $modifier);
    else BanishCard($theirBanish, $theirClassState, $cardID, $modifier);
  }
}

function BanishCard(&$banish, &$classState, $cardID, $modifier)
{
  global $CS_CardsBanished, $actionPoints;
  if($modifier == "BOOST" && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178")) {
      WriteLog("Back Alley Breakline was banished from your deck face up by an action card. Gained 1 action point.");
      ++$actionPoints;
    }
  array_push($banish, $cardID);
  array_push($banish, $modifier);
  ++$classState[$CS_CardsBanished];
}

function AddBottomMainDeck($cardID, $from)
{
  global $mainDeck;
  array_push($mainDeck, $cardID);
}

function AddBottomMyDeck($cardID, $from)
{
  global $myDeck;
  array_push($myDeck, $cardID);
}

function RemoveTopMyDeck()
{
  global $myDeck;
  if(count($myDeck) == 0) return "";
  return array_shift($myDeck);
}

function AddMainHand($cardID, $from)
{
  global $mainHand;
  array_push($mainHand, $cardID);
}

function AddMyArsenal($cardID, $from, $facing)
{
  global $myArsenal, $currentPlayer, $myClassState, $CS_ArsenalFacing, $actionPoints;
  $myArsenal = $cardID;
  $myClassState[$CS_ArsenalFacing] = $facing;
  if($facing == "UP")
  {
    if($from == "DECK" && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178")) {
      WriteLog("Back Alley Breakline was put into your arsenal from your deck face up. Gained 1 action point.");
      ++$actionPoints;
    }
    switch($cardID)
    {
      case "ARC057": case "ARC058": case "ARC059": AddCurrentTurnEffect($cardID, $currentPlayer); break;
      case "ARC063": case "ARC064": case "ARC065": Opt($cardID, 1); break;
      default: break;
    }
  }
}

function SetMyArsenalFacing($facing)
{
  global $myClassState, $CS_ArsenalFacing;
  $myClassState[$CS_ArsenalFacing] = $facing;
}

function GetMyArsenalFacing()
{
  global $myClassState, $CS_ArsenalFacing;
  return $myClassState[$CS_ArsenalFacing];
}

function SetCCAttackModifier($index, $amount)
{
  global $combatChain;
  $combatChain[$index + 5] += $amount;
}

function AddSoul($cardID, $player, $from)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) AddSpecificSoul($cardID, $mainSoul, $from);
    else AddSpecificSoul($cardID, $defSoul, $from);
  }
  else
  {
    if($player == $currentPlayer) AddSpecificSoul($cardID, $mySoul, $from);
    else AddSpecificSoul($cardID, $theirSoul, $from);
  }
}

function AddSpecificSoul($cardID, &$soul, $from)
{
  array_push($soul, $cardID);
}

function BanishFromSoul($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) BanishFromSpecificSoul($mainSoul, $player);
    else BanishFromSpecificSoul($defSoul, $player);
  }
  else
  {
    if($player == $currentPlayer) BanishFromSpecificSoul($mySoul, $player);
    else BanishFromSpecificSoul($theirSoul, $player);
  }
}

function BanishFromSpecificSoul(&$soul, $player)
{
  $cardID = array_shift($soul);
  BanishCardForPlayer($cardID, $player, "SOUL", "SOUL");
}

function AddArcaneBonus($bonus, $player)
{
  global $CS_NextArcaneBonus;
  $newBonus = GetClassState($player, $CS_NextArcaneBonus) + $bonus;
  SetClassState($player, $CS_NextArcaneBonus, $newBonus);
}

function ConsumeArcaneBonus($player)
{
  global $CS_NextArcaneBonus;
  $bonus = GetClassState($player, $CS_NextArcaneBonus);
  SetClassState($player, $CS_NextArcaneBonus, 0);
WriteLog($bonus);
  return $bonus;
}

function GetClassState($player, $piece)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainClassState[$piece];
    else return $defClassState[$piece];
  }
  else
  {
    if($player == $currentPlayer) return $myClassState[$piece];
    else return $theirClassState[$piece];
  }
}


function SetClassState($player, $piece, $value)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) $mainClassState[$piece] = $value;
    else $defClassState[$piece] = $value;
  }
  else
  {
    if($player == $currentPlayer) $myClassState[$piece] = $value;
    else $theirClassState[$piece] = $value;
  }
}


?>

