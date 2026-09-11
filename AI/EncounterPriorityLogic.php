<?php

include_once "BotLogic.php";

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
 *   [3] -> Priority Value (from the bot policy; higher is better)
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
      // For blocking: score every legal hand/equipment candidate against the
      // remaining damage and its future-turn opportunity cost.
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 0);
      $priorityArray = PushArray($priorityArray, "Character", $character, $character, 0);
      return SortPriorityArray($priorityArray);
      
    case "Action":
      // For actions: Hand + Equipment + Arsenal + Items + Allies + Banish
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 1);
      $priorityArray = PushArray($priorityArray, "Character", $character, $character, 1);
      $priorityArray = PushArray($priorityArray, "Arsenal", $arsenal, $character, 2);
      $priorityArray = PushArray($priorityArray, "Items", $items, $character, 7);
      $priorityArray = PushArray($priorityArray, "Allies", $allies, $character, 7);
      $priorityArray = PushArray($priorityArray, "Banish", $banish, $character, 5);
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
 * - The priority value (from BotLogic.php)
 */
function PushArray($priorityArray, $zone, $zoneArr, $character, $priorityIndex)
{
  switch($zone) {
    case "Hand":
      $zoneCount = count($zoneArr);
      for($i = 0; $i < $zoneCount; ++$i) {
        $priorityArray[] = [$zoneArr[$i], "Hand", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex, "Hand")];
      }
      return $priorityArray;

    case "Arsenal":
      $zoneCount = count($zoneArr);
      $arsenalPieces = ArsenalPieces();
      for ($i = 0; $i < $zoneCount; $i += $arsenalPieces) {
        $priorityArray[] = [$zoneArr[$i], "Arsenal", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex, "Arsenal")];
      }
      return $priorityArray;

    case "Character":
      $zoneCount = count($zoneArr);
      $characterPieces = CharacterPieces();
      for ($i = 0; $i < $zoneCount; $i += $characterPieces) {
        $priorityArray[] = [$zoneArr[$i], "Character", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex, "Character")];
      }
      return $priorityArray;

    case "Items":
      $zoneCount = count($zoneArr);
      $itemPieces = ItemPieces();
      for ($i = 0; $i < $zoneCount; $i += $itemPieces) {
        $priorityArray[] = [$zoneArr[$i], "Item", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex, "Item")];
      }
      return $priorityArray;

    case "Allies":
      $zoneCount = count($zoneArr);
      $allyPieces = AllyPieces();
      for ($i = 0; $i < $zoneCount; $i += $allyPieces) {
        $priorityArray[] = [$zoneArr[$i], "Ally", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex, "Ally")];
      }
      return $priorityArray;

    case "Banish":
      $zoneCount = count($zoneArr);
      for ($i = 0; $i < $zoneCount; ++$i) {
        $priorityArray[] = [$zoneArr[$i], "Banish", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex, "Banish")];
      }
      return $priorityArray;

    default:
      return $priorityArray;
  }
}

function SortPriorityArray($priorityArray)
{
  usort($priorityArray, fn($left, $right) => $left[3] <=> $right[3]);
  return $priorityArray;
}

/**
 * Priority value for a card in a given context. Every value comes from the
 * shared bot policy in BotLogic.php.
 */
function GetPriority($cardID, $heroID, $type, $zone = "Hand")
{
  global $currentPlayer;
  return BotPriority($cardID, $heroID, $type, $currentPlayer, $zone);
}

?>
