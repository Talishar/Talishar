<?php

function BotDeckPools()
{
  static $pools = [
    "IraCC" => [
      "hero" => "ira_scarlet_revenger",
      "weapons" => ["edge_of_autumn"],
      "deck" => ["bittering_thorns_red", "bittering_thorns_red", "bittering_thorns_red", "enact_vengeance_red", "enact_vengeance_red",
        "enact_vengeance_red", "flic_flak_red", "flic_flak_red", "flic_flak_red", "flying_kick_red", "flying_kick_red", "flying_kick_red",
        "give_and_take_red", "give_and_take_red", "razor_reflex_red", "razor_reflex_red", "razor_reflex_red", "scar_for_a_scar_red",
        "scar_for_a_scar_red", "scar_for_a_scar_red", "seek_vengeance_red", "seek_vengeance_red", "seek_vengeance_red", "sink_below_red",
        "sink_below_red", "sink_below_red", "snatch_red", "snatch_red", "snatch_red", "soulbead_strike_red", "soulbead_strike_red",
        "soulbead_strike_red", "torrent_of_tempo_red", "torrent_of_tempo_red", "torrent_of_tempo_red", "whirling_mist_blossom_yellow",
        "whirling_mist_blossom_yellow", "whirling_mist_blossom_yellow", "bittering_thorns_blue", "bittering_thorns_blue", "bittering_thorns_blue",
        "energy_potion_blue", "flying_kick_blue", "flying_kick_blue", "flying_kick_blue", "legacy_of_ikaru_blue", "legacy_of_ikaru_blue",
        "legacy_of_ikaru_blue", "seek_vengeance_blue", "seek_vengeance_blue", "seek_vengeance_blue", "soulbead_strike_blue", "soulbead_strike_blue",
        "soulbead_strike_blue", "torrent_of_tempo_blue", "torrent_of_tempo_blue", "torrent_of_tempo_blue", "vengeance_never_rests_blue",
        "vengeance_never_rests_blue", "vengeance_never_rests_blue"],
      "sideboard" => [],
    ],
    "BriarSAGE" => [
      "hero" => "briar",
      "weapons" => ["star_fall"],
      "deck" => ["arcane_seeds__life_red", "arcane_seeds__life_red", "arcanic_shockwave_red", "arcanic_shockwave_red", "burn_up__shock_red",
        "burn_up__shock_red", "entwine_lightning_red", "entwine_lightning_red", "fry_red", "fry_red", "jack_be_nimble_red", "lightning_press_red",
        "lightning_press_red", "lightning_surge_red", "lightning_surge_red", "nimblism_red", "nimblism_red", "nimblism_yellow",
        "path_of_same_ends_red", "path_of_same_ends_red", "quick_succession_blue", "quick_succession_red", "quick_succession_red",
        "ravenous_rabble_red", "ravenous_rabble_red", "rush_of_power_red", "scar_for_a_scar_red", "scar_for_a_scar_red", "second_strike_red",
        "second_strike_red", "sizzle_red", "sizzle_red", "snatch_red", "snatch_red", "sprout_strength_red", "sprout_strength_red",
        "static_shock_red", "static_shock_red", "weave_lightning_red", "weave_lightning_red"],
      "sideboard" => ["cloud_cover_red", "cloud_cover_red", "evergreen_red", "evergreen_red", "quick_succession_blue", "sigil_of_suffering_red",
        "sigil_of_suffering_red"],
    ],
    "BravoSAGE" => [
      "hero" => "bravo_flattering_showman",
      "weapons" => ["titans_fist", "steelbraid_buckler"],
      "deck" => ["boulder_drop_red", "boulder_drop_red", "boulder_drop_blue", "boulder_drop_blue", "cartilage_crush_red", "cartilage_crush_red",
        "cartilage_crush_blue", "cartilage_crush_blue", "crash_and_bash_red", "crash_and_bash_red", "debilitate_red", "debilitate_red",
        "debilitate_blue", "debilitate_blue", "fault_line_red", "fault_line_red", "clash_of_heads_yellow", "clash_of_heads_yellow",
        "buckling_blow_blue", "buckling_blow_blue", "chokeslam_blue", "chokeslam_blue", "clash_of_vigor_blue", "clash_of_vigor_blue",
        "disable_blue", "disable_blue", "edge_of_their_seats_blue", "edge_of_their_seats_blue", "flatten_the_field_blue", "flatten_the_field_blue",
        "macho_grande_blue", "macho_grande_blue", "thunder_quake_blue", "thunder_quake_blue", "thunk_blue", "thunk_blue"],
      "sideboard" => ["arcane_polarity_red", "arcane_polarity_red", "chokeslam_red", "chokeslam_red", "oasis_respite_red", "oasis_respite_red", "pummel_red",
        "pummel_red", "staunch_response_red", "staunch_response_red"],
    ],
    "CindraCC" => [
      "hero" => "cindra_dracai_of_retribution",
      "weapons" => ["kunai_of_retribution", "kunai_of_retribution_r", "claw_of_vynserakai"],
      "deck" => ["ancestral_empowerment_red", "ancestral_empowerment_red", "ancestral_empowerment_red", "art_of_the_dragon_blood_red",
        "art_of_the_dragon_blood_red", "art_of_the_dragon_blood_red", "blaze_headlong_red", "blaze_headlong_red", "blaze_headlong_red",
        "blood_runs_deep_red", "blood_runs_deep_red", "breaking_point_red", "breaking_point_red", "burning_blade_dance_red",
        "burning_blade_dance_red", "burning_blade_dance_red", "cut_through_red", "cut_through_red", "cut_through_red", "demonstrate_devotion_red",
        "demonstrate_devotion_red", "demonstrate_devotion_red", "display_loyalty_red", "display_loyalty_red", "display_loyalty_red",
        "draco_fire_red", "draco_fire_red", "draco_fire_red", "enflame_the_firebrand_red", "fire_tenet_strike_first_red",
        "fire_tenet_strike_first_red", "fire_tenet_strike_first_red", "hot_on_their_heels_red", "hot_on_their_heels_red", "hot_on_their_heels_red",
        "hunt_the_hunter_red", "hunt_the_hunter_red", "ignite_red", "ignite_red", "ignite_red", "lava_burst_red", "lava_burst_red",
        "oath_of_loyalty_red", "oath_of_loyalty_red", "ravenous_rabble_red", "ravenous_rabble_red", "ravenous_rabble_red", "rising_resentment_red",
        "rising_resentment_red", "rising_resentment_red", "ronin_renegade_red", "ronin_renegade_red", "ronin_renegade_red", "snatch_red",
        "snatch_red", "tag_the_target_red", "tag_the_target_red", "tag_the_target_red", "throw_dagger_blue", "throw_dagger_blue"],
      "sideboard" => ["hunt_to_the_ends_of_rathe_red", "hunt_to_the_ends_of_rathe_red", "hunt_to_the_ends_of_rathe_red", "overcrowded_blue",
        "shelter_from_the_storm_red", "shelter_from_the_storm_red", "shelter_from_the_storm_red", "sink_below_red", "sink_below_red",
        "sink_below_red", "warmongers_diplomacy_blue", "warmongers_diplomacy_blue"],
    ],
    "HalaCC" => [
      "hero" => "hala_bladesaint_of_the_vow",
      "weapons" => ["zenith_blade"],
      "deck" => ["brimming_blade_red", "brimming_blade_red", "brimming_blade_red", "command_and_conquer_red", "command_and_conquer_red",
        "command_and_conquer_red", "edict_of_steel_red", "edict_of_steel_red", "edict_of_steel_red", "swordmasters_shine_red",
        "swordmasters_shine_red", "swordmasters_shine_red", "toe_the_line_red", "toe_the_line_red", "toe_the_line_red", "drawn_to_the_blade_yellow",
        "drawn_to_the_blade_yellow", "drawn_to_the_blade_yellow", "edict_of_steel_yellow", "edict_of_steel_yellow", "edict_of_steel_yellow",
        "point_of_escalation_yellow", "point_of_escalation_yellow", "point_of_escalation_yellow", "and_again_blue", "and_again_blue",
        "and_again_blue", "beckon_steel_blue", "beckon_steel_blue", "beckon_steel_blue", "cut_n_carve_blue", "cut_n_carve_blue", "cut_n_carve_blue",
        "display_of_craftsmanship_blue", "edict_of_steel_blue", "edict_of_steel_blue", "edict_of_steel_blue", "ole_blue", "ole_blue", "ole_blue",
        "overcrowded_blue", "provoke_blue", "provoke_blue", "provoke_blue", "ripple_away_blue", "ripple_away_blue", "ripple_away_blue",
        "sharp_incline_blue", "sharp_incline_blue", "sharp_incline_blue", "shove_off_blue", "shove_off_blue", "big_blinder_red", "showdown_red",
        "showdown_red", "swordmasters_path_red", "swordmasters_path_red", "swordmasters_path_red", "the_weakest_link_red", "the_weakest_link_red"],
      "sideboard" => ["shelter_from_the_storm_red", "shelter_from_the_storm_red", "shelter_from_the_storm_red", "sink_below_red", "sink_below_red",
        "sink_below_red", "slice_up_red", "slice_up_red", "blunten_yellow", "blunten_yellow", "blunten_yellow"],
    ],
    "LeviaCC" => [
      "hero" => "levia_shadowborn_abomination",
      "weapons" => ["hexagore_the_death_hydra"],
      "deck" => ["blood_harvest", "blood_harvest", "blood_harvest", "boneyard_marauder_red", "cleave_the_heavens_red",
        "cleave_the_heavens_red", "cleave_the_heavens_red", "dread_screamer_red", "dread_screamer_red", "dread_screamer_red",
        "endless_maw_red", "endless_maw_red", "endless_maw_red", "feeding_frenzy_red", "feeding_frenzy_red", "feeding_frenzy_red",
        "graveling_growl_red", "graveling_growl_red", "shadowrealm_horror_red", "shadowrealm_horror_red", "shadowrealm_horror_red",
        "beast_within_yellow", "beast_within_yellow", "bloodrush_bellow_yellow", "bloodrush_bellow_yellow", "bloodrush_bellow_yellow",
        "engulfing_shadows_yellow", "engulfing_shadows_yellow", "engulfing_shadows_yellow", "fallen_herald_yellow", "fallen_herald_yellow",
        "fallen_herald_yellow", "graveling_growl_yellow", "call_to_the_grave_blue", "call_to_the_grave_blue", "call_to_the_grave_blue",
        "cleave_the_heavens_blue", "cleave_the_heavens_blue", "cleave_the_heavens_blue", "deadwood_rumbler_blue", "deadwood_rumbler_blue",
        "deadwood_rumbler_blue", "diabolic_offering_blue", "diabolic_offering_blue", "diabolic_offering_blue", "doomsday_blue",
        "dread_screamer_blue", "dread_screamer_blue", "goremass_summoning_blue", "pull_from_beyond_blue", "pull_from_beyond_blue",
        "pull_from_beyond_blue", "rites_of_nightfall_blue", "rites_of_nightfall_blue", "rites_of_nightfall_blue", "rockyard_rodeo_blue",
        "rockyard_rodeo_blue", "rockyard_rodeo_blue", "soul_harvest_blue", "wrecker_romp_blue", "wrecker_romp_blue", "wrecker_romp_blue"],
      "sideboard" => [],
      "inventory" => ["crown_of_providence", "levia_redeemed"],
    ],
    "JarlCC" => [
      "hero" => "jarl_vetreidi",
      "weapons" => ["sledge_of_anvilheim", "stalagmite_bastion_of_isenloft", "titans_fist"],
      "deck" => ["ancient_earth_oak_red", "ancient_earth_oak_red", "ancient_earth_oak_red", "command_and_conquer_red", "command_and_conquer_red",
        "command_and_conquer_red", "enlightened_strike_red", "felling_of_the_crown_red", "felling_of_the_crown_red", "felling_of_the_crown_red",
        "mangle_red", "mangle_red", "oaken_old_red", "oaken_old_red", "oaken_old_red", "pummel_red", "pummel_red", "rootbound_carapace_red",
        "rootbound_carapace_red", "rootbound_carapace_red", "shelter_from_the_storm_red", "shelter_from_the_storm_red",
        "shelter_from_the_storm_red", "sink_below_red", "sink_below_red", "sprout_strength_red", "sprout_strength_red", "sprout_strength_red",
        "staunch_response_red", "plow_under_yellow", "plow_under_yellow", "plow_under_yellow", "autumns_touch_blue", "autumns_touch_blue",
        "autumns_touch_blue", "blizzard_blue", "canopy_shelter_blue", "canopy_shelter_blue", "channel_lake_frigid_blue", "channel_lake_frigid_blue",
        "channel_lake_frigid_blue", "crumble_to_eternity_blue", "crumble_to_eternity_blue", "crumble_to_eternity_blue", "everbloom__life_blue",
        "everbloom__life_blue", "everbloom__life_blue", "frozen_to_death_blue", "frozen_to_death_blue", "frozen_to_death_blue",
        "fruits_of_the_forest_blue", "fruits_of_the_forest_blue", "fruits_of_the_forest_blue", "glacial_footsteps_blue", "glacial_footsteps_blue",
        "ice_aged_oak_blue", "ice_aged_oak_blue", "ice_aged_oak_blue", "imposing_visage_blue", "imposing_visage_blue"],
      "sideboard" => ["pulse_of_isenloft_blue", "ripple_away_blue", "ripple_away_blue", "tear_asunder_blue", "tear_asunder_blue", "winters_bite_blue",
        "winters_bite_blue"],
    ],
    "StarvoCC" => [
      "hero" => "bravo_star_of_the_show",
      "weapons" => ["winters_wail", "stalagmite_bastion_of_isenloft", "arcane_lantern"],
      "deck" => ["autumns_touch_red", "autumns_touch_red", "autumns_touch_red", "ball_lightning_red", "ball_lightning_red", "cadaverous_tilling_red",
        "cadaverous_tilling_red", "cadaverous_tilling_red", "crippling_crush_red", "cut_through_the_facade_red", "evergreen_red",
        "felling_of_the_crown_red", "felling_of_the_crown_red", "felling_of_the_crown_red", "fruits_of_the_forest_red", "fruits_of_the_forest_red",
        "fruits_of_the_forest_red", "oaken_old_red", "pulse_of_volthaven_red", "rootbound_carapace_red", "rootbound_carapace_red",
        "spinal_crush_red", "spinal_crush_red", "spinal_crush_red", "turn_timber_red", "turn_timber_red", "autumns_touch_yellow",
        "autumns_touch_blue", "autumns_touch_blue", "autumns_touch_blue", "awakening_blue", "blizzard_blue", "blizzard_blue",
        "cadaverous_tilling_blue", "cadaverous_tilling_blue", "cadaverous_tilling_blue", "channel_lake_frigid_blue", "channel_lake_frigid_blue",
        "channel_lake_frigid_blue", "channel_mount_isen_blue", "channel_mount_isen_blue", "channel_mount_isen_blue", "current_funnel_blue",
        "current_funnel_blue", "current_funnel_blue", "electromagnetic_somersault_blue", "electromagnetic_somersault_blue",
        "electromagnetic_somersault_blue", "exposed_to_the_elements_blue", "frozen_to_death_blue", "frozen_to_death_blue", "frozen_to_death_blue",
        "fruits_of_the_forest_blue", "fruits_of_the_forest_blue", "fruits_of_the_forest_blue", "heavens_claws_blue", "heavens_claws_blue",
        "heavens_claws_blue", "winters_grasp_blue", "winters_grasp_blue"],
      "sideboard" => ["hypothermia_blue", "pulse_of_isenloft_blue", "trip_the_light_fantastic_blue", "warmongers_diplomacy_blue", "winters_grasp_blue"],
    ],
    "KayoSAGE" => [
      "hero" => "kayo",
      "weapons" => ["mandible_claw"],
      "deck" => ["bare_fangs_red", "bare_fangs_red", "buckwild_red", "buckwild_red", "clash_of_might_red", "clash_of_might_red",
        "high_pitched_howl_red", "high_pitched_howl_red", "rough_up_red", "rough_up_red", "sirens_of_safe_harbor_red", "sirens_of_safe_harbor_red",
        "strongest_survive_red", "strongest_survive_red", "wild_ride_red", "wild_ride_red", "agile_windup_yellow", "agile_windup_yellow",
        "bare_fangs_yellow", "bare_fangs_yellow", "buckwild_yellow", "buckwild_yellow", "clash_of_might_yellow", "clash_of_might_yellow",
        "sirens_of_safe_harbor_yellow", "sirens_of_safe_harbor_yellow", "wild_ride_yellow", "wild_ride_yellow", "agile_windup_blue", "agile_windup_blue",
        "assault_and_battery_blue", "assault_and_battery_blue", "bear_hug_blue", "bear_hug_blue", "buckwild_blue", "buckwild_blue",
        "mighty_windup_blue", "mighty_windup_blue", "run_roughshod_blue", "run_roughshod_blue", "smash_instinct_blue", "smash_instinct_blue",
        "unexpected_backhand_blue", "unexpected_backhand_blue", "vigorous_smashup_blue", "vigorous_smashup_blue"],
      "sideboard" => [],
    ],
    "IraSAGE" => [
      "hero" => "ira_crimson_haze",
      "weapons" => ["edge_of_autumn"],
      "deck" => ["arcane_polarity_red", "arcane_polarity_red", "aspect_of_tiger_body_red", "aspect_of_tiger_body_red", "bittering_thorns_red",
        "bittering_thorns_red", "blessing_of_qi_red", "blessing_of_qi_red", "bluster_buff_red", "bluster_buff_red", "flying_kick_red",
        "flying_kick_red", "flying_kick_blue", "flying_kick_blue", "growl_red", "growl_red", "humble_red", "humble_red", "on_the_horizon_red",
        "razor_reflex_red", "pouncing_qi_red", "pouncing_qi_red", "pouncing_qi_blue", "pouncing_qi_blue", "reinforce_the_line_red",
        "reinforce_the_line_red", "scar_for_a_scar_red", "scar_for_a_scar_red", "seek_vengeance_red", "seek_vengeance_red", "seek_vengeance_blue",
        "seek_vengeance_blue", "sirens_of_safe_harbor_red", "sirens_of_safe_harbor_red", "sirens_of_safe_harbor_yellow",
        "sirens_of_safe_harbor_yellow", "sirens_of_safe_harbor_blue", "sirens_of_safe_harbor_blue", "untamed_red", "untamed_red",
        "tiger_eye_reflex_yellow", "tiger_eye_reflex_yellow", "feign_vengeance_blue", "feign_vengeance_blue", "legacy_of_ikaru_blue",
        "legacy_of_ikaru_blue", "qi_unleashed_blue", "qi_unleashed_blue"],
      "sideboard" => [],
    ],
    "DorintheaSAGE" => [
      "hero" => "dorinthea",
      "weapons" => ["decimator_great_axe"],
      "deck" => ["arcane_polarity_red", "arcane_polarity_red", "chest_puff_red", "chest_puff_red", "crash_down_the_gates_red", "felling_swing_red",
        "felling_swing_red", "felling_swing_yellow", "felling_swing_yellow", "felling_swing_blue", "felling_swing_blue", "ironsong_response_red",
        "ironsong_response_red", "ironsong_response_blue", "ironsong_response_blue", "oasis_respite_red", "oasis_respite_red", "on_the_horizon_red",
        "on_the_horizon_red", "overpower_red", "overpower_red", "overpower_blue", "overpower_blue", "sharpen_steel_red", "sharpen_steel_red",
        "sirens_of_safe_harbor_red", "sirens_of_safe_harbor_red", "sirens_of_safe_harbor_blue", "sirens_of_safe_harbor_blue",
        "steelblade_shunt_red", "steelblade_shunt_red", "steelblade_shunt_yellow", "steelblade_shunt_yellow", "steelblade_shunt_blue",
        "steelblade_shunt_blue", "test_of_strength_red", "test_of_strength_red", "biting_blade_blue", "biting_blade_blue",
        "display_of_craftsmanship_blue", "display_of_craftsmanship_blue", "fatal_engagement_blue", "fatal_engagement_blue", "hit_and_run_blue",
        "hit_and_run_blue", "raging_onslaught_blue"],
      "sideboard" => [],
    ],
    "BoltynCC" => [
      "hero" => "ser_boltyn_breaker_of_dawn",
      "weapons" => ["raydn_duskbane"],
      "deck" => ["banneret_of_swordsmanship_yellow", "banneret_of_swordsmanship_yellow", "banneret_of_swordsmanship_yellow", "battlefield_beacon_yellow",
        "battlefield_beacon_yellow", "battlefield_beacon_yellow", "beacon_of_victory_yellow", "beacon_of_victory_yellow",
        "beacon_of_victory_yellow", "beaming_bravado_red", "beaming_bravado_red", "beaming_bravado_red", "beaming_bravado_yellow",
        "beaming_bravado_yellow", "beaming_bravado_yellow", "blessing_of_bellona_yellow", "blessing_of_bellona_yellow",
        "blessing_of_bellona_yellow", "blessing_of_suraya_yellow", "blessing_of_suraya_yellow", "blessing_of_suraya_yellow", "bolt_of_courage_red",
        "bolt_of_courage_red", "bolt_of_courage_red", "bolt_of_courage_yellow", "bolt_of_courage_yellow", "bolt_of_courage_yellow",
        "bolting_blade_yellow", "bravery_of_the_blade_red", "bravery_of_the_blade_red", "bravery_of_the_blade_red", "celestial_cataclysm_yellow",
        "celestial_cataclysm_yellow", "duty_bound_blitz_red", "duty_bound_blitz_red", "duty_bound_blitz_red", "duty_bound_blitz_yellow",
        "duty_bound_blitz_yellow", "engulfing_light_red", "engulfing_light_red", "engulfing_light_red", "lumina_ascension_yellow",
        "lumina_ascension_yellow", "lumina_ascension_yellow", "roaring_beam_yellow", "roaring_beam_yellow", "spirit_of_eirina_yellow",
        "spirit_of_war_red", "spirit_of_war_red", "spirit_of_war_red", "v_of_the_vanguard_yellow", "v_of_the_vanguard_yellow",
        "v_of_the_vanguard_yellow", "edict_of_steel_red", "edict_of_steel_red", "courageous_steelhand_red", "courageous_steelhand_red",
        "tenacity_yellow", "saving_grace_yellow", "saving_grace_yellow"],
      "sideboard" => ["saving_grace_yellow", "sink_below_red", "sink_below_red", "sink_below_red", "soul_shield_yellow", "soul_shield_yellow",
        "energy_potion_blue", "energy_potion_blue"],
    ],
    "AuroraCC" => [
      "hero" => "aurora_legacy_of_tempest",
      "weapons" => ["scorpio_comet_tail"],
      "deck" => ["burn_up__shock_red", "burn_up__shock_red", "burn_up__shock_red", "enlightened_strike_red", "enlightened_strike_red",
        "enlightened_strike_red", "fry_red", "fry_red", "fry_red", "jack_be_nimble_red", "jack_be_nimble_red", "jack_be_nimble_red",
        "lightning_surge_red", "lightning_surge_red", "lightning_surge_red", "lightning_surge_yellow", "nimblism_red", "nimblism_red",
        "nimblism_red", "nimblism_yellow", "nimby_red", "nimby_red", "path_of_same_ends_red", "path_of_same_ends_red", "path_of_same_ends_red",
        "quick_succession_red", "quick_succession_red", "quick_succession_red", "ravenous_rabble_red", "ravenous_rabble_red", "ravenous_rabble_red",
        "scar_for_a_scar_red", "scar_for_a_scar_red", "second_strike_red", "second_strike_red", "second_strike_red", "skyzyk_red", "skyzyk_red",
        "skyzyk_red", "snatch_red", "snatch_red", "snatch_red", "arc_lightning_yellow", "arc_lightning_yellow", "arc_lightning_yellow",
        "skyward_serenade_yellow", "skyward_serenade_yellow", "skyward_serenade_yellow", "current_funnel_blue", "current_funnel_blue",
        "current_funnel_blue", "flittering_charge_red", "flittering_charge_red", "flittering_charge_red", "electrostatic_discharge_red",
        "electrostatic_discharge_red", "electrostatic_discharge_red", "lightning_press_red", "lightning_press_red", "lightning_press_red"],
      "sideboard" => ["sigil_of_suffering_red", "sigil_of_suffering_red", "sigil_of_suffering_red", "sink_below_red", "sink_below_red", "sink_below_red",
        "rush_of_power_red", "rush_of_power_red", "scar_for_a_scar_red", "energy_potion_blue"],
    ],
    "FangCC" => [
      "hero" => "fang_dracai_of_blades",
      "weapons" => ["obsidian_fire_vein", "obsidian_fire_vein_r"],
      "deck" => ["affirm_loyalty_red", "affirm_loyalty_red", "affirm_loyalty_red", "blade_flurry_red", "blade_flurry_red", "blade_flurry_red",
        "blistering_blade_red", "blistering_blade_red", "blistering_blade_red", "fire_and_brimstone_red", "for_the_dracai_red",
        "for_the_dracai_red", "for_the_dracai_red", "hunts_end_red", "hunts_end_red", "hunts_end_red", "imperial_intent_red", "imperial_intent_red",
        "long_whisker_loyalty_red", "long_whisker_loyalty_red", "long_whisker_loyalty_red", "oath_of_loyalty_red", "oath_of_loyalty_red",
        "oath_of_loyalty_red", "pledge_fealty_red", "pledge_fealty_red", "pledge_fealty_red", "savor_bloodshed_red", "savor_bloodshed_red",
        "savor_bloodshed_red", "scar_tissue_red", "scar_tissue_red", "scar_tissue_red", "searing_gaze_red", "searing_gaze_red", "searing_gaze_red",
        "sink_below_red", "sink_below_red", "sink_below_red", "smoke_out_red", "smoke_out_red", "smoke_out_red", "tooth_of_the_dragon_red",
        "tooth_of_the_dragon_red", "tooth_of_the_dragon_red", "glint_the_quicksilver_blue", "glint_the_quicksilver_blue",
        "glint_the_quicksilver_blue", "overcrowded_blue", "overcrowded_blue", "overcrowded_blue", "overpower_blue", "overpower_blue",
        "overpower_blue", "provoke_blue", "provoke_blue", "provoke_blue", "relentless_pursuit_blue", "relentless_pursuit_blue",
        "relentless_pursuit_blue"],
      "sideboard" => ["blaze_headlong_red", "blaze_headlong_red", "blaze_headlong_red", "sworn_vengeance_red", "sworn_vengeance_red", "sworn_vengeance_red",
        "fate_foreseen_red", "fate_foreseen_red", "fate_foreseen_red", "parched_terrain_red", "parched_terrain_red"],
    ],
  ];
  return $pools;
}

function BotDeckKeyForHero($heroID)
{
  static $keys = [
    "briar" => "BriarSAGE",
    "bravo_flattering_showman" => "BravoSAGE",
    "kayo" => "KayoSAGE",
    "dorinthea" => "DorintheaSAGE",
    "ira_crimson_haze" => "IraSAGE",
    "cindra_dracai_of_retribution" => "CindraCC",
    "hala_bladesaint_of_the_vow" => "HalaCC",
    "levia_shadowborn_abomination" => "LeviaCC",
    "jarl_vetreidi" => "JarlCC",
    "bravo_star_of_the_show" => "StarvoCC",
    "ser_boltyn_breaker_of_dawn" => "BoltynCC",
    "aurora_legacy_of_tempest" => "AuroraCC",
    "fang_dracai_of_blades" => "FangCC",
  ];
  return $keys[$heroID] ?? null;
}

function BotOpponentProfile($heroID)
{
  $classes = $heroID === "" ? "" : CardClass($heroID);
  return [
    "name" => $heroID === "" ? "" : strtolower(trim(CardName($heroID))),
    "classes" => array_values(array_filter(array_map(fn($class) => strtolower(trim($class)), explode(",", $classes)))),
  ];
}

function BotRemoveCopies(&$deck, $cardID, $count)
{
  for ($copy = 0; $copy < $count; ++$copy) {
    $index = array_search($cardID, $deck, true);
    if ($index === false) return;
    array_splice($deck, $index, 1);
  }
}

function BotSwapCards(&$deck, $removals, $additions)
{
  foreach ($removals as [$cardID, $count]) BotRemoveCopies($deck, $cardID, $count);
  array_push($deck, ...$additions);
}

function BotNameHasAny($name, $heroes)
{
  foreach ($heroes as $hero) {
    if (str_contains($name, $hero)) return true;
  }
  return false;
}

function BotPresentDeck($key, $opponentHeroID = "", $botTurnOrder = "first")
{
  $pool = BotDeckPools()[$key] ?? null;
  if ($pool === null) return null;
  $opponent = BotOpponentProfile($opponentHeroID);
  $name = $opponent["name"];
  $classes = $opponent["classes"];
  $hasClass = fn($class) => in_array($class, $classes, true);
  $deck = $pool["deck"];
  $weapons = $pool["weapons"];
  $inventory = $pool["inventory"] ?? [];

  switch ($key) {
    case "BriarSAGE":
      $matchup = match (true) {
        BotNameHasAny($name, ["fai", "zen", "cindra"]) => "ninja",
        BotNameHasAny($name, ["terra", "riptide", "oldhim", "dorinthea"]) => "fatigue",
        BotNameHasAny($name, ["blaze", "kano"]) => "wizard",
        BotNameHasAny($name, ["chane", "florian", "viserai"]) => "runeblade",
        BotNameHasAny($name, ["azalea", "valda"]) => "dominate",
        str_contains($name, "dromai") => "dromai",
        str_contains($name, "enigma") => "enigma",
        str_contains($name, "briar") => "briar",
        str_contains($name, "iyslander") => "iyslander",
        default => "stock",
      };
      if ($matchup == "ninja") BotSwapCards($deck, [["fry_red", 1], ["lightning_press_red", 1]], ["sigil_of_suffering_red", "sigil_of_suffering_red"]);
      else if ($matchup == "fatigue" || $matchup == "enigma") BotSwapCards($deck, [["nimblism_yellow", 1], ["second_strike_red", 2]], ["evergreen_red", "evergreen_red", "quick_succession_blue"]);
      else if ($matchup == "wizard") BotSwapCards($deck, [["quick_succession_blue", 1], ["scar_for_a_scar_red", 1]], ["cloud_cover_red", "cloud_cover_red"]);
      else if ($matchup == "dominate") BotSwapCards($deck, [["fry_red", 1], ["jack_be_nimble_red", 1], ["nimblism_yellow", 1], ["quick_succession_blue", 1]], ["cloud_cover_red", "cloud_cover_red", "sigil_of_suffering_red", "sigil_of_suffering_red"]);
      else if ($matchup == "briar" && $botTurnOrder == "first") BotSwapCards($deck, [["fry_red", 2]], ["sigil_of_suffering_red", "sigil_of_suffering_red"]);
      else if ($matchup == "briar") BotSwapCards($deck, [["quick_succession_blue", 1]], ["sigil_of_suffering_red"]);
      else if ($matchup == "iyslander") BotSwapCards($deck, [["nimblism_yellow", 1], ["scar_for_a_scar_red", 2]], ["cloud_cover_red", "cloud_cover_red", "quick_succession_blue"]);
      $equipment = [
        "head" => in_array($matchup, ["wizard", "runeblade", "dromai", "briar", "iyslander"], true) ? "helm_of_might_and_magic" : "blade_beckoner_helm",
        "chest" => "garland_of_spring",
        "arms" => in_array($matchup, ["fatigue", "wizard", "runeblade", "dromai", "enigma"], true) ? "swiftstrike_bracers" : "blade_beckoner_gauntlets",
        "legs" => $matchup == "wizard" || $matchup == "iyslander" ? "nullrune_boots" : "blade_beckoner_boots",
      ];
      break;

    case "BravoSAGE":
      $matchup = match (true) {
        str_contains($name, "blaze") => "blaze",
        str_contains($name, "bravo, flattering") => "bravo",
        str_contains($name, "briar") => "briar",
        str_contains($name, "iyslander") => "iyslander",
        str_contains($name, "kano") => "kano",
        str_contains($name, "oldhim") => "oldhim",
        str_contains($name, "olympia") => "olympia",
        str_contains($name, "oscilio") => "oscilio",
        default => "default",
      };
      $polarity = ["arcane_polarity_red", "arcane_polarity_red"];
      $chokeslam = ["chokeslam_red", "chokeslam_red"];
      $oasis = ["oasis_respite_red", "oasis_respite_red"];
      $pummel = ["pummel_red", "pummel_red"];
      $staunch = ["staunch_response_red", "staunch_response_red"];
      if (in_array($matchup, ["blaze", "iyslander", "kano"], true)) {
        $deck = array_values(array_filter($deck, fn($cardID) => $cardID != "debilitate_red"));
        $additions = [...$polarity, ...$oasis, ...$pummel];
      } else if ($matchup == "bravo") {
        $additions = [...$pummel, ...$staunch];
      } else if ($matchup == "briar") {
        $additions = [...$polarity, ...$chokeslam];
      } else if ($matchup == "oldhim" || $matchup == "olympia") {
        $deck = array_values(array_filter($deck, fn($cardID) => $cardID != "clash_of_heads_yellow"));
        $additions = [...$chokeslam, ...$pummel, ...$staunch];
      } else if ($matchup == "oscilio") {
        $deck = array_values(array_filter($deck, fn($cardID) => $cardID != "clash_of_heads_yellow"));
        $additions = [...$polarity, ...$oasis, ...$pummel];
      } else {
        $additions = [...$chokeslam, ...$pummel];
      }
      array_push($deck, ...$additions);
      $physical = ["head" => "blade_beckoner_helm", "chest" => "magmatic_carapace", "arms" => "blade_beckoner_gauntlets", "legs" => "civic_steps"];
      if (in_array($matchup, ["blaze", "iyslander", "kano", "oscilio"], true)) {
        $equipment = ["head" => "enclosed_firemind", "chest" => "magmatic_carapace", "arms" => "nullrune_gloves", "legs" => "nullrune_boots"];
      } else if ($matchup == "briar") {
        $equipment = ["head" => "enclosed_firemind"] + $physical;
      } else {
        $equipment = $physical;
      }
      break;

    case "CindraCC":
      $matchup = match (true) {
        str_contains($name, "arakni, marionette") => "arakni-marionette",
        str_contains($name, "oscilio") => "oscilio",
        str_contains($name, "gravy bones") => "gravy",
        str_contains($name, "vynnset") => "vynnset",
        BotNameHasAny($name, ["kassai", "hala"]) => "warrior",
        str_contains($name, "marlynn") => "marlynn",
        str_contains($name, "cindra") => "cindra",
        str_contains($name, "arakni, huntsman") => "huntsman",
        str_contains($name, "katsu") => "katsu",
        str_contains($name, "dorinthea") => "dorinthea",
        BotNameHasAny($name, ["dash i/o", "dash io"]) => "dash-io",
        str_contains($name, "boltyn") => "boltyn",
        default => "stock",
      };
      $reactions = ["shelter_from_the_storm_red", "shelter_from_the_storm_red", "shelter_from_the_storm_red", "sink_below_red", "sink_below_red", "sink_below_red"];
      $warmongers = ["warmongers_diplomacy_blue", "warmongers_diplomacy_blue"];
      $huntsmanCards = ["hunt_to_the_ends_of_rathe_red", "hunt_to_the_ends_of_rathe_red", "hunt_to_the_ends_of_rathe_red", "overcrowded_blue"];
      if ($matchup == "arakni-marionette") {
        BotSwapCards($deck, [["breaking_point_red", 2], ["demonstrate_devotion_red", 3], ["draco_fire_red", 3], ["hunt_the_hunter_red", 2]], [...$huntsmanCards, ...$reactions]);
      } else if (in_array($matchup, ["oscilio", "cindra", "katsu", "boltyn"], true)) {
        BotSwapCards($deck, [["breaking_point_red", 1], ["demonstrate_devotion_red", 3], ["hunt_the_hunter_red", 2]], $reactions);
      } else if ($matchup == "dorinthea") {
        BotSwapCards($deck, [["breaking_point_red", 1], ["demonstrate_devotion_red", 3], ["hunt_the_hunter_red", 2], ["lava_burst_red", 2]], [...$reactions, ...$warmongers]);
      } else if ($matchup == "dash-io") {
        BotSwapCards($deck, [["breaking_point_red", 2], ["demonstrate_devotion_red", 3], ["hunt_the_hunter_red", 2], ["lava_burst_red", 1]], [...$reactions, ...$warmongers]);
      } else if (in_array($matchup, ["gravy", "vynnset", "warrior", "marlynn"], true)) {
        BotSwapCards($deck, [["lava_burst_red", 2]], $warmongers);
      } else if ($matchup == "huntsman") {
        array_push($deck, ...$huntsmanCards);
      }
      $furnace = in_array($matchup, ["arakni-marionette", "oscilio", "cindra", "katsu", "dorinthea", "dash-io", "boltyn"], true);
      $weapons = $matchup == "oscilio" || $matchup == "vynnset" ? ["kunai_of_retribution", "claw_of_vynserakai"] : ["kunai_of_retribution", "kunai_of_retribution_r"];
      $equipment = ["head" => "mask_of_momentum", "chest" => $furnace ? "flamescale_furnace" : "blood_splattered_vest", "arms" => "flick_knives", "legs" => "dragonscaler_flight_path"];
      break;

    case "HalaCC":
      $flex = ["big_blinder_red", "showdown_red", "swordmasters_path_red", "the_weakest_link_red", "shelter_from_the_storm_red", "sink_below_red", "slice_up_red", "blunten_yellow"];
      $deck = array_values(array_filter([...$pool["deck"], ...$pool["sideboard"]], fn($cardID) => !in_array($cardID, $flex, true)));
      $assertive = ["big_blinder_red", "showdown_red", "showdown_red", "swordmasters_path_red", "swordmasters_path_red", "swordmasters_path_red", "the_weakest_link_red", "the_weakest_link_red"];
      $defensive = ["shelter_from_the_storm_red", "shelter_from_the_storm_red", "shelter_from_the_storm_red", "sink_below_red", "sink_below_red", "sink_below_red"];
      $blunten = ["blunten_yellow", "blunten_yellow", "blunten_yellow"];
      $warrior = $hasClass("warrior");
      $oscilio = str_contains($name, "oscilio");
      if (str_contains($name, "arakni, marionette")) {
        BotRemoveCopies($deck, "point_of_escalation_yellow", 1);
        $package = [...$defensive, ...$blunten];
      } else if (str_contains($name, "cindra")) {
        $package = [...$defensive, "slice_up_red", "slice_up_red"];
      } else if ($warrior) {
        $package = [...$defensive, "swordmasters_path_red", "swordmasters_path_red", "swordmasters_path_red", ...$blunten];
      } else if (BotNameHasAny($name, ["jarl", "betsy", "arakni, huntsman"])) {
        $package = [...$assertive, ...$defensive, ...$blunten];
      } else if ($oscilio) {
        $package = ["the_weakest_link_red", "the_weakest_link_red", "slice_up_red", "slice_up_red", "showdown_red", "showdown_red", "swordmasters_path_red", "swordmasters_path_red"];
      } else {
        $package = $assertive;
      }
      array_push($deck, ...$package);
      $equipment = [
        "head" => $warrior && !str_contains($name, "olympia") ? "kabuto_of_imperial_authority" : ($oscilio || BotNameHasAny($name, ["aurora", "vynnset"]) ? "nullrune_hood" : "crown_of_providence"),
        "chest" => $oscilio ? "nullrune_robe" : "grains_of_bloodspill",
        "arms" => "reverent_rerebrace",
        "legs" => $oscilio ? "nullrune_boots" : "valiant_dynamo",
      ];
      break;

    case "JarlCC":
      $deck = [...$pool["deck"], ...$pool["sideboard"]];
      $wizard = $hasClass("wizard");
      $weapons = ["titans_fist", "stalagmite_bastion_of_isenloft"];
      $equipment = [
        "head" => BotNameHasAny($name, ["malice", "boltyn", "arakni, marionette", "teklo"]) ? "crown_of_frozen_thoughts" : ($wizard ? "crown_of_everbloom" : "crown_of_providence"),
        "chest" => $wizard || $hasClass("runeblade") ? "heart_of_ice" : "barkskin_of_the_millennium_tree",
        "arms" => "gauntlets_of_the_boreal_domain",
        "legs" => "boots_of_omnis_ward",
      ];
      break;

    case "LeviaCC":
      $equipment = ["head" => "boneseer_skullcap", "chest" => "savage_sash", "arms" => "gauntlets_of_tyrannical_rex", "legs" => "scabskin_leathers"];
      break;

    case "StarvoCC":
      $deck = [...$pool["deck"], ...$pool["sideboard"]];
      $arcane = $hasClass("wizard") || $hasClass("runeblade");
      $weapons = ["winters_wail", $arcane ? "arcane_lantern" : "stalagmite_bastion_of_isenloft"];
      $equipment = [
        "head" => "crown_of_seeds",
        "chest" => $arcane ? "heart_of_ice" : "fyendals_spring_tunic",
        "arms" => "shock_charmers",
        "legs" => $arcane || $hasClass("illusionist") ? "lightning_greaves" : "civic_steps",
      ];
      break;

    case "KayoSAGE":
      $slow = preg_match('/oldhim|dorinthea|enigma|nuu|terra|riptide/', $name) == 1;
      $arcane = $hasClass("wizard") || preg_match('/kano|blaze|iyslander|oscilio|verdance/', $name) == 1;
      $omit = $slow ? ["clash_of_might_red", "clash_of_might_yellow", "sirens_of_safe_harbor_yellow"] : ["agile_windup_yellow", "buckwild_yellow", "mighty_windup_blue"];
      $deck = array_values(array_filter($deck, fn($cardID) => !in_array($cardID, $omit, true)));
      $equipment = $arcane
        ? ["head" => "nullrune_hood", "chest" => "nullrune_robe", "arms" => "skera_strapping", "legs" => "shamanic_shinbones"]
        : ["head" => "knucklehead", "chest" => "predatory_plating", "arms" => "blade_beckoner_gauntlets", "legs" => "flat_trackers"];
      break;

    case "IraSAGE":
      $matchup = match (true) {
        $hasClass("wizard") || $hasClass("runeblade") || preg_match('/kano|blaze|iyslander|oscilio|briar|viserai|aurora/', $name) == 1 => "arcane",
        preg_match('/bravo|azalea|valda/', $name) == 1 => "dominate",
        preg_match('/oldhim|dorinthea|enigma|nuu|terra|riptide|dromai/', $name) == 1 => "slow",
        preg_match('/\b(?:kayo|rhinar|katsu|fai|ira|cindra|dash|boltyn|fang|kassai|levia)\b/', $name) == 1 => "aggro",
        default => "default",
      };
      $flex = ["arcane_polarity_red", "reinforce_the_line_red", "humble_red", "on_the_horizon_red", "razor_reflex_red", "bluster_buff_red"];
      $keep = match ($matchup) {
        "arcane" => ["arcane_polarity_red"],
        "dominate" => ["reinforce_the_line_red"],
        "slow" => ["humble_red"],
        "aggro" => ["on_the_horizon_red", "razor_reflex_red"],
        default => ["bluster_buff_red"],
      };
      $deck = array_values(array_filter($deck, fn($cardID) => !in_array($cardID, $flex, true) || in_array($cardID, $keep, true)));
      $arcane = $matchup == "arcane";
      $equipment = [
        "head" => $arcane ? "mask_of_the_swarming_claw" : "blade_beckoner_helm",
        "chest" => $arcane ? "double_cross_strap" : "blood_scent",
        "arms" => "tearing_shuko",
        "legs" => "pouncing_paws",
      ];
      break;

    case "DorintheaSAGE":
      $matchup = match (true) {
        $hasClass("wizard") || preg_match('/kano|blaze|iyslander|oscilio|verdance/', $name) == 1 => "wizard",
        str_contains($name, "briar") => "briar",
        $hasClass("runeblade") || preg_match('/viserai|aurora|florian|chane|vynnset/', $name) == 1 => "runeblade",
        preg_match('/dorinthea|riptide/', $name) == 1 => "fatigue",
        $hasClass("guardian") || preg_match('/oldhim|bravo|jarl|terra|victor|betsy|valda/', $name) == 1 => "guardian",
        preg_match('/\b(?:rhinar|katsu|fai|ira|cindra|dash|boltyn|fang|levia)\b/', $name) == 1 => "aggro",
        $hasClass("warrior") || preg_match('/azalea|kassai|hala|olympia/', $name) == 1 => "value",
        default => "default",
      };
      $polarity = ["arcane_polarity_red", "arcane_polarity_red"];
      $oasis = ["oasis_respite_red", "oasis_respite_red"];
      $horizon = ["on_the_horizon_red", "on_the_horizon_red"];
      $test = ["test_of_strength_red", "test_of_strength_red"];
      $fatigueAttacks = ["crash_down_the_gates_red", "raging_onslaught_blue"];
      foreach ([...$polarity, ...$oasis, ...$horizon, ...$test, ...$fatigueAttacks] as $cardID) BotRemoveCopies($deck, $cardID, 1);
      array_push($deck, ...match ($matchup) {
        "wizard", "runeblade" => [...$polarity, ...$oasis],
        "briar" => [...$polarity, ...$horizon],
        "value", "guardian" => [...$horizon, ...$oasis],
        "fatigue" => [...$oasis, ...$fatigueAttacks],
        default => [...$horizon, ...$test],
      });
      $wizard = $matchup == "wizard";
      $barrier = $wizard || $matchup == "runeblade" || $matchup == "briar";
      $racing = ($matchup == "briar" || $matchup == "aggro") && $botTurnOrder == "second";
      $equipment = [
        "head" => $barrier ? "nullrune_hood" : "helm_of_unity",
        "chest" => $wizard ? "nullrune_robe" : "plating_of_unity",
        "arms" => $wizard ? "nullrune_gloves" : "gauntlets_of_unity",
        "legs" => $matchup == "fatigue" || $matchup == "guardian" || $racing ? "refraction_bolters" : "pillar_of_unity",
      ];
      break;

    case "BoltynCC":
      $edict = "edict_of_steel_red";
      $steelhand = "courageous_steelhand_red";
      $tenacity = "tenacity_yellow";
      $grace = "saving_grace_yellow";
      $sink = "sink_below_red";
      $shield = "soul_shield_yellow";
      $potion = "energy_potion_blue";
      foreach ([[$edict, 2], [$steelhand, 2], [$tenacity, 1], [$grace, 2]] as [$cardID, $count]) BotRemoveCopies($deck, $cardID, $count);
      $aggressive = [$edict, $edict, $steelhand, $steelhand, $potion, $potion, $tenacity];
      $defensivePackage = [$grace, $grace, $grace, $sink, $sink, $sink, $edict];
      $graces = [$grace, $grace, $grace];
      $shields = [$shield, $shield];
      $swords = [$edict, $edict, $steelhand, $steelhand];
      $defender = ["head" => "helm_of_halos_grace", "chest" => "soulbond_resolve", "arms" => "ironsong_versus", "legs" => "warpath_of_winged_grace"];
      $aggressor = ["head" => "warband_of_bellona", "chest" => "fyendals_spring_tunic", "arms" => "ironsong_versus", "legs" => "warpath_of_winged_grace"];
      $matchup = match (true) {
        str_contains($name, "arakni, marionette") => "marionette",
        str_contains($name, "dash") => "dash",
        BotNameHasAny($name, ["cindra", "fai"]) => "hyper-aggro",
        str_contains($name, "levia") => "levia",
        str_contains($name, "marlynn") => "marlynn",
        str_contains($name, "aurora") => "aurora",
        str_contains($name, "viserai") => "viserai",
        str_contains($name, "vynnset") => "vynnset",
        str_contains($name, "victor") => "victor",
        str_contains($name, "jarl") => "jarl",
        str_contains($name, "valda") => "valda",
        BotNameHasAny($name, ["arakni, huntsman", "teklovossen", "pleiades"]) => "fatigue",
        str_contains($name, "dorinthea") => "dorinthea",
        str_contains($name, "fang") => "fang",
        str_contains($name, "olympia") => "olympia",
        str_contains($name, "gravy bones") => "gravy",
        str_contains($name, "zyggy") => "zyggy",
        BotNameHasAny($name, ["ira", "puffin", "rhinar", "lyath"]) => "aggressive-defender",
        BotNameHasAny($name, ["hala", "kassai"]) || $hasClass("warrior") => "warrior",
        str_contains($name, "oscilio") || $hasClass("wizard") => "wizard",
        default => "default",
      };
      [$equipment, $flex] = match ($matchup) {
        "marionette", "hyper-aggro", "dash" => [$defender, $defensivePackage],
        "aggressive-defender" => [$defender, [...$graces, ...$swords]],
        "levia" => [$defender, [...$graces, ...$shields, $sink, $sink]],
        "marlynn" => [$defender, [...$graces, $sink, $sink, $sink, $steelhand]],
        "aurora" => [["arms" => "solforge_gauntlet"] + $defender, $defensivePackage],
        "viserai" => [["head" => "circlet_of_eternal_end", "arms" => "solforge_gauntlet"] + $defender, $defensivePackage],
        "vynnset" => [["head" => "circlet_of_eternal_end", "arms" => "solforge_gauntlet"] + $aggressor, [...$aggressive, ...$graces]],
        "wizard" => [["arms" => "solforge_gauntlet", "legs" => "snapdragon_scalers"] + $aggressor, $aggressive],
        "victor" => [$aggressor, [...$aggressive, ...$shields]],
        "jarl" => [$aggressor, [...$aggressive, ...$shields, $sink]],
        "valda" => [["chest" => "soulbond_resolve"] + $aggressor, [...$swords, ...$graces, ...$shields, $sink]],
        "fatigue", "zyggy" => [["legs" => "snapdragon_scalers"] + $aggressor, $aggressive],
        "dorinthea" => [["head" => "kabuto_of_imperial_authority"] + $aggressor, [...$graces, $sink, $sink, $sink, ...$swords]],
        "warrior" => [["head" => "kabuto_of_imperial_authority"] + $aggressor, [...$graces, ...$swords, $tenacity, $sink, $sink]],
        "fang" => [["head" => "kabuto_of_imperial_authority", "arms" => "solforge_gauntlet"] + $aggressor, $aggressive],
        "olympia" => [$aggressor, [...$aggressive, ...$graces]],
        "gravy" => [$aggressor, $aggressive],
        default => [$aggressor, [$edict, $edict, $steelhand, $steelhand, $tenacity, $grace, $grace]],
      };
      array_push($deck, ...$flex);
      break;

    case "AuroraCC":
      $beatdown = ["flittering_charge_red", "flittering_charge_red", "flittering_charge_red", "electrostatic_discharge_red", "electrostatic_discharge_red", "electrostatic_discharge_red", "lightning_press_red", "lightning_press_red", "lightning_press_red"];
      foreach ($beatdown as $cardID) BotRemoveCopies($deck, $cardID, 1);
      $racing = preg_match('/\b(?:cindra|fai|kayo|rhinar|katsu|dash|ira|levia|boltyn|fang|kassai|zen|briar|aurora|marlynn|azalea)\b|arakni, marionette/', $name) == 1;
      $arcane = $hasClass("wizard") || $hasClass("runeblade") || preg_match('/\b(?:kano|blaze|iyslander|oscilio|briar|viserai|aurora|vynnset|chane|florian)\b/', $name) == 1;
      array_push($deck, ...($racing
        ? ["sigil_of_suffering_red", "sigil_of_suffering_red", "sigil_of_suffering_red", "sink_below_red", "sink_below_red", "sink_below_red", "rush_of_power_red", "rush_of_power_red", "scar_for_a_scar_red"]
        : $beatdown));
      $equipment = [
        "head" => "crown_of_providence",
        "chest" => $arcane ? "dyadic_carapace" : "aether_ironweave",
        "arms" => $arcane ? "vexing_quillhand" : "grasp_of_the_arknight",
        "legs" => "snapdragon_scalers",
      ];
      break;

    case "FangCC":
      $stance = preg_match('/\b(?:cindra|kayo|katsu|kassai|fai|dash|levia|boltyn|zen|marlynn|azalea|aurora)\b/', $name) == 1 ? "defensive"
        : (preg_match('/\b(?:arakni|florian|gravy bones|jarl|verdance|oscilio|iyslander|kano|bravo|betsy|victor|olympia|dromai|prism|enigma|nuu|uzuri|teklovossen|vynnset|chane|oldhim)\b/', $name) == 1
          || $hasClass("wizard") || $hasClass("guardian") ? "aggro" : "default");
      $head = match (true) {
        preg_match('/\b(?:cindra|fang|kassai|dorinthea|hala|ira)\b/', $name) == 1 => "kabuto_of_imperial_authority",
        preg_match('/\b(?:verdance|oscilio|iyslander|kano|aurora|vynnset)\b/', $name) == 1 || $hasClass("wizard") => "arcanite_skullcap",
        preg_match('/\b(?:gravy bones|jarl|kayo|rhinar|katsu|olympia|betsy)\b/', $name) == 1 || $hasClass("brute") || $hasClass("pirate") => "balance_of_justice",
        default => "crown_of_providence",
      };
      if ($stance == "aggro") {
        BotSwapCards($deck, [["sink_below_red", 3], ["smoke_out_red", 3]], ["blaze_headlong_red", "blaze_headlong_red", "blaze_headlong_red", "sworn_vengeance_red", "sworn_vengeance_red", "sworn_vengeance_red"]);
      } else if ($stance == "defensive") {
        BotSwapCards($deck, [["fire_and_brimstone_red", 1], ["imperial_intent_red", 2]], ["fate_foreseen_red", "fate_foreseen_red", "fate_foreseen_red"]);
      }
      if (preg_match('/\b(?:arakni|jarl|verdance|oldhim|victor|prism)\b/', $name) == 1) {
        if ($head == "arcanite_skullcap" && in_array("imperial_intent_red", $deck, true)) BotRemoveCopies($deck, "imperial_intent_red", 2);
        array_push($deck, "parched_terrain_red", "parched_terrain_red");
      }
      $equipment = ["head" => $head, "chest" => "grains_of_bloodspill", "arms" => "braveforge_bracers", "legs" => "valiant_dynamo"];
      break;

    default:
      return null;
  }

  return [
    "hero" => $pool["hero"],
    "weapons" => $weapons,
    "equipment" => $equipment,
    "deck" => array_values($deck),
    "inventory" => $inventory,
  ];
}

function BotDeckFileText($presentation)
{
  $character = [$presentation["hero"], ...$presentation["weapons"]];
  foreach (["head", "chest", "arms", "legs"] as $slot) {
    if (($presentation["equipment"][$slot] ?? "") !== "") $character[] = $presentation["equipment"][$slot];
  }
  return implode(" ", $character) . "\r\n" . implode(" ", $presentation["deck"]) . str_repeat("\r\n", 10) . implode(" ", $presentation["inventory"]);
}

function BotPresentForOpponent($gameName, $opponentHeroID, $botGoesFirst)
{
  $path = __DIR__ . "/../Games/" . $gameName . "/p2Deck.txt";
  $handler = @fopen($path, "r");
  if ($handler === false) return;
  $character = explode(" ", trim((string)fgets($handler)));
  fclose($handler);
  $key = BotDeckKeyForHero($character[0] ?? "");
  if ($key === null) return;
  $presentation = BotPresentDeck($key, strval($opponentHeroID), $botGoesFirst ? "first" : "second");
  if ($presentation !== null) file_put_contents($path, BotDeckFileText($presentation));
}
