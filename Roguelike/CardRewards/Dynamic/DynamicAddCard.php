<?php

function AddCard($cardID)
{
  $cardTags = DynamicGetCardTags($cardID); //returns an array of tag names
  for($i = 0; $i < count($cardTags); ++$i)
  {
    DynamicIncrementTag($cardTags[$i], $cardID);
  }

}

function DynamicIncrementTag($cardTag, $cardID)
{
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    if($encounter->tags[$i]->tag == $cardTag) ++$encounter->tags[$i]->weight; return;
  }
  if(!DynamicIsIgnored($cardTag, $cardID)) DynamicCreateTag($cardTag);
}

function DynamicIsIgnored($cardTag, $cardID)
{
  $ignored = DynamicGetIgnored($cardTag); //returns an array of cards that are ignored in the tag's card pool (like how Blaze Headlong would create a red pool, but red Nimblism wouldn't)
  if(in_array($cardID, $ignored)) return true;
  else return false;
}

function DynamicCreateTag($tag)
{
  $newTag = new Tag($tag);
  $newTag->removed = DynamicCreateRemoved($tag);
  $newTag->weight = DynamicCreateWeight($tag);
  DynamicAdjustRemoved($tag);
  $encounter = &GetZone(1, "Encounter");
  array_push($encounter->tags, $newTag);
}

function DynamicCreateRemoved($tag)
{
  $initialRemoved = DynamicGetInitialRemoved($tag); //will return an array of cards that are initially removed from the pool
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    $requiredBy = DynamicGetRequiredBy($encounter->tags[$i]->tag);
    for($j = count($initialRemoved)-1; $j >= 0; --$j)
    {
      if(in_array($initialRemoved[$j], $requiredBy))
      {
        unset($initialRemoved[$j]);
        $initialRemoved = array_values($initialRemoved);
      }
    }
  }
  return $initialRemoved
}

function DynamicCreateWeight($tag)
{
  $weight = 0;
  $pool = DynamicGetPool($tag);
  $deck = &GetZone(1, "Deck");
  for($i = 0; $i < count($deck); ++$i)
  {
    if(in_array($deck[$i], $pool)) ++$weight;
  }
  return $weight;
}

function DynamicAdjustRemoved($tag)
{
  $encounter = &GetZone(1, "Encounter");
  $cardsToResolve = DynamicGetRequiredBy($tag);
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    for($j = count($encounter->tags[$i]->removed)-1; $j >= 0; --$j)
    {
      if(in_array($encounter->tags[$i]->removed[$j], $cardsToResolve))
      {
        unset($encounter->tags[$i]->removed[$j]);
        $encounter->tags[$i]->removed = array_values($encounter->tags[$i]->removed);
      }
    }
  }
}
 ?>
