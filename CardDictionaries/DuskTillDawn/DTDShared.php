<?php

function DTDAbilityCost($cardID)
{
  switch($cardID) {

    default: return 0;
  }
}

function DTDAbilityType($cardID, $index = -1)
{
  switch($cardID) {

    default: return "";
  }
}

function DTDAbilityHasGoAgain($cardID)
{
  switch($cardID) {

    default: return false;
  }
}

function DTDEffectAttackModifier($cardID)
{
  global $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  if(count($params) > 1) $parameter = $params[1];
  switch($cardID) {

    default:
      return 0;
  }
}

function DTDCombatEffectActive($cardID, $attackID)
{
  global $combatChainState, $CCS_IsBoosted, $mainPlayer;
  $params = explode(",", $cardID);
  $cardID = $params[0];
  switch($cardID) {
    case "DTD198": return true;//Call Down the Lightning
    default:
      return false;
  }
}

function DTDCardTalent($cardID)
{
  $number = intval(substr($cardID, 3));
  if($number <= 0) return "";
  else return "NONE";
}

function DTDPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $rv = "";
  switch($cardID) {
    case "DTD198"://Call Down the Lightning
      AddCurrentTurnEffect("DTD198", $currentPlayer);
      break;
    case "DTD219"://Lost in Thought
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRHAND:type=A&THEIRHAND:type=AA");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
      return "";
    default:
      return "";
  }
}

function DTDHitEffect($cardID)
{
  switch($cardID) {

    default: break;
  }
}

function DoesAttackTriggerMirage()
{
  global $combatChain, $mainPlayer;
  if(ClassContains($combatChain[0], "ILLUSIONIST", $mainPlayer)) return false;
  return CachedTotalAttack() >= 6;
}

function ProcessMirageOnBlock($index)
{
  global $mainPlayer;
  if(IsMirageActive($index) && DoesAttackTriggerMirage())
  {
    AddLayer("LAYER", $mainPlayer, "MIRAGE");
  }
}

function IsMirageActive($index)
{
  global $combatChain;
  if(count($combatChain) == 0) return false;
  return HasMirage($combatChain[$index]);
}

function HasMirage($cardID)
{
  switch($cardID)
  {
    case "DTD218": return true;
    default: return false;
  }
}

function MirageLayer()
{
  global $combatChain, $mainPlayer, $combatChainState, $defPlayer, $turn, $layers;
  if(DoesAttackTriggerMirage())
  {
    for($i=count($combatChain)-CombatChainPieces(); $i>=CombatChainPieces(); $i-=CombatChainPieces())
    {
      if(IsMirageActive($i))
      {
        WriteLog(CardLink($combatChain[$i], $combatChain[$i]) . " is destroyed by Mirage.");
        AddGraveyard($combatChain[$i], $defPlayer, "CC");
        RemoveCombatChain($i);
      }
    }
  }
  else {
    $turn[0] = "D";
    $currentPlayer = $mainPlayer;
    for($i=count($layers)-LayerPieces(); $i >= 0; $i-=LayerPieces())
    {
      if($layers[$i] == "DEFENDSTEP" || ($layers[$i] == "LAYER" && $layers[$i+2] == "MIRAGE"))
      {
        for($j=$i; $j<($i+LayerPieces()); ++$j) unset($layers[$j]);
      }
    }
    $layers = array_values($layers);
  }
}
