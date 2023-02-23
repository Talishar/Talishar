<?php

function GeneratePriorityValues($hand, $character, $arsenal, $type) //TODO: add items, auras, allies, banish, and permanents //TODO: Make this function able to be called for all sources, blocking, actions, pitching, etc.
{
  $priorityArray = [];
  //array's pushed into this array are as follows: [CardID, Where, Index in location, Priority Value]
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($priorityArray, array($hand[$i], "Hand", $i, GetPriority($hand[$i], $character[0], $type)));
  }
  for($i = 0; $i < count($character); $i += CharacterPieces())
  {
    array_push($priorityArray, array($character[$i], "Character", $i, GetPriority($character[$i], $character[0], $type)));
  }
  if($type == 1 || $type == 3) ++$type;
  for($i = 0; $i < count($arsenal); $i += ArsenalPieces())
  {
    array_push($priorityArray, array($arsenal[$i], "Arsenal", $i, GetPriority($arsenal[$i], $character[0], $type)));
  }
  return sortPriorityArray($priorityArray);
}

function sortPriorityArray($priorityArray)
{
  do
	{
		$swapped = false;
		for($i = 0, $c = count($priorityArray) - 1; $i < $c; ++$i)
		{
			if($priorityArray[$i][3] > $priorityArray[$i + 1][3])
			{
				list($priorityArray[$i + 1], $priorityArray[$i]) = array($priorityArray[$i], $priorityArray[$i + 1]);
				$swapped = true;
			}
		}
	}
	while($swapped);
return $priorityArray;
}

?>
