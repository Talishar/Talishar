<?php

include "Constants.php";
include "CardDictionaries/WelcomeToRathe/WTRShared.php";
include "CardDictionaries/ArcaneRising/ARCShared.php";
include "CardDictionaries/ArcaneRising/ARCGeneric.php";
include "CardDictionaries/ArcaneRising/ARCMechanologist.php";
include "CardDictionaries/ArcaneRising/ARCRanger.php";
include "CardDictionaries/ArcaneRising/ARCRuneblade.php";
include "CardDictionaries/ArcaneRising/ARCWizard.php";
include "CardDictionaries/CrucibleOfWar/CRUShared.php";
include "CardDictionaries/Monarch/MONShared.php";
include "CardDictionaries/Monarch/MONGeneric.php";
include "CardDictionaries/Monarch/MONBrute.php";
include "CardDictionaries/Monarch/MONIllusionist.php";
include "CardDictionaries/Monarch/MONRuneblade.php";
include "CardDictionaries/Monarch/MONWarrior.php";
include "CardDictionaries/Monarch/MONTalent.php";
include "CardDictionaries/TalesOfAria/ELEShared.php";
include "CardDictionaries/TalesOfAria/ELEGuardian.php";
include "CardDictionaries/TalesOfAria/ELERanger.php";
include "CardDictionaries/TalesOfAria/ELERuneblade.php";
include "CardDictionaries/TalesOfAria/ELETalent.php";
include "CardDictionaries/Everfest/EVRShared.php";
include "CardDictionaries/Uprising/UPRShared.php";
include "CardDictionaries/card_names.php";
include "CardDictionaries/PlayAbilities.php";
include "CardDictionaries/HitEffects.php";
include "CardDictionaries/CurrentEffects.php";
include "CardDictionaries/ActivatedAbilities.php";
include "CardDictionaries/Keywords.php";
include "CardDictionaries/ClassicBattles/DVRShared.php";
include "CardDictionaries/ClassicBattles/RVDShared.php";
include "CardDictionaries/Dynasty/DYNShared.php";
include "CardDictionaries/Outsiders/OUTShared.php";
include "CardDictionaries/DuskTillDawn/DTDShared.php";
include "CardDictionaries/FirstStrike/AURShared.php";
include "CardDictionaries/FirstStrike/TERShared.php";
include "CardDictionaries/PartTheMistveil/MSTShared.php";
include "CardDictionaries/ArmoryDecks/AAZShared.php";
include "CardDictionaries/Rosetta/ROSShared.php";
include "CardDictionaries/ArmoryDecks/AIOShared.php";
include "CardDictionaries/ArmoryDecks/AJVShared.php";
include "CardDictionaries/Hunted/HNTShared.php";
include "CardDictionaries/ArmoryDecks/ASTShared.php";
include "CardDictionaries/ArmoryDecks/AMXShared.php";
include "CardDictionaries/HighSeas/SEAShared.php";
include "CardDictionaries/MasteryPacks/MPGShared.php";
include "CardDictionaries/ArmoryDecks/AGBShared.php";
include "CardDictionaries/ArmoryDecks/ASRShared.php";
include "GeneratedCode/GeneratedCardDictionaries.php";
include "CardDictionaries/SuperSlam/SUPShared.php";
include "CardDictionaries/CompendiumOfRathe/PENShared.php";
include "CardDictionaries/ArmoryDecks/APSShared.php";
include "CardDictionaries/ArmoryDecks/AACShared.php";
include "CardDictionaries/ArmoryDecks/ARRShared.php";
include "CardDictionaries/ArmoryDecks/AHAShared.php";

$CID_BloodRotPox = "bloodrot_pox";
$CID_Frailty = "frailty";
$CID_Inertia = "inertia";

$CID_TekloHead = "teklo_base_head";
$CID_TekloChest = "teklo_base_chest";
$CID_TekloArms = "teklo_base_arms";
$CID_TekloLegs = "teklo_base_legs";

function CardType($cardID, $from="", $controller="-", $additionalCosts="-")
{
  global $CS_AdditionalCosts, $currentPlayer;
  $cardID = BlindCard($cardID, true);
  $controller = $controller == "-" ? $currentPlayer : $controller;
  $adminCards = ["TRIGGER", "-", "FINALIZECHAINLINK", "RESOLUTIONSTEP", "ENDTURN", "DEFENDSTEP", "CLOSINGCHAIN", "STARTTURN", "ATTACKSTEP"];
  if (!$cardID || in_array($cardID, $adminCards)) return "";
  
  // Handle meld cards
  $meldCards = [
    "thistle_bloom__life_yellow",
    "arcane_seeds__life_red",
    "burn_up__shock_red",
    "pulsing_aether__life_red",
    "comet_storm__shock_red",
    "regrowth__shock_blue",
    "everbloom__life_blue",
    "consign_to_cosmos__shock_yellow"
  ];

  if (in_array($cardID, $meldCards)) {
    if ($from == "DECK" || $from == "DISCARD" || $from == "BANISH" || $from == "HAND" || $from == "ARS" || $from == "CC") return "A,I";
    if (function_exists("GetClassState")) {      
      $additionalCosts = $additionalCosts == "-" ? GetClassState($controller, $CS_AdditionalCosts) : $additionalCosts;
      if ($additionalCosts == "Both") return "A,I";
      if (IsMeldInstantName($additionalCosts)) return "I";
      if (IsMeldActionName($additionalCosts)) return "A";
    }
    return "A,I";
  }

  // Handle special cases
  $specialCases = [
    "UPR551" => "-",
    "nitro_mechanoida" => "W",
    "nitro_mechanoidb" => "E",
    "teklovossen_the_mechropotentb" => "E",
    "teklovossen_the_mechropotent" => "C",
    "levia_redeemed" => "D",
    "blasmophet_levia_consumed" => "D",
    "suraya_archangel_of_knowledge" => "-",
    "DUMMY" => "C",
    "DUMMYDISHONORED" => "C",
    "tusk" => "W", // AI custom weapon
    "wrenchtastic" => "W", // AI custom weapon
    "aegis_archangel_of_protection" => "-",
    "avalon_archangel_of_rebirth" => "-",
    "bellona_archangel_of_war" => "-",//hardcoded for now
    "metis_archangel_of_tenacity" => "-",
    "sekem_archangel_of_ravages" => "-",
    "suraya_archangel_of_erudition" => "-",
    "themis_archangel_of_judgment" => "-",
    "victoria_archangel_of_triumph" => "-",
    "azvolai" => "-",
    "cromai" => "-",
    "dominia" => "-",
    "kyloria" => "-",
    "miragai" => "-",
    "nekria" => "-",
    "ouvia" => "-",
    "themai" => "-",
    "tomeltai" => "-",
    "vynserakai" => "-",
    "yendurai" => "-",
    "dracona_optimai" => "-",
  ];
  $card = GetClass($cardID, 0);
  if ($card != "-") {
    $specialType = $card->SpecialType();
    if ($specialType != "-") return $specialType;
  }
  if (isset($specialCases[$cardID])) {
    return $specialCases[$cardID];
  }
  $set = CardSet($cardID);
  if ($set != "DUM") {
    $setID = SetID($cardID);
    $number = intval(substr($setID, 3));
    if ($number < 400) return GeneratedCardType($cardID);
    if ($set != "MON" && $set != "DYN" && $set != "HNT" && $setID != "UPR551" && 
        $cardID != "teklovossen_the_mechropotent" && $cardID != "teklovossen_the_mechropotentb") {
      return GeneratedCardType($cardID);
    }
  }
  return GeneratedCardType($cardID);
}

function CardTypeExtended($cardID, $from="") // used to handle evos
{
  $cardID = BlindCard($cardID, true);
  $evoTypes = [
    "evo_steel_soul_memory_blue" => "A,E",
    "evo_steel_soul_processor_blue" => "A,E",
    "evo_steel_soul_controller_blue" => "A,E",
    "evo_steel_soul_tower_blue" => "A,E",
    "evo_steel_soul_memory_blue_equip" => "A,E",
    "evo_steel_soul_processor_blue_equip" => "A,E",
    "evo_steel_soul_controller_blue_equip" => "A,E",
    "evo_steel_soul_tower_blue_equip" => "A,E",
    
    "evo_data_mine_yellow" => "A,E",
    "evo_battery_pack_yellow" => "A,E",
    "evo_cogspitter_yellow" => "A,E",
    "evo_charging_rods_yellow" => "A,E",
    "evo_data_mine_yellow_equip" => "A,E",
    "evo_battery_pack_yellow_equip" => "A,E",
    "evo_cogspitter_yellow_equip" => "A,E",
    "evo_charging_rods_yellow_equip" => "A,E",
    
    "evo_command_center_yellow" => "A,E",
    "evo_engine_room_yellow" => "A,E",
    "evo_smoothbore_yellow" => "A,E",
    "evo_thruster_yellow" => "A,E",
    "evo_command_center_yellow_equip" => "A,E",
    "evo_engine_room_yellow_equip" => "A,E",
    "evo_smoothbore_yellow_equip" => "A,E",
    "evo_thruster_yellow_equip" => "A,E",
    
    "evo_magneto_blue" => "A,E",
    "evo_magneto_blue_equip" => "A,E",
    
    "evo_circuit_breaker_red" => "I,E",
    "evo_atom_breaker_red" => "I,E",
    "evo_face_breaker_red" => "I,E",
    "evo_mach_breaker_red" => "I,E",
    "evo_circuit_breaker_red_equip" => "I,E",
    "evo_atom_breaker_red_equip" => "I,E",
    "evo_face_breaker_red_equip" => "I,E",
    "evo_mach_breaker_red_equip" => "I,E",
    
    "evo_zoom_call_yellow" => "I,E",
    "evo_buzz_hive_yellow" => "I,E",
    "evo_whizz_bang_yellow" => "I,E",
    "evo_zip_line_yellow" => "I,E",
    "evo_zoom_call_yellow_equip" => "I,E",
    "evo_buzz_hive_yellow_equip" => "I,E",
    "evo_whizz_bang_yellow_equip" => "I,E",
    "evo_zip_line_yellow_equip" => "I,E",
    
    "evo_recall_blue" => "I,E",
    "evo_heartdrive_blue" => "I,E",
    "evo_shortcircuit_blue" => "I,E",
    "evo_speedslip_blue" => "I,E",
    "evo_recall_blue_equip" => "I,E",
    "evo_heartdrive_blue_equip" => "I,E",
    "evo_shortcircuit_blue_equip" => "I,E",
    "evo_speedslip_blue_equip" => "I,E"
  ];

  return $evoTypes[$cardID] ?? CardType($cardID, $from);
}

function SetID($cardID)
{
  $cardID = BlindCard($cardID, true);
  $specialCases = [
    "teklovossen_the_mechropotentb" => GeneratedSetID("teklovossen_the_mechropotent"),
    "nitro_mechanoida" => GeneratedSetID("nitro_mechanoid"),
    "nitro_mechanoidb" => GeneratedSetID("nitro_mechanoid"),
    "nitro_mechanoidc" => GeneratedSetID("nitro_mechanoid"),
    "the_hand_that_pulls_the_strings" => "HNT407",
    "valda_seismic_impact" => "HER135",
    "tusk" => "DUM", // AI custom weapon
    "wrenchtastic" => "DUM", // AI custom weapon
    "UPR551" => "UPR551", //ghostly touch
  ];

  return $specialCases[$cardID] ?? GeneratedSetID($cardID);
}

//converts cardIDs to setIDs, retaining any trailing tags
function ConvertToSetID($cardID) {
  $cardID = BlindCard($cardID, true);
  $bareCardID = ExtractCardID($cardID);
  $tags = substr($cardID, strlen($bareCardID));
  return SetID($bareCardID) . $tags;
}

function SetIDtoCardID($setID)
{
  return GeneratedSetIDtoCardID($setID);
}

function ConvertToCardID($setID) {
  $bareSetID = substr($setID, 0, 6);
  $tags = substr($setID, strlen($bareSetID));
  $setID = SetIDtoCardID($bareSetID);
  if ($setID == "") return "";
  return $setID . $tags;
}

function CardSubType($cardID, $uniqueID = -1)
{
  $cardID = BlindCard($cardID, true);
  if (!$cardID) return "";
  switch ($cardID) {
    case "sanctuary_of_aria"://Technically false, but helps with Rosetta Limited
      return "Item";
    case "UPR439":
    case "UPR440":
    case "UPR441": //resolved sand cover
      return "Ash";
    case "UPR551":
      return "Ally";
    case "blasmophet_levia_consumed":
      return "Demon";
    case "teklovossen_the_mechropotent":
      return "Evo";
    case "kiss_of_death_red":
      return "Dagger,Attack";
    case "polly_cranka_ally":
    case "sticky_fingers_ally":
      return "Ally";
    case "suraya_archangel_of_knowledge":
      return "Angel,Ally";
    default:
      break;
  }
  //equipment that could go in any zone
  $adaptive = ["adaptive_plating", "adaptive_dissolver", "adaptive_alpha_mold", "frostbite"];
  if ($uniqueID > -1 && (IsModular($cardID) || $cardID == "frostbite")) {
    global $currentTurnEffects;
    $countCurrentTurnEffects = count($currentTurnEffects);
    $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
    for ($i = 0; $i < $countCurrentTurnEffects; $i += $currentTurnEffectsPieces) {
      $effectArr = explode("-", $currentTurnEffects[$i]);
      if (!in_array($effectArr[0], $adaptive)) continue;
      $effectArr = explode(",", $effectArr[1]);
      if ($effectArr[0] != $uniqueID) continue;
      if($effectArr[1] == "Base") return $effectArr[2];
      return $effectArr[1];
    }
    return "";
  }
  $set = CardSet($cardID);
  if ($set != "DUM") {
    $setID = SetID($cardID);
    $number = intval(substr($setID, 3));
    if ($number < 400) return GeneratedCardSubtype($cardID);
    else if (
      $set != "MON" && $set != "DYN" && $cardID != "UPR551" && $cardID != "nitro_mechanoidc" && $cardID != "teklovossen_the_mechropotent" && $cardID != "teklovossen_the_mechropotentb")
      return GeneratedCardSubtype($cardID);
  }
  switch ($cardID) {
    case "UPR551":
      return "Ally";
    case "nitro_mechanoidb":
      return "Chest"; // Technically not true, but needed to work.
    case "nitro_mechanoidc":
      return "Item";
    case "teklovossen_the_mechropotentb":
      return "Chest,Evo";
    default:
      return "";
  }
}

function CharacterHealth($cardID)
{
  $cardID = BlindCard($cardID, true);
  switch ($cardID) {
    case "valda_seismic_impact":
      return 40;
    default:
      break;
  }
  $set = CardSet($cardID);
  if ($set != "DUM") return GeneratedCharacterHealth($cardID);
  return match ($cardID) {
    "DUMMY" => 40,
    default => 20,
  };
}

function CharacterIntellect($cardID)
{
  $cardID = BlindCard($cardID, true);
  $cardID = ShiyanaCharacter($cardID);
  return GeneratedCharacterIntellect($cardID);
}

function CardSet($cardID)
{
  $cardID = BlindCard($cardID, true);
  if (!$cardID) return "";
  if (substr($cardID, 0, 3) == "DUM") return "DUM";
  switch ($cardID) {
    case "kunai_of_retribution_r"://these cards are from promo packs, this is needed to find their code
    case "obsidian_fire_vein_r":
    case "mark_of_the_huntsman_r":
    case "hunters_klaive_r":
      return "HNT";
    case "valda_seismic_impact":
    case "draw_a_crowd_blue":
    case "promising_terrain_blue":
    case "batter_to_a_pulp_red":
      return "MPG";
    case "polly_cranka": case "polly_cranka_ally":
    case "sticky_fingers": case "sticky_fingers_ally":
      return "SEA";
    case "okana_scar_wraps": case "iris_of_the_blossom":
      return "ASR";
    default:
      $setID = SetID(ExtractCardID($cardID));
      return substr($setID, 0, 3);
  }
}

function CardClass($cardID)
{
  global $currentPlayer, $CS_AdditionalCosts;
  $cardID = BlindCard($cardID, true);
  if (!$cardID) return "";
  switch ($cardID) {
    case "thistle_bloom__life_yellow":
    case "arcane_seeds__life_red":
    case "vaporize__shock_yellow":
    case "burn_up__shock_red":
      if(function_exists("GetClassState")) {
        if (IsMeldInstantName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "NONE";
      }
      return "RUNEBLADE";
    case "rampant_growth__life_yellow":
    case "pulsing_aether__life_red":
    case "null__shock_yellow":
    case "comet_storm__shock_red":
    case "consign_to_cosmos__shock_yellow":
      if(function_exists("GetClassState")) {
        if (IsMeldInstantName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "NONE";
      }
      return "WIZARD";
    case "regrowth__shock_blue":
      if(function_exists("GetClassState")) {
        if (IsMeldInstantName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "NONE";
      }
      return "RUNEBLADE";
    case "everbloom__life_blue":
      return "NONE";
    default:
      break;
  }
  $setID = SetID($cardID);
  $number = intval(substr($setID, 3));
  if ($number >= 400) {
    $set = substr($setID, 0, 3);
    switch ($set) {
      case "MON":
        if ($number == 404) return "ILLUSIONIST";
        else if ($number == 405) return "WARRIOR";
        else if ($number == 406) return "BRUTE";
        else if ($number == 407) return "RUNEBLADE";
        else return "NONE";
      case "UPR":
        if ($number >= 406 && $number <= 417) return "ILLUSIONIST";
        else if ($number >= 439 && $number <= 441) return "ILLUSIONIST";
        else if ($number == 551) return "ILLUSIONIST";
        else return "NONE";
    }
  }
  switch ($cardID) {
    case "nitro_mechanoida":
    case "nitro_mechanoidb":
    case "nitro_mechanoidc":
      return "MECHANOLOGIST";
    case "teklovossen_the_mechropotentb":
      return "MECHANOLOGIST";
    default:
      break;
  }
  $card = GetClass($cardID, 0);
  if ($card != "-" && $card->SpecialClass() != "-") return $card->SpecialClass();
  return GeneratedCardClass($cardID);
}

function CardTalent($cardID, $from="-")
{
  global $currentPlayer, $CS_AdditionalCosts;
  $cardID = BlindCard($cardID, true);
  if (!$cardID) return "";
  switch ($cardID) {
    case "thistle_bloom__life_yellow":
    case "arcane_seeds__life_red":
    case "rampant_growth__life_yellow":
    case "pulsing_aether__life_red":
      if(function_exists("GetClassState") && $from == "-") {
        if(IsMeldLeftSideName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "NONE";
        return "EARTH";
      }
      return "EARTH";
    case "vaporize__shock_yellow":
    case "burn_up__shock_red":
    case "null__shock_yellow":
    case "comet_storm__shock_red":
      if(function_exists("GetClassState") && $from == "-") {
        if(IsMeldLeftSideName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "NONE";
        return "LIGHTNING";        
      }
      return "LIGHTNING";
    case "regrowth__shock_blue":
      if(function_exists("GetClassState")) {
        if (IsMeldRightSideName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "LIGHTNING";
        elseif (IsMeldLeftSideName(GetClassState($currentPlayer, $CS_AdditionalCosts))) return "EARTH";
      }
      return "EARTH,LIGHTNING";
    case "consign_to_cosmos__shock_yellow":
      return "LIGHTNING";
    case "everbloom__life_blue":
      return "EARTH";
    default:
      break;
  }
  $setID = SetID($cardID);
  $set = substr($setID, 0, 3);
  $number = intval(substr($setID, 3));
  if ($number >= 400) {
    switch ($set) {
      case "MON":
        if ($number == 520) return "SHADOW";
        else return "NONE";
      case "UPR":
        if ($number >= 406 && $number <= 417) return "DRACONIC";
        else if ($number >= 439 && $number <= 441) return "DRACONIC";
        else return "NONE";
      case "DYN":
        if ($number == 612) return "LIGHT";
        else return "NONE";
    }
  }
  switch ($cardID) {
    case "teklovossen_the_mechropotent":
    case "teklovossen_the_mechropotentb":
      return "SHADOW";
    case "levia_redeemed":
    case "blasmophet_levia_consumed":
      return "SHADOW";
    default:
      break;
  }
  $card = GetClass($cardID, 0);
  if ($card != "-" && $card->SpecialTalent() != "-") return $card->SpecialTalent();
  return GeneratedCardTalent($cardID);
}

//Minimum cost of the card
function CardCost($cardID, $from="-")
{
  $cardID = BlindCard($cardID, true);
  $cardID = ShiyanaCharacter($cardID);
  $set = CardSet($cardID);
  switch ($cardID) {
    case "imposing_visage_blue":
      return 3;
    case "mighty_windup_red":
    case "mighty_windup_yellow":
    case "mighty_windup_blue":
    case "agile_windup_red":
    case "agile_windup_yellow":
    case "agile_windup_blue":
    case "vigorous_windup_red":
    case "vigorous_windup_yellow":
    case "fruits_of_the_forest_blue":
    case "fruits_of_the_forest_yellow":
    case "fruits_of_the_forest_red":
    case "vigorous_windup_blue":
    case "bam_bam_yellow":
    case "outside_interference_blue":
    case "fearless_confrontation_blue":
    case "burn_bare":
    case "light_up_the_leaves_red":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") return 0;
      return 3;
    case "ripple_away_blue":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") return 0;
      return 2;
    case "MST000_inner_chi_blue":
    case "MST010_inner_chi_blue":
    case "MST032_inner_chi_blue":
    case "MST053_inner_chi_blue":
    case "MST095_inner_chi_blue":
    case "MST096_inner_chi_blue":
    case "MST097_inner_chi_blue":
    case "MST098_inner_chi_blue":
    case "MST099_inner_chi_blue":
    case "MST100_inner_chi_blue":
    case "MST101_inner_chi_blue":
    case "MST102_inner_chi_blue":
      return -1;
    case "trip_the_light_fantastic_blue":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_red":
    case "tip_off_red":
    case "tip_off_yellow":
    case "tip_off_blue":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") return 0;
      return 1;
    case "haunting_rendition_red":
    case "mental_block_blue":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") return 0;
      return -1;
    case "chorus_of_the_amphitheater_red":
    case "chorus_of_the_amphitheater_yellow":
    case "chorus_of_the_amphitheater_blue":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") return 0;
      return 2;
    case "photon_splicing_red":
    case "photon_splicing_yellow":
    case "photon_splicing_blue":
      if (GetResolvedAbilityType($cardID, "HAND") == "I") return 0;
      return 1;
    case "two_sides_to_the_blade_red":
    case "roiling_fissure_blue":
      return 1;
    case "nitro_mechanoidc":
      return -1;
    default:
      break;
  }
  $card = GetClass($cardID, 0);
  if ($card != "-" && $card->SpecialCost() != -1) return $card->SpecialCost();
  elseif ($card != "-") return $card->CardCost($from);
  if ($set != "DUM") {
    return GeneratedCardCost($cardID);
  }
}

function AbilityCost($cardID)
{
  global $currentPlayer, $phase;
  $cardID = ShiyanaCharacter($cardID);
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  $subtype = CardSubtype($cardID);
  if ($cardID == "restless_coalescence_yellow") {
    $abilityType = GetResolvedAbilityType($cardID, "PLAY");
    if ($abilityType == "I") return 0;
  }
  if ($class == "ILLUSIONIST" && DelimStringContains($subtype, "Aura")) {
    if (SearchCharacterForCard($currentPlayer, "luminaris")) return 0;
    if (SearchCharacterForCard($currentPlayer, "iris_of_reality")) return 3;
    if (SearchCharacterForCard($currentPlayer, "reality_refractor")) return 2;
  }
  if (SearchCharacterForCard($currentPlayer, "cosmo_scroll_of_ancestral_tapestry") && HasWard($cardID, $currentPlayer) && DelimStringContains($subtype, "Aura")) return 1;

  if (DelimStringContains($subtype, "Dragon") && SearchCharacterActive($currentPlayer, "storm_of_sandikai")) return 0;
  if (class_exists($cardID)) {
    $card = new $cardID($currentPlayer);
    return $card->AbilityCost();
  }
  if ($set == "WTR") return WTRAbilityCost($cardID);
  else if ($set == "ARC") return ARCAbilityCost($cardID);
  else if ($set == "CRU") return CRUAbilityCost($cardID);
  else if ($set == "MON") return MONAbilityCost($cardID);
  else if ($set == "ELE") return ELEAbilityCost($cardID);
  else if ($set == "EVR") return EVRAbilityCost($cardID);
  else if ($set == "UPR") return UPRAbilityCost($cardID);
  else if ($set == "DVR") return DVRAbilityCost($cardID);
  else if ($set == "RVD") return RVDAbilityCost($cardID);
  else if ($set == "DYN") return DYNAbilityCost($cardID);
  else if ($set == "OUT") return OUTAbilityCost($cardID);
  else if ($set == "DTD") return DTDAbilityCost($cardID);
  else if ($set == "TCC") return TCCAbilityCost($cardID);
  else if ($set == "EVO") return EVOAbilityCost($cardID);
  else if ($set == "HVY") return HVYAbilityCost($cardID);
  else if ($set == "AKO") return AKOAbilityCost($cardID);
  else if ($set == "MST") return MSTAbilityCost($cardID);
  else if ($set == "ROS") return ROSAbilityCost($cardID);
  else if ($set == "TER") return TERAbilityCost($cardID);
  else if ($set == "AIO") return AIOAbilityCost($cardID);
  else if ($set == "AJV") return AJVAbilityCost($cardID);
  else if ($set == "HNT") return HNTAbilityCost($cardID);
  else if ($set == "AMX") return AMXAbilityCost($cardID);
  else if ($set == "SEA") return SEAAbilityCost($cardID);
  else if ($set == "AST") return ASTAbilityCost($cardID);
  else if ($set == "AGB") return AGBAbilityCost($cardID);
  else if ($set == "MPG") return MPGAbilityCost($cardID);
  else if ($set == "SUP") return SUPAbilityCost($cardID);
  else if ($set == "APS") return APSAbilityCost($cardID);
  else if ($set == "ARR") return ARRAbilityCost($cardID);
  else if ($set == "AAC") return AACAbilityCost($cardID);
  else if ($set == "PEN") return PENAbilityCost($cardID);
  else switch ($cardID) {
    case "riggermortis_yellow": return 1;
    case "bravo_flattering_showman": return 2;
    default:
      return 0;
  }
}

function DynamicCost($cardID)
{
  global $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $cardID = BlindCard($cardID, true);
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->DynamicCost();
  switch ($cardID) {
    case "staunch_response_red":
    case "staunch_response_yellow":
    case "staunch_response_blue":
      return "2,6";
    case "spark_of_genius_yellow":
      return "0,2,4,6,8,10,12";
    case "sonata_arcanix_red":
      if(SearchCurrentTurnEffects("bloodsheath_skeleta-NAA", $currentPlayer) || SearchCurrentTurnEffects("bloodsheath_skeleta-AA", $currentPlayer)) {
        return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,52,54,56,58,60,62,64,66,68,70,72,74,76,78,80,82,84,86,88,90,92,94,96,98,100,102,104,106,108,110";
      }
      return "0,2,4,6,8,10,12,14,16,18,20";
    case "imposing_visage_blue":
      return "3,4,5,6,7,8,9,10,11,12,13,14,15";
    case "scour_blue":
      $myAurasCount = SearchCount(SearchAura($currentPlayer, "", "", 0));
      $myAurasCount += SearchCount(SearchLayer($currentPlayer, subtype:"Aura"));
      $otherPlayerAurasCount = SearchCount(SearchAura($otherPlayer, "", "", 0));
      $otherPlayerAurasCount += SearchCount(SearchLayer($otherPlayer, subtype:"Aura"));
      return $myAurasCount > $otherPlayerAurasCount ? GetIndices($myAurasCount + 1) : GetIndices($otherPlayerAurasCount + 1);
    case "ice_eternal_blue":
      return "0,2,4,6,8,10,12,14,16,18,20";
    case "hyper_scrapper_blue":
      $myItemsCount = SearchCount(SearchDiscard($currentPlayer, "", "Item"));
      return GetIndices($myItemsCount + 1);
    case "moonshot_yellow":
      return "0,2,4,6,8,10,12,14,16,18,20";
    case "meganetic_lockwave_blue":
      return "0,3,6,9,12,15,18,21,24,27,30";
    case "system_reset_yellow":
      return "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20";
    case "tectonic_rift_blue":
      return "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15";
    case "sonata_fantasmia_blue":
      if(SearchCurrentTurnEffects("bloodsheath_skeleta-NAA", $currentPlayer) || SearchCurrentTurnEffects("bloodsheath_skeleta-AA", $currentPlayer)) {
        return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,52,54,56,58,60,62,64,66,68,70,72,74,76,78,80,82,84,86,88,90,92,94,96,98,100,102,104,106,108,110";
      }
      return "0,2,4,6,8,10,12,14,16,18,20";
    case "up_the_ante_blue":
      return "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15";
    case "reel_in_blue":
      return "0,1,2,3,4,5,6,7,8,9,10,11,12";
    case "sonata_galaxia_red":
      if(SearchCurrentTurnEffects("bloodsheath_skeleta-NAA", $currentPlayer) || SearchCurrentTurnEffects("bloodsheath_skeleta-AA", $currentPlayer)) {
        return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,52,54,56,58,60,62,64,66,68,70,72,74,76,78,80,82,84,86,88,90,92,94,96,98,100,102,104,106,108,110";
      }
      return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,52,54,56,58,60";
    case "supercell_blue":
      return GetIndices(SearchCount(SearchItemsByName($currentPlayer, "Hyper Driver")) + 1);
    case "germinate_blue":
      if(SearchCurrentTurnEffects("bloodsheath_skeleta-NAA", $currentPlayer) || SearchCurrentTurnEffects("bloodsheath_skeleta-AA", $currentPlayer)) {
        return "0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,52,54,56,58,60,62,64,66,68,70,72,74,76,78,80,82,84,86,88,90,92,94,96,98,100,102,104,106,108,110";
      }
      return "0,2,4,6,8,10,12,14,16,18,20";
    case "roiling_fissure_blue":
      return "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15";
    case "visit_anvilheim_blue":
      return "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15";
    default:
      return "";
  }
}

function PitchValue($cardID)
{
  $cardID = BlindCard($cardID, true);
  if (!$cardID) return "";
  $set = CardSet($cardID);
  if ($cardID == "goldfin_harpoon_yellow") return -1;
  if (CardType($cardID) == "M" || CardSubType($cardID) == "Landmark") return -1;
  switch ($cardID) {
    case "MST000_inner_chi_blue":
    case "MST010_inner_chi_blue":
    case "MST032_inner_chi_blue":
    case "MST053_inner_chi_blue":
    case "MST095_inner_chi_blue":
    case "MST096_inner_chi_blue":
    case "MST097_inner_chi_blue":
    case "MST098_inner_chi_blue":
    case "MST099_inner_chi_blue":
    case "MST100_inner_chi_blue":
    case "MST101_inner_chi_blue":
    case "MST102_inner_chi_blue":
      return 3;
    case "nitro_mechanoida":
      return -1;
    default:
      break;
  }
  if ($set == "LGS") {
    switch ($cardID) {
      case "jack_o_lantern_red":
        return 1;
      case "jack_o_lantern_yellow":
        return 2;
      case "jack_o_lantern_blue":
        return 3;
      default:
        break;
    }
  }
  $card = GetClass($cardID, 0);
  if ($card != "-" && $card->SpecialPitch() != -1) return $card->SpecialPitch();
  if ($set != "DUM") {
    return GeneratedPitchValue($cardID);
  }
}

function BlockValue($cardID, $player="-", $from="-", $blocking=true)
{
  global $defPlayer, $combatChain;
  $char = GetPlayerCharacter($player);
  $lyathActive = false;
  $lyathShoes = false;
  if ($from != "HAND" && $from != "DECK" && $from != "ARS" && $from != "DISCARD" && $from != "BANISH" && $from != "PITCH") {
    $lyathActive = SearchCharacterActive($player, "lyath_goldmane_vile_savant") || SearchCharacterActive($player, "lyath_goldmane");
    $lyathActive = SearchCharacterActive($player, $char[0]) && SearchCurrentTurnEffects("lyath_goldmane-SHIYANA", $player) || SearchCurrentTurnEffects("lyath_goldmane_vile_savant-SHIYANA", $player) || $lyathActive; 
    $lyathShoes = SearchCurrentTurnEffects("walk_in_my_shoes_yellow", $player) && TypeContains($cardID, "AA");
  }
  $block = -2;
  $cardID = BlindCard($cardID, true);
  switch ($cardID) { //cards with a mistake in GeneratedBlockValue
    case "the_librarian":
      $block = 2;
      break;
    case "gallow_end_of_the_line_yellow":
      $block = -1;
      break;
    default:
      break;
  }
  $card = GetClass($cardID, 0);
  if ($card != "-" && $card->SpecialBlock() != -1) $block = $card->SpecialBlock();
  if (!$cardID) return "";
  $set = CardSet($cardID);
  switch ($cardID) {
    case "mutated_mass_blue":
      $block = SearchPitchForNumCosts($defPlayer) * 2;
      break;
    case "fractal_replication_red":
      $block = FractalReplicationStats("Block");
      break;
    case "arcanite_fortress":
      $block = SearchCount(SearchMultiZone($defPlayer, "MYCHAR:type=E;nameIncludes=Arcanite"));
      break;
    case "base_of_the_mountain":
      $blockVal = 0;
      $countCombatChain = count($combatChain);
      $combatChainPieces = CombatChainPieces();
      for ($i = 0; $i < $countCombatChain; $i += $combatChainPieces) {
        if ((TypeContains($combatChain[$i], "A") || TypeContains($combatChain[$i], "AA")) && $combatChain[$i + 1] == $defPlayer) ++$blockVal;
      }
      $block = $blockVal;
      break;
    default:
      break;
  }
  if ($block == -2) { //it hasn't been set yet
    if ($set != "DUM") {
      $setID = SetID($cardID);
      $number = intval(substr($setID, 3));
      if ($number < 400 || $set != "MON" && $set != "DYN" && $set != "MST" && $set != "HNT" && $cardID != "teklovossen_the_mechropotent" && $cardID != "teklovossen_the_mechropotentb") $block = GeneratedBlockValue($cardID);
    }
  }
  if ($block == -2) { //it hasn't been set yet
    switch ($cardID) {
      case "minerva_themis":
      case "lady_barthimont":
      case "lord_sutcliffe":
        $block = 3;
        break;
      case "nitro_mechanoida":
        $block = -1;
        break;
      case "nitro_mechanoidb":
        $block = 5;
        break;
      case "teklovossen_the_mechropotent":
        $block = -1;
        break;
      case "teklovossen_the_mechropotentb":
        $block = 6;
        break;
      case "DUMMYDISHONORED":
        $block = -1;
        break;
      case "MST000_inner_chi_blue":
      case "MST010_inner_chi_blue":
      case "MST032_inner_chi_blue":
      case "MST053_inner_chi_blue":
      case "MST095_inner_chi_blue":
      case "MST096_inner_chi_blue":
      case "MST097_inner_chi_blue":
      case "MST098_inner_chi_blue":
      case "MST099_inner_chi_blue":
      case "MST100_inner_chi_blue":
      case "MST101_inner_chi_blue":
      case "MST102_inner_chi_blue":
        $block = -1;
        break;
      case "evo_recall_blue_equip":
      case "evo_heartdrive_blue_equip":
      case "evo_shortcircuit_blue_equip":
      case "evo_speedslip_blue_equip":
        $block = 0;
        break;
      case "the_hand_that_pulls_the_strings":
        $block = 4;
        break;
      default:
        return 3;
    }
  }
  if ($block == -1 || $block == -2) return -1; //it should never be -2, but being careful
  elseif ($lyathActive && !$blocking) $block = ceil($block / 2); // lyath debuff handled elsewhere when a card is defending
  if ($lyathShoes) $block = ceil($block / 2);
  return $block;
}

function PowerValue($cardID, $player="-", $from="CC", $index=-1, $base=false, $attacking=false)
{
  global $mainPlayer, $CS_NumNonAttackCards, $CS_Num6PowDisc, $CS_NumAuras, $CS_NumCardsDrawn, $CS_Num6PowBan;
  if (!$cardID) return 0;
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  $subtype = CardSubtype($cardID);
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  $player = $player == "-" ? $mainPlayer : $player;
  $char = GetPlayerCharacter($player);
  $lyathActive = false;
  $lyathShoes = false;
  if ($from != "HAND" && $from != "DECK" && $from != "ARS" && $from != "DISCARD" && $from != "BANISH" && $from != "PITCH") {
    $lyathActive = SearchCharacterActive($player, "lyath_goldmane_vile_savant") || SearchCharacterActive($player, "lyath_goldmane");
    $lyathActive = SearchCharacterActive($player, $char[0]) && SearchCurrentTurnEffects("lyath_goldmane-SHIYANA", $player) || SearchCurrentTurnEffects("lyath_goldmane_vile_savant-SHIYANA", $player) || $lyathActive; 
    $lyathShoes = SearchCurrentTurnEffects("walk_in_my_shoes_yellow", $player) && TypeContains($cardID, "AA");
  }
  //Only weapon that gains power, NOT on their attack
  if (!$base) {
    $basePower = PowerValue($cardID, $player, $from, $index, true);
    $card = GetClass($cardID, $player);
    if ($card != "-") return $card->WeaponPowerModifier($basePower);
    switch ($cardID) {
      case "summit_the_unforgiving": return CheckHeavy($mainPlayer) ? $basePower + 2 : $basePower;
      case "anothos": return SearchCount(SearchPitch($mainPlayer, minCost: 3)) >= 2 ? $basePower+2 : $basePower;
      case "nebula_blade": return GetClassState($mainPlayer, $CS_NumNonAttackCards) > 0 ? $basePower+3 : $basePower;
      case "titans_fist": return SearchCount(SearchPitch($mainPlayer, minCost: 3)) >= 1 ? $basePower+1 : $basePower;
      case "hammer_of_havenhold": return SearchPitchForCard($mainPlayer, "chivalry_blue") > -1 ? $basePower+1 : $basePower;
      case "ball_breaker": return GetClassState($mainPlayer, $CS_Num6PowDisc) >= 1 ? $basePower+1 : $basePower;
      case "high_riser": return GetClassState($mainPlayer, $CS_NumCardsDrawn) >= 1 ? $basePower+1 : $basePower;
      case "rotwood_reaper": return GetClassState($mainPlayer, $CS_NumAuras) > 0 ? $basePower+2 : $basePower;
      case "mark_of_the_huntsman":
      case "mark_of_the_huntsman_r":
        if (!IsHeroAttackTarget() || $from != "CC") return $basePower;
        else return CheckMarked($defPlayer) ? $basePower+1 : $basePower;
      default: break;
    }
  }
  $cardID = BlindCard($cardID, true);
  $basePower = -1;
  if ($class == "ILLUSIONIST" && DelimStringContains($subtype, "Aura") && $from == "CC") {
    if (SearchCharacterForCard($mainPlayer, "luminaris")) $basePower = 1;
    if (SearchCharacterForCard($mainPlayer, "iris_of_reality")) $basePower = 4;
    if (SearchCharacterForCard($mainPlayer, "reality_refractor")) $basePower = 5;
    if (SearchCharacterForCard($mainPlayer, "cosmo_scroll_of_ancestral_tapestry")) {
      if ($index != -1) {
        $basePower = WardAmount($cardID, $mainPlayer, $index);
      }
    }
  }
  switch ($cardID) { // cards with * base power
    case "mutated_mass_blue":
      $basePower = SearchPitchForNumCosts($mainPlayer) * 2;
      break;
    case "fractal_replication_red":
      $basePower = FractalReplicationStats("Power");
      break;
    case "spectral_procession_red":
      $basePower = CountAura("spectral_shield", $mainPlayer);
      break;
    case "diabolic_offering_blue":
      $basePower = GetClassState($mainPlayer, $CS_Num6PowBan) > 0 ? 6 : 0;
      break;
    case "tough_as_a_rok_blue":
      $basePower = PlayerHasLessHealth($player) ? 6 : 0;
      break;
    case "rockyard_rodeo_blue":
      $basePower = GetHighestBaseWeaponPower($player);
      break;
    default:
      break;
  }
  if ($set != "DUM") {
    $setID = SetID($cardID);
    $number = intval(substr($setID, 3));
    if ($number < 400 || $set != "MON" && $set != "DYN")
    $basePower = $basePower == -1 ? GeneratedPowerValue($cardID) : $basePower;
  }
  $basePower = match ($cardID) {
    "nitro_mechanoida" => 5,
    "suraya_archangel_of_knowledge" => 4,
    "teklovossen_the_mechropotent" => 6,
    "tusk" => 2, // AI custom weapon
    "wrenchtastic" => 4, // AI custom weapon
    "teklovossen_the_mechropotentb" => 6,
    default => $basePower,
  };
  $card = GetClass($cardID, $player);
  if ($card != "-" && $card->SpecialPower() != -1) $basePower = $card->SpecialPower();
  // Lyath ability is handled elsewhere while attacking
  if ($lyathActive && !$attacking) $basePower = ceil($basePower / 2);
  if ($lyathShoes && !$attacking) $basePower = ceil($basePower / 2);
  if (!is_numeric($basePower)) return -1;
  return $basePower;
}

function HasGoAgain($cardID, $from="-"): bool|int
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->HasGoAgain($from);
  switch ($cardID) { //cards that may have missed go again in the generated script
    case "spiders_bite":
    case "nerve_scalpel":
    case "orbitoclast":
    case "hunters_klaive":
    case "beckoning_mistblade":
    case "shifting_winds_of_the_mystic_beast_blue":
    case "wind_chakra_red":
    case "wind_chakra_yellow":
    case "wind_chakra_blue":
    case "companion_of_the_claw_red":
    case "companion_of_the_claw_yellow":
    case "companion_of_the_claw_blue":
    case "harmony_of_the_hunt_red":
    case "harmony_of_the_hunt_yellow":
    case "harmony_of_the_hunt_blue":
    case "tiger_form_incantation_red":
    case "tiger_form_incantation_yellow":
    case "tiger_form_incantation_blue":
    case "first_tenet_of_chi_moon_blue":
    case "first_tenet_of_chi_tide_blue":
    case "first_tenet_of_chi_wind_blue":
    case "spectral_manifestations_red":
    case "spectral_manifestations_yellow":
    case "spectral_manifestations_blue":
    case "tiger_taming_khakkara":
    case "biting_breeze_red":
    case "biting_breeze_yellow":
    case "biting_breeze_blue":
    case "untamed_red":
    case "untamed_yellow":
    case "untamed_blue":
    case "prismatic_leyline_yellow":
    case "water_the_seeds_red":
    case "water_the_seeds_yellow":
    case "water_the_seeds_blue":
    case "line_it_up_yellow":
    case "arcane_seeds__life_red":
    case "burn_up__shock_red":
    case "heartbeat_of_candlehold_blue":
    case "channel_the_millennium_tree_red":
    case "earths_embrace_blue":
    case "fry_red":
    case "fry_yellow":
    case "fry_blue":
    case "sizzle_red":
    case "sizzle_yellow":
    case "flourish_yellow":
    case "thrive_yellow":
    case "flourish_blue":
    case "arc_lightning_yellow":
    case "harvest_season_red":
    case "harvest_season_yellow":
    case "harvest_season_blue":
    case "strong_yield_red":
    case "strong_yield_yellow":
    case "strong_yield_blue":
    case "sigil_of_earth_blue":
    case "condemn_to_slaughter_red":
    case "machinations_of_dominion_blue":
    case "succumb_to_temptation_yellow":
    case "condemn_to_slaughter_yellow":
    case "condemn_to_slaughter_blue":
    case "malefic_incantation_red":
    case "malefic_incantation_yellow":
    case "malefic_incantation_blue":
    case "sigil_of_the_arknight_blue":
    case "arcane_cussing_red":
    case "arcane_cussing_yellow":
    case "arcane_cussing_blue":
    case "deadwood_dirge_red":
    case "deadwood_dirge_yellow":
    case "deadwood_dirge_blue":
    case "sigil_of_deadwood_blue":
    case "exploding_aether_red":
    case "exploding_aether_yellow":
    case "exploding_aether_blue":
    case "call_to_the_grave_blue":
    case "sigil_of_cycles_blue":
    case "sigil_of_fyendal_blue":
    case "unsheathed_red":
    case "regrowth__shock_blue":
    case "heavy_industry_power_plant":
    case "channel_mount_isen_blue":
    case "hunters_klaive_r":
    case "mark_of_the_huntsman":
    case "mark_of_the_huntsman_r":
    case "orb_weaver_spinneret_red":
    case "orb_weaver_spinneret_yellow":
    case "orb_weaver_spinneret_blue":
    case "kunai_of_retribution":
    case "kunai_of_retribution_r":
    case "ignite_red":
    case "wrath_of_retribution_red":
    case "blood_drop_red":
    case "blood_line_red":
    case "blood_runs_deep_red":
    case "devotion_never_dies_red":
    case "fire_tenet_strike_first_red":
    case "fire_tenet_strike_first_yellow":
    case "fire_tenet_strike_first_blue":
    case "grow_claws_red":
    case "grow_claws_yellow":
    case "grow_claws_blue":
    case "tag_the_target_red":
    case "tag_the_target_yellow":
    case "tag_the_target_blue":
    case "trap_and_release_red":
    case "trap_and_release_yellow":
    case "trap_and_release_blue":
    case "sharpened_senses_yellow":
    case "twist_and_turn_red":
    case "twist_and_turn_yellow":
    case "twist_and_turn_blue":
    case "agility_stance_yellow":
    case "flurry_stance_red":
    case "power_stance_blue":
    case "cut_deep_red":
    case "cut_deep_yellow":
    case "cut_deep_blue":
    case "hunt_a_killer_red":
    case "hunt_a_killer_yellow":
    case "hunt_a_killer_blue":
    case "knife_through_butter_red":
    case "knife_through_butter_yellow":
    case "knife_through_butter_blue":
    case "point_of_engagement_red":
    case "point_of_engagement_yellow":
    case "point_of_engagement_blue":
    case "sworn_vengeance_red":
    case "sworn_vengeance_yellow":
    case "sworn_vengeance_blue":
    case "coat_of_allegiance":
    case "oath_of_loyalty_red":
    case "hunt_to_the_ends_of_rathe_red":
    case "pain_in_the_backside_red":
    case "up_sticks_and_run_red":
    case "up_sticks_and_run_yellow":
    case "up_sticks_and_run_blue":
    case "pick_up_the_point_red":
    case "pick_up_the_point_yellow":
    case "pick_up_the_point_blue":
    case "poisoned_blade_red":
    case "poisoned_blade_yellow":
    case "poisoned_blade_blue":
    case "throw_yourself_at_them_red":
    case "throw_yourself_at_them_yellow":
    case "throw_yourself_at_them_blue":
    case "savor_bloodshed_red":
    case "cut_from_the_same_cloth_red":
    case "cut_from_the_same_cloth_yellow":
    case "cut_from_the_same_cloth_blue":
    case "dual_threat_yellow":
    case "relentless_pursuit_blue":
    case "outed_red":
    case "trot_along_blue":
    case "public_bounty_red":
    case "public_bounty_yellow":
    case "public_bounty_blue":
    case "roiling_fissure_blue":
    case "spur_locked_blue":
    case "money_where_ya_mouth_is_red":
    case "money_where_ya_mouth_is_yellow":
    case "money_where_ya_mouth_is_blue":
    case "jack_o_lantern_red":
    case "jack_o_lantern_yellow":
    case "jack_o_lantern_blue":
    case "flying_high_red":
    case "flying_high_yellow":
    case "flying_high_blue":
      return true;
    case "war_cry_of_themis_yellow":
      return GetResolvedAbilityType($cardID) == "A";
    case "healing_potion_blue": // cards with activated abilities with go again, but don't themselves have it
    case "potion_of_strength_blue":
    case "imperial_seal_of_command_red":
    case "optekal_monocle_blue":
    case "imperial_edict_red":
    case "induction_chamber_red":
    case "convection_amplifier_red":
    case "stasis_cell_blue":
    case "crazy_brew_blue":
    case "plasma_purifier_red":
    case "aether_sink_yellow":
    case "cognition_nodes_blue":
    case "teklo_plasma_pistol":
    case "great_library_of_solana":
    case "plasma_barrel_shot":
    case "kelpie_tangled_mess_yellow":
    case "cutty_shark_quick_clip_yellow":
    case "onyx_amulet_blue":
    case "pearl_amulet_blue":
    case "pounamu_amulet_blue":
      return false;
    case "limpit_hop_a_long_yellow":
      return $from == "ATTACK";
    case "performance_bonus_red": //cards that the script just messed up
    case "performance bonus_yellow":
    case "performance_bonus_blue":
      return false;
  }
  return GeneratedGoAgain($cardID);
}

function TriggerTargets($cardID)
{
  return match($cardID) {
    "blast_to_oblivion_red" => "aura_permanent",
    "blast_to_oblivion_yellow" => "aura_permanent",
    "blast_to_oblivion_blue" => "aura_permanent",
    "figment_of_ravages_yellow" => "any_arcane",
    "azvolai" => "any_arcane",
    "verdance", "verdance_thorn_of_the_rose", "sigil_of_aether_blue" => "any_arcane",
    "leave_them_hanging_red" => "any_hero",
    "channel_the_tranquil_domain_yellow" => "aura_permanent",
    default => ""
  };
}

function GetAbilityType($cardID, $index = -1, $from = "-", $player="-")
{
  global $currentPlayer, $mainPlayer;
  $player = $player == "-" ? $currentPlayer : $player;
  $cardID = ShiyanaCharacter($cardID);
  $set = CardSet($cardID);
  $subtype = CardSubtype($cardID);
  if ($from == "PLAY" && ClassContains($cardID, "ILLUSIONIST", $player) && DelimStringContains($subtype, "Aura")) {
    if (SearchCharacterForCard($currentPlayer, "luminaris") || SearchCharacterForCard($player, "iris_of_reality") || SearchCharacterForCard($player, "reality_refractor")) return "AA";
  }
  if ($from == "PLAY" && DelimStringContains($subtype, "Aura") && SearchCharacterForCard($player, "cosmo_scroll_of_ancestral_tapestry") && HasWard($cardID, $player) && $player == $mainPlayer) return "AA";
  if (DelimStringContains($subtype, "Dragon") && SearchCharacterActive($player, "storm_of_sandikai")) return "AA";
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->AbilityType($index, $from);
  if ($set == "WTR") return WTRAbilityType($cardID, $index, $from);
  else if ($set == "ARC") return ARCAbilityType($cardID, $index);
  else if ($set == "CRU") return CRUAbilityType($cardID, $index);
  else if ($set == "MON") return MONAbilityType($cardID, $index, $from);
  else if ($set == "ELE") return ELEAbilityType($cardID, $index, $from);
  else if ($set == "EVR") return EVRAbilityType($cardID, $index, $from);
  else if ($set == "UPR") return UPRAbilityType($cardID, $index);
  else if ($set == "DVR") return DVRAbilityType($cardID);
  else if ($set == "RVD") return RVDAbilityType($cardID);
  else if ($set == "DYN") return DYNAbilityType($cardID, $index);
  else if ($set == "OUT") return OUTAbilityType($cardID, $index);
  else if ($set == "DTD") return DTDAbilityType($cardID, $index);
  else if ($set == "TCC") return TCCAbilityType($cardID, $index);
  else if ($set == "EVO") return EVOAbilityType($cardID, $index, $from);
  else if ($set == "HVY") return HVYAbilityType($cardID, $index, $from);
  else if ($set == "AKO") return AKOAbilityType($cardID, $index, $from);
  else if ($set == "MST") return MSTAbilityType($cardID, $index, $from);
  else if ($set == "AAZ") return AAZAbilityType($cardID, $index, $from);
  else if ($set == "ROS") return ROSAbilityType($cardID);
  else if ($set == "ASB") return ASBAbilityType($cardID, $index);
  else if ($set == "TER") return TERAbilityType($cardID);
  else if ($set == "AIO") return AIOAbilityType($cardID, $index, $from);
  else if ($set == "AJV") return AJVAbilityType($cardID);
  else if ($set == "HNT") return HNTAbilityType($cardID);
  else if ($set == "AST") return ASTAbilityType($cardID);
  else if ($set == "AMX") return AMXAbilityType($cardID);
  else if ($set == "SEA") return SEAAbilityType($cardID, $from);
  else if ($set == "ASR") return ASRAbilityType($cardID);
  else if ($set == "AGB") return AGBAbilityType($cardID, $from);
  else if ($set == "MPG") return MPGAbilityType($cardID, $from);
  else if ($set == "SUP") return SUPAbilityType($cardID, $index, $from);
  else if ($set == "APS") return APSAbilityType($cardID);
  else if ($set == "ARR") return ARRAbilityType($cardID);
  else if ($set == "AAC") return AACAbilityType($cardID);
  else if ($set == "PEN") return PENAbilityType($cardID);
  else switch ($cardID) {
    case "blaze_firemind": return "I";
    case "magrar": return "A";
    case "riggermortis_yellow": return $from == "PLAY" ? "AA" : "A";
    case "bravo_flattering_showman": return "A";
    case "tusk": return "AA"; // AI custom weapon
    case "wrenchtastic": return "AA"; // AI custom weapon
    default:
      return "";
  }
}

function GetAbilityTypes($cardID, $index = -1, $from = "-"): string
{
  $card = GetClass($cardID, 1);
  if ($card != "-") return $card->GetAbilityTypes($index, $from);
  return match ($cardID) {
    "guardian_of_the_shadowrealm_red" => $from == "BANISH" ? "A" : "",
    "teklo_plasma_pistol", "jinglewood_smash_hit", "plasma_barrel_shot" => "A,AA",

    "barbed_castaway" => "I,I",

    "restless_coalescence_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "mighty_windup_red", "mighty_windup_yellow", "mighty_windup_blue", 
    "agile_windup_red", "agile_windup_yellow", "agile_windup_blue", 
    "vigorous_windup_red", "vigorous_windup_yellow", "vigorous_windup_blue", 
    "trip_the_light_fantastic_red", "trip_the_light_fantastic_yellow", "trip_the_light_fantastic_blue", 
    "fruits_of_the_forest_red", "fruits_of_the_forest_yellow", "fruits_of_the_forest_blue", 
    "ripple_away_blue", "under_the_trap_door_blue", 
    "reapers_call_red", "reapers_call_yellow", "reapers_call_blue",
    "tip_off_red", "tip_off_yellow", "tip_off_blue", 
    "outside_interference_blue", "fearless_confrontation_blue" => "I,AA",

    "chorus_of_the_amphitheater_red", "chorus_of_the_amphitheater_yellow", "chorus_of_the_amphitheater_blue", 
    "arcane_twining_red", "arcane_twining_yellow", "arcane_twining_blue", 
    "photon_splicing_red", "photon_splicing_yellow", "photon_splicing_blue", 
    "war_cry_of_themis_yellow", "burn_bare", "light_up_the_leaves_red" => "I,A",

    "haunting_rendition_red", "mental_block_blue" => "B,I",
    "shelter_from_the_storm_red" => $from == "HAND" ? "I,DR" : "-,DR",
    "war_cry_of_bellona_yellow" => "I,AR",

    "chum_friendly_first_mate_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "anka_drag_under_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "moray_le_fay_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "shelly_hardened_traveler_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "sawbones_dock_hand_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "chowder_hearty_cook_yellow" => ($from != "PLAY") ? "" : "I,AA",
    "kelpie_tangled_mess_yellow" => ($from != "PLAY") ? "" : "A,AA",
    "cutty_shark_quick_clip_yellow" => ($from != "PLAY") ? "" : "A,AA",
    "bam_bam_yellow" => "I,AA",
    "deny_redemption_red" => "I,AA",
    "cogwerx_blunderbuss" => "I,AA",
    default => "",
  };
}

function NameBlocked($cardID, $index, $from, $pitch=false) {
  global $mainPlayer, $defPlayer;
  $foundNullTime = SearchItemForModalities(GamestateSanitize(NameOverride($cardID)), $mainPlayer, "null_time_zone_blue") != -1;
  $foundNullTime = $foundNullTime || SearchItemForModalities(GamestateSanitize(NameOverride($cardID)), $defPlayer, "null_time_zone_blue") != -1;
  $foundNullTime = $foundNullTime && $from == "HAND";

  $foundSpeechless = SearchAuraForModalities(GamestateSanitize(NameOverride($cardID)), $mainPlayer, "leave_em_speechless_blue") != -1;
  $foundSpeechless = $foundSpeechless || SearchAuraForModalities(GamestateSanitize(NameOverride($cardID)), $defPlayer, "leave_em_speechless_blue") != -1;
  $foundSpeechless = $foundSpeechless && $from == "HAND" && !$pitch;
  return $foundNullTime || $foundSpeechless;
}

function GetEasyAbilityNames($cardID, $index, $from) {
  global $mainPlayer, $currentPlayer, $defPlayer, $layers, $combatChain, $actionPoints;
  $layerCount = count($layers);
  $abilityTypes = GetAbilityTypes($cardID, $index, $from);
  $nameBlocked = NameBlocked($cardID, $index, $from);
  switch ($abilityTypes) {
    case "I,AA":
      if (IsResolutionStep()) $layerCount -= LayerPieces();
      $names = "Ability";
      if($nameBlocked) return $names;
      if ($currentPlayer == $mainPlayer && count($combatChain) == 0 && $layerCount <= LayerPieces() && $actionPoints > 0){
        $warmongersPeace = SearchCurrentTurnEffects("WarmongersPeace", $currentPlayer);
        $underEdict = SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($cardID)), $currentPlayer);
        if (!$warmongersPeace && !$underEdict && CanAttack($cardID, $from, $index, type:"AA")) {
          if (!SearchCurrentTurnEffects("oath_of_loyalty_red", $currentPlayer) || SearchCurrentTurnEffects("fealty", $currentPlayer)) $names .= ",Attack";
        }
      }
      return $names;
    default:
    return "";
  }
}

function GetAbilityNames($cardID, $index = -1, $from = "-", $facing = "-"): string
{
  global $currentPlayer, $mainPlayer, $combatChain, $layers, $actionPoints, $CS_PlayIndex, $CS_NumActionsPlayed, $CS_NextWizardNAAInstant, $combatChainState, $CCS_EclecticMag;
  global $defPlayer, $CombatChain, $Stack;
  $character = &GetPlayerCharacter($currentPlayer);
  $auras = &GetAuras($currentPlayer);
  $names = "";
  $nameBlocked = NameBlocked($cardID, $index, $from);
  $layerCount = count($layers);
  //don't count resolution step as a layer blocking actions
  if (SearchLayersForPhase("RESOLUTIONSTEP") != -1) $layerCount -= LayerPieces();
  if ($index == -1) $index = GetClassState($currentPlayer, $CS_PlayIndex);
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->GetAbilityNames($index, $from, $nameBlocked, $layerCount, $facing);
  switch ($cardID) {
    case "teklo_plasma_pistol":
    case "plasma_barrel_shot":
      if ($index == -1) return "";
      $rv = SearchLayersForPhase("RESOLUTIONSTEP") == -1 ? "Add_a_steam_counter" : "-";
      if ($character[$index + 2] > 0 && !SearchCurrentTurnEffects("kabuto_of_imperial_authority", $mainPlayer)) $rv .= ",Attack";
      return $rv;
    case "barbed_castaway":
      if(!SearchCurrentTurnEffects("barbed_castaway-Load", $currentPlayer)) return "Aim";
      if(!SearchCurrentTurnEffects("barbed_castaway-Aim", $currentPlayer)) return "Load";
      return "Load,Aim";
    case "jinglewood_smash_hit":
      if ($index == -1) return "";
      return "Create_tokens,Smash_Jinglewood";
    case "mighty_windup_red":
    case "mighty_windup_yellow":
    case "mighty_windup_blue":
    case "agile_windup_red":
    case "agile_windup_yellow":
    case "agile_windup_blue":
    case "vigorous_windup_red":
    case "vigorous_windup_yellow":
    case "vigorous_windup_blue":
    case "trip_the_light_fantastic_red":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_blue":
    case "fruits_of_the_forest_red":
    case "fruits_of_the_forest_yellow":
    case "fruits_of_the_forest_blue":
    case "ripple_away_blue":
    case "reapers_call_red":
    case "reapers_call_yellow":
    case "reapers_call_blue":
    case "tip_off_red":
    case "tip_off_yellow":
    case "tip_off_blue":
    case "deny_redemption_red":
    case "bam_bam_yellow":
    case "outside_interference_blue":
      $names = "Ability";
      if($nameBlocked) return $names;
      if ($currentPlayer == $mainPlayer && count($combatChain) == 0 && $layerCount <= LayerPieces() && $actionPoints > 0){
        $warmongersPeace = SearchCurrentTurnEffects("WarmongersPeace", $currentPlayer);
        $underEdict = SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($cardID)), $currentPlayer);
        if (!$warmongersPeace && !$underEdict && CanAttack($cardID, $from, $index, type:"AA")) {
          if (!SearchCurrentTurnEffects("oath_of_loyalty_red", $currentPlayer) || SearchCurrentTurnEffects("fealty", $currentPlayer)) $names .= ",Attack";
        }
      }
      return $names;
    case "under_the_trap_door_blue":
      // can't use the ability if there are no traps in graveyard
      $names = (SearchDiscard($currentPlayer, subtype: "Trap") != "") ? "Ability" : "-";
      if($nameBlocked) return $names;
      if ($currentPlayer == $mainPlayer && count($combatChain) == 0 && $layerCount <= LayerPieces() && $actionPoints > 0){
        $warmongersPeace = SearchCurrentTurnEffects("WarmongersPeace", $currentPlayer);
        $underEdict = SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($cardID)), $currentPlayer);
        if (!$warmongersPeace && !$underEdict) {
          if (!SearchCurrentTurnEffects("oath_of_loyalty_red", $currentPlayer) || SearchCurrentTurnEffects("fealty", $currentPlayer)) $names .= ",Attack";
        }
      }
      return $names;
    case "haunting_rendition_red": case "mental_block_blue":
      return "Block,Ability";
    case "restless_coalescence_yellow":
      $canAttack = CanAttack($cardID, "PLAY", $index, "MYAURA", type:"AA");
      if ($auras[$index + 3] ?? 0 > 0) $names = "Instant";
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        return $names;
      } else if ($canAttack) {
        $names != "" ? $names .= ",Attack" : $names = "-,Attack";
      }
      return $names;
    case "chorus_of_the_amphitheater_red":
    case "chorus_of_the_amphitheater_yellow":
    case "chorus_of_the_amphitheater_blue":
    case "arcane_twining_red":
    case "arcane_twining_yellow":
    case "arcane_twining_blue":
    case "photon_splicing_red":
    case "photon_splicing_yellow":
    case "photon_splicing_blue":
    case "war_cry_of_themis_yellow":
      $names = ["-", "-"];
      //can it ability?
      if ($from == "HAND") $names[0] = "Ability";
      else return "-,Action";
      //can it be played?
      if (CanPlayNAA($cardID, $from, $index)) $names[1] = "Action";

      if ($names[1] == "-") return $names[0];
      return implode(",", $names);
    case "burn_bare":
      $names = ["-", "-"];
      //can it ability?
      if ($from == "HAND" && IsPhantasmActive()) $names[0] = "Ability";
      else return "-,Action";
      //can it be played?
      if (CanPlayNAA($cardID, $from, $index)) $names[1] = "Action";

      if ($names[1] == "-") return $names[0];
      return implode(",", $names);
    case "shelter_from_the_storm_red":
      $names = "Ability";
      if($nameBlocked) return $names;
      $dominateRestricted = $from == "HAND" && CachedDominateActive() && CachedNumDefendedFromHand() >= 1 && NumDefendedFromHand() >= 1;
      $restriction = "";
      $effectRestricted = !CanBlock($cardID, $from) || !IsDefenseReactionPlayable($cardID, $from) || EffectPlayCardConstantRestriction($cardID, "DR", $restriction, "", true);
      if ($currentPlayer == $defPlayer && count($combatChain) > 0 && !$dominateRestricted && !$effectRestricted && IsReactionPhase() && IsHeroAttackTarget()) {
        $names .= ",Defense Reaction";
        if ($from != "HAND") $names = "-,Defense Reaction";
      }
      return $names;
    case "war_cry_of_bellona_yellow":
      $names = "Ability";
      if($nameBlocked) return $names;
      $hasRaydn = false;
      $char = GetPlayerCharacter($currentPlayer);
      $countCharacter = count($char);
      $characterPieces = CharacterPieces();
      for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
        if (CardNameContains($char[$i], "Raydn", $currentPlayer)) $hasRaydn = true;
      }
      if ($from != "HAND") $names = "-,Attack Reaction";
      elseif ($currentPlayer == $mainPlayer && count($combatChain) > 0 && IsReactionPhase() && $hasRaydn) $names .= ",Attack Reaction";
      return $names;
    case "cogwerx_blunderbuss":
      $canAttack = CanAttack($cardID, "EQUIP",$index, "MYCHAR", true);
      $names = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog") == "" || SearchCurrentTurnEffects("cogwerx_blunderbuss", $currentPlayer) ? "-" : "Ability";
      if (CheckTapped("MYCHAR-$index", $currentPlayer)) return $names;
      //catch other edge cases like warmongers later
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        return $names;
      } else if ($canAttack) {
        $names != "" ? $names .= ",Attack" : $names = "-,Attack";
      }
      return $names;
    case "chum_friendly_first_mate_yellow":
    case "anka_drag_under_yellow":
      $canAttack = CanAttack($cardID, "PLAY", $index, "MYALLY", type:"AA");
      if (SearchHand($currentPlayer, hasWateryGrave: true) != "") $names = "Instant";
      $allies = &GetAllies($currentPlayer);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        return $names;
      } else if ($canAttack) {
        $names != "" ? $names .= ",Attack" : $names = "-,Attack";
      }
      return $names;
    case "sawbones_dock_hand_yellow":
    case "chowder_hearty_cook_yellow":
    case "moray_le_fay_yellow":
    case "shelly_hardened_traveler_yellow":
      $canAttack = CanAttack($cardID, "PLAY", $index, "MYALLY", type:"AA");
      $names = "Instant";
      $allies = &GetAllies($currentPlayer);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        return $names;
      } else if ($canAttack) {
        $names != "" ? $names .= ",Attack" : $names = "-,Attack";
      }
      return $names;
    case "kelpie_tangled_mess_yellow":
      $canAttack = CanAttack($cardID, "PLAY", $index, "MYALLY", type:"AA");
      $names = "Tangle";
      $allies = &GetAllies($currentPlayer);
      if (SearchCurrentTurnEffects("red_in_the_ledger_red", $currentPlayer) && GetClassState($currentPlayer, $CS_NumActionsPlayed) >= 1) {
        return "";
      } else if ($canAttack) {
        $names != "" ? $names .= ",Attack" : $names = "-,Attack";
      }
      if (SearchLayersForPhase("RESOLUTIONSTEP") != -1) {
        return $canAttack ? "-,Attack" : "-";
      }
      return $names;
    case "cutty_shark_quick_clip_yellow":
      $canAttack = CanAttack($cardID, "PLAY", $index, "MYALLY", type:"AA");
      $allies = &GetAllies($currentPlayer);
      $names = "";
      if (CheckTapped("MYALLY-$index", $currentPlayer)) return "Ability";
      if (SearchLayersForPhase("RESOLUTIONSTEP") != -1 && $canAttack) return "-,Attack";
      if ($allies[$index + 8] ?? 0 > 0) $names = "Ability";
      if ($canAttack) $names != "" ? $names .= ",Attack" : $names = "-,Attack";
      return $names;
    case "fearless_confrontation_blue":
      if($nameBlocked) return "Ability";
      $names = ["-", "-"];
      //can it ability?
      if ($from == "HAND" && ($CombatChain->HasCurrentLink() || IsLayerStep())) {
        if ($Stack->BottomLayer()->ID() != $cardID) $names[0] = "Ability"; //don't let it target itself
      }
      // can it attack?
      if ($currentPlayer == $mainPlayer && count($combatChain) == 0 && $layerCount <= LayerPieces() && $actionPoints > 0){
        $warmongersPeace = SearchCurrentTurnEffects("WarmongersPeace", $currentPlayer);
        $underEdict = SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($cardID)), $currentPlayer);
        if (!$warmongersPeace && !$underEdict && CanAttack($cardID, $from, $index, type:"AA")) {
          if (!SearchCurrentTurnEffects("oath_of_loyalty_red", $currentPlayer) || SearchCurrentTurnEffects("fealty", $currentPlayer)) $names[1] = "Attack";
        }
      }
      $names = $names[1] == "-" ? $names[0] : implode(",", $names);
      return $names;
    case "light_up_the_leaves_red":
      $names = ["-", "-"];
      //can it ability?
      if ($from == "HAND") $names[0] = "Ability";
      else return "-,Action";
      //can it be played?
      if (CanPlayNAA($cardID, $from, $index)) $names[1] = "Action";

      if ($names[1] == "-") return $names[0];
      return implode(",", $names);
    default:
      return "";
  }
}

// checks for stuff like warmongers
function CanAttack($cardID, $from, $index=-1, $zone="-", $isWeapon=false, $type="-")
{
  global $currentPlayer, $mainPlayer, $combatChain, $actionPoints, $layers;
  if (SearchCurrentTurnEffects("WarmongersPeace", $currentPlayer)) return false;
  $type = $type == "-" ? CardType($cardID, $from) : $type;
  if (EffectAttackRestricted($cardID, $type, $from, index:$index, overrideType:$type) != "") return false;
  if ($currentPlayer != $mainPlayer || count($combatChain) > 0 || $actionPoints == 0) return false;
  $layerCount = count($layers);
  if (SearchLayersForPhase("RESOLUTIONSTEP") != -1) $layerCount -= LayerPieces();
  if ($layerCount > LayerPieces()) return false;
  if ($isWeapon && SearchCurrentTurnEffects("kabuto_of_imperial_authority", $currentPlayer)) return false;
  if ($index != -1) {
    switch($zone) {
      case "MYCHAR":
        // $char = GetPlayerCharacter($currentPlayer);
        // if ($char[$index + 2] == 0) return false;
        break;
      case "MYALLY":
        $allies = GetAllies($currentPlayer);
        if (!isset($allies[$index]) || !isset($allies[$index + 3])) return false;
        if ($allies[$index + 3] != 0) return false;
        break;
      case "MYAURA":
        $auras = GetAuras($currentPlayer);
        if (!isset($auras[$index]) || !isset($auras[$index + 1])) return false;
        if ($auras[$index + 1] != 2) return false;
        break;
      default:
        break;
    }
  }
  return true;
}

function CanPlayNAA($cardID, $from, $index=-1)
{
  global $currentPlayer, $mainPlayer, $CS_NextWizardNAAInstant, $defPlayer, $combatChainState, $CCS_EclecticMag, $combatChain, $actionPoints, $layers;
  //check for overall blockers
  if (SearchCurrentTurnEffects("WarmongersWar", $currentPlayer)) return false;
  $nameBlocked = NameBlocked($cardID, $from, $index);
  if($nameBlocked) return false;
  $underEdict = SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($cardID)), $currentPlayer);
  if ($underEdict) return false;
  if (SearchCurrentTurnEffects("oath_of_loyalty_red", $currentPlayer) && !SearchCurrentTurnEffects("fealty", $currentPlayer)) return false;

  //check for if you can play at instant speed
  if (ClassContains($cardID, "WIZARD", $currentPlayer) && GetClassState($currentPlayer, $CS_NextWizardNAAInstant)) return true;
  if ($combatChainState[$CCS_EclecticMag]) return true;
  // check action points
  if ($currentPlayer != $mainPlayer || count($combatChain) > 0 || $actionPoints == 0) return false;
  // check for empty stack (other than the current card)
  if (count($layers) > LayerPieces()) return false;
  return true;
}

function CanBlock($cardID, $from)
{
  global $mainPlayer, $defPlayer;
  if (IsBlockRestricted($cardID, player:$defPlayer, from: $from)) return false;
  $dominateRestricted = IsDominateActive() && NumDefendedFromHand() >= 1;
  $overpowerRestricted = IsOverpowerActive() && NumActionsBlocking() >= 1;
  $confidenceRestricted = SearchCurrentTurnEffects("confidence", $mainPlayer) && IsCombatEffectActive("confidence") &&  NumNonBlocksDefending() >= 2;
  if ($from == "HAND" && $dominateRestricted) return false;
  if ((TypeContains($cardID, "A") || TypeContains($cardID, "AA")) && $overpowerRestricted) return false;
  if (!TypeContains($cardID, "B") && $confidenceRestricted) return false;
  return true;
}

function CanPitch($cardID, $from)
{
  global $mainPlayer, $defPlayer;
  $nameBlocked = NameBlocked($cardID, "-", $from, true);
  if ($nameBlocked) return false;
  return true;
}

function GetAbilityIndex($cardID, $index, $abilityName)
{
  $names = explode(",", GetAbilityNames($cardID, $index));
  $abilityName = GamestateUnsanitize($abilityName);
  $countNames = count($names);
  for ($i = 0; $i < $countNames; ++$i) {
    if ($abilityName == $names[$i]) return $i;
  }
  return 0;
}

function GetResolvedAbilityType($cardID, $from = "-", $player = -1)
{
  global $currentPlayer, $CS_AbilityIndex;
  $player = $player ==  -1 ? $currentPlayer : $player;
  $abilityIndex = GetClassState($player, $CS_AbilityIndex);
  $abilityTypes = GetAbilityTypes($cardID, from: $from);
  if ($abilityTypes == "" || $abilityIndex == "-" || !str_contains($abilityTypes, ",")) return GetAbilityType($cardID, -1, $from, $player);
  $abilityTypes = explode(",", $abilityTypes);
  if (isset($abilityTypes[$abilityIndex])) {
    return $abilityTypes[$abilityIndex];
  } else return "";
}

function GetResolvedAbilityName($cardID, $from = "-"): string
{
  global $currentPlayer, $CS_AbilityIndex;
  $abilityIndex = GetClassState($currentPlayer, $CS_AbilityIndex);
  $abilityNames = GetAbilityNames($cardID, -1, $from);
  if ($abilityNames == "" || $abilityIndex == "-") return "";
  $abilityNames = explode(",", $abilityNames);
  if (isset($abilityNames[$abilityIndex])) return $abilityNames[$abilityIndex];
  else return "";
}


function IsPlayable($cardID, $phase, $from, $index = -1, &$restriction = null, $player = "", $pitchRestriction = ""): bool
{
  global $currentPlayer, $CS_NumActionsPlayed, $combatChainState, $CCS_BaseAttackDefenseMax, $CS_NumNonAttackCards, $CS_NumAttackCards;
  global $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement, $actionPoints, $mainPlayer, $defPlayer, $CCS_NumUsedInReactions;
  global $CombatChain, $combatChain, $layers;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $player == 1 ? 2 : 1;
  $myArsenal = &GetArsenal($player);
  $myAllies = &GetAllies($player);
  $myAuras = &GetAuras($player);
  $character = &GetPlayerCharacter($player);
  $CharacterCard = new CharacterCard($index, $player);
  $myHand = &GetHand($player);
  $banish = new Banish($player);
  $auras = &GetAuras($player);
  $discard = &GetDiscard($currentPlayer);
  $restriction = "";
  $cardType = CardType($cardID, $from, $currentPlayer);
  $subtype = CardSubType($cardID);
  $abilityType = GetAbilityType($cardID, $index, $from);
  $abilityTypes = GetAbilityTypes($cardID, $index, $from);
  if ($phase == "P" && $from != "HAND") return false;
  if ($phase == "B" && $from == "BANISH") return false;
  if ($phase == "B" && $from == "THEIRBANISH") return false;
  if ($from == "BANISH") {
    $banishCard = $banish->Card($index);
    if (!(PlayableFromBanish($banishCard->ID(), $banishCard->Modifier()) || AbilityPlayableFromBanish($banishCard->ID(), $banishCard->Modifier()))) return false;
  }
  if ($from == "THEIRBANISH") {
    $theirBanish = new Banish($otherPlayer);
    $banishCard = $theirBanish->Card($index);
    if (!(PlayableFromOtherPlayerBanish($banishCard->ID(), $banishCard->Modifier()))) return false;
  } else if ($from == "THEIRARS") {
    $theirArs = GetArsenal($otherPlayer);
    if (!(PlayableFromOtherPlayerArsenal($theirArs[$index], $theirArs[$index + 1]))) return false;
  } else if ($from == "GY" && !PlayableFromGraveyard($cardID, $discard[$index + 2], $player, $index) && !AbilityPlayableFromGraveyard($cardID, $index)) return false;
  elseif ($from == "COMBATCHAINATTACKS" && (!AbilityPlayableFromCombatChain($cardID, "-") || !CanPlayInstant($phase))) return false;
  if ($from == "DECK" && ($character[5] == 0 || $character[1] < 2 || $character[0] != "dash_io" && $character[0] != "dash_database" || CardCost($cardID, $from) > 1 || !SubtypeContains($cardID, "Item", $player) || !ClassContains($cardID, "MECHANOLOGIST", $player))) return false;
  if (TypeContains($cardID, "E", $player) && isset($character[$index + 12]) && $character[$index + 12] == "DOWN" && HasCloaked($cardID, $player) == "UP") return false;
  if ($phase == "B") {
    if ((TypeContains($cardID, "E", $player) && $from == "CHAR") && $CharacterCard->OnChain() == 1) return false;
    if (IsBlockRestricted($cardID, $restriction, $player)) return false;
  }
  if ($phase != "B" && $from == "CHAR" && isset($character[$index + 1]) && $character[$index + 1] != "2") return false;
  // I don't remember why this line was here, removing for now as it's causing problems
  // if ($phase != "B" && TypeContains($cardID, "E", $player) && GetCharacterGemState($player, $cardID) == 0 && (ManualTunicSetting($player) == 0 && $cardID != "fyendals_spring_tunic")) return false;
  if ($from == "CHAR" && $phase != "B" && IsFrozenMZ($character, "CHAR", $index, $player)) {
    $restriction = "Frozen";
    return false;
  }
  if ($from == "PLAY" && DelimStringContains($subtype, "Ally") && $phase != "B" && IsFrozenMZ($myAllies, "ALLY", $index, $player)) {
    $restriction = "Frozen";
    return false;
  }
  if ($from == "PLAY" && DelimStringContains($subtype, "Ally") && $phase != "B" && isset($myAllies[$index + 1]) && $myAllies[$index + 1] == "1") {
    switch ($cardID) {
      case "cutty_shark_quick_clip_yellow":
        //has a once per turn and a non-once per turn ability
        break;
      default:
        $restriction = "Already used";
        return false;
    }
  }
  if ($from == "PLAY" && DelimStringContains($subtype, "Aura") && $phase != "B" && IsFrozenMZ($myAuras, "AURAS", $index, $player)) {
    $restriction = "Frozen";
    return false;
  }
  if ($from == "ARS" && $phase != "B") {
    if (IsFrozenMZ($myArsenal, "ARS", $index, $player)) {
      $restriction = "Frozen";
      return false;
    }
    if (SearchCurrentTurnEffects("annexation_of_all_things_known_yellow", $player) && $myArsenal[$index + 1] == "UP") {
      $restriction = "Annexed";
      return false;
    }
  }
  if ($phase != "P" && $cardType == "DR" && !IsHeroAttackTarget() && $abilityTypes == "") return false;
  if ($phase == "D" && $cardType == "DR" && !IsHeroAttackTarget() && $currentPlayer != $mainPlayer) return false;
  if ($CombatChain->HasCurrentLink() && ($phase == "B" || ($phase == "D" || $phase == "INSTANT") && $cardType == "DR")) {
    if ($from == "HAND") {
      if (!DelimStringContains($abilityTypes, "I", true) && CachedDominateActive() && CachedNumDefendedFromHand() >= 1 && NumDefendedFromHand() >= 1) return false;
      $benjiActive = CachedTotalPower() <= 2 && (SearchCharacterForCard($mainPlayer, "benji_the_piercing_wind") || SearchCurrentTurnEffects("benji_the_piercing_wind-SHIYANA", $mainPlayer)) && (SearchCharacterActive($mainPlayer, "benji_the_piercing_wind") || SearchCharacterActive($mainPlayer, "shiyana_diamond_gemini")) && CardType($CombatChain->AttackCard()->ID()) == "AA";
      if ((!DelimStringContains($abilityTypes, "I", true) || $phase == "B") && $benjiActive) return false;
    }
    if (CachedOverpowerActive() && CachedNumActionBlocked() >= 1) {
      if (DelimStringContains($cardType, "A") || $cardType == "AA") return false;
      if (SubtypeContains($cardID, "Evo") && $cardID != "teklovossen_the_mechropotentb" && $cardID != "nitro_mechanoidb") {
        if (CardType(GetCardIDBeforeTransform($cardID)) == "A") return false;
      }
    }
    if (SearchCurrentTurnEffects("confidence", $mainPlayer) && IsCombatEffectActive("confidence")) {
      if (NumNonBlocksDefending() >= 2 && !TypeContains($cardID, "B") && !str_contains(GetAbilityTypes($cardID, $index, $from), "I")) return false;
    }
  }
  if ($phase == "B" && $from == "ARS" && !($cardType == "AA" && SearchCurrentTurnEffects("art_of_war_yellow-2", $player) || $cardID == "down_and_dirty_red" || HasAmbush($cardID, $defPlayer))) return false;
  if ($phase == "B" || $phase == "D") {
    if ($cardType == "AA") {
      $baseAttackMax = $combatChainState[$CCS_BaseAttackDefenseMax];
      if ($baseAttackMax > -1 && PowerValue($cardID, $mainPlayer, "LAYER") > $baseAttackMax) return false;
    }
    if ($CombatChain->AttackCard()->ID() == "regicide_blue" && $phase == "B" && SearchBanishForCardName($player, $cardID) > -1) return false;
    $resourceMin = $combatChainState[$CCS_ResourceCostDefenseMin];
    if ($phase == "B") {
      if ($resourceMin > -1 && CardCost($cardID, $from) < $resourceMin && $cardType != "E") return false;
    }
    elseif ($resourceMin > -1 && CardCost($cardID, $from) < $resourceMin && $cardType == "DR") return false;
    if ($combatChainState[$CCS_CardTypeDefenseRequirement] == "Attack_Action" && $cardType != "AA") return false;
    if ($combatChainState[$CCS_CardTypeDefenseRequirement] == "Non-attack_Action" && $cardType != "A") return false;
  }
  if ($CombatChain->AttackCard()->ID() == "regicide_blue" && $cardType == "DR") return SearchBanishForCardName($player, $cardID) == -1;
  if ($from != "PLAY" && $from != "GY" && $phase == "B" && $cardType != "DR") return BlockValue($cardID) > -1;
  if (($phase == "P" || $phase == "CHOOSEHANDCANCEL") && IsPitchRestricted($cardID, $restriction, $from, $index, $pitchRestriction, phase:$phase)) return false;
  elseif ($phase == "CHOOSEHANDCANCEL" && $from == "HAND") {
    if (count($layers) > 0) {
      $topLayer = $layers[0];
      return match($topLayer) {
        "great_library_of_solana" => ColorContains($cardID, 2, $currentPlayer),
        "arakni_tarantula" => ClassContains($cardID, "ASSASSIN", $currentPlayer),
        default => true
      };
    }
  }
  elseif ($phase == "P") return true;
  if ($from != "PLAY" && $phase == "P" && PitchValue($cardID) > 0) return true;
  $isStaticType = IsStaticType($cardType, $from, $cardID);
  if ($isStaticType) $cardType = GetAbilityType($cardID, $index, $from);
  // don't block cards with multiple abilities where one hasn't been decided yet
  $abilityNames = GetAbilityNames($cardID, $index, $from);
  if ($cardType == "" && $abilityNames == "") return false;
  if (RequiresDiscard($cardID) || $cardID == "enlightened_strike_red") {
    if ($from == "HAND" && count($myHand) < 2) return false;
    else if (count($myHand) < 1) return false;
  }
  if (EffectPlayCardConstantRestriction($cardID, CardType($cardID), $restriction, $phase, $from)) return false;
  if ($phase != "B" && $phase != "P" && !str_contains($phase, "CHOOSE") && IsPlayRestricted($cardID, $restriction, $from, $index, $player)) return false;
  if ($phase == "M" && $subtype == "Arrow") {
    if ($from != "ARS") return false;
    if (!SubtypeContains($character[CharacterPieces()], "Bow") && !SubtypeContains($character[CharacterPieces() * 2], "Bow")) return false;
  }
  if (SearchCurrentTurnEffects("three_of_a_kind_red", $player) && !$isStaticType && $from != "ARS") return false;
  if (SearchCurrentTurnEffects("red_in_the_ledger_red", $player)) {
    if (!HasMeld($cardID) && (DelimStringContains($cardType, "A") || $cardType == "AA") && !str_contains($abilityTypes, "I") && GetClassState($player, $CS_NumActionsPlayed) >= 1) return false;
    if (str_contains($abilityTypes, "I") && ($from == "BANISH" || $from == "THEIRBANISH")) return false;
  }
  if (SearchCurrentTurnEffects("immobilizing_shot_red", $player) && !$isStaticType && DelimStringContains($cardType, "A") && GetClassState($player, $CS_NumNonAttackCards) >= 1) return false;
  if (SearchCurrentTurnEffects("immobilizing_shot_red", $player) && !$isStaticType && $cardType == "AA" && GetClassState($player, $CS_NumAttackCards) >= 1) return false;
  if ($CombatChain->HasCurrentLink()
    && $CombatChain->AttackCard()->ID() == "exude_confidence_red"
    && $player == $defPlayer
    && ($abilityType == "I" || DelimStringContains($cardType, "I") || str_contains($abilityTypes, "I"))) {
    $restriction = "Exude Confidance";
    $exudeAttack = PowerValue("exude_confidence_red", $mainPlayer, "CC");
    $countCombatChain = count($combatChain);
    $combatChainPieces = CombatChainPieces();
    for ($i = $combatChainPieces; $i < $countCombatChain; $i += $combatChainPieces) {
      if (DelimStringContains(CardType($combatChain[$i]), "AA")) {
        $powerValue = PowerValue($combatChain[$i], $defPlayer, "CC");
        if ($powerValue + $combatChain[$i + 5] >= $exudeAttack + $combatChain[5]) {
          $restriction = "";
        }
      }
    }
    if ($restriction == "Exude Confidance") return false;
  }
  if (SearchCurrentTurnEffects("exude_confidence_red", $mainPlayer) && $player == $defPlayer && ($abilityType == "I" || DelimStringContains($cardType, "I") || str_contains($abilityTypes, "I")) && !str_contains($phase, "CHOOSE")) {
    $restriction = "Exude Confidance";
    return false;
  }
  if ($cardID == "restless_coalescence_yellow" && $from == "PLAY") {
    if ($auras[$index + 1] == 2 && $currentPlayer == $mainPlayer && $actionPoints > 0) return true;
    if (SearchCurrentTurnEffectsForUniqueID($auras[$index + 6]) != -1 && CanPlayInstant($phase) && $auras[$index + 3] > 0) return true;
    if ($auras[$index + 1] != 2 || $auras[$index + 3] <= 0) return false;
  }
  if (($cardID == "chum_friendly_first_mate_yellow" || $cardID == "anka_drag_under_yellow" || $cardID == "gallow_end_of_the_line_yellow") && $from == "PLAY") {
    if (CheckTapped("MYALLY-$index", $currentPlayer)) return false;
    else if ($currentPlayer == $mainPlayer && $actionPoints > 0 && CanAttack($cardID, "PLAY", $index, "MYALLY", type:"AA")) return true;
    else if (CanPlayInstant($phase) && SearchHand($currentPlayer, hasWateryGrave:true) != "") return true;
    else return false;
  }
  if (($cardID == "sawbones_dock_hand_yellow" || $cardID == "chowder_hearty_cook_yellow" || $cardID == "moray_le_fay_yellow"|| $cardID == "shelly_hardened_traveler_yellow") && $from == "PLAY") {
    if (CheckTapped("MYALLY-$index", $currentPlayer)) return false;
    else if ($currentPlayer == $mainPlayer && $actionPoints > 0 && CanAttack($cardID, "PLAY", $index, "MYALLY", type:"AA")) return true;
    else if (CanPlayInstant($phase)) return true;
    else return false;
  }
  if ($cardID == "Cutty_Shark_Quick_Clip_Yellow" && $from == "PLAY") {
    $ally = GetAllies($currentPlayer);
    if (CheckTapped("MYALLY-$index", $currentPlayer) && $ally[$index + 1] != 2) return false;
    else if ($currentPlayer == $mainPlayer && $actionPoints > 0) return true;
    else return false;
  }
  if ($cardID == "the_hand_that_pulls_the_strings" && $from == "ARS" && SearchArsenalForCard($currentPlayer, $cardID, "DOWN") != "" && $phase == "A") return true;
  if ((DelimStringContains($cardType, "I") || CanPlayAsInstant($cardID, $index, $from)) && CanPlayInstant($phase)) return true;
  if (($from == "PLAY" || $from == "COMBATCHAINATTACKS") && AbilityPlayableFromCombatChain($cardID, $index) && CanPlayInstant($phase)) {
    return true;
  }
  if ($from == "GY" && AbilityPlayableFromGraveyard($cardID, $index) && CanPlayInstant($phase)) {
    return true;
  }
  if ((DelimStringContains($cardType, "A") || $cardType == "AA") && $actionPoints < 1) return false;
  if ($cardID == "nitro_mechanoida" || $cardID == "teklovossen_the_mechropotent") {
    if ($phase == "M" && $mainPlayer == $currentPlayer) {
      $charIndex = FindCharacterIndex($currentPlayer, $cardID);
      switch ($cardID) {
        case "nitro_mechanoida":
          return $character[$charIndex + 2] > 0;
        case "teklovossen_the_mechropotent":
          return $character[$charIndex + 2] > 1;
      }
    }
    return false;
  }
  switch ($cardType) {
    case "A":
      return $phase == "M";
    case "AA":
      return $phase == "M";
    case "AR":
      return $phase == "A";
    case "DR":
      if ($phase != "D") return false;
      if (!IsDefenseReactionPlayable($cardID, $from)) {
        $restriction = "Defense reaction not playable.";
        return false;
      }
      return true;
    case "Macro":
      return $phase == "M";
    default:
      return false;
  }
}

function IsBlockRestricted($cardID, &$restriction = null, $player = "", $from = "HAND")
{
  global $CombatChain, $mainPlayer, $CS_NumCardsDrawn, $CS_NumVigorDestroyed, $CS_NumMightDestroyed, $CS_NumAgilityDestroyed, $currentTurnEffects;
  global $defPlayer;
  if (IsEquipment($cardID, $player) && !CanBlockWithEquipment()) {
    $restriction = "This attack disallows blocking with equipment";
    return true;
  }
  if (IsEquipment($cardID, $player)) {
    $char = GetPlayerCharacter($player);
    if ($char[FindCharacterIndex($player, $cardID) + 12] == "DOWN") {
      return true;
    }
    switch ($CombatChain->AttackCard()->ID()) {
      case "headbutt_blue":
        if (!SubtypeContains($cardID, "Head")) return true;
      default:
        break;
    }
  }
  if (SearchCurrentTurnEffects("stasis_cell_blue-B-" . $cardID, $player)) {
    $restriction = "stasis_cell_blue";
    return true;
  }
  if ($CombatChain->AttackCard()->ID() == "heavy_artillery_red" || $CombatChain->AttackCard()->ID() == "heavy_artillery_yellow" || $CombatChain->AttackCard()->ID() == "heavy_artillery_blue") {
    if (CardCost($cardID) < EvoUpgradeAmount($mainPlayer) && CardType($cardID) == "AA") {
      $restriction = $CombatChain->AttackCard()->ID();
      return true;
    }
  };
  //modal cards dominate and overpower restriction
  if ($from == "HAND" && IsDominateActive() && NumDefendedFromHand() >= 1 && GetAbilityTypes($cardID, from:"HAND") != "") return true;
  if (IsOverpowerActive() && NumActionsBlocking() >= 1 && GetAbilityTypes($cardID, from:"HAND") != "") {
    if (CardTypeExtended($cardID) == "A" || CardTypeExtended($cardID) == "AA") return true;
  }
  if (SearchCurrentTurnEffects("confidence", $mainPlayer) && IsCombatEffectActive("confidence")) {
    if (NumNonBlocksDefending() >= 2 && !TypeContains($cardID, "B")) return true;
  }
  //current turn effects
  $countCurrentTurnEffects = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  for ($i = $countCurrentTurnEffects - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    if ($currentTurnEffects[$i + 1] == $defPlayer) {
      $effectArr = explode(",", $currentTurnEffects[$i]);
      $effectID = $effectArr[0];
      switch ($effectID) {
        case "chains_of_eminence_red":
          if (GamestateSanitize(NameOverride($cardID)) == $effectArr[1]) return true;
          break;
        default:
          break;
      }
    }
  }
  if(SubtypeContains($cardID, "Aura", $player) && !CanBlockWithAura($cardID)) return true;
  switch ($cardID) {
    case "face_adversity":
      return GetClassState($mainPlayer, $CS_NumCardsDrawn) == 0;
    case "confront_adversity":
      return GetClassState($mainPlayer, $CS_NumVigorDestroyed) == 0;
    case "embrace_adversity":
      return GetClassState($mainPlayer, $CS_NumMightDestroyed) == 0;
    case "overcome_adversity":
      return GetClassState($mainPlayer, $CS_NumAgilityDestroyed) == 0;
    default:
      break;
  }
  return false;
}

function CanBlockWithEquipment()
{
  global $CombatChain, $mainPlayer;
  if (SearchCurrentTurnEffects("confidence", $mainPlayer) && IsCombatEffectActive("confidence")) {
    if (NumNonBlocksDefending() >= 2) return false;
  }
  switch ($CombatChain->AttackCard()->ID()) {
    case "burn_rubber_red":
      return !SearchCurrentTurnEffects("burn_rubber_red", $mainPlayer);
    case "out_pace_red":
    case "out_pace_yellow":
    case "out_pace_blue":
    case "lay_waste_red":
    case "lay_waste_yellow":
    case "lay_waste_blue":
      return false;
    default:
      return true;
  }
}

function CanBlockWithAura($cardID)
{
  global $CombatChain, $defPlayer;
  switch ($CombatChain->AttackCard()->ID()) {
    case "cut_through_the_facade_red":
      return false;
    case "disturb_the_peace_red":
      return !ClassContains($cardID, "GUARDIAN", $defPlayer);
    default:
      return true;
  }
}

function GoesWhereAfterResolving($cardID, $from = null, $player = "", $playedFrom = "", $stillOnCombatChain = 1, $additionalCosts = "-")
{
  global $currentPlayer, $CS_NumWizardNonAttack, $CS_NumBoosted, $mainPlayer, $CS_NumBluePlayed, $CS_NumAttacks;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $player == 2 ? 1 : 2;
  if ($from == "THEIRBANISH" || $playedFrom == "THEIRBANISH") {
    switch ($cardID) {
      case "dig_up_dinner_blue":
        return "THEIRBANISH";
      case "sacred_art_undercurrent_desires_blue":
      case "sacred_art_immortal_lunar_shrine_blue":
      case "sacred_art_jade_tiger_domain_blue":
        if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) return "-";
        else if ($additionalCosts != "-") {
          $modes = explode(",", $additionalCosts);
          for ($i = 0; $i < count($modes); ++$i) {
            if ($modes[$i] == "Transcend") return "-";
          }
        }
        return "THEIRDISCARD";
      case "a_drop_in_the_ocean_blue":
      case "homage_to_ancestors_blue":
      case "pass_over_blue":
      case "path_well_traveled_blue":
      case "preserve_tradition_blue":
      case "rising_sun_setting_moon_blue":
      case "stir_the_pot_blue":
      case "the_grain_that_tips_the_scale_blue":
        if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) return "-";
        else return "THEIRDISCARD";
      default:
        return "THEIRDISCARD";
    }
  }

  if (HasMeld($cardID) && $additionalCosts == "Both" && $from != "MELD") return "-";
  $goesWhereEffect = GoesWhereEffectsModifier($cardID, $from, $player);
  if ($goesWhereEffect != -1) return $goesWhereEffect;
  //hardcode in old favorite for now
  if (($from == "COMBATCHAIN" || $from == "CHAINCLOSING") && $player != $mainPlayer && CardType($cardID) != "DR" && $cardID != "old_favorite_yellow") return "GY"; //If it was blocking, don't put it where it would go if it was played
  $subtype = CardSubType($cardID);
  $type = CardType($cardID);
  if (DelimStringContains($type, "W")) return "-";
  if (DelimStringContains($subtype, "Invocation") || DelimStringContains($subtype, "Ash") || $cardID == "UPR439" || $cardID == "UPR440" || $cardID == "UPR441" || $cardID == "teklovossen_the_mechropotent") return "-";
  if (DelimStringContains($subtype, "Construct")) {
    switch ($cardID) {
      case "construct_nitro_mechanoid_yellow":
        if (CheckIfConstructNitroMechanoidConditionsAreMet($currentPlayer) == "") return "-";
        break;
      case "singularity_red":
        if (CheckIfSingularityConditionsAreMet($currentPlayer) == "") return "-";
        break;
      default:
        break;
    }

  }
  $card = GetClass($cardID, $player);
  if ($card != "-") return $card->GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts);
  switch ($cardID) {
    case "remembrance_yellow":
      return "BANISH";
    case "gaze_the_ages_blue":
      if (substr($from, 0, 5) != "THEIR") return GetClassState($player, $CS_NumWizardNonAttack) >= 2 ? "HAND" : "GY";
      else return GetClassState($player, $CS_NumWizardNonAttack) >= 2 ? "THEIRHAND" : "THEIRDISCARD";
    case "soul_shield_yellow":
      return $from == "CHAINCLOSING" && $stillOnCombatChain ? "SOUL" : "GY";
    case "soul_food_yellow":
      return "SOUL";
    case "sonata_arcanix_red":
      return "BANISH";
    case "pulse_of_candlehold_yellow":
      return "BANISH";
    case "evergreen_red":
    case "evergreen_yellow":
    case "evergreen_blue":
      if ($playedFrom == "ARS" && $from == "CHAINCLOSING") return "BOTDECK";
      if (substr($from, 0, 5) != "THEIR") return "GY";
      else return "THEIRDISCARD";
    case "sow_tomorrow_red":
    case "sow_tomorrow_yellow":
    case "sow_tomorrow_blue":
      return "-";
    case "invigorating_light_red":
    case "invigorating_light_yellow":
    case "invigorating_light_blue":
      return $from == "CHAINCLOSING" && SearchCurrentTurnEffects($cardID, $mainPlayer) ? "SOUL" : "GY";
    case "ray_of_hope_yellow":
      $theirChar = &GetPlayerCharacter($otherPlayer);
      return PlayerHasLessHealth($player) && TalentContains($theirChar[0], "SHADOW") ? "SOUL" : "GY";
    case "guardian_of_the_shadowrealm_red":
      return $from == "BANISH" ? "HAND" : "GY";
    case "rotary_ram_red":
    case "rotary_ram_yellow":
    case "rotary_ram_blue":
      return GetClassState($player, $CS_NumBoosted) > 0 ? "BOTDECK" : "GY";
    case "timekeepers_whim_red":
    case "timekeepers_whim_yellow":
    case "timekeepers_whim_blue":
      if ($player != $mainPlayer && substr($from, 0, 5) != "THEIR") return "BOTDECK";
      else if ($player != $mainPlayer) return "THEIRBOTDECK";
      else return "GY";
    case "double_strike_red":
      if ($from == "COMBATCHAIN" && !SearchCurrentTurnEffects($cardID, $player)) {
        AddCurrentTurnEffect($cardID, $player);
        return "BANISH,TCC";
      } else SearchCurrentTurnEffects($cardID, $player, 1);
      return "GY";
    case "dig_up_dinner_blue":
      return "BANISH";
    case "fabricate_red":
      return "-";
    case "sacred_art_undercurrent_desires_blue":
    case "sacred_art_immortal_lunar_shrine_blue":
    case "sacred_art_jade_tiger_domain_blue":
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) return "-";
      else if ($additionalCosts != "-") {
        $modes = explode(",", $additionalCosts);
        for ($i = 0; $i < count($modes); ++$i) {
          if ($modes[$i] == "Transcend") return "-";
        }
      }
      return "GY";
    case "a_drop_in_the_ocean_blue":
    case "homage_to_ancestors_blue":
    case "pass_over_blue":
    case "path_well_traveled_blue":
    case "preserve_tradition_blue":
    case "rising_sun_setting_moon_blue":
    case "stir_the_pot_blue":
    case "the_grain_that_tips_the_scale_blue":
      if (GetClassState($currentPlayer, $CS_NumBluePlayed) > 1) return "-";
      else return "GY";
    case "relentless_pursuit_blue":
      if (GetClassState($currentPlayer, $CS_NumAttacks) > 0) return "BOTDECK";
      else return "GY";
    case "the_hand_that_pulls_the_strings":
      return "-";
    case "tip_the_barkeep_blue":
    case "cog_in_the_machine_red":
    case "shifting_tides_blue":
      return "-";
    default:
      return "GY";
  }
}

function GoesWhereEffectsModifier($cardID, $from, $player)
{
  global $currentTurnEffects;
  $countCurrentTurnEffects = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  for ($i = $countCurrentTurnEffects - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    $effectID = ExtractCardID($currentTurnEffects[$i]);
    if ($currentTurnEffects[$i + 1] == $player) {
      switch ($effectID) {
        case "blossoming_spellblade_red":
          if (($from == "BANISH" || $from == "MELD") && SearchCurrentTurnEffectsForUniqueID($cardID) != -1) {
            RemoveCurrentTurnEffect($i);
            return "BANISH";
          }
          break;
        case "amulet_of_oblation_blue":
          $effectArr = explode("-", $currentTurnEffects[$i]);
          if ($cardID == $effectArr[1]) {
            RemoveCurrentTurnEffect($i);
            return "BOTDECK";
          }
          break;
        case "under_the_trap_door_blue":
          if ($cardID == $currentTurnEffects[$i + 2]) {
            RemoveCurrentTurnEffect($i);
            return "BANISH";
          }
        default:
          break;
      }
    }
  }
  return -1;
}

function CanPlayInstant($phase)
{
  if ($phase == "M") return true;
  if ($phase == "A") return true;
  if ($phase == "D") return true;
  if ($phase == "INSTANT") return true;
  return false;
}

function IsPitchRestricted($cardID, &$restrictedBy, $from = "", $index = -1, $pitchRestriction = "", $phase = "P")
{
  global $playerID, $currentTurnEffects;
  $resources = &GetResources($playerID);
  if(PitchValue($cardID) <= 0) return true; //Can't pitch mentors or landmarks
  $countCurrentTurnEffects = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  for ($i = $countCurrentTurnEffects - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    if ($currentTurnEffects[$i + 1] == $playerID) {
      $effectArr = explode(",", $currentTurnEffects[$i]);
      $effectID = $effectArr[0];
      switch ($effectID) {
        case "chains_of_eminence_red":
          if (GamestateSanitize(NameOverride($cardID)) == $effectArr[1]) {
            $restrictedBy = "chains_of_eminence_red";
            return true;
          }
        default:
          break;
      }
    }
  }
  if (SearchCurrentTurnEffects("frost_lock_blue-3", $playerID) && CardCost($cardID) == 0) {
    $restrictedBy = "frost_lock_blue";
    return true;
  }
  if (ColorContains($cardID, 1, $playerID) && SearchCurrentTurnEffects("barbed_undertow_red-1", $playerID)) {
    $restrictedBy = "barbed_undertow_red";
    return true;
  } else if (ColorContains($cardID, 2, $playerID) && SearchCurrentTurnEffects("barbed_undertow_red-2", $playerID)) {
    $restrictedBy = "barbed_undertow_red";
    return true;
  } else if (ColorContains($cardID, 3, $playerID) && SearchCurrentTurnEffects("barbed_undertow_red-3", $playerID)) {
    $restrictedBy = "barbed_undertow_red";
    return true;
  }
  if (CardCareAboutChiPitch($pitchRestriction) && !SubtypeContains($cardID, "Chi") && $resources[0] < 3) return true;
  $nameBlocked = NameBlocked($cardID, 0, $from, true);
  if(($phase == "P" || $phase == "CHOOSEHANDCANCEL") && $nameBlocked){
    $restrictedBy = "Name Blocked";
    return true;
  }
  return false;
}

function IsPlayRestricted($cardID, &$restriction, $from = "", $index = -1, $player = "", $resolutionCheck = false)
{
  global $CS_NumBoosted, $combatChain, $CombatChain, $combatChainState, $currentPlayer, $mainPlayer, $CS_Num6PowBan;
  global $CS_DamageTaken, $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_NumNonAttackCards, $CS_DamageDealt, $defPlayer, $CS_NumCardsPlayed, $CS_NumLightningPlayed;
  global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layers, $CS_HitsWithWeapon, $CS_AttacksWithWeapon, $CS_CardsEnteredGY, $CS_NumRedPlayed, $CS_NumPhantasmAADestroyed;
  global $CS_Num6PowDisc, $CS_HighestRoll, $CS_NumCrouchingTigerPlayedThisTurn, $CCS_WagersThisLink, $chainLinks, $CS_NumInstantPlayed, $CS_PowDamageDealt;
  global $CS_TunicTicks, $CS_NumActionsPlayed, $CCS_NumUsedInReactions, $CS_NumAllyPutInGraveyard, $turn, $CS_PlayedNimblism, $CS_NumAttackCardsAttacked, $CS_NumAttackCardsBlocked;
  global $CS_NumCardsDrawn, $chainLinkSummary, $CCS_AttackCost, $CS_HitCounter, $ChainLinks;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  $character = &GetPlayerCharacter($player);
  $myHand = &GetHand($player);
  $myArsenal = &GetArsenal($player);
  $myItems = &GetItems($player);
  $mySoul = &GetSoul($player);
  $discard = new Discard($player);
  $otherPlayerDiscard = &GetDiscard($otherPlayer);
  $type = CardType($cardID);
  if (IsStaticType($type, $from, $cardID)) $type = GetResolvedAbilityType($cardID, $from);
  if (!$resolutionCheck) { //when running a resoulution check, only check for targets
    if (SearchAurasForCard("bait", $player) != "" && $cardID != "bait" && !str_contains($from, "THEIR")) {
      //exception for manual tunic mode
      if ($cardID == "fyendals_spring_tunic" && $currentPlayer == $mainPlayer && ManualTunicSetting($player) && GetClassState($player, piece: $CS_TunicTicks) == 0) {
        if (GetClassState($player, $CS_NumCardsPlayed) == 0 && $character[$index + 2] < 3) return false;
      }
      return true;
    }
    if (CardCareAboutChiPitch($cardID) && SearchHand($player, subtype: "Chi") == "") return true;
    if (SearchCurrentTurnEffects("herald_of_judgment_yellow", $player) && $from == "BANISH") {
      $restriction = "herald_of_judgment_yellow";
      return true;
    }
    if (SearchCurrentTurnEffects("light_it_up_yellow", $player) && TypeContains($cardID, "E", $player)) {
      $restriction = "light_it_up_yellow";
      return true;
    }
    if (SearchCurrentTurnEffects("frost_lock_blue-3", $player) && CardCost($cardID, $from) == 0 && !IsStaticType(CardType($cardID), $from, $cardID)) {
      $restriction = "frost_lock_blue";
      return true;
    }
    if (SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($cardID)), $player)) {
      if ($from != "PLAY" && $from != "EQUIP" && !DelimStringContains(GetAbilityNames($cardID, $from), "Ability", true) && GetAbilityNames($cardID, $index, $from) == "") {
        $restriction = "imperial_edict_red";
        return true;
      }
    } //Can't be played
    if (SearchCurrentTurnEffects("stasis_cell_blue-" . $cardID, $player)) {
      $restriction = "stasis_cell_blue";
      return true;
    } //Can't be activated
    if (CardType($cardID) == "A" 
      && $from != "PLAY" 
      && GetClassState($player, $CS_NumNonAttackCards) >= 1 
      && (SearchItemsForCard("signal_jammer_blue", 1) != "" || SearchItemsForCard("signal_jammer_blue", 2) != "") 
      && (GetAbilityTypes($cardID, from:$from) == "" || !DelimStringContains(GetAbilityTypes($cardID, from:$from), "I"))
      ){
      $restriction = "signal_jammer_blue";
      return true;
    }
    if ($player != $mainPlayer && SearchAlliesActive($mainPlayer, "themai")) {
      $restriction = "themai";
      return true;
    }
    if (EffectPlayCardRestricted($cardID, $type, $from, resolutionCheck: $resolutionCheck) != "") {
      $restriction = true;
      return true;
    }
    if (EffectAttackRestricted($cardID, $type, $from, index:$index) != "" && $currentPlayer == $mainPlayer) {
      $restriction = true;
      return true;
    }
  }
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") {
    return $card->IsPlayRestricted($restriction, $from, $index, $resolutionCheck);
  }
  switch ($cardID) {
    case "breaking_scales":
      return !$CombatChain->HasCurrentLink() || !HasCombo($CombatChain->AttackCard()->ID());
    case "ancestral_empowerment_red":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "NINJA", $player) || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "braveforge_bracers":
      return GetClassState($player, $CS_HitsWithWeapon) == 0;
    case "rout_red":
    case "singing_steelblade_yellow":
    case "overpower_red":
    case "overpower_yellow":
    case "overpower_blue":
    case "glint_the_quicksilver_blue":
    case "biting_blade_red":
    case "biting_blade_yellow":
    case "biting_blade_blue":
    case "stroke_of_foresight_red":
    case "stroke_of_foresight_yellow":
    case "stroke_of_foresight_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (SearchCombatChainAttacks($mainPlayer, type:"W") != "") return false;
      if (TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer)) return false;
      return true;
    case "ironsong_response_red":
    case "ironsong_response_yellow":
    case "ironsong_response_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!RepriseActive()) return false;
      return !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer);
    case "fyendals_spring_tunic":
      if ($character[$index + 2] == 3) return false;
      if ($currentPlayer != $mainPlayer) return true; //only tick up on your own turn
      if (ManualTunicSetting($player) && GetClassState($player, piece: $CS_TunicTicks) == 0) {
        if (GetClassState($player, $CS_NumCardsPlayed) == 0) return false;
      }
      return true;
    case "snapdragon_scalers":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (CardType($CombatChain->AttackCard()->ID()) != "AA") return true;
      if (CardCost($CombatChain->AttackCard()->ID()) > 1) return true;
      return false;
    case "demolition_crew_red":
    case "demolition_crew_yellow":
    case "demolition_crew_blue":
      return SearchCount(SearchHand($currentPlayer, "", "", -1, 2)) < 1;
    case "flock_of_the_feather_walkers_red":
    case "flock_of_the_feather_walkers_yellow":
    case "flock_of_the_feather_walkers_blue":    
      return SearchCount(SearchHand($currentPlayer, "", "", 1, 0)) < 1;
    case "pummel_red":
    case "pummel_yellow":
    case "pummel_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      $subtype = CardSubtype($CombatChain->AttackCard()->ID());
      $isClub = SubtypeContains($CombatChain->AttackCard()->ID(), "Club");
      $isHammer = SubtypeContains($CombatChain->AttackCard()->ID(), "Hammer");
      if ($isClub || $isHammer || CardType($CombatChain->AttackCard()->ID()) == "AA" && CardCost($CombatChain->AttackCard()->ID(), "CC") >= 2 || $combatChainState[$CCS_AttackCost] >= 2) return false;
      return true;
    case "razor_reflex_red":
    case "razor_reflex_yellow":
    case "razor_reflex_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      $subtype = CardSubtype($CombatChain->AttackCard()->ID());
      $attackCost = $combatChainState[$CCS_AttackCost] == -1 ? CardCost($CombatChain->AttackCard()->ID(), "CC") : $combatChainState[$CCS_AttackCost];
      if ($subtype == "Sword" || $subtype == "Dagger" || CardType($CombatChain->AttackCard()->ID()) == "AA" && $attackCost <= 1) return false;
      return true;
    case "teklo_plasma_pistol":
    case "plasma_barrel_shot":
      return GetAbilityNames($cardID, $index, $from) == "-";
    case "teklo_foundry_heart":
      return GetClassState($player, $CS_NumBoosted) < 1;
    case "achilles_accelerator":
      return GetClassState($player, $CS_NumBoosted) < 1;
    case "maximum_velocity_red":
      return GetClassState($player, $CS_NumBoosted) < 3;
    case "induction_chamber_red":
      return $CombatChain->HasCurrentLink()
        && $from == "PLAY"
        && (!ClassContains($CombatChain->AttackCard()->ID(), "MECHANOLOGIST", $player)
          || $myItems[$index + 1] == 0
          || CardSubtype($CombatChain->AttackCard()->ID()) != "Pistol"
          || $myItems[$index + 2] != 2);
    case "cognition_nodes_blue":
      return $CombatChain->HasCurrentLink() && $from == "PLAY" && ($myItems[$index + 1] == 0 || CardType($CombatChain->AttackCard()->ID()) != "AA" || $myItems[$index + 2] != 2);
    case "skullbone_crosswrap":
      return !ArsenalHasFaceDownCard($player);
    case "twinning_blade_yellow":
    case "unified_decree_yellow":
      return !$CombatChain->HasCurrentLink() || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer);
    case "out_for_blood_red":
    case "out_for_blood_yellow":
    case "out_for_blood_blue":
      return !$CombatChain->HasCurrentLink() || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer);
    case "shiyana_diamond_gemini":
      return IsPlayRestricted(ShiyanaCharacter("shiyana_diamond_gemini"), $restriction, $from, $index, $player);
    case "feign_death_yellow":
      return !HasTakenDamage($player);
    case "tripwire_trap_red":
    case "pitfall_trap_yellow":
    case "rockslide_trap_blue":
      return $from != "ARS";
    case "rattle_bones_red":
      return SearchCount(SearchDiscard($player, "AA", "", -1, -1, "RUNEBLADE")) == 0;
    case "aetherize_blue":
      return count($layers) == 0 || SearchLayer($player, "I", "", 1) == "";
    case "lunging_press_blue":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "reinforce_the_line_red":
    case "reinforce_the_line_yellow":
    case "reinforce_the_line_blue":
      $found = SearchCombatChainLink($player, "AA");
      return $found == "" || $found == "0";
    case "great_library_of_solana":
      return $from == "PLAY" && SearchCount(SearchHand($player, "", "", -1, -1, "", "", false, false, 2)) < 2;
    case "prism_sculptor_of_arc_light":
    case "prism":
      return count($mySoul) == 0 || $character[$index + 5] == 0;
    case "ser_boltyn_breaker_of_dawn":
    case "boltyn":
      return count($mySoul) == 0 || !HasIncreasedAttack();
    case "chane_bound_by_shadow":
    case "chane":
      if (!CanPlayAura("soul_shackle", $player, $cardID)) return true;
      return false;
    case "beacon_of_victory_yellow":
      return count($mySoul) == 0;
    case "celestial_cataclysm_yellow":
      return count($mySoul) < 3;
    case "blinding_beam_red":
    case "blinding_beam_yellow":
    case "blinding_beam_blue":
      return !$CombatChain->HasCurrentLink();
    case "graveling_growl_red":
    case "graveling_growl_yellow":
    case "graveling_growl_blue":
      return GetClassState($player, $CS_Num6PowBan) == 0;
    case "endless_maw_red":
    case "endless_maw_yellow":
    case "endless_maw_blue":
    case "writhing_beast_hulk_red":
    case "writhing_beast_hulk_yellow":
    case "writhing_beast_hulk_blue":
    case "convulsions_from_the_bellows_of_hell_red":
    case "convulsions_from_the_bellows_of_hell_yellow":
    case "convulsions_from_the_bellows_of_hell_blue":
    case "boneyard_marauder_red":
    case "boneyard_marauder_yellow":
    case "boneyard_marauder_blue":
    case "dread_screamer_red":
    case "dread_screamer_yellow":
    case "dread_screamer_blue":
    case "hungering_slaughterbeast_red":
    case "hungering_slaughterbeast_yellow":
    case "hungering_slaughterbeast_blue":
    case "unworldly_bellow_red":
    case "unworldly_bellow_yellow":
    case "unworldly_bellow_blue":
      return $discard->NumCards() < 3;
    case "doomsday_blue":
      return SearchCount(SearchBanish($player, "", "", -1, -1, "", "", true)) < 6;
    case "eclipse_blue":
      return GetClassState($player, $CS_NumBloodDebtPlayed) < 6;
    case "soul_harvest_blue":
      return $discard->NumCards() < 6;
    case "aether_ironweave":
      return GetClassState($player, $CS_NumAttackCards) == 0 || GetClassState($player, $CS_NumNonAttackCards) == 0;
    case "blood_drop_brocade":
      if ($player == $mainPlayer) return GetClassState($player, piece: $CS_PowDamageDealt) == 0;
      else return GetClassState($otherPlayer, piece: $CS_PowDamageDealt) == 0; // this is technically incorrect and will allow activating if the opponent hits your ally
    case "rally_the_rearguard_red":
    case "rally_the_rearguard_yellow":
    case "rally_the_rearguard_blue":
    case "rally_the_coast_guard_red":
    case "rally_the_coast_guard_yellow":
    case "rally_the_coast_guard_blue":
      if ($index == 0 && $from == "PLAY") return true;
      else if (isset($combatChain[$index + 7]) && $from == "PLAY") return SearchCurrentTurnEffects($cardID, $player, false, true) == $combatChain[$index + 7];
      if ($from == "COMBATCHAINATTACKS") {
        return true; // for now block these from being activated on later chain links
      }
      else return false;
    case "memorial_ground_red":
    case "memorial_ground_yellow":
    case "memorial_ground_blue":
      $maxCost = 2;
      if ($cardID == "memorial_ground_yellow") $maxCost = 1;
      elseif ($cardID == "memorial_ground_blue") $maxCost = 0;
      return SearchDiscard($player, "AA", "", $maxCost) == "";
    case "lexi_livewire":
    case "lexi":
    case "crown_of_seeds":
      return !ArsenalHasFaceDownCard($player);
    case "plume_of_evergrowth":
      $found = CombineSearches(SearchDiscard($player, "AA", talent: "EARTH"), SearchDiscard($player, "A", talent: "EARTH"));
      $found = CombineSearches(SearchDiscard($player, "I", talent: "EARTH"), $found);
      return $found == "";
    case "tome_of_harvests_blue":
      return $from == "ARS" || ArsenalEmpty($player);
    case "summerwood_shelter_red":
    case "summerwood_shelter_yellow":
    case "summerwood_shelter_blue":
      $found = CombineSearches(SearchCombatChainLink($defPlayer, "A", talent: "EARTH,ELEMENTAL"), SearchCombatChainLink($defPlayer, "AA", talent: "EARTH,ELEMENTAL"));
      return $found == "" || $found == "0";
    case "sow_tomorrow_red":
    case "sow_tomorrow_yellow":
    case "sow_tomorrow_blue":
      return SowTomorrowIndices($player, $cardID) == "";
    case "amulet_of_earth_blue":
      return $from == "PLAY" && GetClassState($player, $CS_NumFusedEarth) == 0;
    case "blizzard_blue":
      if ($CombatChain->HasCurrentLink()) return false;//If there's an attack, there's a valid target
      return !IsLayerStep();
    case "amulet_of_ice_blue":
      return $from == "PLAY" && GetClassState($player, $CS_NumFusedIce) == 0;
    case "lightning_press_red":
    case "lightning_press_yellow":
    case "lightning_press_blue":
      if (count($layers) == 0 && !$CombatChain->HasCurrentLink() && !IsResolutionStep()) return true;
      if (SearchCount(SearchCombatChainLink($currentPlayer, type: "AA", maxCost: 1)) > 0) return false;
      if (SearchCount(SearchCombatChainAttacks($currentPlayer, type: "AA", maxCost: 1)) > 0) return false;
      if ($ChainLinks->SearchForType("AA") != "") return false;
      $countLayers = count($layers);
      $layerPieces = LayerPieces();
      for ($i = 0; $i < $countLayers; $i += $layerPieces) {
        if (CardType($layers[$i]) == "AA" && CardCost($layers[$i]) <= 1) return false;
      }
      return true;
    case "shock_striker_red":
    case "shock_striker_yellow":
    case "shock_striker_blue":
      return SearchCurrentTurnEffects($cardID, $player);
    case "amulet_of_lightning_blue":
      return $from == "PLAY" && GetClassState($player, $CS_NumFusedLightning) == 0;
    case "spellbound_creepers":
      return GetClassState($player, $CS_NumAttackCardsAttacked) == 0 && GetClassState($player, $CS_NumAttackCardsBlocked) == 0; // Attacked with/Blocked with
    case "sutcliffes_suede_hides":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA" || GetClassState($currentPlayer, $CS_NumNonAttackCards) == 0;
    case "ragamuffins_hat":
      return count($myHand) != 1;
    case "deep_blue":
      return count($myHand) == 0;
    case "runaways":
      return !HasTakenDamage($player);
    case "shatter_yellow":
      return !$CombatChain->HasCurrentLink() || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) || Is1H($CombatChain->AttackCard()->ID());
    case "blade_runner_red":
    case "blade_runner_yellow":
    case "blade_runner_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (SearchCombatChainAttacks($mainPlayer, type:"W", is1h:true) != "") return false;
      if (TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) && Is1H($CombatChain->AttackCard()->ID())) return false;
      return true;
    case "in_the_swing_red":
    case "in_the_swing_yellow":
    case "in_the_swing_blue":
      return GetClassState($player, $CS_AttacksWithWeapon) < 1 || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer);
    case "crown_of_reflection":
      return $player != $mainPlayer;
    case "even_bigger_than_that_red":
    case "even_bigger_than_that_yellow":
    case "even_bigger_than_that_blue":
      return GetClassState($player, piece: $CS_PowDamageDealt) == 0;
    case "amulet_of_assertiveness_yellow":
      return $from == "PLAY" && count($myHand) < 4;
    case "amulet_of_echoes_blue":
      return $from == "PLAY" && IsAmuletOfEchoesRestricted($from);
    case "amulet_of_havencall_blue":
      return $from == "PLAY" && count($myHand) > 0;
    case "amulet_of_ignition_yellow":
      return $from == "PLAY" && GetClassState($player, $CS_NumCardsPlayed) >= 1;
    case "helm_of_sharp_eye":
      return !IsWeaponGreaterThanTwiceBasePower();
    case "amulet_of_oblation_blue":
      return $from == "PLAY" && (GetClassState(1, $CS_CardsEnteredGY) == 0 && GetClassState(2, $CS_CardsEnteredGY) == 0 || !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA");
    case "run_through_yellow":
      return !$CombatChain->HasCurrentLink() || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) || CardSubType($CombatChain->AttackCard()->ID()) != "Sword";
    case "thrust_red":
    case "blade_flash_blue":
      return !$CombatChain->HasCurrentLink() || CardSubType($CombatChain->AttackCard()->ID()) != "Sword";
    case "combustion_point_red":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA" || !ClassContains($CombatChain->AttackCard()->ID(), "NINJA", $player) && !TalentContains($CombatChain->AttackCard()->ID(), "DRACONIC", $currentPlayer);
    case "flamescale_furnace":
      return GetClassState($player, $CS_NumRedPlayed) == 0;
    case "sash_of_sandikai":
      return GetClassState($player, $CS_NumRedPlayed) == 0;
    case "liquefy_red":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "tome_of_firebrand_red":
      return $player != $mainPlayer || NumDraconicChainLinks() < 4;
    case "ghostly_touch":
      return $character[$index + 2] < 2 && !SearchCurrentTurnEffects($cardID, $player);
    case "frightmare_red":
      return GetClassState($player, $CS_NumPhantasmAADestroyed) < 1;
    case "semblance_blue":
      if ($CombatChain->HasCurrentLink()) return !ClassContains($CombatChain->AttackCard()->ID(), "ILLUSIONIST", $player);
      else if (count($layers) != 0) return !ClassContains($layers[0], "ILLUSIONIST", $player);
      return true;
    case "tide_flippers":
      return !$CombatChain->HasCurrentLink() || PowerValue($CombatChain->AttackCard()->ID(), $mainPlayer, "CC") > 2 || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "rapid_reflex_red":
    case "rapid_reflex_yellow":
    case "rapid_reflex_blue":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA" || CardCost($CombatChain->AttackCard()->ID()) > 0;
    case "waning_moon":
      return GetClassState($player, $CS_NumNonAttackCards) == 0;
    case "alluvion_constellas":
      return $character[$index + 2] < 2;
    case "spellfire_cloak":
      return $player == $mainPlayer;
    case "rewind_blue":
      return SearchLayersForNAA() == "";
    //Invocations must target Ash
    case "silken_form":
    case "invoke_dracona_optimai_red":
    case "invoke_tomeltai_red":
    case "invoke_dominia_red":
    case "invoke_azvolai_red":
    case "invoke_cromai_red":
    case "invoke_kyloria_red":
    case "invoke_miragai_red":
    case "invoke_nekria_red":
    case "invoke_ouvia_red":
    case "invoke_themai_red":
    case "invoke_vynserakai_red":
    case "invoke_yendurai_red":
    case "skittering_sands_red":
    case "skittering_sands_yellow":
    case "skittering_sands_blue":
    case "sand_cover_red":
    case "sand_cover_yellow":
    case "sand_cover_blue":
      return SearchCount(SearchPermanents($player, "", "Ash")) < 1;
    case "rok":
      return count(GetHand($player)) != 0;
    case "savage_beatdown_red":
      return GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 0 : 1;
    case "rumble_grunting_red":
    case "rumble_grunting_yellow":
    case "rumble_grunting_blue":
      return GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 0 : 1;
    case "hanabi_blaster":
      return $character[$index + 2] < 2;
    case "puncture_red":
    case "puncture_yellow":
    case "puncture_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      $subtype = CardSubType($CombatChain->AttackCard()->ID());
      return $subtype != "Sword" && $subtype != "Dagger";
    case "blacktek_whisperers":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer) || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "mask_of_perdition":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer) || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "shred_red":
    case "shred_yellow":
    case "shred_blue":
      $countChainLinks = count($chainLinks);
      $chainLinkPieces = ChainLinksPieces();
      for ($i = 0; $i < $countChainLinks; ++$i) {
        if (ClassContains($chainLinks[$i][0], "ASSASSIN", $mainPlayer)) {
          for ($j = $chainLinkPieces; $j < count($chainLinks[$i]); $j += $chainLinkPieces) {
            if ($chainLinks[$i][$j + 1] == $defPlayer && $chainLinks[$i][$j+2] == 1) return false;
          }
        }
      }
      return NumCardsBlocking() < 1 || !ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer);
    case "cut_to_the_chase_red":
    case "cut_to_the_chase_yellow":
    case "cut_to_the_chase_blue":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer) || ContractType($CombatChain->AttackCard()->ID()) == "";
    case "point_the_tip_red":
    case "point_the_tip_yellow":
    case "point_the_tip_blue":
      $arsenalHasFaceUp = ArsenalHasArrowCardFacing($mainPlayer, "UP");
      if (!$arsenalHasFaceUp) $restriction = "There must be a face up arrow in your arsenal.";
      return !$arsenalHasFaceUp;
    case "invoke_suraya_yellow":
      return CountAura("spectral_shield", $currentPlayer) < 1;
    case "uzuri_switchblade":
    case "uzuri":
      return !$CombatChain->HasCurrentLink() || !HasStealth($CombatChain->AttackCard()->ID()) || count($myHand) == 0;
    case "spike_with_bloodrot_red":
    case "spike_with_frailty_red":
    case "spike_with_inertia_red":
    case "razors_edge_red":
    case "razors_edge_yellow":
    case "razors_edge_blue":
      return !$CombatChain->HasCurrentLink() || !HasStealth($CombatChain->AttackCard()->ID()) || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "silverwind_shuriken_blue":
      return $from == "PLAY" ? !$CombatChain->HasCurrentLink() || !HasCombo($CombatChain->AttackCard()->ID()) : false;
    case "trench_of_sunken_treasure":
      return !ArsenalHasFaceDownCard($player);
    case "flick_knives":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!SearchCharacterAliveSubtype($player, "Dagger", true) && SearchCombatChainAttacks($player, subtype:"Dagger", type:"AA") == "") {
        $restriction = "No dagger to throw";
        return true;
      }
      return false;
    case "concealed_blade_blue":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA" || !ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer) && !ClassContains($CombatChain->AttackCard()->ID(), "NINJA", $mainPlayer);
    case "short_and_sharp_red":
    case "short_and_sharp_yellow":
    case "short_and_sharp_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      $subtype = CardSubtype($CombatChain->AttackCard()->ID());
      if ($subtype == "Dagger" || CardType($CombatChain->AttackCard()->ID()) == "AA" && PowerValue($CombatChain->AttackCard()->ID(), $mainPlayer, "CC") <= 2) return false;
      return true;
    case "mask_of_malicious_manifestations":
      return count($myHand) + count($myArsenal) < 1;
    case "death_touch_red":
    case "death_touch_yellow":
    case "death_touch_blue":
      return $from == "HAND";
    case "virulent_touch_red":
    case "virulent_touch_yellow":
    case "virulent_touch_blue":
      return $from == "HAND";
    case "threadbare_tunic":
      return count($myHand) > 0;
    case "fisticuffs":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "fleet_foot_sandals":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA" && !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer) || PowerValue($CombatChain->AttackCard()->ID(), $mainPlayer, "CC", base:true) > 1;
    case "prism_awakener_of_sol":
    case "prism_advent_of_thrones":
      return count($mySoul) == 0 || $character[5] == 0 || SearchPermanents($player, subtype: "Figment") == "";
    case "luminaris_celestial_fury":
      return !$CombatChain->HasCurrentLink() || !str_contains(NameOverride($CombatChain->AttackCard()->ID(), $mainPlayer), "Herald") && !SubtypeContains($CombatChain->AttackCard()->ID(), "Angel", $mainPlayer);
    case "angelic_descent_red":
    case "angelic_descent_yellow":
    case "angelic_descent_blue":
      return !$CombatChain->HasCurrentLink() || !str_contains(NameOverride($CombatChain->AttackCard()->ID(), $mainPlayer), "Herald");
    case "angelic_wrath_red":
    case "angelic_wrath_yellow":
    case "angelic_wrath_blue":
      $found = GetChainLinkCards($defPlayer, nameContains: "Herald");
      if ($found != "" && $found != "0") {
      return false;  
}
      $pastHeralds = SearchCombatChainAttacks($player, nameIncludes:"Herald");
      if ($pastHeralds != "") return false;
      return !$CombatChain->HasCurrentLink() || !str_contains(NameOverride($CombatChain->AttackCard()->ID(), $mainPlayer), "Herald");
    case "celestial_reprimand_red":
    case "celestial_reprimand_yellow":
    case "celestial_reprimand_blue":
      return count($combatChain) < CombatChainPieces() * 2 || !str_contains(NameOverride($CombatChain->AttackCard()->ID(), $mainPlayer), "Herald");
    case "celestial_resolve_red":
    case "celestial_resolve_yellow":
    case "celestial_resolve_blue":
      return count($combatChain) < CombatChainPieces() * 2 || GetChainLinkCards($defPlayer, nameContains: "Herald") == "";
    case "v_for_valor_red":
    case "v_for_valor_yellow":
    case "v_for_valor_blue":
      $hand = &GetHand($currentPlayer);
      return $from == "PLAY" && count($hand) == 0;
    case "resounding_courage_red":
    case "resounding_courage_yellow":
    case "resounding_courage_blue":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer) || !TalentContains($CombatChain->AttackCard()->ID(), "LIGHT", $mainPlayer);
    case "radiant_view":
    case "radiant_raiment":
    case "radiant_touch":
    case "radiant_flow":
      return count($mySoul) == 0;
    case "spoiled_skull":
      $index = CombineSearches(SearchBanish($player, "AA"), SearchBanish($player, "A"));
      $cleanIndexes = RemoveCardSameNames($player, $index, GetBanish($player));
      return SearchCount($cleanIndexes) < 3;
    case "oblivion_blue":
      return CountAura("runechant", $currentPlayer) != 6;
    case "levia_redeemed":
      return $from != "PLAY" || SearchCount(SearchBanish($currentPlayer, bloodDebtOnly: true)) < 13;
    case "rugged_roller":
      return GetClassState($currentPlayer, $CS_HighestRoll) != 6;
    case "chorus_of_ironsong_yellow":
      return !$CombatChain->HasCurrentLink() || !CardNameContains($CombatChain->AttackCard()->ID(), "Dawnblade", $mainPlayer, true);
    case "apocalypse_automaton_red":
      return EvoUpgradeAmount($player) == 0;//Restricted if no EVOs
    case "mask_of_three_tails":
      return HitsInCombatChain() < 3 || $player != $mainPlayer;
    case "blood_scent":
      return GetClassState($player, $CS_NumCrouchingTigerPlayedThisTurn) == 0;
    case "symbiosis_shot":
      return $character[$index + 2] <= 0;
    case "maxx_the_hype_nitro":
    case "maxx_nitro": 
    case "teklovossen_esteemed_magnate":
    case "teklovossen": 
      return $character[5] == 0;
    case "cogwerx_base_head":
    case "cogwerx_base_chest":
    case "cogwerx_base_arms":
    case "cogwerx_base_legs":
      return $character[$index + 2] == 0 || GetClassState($player, $CS_NumBoosted) == 0;
    case "prismatic_lens_yellow":
    case "quantum_processor_yellow":
      if ($from == "PLAY") return $myItems[$index + 2] != 2; else return false;
    case "dissolving_shield_red":
    case "dissolving_shield_yellow":
    case "dissolving_shield_blue":
      if ($from == "PLAY") return $myItems[$index + 1] == 0; else return false;
    case "moonshot_yellow":
      return GetClassState($player, $CS_NumBoosted) <= 0;
    case "shriek_razors":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $mainPlayer)) return true;
      //check for blocking AAs
      $countCombatChain = count($combatChain);
      $combatChainPieces = CombatChainPieces();
      for ($i = $combatChainPieces; $i < $countCombatChain; $i += $combatChainPieces) {
        if ($combatChain[$i+1] == $defPlayer && TypeContains($combatChain[$i], "AA")) return false;
      }
      return true; 
    case "evo_command_center_yellow_equip":
    case "evo_engine_room_yellow_equip":
    case "evo_smoothbore_yellow_equip":
    case "evo_thruster_yellow_equip":
    case "evo_data_mine_yellow_equip":
    case "evo_battery_pack_yellow_equip":
    case "evo_cogspitter_yellow_equip":
    case "evo_charging_rods_yellow_equip":
      return !EvoHasUnderCard($currentPlayer, $index);
    case "good_time_chapeau":
      return CountItem("gold", $currentPlayer) <= 0;
    case "kassai_of_the_golden_sand":
    case "kassai":
      return SearchCount(SearchDiscard($currentPlayer, pitch: 1)) < 2 || SearchCount(SearchDiscard($currentPlayer, pitch: 2)) < 2;
    case "prized_galea":
      return !TypeContains($CombatChain->AttackCard()->ID(), "W", $currentPlayer);
    case "hood_of_red_sand":
      return SearchCount(SearchDiscard($currentPlayer, pitch: 1)) < 1 || SearchCount(SearchDiscard($currentPlayer, pitch: 2)) < 1 || !CardSubtype($CombatChain->AttackCard()->ID()) == "Sword";
    case "blade_flurry_red":
      return !$CombatChain->HasCurrentLink() || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer);
    case "shift_the_tide_of_battle_yellow":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer) || CachedTotalPower() <= PowerValue($CombatChain->AttackCard()->ID(), $mainPlayer, "CC");
    case "cut_the_deck_red":
    case "cut_the_deck_yellow":
    case "cut_the_deck_blue":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
    case "fatal_engagement_red":
    case "fatal_engagement_yellow":
    case "fatal_engagement_blue":
      return !$CombatChain->HasCurrentLink() || NumAttacksBlocking() == 0;
    case "take_the_upper_hand_red":
    case "take_the_upper_hand_yellow":
    case "take_the_upper_hand_blue":
      return !$CombatChain->HasCurrentLink() || $combatChainState[$CCS_WagersThisLink] == 0;
    case "agile_engagement_red":
    case "agile_engagement_yellow":
    case "agile_engagement_blue":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
    case "vigorous_engagement_red":
    case "vigorous_engagement_yellow":
    case "vigorous_engagement_blue":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "WARRIOR", $mainPlayer);
    case "cintari_sellsword":
      return GetClassState($player, $CS_AttacksWithWeapon) <= 0;
    case "balance_of_justice":
      return GetClassState($otherPlayer, $CS_NumCardsDrawn) < 2;
    case "graven_call":
      if ($from == "GY") return CountItem("silver", $currentPlayer) < 2; else return false;
    case "mask_of_recurring_nightmares":
      if (!$CombatChain->HasCurrentLink()) return true;
      return false;
    case "pass_over_blue":
      return count($otherPlayerDiscard) <= 0;
    case "preserve_tradition_blue":
      return CombineSearches(SearchDiscard($player, "A"), SearchDiscard($player, "AA")) == "";
    case "run_roughshod_blue":
      return GetClassState($mainPlayer, $CS_Num6PowDisc) > 0 ? 0 : 1;
    case "tide_chakra_red":
    case "tide_chakra_yellow":
    case "tide_chakra_blue":
    case "hiss_red":
    case "hiss_yellow":
    case "hiss_blue":
    case "intimate_inducement_red":
    case "intimate_inducement_yellow":
    case "intimate_inducement_blue":
    case "venomous_bite_red":
    case "venomous_bite_yellow":
    case "venomous_bite_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (CardType($CombatChain->AttackCard()->ID()) == "AA" && (ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $player) || TalentContains($CombatChain->AttackCard()->ID(), "MYSTIC", $player))) return false;
      return true;
    case "fang_strike":
    case "slither":
      return !$CombatChain->HasCurrentLink() || CardType($CombatChain->AttackCard()->ID()) != "AA";
    case "truths_retold":
    case "uphold_tradition":
    case "aqua_seeing_shell":
    case "skycrest_keikoi":
    case "skybody_keikoi":
    case "skyhold_keikoi":
    case "skywalker_keikoi":
      $charIndex = FindCharacterIndex($player, $cardID);
      return $character[$charIndex + 12] != "DOWN";
    case "waves_of_aqua_marine":
    case "aqua_laps":
      $charIndex = FindCharacterIndex($player, $cardID);
      return $character[$charIndex + 12] != "DOWN" || !$CombatChain->HasCurrentLink();
    case "a_drop_in_the_ocean_blue":
    case "path_well_traveled_blue":
    case "the_grain_that_tips_the_scale_blue":
      if ($CombatChain->HasCurrentLink()) return false;//If there's an attack, there's a valid target
      if (count($chainLinks) > 0) return false; //If there's an attack on previous chain links, there's a valid target
      return !IsLayerStep();
    case "just_a_nick_red":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (HasStealth($CombatChain->AttackCard()->ID())) return false;
      if (LinkBasePower() <= 1 && CardType($CombatChain->AttackCard()->ID()) == "AA") return false;
      return true;
    case "astral_etchings_red":
    case "astral_etchings_yellow":
    case "astral_etchings_blue":
      $haveAuraWard = 0;
      $auras = &GetAuras($player);
      foreach ($auras as &$aura) {
        if (HasWard($aura, $player)){
          $haveAuraWard = +1;
        } 
       }
      return $haveAuraWard <= 0;
    case "bloodtorn_bodice":
      $auras = &GetAuras($player);
      return Count($auras) <= 0;
    case "maul_yellow":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (CardNameContains($CombatChain->AttackCard()->ID(), "Crouching Tiger", $player)) return false;
      if (LinkBasePower() <= 1 && CardType($CombatChain->AttackCard()->ID()) == "AA") return false;
      return true;
    case "longdraw_half_glove":
      return count($myHand) + count($myArsenal) < 2;
    case "shadowrealm_horror_red":
      return $discard->NumCards() < 3;
    case "enigma_new_moon":
      return GetPlayerNumEquipment($player, "DOWN") <= 0;
    case "aurora_shooting_star":
    case "aurora":
      return GetClassState($player, $CS_NumLightningPlayed) == 0;
    case "oscilio_constella_intelligence":
    case "oscilio":
      return SearchCount(SearchMultiZone($player, "MYHAND:type=I")) == 0;
    case "seeds_of_tomorrow_blue":
      return $from == "ARS" || ArsenalEmpty($player);
    case "solar_plexus":
      return count($mySoul) == 0;
    case "hidden_agenda":
      return !ArsenalHasArrowCardFacing($player, "DOWN");
    case "flight_path":
      return !HasAimCounter();
    case "well_grounded":
      return SearchCount(SearchBanish($player, talent: "EARTH")) < 4;
    case "twinkle_toes":
      return GetClassState($player, $CS_NumInstantPlayed) <= 0;
    case "hood_of_second_thoughts":
    case "bruised_leather":
    case "four_finger_gloves":
      return !HasTakenDamage($player);
    case "ink_lined_cloak":
      return !HasAuraWithSigilInName($currentPlayer);
    case "cerebellum_processor_blue":
      if ($from == "PLAY") return $myItems[$index + 2] != 2;
      else return false;
    case "arakni_black_widow":
    case "arakni_funnel_web":
    case "arakni_redback":
      if (!($CombatChain->HasCurrentLink() && ClassContains($CombatChain->AttackCard()->ID(), "ASSASSIN", $currentPlayer))) return true;
      if (SearchHand($currentPlayer, class:"ASSASSIN") == "") return true;
      return false;
    case "arakni_orb_weaver":
      if (SearchHand($currentPlayer, class:"ASSASSIN") == "") return true;
      return false;
    case "arakni_tarantula":
      $activeDagger = $CombatChain->HasCurrentLink() && SubtypeContains($CombatChain->AttackCard()->ID(), "Dagger", $currentPlayer);
      $prevLinks = GetCombatChainAttacks();
      $prevDagger = false;
      $countPrevLinks = count($prevLinks);
      $chainLinkPieces = ChainLinksPieces();
      for ($i = 0; $i < $countPrevLinks; $i += $chainLinkPieces) {
        if (SubtypeContains($prevLinks[$i], "Dagger", $currentPlayer)) $prevDagger = true;
      }
      if (!$activeDagger && !$prevDagger) return true;
      if (SearchHand($currentPlayer, class:"ASSASSIN") == "") return true;
      return false;
    case "take_up_the_mantle_yellow":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (HasStealth($CombatChain->AttackCard()->ID()) && DelimStringContains(CardType($CombatChain->AttackCard()->ID()), "AA", true)) return false;
      return true;
    case "tarantula_toxin_red":
      if (!$CombatChain->HasCurrentLink()) return true;
      $countChainLinks = count($chainLinks);
      $chainLinkPieces = ChainLinksPieces();
      for ($i = 0; $i < $countChainLinks; ++$i) {
        if (HasStealth($chainLinks[$i][0])) {
          for ($j = $chainLinkPieces; $j < count($chainLinks[$i]); $j += $chainLinkPieces) {
            if ($chainLinks[$i][$j + 1] == $defPlayer && $chainLinks[$i][$j+2] == 1) return false;
          }
        }
        if (SubtypeContains($chainLinks[$i][0], "Dagger", $currentPlayer)) {
          if ($chainLinks[$i][2] == 1) return false;
          if (SearchCharacterForUniqueID($chainLinks[$i][8], $currentPlayer) != -1) return false;
        }
      }
      if (HasStealth($CombatChain->AttackCard()->ID()) && NumCardsBlocking() > 0) return false;
      if (SubtypeContains($CombatChain->AttackCard()->ID(), "Dagger", $currentPlayer)) return false;
      return true;
    case "stains_of_the_redback_red":
    case "stains_of_the_redback_yellow":
    case "stains_of_the_redback_blue":
      return !$CombatChain->HasCurrentLink() || !HasStealth($CombatChain->AttackCard()->ID());
    case "two_sides_to_the_blade_red":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (HasStealth($CombatChain->AttackCard()->ID())) return false;
      if (SubtypeContains($CombatChain->AttackCard()->ID(), "Dagger", $currentPlayer)) return false;
      return true;
    case "hunts_end_red";
      if (!$CombatChain->HasCurrentLink()) return true;
      if (CountAura("fealty", $currentPlayer) < 3) return true;
      $subtype = CardSubType($CombatChain->AttackCard()->ID());
      return $subtype != "Dagger";
    case "long_whisker_loyalty_red":
      if (!$CombatChain->HasCurrentLink()) return true;
      // This next line is based on my interpretation of the card. It seems to require you to pick all 3 modes
      // if you have 3 draconic chain links, and you can't pick the first mode without a dagger attack
      // this could change with release notes
      if (NumDraconicChainLinks() > 2 && !SubtypeContains($CombatChain->CurrentAttack(), "Dagger", $currentPlayer)) return true;
      if (NumDraconicChainLinks() > 0) {
        // make sure you have at least one dagger equipped
        $mainCharacter = &GetPlayerCharacter($mainPlayer);
        $countMainCharacter = count($mainCharacter);
        $characterPieces = CharacterPieces();
        for ($i = 0; $i < $countMainCharacter; $i += $characterPieces) {
          if (SubtypeContains($mainCharacter[$i], "Dagger", $mainPlayer)) return false;
        }
        return true;
      }
      // you can play it, but it won't do anything
      return false;
    case "affirm_loyalty_red":
    case "endear_devotion_red":
    case "blistering_blade_red":
    case "dynastic_dedication_red":
    case "imperial_intent_red":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (SearchCombatChainAttacks($currentPlayer, subtype:"Dagger") != "") return false;
      if (SubtypeContains($CombatChain->CurrentAttack(), "Dagger", $currentPlayer)) return false;
      return true;
    case "brothers_of_flame_red":
      if (NumDraconicChainLinks() < 2) return true;
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!SubtypeContains($CombatChain->CurrentAttack(), "Dagger", $currentPlayer)) return true;
      return false;
    case "scalding_iron_red":
    case "searing_gaze_red":
    case "sizzling_steel_red":
    case "stabbing_pain_red":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!SubtypeContains($CombatChain->CurrentAttack(), "Dagger", $currentPlayer)) return true;
      return false;
    case "sisters_of_fire_red":
      if (NumDraconicChainLinks() < 2) return true;
    case "jagged_edge_red":
      return !$CombatChain->HasCurrentLink() || !TypeContains($CombatChain->AttackCard()->ID(), "W", $mainPlayer);
    case "provoke_blue":
      return !$CombatChain->HasCurrentLink();
    case "diced_red":
    case "diced_yellow":
    case "diced_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      return CardSubType($CombatChain->AttackCard()->ID()) != "Dagger";
    case "dragonscaler_flight_path":
      if(!$CombatChain->HasCurrentLink() && SearchLayersForPhase("RESOLUTIONSTEP") == -1) return true;
      $previousLink = SearchCombatChainAttacks($currentPlayer, talent:"DRACONIC") == "";
      $currentLink = !TalentContains($CombatChain->AttackCard()->ID(), "DRACONIC", $currentPlayer);
      if ($previousLink && $currentLink) return true;
      return false;
    case "vow_of_vengeance":
      $otherChar = &GetPlayerCharacter($otherPlayer);
      if (!CardNameContains($otherChar[0], "Arakni")) return true;
      break;
    case "hand_of_vengeance":
      $otherChar = &GetPlayerCharacter($otherPlayer);
      if (!CardNameContains($otherChar[0], "Arakni")) return true;
      break;
    case "path_of_vengeance":
      $otherChar = &GetPlayerCharacter($otherPlayer);
      if (!CardNameContains($otherChar[0], "Arakni")) return true;
      break;
    case "oath_of_loyalty_red":
      return GetClassState($currentPlayer, piece: $CS_NumActionsPlayed) > 0;
    case "danger_digits":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!SearchCharacterAliveSubtype($player, "Dagger", true) && SearchCombatChainAttacks($player, "AA", "Dagger") == "") {
        $restriction = "No dagger to throw";
        return true;
      }
      return false;
    case "throw_dagger_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!SearchCharacterAliveSubtype($player, "Dagger", true) && SearchCombatChainAttacks($player, "AA", "Dagger") == "") {
        $restriction = "No dagger to throw";
        return true;
      }
      return false;
    case "starting_point":
      return $combatChainState[$CCS_NumUsedInReactions] == 0;
    case "perforate_yellow":
      // make sure you have at least one dagger equipped
      $mainCharacter = &GetPlayerCharacter($mainPlayer);
      $countMainCharacter = count($mainCharacter);
      $characterPieces = CharacterPieces();
      for ($i = 0; $i < $countMainCharacter; $i += $characterPieces) {
        if (SubtypeContains($mainCharacter[$i], "Dagger", $mainPlayer)) return false;
      }
      return true;
    case "to_the_point_red":
    case "to_the_point_yellow":
    case "to_the_point_blue":
    case "incision_red":
    case "incision_yellow":
    case "incision_blue":
    case "scar_tissue_red":
    case "scar_tissue_yellow":
    case "scar_tissue_blue":
    case "take_a_stab_red":
    case "take_a_stab_yellow":
    case "take_a_stab_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      for ($i = 0; $i < count($chainLinks); ++$i) {
        if (SubtypeContains($chainLinks[$i][0], "Dagger") && $chainLinks[$i][2] == 1) {
          return false;
        }
      }
      return !DelimStringContains(CardSubType($CombatChain->AttackCard()->ID()), "Dagger");
    case "bunker_beard":
      if (!$CombatChain->HasCurrentLink()) return true;
      return SearchArsenal($currentPlayer, type:"A") == "" && SearchArsenal($currentPlayer, type:"AA");
    case "outed_red":
      return CheckMarked($currentPlayer);
    case "lay_low_yellow":
      return CheckMarked($currentPlayer) && !IsAllyAttacking();
    case "exposed_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (CheckMarked($currentPlayer)) return true;
      return false;
    case "put_in_context_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (LinkBasePower() > 3) return true;
      return false;
    case "nip_at_the_heels_blue":
      if (!$CombatChain->HasCurrentLink()) return true;
      if (PowerValue($CombatChain->AttackCard()->ID(), $mainPlayer, "CC") > 3) return true;
      return false;
    case "war_cry_of_bellona_yellow":
      if ($from == "HAND") return false;
      if (!$CombatChain->HasCurrentLink()) return true;
      if (!CardNameContains($CombatChain->AttackCard()->ID(), "Raydn", $mainPlayer, true)) return true;
      return false;
    case "quickdodge_flexors":
      if (!IsHeroAttackTarget()) return true;
      return false;
    case "the_hand_that_pulls_the_strings":
      return !ArsenalHasFaceDownCard($player);
    case "shock_frock":
      return GetClassState($player, $CS_NumLightningPlayed) == 0;
    case "gravy_bones_shipwrecked_looter":
    case "gravy_bones":
    case "puffin_hightail":
    case "puffin":
    case "marlynn_treasure_hunter":
    case "marlynn":
    case "scurv_stowaway":
      if (CheckTapped("MYCHAR-$index", $currentPlayer)) return true;
      return CountItemByName("Gold", $currentPlayer) == 0;
    case "compass_of_sunken_depths":
    case "gold_baited_hook":
    case "redspine_manta":
    case "hammerhead_harpoon_cannon":
    case "polly_cranka":
    case "sticky_fingers":
      return CheckTapped("MYCHAR-$index", $currentPlayer);
    case "rust_belt":
      return GetUntapped($player, "MYITEMS", "subtype=Cog") == "";
    case "spitfire":
      if (CheckTapped("MYCHAR-$index", $currentPlayer)) return true;
      if (GetUntapped($player, "MYITEMS", "subtype=Cog") == "") return true;
      return false;
    case "cogwerx_blunderbuss":
      if (!CheckTapped("MYCHAR-$index", $currentPlayer) && $turn[0] == "M") return false;
      if (SearchCurrentTurnEffects("cogwerx_blunderbuss", $player)) return true;
      if (GetUntapped($player, "MYITEMS", "subtype=Cog") != "") return false;
      return true;
    case "dead_threads":
      return CheckTapped("MYCHAR-$index", $currentPlayer) || GetClassState($currentPlayer, $CS_NumAllyPutInGraveyard) == 0;
    case "sealace_sarong":
      return CheckTapped("MYCHAR-$index", $currentPlayer) || !ArsenalHasArrowFacingColor($player, "DOWN", 3);
    case "riggermortis_yellow":
    case "swabbie_yellow":
    case "limpit_hop_a_long_yellow":
    case "barnacle_yellow":
    case "oysten_heart_of_gold_yellow":
    case "wailer_humperdinck_yellow":
    case "polly_cranka_ally":
    case "sticky_fingers_ally":
    case "scooba_salty_sea_dog_yellow":
    case "kelpie_tangled_mess_yellow":
      if($from == "PLAY") return CheckTapped("MYALLY-$index", $currentPlayer);
      return false;
    case "sky_skimmer_red":
    case "sky_skimmer_yellow":
    case "sky_skimmer_blue":
    case "cloud_skiff_red":
    case "cloud_skiff_yellow":
    case "cloud_skiff_blue":
      if ($player != $mainPlayer) return true;
      if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
      if (GetUntapped($player, "MYITEMS", "subtype=Cog") == "") return true;
      if ($from == "PLAY" && $combatChain[11] >= 1) return true;
      if ($from == "COMBATCHAINATTACKS" && $chainLinks[$index][9] >= 1) return true;
      return false;
    case "cloud_city_steamboat_red":
    case "cloud_city_steamboat_yellow":
    case "cloud_city_steamboat_blue":
    case "cogwerx_zeppelin_red":
    case "cogwerx_zeppelin_yellow":
    case "cogwerx_zeppelin_blue":
      if ($player != $mainPlayer) return true;
      if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
      if (GetUntapped($player, "MYITEMS", "subtype=Cog") == "") return true;
      if ($from == "PLAY" && $combatChain[11] >= 2) return true;
      if ($from == "COMBATCHAINATTACKS" && $chainLinks[$index][9] >= 2) return true;
      return false;
    case "palantir_aeronought_red":
    case "jolly_bludger_yellow":
    case "cogwerx_dovetail_red":
      if ($player != $mainPlayer) return true;
      if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
      if (GetUntapped($player, "MYITEMS", "subtype=Cog") == "") return true;
      if ($from == "PLAY" && $combatChain[11] >= 3) return true;
      if ($from == "COMBATCHAINATTACKS" && $chainLinks[$index][9] >= 3) return true;
      return false;
    case "old_knocker":
      return CheckTapped("MYCHAR-0", $currentPlayer);
    case "swiftstrike_bracers":
    case "quick_clicks":
      return GetClassState($currentPlayer, $CS_PlayedNimblism) == 0;
    case "captains_coat":
      return GetClassState($currentPlayer, $CS_NumCardsDrawn) == 0;
    case "platinum_amulet_blue":
      return $from == "PLAY" && NumCardsBlocking() < 1;
    case "goldkiss_rum":
      return CheckTapped("MYCHAR-0", $currentPlayer);
    case "arcane_compliance_blue":
      return SearchLayersCardType("A") == "" && SearchLayersCardType("AA") == "";
    case "breakwater_undertow":
      if (!$CombatChain->HasCurrentLink()) return true;
      return !ClassContains($CombatChain->CurrentAttack(), "PIRATE", $mainPlayer) || !SubtypeContains($CombatChain->CurrentAttack(), "Ally", $mainPlayer);
    case "midas_touch_yellow":
      return SearchMultizone($player, "MYALLY&THEIRALLY&MYCHAR:subtype=Ally&THEIRCHAR:subtype=Ally") == "";
    case "iris_of_the_blossom":
      $hand = GetHand($currentPlayer);
      if (GetClassState($player, $CS_HitCounter) == 0) return true;
      return CheckTapped("MYCHAR-$index", $currentPlayer) || count($hand) == 0;
    case "okana_scar_wraps":
      if (!$CombatChain->HasCurrentLink()) return true;
      $attackID = $CombatChain->AttackCard()->ID();
      if (!ClassContains($attackID, "NINJA", $currentPlayer) || !TypeContains($attackID, "AA", $currentPlayer)) return true;
      $edgeThere = SearchCharacterAlive($currentPlayer, "edge_of_autumn");
      $attacks = GetCombatChainAttacks();
      $countChainLinkSummary = count($chainLinkSummary);
      $chainLinkSummaryPieces = ChainLinkSummaryPieces();
      $chainLinksPieces = ChainLinksPieces();
      for ($i = 0; $i < $countChainLinkSummary; $i += $chainLinkSummaryPieces) {
        $ind = intdiv($i, $chainLinkSummaryPieces) * $chainLinksPieces;
        $attackID = $attacks[$ind];
        $names = GamestateUnsanitize($chainLinkSummary[$i+4]);
        if (!DelimStringContains(CardType($attackID), "W") && DelimStringContains($names, "Edge of Autumn")) {
          $edgeThere = true;
        }
      }
      return CheckTapped("MYCHAR-$index", $currentPlayer) || !$edgeThere;
    case "legacy_of_ikaru_blue":
      return !$CombatChain->HasCurrentLink() || !ClassContains($CombatChain->AttackCard()->ID(), "NINJA", $player);
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
    case "bravo_flattering_showman":
      return CheckTapped("MYCHAR-$index", $currentPlayer);
    case "kayo_underhanded_cheat":
    case "kayo_strong_arm":
      if (CheckTapped("MYCHAR-$index", $currentPlayer)) return true;
      if ($currentPlayer == $mainPlayer) {
        if(!$CombatChain->HasCurrentLink() && SearchLayersForPhase("RESOLUTIONSTEP") == -1 && !IsLayerStep()) return true;
        $previousLink = SearchCombatChainAttacks($currentPlayer, type:"AA") == "";
        $currentLink = !TypeContains($CombatChain->AttackCard()->ID(), "AA", $currentPlayer);
        $unresolvedAttacks = SearchLayersCardType("AA") == "";
        if ($previousLink && $currentLink && $unresolvedAttacks) return true;
      }
      else {
        //for now only support buffing cards on the current chain link
        $numOptions = GetChainLinkCards($currentPlayer, "AA", "C");
        if ($numOptions == "") return true;
      }
      return false;
    case "pleiades":
    case "pleiades_superstar":
      if (CheckTapped("MYCHAR-$index", $currentPlayer)) return true;
      //check that there's an aura with a suspense counter
      if (count(GetSuspenseAuras($currentPlayer, true)) == 0) return true;
      return false;
    default:
      return false;
  }
}

function IsDefenseReactionPlayable($cardID, $from)
{
  global $CombatChain, $mainPlayer;
  $attackCard = $CombatChain->AttackCard()->ID();
  $extraText = GetHorrorsBuff();
  $blocksDreacts = ["command_and_conquer_red", "back_stab_red", "back_stab_yellow", "back_stab_blue",
                    "widowmaker_red", "widowmaker_yellow", "widowmaker_blue", "wreck_havoc_red", "wreck_havoc_yellow", "wreck_havoc_blue"];
  if ((in_array($attackCard, $blocksDreacts) || in_array($extraText, $blocksDreacts)) && CardType($cardID) == "DR") return false;
  if ($CombatChain->AttackCard()->ID() == "exude_confidence_red") if (!ExudeConfidenceReactionsPlayable()) return false;
  if ($from == "HAND" && CardSubType($CombatChain->AttackCard()->ID()) == "Arrow" && SearchCharacterForCard($mainPlayer, "dreadbore")) return false;
  if (CurrentEffectPreventsDefenseReaction($from)) return false;
  if (SearchCurrentTurnEffects("exude_confidence_red", $mainPlayer)) return false;
  if (SearchCurrentTurnEffects("imperial_seal_of_command_red", $mainPlayer) && CardType($cardID) == "DR") return false;
  if ($from == "HAND" && CachedTotalPower() <= 2 && (SearchCharacterForCard($mainPlayer, "benji_the_piercing_wind") || SearchCurrentTurnEffects("benji_the_piercing_wind-SHIYANA", $mainPlayer)) && (SearchCharacterActive($mainPlayer, "benji_the_piercing_wind") || SearchCharacterActive($mainPlayer, "shiyana_diamond_gemini")) && CardType($CombatChain->AttackCard()->ID()) == "AA") return false;
  return true;
}

function IsAction($cardID, $from="")
{
  if(IsStaticType(CardType($cardID), $from, $cardID)) {
    $abilityType = GetAbilityType($cardID, from: $from);
    if ($abilityType == "A" || $abilityType == "AA") return true;
  }
  else {
    $cardType = CardType($cardID, $from);
    if (DelimStringContains($cardType, "A") || $cardType == "AA") return true;
  }
  return false;
}

function IsActionCard($cardID)
{
  $cardType = CardType($cardID);
  if (DelimStringContains($cardType, "A") || $cardType == "AA") return true;
  return false;
}

function GoesOnCombatChain($phase, $cardID, $from, $currentPlayer)
{
  global $layers, $combatChain;
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-" && $card->GoesOnCombatChain($phase, $from)) return true;
  switch ($cardID) {
    case "mighty_windup_red":
    case "mighty_windup_yellow":
    case "mighty_windup_blue":
    case "agile_windup_red":
    case "agile_windup_yellow":
    case "agile_windup_blue":
    case "vigorous_windup_red":
    case "vigorous_windup_yellow":
    case "vigorous_windup_blue":
    case "trip_the_light_fantastic_blue":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_red":
    case "fruits_of_the_forest_blue":
    case "fruits_of_the_forest_yellow":
    case "fruits_of_the_forest_red":
    case "ripple_away_blue":
    case "under_the_trap_door_blue":
    case "reapers_call_red":
    case "reapers_call_yellow":
    case "reapers_call_blue":
    case "tip_off_red":
    case "tip_off_yellow":
    case "tip_off_blue":
    case "deny_redemption_red":
    case "bam_bam_yellow":
    case "outside_interference_blue":
    case "fearless_confrontation_blue":
      return $phase == "B" && count($layers) == 0 || GetResolvedAbilityType($cardID, $from) == "AA";
    case "restless_coalescence_yellow":
    case "chum_friendly_first_mate_yellow":
    case "anka_drag_under_yellow":
    case "sawbones_dock_hand_yellow":
    case "chowder_hearty_cook_yellow":
    case "cutty_shark_quick_clip_yellow":
      return GetResolvedAbilityType($cardID, $from) == "AA";
    case "shelter_from_the_storm_red":
      return GetResolvedAbilityType($cardID, $from) == "DR";
    case "war_cry_of_bellona_yellow":
      return $phase == "B" || GetResolvedAbilityType($cardID, $from) == "AR";
    case "quickdodge_flexors":
      if (!CanBlockWithEquipment()) return false;
      if (!CanBlock($cardID, "EQUIP")) return false;
      $countCombatChain = count($combatChain);
      $combatChainPieces = CombatChainPieces();
      for ($i = 0; $i < $countCombatChain; $i += $combatChainPieces) {
        if ($combatChain[$i] == $cardID) return false;
      }
      return true;
    case "bunker_beard":
      return $phase == "B";
    default:
      break;
  }
  if (canBeAddedToChainDuringDR($cardID) && $phase == "D") return true;
  if ($phase != "B" && ($from == "EQUIP" || $from == "PLAY" || $from == "COMBATCHAINATTACKS")) $cardType = GetResolvedAbilityType($cardID, $from, $currentPlayer);
  else if ($phase == "M" && $cardID == "guardian_of_the_shadowrealm_red" && $from == "BANISH") $cardType = GetResolvedAbilityType($cardID, $from, $currentPlayer);
  else $cardType = CardType($cardID);
  if ($phase == "B" && count($layers) == 0) return true; //Anything you play during these combat phases would go on the chain
  if (DelimStringContains($cardType, "I")) return false; //Instants as yet never go on the combat chain
  if (($phase == "A" || $phase == "D") && DelimStringContains($cardType, "A")) return false; //Non-attacks played as instants never go on combat chain
  if ($cardType == "AR") return true; // Technically wrong, AR goes to the graveyard instead of remaining on the active chain link. CR 2.4.0 - 8.1.2b
  if ($cardType == "DR") return true;
  if (($phase == "M" || $phase == "ATTACKWITHIT") && $cardType == "AA") return true; //If it's an attack action, it goes on the chain
  return false;
}

function IsStaticType($cardType, $from = "", $cardID = "")
{
  if (
    DelimStringContains($cardType, "C") || 
    DelimStringContains($cardType, "E") || 
    DelimStringContains($cardType, "W") || 
    DelimStringContains($cardType, "D") || 
    DelimStringContains($cardType, "Companion") || 
    $from == "PLAY" || 
    $from == "COMBATCHAINATTACKS" || 
    $from == "ARS" && DelimStringContains($cardType, "M") || 
    $cardID != "" && $from == "BANISH" && AbilityPlayableFromBanish($cardID)) {
      return true;
  }
  return false;
}

function IsActivated($cardID, $from) {
  $cardType = CardType($cardID, $from);
  if (IsStaticType($cardType, $from, $cardID)) return true;
  if (GetAbilityTypes($cardID, -1, $from) == "") return false;
  else return GetResolvedAbilityType($cardID, $from) != $cardType;
}

function HasBladeBreak($cardID)
{
  global $defPlayer, $CID_TekloHead, $CID_TekloChest, $CID_TekloArms, $CID_TekloLegs;
  switch ($cardID) {
    case $CID_TekloHead:
    case $CID_TekloChest:
    case $CID_TekloArms:
    case $CID_TekloLegs:
      return true;
    case "vambrace_of_determination":
      return SearchCurrentTurnEffects($cardID . "-BB", $defPlayer);
    case "heirloom_of_tiger_hide":
      $char = &GetPlayerCharacter($defPlayer);
      $index = FindCharacterIndex($defPlayer, $cardID);
      return $char[$index + 12] == "UP";
    case "mask_of_malicious_manifestations":
      return true;
    case "glove_of_azure_waves":
      return HighTideConditionMet($defPlayer);
    default:
      return GeneratedHasBladeBreak($cardID);
  }
}

function HasBattleworn($cardID)
{
  global $defPlayer;
  switch ($cardID) {
    case "teklovossen_the_mechropotentb":
    case "torc_of_vim":
    case "echo_casque":
      return true;
    case "heirloom_of_snake_hide":
      $char = &GetPlayerCharacter($defPlayer);
      $index = FindCharacterIndex($defPlayer, $cardID);
      return $char[$index + 12] == "UP";
    default:
      return GeneratedHasBattleworn($cardID);
  }
}

function HasTemper($cardID)
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->HasTemper();
  switch ($cardID) {
    case "trampling_trackers":
    case "nitro_mechanoidb":
      return true;
  }
  return GeneratedHasTemper($cardID);
}

function HasGuardwell($cardID)
{
  return GeneratedHasGuardwell($cardID);
}

function HasPiercing($cardID, $from = "")
{
  switch ($cardID) {
     //Weapons with Piercing
    case "spiders_bite":
    case "spiders_bite_r":
    case "nerve_scalpel":
    case "nerve_scalpel_r":
    case "orbitoclast":
    case "orbitoclast_r":
    case "scale_peeler":
    case "scale_peeler_r":
    case "graven_call":
    case "hunters_klaive":
    case "hunters_klaive_r":
      return true;
    case "precision_press_red":
    case "precision_press_yellow":
    case "precision_press_blue":
    case "puncture_red":
    case "puncture_yellow":
    case "puncture_blue":
    case "visit_the_imperial_forge_red":
    case "visit_the_imperial_forge_yellow":
    case "visit_the_imperial_forge_blue":
      return !IsPlayRestricted($cardID, $restriction, $from) || IsCombatEffectActive($cardID);
    case "drill_shot_red":
    case "drill_shot_yellow":
    case "drill_shot_blue":
      return HasAimCounter();
    default:
      return GeneratedHasPiercing($cardID);
  }
}

function HasTower($cardID)
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->HasTower();
  return GeneratedHasTower($cardID);
}

function RequiresDiscard($cardID)
{
  switch ($cardID) {
    case "bloodrush_bellow_yellow":
    case "reckless_swing_blue":
    case "breakneck_battery_red":
    case "breakneck_battery_yellow":
    case "breakneck_battery_blue":
    case "savage_feast_red":
    case "savage_feast_yellow":
    case "savage_feast_blue":
    case "savage_swing_red":
    case "savage_swing_yellow":
    case "savage_swing_blue":
    case "wrecker_romp_red":
    case "wrecker_romp_yellow":
    case "wrecker_romp_blue":
    case "primeval_bellow_red":
    case "primeval_bellow_yellow":
    case "primeval_bellow_blue":
    case "barraging_big_horn_red":
    case "barraging_big_horn_yellow":
    case "barraging_big_horn_blue":
    case "swing_fist_think_later_red":
    case "swing_fist_think_later_yellow":
    case "swing_fist_think_later_blue":
    case "savage_beatdown_red":
    case "madcap_charger_red":
    case "madcap_charger_yellow":
    case "madcap_charger_blue":
    case "madcap_muscle_red":
    case "madcap_muscle_yellow":
    case "madcap_muscle_blue":
      return true;
    default:
      return false;
  }
}

function RequiresBanish($cardID)
{
  switch ($cardID) {
    case "expendable_limbs_blue":
    case "ram_raider_red":
    case "ram_raider_yellow":
    case "ram_raider_blue":
    case "shaden_scream_red":
    case "shaden_scream_yellow":
    case "shaden_scream_blue":
    case "shaden_swing_red":
    case "shaden_swing_yellow":
    case "shaden_swing_blue":
    case "tribute_to_demolition_red":
    case "tribute_to_demolition_yellow":
    case "tribute_to_demolition_blue":
    case "tribute_to_the_legions_of_doom_red":
    case "tribute_to_the_legions_of_doom_yellow":
    case "tribute_to_the_legions_of_doom_blue":
      return true;
    default:
      return false;
  }
}

function HasBeatChest($cardID)
{
  $card = GetClass($cardID, 1);
  if ($card != "-") return $card->HasBeatChest();
  return GeneratedHasBeatChest($cardID);
}

function ETASteamCounters($cardID)
{
  global $currentPlayer;
  switch ($cardID) {
    case "aether_sink_yellow":
    case "dissolution_sphere_yellow":
    case "signal_jammer_blue":
    case "prismatic_lens_yellow":
    case "quantum_processor_yellow":
    case "tick_tock_clock_red":
    case "polarity_reversal_script_red":
    case "penetration_script_yellow":
    case "security_script_blue":
    case "backup_protocol_red_red":
    case "backup_protocol_yel_yellow":
    case "backup_protocol_blu_blue":
    case "boom_grenade_red":
    case "boom_grenade_yellow":
    case "boom_grenade_blue":
    case "dissolving_shield_blue":
    case "grinding_gears_blue":
    case "overload_script_red":
    case "mhz_script_yellow":
    case "autosave_script_blue":
    case "golden_cog":
    case "assembly_module_blue":
      return 1;
    case "teklo_core_blue":
    case "convection_amplifier_red":
    case "dissolving_shield_yellow":
    case "mini_forcefield_blue":
    case "hadron_collider_blue":
    case "cerebellum_processor_blue":
    case "null_time_zone_blue":
    case "clamp_press_blue":
    case "copper_cog_blue":
      return 2;
    case "teklo_pounder_blue":
    case "dissolving_shield_red":
    case "mini_forcefield_yellow":
    case "hadron_collider_yellow":
      return 3;
    case "dissipation_shield_yellow":
    case "mini_forcefield_red":
    case "hadron_collider_red":
      return 4;
    case "optekal_monocle_blue":
    case "plasma_mainline_red":
      return 5;
    case "hyper_driver_red":
      if (SearchCharacterActive($currentPlayer, "puffer_jacket")) return 4;
      else return 3;
    case "hyper_driver_yellow":
      if (SearchCharacterActive($currentPlayer, "puffer_jacket")) return 3;
      else return 2;
    case "hyper_driver_blue":
      if (SearchCharacterActive($currentPlayer, "puffer_jacket")) return 2;
      else return 1;
    default:
      return 0;
  }
}

function AbilityHasGoAgain($cardID, $from)
{
  global $currentPlayer;
  $cardID = ShiyanaCharacter($cardID);
  $set = CardSet($cardID);
  $class = CardClass($cardID);
  $subtype = CardSubtype($cardID);
  $abilityType = GetResolvedAbilityType($cardID);
  if ($class == "ILLUSIONIST" && DelimStringContains($subtype, "Aura") && SearchCharacterForCard($currentPlayer, "iris_of_reality") && $abilityType == "AA") return true;
  if (class_exists($cardID)) {
    $card = new $cardID($currentPlayer);
    return $card->AbilityHasGoAgain($from);
  }
  if ($set == "WTR") return WTRAbilityHasGoAgain($cardID);
  else if ($set == "ARC") return ARCAbilityHasGoAgain($cardID);
  else if ($set == "CRU") return CRUAbilityHasGoAgain($cardID);
  else if ($set == "MON") return MONAbilityHasGoAgain($cardID);
  else if ($set == "ELE") return ELEAbilityHasGoAgain($cardID);
  else if ($set == "EVR") return EVRAbilityHasGoAgain($cardID);
  else if ($set == "UPR") return UPRAbilityHasGoAgain($cardID);
  else if ($set == "DYN") return DYNAbilityHasGoAgain($cardID);
  else if ($set == "OUT") return OUTAbilityHasGoAgain($cardID);
  else if ($set == "DTD") return DTDAbilityHasGoAgain($cardID);
  else if ($set == "TCC") return TCCAbilityHasGoAgain($cardID);
  else if ($set == "EVO") return EVOAbilityHasGoAgain($cardID);
  else if ($set == "HVY") return HVYAbilityHasGoAgain($cardID);
  else if ($set == "AKO") return AKOAbilityHasGoAgain($cardID);
  else if ($set == "AAZ") return AAZAbilityHasGoAgain($cardID);
  else if ($set == "ROS") return ROSAbilityHasGoAgain($cardID);
  else if ($set == "AIO") return AIOAbilityHasGoAgain($cardID);
  else if ($set == "AJV") return AJVAbilityHasGoAgain($cardID);
  else if ($set == "HNT") return HNTAbilityHasGoAgain($cardID);
  else if ($set == "AST") return ASTAbilityHasGoAgain($cardID);
  else if ($set == "SEA") return SEAAbilityHasGoAgain($cardID, $from);
  else if ($set == "MPG") return MPGAbilityHasGoAgain($cardID);
  else if ($set == "SUP") return SUPAbilityHasGoAgain($cardID);
  else if ($set == "APS") return APSAbilityHasGoAgain($cardID);
  else if ($set == "AAC") return AACAbilityHasGoAgain($cardID);
  else if ($set == "ARR") return ARRAbilityHasGoAgain($cardID);
  else if ($set == "PEN") return PENAbilityHasGoAgain($cardID);
  switch ($cardID) {
    case "blossom_of_spring":
    case "bravo_flattering_showman":
      return true;
    default:
      return false;
  }
}

function DoesEffectGrantsOverpower($cardID): bool
{
  $cardID = ShiyanaCharacter($cardID);
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  return match ($cardID) {
    "betsy_skin_in_the_game", "betsy", "the_golden_son_yellow", "down_but_not_out_red", "down_but_not_out_yellow", "down_but_not_out_blue", "log_fall_red", "log_fall_yellow", "machinations_of_dominion_blue" => true,
    "bank_breaker", "board_the_ship_red" => true,
    "hammerhead_harpoon_cannon" => CardNameContains($attackID, "Harpoon", $mainPlayer, true),
    "jolly_bludger_yellow-OP" => true,
    "monkey_powder_red" => true,
    "heave_ho_blue" => true,
    "burly_bones_red", "burly_bones_yellow", "burly_bones_blue" => true,
    "sinker_blue" => true,
    "mutiny_on_the_nimbus_sovereign_blue" => true,
    default => false,
  };
}

function DoesEffectGrantsDominate($cardID): bool
{
  global $combatChainState, $CCS_AttackFused, $mainPlayer;
  $cardID = ShiyanaCharacter($cardID);
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") return $card->DoesEffectGrantDominate();
  switch ($cardID) {
    case "bravo_showstopper":
    case "bravo":
    case "regurgitating_slog_red":
    case "regurgitating_slog_yellow":
    case "regurgitating_slog_blue":
    case "pedal_to_the_metal_red":
    case "pedal_to_the_metal_yellow":
    case "pedal_to_the_metal_blue":
    case "convection_amplifier_red":
    case "azalea_ace_in_the_hole":
    case "azalea":
    case "predatory_assault_red":
    case "predatory_assault_yellow":
    case "predatory_assault_blue":
    case "emerging_dominance_red":
    case "emerging_dominance_yellow":
    case "emerging_dominance_blue":
    case "push_forward_red-2":
    case "push_forward_yellow-2":
    case "push_forward_blue-2":
    case "high_speed_impact_red":
    case "high_speed_impact_yellow":
    case "high_speed_impact_blue":
    case "spill_blood_red":
    case "writhing_beast_hulk_red":
    case "writhing_beast_hulk_yellow":
    case "writhing_beast_hulk_blue":
    case "convulsions_from_the_bellows_of_hell_red":
    case "convulsions_from_the_bellows_of_hell_yellow":
    case "convulsions_from_the_bellows_of_hell_blue":
    case "consuming_aftermath_red":
    case "consuming_aftermath_yellow":
    case "consuming_aftermath_blue":
    case "pulping_red":
    case "pulping_yellow":
    case "pulping_blue":
    case "pound_for_pound_red":
    case "pound_for_pound_yellow":
    case "pound_for_pound_blue":
    case "lady_barthimont":
    case "oaken_old_red":
    case "glacial_footsteps_red":
    case "glacial_footsteps_yellow":
    case "glacial_footsteps_blue":
    case "shiver-2":
    case "flake_out_red":
    case "flake_out_yellow":
    case "flake_out_blue":
    case "flashfreeze_red-DOMATK":
    case "entwine_ice_red":
    case "entwine_ice_yellow":
    case "entwine_ice_blue":
    case "polar_blast_red":
    case "polar_blast_yellow":
    case "polar_blast_blue":
    case "tear_asunder_blue":
    case "bravo_star_of_the_show":
    case "valda_brightaxe":
    case "valda_seismic_impact":
    case "rise_up_red":
    case "buckle_blue":
    case "figment_of_tenacity_yellow":
    case "metis_archangel_of_tenacity":
    case "crumble_to_eternity_blue":
    case "bravo_flattering_showman":
    case "murky_water_red":
    case "stone_rain_red":
    case "gauntlets_of_the_boreal_domain-I":
    case "craterhoof":
      return true;
    case "weave_ice_red":
    case "weave_ice_yellow":
    case "weave_ice_blue":
      return $combatChainState[$CCS_AttackFused] == 1;
    default:
      return false;
  }
}

function CharacterNumUsesPerTurn($cardID)
{
  switch ($cardID) {
    case "bravo_showstopper":
    case "bravo":
    case "helios_mitre":
    case "emperor_dracai_of_aesir":
    case "seerstone":
    case "nitro_mechanoida":
    case "stasis_cell_blue":
    case "teklovossen_the_mechropotent":
    case "nuu_alluring_desire":
    case "nuu":
    case "enigma_new_moon":
    case "sanctuary_of_aria":
    case "quickdodge_flexors":
    case "bravo_flattering_showman":
    case "redspine_manta":
    case "sealace_sarong":
    case "cogwerx_blunderbuss":
    case "dead_threads":
    case "puffin":
    case "puffin_hightail":
    case "hammerhead_harpoon_cannon":
    case "spitfire":
    case "compass_of_sunken_depths":
    case "gravy_bones":
    case "gravy_bones_shipwrecked_looter":
    case "marlynn":
    case "marlynn_treasure_hunter":
    case "gold_baited_hook":
    case "scurv_stowaway":
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
    case "kayo_underhanded_cheat":
    case "kayo_strong_arm":
    case "pleiades":
    case "pleiades_superstar":
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
    case "mbrio_base_digits":
    case "farflight_longbow":
      return 999;
    case "voltaire_strike_twice":
    case "barbed_castaway":  
    case "bank_breaker":
      return 2;
    default:
      return 1;
  }
}

//Active (2 = Always Active, 1 = Yes, 0 = No)
function CharacterDefaultActiveState($cardID)
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->DefaultActiveState();
  switch ($cardID) {
    case "silken_form":
    case "heat_wave":
    case "conduit_of_frostburn":
    case "quelling_robe":
    case "quelling_sleeves":
    case "quelling_slippers":
    case "shroud_of_darkness":
    case "cloak_of_darkness":
    case "grasp_of_darkness":
    case "dance_of_darkness":
    case "blasmophet_levia_consumed":
      return 0;
    case "refraction_bolters":
    case "vest_of_the_first_fist":
    case "breeze_rider_boots":
    case "metacarpus_node":
    case "hooves_of_the_shadowbeast":
    case "plume_of_evergrowth":
    case "shock_charmers":
    case "mark_of_lightning":
    case "halo_of_illumination":
    case "dream_weavers":
    case "ebon_fold":
    case "spell_fray_tiara":
    case "spell_fray_cloak":
    case "spell_fray_gloves":
    case "spell_fray_leggings":
    case "mask_of_the_pouncing_lynx":
    case "beaten_trackers":
    case "quiver_of_abyssal_depths":
    case "quiver_of_rustling_leaves":
    case "enchanted_quiver":
    case "driftwood_quiver":
    case "evo_circuit_breaker_red_equip":
    case "evo_atom_breaker_red_equip":
    case "evo_face_breaker_red_equip":
    case "evo_mach_breaker_red_equip":
    case "hide_tanner":
    case "grains_of_bloodspill":
    case "meridian_pathway":
    case "longdraw_half_glove":
    case "aether_crackers":
    case "hard_knuckle":
    case "verdance_thorn_of_the_rose":
    case "verdance":
    case "arcanite_fortress":
    case "widow_veil_respirator":
    case "widow_back_abdomen":
    case "widow_claw_tarsus":
    case "widow_web_crawler":
    case "blood_splattered_vest":
    case "leap_frog_vocal_sac":
    case "leap_frog_slime_skin":
    case "leap_frog_gloves":
    case "leap_frog_leggings":
    case "robe_of_autumns_fall":
    case "prism_awakener_of_sol":
    case "prism_advent_of_thrones":
    case "storm_striders":
    case "okana_scar_wraps":
    case "alluvion_constellas":
    case "compass_of_sunken_depths":
    case "pouncing_paws":
    case "fyendals_spring_tunic":
    case "halo_of_lumina_light":
    case "unicycle":
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
    case "trench_of_sunken_treasure":
    case "well_grounded":
    case "tiara_of_suspense":
    case "aether_bindings_of_the_third_age":
    case "mask_of_many_faces":
    case "ornate_tessen":
    case "radiant_flow":
    case "radiant_raiment":
    case "radiant_touch":
    case "radiant_view":
    case "tremorshield_sabatons":
    case "grimoire_of_fellingsong":
    case "sealace_sarong":
    case "talismanic_lense":
    case "gravy_bones":
    case "gravy_bones_shipwrecked_looter":
    case "dead_threads":
    case "voltic_vanguard":
    case "kimono_of_layered_lessons":
    case "templar_spellbane":
      return 1;
    default:
      return 2;
  }
}

//Hold priority for triggers (2 = Always hold, 1 = Hold, 0 = Don't Hold)
function AuraDefaultHoldTriggerState($cardID): int
{
  return match ($cardID) {
    "forged_for_war_yellow", "show_time_blue", "blessing_of_deliverance_red", "blessing_of_deliverance_yellow", "blessing_of_deliverance_blue", "emerging_power_red", "emerging_power_yellow", "emerging_power_blue", "stonewall_confidence_red", "stonewall_confidence_yellow", "stonewall_confidence_blue",
    "seismic_surge", "eloquence", "tome_of_aeo_blue", "fog_down_yellow", "sigil_of_protection_red", "sigil_of_protection_yellow", "sigil_of_protection_blue", "runeblood_incantation_red", "runeblood_incantation_yellow", "runeblood_incantation_blue", "pyroglyphic_protection_red",
    "pyroglyphic_protection_yellow", "pyroglyphic_protection_blue", "emerging_avalanche_red", "emerging_avalanche_yellow", "emerging_avalanche_blue", "strength_of_sequoia_red", "strength_of_sequoia_yellow", "strength_of_sequoia_blue", "embolden_red", "embolden_yellow", "embolden_blue",
    "embodiment_of_earth", "embodiment_of_lightning", "frostbite", "stamp_authority_blue", "towering_titan_red", "towering_titan_yellow", "towering_titan_blue", "emerging_dominance_red", "emerging_dominance_yellow", "emerging_dominance_blue", "zen_state", "preach_modesty_red",
    "runeblood_barrier_yellow", "soul_shackle", "channel_mount_isen_blue" => 0,
    "runechant", "spellbane_aegis" => 1,
    default => 2
  };
}

function ItemDefaultHoldTriggerState($cardID, $player)
{
  switch ($cardID) {
    case "teklo_core_blue":
    case "dissipation_shield_yellow":
    case "talisman_of_dousing_yellow":
    case "dissolution_sphere_yellow":
    case "signal_jammer_blue":
      return 1;
    case "goldkiss_rum":
      return 0;
    default:
      break;
  }
  return ETASteamCounters($cardID) > 0 ? 0 : 2;
}

//cards where the Hold Trigger State is interpretted as an active state
function ItemActiveStateTracked($cardID) {
  return match($cardID) {
    "goldkiss_rum" => true,
    default => false,
  };
}

function IsCharacterActive($player, $index) 
{
  $character = &GetPlayerCharacter($player);
  if (isset($character[$index + 9])) {
    return $character[$index + 9] == "1" || $character[$index + 9] == "2";
  } else {
    return false;
  }
}

function HasReprise($cardID)
{
  return GeneratedHasReprise($cardID);
}

function RepriseActive()
{
  global $currentPlayer, $mainPlayer;
  if ($currentPlayer == $mainPlayer) return CachedNumDefendedFromHand() > 0;
  else return 0;
}

function HasCombo($cardID)
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->HasCombo();
  return GeneratedHasCombo($cardID);
}

function ComboActive($cardID = "")
{
  global $CombatChain, $chainLinkSummary, $mainPlayer, $chainLinks, $ChainLinks;
  if ($cardID == "" && $CombatChain->HasCurrentLink()) $cardID = $CombatChain->AttackCard()->ID();
  if ($cardID == "") return false;
  if (count($chainLinkSummary) == 0) return false;//No combat active if no previous chain links
  $card = GetClass($cardID, $mainPlayer);
  $lastAttackNames = explode(",", $chainLinkSummary[count($chainLinkSummary) - ChainLinkSummaryPieces() + 4]);
  $countLastAttacks = count($lastAttackNames);
  for ($i = 0; $i < $countLastAttacks; ++$i) {
    $lastAttackName = GamestateUnsanitize($lastAttackNames[$i]);
    if (SearchCurrentTurnEffects("amnesia_red", $mainPlayer)) $lastAttackName = "";
    if ($card != "-") {
      if ($card->ComboActive($lastAttackName)) return true;
    }
    switch ($cardID) {
      case "lord_of_wind_blue":
        if ($lastAttackName == "Mugenshi: RELEASE") return true;
        break;
      case "mugenshi_release_yellow":
        if ($lastAttackName == "Whelming Gustwave") return true;
        break;
      case "hurricane_technique_yellow":
        if ($lastAttackName == "Rising Knee Thrust") return true;
        break;
      case "pounding_gale_red":
        if ($lastAttackName == "Open the Center") return true;
        break;
      case "fluster_fist_red":
      case "fluster_fist_yellow":
      case "fluster_fist_blue":
        if ($lastAttackName == "Open the Center") return true;
        break;
      case "blackout_kick_red":
      case "blackout_kick_yellow":
      case "blackout_kick_blue":
        if ($lastAttackName == "Rising Knee Thrust") return true;
        break;
      case "open_the_center_red":
      case "open_the_center_yellow":
      case "open_the_center_blue":
        if ($lastAttackName == "Head Jab") return true;
        break;
      case "rising_knee_thrust_red":
      case "rising_knee_thrust_yellow":
      case "rising_knee_thrust_blue":
        if ($lastAttackName == "Leg Tap") return true;
        break;
      case "whelming_gustwave_red":
      case "whelming_gustwave_yellow":
      case "whelming_gustwave_blue":
        if ($lastAttackName == "Surging Strike") return true;
        break;
      case "find_center_blue":
        if ($lastAttackName == "Crane Dance") return true;
        break;
      case "flood_of_force_yellow":
        if ($lastAttackName == "Rushing River" || $lastAttackName == "Flood of Force") return true;
        break;
      case "herons_flight_red":
        if ($lastAttackName == "Crane Dance") return true;
        break;
      case "crane_dance_red":
      case "crane_dance_yellow":
      case "crane_dance_blue":
        if ($lastAttackName == "Soulbead Strike") return true;
        break;
      case "rushing_river_red":
      case "rushing_river_yellow":
      case "rushing_river_blue":
        if ($lastAttackName == "Torrent of Tempo") return true;
        break;
      case "break_tide_yellow":
        if ($lastAttackName == "Rushing River" || $lastAttackName == "Flood of Force") return true;
        break;
      case "winds_of_eternity_blue":
        if ($lastAttackName == "Hundred Winds") return true;
        break;
      case "hundred_winds_red":
      case "hundred_winds_yellow":
      case "hundred_winds_blue":
        if ($lastAttackName == "Hundred Winds") return true;
        break;
      case "tiger_swipe_red":
      case "pouncing_qi_red":
      case "pouncing_qi_yellow":
      case "pouncing_qi_blue":
      case "qi_unleashed_red":
      case "qi_unleashed_yellow":
      case "qi_unleashed_blue":
        if ($lastAttackName == "Crouching Tiger") return true;
        break;
      case "cyclone_roundhouse_yellow":
        if ($lastAttackName == "Spinning Wheel Kick") return true;
        break;
      case "dishonor_blue":
        if ($lastAttackName == "Bonds of Ancestry") return true;
        break;
      case "bonds_of_ancestry_red":
      case "bonds_of_ancestry_yellow":
      case "bonds_of_ancestry_blue":
        if (str_contains($lastAttackName, "Gustwave")) return true;
        break;
      case "recoil_red":
      case "recoil_yellow":
      case "recoil_blue":
        if ($lastAttackName == "Head Jab") return true;
        break;
      case "spinning_wheel_kick_red":
      case "spinning_wheel_kick_yellow":
      case "spinning_wheel_kick_blue":
        if ($lastAttackName == "Twin Twisters" || $lastAttackName == "Spinning Wheel Kick") return true;
        break;
      case "back_heel_kick_red":
      case "back_heel_kick_yellow":
      case "back_heel_kick_blue":
        if ($lastAttackName == "Twin Twisters") return true;
        break;
      case "descendent_gustwave_red":
      case "descendent_gustwave_yellow":
      case "descendent_gustwave_blue":
        if ($lastAttackName == "Surging Strike") return true;
        break;
      case "one_two_punch_red":
      case "one_two_punch_yellow":
      case "one_two_punch_blue":
        if ($lastAttackName == "Head Jab") return true;
        break;
      case "mauling_qi_red":
        if ($lastAttackName == "Crouching Tiger") return true;
        break;
      case "chase_the_tail_red":
        if ($lastAttackName == "Crouching Tiger") return true;
        break;
      case "aspect_of_tiger_body_red":
        return DelimStringContains($ChainLinks->LastLink()->Colors(), 1);
      case "aspect_of_tiger_soul_yellow":
        return DelimStringContains($ChainLinks->LastLink()->Colors(), 2);
      case "aspect_of_tiger_mind_blue":
        return DelimStringContains($ChainLinks->LastLink()->Colors(), 3);
      case "breed_anger_red":
      case "breed_anger_yellow":
      case "breed_anger_blue":
        if ($lastAttackName == "Crouching Tiger") return true;
        break;
      case "gustwave_of_the_second_wind_red":
        if ($lastAttackName == "Surging Strike") return true;
        break;
      case "retrace_the_past_blue":
        if (str_contains($lastAttackName, "Gustwave")) return true;
        break;
      case "enact_vengeance_red":
        if ($lastAttackName == "Edge of Autumn") return true;
        if (str_contains($lastAttackName, "Vengeance")) return true;
        break;
      case "seek_vengeance_red":
      case "seek_vengeance_blue":
      case "vengeance_never_rests_blue":
        if ($lastAttackName == "Edge of Autumn") return true;
        break;
      default:
        break;
    }
  }
  return false;
}

function HasBloodDebt($cardID)
{
  global $currentPlayer;
  $char = GetPlayerCharacter($currentPlayer);
  if ($char[0] == "levia_redeemed") return false;
  return GeneratedHasBloodDebt($cardID);
}

function HasRunegate($cardID)
{
  return GeneratedHasRunegate($cardID);
}

function PlayableFromBanish($cardID, $mod = "", $nonLimitedOnly = false, $player = "")
{
  global $currentPlayer, $CS_NumNonAttackCards, $CS_Num6PowBan;
  if ($player == "") $player = $currentPlayer;
  $mod = explode("-", $mod ?? "")[0];
  if ($mod == "TRAPDOOR") return SubtypeContains($cardID, "Trap", $currentPlayer);
  if (isFaceDownMod($mod)) return false;
  if ($mod == "TCL" || $mod == "TT" || $mod == "TCC" || $mod == "NT" || $mod == "INST" || $mod == "spew_shadow_red" || $mod == "sonic_boom_yellow" || $mod == "blossoming_spellblade_red") return true;
  if (str_contains($mod, "shadowrealm_horror_red") && SearchCurrentTurnEffects("shadowrealm_horror_red-3", $player) && CardType($cardID) != "E") return true;
  if (HasRunegate($cardID) && SearchCount(SearchAurasForCard("runechant", $player, false)) >= CardCost($cardID, "BANISH")) return true;
  $char = &GetPlayerCharacter($player);
  if (SubtypeContains($cardID, "Evo") && ($char[0] == "professor_teklovossen" || $char[0] == "teklovossen_esteemed_magnate" || $char[0] == "teklovossen") && $char[1] < 3) return true;
  if (!$nonLimitedOnly && $char[0] == "blasmophet_levia_consumed" && SearchCurrentTurnEffects("blasmophet_levia_consumed", $player) && HasBloodDebt($cardID) && $char[1] < 3 && !TypeContains($cardID, "E") && !TypeContains($cardID, "W")) return true;
  $card = GetClass($cardID, $player);
  if ($card != "-") return $card->PlayableFromBanish($mod, $nonLimitedOnly);
  switch ($cardID) {
    case "deep_rooted_evil_yellow":
      return GetClassState($player, $CS_Num6PowBan) > 0;
    case "shadow_of_ursur_blue":
    case "invert_existence_blue":
      return true;
    case "unhallowed_rites_red":
    case "unhallowed_rites_yellow":
    case "unhallowed_rites_blue":
      return GetClassState($player, $CS_NumNonAttackCards) > 0;
    case "seeping_shadows_red":
    case "seeping_shadows_yellow":
    case "seeping_shadows_blue":
      return true;
    case "bounding_demigon_red":
    case "bounding_demigon_yellow":
    case "bounding_demigon_blue":
      return GetClassState($player, $CS_NumNonAttackCards) > 0;
    case "piercing_shadow_vise_red":
    case "piercing_shadow_vise_yellow":
    case "piercing_shadow_vise_blue":
      return true;
    case "rift_bind_red":
    case "rift_bind_yellow":
    case "rift_bind_blue":
      return true;
    case "rifted_torment_red":
    case "rifted_torment_yellow":
    case "rifted_torment_blue":
      return true;
    case "rip_through_reality_red":
    case "rip_through_reality_yellow":
    case "rip_through_reality_blue":
      return true;
    case "seeds_of_agony_red":
    case "seeds_of_agony_yellow":
    case "seeds_of_agony_blue":
      return true;
    case "eclipse_blue":
    case "mutated_mass_blue":
    case "tome_of_torment_red":
    case "howl_from_beyond_red":
    case "howl_from_beyond_yellow":
    case "howl_from_beyond_blue":
    case "ghostly_visit_red":
    case "ghostly_visit_yellow":
    case "ghostly_visit_blue":
    case "void_wraith_red":
    case "void_wraith_yellow":
    case "void_wraith_blue":
      return true;
    case "requiem_for_the_damned_red":
    case "putrid_stirrings_red":
    case "putrid_stirrings_yellow":
    case "putrid_stirrings_blue":
    case "grim_feast_red":
    case "grim_feast_yellow":
    case "grim_feast_blue":
    case "funeral_moon_red":
    case "chains_of_mephetis_blue":
    case "dimenxxional_vortex":
      return true;
    case "hungering_demigon_red":
    case "hungering_demigon_yellow":
    case "hungering_demigon_blue":
      $soul = &GetSoul($player == 1 ? 2 : 1);
      return count($soul) > 0;
    case "vile_inquisition_red":
    case "vile_inquisition_yellow":
    case "vile_inquisition_blue":
      return true;
    case "cull_red":
      return true;
    default:
      break;
  }
  return false;
}

function AbilityPlayableFromBanish($cardID, $mod = "")
{
  global $currentPlayer, $mainPlayer;
  $mod = explode("-", $mod ?? "")[0];
  if (isFaceDownMod($mod)) return false;
  switch ($cardID) {
    case "guardian_of_the_shadowrealm_red":
      return $currentPlayer == $mainPlayer;
    default:
      return false;
  }
}

function PlayableFromOtherPlayerBanish($cardID, $mod = "", $player = "")
{
  global $currentPlayer;
  $mod = explode("-", $mod)[0];
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $player == 1 ? 2 : 1;
  if (isFaceDownMod($mod)) return false;
  if (ColorContains($cardID, 3, $otherPlayer) && (SearchCurrentTurnEffects("nuu_alluring_desire", $player) || SearchCurrentTurnEffects("nuu", $player))) return true;
  if ($mod == "NTFromOtherPlayer" || $mod == "TTFromOtherPlayer" || $mod == "TCCGorgonsGaze") return true;
  else return false;
}

function PlayableFromOtherPlayerArsenal($cardID, $face="DOWN", $player ="")
{
  global $currentPlayer;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $player == 1 ? 2 : 1;
  if ($face == "UP" && SearchCurrentTurnEffects("annexation_of_all_things_known_yellow-MAIN", $player)) return true;
  else return false;
}

function PlayableFromGraveyard($cardID, $mod="-", $player = "", $index = -1)
{
  global $currentPlayer, $mainPlayer, $currentTurnEffects;
  if ($player == "") $player = $currentPlayer;
  if (isFaceDownMod($mod)) return false;
  if (HasWateryGrave($cardID) && SearchCurrentTurnEffects("gravy_bones_shipwrecked_looter", $player) && SearchCharacterActive($player, "gravy_bones_shipwrecked_looter") && $player == $currentPlayer) return true;
  if (HasWateryGrave($cardID) && SearchCurrentTurnEffects("gravy_bones", $player) && SearchCharacterActive($player, "gravy_bones")  && $player == $currentPlayer) return true;
  if (HasSuspense($cardID) && SearchCurrentTurnEffects("cries_of_encore_red", $player)) return true;
  $card = GetClass($cardID, $player);
  if ($card != "-") return $card->PlayableFromGraveyard($index);
  return match ($cardID) {
    "graven_call" => true,
    default => false,
  };
}

function ActivatedFromGraveyard($cardID) {
  return match ($cardID) {
    "graven_call" => true,
    "graven_gaslight" => true,
    default => false,
  };
}

function RequiresDieRoll($cardID, $from, $player): bool
{
  global $turn;
  if (GetDieRoll($player) > 0) return false;
  if ($turn[0] == "B") return false;
  $type = CardType($cardID);
  if ($type == "AA" && (GetResolvedAbilityType($cardID, $from, $player) == "" || GetResolvedAbilityType($cardID, $from, $player) == "AA") && PowerValue($cardID, $player, "LAYER") >= 6 && (SearchCharacterActive($player, "kayo_berserker_runt") || SearchCurrentTurnEffects("kayo_berserker_runt-SHIYANA", $player))) return true;
  return match ($cardID) {
    "crazy_brew_blue" => $from == "PLAY",
    "scabskin_leathers", "barkbone_strapping", "bone_head_barrier_yellow", "argh_smash_yellow", "rolling_thunder_red", "bad_beats_red", "bad_beats_yellow", "bad_beats_blue", "knucklehead" => true,
    "reckless_charge_blue", "reckless_arithmetic_blue" => true,
    default => false
  };
}

function SpellVoidAmount($cardID, $player): int
{
  if ($cardID == "runechant" && SearchCurrentTurnEffects("amethyst_tiara", $player)) return 1;
  $card = GetClass($cardID, $player);
  if ($card != "-") {
    return $card->SpellVoidAmount();
  }
  return match ($cardID) {
    "arcanite_fortress" => SearchCount(SearchMultiZone($player, "MYCHAR:type=E;nameIncludes=Arcanite")),
    default => GeneratedSpellVoidAmount($cardID),
  };
}

function HasSpecialization($cardID): bool
{
  return GeneratedHasSpecialization($cardID);
}

function HasLegendary($cardID): bool
{
  return GeneratedHasLegendary($cardID);
}

function Is1H($cardID): bool|int
{
  switch ($cardID) {
    case "claw_of_vynserakai": 
    case "gavel_of_natural_order":
      return true;
    default:
      break;
  }
  if (SubtypeContains($cardID, "Off-Hand")) return true;
  return GeneratedIs1H($cardID);
}

function AbilityPlayableFromCombatChain($cardID, $index="-"): bool
{
  global $currentPlayer, $mainPlayer;
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->AbilityPlayableFromCombatChain($index);
  $isAttacking = $currentPlayer == $mainPlayer;
  $auras = GetAuras($currentPlayer);
  return match ($cardID) {
    "exude_confidence_red" => $isAttacking,
    "shock_striker_red", "shock_striker_yellow", "shock_striker_blue", "firebreathing_red" => $isAttacking,
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => $isAttacking,
    "cloud_skiff_red", "cloud_skiff_yellow", "cloud_skiff_blue" => $isAttacking,
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => $isAttacking,
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red" => $isAttacking,
    "cogwerx_zeppelin_red", "cogwerx_zeppelin_yellow", "cogwerx_zeppelin_blue" => $isAttacking,
    "rally_the_coast_guard_red", "rally_the_coast_guard_yellow", "rally_the_coast_guard_blue" => !$isAttacking && $currentPlayer != $mainPlayer,
    "rally_the_rearguard_red", "rally_the_rearguard_yellow", "rally_the_rearguard_blue" => !$isAttacking && $currentPlayer != $mainPlayer,
    default => false
  };
}

function AbilityPlayableFromGraveyard($cardID, $index) {
  global $currentPlayer;
  $discard = GetDiscard($currentPlayer);
  if (!isset($discard[$index + 2]) || isFaceDownMod($discard[$index + 2])) return false;
  $card = GetClass($cardID, $currentPlayer);
  if ($card != "-") return $card->AbilityPlayableFromGraveyard($index);
}

function CardCaresAboutPitch($cardID): bool
{
  $cardID = ShiyanaCharacter($cardID);
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->CardCaresAboutPitch();
  return match ($cardID) {
    "oldhim_grandfather_of_eternity", "oldhim", "winters_wail", "annals_of_sutcliffe", "cryptic_crossing_yellow", "diabolic_ultimatum_red", "deathly_duet_red", "deathly_duet_yellow", "deathly_duet_blue", "aether_slash_red", "aether_slash_yellow",
    "aether_slash_blue", "runic_reaping_red", "runic_reaping_yellow", "runic_reaping_blue", "gorgons_gaze_yellow", "manifestation_of_miragai_blue", "shifting_winds_of_the_mystic_beast_blue", "cosmic_awakening_blue", "unravel_aggression_blue", "dense_blue_mist_blue", "orihon_of_mystic_tenets_blue",
    "redwood_hammer", "bracken_rap_red", "bracken_rap_yellow", "strong_wood_red", "strong_wood_yellow", "seeds_of_strength_yellow", "seeds_of_strength_blue", "log_fall_red", "log_fall_yellow", "staff_of_verdant_shoots", "gauntlets_of_the_boreal_domain",
    "savage_claw", "leaven_sheath_red", "stormwind_sheath_red", "frosthaven_sheath_red" => true,
    default => false
  };
}

function IsIyslander($character)
{
  switch ($character) {
    case 'iyslander':
    case 'iyslander_stormbind':
      return true;
    default:
      return false;
  }
}

function WardAmount($cardID, $player, $index = -1)
{
  global $mainPlayer;
  $auras = &GetAuras($player);
  $card = GetClass($cardID, $player);
  if ($card != "-") return $card->WardAmount($index);
  switch ($cardID) {
    case "empyrean_rapture":
      if (SearchCurrentTurnEffects("empyrean_rapture-1", $player)) return 1;
      else return 0;
    case "meridian_pathway":
      return SearchCurrentTurnEffects("MERIDIANWARD", $player) ? 3 : 0;
    case "manifestation_of_miragai_blue":
      return $auras[$index + 3] ?? 0;
    case "three_visits_red":
      return SearchPitchForColor($player, 3) * 3;
    case "haze_shelter_red":
      if (SearchPitchForColor($player, 3) > 0) return 4;
      else return 1;
    case "haze_shelter_yellow":
      if (SearchPitchForColor($player, 3) > 0) return 3;
      else return 1;
    case "haze_shelter_blue":
      if (SearchPitchForColor($player, 3) > 0) return 2;
      else return 1;
    case "rage_specter_blue":
      return $player == $mainPlayer ? 6 : 1;
    default:
      return GeneratedWardAmount($cardID);
  }
}

function HasWard($cardID, $player)
{
  $card = GetClass($cardID, $player);
  if ($card != "-") return $card->HasWard();
  switch ($cardID) {
    case "empyrean_rapture":
      return SearchCurrentTurnEffects("empyrean_rapture-1", $player);
    case "meridian_pathway":
      return SearchCurrentTurnEffects("MERIDIANWARD", $player);
    case "heirloom_of_rabbit_hide":
    case "truths_retold":
    case "uphold_tradition":
      $char = &GetPlayerCharacter($player);
      $index = FindCharacterIndex($player, $cardID);
      return $char[$index + 12] != "DOWN";
    case "manifestation_of_miragai_blue": //missing from generated
    case "haze_shelter_red":
    case "haze_shelter_yellow":
    case "haze_shelter_blue":
      return true;
    default:
      return GeneratedHasWard($cardID);
  }
}

function ArcaneShelterAmount($cardID)
{
  return GeneratedArcaneShelterAmount($cardID);
}

function HasArcaneShelter($cardID): bool
{
  return GeneratedHasArcaneShelter($cardID);
}

function HasDominate($cardID)
{
  global $mainPlayer, $combatChainState, $CombatChainState;
  global $CS_NumAuras, $CCS_NumBoosted;
  switch ($cardID) {
    case "open_the_center_red":
    case "open_the_center_yellow":
    case "open_the_center_blue":
      return ComboActive() ? true : false;
    case "demolition_crew_red":
    case "demolition_crew_yellow":
    case "demolition_crew_blue":
    case "arknight_ascendancy_red":
    case "herald_of_erudition_yellow":
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
      return ComboActive() ? true : false;
    case "payload_red":
    case "payload_yellow":
    case "payload_blue":
      return $CombatChainState->NumBoosted() > 0;
    case "drowning_dire_red":
    case "drowning_dire_yellow":
    case "drowning_dire_blue":
      return GetClassState($mainPlayer, $CS_NumAuras) > 0;
    case "isolate_red":
    case "isolate_yellow":
    case "isolate_blue":
      return true;
    default:
      break;
  }
  return GeneratedHasDominate($cardID);
}

function HasAmbush($cardID, $player)
{
  $card = GetClass($cardID, $player);
  if ($card != "-") return $card->HasAmbush();
  return GeneratedHasAmbush($cardID);
}

function HasScrap($cardID)
{
  return GeneratedHasScrap($cardID);
}

function HasGalvanize($cardID)
{
  return GeneratedHasGalvanize($cardID);

}

function HasStealth($cardID)
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->HasStealth();
  return GeneratedHasStealth($cardID);
}

function PowerCantBeModified($cardID)
{
  switch ($cardID) {
    case "numbskull_red":
      return true;
    default:
      return false;
  }
}

function CostCantBeModified($cardID)
{
  switch ($cardID) {
    case "numbskull_red":
      return true;
    default:
      return false;
  }
}

function BlockCantBeModified($cardID)
{
  switch ($cardID) {
    case "numbskull_red":
      return true;
    default:
      return false;
  }
}

function Rarity($cardID)
{
  $set = CardSet($cardID);
  if ($set != "DUM") {
    return GeneratedRarity($cardID);
  }
}

function IsEquipment($cardID, $player = "")
{
  return TypeContains($cardID, "E", $player) || SubtypeContains($cardID, "Evo", $player);
}

function CardCareAboutChiPitch($cardID)
{
  $cardID = ShiyanaCharacter($cardID);
  switch ($cardID) {
    case "nuu_alluring_desire":
    case "nuu":
    case "mask_of_recurring_nightmares":
    case "enigma_ledger_of_ancestry":
    case "enigma":
    case "meridian_pathway":
    case "zen_tamer_of_purpose":
    case "zen":
    case "twelve_petal_kasaya":
    case "enigma_new_moon":
    case "kimono_of_layered_lessons":
      return true;
    default:
      return false;
  }
}

function IsModular($cardID)
{
  return GeneratedHasModular($cardID);
}

function HasCloaked($cardID, $player = "", $hero = "")
{
  $char = GetPlayerCharacter($player);
  if (TypeContains($cardID, "E", $player) && $hero == "enigma_new_moon") return "DOWN";
  if (GeneratedHasCloaked($cardID)) {
    return "DOWN";
  }
  return "UP";
}

function HasEphemeral($cardID)
{
  return GeneratedHasEphemeral($cardID);
}

//Deprecated, use IsLayerStep() instead
function HasAttackLayer()
{
  global $layers;
  if (count($layers) == 0) return false;//If there's no attack, and no layers, nothing to do
  $layerIndex = count($layers) - LayerPieces();//Only the earliest layer can be an attack
  $layerID = $layers[$layerIndex];
  $parameters = explode("|", $layers[$layerIndex+2]);
  $player = $layers[$layerIndex+1];
  // if (strlen($layerID) != 6) return false;//Game phase, not a card - sorta hacky
  if (GetResolvedAbilityType($layerID, $parameters[0], $player) == "AA") return true;
  //handle modal cards separately
  if (GetAbilityTypes($layerID, from:$parameters[0]) != "") {
    if (GetResolvedAbilityType($layerID, $parameters[0], $player) != "AA") return false;
  }
  if ($layerID == "MELD") return false;
  $layerType = CardType($layerID);
  if ($layerType == "AA") return true; //It's an attack
  if ($parameters[0] == "PLAY" && DelimStringContains(CardSubType($layerID), "Aura")) return true;
  return false;
}

function HasMeld($cardID){
  return GeneratedHasMeld($cardID); 
}

Function IsMeldInstantName($term){
  switch ($term) {
      case "Shock":
      case "Life":
      case "Rampant_Growth":
      case "Null":
      case "Vaporize":
      return true;
    default:
      return false;
  }  
}

Function IsMeldActionName($term){
  switch ($term) {
      case "Pulsing_Aether":
      case "Arcane_Seeds":
      case "Comet_Storm":
      case "Thistle_Bloom":
      case "Burn_Up":
      case "Regrowth":
      case "Consign_To_Cosmos":
      case "Everbloom":
      return true;
    default:
      return false;
  }  
}


Function IsMeldRightSideName($term){
  switch ($term) {
      case "Shock":
      case "Life":
      return true;
    default:
      return false;
  }  
}

Function IsMeldLeftSideName($term){
  switch ($term) {
      case "Pulsing_Aether":
      case "Arcane_Seeds":
      case "Comet_Storm":
      case "Thistle_Bloom":
      case "Burn_Up":
      case "Regrowth":
      case "Null":
      case "Rampant_Growth":
      case "Vaporize":
        return true;
      default:
        return false;
  }  
}

function canBeAddedToChainDuringDR($cardID){
  switch ($cardID) {
    case "leap_frog_vocal_sac":
    case "leap_frog_slime_skin":
    case "leap_frog_gloves":
    case "leap_frog_leggings":
      return true;
    default:
      return false;
  }
}

function ExtractCardID($cardID) {
  if ($cardID === null) return "";
  $cardID = explode(",", $cardID)[0];
  $cardID = explode("-", $cardID)[0];
  $cardID = explode("|", $cardID)[0];
  return $cardID;
}

function CountAllies($player)
{
  $ally = &GetAllies($player);
  $char = GetPlayerCharacter($player);
  $count = count($ally);
  $countChar = count($char);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $countChar; $i += $charPieces) {
    if (HasPerched($char[$i])) $count++;
  }
  return $count;
}

//Function used for cosmetic effect to show when something is active. e.g. Hold the Line
function HasEffectActive($cardID) {
  global $CS_NumCardsDrawn, $playerID;
  $otherPlayer = $playerID == 1 ? 2 : 1;
  switch ($cardID) {
    case "hold_the_line_blue": return GetClassState($otherPlayer, $CS_NumCardsDrawn) >= 2;
  default:
    return false;
  }
}

function BlindCard($cardID, $unblind=false, $excludeEquips=false) {
  if (!$unblind && $excludeEquips && TypeContains($cardID, "E")) return $cardID;
  $blindMarker = "BLIND";
  $c = strlen($blindMarker) + 1;
  if ($cardID === null) return "";
  if ($unblind) {
    if (str_contains($cardID, $blindMarker)) return substr($cardID, 0, -$c);
    else return $cardID;
  }
  else {
    if (str_contains($cardID, $blindMarker)) return $cardID;
    return "$cardID~$blindMarker";
  }
}

//For effects that grant an ability to an AA card that then grants the attack power
function IsGrantedBuff($cardID) {
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->IsGrantedBuff();
  return match($cardID) {
    "barraging_beatdown_red", "barraging_beatdown_yellow", "barraging_beatdown_blue" => true,
    default => false,
  };
}

// equipment that count as gold
function IsGold($cardID) {
  if (class_exists($cardID)) {
    $card = new $cardID(1);
    return $card->IsGold();
  }
  return match ($cardID) {
    "aurum_aegis" => true,
    default => false
  };
}

function GetClass($cardID, $player) {
  if ($cardID !== null && str_contains($cardID, "BLIND")) return "-";
  if ($cardID == "LAYER" || $cardID == "TRIGGER") return "-";
  $cardID = ExtractCardID($cardID);
  $className = match($cardID) {
    "10000_year_reunion" => "tenk_year_reunion", //class name can't start with digits
    default => $cardID
  };
  if (class_exists($className)) return new $className($player);
  else return "-";
}

function IsInstantMod($mod) {
  return match($mod) {
    "INST" => true,
    "blossoming_spellblade_red" => true,
    "sonic_boom_yellow" => true,
    default => false
  };
}