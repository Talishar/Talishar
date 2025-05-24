<?php

function SEAAbilityType($cardID, $from="-"): string
{
  return match ($cardID) {
    "peg_leg" => "A",
    "blue_sea_tricorn" => "A",
    "buccaneers_bounty" => "A",
    "fish_fingers" => "A",
    "patch_the_hole" => "I",
    "gold_baited_hook" => "A",
    "glidewell_fins" => "A",
    "sawbones_dock_hand_yellow" => "I",
    "rust_belt" => "I",
    "unicycle" => "I",
    "head_stone" => "I",
    "gravy_bones_shipwrecked_looter" => "I",
    "gravy_bones" => "I",
    "chum_friendly_first_mate_yellow" => "I",
    "moray_le_fay_yellow" => "I",
    "shelly_hardened_traveler_yellow" => "I",
    "kelpie_tangled_mess_yellow" => "A",
    "chowder_hearty_cook_yellow" => "I",
    "cutty_shark_quick_clip_yellow" => "A",
    "scooba_salty_sea_dog_yellow" => $from == "PLAY" ? "AA": "A",
    "wailer_humperdinck_yellow" => $from == "PLAY" ? "AA": "A",
    "riggermortis_yellow" => $from == "PLAY" ? "AA" : "A",  
    "swabbie_yellow" => $from == "PLAY" ? "AA" : "A",
    "limpit_hop_a_long_yellow" => $from == "PLAY" ? "AA" : "A",
    "barnacle_yellow" => $from == "PLAY" ? "AA" : "A",
    "oysten_heart_of_gold_yellow" => $from == "PLAY" ? "AA" : "A",
    "compass_of_sunken_depths" => "I",
    "dead_threads" => "I",
    "sealace_sarong" => "I",

    "puffin_hightail" => "A",
    "puffin" => "A",
    "spitfire" => "AA",
    "cogwerx_blunderbuss" => "I",
    
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => $from == "PLAY" ? "I": "AA",
    "cloud_skiff_red", "cloud_skiff_yellow", "cloud_skiff_blue" => $from == "PLAY" ? "I": "AA",
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => $from == "PLAY" ? "I": "AA",
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red" => $from == "PLAY" ? "I": "AA",
    "cogwerx_zeppelin_red", "cogwerx_zeppelin_yellow", "cogwerx_zeppelin_blue" => $from == "PLAY" ? "I": "AA",
    "polly_cranka", "polly_cranka_ally" => "A",
    "sticky_fingers", "sticky_fingers_ally" => "AA",
    "redspine_manta" => "A",
    "marlynn_treasure_hunter" => "A",
    "marlynn" => "A",
    "hammerhead_harpoon_cannon" => "A",

    "diamond_amulet_blue", "opal_amulet_blue",  "platinum_amulet_blue", "ruby_amulet_blue", "amethyst_amulet_blue" => "I",
    "onyx_amulet_blue", "pearl_amulet_blue", "pounamu_amulet_blue", "sapphire_amulet_blue"=> "A",
    "rally_the_coast_guard_red", "rally_the_coast_guard_yellow", "rally_the_coast_guard_blue" => $from == "PLAY" ? "I" : "AA",

    "goldkiss_rum" => "I",
    "scurv_stowaway" => "A",
    "bandana_of_the_blue_beyond", "swiftstrike_bracers", "quick_clicks", "captains_coat", "quartermasters_boots" => "A",
    "old_knocker" => "I",
    default => ""
  };
}

function SEAAbilityCost($cardID): int
{
  return match ($cardID) {
    "cogwerx_blunderbuss" => GetResolvedAbilityType($cardID) == "AA" ? 2 : 0,
    "wailer_humperdinck_yellow" => 6,
    "riggermortis_yellow" => 1,
    "swabbie_yellow" => 2,
    "limpit_hop_a_long_yellow" => 1,
    "peg_leg" => 3,
    "blue_sea_tricorn" => 3,
    "fish_fingers" => 1,
    "glidewell_fins" => 1,
    "scooba_salty_sea_dog_yellow" => 3,
    "hammerhead_harpoon_cannon" => 4,
    "sawbones_dock_hand_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "AA" ? 1 : 0,
    "cutty_shark_quick_clip_yellow" => 1,
    "moray_le_fay_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "I" ? 1 : 0,
    "shelly_hardened_traveler_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "I" ? 0 : 3,
    "kelpie_tangled_mess_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "A" ? 1 : 0,
    "quartermasters_boots" => 2,
    default => 0
  };
}

function SEAAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "limpit_hop_a_long_yellow" => true,
    "peg_leg" => true,
    "blue_sea_tricorn" => true,
    "buccaneers_bounty" => true,
    "fish_fingers" => true,
    "gold_baited_hook" => true,
    "redspine_manta" => true,
    "marlynn_treasure_hunter" => true,
    "scurv_stowaway" => true,
    "marlynn" => true,
    "glidewell_fins" => true,
    "hammerhead_harpoon_cannon" => true,
    "bandana_of_the_blue_beyond" => true,
    "captains_coat", "swiftstrike_bracers", "quick_clicks", "old_knocker", "quartermasters_boots" => true,
    "onyx_amulet_blue", "pearl_amulet_blue", "pounamu_amulet_blue" => true,
    "kelpie_tangled_mess_yellow" => GetResolvedAbilityType($cardID) == "A",
    "cutty_shark_quick_clip_yellow" => GetResolvedAbilityType($cardID) == "A",
    default => false,
  };
}

function SEAEffectPowerModifier($cardID): int
{
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  return match ($cardID) {
    "yo_ho_ho_blue" => 1,
    "regain_composure_blue" => 1,
    "angry_bones_red", "angry_bones_yellow", "angry_bones_blue" => 1,
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => 1,
    "cloud_skiff_red", "cloud_skiff_yellow", "cloud_skiff_blue" => 1,
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => 1,
    "cogwerx_zeppelin_red", "cogwerx_zeppelin_yellow", "cogwerx_zeppelin_blue" => 1,
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red" => 1,
    "draw_back_the_hammer_red", "perk_up_red", "tighten_the_screws_red" => 4,
    "mutiny_on_the_battalion_barque_blue" => 2,
    "goldwing_turbine_red" => 3,
    "goldwing_turbine_yellow" => 2, 
    "goldwing_turbine_blue" => 1,
    "call_in_the_big_guns_red" => 3,
    "call_in_the_big_guns_yellow" => 2,
    "call_in_the_big_guns_blue" => 1,
    "spitfire" => 1,
    "dry_powder_shot_red" => 2,
    "gold_hunter_longboat_yellow" => 2,
    "glidewell_fins" => 1,
    "hook_blue" => 1,
    "drop_the_anchor_red" => 3,
    "cutty_shark_quick_clip_yellow" => 1,
    "big_game_trophy_shot_yellow" => 4,
    "fire_in_the_hole_red" => 3,
    "monkey_powder_red" => 1,
    "return_fire_red" => 3,
    "fish_fingers" => 1,
    "gold_the_tip_yellow" => 3,
    "amethyst_amulet_blue" => 2,
    "flying_high_red" => ColorContains($attackID, 1, $mainPlayer) ? 1 : 0,
    "flying_high_yellow" => ColorContains($attackID, 2, $mainPlayer) ? 1 : 0,
    "flying_high_blue"  => ColorContains($attackID, 3, $mainPlayer) ? 1 : 0,
    "hammerhead_harpoon_cannon" => SubtypeContains($attackID, "Arrow", $mainPlayer) ? 4 : 0,
    "chart_a_course_red" => 3,
    "chart_a_course_yellow" => 2,
    "chart_a_course_blue" => 1,
    "swiftstrike_bracers" => 2,
    "crash_down_the_gates_red", "crash_down_the_gates_yellow", "crash_down_the_gates_blue" => 2,
    "jack_be_nimble_red", "jack_be_quick_red" => 1,
    default => 0,
  };
}

function SEACombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match ($cardID) {
    "peg_leg" => true,
    // pirate is inconsistently classed as a talent or a class leave it like this until it gets cleaned up
    "gold_baited_hook" => ClassContains($attackID, "PIRATE", $mainPlayer) || TalentContains($attackID, "PIRATE", $mainPlayer),
    "regain_composure_blue" => true,
    "board_the_ship_red" => true,
    "hoist_em_up_red" => true,
    "fish_fingers" => true,
    "gold_hunter_longboat_yellow" => true,
    "mutiny_on_the_battalion_barque_blue", "mutiny_on_the_nimbus_sovereign_blue", "mutiny_on_the_swiftwater_blue" => true,
    "angry_bones_red", "angry_bones_yellow", "angry_bones_blue" => true,
    "burly_bones_red", "burly_bones_yellow", "burly_bones_blue" => true,
    "jittery_bones_red", "jittery_bones_yellow", "jittery_bones_blue" => true,
    "restless_bones_red", "restless_bones_yellow", "restless_bones_blue" => true,
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => true,
    "cloud_skiff_red", "cloud_skiff_yellow", "cloud_skiff_blue" => true,
    "hook_blue", "line_blue", "sinker_blue" => true,
    "dry_powder_shot_red" => true,
    "swift_shot_red" => true,
    "amethyst_amulet_blue" => true,
    "jack_be_nimble_red", "jack_be_quick_red" => true,
    "sky_skimmer_red-GOAGAIN", "sky_skimmer_yellow-GOAGAIN", "sky_skimmer_blue-GOAGAIN" => true,
    "cloud_skiff_red-GOAGAIN", "cloud_skiff_yellow-GOAGAIN", "cloud_skiff_blue-GOAGAIN" => true,
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => true,
    "cogwerx_zeppelin_red", "cogwerx_zeppelin_yellow", "cogwerx_zeppelin_blue" => true,
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red", "cogwerx_dovetail_red-GOAGAIN" => true,
    "draw_back_the_hammer_red", "perk_up_red", "tighten_the_screws_red" => ClassContains($attackID, "MECHANOLOGIST", $mainPlayer),
    "goldwing_turbine_red", "goldwing_turbine_yellow", "goldwing_turbine_blue" => ClassContains($attackID, "MECHANOLOGIST", $mainPlayer),
    "jolly_bludger_yellow-OP" => true,
    "avast_ye_blue", "heave_ho_blue", "yo_ho_ho_blue" => (ClassContains($attackID, "PIRATE", $mainPlayer) || TalentContains($attackID, "PIRATE", $mainPlayer)) && SubtypeContains($attackID, "Ally", $mainPlayer),
    "cutty_shark_quick_clip_yellow" => SubtypeContains($attackID, "Ally", $mainPlayer),
    "cogwerx_blunderbuss" => $attackID == "cogwerx_blunderbuss",
    "spitfire" => true,
    "return_fire_red" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "call_in_the_big_guns_red", "call_in_the_big_guns_yellow", "call_in_the_big_guns_blue" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "big_game_trophy_shot_yellow" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "gold_the_tip_yellow" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "drop_the_anchor_red" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "flying_high_red", "flying_high_yellow", "flying_high_blue" => true,
    "hammerhead_harpoon_cannon", "fire_in_the_hole_red", "monkey_powder_red" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "sealace_sarong" => true,
    "goldkiss_rum" => true,
    "chart_a_course_red", "chart_a_course_yellow", "chart_a_course_blue" => true,
    "swiftstrike_bracers", "quick_clicks" => true,
    "crash_down_the_gates_red", "crash_down_the_gates_yellow", "crash_down_the_gates_blue" => true,
    default => false,
  };
}

function SEAPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $combatChainState, $CCS_RequiredEquipmentBlock, $combatChain, $CombatChain, $landmarks, $CS_DamagePrevention;
  global $CS_PlayIndex, $CS_NumAttacks, $CS_NextNAACardGoAgain, $defPlayer, $combatChainState, $CCS_CachedTotalPower;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    // Generic cards
    case "regain_composure_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "tit_for_tat_blue":
      $inds = GetUntapped($otherPlayer, "MYCHAR", "type=C");
      if(!empty($inds)) {
        AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "You may tap a hero");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, $inds);
        AddDecisionQueue("MZTAP", $otherPlayer, "1", 1);
      }
      $inds = GetTapped($currentPlayer, "MYCHAR", "type=C");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap a hero");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      break;
    case "blow_for_a_blow_red":
      if(PlayerHasLessHealth($currentPlayer)) { GiveAttackGoAgain(); $rv = "Gains go again"; }
      return $rv;
    case "amethyst_amulet_blue":
      if($from == "PLAY") AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      break;
    case "onyx_amulet_blue":
      if($from == "PLAY") {
        Tap("MYCHAR-0", $currentPlayer);
        Tap("THEIRCHAR-0", $otherPlayer);
        AddDecisionQueue("TAPALL", $currentPlayer, "MYALLY&THEIRALLY", 1);
      }
      break;
    case "opal_amulet_blue":
      if($from == "PLAY") Opt($cardID, 2);
      break;
    case "pearl_amulet_blue":
      if($from == "PLAY") {
        $inds = GetTapped($currentPlayer, "MYITEMS&THEIRITEMS&MYCHAR&THEIRCHAR&MYALLY&THEIRALLY");   
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Untap a permanent", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds, 1);
        AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      }
      break;
    case "platinum_amultet_blue":
      if($from == "PLAY") {
        $targetCard = GetMZCard($currentPlayer, $target);
        $targetInd = explode("-", $target)[1];
        if (TypeContains($targetCard, "E")) {
          // I'm going to assume that a player can't have two copies of the same blocking equipment
          AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID:$combatChain[$targetInd]);
        }
        else {
          CombatChainDefenseModifier($targetInd, 1);
        }
      }
      return "";
    case "pounamu_amulet_blue":
      if($from == "PLAY") GainHealth(2, $currentPlayer);
      break;
    case "ruby_amulet_blue":
      if($from == "PLAY") GainResources($currentPlayer, 2);
      break;
    case "sapphire_amulet_blue":
      if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "divvy_up_blue":
      $treasureID = SearchLandmarksForID("treasure_island");
      $char = GetPlayerCharacter($currentPlayer);
      if ($treasureID != -1) {
        ClassContains($char[0], "Thief", $currentPlayer) ? $numGold = $landmarks[$treasureID + 3] : round($landmarks[$treasureID + 3] / 2);
        $landmarks[$treasureID + 3] -= $numGold;
        PutItemIntoPlayForPlayer("gold", $currentPlayer, number:$numGold, isToken:true);
        WriteLog("Player $currentPlayer plundered $numGold " . CardLink("gold", "gold") . " from " . CardLink("treasure_island", "treasure_island"));
      }
      break;
    case "mutiny_on_the_battalion_barque_blue":
    case "mutiny_on_the_nimbus_sovereign_blue":
    case "mutiny_on_the_swiftwater_blue":
      $myNumGold = CountItem("gold", $currentPlayer);
      $theirNumGold = CountItem("gold", $otherPlayer);
      if ($myNumGold < $theirNumGold) {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRITEMS:type=T;cardID=gold");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $currentPlayer, "GAINCONTROL", 1);
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    case "thievn_varmints_red":
      AddDecisionQueue("YESNO", $currentPlayer, "if you want to remove a gold counter from " . CardLink("treasure_island", "treasure_island"), 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("REMOVETREASUREISLANDCOUNTER", $currentPlayer, 1, 1);
      break;
    case "peg_leg":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "rust_belt":
      GainResources($currentPlayer, 1);
      break;
    case "unicycle":
      $inds = GetTapped($currentPlayer, "MYITEMS", "subtype=Cog");   
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap a cog you control");
      //technically should be a MAYCHOOSEMULTIZONE but for playerMacro we make it so it skips the step if there is 1 choice
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      break;
    case "head_stone":
        $deck = new Deck($currentPlayer);
        if($deck->Empty()) {
          break;
        }
        DestroyTopCard($currentPlayer);
      break;
    case "blue_sea_tricorn":
      Draw($currentPlayer);
      break;
    case "buccaneers_bounty":
      GainResources($currentPlayer, 1);
      break;
    case "fish_fingers":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "patch_the_hole":
      MZMoveCard($currentPlayer, "MYARS", "MYHAND", silent: true);
      break;
    case "gold_baited_hook":
    case "avast_ye_blue":
    case "heave_ho_blue":
    case "yo_ho_ho_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "flying_high_red": case "flying_high_yellow": case "flying_high_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "portside_exchange_blue":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
      AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-$currentPlayer", 1);
      AddDecisionQueue("ALLCARDCOLORORPASS", $currentPlayer, "2", 1);
      AddDecisionQueue("PLAYITEM", $currentPlayer, "gold", 1);
      AddDecisionQueue("DRAW", $currentPlayer, $cardID);
      break;
    case "expedition_to_azuro_keys_red":
    case "expedition_to_blackwater_strait_red":
    case "expedition_to_dreadfall_reach_red":
    case "expedition_to_horizons_mantle_red":
      $treasureID = SearchLandmarksForID("treasure_island");
      if ($treasureID != -1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to put a gold counter on for " . CardLink("treasure_island", "treasure_island") . "?");
        AddDecisionQueue("YESNO", $currentPlayer, "-");
        AddDecisionQueue("NOPASS", $currentPlayer, "-");
        AddDecisionQueue("ADDCOUNTERLANDMARK", $currentPlayer, $treasureID, 1);
      }
      break;
    case "loan_shark_yellow":
      PutItemIntoPlayForPlayer("gold", $currentPlayer, number: 2, effectController: $currentPlayer);
      break;
    // Gravy cards
    case "gravy_bones_shipwrecked_looter":
    case "gravy_bones":
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
      break;
    case "dead_threads":
      GainResources($currentPlayer, 1);
      break;
    case "blood_in_the_water_red":
      AddLayer("TRIGGER", $currentPlayer, $cardID, SearchCombatChainForIndex($cardID, $currentPlayer));
      break;
    case "sea_floor_salvage_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRDISCARD&MYDISCARD");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to turn face-down");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "TURNDISCARDFACEDOWN", 1);
      AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SUNKENTREASURE", 1);
      break;
    case "scrub_the_deck_blue":
      DestroyTopCardTarget($currentPlayer, true);
      AddDecisionQueue("ALLCARDCOLORORPASS", $currentPlayer, "2", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "gold", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
      break;
    case "golden_tipple_red":
    case "golden_tipple_yellow":
    case "golden_tipple_blue":
    case "swindlers_grift_red":
    case "swindlers_grift_yellow":
    case "swindlers_grift_blue":
      MZMoveCard($currentPlayer, "MYHAND:pitch=2", "MYDISCARD", true);
      AddDecisionQueue("ALLCARDCOLORORPASS", $currentPlayer, "2", 1);
      AddDecisionQueue("DRAW", $currentPlayer, $cardID, 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "gold", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
      break;
    case "gold_hunter_lightsail_yellow":
      $myNumGold = CountItem("gold", $currentPlayer);
      $theirNumGold = CountItem("gold", $otherPlayer);
      if ($myNumGold < $theirNumGold) {
        GiveAttackGoAgain();
      }
      break;
    case "angry_bones_red": case "angry_bones_yellow": case "angry_bones_blue":
    case "burly_bones_red": case "burly_bones_yellow": case "burly_bones_blue":
    case "jittery_bones_red": case "jittery_bones_yellow": case "jittery_bones_blue":
    case "restless_bones_red": case "restless_bones_yellow": case "restless_bones_blue":
      $hand = &GetHand($currentPlayer);
      $handCount = count($hand);
      if ($handCount == 0) {
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYDECK-0", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to discard from the top of your deck (or pass)", 1);
      } 
      else {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND", 1);
        AddDecisionQueue("APPENDLASTRESULT", $currentPlayer, ",MYDECK-0", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to discard from your hand or top of your deck (or pass)", 1);
      }
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZSETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("WRITELOG", $currentPlayer, "Card chosen: <0>", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "MYDISCARD", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ALLCARDWATERYGRAVEORPASS", $currentPlayer, "<-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $cardID, 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "chum_friendly_first_mate_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $target);
      break;
    case "chowder_hearty_cook_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") GainHealth(1, $currentPlayer);
      break;
    case "cutty_shark_quick_clip_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "A") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "sawbones_dock_hand_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "scooba_salty_sea_dog_yellow":
      if ($from == "PLAY") {
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRDISCARD:pitch=2&MYDISCARD:pitch=2", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target yellow card", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZSETDQVAR", $currentPlayer, "1", 1);
        AddDecisionQueue("WRITELOGCARDLINK", $currentPlayer, "{1}", 1);
        AddDecisionQueue("MZBOTTOM", $currentPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "gold", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "🪙" . CardLink($cardID, $cardID). " found some sunken treasure!", 1);
      }
      break;
    case "moray_le_fay_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") {
        $targetPlayer = explode("-", $target)[0] == "MYALLY" ? $currentPlayer : $otherPlayer;
        $targetUid = explode("-", $target)[1];
        $allyInd = SearchAlliesForUniqueID($targetUid, $targetPlayer);
        $allies = &GetAllies($targetPlayer);
        $allies[$allyInd + 9]++;
      }
      break;
    case "shelly_hardened_traveler_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    case "kelpie_tangled_mess_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "A") {
        $zone = explode("-", $target)[0];
        $targetUid = explode("-", $target)[1];
        if (str_contains($target, "ALLY")) {
          $targetPlayer = $zone == "MYALLY" ? $currentPlayer : $otherPlayer;
          $allyInd = SearchAlliesForUniqueID($targetUid, $targetPlayer);
          Tap("$zone-$allyInd", $currentPlayer);
        }
        else {
          $targetPlayer = $zone == "MYCHAR" ? $currentPlayer : $otherPlayer;
          $charInd = SearchCharacterForUniqueID($targetUid, $targetPlayer);
          $zone = $zone == "THEIRCHARUID" ? "THEIRCHAR": $zone;
          Tap("$zone-$charInd", $currentPlayer);
        }
      }
      break;
    case "clap_em_in_irons_blue":
      $zone = explode("-", $target)[0];
      $targetUid = explode("-", $target)[1];
      if (str_contains($target, "ALLY")) {
        $targetPlayer = $zone == "MYALLY" ? $currentPlayer : $otherPlayer;
        $allyInd = SearchAlliesForUniqueID($targetUid, $targetPlayer);
        Tap("$zone-$allyInd", $currentPlayer);
      }
      else {
        $targetPlayer = $zone == "MYCHAR" ? $currentPlayer : $otherPlayer;
        $charInd = SearchCharacterForUniqueID($targetUid, $targetPlayer);
        $zone = $zone == "THEIRCHARUID" ? "THEIRCHAR": $zone;
        Tap("$zone-$charInd", $currentPlayer);
      }
      AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $targetUid);
      AddNextTurnEffect($cardID, $otherPlayer, uniqueID: $targetUid);
      break;
    case "compass_of_sunken_depths":
      LookAtTopCard($currentPlayer, $cardID, setPlayer: $currentPlayer);
      break;
    case "paddle_faster_red":
      $inds = GetUntapped($currentPlayer, "MYALLY");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "choose an ally to tap (or pass)");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $inds, 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
      AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "board_the_ship_red":
      $inds = GetUntapped($currentPlayer, "MYALLY");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "choose an ally to tap (or pass)");
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $inds, 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "chart_the_high_seas_blue":
      $deck = GetDeck($currentPlayer);
      $foundBlues = [];
      $topTwo = [];
      for ($i = 0; $i < 2; ++$i) {
        $val = CardLink($deck[$i], $deck[$i]);
        array_push($topTwo, $val);
        if (ColorContains($deck[$i], 3, $currentPlayer)) array_push($foundBlues, $val);
      }
      $foundBlues = implode(" and ", $foundBlues);
      $topTwo = implode(" and ", $topTwo);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, CardName($cardID) . " shows the top two cards of your deck are $topTwo", 1);
      AddDecisionQueue("OK", $currentPlayer, "-", 1);
      if ($foundBlues > 0){
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "would you like to pitch a blue card from among $foundBlues?");
        AddDecisionQueue("YESNO", $currentPlayer, "");
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "chart_the_high_seas_blue_1,2,NOPASS", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CHARTTHEHIGHSEAS", 1);
        AddDecisionQueue("ELSE", $currentPlayer, "-");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CHARTTHEHIGHSEAS", 1);
      }
      else AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CHARTTHEHIGHSEAS");
      break;
    case "hook_blue": case "line_blue": case "sinker_blue":
      $deck = GetDeck($currentPlayer);
      $topCard = $deck[0];
      if(SubtypeContains($topCard, "Arrow", $currentPlayer)) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put " . CardLink($topCard, $topCard) . " face-up in your arsenal");
        AddDecisionQueue("YESNO", $currentPlayer, "");
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENAL", $currentPlayer, "DECK-UP", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("LASTARSENALADDEFFECT", $currentPlayer, $cardID . ",DECK", 1);
      }
      else{
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, CardLink($cardID, $cardID) . " shows the top card of your deck is " . CardLink($topCard, $topCard), 1);
        AddDecisionQueue("OK", $currentPlayer, "-", 1);
      }
      break;
    case "diamond_amulet_blue":
      if($from == "PLAY") GainActionPoints(1, $currentPlayer);
      break;
    case "give_no_quarter_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    // Puffin cards
    case "puffin_hightail":
    case "puffin":
      PutItemIntoPlayForPlayer("golden_cog", $currentPlayer, isToken: true);
      break;
    case "polly_cranka": case "polly_cranka_ally":
      $index = SearchBanishForCard($currentPlayer, "polly_cranka");
      if ($index != -1) {
        PlayAlly("polly_cranka_ally", $currentPlayer, tapped:true, from:$from);
        RemoveBanish($currentPlayer, $index);
      }
      break;
    case "sticky_fingers": case "sticky_fingers_ally":
      if ($cardID == "sticky_fingers") PlayAlly("sticky_fingers_ally", $currentPlayer, tapped:true, from:$from);
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRITEMS:type=T;cardID=gold");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "GAINCONTROL", 1);
      break;
    case "cogwerx_blunderbuss":
      if (GetResolvedAbilityType($cardID) == "I") {
        AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      }
      break;
    case "cogwerx_workshop_blue":
      PutItemIntoPlayForPlayer("golden_cog", $currentPlayer);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "");
      for ($i = 0; $i < 2; ++$i) {
        //this indices function removes already selected cogs
        AddDecisionQueue("COGWERXINDICES", $currentPlayer, "", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a cog to put a steam counter", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $currentPlayer, "-", 1);
      }
      break;
    case "spitfire":
      $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Tap a cog to buff ".CardLink($cardID, $cardID));
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, $inds, 1);
      AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "cog_in_the_machine_red":
      PutItemIntoPlayForPlayer("golden_cog", $currentPlayer, number:2, isToken: true);
      $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may tap a cog you control (or pass)");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
      AddDecisionQueue("GOESWHERE", $currentPlayer, $cardID.",".$from.",MYBOTDECK", 1);
      AdddecisionQueue("ELSE", $currentPlayer, "-");
      AddDecisionQueue("GOESWHERE", $currentPlayer, $cardID.",".$from.",DISCARD", 1);
      break;
    case "draw_back_the_hammer_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $inds = GetTapped($currentPlayer, "MYCHAR", "subtype=Gun");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap a gun you control");
      //technically should be a MAYCHOOSEMULTIZONE but for playerMacro we make it so it skips the step if there is 1 choice
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      break;
    case "perk_up_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $inds = GetTapped($currentPlayer, "MYCHAR", "type=C");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap your hero");
      //technically should be a MAYCHOOSEMULTIZONE but for playerMacro we make it so it skips the step if there is 1 choice
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      break;
    case "tighten_the_screws_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $inds = GetTapped($currentPlayer, "MYITEMS", "subtype=Cog");   
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap a cog you control");
      //technically should be a MAYCHOOSEMULTIZONE but for playerMacro we make it so it skips the step if there is 1 choice
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      break;
    case "goldwing_turbine_red":
    case "goldwing_turbine_yellow":
    case "goldwing_turbine_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      PutItemIntoPlayForPlayer("golden_cog", $currentPlayer);
      break;
    case "lubricate_blue":
      $inds = GetTapped($currentPlayer, "MYITEMS", "subtype=Cog");   
      if(empty($inds)) break;
      $indices = explode(",", $inds);
      $maxCogs = count($indices) >= 3 ? 3 : count($indices);
      for ($i = 0; $i < count($indices); $i++) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap ".($maxCogs-$i)." cogs you control");
        //technically should be a MAYCHOOSEMULTIZONE but for playerMacro we make it so it skips the step if there is 1 choice
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
        AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      }
      break;
    case "sky_skimmer_red":
    case "sky_skimmer_yellow":
    case "sky_skimmer_blue":
    case "cloud_skiff_red":
    case "cloud_skiff_yellow":
    case "cloud_skiff_blue":
      if ($from == "PLAY") {
        AddDecisionQueue("BUTTONINPUTNOPASS", $currentPlayer, "+1 Power,Go Again");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "COGCONTROL-".$cardID, 1);
      }
      break;
    case "palantir_aeronought_red":
      if($from != "PLAY" && !IsAllyAttackTarget()) $combatChainState[$CCS_RequiredEquipmentBlock] = 1;
      elseif($from == "PLAY") {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $numResolved = CountCurrentTurnEffects($cardID, $currentPlayer);
        //technically inaccurate, but should be functionally mostly the same
        if ($numResolved == 3) {
          AddDecisionQueue("SEARCHCOMBATCHAIN", $currentPlayer, "-");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card to destroy");
          AddDecisionQueue("CHOOSECARDID", $currentPlayer, "<-", 1);
          AddDecisionQueue("SPECIFICCARD", $currentPlayer, "AERONOUGHT", 1);
        }
      }
      return "";
    case "jolly_bludger_yellow":
      if ($from != "PLAY") {
        $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
        if(empty($inds)) break;
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Tap a cog to gain overpower");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, $inds, 1);
        AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, "jolly_bludger_yellow-OP", 1);
      }
      else AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "cogwerx_dovetail_red":
      if ($from == "PLAY") {
        AddDecisionQueue("BUTTONINPUTNOPASS", $currentPlayer, "+1 Power,Go Again");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "COGCONTROL-".$cardID, 1);
      }
      return "";
    case "cloud_city_steamboat_red":
    case "cloud_city_steamboat_yellow":
    case "cloud_city_steamboat_blue":
    case "cogwerx_zeppelin_red":
    case "cogwerx_zeppelin_yellow":
    case "cogwerx_zeppelin_blue":
      if ($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "scurv_stowaway":
      PutItemIntoPlayForPlayer("goldkiss_rum", $currentPlayer);
      break;
    // Marlynn cards
    case "redspine_manta":
      LoadArrow($currentPlayer);
      return "";
    case "sealace_sarong":
      $arsenal = GetArsenal($currentPlayer);
      AddCurrentTurnEffect($cardID, $currentPlayer, "", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
      break;
    case "marlynn_treasure_hunter":
    case "marlynn":
      AddPlayerHand("goldfin_harpoon_yellow", $currentPlayer, $cardID);
      break;
    case "gold_the_tip_yellow":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if(ArsenalHasArrowFacingColor($currentPlayer, "UP", 2)) PutItemIntoPlayForPlayer("gold", $currentPlayer);
      break;
    case "hammerhead_harpoon_cannon":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "big_game_trophy_shot_yellow":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
      break;
    case "glidewell_fins":
      LoadArrow($currentPlayer);
      AddDecisionQueue("LASTARSENALADDEFFECT", $currentPlayer, $cardID . ",HAND", 1);
      break;
    case "fire_in_the_hole_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $inds = GetTapped($currentPlayer, "MYCHAR", "subtype=Bow");
      if(empty($inds)) break;
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may untap a bow you control");
      //technically should be a MAYCHOOSEMULTIZONE but for playerMacro we make it so it skips the step if there is 1 choice
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds);
      AddDecisionQueue("MZTAP", $currentPlayer, "0", 1);
      break;
    case "drop_the_anchor_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "monkey_powder_red":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Draw($currentPlayer);
      break;
    case "call_in_the_big_guns_red": 
    case "call_in_the_big_guns_yellow": 
    case "call_in_the_big_guns_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      MZMoveCard($currentPlayer, "MYHAND:subtype=Arrow", "MYARS,HAND,UP", may:true, silent:true);
      break;
      //other cards
    case "tip_the_barkeep_blue":
      PutItemIntoPlayForPlayer("goldkiss_rum", $currentPlayer, isToken: true);
      if(CountItem("gold", $currentPlayer, false) > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Would you like to give a ".CardLink("gold", "gold")." token to your opponent?");
        AddDecisionQueue("YESNO", $currentPlayer, "");
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYITEMS:type=T;cardID=gold", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $otherPlayer, "GAINCONTROL", 1);
        AddDecisionQueue("GOESWHERE", $currentPlayer, $cardID.",".$from.",MYBOTDECK");
      }
      else AddDecisionQueue("GOESWHERE", $currentPlayer, $cardID.",".$from.",DISCARD");
      break;
    case "murderous_rabble_blue":
      $deck = new Deck($currentPlayer);
      if($deck->Empty()) return CardLink($cardID, $cardID). " does not get power because your deck is empty";
      if($deck->Reveal(1)) {
        $top = $deck->Top();
        $pitch = PitchValue($top);
        $CombatChain->AttackCard()->ModifyPower($pitch);
        return "Reveals " . CardLink($top, $top) . " and gets +" . $pitch . " power";
      }
      return CardLink($cardID, $cardID). " does not get power because the reveal was prevented";
    case "saltwater_swell_red":
    case "saltwater_swell_yellow": 
    case "saltwater_swell_blue":
      $deck = new Deck($currentPlayer);
      if($deck->Empty()) break;
      $deck->Reveal(1);
      $top = $deck->Top();
      if(ColorContains($top, 3, $currentPlayer)) {
        Pitch($top, $currentPlayer);
      }
      break;
    case "not_so_fast_yellow":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "throw_caution_to_the_wind_blue":
      $deck = new Deck($currentPlayer);
      if($deck->Empty()) break;
      $deck->Reveal(1);
      $pitchValue = pitchValue($deck->Top());
      AddCurrentTurnEffect($cardID, $currentPlayer);
      IncrementClassState($currentPlayer, $CS_DamagePrevention, $pitchValue);
      WriteLog(CardLink($cardID, $cardID) . " prevents the next $pitchValue damage");
      break;
    case "midas_touch_yellow":
      $targetPlayer = str_contains($target, "MY") ? $currentPlayer : $otherPlayer;
      $uid = explode("-", $target)[1];
      $indexAlly = SearchAlliesForUniqueID($uid, $targetPlayer);
      if ($indexAlly != -1) {
        $allies = GetAllies($targetPlayer);
        $allyCost = CardCost($allies[$indexAlly]);
        PutItemIntoPlayForPlayer("gold", $targetPlayer, number:$allyCost, isToken:true, effectController:$currentPlayer);
        $token = $allyCost > 1 ? " tokens" : " token";
        $allyName = CardLink($allies[$indexAlly], $allies[$indexAlly]);
        WriteLog("Player $targetPlayer's $allyName turned into $allyCost " . CardLink("gold", "gold") . " $token!");
        DestroyAlly($targetPlayer, $indexAlly);
        return "";
      }
      $indexChar = SearchCharacterForUniqueID($uid, $targetPlayer);
      if ($indexChar != -1) {
        $char = GetPlayerCharacter($targetPlayer);
        $charCost = CardCost($char[$indexChar]) >= 0 ? CardCost($char[$indexChar]) : 0;
        PutItemIntoPlayForPlayer("gold", $targetPlayer, number:$charCost, isToken:true, effectController:$currentPlayer);
        $token = $charCost > 1 ? " tokens" : " token";
        $CharName = CardLink($char[$indexChar], $char[$indexChar]);
        WriteLog("Player $targetPlayer's $CharName turned into $charCost " . CardLink("gold", "gold") . " $token!");
        DestroyCharacter($targetPlayer, $indexChar);
        return "";
      }
      else {
        WriteLog(CardLink($cardID, $cardID) . " fizzles due to missing target");
        return "FAILED";
      }
    case "chart_a_course_red":
    case "chart_a_course_yellow":
    case "chart_a_course_blue":
      if (GetClassState($currentPlayer, $CS_NumAttacks) == 0) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      else WriteLog("You've already attacked this turn, no buff");
      $treasureID = SearchLandmarksForID("treasure_island");
      if ($treasureID != -1) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to put a gold counter on for " . CardLink("treasure_island", "treasure_island") . "?");
        AddDecisionQueue("YESNO", $currentPlayer, "-");
        AddDecisionQueue("NOPASS", $currentPlayer, "-");
        AddDecisionQueue("ADDCOUNTERLANDMARK", $currentPlayer, $treasureID, 1);
      }
      break;
    case "nimby_red":
    case "nimby_yellow":
    case "nimby_blue":
      MZMoveCard($currentPlayer, "MYDECK:isSameName=nimblism_red", "MYHAND", may:true, isReveal:true);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      break;
    case "jack_be_nimble_red":
    case "jack_be_quick_red":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:isSameName=nimblism_red");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBANISH,GY,-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $currentPlayer, $cardID, 1);
      AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "rally_the_coast_guard_red": case "rally_the_coast_guard_yellow": case "rally_the_coast_guard_blue":
      if($from == "PLAY") {
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        //Safety in case it loses the index when more cards are played at instant
        if($index == -1) $index = GetCombatChainIndex($cardID, $currentPlayer); 
        CombatChainDefenseModifier($index, 3);
      }
      return "";
    case "bandana_of_the_blue_beyond":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD:color=3");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a blue card to put on the bottom of your deck");
      AddDecisionQueue("MZREMOVE", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDBOTDECK", $currentPlayer, "<-", 1);
      break;
    case "old_knocker":
    case "captains_coat":
      GainResources($currentPlayer, 1);
      break;
    case "swiftstrike_bracers":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "quick_clicks":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "quartermasters_boots":
      SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 1);
      return "";
    case "crash_down_the_gates_red":
    case "crash_down_the_gates_yellow":
    case "crash_down_the_gates_blue":
      if (IsHeroAttackTarget()) {
        $totalPower = 0;
        $totalDefense = 0;
        EvaluateCombatChain($totalPower, $totalDefense);
        $deck = new Deck($defPlayer);
        if($deck->Reveal()) {
          $deckPower = ModifiedPowerValue($deck->Top(), $defPlayer, "DECK", source:$cardID);
        }
        else $deckPower = -1;
        if ($totalPower > $deckPower) {
          WriteLog("Your power exceeds the gates!");
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
      }
      break;
    case "goldkiss_rum":
      if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "burn_bare":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") {
        AddLayer("LAYER", $currentPlayer, "PHANTASM", $combatChain[0], $cardID);
      } else {
        DealArcane(ArcaneDamage($cardID), 2, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      break;
    case "herald_of_sekem_red":
        $indices = SearchHand($currentPlayer, pitch: 2);
        if ($indices == "") break;
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $indices);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to put into your soul", 1);
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-");
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDSOUL", $currentPlayer, "HAND", 1);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARCANETARGET,2", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a target for ".CardLink($cardID, $cardID), 1);
        if (ShouldAutotargetOpponent($currentPlayer) && CountAllies($currentPlayer) <= 0 && CountAllies($otherPlayer) <= 0) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "THEIRCHAR-0", 1);
        }
        else{
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        }
        AddDecisionQueue("DEALARCANE", $currentPlayer, "2" . "-" . $cardID . "-" . "TRIGGER", 1);
      break;
    default:
      break;
  }
  return "";
}

function SEAHitEffect($cardID): void
{
  global $CS_NumCannonsActivated, $mainPlayer, $defPlayer, $combatChain;
  switch ($cardID) {
    //puffin cards
    case "cloud_city_steamboat_red":
    case "cloud_city_steamboat_yellow":
    case "cloud_city_steamboat_blue":
      $inds = GetUntapped($mainPlayer, "MYITEMS", "subtype=Cog");
      if(empty($inds)) break;
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, $inds);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Tap a cog to put a steam counter on a cog (or pass)", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZTAP", $mainPlayer, "<-", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a cog to add a steam counter to", 1);
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYITEMS:subtype=Cog", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer ,"<-", 1);
      AddDecisionQueue("MZADDCOUNTER", $mainPlayer, "-", 1);      
      break;
    case "cogwerx_zeppelin_red":
    case "cogwerx_zeppelin_yellow":
    case "cogwerx_zeppelin_blue":
      $inds = GetUntapped($mainPlayer, "MYITEMS", "subtype=Cog");
      if(empty($inds)) break;
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, $inds);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Tap a cog to create a ".CardLink("golden_cog", "golden_cog")." (or pass)", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZTAP", $mainPlayer, "<-", 1);
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, "golden_cog", 1);
      AddDecisionQueue("PUTPLAY", $mainPlayer, "0", 1);
      break;
    //marlynn cards
    case "king_kraken_harpoon_red":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, non-attack action cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "KINGKRAKENHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, non-attack action cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "KINGKRAKENHARPOON", 1);
      }
      break;
    case "king_shark_harpoon_red":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, attack action cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "KINGSHARKHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, attack action cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "KINGSHARKHARPOON", 1);
      }
      break;
    case "red_fin_harpoon_blue":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, red cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "REDFINHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, red cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "REDFINHARPOON", 1);
      }
      break;
    case "yellow_fin_harpoon_blue":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, yellow cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "YELLOWFINHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, yellow cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "YELLOWFINHARPOON", 1);
      }
      break;
    case "blue_fin_harpoon_blue":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, blue cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "BLUEFINHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, blue cards will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "BLUEFINHARPOON", 1);
      }
      break;
    case "conqueror_of_the_high_seas_red":
      $arsenal = GetArsenal($defPlayer);
      $count = count($arsenal) / ArsenalPieces();
      DestroyArsenal($defPlayer, effectController:$mainPlayer);
      PutItemIntoPlayForPlayer("gold", $mainPlayer, number:$count, effectController:$mainPlayer, isToken:true);
      break;
    case "cogwerx_dovetail_red":
      WriteLog(CardLink($cardID, $cardID) . " untap all the cogs Player " . $mainPlayer . " control.");
      AddDecisionQueue("UNTAPALL", $mainPlayer, "MYITEMS:subtype=Cog", 1);
      break;
    case "hms_barracuda_yellow":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRALLY");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an ally to destroy", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
      break; 
    case "hms_kraken_yellow":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an ally to destroy", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
      break; 
    case "hms_marlin_yellow":   
      DestroyTopCard($defPlayer);
      break; 
    case "pilfer_the_wreck_red":
    case "pilfer_the_wreck_yellow":
    case "pilfer_the_wreck_blue":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRDISCARD");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card to turn face-down");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "TURNDISCARDFACEDOWN", 1);
      AddDecisionQueue("SPECIFICCARD", $mainPlayer, "SUNKENTREASURE", 1);
      break;
    case "strike_gold_red":
    case "strike_gold_yellow":
    case "strike_gold_blue":
      PutItemIntoPlayForPlayer("gold", $mainPlayer, isToken:true);
      break;
    case "walk_the_plank_red":
    case "walk_the_plank_yellow":
    case "walk_the_plank_blue":
      $indices = SearchMultizone($mainPlayer, "THEIRALLY");
      $indices = $indices == "" ? "THEIRCHAR-0" : "$indices,THEIRCHAR-0";
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, $indices);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a hero or ally to tap", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZTAP", $mainPlayer, "<-", 1);
      break;
    case "crash_down_the_gates_red":
    case "crash_down_the_gates_yellow":
    case "crash_down_the_gates_blue":
      DestroyTopCard($defPlayer);
      break;
    case "undercover_acquisition_red":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL", 1);
      break;
    case "jack_be_nimble_red":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL,Temporary", 1);
      break;
    case "jack_be_quick_red":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRALLY");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL,Temporary", 1);
      break;
    case "money_or_your_life_red":
    case "money_or_your_life_yellow":
    case "money_or_your_life_blue":
      AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose if you want to give " . CardLink($cardID, $cardID));
      AddDecisionQueue("BUTTONINPUT", $defPlayer, "Gold,Life");

      AddDecisionQueue("EQUALPASS", $defPlayer, "Life");
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:type=T;cardID=gold", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL", 1);

      AddDecisionQueue("NOTEQUALPASS", $defPlayer, "PASS");
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, 2 . "-" . $combatChain[0] . "-" . "TRIGGER", 1);
      AddDecisionQueue("DEALDAMAGE", $defPlayer, "MYCHAR-0", 1);
      break;
    case "blow_for_a_blow_red":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYCHAR:type=C&THEIRCHAR:type=C&MYALLY&THEIRALLY", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target to deal 1 damage");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDAMAGE", $mainPlayer, "1,DAMAGE," . $cardID, 1);
      break;
    default:
      break;
  }
}

function GetUntapped($player, $zone, $cond="-")
{
  switch ($zone) {
    case "MYCHAR":
      $arr = GetPlayerCharacter($player);
      $count = CharacterPieces();
      break;
    case "MYALLY":
      $arr = GetAllies($player);
      $count = AllyPieces();
      break;
    case "MYITEMS":
      $arr = GetItems($player);
      $count = ItemPieces();
      break;
    default:
      return "";
  }
  $unwavedInds = [];
  $allowedInds = -1;
  $allowedInds = ($cond != "-") ? explode(",", SearchMultizone($player, "$zone:$cond")) : [];
  for ($i = 0; $i < count($arr); $i += $count) {
    $index = "$zone-$i";
    if ($cond != "-" && !in_array($index, $allowedInds)) continue;
    if (!CheckTapped($index, $player)) array_push($unwavedInds, $index);
  }
  return implode(",", $unwavedInds);
}

function GetTapped($player, $zone, $cond="-")
{
  $otherPlayer = $player == 1 ? 2 : 1;
  switch ($zone) {
    case "MYCHAR":
      $arr = GetPlayerCharacter($player);
      $count = CharacterPieces();
      break;
    case "THEIRCHAR":
      $arr = GetPlayerCharacter($otherPlayer);
      $count = CharacterPieces();
      break;
    case "MYALLY":
      $arr = GetAllies($player);
      $count = AllyPieces();
      break;
    case "THEIRALLY":
      $arr = GetAllies($otherPlayer);
      $count = AllyPieces();
      break;
    case "MYITEMS":
      $arr = GetItems($player);
      $count = ItemPieces();
      break;
    case "THEIRITEMS":
      $arr = GetItems($otherPlayer);
      $count = ItemPieces();
      break;
    default:
      return "";
  }
  $unwavedInds = [];
  $allowedInds = -1;
  $allowedInds = ($cond != "-") ? explode(",", SearchMultizone($player, "$zone:$cond")) : [];
  for ($i = 0; $i < count($arr); $i += $count) {
    $index = "$zone-$i";
    if ($cond != "-" && !in_array($index, $allowedInds)) continue;
    if (CheckTapped($index, $player)) array_push($unwavedInds, $index);
  }
  return implode(",", $unwavedInds);
}

function Tap($MZindex, $player, $tapState=1)
{
  global $CS_NumGoldCreated;
  $zoneName = explode("-", $MZindex)[0];
  $otherPlayer = $player == 1 ? 2 : 1;
  $targetPlayer = (str_contains($zoneName, "THEIR")) ? $otherPlayer : $player;
  
  $zone = &GetMZZone($targetPlayer, $zoneName);
  if (!isset(explode("-", $MZindex)[1])) {
    WriteLog("Something odd happened, please submit a bug report");
    return;
  }
  $index = intval(explode("-", $MZindex)[1]);
  //Untap
  if($tapState == 0 && !isUntappedPrevented($MZindex, $zoneName, $targetPlayer)) {
    if($zone[$index] == "gold_baited_hook" && GetClassState($player, piece: $CS_NumGoldCreated) <= 0 && $zone[$index + 14] == 1) DestroyCharacter($player, $index);
    elseif (str_contains($zoneName, "CHAR")) $zone[$index + 14] = $tapState;
    elseif (str_contains($zoneName, "ALLY")) $zone[$index + 11] = $tapState;
    elseif (str_contains($zoneName, "ITEM")) $zone[$index + 10] = $tapState;
  }
  //Tap
  elseif ($tapState == 1) {
    if (str_contains($zoneName, "CHAR")) $zone[$index + 14] = $tapState;
    elseif (str_contains($zoneName, "ALLY")) $zone[$index + 11] = $tapState;
    elseif (str_contains($zoneName, "ITEM")) $zone[$index + 10] = $tapState;
  }
}

function CheckTapped($MZindex, $player): bool
{
  $zoneName = explode("-", $MZindex)[0];
  $zone = &GetMZZone($player, $zoneName);
  $index = intval(explode("-", $MZindex)[1]);
  if (str_contains($zoneName, "CHAR")) return $zone[$index + 14] == 1;
  elseif (str_contains($zoneName, "ALLY")) return $zone[$index + 11] == 1;
  elseif (str_contains($zoneName, "ITEM")) return $zone[$index + 10] == 1;
  return false;
}

function isUntappedPrevented($MZindex, $zoneName, $player): bool
{
  $untapPrevented = false;
  $zoneName = explode("-", $MZindex)[0];
  $index = intval(explode("-", $MZindex)[1]);
  $zone = &GetMZZone($player, $zoneName);
  if(SearchCurrentTurnEffects("goldkiss_rum", $player) && str_contains($zoneName, "CHAR") && !TalentContains(GetMZCard($player, $MZindex), "PIRATE", $player)) {
    return true;
  }
   if (str_contains($zoneName, "CHAR")) SearchCurrentTurnEffects("clap_em_in_irons_blue", $player, returnUniqueID:true) == $zone[$index + 11] ?? $untapPrevented = true;
  elseif (str_contains($zoneName, "ALLY")) SearchCurrentTurnEffects("clap_em_in_irons_blue", $player, returnUniqueID:true) == $zone[$index + 5] ?? $untapPrevented = true;
  return $untapPrevented;
}

function HasWateryGrave($cardID): bool
{
  return match($cardID) {
    "chum_friendly_first_mate_yellow" => true,
    "riggermortis_yellow" => true,
    "swabbie_yellow" => true,
    "limpit_hop_a_long_yellow" => true,
    "barnacle_yellow" => true,
    "oysten_heart_of_gold_yellow" => true,
    "diamond_amulet_blue" => true,
    "sawbones_dock_hand_yellow" => true,
    "chowder_hearty_cook_yellow" => true,
    "wailer_humperdinck_yellow" => true,
    "moray_le_fay_yellow" => true,
    "shelly_hardened_traveler_yellow" => true,
    "kelpie_tangled_mess_yellow" => true,
    "scooba_salty_sea_dog_yellow" => true,
    "cutty_shark_quick_clip_yellow" => true,
    "amethyst_amulet_blue", "onyx_amulet_blue", "opal_amulet_blue", "pearl_amulet_blue", "platinum_amulet_blue",
    "pounamu_amulet_blue", "ruby_amulet_blue", "sapphire_amulet_blue" => true,
    default => false
  };
}

function HasHighTide($cardID): bool
{
  return match($cardID) {
    "conqueror_of_the_high_seas_red" => true,
    "hms_barracuda_yellow", "hms_kraken_yellow", "hms_marlin_yellow" => true,
    "battalion_barque_red", "battalion_barque_yellow", "battalion_barque_blue" => true,
    "swiftwater_sloop_red", "swiftwater_sloop_yellow", "swiftwater_sloop_blue" => true,
    default => false
  };
}

function HighTideConditionMet($player) 
{
  $blueCount = SearchCount(SearchPitch($player, pitch: 3));
  if($blueCount >= 2)
    return true;
  else {
    return false;
  }
}

function HasPerched($cardID): bool
{
  return match($cardID) {
    "polly_cranka" => true,
    "sticky_fingers" => true,
    default => false
  };
}

function UndestroyHook($player)
{
  if (SearchCurrentTurnEffects("gold_baited_hook", $player)) {
    $char = GetPlayerCharacter($player);
    for ($i = 0; $i < count($char); $i += CharacterPieces()) {
      if ($char[$i] == "gold_baited_hook") UndestroyCharacter($player, $i, false);
    }
  }
}

function hasUnlimited($cardID)
{
switch ($cardID) {
  case "copper_cog_blue":
    return true;
  default:
    return false;
  }
}