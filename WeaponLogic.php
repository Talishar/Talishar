<?php

function IsWeapon($cardID, $from)
{
  global $currentPlayer, $mainPlayer;
  if (DelimStringContains(CardSubType($cardID), "Aura") && ClassContains($cardID, "ILLUSIONIST", $mainPlayer) && $from == "PLAY" && (
      SearchCharacterForCard($mainPlayer, "luminaris") || SearchCharacterForCard($mainPlayer, "iris_of_reality") || SearchCharacterForCard($mainPlayer, "reality_refractor") || SearchCharacterForCard($mainPlayer, "cosmo_scroll_of_ancestral_tapestry"))) 
    {
    return true;
  }
  return TypeContains($cardID, "W", $currentPlayer);
}

function IsWeaponAttack()
{
  global $combatChain, $mainPlayer;
  if (count($combatChain) == 0) return false;
  if (TypeContains($combatChain[0], "W", $mainPlayer) || SubtypeContains($combatChain[0], "Aura") && IsWeapon($combatChain[0], "PLAY")) return true;
  return false;
}

function WeaponWithNonAttack($cardID, $from) 
{
  if (!IsWeapon($cardID, $from)) return false;
  if (GetAbilityTypes($cardID, from:$from) != "") return true;
  $abilityType = GetAbilityType($cardID, from:$from);
  if ($abilityType != "AA" && $abilityType != "") return true;
  return false;
}

function GetHighestBaseWeaponPower($player)
{
  $character = GetPlayerCharacter($player);
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
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if ($character[$i + 1] != 0 && TypeContains($character[$i], "W") && ($subtype == "" || CardSubType($character[$i]) == $subtype)) {
      $weaponsList[] = $whoPrefix . "CHAR-" . $i;
    }
  }
  $hasLuminaris = SearchCharacterForCard($player, "luminaris");
  $hasIris = SearchCharacterForCard($player, "iris_of_reality");
  $hasRefractor = SearchCharacterForCard($player, "reality_refractor");
  $hasCosmo = SearchCharacterForCard($player, "cosmo_scroll_of_ancestral_tapestry");
  $auraWeapons = ($hasLuminaris || $hasIris || $hasRefractor || $hasCosmo) && $player == $mainPlayer;
  if ($auraWeapons) {
    $auras = GetAuras($player);
    $countAuras = count($auras);
    $auraPieces = AuraPieces();
    for ($i = 0; $i < $countAuras; $i += $auraPieces) {
      if ($hasCosmo) {
        if (HasWard($auras[$i], $player)) {
          $weaponsList[] = $whoPrefix . "AURAS-" . $i;
        }
      } else if (ClassContains($auras[$i], "ILLUSIONIST", $player)) {
        $weaponsList[] = $whoPrefix . "AURAS-" . $i;
      }
    }
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
