<?php

function ROGUEAbilityCost($cardID)
{
    switch ($cardID) {

        default: return 0;
    }
}

function ROGUEAbilityType($cardID, $index = -1)
{
    switch ($cardID) {

        default: return "";
    }
}

// Natural go again or ability go again. Attacks that gain go again should be in CoreLogic (due to hypothermia)
function ROGUEHasGoAgain($cardID)
{
    switch ($cardID) {

        default: return false;
    }
}

function ROGUEAbilityHasGoAgain($cardID)
{
    switch ($cardID) {


        default: return false;
    }
}

function ROGUEEffectAttackModifier($cardID)
{
    global $combatChainState, $CCS_LinkBaseAttack;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if (count($params) > 1) $parameter = $params[1];
    switch ($cardID) {

        default:
            return 0;
    }
}

function ROGUECombatEffectActive($cardID, $attackID)
{
    $params = explode(",", $cardID);
    $cardID = $params[0];
    switch ($cardID) {

        default:
            return false;
    }
}


function ROGUECardTalent($cardID) // TODO
{
  $number = intval(substr($cardID, 3));
  if($number <= 0) return "";
//   else if($number >= 3 && $number <= 124) return "";
//   else if($number >= 125 && $number <= 150) return "";
//   else if($number >= 406 && $number <= 417 ) return "";
//   else if($number >= 439 && $number <= 441) return "";
  else return "NONE";
}

function ROGUECardType($cardID)
{
    switch ($cardID) {


        default:
            return "";
    }
}

function ROGUECardSubtype($cardID)
{
    switch ($cardID) {


        default: return "";
    }
}

function ROGUECardCost($cardID)
{
    switch ($cardID) {

        default: return 0;
    }
}

function ROGUEPitchValue($cardID)
{
    switch ($cardID) {


        default: return 3;
    }
}

function ROGUEBlockValue($cardID)
{
    switch ($cardID) {

        default:
            return 3;
    }
}

function ROGUEAttackValue($cardID)
{
    switch ($cardID) {

        default:
            return 0;
    }
}

function ROGUEPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
    global $currentPlayer, $CS_PlayIndex;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch ($cardID) {

        default:
            return "";
    }
}

function ROGUEHitEffect($cardID)
{
    switch ($cardID) {

        default: break;
    }
}
