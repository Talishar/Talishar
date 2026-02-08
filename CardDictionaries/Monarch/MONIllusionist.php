<?php

  function MONIllusionistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $defPlayer;
    switch($cardID)
    {
      case "prism_sculptor_of_arc_light": case "prism":
        PlayAura("spectral_shield", $currentPlayer);
        return "";
      case "herald_of_triumph_red": case "herald_of_triumph_yellow": case "herald_of_triumph_blue":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "dream_weavers":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "phantasmaclasm_red":
        AddLayer("TRIGGER", $currentPlayer, $cardID, "-", "ATTACKTRIGGER");
        return "";
      case "prismatic_shield_red": PlayAura("spectral_shield", $currentPlayer, 3); return "";
      case "prismatic_shield_yellow": PlayAura("spectral_shield", $currentPlayer, 2); return "";
      case "prismatic_shield_blue": PlayAura("spectral_shield", $currentPlayer, 1); return "";
      case "phantasmify_red": case "phantasmify_yellow": case "phantasmify_blue":
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
      case "herald_of_erudition_yellow":
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        Draw($mainPlayer, num:2);
        break;
      case "herald_of_judgment_yellow":
        if(IsHeroAttackTarget()) {
          AddCurrentTurnEffect($cardID, $defPlayer);
          AddNextTurnEffect($cardID, $defPlayer);
        }
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "herald_of_triumph_red": case "herald_of_triumph_yellow": case "herald_of_triumph_blue":
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "herald_of_protection_red": case "herald_of_protection_yellow": case "herald_of_protection_blue":
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        PlayAura("spectral_shield", $mainPlayer);
        break;
      case "herald_of_ravages_red": case "herald_of_ravages_yellow": case "herald_of_ravages_blue":
        DealArcane(1, 0, "PLAYCARD", $cardID, false, $mainPlayer);
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "herald_of_rebirth_red": case "herald_of_rebirth_yellow": case "herald_of_rebirth_blue":
        AddDecisionQueue("FINDINDICES", $mainPlayer, $cardID);
        AddDecisionQueue("MAYCHOOSEDISCARD", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $mainPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
        AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $mainPlayer, "<0> was selected.", 1);
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "herald_of_tenacity_red": case "herald_of_tenacity_yellow": case "herald_of_tenacity_blue": 
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      case "wartune_herald_red": case "wartune_herald_yellow": case "wartune_herald_blue": 
        if (DoesAttackHaveGoAgain()) GiveAttackGoAgain();
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-"; 
        AddSoul($cardID, $mainPlayer, "CC");
        break;
      default: break;
    }
  }

  function HasPhantasm($cardID, $from="CC")
  {
    switch($cardID)
    {
      case "fractal_replication_red": return FractalReplicationStats("HasPhantasm", $from);
      default: return GeneratedHasPhantasm($cardID);
    }
  }

  function IsPhantasmActive()
  {
    global $CombatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $CS_NumIllusionistAttacks;
    if(!$CombatChain->HasCurrentLink()) return false;
    $attackID = $CombatChain->AttackCard()->ID();
    if (SearchAurasForCard("passing_mirage_blue", $mainPlayer) != "" && ClassContains($attackID, "ILLUSIONIST", $mainPlayer) && GetClassState($mainPlayer, $CS_NumIllusionistAttacks) == 1) return false;
    if (SearchCurrentTurnEffects("dream_weavers", $mainPlayer) && CardType($attackID) == "AA" && ClassContains($attackID, "ILLUSIONIST", $mainPlayer)) return false;
    if (SearchCurrentTurnEffects("semblance_blue", $mainPlayer)) return false;
    if (SearchCurrentTurnEffects("miragai", $mainPlayer)) return false;
    
    if(SearchCurrentTurnEffectsForCycle("veiled_intentions_red", "veiled_intentions_yellow", "veiled_intentions_blue", $mainPlayer)) return true;
    if(SearchCurrentTurnEffectsForCycle("phantasmify_red", "phantasmify_yellow", "phantasmify_blue", $mainPlayer)) return true;
    if(SearchCurrentTurnEffectsForCycle("transmogrify_red", "transmogrify_yellow", "transmogrify_blue", $mainPlayer)) return true;
    if($combatChainState[$CCS_WeaponIndex] != "-1" && DelimStringContains(CardSubType($attackID), "Ally"))
    {
      $allies = &GetAllies($mainPlayer);
      if(isset($allies[$combatChainState[$CCS_WeaponIndex] + 4])){
        if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "ash")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "dust_from_the_golden_plains_red") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Themai")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "dust_from_the_red_desert_red") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Vynserakai")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "dust_from_the_shadow_crypts_red") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Nekria")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "dust_from_the_chrome_caverns_red") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Cromai")) return true;
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "dust_from_stillwater_shrine_red") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Miragai")) return true;  
        else if(DelimStringContains($allies[$combatChainState[$CCS_WeaponIndex] + 4], "dust_from_the_fertile_fields_red") && !CardNameContains($allies[$combatChainState[$CCS_WeaponIndex]], "Ouvia")) return true;
      }
    }
    return HasPhantasm($attackID);
  }

  function ProcessPhantasmOnBlock($index)
  {
    global $mainPlayer;
    if(IsPhantasmActive() && DoesBlockTriggerPhantasm($index) && SearchLayersForCardID("PHANTASM") == -1) AddLayer("TRIGGER", $mainPlayer, "PHANTASM");
  }

  function DoesBlockTriggerPhantasm($index)
  {
    global $CombatChain, $defPlayer, $powerModifiers;
    $powerModifiers = [];
    $card = $CombatChain->Card($index);
    $defendingCardType = CardType($card->ID());
    if($defendingCardType != "AA") return false;
    if(ClassContains($card->ID(), "ILLUSIONIST", $defPlayer)) return false;
    return $card->TotalPower() >= 6;
  }

  function IsPhantasmStillActive($source)
  {
    global $combatChain, $CombatChain, $mainPlayer;
    if(count($combatChain) == 0) return false;
    if($source == "burn_bare") return true;
    $blockGreaterThan6 = false;
    for($i=CombatChainPieces(); $i<count($combatChain); $i+=CombatChainPieces())
    {
      if(DoesBlockTriggerPhantasm($i)) $blockGreaterThan6 = true;
    }
    if(!$blockGreaterThan6) return false;
    if((SearchCurrentTurnEffects("dream_weavers", $mainPlayer) && CardType($CombatChain->AttackCard()->ID()) == "AA") || SearchCurrentTurnEffects("passing_mirage_blue", $mainPlayer) || SearchCurrentTurnEffects("semblance_blue", $mainPlayer) || SearchCurrentTurnEffects("miragai", $mainPlayer)) { return false; }
    return true;
  }

  function PhantasmLayer($source)
  {
    global $CombatChain, $mainPlayer, $combatChainState, $CCS_WeaponIndex, $CS_NumPhantasmAADestroyed, $turn, $layers, $combatChain;
    if(IsPhantasmStillActive($source))
    {
      $attackID = $CombatChain->AttackCard()->ID();
      if(DelimStringContains(CardSubType($attackID), "Ally")) {
        $originUID = $CombatChain->AttackCard()->OriginUniqueID();
        $allyInd = SearchAlliesForUniqueID($originUID, $mainPlayer);
        if ($allyInd != -1) DestroyAlly($mainPlayer, $allyInd);
      }
      if(ClassContains($attackID, "ILLUSIONIST", $mainPlayer)) {
        GhostlyTouchPhantasmDestroy();
        if(!SubtypeContains($attackID, "Aura", $mainPlayer)) PhantomTidemawDestroy($mainPlayer);//Aura destroy is handled elsewhere
      }
      if($attackID != "phantom_tidemaw_blue") AttackDestroyed($attackID); //Need to skip PhantomTidemaw Phatasm once or it triggers twice. Here and in DestroyAura
      if(CardType($attackID) == "AA") {
        IncrementClassState($mainPlayer, $CS_NumPhantasmAADestroyed);
      } else if(SubtypeContains($attackID, "Aura", $mainPlayer)) {
        DestroyAura($mainPlayer, $combatChainState[$CCS_WeaponIndex]);
      }
      $combatChain[10] = "PHANTASM"; //indicates that the attack has been destroyed
      CloseCombatChain();
    }
    else if ($combatChain[10] != "PHANTASM") {
      $turn[0] = "A";
      for($i=count($layers)-LayerPieces(); $i >= 0; $i-=LayerPieces())
      {
        if($layers[$i] == "DEFENDSTEP" || ($layers[$i] == "TRIGGER" && $layers[$i+2] == "PHANTASM"))
        {
          for($j=$i; $j<$i+LayerPieces(); ++$j) unset($layers[$j]);
        }
      }
      $layers = array_values($layers);
    }
  }

  function HasSpectra($cardID)
  {
    return GeneratedHasSpectra($cardID);
  }

  function TheLibrarianEffect($player, $index)
  {
    $arsenal = &GetArsenal($player);
    --$arsenal[$index+2];
    ++$arsenal[$index+3];
    Draw($player);
    if($arsenal[$index+3] == 3) MentorTrigger($player, $index);
  }

  function MercifulTriggers($player, $cardID) {
    global $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    if (SubtypeContains($cardID, "Aura") || TypeContains($cardID, "AA")) {
      $numMercifulRetribution = SearchCount(SearchAurasForCard("merciful_retribution_yellow", $player));
      if ($numMercifulRetribution > 0 && TalentContains($cardID, "LIGHT", $player)) {
        AddGraveyard($cardID, $player, "COMBATCHAIN");
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "-";
        $grave = GetDiscard($player);
        $uid = $grave[count($grave) - DiscardPieces() + 1];
      }

      for ($i = 0; $i < $numMercifulRetribution; ++$i) {
        AddLayer("TRIGGER", $player, "merciful_retribution_yellow", additionalCosts: $uid);
      }
    }
  }