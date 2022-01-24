<?php

  function EVRAbilityCost($cardID)
  {
    switch($cardID)
    {
      default: return 0;
    }
  }

  function EVRAbilityType($cardID, $index=-1)
  {
    switch($cardID)
    {
      default: return "";
    }
  }

  function EVRHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "EVR160": return true;
      default: return false;
    }
  }

  function EVRAbilityHasGoAgain($cardID)
  {
    switch($cardID)
    {
      default: return false;
    }
  }

  function EVREffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "EVR160": return -1;
      default: return 0;
    }
  }

  function EVRCombatEffectActive($cardID, $attackID)
  {
    global $combatChain;
    switch($cardID)
    {
      case "EVR019": return HasCrush($attackID);
      case "EVR160": return true;
      default: return false;
    }
  }

  function EVRCardType($cardID)
  {
    switch($cardID)
    {
      case "EVR019": return "C";
      case "EVR027": case "EVR028": case "EVR029": return "AA";
      case "EVR120": return "C";
      case "EVR159": case "EVR160": return "A";
      case "EVR161": case "EVR162": case "EVR163": return "AA";
      case "EVR173": return "I";
      default: return "";
    }
  }

  function EVRCardSubtype($cardID)
  {
    switch($cardID)
    {

      default: return "";
    }
  }

  function EVRCardCost($cardID)
  {
    switch($cardID)
    {
      case "EVR019": return 0;
      case "EVR027": case "EVR028": case "EVR029": return 7;
      case "EVR120": return 0;
      case "EVR159": return 3;
      case "EVR160": return 1;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR173": return 0;
      default: return 0;
    }
  }

  function EVRPitchValue($cardID)
  {
    switch($cardID)
    {
      case "EVR019": return 0;
      case "EVR027": return 1;
      case "EVR028": return 2;
      case "EVR029": return 3;
      case "EVR120": return 0;
      case "EVR159": return 1;
      case "EVR160": return 3;
      case "EVR161": return 1;
      case "EVR162": return 2;
      case "EVR163": return 3;
      case "EVR173": return 1;
      default: return 3;
    }
  }

  function EVRBlockValue($cardID)
  {
    switch($cardID)
    {
      case "EVR019": return 0;
      case "EVR120": return 0;
      case "EVR159": return 2;
      case "EVR161": case "EVR162": case "EVR163": return 2;
      case "EVR173": return -1;
      default: return 3;
    }
  }

  function EVRAttackValue($cardID)
  {
    switch($cardID)
    {
      case "EVR027": return 10;
      case "EVR028": return 9;
      case "EVR029": return 8;
      case "EVR161": return 4;
      case "EVR162": return 3;
      case "EVR163": return 2;
      default: return 0;
    }
  }

  function EVRPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "EVR160":
        Draw(1);
        Draw(2);
        AddNextTurnEffect($cardID, $otherPlayer);
        return "This Round's on Me drew a card for each player and gave attacks targeting you -1.";
      case "EVR173": case "EVR174": case "EVR175":
        if($cardID == "EVR173") $opt = 3;
        else if($cardID == "EVR174") $opt = 2;
        else if($cardID == "EVR175") $opt = 1;
        Opt($cardID, $opt);
        return "";
      default: return "";
    }
  }


?>
