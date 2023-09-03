<?php

  function TCCAbilityCost($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function TCCAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "TCC079": return "I";
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
