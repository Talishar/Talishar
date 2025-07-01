<?php

function SUPAbilityType($cardID): string
{
  return match ($cardID) {
    "lyath_goldmane" => "I",
    "lyath_goldmane_vile_savant" => "I",
    "kayo_underhanded_cheat" => "I",
    "tuffnut" => "I",
    "tuffnut_bumbling_hulkster" => "I",
    default => ""
  };
}

function SUPAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "lyath_goldmane" => 2,
    "lyath_goldmane_vile_savant" => 2,
    "kayo_underhanded_cheat" => 4,
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

function SUPPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $mainPlayer, $combatChainState, $CCS_LinkBasePower;
  switch ($cardID) {
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "kayo_underhanded_cheat":
      if ($currentPlayer == $mainPlayer) {
        //check to make sure they targeted the current chain link
        $combatChainState[$CCS_LinkBasePower] = 6;
      }
      else {
        // AddCurrentTurnEffect($cardID, $currentPlayer, uniqueID:$target);
      }
      break;
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
      $deck = new Deck($mainPlayer);
      $top = $deck->Top(true);
      Pitch($top, $mainPlayer);
      if (ModifiedPowerValue($top, $currentPlayer, "DECK") >= 6) {
        Cheer($currentPlayer);
      }
      break;
    default:
      break;
  }
  return "";
}

function SUPHitEffect($cardID): void
{
  switch ($cardID) {
    default:
      break;
  }
}

function BOO($player)
{
  $char = GetPlayerCharacter($player);
  switch($char[0]) {
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
    case "kayo_underhanded_cheat":
    //case "young_kayo"
      AddLayer("TRIGGER", $player, $char[0]);
      break;
    default:
      break;
  }
}

function Cheer($player)
{
  $char = GetPlayerCharacter($player);
  switch($char[0]) {
    case "pleiades":
    case "pleiades_superstar":
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
      AddLayer("TRIGGER", $player, $char[0]);
      break;
    default:
      break;
  }
}