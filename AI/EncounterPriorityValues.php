<?php

//Priority Array Values:
//[0] = Block Priority
  //0 -> can't/won't block
  //0.1-0.9 -> will block with only if lethal
  //1.1-1.9 -> NOT IMPLEMENTED (planned to be cards that are blocked with to prevent on hits)
  //2.1-2.9 -> will willingly block with when efficient (higher prio blocked with first)
  //3.1-3.9 -> NOT IMPLEMENTED (if desired, I can add a type where these cards will be blocked with at the first opportunity)
  //10.1-10.9 -> the highest priority in this range will resolve to 0.1-0.9. additional cards in this range resolve to 2.1-2.9
  //11.1-10.9 -> exactly the same as above, just giving a second channel to work with
//[1] = Action Priority
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
  //10.1-10.9 -> the highest priority in this range will resolve to 0. additional cards in this range resolve to 0.1-0.9
//[2] = Arsenal Action Priority (Implemented for arrows and other cards that change priority based on zone)
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
//[3] = Reaction Priority
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
  //10.1-10.9 -> the highest priority in this range will resolve to 0. additional cards in this range resolve to 0.1-0.9
//[4] = Arsenal Reaction Priority
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
//[5] = Pitch Priority
  //0 -> can't/won't pitch
  //0.1-X.Y Will pitch if possible (higher prio pitched first) (There is no need to implement specific value channels here, I personally use 0.X for reds, 1.X for yellows, and 2.X for blues. Go wild if you want though)
//[6] = Arsenal Priority
  //0 -> can't/won't arsenal
  //0.1-0.9 -> will arsenal if possible (higher prio put in arsenal first)
//[7] = Permanent Priority
  //0 -> can't/won't activate
  //0.1->0.9 -> will activate if possible (higher prio activated first)

//TODO: Implement the following priority values: (These might not be the final indexes. Consider the above indexes final though)
//[8] = Banish Priority

function GetPriority($cardID, $heroID, $type)
{
  global $currentPlayer;
  switch($heroID)
  {
    case "ira_crimson_haze"://Ira
      $redBlockChance = SearchCount(SearchHand(2, pitch:1)) > 1 ? 0.8 : 0.5;
      $resources = &GetResources(2);
      switch($cardID)
      {
        //Kodachis, activate if possible
        case "harmonized_kodachi":
          $blueCount = SearchCount(SearchHand(2, pitch:3)) || $resources[0] > 1;
          $priority = [0, $blueCount > 0 ? 0.95 : 0.1, 0, 0, 0, 0, 0, $blueCount > 0 ? 0.95 : 0.1]; return $priority[$type];
        //Blue combo cards
        case "fluster_fist_blue": case "back_heel_kick_blue": case "crane_dance_blue": case "find_center_blue": case "rising_knee_thrust_blue": case "rushing_river_blue": case "whelming_gustwave_blue": case "winds_of_eternity_blue":
          $blockChance = IsFirstTurn() || SearchCount(SearchHand(2, pitch: 3)) > 1 ? .9 : .1;
          $priority = [$blockChance, 0.1, 0.1, 0, 0, 2.5, 0.1, 0];
          return $priority[$type];
        //Red attacks
        case "censor_red": $priority = [$redBlockChance - 0.2, 0.7, 0.7, 0, 0, 1.1, 0.8, 0]; return $priority[$type];
        case "fluster_fist_red": $priority = [$redBlockChance + 0.1, 0.4, 0.4, 0, 0, 1.2, 0.7, 0]; return $priority[$type];
        case "soulbead_strike_red": $priority = [$redBlockChance - 0.1, 0.8, 0.8, 0, 0, 1.2, 0.7, 0]; return $priority[$type];
        case "torrent_of_tempo_red": $priority = [$redBlockChance - 0.2, 0.9, 0.9, 0, 0, 1.2, 0.7, 0]; return $priority[$type];
        case "mauling_qi_red": case "pounding_gale_red": $priority = [$redBlockChance, 0.5, 0.5, 0, 0, 1.2, 0.7, 0]; return $priority[$type];
        //Defense Reactions
        case "flic_flak_red": case "fate_foreseen_red": case "sink_below_red": $priority = [0.9, 0.0, 0.0, 0.9, 0.9, 1.5, 0.9, 0]; return $priority[$type];
        case "that_all_you_got_yellow": case "flic_flak_yellow": $priority = [0.9, 0.0, 0.0, 0.9, 0.9, 2.5, 0.8, 0]; return $priority[$type];
        case "flic_flak_blue":
          $blueCount = SearchCount(SearchHand(2, pitch:3));
          $priority = [0.6, 0.0, 0.0, $blueCount > 1 ? 0.9 : 0.1, $blueCount > 1 ? 0.9 : 0, 2.9, 0.8, 0]; return $priority[$type];
        //Equipment
        case "tide_flippers": $priority = [0, 0, 0, 0, 0, 0, 0, 0]; return $priority[$type];
        case "flick_knives": case "fyendals_spring_tunic": case "mask_of_momentum":
          //TODO: Allowing blocking if threatened by lethal damage
          $priority = [0, 0, 0, 0, 0, 0, 0, 0];
          return $priority[$type];
        default: $priority = [0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1]; return $priority[$type];
      }
      case "fai_rising_rebellion"://Fai
        $resources = &GetResources(2);
        switch($cardID)
        {
          //Kodachis, activate if possible
          case "fai_rising_rebellion":
            $priorityValue = NumDraconicChainLinks() >= 3 || ($resources[0] > NumDraconicChainLinks() && NumDraconicChainLinks() > 0) ? 1.0 : 0.0;
            $priority = [0, $priorityValue, 0, 0, 0, 0, 0, $priorityValue]; return $priority[$type];
          case "harmonized_kodachi":
            $blueCount = SearchCount(SearchHand(2, minCost:0, maxCost:0, pitch:3)) > 0 || $resources[0] > 1 && SearchCount(SearchPitch(2, minCost:0, maxCost:0)) > 0;
            $priority = [0, $blueCount > 0 ? 0.95 : 0.0, 0, 0, 0, 0, 0, $blueCount > 0 ? 0.95 : 0.0]; return $priority[$type];
          //Art of War
          case "art_of_war_yellow":
            $playPriority = (!ArsenalEmpty($currentPlayer) && SearchCount(SearchHand(2, pitch:3)) > 0 ? 1.0 : 0.0);//Arsenal if not full hand or don't have blue
            $blueCount = SearchCount(SearchHand(2, pitch:3));
            $priority = [0, $playPriority, $playPriority, 0, 0, 2.5, 1.0, 0];
            return $priority[$type];
          //Ravenous Rabble - Play early
          case "ravenous_rabble_red": $priority = [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "bittering_thorns_red": $priority = [0.1, 0.85, 0.85, 0, 0, 1.5, 0.5, 0]; return $priority[$type];
          case "mounting_anger_red": $priority = [0.1, 0.85, 0.85, 0, 0, 1.5, 0.5, 0]; return $priority[$type];
          case "brand_with_cinderclaw_red": $priority = [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "brand_with_cinderclaw_yellow": $priority = [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "brand_with_cinderclaw_blue": $priority = [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "rising_resentment_red": $priority = [0.1, 0.9, 0.9, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "ronin_renegade_red": $priority = [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "blaze_headlong_red": $priority = [0.1, 0.7, 0.7, 0, 0, 1.1, 0.6, 0]; return $priority[$type];
          case "lava_burst_red": $priority = [0.1, 0.1, 0.1, 0, 0, 1.1, 0.7, 0]; return $priority[$type];
          case "phoenix_flame_red": $priority = [0, 0.7, 0.7, 0, 0, 0.1, 0.2, 0]; return $priority[$type];
          case "double_strike_red": $priority = [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "ancestral_empowerment_red": $priority = [0.1, 0.9, 0.9, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          case "snatch_red": $priority = [0.1, 0.1, 0.1, 0, 0, 1.1, 0.7, 0]; return $priority[$type];
          case "command_and_conquer_red": $priority = [0.1, 0.1, 0.1, 0, 0, 1.1, 0.7, 0]; return $priority[$type];
          case "spreading_flames_red": $priority = [0.1, 1.0, 1.0, 0, 0, 1.1, 0.5, 0]; return $priority[$type];
          //Salt the Wound - Play last, or pitch
          case "salt_the_wound_yellow": $priority = [0.5, 0.1, 0.1, 0, 0, 2.5, 0.2, 0]; return $priority[$type];
          case "tenacity_yellow" : $priority = [0.5, 0.1, 0.1, 0, 0, 2.5, 0.2, 0]; return $priority[$type];
          //Blues
          case "soulbead_strike_blue": $priority = [0.5, 0.3, 0.3, 0, 0, 3.5, 0.2, 0]; return $priority[$type];
          case "warmongers_diplomacy_blue": $priority = [0.8, 0.1, 0.1, 0, 0, 3.5, 0.1, 0]; return $priority[$type];
          case "stab_wound_blue": $priority = [0.8, 0.4, 0.4, 0, 0, 3.5, 0.1, 0]; return $priority[$type];
          case "lava_vein_loyalty_blue": $priority = [0.8, 0.5, 0.5, 0, 0, 3.1, 0.1, 0]; return $priority[$type];
          default: $priority = [0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1]; return $priority[$type];
        }
    default: $priority = [0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1]; return $priority[$type];
  }
}

function EncounterBlocksFirstTurn($character) //This is a way to allow encounters to block or not block on the first turn. All encounters block out unless specifically asked not to.
{
  switch($character)
  {
    default: return true;
  }
}