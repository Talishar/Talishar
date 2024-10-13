<?php

  function MONIllusionistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $defPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "MON001": case "MON002":
        PlayAura("MON104", $currentPlayer);
        return "";
      case "MON008": case "MON009": case "MON010":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "MON090":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON091":
        if(!IsAllyAttackTarget()) {
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
          AddDecisionQueue("WRITELOG", $currentPlayer, "<0> was put on the bottom of the deck.", 1);
          AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
          AddDecisionQueue("DRAW", $otherPlayer, "-");
        }
        return "";
      case "MON092": PlayAura("MON104", $currentPlayer, 3); return "";
      case "MON093": PlayAura("MON104", $currentPlayer, 2); return "";
      case "MON094": PlayAura("MON104", $currentPlayer, 1); return "";
      case "MON095": case "MON096": case "MON097":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      default: return "";
    }
  }

  function MONIllusionistHitEffect($cardID)
  {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "MON004":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        Draw($mainPlayer);
        Draw($mainPlayer);
        break;
      case "MON007":
        if(!IsAllyAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
          AddNextTurnEffect($cardID, $defPlayer);
        }
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "MON008": case "MON009": case "MON010": 
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "MON014": case "MON015": case "MON016":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        PlayAura("MON104", $mainPlayer);
        break;
      case "MON017": case "MON018": case "MON019":
        DealArcane(1, 0, "PLAYCARD", $cardID, false, $mainPlayer);
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "MON020": case "MON021": case "MON022":
        AddDecisionQueue("FINDINDICES", $mainPlayer, $cardID);
        AddDecisionQueue("MAYCHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
        AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $mainPlayer, "<0> was selected.", 1);
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "MON023": case "MON024": case "MON025": 
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "MON026": case "MON027": case "MON028": 
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      default: break;
    }
  }

  function HasPhantasm($cardID)
  {
    switch($cardID)
    {
      case "MON004": case "MON007": case "MON008": case "MON009": case "MON010": case "MON014":
      case "MON015": case "MON016": case "MON017": case "MON018": case "MON019": case "MON020":
      case "MON021": case "MON022": case "MON023": case "MON024": case "MON025": case "MON026":
      case "MON027": case "MON028": case "MON091": case "MON098": case "MON099": case "MON100":
      case "MON101": case "MON102": case "MON103": return true;
      case "EVR138": return FractalReplicationStats("HasPhantasm");
      case "EVR139": case "EVR144": case "EVR145": case "EVR146": case "EVR147": case "EVR148": case "EVR149": return true;
      case "UPR021": case "UPR022": case "UPR023": case "UPR027": case "UPR028": case "UPR029": case "UPR153": case "UPR551": return true;
      case "DYN215": case "DYN216": case "DYN224": case "DYN225": case "DYN226": case "DYN227": case "DYN228": case "DYN229":
      case "EVO244":
        return true;
      default: return false;
    }
  }

  function IsPhantasmActive()
  {
    global $CombatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex;
    if(!$CombatChain->HasCurrentLink()) return false;
    $attackID = $CombatChain->AttackCard()->ID();
    if((SearchCurrentTurnEffects("MON090", $mainPlayer) && CardType($attackID) == "AA") || SearchCurrentTurnEffects("EVR142", $mainPlayer) || SearchCurrentTurnEffects("UPR154", $mainPlayer) || SearchCurrentTurnEffects("UPR412", $mainPlayer)) { return false; }
    if(SearchCurrentTurnEffectsForCycle("EVR150", "EVR151", "EVR152", $mainPlayer)) return true;
    if(SearchCurrentTurnEffectsForCycle("MON095", "MON096", "MON097", $mainPlayer)) return true;
    if(SearchCurrentTurnEffectsForCycle("UPR155", "UPR156", "UPR157", $mainPlayer)) return true;
    if($combatChainState[$CCS_WeaponIndex] != "-1" && DelimStringContains(CardSubType($attackID), "Ally"))
    {
      $allies = &GetAllies($mainPlayer);
      if(isset($allies[$combatChainState[$CCS_WeaponIndex] + 4])){
        if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "UPR043")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "DYN002") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Themai")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "DYN003") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Vynserakai")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "DYN004") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Nekria")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "EVO246") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Cromai")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "MST235") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Miragai")) return true;  
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "ROS252") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Ouvia")) return true;
      }
    }
    return HasPhantasm($attackID);
  }

  function ProcessPhantasmOnBlock($index)
  {
    global $mainPlayer;
    if(IsPhantasmActive() && DoesBlockTriggerPhantasm($index) && SearchLayersForCardID("PHANTASM") == -1) AddLayer("LAYER", $mainPlayer, "PHANTASM");
  }

  function DoesBlockTriggerPhantasm($index)
  {
    global $CombatChain, $mainPlayer, $defPlayer, $attackModifiers;
    $attackModifiers = [];
    $card = $CombatChain->Card($index);
    $defendingCardType = CardType($card->ID());
    if($defendingCardType != "AA") return false;
    if(ClassContains($card->ID(), "ILLUSIONIST", $defPlayer)) return false;
    if(PowerCantBeModified($card->ID())) return AttackValue($card->ID()) >= 6;
    $attackValue = ModifiedAttackValue($card->ID(), $defPlayer, "CC", source:$card->ID());
    $attackValue += AuraAttackModifiers($index, $attackModifiers);
    $attackValue += $card->AttackValue();//Combat chain attack modifier
    return $attackValue >= 6;
  }

  function IsPhantasmStillActive()
  {
    global $combatChain, $CombatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex;
    if(count($combatChain) == 0) return false;
    $blockGreaterThan6 = false;
    for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces())
    {
      if(DoesBlockTriggerPhantasm($i)) $blockGreaterThan6 = true;
    }
    if(!$blockGreaterThan6) return false;
    if((SearchCurrentTurnEffects("MON090", $mainPlayer) && CardType($CombatChain->AttackCard()->ID()) == "AA") || SearchCurrentTurnEffects("EVR142", $mainPlayer) || SearchCurrentTurnEffects("UPR154", $mainPlayer) || SearchCurrentTurnEffects("UPR412", $mainPlayer)) { return false; }
    return true;
  }

  function PhantasmLayer()
  {
    global $CombatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $CS_NumPhantasmAADestroyed, $defPlayer, $turn, $layers;
    if(IsPhantasmStillActive())
    {
      $attackID = $CombatChain->AttackCard()->ID();
      if($combatChainState[$CCS_WeaponIndex] != "-1" && DelimStringContains(CardSubType($attackID), "Ally")) DestroyAlly($mainPlayer, $combatChainState[$CCS_WeaponIndex]);
      if(ClassContains($attackID, "ILLUSIONIST", $mainPlayer)) {
        GhostlyTouchPhantasmDestroy();
        if(!SubtypeContains($attackID, "Aura", $mainPlayer)) PhantomTidemawDestroy($mainPlayer);//Aura destroy is handled elsewhere
      }
      if($attackID != "EVO244") AttackDestroyed($attackID); //Need to skip PhantomTidemaw Phatasm once or it triggers twice. Here and in DestroyAura
      if(CardType($attackID) == "AA") {
        IncrementClassState($mainPlayer, $CS_NumPhantasmAADestroyed);
      } else if(SubtypeContains($attackID, "Aura", $mainPlayer)) {
        DestroyAura($mainPlayer, $combatChainState[$CCS_WeaponIndex]);
      }
      CloseCombatChain();
      ProcessDecisionQueue();
    }
    else {
      $turn[0] = "A";
      $currentPlayer = $mainPlayer;
      for($i=count($layers)-LayerPieces(); $i >= 0; $i-=LayerPieces())
      {
        if($layers[$i] == "DEFENDSTEP" || ($layers[$i] == "LAYER" && $layers[$i+2] == "PHANTASM"))
        {
          for($j=$i; $j<($i+LayerPieces()); ++$j) unset($layers[$j]);
        }
      }
      $layers = array_values($layers);
    }
  }

  function HasSpectra($cardID)
  {
    switch($cardID)
    {
      case "MON005": case "MON006": case "MON011": case "MON012": case "MON013": return true;
      case "EVR140": case "EVR141": case "EVR142": case "EVR143": return true;
      default: return false;
    }
  }

  function TheLibrarianEffect($player, $index)
  {
    $arsenal = &GetArsenal($player);
    --$arsenal[$index+2];
    ++$arsenal[$index+3];
    Draw($player);
    if($arsenal[$index+3] == 3) MentorTrigger($player, $index);
  }

?>
