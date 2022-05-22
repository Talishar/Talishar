<?php

function MZFreeze($target)
{
  global $currentPlayer;
  $pieces = explode("-", $target);
  $player = (substr($pieces[0], 0, 2) == "MY" ? $currentPlayer : ($currentPlayer == 1 ? 2 : 1));
  $zone = &GetMZZone($player, $pieces[0]);
  switch($pieces[0])
  {
    case "THEIRALLY": case "MYALLY": $zone[$pieces[1]+3] = 1; break;
    case "THEIRARS": case "MYARS": $zone[$pieces[1]+4] = 1; break;
    default: break;
  }
}

?>
