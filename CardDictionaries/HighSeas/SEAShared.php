<?php

function SEAAbilityType($cardID, $from="-"): string
{
  return match ($cardID) {
    "peg_leg" => "A",
    "patch_the_hole" => "I",
    "gold_baited_hook" => "A",
    "sawbones_dock_hand_yellow" => "I",

    "gravy_bones_shipwrecked_looter" => "I",
    "gravy_bones" => "I",
    "chum_friendly_first_mate_yellow" => "I",
    "moray_le_fay_yellow" => "I",
    "shelly_hardened_traveler_yellow" => "I",
    "kelpie_tangled_mess_yellow" => "A",
    "chowder_hearty_cook_yellow" => "I",
    "scooba_salty_sea_dog_yellow" => $from == "PLAY" ? "AA": "A",
    "wailer_humperdinck_yellow" => $from == "PLAY" ? "AA": "A",
    "riggermortis_yellow" => $from == "PLAY" ? "AA" : "A",  
    "swabbie_yellow" => $from == "PLAY" ? "AA" : "A",
    "limpit_hop_a_long_yellow" => $from == "PLAY" ? "AA" : "A",
    "barnacle_yellow" => $from == "PLAY" ? "AA" : "A",
    "compass_of_sunken_depths" => "I",
    "dead_threads" => "I",
    "sealace_sarong" => "I",

    "puffin_hightail" => "A",
    "puffin" => "A",
    "spitfire" => "AA",
    "cogwerx_blunderbuss" => "I",
    
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => $from == "PLAY" ? "I": "AA",
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => $from == "PLAY" ? "I": "AA",
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red" => $from == "PLAY" ? "I": "AA",

    "polly_cranka", "polly_cranka_ally" => "A",

    "redspine_manta" => "A",
    "marlynn_treasure_hunter" => "A",
    "marlynn" => "A",
    "hammerhead_harpoon_cannon" => "A",

    "diamond_amulet_blue" => "I",
    "goldkiss_rum" => "I",
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
    "scooba_salty_sea_dog_yellow" => 3,
    "hammerhead_harpoon_cannon" => 4,
    "sawbones_dock_hand_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "AA" ? 1 : 0,

    "moray_le_fay_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "I" ? 1 : 0,
    "shelly_hardened_traveler_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "I" ? 0 : 3,
    "kelpie_tangled_mess_yellow" => GetResolvedAbilityType($cardID, "PLAY") == "A" ? 1 : 0,
    default => 0
  };
}

function SEAAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "limpit_hop_a_long_yellow" => true,
    "peg_leg" => true,
    "gold_baited_hook" => true,
    "redspine_manta" => true,
    "marlynn_treasure_hunter" => true,
    "marlynn" => true,
    "hammerhead_harpoon_cannon" => true,
    "kelpie_tangled_mess_yellow" => GetResolvedAbilityType($cardID) == "A",
    default => false,
  };
}

function SEAEffectPowerModifier($cardID): int
{
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  return match ($cardID) {
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => 1,
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => 1,
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red" => 1,
    "draw_back_the_hammer_red", "perk_up_red", "tighten_the_screws_red" => 4,
    "spitfire" => 1,
    "big_game_trophy_shot_yellow" => 4,
    "flying_high_red" => ColorContains($attackID, 1, $mainPlayer) ? 1 : 0,
    "flying_high_yellow" => ColorContains($attackID, 2, $mainPlayer) ? 1 : 0,
    "flying_high_blue"  => ColorContains($attackID, 3, $mainPlayer) ? 1 : 0,
    "hammerhead_harpoon_cannon" => SubtypeContains($attackID, "Arrow", $mainPlayer) ? 4 : 0,
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
    "board_the_ship_red" => true,
    "hoist_em_up_red" => true,
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => true,
    "cloud_city_steamboat_red", "cloud_city_steamboat_yellow", "cloud_city_steamboat_blue" => true,
    "palantir_aeronought_red", "jolly_bludger_yellow", "cogwerx_dovetail_red" => true,
    "draw_back_the_hammer_red", "perk_up_red", "tighten_the_screws_red" => ClassContains($attackID, "MECHANOLOGIST", $mainPlayer),
    "jolly_bludger_yellow-OP" => true,
    "cogwerx_blunderbuss" => $attackID == "cogwerx_blunderbuss",
    "spitfire" => true,
    "big_game_trophy_shot_yellow" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "flying_high_red", "flying_high_yellow", "flying_high_blue" => true,
    "hammerhead_harpoon_cannon" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "sealace_sarong" => true,
    "goldkiss_rum" => true,
    default => false,
  };
}

function SEAPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $combatChainState, $CCS_RequiredEquipmentBlock, $combatChain, $defPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    // Generic cards
    case "peg_leg":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "patch_the_hole":
      MZMoveCard($currentPlayer, "MYARS", "MYHAND", silent: true);
      break;
    case "gold_baited_hook":
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
      AddDecisionQueue("ALLCARDPITCHORPASS", $currentPlayer, "2", 1);
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
    case "chum_friendly_first_mate_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $target);
      break;
    case "chowder_hearty_cook_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") GainHealth(1, $currentPlayer);
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
        AddDecisionQueue("MZBOTTOM", $currentPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "gold", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "0", 1);
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
          Tap("$zone-$allyInd", $targetPlayer);
        }
        else {
          $targetPlayer = $zone == "MYCHAR" ? $currentPlayer : $otherPlayer;
          $charInd = SearchCharacterForUniqueID($targetUid, $targetPlayer);
          $zone = $zone == "THEIRCHARUID" ? "THEIRCHAR": $zone;
          Tap("$zone-$charInd", $targetPlayer);
        }
      }
      break;
    case "compass_of_sunken_depths":
      LookAtTopCard($currentPlayer, $cardID, setPlayer: $currentPlayer);
      break;
    case "paddle_faster_red":
      $inds = GetUntapped($currentPlayer, "MYALLY");
      if (strlen($inds) > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "choose an ally to tap or pass");
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $inds, 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
        AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      }
      break;
    case "board_the_ship_red":
      $inds = GetUntapped($currentPlayer, "MYALLY");
      if (strlen($inds) > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "choose an ally to tap or pass");
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $inds, 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
      }
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
        PlayAlly("polly_cranka_ally", $currentPlayer, tapped:true);
        RemoveBanish($currentPlayer, $index);
      }
      break;
    case "cogwerx_blunderbuss":
      if (GetResolvedAbilityType($cardID) == "I") {
        AddCurrentTurnEffectNextAttack($cardID, $currentPlayer);
      }
      break;
    case "spitfire":
      $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Tap a cog to activate ".CardLink($cardID, $cardID));
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $inds, 1);
      AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "cog_in_the_machine_red":
      PutItemIntoPlayForPlayer("golden_cog", $currentPlayer, number:2, isToken: true);
      $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "You may tap a cog you control or pass");
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
    case "sky_skimmer_red":
    case "sky_skimmer_yellow":
    case "sky_skimmer_blue":
      if ($from == "PLAY") {
        AddDecisionQueue("BUTTONINPUTNOPASS", $currentPlayer, "+1 Power,Go Again");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "COGCONTROL-".$cardID, 1);
      }
      break;
    case "palantir_aeronought_red":
      if($from != "PLAY" && !IsAllyAttackTarget()) $combatChainState[$CCS_RequiredEquipmentBlock] = 1;
      else {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $numResolved = CountCurrentTurnEffects($cardID, $currentPlayer);
        //technically inaccurate, but should be functionally mostly the same
        if ($numResolved == 3) {
          $indices = [];
          for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
            if ($combatChain[$i + 1] == $defPlayer) array_push($indices, "COMBATCHAINLINK-$i");
          }
          $indices = implode(",", $indices);
          // AddDecisionQueue("PASSPARAMETER", $currentPlayer, $indices);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, $indices);
          AddDecisionQueue("SPECIFICCARD", $currentPlayer, "AERONOUGHT", 1);
        }
      }
      return "";
    case "jolly_bludger_yellow":
      if ($from != "PLAY") {
        $inds = GetUntapped($currentPlayer, "MYITEMS", "subtype=Cog");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Tap a cog to gain overpower");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, $inds, 1);
        AddDecisionQueue("MZTAP", $currentPlayer, "<-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "jolly_bludger_yellow-OP", 1);
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
      if ($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
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
    case "hammerhead_harpoon_cannon":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "big_game_trophy_shot_yellow":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
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
    case "goldkiss_rum":
      if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "burn_bare":
      if (GetResolvedAbilityType($cardID, "HAND") == "I" && $from == "HAND") {
        AddLayer("LAYER", $currentPlayer, "PHANTASM", $combatChain[0], $cardID);
      } else {
        DealArcane(ArcaneDamage($cardID), 2, "PLAYCARD", $cardID, resolvedTarget: $target);
      }
      return "";
    default:
      break;
  }
  return "";
}

function SEAHitEffect($cardID): void
{
  global $CS_NumCannonsActivated, $mainPlayer, $defPlayer;
  switch ($cardID) {
    //puffin cards
    case "cloud_city_steamboat_red":
    case "cloud_city_steamboat_yellow":
    case "cloud_city_steamboat_blue":
      $inds = GetUntapped($mainPlayer, "MYITEMS", "subtype=Cog");
      if($inds != "") {
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $inds);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Tap a cog to put a steam counter on a cog (or pass)", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZTAP", $mainPlayer, "<-", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a cog to add a steam counter to", 1);
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYITEMS:subtype=Cog", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer ,"<-", 1);
        AddDecisionQueue("MZADDCOUNTER", $mainPlayer, "-", 1);
      }
      break;
    //marlynn cards
    case "king_kraken_harpoon_red":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, non-attack action card will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "KINGKRAKENHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, non-attack action card will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "KINGKRAKENHARPOON", 1);
      }
      break;
    case "king_shark_harpoon_red":
      if (GetClassState($mainPlayer, $CS_NumCannonsActivated) == 0){
        AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, attack action card will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $defPlayer, "KINGSHARKHARPOON", 1);
      }
      else {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card from their hand, attack action card will be discarded");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "KINGSHARKHARPOON", 1);
      }
      break;
    case "conqueror_of_the_high_seas_red":
      $arsenal = GetArsenal($defPlayer);
      $count = count($arsenal) / ArsenalPieces();
      DestroyArsenal($defPlayer, effectController:$mainPlayer);
      PutItemIntoPlayForPlayer("gold", $mainPlayer, number:$count, effectController:$mainPlayer, isToken:true);
      break;
    case "cogwerx_dovetail_red":
      Writelog(CardLink($cardID, $cardID) . " untap all the cogs Player " . $mainPlayer . " control.");
      AddDecisionQueue("UNTAPALL", $mainPlayer, "MYITEMS:subtype=Cog", 1);
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
  if ($cond != "-") {
    $allowedInds = explode(",", SearchMultizone($player, "$zone:$cond"));
  }
  else $allowedInds = [];
  for ($i = 0; $i < count($arr); $i += $count) {
    $index = "$zone-$i";
    if ($cond != "-" && !in_array($index, $allowedInds)) continue;
    if (!CheckTapped($index, $player)) array_push($unwavedInds, $index);
  }
  return implode(",", $unwavedInds);
}

function GetTapped($player, $zone, $cond="-")
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
  if ($cond != "-") {
    $allowedInds = explode(",", SearchMultizone($player, "$zone:$cond"));
  }
  else $allowedInds = [];
  for ($i = 0; $i < count($arr); $i += $count) {
    $index = "$zone-$i";
    if ($cond != "-" && !in_array($index, $allowedInds)) continue;
    if (CheckTapped($index, $player)) array_push($unwavedInds, $index);
  }
  return implode(",", $unwavedInds);
}

function TapPermanent($player, $zone, $may=true) {
  $obj = match($zone) {
    "MYALLY" => "an ally",
    "MYITEMS" => "an item",
    default => "something"
  };
  $inds = GetUntapped($player, $zone);
  if (strlen($inds) > 0) {
    AddDecisionQueue("SETDQCONTEXT", $player, "choose $obj to tap or pass");
    AddDecisionQueue("PASSPARAMETER", $player, $inds, 1);
    if ($may) AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
    AddDecisionQueue("MZTAP", $player, "<-", 1);
  }
}

function Tap($MZindex, $player, $tapState=1)
{
  global $CS_NumGoldCreated;
  $zoneName = explode("-", $MZindex)[0];
  $zone = &GetMZZone($player, $zoneName);
  $index = intval(explode("-", $MZindex)[1]);
  //Untap
  if($tapState == 0 && !isUntappedPrevented($zone[$index], $zoneName, $player)) {
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

function isUntappedPrevented($cardID, $zoneName, $player): bool
{
  $untapPrevented = false;
  if(SearchCurrentTurnEffects("goldkiss_rum", $player) && str_contains($zoneName, "CHAR") && !TalentContains($cardID, "PIRATE", $player)) {
    $untapPrevented = true;
  }
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
    "diamond_amulet_blue" => true,
    "sawbones_dock_hand_yellow" => true,
    "chowder_hearty_cook_yellow" => true,
    "wailer_humperdinck_yellow" => true,
    "moray_le_fay_yellow" => true,
    "shelly_hardened_traveler_yellow" => true,
    "kelpie_tangled_mess_yellow" => true,
    "scooba_salty_sea_dog_yellow" => true,
    default => false
  };
}

function HasHighTide($cardID): bool
{
  return match($cardID) {
    "conqueror_of_the_high_seas_red" => true,
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