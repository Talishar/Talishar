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
      case "EVO055":
        if(EvoUpgradeAmount($mainPlayer) >= 1) PummelHit();
        break;
      case "EVO236":
        if(IsHeroAttackTarget()) {
          $deck = new Deck($defPlayer);
          if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
          $deck->BanishTop(banishedBy:$mainPlayer);
        }
        $options = GetChainLinkCards($defPlayer, "", "C");
        AddDecisionQueue("MAYCHOOSECOMBATCHAIN", $mainPlayer, $options);
        AddDecisionQueue("REMOVECOMBATCHAIN", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $defPlayer, "CC,-,EVO236", 1);
        break;
      default: break;
    }
  }

?>
