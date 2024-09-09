<?php

/*  Azalea Armory Deck METHODS
  // Ability Type
  // Ability Has Goi Again
  // Combat Effect active
  // Effect Attack Modifier
  // Play Ability
  // Hit Effect
*/

function AAZAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "AAZ004", "AAZ006" => "A",
    "AAZ005" => "I",
    "AAZ007" => "AR",
    default => ""
  };
}

function AAZAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "AAZ004", "AAZ006" => true,
    default => false
  };
}

function AAZCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    "AAZ024", "AAZ007" => CardSubType($attackID) == "Arrow",
    "AAZ004" => CardSubType($attackID) == "Arrow" && HasAimCounter(),
    "AAZ016" => true,
    default => false
  };
}

function AAZEffectAttackModifier($cardID): int
{
  return match ($cardID) {
    "AAZ024" => 3,
    default => 0
  };
}

function AAZPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  switch ($cardID) {
    case "AAZ004":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "AAZ006":
      LoadArrow($currentPlayer, "UP", 1);
      return "";
    case "AAZ007":
      GiveAttackGoAgain();
      return "";
    case "AAZ005":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      GainResources($currentPlayer, 1);
      return "";
    case "AAZ016":
      if (HasAimCounter()) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "AAZ024":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $arsenal = &GetArsenal($currentPlayer);
      for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
        if (CardSubType($arsenal[$i]) == "Arrow" && $arsenal[$i + 1] == "DOWN"){
          AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_turn_your_arsenal_face_up");
          AddDecisionQueue("NOPASS", $currentPlayer, "-");
          AddDecisionQueue("TURNARSENALFACEUP", $currentPlayer, $i, 1);
          AddDecisionQueue("ADDAIMCOUNTER", $currentPlayer, $i, 1);
        }
      }
      return "";
    default:
      return "";
  }
}

function AAZHitEffect($cardID): void
{
  global $defPlayer;
  switch ($cardID) {
    case "AAZ016":
      if (IsHeroAttackTarget() && HasAimCounter()) {
        AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
        AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to banish", 1);
        AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
        AddDecisionQueue("BANISHCARD", $defPlayer, "HAND,NTINT," . $cardID, 1);
      }
      break;
  }
}