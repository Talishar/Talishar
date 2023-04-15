<?php

function DynamicGetCards($amount, $rarity, $special = "-")
{
  $typeRewards = DynamicGetRewards($amount); //Gets an array of types (See -> DynamicGetRewards)
  $reward = [];
  for($i = 0; $i < count($typeRewards); ++$i) //For each type generated, it's going to add in a card to the reward pool
  {
    array_push($reward, DynamicGetCardOuter(DynamicGetTags($typeRewards[$i]), $rarity, $reward, $special)); //Pushes a random card into the array (See -> DynamicGetCardOuter and DynamicGetTags)
  }
  return implode(",", $reward);
}

function DynamicGetCardOuter($tagRewards, $rarity, $reward, $special) //This returns a random card that matches the criteria
{
  $addedReward = DynamicGetCardInner($tagRewards, $rarity, $reward, $special); //First, it grabs a random card option (See -> DynamicGetCardInner)
  if($addedReward == "NoResult") return "NoResult"; //return DynamicGetCardOuter($tagRewards, DynamicNextRarity($rarity), $reward, $special); //If it couldn't find an option, it goes down a rarity and recursively tries again (See -> DynamicNextRarity)
  else DynamicUpdateMajesticCard($rarity); return $addedReward; //If it finds an option, it will update the MajesticCard chance and return the option (See -> DynamicUpdateMajesticCard)
}

function DynamicGetCardInner($tagRewards, $rarity, $reward, $special) //$tagRewards is an array of tags, $rarity is the rarity of cards, $reward is the previously chosen cards in this reward, $special does nothing
{
  $tag = $tagRewards[count($tagRewards)-1]; //First, grab the first item in the $tagRewards tag array
  array_pop($tagRewards); //Then, remove it
  $pool = DynamicGeneratePool($rarity, $reward, DynamicGetPool($tag), DynamicGetRemoved($tag), $special); //Then, it generates the pool using the information (See -> DynamicGeneratePool and DynamicGetPool (The latter is in DynamicGetPool.php))
  if(count($pool) == 0 && false) return "NoResult"; //If there are no cards in the generated pool (nothing meets the requirement) and no more tags to check, return to the previous function with "NoResult"
  else if(count($pool) == 0) return DynamicGetCardInner($tagRewards, $rarity, $reward, $special); //If there are no cards in the generated pool (and there's still tags to check) recursively return to the top of the function
  else return $pool[rand(0, count($pool)-1)]; //If the pool has cards in it, return a random card from the generated pool
}

function DynamicGeneratePool($rarity, $reward, $pool, $requiredPool, $special) //This generates the pool given the given information.
{
  if($rarity == "Random") $rarity = DynamicGetRarity(); //If the rarity *isn't* set, it'll grab a random rarity based on MajesticCard
  $generatedPool = [];
  for($i = 0; $i < count($pool); ++$i) //For each card in the $pool (acquired using DynamicGetPool($tag)), do the following
  { //If the card is of the given rarity (See DynamicCheckRarity), Is not already in the rewards (See DynamicCheckRewards), Meets the requirements (See DynamicCheckRequirements), and meets the special requirements (See DynamicCheckSpecial),
    if(DynamicCheckRarity($rarity, Rarity($pool[$i])) && DynamicCheckRewards($reward, $pool[$i]) && DynamicCheckRequirements($requiredPool, $pool[$i]) && DynamicCheckSpecial($special, $pool[$i])) array_push($generatedPool, $pool[$i]); //push it into the array
  }
  return $generatedPool; //Once it has created the pool that meets all the requirements return it
}

function DynamicCheckRarity($rarityOrig, $rarityCheck) //Checks the card for if it lines up with the rarity
{
  switch($rarityOrig)
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

function DynamicGetRarity() //Will return a randomly chosen rarity based on MajesticCard chance
{
  $encounter = &GetZone(1, "Encounter");
  $randRarity = rand(1,100);
  if($randRarity <= $encounter->majesticCard) return "Majestic";
  else if($randRarity >= 75) return "Rare";
  else return "Common";
}

function DynamicUpdateMajesticCard($rarity) //Input is a rarity, based on the rarity, it will update the MajesticCard chance
{
  $encounter = &GetZone(1, "Encounter");
  switch($rarity)
  {
    case "Common": $encounter->majesticCard +=1; break;
    case "Rare": $encounter->majesticCard +=3; break;
    case "Majestic": $encounter->majesticCard =1; break;
    default: break;
  }
}

function DynamicCheckRewards($reward, $cardID) //If it's not already in the reward, it's good to go
{
  if(in_array($cardID, $reward)) return false;
  else return true;
}

function DynamicCheckRequirements($removed, $cardID) //If it's not in the removed cards, it's good to go
{
  if(in_array($cardID, $removed)) return false;
  else return true;
}

function DynamicCheckSpecial($special, $cardID) //As there are no special cases implemented, it's good to go
{
  return true;
}

function DynamicGetTags($cardReward) //NOT IMPLEMENTED YET (Will return a 2D array of tags/removed cards based on the type)
{
  $encounter = &GetZone(1, "Encounter");
  $generatedTagPool = [];
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    if(!$encounter->tags[$i]->unused) array_push($generatedTagPool, $encounter->tags[$i]->tag);
  }

  $countOffset = (int) (count($generatedTagPool) / 3);

  switch($cardReward)
  {
    case "Perfect": return array("Deck");
    //case "High": return DynamicShuffleArray(array("PhoenixFlame", "Wide"));
    case "High": $newArray = array_slice($generatedTagPool, -$countOffset); return DynamicShuffleArray($newArray);
    //case "Mid": return DynamicShuffleArray(array("CrouchingTiger", "Sword"));
    case "Mid": $newArray = array_slice($generatedTagPool, $countOffset, -$countOffset); return DynamicShuffleArray($newArray);
    //case "Low": return DynamicShuffleArray(array("Nimble", "Sloggish"));
    case "Low": $newArray = array_slice($generatedTagPool, 0, $countOffset); return DynamicShuffleArray($newArray);
    case "No": $newArray = $encounter->notInTags; return DynamicShuffleArray($newArray); //TODO: implement encounter->notInTags
  }
}

function DynamicShuffleArray($array) //This function exists solely for the readability of the above function
{
  shuffle($array);
  return $array;
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

function DynamicGetRemoved($tag)
{
  $encounter = &GetZone(1, "Encounter");
  for($i = 0; $i < count($encounter->tags); ++$i)
  {
    if($encounter->tags[$i]->tag == $tag) return $encounter->tags[$i]->removed;
  }
}

function WriteLogArray($array)
{
  WriteLog("[".implode(", ", $array)."]");
}
 ?>
