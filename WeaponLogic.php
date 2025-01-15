<?php

function IsWeapon($cardID)
{
  global $currentPlayer, $mainPlayer;
  if(DelimStringContains(CardSubType($cardID), "Aura") && 
      ClassContains($cardID, "ILLUSIONIST", $mainPlayer) && 
      SearchCharacterForCard($mainPlayer, "MON003") || 
      SearchCharacterForCard($mainPlayer, "MON088") || 
      SearchCharacterForCard($mainPlayer, "DTD216") || 
      SearchCharacterForCard($mainPlayer, "MST130")
  ) return true;
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
