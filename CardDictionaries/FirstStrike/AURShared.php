<?php

/*  Aurora First Strike Deck METHODS
  // Ability Type
  // Ability Cost
  // Combat Effect active
  // Effect Attack Modifier
  // Play Ability
  // Hit Effect
*/

function AUREffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "AUR014" => 3,
    "AUR021" => 2,
    "AUR022", "AUR025" => 1,
    default => 0,
  };
}

function AURCombatEffectActive($cardID, $attackID): bool|string
{
  global $mainPlayer;
  return match ($cardID) {
    "AUR014", "AUR021" => TalentContainsAny($attackID, "LIGHTNING,ELEMENTAL", $mainPlayer),
    "AUR022", "AUR025" => true,
    default => "",
  };
}

function AURPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $mainPlayer, $CS_NumLightningPlayed;
  switch ($cardID) {
    case "AUR013":
      if (GetClassState($mainPlayer, $CS_NumLightningPlayed) > 0) {
        DealArcane(3, 0, "PLAYCARD", $cardID);
      }
      return "";
    case "AUR020":
      if (GetClassState($mainPlayer, $CS_NumLightningPlayed) > 0) {
        DealArcane(2, 0, "PLAYCARD", $cardID);
      }
      return "";
    case "AUR014":
    case "AUR023":
    case "AUR021":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    default:
      return "";
  }
}

function AURHitEffect($cardID): void
{
  global $mainPlayer, $CS_NumLightningPlayed;
  switch ($cardID) {
    case "AUR012": case "AUR019":
    if (GetClassState($mainPlayer, $CS_NumLightningPlayed) > 0) {
      DealArcane(1, 0, "PLAYCARD", $cardID, false, $mainPlayer);
    }
    break;
    default:
      break;
  }
}