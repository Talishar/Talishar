<?php

function AddCard($cardID)
{
  $cardTags = DynamicGetCardTags($cardID); //returns an array of tag names
  for($i = 0; $i < count($cardTags); ++$i) //For each item in the added card's tags:
  {
    DynamicIncrementTag($cardTags[$i], $cardID); //Increment a tag in the encounter variable
  }
  DynamicSortEncounterTags(); //Once the card has been added, it's going to go in and resort encounter arrays.
}

function DynamicIncrementTag($cardTag, $cardID) //Takes a tag and a card ID, doesn't return anything
{
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i) //Search through the encounter tag array until it finds a tag matching the tag being incremented
  {
    if($encounter->tags[$i]->tag == $cardTag) { ++$encounter->tags[$i]->weight; return; } //increment the tag, then stop the function, as the tag has been found.
  }
  if(!DynamicIsIgnored($cardTag, $cardID)) DynamicCreateTag($cardTag); //The tag is not in the function, so we need to create it (if it should be created. See -> DynamicIsIgnored). See DynamicCreateTag
}

function DynamicIsIgnored($cardTag, $cardID)
{
  $ignored = DynamicGetIgnored($cardTag); //returns an array of cards that are ignored in the tag's card pool (like how Blaze Headlong would create a red pool, but red Nimblism wouldn't)
  if(in_array($cardID, $ignored)) return true;
  else return false;
}

function DynamicCreateTag($tag) //The tag isn't already in the encounter array, and is going to be created by this card.
{
  $newTag = new Tag($tag); //First, create a new tag object with the tag name
  $newTag->removed = DynamicCreateRemoved($tag); //then, create the cards that are excluded from the pool (See -> DynamicCreateRemoved)
  $newTag->weight = DynamicCreateWeight($tag); //then, add up the weight of cards already in the deck (See -> DynamicCreateWeight)
  DynamicAdjustRemoved($tag); //Then, adjust the removed list of other cards in the game that might need this
  $encounter = &GetZone(1, "Encounter");
  array_push($encounter->tags, $newTag); //Once all the creation has been done, add it to the encounter variable
}

function DynamicCreateRemoved($tag)
{
  $initialRemoved = DynamicGetInitialRemoved($tag); //will return an array of cards that are initially removed from the pool (See -> DynamicGetInitialRemoved)
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i) //For each tag in the already in the encounter
  {
    $requiredBy = DynamicGetRequiredBy($encounter->tags[$i]->tag); //Get's an array of cards which require the tag to be in the pool
    for($j = count($initialRemoved)-1; $j >= 0; --$j) //For each card in the initially removed pool
    {
      if(in_array($initialRemoved[$j], $requiredBy)) //If it meets the tag requirement above
      {
        unset($initialRemoved[$j]); //remove it from the removed pool
        $initialRemoved = array_values($initialRemoved); //and clean it up
      }
    }
  }
  return $initialRemoved; //Once the final added pool has been dealt with, return it.
}

function DynamicCreateWeight($tag)
{
  $weight = 0;
  $pool = DynamicGetPool($tag);
  $deck = &GetZone(1, "Deck");
  for($i = 0; $i < count($deck); ++$i) //For each card in the deck
  {
    if(in_array($deck[$i], $pool)) ++$weight; //If it's in the pool of the tag, increment it
  }
  return $weight; //And then return the final weight
}

function DynamicAdjustRemoved($tag)
{
  $encounter = &GetZone(1, "Encounter");
  $cardsToResolve = DynamicGetRequiredBy($tag); //Get's an array of cards which require the tag to be in the pool
  for($i = 0; $i < count($encounter->tags); ++$i) //For each tag in the encounter object
  {
    for($j = count($encounter->tags[$i]->removed)-1; $j >= 0; --$j) //for each removed card in that tag
    {
      if(in_array($encounter->tags[$i]->removed[$j], $cardsToResolve)) //if it meets the requirement of the tag that was just added
      {
        unset($encounter->tags[$i]->removed[$j]); //remove it from the removed pool
        $encounter->tags[$i]->removed = array_values($encounter->tags[$i]->removed); //and clean it up
      }
    }
  }
}

function DynamicSortEncounterTags()
{
  $encounter = &GetZone(1, "Encounter");
  do {
		$swapped = false;
		for($i = 0, $c = count($encounter->tags) - 1; $i < $c; ++$i)
		{
			if($encounter->tags[$i]->weight > $encounter->tags[$i + 1]->weight)
			{
				list($encounter->tags[$i + 1], $encounter->tags[$i]) = array($encounter->tags[$i], $encounter->tags[$i + 1]);
				$swapped = true;
			}
		}
	} while($swapped);
}

function TestingInitialization()
{
  $encounter = &GetZone(1, "Encounter");
  $tag = new Tag("Banish");
  $tag->weight = 3;
  $tag->removed = array("WTR131");
  array_push($encounter->tags, $tag);
  $tag = new Tag("Red");
  $tag->weight = 6;
  $tag->removed = array();
  array_push($encounter->tags, $tag);
  $tag = new Tag("Wide");
  $tag->weight = 1;
  $tag->removed = array();
  array_push($encounter->tags, $tag);
  WriteTagLog();
  AddCard("DYN062");
  WriteTagLog();

  $int = 5;
  $inttwo = (int) ($int / 3);
  $inttwo = $inttwo;
  WriteLog($inttwo);
}

function WriteTagLog()
{
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    WriteLog($encounter->tags[$i]->tag.",".$encounter->tags[$i]->weight);
    WriteRemovedLog($encounter->tags[$i]->removed);
  }
}
function WriteRemovedLog($array)
{
  WriteLog(implode(",", $array));
  //WriteLog($array);
}
 ?>
