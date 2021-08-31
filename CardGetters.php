<?php

function GetPlayerCharacter($player)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mainCharacter, $defCharacter, $myCharacter, $theirCharacter;
  if($mainPlayerGamestateStillBuilt)
  {
    if($player == $mainPlayer) return $mainCharacter;
    else return $defCharacter;
  }
  else
  {
    if($player == $currentPlayer) return $myCharacter;
    else return $theirCharacter;
  }
}

?>

