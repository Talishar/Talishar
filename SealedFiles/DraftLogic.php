<?php

  function AllPlayersFinished()
  {
    global $numPlayers;
    for($i=1; $i<=$numPlayers; ++$i)
    {
      $count = GetZoneCount($i, "DecisionQueue", "CHOOSECARD");
      if($count > 0) return false;
    }
    return true;
  }

?>