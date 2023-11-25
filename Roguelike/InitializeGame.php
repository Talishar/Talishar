<?php

  include '../Libraries/HTTPLibraries.php';
  include '../Libraries/SHMOPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include "ZoneGetters.php";
  include "../WriteLog.php";
  include "EncounterClass.php";
  //include "EncounterDictionary.php";

  for($i=1; $i<=$numPlayers; ++$i)
  { //this is what I am currently working in
    $encounter = &GetZone($i, "Encounter");
    $encounter = new Encounter();

    //See EncounterClass for the pieces of the encounter object
    InitializeEncounter($i);

    ResetHero(1); //Defined in DecisionQueue.php

  }

  include 'WriteGamestate.php';

  header("Location: NextEncounter.php?gameName=$gameName&playerID=1");
?>
