<?php

function MPGAbilityType($cardID, $index = -1, $from = "-"): string
{
  global $currentPlayer;
  return match ($cardID) {
    default => ""
  };
}

function MPGAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function MPGEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    default => 0
  };
}

function MPGCombatEffectActive($cardID, $attackID): bool
{
  return match($cardID) {
    "valda_seismic_impact" => HasCrush($attackID),
    default => false
  };
}

function MPGAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function MPGPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  switch ($cardID) {
    case "seismic_eruption_yellow":
      PlayAura("seismic_surge", $currentPlayer, 3, true);
      return "";
    default:
      return "";
  }
}
