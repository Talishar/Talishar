<?php

function SEAAbilityType($cardID, $from="-"): string
{
  return match ($cardID) {
    "gravy_bones_shipwrecked_looter" => "I",
    "chum_friendly_first_mate_yellow" => "I",
    "compass_of_sunken_depths" => "I",

    // "puffin_hightail" => "A", disabling for now
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => $from == "PLAY" ? "I": "AA",

    // "marlynn_treasure_hunter" => "A", disabling for now
    default => ""
  };
}

function SEAAbilityCost($cardID): int
{
  return match ($cardID) {
    default => 0
  };
}

function SEAAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "marlynn_treasure_hunter" => true,
    default => false,
  };
}

function SEAEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => 1,
    "big_game_trophy_shot_yellow" => 4,
    default => 0,
  };
}

function SEACombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match ($cardID) {
    "board_the_ship_red" => true,
    "hoist_em_up_red" => true,
    "sky_skimmer_red", "sky_skimmer_yellow", "sky_skimmer_blue" => true,
    "big_game_trophy_shot_yellow" => SubtypeContains($attackID, "Arrow", $mainPlayer),
    default => false,
  };
}

function SEAPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    // Gravy cards
    case "gravy_bones_shipwrecked_looter":
      Draw($currentPlayer, effectSource:$cardID);
      PummelHit($currentPlayer);
      break;
    case "chum_friendly_first_mate_yellow":
      AddCurrentTurnEffect($cardID, $otherPlayer, uniqueID: $target);
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
    // Puffin cards
    case "puffin_hightail":
      PutItemIntoPlayForPlayer("golden_cog", $currentPlayer, isToken: true);
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

function GetUnwaved($player, $zone, $cond="-")
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
  $inds = GetUnwaved($player, $zone);
  AddDecisionQueue("SETDQCONTEXT", $player, "choose $obj to TEMPNAME or pass");
  AddDecisionQueue("PASSPARAMETER", $player, $inds, 1);
  if ($may) AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
  else AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
  AddDecisionQueue("MZTAP", $player, "<-", 1);
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
  elseif (str_contains($zoneName, "ALLY")) return $zone[$ind + 11] == 1;
  elseif (str_contains($zoneName, "ITEM")) return $zone[$ind + 10] == 1;
  return false;
}

function HasWateryGrave($cardID): bool
{
  return match($cardID) {
    "chum_friendly_first_mate_yellow" => true,
    "riggermortis_yellow" => true,
    default => false
  };
}