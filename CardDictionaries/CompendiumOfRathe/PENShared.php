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

function DoSolrayPlating($targetPlayer, $damage)
{
  if ($damage > 0) {
    PrependDecisionQueue("ADDTOLASTRESULT", $targetPlayer, "{0}", 1);
    PrependDecisionQueue("PASSPARAMETER", $targetPlayer, 1, 1); //prevent 1 damage
    if (!SearchCurrentTurnEffects("solray_plating", $targetPlayer))
      PrependDecisionQueue("CHARFLAGDESTROY", $targetPlayer, FindCharacterIndex($targetPlayer, "solray_plating"), 1);
    PrependDecisionQueue("MULTIBANISHSOUL", $targetPlayer, "-", 1);
    PrependDecisionQueue("MAYCHOOSEMYSOUL", $targetPlayer, "<-", 1);
    PrependDecisionQueue("SETDQCONTEXT", $targetPlayer, "Banish a card from soul to " . CardLink("solray_plating"), 1);
    PrependDecisionQueue("FINDINDICES", $targetPlayer, "SOULINDICES0", 1);
    PrependDecisionQueue("SETDQVAR", $targetPlayer, "0", 1); // current damage prevention
    LogDamagePreventedStats($targetPlayer, 1);
  }
}

function SuperFrozen($player, $MZIndex) {
  global $CurrentTurnEffects;
  for ($i = 0; $i < $CurrentTurnEffects->NumEffects(); ++$i) {
    $Effect = $CurrentTurnEffects->Effect($i, true);
    if ($Effect->PlayerID() != $player || $Effect->EffectID() != "channel_galcias_cradle_blue") continue;
    $mzUID = MZIndexToMZUID($player, $MZIndex);
    if ($mzUID == (explode(",", $Effect->AppliestoUniqueID())[1] ?? "-")) return true;
  }
  return false;
}