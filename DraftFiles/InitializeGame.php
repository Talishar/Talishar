<?php

  include '../Libraries/HTTPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include 'BoosterGenerator.php';
  include "ZoneGetters.php";

  for($i=1; $i<=$numPlayers; ++$i)
  {
    $booster = implode(",", explode(" ", GenerateELEBooster()));
    AddDecisionQueue("CHOOSECARD", $i, $booster);
    AddDecisionQueue("DRAFTPASS", $i, "L");
    $packData = &GetZone($i, "PackData");
    array_push($packData, "1");//Pack 1
    array_push($packData, "1");//Pick 1
  }

  include 'WriteGamestate.php';

  header("Location: NextPick.php?gameName=$gameName&playerID=1");
?>