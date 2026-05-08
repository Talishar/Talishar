<?php

function EvaluateCardValue($cardID, $playerID, $context = "Action")
{
  $baseValue = 0.5;
  
  // Get game state references
  $resources = &GetResources($playerID);
  $hand = &GetHand($playerID);
  $character = &GetPlayerCharacter($playerID);
  $health = &GetHealth($playerID);
  $enemyHealth = &GetHealth($playerID == 1 ? 2 : 1);
  
  // Evaluate based on context
  switch($context) {
    case "Action":
      $baseValue = EvaluateActionValue($cardID, $playerID, $resources, $hand, $character, $health, $enemyHealth);
      break;
    case "Block":
      $baseValue = EvaluateBlockValue($cardID, $playerID, $resources, $character, $health);
      break;
    case "Reaction":
      $baseValue = EvaluateReactionValue($cardID, $playerID, $resources, $hand, $health);
      break;
    case "Pitch":
      $baseValue = EvaluatePitchValue($cardID);
      break;
  }
  
  return $baseValue;
}

function EvaluateActionValue($cardID, $playerID, &$resources, &$hand, &$character, $playerHealth, $enemyHealth)
{
  $value = 0.5;
  
  // Cards that deal damage are high priority if opponent is low
  if(CardType($cardID) == "AA" || CardType($cardID) == "I") {
    $damage = PowerValue($cardID);
    if($enemyHealth <= $damage) {
      return 0.95;
    }
    $value += ($damage / max($enemyHealth, 1)) * 0.3;
  }
  
  if(HasCardAbility($cardID, "generate_resources")) {
    $value += 0.2;
  }
  
  if(HasCardAbility($cardID, "combo")) {
    $value += 0.15;
  }
  
  if(CardType($cardID) == "E" || CardType($cardID) == "I") {
    $value += 0.25;
  }
  
  return min($value, 0.99);
}

function EvaluateBlockValue($cardID, $playerID, &$resources, &$character, $playerHealth)
{
  global $combatChain;
  
  $value = 0;
  $blockPower = BlockValue($cardID);

  if($playerHealth <= $blockPower) {
    return 0.95;
  }
  
  $value = min($blockPower / max($playerHealth, 1), 0.5);

  if(HasCardAbility($cardID, "reaction")) {
    $value += 0.15;
  }
  
  return min($value, 0.89);
}

function EvaluateReactionValue($cardID, $playerID, &$resources, &$hand, $playerHealth)
{
  $value = 0.4;
  
  if(HasCardAbility($cardID, "interrupt")) {
    $value += 0.3;
  }
  
  if(HasCardAbility($cardID, "damage_prevent")) {
    $value += 0.25;
  }
  
  if(HasCardAbility($cardID, "tutor") || HasCardAbility($cardID, "draw")) {
    $value += 0.2;
  }
  
  return min($value, 0.89);
}

function EvaluatePitchValue($cardID)
{
  $value = PitchValue($cardID) / 3;

  return min($value, 0.9);
}

function HasCardAbility($cardID, $abilityTag)
{
  
  switch($abilityTag) {
    case "combo":
      return strpos($cardID, "combo") !== false || CardSubtype($cardID) == "Combo";
    case "reaction":
      return CardType($cardID) == "R";
    case "interrupt":
      return strpos(CardName($cardID), "interrupt") !== false;
    case "draw":
      return strpos($cardID, "draw") !== false || strpos($cardID, "search") !== false;
    case "tutor":
      return strpos($cardID, "tutor") !== false || strpos($cardID, "search") !== false;
    case "damage_prevent":
      return CardType($cardID) == "DR" || CardType($cardID) == "R" && BlockValue($cardID) > 0;
    case "generate_resources":
      return strpos($cardID, "resource") !== false || strpos($cardID, "pitch") !== false;
    default:
      return false;
  }
}

function EstimateDamageOutput($playerID)
{
  $hand = &GetHand($playerID);
  $totalDamage = 0;
  $resources = &GetResources($playerID);
  
  for($i = 0; $i < count($hand); ++$i) {
    $cost = CardCost($hand[$i]);
    if($cost <= $resources[0] + PitchValue($hand[$i])) {
      $totalDamage += PowerValue($hand[$i]);
    }
  }
  
  return $totalDamage;
}

function GetResourceEfficiency($cardID)
{
  $cost = CardCost($cardID);
  $power = PowerValue($cardID);
  $value = EvaluateCardValue($cardID, 2, "Action");
  
  if($cost == 0) return $power + $value;
  return ($power + $value) / $cost;
}

function ShouldBeAggressive($playerID)
{
  $myHealth = &GetHealth($playerID);
  $enemyHealth = &GetHealth($playerID == 1 ? 2 : 1);
  
  $damageOutput = EstimateDamageOutput($playerID);

  return ($damageOutput >= $enemyHealth) ||
         ($enemyHealth <= 10 && $myHealth >= 15) ||
         ($damageOutput >= $enemyHealth - 5 && $myHealth >= $enemyHealth);
}

function GetHandQuality($playerID)
{
  $hand = &GetHand($playerID);
  if(count($hand) == 0) return 0;
  
  $totalValue = 0;
  foreach($hand as $cardID) {
    $totalValue += EvaluateCardValue($cardID, $playerID, "Action");
  }
  
  return min($totalValue / count($hand), 1.0);
}

function GetDangerLevel($playerID)
{
  $myHealth = &GetHealth($playerID);
  $enemyHand = &GetHand($playerID == 1 ? 2 : 1);
  $enemyResources = &GetResources($playerID == 1 ? 2 : 1);
  
  // Estimate enemy damage
  $estimatedDamage = 0;
  foreach($enemyHand as $cardID) {
    $cost = CardCost($cardID);
    if($cost <= $enemyResources[0] + 3) {
      $estimatedDamage += PowerValue($cardID);
    }
  }
  
  if($myHealth <= 0) return 1.0;
  $danger = min($estimatedDamage / $myHealth, 1.0);

  return $danger;
}
function IraEstimateAttack($playerID)
{
  $hand      = &GetHand($playerID);
  $resources = &GetResources($playerID);

  $bluePitch = $resources[0];
  foreach ($hand as $cardID) {
    if (PitchValue($cardID) == 3) $bluePitch += 3;
  }

  $attacks = [];
  foreach ($hand as $cardID) {
    if (PitchValue($cardID) < 3 && PowerValue($cardID) > 0) {
      $attacks[] = [CardCost($cardID), PowerValue($cardID)];
    }
  }
  usort($attacks, fn($a, $b) => $b[1] <=> $a[1]);

  $totalAttack  = 0;
  $attacksFired = 0;
  foreach ($attacks as [$cost, $attack]) {
    if ($cost <= $bluePitch) {
      $bluePitch   -= $cost;
      $totalAttack += $attack;
      $attacksFired++;
    }
  }

  if ($attacksFired > 0) $totalAttack += 1;

  return $totalAttack;
}

function IraEstimateBlock($playerID)
{
  $hand  = &GetHand($playerID);
  $total = 0;
  foreach ($hand as $cardID) {
    $total += BlockValue($cardID);
  }
  return $total;
}

function IraIsInAttackMode($playerID)
{
  return IraEstimateAttack($playerID) > IraEstimateBlock($playerID);
}

function IraIsCardReservedForOffense($cardID, $playerID)
{
  $hand      = &GetHand($playerID);
  $resources = &GetResources($playerID);

  $pitch   = PitchValue($cardID);
  $cost    = CardCost($cardID);
  $attack  = PowerValue($cardID);
  $defense = BlockValue($cardID);

  if ($pitch == 1 && $attack > $defense) {
    if ($cost == 0) return true;

    $idx = array_search($cardID, $hand, true);
    if ($idx === false) return false;

    return IraCanFundCostlyAttack([$cardID, "Hand", $idx, 0], $hand, $resources);
  }

  if ($pitch == 3) {
    $bluesInHand     = 0;
    $totalCostlyCost = 0;
    foreach ($hand as $hCard) {
      if (PitchValue($hCard) == 3) $bluesInHand++;
      if (CardCost($hCard) > 0 && PitchValue($hCard) < 3 && PowerValue($hCard) > 0) {
        $totalCostlyCost += CardCost($hCard);
      }
    }

    $totalNeeded = $totalCostlyCost + 1;
    $resourceFloat = $resources[0] ?? 0;
    $bluesNeeded = max(0, (int)ceil(($totalNeeded - $resourceFloat) / 3));

    return ($bluesInHand - 1) < $bluesNeeded;
  }

  return false;
}

function IraCanFundCostlyAttack($node, $hand, $resources)
{
  $cardID = $node[0];
  $idx    = $node[2];
  $cost   = CardCost($cardID);
  if ($cost <= 0) return true;

  $blueYellowPitch = $resources[0];
  $reds = [];
  foreach ($hand as $hIdx => $hCard) {
    if ($hIdx == $idx) continue;
    $pv = PitchValue($hCard);
    if ($pv >= 2) {
      $blueYellowPitch += $pv;
    } elseif ($pv == 1) {
      $reds[] = [
        'attack'    => PowerValue($hCard),
        'block'     => BlockValue($hCard),
        'cost'      => CardCost($hCard),
        'fundable'  => CardCost($hCard) == 0, // 0-cost reds would attack in the chain
      ];
    }
  }

  if ($cost <= $blueYellowPitch) return true;

  usort($reds, function ($a, $b) {
    if ($a['fundable'] !== $b['fundable']) return $a['fundable'] <=> $b['fundable'];
    return $a['attack'] <=> $b['attack'];
  });

  $shortfall = $cost - $blueYellowPitch;
  $pitchedCount = 0;
  $blockGiven = 0;
  $attackLost = 0;
  foreach ($reds as $r) {
    if ($pitchedCount >= $shortfall) break;
    $pitchedCount++;
    $blockGiven += $r['block'];
    if ($r['fundable']) $attackLost += $r['attack'];
  }
  if ($pitchedCount < $shortfall) return false;

  $attackPath = PowerValue($cardID) + 1 - $attackLost;
  $blockPath  = BlockValue($cardID) + $blockGiven;

  return $attackPath >= $blockPath;
}