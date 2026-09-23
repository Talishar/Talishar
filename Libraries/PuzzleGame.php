<?php

const PUZZLE_MARKER_FILE = "puzzle.txt";

function IsPuzzleGame($gameName)
{
  return file_exists("./Games/$gameName/" . PUZZLE_MARKER_FILE);
}

function PreparePuzzleGamestate($content, $player, $p1Key, $p2Key, $emptyOpponentHand, $removeDecks)
{
  $lines = explode("\r\n", $content);
  $numChainLinks = intval(trim($lines[56] ?? "0"));
  $opponentOffset = $player == 1 ? 18 : 0;
  if ($emptyOpponentHand) {
    $lines[1 + $opponentOffset] = "";
    $lines[5 + $opponentOffset] = "";
  }
  if ($removeDecks) {
    $lines[2] = "";
    $lines[20] = "";
  }
  $lines[18] = "";
  $lines[36] = "";
  $lines[58 + $numChainLinks] = $p1Key;
  $lines[59 + $numChainLinks] = $p2Key;
  $lines[74 + $numChainLinks] = "0";
  $lines[75 + $numChainLinks] = "0";
  return implode("\r\n", $lines);
}
