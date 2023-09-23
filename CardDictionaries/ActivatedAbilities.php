<?php

  function TCCAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "TCC002": return 3;
      case "TCC028": return 3;
      case "TCC051": case "TCC052": case "TCC053": case "TCC054": case "TCC080": return 0;
      default: return 0;
    }
  }

  function TCCAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      case "TCC002": return "AA";
      case "TCC028": return "AA";
      case "TCC051": case "TCC052": case "TCC053": case "TCC054": return "A";
      case "TCC079": return "I";
      case "TCC080": return "I";
      case "TCC082": return "I";
      default: return "";
    }
  }

  function TCCAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "TCC051": case "TCC052": case "TCC053": case "TCC054": return true;
      default: return false;
    }
  }

  function EVOAbilityCost($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "EVO004": case "EVO005": return 2;
      case "EVO006": return 1;
      case "EVO007": case "EVO008": return 3;
      case "EVO009":
        $evoAmt = EvoUpgradeAmount($currentPlayer);
        if($evoAmt == 1) return 3;
        else if($evoAmt >= 2) return 1;
        else return 0;
      case "EVO235": return 2;
      case "EVO247": return 2;
      default: return 0;
    }
  }

  function EVOAbilityType($cardID, $index=-1)
  {
    global $currentPlayer, $CS_NumCranked, $CS_NumBoosted;
    switch($cardID)
    {
      case "EVO004": case "EVO005": return GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? "A" : "";
      case "EVO006": return GetClassState($currentPlayer, $CS_NumCranked) > 0 ? "AA" : "";
      case "EVO007": case "EVO008": return "I";
      case "EVO009": return EvoUpgradeAmount($currentPlayer) >= 1 ? "AA" : "";
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
