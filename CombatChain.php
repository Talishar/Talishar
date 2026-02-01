<?php

function ProcessHitEffect($cardID, $from = "-", $uniqueID = -1, $target="-")
{
  global $CombatChain, $layers, $mainPlayer, $chainLinks;
  WriteLog("Processing hit effect for " . CardLink($cardID, $cardID));
  if (!DelimStringContains(CardType($cardID), "W")) {//stops flicks from interacting with tarpit trap
    if (CardType($CombatChain->AttackCard()->ID()) == "AA" && SearchCurrentTurnEffects("tarpit_trap_yellow", $mainPlayer, count($layers) < LayerPieces())) {
      WriteLog("Hit effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
      return true;
    }
  }
  //check tarpit trap against flicked kiss of death if the current attack is a dagger
  if (CardType($cardID) == "AA" && SubtypeContains($cardID, "Dagger", $mainPlayer) && SearchCurrentTurnEffects("tarpit_trap_yellow", $mainPlayer, count($layers) < LayerPieces())) {
    WriteLog("Hit effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
    return true;
  }
  $cardID = ShiyanaCharacter($cardID);
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") return $card->HitEffect($cardID, $from, $uniqueID, $target);
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  if ($set == "WTR") return WTRHitEffect($cardID);
  else if ($set == "ARC") {
    switch ($class) {
      case "MECHANOLOGIST":
        return ARCMechanologistHitEffect($cardID, $from);
      case "RANGER":
        return ARCRangerHitEffect($cardID, $from);
      case "RUNEBLADE":
        return ARCRunebladeHitEffect($cardID);
      case "WIZARD":
        return ARCWizardHitEffect($cardID);
      case "GENERIC":
        return ARCGenericHitEffect($cardID);
    }
  } else if ($set == "CRU") return CRUHitEffect($cardID);
  else if ($set == "MON") {
    switch ($class) {
      case "BRUTE":
        return MONBruteHitEffect($cardID);
      case "ILLUSIONIST":
        return MONIllusionistHitEffect($cardID);
      case "RUNEBLADE":
        return MONRunebladeHitEffect($cardID);
      case "WARRIOR":
        return MONWarriorHitEffect($cardID);
      case "GENERIC":
        return MONGenericHitEffect($cardID);
      case "NONE":
        return MONTalentHitEffect($cardID);
      default:
        return "";
    }
  } else if ($set == "ELE") {
    switch ($class) {
      case "GUARDIAN":
        return ELEGuardianHitEffect($cardID);
      case "RANGER":
        return ELERangerHitEffect($cardID);
      case "RUNEBLADE":
        return ELERunebladeHitEffect($cardID);
      default:
        return ELETalentHitEffect($cardID);
    }
  } else if ($set == "EVR") return EVRHitEffect($cardID, $target);
  else if ($set == "UPR") return UPRHitEffect($cardID);
  else if ($set == "DYN") return DYNHitEffect($cardID, $from, $CombatChain->AttackCard()->ID());
  else if ($set == "OUT") return OUTHitEffect($cardID, $from);
  else if ($set == "DTD") return DTDHitEffect($cardID);
  else if ($set == "TCC") return TCCHitEffect($cardID);
  else if ($set == "EVO") return EVOHitEffect($cardID);
  else if ($set == "HVY") return HVYHitEffect($cardID);
  else if ($set == "AKO") return AKOHitEffect($cardID);
  else if ($set == "MST") return MSTHitEffect($cardID, $from);
  else if ($set == "AAZ") return AAZHitEffect($cardID);
  else if ($set == "AUR") return AURHitEffect($cardID);
  else if ($set == "ROS") return ROSHitEffect($cardID);
  else if ($set == "AJV") return AJVHitEffect($cardID);
  else if ($set == "HNT") return HNTHitEffect($cardID, $uniqueID, target:$target);
  else if ($set == "SEA") return SEAHitEffect($cardID);
  else if ($set == "ASR") return ASRHitEffect($cardID);
  else if ($set == "SUP") return SUPHitEffect($cardID);
  else if ($set == "APS") return APSHitEffect($cardID);
  else if ($set == "ARR") return ARRHitEffect($cardID);
  else if ($set == "AAC") return AACHitEffect($cardID, $target);
  else if ($set == "PEN") return PENHitEffect($cardID);
  else return -1;
}

function PowerModifier($cardID, $from = "", $resourcesPaid = 0, $repriseActive = -1)
{
  global $mainPlayer, $defPlayer, $CS_Num6PowDisc, $CombatChain, $combatChainState, $mainAuras, $CS_CardsBanished;
  global $CS_NumCharged, $CCS_NumBoosted, $defPlayer, $CS_ArcaneDamageTaken, $CS_NumYellowPutSoul, $CS_NumCardsDrawn;
  global $CS_NumPlayedFromBanish, $CS_NumAuras, $CS_AttacksWithWeapon, $CS_Num6PowBan, $CS_HaveIntimidated, $chainLinkSummary;
  global $combatChain, $CS_Transcended, $CS_NumBluePlayed, $CS_NumLightningPlayed, $CS_DamageDealt, $CS_NumCranked, $CS_ArcaneDamageDealt;
  global $chainLinks, $chainLinkSummary, $CCS_FlickedDamage, $CS_CheeredThisTurn, $CS_BooedThisTurn, $CS_NumItemsDestroyed;
  $attackID = $CombatChain->AttackCard()->ID();
  if ($repriseActive == -1) $repriseActive = RepriseActive();
  $extraText = $cardID == $attackID ? GetHorrorsBuff() : "-";
  if ($extraText != "-") {
    $textBoxes = [$cardID, $extraText];
  }
  else $textBoxes = [$cardID];
  $power = 0;
  foreach($textBoxes as $cardID) {
    if (HasPiercing($cardID, $from)) $power += NumEquipBlock() > 0 ? 1 : 0;
    if (HasHighTide($cardID) && HighTideConditionMet($mainPlayer)) {
      switch ($cardID) {
        case "conqueror_of_the_high_seas_red":
        case "hms_kraken_yellow":
        case "hms_marlin_yellow":
        case "hms_barracuda_yellow":
          $power += 1;
          break;
        case "battalion_barque_red":
        case "battalion_barque_yellow":
        case "battalion_barque_blue":
          $power += 2;
          break;
        default:
          $power += 0;
          break;
      }
    }
    if (class_exists($cardID)) {
      $card = new $cardID($mainPlayer);
      $power += $card->PowerModifier($from, $resourcesPaid, $repriseActive, $attackID);
    }
    switch ($cardID) {
      case "romping_club":
        $power += (GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 1 : 0);
        break;
      case "breaking_scales":
        $power += 1;
        break;
      case "lord_of_wind_blue":
        $power += (ComboActive() ? $resourcesPaid : 0);
        break;
      case "ancestral_empowerment_red":
        $power += 1;
        break;
      case "mugenshi_release_yellow":
        $power += (ComboActive() ? 1 : 0);
        break;
      case "hurricane_technique_yellow":
        $power += (ComboActive() ? 1 : 0);
        break;
      case "fluster_fist_red":
      case "fluster_fist_yellow":
      case "fluster_fist_blue":
        $power += (ComboActive() ? NumAttacksHit() : 0);
        break;
      case "blackout_kick_red":
      case "blackout_kick_yellow":
      case "blackout_kick_blue":
        $power += (ComboActive() ? 3 : 0);
        break;
      case "open_the_center_red":
      case "open_the_center_yellow":
      case "open_the_center_blue":
        $power += (ComboActive() ? 1 : 0);
        break;
      case "rising_knee_thrust_red":
      case "rising_knee_thrust_yellow":
      case "rising_knee_thrust_blue":
        $power += (ComboActive() ? 2 : 0);
        break;
      case "whelming_gustwave_red":
      case "whelming_gustwave_yellow":
      case "whelming_gustwave_blue":
        $power += (ComboActive() ? 1 : 0);
        break;
      case "rout_red":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 3 : 0;
        break;
      case "singing_steelblade_yellow":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 1 : 0;
        break;
      case "overpower_red":
        if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) $power += 0;
        $power += $repriseActive ? 6 : 4;
        break;
      case "overpower_yellow":
        if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) $power += 0;
        $power += $repriseActive ? 5 : 3;
        break;
      case "overpower_blue":
        if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) $power += 0;
        $power += $repriseActive ? 4 : 2;
        break;
      case "ironsong_response_red":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) && $repriseActive ? 3 : 0;
        break;
      case "ironsong_response_yellow":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) && $repriseActive ? 2 : 0;
        break;
      case "ironsong_response_blue":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) && $repriseActive ? 1 : 0;
        break;
      case "biting_blade_red":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 3 : 0;
        break;
      case "biting_blade_yellow":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 2 : 0;
        break;
      case "biting_blade_blue":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 1 : 0;
        break;
      case "stroke_of_foresight_red":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 3 : 0;
        break;
      case "stroke_of_foresight_yellow":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 2 : 0;
        break;
      case "stroke_of_foresight_blue":
        $power += TypeContains($CombatChain->AttackCard()->ID(), "W") ? 1 : 0;
        break;
      case "barraging_brawnhide_red":
      case "barraging_brawnhide_yellow":
      case "barraging_brawnhide_blue":
        $power += NumCardsNonEquipBlocking() < 2 ? 1 : 0;
        break;
      case "push_the_point_red":
      case "push_the_point_yellow":
      case "push_the_point_blue":
        $idx = count($chainLinkSummary) - ChainLinkSummaryPieces();
        if (isset($chainLinkSummary[$idx]) && $chainLinkSummary[$idx] > 0) $power += 2;
        break;
      case "riled_up_red":
      case "riled_up_yellow":
      case "riled_up_blue":
        $power += GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 1 : 0;
        break;
      case "herons_flight_red":
        $power += ComboActive() ? 2 : 0;
        break;
      case "crane_dance_red":
      case "crane_dance_yellow":
      case "crane_dance_blue":
        $power += ComboActive() ? 1 : 0;
        break;
      case "rushing_river_red":
      case "rushing_river_yellow":
      case "rushing_river_blue":
        $power += ComboActive() ? 1 : 0;
        break;
      case "flying_kick_red":
      case "flying_kick_yellow":
      case "flying_kick_blue":
        $power += NumChainLinks() >= 3 ? 2 : 0;
        break;
      case "salt_the_wound_yellow":
        $power += NumAttacksHit();
        break;
      case "unified_decree_yellow":
        $power += 3;
        break;
      case "plasma_barrel_shot":
        $power += 1 + $combatChainState[$CCS_NumBoosted];
        break;
      case "overblast_red":
      case "overblast_yellow":
      case "overblast_blue":
        $power += $combatChainState[$CCS_NumBoosted];
        break;
      case "raydn_duskbane":
        $power += GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
        break;
      case "valiant_thrust_red":
      case "valiant_thrust_yellow":
      case "valiant_thrust_blue":
        $power += GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
        break;
      case "courageous_steelhand_red":
        $power += GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 3 : 0;
        break;
      case "courageous_steelhand_yellow":
        $power += GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 2 : 0;
        break;
      case "courageous_steelhand_blue":
        $power += GetClassState($mainPlayer, $CS_NumCharged) > 0 ? 1 : 0;
        break;
      case "galaxxi_black":
        $power += GetClassState($mainPlayer, $CS_NumPlayedFromBanish) > 0 ? 2 : 0;
        break;
      case "piercing_shadow_vise_red":
      case "piercing_shadow_vise_yellow":
      case "piercing_shadow_vise_blue":
        $power += GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0 ? 2 : 0;
        break;
      case "tremor_of_iarathael_red":
      case "tremor_of_iarathael_yellow":
      case "tremor_of_iarathael_blue":
        $power += GetClassState($mainPlayer, $CS_CardsBanished) > 0 ? 2 : 0;
        break;
      case "stony_woottonhog_red":
      case "stony_woottonhog_yellow":
      case "stony_woottonhog_blue":
        $power += NumCardsNonEquipBlocking() < 2 ? 1 : 0;
        break;
      case "surging_militia_red":
      case "surging_militia_yellow":
      case "surging_militia_blue":
        $power += NumCardsNonEquipBlocking();
        break;
      case "yinti_yanti_red":
      case "yinti_yanti_yellow":
      case "yinti_yanti_blue":
        $power += count($mainAuras) >= 1 ? 1 : 0;
        break;
      case "stir_the_wildwood_red":
      case "stir_the_wildwood_yellow":
      case "stir_the_wildwood_blue":
        $power += GetClassState($defPlayer, $CS_ArcaneDamageTaken) >= 1 ? 2 : 0;
        break;
      case "burgeoning_red":
      case "burgeoning_yellow":
      case "burgeoning_blue":
        $power += $from == "ARS" ? 1 : 0;
        break;
      case "break_tide_yellow":
        $power += (ComboActive() ? 3 : 0);
        break;
      case "winds_of_eternity_blue":
        $power += (ComboActive() ? 2 : 0);
        break;
      case "hundred_winds_red":
      case "hundred_winds_yellow":
      case "hundred_winds_blue":
        $power += (ComboActive() ? NumChainLinksWithName("Hundred Winds") - 1 : 0);
        break;
      case "in_the_swing_red":
        $power += 3;
        break;
      case "in_the_swing_yellow":
        $power += 2;
        break;
      case "in_the_swing_blue":
        $power += 1;
        break;
      case "swarming_gloomveil_red":
        $power += (GetClassState($mainPlayer, $CS_NumAuras) >= 2 ? 1 : 0);
        break;
      case "shrill_of_skullform_red":
      case "shrill_of_skullform_yellow":
      case "shrill_of_skullform_blue":
        $power += (GetClassState($mainPlayer, $CS_NumAuras) > 0 ? 3 : 0);
        break;
      case "dawnblade_resplendent":
        $power += GetClassState($mainPlayer, $CS_AttacksWithWeapon) >= 1 ? 1 : 0;
        break;
      case "phoenix_form_red":
        $power += (NumChainLinksWithName("Phoenix Flame") >= 2 ? 2 : 0);
        break;
      case "combustion_point_red":
        $power += 1;
        break;
      case "lava_burst_red":
        $power += (RuptureActive() ? 3 : 0);
        break;
      case "phoenix_flame_red":
        $power += (NumDraconicChainLinks() >= 2 ? 1 : 0);
        break;
      case "rapid_reflex_red":
        $power += 3;
        break;
      case "rapid_reflex_yellow":
        $power += 2;
        break;
      case "rapid_reflex_blue":
        $power += 1;
        break;
      case "tiger_swipe_red":
        $power += (ComboActive() ? 2 : 0);
        break;
      case "pouncing_qi_red":
      case "pouncing_qi_yellow":
      case "pouncing_qi_blue":
        $power += (ComboActive() ? 1 : 0);
        break;
      case "qi_unleashed_red":
      case "qi_unleashed_yellow":
      case "qi_unleashed_blue":
        $power += (ComboActive() ? 4 : 0);
        break;
      case "sneak_attack_red":
      case "sneak_attack_yellow":
      case "sneak_attack_blue":
        $power += (NumAttackReactionsPlayed() > 0 ? 4 : 0);
        break;
      case "dishonor_blue":
        $power += (ComboActive() ? 2 : 0);
        break;
      case "silverwind_shuriken_blue":
        $power += 1;
        break;
      case "spinning_wheel_kick_red":
      case "spinning_wheel_kick_yellow":
      case "spinning_wheel_kick_blue":
        $power += (ComboActive() ? 1 : 0);
        break;
      case "descendent_gustwave_red":
      case "descendent_gustwave_yellow":
      case "descendent_gustwave_blue":
        $power += (ComboActive() ? 2 : 0);
        break;
      case "widowmaker_red":
      case "widowmaker_yellow":
      case "widowmaker_blue":
        $power += NumCardsDefended() < 2 ? 3 : 0;
        break;
      case "fisticuffs":
        $power += 1;
        break;
      case "feisty_locals_red":
      case "feisty_locals_yellow":
      case "feisty_locals_blue":
        $power += (CachedNumActionBlocked() > 0 ? 2 : 0);
        break;
      case "freewheeling_renegades_red":
      case "freewheeling_renegades_yellow":
      case "freewheeling_renegades_blue":
        $power += (CachedNumActionBlocked() > 0 ? -2 : 0);
        break;
      case "beaming_blade":
        $power += GetClassState($mainPlayer, $CS_NumYellowPutSoul) > 0 ? 5 : 0;
        break;
      case "searing_ray_red":
      case "searing_ray_yellow":
      case "searing_ray_blue":
        $power += (SearchPitchForColor($mainPlayer, 2) > 0 ? 2 : 0);
        break;
      case "battlefield_breaker_red":
      case "battlefield_breaker_yellow":
      case "battlefield_breaker_blue":
        $power += GetClassState($mainPlayer, $CS_Num6PowBan) > 0 ? 1 : 0;
        break;
      case "soul_butcher_red":
      case "soul_butcher_yellow":
      case "soul_butcher_blue":
        $theirSoul = &GetSoul($defPlayer);
        $power += (count($theirSoul) > 0 && IsHeroAttackTarget() ? 2 : 0);
        break;
      case "teklo_leveler":
        $power += EvoUpgradeAmount($mainPlayer) >= 4;
        break;
      case "annihilator_engine_red":
      case "terminator_tank_red":
      case "war_machine_red":
        $power += EvoUpgradeAmount($mainPlayer) >= 4 ? 3 : 0;
        break;
      case "steel_street_hoons_blue":
        $power += GetClassState($mainPlayer, piece: $CS_NumItemsDestroyed) > 0 ? 2 : 0;
        break;
      case "mechanical_strength_red":
      case "mechanical_strength_yellow":
      case "mechanical_strength_blue":
        $power += EvoUpgradeAmount($mainPlayer);
        break;
      case "fender_bender_red":
      case "fender_bender_yellow":
      case "fender_bender_blue":
      case "panel_beater_red":
      case "panel_beater_yellow":
      case "panel_beater_blue":
        $power += NumEquipBlock();
        break;
      case "show_no_mercy_red":
        $hand = &GetHand($defPlayer);
        $power += $combatChain[0] == "show_no_mercy_red" && count($hand) == 0 && IsHeroAttackTarget() ? 3 : 0;
        break;
      case "beast_mode_red":
      case "beast_mode_yellow":
      case "beast_mode_blue":
        $power += GetClassState($mainPlayer, $CS_HaveIntimidated) > 0 ? 2 : 0;
        break;
      case "take_the_upper_hand_red":
        $power += 3;
        break;
      case "take_the_upper_hand_yellow":
        $power += 2;
        break;
      case "take_the_upper_hand_blue":
        $power += 1;
        break;
      case "rising_power_red":
      case "rising_power_yellow":
      case "rising_power_blue":
        $power += GetClassState($mainPlayer, $CS_NumCardsDrawn) >= 1 ? 1 : 0;
        break;
      case "second_tenet_of_chi_tide_blue":
        $power += GetClassState($mainPlayer, $CS_Transcended) > 0 ? 2 : 0;
        break;
      case "droplet_blue":
      case "rising_tide_blue":
      case "spillover_blue":
      case "tidal_surge_blue":
        $power += GetClassState($mainPlayer, $CS_NumBluePlayed) > 1 ? 2 : 0;
        break;
      case "bonds_of_agony_blue":
        $power += NumAttackReactionsPlayed() > 2 ? 3 : 0;
        break;
      case "double_trouble_red":
      case "double_trouble_yellow":
      case "double_trouble_blue":
        $power += NumAttackReactionsPlayed() > 1 ? 2 : 0;
        break;
      case "pick_to_pieces_red":
      case "pick_to_pieces_yellow":
      case "pick_to_pieces_blue":
        $power += NumAttackReactionsPlayed() > 0 ? 1 : 0;
        break;
      case "rowdy_locals_blue":
        $power += CachedNumActionBlocked() > 0 ? 2 : 0;
        break;
      case "crackling_red":
      case "crackling_yellow":
      case "star_fall":
        $power += GetClassState($mainPlayer, $CS_NumLightningPlayed) > 0;
        break;
      case "felling_of_the_crown_red":
        $power += SearchCount(SearchMultiZone($mainPlayer, "MYBANISH:talent=EARTH")) >= 4 ? 4 : 0;
        break;
      case "plow_under_yellow":
        $power += SearchCount(SearchMultiZone($mainPlayer, "MYBANISH:talent=EARTH")) >= 4 ? 4 : 0;
        break;
      case "strength_of_four_seasons_red":
      case "strength_of_four_seasons_yellow":
      case "strength_of_four_seasons_blue":
        $power += SearchCount(SearchMultiZone($mainPlayer, "MYBANISH:talent=EARTH")) >= 4 ? 4 : 0;
        break;
      case "arcanic_spike_red":
      case "arcanic_spike_yellow":
      case "arcanic_spike_blue":
        $power += GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0 ? 2 : 0;
        break;
      case "hit_the_high_notes_red":
      case "hit_the_high_notes_yellow":
      case "hit_the_high_notes_blue":
        $power += (GetClassState($mainPlayer, $CS_NumAuras) > 0 ? 2 : 0);
        break;
      case "fast_and_furious_red":
        $power += (GetClassState($mainPlayer, $CS_NumCranked)) > 0 ? 1 : 0;
        break;
      case "plunge_the_prospect_red":
      case "plunge_the_prospect_yellow":
      case "plunge_the_prospect_blue":
        $power += (IsHeroAttackTarget() && CheckMarked($defPlayer)) ? 1 : 0;
        break;
      case "grow_claws_red":
      case "grow_claws_yellow":
      case "grow_claws_blue":
        $power += isPreviousLinkDraconic() ? 1 : 0;
        break;
      case "hunts_end_red":
        $power += 4;
        break;
      case "jagged_edge_red":
        $power += 3;
        break;
      case "diced_red":
      case "diced_yellow":
      case "diced_blue":
        $power += 1;
        break;
      case "hand_of_vengeance":
        $power += 1;
        break;
      case "hunt_to_the_ends_of_rathe_red":
        $power += (IsHeroAttackTarget() && CheckMarked($defPlayer)) ? 2 : 0;
        break;
      case "cut_through_red":
      case "cut_through_yellow":
      case "cut_through_blue":
        $numDaggerHits = 0;
          for($i=0; $i<count($chainLinks); ++$i)
          {
            if(SubtypeContains($chainLinks[$i][0], "Dagger") && $chainLinkSummary[$i*ChainLinkSummaryPieces()] > 0) ++$numDaggerHits;
          }
          $numDaggerHits += $combatChainState[$CCS_FlickedDamage];
        $power += $numDaggerHits > 0 ? 1 : 0;
        break;
      case "incision_red": 
        $power += SubtypeContains($attackID, "Dagger") ? 3 : 0;
        break;
      case "incision_yellow":
        $power += SubtypeContains($attackID, "Dagger") ? 2 : 0;
        break;
      case "incision_blue":
        $power += SubtypeContains($attackID, "Dagger") ? 1 : 0;
        break;
      case "outed_red":
        $power += CheckMarked($defPlayer) ? 1 : 0;
        break;
      case "retrace_the_past_blue":
        $power += (SearchCurrentTurnEffectsForIndex("retrace_the_past_blue", $mainPlayer) != -1 ? 2 : 0);
        break;
      case "skyzyk_red":
        $power += DoesAttackHaveGoAgain() ? 1 : 0;
        break;
      case "gold_hunter_longboat_yellow":
        $myNumGold = CountItem("gold", $mainPlayer);
        $theirNumGold = CountItem("gold", $defPlayer);
        $power += $myNumGold < $theirNumGold ? 2 : 0;
        break;
      case "mocking_blow_red":
        $power += GetClassState($mainPlayer, $CS_BooedThisTurn) ? 4 : 0;
        break;
      case "mocking_blow_yellow":
        $power += GetClassState($mainPlayer, $CS_BooedThisTurn) ? 3 : 0;
        break;
      case "mocking_blow_blue":
        $power += GetClassState($mainPlayer, $CS_BooedThisTurn) ? 2 : 0;
        break;
      case "old_leather_and_vim_red":
        if (SearchAurasForCard("toughness", $mainPlayer, false) != "") $power += 1;
        elseif (SearchAurasForCard("vigor", $mainPlayer, false) != "") $power += 1;
        else $power += 0;
        break;
      case "uplifting_performance_blue":
        if (SearchAurasForCard("toughness", $mainPlayer, false) != "") $power += 1;
        elseif (SearchAurasForCard("confidence", $mainPlayer, false) != "") $power += 1;
        else $power += 0;
        break;
      case "offensive_behavior_blue":
        if (SearchAurasForCard("might", $mainPlayer, false) != "") $power += 1;
        elseif (SearchAurasForCard("vigor", $mainPlayer, false) != "") $power += 1;
        else $power += 0;
        break;
      case "spew_obscenities_yellow":
        if (SearchAurasForCard("might", $mainPlayer, false) != "") $power += 1;
        elseif (SearchAurasForCard("confidence", $mainPlayer, false) != "") $power += 1;
        else $power += 0;
        break;
      case "headbutt_blue":
        $mainEmptySlots = ListExposedEquipSlots($mainPlayer);
        $defEmptySlots = ListExposedEquipSlots($defPlayer);
        $power += !str_contains($mainEmptySlots, "Head") && str_contains($defEmptySlots, "Head") ? 1 : 0;
        break;
      case "fault_line_red":
        $arsenal = GetArsenal($mainPlayer);
        $power += count($arsenal) > 0 ? 1 : 0;
        break;
      case "renounce_grandeur_red":
        $auras = GetAuras($defPlayer);
        $foundAura = false;
        for ($i = 0; $i < count($auras); $i += AuraPieces()) {
          if (TypeContains($auras[$i], "T", $defPlayer) && !$foundAura) {
            $foundAura = true;
            $power += 1;
          }
        }
        $char = GetPlayerCharacter($defPlayer);
        for ($i = 0; $i < count($char); $i += CharacterPieces()) {
          if (TypeContains($char[$i], "T", $defPlayer) && SubtypeContains($char[$i], "Aura") && !$foundAura) {
            $foundAura = true;
            $power += 1;
          }
        }
        $power += 0;
        break;
      case "little_big_foot_red":
        $power += SearchCount(SearchPitch($mainPlayer, minCost: 3)) >= 2 ? 6 : 0;
        break;
      case "little_big_foot_yellow":
        $power += SearchCount(SearchPitch($mainPlayer, minCost: 3)) >= 2 ? 5 : 0;
        break;
      case "little_big_foot_blue":
        $power += SearchCount(SearchPitch($mainPlayer, minCost: 3)) >= 2 ? 4 : 0;
        break;
      default:
        break;
    }
  }
  return $power;
}

function BlockModifier($cardID, $from, $resourcesPaid, $index=-1)
{
  global $defPlayer, $CS_CardsBanished, $mainPlayer, $CS_ArcaneDamageDealt, $CombatChain, $chainLinks, $CS_NumClashesWon, $CS_Num6PowBan, $CS_NumCrouchingTigerCreatedThisTurn;
  global $CS_NumBluePlayed, $currentTurnEffects, $combatChain, $combatChainState, $CCS_CachedTotalPower;
  $blockModifier = 0;
  $noGain = !CanGainBlock($cardID);
  $cardType = CardType($cardID);
  $blockCard = $index != -1 && is_numeric($index) ? $CombatChain->Card($index) : "-";
  // should probably refactor this as an EffectBlockModifier function
  if (!$noGain) {
    if ($cardType == "AA") {
      $blockModifier += CountCurrentTurnEffects("art_of_war_yellow-1", $defPlayer);
      $blockModifier += CountCurrentTurnEffects("potion_of_ironhide_blue", $defPlayer);
    }
    if ($cardType == "AA" || $cardType == "A") {
      $blockModifier += CountCurrentTurnEffects("lyath_goldmane", $defPlayer);
      $blockModifier += CountCurrentTurnEffects("lyath_goldmane_vile_savant", $defPlayer);
      $blockModifier += CountCurrentTurnEffects("will_of_the_crowd_blue", $defPlayer) * 3;
    }
  }
  if ($cardType == "E") {
    $originUniqueID = $blockCard != "-" ? $blockCard->OriginUniqueID() : "-";
    if ($blockCard == "-" && str_contains($index, ",")) {
      $i = explode(",", $index)[0];
      $j = explode(",", $index)[1];
      $originUniqueID = $chainLinks[$i][$j + 8];
    }
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectsPieces()) {
      switch ($currentTurnEffects[$i]) {
        case "shred_red":
          if ($currentTurnEffects[$i+1] == $defPlayer && $currentTurnEffects[$i+2] == $originUniqueID) $blockModifier -= 4;
          break;
        case "shred_yellow":
          if ($currentTurnEffects[$i+1] == $defPlayer && $currentTurnEffects[$i+2] == $originUniqueID) $blockModifier -= 3;
          break;
        case "shred_blue":
          if ($currentTurnEffects[$i+1] == $defPlayer && $currentTurnEffects[$i+2] == $originUniqueID) $blockModifier -= 2;
          break;
        case "tarantula_toxin_red-SHRED":
          if ($currentTurnEffects[$i+1] == $defPlayer && $currentTurnEffects[$i+2] == $originUniqueID) $blockModifier -= 3;
          break;
      }
    }
  }
  if ((DelimStringContains($cardType, "E") || SubtypeContains($cardID, "Evo")) && (SearchCurrentTurnEffects("scramble_pulse_red", $mainPlayer) || SearchCurrentTurnEffects("scramble_pulse_yellow", $mainPlayer) || SearchCurrentTurnEffects("scramble_pulse_blue", $mainPlayer))) {
    $countScramblePulse = 0 + CountCurrentTurnEffects("scramble_pulse_red", $mainPlayer);
    $countScramblePulse += CountCurrentTurnEffects("scramble_pulse_yellow", $mainPlayer);
    $countScramblePulse += CountCurrentTurnEffects("scramble_pulse_blue", $mainPlayer);
    $blockModifier -= 1 * $countScramblePulse;
  }
  if (SearchCurrentTurnEffects("pulse_of_isenloft_blue", $defPlayer) && ($cardType == "AA" || DelimStringContains($cardType, "A")) && (TalentContains($cardID, "ICE", $defPlayer) || TalentContains($cardID, "EARTH", $defPlayer) || TalentContains($cardID, "ELEMENTAL", $defPlayer))) $blockModifier += 1;
  if (SearchCurrentTurnEffects("fabricate_red", $defPlayer) && SubtypeContains($cardID, "Evo", $defPlayer) && ($from == "EQUIP" || $from == "CC")) $blockModifier += CountCurrentTurnEffects("fabricate_red", $defPlayer);
  // Effect Block Modifier ends here
  $blockModifier += AuraBlockModifier($cardID, $from);
  $blockModifier += ItemBlockModifier($cardID);
  $totalPower = $combatChainState[$CCS_CachedTotalPower];

  $defAuras = &GetAuras($defPlayer);
  $attackID = $CombatChain->AttackCard()->ID();
  foreach($chainLinks as $link) {
    for ($i = 0; $i < count($link); $i += ChainLinksPieces()) {
      if ($link[$i+1] == $defPlayer && $link[$i+2] == 1) {
        switch ($link[$i]) {
          case "captain_of_the_guard_blue":
            if ($blockCard != "-" && $blockCard->TotalPower() > $totalPower) {
              if (!$noGain) ++$blockModifier;
            }
            break;
          default:
            break;
        }
      }
    }
  }
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i+1] == $defPlayer) {
      switch ($combatChain[$i]) {
          case "captain_of_the_guard_blue":
            if ($blockCard != "-" && $blockCard->TotalPower() > $totalPower) {
              if (!$noGain) ++$blockModifier;
            }
            break;
          default:
            break;
        }
    }
  }
  $currentBlockModifier = $blockModifier;
  $card = GetClass($cardID, $defPlayer);
  if ($card != "-") $blockModifier += $card->CardBlockModifier($from, $resourcesPaid, $index);
  switch ($cardID) {
    case "sigil_of_suffering_red":  
    case "sigil_of_suffering_yellow":
    case "sigil_of_suffering_blue":
      $blockModifier += GetClassState($defPlayer, $CS_ArcaneDamageDealt) > 0 ? 1 : 0;
      break;
    case "unmovable_red":
    case "unmovable_yellow":
    case "unmovable_blue":
      $blockModifier += $from == "ARS" ? 1 : 0;
      break;
    case "staunch_response_red":
    case "staunch_response_yellow":
    case "staunch_response_blue":
      $blockModifier += ($resourcesPaid >= 6 ? 3 : 0);
      break;
    case "arcanite_skullcap":
      $blockModifier += (PlayerHasLessHealth($defPlayer) ? 1 : 0);
      break;
    case "springboard_somersault_yellow":
      $blockModifier += ($from == "ARS" ? 2 : 0);
      break;
    case "impenetrable_belief_red":
    case "impenetrable_belief_yellow":
    case "impenetrable_belief_blue":
      return GetClassState($mainPlayer, $CS_CardsBanished) >= 3 ? 2 : 0;
    case "yinti_yanti_red":
    case "yinti_yanti_yellow":
    case "yinti_yanti_blue":
      return count($defAuras) >= 1 ? 1 : 0;
    case "wax_on_red":
    case "wax_on_yellow":
    case "wax_on_blue":
      return (CardCost($attackID) == 0 && CardType($attackID) == "AA" ? 2 : 0);
    case "blazen_yoroi":
      $blockModifier += (count($chainLinks) >= 3 ? 4 : 0);
      break;
    case "shield_wall_red":
    case "shield_wall_yellow":
    case "shield_wall_blue":
      $blockModifier += SearchCharacter($defPlayer, subtype: "Off-Hand", class: "GUARDIAN") != "" ? 4 : 0;
      break;
    case "diabolic_offering_blue":
      $blockModifier += GetClassState($defPlayer, $CS_Num6PowBan) > 0 ? 6 : 0;
      break;
    case "bastion_of_unity":
      $blockModifier += CountCurrentTurnEffects($cardID, $defPlayer);
      break;
    case "steel_street_enforcement_blue":
      $blockModifier += EvoUpgradeAmount($defPlayer);
      break;
    case "teklonetic_force_field_red":
    case "teklonetic_force_field_yellow":
    case "teklonetic_force_field_blue":
      if (CachedOverpowerActive()) $blockModifier += 2;
      break;
    case "raw_meat":
      CountAura("agility", $defPlayer) > 0 ? $blockModifier += 1 : 0; 
      CountAura("might", $defPlayer) > 0 ? $blockModifier += 1 : 0; 
      break;
    case "stonewall_impasse":
      $blockModifier += SearchCurrentTurnEffects($cardID, $defPlayer);
      break;
    case "stand_ground":
      CountAura("might", $defPlayer) > 0 ? $blockModifier += 1 : 0; 
      CountAura("vigor", $defPlayer) > 0 ? $blockModifier += 1 : 0; 
      break;
    case "boast_blue":
      $blockModifier += (2 * GetClassState($defPlayer, $CS_NumClashesWon));
      break;
    case "parry_blade":
      if (IsWeaponAttack()) $blockModifier += 2;
      break;    
    case "beckon_applause":
      CountAura("agility", $defPlayer) > 0 ? $blockModifier += 1 : 0; 
      CountAura("vigor", $defPlayer) > 0 ? $blockModifier += 1 : 0; 
      break;
    case "standing_order_red":
      if (SearchCurrentTurnEffects($cardID, $defPlayer, true)) $blockModifier += 2;
      break;
    case "big_blue_sky_blue":
      if (SearchCurrentTurnEffects($cardID, $defPlayer)) $blockModifier += SearchPitchForColor($defPlayer, 3);
      break;
    case "wash_away_blue":
      if (GetClassState($defPlayer, $CS_NumBluePlayed) > 1) $blockModifier += 2;
      break;
    case "territorial_domain_blue":
      if (GetClassState($defPlayer, $CS_NumCrouchingTigerCreatedThisTurn) > 0) $blockModifier += 3;
      break;
    case "helm_of_lignum_vitae":
      if (SearchCount(SearchBanish($defPlayer, talent: "EARTH")) >= 4) $blockModifier += 1;
      break;
    case "heavy_industry_surveillance":
      if (SearchCurrentTurnEffects($cardID, $defPlayer)) $blockModifier += CountCurrentTurnEffects($cardID, $defPlayer);
      break;
    case "heavy_industry_ram_stop":
      if (SearchCurrentTurnEffects($cardID, $defPlayer)) $blockModifier += CountCurrentTurnEffects($cardID, $defPlayer);
      break;
    case "red_alert_visor":
    case "red_alert_vest":
    case "red_alert_gloves":
    case "red_alert_boots":
      if (NumAttackReactionsPlayed() > 0) $blockModifier += 1;
      break;
    case "blade_beckoner_helm":
    case "blade_beckoner_plating":
    case "blade_beckoner_gauntlets":
    case "blade_beckoner_boots":
      if (IsWeaponAttack()) $blockModifier += 1;
      break;
    case "breaker_helm_protos":
      if (SearchCurrentTurnEffects($cardID, $defPlayer)) $blockModifier += CountCurrentTurnEffects($cardID, $defPlayer);
      break;
    case "testament_of_valahai":
      $countSeismic = CountAura("seismic_surge", $defPlayer);
      if ($countSeismic >= 6) {
        $blockModifier += 4;
      }
      elseif ($countSeismic >= 3) {
        $blockModifier += 2;
      }
      break;
    case "hoarding_of_denial":
      for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
        if (CardCost($combatChain[$i]) >= 3 && $combatChain[$i + 1] == $defPlayer) ++$blockModifier;
      }
      foreach ($chainLinks as $link) {
        for ($j = 0; $j < count($link); $j += ChainLinksPieces()) {
          if ($link[$j + 1] == $defPlayer && CardCost($link[$j]) >= 3) ++$blockModifier;
        }
      }
      break;
    case "tremor_of_resistance":
      if (SearchAuras("seismic_surge", $defPlayer)) $blockModifier += 2;
      break;
    default:
      break;
  }
  if ($currentBlockModifier < $blockModifier && $noGain) return $currentBlockModifier;
  else return $blockModifier;
}

function PlayBlockModifier($cardID)
{
  switch ($cardID) {
    case "reinforce_the_line_red":
      return 4;
    case "reinforce_the_line_yellow":
      return 3;
    case "reinforce_the_line_blue":
      return 2;
    case "summerwood_shelter_red":
      return 4;
    case "summerwood_shelter_yellow":
      return 3;
    case "summerwood_shelter_blue":
      return 2;
    case "celestial_resolve_red":
      return 5;
    case "celestial_resolve_yellow":
      return 4;
    case "celestial_resolve_blue":
      return 3;
    default:
      return 0;
  }
}

function OnDefenseReactionResolveEffects($from, $cardID)
{
  global $currentTurnEffects, $mainPlayer, $defPlayer, $combatChain, $CS_NumBlueDefended, $combatChainState, $CCS_NumCardsBlocking;
  global $Stack;
  $blockedFromHand = 0;
  for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
    if(DelimStringContains(CardType($combatChain[$i]), "DR")) {
      if (ColorContains($combatChain[$i], 3, $defPlayer)) IncrementClassState($defPlayer, $CS_NumBlueDefended);
      if ($combatChain[$i + 2] == "HAND") ++$blockedFromHand;
    }
  }
  $card = GetClass($combatChain[0], $mainPlayer);
  if ($card != "-") $card->AttackGetsBlockedEffect(count($combatChain) - CombatChainPieces());
  switch ($combatChain[0]) {
    case "zephyr_needle":
    case "zephyr_needle_r":
      EvaluateCombatChain($totalPower, $totalBlock);
      break;
    case "decimator_great_axe":
      if (!SearchCurrentTurnEffects("decimator_great_axe", $mainPlayer) && $Stack->FindTrigger("decimator_great_axe") == "") {
        $nonEquipBlockingCards = GetChainLinkCards($defPlayer, "", "E", exclCardSubTypes: "Evo");
        if ($nonEquipBlockingCards != "") {
          $options = GetChainLinkCards($defPlayer, asMZInd:true);
          $pastOptions = GetPastChainLinkCards($defPlayer, asMZInd:true);
          if ($pastOptions != "") $options .= ",$pastOptions";
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $options);
          AddDecisionQueue("SHOWSELECTEDTARGET", $mainPlayer, "<-", 1);
          AddDecisionQueue("ADDTRIGGER", $mainPlayer, $combatChain[0], 1);
        }
      }
      break;
    case "spark_spray_red":
    case "spark_spray_yellow":
    case "spark_spray_blue":
      AddLayer("TRIGGER", $mainPlayer, $combatChain[0]);
      break;
    case "pec_perfect_red":
      AddLayer("TRIGGER", $mainPlayer, $combatChain[0]);
      break;
    default:
      break;
  }
  if (class_exists($cardID)) {
    $card = new $cardID($defPlayer);
    $card->OnDefenseReactionResolveEffects($from, $blockedFromHand);
  }
  switch ($cardID) {
    case "tripwire_trap_red":
      AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "pitfall_trap_yellow":
      if (!IsAllyAttacking()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "rockslide_trap_blue":
      AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "buzzsaw_trap_blue":
      if (HasIncreasedAttack()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "collapsing_trap_blue":
      if (!IsAllyAttacking() && DoesAttackHaveGoAgain()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "spike_pit_trap_blue":
      if (!IsAllyAttacking() && NumAttackReactionsPlayed() > 0) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "boulder_trap_yellow":
      if (!IsAllyAttacking() && HasIncreasedAttack()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "pendulum_trap_yellow":
      if (!IsAllyAttacking() && NumAttackReactionsPlayed() > 0) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "tarpit_trap_yellow":
      if (DoesAttackHaveGoAgain()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "bloodrot_trap_red":
      if (!IsAllyAttacking() && NumAttackReactionsPlayed() > 0) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "frailty_trap_red":
      if (!IsAllyAttacking() && DoesAttackHaveGoAgain()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "inertia_trap_red":
      if (!IsAllyAttacking() && HasIncreasedAttack()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "hunted_or_hunter_red":
      if (!IsAllyAttacking() && NumAttackReactionsPlayed() > 0) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "smoke_out_red":
      if (ColorContains($combatChain[0], 1, $mainPlayer)) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "lair_of_the_spider_red":
      if (!IsAllyAttacking() && DoesAttackHaveGoAgain()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "den_of_the_spider_red":
      if (!IsAllyAttacking() && HasIncreasedAttack()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
    case "chain_reaction_yellow":
      if (DoesAttackHaveGoAgain()) AddLayer("TRIGGER", $defPlayer, $cardID);
      break;
  }
  if ($blockedFromHand > 0 && SearchCharacterActive($mainPlayer, "mark_of_lightning", true) && (TalentContains($combatChain[0], "LIGHTNING", $mainPlayer) || TalentContains($combatChain[0], "ELEMENTAL", $mainPlayer))) {
    AddLayer("TRIGGER", $mainPlayer, "mark_of_lightning");
  }
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $defPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "nerve_scalpel":
        case "nerve_scalpel_r":
          $count = ModifyBlockForType("DR", -1); //AR is handled in OnBlockResolveEffects
          $remove = $count > 0;
          break;
        default:
          break;
      }
    } else {
      switch ($currentTurnEffects[$i]) {
        case "call_down_the_lightning_yellow":
          if ($from == "HAND") CallDownLightning();
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  ProcessMirageOnBlock(count($combatChain) - CombatChainPieces());
  ++$combatChainState[$CCS_NumCardsBlocking];
}

function OnBlockResolveEffects($cardID = "")
{
  global $combatChain, $defPlayer, $mainPlayer, $currentTurnEffects, $combatChainState, $CCS_WeaponIndex, $CombatChain, $CS_NumBlueDefended;
  global $CCS_NumCardsBlocking;
  //This is when blocking fully resolves, so everything on the chain from here is a blocking card except the first
  for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
    $effectPowerModifier = EffectsAttackYouControlModifiers($combatChain[$i], $defPlayer);
    if ($effectPowerModifier != 0) CombatChainPowerModifier($i, $effectPowerModifier);
    $auraPowerModifier = AurasAttackYouControlModifiers($combatChain[$i], $defPlayer);
    if ($auraPowerModifier != 0) CombatChainPowerModifier($i, $auraPowerModifier);
    else ProcessPhantasmOnBlock($i);
    ProcessMirageOnBlock($i);
  }
  $blockedFromHand = 0;
  $blockedWithIce = 0;
  $blockedWithEarth = 0;
  $blockedWithAura = 0;
  $numDefending = 0; //number of total cards defending
  $start = -1; //contains the index where cards "defending together" starts
  for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] == $defPlayer) ++$numDefending;
    if ($numDefending > $combatChainState[$CCS_NumCardsBlocking]) {
      $start = $start == -1 ? $i : $start;
      if (ColorContains($combatChain[$i], 3, $defPlayer)) IncrementClassState($defPlayer, $CS_NumBlueDefended);
      if ($combatChain[$i + 2] == "HAND" && $combatChain[$i + 1] == $defPlayer) ++$blockedFromHand;
      if (TalentContains($combatChain[$i], "ICE", $defPlayer)) ++$blockedWithIce;
      if (TalentContains($combatChain[$i], "EARTH", $defPlayer)) ++$blockedWithEarth;
      if (SubtypeContains($combatChain[$i], "Aura", $defPlayer)) ++$blockedWithAura;
    }
  }
  // if ($cardID != "") { //Code for when a card is pulled as a defending card on the chain, may not be necessary anymore
  //     $defendingCard = $cardID;
  //     $start = count($combatChain) - CombatChainPieces();
  // }
  // else $start = $start == -1 ? CombatChainPieces() : $start; // this shouldn't be necessary, but try to catch potential problems above

  if ($start == -1) return; //there wasn't a new card added as defending
  if ($CombatChain->HasCurrentLink()) {
    $card = GetClass($combatChain[0], $mainPlayer);
    if ($card != "-") $card->AttackGetsBlockedEffect($start);
    switch ($combatChain[0]) {
      case "zephyr_needle":
      case "zephyr_needle_r":
        EvaluateCombatChain($totalPower, $totalBlock);
        break;
      case "endless_winter_red":
        if (SearchCurrentTurnEffects($combatChain[0], $defPlayer)) {
          $numBlocking = intdiv(count($combatChain) - $start, CombatChainPieces());
          for ($i = 0; $i < $numBlocking; ++$i) {
            AddLayer("TRIGGER", $defPlayer, $combatChain[0]);
          }
        }
        break;
      case "give_and_take_red":
        $NumActionsBlocking = NumActionsBlocking();
        for ($i = 0; $i < $NumActionsBlocking; ++$i) {
          AddLayer("TRIGGER", $defPlayer, $combatChain[0]);
        }
        break;
      case "decimator_great_axe":
        if (!SearchCurrentTurnEffects("decimator_great_axe", $mainPlayer)) {
          $nonEquipBlockingCards = GetChainLinkCards($defPlayer, "", "E", exclCardSubTypes: "Evo");
          if ($nonEquipBlockingCards != "") {
            $options = GetChainLinkCards($defPlayer, asMZInd:true);
            $pastOptions = GetPastChainLinkCards($defPlayer, asMZInd:true);
            if ($pastOptions != "") $options .= ",$pastOptions";
            AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $options);
            AddDecisionQueue("SHOWSELECTEDTARGET", $mainPlayer, "<-", 1);
            AddDecisionQueue("ADDTRIGGER", $mainPlayer, $combatChain[0], 1);
            // AddCurrentTurnEffect("decimator_great_axe", $mainPlayer);
            // AddDecisionQueue("CHOOSECOMBATCHAIN", $mainPlayer, $options);
            // AddDecisionQueue("HALVEBASEDEFENSE", $defPlayer, "-", 1);
          }
        }
        break;
      case "hot_streak":
        $character = &GetPlayerCharacter($mainPlayer);
        if (NumAttacksBlocking() > 0 && SearchCurrentTurnEffectsForUniqueID($character[$combatChainState[$CCS_WeaponIndex] + 11] == -1)) {
          AddCurrentTurnEffect($combatChain[0], $mainPlayer, "CC", $character[$combatChainState[$CCS_WeaponIndex] + 11]);
        }
        break;
      case "spark_spray_red":
      case "spark_spray_yellow":
      case "spark_spray_blue":
        $numBlocking = 0;
        for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
          if ($combatChain[$i+1] == $defPlayer) $numBlocking += 1;
        }
        if ($numBlocking > 0) {
          AddLayer("TRIGGER", $mainPlayer, $combatChain[0]);
        }
        break;
      case "pec_perfect_red":
        $numBlocking = $cardID == "" ? NumCardsBlocking() : 1;
        for($i = 0; $i < $numBlocking; ++$i) {
          AddLayer("TRIGGER", $mainPlayer, $combatChain[0]);
        }
        break;
      default:
        break;
    }
  }
  $blockingCards = [];
  for ($i = $start; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] == $defPlayer) {
      $defendingCard = $combatChain[$i];
      $card = GetClass($defendingCard, $defPlayer);
      if ($card != "-") {
        $card -> OnBlockResolveEffects($blockedFromHand, $i, $start);
      }
      if (($blockedFromHand >= 2 && $combatChain[$i + 2] == "HAND") || ($blockedFromHand >= 1 && $combatChain[$i + 2] != "HAND")) UnityEffect($combatChain[$i]);
      if($cardID == "" && HasGalvanize($combatChain[$i])) AddLayer("TRIGGER", $defPlayer, $combatChain[$i], $i);
      elseif($cardID != "" && $combatChain[$i] == $cardID && HasGalvanize($combatChain[$i])) AddLayer("TRIGGER", $defPlayer, $cardID, $i);
      if (SearchCurrentTurnEffects("commanding_performance_red", $mainPlayer) != "" && TypeContains($combatChain[$i], "AA", $defPlayer) && ClassContains($combatChain[0], "WARRIOR", $mainPlayer) && IsHeroAttackTarget() && SearchLayersForCardID("commanding_performance_red") == -1) AddLayer("TRIGGER", $mainPlayer, "commanding_performance_red", $defPlayer);
      switch ($defendingCard) {//code for Jarl's armor
        case "ollin_ice_cap":
          $sub = TalentContains($defendingCard, "ICE", $defPlayer) ? 1 : 0; //necessary for a fringe case where the helm but not the other blocking card loses its talent
          if ($blockedWithIce - $sub > 0 && !IsAllyAttacking()) AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          break;
        case "tectonic_crust":
          $sub = TalentContains($defendingCard, "EARTH", $defPlayer) == true ? 1 : 0; //necessary for a fringe case where the chest but not the other blocking card loses its talent
          if ($blockedWithEarth - $sub > 0) AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          break;
        case "root_bound_trunks":
          if ($blockedWithAura > 0) AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          break;
        default:
          break;
      }
      switch ($defendingCard) {
        case "stalagmite_bastion_of_isenloft":
          if (!IsAllyAttacking()) AddLayer("TRIGGER", $defPlayer, $defendingCard);
          else WriteLog("<span style='color:red;'>No ".CardLink("frostbite", "frostbite")." is created because there is no attacking hero when allies attack.</span>");
          break;
        case "ironhide_helm":
        case "ironhide_plate":
        case "ironhide_gauntlet":
        case "ironhide_legs":
        case "rampart_of_the_rams_head":
        case "phantasmal_footsteps":
        case "flameborn_retribution_red":
        case "crown_of_providence":
        case "flex_red":
        case "flex_yellow":
        case "flex_blue":
        case "fyendals_fighting_spirit_red":
        case "fyendals_fighting_spirit_yellow":
        case "fyendals_fighting_spirit_blue":
        case "brothers_in_arms_red":
        case "brothers_in_arms_yellow":
        case "brothers_in_arms_blue":
        case "hornets_sting":
        case "wayfinders_crest":
        case "vambrace_of_determination":
        case "soulbond_resolve":
        case "firewall_red":
        case "firewall_yellow":
        case "firewall_blue":
        case "civic_peak":
        case "civic_duty":
        case "civic_guide":
        case "civic_steps":
        case "tiger_eye_reflex_yellow":
        case "tiger_eye_reflex_blue":
        case "crowd_control_red":
        case "crowd_control_yellow":
        case "song_of_the_shining_knight_blue":
        case "pack_call_red":
        case "pack_call_yellow":
        case "pack_call_blue":
        case "stonewall_impasse":
        case "gauntlets_of_iron_will":
        case "golden_glare":
        case "trounce_red":
        case "test_of_agility_red":
        case "clash_of_might_red":
        case "clash_of_might_yellow":
        case "clash_of_might_blue":
        case "test_of_might_red":
        case "wall_of_meat_and_muscle_red":
        case "clash_of_agility_red":
        case "clash_of_agility_yellow":
        case "clash_of_agility_blue":
        case "run_into_trouble_red":
        case "clash_of_vigor_red":
        case "clash_of_vigor_yellow":
        case "clash_of_vigor_blue":
        case "hearty_block_red":
        case "test_of_vigor_red":
        case "standing_order_red":
        case "test_of_strength_red":
        case "evo_magneto_blue_equip":
        case "stride_of_reprisal":
        case "traverse_the_universe":
        case "mask_of_wizened_whiskers":
        case "stonewall_gauntlet":
        case "helm_of_halos_grace":
        case "bracers_of_bellonas_grace":
        case "warpath_of_winged_grace":
        case "canopy_shelter_blue":
        case "heavy_industry_surveillance":
        case "heavy_industry_ram_stop":
        case "flash_of_brilliance":
        case "unforgetting_unforgiving_red":
        case "mask_of_deceit":
        case "kabuto_of_imperial_authority":
        case "thick_hide_hunter_yellow":
        case "zap_clappers":
        case "starlight_striders":
        case "hoist_em_up_red":
        case "breaker_helm_protos":
        case "sunken_treasure_blue":
        case "crash_and_bash_red":
        case "crash_and_bash_yellow":
        case "crash_and_bash_blue":
        case "return_fire_red":
        case "cogwerx_tinker_rings":
        case "pinion_sentry_blue":
        case "washed_up_wave":
        case "on_the_horizon_red":
        case "on_the_horizon_yellow":
        case "on_the_horizon_blue":
        case "helmsmans_peak":
        case "lost_in_transit_yellow":
        case "tricorn_of_saltwater_death":
        case "dig_in_red":
        case "dig_in_yellow":
        case "dig_in_blue":
        case "clash_of_bravado_yellow":
        case "base_of_the_mountain":
        case "test_of_iron_grip_red":
        case "sunkwater_lookout":
        case "sunkwater_exoshell":
        case "sunkwater_pincers":
        case "sunkwater_scalers":
        case "call_for_backup_red":
        case "valahai_riven_yellow":
          AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          break;
        case "scowling_flesh_bag":
          $targets = "THEIRCHAR-0";
          if (!ShouldAutotargetOpponent($defPlayer)) $targets = "MYCHAR-0,$targets";
          AddDecisionQueue("PASSPARAMETER", $defPlayer, $targets, 1);
          AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a hero to intimidate", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
          AddDecisionQueue("SHOWSELECTEDTARGET", $defPlayer, "<-", 1);
          AddDecisionQueue("ADDTRIGGER", $defPlayer, $defendingCard, 1);
          break;
        case "apex_bonebreaker":
          $num6Block = 0;
          for ($j = $start; $j < count($combatChain); $j += CombatChainPieces()) {
            if (ModifiedPowerValue($combatChain[$j], $defPlayer, "CC", "apex_bonebreaker") >= 6) ++$num6Block;
          }
          if ($num6Block) {
            AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          }
          break;
        case "defender_of_daybreak_red":
        case "defender_of_daybreak_yellow":
        case "defender_of_daybreak_blue":
          if (TalentContains($combatChain[0], "SHADOW", $mainPlayer)) AddCurrentTurnEffect($defendingCard, $defPlayer);
          break;
        case "attune_with_cosmic_vibrations_blue":
          if (!IsAllyAttacking()) {
            $deck = new Deck($mainPlayer);
            if ($deck->Reveal(1) && PitchValue($deck->Top()) == 3) {
              AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
            }
          }
          break;
        case "battlefront_bastion_red":
        case "battlefront_bastion_yellow":
        case "battlefront_bastion_blue":
          if (NumCardsBlocking() <= 1) AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          break;
        case "face_purgatory":
          $conditionsMet = 0;
          for ($j = $start; $j < count($combatChain); $j += CombatChainPieces()) {
            if (CardType($combatChain[$j]) == "AA") {
              ++$conditionsMet; 
              break;
            }
          }
          for ($k = $start; $k < count($combatChain); $k += CombatChainPieces()) {
            if (DelimStringContains(CardType($combatChain[$k], "CC"), "A")) {
              ++$conditionsMet; 
              break;
            }
          }
          if ($conditionsMet == 2) {
            AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          }
          break;
        case "ten_foot_tall_and_bulletproof_red":
          AddNextTurnEffect($defendingCard, $defPlayer);
          break;
        case "quickdodge_flexors":
          $char = &GetPlayerCharacter($defPlayer);
          for ($j = 0; $j < count($char); $j += CharacterPieces()) {
            if ($char[$j] == $defendingCard) $char[$j+7] = "1";
          }
          break;
        case "light_fingers":
          $defChar = GetPlayerCharacter($defPlayer);
          if (ClassContains($defChar[0], "THIEF", $defPlayer)) AddLayer("TRIGGER", $defPlayer, $defendingCard, $i);
          break;
        case "clash_of_mountains_red":
        case "clash_of_mountains_yellow":
        case "clash_of_mountains_blue":
        case "clash_of_heads_yellow":
        case "clash_of_chests_yellow":
        case "clash_of_arms_yellow":
        case "clash_of_legs_yellow":
        case "clash_of_shields_yellow":
          if (ClassContains($combatChain[0], "GUARDIAN", $mainPlayer)) {
            AddLayer("TRIGGER", $defPlayer, $defendingCard, $defendingCard);
          }
          break;
        case "overcrowded_blue":
          //calling it an attack trigger for organization
          $uid = $combatChain[$i + 7];
          AddLayer("TRIGGER", $defPlayer, $defendingCard, $uid, "ATTACKTRIGGER");
          break;
        default:
          break;
      }
      if (SearchAuras("daily_grind_blue", $defPlayer) && TypeContains($defendingCard, "AA")) {
        AddLayer("TRIGGER", $defPlayer, "daily_grind_blue", $defendingCard);
      }
      ++$combatChainState[$CCS_NumCardsBlocking];
      array_push($blockingCards, CardLink($defendingCard, $defendingCard));
    }
  }
  $message = "Player $defPlayer blocked with";
  switch (count($blockingCards)) {
    case 1: 
      WriteLog("$message $blockingCards[0]");
      break;
    case 2:
      WriteLog("$message $blockingCards[0] and $blockingCards[1]");
      break;
    default:
      $lastCard = array_pop($blockingCards);
      $otherCards = implode(", ", $blockingCards);
      WriteLog("$message $otherCards, and $lastCard");
      break;
  }
  if ($blockedFromHand > 0 && SearchCharacterActive($mainPlayer, "mark_of_lightning", true) && (TalentContains($combatChain[0], "LIGHTNING", $mainPlayer) || TalentContains($combatChain[0], "ELEMENTAL", $mainPlayer))) {
    AddLayer("TRIGGER", $mainPlayer, "mark_of_lightning");
  }
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $defPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "spiders_bite":
        case "spiders_bite_r":
          $count = ModifyBlockForType("AA", 0);
          $remove = $count > 0;
          break;
        case "nerve_scalpel":
        case "nerve_scalpel_r":
          $count = ModifyBlockForType("AR", 0); //DR could not possibly be blocking at this time, see OnDefenseReactionResolveEffects
          $remove = $count > 0;
          break;
        case "orbitoclast":
        case "orbitoclast_r":
          $count = ModifyBlockForType("A", 0);
          $remove = $count > 0;
          break;
        case "scale_peeler":
        case "scale_peeler_r":
          $count = ModifyBlockForType("E", 0);
          $remove = $count > 0;
          break;
        default:
          break;
      }
    }
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "call_down_the_lightning_yellow":
          if ($blockedFromHand >= 1) CallDownLightning();
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
}

function GetDefendingCardsFromCombatChainLink($chainLink, $defPlayer)
{
  // returns array of equipments played by the defending hero which is still on the chain
  $defendingCardIndices = array();
  for ($i = 0; $i < count($chainLink); $i += ChainLinksPieces()) {
    if ($chainLink[$i + 2] == 1 && $chainLink[$i + 1] == $defPlayer) {
      array_push($defendingCardIndices, $i);
    }
  }
  return $defendingCardIndices;
}

function BeginningReactionStepEffects()
{
  global $combatChain, $mainPlayer, $CombatChain;
  if (!$CombatChain->HasCurrentLink()) return "";
  switch ($combatChain[0]) {
    case "cyclone_roundhouse_yellow":
      if (ComboActive()) {
        AddLayer("TRIGGER", $mainPlayer, "cyclone_roundhouse_yellow");
      }
  }
}

function ModifyBlockForType($type, $amount)
{
  global $combatChain, $defPlayer;
  $count = 0;
  for ($i = count($combatChain) - CombatChainPieces(); $i > 0; $i -= CombatChainPieces()) {
    WriteLog($combatChain[$i] . "-" . CardTypeExtended($combatChain[$i]));
    if ($combatChain[$i + 1] != $defPlayer) continue;
    if (!DelimStringContains(CardTypeExtended($combatChain[$i]), $type)) continue;
    ++$count;
    $combatChain[$i + 6] += $amount;
    if ($type == "DR") return $count;
  }
  return $count;
}

function OnBlockEffects($index, $from)
{
  global $currentTurnEffects, $CombatChain, $currentPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer;
  global $Card_BlockBanner;
  $chainCard = $CombatChain->Card($index);
  $cardType = CardType($chainCard->ID());
  $cardSubtype = CardSubType($chainCard->ID());
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "flic_flak_red":
        case "flic_flak_yellow":
        case "flic_flak_blue":
          if (HasCombo($chainCard->ID())) $chainCard->ModifyDefense(2);
          $remove = true;
          break;
        case "endless_winter_red":
          if ($cardType == "DR") AddLayer("TRIGGER", $defPlayer, $currentTurnEffects[$i], );
          break;
        case "withstand_red":
        case "withstand_yellow":
        case "withstand_blue":
          if (ClassContains($chainCard->ID(), "GUARDIAN", $currentPlayer) && CardSubType($chainCard->ID()) == "Off-Hand") {
            if ($currentTurnEffects[$i] == "withstand_red") $amount = 6;
            else if ($currentTurnEffects[$i] == "withstand_yellow") $amount = 5;
            else $amount = 4;
            $chainCard->ModifyDefense($amount);
            $remove = true;
          }
          break;
        case "spiders_bite":
        case "spiders_bite_r":
          if ($cardType == "AA") $chainCard->ModifyDefense(-1);
          break;
        case "nerve_scalpel":
        case "nerve_scalpel_r":
          if ($cardType == "AR") $chainCard->ModifyDefense(-1);
          break;
        case "orbitoclast":
        case "orbitoclast_r":
          if (DelimStringContains($cardType, "A")) $chainCard->ModifyDefense(-1);
          $splitCard = explode("_", $chainCard->ID());
          if ($splitCard[count($splitCard) - 1] == "equip") {
            $id = implode("_", array_splice($splitCard, 0, count($splitCard) - 1));
            if (CardType($id) != $cardType) $chainCard->ModifyDefense(-1);
          }
          break;
        case "scale_peeler":
        case "scale_peeler_r":
          if ($cardType == "E" || DelimStringContains($cardSubtype, "Evo")) $chainCard->ModifyDefense(-1);
          break;
        case $Card_BlockBanner:
          if (DelimStringContains($cardType, "A") || $cardType == "AA") {
            $chainCard->ModifyDefense(1);
            $remove = true;
          }
          break;
        case "shelly_hardened_traveler_yellow":
          if (TypeContains($chainCard->ID(), "AA")) {
            $chainCard->ModifyDefense(1);
            $remove = true;
          }
          break;
        case "platinum_amulet_blue":
          $charInd = SearchCharacterForUniqueID($currentTurnEffects[$i+2], $defPlayer);
          $defChar = GetPlayerCharacter($defPlayer);
          if($charInd != -1 && $defChar[$charInd] == $chainCard->ID()) {
            $chainCard->ModifyDefense(1);
          }
          break;
        case "toughness":
          if (TypeContains($chainCard->ID(), "A") || TypeContains($chainCard->ID(), "AA")) {
            $chainCard->ModifyDefense(1);
            $remove = true;
          }
          break;
        default:
          break;
      }
    } else if ($currentTurnEffects[$i + 1] == $otherPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "plow_through_red":
        case "plow_through_yellow":
        case "plow_through_blue":
          if ($cardType == "AA" && NumAttacksBlocking() == 1) {
            AddCharacterEffect($otherPlayer, $combatChainState[$CCS_WeaponIndex], $currentTurnEffects[$i]);
            WriteLog(CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " gives your weapon +1 for the rest of the turn");
          }
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
  switch ($CombatChain->AttackCard()->ID()) {
    case "cintari_saber":
    case "cintari_saber_r":
      if ($cardType == "AA" && NumAttacksBlocking() == 1) {
        AddCharacterEffect($otherPlayer, $combatChainState[$CCS_WeaponIndex], $CombatChain->AttackCard()->ID());
        WriteLog(CardLink($CombatChain->AttackCard()->ID(), $CombatChain->AttackCard()->ID()) . " got +1 for the rest of the turn.");
      }
      break;
    default:
      break;
  }
  switch ($CombatChain->Card($index)->ID()) {
    case "headliner_helm":
    case "stadium_centerpiece":
    case "ticket_puncher":
    case "grandstand_legplates":
    case "bloodied_oval":
      AddCurrentTurnEffect($CombatChain->Card($index)->ID(), $defPlayer);
      break;
    default:
      break;
  }
  //bizarre, but technically symbiosis shot gets a counter when an item blocks
  if (SubtypeContains($chainCard->ID(), "Item", $defPlayer)) {
    $char = &GetPlayerCharacter($defPlayer);
    if (($symbiosisIndex = FindCharacterIndex($defPlayer, "symbiosis_shot")) > 0 && ClassContains($chainCard->ID(), "MECHANOLOGIST", $defPlayer)) {
    if ($char[$symbiosisIndex + 2] < 6) $char[$symbiosisIndex + 2] += 1;
    if ($char[$symbiosisIndex + 2] > 6) $char[$symbiosisIndex + 2] = 6;
  }
  }
}

function CombatChainCloseAbilities($player, $cardID, $chainLink)
{
  global $chainLinkSummary, $mainPlayer, $defPlayer, $chainLinks;
  $card = GetClass($cardID, $player);
  if ($card != "-") $card->CombatChainCloseAbility($chainLink);
  switch ($cardID) {
    case "swing_big_red":
      if ($player == $mainPlayer && SearchCurrentTurnEffects($cardID, $mainPlayer, true) && $chainLinkSummary[$chainLink * ChainLinkSummaryPieces()] == 0 && $chainLinks[$chainLink][0] == $cardID) {
        PlayAura("quicken", $defPlayer);
      }
      break;
    case "that_all_you_got_yellow":
      $AttackPowerValue = $chainLinkSummary[$chainLink * ChainLinkSummaryPieces() + 1];
      if ($AttackPowerValue <= 2) {
        Draw($player, effectSource:$cardID);
        WriteLog(CardLink($cardID, $cardID) . " drew a card");
      }
      break;
    case "regicide_blue":
      if ($player == $mainPlayer) LoseHealth(GetHealth($mainPlayer), $mainPlayer);
      break;
    default:
      break;
  }
}

function NumNonEquipmentDefended()
{
  global $combatChain, $defPlayer;
  $number = 0;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    $cardType = CardType($combatChain[$i]);
    if ($combatChain[$i + 1] == $defPlayer && $cardType != "E" && $cardType != "C" && !DelimStringContains(CardSubType($combatChain[$i]), "Evo")) ++$number;
  }
  return $number;
}

function NumCardsDefended()
{
  global $combatChain, $defPlayer;
  $number = 0;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] == $defPlayer) ++$number;
  }
  return $number;
}

function CombatChainPlayAbility($cardID)
{
  global $combatChain, $defPlayer;
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    switch ($combatChain[$i]) {
      case "sigil_of_parapets_blue":
        if (ClassContains($cardID, "WIZARD", $defPlayer)) {
          $combatChain[$i + 6] += 2;
          WriteLog(CardLink($combatChain[$i], $combatChain[$i]) . " gets +2 defense");
        }
        break;
      default:
        break;
    }
  }
}

function IsDominateActive()
{
  global $currentTurnEffects, $mainPlayer, $CCS_WeaponIndex, $combatChain, $CCS_CachedDominateActive, $combatChainState;
  global $CS_NumAuras, $CCS_NumBoosted, $chainLinks, $chainLinkSummary;
  if (count($combatChain) == 0) return false;
  if (SearchCurrentTurnEffectsForCycle("timidity_point_red", "timidity_point_yellow", "timidity_point_blue", $mainPlayer)) return false;
  if (SearchCurrentTurnEffects("fearless_confrontation_blue", $mainPlayer)) return false;
  $characterEffects = GetCharacterEffects($mainPlayer);
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i) && DoesEffectGrantsDominate($currentTurnEffects[$i])) return true;
  }
  for ($i = 0; $i < count($characterEffects); $i += CharacterEffectPieces()) {
    if ($characterEffects[$i] == $combatChainState[$CCS_WeaponIndex]) {
      switch ($characterEffects[$i + 1]) {
        case "ironsong_determination_yellow":
          return true;
        default:
          break;
      }
    }
  }
  // $foundHorrors = SearchCurrentTurnEffects("horrors_of_the_past_yellow", $mainPlayer, returnUniqueID:true);
  // $extraText = $foundHorrors != -1 ? $foundHorrors : "-";
  $extraText = GetHorrorsBuff();
  $textBoxes = [$combatChain[0], $extraText];
  foreach ($textBoxes as $box) {
    switch ($box) {
      case "open_the_center_red":
      case "open_the_center_yellow":
      case "open_the_center_blue":
        return (ComboActive() ? true : false);
      case "demolition_crew_red":
      case "demolition_crew_yellow":
      case "demolition_crew_blue":
        return true;
      case "arknight_ascendancy_red":
        return true;
      case "herald_of_erudition_yellow":
        return true;
      case "herald_of_tenacity_red":
      case "herald_of_tenacity_yellow":
      case "herald_of_tenacity_blue":
        return true;
      case "nourishing_emptiness_red":
        return SearchDiscard($mainPlayer, "AA") == "";
      case "overload_red":
      case "overload_yellow":
      case "overload_blue":
        return true;
      case "thump_red":
      case "thump_yellow":
      case "thump_blue":
        return HasIncreasedAttack();
      case "macho_grande_red":
      case "macho_grande_yellow":
      case "macho_grande_blue":
        return true;
      case "break_tide_yellow":
        return (ComboActive() ? true : false);
      case "payload_red":
      case "payload_yellow":
      case "payload_blue":
        return $combatChainState[$CCS_NumBoosted] > 0;
      case "drowning_dire_red":
      case "drowning_dire_yellow":
      case "drowning_dire_blue":
        return GetClassState($mainPlayer, $CS_NumAuras) > 0;
      case "fractal_replication_red":
        $hasDominate = false;
        for ($i = 0; $i < count($chainLinks); ++$i) {
          for ($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
            $isIllusionist = ClassContains($chainLinks[$i][$j], "ILLUSIONIST", $mainPlayer) || ($j == 0 && DelimStringContains($chainLinkSummary[$i * ChainLinkSummaryPieces() + 3], "ILLUSIONIST"));
            if ($chainLinks[$i][$j + 2] == "1" && $chainLinks[$i][$j] != "fractal_replication_red" && $isIllusionist && CardType($chainLinks[$i][$j]) == "AA") {
              if (!$hasDominate) $hasDominate = HasDominate($chainLinks[$i][$j]);
            }
          }
        }
        return $hasDominate;
      case "isolate_red":
      case "isolate_yellow":
      case "isolate_blue":
        return true;
      default:
        break;
    }
  }
  if ($combatChainState[$CCS_CachedDominateActive] == 1) {
    return true;
  }
  return false;
}

function IsOverpowerActive()
{
  global $combatChain, $mainPlayer, $defPlayer, $currentTurnEffects, $CS_Num6PowBan, $CS_NumItemsDestroyed, $CS_NumAuras;
  if (count($combatChain) == 0) return false;
  if (SearchItemsForCard("overload_script_red", $mainPlayer) != "" && CardType($combatChain[0]) == "AA" && ClassContains($combatChain[0], "MECHANOLOGIST", $mainPlayer)) {
    return true;
  }
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i) && DoesEffectGrantsOverpower($currentTurnEffects[$i])) return true;
    if ($currentTurnEffects[$i + 1] == $mainPlayer && $currentTurnEffects[$i] == "double_down_red" && CachedWagerActive()) return true;
  }
  if (HasHighTide($combatChain[0]) && HighTideConditionMet($mainPlayer)) {
    switch ($combatChain[0]) {
    case "hms_barracuda_yellow":
    case "hms_kraken_yellow":
    case "hms_marlin_yellow":
        return true;
      default:
        return false;
    }
  }
  switch ($combatChain[0]) {
    case "merciless_battleaxe":
      return SearchCurrentTurnEffects("merciless_battleaxe", $mainPlayer);
    case "hanabi_blaster":
      return true;
    case "spectral_rider_red":
    case "spectral_rider_yellow":
    case "spectral_rider_blue":
      return SearchCurrentTurnEffects("spectral_rider_red", $mainPlayer);
    case "nitro_mechanoida":
      return true;
    case "glaring_impact_red":
    case "glaring_impact_yellow":
    case "glaring_impact_blue":
      return SearchCurrentTurnEffects($combatChain[0], $mainPlayer);
    case "wall_breaker_red":
    case "wall_breaker_yellow":
    case "wall_breaker_blue":
      return GetClassState($mainPlayer, $CS_Num6PowBan) > 0;
    case "annihilator_engine_red":
    case "terminator_tank_red":
    case "war_machine_red":
      return EvoUpgradeAmount($mainPlayer) >= 3;
    case "hydraulic_press_red":
    case "hydraulic_press_yellow":
    case "hydraulic_press_blue":
      return SearchCurrentTurnEffects($combatChain[0], $mainPlayer);
    case "moonshot_yellow":
      return CachedTotalPower() >= 10;
    case "torque_tuned_red":
    case "torque_tuned_yellow":
    case "torque_tuned_blue":
      return GetClassState($mainPlayer, $CS_NumItemsDestroyed) > 0;
    case "bull_bar_red":
    case "bull_bar_yellow":
    case "bull_bar_blue":
      return SearchItemsByName($mainPlayer, "Hyper Driver") != "";
    case "over_the_top_red":
    case "over_the_top_yellow":
    case "over_the_top_blue":
      return HasIncreasedAttack();
    case "pay_up_red":
      return IsHeroAttackTarget() && CountItem("gold", $defPlayer) > 0;
    case "vantage_point_red":
    case "vantage_point_yellow":
    case "vantage_point_blue":
      return GetClassState($mainPlayer, $CS_NumAuras) > 0;
    case "gold_hunter_marauder_yellow":
      $myNumGold = CountItem("gold", $mainPlayer);
      $theirNumGold = CountItem("gold", $defPlayer);
      return $myNumGold < $theirNumGold;
    default:
      break;
  }
  return false;
}

function IsWagerActive()
{
  global $combatChainState, $CCS_WagersThisLink;
  return intval($combatChainState[$CCS_WagersThisLink]) > 0;
}

function IsFusionActive()
{
  global $combatChainState, $CCS_AttackFused;
  return intval($combatChainState[$CCS_AttackFused]) > 0;
}

function CombatChainClosedTriggers()
{
  global $chainLinks, $mainPlayer, $defPlayer, $CS_HealthLost, $currentTurnEffects;
  for ($i = 0; $i < count($chainLinks); ++$i) {
    for ($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      $cardType = CardType($chainLinks[$i][$j]);
      if ($chainLinks[$i][$j + 1] != $mainPlayer || ($chainLinks[$i][$j + 2] == 0 && !IsStaticType($cardType))) continue;
      switch ($chainLinks[$i][$j]) {
        case "hell_hammer":
          $index = FindCharacterIndex($mainPlayer, "hell_hammer");
          if ($index > -1 && SearchCurrentTurnEffects("hell_hammer", $mainPlayer, true)) {
            BanishCardForPlayer("hell_hammer", $mainPlayer, "CC");
            DestroyCharacter($mainPlayer, $index, true);
          }
          break;
        case "widespread_annihilation_blue":
          if (GetClassState($mainPlayer, $CS_HealthLost) > 0) MZChooseAndBanish($mainPlayer, "MYHAND", "ARS,-");
          if (GetClassState($defPlayer, $CS_HealthLost) > 0) MZChooseAndBanish($defPlayer, "MYHAND", "ARS,-");
          break;
        case "widespread_destruction_yellow":
          if (GetClassState($mainPlayer, $CS_HealthLost) > 0) MZChooseAndBanish($mainPlayer, "MYARS", "ARS,-");
          if (GetClassState($defPlayer, $CS_HealthLost) > 0) MZChooseAndBanish($defPlayer, "MYARS", "ARS,-");
          break;
        case "widespread_ruin_red":
          if (GetClassState($mainPlayer, $CS_HealthLost) > 0) {
            $deck = new Deck($mainPlayer);
            $deck->BanishTop();
          }
          if (GetClassState($defPlayer, $CS_HealthLost) > 0) {
            $deck = new Deck($defPlayer);
            $deck->BanishTop();
          }
          break;
        case "deathly_wail_red":
        case "deathly_wail_yellow":
        case "deathly_wail_blue":
          $numRunechant = 0;
          if (GetClassState($mainPlayer, $CS_HealthLost) > 0) ++$numRunechant;
          if (GetClassState($defPlayer, $CS_HealthLost) > 0) ++$numRunechant;
          if ($numRunechant > 0) PlayAura("runechant", $mainPlayer, $numRunechant, effectSource: $chainLinks[$i][$j]);
          break;
        case "deathly_delight_red":
        case "deathly_delight_yellow":
        case "deathly_delight_blue":
          $numLife = 0;
          if (GetClassState($mainPlayer, $CS_HealthLost) > 0) ++$numLife;
          if (GetClassState($defPlayer, $CS_HealthLost) > 0) ++$numLife;
          if ($numLife > 0) GainHealth($numLife, $mainPlayer);
          break;
        case "eloquent_eulogy_red":
          $numEloquence = 0;
          if (GetClassState($mainPlayer, $CS_HealthLost) > 0) ++$numEloquence;
          if (GetClassState($defPlayer, $CS_HealthLost) > 0) ++$numEloquence;
          if ($numEloquence > 0) PlayAura("eloquence", $mainPlayer);
          break;
        default:
          break;
      }
    }
  }
  for ($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    if (!isset($currentTurnEffects[$i + 1])) continue;
    $effectID = explode("-", $currentTurnEffects[$i])[0];
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($effectID) {
        case "kunai_of_retribution":
        case "kunai_of_retribution_r":
          $uniqueID = explode("-", $currentTurnEffects[$i])[1];
          $index = FindCharacterIndexUniqueID($mainPlayer, $uniqueID);
          if ($index != -1) DestroyCharacter($mainPlayer, $index);
          break;
        case "breakwater_undertow":
          $uniqueID = explode("-", $currentTurnEffects[$i+2])[1];
          $index = SearchAlliesForUniqueID($uniqueID, $mainPlayer);
          if ($index != -1) DestroyAlly($mainPlayer, $index);
          RemoveCurrentTurnEffect($i);
          break;
        default:
          break;
      }
    }
  }
}

function CombatChainPayAdditionalCosts($index, $from)
{
  global $combatChain, $currentPlayer, $chainLinks;
  
  if ($from == "PLAY") {
    $i = $index * CombatChainPieces();
    if(!isset($combatChain[$i]))  {
      //PHP error happening here. Undefined array key 121 in /opt/lampp/htdocs/game/CombatChain.php on line 1503
      return; 
    }
    $cardID = $combatChain[$i];
  }
  else {
    $i = intdiv($index, ChainLinksPieces());
    $cardID = $chainLinks[$i][0];
  }
  switch($cardID) {
    case "sky_skimmer_red":
    case "sky_skimmer_yellow":
    case "sky_skimmer_blue":
    case "cloud_city_steamboat_red":
    case "cloud_city_steamboat_yellow":
    case "cloud_city_steamboat_blue":
    case "palantir_aeronought_red":
    case "jolly_bludger_yellow":
    case "cogwerx_dovetail_red":
    case "cogwerx_zeppelin_red":
    case "cogwerx_zeppelin_yellow":
    case "cogwerx_zeppelin_blue":
    case "cloud_skiff_red":
    case "cloud_skiff_yellow":
    case "cloud_skiff_blue":
      //for some reason DQs aren't working here, for now just automatically choose the first cog
      $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
      if($inds != "") Tap(explode(",", $inds)[0], $currentPlayer);
      if ($from == "PLAY") ++$combatChain[$i + 11];
      else ++$chainLinks[$i][9];
      break;
    default:
      break;
  }
}

function CacheCombatResult()
{
  global $combatChain, $combatChainState, $CCS_CachedTotalPower, $CCS_CachedTotalBlock, $CCS_CachedDominateActive, $CCS_CachedOverpowerActive;
  global $CSS_CachedNumActionBlocked, $CCS_CachedNumDefendedFromHand, $CCS_PhantasmThisLink, $CCS_AttackFused, $CCS_WagersThisLink, $mainPlayer;
  if (count($combatChain) == 0) return;
  $combatChainState[$CCS_CachedTotalPower] = 0;
  $combatChainState[$CCS_CachedTotalBlock] = 0;
  EvaluateCombatChain($combatChainState[$CCS_CachedTotalPower], $combatChainState[$CCS_CachedTotalBlock], secondNeedleCheck:true);
  // hard code this exception to avoid circularity
  $card = GetClass($combatChain[0], $mainPlayer);
  if (is_a($card, "SUPDwarfCard") && $combatChainState[$CCS_CachedTotalPower] > LinkBasePower()) {
    ++$combatChainState[$CCS_CachedTotalPower];
  }
  $combatChainState[$CCS_CachedDominateActive] = (IsDominateActive() ? "1" : "0");
  $combatChainState[$CCS_CachedOverpowerActive] = (IsOverpowerActive() ? "1" : "0");
  $combatChainState[$CSS_CachedNumActionBlocked] = NumActionsBlocking();
  if ($combatChainState[$CCS_CachedNumDefendedFromHand] == 0) $combatChainState[$CCS_CachedNumDefendedFromHand] = NumDefendedFromHand();
  $combatChainState[$CCS_WagersThisLink] = (IsWagerActive() ? intval($combatChainState[$CCS_WagersThisLink]) : "0");
  $combatChainState[$CCS_PhantasmThisLink] = (IsPhantasmActive() ? "1" : "0");
  $combatChainState[$CCS_AttackFused] = (IsFusionActive() ? "1" : "0");
}

function CachedTotalPower()
{
  global $combatChainState, $CCS_CachedTotalPower;
  return $combatChainState[$CCS_CachedTotalPower];
}

function CachedTotalBlock()
{
  global $combatChainState, $CCS_CachedTotalBlock;
  return $combatChainState[$CCS_CachedTotalBlock];
}

function CachedDominateActive()
{
  global $combatChainState, $CCS_CachedDominateActive;
  return ($combatChainState[$CCS_CachedDominateActive] == "1" ? true : false);
}

function CachedOverpowerActive()
{
  global $combatChainState, $CCS_CachedOverpowerActive;
  return ($combatChainState[$CCS_CachedOverpowerActive] == "1" ? true : false);
}

function CachedWagerActive()
{
  global $combatChainState, $CCS_WagersThisLink;
  if (isset($combatChainState[$CCS_WagersThisLink])) {
    return ($combatChainState[$CCS_WagersThisLink] >= "1" ? true : false);
  } else return false;
}

function CachedFusionActive()
{
  global $combatChainState, $CCS_AttackFused;
  if (isset($combatChainState[$CCS_AttackFused])) {
    return ($combatChainState[$CCS_AttackFused] == "1" ? true : false);
  } else return false;
}

function CachedPhantasmActive()
{
  global $combatChainState, $CCS_PhantasmThisLink;
  if (isset($combatChainState[$CCS_PhantasmThisLink])) {
    return ($combatChainState[$CCS_PhantasmThisLink] == "1" ? true : false);
  } else return false;
}

function CachedNumDefendedFromHand() //Reprise
{
  global $combatChainState, $CCS_CachedNumDefendedFromHand;
  return $combatChainState[$CCS_CachedNumDefendedFromHand];
}

function CachedNumActionBlocked()
{
  global $combatChainState, $CSS_CachedNumActionBlocked;
  return $combatChainState[$CSS_CachedNumActionBlocked];
}

function IsPiercingActive($cardID)
{
  global $CombatChain, $currentTurnEffects, $mainPlayer;
  if ($CombatChain->HasCurrentLink()) {
    if (HasPiercing($cardID)) return true;
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
      if (!isset($currentTurnEffects[$i + 1])) continue;
      if ($currentTurnEffects[$i + 1] == $mainPlayer && HasPiercing($currentTurnEffects[$i])) return true;
    }
  }
  return false;
}

function IsTowerActive()
{
  global $combatChain;
  return (CachedTotalPower() >= 13 && HasTower($combatChain[0]));
}

function IsHighTideActive()
{
  global $combatChain, $mainPlayer;
  return (HasHighTide($combatChain[0]) && HighTideConditionMet($mainPlayer));
}

function ActiveOnHits(): bool
{
  global $CombatChain;
  if (!$CombatChain->HasCurrentLink()) return false;
  if (AddOnHitTrigger($CombatChain->AttackCard()->ID(), check: true)) return true;
  return false;
}

function RemoveCombatChainLink($ind) 
{
  global $chainLinks;
  $linkInd = intdiv($ind, ChainLinksPieces());
  $chainLinks[$linkInd][2] = 0;
  return $chainLinks[$linkInd][0];
}

function IsResolutionStep()
{
  global $Stack;
  $resolutionStep = $Stack->FindCardID("RESOLUTIONSTEP");
  return $resolutionStep != "";
}

function IsLayerStep()
{
  global $layers, $CombatChain, $mainPlayer;
  if ($CombatChain->HasCurrentLink()) return false;
  $layerInd = count($layers) - LayerPieces();
  $nonCardLayers = ["LAYER", "PRELAYERS", "TRIGGER", "PRETRIGGER", "ABILITY", "MELD", "RESUMETURN"];
  if (!isset($layers[$layerInd])) return false;
  if (in_array($layers[$layerInd], $nonCardLayers)) return false;
  if ($layers[$layerInd + 1] != $mainPlayer) return false;
  $layerFrom = explode("|", $layers[$layerInd + 2])[0];
  return GoesOnCombatChain("M", $layers[$layerInd], $layerFrom, $mainPlayer);
}

// check if we've already passed damage
// combat chain doesn't close of the attack goes away now
function AfterDamage()
{
  if (SearchLayersForPhase("RESOLUTIONSTEP") != -1) return true;
  if (SearchLayersForPhase("FINALIZECHAINLINK") != -1) return true;
  return false;
}

function LinkBasePower()
{
  global $CombatChain, $currentTurnEffects, $mainPlayer, $combatChain, $CS_Num6PowBan;
  if ($CombatChain->HasCurrentLink()) {
    $attackID = $CombatChain->AttackCard()->ID();
    $attackUID = $CombatChain->AttackCard()->UniqueID();
    $attackOriginUID = $combatChain[8];
    if (SubTypeContains($attackID, "Aura")) {
      $index = SearchAurasForUniqueID($attackOriginUID, $mainPlayer);
    }
    else $index = 0;
    $basePower = PowerValue($attackID, $mainPlayer, 'CC', $index, true, true);
    $attackCard = GetClass($attackID, $mainPlayer);
    //substage 2
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
      $card = GetClass($currentTurnEffects[$i], $mainPlayer);
      if ($card != "-") $basePower = $card->EffectSetBasePower($basePower);
      $effects = explode("-", $currentTurnEffects[$i]);
      switch ($effects[0]) {
        case "kayo_underhanded_cheat":
        case "kayo_strong_arm":
          if ($mainPlayer == $currentTurnEffects[$i + 1]) $basePower = 6;
          break;
        case "transmogrify_red":
          $basePower = 8;
          break;
        case "transmogrify_yellow":
          $basePower = 7;
          break;
        case "transmogrify_blue":
          $basePower = 6;
          break;
        case "cosmic_awakening_blue":
          $basePower = match($effects[1]) {
            "1" => 10, "2" => 15, "3" => 20,
          };
          break;
        case "ghostly_touch":
          if ($attackID == "UPR551") $basePower = $effects[1];
          break;
        default:
          break;
      }
    }
    //substage 3
    if ($attackCard != "-") $basePower = $basePower * $attackCard->MultiplyBasePower();
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
      $card = GetClass($currentTurnEffects[$i], $mainPlayer);
      if ($card != "-") $basePower = $card->EffectMultiplyBasePower() * $basePower;
      switch ($currentTurnEffects[$i]) {
        case "kayo_berserker_runt-DOUBLE":
          $basePower = 2 * $basePower;
          break;
        default:
          break;
      }
    }
    //substage 4
    if ($attackCard != "-") $basePower = ceil($basePower / $attackCard->DivideBasePower());
    for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
      $card = GetClass($currentTurnEffects[$i], $mainPlayer);
      if (!IsCombatEffectActive($currentTurnEffects[$i])) continue;
      if ($card != "-") $basePower = ceil($basePower / $card->EffectDivideBasePower());
      switch ($currentTurnEffects[$i]) {
        case "kayo_berserker_runt-HALF":
          $basePower = floor($basePower / 2);
          break;
        case "fatigue_shot_red":
        case "fatigue_shot_yellow":
        case "fatigue_shot_blue":
          $basePower = ceil($basePower / 2);
          break;
        default:
          break;
      }
    }
    $char = GetPlayerCharacter($mainPlayer);
    for ($i = 0; $i < count($char); $i += CharacterPieces()) {
      if (isset($char[$i + 1]) && $char[$i + 1] == 2) {
        $card = GetClass($char[$i], $mainPlayer);
        if ($card != "-") $basePower = ceil($basePower / $card->CharDivideBasePower());
        $char[$i] = ShiyanaCharacter($char[$i], $mainPlayer);
        switch ($char[$i]) {
          case "lyath_goldmane_vile_savant":
          case "lyath_goldmane":
            $basePower = ceil($basePower / 2);
            break;
          default:
            break;
        }
      }
    }
    return $basePower;
  }
  return 0;
}

function CombatChainHitEffect($cardID, $sourceID="-", $targetPlayer="-") {
  global $CombatChain, $mainPlayer, $defPlayer;
  $source = $sourceID != "-" ? $sourceID : $CombatChain->AttackCard()->ID();
  if (SearchCurrentTurnEffects("dense_blue_mist_blue-HITPREVENTION", $defPlayer)) return false;
  switch ($cardID) {
    case "poisoned_blade_red":
    case "poisoned_blade_yellow":
    case "poisoned_blade_blue":
      if(IsHeroAttackTarget() && (SubtypeContains($CombatChain->AttackCard()->ID(), "Dagger") || SubtypeContains($sourceID, "Dagger"))) {
        AddLayer("TRIGGER", $mainPlayer, $cardID, $cardID, "EFFECTHITEFFECT", $source);
      }
      break;
    default:
      break;
  }
}

function CombatChainHitEffects($sourceID="-", $targetPlayer="-") {
  global $CombatChain, $ChainLinks;

  if ($CombatChain->HasCurrentLink()) {
    CombatChainHitEffect($CombatChain->AttackCard()->ID(), $sourceID, $targetPlayer);
  }

  for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
    $attackCard = $ChainLinks->GetLink($i)->GetLinkCard(0)->ID();
    CombatChainHitEffect($attackCard, $sourceID, $targetPlayer);
  }
}

function LayerStepPower() { //calculates the modified power of an attack in the layer step
  global $currentTurnEffects, $Stack, $mainPlayer;
  if (!IsLayerStep()) return 0;
  $power = 0;
  $cardID = $Stack->BottomLayer()->ID();
  for ($j = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $j >= 0; $j -= CurrentTurnEffectsPieces()) {
    if (IsCombatEffectActive($currentTurnEffects[$j], $cardID)) {
      if ($currentTurnEffects[$j + 1] == $mainPlayer) {
        $power += EffectPowerModifier($currentTurnEffects[$j]);
      }
    }
  }  
  $power += ModifiedPowerValue($cardID, $mainPlayer, "LAYER");
  return $power;
}