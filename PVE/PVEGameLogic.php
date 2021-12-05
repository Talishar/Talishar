<?php

  function StanceFlipEffect($cardID)
  {
    switch($cardID)
    {
      case "OVRPVE017":
        Recover(-1, "-", true);
        break;
      default: break;
    }
  }

  function Recover($mode=-1, $cardID="-", $fromDQ=false)
  {
    $bossDeck = &GetGlobalZone("BossDeck");
    $bossStatus = &GetGlobalZone("BossStatus");
    $bossDiscard = &GetGlobalZone("BossDiscard");
    if(count($bossDiscard) == 0) return;
    $bossStatus[0] = 1;
    while(count($bossDiscard) > 0) array_unshift($bossDeck, array_shift($bossDiscard));
    AddDecisionQueue("SHUFFLEGLOBALZONE", 1, "BossDeck");
    if($mode != -1 && $cardID != "-") AddDecisionQueue("RESUMEPROCESSINPUT", 1, $mode, $cardID);
    if(!$fromDQ) ProcessDecisionQueue(1);
  }
?>