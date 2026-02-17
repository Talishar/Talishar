<?php

function AGBAbilityType($cardID, $from): string
{
  return match ($cardID) {
    "graven_justaucorpse" => "I",
    "breakwater_undertow" => "AR",
    "anka_drag_under_yellow" => "I",
    "oysten_heart_of_gold_yellow" => $from == "PLAY" ? "AA" : "A",
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

function AGBEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    default => 0
  };
}

function AGBCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    "loot_the_hold_blue", "loot_the_arsenal_blue" => IsAllyAttacking() && ClassContains($attackID, "PIRATE", $mainPlayer),
    "breakwater_undertow-GOAGAIN" => ClassContains($attackID, "PIRATE", $mainPlayer) && SubtypeContains($attackID, "Ally", $mainPlayer),
    default => false
  };
}

function AGBAbilityCost($cardID): int
{
  return match($cardID) {
    "anka_drag_under_yellow" => (GetResolvedAbilityType($cardID, "PLAY") == "AA" ? 1 : 0),
    "sawbones_dock_hand_yellow" => (GetResolvedAbilityType($cardID, "PLAY") == "AA" ? 1 : 0),
    default => 0
  };
}

function AGBPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $combatChain;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
    switch ($cardID) {
      case "loot_the_hold_blue":
      case "loot_the_arsenal_blue":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        break;
      case "graven_justaucorpse":
        PummelHit($currentPlayer);
        AddDecisionQueue("GAINRESOURCESLASTRESULT", $currentPlayer, "<-", 1);
        break;
      case "breakwater_undertow":
        AddCurrentTurnEffect($cardID, $currentPlayer, uniqueID:"breakwater_undertow-".$combatChain[8]);
        AddCurrentTurnEffect($cardID."-GOAGAIN", $currentPlayer);
        break;
      case "anka_drag_under_yellow":
        $abilityType = GetResolvedAbilityType($cardID, $from);
        if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $target);
        break;
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