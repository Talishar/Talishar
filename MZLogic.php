<?php

function MZFreeze($target)
{
  global $currentPlayer;
  $pieces = explode("-", $target);
  $player = (substr($pieces[0], 0, 2) == "MY" ? $currentPlayer : ($currentPlayer == 1 ? 2 : 1));
  $zone = &GetMZZone($player, $pieces[0]);
  switch($pieces[0])
  {
    case "THEIRCHAR": case "MYCHAR": $zone[$pieces[1]+8] = 1; break;
    case "THEIRALLY": case "MYALLY": $zone[$pieces[1]+3] = 1; break;
    case "THEIRARS": case "MYARS": $zone[$pieces[1]+4] = 1; break;
    default: break;
  }
}

function IsFrozenMZ(&$array, $zone, $i)
{
  switch($zone)
  {
    case "ARS": $offset = 4; break;
    case "ALLY": $offset = 3; break;
    case "CHAR": $offset = 8; break;
    default: $offset = -1;
  }
  if($offset == -1) return false;
  return $array[$i+$offset] == "1";
}

?>
