<?php
declare(strict_types=1);

function PermanentsContainCard($player, string $card): bool
{
  $permanents = GetPermanents($player);
  $count = count($permanents);
  $pieces = PermanentPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($permanents[$i] === $card) return true;
  }
  return false;
}
