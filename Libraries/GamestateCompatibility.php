<?php

// Pieces() for example in replays written with the Pieces amount get read at an offsets. Throw php error logs.
function NormalizeLegacyCharacterState(array $characters): array
{
  $currentPieces = CharacterPieces();
  $legacyPieces = 15;
  $count = count($characters);

  if ($count === 0 || $count % $currentPieces === 0 || $count % $legacyPieces !== 0) {
    return $characters;
  }

  $normalized = [];
  $numWeapons = 0;
  for ($i = 0; $i < $count; $i += $legacyPieces) {
    $record = array_slice($characters, $i, $legacyPieces);
    if (count($record) !== $legacyPieces) return $characters;

    array_push($normalized, ...$record);
    $normalized[] = function_exists('GetSlot')
      ? GetSlot((string)$record[0], $numWeapons)
      : "-";
  }

  return $normalized;
}

function NormalizeCombatChainState(array $state): array
{
  global $CCS_DefenseReactionsPlayed, $CCS_AttackReactionsPlayed;

  if (!array_key_exists($CCS_DefenseReactionsPlayed, $state)) {
    $state[$CCS_DefenseReactionsPlayed] = 0;
  }
  if (!array_key_exists($CCS_AttackReactionsPlayed, $state)) {
    $state[$CCS_AttackReactionsPlayed] = 0;
  }

  return $state;
}
