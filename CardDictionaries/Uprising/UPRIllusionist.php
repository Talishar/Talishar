<?php


  function UPRIllusionistCardType($cardID)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  function UPRIllusionistCardSubType($cardID)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRIllusionistCardCost($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRIllusionistPitchValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRIllusionistBlockValue($cardID)
  {
    switch($cardID)
    {

      default: return 3;
    }
  }

  function UPRIllusionistAttackValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRIllusionistPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $CS_PitchedForThisCard, $CS_DamagePrevention;
    $rv = "";
    switch($cardID)
    {

      default: return "";
    }
  }

  function UPRIllusionistHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_AttackFused;
    switch($cardID)
    {

      default: break;
    }
  }


?>
