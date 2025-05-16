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
  return match($cardID) {
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