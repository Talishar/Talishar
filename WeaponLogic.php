<?php

function IsWeapon($cardID)
{
  global $currentPlayer;
  return TypeContains($cardID, "W", $currentPlayer);
}

function IsWeaponAttack()
{
  global $combatChain, $mainPlayer;
  if (count($combatChain) == 0) return false;
  if (TypeContains($combatChain[0], "W", $mainPlayer) || DelimStringContains(CardSubType($combatChain[0]), "Aura")) return true;
  return false;
}

function WeaponIndices($chooser, $player, $subtype = "")
{
  global $mainPlayer;
  $whoPrefix = ($player == $chooser ? "MY" : "THEIR");
  $character = GetPlayerCharacter($player);
  $weapons = "";
  for ($i = 0; $i < count($character); $i += CharacterPieces()) {
    if ($character[$i + 1] != 0 && CardType($character[$i]) == "W" && ($subtype == "" || CardSubType($character[$i]) == $subtype)) {
      if ($weapons != "") $weapons .= ",";
      $weapons .= $whoPrefix . "CHAR-" . $i;
    }
  }
  $auraWeapons = (SearchCharacterForCard($player, "MON003") || SearchCharacterForCard($player, "MON088") || SearchCharacterForCard($player, "DTD216") || SearchCharacterForCard($player, "MST130")) && ($player == $mainPlayer);
  if ($auraWeapons) {
    $auras = GetAuras($player);
    for ($i = 0; $i < count($auras); $i += AuraPieces()) {
      if (SearchCharacterForCard($player, "MST130")) {
        if (HasWard($auras[$i], $player)) {
          if ($weapons != "") $weapons .= ",";
          $weapons .= $whoPrefix . "AURAS-" . $i;
        }
      } else if (ClassContains($auras[$i], "ILLUSIONIST", $player)) {
        if ($weapons != "") $weapons .= ",";
        $weapons .= $whoPrefix . "AURAS-" . $i;
      }
    }
  }
  return $weapons;
}

function ApplyEffectToEachWeapon($effectID)
{
  global $currentPlayer;
  $character = &GetPlayerCharacter($currentPlayer);
  for ($i = 0; $i < count($character); $i += CharacterPieces()) {
    if (CardType($character[$i]) == "W") AddCharacterEffect($currentPlayer, $i, $effectID);
  }
}

function IsAuraWeapon($cardID, $player, $from)
{
  if ((SearchCharacterForCard($player, "MON003") || SearchCharacterForCard($player, "MON088") || SearchCharacterForCard($player, "DTD216") || SearchCharacterForCard($player, "MST130")) && DelimStringContains(CardSubType($cardID), "Aura") && $from == "PLAY") return true;
  else return false;
}
