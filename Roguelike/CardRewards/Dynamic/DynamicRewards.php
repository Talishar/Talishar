<?php

/*function DynamicSortPools()
{
  $encounter = &GetZone(1, "Encounter");
  $smallTags
  for($i = 0; $i)
}*/

function DynamicGetCards($amount, $rarity, $special = "-")
{
  if($rarity == "Random") $rarity = DynamicGetRarity(); //If the rarity *isn't* set, it'll grab a random rarity based on MajesticCard
  $typeRewards = DynamicGetRewards($amount); //Gets an array of types (See -> DynamicGetRewards)
  $reward = [];
  for($i = 0; $i < count($cardRewards); ++$i) //For each type generated, it's going to add in a card to the reward pool
  {
    array_push($reward, DynamicGetCardOuter(DynamicGetTags($typeRewards[$i]), $rarity, $reward, $special)); //Pushes a random card into the array (See -> DynamicGetCardOuter and DynamicGetTags)
  }
}

function DynamicGetCardOuter($tagRewards, $rarity, $reward, $special) //This returns a random card that matches the criteria
{
  $addedReward = DynamicGetCardInner($tagRewards, $rarity, $reward, $special); //First, it grabs a random card option (See -> DynamicGetCardInner)
  if($addedReward = "NoResult") return DynamicGetCardOuter($tagRewards, DynamicNextRarity($rarity), $reward, $special); //If it couldn't find an option, it goes down a rarity and recursively tries again (See -> DynamicNextRarity)
  else DynamicUpdateMajesticCard($rarity); return $addedReward; //If it finds an option, it will update the MajesticCard chance and return the option (See -> DynamicUpdateMajesticCard)
}

function DynamicGetCardInner($tagRewards, $rarity, $reward, $special) //$tagRewards is an array of tags, $rarity is the rarity of cards, $reward is the previously chosen cards in this reward, $special does nothing
{
  $tag = $tagRewards[1][count($typeRewards[1])-1]; //First, grab the first item in the $tagRewards tag array
  array_pop($tagRewards[1]); //Then, remove it
  //$encounter = &GetZone(1, "Encounter");
  /*for($i = 0; $i < count($encounter->tags); ++$i) //It will search through the encounter to find a tag with the chosen name (This is necessary to get the "removedCardPool" from the tag)
  {
    if($encounter->tags[$i]->name == $tag) $pool = DynamicGeneratePool($rarity, $reward, DynamicGetPool($tag), $encounter->tags[$i]->removedCardPool, $special); break;
  }*/
  $pool = DynamicGeneratePool($rarity, $reward, DynamicGetPool($tag), $tagRewards[0], $special);
  if(count($pool) == 0 && count($cardRewards) == 0) return "NoResult";
  else if(count($pool) == 0) return DynamicGetCardInner($tagRewards, $rarity, $reward, $special);
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

function DynamicNextRarity($rarity) //Based on a rarity input, it will return the next rarity of a lower degree
{
  switch($rarity)
  {
    case "Common": return "Majestic"; //If it's common, it'll just loop back to Majestic
    case "Rare": return "Common";
    case "Majestic": return "Rare";
    case "Legendary": return "Majestic";
  }
}

function DynamicGetRarity()
{
  $encounter = &GetZone(1, $encounter);
  $randRarity = rand(1,100);
  if($randRarity <= $encounter->majesticCard)
  {
    return "Majestic";
  }
  else if($randRarity >= 75)
  {
    return "Rare";
  }
  else
  {
    return "Common";
  }
}

function DynamicUpdateMajesticCard($rarity) //Input is a rarity, based on the rarity, it will update the MajesticCard chance
{
  $encounter = &GetZone(1, $encounter);
  switch($rarity)
  {
    case "Common": $encounter->majesticCard +=1; break;
    case "Rare": $encounter->majesticCard +=3; break;
    case "Majestic": $encounter->majesticCard =1; break;
    default: break;
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

function DynamicCheckSpecial($special, $cardID)
{
  return true;
}

function DynamicGetTags($cardReward) //NOT IMPLEMENTED YET (Will return a 2D array of tags/removed cards based on the type)
{
  switch($cardReward)
  {
    case "Perfect": return array(array(), array("Deck"));
    case "High": return ;
    case "Mid":
    case "Low":
    case "No":
  }
}

function DynamicGetRewards($amount) //This function recieves an amount and returns an array filled with that many types
{
  $rv = [];
  for($i = 0; $i < $amount; ++$i)
  {
    array_push($rv, DynamicRandomReward()); //See -> DynamicRandomReward
  }
  return $rv;
}

function DynamicRandomReward() //This function just returns a random type. All numbers are subject to change
{
  $result = rand(1, 100);
  if($result < 25) return "Mid"; //25% chance to return "Mid"
  else if($result < 50) return "Low"; //25% chance to return "Low"
  else if($result < 70) return "No"; //20% chance to return "No"
  else if($result < 80) return "High"; //10% chance to return "High"
  else return "Perfect"; //10% chance to return "Perfect"
}
 ?>
