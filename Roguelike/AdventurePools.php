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
            case 1: return CrossroadsDoubleChoice("Make_your_way_up_through_Metrix,Take_the_scenic_route_through_the_back_streets,Catch_a_ferry_across_the_lake");//combat choice of X, Y, and Z
            case 2: return RandomEvent();
            case 3: return CrossroadsDoubleChoice("Turn_back_and_take_the_long_way_around,Approach_the_roadblock_head_on,Venture_into_the_forest_and_attempt_to_sneak_past");//combat choice of X, Y, and Z
            case 4: return RandomEvent();
            case 5: return CrossroadsDoubleChoice("Ignore_your_instincts_and_stop_for_the_night,Stay_very_briefly_to_stock_up,Leave_the_town_immediately");//combat choice of X, Y, and Z
            case 6: return RandomEvent();
            case 7: return "Go_towards_the_smoke_rising_in_the_distance,Follow_the_sounds_of_laughter"; //Campfire or a shop
            case 8: return "Explore_the_cave"; //Elite
            case 9: return "Search_through_the_treasures"; //Get a power
            case 10: return CrossroadsDoubleChoice("Attempt_to_cross_the_river_here,Travel_downstream_to_find_a_bridge,Travel_upstream_to_the_nearest_town");//combat choice of X, Y, and Z
            case 11: return RandomEvent();
            case 12: return CrossroadsDoubleChoice("You_notice_a_mountain_pass_you_can_move_through,A_river_flows_nearby_for_you_to_travel_alongside,A_nearby_town_could_provide_some_directions");//combat choice of X, Y, and Z
            case 13: return RandomEvent();
            case 14: return CrossroadsDoubleChoice("Use_the_cover_of_darkness_to_cross,Take_precaution_and_find_a_different_way_across,Brave_the_bridge");//combat choice of X, Y, and Z
            case 15: return RandomEvent();
            case 16: return "Go_towards_the_smoke_rising_in_the_distance,Follow_the_sounds_of_laughter"; //Campfire or a shop
            case 17: return "Approach_your_destination"; //Boss
          }
      }
  }
}

function RandomEvent()
{
  $commonEvents = array("Explore_some_nearby_ruins", "Visit_a_local_library", "Enter_a_nearby_Temple");
  $rareEvents = array("Explore_some_nearby_ruins", "Enter_a_nearby_Temple");
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

function CrossroadsDoubleChoice($string)
{
  $choices = explode(",", $string);
  $options = GetOptions(2, count($choices)-1);
  return $choices[$options[0]].",".$choices[$options[1]];
}

function GetCrossroadsDescription()
{
  $encounter = &GetZone(1, "Encounter");
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
            case 1: return "Your destination lies beyond the Pits. How would you like to leave?";
            case 3: return "Ahead of you lies a fallen tree. It likely did not fall naturally. What would you like to do?";
            case 5: return "You find yourself in a small town. Something about this town feels unnatural. It unsettles you. What would you like to do?";
            case 7: return "You come to a crossroads. In one direction, you see smoke rising in the distance. In the other, you hear the distant sounds of laughter. Which way would you like to go?";
            case 8: return "Off to the side of the road is a small cave. You hear a roar echo from inside. Perhaps there's some gold inside, it's certainly worth checking out.";
            case 9: return "With the great beast felled, you see a pile of treasures within the cave.";
            case 10: return "You come upon a great river. It's too wide to cross on your own. What would you like to do?";
            case 12: return "Before you lies the mountains of Misteria, and within them your goal. Where would you like to go?";
            case 14: return "As you travel through the mountains, you come upon a rope bridge connecting the mountains. You can sense the end of your journey is approaching. A hooded figure stands on the bridge, waiting.";
            default: return "You come to a crossroads. Which way would you like to go?";
          }
      }
  }
}

function GetCrossroadsImage()
{
  {
    $encounter = &GetZone(1, "Encounter");
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
              case 1: return "CRU122_cropped.png";
              case 3: return "CRU006_cropped.png";
              case 5: return "ELE227_cropped.png";
              case 8: return "RVD025_cropped.png";
              case 9: return "EVR184_cropped.png";
              case 10: return "MON240_cropped.png";
              case 12: return "CRU075_cropped.png";
              case 14: return "WTR092_cropped.png";
              default: return "EVR100_cropped.png";

            }
        }
    }
  }
}
?>
