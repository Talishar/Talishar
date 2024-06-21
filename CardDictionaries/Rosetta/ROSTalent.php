<?php


  function ROSRunebladePlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "ROS033":
        PlayAura("ARC112", $currentPlayer);
        return true;
      default: return "";
    }
  }

  function ARCRunebladeHitEffect($cardID)
  {
    global $combatChainState, $mainPlayer, $CCS_DamageDealt;
    switch($cardID)
    {
      case "ARC077":
        PlayAura("ARC112", $mainPlayer);
        break;
      case "ARC080":
        $damageDone = $combatChainState[$CCS_DamageDealt];
        PlayAura("ARC112", $mainPlayer, $damageDone);
        break;
      default: break;
    }
    return "";
  }

?>
