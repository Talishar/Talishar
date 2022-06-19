<?php

function PutPermanentIntoPlay($player, $cardID)
{
  $permanents = &GetPermanents($player);
  array_push($permanents, $cardID);
  return count($permanents) - PermanentPieces();
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

function DestroyPermanent($player, $index)
{
  $permanents = &GetPermanents($player);
  $cardID = $permanents[$index];
  $isToken = $permanents[$index+4] == 1;
  PermanentDestroyed($player, $cardID, $isToken);
  for($j = $index+PermanentPieces()-1; $j >= $index; --$j)
  {
    unset($permanents[$j]);
  }
  $permanents = array_values($permanents);
}

function PermanentDestroyed($player, $cardID, $isToken=false)
{
  $permanents = &GetPermanents($player);
  for($i=0; $i<count($permanents); $i+=PermanentPieces())
  {
    switch($permanents[$i])
    {
      default: break;
    }
  }
  $goesWhere = GoesWhereAfterResolving($cardID);
  if(CardType($cardID) == "T" || $isToken) return;//Don't need to add to anywhere if it's a token
  switch($goesWhere)
  {
    case "GY": AddGraveyard($cardID, $player, "PLAY"); break;
    case "SOUL": AddSoul($cardID, $player, "PLAY"); break;
    case "BANISH": BanishCardForPlayer($cardID, $player, "PLAY", "NA"); break;
    default: break;
  }
}

function PermanentEndTurnAbilities()
{
  global $mainClassState, $CS_NumNonAttackCards, $mainPlayer;
  $permanents = &GetPermanents($mainPlayer);
  for($i=count($permanents)-PermanentPieces(); $i>=0; $i-=PermanentPieces())
  {
    $remove = 0;
    switch($permanents[$i])
    {
      case "UPR439": case "UPR440": case "UPR441": $remove = 1; break;
      default: break;
    }
    if($remove == 1) DestroyPermanent($mainPlayer, $i);
  }
}

function PermanentTakeDamageAbilities($player, $damage, $type)
{
  $permanents = &GetPermanents($player);
  for($i=count($permanents)-PermanentPieces(); $i>=0; $i-=PermanentPieces())
  {
    $remove = 0;
    if($damage <= 0) { $damage = 0; break; }
    switch($permanents[$i])
    {
      case "UPR439": $damage -= 4; $remove = 1; break;
      case "UPR440": $damage -= 3; $remove = 1; break;
      case "UPR441": $damage -= 2; $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      DestroyPermanent($player, $i);
    }
  }
  return $damage;
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
