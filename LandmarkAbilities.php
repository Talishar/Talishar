<?php

function PlayLandmark($cardID, $player, $from="-")
{
  global $landmarks;
  if (count($landmarks) > 0) DestroyLandmark(0); //Right now, playing a new landmark destroys the old landmark
  if (!SubtypeContains($cardID, "Landmark")) {
    WriteLog($cardID . " was tried to play as a landmark, but is not a landmark.", highlight: true);
    return;
  }
  array_push($landmarks, $cardID, $player, $from, 0); // cardID, owner, from, counters
  switch ($cardID) {
    case "treasure_island":
      AddCurrentTurnEffect($cardID, $player);
      break;
    case "omens_of_arcana":
      PlayAura("lightning_flow", 1);
      PlayAura("lightning_flow", 2);
      break;
    default:
      break;
  }
}

function DestroyLandmark($index, $skipDestroy=false)
{
  global $landmarks;
  $cardID = $landmarks[$index] ?? "";
  $ownerID = $landmarks[$index + 1] ?? "";
  array_splice($landmarks, $index, LandmarkPieces());
  if(!$skipDestroy) AddGraveyard($cardID, $ownerID, "PLAY");
  return $cardID;
}

function LandmarkBeginEndPhaseAbilities()
{
  global $landmarks, $mainPlayer, $CS_NumBluePlayed, $CS_NumBlueDefended;
  for ($i = 0; $i < count($landmarks); ++$i) {
    switch ($landmarks[$i]) {
      case "great_library_of_solana":
        if (SearchPitchForColor($mainPlayer, 2) >= 2) {
          AddCurrentTurnEffect("great_library_of_solana", $mainPlayer);
        }
        break;
      case "mistcloak_gully":
        $owner = $landmarks[$i + 1];
        $from  = $landmarks[$i + 2];
        if (GetClassState($owner, $CS_NumBluePlayed) > 0 && SearchPitchForColor($owner, 3) > 0 && GetClassState($owner, $CS_NumBlueDefended) > 0) {
          Transcend($owner, "MST000_inner_chi_blue", $from);
          DestroyLandmark($i, true);
        }
        elseif (GetClassState($owner, $CS_NumBluePlayed) <= 0 && SearchPitchForColor($owner, 3) <= 0 && GetClassState($owner, $CS_NumBlueDefended) <= 0) {
          DestroyLandmark($i);
        }
        break;
      default:
        break;
    }
  }
}

function LandmarkStartTurnAbilities()
{
  global $landmarks, $mainPlayer;
  $n = count($landmarks);
  for ($i = 0; $i < $n; ++$i) {
    $cardID = $landmarks[$i];
    switch ($cardID) {
      case "mistcloak_gully":
        if ($landmarks[$i + 1] != $mainPlayer) {
          AddCurrentTurnEffect($cardID, $mainPlayer);
        }
        break;
      case "treasure_island":
        AddCurrentTurnEffect($cardID, $mainPlayer);
        break;
      default:
        break;
    }
  }
}

function SearchLandmarksForID($cardID)
{
  global $landmarks;
  //there should only ever be one landmark
  if (!isset($landmarks[0])) return -1;
  return $landmarks[0] == $cardID ? 0 : -1;
}