<?php

function PlayAlly($cardID, $player, $subCards="-")
{
  $allies = &GetAllies($player);
  array_push($allies, $cardID);
  array_push($allies, 2);
  array_push($allies, AllyHealth($cardID));
  array_push($allies, 0);//Frozen
  array_push($allies, $subCards);//Subcards
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

function AllyHealth($cardID)
{
  switch($cardID)
  {
    case "MON219": return 6;
    case "MON220": return 6;
    case "UPR408": return 4;
    default: return 1;
  }
}

?>
