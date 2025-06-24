<?php

function ASRAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "iris_of_the_blossom" => "I",
    "okana_scar_wraps" => "AR",
    default => ""
  };
}

function ASRAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function ASREffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "okana_scar_wraps" => 1,
    default => 0
  };
}

function ASRCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    "okana_scar_wraps" => ClassContains($attackID, "NINJA", $mainPlayer) && TypeContains($attackID, "AA", $mainPlayer),
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
  global $currentPlayer, $combatChain;
    switch ($cardID) {
      case "enact_vengeance_red":
        if(ComboActive($cardID)) AddCurrentTurnEffect("enact_vengeance_red", $currentPlayer);
        return "";
      case "okana_scar_wraps":
        $attackID = $combatChain[0];
        if (ClassContains($attackID, "NINJA", $currentPlayer) && TypeContains($attackID, "AA", $currentPlayer)) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "iris_of_the_blossom":
        $search = "MYDECK:cardID=whirling_mist_blossom_yellow";
        $fromMod = "Deck,TT"; //pull it out of the deck, playable "This Turn"
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search, 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $currentPlayer, $fromMod, 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
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