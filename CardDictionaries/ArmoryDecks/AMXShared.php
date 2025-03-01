<?php

function AMXAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "bank_breaker" => "AA",
    default => ""
  };
}

function AMXAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AMXEffectAttackModifier($cardID): int
{
  global $currentPlayer, $defPlayer;
  return match ($cardID) {
    default => 0
  };
}

function AMXCombatEffectActive($cardID, $attackID): bool
{
  return match($cardID) {
    default => false
  };
}

function AMXAbilityCost($cardID): int
{
  return match($cardID) {
    default => 0
  };
}

function AMXPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $defPlayer, $combatChain, $CS_CharacterIndex;

  switch ($cardID) {
    case "bank_breaker":
      $character = &GetPlayerCharacter($currentPlayer);
      $index = GetClassState($currentPlayer, $CS_CharacterIndex);
      if (count(explode(",", $character[$index + 10])) > 0) {
        CharacterChooseSubcard($currentPlayer, $index, isMandatory: false);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "EQUIP,-", 1);
        AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, 1);
        AddDecisionQueue("GIVEATTACKOVERPOWER", $currentPlayer, 1);
      }
      return "";
    case "clamp_press_blue":
      if ($currentPlayer == $defPlayer) {
        for ($j = CombatChainPieces(); $j < count($combatChain); $j += CombatChainPieces()) {
          if ($combatChain[$j + 1] != $currentPlayer) continue;
          if (CardType($combatChain[$j]) == "AA" && ClassContains($combatChain[$j], "MECHANOLOGIST", $currentPlayer)) CombatChainPowerModifier($j, 1);
        }
      }
      return "";
    default:
      return "";
  }
}
