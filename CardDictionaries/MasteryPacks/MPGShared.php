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

function MPGEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "draw_a_crowd_blue" => 3,
    default => 0
  };
}

function MPGCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    "valda_seismic_impact" => HasCrush($attackID),
    "draw_a_crowd_blue" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && TypeContains($attackID, "AA"),
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
  $otherPlayer = $currentPlayer == 1 ? 0 : 1;
  switch ($cardID) {
    case "seismic_eruption_yellow":
      PlayAura("seismic_surge", $currentPlayer, 3, true);
      return "";
    case "draw_a_crowd_blue":
      Draw($currentPlayer, effectSource:$cardID);
      Draw($otherPlayer, effectSource:$cardID);
      return "";
    default:
      return "";
  }
}
