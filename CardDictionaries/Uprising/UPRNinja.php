<?php


  function UPRNinjaCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR044": return "C";
      default: return "";
    }
  }

  function UPRNinjaCardSubType($cardID)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRNinjaCardCost($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRNinjaPitchValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRNinjaBlockValue($cardID)
  {
    switch($cardID)
    {

      default: return -1;
    }
  }

  function UPRNinjaAttackValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRNinjaPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    $rv = "";
    switch($cardID)
    {
      case "UPR044": case "UPR045":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDHAND", $currentPlayer, "-", 1);
        return "";
      default: return "";
    }
  }

  function UPRNinjaHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

?>
