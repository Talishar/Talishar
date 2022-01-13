<?php

  function EVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      default: return 0;
    }
  }

  function EVRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain;
    switch($cardID)
    {
      default: return false;
    }
  }


?>

