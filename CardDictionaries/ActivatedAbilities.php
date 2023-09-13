<?php

  function TCCAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "TCC002": return 3;
      case "TCC028": return 3;
      default: return 0;
    }
  }

  function TCCAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "TCC002": return "AA";
      case "TCC028": return "AA";
      case "TCC079": return "I";
      case "TCC082": return "I";
      default: return "";
    }
  }

  function TCCAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      default: return false;
    }
  }

  function EVOAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "EVO007": case "EVO008": return 3;
      case "EVO235": return 2;
      case "EVO247": return 2;
      default: return 0;
    }
  }

  function EVOAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "EVO007": case "EVO008": return "I";
      case "EVO235": return "AR";
      case "EVO247": return "A";
      default: return "";
    }
  }

  function EVOAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVO247": return true;
      default: return false;
    }
  }

?>
