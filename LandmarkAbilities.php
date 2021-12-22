<?php

function PlayLandmark($cardID, $player)
{
  global $landmarks;
  if(count($landmarks) > 0) DestroyLandmark(0);//Right now, playing a new landmark destroys the old landmark
  if(CardSubtype($cardID) != "Landmark") { WriteError($cardID . " was tried to play as a landmark, but is not a landmark."); return; }
  array_push($landmarks, $cardID);
  array_push($landmarks, $player);//The player that originally played the landmark
}

function DestroyLandmark($index)
{
  global $landmarks;
  $cardID = $landmarks[$index];
  $player = $landmarks[$index+1];
  for($j = $index+LandmarkPieces()-1; $j >= $index; --$j)
  {
    unset($landmarks[$j]);
  }
  $landmarks = array_values($landmarks);
  AddGraveyard($cardID, $player, "PLAY");
}

function LandmarkBeginEndStepAbilities()
{
  global $landmarks, $mainPlayer;
  for($i=0; $i<count($landmarks); ++$i)
  {
    switch($landmarks[$i])
    {
      case "MON000": if(SearchPitchForColor($mainPlayer, 2) >= 2) { AddCurrentTurnEffect("MON000", $mainPlayer); } break;
      default: break;
    }
  }
}

?>

