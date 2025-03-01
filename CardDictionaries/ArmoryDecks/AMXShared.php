<?php

function AMXAbilityType($cardID, $index = -1, $from = "-"): string
{
  global $currentPlayer, $CS_NumCranked;
  return match ($cardID) {
    "bank_breaker" => GetClassState($currentPlayer, $CS_NumCranked) > 0 ? "AA" : "",
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
    "bank_breaker" => true,
    default => false
  };
}

function AMXAbilityCost($cardID): int
{
  return match($cardID) {
    "bank_breaker" => 1,
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
      $character[$index + 10] = "cracked_bauble_yellow";
      if (count(explode(",", $character[$index + 10])) > 0 && $character[$index + 10] != "-") {
        CharacterChooseSubcard($currentPlayer, $index, isMandatory:false);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "EQUIP,-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "bank_breaker", 1);
      }
      return "";
    case "clamp_press_blue":
      // penetration script has something here, and I don't know why
      // leaving it blank for now
      return "";
    default:
      return "";
  }
}
