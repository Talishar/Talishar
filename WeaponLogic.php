<?php

function IsWeapon($cardID)
{
  return CardType($cardID) == "W";
}

function IsWeaponAttack()
{
  global $combatChain;
  if(count($combatChain) == 0) return false;
  if(CardType($combatChain[0]) == "W" || CardSubType($combatChain[0]) == "Aura") return true;
  return false;
}

function WeaponIndices($chooser, $player, $subtype = "")
{
  global $mainPlayer;
  $whoPrefix = ($player == $chooser ? "MY" : "THEIR");
  $character = GetPlayerCharacter($player);
  $weapons = "";
  for($i = 0; $i < count($character); $i += CharacterPieces()) {
    if($character[$i + 1] != 0 && CardType($character[$i]) == "W" && ($subtype == "" || CardSubType($character[$i]) == $subtype)) {
      if($weapons != "") $weapons .= ",";
      $weapons .= $whoPrefix . "CHAR-" . $i;
    }
  }
  $auraWeapons = (SearchCharacterForCard($player, "MON003") || SearchCharacterForCard($player, "MON088")) && ($player == $mainPlayer);
  if($auraWeapons) {
    $auras = GetAuras($player);
    for($i = 0; $i < count($auras); $i += AuraPieces()) {
      if(ClassContains($auras[$i], "ILLUSIONIST", $player)) {
        if($weapons != "") $weapons .= ",";
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
  for($i = 0; $i < count($character); $i += CharacterPieces()) {
    if(CardType($character[$i]) == "W") AddCharacterEffect($currentPlayer, $i, $effectID);
  }
}

function IsAuraWeapon($cardID, $player, $from)
{
  if((SearchCharacterForCard($player, "MON003") || SearchCharacterForCard($player, "MON088")) && DelimStringContains(CardSubType($cardID), "Aura") && $from == "PLAY") return true;
  else return false;
}
