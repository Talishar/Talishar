<?php

function AGBAbilityType($cardID): string
{
  return match ($cardID) {
    default => ""
  };
}

function AGBAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AGBEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    default => 0
  };
}

function AGBCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    "loot_the_hold_blue", "loot_the_arsenal_blue" => IsAllyAttacking() && ClassContains($attackID, "PIRATE", $mainPlayer),
    default => false
  };
}

function AGBAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function AGBPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
    switch ($cardID) {
      case "loot_the_hold_blue":
      case "loot_the_arsenal_blue":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        break;
      default:
        return "";
    }
}

function AGBHitEffect($cardID)
{
  switch ($cardID) {
    default:
      break;
  }
}