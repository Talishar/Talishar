<?php


  function MONIllusionistCardType($cardID)
  {
    switch($cardID)
    {
      case "MON001": case "MON002": return "C";
      case "MON003": return "W";
      case "MON004": return "AA";
      case "MON005": return "I";
      case "MON006": return "I";
      case "MON007": return "AA";
      case "MON008": case "MON009": case "MON010": return "AA";
      case "MON011": case "MON012": case "MON013": return "I";
      case "MON014": case "MON015": case "MON016": return "AA";
      case "MON017": case "MON018": case "MON019": return "AA";
      case "MON020": case "MON021": case "MON022": return "AA";
      case "MON023": case "MON024": case "MON025": return "AA";
      case "MON026": case "MON027": case "MON028": return "AA";
      case "MON088": return "W";
      case "MON089": case "MON090": return "E";
      case "MON091": return "AA";
      case "MON092": case "MON093": case "MON094": return "I";
      case "MON095": case "MON096": case "MON097": return "A";
      case "MON098": case "MON099": case "MON100": return "AA";
      case "MON101": case "MON102": case "MON103": return "AA";
      case "MON104": return "T";
      case "MON404": return "M";
      default: return "";
    }
  }

  function MONIllusionistCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON003": return "Scepter";
      case "MON005": case "MON006": return "Aura";
      case "MON011": case "MON012": case "MON013": return "Aura";
      case "MON088": return "Orb";
      case "MON089": return "Legs";
      case "MON090": return "Arms";
      case "MON104": return "Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONIllusionistCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON004": return 2;
      case "MON005": return 6;
      case "MON006": return 4;
      case "MON007": return 2;
      case "MON008": case "MON009": case "MON010": return 2;
      case "MON011": case "MON012": case "MON013": return 4;
      case "MON014": case "MON015": case "MON016": return 2;
      case "MON017": case "MON018": case "MON019": return 2;
      case "MON020": case "MON021": case "MON022": return 2;
      case "MON023": case "MON024": case "MON025": return 2;
      case "MON026": case "MON027": case "MON028": return 1;
      case "MON091": return 3;
      case "MON092": case "MON093": case "MON094": return 3;
      case "MON095": case "MON096": case "MON097": return 1;
      case "MON098": case "MON099": case "MON100": return 2;
      case "MON101": case "MON102": case "MON103": return 1;
      default: return 0;
    }
  }

  function MONIllusionistPitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON004": case "MON005": case "MON006": case "MON007": case "MON011": case "MON012": case "MON013": return 2;
      case "MON008": case "MON014": case "MON017": case "MON020": case "MON023": case "MON026": return 1;
      case "MON009": case "MON015": case "MON018": case "MON021": case "MON024": case "MON027": return 2;
      case "MON010": case "MON016": case "MON019": case "MON022": case "MON025": case "MON028": return 3;
      case "MON091": return 1;
      case "MON092": case "MON095": case "MON098": case "MON101": return 1;
      case "MON093": case "MON096": case "MON099": case "MON102": return 2;
      case "MON094": case "MON097": case "MON100": case "MON103": return 3;
      case "MON404": return 0;
      default: return 0;
    }
  }

  function MONIllusionistBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON001": case "MON002": case "MON003": return 0;
      case "MON005": case "MON006": return 0;
      case "MON011": case "MON012": case "MON013": return 0;
      case "MON089": return 0;
      case "MON092": case "MON093": case "MON094": return 0;
      case "MON095": case "MON096": case "MON097": return 2;
      case "MON404": return 2;
      default: return 3;
    }
  }

  function MONIllusionistAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON004": return 5;
      case "MON007": return 6;
      case "MON008": case "MON014": case "MON017": case "MON020": case "MON026": return 7;
      case "MON009": case "MON015": case "MON018": case "MON021": case "MON023": case "MON027": return 6;
      case "MON010": case "MON016": case "MON019": case "MON022": case "MON024": case "MON028": return 5;
      case "MON025": return 4;
      case "MON091": return 9;
      case "MON098": return 8;
      case "MON099": return 7;
      case "MON100": return 6;
      case "MON101": return 5;
      case "MON102": return 4;
      case "MON103": return 3;
      default: return 0;
    }
  }

  function MONIllusionistPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "MON001": case "MON002":
        PlayAura("MON104", $currentPlayer);
        return "Prism created a Spectral Shield.";
      case "MON090":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Dream Weavers makes your next Illusionist attack action card you play lose Phantasm.";
      case "MON091":
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
        AddDecisionQueue("DRAW", $otherPlayer, "-", 1);
        return "Phantasmaclasm let you put a card from your opponent's hand on the bottom of their deck.";
      case "MON092": PlayAura("MON104", $currentPlayer);
      case "MON093": PlayAura("MON104", $currentPlayer);
      case "MON094": PlayAura("MON104", $currentPlayer);
        return "Prismatic Shield created Spectral Shields.";
      case "MON095": case "MON096": case "MON097":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Phantasmify makes your next attack be Illusionist, get +" . EffectAttackModifier($cardID) . " and phantasm.";
      default: return "";
    }
  }

  function MONIllusionistHitEffect($cardID)
  {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "MON004":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
        MainDrawCard();
        MainDrawCard();
        break;
      case "MON007":
        AddCurrentTurnEffect($cardID, $defPlayer);
        AddNextTurnEffect($cardID, $defPlayer);
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
        break;
      case "MON008": case "MON009": case "MON010": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON014": case "MON015": case "MON016":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
        PlayAura("MON104", $mainPlayer);
        break;
      case "MON017": case "MON018": case "MON019":
        DealArcane(1, 0, "PLAYCARD", $cardID, false, $mainPlayer);
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
        break;
      case "MON020": case "MON021": case "MON022":
        AddDecisionQueue("FINDINDICES", $mainPlayer, $cardID);
        AddDecisionQueue("MAYCHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
        break;
      case "MON023": case "MON024": case "MON025": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      case "MON026": case "MON027": case "MON028": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL"; break;
      default: break;
    }
  }

  function HasPhantasm($cardID)
  {
    switch($cardID)
    {
      case "MON004":
      case "MON007":
      case "MON008": case "MON009": case "MON010":
      case "MON014": case "MON015": case "MON016":
      case "MON017": case "MON018": case "MON019":
      case "MON020": case "MON021": case "MON022":
      case "MON023": case "MON024": case "MON025":
      case "MON026": case "MON027": case "MON028":
      case "MON091":
      case "MON098": case "MON099": case "MON100":
      case "MON101": case "MON102": case "MON103": return true;
      case "EVR138": FractalReplicationStats("HasPhantasm");
      case "EVR139": return true;
      case "EVR144": case "EVR145": case "EVR146": return true;
      case "EVR147": case "EVR148": case "EVR149": return true;
    }
  }

  function IsPhantasmActive()
  {
    global $combatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex;
    if(count($combatChain) == 0) return false;
    if(SearchCurrentTurnEffects("MON090", $mainPlayer) || SearchCurrentTurnEffects("EVR142", $mainPlayer)) { return false; }
    if(SearchCurrentTurnEffectsForCycle("EVR150", "EVR151", "EVR152", $mainPlayer)) return true;
    if(SearchCurrentTurnEffectsForCycle("MON095", "MON096", "MON097", $mainPlayer)) return true;
    if(SearchCurrentTurnEffectsForCycle("UPR155", "UPR156", "UPR157", $mainPlayer)) return true;
    if($combatChainState[$CCS_WeaponIndex] != "-1" && DelimStringContains(CardSubType($combatChain[0]), "Ally"))
    {
      $allies = &GetAllies($mainPlayer);
      if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "UPR043")) return true;
    }
    return HasPhantasm($combatChain[0]);//TODO: Incorporate things that can gain or lose phantasm
  }

  function ProcessPhantasmOnBlock($index)
  {
    global $combatChain, $combatChainState, $CCS_WeaponIndex, $mainPlayer;
    if(CardType($combatChain[$index]) != "AA") return;
    if(CardClass($combatChain[$index]) == "ILLUSIONIST") return;
    $attackID = $combatChain[0];
    $av = AttackValue($combatChain[$index]);
    $origAV = $av;
    if($attackID == "MON008" || $attackID == "MON009" || $attackID == "MON010") --$av;
    $av += AuraAttackModifiers($index);
    if($av < 6 && $origAV >= 6) WriteLog("Herald of Triumph reduced the attack below 6, so Phantasm does not trigger.");
    if(IsPhantasmActive() && ($av >= 6))
    {
      if($combatChainState[$CCS_WeaponIndex] != "-1" && DelimStringContains(CardSubType($combatChain[0]), "Ally")) DestroyAlly($mainPlayer, $combatChainState[$CCS_WeaponIndex]);
      AttackDestroyed($attackID);
      CloseCombatChain();
    }
  }

  function HasSpectra($cardID)
  {
    switch($cardID)
    {
      case "MON005": return true;
      case "MON006": return true;
      case "MON011": return true;
      case "MON012": return true;
      case "MON013": return true;
      case "EVR140": return true;
      case "EVR141": case "EVR142": case "EVR143": return true;
      default: return false;
    }
  }

  function GenesisStartTurnAbility()
  {
    global $mainPlayer;
    AddDecisionQueue("FINDINDICES", $mainPlayer, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from hand to put in your hero's soul.");
    AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("ADDSOUL", $mainPlayer, "HAND", 1);
    AddDecisionQueue("GENESIS", $mainPlayer, "-", 1);
  }

  function TheLibrarianEffect($player, $index)
  {
    $arsenal = &GetArsenal($player);
    --$arsenal[$index+2];
    ++$arsenal[$index+3];
    Draw($player);
    $log = "The Librarian drew a card";
    if($arsenal[$index+3] == 3)
    {
      $log .= " and searched for a specialization card";
      RemoveArsenal($player, $index);
      BanishCardForPlayer("MON404", $player, "ARS", "-");
      AddDecisionQueue("FINDINDICES", $player, "DECKSPEC");
      AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
    }
    WriteLog($log . ".");
  }

?>
