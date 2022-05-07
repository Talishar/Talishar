<?php


  function UPRTalentCardType($cardID)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  function UPRTalentCardSubType($cardID)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRTalentCardCost($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRTalentPitchValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRTalentBlockValue($cardID)
  {
    switch($cardID)
    {

      default: return 2;
    }
  }

  function UPRTalentAttackValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRTalentPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $CS_PlayIndex;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {

      default: return "";
    }
  }

  function UPRTalentHitEffect($cardID)
  {
    switch($cardID)
    {

      default: break;
    }
  }

?>
