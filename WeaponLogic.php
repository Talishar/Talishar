<?php

function IsWeapon($cardID, $from, $player="-")
{
  global $currentPlayer, $mainPlayer;
  if ($player == "-")
    $player = $currentPlayer;
  if (SubtypeContains($cardID, "Aura") && $from == "PLAY") {
    if (SearchCharacterForCard($player, "luminaris") || SearchCharacterForCard($player, "iris_of_reality"))
      return ClassContains($cardID, "ILLUSIONIST", $player) && $player == $mainPlayer && IsActionPhase();
    if (SearchCharacterForCard($player, "reality_refractor"))
      return ClassContains($cardID, "ILLUSIONIST", $player);
    if (SearchCharacterForCard($player, "cosmo_scroll_of_ancestral_tapestry"))
      return HasWard($cardID, $player);
  }
  return TypeContains($cardID, "W", $currentPlayer);
}

function IsWeaponAttack()
{
  global $combatChain, $mainPlayer;
  if (empty($combatChain)) return false;
  return TypeContains($combatChain[0], "W", $mainPlayer) || (SubtypeContains($combatChain[0], "Aura") && IsWeapon($combatChain[0], "PLAY"));
}

function WeaponWithNonAttack($cardID, $from)
{
  if (!IsWeapon($cardID, $from)) return false;
  if (GetAbilityTypes($cardID, from:$from) !== "") return true;
  $abilityType = GetAbilityType($cardID, from:$from);
  return $abilityType !== "AA" && $abilityType !== "";
}

function GetHighestBaseWeaponPower($player)
{
  $character = &GetPlayerCharacter($player);
  $countCharacter = count($character);
  $characterPieces = CharacterPieces();
  $maxPower = 0;
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if (TypeContains($character[$i], "W", $player)) {
      $basePower = PowerValue($character[$i], $player);
      if ($basePower > $maxPower) {
        $maxPower = $basePower;
      }
    }
  }
  $auras = GetAuras($player);
  $countAuras = count($auras);
  $auraPieces = AuraPieces();
  for ($i = 0; $i < $countAuras; $i += $auraPieces) {
    $basePower = PowerValue($auras[$i], $player);
    if ($basePower > $maxPower) {
      $maxPower = $basePower;
    }
  }
  return $maxPower;
}

function WeaponIndices($chooser, $player, $subtype = "")
{
  global $mainPlayer;
  $whoPrefix = ($player == $chooser ? "MY" : "THEIR");
  $character = GetPlayerCharacter($player);
  $weaponsList = [];
  $countCharacter = count($character);
  $characterPieces = CharacterPieces();
  $charPrefix = $whoPrefix . "CHAR-";
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if ($character[$i + 1] != 0 && TypeContains($character[$i], "W") && ($subtype == "" || CardSubType($character[$i]) == $subtype)) {
      $weaponsList[] = $charPrefix . $i;
    }
  }
  $Auras = new Auras($player);
  $auraPrefix = $whoPrefix . "AURAS-";
  for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
    $AuraCard = $Auras->Card($i, true);
    if (IsWeapon($AuraCard->CardID(), "PLAY"))
      $weaponsList[] = $auraPrefix . $AuraCard->Index();
  }
  return implode(",", $weaponsList);
}

function ApplyEffectToEachWeapon($effectID)
{
  global $currentPlayer;
  $character = &GetPlayerCharacter($currentPlayer);
  $countCharacter = count($character);
  $characterPieces = CharacterPieces();
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if (TypeContains($character[$i], "W", $currentPlayer)) AddCharacterEffect($currentPlayer, $i, $effectID);
  }
}
