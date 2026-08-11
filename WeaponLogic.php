<?php

function IsWeapon($cardID, $from)
{
  global $currentPlayer, $mainPlayer;
  if ($from == "PLAY" && DelimStringContains(CardSubType($cardID), "Aura") && ClassContains($cardID, "ILLUSIONIST", $mainPlayer) && (
      SearchCharacterForCard($mainPlayer, "luminaris") || SearchCharacterForCard($mainPlayer, "iris_of_reality") || SearchCharacterForCard($mainPlayer, "reality_refractor") || SearchCharacterForCard($mainPlayer, "cosmo_scroll_of_ancestral_tapestry"))) {
    return true;
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
  if ($player == $mainPlayer) {
    $hasIris = SearchCharacterForCard($player, "iris_of_reality");
    $hasRefractor = SearchCharacterForCard($player, "reality_refractor");
    // LL weapons are less played so we don't need to check for them if players have either of the more popular weapons
    $hasOtherAuraWeapon = !$hasIris && !$hasRefractor && (
      SearchCharacterForCard($player, "luminaris") ||
      SearchCharacterForCard($player, "cosmo_scroll_of_ancestral_tapestry")
    );
    if ($hasIris || $hasRefractor || $hasOtherAuraWeapon) {
      $auras = GetAuras($player);
      $countAuras = count($auras);
      $auraPieces = AuraPieces();
      $auraPrefix = $whoPrefix . "AURAS-";
      for ($i = 0; $i < $countAuras; $i += $auraPieces) {
        if ($hasIris || $hasRefractor) {
          if (HasWard($auras[$i], $player)) {
            $weaponsList[] = $auraPrefix . $i;
          }
        } else if (ClassContains($auras[$i], "ILLUSIONIST", $player)) {
          $weaponsList[] = $auraPrefix . $i;
        }
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
