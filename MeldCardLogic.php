<?php

function PlayPartMeldAbility($cardId)
{
  global $currentPlayer;
  switch ($cardId) {
    case "ROS018-Left":
      ROS018LeftAbility($currentPlayer);
      return "";
    case "ROS018-Right":
      ROS018RightAbility($currentPlayer);
      return "";
  }
}

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
  global $turn;
  if (AreBothPartsPlayable($cardID)) {
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose which side to play");
    AddDecisionQueue("BUTTONINPUT", $player, "Left,Meld,Right");
    AddDecisionQueue("SHOWMODES", $player, $cardID, 1);
    AddDecisionQueue("MODAL", $player, "MELDCARD," . $cardID, 1);
  } elseif (IsPlayable($cardID . "-Right", $turn[0], "HAND")) {
    PlayPartMeldAbility($cardID . "-Right");
  } elseif (IsPlayable($cardID . "-Left", $turn[0], "HAND")) {
    PlayPartMeldAbility($cardID . "-Left");
  }
}

function AreBothPartsPlayable($cardID): bool
{
  global $turn;
  return IsPlayable($cardID . "-Left", $turn[0], "HAND") &&
    IsPlayable($cardID . "-Right", $turn[0], "HAND");
}

function ROS018LeftAbility($player): void
{
  DealArcane(4, 2, "PLAYCARD", "ROS018", false, $player);
  GainActionPoints(-1);
}

function ROS018RightAbility($player): void
{
  GainHealth(1, $player);
}
