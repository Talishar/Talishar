<?php

function DynamicSortPools()
{
  $encounter = &GetZone(1, "Encounter");
  $smallTags
  for($i = 0; $i)
}

function DynamicGetCards($input)
{
  //Use the input to determine the pools it's generating cards from
  for(/*each item in the input*/)
  {
    
  }
  $addedReward = DynamicGetCard($tag, $rarity, $reward); //using the tags it's determined to get, it will then call GetCard to get an individual card
  //NOTE: $addedReward might be empty. Or more specifically, "NoResult". Need to add in a fail case for that so it doesn't break.
}

//In the encounter class, the tags array is an array of tag items. The information stored is: $name, $weight, $unused (true or false), $cardPool, and $removedCardPool

function DynamicGetCard($tag, $rarity, $reward)
{
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    if($encounter->tags[$i]->name == $tag) $pool = $encounter->tags[$i]->pool;
  }
  $pool = DynamicGeneratePool($rarity, $reward, $pool);
  if(count($pool) == 0) return "NoResult";
  else return $pool[rand(0, count($pool)-1)];
}

function DynamicGeneratePool($rarity, $reward, $pool)
{
  $generatedPool = [];
  for($i = 0; $i < count($pool); ++$i)
  {
    if(DynamicCheckRarity($rarity, Rarity($pool[$i])) && DynamicCheckRewards($reward, $pool[$i])) array_push($generatedPool, $pool[$i]); //if it's the desired rarity and not already in the reward, add it to the pool
  }
  return $generatedPool;
}

function DynamicCheckRarity($rarityOrig, $rarityCheck)
{
  switch($rarity)
  {
    case "Common": return $rarityCheck == "C" || $rarityCheck == "T";
    case "Rare": return $rarityCheck == "R";
    case "Majestic": return $rarityCheck == "M" || $rarityCheck == "S";
    case "Legendary": return $rarityCheck == "L";
  }
}

function DynamicCheckRewards($reward, $cardID)
{
  for($i = 0; $i < count($reward); ++$i)
  {
    if($reward[$i] == $cardID) return false;
  }
  return true;
}

 ?>
