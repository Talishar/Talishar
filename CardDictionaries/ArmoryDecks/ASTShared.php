<?php

function ASTAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function ASTAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function ASTEffectAttackModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    default => 0
  };
}

function ASTCombatEffectActive($cardID, $attackID): bool
{
  return match($cardID) {
    default => false
  };
}

function ASTAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function ASTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $current_player, $CS_PlayIndex;
  switch ($cardID) {
    default:
      return "";
  }
}
