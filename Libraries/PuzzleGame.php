<?php

const PUZZLE_MARKER_FILE = "puzzle.txt";
const PUZZLE_START_FILE = "puzzleStart.txt";

function IsPuzzleGame($gameName)
{
  return file_exists("./Games/$gameName/" . PUZZLE_MARKER_FILE);
}

function PuzzleIntroLog($candidateID, $opponentLife)
{
  return "<p style='background: #005900;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>"
    . "🧩 Puzzle #$candidateID: win this turn. Your opponent is at $opponentLife life.</span></p>\r\n";
}

function PreparePuzzleGamestate($content, $player, $p1Key, $p2Key, $emptyOpponentHand, $removeDecks, $opponentLife)
{
  $lines = explode("\r\n", $content);
  $healths = explode(" ", trim($lines[0]));
  $healths[$player == 1 ? 1 : 0] = $opponentLife;
  $lines[0] = implode(" ", $healths);
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
  $lines[74 + $numChainLinks] = $player == 1 ? "0" : "1";
  $lines[75 + $numChainLinks] = $player == 2 ? "0" : "1";
  $lines[76 + $numChainLinks] = "0";
  return implode("\r\n", $lines);
}

function RestartPuzzleGame($playerID)
{
  global $gameName, $filepath;
  if (!IsPuzzleGame($gameName) || IsPlayerAI($playerID)) return;
  $startFile = file_exists($filepath . PUZZLE_START_FILE) ? PUZZLE_START_FILE : "beginTurnGamestate.txt";
  $start = @file_get_contents($filepath . $startFile);
  if ($start === false) return;
  RevertGamestate($startFile);
  SetCachePiece($gameName, 14, 5); //MGS_GameStarted
  foreach (glob($filepath . "gamestateBackup_*.txt") ?: [] as $backup) @unlink($backup);
  @unlink($filepath . "preBlockBackup.txt");
  @unlink($filepath . "startChainLinkGamestate.txt");
  $healths = explode(" ", trim(explode("\r\n", $start)[0]));
  $opponentLife = intval($healths[$playerID == 1 ? 1 : 0] ?? 0);
  $candidateID = trim((string)@file_get_contents($filepath . PUZZLE_MARKER_FILE));
  FlushLogBuffer();
  file_put_contents($filepath . "gamelog.txt", PuzzleIntroLog($candidateID, $opponentLife));
  WriteLog("🧩 Puzzle restarted.");
}
