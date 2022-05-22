<?php


  function UPRTalentCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR139": return "A";
      case "UPR147": case "UPR148": case "UPR149": return "A";
      default: return "";
    }
  }

  function UPRTalentCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR139": return "Affliction,Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRTalentCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR139": return 0;
      case "UPR147": case "UPR148": case "UPR149": return 1;
      default: return 0;
    }
  }

  function UPRTalentPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR139": return 3;
      case "UPR147": return 1;
      case "UPR148": return 2;
      case "UPR149": return 3;
      default: return 0;
    }
  }

  function UPRTalentBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR139": return 2;
      case "UPR147": case "UPR148": case "UPR149": return 2;
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
      case "UPR147": case "UPR148": case "UPR149":
        if($cardID == "UPR147") $cost = 3;
        else if($cardID == "UPR148") $cost = 2;
        else $cost = 1;
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to pay $cost to prevent an arsenal or ally from being frozen");
        AddDecisionQueue("BUTTONINPUT", $otherPlayer, "0," . $cost, 0, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,THEIRALLY|THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        if($from == "ARS") MyDrawCard();
        return "";
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
