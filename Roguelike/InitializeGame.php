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
    $health = &GetZone($i, "Health");
    array_push($health, 20);//TODO: Base on hero health
    $character = &GetZone($i, "Character");
    $character = explode(" ", "DVR001 DVR002 WTR156");//TODO: Support multiple heroes
    $deck = &GetZone($i, "Deck");
    $deck = explode(" ", "WTR129 WTR145 WTR201 ARC205 CRU093 MON116 MON283 DVR019 DVR022 DVR009 DVR024 CRU186");//TODO: Support multiple heroes
    $encounter = &GetZone($i, "Encounter");
    if(true)
    {
      array_push($encounter, 1);
      array_push($encounter, "Fight");
    }
    else
    {
      array_push($encounter, 10);
      array_push($encounter, "PickMode");
      array_push($encounter, 1);
      //array_push($encounter, "");
      InitializeEncounter($i, $encounter[0], "-");
    }

  }

  include 'WriteGamestate.php';

  header("Location: NextEncounter.php?gameName=$gameName&playerID=1");
?>
