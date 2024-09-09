<?php

function AIOAbilityType($cardID, $index = -1, $from = "-"): string
{
  return match ($cardID) {
    "AIO006" => "A",
    "AIO026" => "A",
    default => ""
  };
}

function AIOAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AIOCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    default => false
  };
}

function AIOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer, $CS_PlayIndex;
  switch ($cardID) {
    case "AIO006":
      $deck = new Deck($currentPlayer);
      for($i = 0; $i < 2 && !$deck->Empty(); ++$i) {
        $banished = $deck->BanishTop();
        if(ClassContains($banished, "MECHANOLOGIST", $currentPlayer)) GainActionPoints(1, $currentPlayer);
      }
      return "";
    case "AIO026":
      if ($from == "PLAY") {
        Draw($currentPlayer);
      }
      return "";
    default:
      return "";
  }
}