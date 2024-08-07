<?php

/*  Terra First Strike Deck METHODS
  // Ability Type
  // Ability Cost
  // Combat Effect active
  // Effect Attack Modifier
  // Play Ability
  // Hit Effect
*/

function TERAbilityType($cardID): string
{
  return match ($cardID) {
    "TER002" => "AA",
    default => ""
  };
}

function TERAbilityCost($cardID): int
{
  return match ($cardID) {
    "TER002" => 3,
    default => 0
  };
}

function TERCombatEffectActive($cardID): bool
{
  return match ($cardID) {
    "TER002", "TER011", "TER015", "TER012", "TER016", "TER019", "TER006", "TER017", "TER024" => true,
    default => false
  };
}

function TEREffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "TER017" => 3,
    "TER024" => 2,
    "TER002", "TER012", "TER016", "TER006" => 1,
    default => 0
  };
}

function TERPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  switch ($cardID) {
    case "TER002":
      if (SearchCardList($additionalCosts, $currentPlayer, talent: "EARTH") != "") AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "TER008": case "TER014":
      if (SearchCardList($additionalCosts, $currentPlayer, talent: "EARTH") != "") PlayAura("HVY241", $currentPlayer); //Might
      return "";
    case "TER011":
    case "TER015":
    case "TER012":
    case "TER016":
      if (SearchCardList($additionalCosts, $currentPlayer, talent: "EARTH") != "") AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "TER020":
    case "TER026":
    case "TER019":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "TER017":
      AddCurrentTurnEffect("TER017-INACTIVE", $currentPlayer);
      return "";
    case "TER024":
      AddCurrentTurnEffect("TER024-INACTIVE", $currentPlayer);
      return "";
    case "TER018":
      PlayAura("HVY241", $currentPlayer);
      PlayAura("HVY241", $currentPlayer);
      if (SearchCardList($additionalCosts, $currentPlayer, talent: "EARTH") != "") {
        PlayAura("HVY241", $currentPlayer);
      }
      return "";
    case "TER025":
      PlayAura("HVY241", $currentPlayer);
      if (SearchCardList($additionalCosts, $currentPlayer, talent: "EARTH") != "") {
        PlayAura("HVY241", $currentPlayer);
      }
      return "";
    default:
      return "";
  }
}

function TerraEndPhaseAbility($characterID, $player): void
{
  $resources = &GetResources($player);
  $hand = &GetHand($player);
  $earthTalent = SearchCount(SearchPitch($player, talent: "EARTH"));
  if (($earthTalent >= 1) && (Count($hand) > 0 || $resources[0] > 0)) {
    AddDecisionQueue("YESNO", $player, "if you want to pay 1 to create a " . CardLink("HVY241", "HVY241"), 0, 1);
    AddDecisionQueue("NOPASS", $player, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
    AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
    AddDecisionQueue("WRITELOG", $player, CardLink($characterID, $characterID) . " created a " . CardLink("HVY241", "HVY241") . " token ", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "HVY241", 1);
    AddDecisionQueue("PUTPLAY", $player, "-", 1);
  }
}