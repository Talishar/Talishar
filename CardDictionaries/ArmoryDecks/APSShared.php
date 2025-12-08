<?php

function APSAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function APSAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function APSEffectPowerModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    default => 0
  };
}

function APSHitEffect($cardID): void
{
  global $mainPlayer;
  switch ($cardID) {
    case "standing_ovation_blue":
      WriteLog("The crowd demands an encore! Who are you to refuse?", highlight:true);
      AddCurrentTurnEffect($cardID, $mainPlayer);
      break;
    default:
      break;
  }
}

function APSAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function APSPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  switch ($cardID) {
    default:
      return "";
  }
}
