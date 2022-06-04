<?php


  function UPRIllusionistCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return "C";
      case "UPR004": return "E";
      case "UPR008": return "A";
      case "UPR018": case "UPR019": case "UPR020": return "AA";
      case "UPR033": case "UPR034": case "UPR035": return "A";
      case "UPR036": case "UPR037": case "UPR038": return "A";
      case "UPR042": case "UPR043": return "T";
      default: return "";
    }
  }

  function UPRIllusionistCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR004": return "Arms";
      case "UPR042": return "Dragon,Ally";
      case "UPR043": return "Ash";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRIllusionistCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return -1;
      case "UPR004": return -1;
      case "UPR008": return 4;
      case "UPR018": case "UPR019": case "UPR020": return 1;
      case "UPR033": case "UPR034": case "UPR035": return 1;
      case "UPR036": case "UPR037": case "UPR038": return 0;
      case "UPR042": case "UPR043": return -1;
      default: return 0;
    }
  }

  function UPRIllusionistPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR008": return 1;
      case "UPR018": case "UPR033": case "UPR036": return 1;
      case "UPR019": case "UPR034": case "UPR037": return 2;
      case "UPR020": case "UPR035": case "UPR038": return 3;
      default: return 0;
    }
  }

  function UPRIllusionistBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR008": return 3;
      case "UPR018": case "UPR019": case "UPR020": return 3;
      case "UPR033": case "UPR034": case "UPR035": return 2;
      case "UPR036": case "UPR037": case "UPR038": return 2;
      default: return -1;
    }
  }

  function UPRIllusionistAttackValue($cardID)
  {
    switch($cardID)
    {
      case "UPR018": return 3;
      case "UPR019": return 2;
      case "UPR020": return 1;
      case "UPR042": return 1;
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

  function Transform($player, $materialType, $into)
  {
    
  }

?>
