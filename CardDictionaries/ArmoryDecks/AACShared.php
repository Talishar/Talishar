<?php

function AACAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    default => ""
  };
}

function AACAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AACEffectPowerModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    default => 0
  };
}

function AACHitEffect($cardID): void
{
  global $mainPlayer, $defPlayer;
  switch ($cardID) {
    case "meet_madness_red":
      $roll = GetRandom(1,3);
      switch ($roll) {
        case 1:
          WriteLog("<b>The madness says \"Banish a card from hand!\"</b>");
          AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
          AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to banish", 1);
          AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
          //including $cardID as the third param makes it count for contracts
          AddDecisionQueue("BANISHCARD", $defPlayer, "THEIRHAND,-,$cardID", 1);
          break;
        case 2:
          WriteLog("<b>The madness says \"banish a card from arsenal!\"</b>");
          //including $cardID as the third param makes it count for contracts
          MZMoveCard($defPlayer, "MYARS", "MYBANISH,ARS,$cardID," . $defPlayer, false);
          break;
        case 3:
          WriteLog("<b>The madness says \"banish a card from the top of your deck!\"</b>");
          $deck = new Deck($defPlayer);
          if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
          $deck->BanishTop(banishedBy:$cardID);
          break;
      }
      break;
    default:
      break;
  }
}

function AACCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match($cardID) {
    default => false
  };
}

function AACAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function AACPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  switch ($cardID) {
    default:
      return "";
  }
}
