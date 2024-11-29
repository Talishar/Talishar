<?php

function HNTAbilityType($cardID): string
{
  return match ($cardID) {
    "HNT054" => "I",
    "HNT055" => "I",
    "HNT167" => "I",
    default => ""
  };
}

function HNTAbilityCost($cardID): int
{
  global $currentPlayer, $mainPlayer;
  return match ($cardID) {
    "HNT054" => 3 - ($mainPlayer == $currentPlayer ? NumDraconicChainLinks() : 0),
    "HNT055" => 3 - ($mainPlayer == $currentPlayer ? NumDraconicChainLinks() : 0),
    "HNT167" => 0,
    default => 0
  };
}

function HNTAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function HNTEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    default => 0,
  };
}

function HNTCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    "HNT116" => true,
    "HNT167" => true,
    default => false,
  };
}

function HNTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $mainPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "HNT054":
    case "HNT055":
      RecurDagger($currentPlayer, 0);
      RecurDagger($currentPlayer, 1);
      break;
    case "HNT116":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "HNT165":
      $otherchar = &GetPlayerCharacter($otherPlayer);
      MarkHero($otherPlayer);
      if (CardNameContains($otherchar[0], "Arakni")) {
        GainResources($currentPlayer, 1);
      }
      break;
    case "HNT167":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    default:
      break;
  }
  return "";
}

function MarkHero($player): string
{
  WriteLog($player . " is now marked!");
  $character = &GetPlayerCharacter($player);
  $character[13] = 1;
  return "";
}

function CheckMarked($player): bool
{
  $character = &GetPlayerCharacter($player);
  return $character[13] == 1;
}

function RemoveMark($player)
{
  $character = &GetPlayerCharacter($player);
  $character[13] = 0;
}

function RecurDagger($player, $mode) //$mode == 0 for left, and 1 for right
{
  $weapons = "";
  $char = &GetPlayerCharacter($player);
  $graveyard = &GetDiscard($player);
  if ($char[CharacterPieces() * ($mode + 1) + 1] == 0) { //Only Equip if there is a broken weapon/off-hand
    foreach ($graveyard as $cardID) {
      if (TypeContains($cardID, "W", $player) && SubtypeContains($cardID, "Dagger")) {
        if (TalentContains($cardID, "DRACONIC")) {
          if ($weapons != "") $weapons .= ",";
          $weapons .= $cardID;
        }
      };
    }
    if ($weapons == "") {
      WriteLog("Player " . $player . " doesn't have any dagger in their graveyard");
      return;
    }
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a dagger to equip");
    AddDecisionQueue("CHOOSECARD", $player, $weapons);
    AddDecisionQueue("EQUIPCARDGRAVEYARD", $player, "<-");
  }
}