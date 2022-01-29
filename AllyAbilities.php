<?php

function PlayAlly($cardID, $player)
{
  $allies = &GetAllies($player);
  array_push($allies, $cardID);
  array_push($allies, 2);
  array_push($allies, AllyHealth($cardID));
}

function DestroyAlly($player, $index)
{
  $allies = &GetAllies($player);
  for($j = $index+AllyPieces()-1; $j >= $index; --$j)
  {
    unset($allies[$j]);
  }
  $allies = array_values($allies);
}


?>

