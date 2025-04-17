<?php

function ASRAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function ASRAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function ASREffectAttackModifier($cardID): int
{
  return match ($cardID) {
    default => 0
  };
}

function ASRCombatEffectActive($cardID, $attackID): bool
{
  return match($cardID) {
    default => false
  };
}

function ASRAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function ASRPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
    switch ($cardID) {
    case "enact_vengeance_red":
      if(ComboActive($cardID)) AddCurrentTurnEffect("enact_vengeance_red", $currentPlayer);
      return "";
    default:
      return "";
  }
}

function ASRHitEffect($cardID)
{
  global $mainPlayer, $defPlayer;
  switch ($cardID) {
    case "enact_vengeance_red":
      DestroyArsenal($defPlayer, effectController:$mainPlayer);      
      break;
    default:
      break;
  }
}