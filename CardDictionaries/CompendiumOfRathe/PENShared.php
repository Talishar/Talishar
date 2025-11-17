<?php
function PENAbilityType($cardID, $index = -1, $from = "-"): string
{
  switch ($cardID) {
    default:
      return "";
  }
}

function PENAbilityCost($cardID): int
{
  global $currentPlayer;
  switch ($cardID) {
    default:
      return 0;
  }
}

function PENAbilityHasGoAgain($cardID): bool
{
  switch ($cardID) {
    default:
      return false;
  }
}

function PENEffectPowerModifier($cardID): int
{
  switch ($cardID) {
    default:
      return  0;
  }
}

function PENCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  switch ($cardID) {
    default:
      return false;
  }
  ;
}

function PENPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $mainPlayer, $combatChainState, $combatChain, $chainLinkSummary, $chainLinks, $defPlayer;
  global $CombatChain;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    default:
      return "";
  }
}

function PENHitEffect($cardID): void
{
  global $mainPlayer;
  switch ($cardID) {
    default:
      break;
  }
}