<?php

function PutPermanentIntoPlay($player, $cardID)
{
  $permanents = &GetPermanents($player);
  array_push($permanents, $cardID);
}

function RemovePermanent($player, $index)
{
  $permanents = &GetPermanents($player);
  $permID = $permanents[$index];
  for($j = $index+PermanentPieces()-1; $j >= $index; --$j)
  {
    unset($permanents[$j]);
  }
  $permanents = array_values($permanents);
  return $permID;
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
