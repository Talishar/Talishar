<?php
function GetCardBehavior($cardID, $heroID)
{
  $behavior = GetCardBehaviorForHero($heroID);

  if(isset($behavior[$cardID])) {
    return $behavior[$cardID];
  }

  if (substr($cardID, -2) === "_r") {
    $base = substr($cardID, 0, -2);
    if (isset($behavior[$base])) {
      return $behavior[$base];
    }
  }

  if ($heroID == "ira_crimson_haze") {
    return ComputeIraCardBehavior($cardID);
  }

  return [0, 0, 0, 0, 0, 0, 0, 0];
}

function GetCardBehaviorForHero($heroID)
{
  switch($heroID) {
    case "ira_crimson_haze":
      return GetIraBehaviors();
    case "fai_rising_rebellion":
      return GetFaiBehaviors();
    default:
      return [];
  }
}

function GetIraBehaviors()
{
  return [
    "ira_crimson_haze" => [0, 0, 0, 0, 0, 0, 0, 0],

    "harmonized_kodachi"   => ComputeKodachiPriority(2),
    "harmonized_kodachi_r" => ComputeKodachiPriority(2),
  ];
}

function ComputeIraCardBehavior($cardID)
{
  $type    = CardType($cardID);
  $power   = PowerValue($cardID);
  $defense = BlockValue($cardID);
  $pitch   = PitchValue($cardID);

  // Equipment blocks are "free" — they don't displace a hand card and the
  // engine handles per-piece block-counter exhaustion via CardIsBlockable's
  // Character-zone gate ($character[i+6]==1 means already blocked this turn).
  // Set high so equipment absorbs hits before hand cards, preserving the
  // hand for offense.
  if ($type == "E") {
    return [0.85, 0, 0, 0, 0, 0, 0, 0];
  }

  if ($type == "C" || $type == "W") {
    return [0, 0, 0, 0, 0, 0, 0, 0];
  }

  if ($type == "DR" || $type == "B") {
    return [
      0.9,
      0,
      0,
      0.9,
      0.9,
      ComputeIraPitchPriority($cardID),
      ComputeIraArsenalPriority($cardID),
      0,
    ];
  }

  if ($type == "AR") {
    $blockPriority = ($defense >= 2) ? 0.85 : 0;
    return [
      $blockPriority,
      0,
      0,
      0.6,
      0.6,
      ComputeIraPitchPriority($cardID),
      ComputeIraArsenalPriority($cardID),
      0,
    ];
  }

  if ($type == "AA") {
    if ($pitch == 3) {
      $blockPriority = 0.85;
    } elseif ($pitch == 2) {
      $blockPriority = ($defense >= 2) ? 0.6 : 0.4;
    } elseif ($defense >= 2) {
      $blockPriority = 0.5;
    } else {
      $blockPriority = 0;
    }

    return [
      $blockPriority,
      ComputeIraActionPriority($cardID),
      ComputeIraActionPriority($cardID),
      0,
      0,
      ComputeIraPitchPriority($cardID),
      ComputeIraArsenalPriority($cardID),
      0,
    ];
  }

  if ($type == "A") {
    return [
      $defense >= 2 ? 0.5 : 0,
      0.2,
      0.2,
      0,
      0,
      ComputeIraPitchPriority($cardID),
      ComputeIraArsenalPriority($cardID),
      0,
    ];
  }

  return [0, 0, 0, 0, 0, 0, 0, 0];
}

function GetFaiBehaviors()
{
  return [
    "fai_rising_rebellion" => ComputeFaiHeroPriority(2),
    
    "harmonized_kodachi" => ComputeKodachiPriority(2),
    
    "art_of_war_yellow" => ComputeArtOfWarPriority(2),
    
    "ravenous_rabble_red"     => [0.1, 0.8, 0.8, 0, 0, 1.1, 0.5, 0],
    "bittering_thorns_red"    => [0.1, 0.85, 0.85, 0, 0, 1.5, 0.5, 0],
    "mounting_anger_red"      => [0.1, 0.85, 0.85, 0, 0, 1.5, 0.5, 0],
    "brand_with_cinderclaw_red"    => [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0],
    "brand_with_cinderclaw_yellow" => [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0],
    "brand_with_cinderclaw_blue"   => [0.1, 0.99, 0.99, 0, 0, 1.1, 0.5, 0],
    
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
    
    "salt_the_wound_yellow"   => [0.5, 0.1, 0.1, 0, 0, 2.5, 0.2, 0],
    "tenacity_yellow"         => [0.5, 0.1, 0.1, 0, 0, 2.5, 0.2, 0],
    
    "soulbead_strike_blue"    => [0.5, 0.3, 0.3, 0, 0, 3.5, 0.2, 0],
    "warmongers_diplomacy_blue" => [0.8, 0.1, 0.1, 0, 0, 3.5, 0.1, 0],
    "stab_wound_blue"         => [0.8, 0.4, 0.4, 0, 0, 3.5, 0.1, 0],
    "lava_vein_loyalty_blue"  => [0.8, 0.5, 0.5, 0, 0, 3.1, 0.1, 0],
    
    "snapdragon_scalers" => ComputeSnapdragonPriority(2),
  ];
}

function ComputeKodachiPriority($playerID)
{
  $resources = &GetResources($playerID);
  $hand      = &GetHand($playerID);

  $highTierPitch = $resources[0];
  $anyPitch      = $resources[0];
  $totalCostlyAttacks = 0;
  foreach ($hand as $cardID) {
    $pv   = PitchValue($cardID);
    $cost = CardCost($cardID);
    $pwr  = PowerValue($cardID);
    if ($pv >= 2) $highTierPitch += $pv;
    if ($pv >= 1) $anyPitch      += $pv;
    if ($cost > 0 && $pv < 3 && $pwr > 0) $totalCostlyAttacks += $cost;
  }

  if ($highTierPitch >= $totalCostlyAttacks + 1) {
    return [0, 0.95, 0, 0, 0, 0, 0, 0.95];
  }

  if ($totalCostlyAttacks == 0 && $anyPitch >= 1) {
    return [0, 0.95, 0, 0, 0, 0, 0, 0.95];
  }

  if ($highTierPitch < $totalCostlyAttacks && $anyPitch >= 1) {
    return [0, 0.4, 0, 0, 0, 0, 0, 0.4];
  }

  return [0, 0.1, 0, 0, 0, 0, 0, 0.1];
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
  $hasGoAgain = DoesAttackHaveGoAgain();
  $resources = &GetResources($playerID);
  $hand = &GetHand($playerID);
  $shouldActivate = !$hasGoAgain && (count($hand) > 0 || $resources[0] > 0);
  
  return [0, 0, 0, 0, 0, 0, 0, $shouldActivate ? 0.85 : 0];
}

function ComputeIraPitchPriority($cardID)
{
  $pitch  = PitchValue($cardID);
  $attack = PowerValue($cardID);

  switch ($pitch) {
    case 3: $base = 2.5; break;
    case 2: $base = 1.8; break;
    case 1: $base = 1.1; break;
    default: return 0;
  }

  return max(0.05, $base - ($attack * 0.01));
}

function ComputeIraArsenalPriority($cardID)
{
  $attack = PowerValue($cardID);
  if ($attack == 0) return 0;
  return min(0.9, 0.1 + ($attack * 0.08));
}

function ComputeIraActionPriority($cardID)
{
  $attack = PowerValue($cardID);

  if ($attack == 0) return 0;
  if (PitchValue($cardID) == 3) {
    return 0.1;
  }

  $hasGoAgain = HasGoAgain($cardID) || IraConditionalGoAgain($cardID);

  if ($hasGoAgain) {
    return max(0.6, 0.9 - ($attack * 0.01));
  }

  return max(0.15, 0.55 - ($attack * 0.01));
}

function IraConditionalGoAgain($cardID)
{
  global $CS_HitsWithWeapon, $currentPlayer;

  if ($cardID == "cut_through_red") {
    if (isset($CS_HitsWithWeapon)) {
      return GetClassState($currentPlayer, $CS_HitsWithWeapon) >= 1;
    }
  }

  // Scar for a Scar grants itself go-again when its controller has less HP
  // than the opponent. The engine resolves this via PlayerHasLessHealth at
  // play time; mirror it here so the priority function orders the chain
  // correctly when the condition is met.
  if ($cardID == "scar_for_a_scar_red"
      || $cardID == "scar_for_a_scar_yellow"
      || $cardID == "scar_for_a_scar_blue") {
    if (function_exists("PlayerHasLessHealth")) {
      return PlayerHasLessHealth($currentPlayer);
    }
  }

  return false;
}