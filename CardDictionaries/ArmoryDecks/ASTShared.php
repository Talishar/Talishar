<?php

function ASTAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "shock_frock" => "A",
    "cap_of_quick_thinking" => "I",
    default => ""
  };
}

function ASTAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "shock_frock" => true,
    default => false
  };
}

function ASTEffectAttackModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    "skyward_serenade_yellow" => 1,
    default => 0
  };
}

function ASTCombatEffectActive($cardID, $attackID): bool
{
  return match($cardID) {
    "skyward_serenade_yellow" => true,
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
  global $currentPlayer, $CS_PlayIndex, $CS_ArcaneDamageTaken;

  $otherPlayer = $currentPlayer == 1 ? 0 : 1;
  switch ($cardID) {
    case "skyward_serenade_yellow":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "SKYWARDSERENADE", 1);
      return "";
    case "written_in_the_stars_blue":
      PlayAura("embodiment_of_lightning", $currentPlayer);
      if (GetClassState($otherPlayer, $CS_ArcaneDamageTaken) > 0) Draw($currentPlayer, effectSource:$cardID);
      return "";
    case "shock_frock":
      GainResources($currentPlayer, 1);
      return "";
    default:
      return "";
  }
}
