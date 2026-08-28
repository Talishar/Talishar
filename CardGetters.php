<?php

//Player == currentplayer
function &GetMZZone($player, $zone)
{
  global $layers, $combatChain, $chainLinks, $attackQueue;
  $rv = [];
  if ($zone == "MYCHAR" || $zone == "THEIRCHAR") $rv = &GetPlayerCharacter($player);
  else if ($zone == "MYAURAS" || $zone == "THEIRAURAS") $rv = &GetAuras($player);
  else if ($zone == "ALLY" || $zone == "MYALLY" || $zone == "THEIRALLY") $rv = &GetAllies($player);
  else if ($zone == "MYARS" || $zone == "THEIRARS" || $zone == "MYARSENAL" || $zone == "THEIRARSENAL") $rv = &GetArsenal($player);
  else if ($zone == "MYHAND" || $zone == "THEIRHAND") $rv = &GetHand($player);
  else if ($zone == "MYPITCH" || $zone == "THEIRPITCH") $rv = &GetPitch($player);
  else if ($zone == "MYDISCARD" || $zone == "THEIRDISCARD") $rv = &GetDiscard($player);
  else if ($zone == "PERM" || $zone == "MYPERM" || $zone == "THEIRPERM") $rv = &GetPermanents($player);
  else if ($zone == "BANISH" || $zone == "MYBANISH" || $zone == "THEIRBANISH") $rv = &GetBanish($player);
  else if ($zone == "DECK" || $zone == "MYDECK" || $zone == "THEIRDECK") $rv = &GetDeck($player);
  else if ($zone == "SOUL" || $zone == "MYSOUL" || $zone == "THEIRSOUL") $rv = &GetSoul($player);
  else if ($zone == "ITEMS" || $zone == "MYITEMS" || $zone == "THEIRITEMS") $rv = &GetItems($player);
  else if ($zone == "LAYER") return $layers;
  else if ($zone == "CC" || $zone == "COMBATCHAINLINK") return $combatChain;
  else if ($zone == "ATTACKQUEUE") return $attackQueue;
  else if ($zone == "COMBATCHAINATTACKS") {
    $attacks = GetCombatChainAttacks();
    return $attacks;
  }
  else if ($zone == "PASTCHAINLINK") return $chainLinks;
  return $rv;
}

function GetMZZonePieces($zone)
{
  return match($zone) {
    "MYCHAR", "THEIRCHAR" => CharacterPieces(),
    "MYAURAS", "THEIRAURAS" => AuraPieces(),
    "ALLY", "MYALLY", "THEIRALLY" => AllyPieces(),
    "MYARS", "THEIRARS", "MYARSENAL", "THEIRARSENAL" => ArsenalPieces(),
    "MYHAND", "THEIRHAND" => HandPieces(),
    "MYPITCH", "THEIRPITCH" => PitchPieces(),
    "MYDISCARD", "THEIRDISCARD" => DiscardPieces(),
    "PERM", "MYPERM", "THEIRPERM" => PermanentPieces(),
    "BANISH", "MYBANISH", "THEIRBANISH" => BanishPieces(),
    "DECK", "MYDECK", "THEIRDECK" => DeckPieces(),
    "SOUL", "MYSOUL", "THEIRSOUL" => SoulPieces(),
    "ITEMS", "MYITEMS", "THEIRITEMS" => ItemPieces(),
    "LAYER" => LayerPieces(),
    "CC", "COMBATCHAINLINK" => CombatChainPieces(),
    "COMBATCHAINATTACKS", "PASTCHAINLINK" => ChainLinksPieces(),
    default => 0,
  };
}

function GetMZZoneUIDIndex($zone)
{
  return match($zone) {
    "MYCHAR", "THEIRCHAR" => 11,
    "MYAURAS", "THEIRAURAS" => 6,
    "ALLY", "MYALLY", "THEIRALLY" => 5,
    "MYARS", "THEIRARS", "MYARSENAL", "THEIRARSENAL" => 5,
    "MYDISCARD", "THEIRDISCARD" => 1,
    "PERM", "MYPERM", "THEIRPERM" => 0, //not currently tracked
    "BANISH", "MYBANISH", "THEIRBANISH" => 2,
    "ITEMS", "MYITEMS", "THEIRITEMS" => 4,
    "LAYER" => 6,
    "CC", "COMBATCHAINLINK" => 7,
    "COMBATCHAINATTACKS", "PASTCHAINLINK" => 8,
    default => -1,
  };
}

function &GetRelativeMZZone($player, $zone)
{
  global $layers, $combatChain;
  $rv = "";
  if (substr($zone, 0, 5) == "THEIR") $player = $player == 1 ? 2 : 1;
  if ($zone == "MYCHAR" || $zone == "THEIRCHAR") $rv = &GetPlayerCharacter($player);
  else if ($zone == "MYAURAS" || $zone == "THEIRAURAS") $rv = &GetAuras($player);
  else if ($zone == "ALLY" || $zone == "MYALLY" || $zone == "THEIRALLY") $rv = &GetAllies($player);
  else if ($zone == "MYARS" || $zone == "THEIRARS" || $zone == "MYARSENAL" || $zone == "THEIRARSENAL") $rv = &GetArsenal($player);
  else if ($zone == "MYHAND" || $zone == "THEIRHAND") $rv = &GetHand($player);
  else if ($zone == "MYPITCH" || $zone == "THEIRPITCH") $rv = &GetPitch($player);
  else if ($zone == "MYDISCARD" || $zone == "THEIRDISCARD") $rv = &GetDiscard($player);
  else if ($zone == "PERM" || $zone == "MYPERM" || $zone == "THEIRPERM") $rv = &GetPermanents($player);
  else if ($zone == "BANISH" || $zone == "MYBANISH" || $zone == "THEIRBANISH") $rv = &GetBanish($player);
  else if ($zone == "DECK" || $zone == "MYDECK" || $zone == "THEIRDECK") $rv = &GetDeck($player);
  else if ($zone == "SOUL" || $zone == "MYSOUL" || $zone == "THEIRSOUL") $rv = &GetSoul($player);
  else if ($zone == "LAYER") return $layers;
  else if ($zone == "CC") return $combatChain;
  return $rv;
}

function IsValidZoneIndex($zone, $index, $pieces)
{
  if (!is_int($pieces) || $pieces <= 0) return false;
  if (is_int($index)) $validatedIndex = $index;
  else if (is_string($index) && preg_match('/^\d+$/D', $index)) $validatedIndex = (int)$index;
  else return false;

  return $validatedIndex % $pieces === 0 && $validatedIndex + $pieces <= count($zone);
}

function &GetPlayerCharacter($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainCharacter; return $mainCharacter; }
    global $defCharacter;
    return $defCharacter;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myCharacter; return $myCharacter; }
  global $theirCharacter;
  return $theirCharacter;
}

function &GetCharacterEffects($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainCharacterEffects; return $mainCharacterEffects; }
    global $defCharacterEffects;
    return $defCharacterEffects;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myCharacterEffects; return $myCharacterEffects; }
  global $theirCharacterEffects;
  return $theirCharacterEffects;
}

function &GetPlayerClassState($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainClassState; return $mainClassState; }
    global $defClassState;
    return $defClassState;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myClassState; return $myClassState; }
  global $theirClassState;
  return $theirClassState;
}

function GetClassState($player, $piece)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) {
      global $mainClassState;
      return isset($mainClassState) ? ($mainClassState[$piece] ?? "") : "";
    }
    global $defClassState;
    return isset($defClassState) ? ($defClassState[$piece] ?? "") : "";
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) {
    global $myClassState;
    return isset($myClassState) ? ($myClassState[$piece] ?? "") : "";
  }
  global $theirClassState;
  return isset($theirClassState) ? ($theirClassState[$piece] ?? "") : "";
}

function GetCombatChainState($piece) {
  global $combatChainState;
  return $combatChainState[$piece] ?? "";
}

function &GetDeck($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainDeck; return $mainDeck; }
    global $defDeck;
    return $defDeck;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myDeck; return $myDeck; }
  global $theirDeck;
  return $theirDeck;
}

function &GetHand($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainHand; return $mainHand; }
    global $defHand;
    return $defHand;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myHand; return $myHand; }
  global $theirHand;
  return $theirHand;
}

function &GetBanish($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainBanish; return $mainBanish; }
    global $defBanish;
    return $defBanish;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myBanish; return $myBanish; }
  global $theirBanish;
  return $theirBanish;
}

function &GetPitch($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainPitch; return $mainPitch; }
    global $defPitch;
    return $defPitch;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myPitch; return $myPitch; }
  global $theirPitch;
  return $theirPitch;
}

function &GetHealth($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainHealth; return $mainHealth; }
    global $defHealth;
    return $defHealth;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myHealth; return $myHealth; }
  global $theirHealth;
  return $theirHealth;
}

function &GetResources($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainResources; return $mainResources; }
    global $defResources;
    return $defResources;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myResources; return $myResources; }
  global $theirResources;
  return $theirResources;
}

function &GetItems($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainItems; return $mainItems; }
    global $defItems;
    return $defItems;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myItems; return $myItems; }
  global $theirItems;
  return $theirItems;
}

function &GetSoul($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainSoul; return $mainSoul; }
    global $defSoul;
    return $defSoul;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $mySoul; return $mySoul; }
  global $theirSoul;
  return $theirSoul;
}

function &GetDiscard($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainDiscard; return $mainDiscard; }
    global $defDiscard;
    return $defDiscard;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myDiscard; return $myDiscard; }
  global $theirDiscard;
  return $theirDiscard;
}

function &GetArsenal($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainArsenal; return $mainArsenal; }
    global $defArsenal;
    return $defArsenal;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myArsenal; return $myArsenal; }
  global $theirArsenal;
  return $theirArsenal;
}

function &GetAuras($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) {
      global $mainAuras;
      if (!array_is_list($mainAuras)) $mainAuras = array_values($mainAuras);
      return $mainAuras;
    }
    global $defAuras;
    if (!array_is_list($defAuras)) $defAuras = array_values($defAuras);
    return $defAuras;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) {
    global $myAuras;
    if (!array_is_list($myAuras)) $myAuras = array_values($myAuras);
    return $myAuras;
  }
  global $theirAuras;
  if (!array_is_list($theirAuras)) $theirAuras = array_values($theirAuras);
  return $theirAuras;
}

function &GetCardStats($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainCardStats; return $mainCardStats; }
    global $defCardStats;
    return $defCardStats;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myCardStats; return $myCardStats; }
  global $theirCardStats;
  return $theirCardStats;
}

function &GetCardTurnLog($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainCardTurnLog; return $mainCardTurnLog; }
    global $defCardTurnLog;
    return $defCardTurnLog;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myCardTurnLog; return $myCardTurnLog; }
  global $theirCardTurnLog;
  return $theirCardTurnLog;
}

function &GetTurnStats($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainTurnStats; return $mainTurnStats; }
    global $defTurnStats;
    return $defTurnStats;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myTurnStats; return $myTurnStats; }
  global $theirTurnStats;
  return $theirTurnStats;
}

function &GetAllies($player)
{
  global $p1Allies, $p2Allies;
  if ($player == 1) return $p1Allies;
  else return $p2Allies;
}

function &GetPermanents($player)
{
  global $p1Permanents, $p2Permanents;
  if ($player == 1) return $p1Permanents;
  else return $p2Permanents;
}

function &GetInventory($player)
{
  global $p1Inventory, $p2Inventory;
  if ($player == 1) return $p1Inventory;
  else return $p2Inventory;
}

function &GetSettings($player)
{
  global $p1Settings, $p2Settings;
  if ($player == 1) return $p1Settings;
  else return $p2Settings;
}

function &GetMainCharacterEffects($player)
{
  global $mainPlayerGamestateStillBuilt;
  if ($mainPlayerGamestateStillBuilt) {
    global $mainPlayer;
    if ($player == $mainPlayer) { global $mainCharacterEffects; return $mainCharacterEffects; }
    global $defCharacterEffects;
    return $defCharacterEffects;
  }
  global $myStateBuiltFor;
  if ($player == $myStateBuiltFor) { global $myCharacterEffects; return $myCharacterEffects; }
  global $theirCharacterEffects;
  return $theirCharacterEffects;
}


function GetPreLayers() {
  global $layers;
  $preLayers = [];
  $layerPieces = LayerPieces();
  $layerCount = count($layers);
  for ($i = 0; $i < $layerCount; $i += $layerPieces) {
    if (($layers[$i] ?? "-") == "PRETRIGGER") {
      for ($j = 0; $j < $layerPieces; ++$j) $preLayers[] = $layers[$i + $j];
    }
  }
  return $preLayers;
}

function GetCombatChainAttacks()
{
  global $chainLinks, $ChainLinks;
  $chainLinksPieces = ChainLinksPieces();
  $chainLinksCount = $ChainLinks->NumLinks();
  $attacks = array_fill(0, $chainLinksCount * $chainLinksPieces, "-");
  $idx = 0;
  for ($i = 0; $i < $chainLinksCount; ++$i) {
    if ($ChainLinks->GetLink($i)->AttackCard()->StillOnChain()) {
      $link = $chainLinks[$i];
      for ($j = 0; $j < $chainLinksPieces; ++$j) $attacks[$idx + $j] = $link[$j];
    }
    $idx += $chainLinksPieces;
  }
  return $attacks;
}

function HasTakenDamage($player)
{
  global $CS_DamageTaken;
  return GetClassState($player, $CS_DamageTaken) > 0;
}

function ArsenalHasFaceDownCard($player)
{
  $arsenal = &GetArsenal($player);
  $arsenalPieces = ArsenalPieces();
  $arsenalCount = count($arsenal);
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    if ($arsenal[$i + 1] == "DOWN") return true;
  }
  return false;
}

function ArsenalHasFaceUpCard($player)
{
  $arsenal = &GetArsenal($player);
  $arsenalPieces = ArsenalPieces();
  $arsenalCount = count($arsenal);
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    if ($arsenal[$i + 1] == "UP") return true;
  }
  return false;
}

function ArsenalHasArrowCardFacing($player, $facing)
{
  $arsenal = &GetArsenal($player);
  $arsenalPieces = ArsenalPieces();
  $arsenalCount = count($arsenal);
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    if (CardSubType($arsenal[$i]) == "Arrow" && $arsenal[$i + 1] == $facing) return true;
  }
  return false;
}

function ArsenalHasArrowFacingColor($player, $facing, $color)
{
  $arsenal = &GetArsenal($player);
  $arsenalPieces = ArsenalPieces();
  $arsenalCount = count($arsenal);
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    if (CardSubType($arsenal[$i]) == "Arrow" && $arsenal[$i + 1] == $facing && ColorContains($arsenal[$i], $color, $player)) return true;
  }
  return false;
}

function ArsenalFull($player)
{
  $arsenal = &GetArsenal($player);
  $pieces = ArsenalPieces();
  $fullCount = SearchCharacterActive($player, "new_horizon") && ArsenalHasFaceUpCard($player) ? $pieces * 2 : $pieces;
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
  $characterPieces = CharacterPieces();
  $characterCount = count($character);
  for ($i = 0; $i < $characterCount; $i += $characterPieces) {
    if (CardType($character[$i]) == "E" && $character[$i + 1] != 0) ++$numEquip;
  }
  return $numEquip;
}
