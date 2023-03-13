<?php

  include '../Libraries/HTTPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include "ZoneGetters.php";
  include "../WriteLog.php";
  //include "EncounterDictionary.php";
 /*
  Encounter variable
  encounter["EncounterID"] = Encounter ID (001-099 Special Encounters | 101-199 Combat Encounters | 201-299 Event Encounters)
  encounter["Subphase"] = Encounter Subphase
  encounter["AdventurePosition] = Position in adventure
  encounter["Hero"] = Hero ID
  encounter["FinalBoss"] = Adventure ID
  encounter["MajesticCardPercentage"] = majesticCard% (1-100, the higher it is, the more likely a majestic card is chosen) (Whole code is based off of the Slay the Spire rare card chance)
  encounter["Background"] = background chosen
  encounter["Difficulty"] = adventure difficulty (to be used later)
  encounter["Gold"] = current gold
  encounter["Rerolls"] = rerolls remaining //TODO: Add in a reroll system
  encounter["ShopHealCost"] = How much it costs to heal at the shop
  encounter["ShopRemoveCost"] = How much it costs to remove a card from the deck through the shop
  */

  // for($i=1; $i<=$numPlayers; ++$i)
  // { //this is what I am currently working in
    $encounter = &GetZone(1, "Encounter");
      $encounter["EncounterID"] = 001;
      $encounter["Subphase"] = "PickMode"; 
      $encounter["AdventurePosition"] = 0; 
      $encounter["Hero"] = "Dorinthea";
      $encounter["FinalBoss"] = "Ira"; 
      $encounter["MajesticCardPercentage"] = 1; 
      $encounter["Background"] = "none"; 
      $encounter["Difficulty"] = "Normal"; 
      $encounter["Gold"] = 0; 
      $encounter["Rerolls"] = 0;
      $encounter["ShopHealCost"] = 1;
      $encounter["ShopRemoveCost"] = 1; 
    InitializeEncounter(1);

    ResetHero(1); //Defined in DecisionQueue.php

  //}

  include 'WriteGamestate.php';

  header("Location: NextEncounter.php?gameName=$gameName&playerID=1");
?>
