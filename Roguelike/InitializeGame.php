<?php

  include '../Libraries/HTTPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include "ZoneGetters.php";
  include "../WriteLog.php";
  //include "EncounterDictionary.php";

  for($i=1; $i<=$numPlayers; ++$i)
  { //this is what I am currently working in
    $encounter = &GetZone($i, "Encounter");
    $encounter  = array(001, "PickMode", 0, "Dorinthea", "Ira", "none", 1, "none", "Normal", 0, 0, 1, 1);
    //See EncounterDictionary for explanations for encounter variable
    InitializeEncounter($i);

    ResetHero(1); //Defined in DecisionQueue.php

  }

  include 'WriteGamestate.php';

  header("Location: NextEncounter.php?gameName=$gameName&playerID=1");
?>
