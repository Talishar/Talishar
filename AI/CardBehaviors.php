<?php

/**
 * CardBehaviors.php - Organized card priority database
 * 
 * This file contains all card priority definitions organized by hero.
 * Format is much cleaner than the original - each hero gets a config section,
 * and cards are grouped by behavior/purpose.
 * 
 * PRIORITY VALUE RANGES:
 * [0] = Block Priority
 *   0 = won't block
 *   0.1-0.9 = will block if lethal or efficient  
 *   10.1-10.9 = highest priority (resolves to 0.1-0.9 + 2.1-2.9)
 * 
 * [1] = Action Priority  
 *   0 = won't play
 *   0.1-0.9 = normal plays (higher first)
 *   10.1-10.9 = priority plays (highest resolves to 0, rest to 0.1-0.9)
 * 
 * [2] = Arsenal Priority
 *   0 = won't arsenal
 *   0.1-0.9 = will arsenal (higher first)
 * 
 * [3] = Reaction Priority
 *   0 = won't play
 *   0.1-0.9 = normal reactions (higher first)
 *   10.1-10.9 = priority reactions (highest resolves to 0, rest to 0.1-0.9)
 * 
 * [4] = Arsenal Reaction Priority
 *   0 = won't play
 *   0.1-0.9 = will play (higher first)
 * 
 * [5] = Pitch Priority
 *   0 = won't pitch
 *   0.1-X.Y = will pitch (higher first)
 * 
 * [6] = Arsenal Priority
 *   0 = won't arsenal
 *   0.1-0.9 = will arsenal (higher first)
 * 
 * [7] = Permanent Ability Priority
 *   0 = won't activate
 *   0.1-0.9 = will activate (higher first)
 */

/**
 * Get card priority values for a specific card and hero
 * 
 * @param string $cardID - Card ID to get priorities for
 * @param string $heroID - Hero ID
 * @return array - Priority array [block, action, arsenal_action, reaction, arsenal_reaction, pitch, arsenal, permanent]
 */
function GetCardBehavior($cardID, $heroID)
{
  $behavior = GetCardBehaviorForHero($heroID);
  
  if(isset($behavior[$cardID])) {
    return $behavior[$cardID];
  }
  
  // Return neutral default
  return [0, 0, 0, 0, 0, 0, 0, 0];
}

/**
 * Get all card behaviors for a specific hero
 */
function GetCardBehaviorForHero($heroID)
{
  switch($heroID) {
    case "ira_crimson_haze":
      return GetIraBehaviors();
    case "fai_rising_rebellion":
      return GetFaiBehaviors();
    case "lexi_rowdeez":
      return GetLexiBehaviors();
    default:
      return [];
  }
}

/**
 * IRA CRIMSON HAZE - Blue combo focused hero
 * Strategy: Setup blue combos, block efficiently with combos
 */
function GetIraBehaviors()
{
  return [
    // ===== HERO HERO ABILITY =====
    "ira_crimson_haze" => [0, 0, 0, 0, 0, 0, 0, 0],
    
    // ===== KODACHIS - High Priority Equipment =====
    "harmonized_kodachi" => ComputeKodachiPriority(2), // Computed based on hand state
    
    // ===== BLUE COMBO CARDS - Setup for value =====
    "fluster_fist_blue"       => [0.85, 0.8, 0.8, 0.5, 0, 2.5, 0.1, 0],
    "back_heel_kick_blue"     => [0.85, 0.75, 0.75, 0.4, 0, 2.5, 0.1, 0],
    "crane_dance_blue"        => [0.8, 0.7, 0.7, 0.3, 0, 2.4, 0.1, 0],
    "find_center_blue"        => [0.85, 0.8, 0.8, 0.3, 0, 2.4, 0.1, 0],
    "rising_knee_thrust_blue" => [0.8, 0.75, 0.75, 0.3, 0, 2.5, 0.1, 0],
    "rushing_river_blue"      => [0.75, 0.7, 0.7, 0.2, 0, 2.4, 0.1, 0],
    "whelming_gustwave_blue"  => [0.8, 0.75, 0.75, 0.2, 0, 2.5, 0.1, 0],
    "winds_of_eternity_blue"  => [0.75, 0.7, 0.7, 0.2, 0, 2.5, 0.1, 0],
    
    // ===== RED ATTACKS - Good damage output =====
    "censor_red"              => [0.5, 0.7, 0.7, 0.3, 0, 1.1, 0.8, 0],
    "fluster_fist_red"        => [0.6, 0.4, 0.4, 0.3, 0, 1.2, 0.7, 0],
    "soulbead_strike_red"     => [0.4, 0.8, 0.8, 0.3, 0, 1.2, 0.7, 0],
    "torrent_of_tempo_red"    => [0.5, 0.9, 0.9, 0.3, 0, 1.2, 0.7, 0],
    "mauling_qi_red"          => [0.6, 0.5, 0.5, 0.3, 0, 1.2, 0.7, 0],
    "pounding_gale_red"       => [0.6, 0.5, 0.5, 0.3, 0, 1.2, 0.7, 0],
    
    // ===== DEFENSE REACTIONS - High block value =====
    "flic_flak_red"           => [0.9, 0.0, 0.0, 0.9, 0.9, 1.5, 0.9, 0],
    "fate_foreseen_red"       => [0.9, 0.0, 0.0, 0.9, 0.9, 1.5, 0.9, 0],
    "sink_below_red"          => [0.9, 0.0, 0.0, 0.9, 0.9, 1.5, 0.9, 0],
    "that_all_you_got_yellow" => [0.9, 0.0, 0.0, 0.9, 0.9, 2.5, 0.8, 0],
    "flic_flak_yellow"        => [0.9, 0.0, 0.0, 0.9, 0.9, 2.5, 0.8, 0],
    "flic_flak_blue"          => ComputeFlichFlakBluePriority(2), // Computed: depends on blue count
    
    // ===== EQUIPMENT - Generally don't activate in encounters =====
    "tide_flippers"           => [0, 0, 0, 0, 0, 0, 0, 0],
    "flick_knives"            => [0, 0, 0, 0, 0, 0, 0, 0],
    "fyendals_spring_tunic"   => [0, 0, 0, 0, 0, 0, 0, 0],
    "mask_of_momentum"        => [0, 0, 0, 0, 0, 0, 0, 0],
  ];
}

/**
 * FAI RISING REBELLION - Draconic chain focused hero
 * Strategy: Build draconic chains, play efficient attacks
 */
function GetFaiBehaviors()
{
  return [
    // ===== HERO ABILITY =====
    "fai_rising_rebellion" => ComputeFaiHeroPriority(2), // Computed based on draconic chains
    
    // ===== KODACHI =====
    "harmonized_kodachi" => ComputeKodachiPriority(2),
    
    // ===== DRACONIC CHAIN STARTERS - Play early for setup =====
    "art_of_war_yellow" => ComputeArtOfWarPriority(2),
    
    // ===== HIGH-VALUE ATTACKS - Ravenous Rabble theme =====
    "ravenous_rabble_red"     => [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0],
    "bittering_thorns_red"    => [0.1, 0.85, 0.85, 0, 0, 1.5, 0.5, 0],
    "mounting_anger_red"      => [0.1, 0.85, 0.85, 0, 0, 1.5, 0.5, 0],
    "brand_with_cinderclaw_red"    => [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0],
    "brand_with_cinderclaw_yellow" => [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0],
    "brand_with_cinderclaw_blue"   => [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0],
    
    // ===== OTHER GOOD ATTACKS =====
    "rising_resentment_red"   => [0.1, 0.9, 0.9, 0, 0, 1.1, 0.5, 0],
    "ronin_renegade_red"      => [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0],
    "blaze_headlong_red"      => [0.1, 0.7, 0.7, 0, 0, 1.1, 0.6, 0],
    "lava_burst_red"          => [0.1, 0.1, 0.1, 0, 0, 1.1, 0.7, 0],
    "phoenix_flame_red"       => [0, 0.7, 0.7, 0, 0, 0.1, 0.2, 0],
    "double_strike_red"       => [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0],
    "ancestral_empowerment_red" => [0.1, 0.9, 0.9, 0, 0, 1.1, 0.5, 0],
    "snatch_red"              => [0.1, 0.1, 0.1, 0, 0, 1.1, 0.7, 0],
    "command_and_conquer_red" => [0.1, 0.1, 0.1, 0, 0, 1.1, 0.7, 0],
    "spreading_flames_red"    => [0.1, 1.0, 1.0, 0, 0, 1.1, 0.5, 0],
    
    // ===== PITCH CARDS - Low play value, high pitch value =====
    "salt_the_wound_yellow"   => [0.5, 0.1, 0.1, 0, 0, 2.5, 0.2, 0],
    "tenacity_yellow"         => [0.5, 0.1, 0.1, 0, 0, 2.5, 0.2, 0],
    
    // ===== BLUE CARDS - High pitch, can play if setup =====
    "soulbead_strike_blue"    => [0.5, 0.3, 0.3, 0, 0, 3.5, 0.2, 0],
    "warmongers_diplomacy_blue" => [0.8, 0.1, 0.1, 0, 0, 3.5, 0.1, 0],
    "stab_wound_blue"         => [0.8, 0.4, 0.4, 0, 0, 3.5, 0.1, 0],
    "lava_vein_loyalty_blue"  => [0.8, 0.5, 0.5, 0, 0, 3.1, 0.1, 0],
    
    // ===== SPECIAL CARDS =====
    "snapdragon_scalers" => ComputeSnapdragonPriority(2), // Computed: depends on go again
  ];
}

/**
 * LEXI ROWDEEZ - Pepper focused hero (placeholder)
 * Strategy: Play defensive, setup pepper counters
 */
function GetLexiBehaviors()
{
  return [
    // Placeholder for Lexi - can expand later
  ];
}

/**
 * Computed priorities - these update based on game state
 * This allows for smarter, context-aware decisions
 */

function ComputeKodachiPriority($playerID)
{
  $resources = &GetResources($playerID);
  $blueCount = SearchCount(SearchHand($playerID, "pitch", 3));
  
  // If we have blue or excess resources, activate kodachi
  $shouldActivate = ($blueCount > 0 || $resources[0] > 1);
  
  return [0, $shouldActivate ? 0.95 : 0.1, 0, 0, 0, 0, 0, $shouldActivate ? 0.95 : 0.1];
}

function ComputeFlichFlakBluePriority($playerID)
{
  $blueCount = SearchCount(SearchHand($playerID, "pitch", 3));
  
  return [0.6, 0.0, 0.0, $blueCount > 1 ? 0.9 : 0.1, $blueCount > 1 ? 0.9 : 0, 2.9, 0.8, 0];
}

function ComputeFaiHeroPriority($playerID)
{
  $resources = &GetResources($playerID);
  $chainLinks = NumDraconicChainLinks();
  
  $priorityValue = ($chainLinks >= 3 || ($resources[0] > $chainLinks && $chainLinks > 0)) ? 1.0 : 0.0;
  return [0, $priorityValue, 0, 0, 0, 0, 0, $priorityValue];
}

function ComputeArtOfWarPriority($playerID)
{
  $resources = &GetResources($playerID);
  $blueCount = SearchCount(SearchHand($playerID, "pitch", 3));
  
  $playPriority = (!ArsenalEmpty($playerID) && $blueCount > 0) ? 1.0 : 0.0;
  return [0, $playPriority, $playPriority, 0, 0, 2.5, 1.0, 0];
}

function ComputeSnapdragonPriority($playerID)
{
  // Snapdragon Scalers: Only activate as permanent ability (index 7)
  // if we DON'T already have go again
  // All other priorities should be 0 (don't use for anything else)
  
  $hasGoAgain = DoesAttackHaveGoAgain();
  $resources = &GetResources($playerID);
  $hand = &GetHand($playerID);
  
  // Only activate if:
  // 1. We don't have go again yet
  // 2. We have resources/cards to continue (rough check)
  $shouldActivate = !$hasGoAgain && (count($hand) > 0 || $resources[0] > 0);
  
  return [0, 0, 0, 0, 0, 0, 0, $shouldActivate ? 0.85 : 0];
}

?>
