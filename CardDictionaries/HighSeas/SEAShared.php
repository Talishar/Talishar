<?php

function SEAAbilityType($cardID, $from="-"): string
{
  return match ($cardID) {
    "peg_leg" => "A",

    "gravy_bones_shipwrecked_looter" => "I",
    "chum_friendly_first_mate_yellow" => "I",
    "compass_of_sunken_depths" => "I",

    "puffin_hightail" => "A",
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => $from == "PLAY" ? "I": "AA",
    "polly_cranka", "polly_cranka_ally" => "A",

    "redspine_manta" => "A",
    "marlynn_treasure_hunter" => "A",
    "diamond_amult" => "I",
    default => ""
  };
}

function SEAAbilityCost($cardID): int
{
  return match ($cardID) {
    "peg_leg" => 3,
    default => 0
  };
}

function SEAAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "peg_leg" => true,
    "redspine_manta" => true,
    "marlynn_treasure_hunter" => true,
    default => false,
  };
}

function SEAEffectAttackModifier($cardID): int
{
  global $CombatChain;
  $attackID = $CombatChain->AttackCard()->ID();
  return match ($cardID) {
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => 1,
    "big_game_trophy_shot_yellow" => 4,
    "flying_high_red" => PitchValue($attackID) == 1 ? 1 : 0,
    "flying_high_yellow" => PitchValue($attackID) == 2 ? 1 : 0,
    "flying_high_blue"  => PitchValue($attackID) == 3 ? 1 : 0,
    default => 0,
  };
}

function SEACombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match ($cardID) {
    "peg_leg" => true,
    "board_the_ship_red" => true,
    "hoist_em_up_red" => true,
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => true,
    "big_game_trophy_shot_yellow" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    "flying_high_red", "flying_high_yellow", "flying_high_blue" => true,
    default => false,
  };
}

function SEAPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    // Generic cards
    case "peg_leg":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "flying_high_red": case "flying_high_yellow": case "flying_high_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    // Gravy cards
    case "gravy_bones_shipwrecked_looter":
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
      break;
    case "chum_friendly_first_mate_yellow":
      $abilityType = GetResolvedAbilityType($cardID, $from);
      if ($abilityType == "I") AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $target);
      break;
    case "compass_of_sunken_depths":
      LookAtTopCard($currentPlayer, $cardID, setPlayer: $currentPlayer);
      break;
    case "paddle_faster_red":
      TapPermanent($currentPlayer, "MYALLY");
      AddDecisionQueue("OP", $currentPlayer, "GIVEATTACKGOAGAIN", 1);
      break;
    case "board_the_ship_red":
      TapPermanent($currentPlayer, "MYALLY");
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
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
      else AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CHARTTHEHIGHSEAS", 1);
      break;
    case "diamond_amulet_blue":
      if($from == "PLAY") GainActionPoints(1, $currentPlayer);
      break;
    // Puffin cards
    case "puffin_hightail":
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
        GiveAttackGoAgain();
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      break;
    // Marlynn cards
    case "redspine_manta":
      LoadArrow($currentPlayer);
      return "";
    case "marlynn_treasure_hunter":
      AddPlayerHand("goldfin_harpoon_yellow", $currentPlayer, $cardID);
      break;
    case "big_game_trophy_shot_yellow":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
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
    $ind = "$zone-$i";
    if ($cond != "-" && !in_array($ind, $allowedInds)) continue;
    if (!CheckTapped($ind, $player)) array_push($unwavedInds, $ind);
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
  $zoneName = explode("-", $MZindex)[0];
  $zone = &GetMZZone($player, $zoneName);
  $ind = intval(explode("-", $MZindex)[1]);
  if (str_contains($zoneName, "CHAR")) $zone[$ind + 14] = $tapState;
  elseif (str_contains($zoneName, "ALLY")) $zone[$ind + 11] = $tapState;
  elseif (str_contains($zoneName, "ITEM")) $zone[$ind + 10] = $tapState;
}

function CheckTapped($MZindex, $player): bool
{
  $zoneName = explode("-", $MZindex)[0];
  $zone = &GetMZZone($player, $zoneName);
  $ind = intval(explode("-", $MZindex)[1]);
  if (str_contains($zoneName, "CHAR")) return $zone[$ind + 14] == 1;
  elseif (str_contains($zoneName, "ALLY")) return $zone[$ind + 1] == 2 && $zone[$ind + 11] == 1;
  elseif (str_contains($zoneName, "ITEM")) return $zone[$ind + 10] == 1;
  return false;
}

function HasWateryGrave($cardID): bool
{
  return match($cardID) {
    "chum_friendly_first_mate_yellow" => true,
    "riggermortis_yellow" => true,
    "diamond_amulet_blue" => true,
    "sawbones_dock_hand_yellow" => true,
    default => false
  };
}

function HasPerched($cardID): bool
{
  return match($cardID) {
    "polly_cranka" => true,
    default => false
  };
}