<?php

  error_reporting(E_ALL);

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $remainingHealth=$_GET["health"];

  //First we need to parse the game state from the file
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "DecisionQueue.php";
  include "../WriteLog.php";

  $health = &GetZone($playerID, "Health");
  $health[0] = $remainingHealth;
  $encounter = &GetZone($playerID, "Encounter");
  $encounter[1] = "AfterFight";

  AddDecisionQueue("CHOOSECARD", $playerID, "MON110,WTR146,WTR135");
  AddDecisionQueue("SETENCOUNTER", $playerID, GetNextEncounter($encounter[0]));

  include "WriteGamestate.php";

  header("Location: " . $redirectPath . "/Roguelike/NextEncounter.php?gameName=$gameName&playerID=" . $playerID);

  exit;

  function GetNextEncounter($previousEncounter)
  {
    switch($previousEncounter)
    {
      case 1: return "4-PickMode";
      case 3: return "2-PickMode";
      case 5: return "6-PickMode";
      default: return "";
    }
  }

?>
