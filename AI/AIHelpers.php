<?php

/**
 * AIHelpers.php - Core AI utility functions for smarter game evaluation
 * 
 * This file contains helper functions for evaluating card value, game state,
 * and making smarter AI decisions beyond simple priority lists.
 */

/**
 * Evaluates the tactical value of playing a card based on current game state
 * 
 * @param string $cardID - Card identifier
 * @param int $playerID - Player ID (usually 2 for AI)
 * @param string $context - Current phase/context (Block, Action, Reaction, etc)
 * @return float - Value score (higher = better to play)
 */
function EvaluateCardValue($cardID, $playerID, $context = "Action")
{
  $baseValue = 0.5; // Default neutral value
  
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

/**
 * Evaluates value of an action phase card
 */
function EvaluateActionValue($cardID, $playerID, &$resources, &$hand, &$character, $playerHealth, $enemyHealth)
{
  $value = 0.5;
  
  // Cards that deal damage are high priority if opponent is low
  if(CardType($cardID) == "AA" || CardType($cardID) == "I") {
    $damage = PowerValue($cardID);
    if($enemyHealth <= $damage) {
      return 0.95; // Lethal threat
    }
    // Damage scales in value as opponent gets lower
    $value += ($damage / max($enemyHealth, 1)) * 0.3;
  }
  
  // Cards that generate resources or setup are good
  if(HasCardAbility($cardID, "generate_resources")) {
    $value += 0.2;
  }
  
  // Cards with combo potential
  if(HasCardAbility($cardID, "combo")) {
    $value += 0.15;
  }
  
  // Permanent effects (equipment, items) scale up over time
  if(CardType($cardID) == "E" || CardType($cardID) == "I") {
    $value += 0.25;
  }
  
  return min($value, 0.99);
}

/**
 * Evaluates value of a block card
 */
function EvaluateBlockValue($cardID, $playerID, &$resources, &$character, $playerHealth)
{
  global $combatChain;
  
  $value = 0;
  $blockPower = BlockValue($cardID);
  
  // If lethal threat, high priority
  if($playerHealth <= $blockPower) {
    return 0.95;
  }
  
  // Block efficiency: how much damage it prevents
  $value = min($blockPower / max($playerHealth, 1), 0.5);
  
  // Defensive reactions or combo cards in block phase get bonus
  if(HasCardAbility($cardID, "reaction")) {
    $value += 0.15;
  }
  
  return min($value, 0.89); // Cap below lethal threshold
}

/**
 * Evaluates value of a reaction phase card
 */
function EvaluateReactionValue($cardID, $playerID, &$resources, &$hand, $playerHealth)
{
  $value = 0.4; // Reactions are typically lower value
  
  // Interrupt effects are high value
  if(HasCardAbility($cardID, "interrupt")) {
    $value += 0.3;
  }
  
  // Damage prevention effects
  if(HasCardAbility($cardID, "damage_prevent")) {
    $value += 0.25;
  }
  
  // Card draw/tutoring effects
  if(HasCardAbility($cardID, "tutor") || HasCardAbility($cardID, "draw")) {
    $value += 0.2;
  }
  
  return min($value, 0.89);
}

/**
 * Evaluates value of a pitch card
 */
function EvaluatePitchValue($cardID)
{
  $value = PitchValue($cardID) / 3; // Normalize 0-3 pitch to 0-1
  
  // Could add logic here to prefer certain colors based on hand composition
  // For now, just use pitch value
  
  return min($value, 0.9);
}

/**
 * Check if card has a specific ability tag
 * @param string $cardID
 * @param string $abilityTag
 * @return bool
 */
function HasCardAbility($cardID, $abilityTag)
{
  // This would be expanded with a card abilities database
  // For now, implement basic checks
  
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

/**
 * Gets estimated damage output from current hand
 */
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

/**
 * Gets resource efficiency score (how much value per resource spent)
 */
function GetResourceEfficiency($cardID)
{
  $cost = CardCost($cardID);
  $power = PowerValue($cardID);
  $value = EvaluateCardValue($cardID, 2, "Action");
  
  if($cost == 0) return $power + $value;
  return ($power + $value) / $cost;
}

/**
 * Should the AI be aggressive (go for lethal) or defensive?
 */
function ShouldBeAggressive($playerID)
{
  $myHealth = &GetHealth($playerID);
  $enemyHealth = &GetHealth($playerID == 1 ? 2 : 1);
  
  $damageOutput = EstimateDamageOutput($playerID);
  
  // Be aggressive if:
  // - Can deal lethal damage
  // - Enemy is low health (<= 10)
  // - Own health is good
  return ($damageOutput >= $enemyHealth) || 
         ($enemyHealth <= 10 && $myHealth >= 15) ||
         ($damageOutput >= $enemyHealth - 5 && $myHealth >= $enemyHealth);
}

/**
 * Get hand quality score (0-1)
 * Higher = better cards in hand
 */
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

/**
 * Get the "danger level" - how threatened is the AI?
 * Used to shift between offensive and defensive plays
 * 
 * @return float 0-1, where 1 = very threatened
 */
function GetDangerLevel($playerID)
{
  $myHealth = &GetHealth($playerID);
  $enemyHand = &GetHand($playerID == 1 ? 2 : 1);
  $enemyResources = &GetResources($playerID == 1 ? 2 : 1);
  
  // Estimate enemy damage
  $estimatedDamage = 0;
  foreach($enemyHand as $cardID) {
    $cost = CardCost($cardID);
    if($cost <= $enemyResources[0] + 3) { // Assume could pitch for cost
      $estimatedDamage += PowerValue($cardID);
    }
  }
  
  // Danger is lethal % of our health
  if($myHealth <= 0) return 1.0;
  $danger = min($estimatedDamage / $myHealth, 1.0);
  
  return $danger;
}