<?php

/*
Encounter variable
encounter[0] = Encounter ID (001-099 Special Encounters | 101-199 Combat Encounters | 201-299 Event Encounters)
encounter[1] = Encounter Subphase
encounter[2] = Position in adventure
encounter[3] = Hero ID
encounter[4] = Adventure ID
encounter[5] = A string made up of encounters that have already been visited, looks like "ID-subphase,ID-subphase,ID-subphase,etc."
encounter[6] = majesticCard% (1-100, the higher it is, the more likely a majestic card is chosen) (Whole code is based off of the Slay the Spire rare card chance)
encounter[7] = background chosen
encounter[8] = adventure difficulty (to be used later)
encounter[9] = current gold
encounter[10] = rerolls remaining //TODO: Add in a reroll system
*/

function GetNextEncounter() //TODO overhaul this whole function and children
{
  $encounter = &GetZone(1, "Encounter");
  // WriteLog("hijacked GetNextEncounter");
  // WriteLog("Encounter[0]: " . $encounter[0]);
  // WriteLog("Encounter[1]: " . $encounter[1]);
  // WriteLog("Encounter[2]: " . $encounter[2]);
  ++$encounter[2];
  switch($encounter[4])
  {
    case "Ira":
      switch($encounter[8])
      {
        case "Easy":
        case "Normal":
        case "Hard":
          switch($encounter[2])
          {
            case 1: return "Go_towards_the_smoke_rising_in_the_distance,Follow_the_sounds_of_laughter";//combat choice of X, Y, and Z
            case 2: return RandomEvent();
            case 3: //combat choice of X, Y, and Z
            case 4: return RandomEvent();
            case 5: //combat choice of X, Y, and Z
            case 6: return RandomEvent();
            case 7: return "Go_towards_the_smoke_rising_in_the_distance,Follow_the_sounds_of_laughter"; //Campfire or a shop
            case 8: return "Travel_through_the_mountain_pass"; //Elite
            case 9: return "Loot_the_lair"; //Get a power
            case 10: //combat choice of X, Y, and Z
            case 11: return RandomEvent();
            case 12: //combat choice of X, Y, and Z
            case 13: return RandomEvent();
            case 14: //combat choice of X, Y, and Z
            case 15: return RandomEvent();
            case 16: return "Go_towards_the_smoke_rising_in_the_distance,Follow_the_sounds_of_laughter"; //Campfire or a shop
            case 17: return "Approach_your_destination"; //Elite
          }
      }
  }
}

function RandomEvent()
{
  $commonEvents = array("Explore_some_nearby_ruins", "Visit_a_local_library", "Enter_a_nearby_Temple");
  $rareEvents = array("Explore_some_nearby_ruins", "Visit_a_local_library");
  $majesticEvents = array("Explore_some_nearby_ruins", "Visit_a_local_library");
  $randEvent = rand(1,100);
  if($randEvent > 90)
  {
    $options = GetOptions(2, count($majesticEvents)-1);
    return $majesticEvents[$options[0]] . "," . $majesticEvents[$options[1]];
  }
  else if($randEvent > 70)
  {
    $options = GetOptions(2, count($rareEvents)-1);
    return $rareEvents[$options[0]] . "," . $rareEvents[$options[1]];
  }
  else
  {
    $options = GetOptions(2, count($commonEvents)-1);
    return $commonEvents[$options[0]] . "," . $commonEvents[$options[1]];
  }
}
?>
