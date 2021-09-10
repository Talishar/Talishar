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

function &GetCharacterEffects($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mainCharacterEffects, $defCharacterEffects, $myCharacterEffects, $theirCharacterEffects;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCharacterEffects;
    else return $defCharacterEffects;
  }
  else
  {
    if($player == $currentPlayer) return $myCharacterEffects;
    else return $theirCharacterEffects;
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

function &GetPitch($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myPitch, $theirPitch, $mainPitch, $defPitch;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainPitch;
    else return $defPitch;
  }
  else
  {
    if($player == $playerID) return $myPitch;
    else return $theirPitch;
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

function &GetDiscard($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainDiscard;
    else return $defDiscard;
  }
  else
  {
    if($player == $playerID) return $myDiscard;
    else return $theirDiscard;
  }
}

function &GetArsenal($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myArsenal, $theirArsenal, $mainArsenal, $defArsenal;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainArsenal;
    else return $defArsenal;
  }
  else
  {
    if($player == $playerID) return $myArsenal;
    else return $theirArsenal;
  }
}

function &GetAuras($player)
{
  global $playerID, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myAuras, $theirAuras, $mainAuras, $defAuras;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainAuras;
    else return $defAuras;
  }
  else
  {
    if($player == $playerID) return $myAuras;
    else return $theirAuras;
  }
}

function HasTakenDamage($player)
{
  global $CS_DamageTaken;
  return GetClassState($player, $CS_DamageTaken) > 0;
}

function ArsenalHasFaceDownCard($player)
{
  global $CS_ArsenalFacing;
  $arsenal = &GetArsenal($player);
  if($arsenal == "") return false;
  return GetClassState($player, $CS_ArsenalFacing) == "DOWN";
}

function ArsenalFull($player)
{
  $arsenal = &GetArsenal($player);
  return $arsenal != "";
}

function ArsenalEmpty($player)
{
  $arsenal = &GetArsenal($player);
  return $arsenal == "";
}

function NumEquipment($player)
{
  $character = &GetPlayerCharacter($player);
  $numEquip = 0;
  for($i=0; $i<count($character); $i += CharacterPieces())
  {
    if(CardType($character[$i]) == "E" && $character[$i+1] != 0) ++$numEquip;
  }
  return $numEquip;
}

?>

