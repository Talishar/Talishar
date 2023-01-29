<?php

  include '../Libraries/HTTPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include "ZoneGetters.php";
  include "../WriteLog.php";
  //include "EncounterDictionary.php";

  for($i=1; $i<=$numPlayers; ++$i)
  { 
    if(false) //set to false to start in the new encounter start
    {
      array_push($encounter, 1);
      array_push($encounter, "Fight");
    }
    else if(true) //this is what I am currently working in
    {
      $encounter = &GetZone($i, "Encounter");
      $encounter  = array(001, "PickMode", 1, "Dorinthea", "Ira", "none", 1, "none");
      //See EncounterDictionary for explanations for encounter variable
      //array_push($encounter, 001);
      //array_push($encounter, "PickMode");
      //array_push($encounter, 1);
      //array_push($encounter, "Dorinthea");
      //array_push($encounter, "Ira");
      //array_push($encounter, "none");
      //array_push($encounter, 1);
      //array_push($encounter, "none");
      InitializeEncounter($i);
    }
    else
    {
      array_push($encounter, 10);
      array_push($encounter, "PickMode");
      array_push($encounter, 1);
      //array_push($encounter, "");
      InitializeEncounter($i);
    }

    ResetHero(1); //Defined in DecisionQueue.php

  }

  include 'WriteGamestate.php';

  header("Location: NextEncounter.php?gameName=$gameName&playerID=1");
?>
