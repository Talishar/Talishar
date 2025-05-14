<?php

function SEAAbilityType($cardID, $from="-"): string
{
  return match ($cardID) {
    "peg_leg" => "A",
    "gold_baited_hook" => "A",

    "gravy_bones_shipwrecked_looter" => "I",
    "gravy_bones" => "I",
    "chum_friendly_first_mate_yellow" => "I",
    "chowder_hearty_cook_yellow" => "I",
    "wailer_humperdink_yellow" => $from == "PLAY" ? "AA": "A",
    "riggermortis_yellow" => $from == "PLAY" ? "AA" : "A",  
    "barnacle_yellow" => $from == "PLAY" ? "AA" : "A",
    "compass_of_sunken_depths" => "I",

    "puffin_hightail" => "A",
    "puffin" => "A",
    
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => $from == "PLAY" ? "I": "AA",
    "palantir_aeronought_red" => $from == "PLAY" ? "I": "AA",
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
    "wailer_humperdink_yellow" => 6,
    "riggermortis_yellow" => 1,
    "peg_leg" => 3,
    "hammerhead_harpoon_cannon" => 4,
    default => 0
  };
}

function SEAAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "peg_leg" => true,
    "gold_baited_hook" => true,
    "redspine_manta" => true,
    "marlynn_treasure_hunter" => true,
    "marlynn" => true,
    "hammerhead_harpoon_cannon" => true,
    default => false,
  };
}

function SEAEffectPowerModifier($cardID): int
{
  global $CombatChain, $mainPlayer;
  $attackID = $CombatChain->AttackCard()->ID();
  return match ($cardID) {
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => 1,
    "palantir_aeronought_red" => 1,
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
    "palantir_aeronought_red" => true,
    "big_game_trophy_shot_yellow" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "flying_high_red", "flying_high_yellow", "flying_high_blue" => true,
    "hammerhead_harpoon_cannon" => SubtypeContains($attackID, "Arrow", $mainPlayer),
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
    // Gravy cards
    case "gravy_bones_shipwrecked_looter":
    case "gravy_bones":
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
      break;
    case "chum_friendly_first_mate_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $target);
      break;
    case "chowder_hearty_cook_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($from == "PLAY" && $abilityType == "I") GainHealth(1, $currentPlayer);
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
    case "sky_skimmer_red":
    case "sky_skimmer_yellow":
    case "sky_skimmer_blue":
      if ($from == "PLAY") {
        AddDecisionQueue("BUTTONINPUTNOPASS", $currentPlayer, "Go Again,+1 Power");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "SKYSKIMMER", 1);
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
    // Marlynn cards
    case "redspine_manta":
      LoadArrow($currentPlayer);
      return "";
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
    case "goldkiss_rum":
      if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    default:
      break;
  }
  return "";
}

function SEAHitEffect($cardID): void
{
  global $CS_NumCannonsActivated, $mainPlayer, $defPlayer;
  switch ($cardID) {
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
      if(IsHeroAttackTarget()) {
        $arsenal = GetArsenal($defPlayer);
        $count = count($arsenal) / ArsenalPieces();
        DestroyArsenal($defPlayer, effectController:$mainPlayer);
        PutItemIntoPlayForPlayer("gold", $mainPlayer, number:$count, effectController:$mainPlayer, isToken:true);
      }     
      break;
    default:
      break;
  }
}

function GetUntapped($player, $zone, $cond="-")
{
  switch ($zone) {
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
    "barnacle_yellow" => true,
    "diamond_amulet_blue" => true,
    "sawbones_dock_hand_yellow" => true,
    "chowder_hearty_cook_yellow" => true,
    "wailer_humperdink_yellow" => true,
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