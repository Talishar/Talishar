<?php

function AACAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function AACAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AACEffectPowerModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    default => 0
  };
}

function AACHitEffect($cardID, $target): void
{
  global $mainPlayer, $defPlayer;
  switch ($cardID) {
    default:
      break;
  }
}

function AACCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    default => false
  };
}

function AACAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function AACPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  switch ($cardID) {
    default:
      return "";
  }
}
