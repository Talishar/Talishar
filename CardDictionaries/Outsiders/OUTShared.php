<?php

function OUTAbilityCost($cardID)
{
  switch($cardID)
  {
    default: return 0;
  }
}

  function OUTAbilityType($cardID, $index=-1)
  {
    switch ($cardID)
    {

      default: return "";
    }
  }

  function OUTAbilityHasGoAgain($cardID)
  {
    switch ($cardID)
    {
      default: return false;
    }
  }

  function OutEffectAttackModifier($cardID)
  {
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {
      default: return 0;
    }
  }

  function OUTCombatEffectActive($cardID, $attackID)
  {
    global $mainPlayer;
    $idArr = explode("-", $cardID);
    $cardID = $idArr[0];
    switch ($cardID)
    {

      default: return false;
    }
  }

  function OUTHasGoAgain($cardID)
  {
    switch ($cardID)
    {

      default: return false;
    }
  }

  function OUTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer;
    $rv = "";
    switch ($cardID)
    {

      default: return "";
    }
  }

  function OUTHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    $attackID = $combatChain[0];
    switch ($cardID)
    {

      default: break;
    }
  }

?>
