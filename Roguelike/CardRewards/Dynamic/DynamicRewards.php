<?php

function DynamicSortPools()
{
  $encounter = &GetZone(1, "Encounter");
  $smallTags
  for($i = 0; $i)
}

function DynamicGetCards($input)
{
  $cardRewards = DynamicGetRewards($input);
  for($i = 0; $i < count($cardRewards); ++$i)
  {
    $addedReward = DynamicGetCard(DynamicGetTags($cardRewards[$i]), $rarity, $reward, $special); //using the tags it's determined to get, it will then call GetCard to get an individual card
    //NOTE: $addedReward might be empty. Or more specifically, "NoResult". Need to add in a fail case for that so it doesn't break.
  }
}

//In the encounter class, the tags array is an array of tag items. The information stored is: $name, $weight, $unused (true or false), $removedCardPool

function DynamicGetCard($typeRewards, $rarity, $reward, $special)
{
  $tag = $typeRewards[count($typeRewards)-1];
  array_pop($typeRewards);
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    if($encounter->tags[$i]->name == $tag) $pool = DynamicGeneratePool($rarity, $reward, DynamicGetPool($tag), $encounter->tags[$i]->removedCardPool, $special); break;
  }
  if(count($pool) == 0 && count($cardRewards) == 0) return "NoResult";
  else if(count($pool) == 0) return DynamicGetCard($typeRewards, $rarity, $reward, $special);
  else return $pool[rand(0, count($pool)-1)];
}

function DynamicGeneratePool($rarity, $reward, $pool, $requiredPool, $Special)
{
  $generatedPool = [];
  for($i = 0; $i < count($pool); ++$i)
  {
    if(DynamicCheckRarity($rarity, Rarity($pool[$i])) && DynamicCheckRewards($reward, $pool[$i]) && DynamicCheckRequirements($requiredPool, $pool[$i]), DynamicCheckSpecial($special, $pool[$i])) array_push($generatedPool, $pool[$i]); //if it's the desired rarity and not already in the reward, add it to the pool
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
  if(in_array($cardID, $reward)) return false;
  else return true;
}

function DynamicCheckRequirements($removed, $cardID)
{
  if(in_array($cardID, $removed)) return false;
  else return true;
}

function DynamicGetRewardTypes($input)
{
  //basically turns the input into an array of types
}

function DynamicGetTags($cardRewards)
{
 //turns a type into an array of shuffled choosable pools
}
 ?>
