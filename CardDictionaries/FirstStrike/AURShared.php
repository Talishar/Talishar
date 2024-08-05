<?php

function AUREffectAttackModifier($cardID)
{
  return match ($cardID) {
    "AUR014" => 3,
    "AUR021" => 2,
    default => 0,
  };
}

function AURCombatEffectActive($cardID, $attackID)
{
    global $mainPlayer;
  return match ($cardID) {
    "AUR014", "AUR021" => TalentContainsAny($attackID, "LIGHTNING,ELEMENTAL", $mainPlayer),
    default => "",
  };
}
