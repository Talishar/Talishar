<?php

const PUZZLE_MARKER_FILE = "puzzle.txt";
const PUZZLE_START_FILE = "puzzleStart.txt";
const PUZZLE_INFO_FILE = "puzzleInfo.json";
const PUZZLE_MODES = ["lethal" => true, "damage" => true, "survive" => true];
const PUZZLE_DAMAGE_LIFE = 99;
const PUZZLE_VERIFY_MARKER = "verify";
const PUZZLE_KIND_LETHAL = 0;
const PUZZLE_KIND_SURVIVE = 1;

function IsPuzzleGame($gameName)
{
  return file_exists(__DIR__ . "/../Games/$gameName/" . PUZZLE_MARKER_FILE);
}

function IsPuzzleVerifyGame($gameName)
{
  return trim((string)@file_get_contents(__DIR__ . "/../Games/$gameName/" . PUZZLE_MARKER_FILE)) === PUZZLE_VERIFY_MARKER;
}

function ReadPuzzleInfo($gameName)
{
  $info = json_decode((string)@file_get_contents(__DIR__ . "/../Games/$gameName/" . PUZZLE_INFO_FILE), true);
  if (!is_array($info)) $info = [];
  if (!isset(PUZZLE_MODES[$info["mode"] ?? ""])) $info["mode"] = "lethal";
  return $info;
}

function WritePuzzleInfo($gameName, $info)
{
  file_put_contents(__DIR__ . "/../Games/$gameName/" . PUZZLE_INFO_FILE, json_encode($info), LOCK_EX);
}

function PuzzleLogLine($text, $color = "#005900")
{
  return "<p style='background: $color;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>"
    . htmlspecialchars($text, ENT_QUOTES) . "</span></p>\r\n";
}

function PuzzleTitle($info)
{
  if (isset($info["daily"])) {
    $title = "Daily puzzle #" . intval($info["daily"]["number"] ?? 0);
    return !empty($info["daily"]["practice"]) ? "$title (practice)" : $title;
  }
  return "Puzzle #" . intval($info["candidateId"] ?? 0);
}

function PuzzleGoal($info)
{
  $life = intval($info["life"] ?? 0);
  switch ($info["mode"]) {
    case "damage":
      $bars = $info["bars"] ?? [];
      $goal = "deal as much damage as you can this turn.";
      if (isset($bars["bot"], $bars["real"])) {
        $goal .= " The bot dealt " . intval($bars["bot"]) . ", the player in the real game dealt " . intval($bars["real"]) . ".";
      }
      return $goal;
    case "survive":
      return "survive this turn. You are at $life life, and your opponent attacks exactly like they did in the real game.";
    default:
      return "win this turn. Your opponent is at $life life.";
  }
}

function PuzzleIntroLog($info)
{
  $log = PuzzleLogLine("🧩 " . PuzzleTitle($info) . ": " . PuzzleGoal($info));
  $hints = $info["hints"] ?? [];
  for ($i = 0, $used = min(count($hints), intval($info["hintsUsed"] ?? 0)); $i < $used; ++$i) {
    $log .= PuzzleLogLine("💡 Hint " . ($i + 1) . "/" . count($hints) . ": " . $hints[$i], "#2a3f5a");
  }
  return $log;
}

// Without keys this is the state the proof runs on: the solver keeps the recorded player's settings,
// because their inputs were given under them. A survive puzzle scripts the attacker from its recording,
// so the attacker keeps its settings too. $life is the opponent's life, or the solver's own in a survive puzzle.
function PreparePuzzleGamestate($content, $player, $life, $p1Key = null, $p2Key = null, $mode = "lethal", $bothAI = false)
{
  $lines = explode("\r\n", $content);
  $opponent = 3 - $player;
  $healths = explode(" ", trim($lines[0]));
  $healths[($mode === "survive" ? $player : $opponent) - 1] = $life;
  $lines[0] = implode(" ", $healths);
  $numChainLinks = intval(trim($lines[56] ?? "0"));
  if ($mode !== "survive") $lines[$opponent == 2 ? 36 : 18] = "";
  if ($p1Key !== null) {
    $lines[$player == 1 ? 18 : 36] = "";
    $lines[58 + $numChainLinks] = $p1Key;
    $lines[59 + $numChainLinks] = $p2Key;
  }
  $lines[74 + $numChainLinks] = $player == 1 && !$bothAI ? "0" : "1";
  $lines[75 + $numChainLinks] = $player == 2 && !$bothAI ? "0" : "1";
  $lines[76 + $numChainLinks] = "0";
  return implode("\r\n", $lines);
}

function RestartPuzzleGame($playerID)
{
  global $gameName, $filepath, $skipWriteGamestate;
  if (!IsPuzzleGame($gameName) || IsPlayerAI($playerID)) return;
  $startFile = file_exists($filepath . PUZZLE_START_FILE) ? PUZZLE_START_FILE : "beginTurnGamestate.txt";
  if (!file_exists($filepath . $startFile)) return;
  RevertGamestate($startFile);
  SetCachePiece($gameName, 14, 5); //MGS_GameStarted
  foreach (glob($filepath . "gamestateBackup_*.txt") ?: [] as $backup) @unlink($backup);
  @unlink($filepath . "preBlockBackup.txt");
  @unlink($filepath . "startChainLinkGamestate.txt");
  $info = ReadPuzzleInfo($gameName);
  if (!isset($info["life"])) {
    $healths = explode(" ", trim(explode("\r\n", (string)@file_get_contents($filepath . $startFile))[0]));
    $info["life"] = intval($healths[$playerID == 1 ? 1 : 0] ?? 0);
    $info["candidateId"] = intval(@file_get_contents($filepath . PUZZLE_MARKER_FILE));
  }
  if (empty($info["finished"])) {
    $info["tries"] = intval($info["tries"] ?? 1) + 1;
    WritePuzzleInfo($gameName, $info);
    if (PuzzleRecordsResult($info)) {
      include_once __DIR__ . "/PuzzleDaily.php";
      AddDailyPuzzleTry($info["daily"]["date"], intval($info["daily"]["userId"]));
    }
  }
  FlushLogBuffer();
  file_put_contents($filepath . "gamelog.txt", PuzzleIntroLog($info));
  WriteLog("🧩 Puzzle restarted.");
  if ($info["mode"] === "survive") {
    include_once __DIR__ . "/PuzzleScript.php";
    ResetPuzzleScript($gameName);
    // The scripted attacker moves first, so it has to act on the restored state in this request.
    ParseGamestate();
    $skipWriteGamestate = false;
  }
}

function PuzzleRecordsResult($info)
{
  return isset($info["daily"]) && empty($info["daily"]["practice"]) && intval($info["daily"]["userId"] ?? 0) > 0;
}

function PuzzleClientInfo($gameName)
{
  if (!IsPuzzleGame($gameName)) return null;
  $info = ReadPuzzleInfo($gameName);
  return [
    "mode" => $info["mode"],
    "daily" => isset($info["daily"]),
    "hintsTotal" => count($info["hints"] ?? []),
    "hintsUsed" => min(count($info["hints"] ?? []), intval($info["hintsUsed"] ?? 0))
  ];
}

// The next hint for the puzzle solver, also written to the log.
function PuzzleHint($playerID)
{
  global $gameName;
  if (!IsPuzzleGame($gameName)) return null;
  $info = ReadPuzzleInfo($gameName);
  if (intval($info["player"] ?? 0) != $playerID) return null;
  $hints = $info["hints"] ?? [];
  $used = intval($info["hintsUsed"] ?? 0);
  if ($used >= count($hints)) {
    WriteLog(count($hints) == 0 ? "💡 This puzzle has no hints." : "💡 No more hints.");
    return ["hint" => null, "index" => $used, "total" => count($hints)];
  }
  $info["hintsUsed"] = ++$used;
  WritePuzzleInfo($gameName, $info);
  WriteLog("💡 Hint $used/" . count($hints) . ": " . $hints[$used - 1], highlight: true, highlightColor: "#2a3f5a");
  if (empty($info["finished"]) && PuzzleRecordsResult($info)) {
    include_once __DIR__ . "/PuzzleDaily.php";
    SetDailyPuzzleHints($info["daily"]["date"], intval($info["daily"]["userId"]), $used);
  }
  return ["hint" => $hints[$used - 1], "index" => $used, "total" => count($hints)];
}

// The puzzle turn ended without a kill: who wins depends on what the puzzle asked for.
function PuzzleTurnEnded()
{
  global $gameName, $mainPlayer, $defPlayer;
  $info = ReadPuzzleInfo($gameName);
  switch ($info["mode"]) {
    case "damage":
      PlayerWon($mainPlayer);
      break;
    case "survive":
      WriteLog("🧩 Puzzle solved: you survived the turn!", highlight: true, highlightColor: "darkgreen");
      PlayerWon($defPlayer);
      break;
    default:
      WriteLog("🧩 Puzzle failed: your opponent survived the turn.", highlight: true);
      PlayerWon($defPlayer);
  }
}

function PuzzleGameOver($winner)
{
  global $gameName;
  $info = ReadPuzzleInfo($gameName);
  if (IsPuzzleVerifyGame($gameName)) return;
  $player = intval($info["player"] ?? 0);
  if ($player != 1 && $player != 2) {
    global $mainPlayer;
    if ($winner == $mainPlayer) WriteLog("🧩 Puzzle solved!", highlight: true, highlightColor: "darkgreen");
    return;
  }
  $opponent = 3 - $player;
  $damage = null;
  if ($info["mode"] === "damage") {
    $damage = max(0, intval($info["life"] ?? PUZZLE_DAMAGE_LIFE) - intval(GetHealth($opponent)));
    $solved = true;
    WriteLog("🧩 You dealt $damage damage this turn.", highlight: true, highlightColor: "darkgreen");
  } else {
    $solved = $winner == $player;
    if ($info["mode"] === "lethal" && $solved) WriteLog("🧩 Puzzle solved!", highlight: true, highlightColor: "darkgreen");
    if ($info["mode"] === "survive" && !$solved) WriteLog("🧩 Puzzle failed: you did not survive the turn.", highlight: true);
  }
  if (!empty($info["trick"])) WriteLog("🧩 The trick: " . $info["trick"], highlight: true, highlightColor: "#2a3f5a");
  if (!empty($info["finished"])) return;
  $info["finished"] = true;
  WritePuzzleInfo($gameName, $info);
  if (!PuzzleRecordsResult($info)) return;
  include_once __DIR__ . "/PuzzleDaily.php";
  RecordDailyPuzzleResult($info["daily"]["date"], intval($info["daily"]["userId"]), $solved, $damage);
}
