<?php

  include '../Libraries/HTTPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include 'BoosterGenerator.php';
  include "ZoneGetters.php";

  for($i=1; $i<=$numPlayers; ++$i)
  {
    $chosenCards = &GetZone($i, "ChosenCards");
    for($i=0; $i<6; ++$i)
    {
      array_push($chosenCards, GenerateUPRBooster());
    }
  }

  include 'WriteGamestate.php';

  header("Location: NextPick.php?gameName=$gameName&playerID=1");
?>
