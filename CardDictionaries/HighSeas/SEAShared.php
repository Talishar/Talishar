<?php

function SEAAbilityType($cardID): string
{
  return match ($cardID) {
    default => ""
  };
}

function SEAAbilityCost($cardID): int
{
  return match ($cardID) {
    default => 0
  };
}

function SEAAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SEAEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    default => 0,
  };
}

function SEACombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SEAPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  switch ($cardID) {
    default:
      break;
  }
  return "";
}

function SEAHitEffect($cardID): void
{
  switch ($cardID) {
    default:
      break;
  }
}

function Wave($MZindex): string
{
  return "";
}

function CheckWaved($player): bool
{
  return false;
}

