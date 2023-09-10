<?php


  function TCCHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "TCC088":
        if(ComboActive()) DamageTrigger($defPlayer, damage:1, type:"DAMAGE", source:$cardID);
        break;
      default: break;
    }
  }

  function EVOHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "EVO236":
        if(IsHeroAttackTarget()) {
          $deck = new Deck($defPlayer);
          if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
          $deck->BanishTop(banishedBy:$mainPlayer);
        }
        break;
      default: break;
    }
  }

?>
