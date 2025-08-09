<?php

function ARRAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function ARRAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function ARREffectPowerModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    default => 0
  };
}

function ARRHitEffect($cardID): void
{
  global $mainPlayer;
  switch ($cardID) {
    default:
      break;
  }
}

function ARRCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    default => false
  };
}

function ARRAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function ARRPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  switch ($cardID) {
    default:
      return "";
  }
}
