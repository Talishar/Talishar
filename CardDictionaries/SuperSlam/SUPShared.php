<?php

function SUPAbilityType($cardID): string
{
  return match ($cardID) {
    "lyath_goldmane" => "I",
    "lyath_goldmane_vile_savant" => "I",
    "kayo_underhanded_cheat" => "I",
    "kayo_strong_arm" => "I",
    "tuffnut" => "I",
    "tuffnut_bumbling_hulkster" => "I",
    "pleiades_superstar" => "I",
    "pleiades" => "I",
    default => ""
  };
}

function SUPAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "lyath_goldmane" => 2,
    "lyath_goldmane_vile_savant" => 2,
    "kayo_underhanded_cheat" => 4,
    "kayo_strong_arm" => 4,
    default => 0
  };
}

function SUPAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SUPEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    default => 0,
  };
}

function SUPCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SUPPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $mainPlayer, $combatChainState, $CCS_LinkBasePower, $combatChain;
  switch ($cardID) {
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
      BOO($currentPlayer);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "kayo_underhanded_cheat":
    case "kayo_strong_arm":
      if ($currentPlayer == $mainPlayer) {
        //check to make sure they targeted the current chain link
        $combatChainState[$CCS_LinkBasePower] = 6;
      }
      else {
        $targetIndex = intval(explode("-", $target)[1]);
        CombatChainPowerModifier($targetIndex, 6 - $combatChain[$targetIndex + 5]);
      }
      break;
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
      $deck = new Deck($currentPlayer);
      $top = $deck->Top(true);
      Pitch($top, $currentPlayer);
      if (ModifiedPowerValue($top, $currentPlayer, "DECK") >= 6) {
        Cheer($currentPlayer);
      }
      break;
    case "pleiades":
    case "pleiades_superstar":
      //put a suspense counter on an aura of suspense you control
      break;
    case "comback_kid_red": //I'm going to try be default to be consistent in coding attack triggers as triggers
    case "mocking_blow_red":
    case "bully_tactics_red":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      break;
    default:
      break;
  }
  return "";
}

function SUPHitEffect($cardID): void
{
  switch ($cardID) {
    default:
      break;
  }
}

function BOO($player)
{
  global $CS_BooedThisTurn;
  SetClassState($player, $CS_BooedThisTurn, 1);
  $char = GetPlayerCharacter($player);
  WriteLog("BOOOOO! The crowd jeers at " . CardLink($char[0], $char[0]) . "!");
  switch($char[0]) {
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
    case "kayo_underhanded_cheat":
    case "kayo_strong_arm":
      AddLayer("TRIGGER", $player, $char[0]);
      break;
    default:
      break;
  }
}

function Cheer($player)
{
  global $CS_CheeredThisTurn;
  SetClassState($player, $CS_CheeredThisTurn, 1);
  $char = GetPlayerCharacter($player);
  WriteLog("Let's go! The crowd cheers for " . CardLink($char[0], $char[0]) . "!");
  switch($char[0]) {
    case "pleiades":
    case "pleiades_superstar":
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
      AddLayer("TRIGGER", $player, $char[0]);
      break;
    default:
      break;
  }
}

function GetSuspenseAuras()
{
  return [];
}

function RemoveSuspense()
{

}

function AddSuspense()
{

}