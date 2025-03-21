<?php

function SEAAbilityType($cardID): string
{
  return match ($cardID) {
    "gravy_bones_shipwrecked_looter" => "I",
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
	global $currentPlayer;
  switch ($cardID) {
    case "gravy_bones_shipwrecked_looter":
			Draw($currentPlayer, effectSource:$cardID);
			PummelHit($currentPlayer);
      break;
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

function HasWateryGrave($cardID): bool
{
	return match($cardID) {
		"chum_friendly_first_mate" => true,
		"riggermortis" => true,
		default => false
	};
}