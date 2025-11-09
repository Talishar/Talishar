<?php

/**
 * EncounterPriorityLogic.php - Refactored AI priority system
 * 
 * Handles converting card priority values into sorted decision arrays
 * that guide the AI through playing actions, blocking, reacting, etc.
 * 
 * $storedPriorityNode structure:
 *   [0] -> CardID
 *   [1] -> Zone (Hand, Arsenal, Character, Item, Ally, Banish)
 *   [2] -> Index in that zone
 *   [3] -> Priority Value (0-0.9 normal, 10.1-10.9 computed)
 */

/**
 * Generate prioritized array of playable actions for a given phase
 * 
 * @param array $hand - Player's hand
 * @param array $character - Character data
 * @param array $arsenal - Arsenal zone
 * @param array $items - Items/permanents
 * @param array $allies - Allies
 * @param array $banish - Banish zone
 * @param string $type - Phase type: Block, Action, Reaction, Pitch, ToArsenal
 * 
 * @return array - Sorted priority nodes, highest priority last
 */
function GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, $type)
{
  $priorityArray = [];
  
  switch($type) {
    case "Block":
      // For blocking: Hand cards + Equipment, resolve computed values, sort
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 0);
      $priorityArray = PushArray($priorityArray, "Character", $character, $character, 0);
      $priorityArray = ResolvePriorityArray($priorityArray, 10, 0, 2);
      $priorityArray = ResolvePriorityArray($priorityArray, 11, 0, 2);
      $priorityArray = FirstTurnResolution($priorityArray, $character);
      return SortPriorityArray($priorityArray);
      
    case "Action":
      // For actions: Hand + Equipment + Arsenal + Items + Allies + Banish
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 1);
      $priorityArray = PushArray($priorityArray, "Character", $character, $character, 1);
      $priorityArray = PushArray($priorityArray, "Arsenal", $arsenal, $character, 2);
      $priorityArray = PushArray($priorityArray, "Items", $items, $character, 7);
      $priorityArray = PushArray($priorityArray, "Allies", $allies, $character, 7);
      $priorityArray = PushArray($priorityArray, "Banish", $banish, $character, 5);
      $priorityArray = ResolvePriorityArray($priorityArray, 10, "Unplayed", 0);
      return SortPriorityArray($priorityArray);
      
    case "Pitch":
      // For pitching: Only hand cards, pitch priority
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 5);
      return SortPriorityArray($priorityArray);
      
    case "ToArsenal":
      // For arsenaling: Only hand cards
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 6);
      return SortPriorityArray($priorityArray);
      
    case "Reaction":
      // For reactions: Hand + Equipment + Arsenal
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 3);
      $priorityArray = PushArray($priorityArray, "Character", $character, $character, 3);
      $priorityArray = PushArray($priorityArray, "Arsenal", $arsenal, $character, 4);
      $priorityArray = ResolvePriorityArray($priorityArray, 10, "Unplayed", 0);
      return SortPriorityArray($priorityArray);
      
    default:
      WriteLog("ERROR: Priority type '$type' not implemented in AI");
      return $priorityArray;
  }
}

/**
 * Add all cards from a zone to the priority array
 * 
 * For each card in the zone, creates a storedPriorityNode with:
 * - The card ID
 * - The zone name
 * - The index in that zone
 * - The priority value (fetched from CardBehaviors.php)
 */
function PushArray($priorityArray, $zone, $zoneArr, $character, $priorityIndex)
{
  switch($zone) {
    case "Hand":
      for($i = 0; $i < count($zoneArr); ++$i) {
        array_push($priorityArray, array(
          $zoneArr[$i], 
          "Hand", 
          $i, 
          GetPriority($zoneArr[$i], $character[0], $priorityIndex)
        ));
      }
      return $priorityArray;
      
    case "Arsenal":
      for($i = 0; $i < count($zoneArr); $i += ArsenalPieces()) {
        array_push($priorityArray, array(
          $zoneArr[$i], 
          "Arsenal", 
          $i, 
          GetPriority($zoneArr[$i], $character[0], $priorityIndex)
        ));
      }
      return $priorityArray;
      
    case "Character":
      for($i = 0; $i < count($zoneArr); $i += CharacterPieces()) {
        array_push($priorityArray, array(
          $zoneArr[$i], 
          "Character", 
          $i, 
          GetPriority($zoneArr[$i], $character[0], $priorityIndex)
        ));
      }
      return $priorityArray;
      
    case "Items":
      for($i = 0; $i < count($zoneArr); $i += ItemPieces()) {
        array_push($priorityArray, array(
          $zoneArr[$i], 
          "Item", 
          $i, 
          GetPriority($zoneArr[$i], $character[0], $priorityIndex)
        ));
      }
      return $priorityArray;
      
    case "Allies":
      for($i = 0; $i < count($zoneArr); $i += AllyPieces()) {
        array_push($priorityArray, array(
          $zoneArr[$i], 
          "Ally", 
          $i, 
          GetPriority($zoneArr[$i], $character[0], $priorityIndex)
        ));
      }
      return $priorityArray;
      
    case "Banish":
      for($i = 0; $i < count($zoneArr); ++$i) {
        array_push($priorityArray, array(
          $zoneArr[$i], 
          "Banish", 
          $i, 
          GetPriority($zoneArr[$i], $character[0], $priorityIndex)
        ));
      }
      return $priorityArray;
      
    default:
      return $priorityArray;
  }
}

/**
 * Sort priority array using bubble sort
 * Sorts in ascending order (lowest priority first, highest priority last)
 * This allows AI to pop from the end for highest-priority cards
 */
function SortPriorityArray($priorityArray)
{
  do {
    $swapped = false;
    for($i = 0, $c = count($priorityArray) - 1; $i < $c; ++$i) {
      if($priorityArray[$i][3] > $priorityArray[$i + 1][3]) {
        list($priorityArray[$i + 1], $priorityArray[$i]) = array($priorityArray[$i], $priorityArray[$i + 1]);
        $swapped = true;
      }
    }
  } while($swapped);
  
  return $priorityArray;
}

/**
 * Resolve computed priority values (10.1-10.9 range) to final priorities
 * 
 * This function handles the "computed priority" system where cards marked
 * with 10.1-10.9 get dynamically resolved based on their order:
 * - First $amount cards in range resolve to $destinationPrime
 * - Remaining cards in range resolve to $destinationSecondary
 * 
 * @param array $priorityArray - Array of priority nodes
 * @param float $range - Range to look for (e.g., 10 for 10.1-10.9)
 * @param float|string $destinationPrime - Where to resolve first cards to (or "Unplayed" for 0)
 * @param float|string $destinationSecondary - Where to resolve remaining cards to
 * @param int $amount - How many cards get the prime destination
 */
function ResolvePriorityArray($priorityArray, $range, $destinationPrime, $destinationSecondary, $amount = 1)
{
  // Find and resolve the highest-priority cards in range
  for($i = 0; $i < $amount; $i++) {
    $index = -1;
    $maxPriority = $range + 0.09; // Just below range start
    
    // Find highest priority card in range
    for($j = 0; $j < count($priorityArray); ++$j) {
      if($priorityArray[$j][3] >= $range + 0.1 && 
         $priorityArray[$j][3] <= $range + 0.9 &&
         $priorityArray[$j][3] > $maxPriority) {
        $index = $j;
        $maxPriority = $priorityArray[$j][3];
      }
    }
    
    // Not found, stop
    if($index == -1) return $priorityArray;
    
    // Resolve this card
    if($destinationPrime == "Unplayed") {
      $priorityArray[$index][3] = 0;
    } else {
      $decimalPart = $priorityArray[$index][3] - (int)$priorityArray[$index][3];
      $priorityArray[$index][3] = $destinationPrime + $decimalPart;
    }
  }
  
  // Resolve remaining cards in range
  for($k = 0; $k < count($priorityArray); ++$k) {
    if($priorityArray[$k][3] >= $range + 0.1 && $priorityArray[$k][3] <= $range + 0.9) {
      if($destinationSecondary == "Unplayed") {
        $priorityArray[$k][3] = 0;
      } else {
        $decimalPart = $priorityArray[$k][3] - (int)$priorityArray[$k][3];
        $priorityArray[$k][3] = $destinationSecondary + $decimalPart;
      }
    }
  }
  
  return $priorityArray;
}

/**
 * On first turn, boost block priority for cards that should block early
 * Encounters often need to block early to prevent huge onhits
 */
function FirstTurnResolution($priorityArray, $character)
{
  global $currentTurn;
  
  if($currentTurn == 1 && EncounterBlocksFirstTurn($character[0])) {
    for($i = 0; $i < count($priorityArray); ++$i) {
      // Boost hand cards but not equipment
      if($priorityArray[$i][3] != 0 && $priorityArray[$i][1] != "Character") {
        $decimalPart = $priorityArray[$i][3] - (int)$priorityArray[$i][3];
        $priorityArray[$i][3] = 2.0 + $decimalPart;
      }
    }
  }
  
  return $priorityArray;
}

?>
