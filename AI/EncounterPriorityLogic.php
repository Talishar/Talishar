<?php

//$storedPriorityNode values:
//[0] -> CardID
//[1] -> Where it's being played from (Hand, Arsenal, and Character(weapons, equipment, hero) implemented)
//[2] -> Index of the location it's being played from
//[3] -> Priority Value

//This function is super complicated, let me run you through it.
function GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $type) //TODO: add items, auras, allies, banish, and permanents
{
  $priorityArray = []; //Creates an empty array to push things into, then checks what type of priority array is being created.
  switch($type)
  {
    case "Block":
      $priorityArray = PushArray(PushArray($priorityArray, "Hand", $hand, $character, 0), "Character", $character, $character, 0);
      $priorityArray = ResolvePriorityArray(ResolvePriorityArray($priorityArray, 10, 0, 2), 11, 0, 2);
      $priorityArray = SortPriorityArray(FirstTurnResolution($priorityArray, $character));
      return $priorityArray; //The block case pushes in Hand values, then pushes in Equipment values, then resolves 10 values, then resolves 11 values, and finally sorts the array
    case "Action":
      $priorityArray = PushArray(PushArray(PushArray($priorityArray, "Hand", $hand, $character, 1), "Character", $character, $character, 1), "Arsenal", $arsenal, $character, 2);
      $priorityArray = PushArray(PushArray($priorityArray, "Items", $items, $character, 7), "Allies", $allies, $character, 7);
      $priorityArray = ResolvePriorityArray($priorityArray, 10, "Unplayed", 0);
      $priorityArray = SortPriorityArray($priorityArray);
      return $priorityArray; //The action case pushes in Hand values, then Character values, then Arsenal values, then resolves 10 values, and finally sorts the array
    case "Pitch":
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 5);
      $priorityArray = SortPriorityArray($priorityArray);
      return $priorityArray; //The pitch case pushes in Hand values and sorts the array
    case "ToArsenal":
      $priorityArray = PushArray($priorityArray, "Hand", $hand, $character, 6);
      $priorityArray = SortPriorityArray($priorityArray);
      return $priorityArray; //The toarsenal case pushes in Hand values and sorts the array
    case "Reaction":
      $priorityArray = PushArray(PushArray(PushArray($priorityArray, "Hand", $hand, $character, 3), "Character", $character, $character, 3), "Arsenal", $arsenal, $character, 4);
      $priorityArray = ResolvePriorityArray($priorityArray, 10, "Unplayed", 0);
      $priorityArray = SortPriorityArray($priorityArray);
      return $priorityArray; //the reaction case pushes in Hand Values, then Character values, then Arsenal values, then resolves 10 values, and finally sorts the array
    default: WriteLog("ERROR: Priority Value type case not implemented in AI. Please submit a bug report."); return $priorityArray;
  }
}

function PushArray($priorityArray, $zone, $zoneArr, $character, $priorityIndex) //this function takes the following arguments: The array that it's pushing into, the name of the zone it's grabbing values from, the array of that zone, the character array, and an index it's using to grab values (SEE: EncounterPriorityValues.php)
{
  switch($zone)
  {
    case "Hand":
      for($i = 0; $i < count($zoneArr); ++$i) //for each item in the respective source location, it pushes in a storedPriorityNode array. See the top of this function for the definition of each index. The priority is stolen from EncounterPriorityValues.php, see that file for more details
      {
        array_push($priorityArray, array($zoneArr[$i], "Hand", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex)));
      }
      return $priorityArray;
    case "Arsenal":
      for($i = 0; $i < count($zoneArr); $i += ArsenalPieces())
      {
        array_push($priorityArray, array($zoneArr[$i], "Arsenal", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex)));
      }
      return $priorityArray;
    case "Character":
      for($i = 0; $i < count($zoneArr); $i += CharacterPieces())
      {
        array_push($priorityArray, array($zoneArr[$i], "Character", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex)));
      }
      return $priorityArray;
    case "Items":
      for($i = 0; $i < count($zoneArr); $i += ItemPieces())
      {
        array_push($priorityArray, array($zoneArr[$i], "Item", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex)));
      }
      return $priorityArray;
    case "Allies":
    for($i = 0; $i < count($zoneArr); $i += AllyPieces())
      {
        array_push($priorityArray, array($zoneArr[$i], "Ally", $i, GetPriority($zoneArr[$i], $character[0], $priorityIndex)));
      }
    return $priorityArray;
    default: return $priorityArray;
  }
}

function SortPriorityArray($priorityArray) //this is just a bubblesort method to sort the array. It is sorted such that the first item in the array is the smallest and the last item is the largest
{
  do {
		$swapped = false;
		for($i = 0, $c = count($priorityArray) - 1; $i < $c; ++$i)
		{
			if($priorityArray[$i][3] > $priorityArray[$i + 1][3])
			{
				list($priorityArray[$i + 1], $priorityArray[$i]) = array($priorityArray[$i], $priorityArray[$i + 1]);
				$swapped = true;
			}
		}
	} while($swapped);
return $priorityArray;
}

function ResolvePriorityArray($priorityArray, $range, $destinationPrime, $destinationSecondary, $amount = 1) //This resolves a portion of the array for use. It takes a number of items in the array within range.1-range.9 equal to the amount passed in, and resolves it to destinationPrime.X; it then resolves any additional values of the range to destinationSecondary.X
{
  for($i = 0; $i < $amount; $i++)
  {
    $index = 0;
    $found = false;
    for($j = 0; $j < count($priorityArray); ++$j)
    {
      if($priorityArray[$j][3] >= $priorityArray[$index][3] && $range + 0.1 <= $priorityArray[$j][3] && $priorityArray[$j][3] <= $range + 0.9)
      {
        $index = $j;
        $found = true;
      }
    }
    if(!$found) return $priorityArray;
    if($destinationPrime == "Unplayed") $priorityArray[$index][3] = 0;
    else
    {
      $indexValue = $priorityArray[$index][3] - (int) $priorityArray[$index][3];
      $priorityArray[$index][3] = $destinationPrime + $indexValue;
    }
  }
  for($k = 0; $k < count($priorityArray); ++$k)
  {
    if($range + 0.1 <= $priorityArray[$k][3] && $priorityArray[$k][3] <= $range + 0.9)
    {
      if($destinationSecondary == "Unplayed") $priorityArray[$k][3] = 0;
      else
      {
        $indexValue = $priorityArray[$k][3] - (int) $priorityArray[$k][3];
        $priorityArray[$k][3] = $destinationSecondary + $indexValue;
      }
    }
  }
  return $priorityArray;
}

function FirstTurnResolution($priorityArray, $character)
{
  global $currentTurn;
  if($currentTurn == 1 && EncounterBlocksFirstTurn($character[0]))
  {
    for($i = 0; $i < count($priorityArray); ++$i)
    {
      if($priorityArray[$i][3] != 0 && $priorityArray[$i][1] != "Character")
      {
        $indexValue = $priorityArray[$i][3] - (int) $priorityArray[$i][3];
        $priorityArray[$i][3] = 2.0 + $indexValue;
      }
    }
  }
  return $priorityArray;
}

?>
