<?php

function IsMeldCard($cardId): bool
{
  return match ($cardId) {
    "ROS018" => true,
    default => false,
  };
}

function IsPartOfMeldCard($cardId): bool
{
  $split = explode('-', $cardId);
  if (count($split) == 1) return false;
  return match ($split[0]) {
    "ROS018" => true,
    default => false,
  };
}

function MeldCardPartAbilityTypes($cardID): string
{
  return match ($cardID) {
    "ROS018-Left" => "A",
    "ROS018-Right" => "I",
    default => ""
  };
}

function MeldCardAbilityTypes($cardID): string
{
  return match ($cardID) {
    "ROS018" => "A,I",
    default => ""
  };
}

function PlayMeldCard($player, $cardID): void
{
  if (AreBothPartsPlayable($cardID)) {

  }
  AddDecisionQueue("SETDQCONTEXT", $player, "Choose which side to play");
  AddDecisionQueue("BUTTONINPUT", $player, "Left,Meld,Right");
  AddDecisionQueue("SHOWMODES", $player, $cardID, 1);
  AddDecisionQueue("MODAL", $player, "MELDCARD," . $cardID, 1);
}

function AreBothPartsPlayable($cardID): bool
{
  if (AreBothSidesInstant($cardID)) {
    return true;
  } elseif (IsPlayable($cardID, "P", "HAND")) {
    return true;
  }
}

function MeldCardContainsInstant($cardID): bool
{
  $types = explode(",", GetAbilityType($cardID));
  return in_array("I", $types);
}

function MeldCardContainsAction($cardID): bool
{
  $types = explode(",", GetAbilityType($cardID));
  return in_array("A", $types);
}

function AreBothSidesInstant($cardID): bool
{
  $types = explode(",", GetAbilityType($cardID));
  return $types[0] === "I" && $types[1] === "I";
}
