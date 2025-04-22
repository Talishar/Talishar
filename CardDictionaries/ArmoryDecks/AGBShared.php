<?php

function AGBAbilityType($cardID): string
{
  return match ($cardID) {
    "sawbones_dock_hand_yellow" => "I",
    default => ""
  };
}

function AGBAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AGBEffectAttackModifier($cardID): int
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
    "sawbones_dock_hand_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "AA" ? 1 : 0,
    default => 0
  };
}

function AGBPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
    switch ($cardID) {
      case "sawbones_dock_hand_yellow":
        $abilityType = GetResolvedAbilityType($cardID, $from);
        if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($cardID, $currentPlayer);
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