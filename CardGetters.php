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

function &GetDeck($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDeck, $theirDeck, $mainDeck, $defDeck;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainDeck;
    else return $defDeck;
  }
  else
  {
    if($player == $currentPlayer) return $myDeck;
    else return $theirDeck;
  }
}

function &GetHand($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myHand, $theirHand, $mainHand, $defHand;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHand;
    else return $defHand;
  }
  else
  {
    if($player == $currentPlayer) return $myHand;
    else return $theirHand;
  }
}

function &GetBanish($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainBanish;
    else return $defBanish;
  }
  else
  {
    if($player == $currentPlayer) return $myBanish;
    else return $theirBanish;
  }
}

function &GetPitch($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myPitch, $theirPitch, $mainPitch, $defPitch;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainPitch;
    else return $defPitch;
  }
  else
  {
    if($player == $currentPlayer) return $myPitch;
    else return $theirPitch;
  }
}

function &GetHealth($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myHealth, $theirHealth, $mainHealth, $defHealth;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHealth;
    else return $defHealth;
  }
  else
  {
    if($player == $currentPlayer) return $myHealth;
    else return $theirHealth;
  }
}

function &GetItems($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myItems, $theirItems, $mainItems, $defItems;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainItems;
    else return $defItems;
  }
  else
  {
    if($player == $currentPlayer) return $myItems;
    else return $theirItems;
  }
}

function &GetSoul($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainSoul;
    else return $defSoul;
  }
  else
  {
    if($player == $currentPlayer) return $mySoul;
    else return $theirSoul;
  }
}

function &GetDiscard($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainDiscard;
    else return $defDiscard;
  }
  else
  {
    if($player == $currentPlayer) return $myDiscard;
    else return $theirDiscard;
  }
}

function &GetArsenal($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myArsenal, $theirArsenal, $mainArsenal, $defArsenal;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainArsenal;
    else return $defArsenal;
  }
  else
  {
    if($player == $currentPlayer) return $myArsenal;
    else return $theirArsenal;
  }
}

function &GetAuras($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myAuras, $theirAuras, $mainAuras, $defAuras;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainAuras;
    else return $defAuras;
  }
  else
  {
    if($player == $currentPlayer) return $myAuras;
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
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if($arsenal[$i+1] == "DOWN") return true;
  }
  return false;
}

function ArsenalHasFaceUpCard($player)
{
  global $CS_ArsenalFacing;
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if($arsenal[$i+1] == "UP") return true;
  }
  return false;
}

function ArsenalFull($player)
{
  $arsenal = &GetArsenal($player);
  $fullCount = SearchCharacterForCard($player, "ELE213") && ArsenalHasFaceUpCard($player) ? 4 : 2;
  return count($arsenal) >= $fullCount;
}

function ArsenalEmpty($player)
{
  $arsenal = &GetArsenal($player);
  return count($arsenal) == 0;
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

