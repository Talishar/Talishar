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

?>
