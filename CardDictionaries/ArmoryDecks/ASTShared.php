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

function ASTEffectPowerModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    "skyward_serenade_yellow" => 1,
    "spark_spray_red" => 1,
    default => 0
  };
}

function ASTCombatEffectActive($cardID, $attackID): bool
{
  return match($cardID) {
    "skyward_serenade_yellow" => true,
    "spark_spray_red" => true,
    default => false
  };
}

function ASTAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function DoCapQuickThinking($targetPlayer, $damage)
{
  if ($damage > 0) {
    PrependDecisionQueue("ADDTOLASTRESULT", $targetPlayer, "{0}", 1);
    PrependDecisionQueue("PASSPARAMETER", $targetPlayer, 1, 1); //prevent 1 damage
    PrependDecisionQueue("DRAW", $targetPlayer, 1, 1);
    PrependDecisionQueue("MZREMOVE", $targetPlayer, "HAND", 1);
    PrependDecisionQueue("MZDISCARD", $targetPlayer, "HAND", 1);
    PrependDecisionQueue("MAYCHOOSEMULTIZONE", $targetPlayer, "<-", 1);
    PrependDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose an instant to discard to " . CardLink("cap_of_quick_thinking", "cap_of_quick_thinking"), 1);
    PrependDecisionQueue("MULTIZONEINDICES", $targetPlayer, "MYHAND:type=I", 1);
    PrependDecisionQueue("SETDQVAR", $targetPlayer, "0", 1); // current damage prevention
  }
}

function ASTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_PlayIndex, $CS_ArcaneDamageDealt;

  $otherPlayer = $currentPlayer == 1 ? 0 : 1;
  switch ($cardID) {
    case "skyward_serenade_yellow":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "SKYWARDSERENADE", 1);
      return "";
    case "written_in_the_stars_blue":
      PlayAura("embodiment_of_lightning", $currentPlayer);
      if (GetClassState($currentPlayer, $CS_ArcaneDamageDealt) > 0) Draw($currentPlayer, effectSource:$cardID);
      return "";
    case "shock_frock":
      GainResources($currentPlayer, 1);
      return "";
    case "cap_of_quick_thinking":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    default:
      return "";
  }
}
