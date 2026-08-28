<?php

// Pieces() for example in replays written with the Pieces amount get read at an offsets. Throw php error logs.
function NormalizeLegacyCharacterState(array $characters): array
{
  $currentPieces = function_exists('CharacterPieces') ? CharacterPieces() : 16;
  $legacyPieces = 15;
  $count = count($characters);

  if ($count === 0 || $count % $currentPieces === 0 || $count % $legacyPieces !== 0) {
    return $characters;
  }

  $hasGetSlot = function_exists('GetSlot');
  $normalized = [];
  $numWeapons = 0;
  for ($i = 0; $i < $count; $i += $legacyPieces) {
    $end = $i + $legacyPieces;
    if ($end > $count) return $characters;
    for ($j = $i; $j < $end; ++$j) $normalized[] = $characters[$j];
    $normalized[] = $hasGetSlot ? GetSlot((string)$characters[$i], $numWeapons) : "-";
  }

  return $normalized;
}

function NormalizeCombatChainState(array $state): array
{
  global $CCS_DefenseReactionsPlayed, $CCS_AttackReactionsPlayed;

  $defenseReactionsIndex = $CCS_DefenseReactionsPlayed ?? 52;
  $attackReactionsIndex = $CCS_AttackReactionsPlayed ?? 53;

  if (!array_key_exists($defenseReactionsIndex, $state)) {
    $state[$defenseReactionsIndex] = 0;
  }
  if (!array_key_exists($attackReactionsIndex, $state)) {
    $state[$attackReactionsIndex] = 0;
  }

  return $state;
}
