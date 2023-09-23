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
      case "EVO006":
        if(IsHeroAttackTarget()) {
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card with Crank to get a steam counter", 1);
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYITEMS:hasCrank=true");
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZADDSTEAMCOUNTER", $mainPlayer, "-", 1);
        }
        break;
      case "EVO055":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) >= 1) PummelHit();
        break;
      case "EVO056":
        if(IsHeroAttackTarget() && EvoUpgradeAmount($mainPlayer) >= 1) DestroyArsenal($defPlayer);
        break;
      case "EVO138":
        if(IsHeroAttackTarget())
        {
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to put into play");
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYBANISH:maxCost=1;subtype=Item&THEIRBANISH:maxCost=1;subtype=Item");
          AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
          AddDecisionQueue("PUTPLAY", $mainPlayer, "0", 1);
        }
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
      case "EVO241":
        PlayAura("DTD232", $defPlayer);
        PlayAura("WTR225", $defPlayer);
        break;
      default: break;
    }
  }

?>
