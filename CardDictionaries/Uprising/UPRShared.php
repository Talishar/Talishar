<?php

  function UPRAbilityCost($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  function UPRHasGoAgain($cardID)
  {
    switch($cardID)
    {

      default: return false;
    }
  }

  function UPRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {

      default: return false;
    }
  }

  function UPREffectAttackModifier($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRCombatEffectActive($cardID, $attackID)
  {
    switch($cardID)
    {

      default: return false;
    }
  }

  function UPRCardTalent($cardID)
  {
    $number = intval(substr($cardID, 3));
    if($number <= 0) return "??";
    else if($number >= 1 && $number <= 111) return "ELEMENTAL";//Is this right?
    else if($number == 112) return "LIGHTNING,ICE";
    else if($number == 113) return "LIGHTNING,EARTH";
    else if($number == 114) return "ICE,EARTH";
    else if($number >= 115 && $number <= 143) return "EARTH";
    else if($number >= 144 && $number <= 172) return "ICE";
    else if($number >= 173 && $number <= 201) return "LIGHTNING";
    else return "NONE";
  }

?>
