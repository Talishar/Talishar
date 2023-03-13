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
  $encounter["Subphase"] = "AfterFight";
  if ($encounter["AdventurePosition"] < 9){
    $encounter["Gold"] += 2;
  } else {
    $encounter["Gold"] += 3;
  }

  AddDecisionQueue("CHOOSECARD", $playerID, GetRandomCards(4));
  AddDecisionQueue("SETENCOUNTER", $playerID, "009-PickMode");

  include "WriteGamestate.php";

  header("Location: " . $redirectPath . "/Roguelike/NextEncounter.php?gameName=$gameName&playerID=" . $playerID);

  exit;


?>
