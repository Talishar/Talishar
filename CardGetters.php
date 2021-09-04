<?php

function &GetPlayerCharacter($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mainCharacter, $defCharacter, $myCharacter, $theirCharacter;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCharacter;
    else return $defCharacter;
  }
  else
  {
    if($player == $currentPlayer) return $myCharacter;
    else return $theirCharacter;
  }
}

function GetClassState($player, $piece)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainClassState[$piece];
    else return $defClassState[$piece];
  }
  else
  {
    if($player == $playerID) return $myClassState[$piece];
    else return $theirClassState[$piece];
  }
}

function &GetDeck($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDeck, $theirDeck, $mainDeck, $defDeck;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainDeck;
    else return $defDeck;
  }
  else
  {
    if($player == $playerID) return $myDeck;
    else return $theirDeck;
  }
}

function &GetHand($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myHand, $theirHand, $mainHand, $defHand;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHand;
    else return $defHand;
  }
  else
  {
    if($player == $playerID) return $myHand;
    else return $theirHand;
  }
}

function &GetBanish($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainBanish;
    else return $defBanish;
  }
  else
  {
    if($player == $playerID) return $myBanish;
    else return $theirBanish;
  }
}

function &GetHealth($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myHealth, $theirHealth, $mainHealth, $defHealth;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHealth;
    else return $defHealth;
  }
  else
  {
    if($player == $playerID) return $myHealth;
    else return $theirHealth;
  }
}

function &GetItems($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myItems, $theirItems, $mainItems, $defItems;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainItems;
    else return $defItems;
  }
  else
  {
    if($player == $playerID) return $myItems;
    else return $theirItems;
  }
}

function &GetSoul($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainSoul;
    else return $defSoul;
  }
  else
  {
    if($player == $playerID) return $mySoul;
    else return $theirSoul;
  }
}

function HasTakenDamage($player)
{
  global $CS_DamageTaken;
  return GetClassState($player, $CS_DamageTaken) > 0;
}

?>

