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
