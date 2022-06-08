<?php

function PlayAlly($cardID, $player, $subCards="-")
{
  $allies = &GetAllies($player);
  array_push($allies, $cardID);
  array_push($allies, 2);
  array_push($allies, AllyHealth($cardID));
  array_push($allies, 0);//Frozen
  array_push($allies, $subCards);//Subcards
  array_push($allies, GetUniqueId());
  return count($allies) - AllyPieces();
}

function DestroyAlly($player, $index)
{
  $allies = &GetAllies($player);
  AllyDestroyAbility($player, $allies[$index]);
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
    case "UPR409": return 3;
    case "UPR410": return 2;
    case "UPR411": return 2;
    case "UPR413": return 7;
    case "UPR415": return 4;
    case "UPR416": return 1;
    case "UPR417": return 3;
    default: return 1;
  }
}

function AllyDestroyAbility($player, $cardID)
{
  global $mainPlayer;
  switch($cardID)
  {
    case "UPR410": if($player == $mainPlayer) GainActionPoints(1); break;
    default: break;
  }
}

?>
