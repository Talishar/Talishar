<?php

include_once __DIR__ . "/PuzzleVerify.php";

const PUZZLE_SCRIPT_FILE = "puzzleScript.json";

// The attacker of a survive puzzle replays its recorded inputs. A recorded input whose situation is gone is
// skipped, an extra priority window the defender opened is passed, and a prompt the recording never saw is
// answered by the bot.

function PuzzleScriptPath($gameName)
{
  return __DIR__ . "/../Games/$gameName/" . PUZZLE_SCRIPT_FILE;
}

function ReadPuzzleScript($gameName)
{
  $script = json_decode((string)@file_get_contents(PuzzleScriptPath($gameName)), true);
  return is_array($script) && is_array($script["line"] ?? null) ? $script : null;
}

function WritePuzzleScript($gameName, $player, $line, $next = 0)
{
  file_put_contents(PuzzleScriptPath($gameName), json_encode(["player" => intval($player), "line" => $line, "next" => $next]), LOCK_EX);
}

function ResetPuzzleScript($gameName)
{
  $script = ReadPuzzleScript($gameName);
  if ($script !== null) WritePuzzleScript($gameName, $script["player"], $script["line"]);
}

function PuzzleScriptPlayer()
{
  global $gameName;
  static $players = [];
  return $players[$gameName ?? ""] ??= file_exists(PuzzleScriptPath($gameName ?? ""))
    ? intval(ReadPuzzleScript($gameName)["player"] ?? 0) : 0;
}

function PuzzleLiveSignature()
{
  global $turn, $combatChain, $chainLinks;
  return ($turn[0] ?? "") . "|" . count($chainLinks ?? []) . "|" . ($combatChain[0] ?? "");
}

function PuzzleScriptInput($player, $command)
{
  [$mode, $button, $card, $chkCount, $chk] = $command;
  $card = RemapPuzzleAnswers($player, $card, $command[6] ?? []);
  if ($mode == 27) {
    $hand = &GetHand($player);
    if (($hand[intval($card)] ?? "") !== $button) {
      $index = array_search($button, $hand, true);
      if ($index !== false) $card = (string)$index;
    }
    $button = $hand[intval($card)] ?? "";
    unset($hand);
  }
  ProcessInput($player, $mode, $button, $card, $chkCount, $chk, false, "");
}

function PuzzleScriptFallback($player)
{
  global $decisionQueue, $turn, $EffectContext;
  if (count($decisionQueue) == 0) {
    PassInput();
    return;
  }
  $isYesNo = $turn[0] == "YESNO" || $turn[0] == "DOCRANK";
  $options = $isYesNo ? ["YES", "NO"] : explode(",", $turn[2] ?? "");
  $choice = BotChooseDecisionOption($turn[0], $options, $player, $isYesNo ? ($turn[2] ?? "") : ($EffectContext ?? ""));
  if ($turn[0] == "CHOOSEDECK" || $turn[0] == "MAYCHOOSEDECK") {
    $deck = &GetDeck($player);
    $choice = $deck[$choice * DeckPieces()];
  }
  ContinueDecisionQueue($choice);
}

// One input for the scripted player, so the caller's loop always makes progress.
function PuzzleScriptAct()
{
  global $gameName, $turn, $layers;
  $script = ReadPuzzleScript($gameName);
  if ($script === null) {
    PassInput();
    return;
  }
  $player = intval($script["player"]);
  $line = $script["line"];
  $next = intval($script["next"] ?? 0);
  while (true) {
    $command = $line[$next] ?? null;
    if ($command === null) {
      PuzzleScriptFallback($player);
      break;
    }
    $mode = $command[0];
    if ($mode === "SETTINGS") {
      ++$next;
      PuzzleScriptInput($player, $command);
      break;
    }
    if ($mode === "OPT" || $mode === "REORDER") {
      ++$next;
      $applies = $mode === "OPT" ? ($turn[0] ?? "") === "OPT" : in_array("PRETRIGGER", $layers ?? [], true);
      if (!$applies) continue;
      PuzzleScriptInput($player, $command);
      break;
    }
    $phase = $turn[0] ?? "";
    $recordedPhase = explode("|", $command[5])[0];
    $samePhase = $recordedPhase === $phase && !isset(PUZZLE_SAFE_PASS_PHASES[$phase]);
    if ($command[5] === "" || $command[5] === PuzzleLiveSignature() || $samePhase) {
      ++$next;
      PuzzleScriptInput($player, $command);
      break;
    }
    if ($mode == 99 || !isset(PUZZLE_FLOW_PHASES[$recordedPhase]) || $phase === "M") {
      ++$next;
      continue;
    }
    if (isset(PUZZLE_SAFE_PASS_PHASES[$phase])) PassInput();
    else PuzzleScriptFallback($player);
    break;
  }
  WritePuzzleScript($gameName, $player, $line, $next);
}
