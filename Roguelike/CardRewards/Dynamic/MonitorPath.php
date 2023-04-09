<?php

function TagGeneratePool($tag)
{
  $pool = GetPoolByTag($tag); //returns an array of CardReward classes
  $result = [];
  for($i = 0; $i < count($pool); ++$i)
  {
    if(CanBeAdded($pool[$i])) array_push($result, $pool[$i]);
  }
  return $result;
}

function CanBeAdded($cardReward)
{
  if($cardReward->requirement == "-") return true; //no requirements to be in the pool
  for($i = 0; $i < count($cardReward->requirement); ++$i)
  {
    if(TagNotMet($cardReward->requirement[$i])) return false; //if the requirement isn't met
  }
  return true; //It's gone through all of the requirements and all of them are met
}

function TagNotMet($tag)
{
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    if($tag == $encounter->tags[$i]->tag) return false;
  }
  return true;
}

 ?>
