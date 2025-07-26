<?php

function MPGAbilityType($cardID, $index = -1, $from = "-"): string
{
  global $currentPlayer;
  return match ($cardID) {
    default => ""
  };
}

function MPGAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function MPGEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "draw_a_crowd_blue" => 3,
    "fearless_confrontation_blue" => -1,
    default => 0
  };
}

function MPGCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    "valda_seismic_impact" => HasCrush($attackID),
    "draw_a_crowd_blue" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && TypeContains($attackID, "AA"),
    "leave_a_dent_blue" => ClassContains($attackID, "GUARDIAN", $mainPlayer),
    "fearless_confrontation_blue" => true,
    default => false
  };
}

function MPGAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function MPGPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 0 : 1;
  switch ($cardID) {
    case "seismic_eruption_yellow":
      PlayAura("seismic_surge", $currentPlayer, 3, true);
      return "";
    case "draw_a_crowd_blue":
      Draw($currentPlayer, effectSource:$cardID);
      Draw($otherPlayer, effectSource:$cardID);
      return "";
    case "visit_anvilheim_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Off-Hand;hasNegCounters=true;class=GUARDIAN");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "GETCARDINDEX", 1);
      AddDecisionQueue("MODDEFCOUNTER", $currentPlayer, $resourcesPaid, 1);
      return "";
    case "leave_a_dent_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    default:
      return "";
  }
}
