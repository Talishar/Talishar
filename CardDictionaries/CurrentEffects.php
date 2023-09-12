<?php

  function TCCEffectAttackModifier($cardID)
  {
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "TCC057": return $idArr[1];
      case "TCC083": return 1;
      case "TCC086": return 1;
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
        case "TCC057": return true;
        case "TCC083": return true;
        case "TCC086": return CardName($attackID) == "Crouching Tiger";
        case "TCC409": return true;
        default: return false;
      }
    }
?>
