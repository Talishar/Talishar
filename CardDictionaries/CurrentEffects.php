<?php

  function TCCEffectAttackModifier($cardID)
  {
    $idArr = explode(",", $cardID);
    $cardID = $idArr[0];
    switch($cardID) {
      case "TCC057": return $idArr[1];
      default: return 0;
    }
  }

    function TCCCombatEffectActive($cardID, $attackID)
    {
      global $mainPlayer;
      $idArr = explode(",", $cardID);
      $cardID = $idArr[0];
      switch($cardID) {
        case "TCC057": return true;
        default: return false;
      }
    }
?>
