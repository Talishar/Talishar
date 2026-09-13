<?php

function ResetGameGUIDForRematch(): string
{
  return GenerateGameGUID();
}

function StartRematch(bool $isSwapRematch = false): void
{
  global $gameName, $inGameStatus, $GameStatus_Rematch, $GameStatus_SwapRematch;
  //reset health so they don't immediately die again
  $p1Health = &GetHealth(1);
  $p1Health = 1;
  $p2Health = &GetHealth(2);
  $p2Health = 1;
  $inGameStatus = $isSwapRematch ? $GameStatus_SwapRematch : $GameStatus_Rematch;
  ClearGameFiles($gameName, true);
}
