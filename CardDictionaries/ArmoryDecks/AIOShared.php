<?php

function AIOAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function AIOAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AIOCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AIOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  switch ($cardID) {
    default:
      return "";
  }
}