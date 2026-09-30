<?php

include_once __DIR__ . "/ReplayLibraries.php";
include_once __DIR__ . "/PuzzleGame.php";
include_once __DIR__ . "/FormatCodes.php";

const PUZZLE_PROOF_VERSION = 1;
const PUZZLE_PROOF_MAX_RUNS = 6;
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

// The winner's inputs for the turn, with every undone step removed and the phase each input was given in.
function ExtractPuzzleLine($gameDirectory, $winner, $turn)
{
  $gameDirectory = rtrim($gameDirectory, "/\\") . "/";
  $commands = @file($gameDirectory . "commandfile.txt", FILE_IGNORE_NEW_LINES);
  if (!is_array($commands)) return null;
  $start = null;
  foreach ($commands as $index => $command) {
    if (rtrim($command) === "$winner StartTurn $turn 0") $start = $index;
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

function PuzzleCreateVerifyGame($gamestate, $format)
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
  file_put_contents($directory . PUZZLE_MARKER_FILE, "verify");
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

function PuzzleStep($player, $command = null)
{
  PuzzleBeginStep($player);
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
    if ($mode !== null) ProcessInput($player, $mode, $button, $card, $chkCount, $chk, false, "");
  }
  PuzzleFinishStep();
  return explode("\r\n", (string)($GLOBALS["lastWrittenGamestate"] ?? ""));
}

function PuzzleDriveLine($player, $line)
{
  $state = PuzzleStep($player);
  $outcome = null;
  foreach ($line as $index => $command) {
    if (IsGameOver()) break;
    $mode = $command[0];
    if ($mode === "SETTINGS" || $mode === "OPT" || $mode === "REORDER") {
      $state = PuzzleStep($player, $command);
      continue;
    }
    for ($passes = 0; ; ++$passes) {
      if (IsGameOver()) break;
      if (intval($GLOBALS["currentPlayer"]) != $player) {
        $outcome = "stuck at input " . ($index + 1);
        break 2;
      }
      $signature = PuzzleStateSignature($state);
      if ($command[5] === "" || $command[5] === $signature) {
        $state = PuzzleStep($player, $command);
        break;
      }
      if ($mode == 99 || !isset(PUZZLE_FLOW_PHASES[explode("|", $command[5])[0]])) break;
      $phase = explode("|", $signature)[0];
      if (!isset(PUZZLE_SAFE_PASS_PHASES[$phase]) || $passes >= PUZZLE_EXTRA_PASSES) {
        $outcome = "diverged at input " . ($index + 1) . " ($signature, expected " . $command[5] . ")";
        break 2;
      }
      $state = PuzzleStep($player, ["99", "", "", 0, []]);
    }
  }
  for ($passes = 0; $outcome === null && $passes < PUZZLE_EXTRA_PASSES && !IsGameOver(); ++$passes) {
    if (intval($GLOBALS["currentPlayer"]) != $player) break;
    if (!isset(PUZZLE_SAFE_PASS_PHASES[explode(" ", trim($state[42] ?? ""))[0]])) break;
    $state = PuzzleStep($player, ["99", "", "", 0, []]);
  }
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
    "stats" => PuzzleTurnMeta($player, $opponent)
  ];
}

function RunPuzzleLine($gamestate, $player, $line, $format)
{
  $gameName = PuzzleCreateVerifyGame($gamestate, $format);
  if ($gameName === null) return ["won" => false, "diverged" => true, "reason" => "could not create a test game"];
  $saved = [];
  foreach (["gameName", "filepath", "filename", "lastWrittenGamestate"] as $name) $saved[$name] = $GLOBALS[$name] ?? null;
  $GLOBALS["gameName"] = $gameName;
  $GLOBALS["filepath"] = "./Games/$gameName/";
  $GLOBALS["filename"] = "./Games/$gameName/gamestate.txt";
  $GLOBALS["lastWrittenGamestate"] = $gamestate;
  ob_start();
  try {
    return PuzzleDriveLine($player, $line);
  } catch (Throwable $e) {
    return ["won" => false, "diverged" => true, "reason" => "engine error: " . $e->getMessage()];
  } finally {
    ob_end_clean();
    PuzzleDeleteVerifyGame($gameName);
    foreach ($saved as $name => $value) $GLOBALS[$name] = $value;
  }
}

// Finds the highest opponent life at which the recorded line still kills the puzzle bot.
function ProvePuzzleCandidate($content, $player, $line, $format)
{
  $opponent = 3 - $player;
  $healths = explode(" ", trim(explode("\r\n", $content)[0]));
  $realLife = intval($healths[$opponent - 1] ?? 0);
  $results = [];
  $run = function ($life) use (&$results, $content, $player, $line, $format) {
    return $results[$life] ??= RunPuzzleLine(PreparePuzzleGamestate($content, $player, $life), $player, $line, $format);
  };

  $won = null;
  $lost = null;
  $reason = "";
  $life = max(1, $realLife);
  while (count($results) < PUZZLE_PROOF_MAX_RUNS && $life >= 1 && !isset($results[$life])) {
    $result = $run($life);
    if ($result["won"]) {
      $won = $life;
      $overkill = -$result["health"];
      if ($overkill <= 0) break;
      $life = $lost === null ? $life + $overkill : intdiv($won + $lost, 2);
    } else {
      $reason = $result["reason"];
      if ($result["diverged"]) break;
      $lost = $life;
      $life = $won === null ? $life - max(1, $result["health"]) : intdiv($won + $lost, 2);
    }
    if ($won !== null && $lost !== null && $lost - $won <= 1) break;
  }

  if ($won === null) {
    return ["v" => PUZZLE_PROOF_VERSION, "status" => "failed", "reason" => substr($reason, 0, 300), "realLife" => $realLife];
  }
  return ["v" => PUZZLE_PROOF_VERSION, "status" => "proven", "life" => $won, "realLife" => $realLife] + $results[$won]["stats"];
}

function CurrentPuzzleProof($encoded)
{
  $proof = json_decode($encoded ?? "", true);
  return is_array($proof) && ($proof["v"] ?? 0) == PUZZLE_PROOF_VERSION ? $proof : null;
}

function VerifyPuzzleCandidate($conn, $candidateID)
{
  $stmt = mysqli_prepare($conn, "SELECT player, format, proof, winning_line, gamestate FROM puzzle_candidates WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $candidateID);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  if (!$row || $row["winning_line"] === null) return null;
  $proof = CurrentPuzzleProof($row["proof"]);
  if ($proof !== null) return $proof;

  $content = @gzuncompress($row["gamestate"]);
  $line = json_decode((string)@gzuncompress($row["winning_line"]), true);
  $proof = $content === false || !is_array($line)
    ? ["v" => PUZZLE_PROOF_VERSION, "status" => "failed", "reason" => "unreadable candidate"]
    : ProvePuzzleCandidate($content, intval($row["player"]), $line, $row["format"]);
  $encoded = json_encode($proof);
  $stmt = mysqli_prepare($conn, "UPDATE puzzle_candidates SET proof = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "si", $encoded, $candidateID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  return $proof;
}
