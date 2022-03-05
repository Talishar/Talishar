<?php

function ProcessMacros()
{
  global $currentPlayer, $turn;
  $somethingChanged = true;
  if($turn[0] != "OVER")
  {
    for($i=0; $i<100 && $somethingChanged; ++$i)
    {
      $somethingChanged = false;
      if($turn[0] == "A" && ShouldSkipARs($currentPlayer)) { $somethingChanged = true; PassInput(); }
      else if($turn[0] == "D" && ShouldSkipDRs($currentPlayer)) { $somethingChanged = true; PassInput(); }
    }
  }
}

?>
