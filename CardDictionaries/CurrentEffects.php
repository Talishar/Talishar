<?php

  function TCCEffectAttackModifier($cardID)
  {
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "TCC037": return 6;
      case "TCC038": return 5;
      case "TCC042": return 5;
      case "TCC043": return 4;
      case "TCC057": return $idArr[1];
      case "TCC083": return 1;
      case "TCC086": case "TCC094": return 1;
      case "TCC105": return 1;
      case "TCC409": return 1;
      default: return 0;
    }
  }

    function TCCCombatEffectActive($cardID, $attackID)
    {
      global $mainPlayer;
      $idArr = explode(",", $cardID);
      $cardID = $idArr[0];
      switch($cardID) {
        case "TCC035": return true;
        case "TCC037": case "TCC038": case "TCC042": case "TCC043": return ClassContains($attackID, "GUARDIAN", $mainPlayer) && CardType($attackID) == "AA";
        case "TCC057": return true;
        case "TCC083": return true;
        case "TCC086": case "TCC094": return CardName($attackID) == "Crouching Tiger";
        case "TCC105": return true;
        case "TCC409": return true;
        default: return false;
      }
    }
?>
