<?php

function HNTAbilityType($cardID): string
{
  return match ($cardID) {
    default => ""
  };
}

function HNTAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
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
    default => false,
  };
}

function HNTPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "HNT031":
    case "HNT030":
      if (CheckMarked($currentPlayer)) {
        WriteLog("Uh oh, I am marked");
      }
      else WriteLog("Not marked");
    case "HNT165":
      $otherchar = &GetPlayerCharacter($otherPlayer);
      MarkHero($otherPlayer);
      if (CardNameContains($otherchar[0], "Arakni")) {
        GainResources($currentPlayer, 1);
      }
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