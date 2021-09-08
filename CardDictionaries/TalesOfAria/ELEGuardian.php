<?php


  function ELEGuardianCardType($cardID)
  {
    switch($cardID)
    {
      case "ELE001": case "ELE002": return "C";
      case "ELE003": return "W";
      case "ELE004": return "AA";
      case "ELE016": case "ELE017": case "ELE018": return "AA";
      default: return "";
    }
  }

  function ELEGuardianCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ELE003": return "Hammer";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ELEGuardianCardCost($cardID)
  {
    switch($cardID)
    {
      case "ELE004": return 4;
      case "ELE016": case "ELE017": case "ELE018": return 6;
      default: return 0;
    }
  }

  function ELEGuardianPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ELE004": return 1;
      case "ELE016": return 1;
      case "ELE017": return 2;
      case "ELE018": return 3;
      default: return 0;
    }
  }

  function ELEGuardianBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ELE001": case "ELE002": case "ELE003": return 0;
      default: return 3;
    }
  }

  function ELEGuardianAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ELE003": return 4;
      case "ELE004": return 8;
      case "ELE016": return 10;
      case "ELE017": return 9;
      case "ELE018": return 8;
      default: return 0;
    }
  }

  function ELEGuardianPlayAbility($cardID, $from, $resourcesPaid)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  function ELEGuardianHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }


?>

