<?php

function SUPAbilityType($cardID): string
{
  return match ($cardID) {
    default => ""
  };
}

function SUPAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    default => 0
  };
}

function SUPAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SUPEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    default => 0,
  };
}

function SUPCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SUPPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  switch ($cardID) {
    default:
      return "";
  }
}

function SUPHitEffect($cardID): void
{
  switch ($cardID) {
    default:
      break;
  }
}