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
    //AddDecisionQueue("CHOOSECARD", $i, $booster);
    //AddDecisionQueue("DRAFTPASS", $i, "L");
  /*
    $health = &GetZone($i, "Health");
    array_push($health, 20);//TODO: Base on hero health
    $character = &GetZone($i, "Character");
    //$character = explode(" ", "UPR044 WTR078 WTR078 WTR079 WTR150 UPR158 CRU053");//TODO: Support multiple heroes
    $character = explode(" ", "DVR001 DVR002 WTR156");//TODO: Support multiple heroes
    $deck = &GetZone($i, "Deck");
    $deck = explode(" ", "WTR129 WTR145 WTR201 ARC205 CRU093 MON116 MON283 DVR019 DVR022 DVR009 DVR024 CRU186");//TODO: Support multiple heroes
    */
    $encounter = &GetZone($i, "Encounter");
    if(false) //set to false to start in the new encounter start
    {
      array_push($encounter, 1);
      array_push($encounter, "Fight");
    }
    else if(true) //this is what I am currently working in
    {
      array_push($encounter, 001);
      array_push($encounter, "PickMode");
      array_push($encounter, 1);
      array_push($encounter, "Dorinthea");
      array_push($encounter, "Ira");
      array_push($encounter, "none");
      array_push($encounter, 1);
      array_push($encounter, "none");
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

    ResetHero(1);

  }

function ResetHero($player, $hero="Dorinthea")
  {
  $heroFileArray = file("Heroes/" . $hero . ".txt", FILE_IGNORE_NEW_LINES);
  $health = &GetZone($player, "Health");
  array_push($health, 20); //TODO: Base on hero health
  $character = &GetZone($player, "Character");
  $character = explode(" ", $heroFileArray[0]); //TODO: Support multiple heroes
  $deck = &GetZone($player, "Deck");
  $deck = explode(" ", $heroFileArray[1]); //TODO: Support multiple heroes
  $encounter = &GetZone($player, "Encounter");
  $encounter[3] = $hero;
  }

  include 'WriteGamestate.php';

  header("Location: NextEncounter.php?gameName=$gameName&playerID=1");
?>
