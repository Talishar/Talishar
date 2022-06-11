<?php

//Player == currentplayer
function &GetMZZone($player, $zone)
{
  global $p1Permanents;
  $rv = "";
  if($zone == "MYCHAR" || $zone == "THEIRCHAR") $rv = &GetPlayerCharacter($player);
  else if($zone == "MYAURAS" || $zone == "THEIRAURAS") $rv = &GetAuras($player);
  else if($zone == "ALLY" || $zone == "MYALLY" || $zone == "THEIRALLY") $rv = &GetAllies($player);
  else if($zone == "MYARS" || $zone == "THEIRARS") $rv = &GetArsenal($player);
  else if($zone == "MYDISCARD" || $zone == "THEIRDISCARD") $rv = &GetDiscard($player);
  else if($zone == "PERM" || $zone == "MYPERM" || $zone == "THEIRPERM") $rv = &GetPermanents($player);
  else if($zone == "BANISH" || $zone == "MYBANISH" || $zone == "THEIRBANISH") $rv = &GetBanish($player);
  return $rv;
}

/*
function GetMZPieces($zone)
{
  if($zone == "MYCHAR" || $zone == "THEIRCHAR") return CharacterPieces();
  else if($zone == "MYAURAS" || $zone == "THEIRAURAS") return AuraPieces();
}
*/

function &GetPlayerCharacter($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mainCharacter, $defCharacter, $myCharacter, $theirCharacter;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCharacter;
    else return $defCharacter;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myCharacter;
    else return $theirCharacter;
  }
}

function &GetCharacterEffects($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mainCharacterEffects, $defCharacterEffects, $myCharacterEffects, $theirCharacterEffects;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCharacterEffects;
    else return $defCharacterEffects;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myCharacterEffects;
    else return $theirCharacterEffects;
  }
}

function &GetPlayerClassState($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainClassState;
    else return $defClassState;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myClassState;
    else return $theirClassState;
  }
}

function GetClassState($player, $piece)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainClassState[$piece];
    else return $defClassState[$piece];
  }
  else
  {
    if($player == $myStateBuiltFor) return $myClassState[$piece];
    else return $theirClassState[$piece];
  }
}

function &GetDeck($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDeck, $theirDeck, $mainDeck, $defDeck;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainDeck;
    else return $defDeck;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myDeck;
    else return $theirDeck;
  }
}

function &GetHand($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myHand, $theirHand, $mainHand, $defHand;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHand;
    else return $defHand;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myHand;
    else return $theirHand;
  }
}

function &GetBanish($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainBanish;
    else return $defBanish;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myBanish;
    else return $theirBanish;
  }
}

function &GetPitch($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myPitch, $theirPitch, $mainPitch, $defPitch;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainPitch;
    else return $defPitch;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myPitch;
    else return $theirPitch;
  }
}

function &GetHealth($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myHealth, $theirHealth, $mainHealth, $defHealth;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainHealth;
    else return $defHealth;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myHealth;
    else return $theirHealth;
  }
}

function &GetResources($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myResources, $theirResources, $mainResources, $defResources;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainResources;
    else return $defResources;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myResources;
    else return $theirResources;
  }
}

function &GetItems($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myItems, $theirItems, $mainItems, $defItems;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainItems;
    else return $defItems;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myItems;
    else return $theirItems;
  }
}

function &GetSoul($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainSoul;
    else return $defSoul;
  }
  else
  {
    if($player == $myStateBuiltFor) return $mySoul;
    else return $theirSoul;
  }
}

function &GetDiscard($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainDiscard;
    else return $defDiscard;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myDiscard;
    else return $theirDiscard;
  }
}

function &GetArsenal($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myArsenal, $theirArsenal, $mainArsenal, $defArsenal;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainArsenal;
    else return $defArsenal;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myArsenal;
    else return $theirArsenal;
  }
}

function &GetAuras($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myAuras, $theirAuras, $mainAuras, $defAuras;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainAuras;
    else return $defAuras;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myAuras;
    else return $theirAuras;
  }
}

function &GetCardStats($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myCardStats, $theirCardStats, $mainCardStats, $defCardStats;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCardStats;
    else return $defCardStats;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myCardStats;
    else return $theirCardStats;
  }
}

function &GetTurnStats($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myTurnStats, $theirTurnStats, $mainTurnStats, $defTurnStats;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainTurnStats;
    else return $defTurnStats;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myTurnStats;
    else return $theirTurnStats;
  }
}

function &GetAllies($player)
{
  global $p1Allies, $p2Allies;
  if($player == 1) return $p1Allies;
  else return $p2Allies;
}

function &GetPermanents($player)
{
  global $p1Permanents, $p2Permanents;
  if($player == 1) return $p1Permanents;
  else return $p2Permanents;
}

function &GetSettings($player)
{
  global $p1Settings, $p2Settings;
  if($player == 1) return $p1Settings;
  else return $p2Settings;
}

function &GetMainCharacterEffects($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myCharacterEffects, $theirCharacterEffects, $mainCharacterEffects, $defCharacterEffects;
  global $myStateBuiltFor;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCharacterEffects;
    else return $defCharacterEffects;
  }
  else
  {
    if($player == $myStateBuiltFor) return $myCharacterEffects;
    else return $theirCharacterEffects;
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
  $fullCount = SearchCharacterForCard($player, "ELE213") && ArsenalHasFaceUpCard($player) ? ArsenalPieces() * 2 : ArsenalPieces();
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

function ActiveCharacterEffects($player, $index)
{
  $effects = "";
  $characterEffects = GetCharacterEffects($player);
  for($i=0; $i<count($characterEffects); $i+=CharacterEffectPieces())
  {
    if($characterEffects[$i] == $index)
    {
      if($effects != "") $effects .= ", ";
      $effects .= CardName($characterEffects[$i+1]);
    }
  }
  return $effects;
}

?>
