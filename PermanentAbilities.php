<?php

function PutPermanentIntoPlay($player, $cardID)
{
  $permanents = &GetPermanents($player);
  array_push($permanents, $cardID);
}

/*
function DestroyAlly($player, $index)
{
  $allies = &GetAllies($player);
  for($j = $index+AllyPieces()-1; $j >= $index; --$j)
  {
    unset($allies[$j]);
  }
  $allies = array_values($allies);
}
*/

?>
