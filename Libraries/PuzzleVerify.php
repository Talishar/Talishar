<?php

include_once __DIR__ . "/ReplayLibraries.php";
include_once __DIR__ . "/PuzzleGame.php";
include_once __DIR__ . "/FormatCodes.php";

const PUZZLE_PROOF_VERSION = 2;
const PUZZLE_SURVIVE_PROOF_VERSION = 3;
const PUZZLE_PROOF_MAX_RUNS = 6;
const PUZZLE_SPARE_RUNS = 8;
const PUZZLE_EXTRA_PASSES = 12;
const PUZZLE_SAFE_PASS_PHASES = ["A" => true, "D" => true, "INSTANT" => true];
const PUZZLE_FLOW_PHASES = ["M" => true, "A" => true, "D" => true, "B" => true, "INSTANT" => true];
const PUZZLE_REVERT_MODES = ["10000" => true, "10001" => true, "10003" => true, "100016" => true, "100018" => true,
  "100019" => true, "100022" => true];

function PuzzleStateKey($lines)
{
  $links = intval($lines[56] ?? 0);
  foreach ([11, 18, 29, 36, 63 + $links, 66 + $links, 67 + $links, 68 + $links, 70 + $links] as $index) unset($lines[$index]);
  return md5(implode("\n", $lines));
}

function PuzzleStateSignature($lines)
{
  $phase = explode(" ", trim($lines[42] ?? ""))[0];
  $attack = explode(" ", trim($lines[44] ?? ""))[0];
  return $phase . "|" . intval($lines[56] ?? 0) . "|" . $attack;
}

const PUZZLE_ZONE_LINES = ["HAND" => 1, "DECK" => 2, "ARS" => 5, "ARSENAL" => 5, "ITEMS" => 6, "AURAS" => 7,
  "DISCARD" => 8, "PITCH" => 9, "BANISH" => 10, "SOUL" => 13, "ALLY" => 16, "PERM" => 17];
const PUZZLE_HAND_PROMPTS = ["CHOOSEHAND" => true, "MAYCHOOSEHAND" => true];
const PUZZLE_PLAY_ZONES = ["27" => "MYHAND", "5" => "MYARS", "14" => "MYBANISH", "15" => "THEIRBANISH", "35" => "MYDECK",
  "36" => "MYDISCARD", "37" => "THEIRARS"];
const PUZZLE_ABILITY_ZONES = ["3" => "MYCHAR", "10" => "MYITEMS", "21" => "CC", "22" => "MYAURAS", "24" => "MYALLY",
  "34" => "MYPERM", "38" => "COMBATCHAINATTACKS"];
const PUZZLE_CARD_ANSWER_MODES = ["8" => true, "9" => true, "12" => true, "13" => true, "23" => true, "29" => true];

// Positional answers ("MYHAND-2", or a bare hand index) with the zone name and the card they pointed at.
function PuzzleAnswerRefs($lines, $player, $card, $phase)
{
  $refs = [];
  foreach (explode(",", $card) as $index => $answer) {
    if (preg_match('/^(MY|THEIR)([A-Z]+)-(\d+)$/', $answer, $match)) {
      [, $side, $zone, $position] = $match;
    } else if (isset(PUZZLE_HAND_PROMPTS[$phase]) && ctype_digit($answer)) {
      [$side, $zone, $position] = ["MY", "HAND", $answer];
    } else continue;
    if (!isset(PUZZLE_ZONE_LINES[$zone])) continue;
    $owner = $side === "MY" ? $player : 3 - $player;
    $cards = explode(" ", trim($lines[PUZZLE_ZONE_LINES[$zone] + ($owner - 1) * 18] ?? ""));
    if (isset($cards[intval($position)])) $refs[$index] = [$side . $zone, $cards[intval($position)]];
  }
  return $refs;
}

function RemapPuzzleAnswers($player, $card, $refs)
{
  if (empty($refs)) return $card;
  $answers = explode(",", $card);
  foreach ($refs as $index => [$zone, $cardID]) {
    $answer = $answers[$index] ?? "";
    $bare = !str_contains($answer, "-");
    $position = intval($bare ? $answer : explode("-", $answer)[1]);
    $cards = &GetMZZone(str_starts_with($zone, "THEIR") ? 3 - $player : $player, $zone);
    if (($cards[$position] ?? null) !== $cardID) {
      $pieces = max(1, GetMZZonePieces($zone));
      for ($i = 0, $count = count($cards); $i < $count; $i += $pieces) {
        if ($cards[$i] === $cardID) {
          $answers[$index] = $bare ? (string)$i : "$zone-$i";
          break;
        }
      }
    }
    unset($cards);
  }
  return implode(",", $answers);
}

function IsPuzzleManualMode($mode)
{
  if ($mode === "MANUALDECK") return true;
  if (!is_numeric($mode)) return false;
  $mode = intval($mode);
  return $mode == 10002 || ($mode >= 10004 && $mode <= 10020);
}

function IsPuzzleIgnoredMode($mode)
{
  if ($mode === "StartTurn") return true;
  return is_numeric($mode) && (intval($mode) >= 100000 || intval($mode) == 10023);
}

// One player's inputs for a turn, with every undone step removed and the phase each input was given in.
function ExtractPuzzleLine($gameDirectory, $winner, $turn, $turnPlayer = null)
{
  $turnPlayer ??= $winner;
  $gameDirectory = rtrim($gameDirectory, "/\\") . "/";
  $commands = @file($gameDirectory . "commandfile.txt", FILE_IGNORE_NEW_LINES);
  if (!is_array($commands)) return null;
  $start = null;
  foreach ($commands as $index => $command) {
    if (rtrim($command) === "$turnPlayer StartTurn $turn 0") $start = $index;
  }
  if ($start === null) return null;
  $pointers = array_values(array_filter(ReplayStatePointers($gameDirectory), fn($pointer) => $pointer >= $start));
  $states = [];
  $stateBefore = function ($index) use ($gameDirectory, $pointers, &$states) {
    $found = null;
    foreach ($pointers as $pointer) {
      if ($pointer > $index) break;
      $found = $pointer;
    }
    if ($found === null) return null;
    if (!array_key_exists($found, $states)) {
      $compressed = @file_get_contents(ReplayStateFilename($gameDirectory, $found));
      $state = is_string($compressed) ? @gzdecode($compressed) : false;
      $states[$found] = is_string($state) ? explode("\r\n", $state) : null;
    }
    return $states[$found];
  };

  $first = $stateBefore($start + 1);
  if ($first === null) return null;
  $path = [["key" => PuzzleStateKey($first), "command" => null]];
  for ($index = $start + 1, $count = count($commands); $index < $count; ++$index) {
    $parts = array_pad(explode(" ", rtrim($commands[$index])), 6, "");
    $mode = $parts[1];
    $after = $index + 1 < $count ? $stateBefore($index + 1) : null;
    $afterKey = $after === null ? null : PuzzleStateKey($after);
    if (isset(PUZZLE_REVERT_MODES[$mode])) {
      if ($afterKey === null) return null;
      for ($target = count($path) - 1; $target >= 0 && $path[$target]["key"] !== $afterKey; --$target);
      if ($target < 0) return null;
      $path = array_slice($path, 0, $target + 1);
      continue;
    }
    if ($parts[0] != $winner || IsPuzzleIgnoredMode($mode)) {
      $path[] = ["key" => $afterKey, "command" => null];
      continue;
    }
    if (IsPuzzleManualMode($mode)) return null;
    $before = $stateBefore($index);
    $chk = array_values(array_filter(explode("|", $parts[5]), fn($value) => trim($value) !== ""));
    $signature = $before === null ? "" : PuzzleStateSignature($before);
    $refs = $before === null ? [] : PuzzleAnswerRefs($before, $winner, $parts[3], explode("|", $signature)[0]);
    $path[] = ["key" => $afterKey, "command" => [$mode, $parts[2], $parts[3], intval($parts[4]), $chk, $signature, $refs]];
  }
  return array_values(array_filter(array_column($path, "command")));
}

// Everything below needs the engine loaded at global scope (see APIs/VerifyPuzzleCandidates.php).

function PuzzleIncludeGlobal($__file)
{
  foreach (array_keys($GLOBALS) as $__name) {
    if ($__name !== "GLOBALS" && $__name !== "__file" && $__name !== "__name") $$__name = &$GLOBALS[$__name];
  }
  include $__file;
  if (isset($lastWrittenGamestate)) $GLOBALS["lastWrittenGamestate"] = $lastWrittenGamestate;
}

function PuzzleCreateVerifyGame($gamestate, $format, $mode = "lethal", $script = null)
{
  for ($try = 0; $try < 5; ++$try) {
    $gameName = (string)random_int(900000000, 999999999);
    $directory = "./Games/$gameName/";
    if (!file_exists($directory) && @mkdir($directory, 0700, true)) break;
    $gameName = null;
  }
  if ($gameName === null) return null;
  $firstPlayer = trim(explode("\r\n", $gamestate)[39] ?? "1");
  file_put_contents($directory . "GameFile.txt", "1\r\n2\r\n5\r\n" . FormatName(intval($format)) . "\r\nprivate\r\n\r\n$firstPlayer\r\n");
  file_put_contents($directory . "gamestate.txt", $gamestate);
  file_put_contents($directory . "gamelog.txt", "");
  file_put_contents($directory . PUZZLE_MARKER_FILE, PUZZLE_VERIFY_MARKER);
  WritePuzzleInfo($gameName, ["mode" => $mode]);
  if ($script !== null) {
    include_once __DIR__ . "/PuzzleScript.php";
    WritePuzzleScript($gameName, $script["player"], $script["line"]);
  }
  $now = round(microtime(true) * 1000);
  WriteCache($gameName, "1!$now!$now!-1!-1!$now!!!0!0!0!0!$format!5!0!0");
  WriteGamestateCache($gameName, $gamestate);
  return $gameName;
}

function PuzzleDeleteVerifyGame($gameName)
{
  DeleteCache($gameName);
  foreach (glob("./Games/$gameName/*") ?: [] as $file) @unlink($file);
  @rmdir("./Games/$gameName");
}

function PuzzleBeginStep($player)
{
  if (!function_exists("ParseGamestate")) PuzzleIncludeGlobal("ParseGamestate.php");
  else ParseGamestate();
  $GLOBALS["playerID"] = $player;
  $GLOBALS["isProcessInput"] = true;
  $GLOBALS["otherPlayer"] = 3 - intval($GLOBALS["currentPlayer"]);
  $GLOBALS["skipWriteGamestate"] = false;
  $GLOBALS["mainPlayerGamestateStillBuilt"] = 0;
  $GLOBALS["makeCheckpoint"] = 0;
  $GLOBALS["makeBlockBackup"] = 0;
  $GLOBALS["MakeStartTurnBackup"] = false;
  $GLOBALS["MakeStartGameBackup"] = false;
  $GLOBALS["conceded"] = false;
  $GLOBALS["randomSeeded"] = false;
  $GLOBALS["afterResolveEffects"] = [];
  $GLOBALS["animations"] = [];
  $GLOBALS["priorEvents"] = $GLOBALS["events"] ?? [];
  $GLOBALS["events"] = [];
}

function PuzzleFinishStep()
{
  ProcessMacros();
  if ($GLOBALS["winner"] != 0 && ($GLOBALS["turn"][0] ?? "") != "YESNO") {
    $GLOBALS["inGameStatus"] = $GLOBALS["GameStatus_Over"];
    $GLOBALS["turn"][0] = "OVER";
    $GLOBALS["currentPlayer"] = 1;
    $GLOBALS["events"] = [];
  }
  CombatDummyAI();
  if ($GLOBALS["p1IsAI"] == "1" || $GLOBALS["p2IsAI"] == "1") EncounterAI();
  CacheCombatResult();
  if (!$GLOBALS["skipWriteGamestate"]) {
    DoGamestateUpdate();
    $GLOBALS["filename"] = $GLOBALS["filepath"] . "gamestate.txt";
    PuzzleIncludeGlobal("WriteGamestate.php");
  }
}

function PuzzleResolveAnswer($player, $answer, $phase)
{
  if (preg_match('/^(MY|THEIR)[A-Z]+-\d+$/', $answer)) return GetMZCard($player, $answer);
  if (ctype_digit($answer) && isset(PUZZLE_HAND_PROMPTS[$phase])) return GetHand($player)[intval($answer)] ?? $answer;
  return $answer;
}

function PuzzlePromptStep($step)
{
  global $dqState;
  $prompt = trim(strip_tags(GamestateUnsanitize($dqState[4] ?? "-")));
  if ($prompt !== "" && $prompt !== "-") $step["prompt"] = $prompt;
  return $step;
}

function PuzzleChoiceStep($player, $answers, $phase)
{
  $answers = array_map(fn($answer) => PuzzleResolveAnswer($player, trim((string)$answer), $phase), $answers);
  $answers = array_values(array_filter($answers, fn($answer) => $answer !== ""));
  $cards = array_filter($answers, fn($answer) => GeneratedCardName($answer) !== "");
  return PuzzlePromptStep(count($answers) > 0 && count($cards) == count($answers)
    ? ["kind" => "CHOOSE", "cards" => $answers]
    : ["kind" => "CHOOSE", "text" => GamestateUnsanitize(implode(", ", $answers))]);
}

function PuzzleCardList($list)
{
  return array_values(array_filter(explode(",", $list), fn($cardID) => $cardID !== ""));
}

// What the solver did with one input, in terms a player can follow, or null when it is not a decision.
function PuzzleDescribeInput($player, $mode, $button, $card, $chk)
{
  global $turn;
  $phase = $turn[0] ?? "";
  $mode = (string)$mode;
  if (isset(PUZZLE_PLAY_ZONES[$mode])) {
    $zone = PUZZLE_PLAY_ZONES[$mode];
    $cards = [GetMZCard($player, "$zone-" . intval($card))];
    if ($phase === "P") return ["kind" => "PITCH", "cards" => $cards];
    if ($phase === "B") return ["kind" => "BLOCK", "cards" => $cards, "target" => $GLOBALS["combatChain"][0] ?? ""];
    return $zone === "MYHAND" ? ["kind" => "PLAY", "cards" => $cards] : ["kind" => "PLAY", "cards" => $cards, "from" => $zone];
  }
  if (isset(PUZZLE_ABILITY_ZONES[$mode]) || $mode === "25") {
    $cardID = $mode === "25" ? ($GLOBALS["landmarks"][intval($card)] ?? "")
      : GetMZCard($player, PUZZLE_ABILITY_ZONES[$mode] . "-" . intval($card));
    if ($phase === "B") return ["kind" => "BLOCK", "cards" => [$cardID], "target" => $GLOBALS["combatChain"][0] ?? ""];
    return ["kind" => "ACTIVATE", "cards" => [$cardID]];
  }
  if (isset(PUZZLE_CARD_ANSWER_MODES[$mode])) return PuzzleChoiceStep($player, [$button], $phase);
  switch ($mode) {
    case "99":
      return isset(PUZZLE_FLOW_PHASES[$phase]) || $phase === "P" ? null : PuzzlePromptStep(["kind" => "DECLINE"]);
    case "7":
    case "17":
      return PuzzleChoiceStep($player, [$button], $phase);
    case "16":
      return PuzzleChoiceStep($player, [$card], $phase);
    case "11":
      return PuzzleChoiceStep($player, [($phase === "CHOOSETHEIRDECK" ? "THEIRDECK-" : "MYDECK-") . intval($card)], $phase);
    case "19":
      if ($phase === "CHOOSEMULTIZONE" || $phase === "MAYCHOOSEMULTIZONE") {
        $options = explode(",", $turn[2] ?? "");
        $offset = count(array_filter(array_slice($options, 0, 2), fn($option) => preg_match('/^(MAXCOUNT|MINCOUNT)-/', $option)));
      } else {
        $options = explode(",", explode("-", $turn[2] ?? "")[1] ?? "");
        $offset = 0;
      }
      return PuzzleChoiceStep($player, array_map(fn($index) => $options[intval($index) + $offset] ?? "", $chk), $phase);
    case "20":
    case "115":
      return PuzzlePromptStep(["kind" => "CHOOSE", "text" => $mode === "20" && $button === "YES" ? "Yes" : "No"]);
    case "OPT":
      return ["kind" => "OPT", "top" => PuzzleCardList($button), "bottom" => PuzzleCardList($card)];
    case "REORDER":
      return ["kind" => "ORDER", "cards" => PuzzleCardList($button)];
  }
  return null;
}

function PuzzleAddStep(&$steps, $step)
{
  $last = count($steps) - 1;
  if ($step["kind"] === "PITCH" && $last >= 0 && $steps[$last]["kind"] === "PITCH") {
    $steps[$last]["cards"] = array_merge($steps[$last]["cards"], $step["cards"]);
  } else $steps[] = $step;
}

function PuzzleStep($player, $command = null, &$steps = null)
{
  PuzzleBeginStep($player);
  $step = null;
  if ($command !== null) {
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
    if ($mode === "OPT" && ($GLOBALS["turn"][0] ?? "") !== "OPT") $mode = null;
    if ($mode === "REORDER" && !in_array("PRETRIGGER", $GLOBALS["layers"] ?? [], true)) $mode = null;
    if ($mode !== null) {
      if ($steps !== null) {
        try {
          $step = PuzzleDescribeInput($player, $mode, $button, $card, $chk);
        } catch (Throwable $e) {
          $step = null;
        }
      }
      $before = PuzzleStateKey(explode("\r\n", (string)($GLOBALS["lastWrittenGamestate"] ?? "")));
      ProcessInput($player, $mode, $button, $card, $chkCount, $chk, false, "");
    }
  }
  PuzzleFinishStep();
  $state = explode("\r\n", (string)($GLOBALS["lastWrittenGamestate"] ?? ""));
  if ($step !== null && PuzzleStateKey($state) !== $before) PuzzleAddStep($steps, $step);
  return $state;
}

function PuzzleCardLogCounts($gamestate)
{
  $lines = explode("\r\n", $gamestate);
  $links = intval($lines[56] ?? 0);
  $counts = [];
  foreach ([1, 2] as $player) {
    $log = json_decode($lines[76 + $player + $links] ?? "", true);
    $counts[$player] = is_array($log) ? count($log) : 0;
  }
  return $counts;
}

const PUZZLE_UNPLAYED_LOG_TYPES = ["P" => true, "B" => true, "HIT" => true, "CHARGE" => true, "KATSUDISCARD" => true,
  "DISCARD" => true, "PASSIVE" => true, "TRANSFORM" => true];

// What each player played, pitched and blocked with since the puzzle started.
function PuzzleTurnCards($cardLogCounts)
{
  $cards = [];
  foreach ([1, 2] as $player) {
    $summary = ["played" => [], "pitched" => [], "blocked" => []];
    foreach (array_slice(GetCardTurnLog($player), $cardLogCounts[$player] ?? 0) as $entry) {
      $type = (string)($entry[2] ?? "");
      if ($type === "P") $summary["pitched"][] = $entry[1];
      else if ($type === "B") $summary["blocked"][] = $entry[1];
      else if (!isset(PUZZLE_UNPLAYED_LOG_TYPES[$type])) $summary["played"][] = $entry[1];
    }
    $cards[$player] = $summary;
  }
  return $cards;
}

function PuzzleRunResult($player, $outcome, $unused, $steps, $cardLogCounts, $blockSteps = [])
{
  global $mainPlayer;
  ParseGamestate();
  $opponent = 3 - $player;
  $health = intval(GetHealth($opponent));
  $won = IsGameOver() && $GLOBALS["winner"] == $player;
  include_once __DIR__ . "/PuzzleHarvest.php";
  return [
    "won" => $won,
    "diverged" => $outcome !== null,
    "reason" => $outcome ?? ($won ? "" : "$health life left"),
    "health" => $health,
    "ownHealth" => intval(GetHealth($player)),
    "unused" => $unused,
    "stats" => PuzzleTurnMeta($mainPlayer, 3 - $mainPlayer),
    "steps" => $steps,
    "cards" => PuzzleTurnCards($cardLogCounts),
    "spare" => PuzzleSpareBlockers($player, $blockSteps, intval(GetHealth($player)))
  ];
}

function PuzzleLegalBlockers($player)
{
  $blockers = [];
  foreach (GetHand($player) as $cardID) {
    if (IsPlayable($cardID, "B", "HAND", -1, $restriction, $player)) $blockers[] = [$cardID, intval(BlockValue($cardID, $player, "HAND", false))];
  }
  $character = GetPlayerCharacter($player);
  for ($i = CharacterPieces(), $count = count($character); $i < $count; $i += CharacterPieces()) {
    if (intval($character[$i + 1]) == 0 || !TypeContains($character[$i], "E", $player)) continue;
    if (IsPlayable($character[$i], "B", "CHAR", $i, $restriction, $player)) {
      $blockers[] = [$character[$i], intval(BlockValue($character[$i], $player, "EQUIP", false))];
    }
  }
  return array_values(array_filter($blockers, fn($blocker) => $blocker[1] > 0));
}

function PuzzleBlockCommand($player, $cardID)
{
  $index = array_search($cardID, GetHand($player), true);
  if ($index !== false) return ["27", $cardID, (string)$index, 0, []];
  $character = GetPlayerCharacter($player);
  for ($i = CharacterPieces(), $count = count($character); $i < $count; $i += CharacterPieces()) {
    if ($character[$i] === $cardID) return ["3", "", (string)$i, 0, []];
  }
  return null;
}

function PuzzleSpareBlockers($player, $blockSteps, $finalHealth)
{
  $log = GetCardTurnLog($player);
  $spare = [];
  foreach ($blockSteps as $step => $blockStep) {
    $damage = $blockStep["health"] - ($blockSteps[$step + 1]["health"] ?? $finalHealth);
    if ($damage <= 0) continue;
    $used = [];
    foreach (array_slice($log, $blockStep["log"]) as $entry) {
      $type = (string)($entry[2] ?? "");
      if ($type === "P" || $type === "B" || !isset(PUZZLE_UNPLAYED_LOG_TYPES[$type])) $used[$entry[1]] = ($used[$entry[1]] ?? 0) + 1;
    }
    foreach ($blockStep["blockers"] as [$cardID, $value]) {
      if (($used[$cardID] ?? 0) > 0) --$used[$cardID];
      else $spare[] = [$step, $cardID, min($value, $damage)];
    }
  }
  return $spare;
}

function PuzzleIsPrompt($phase)
{
  return $phase !== "" && $phase !== "P" && $phase !== "OVER" && !isset(PUZZLE_FLOW_PHASES[$phase]);
}

function PuzzleBotAnswer($player)
{
  include_once __DIR__ . "/PuzzleScript.php";
  PuzzleBeginStep($player);
  PuzzleScriptFallback($player);
  PuzzleFinishStep();
  return explode("\r\n", (string)($GLOBALS["lastWrittenGamestate"] ?? ""));
}

function PuzzleDriveLine($player, $line, $cardLogCounts = [], $blocks = [])
{
  $state = PuzzleStep($player);
  $passPhases = PUZZLE_SAFE_PASS_PHASES + ($GLOBALS["mainPlayer"] != $player ? ["B" => true] : []);
  $outcome = null;
  $steps = [];
  $unused = 0;
  $blockSteps = [];
  $lastSignature = null;
  $answerPrompts = count($blocks) > 0;
  $atDecision = function () use ($player, $blocks, &$state, &$steps, &$blockSteps, &$lastSignature) {
    if (IsGameOver() || intval($GLOBALS["currentPlayer"]) != $player) return;
    $signature = PuzzleStateSignature($state);
    $phase = explode("|", $signature)[0];
    if (PuzzleIsPrompt($phase) || $phase === "P") return;
    $newStep = $phase === "B" && $signature !== $lastSignature;
    $lastSignature = $signature;
    if (!$newStep) return;
    $blockSteps[] = ["health" => intval(explode(" ", trim($state[0] ?? ""))[$player - 1] ?? 0),
      "log" => count(GetCardTurnLog($player)), "blockers" => PuzzleLegalBlockers($player)];
    foreach ($blocks[count($blockSteps) - 1] ?? [] as $cardID) {
      $command = PuzzleBlockCommand($player, $cardID);
      if ($command !== null) $state = PuzzleStep($player, $command, $steps);
    }
  };
  foreach ($line as $index => $command) {
    if (IsGameOver()) {
      $unused = count(array_filter(array_slice($line, $index), fn($remaining) => $remaining[0] != 99));
      break;
    }
    $mode = $command[0];
    if ($mode === "SETTINGS" || $mode === "OPT" || $mode === "REORDER") {
      $state = PuzzleStep($player, $command, $steps);
      continue;
    }
    for ($passes = 0; ; ++$passes) {
      if (IsGameOver()) break;
      $atDecision();
      if (IsGameOver()) break;
      if (intval($GLOBALS["currentPlayer"]) != $player) {
        $outcome = "stuck at input " . ($index + 1);
        break 2;
      }
      $signature = PuzzleStateSignature($state);
      if ($command[5] === "" || $command[5] === $signature) {
        $state = PuzzleStep($player, $command, $steps);
        break;
      }
      $phase = explode("|", $signature)[0];
      if ($answerPrompts && PuzzleIsPrompt($phase) && $passes < PUZZLE_EXTRA_PASSES) {
        $state = PuzzleBotAnswer($player);
        continue;
      }
      if ($mode == 99 || !isset(PUZZLE_FLOW_PHASES[explode("|", $command[5])[0]])) break;
      if (!isset($passPhases[$phase]) || $passes >= PUZZLE_EXTRA_PASSES) {
        $outcome = "diverged at input " . ($index + 1) . " ($signature, expected " . $command[5] . ")";
        break 2;
      }
      $state = PuzzleStep($player, ["99", "", "", 0, []]);
    }
  }
  for ($passes = 0; $outcome === null && $passes < PUZZLE_EXTRA_PASSES && !IsGameOver(); ++$passes) {
    $atDecision();
    if (IsGameOver() || intval($GLOBALS["currentPlayer"]) != $player) break;
    $phase = explode(" ", trim($state[42] ?? ""))[0];
    if (isset($passPhases[$phase])) $state = PuzzleStep($player, ["99", "", "", 0, []]);
    else if ($answerPrompts && PuzzleIsPrompt($phase)) $state = PuzzleBotAnswer($player);
    else break;
  }
  return PuzzleRunResult($player, $outcome, $unused, $steps, $cardLogCounts, $blockSteps);
}

const PUZZLE_BOT_STEPS = 40;

// Both seats are bots (or the survive script): step until the turn is over or nothing moves.
function PuzzleDriveBots($player, $cardLogCounts)
{
  $state = PuzzleStep($player);
  for ($step = 0; $step < PUZZLE_BOT_STEPS && !IsGameOver(); ++$step) {
    $before = PuzzleStateKey($state);
    $state = PuzzleStep($player);
    if (PuzzleStateKey($state) === $before) break;
  }
  return PuzzleRunResult($player, IsGameOver() ? null : "the bots stopped before the turn ended", 0, [], $cardLogCounts);
}

// $line null lets the bots play the turn. $player is the side the result is about.
function RunPuzzleLine($gamestate, $player, $line, $format, $mode = "lethal", $script = null, $blocks = [])
{
  $gameName = PuzzleCreateVerifyGame($gamestate, $format, $mode, $script);
  if ($gameName === null) return ["won" => false, "diverged" => true, "reason" => "could not create a test game"];
  $saved = [];
  foreach (["gameName", "filepath", "filename", "lastWrittenGamestate"] as $name) $saved[$name] = $GLOBALS[$name] ?? null;
  $GLOBALS["gameName"] = $gameName;
  $GLOBALS["filepath"] = "./Games/$gameName/";
  $GLOBALS["filename"] = "./Games/$gameName/gamestate.txt";
  $GLOBALS["lastWrittenGamestate"] = $gamestate;
  ob_start();
  try {
    $cardLogCounts = PuzzleCardLogCounts($gamestate);
    return $line === null ? PuzzleDriveBots($player, $cardLogCounts) : PuzzleDriveLine($player, $line, $cardLogCounts, $blocks);
  } catch (Throwable $e) {
    return ["won" => false, "diverged" => true, "reason" => "engine error: " . $e->getMessage()];
  } finally {
    ob_end_clean();
    PuzzleDeleteVerifyGame($gameName);
    foreach ($saved as $name => $value) $GLOBALS[$name] = $value;
  }
}

// The line must kill the puzzle bot at the life they really had. When it deals more than that, the puzzle life
// is raised to the highest life it still kills at; it is never lowered.
function ProvePuzzleCandidate($content, $player, $line, $format)
{
  $opponent = 3 - $player;
  $healths = explode(" ", trim(explode("\r\n", $content)[0]));
  $realLife = intval($healths[$opponent - 1] ?? 0);
  $results = [];
  $won = null;
  $lost = null;
  $life = max(1, $realLife);
  while (count($results) < PUZZLE_PROOF_MAX_RUNS && !isset($results[$life])) {
    $result = $results[$life] = RunPuzzleLine(PreparePuzzleGamestate($content, $player, $life), $player, $line, $format);
    if (!$result["won"]) {
      if ($won === null) {
        $proof = ["v" => PUZZLE_PROOF_VERSION, "status" => "failed", "reason" => substr($result["reason"], 0, 300), "realLife" => $realLife];
        return ["proof" => $proof, "solution" => null];
      }
      $lost = $life;
    } else {
      $won = $life;
      if ($result["health"] >= 0 && $result["unused"] == 0) break;
    }
    if ($lost !== null && $lost - $won <= 1) break;
    $life = $lost === null ? $won + max(1, -$result["health"]) : intdiv($won + $lost, 2);
  }
  $proof = ["v" => PUZZLE_PROOF_VERSION, "status" => "proven", "life" => $won, "realLife" => $realLife] + $results[$won]["stats"];
  return ["proof" => $proof, "solution" => $results[$won]["steps"]];
}

// The recorded defender kept cards for a next turn that a puzzle does not have. Each card they never used is tried as
// an extra block where it can save the most, and kept when the defense ends the turn with more life.
function PuzzleAddSpareBlocks($run, $base)
{
  $blocks = [];
  $best = $base;
  $tried = [];
  for ($runs = 0; $runs < PUZZLE_SPARE_RUNS;) {
    $spare = $best["spare"] ?? [];
    usort($spare, fn($a, $b) => $b[2] <=> $a[2]);
    $improved = false;
    foreach ($spare as [$step, $cardID]) {
      $key = "$step|$cardID|" . count(array_keys($blocks[$step] ?? [], $cardID, true));
      if (isset($tried[$key])) continue;
      if ($runs >= PUZZLE_SPARE_RUNS) break;
      $tried[$key] = true;
      $try = $blocks;
      $try[$step][] = $cardID;
      $result = $run($try);
      ++$runs;
      if ($result["won"] && $result["ownHealth"] > $best["ownHealth"]) {
        [$blocks, $best, $improved] = [$try, $result, true];
        break;
      }
    }
    if (!$improved) break;
  }
  return [$blocks, $best];
}

function ProveSurvivePuzzle($content, $player, $line, $script, $format)
{
  $healths = explode(" ", trim(explode("\r\n", $content)[0]));
  $realLife = intval($healths[$player - 1] ?? 0);
  $run = fn($life, $blocks = []) => RunPuzzleLine(PreparePuzzleGamestate($content, $player, $life, null, null, "survive"),
    $player, $line, $format, "survive", $script, $blocks);
  $results = [$realLife => $run($realLife)];
  if (!$results[$realLife]["won"]) {
    $proof = ["v" => PUZZLE_SURVIVE_PROOF_VERSION, "status" => "failed", "reason" => substr($results[$realLife]["reason"], 0, 300),
      "realLife" => $realLife];
    return ["proof" => $proof, "solution" => null];
  }
  [$blocks, $results[$realLife]] = PuzzleAddSpareBlocks(fn($try) => $run($realLife, $try), $results[$realLife]);
  $survived = $realLife;
  $died = null;
  $life = max(1, $realLife - max(0, $results[$realLife]["ownHealth"] - 1));
  while (count($results) < PUZZLE_PROOF_MAX_RUNS && !isset($results[$life]) && $life < $survived) {
    $result = $results[$life] = $run($life, $blocks);
    $next = $life;
    if ($result["won"]) {
      $survived = $life;
      if ($result["ownHealth"] <= 1 || $life <= 1) break;
      $next = max(1, $life - ($result["ownHealth"] - 1));
    } else $died = $life;
    if ($died !== null) {
      if ($survived - $died <= 1) break;
      $next = intdiv($died + $survived, 2);
    }
    $life = $next;
  }
  $proof = ["v" => PUZZLE_SURVIVE_PROOF_VERSION, "status" => "proven", "life" => $survived, "realLife" => $realLife]
    + (count($blocks) > 0 ? ["blocks" => $blocks] : []) + $results[$survived]["stats"];
  return ["proof" => $proof, "solution" => $results[$survived]["steps"]];
}

const PUZZLE_BASELINE_VERSION = 1;

// The bot plays the solver's side at the puzzle life. A bot kill (or a bot survival) means the puzzle needs no
// insight. The real line runs again to record which cards it used, so the two can be compared.
function PuzzleBaseline($content, $kind, $player, $life, $line, $script, $format, $blocks = [])
{
  $survive = $kind == PUZZLE_KIND_SURVIVE;
  $mode = $survive ? "survive" : "lethal";
  $real = RunPuzzleLine(PreparePuzzleGamestate($content, $player, $life, null, null, $mode), $player, $line, $format, $mode, $script,
    $blocks);
  $bot = RunPuzzleLine(PreparePuzzleGamestate($content, $player, $life, null, null, $mode, true), $player, null, $format, $mode, $script);
  $empty = ["played" => [], "pitched" => [], "blocked" => []];
  $damage = $survive ? $life - intval($bot["ownHealth"] ?? $life) : $life - intval($bot["health"] ?? $life);
  return [
    "v" => PUZZLE_BASELINE_VERSION,
    "life" => $life,
    "real" => $real["cards"][$player] ?? $empty,
    "bot" => ["won" => $bot["won"], "reason" => substr((string)$bot["reason"], 0, 200), "damage" => max(0, $damage)]
      + ($bot["cards"][$player] ?? $empty)
  ];
}

// What the bot and the real line deal at the damage puzzle life, against the same defense the solver faces.
function PuzzleDamageBars($content, $player, $line, $format, $proof)
{
  $prepare = fn($bothAI) => PreparePuzzleGamestate($content, $player, PUZZLE_DAMAGE_LIFE, null, null, "damage", $bothAI);
  $real = RunPuzzleLine($prepare(false), $player, $line, $format, "damage");
  $bot = RunPuzzleLine($prepare(true), $player, null, $format, "damage");
  $realDamage = max(0, PUZZLE_DAMAGE_LIFE - intval($real["health"] ?? PUZZLE_DAMAGE_LIFE));
  if ($real["diverged"]) $realDamage = max($realDamage, intval($proof["dealt"] ?? 0));
  return ["bot" => max(0, PUZZLE_DAMAGE_LIFE - intval($bot["health"] ?? PUZZLE_DAMAGE_LIFE)), "real" => $realDamage];
}

function CurrentPuzzleProof($encoded, $kind = PUZZLE_KIND_LETHAL)
{
  $proof = json_decode($encoded ?? "", true);
  $version = intval($kind) == PUZZLE_KIND_SURVIVE ? PUZZLE_SURVIVE_PROOF_VERSION : PUZZLE_PROOF_VERSION;
  return is_array($proof) && ($proof["v"] ?? 0) == $version ? $proof : null;
}

function CurrentPuzzleBaseline($encoded)
{
  $baseline = json_decode($encoded ?? "", true);
  return is_array($baseline) && ($baseline["v"] ?? 0) == PUZZLE_BASELINE_VERSION ? $baseline : null;
}

// [line, script]: a survive candidate stores the defender's line together with the attacker's script.
function PuzzleCandidateLines($row)
{
  $decoded = json_decode((string)@gzuncompress((string)$row["winning_line"]), true);
  if (!is_array($decoded)) return [null, null];
  if (intval($row["kind"] ?? 0) != PUZZLE_KIND_SURVIVE) return [$decoded, null];
  if (!is_array($decoded["line"] ?? null) || !is_array($decoded["script"] ?? null)) return [null, null];
  return [$decoded["line"], ["player" => 3 - intval($row["player"]), "line" => $decoded["script"]]];
}

function LoadPuzzleCandidate($conn, $candidateID)
{
  $stmt = mysqli_prepare($conn, "SELECT id, kind, player, format, hero, opponent_hero, proof, baseline, solution, meta,
    winning_line, gamestate FROM puzzle_candidates WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $candidateID);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  return $row ?: null;
}

// Proves the candidate and measures the bot against it, once each; both results are stored.
function VerifyPuzzleCandidate($conn, $candidateID)
{
  $row = LoadPuzzleCandidate($conn, $candidateID);
  if (!$row || $row["winning_line"] === null) return null;
  $kind = intval($row["kind"]);
  $proof = CurrentPuzzleProof($row["proof"], $kind);
  $baseline = CurrentPuzzleBaseline($row["baseline"]);
  if ($proof !== null && ($baseline !== null || $proof["status"] !== "proven")) return ["proof" => $proof, "baseline" => $baseline];

  $player = intval($row["player"]);
  $content = @gzuncompress($row["gamestate"]);
  [$line, $script] = PuzzleCandidateLines($row);
  if ($content === false || $line === null) {
    $proof = ["v" => $kind == PUZZLE_KIND_SURVIVE ? PUZZLE_SURVIVE_PROOF_VERSION : PUZZLE_PROOF_VERSION, "status" => "failed",
      "reason" => "unreadable candidate"];
  } else if ($proof === null) {
    $result = $kind == PUZZLE_KIND_SURVIVE
      ? ProveSurvivePuzzle($content, $player, $line, $script, $row["format"])
      : ProvePuzzleCandidate($content, $player, $line, $row["format"]);
    $proof = $result["proof"];
    $solution = $result["solution"] === null ? null : json_encode($result["solution"]);
    PuzzleUpdateCandidate($conn, $candidateID, ["proof" => json_encode($proof), "solution" => $solution]);
  }
  if (($proof["status"] ?? "") === "proven") {
    $baseline = PuzzleBaseline($content, $kind, $player, intval($proof["life"]), $line, $script, $row["format"],
      $proof["blocks"] ?? []);
    PuzzleUpdateCandidate($conn, $candidateID, ["baseline" => json_encode($baseline)]);
  } else if ($content === false || $line === null) {
    PuzzleUpdateCandidate($conn, $candidateID, ["proof" => json_encode($proof)]);
  }
  return ["proof" => $proof, "baseline" => $baseline];
}

function PuzzleUpdateCandidate($conn, $candidateID, $columns)
{
  $set = implode(", ", array_map(fn($column) => "$column = ?", array_keys($columns)));
  $values = array_values($columns);
  $values[] = $candidateID;
  $stmt = mysqli_prepare($conn, "UPDATE puzzle_candidates SET $set WHERE id = ?");
  mysqli_stmt_bind_param($stmt, str_repeat("s", count($columns)) . "i", ...$values);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}
