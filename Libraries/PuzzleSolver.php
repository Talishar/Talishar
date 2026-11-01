<?php

include_once __DIR__ . "/PuzzleVerify.php";
include_once __DIR__ . "/PuzzleAnalysis.php";

const PUZZLE_SOLVER_VERSION = 1;
const PUZZLE_SOLVER_SKIP_MODES = ["10000" => true, "10001" => true, "10003" => true, "100002" => true, "100007" => true,
  "100016" => true, "100018" => true, "100019" => true, "100022" => true, "100027" => true, "100028" => true,
  "100029" => true, "100030" => true, "101" => true];
const PUZZLE_SOLVER_END_PHASES = ["ARS" => true, "PDECK" => true];
const PUZZLE_SOLVER_MAX_SUBSETS = 32;
const PUZZLE_SOLVER_MAX_DEPTH = 120;
const PUZZLE_SOLVER_KEY_LENGTH = 16;

const PUZZLE_SOLVER_KEY_GLOBALS = ["playerHealths", "landmarks", "winner", "currentPlayer", "currentTurn", "turn", "actionPoints",
  "combatChain", "combatChainState", "currentTurnEffects", "currentTurnEffectsFromCombat", "nextTurnEffects", "decisionQueue",
  "dqVars", "dqState", "layers", "layerPriority", "mainPlayer", "lastPlayed", "chainLinks", "chainLinkSummary", "EffectContext",
  "attackQueue", "permanentUniqueIDCounter"];
const PUZZLE_SOLVER_PLAYER_GLOBALS = ["Hand", "Deck", "CharEquip", "Resources", "Arsenal", "Items", "Auras", "Discard", "Pitch",
  "Banish", "ClassState", "CharacterEffects", "Soul", "Allies", "Permanents"];

function PuzzleSolverKeyPart($value)
{
  if (!is_array($value)) return (string)$value;
  return implode(" ", array_map(fn($item) => is_array($item) ? implode(" ", $item) : (string)$item, $value));
}

function PuzzleSolverKey()
{
  global $currentTurn;
  DoGamestateUpdate();
  $parts = [];
  foreach (PUZZLE_SOLVER_KEY_GLOBALS as $name) $parts[] = PuzzleSolverKeyPart($GLOBALS[$name] ?? "");
  foreach ([1, 2] as $player) {
    foreach (PUZZLE_SOLVER_PLAYER_GLOBALS as $zone) {
      $value = $GLOBALS["p$player$zone"] ?? "";
      $parts[] = $zone === "ClassState" ? ClassStateToString($value) : PuzzleSolverKeyPart($value);
    }
    $log = $GLOBALS["p{$player}CardTurnLog"] ?? [];
    if (is_string($log)) $log = json_decode($log, true) ?? [];
    $entries = [];
    foreach ($log as $entry) if (($entry[0] ?? -1) == $currentTurn) $entries[] = ($entry[1] ?? "") . "|" . ($entry[2] ?? "");
    sort($entries);
    $parts[] = $entries;
  }
  return substr(md5(implode("\n", array_map(fn($part) => is_array($part) ? implode(" ", $part) : $part, $parts))), 0, PUZZLE_SOLVER_KEY_LENGTH);
}

function PuzzleSolverRestore($state)
{
  WriteGamestateCache($GLOBALS["gameName"], $state);
  $GLOBALS["lastWrittenGamestate"] = $state;
}

function PuzzleSolverCollectCards($node, &$inputs, $skip = null)
{
  if (is_object($node)) {
    if ($node === $skip) return;
    if (isset($node->cardNumber) && intval($node->action ?? 0) > 0) {
      $inputs[] = [(string)$node->action, "", (string)($node->actionDataOverride ?? ""), 0, []];
    }
    foreach (get_object_vars($node) as $value) {
      if (is_object($value) || is_array($value)) PuzzleSolverCollectCards($value, $inputs, $skip);
    }
  } else if (is_array($node)) {
    foreach ($node as $value) {
      if (is_object($value) || is_array($value)) PuzzleSolverCollectCards($value, $inputs, $skip);
    }
  }
}

function PuzzleSolverSubsets($values, $min, $max)
{
  $count = count($values);
  $subsets = [];
  for ($mask = 0; $mask < (1 << $count) && count($subsets) < PUZZLE_SOLVER_MAX_SUBSETS; ++$mask) {
    $size = substr_count(decbin($mask), "1");
    if ($size < $min || $size > $max) continue;
    $chosen = [];
    for ($i = 0; $i < $count; ++$i) if ($mask & (1 << $i)) $chosen[] = $values[$i];
    $subsets[] = $chosen;
  }
  return $subsets;
}

function PuzzleSolverInputs($player)
{
  global $gameName;
  $lines = explode("\r\n", (string)$GLOBALS["lastWrittenGamestate"]);
  $links = intval($lines[56] ?? 0);
  $key = trim($lines[($player == 1 ? 58 : 59) + $links] ?? "");
  $response = BuildGameStateResponse($gameName, $player, $key, [], false, false, null, false);
  if (!is_object($response)) return [];
  $inputs = [];
  $popup = $response->playerInputPopUp ?? null;
  $form = is_object($popup) ? ($popup->formOptions ?? null) : null;
  $checkboxCards = is_object($form) && isset($form->mode) ? ($popup->popup ?? null) : null;
  PuzzleSolverCollectCards($response, $inputs, $checkboxCards);
  foreach (array_merge($response->playerPrompt->buttons ?? [], is_object($popup) ? ($popup->buttons ?? []) : []) as $button) {
    $inputs[] = [(string)$button->mode, (string)$button->buttonInput, "", 0, []];
  }
  if (is_object($form) && isset($form->mode)) {
    $values = [];
    if (!empty($popup->multiChooseText)) foreach ($popup->multiChooseText as $option) $values[] = (string)$option->value;
    else if (is_object($checkboxCards)) foreach ($checkboxCards->cards ?? [] as $card) $values[] = (string)($card->actionDataOverride ?? "");
    $count = count($values);
    foreach (PuzzleSolverSubsets($values, intval($form->minNo ?? 0), intval($form->maxNo ?? $count)) as $chosen) {
      $inputs[] = [(string)$form->mode, "", "", $count, $chosen];
    }
  }
  if (!empty($response->canPassPhase)) $inputs[] = ["99", "", "", 0, []];
  $unique = [];
  foreach ($inputs as $input) {
    if (isset(PUZZLE_SOLVER_SKIP_MODES[$input[0]])) continue;
    $unique[json_encode($input)] = $input;
  }
  return array_values($unique);
}

final class PuzzleSolverSearch
{
  public array $memo = [];
  public int $nodes = 0;
  public int $steps = 0;
  public int $stuck = 0;
  public bool $truncated = false;
  public array $known = [];
  private int $logStart = 0;
  private float $deadline;

  // $survive: the solver defends and the attacker is searched too; an attacker node stops at its first kill, since one
  // kill decides it. $firstWin also stops a solver node at its first win, which proves a position but measures nothing.
  public function __construct(public int $solver, public bool $survive, public int $startTurn, public int $maxNodes,
    float $seconds, public bool $firstWin = false)
  {
    $this->deadline = microtime(true) + $seconds;
  }

  // 1 the solver won, 0 the solver lost, null the turn goes on.
  private function Outcome()
  {
    global $mainPlayer, $currentTurn, $turn;
    if (IsGameOver()) return $GLOBALS["winner"] == $this->solver ? 1 : 0;
    $over = $currentTurn != $this->startTurn || isset(PUZZLE_SOLVER_END_PHASES[$turn[0] ?? ""])
      || ($mainPlayer == $this->solver) == $this->survive;
    if ($over) return $this->survive ? 1 : 0;
    return null;
  }

  public function Root($state)
  {
    PuzzleSolverRestore($state);
    ParseGamestate();
    $this->known = PuzzleSolverKnownCards($state, $this->solver);
    $this->logStart = count(GetCardTurnLog($this->solver));
    $key = PuzzleSolverKey();
    $this->Visit($state, $key, 0);
    return $key;
  }

  // Memo entry: win for the solver, mover (whose input each child is), end, open (not searched), children.
  private function Visit($state, $key, $depth)
  {
    if (isset($this->memo[$key])) return $this->memo[$key];
    if ($this->nodes >= $this->maxNodes || microtime(true) > $this->deadline || $depth > PUZZLE_SOLVER_MAX_DEPTH) {
      $this->truncated = true;
      return ["win" => false, "open" => true, "children" => []];
    }
    ++$this->nodes;
    $this->memo[$key] = ["win" => false, "open" => true, "children" => []];
    PuzzleSolverRestore($state);
    ParseGamestate();
    $outcome = $this->Outcome();
    if ($outcome !== null) return $this->memo[$key] = ["win" => $outcome == 1, "end" => true, "children" => []];
    $mover = intval($GLOBALS["currentPlayer"]);
    $isSolver = $mover == $this->solver;
    $inputs = PuzzleSolverInputs($mover);
    $hand = GetHand($mover);
    $children = [];
    $win = !$isSolver;
    foreach ($inputs as $input) {
      if ($isSolver && !$this->Known($input, $hand)) {
        $children[] = ["input" => $input, "key" => null, "win" => false];
        continue;
      }
      PuzzleSolverRestore($state);
      ++$this->steps;
      try {
        $next = implode("\r\n", PuzzleStep($mover, $input));
        $childKey = PuzzleSolverKey();
      } catch (Throwable $e) {
        continue;
      }
      if ($childKey === $key) continue;
      $child = $this->Visit($next, $childKey, $depth + 1);
      $children[] = ["input" => $input, "key" => $childKey, "win" => $child["win"]];
      $win = $isSolver ? $win || $child["win"] : $win && $child["win"];
      if ($isSolver ? $win && $this->firstWin : !$win) break;
    }
    if (count($children) == 0 || (!$this->survive && !$isSolver)) {
      ++$this->stuck;
      $win = false;
    }
    return $this->memo[$key] = ["win" => $win, "mover" => $mover, "children" => $children];
  }

  // A line cannot lean on a card the solver had not seen when the puzzle started: one drawn this turn, or the deck's top.
  // Copies count, so a drawn second copy of a card in hand is still unseen.
  private function Known($input, $hand)
  {
    if ($input[0] === "35") return false;
    if ($input[0] !== "27") return true;
    $cardID = $hand[intval($input[2])] ?? "";
    $used = 0;
    foreach (array_slice(GetCardTurnLog($this->solver), $this->logStart) as $entry) {
      $type = (string)($entry[2] ?? "");
      if (($entry[1] ?? "") === $cardID && ($type === "P" || !isset(PUZZLE_UNPLAYED_LOG_TYPES[$type]))) ++$used;
    }
    return $used < ($this->known[$cardID] ?? 0);
  }

  private function IsSolverNode($node)
  {
    return ($node["mover"] ?? $this->solver) == $this->solver;
  }

  // Bits of choice the solver must get right on the easiest winning line. Each solver decision costs
  // log2(options / winning options), so a decision where everything wins is free; the attacker picks the line that
  // asks the most.
  public function Bits($key, &$cache = [])
  {
    if (isset($cache[$key])) return $cache[$key];
    $node = $this->memo[$key] ?? null;
    if ($node === null || !$node["win"]) return $cache[$key] = INF;
    if (!empty($node["end"])) return $cache[$key] = 0.0;
    $cache[$key] = INF;
    if (!$this->IsSolverNode($node)) {
      $worst = 0.0;
      foreach ($node["children"] as $child) $worst = max($worst, $this->Bits($child["key"], $cache));
      return $cache[$key] = $worst;
    }
    $winning = array_filter($node["children"], fn($child) => $child["win"]);
    $cost = log(count($node["children"]) / count($winning), 2);
    $best = INF;
    foreach ($winning as $child) $best = min($best, $cost + $this->Bits($child["key"], $cache));
    return $cache[$key] = $best;
  }

  // Chance that picking uniformly among the offered inputs wins, against the attacker's best reply.
  public function RandomWin($key, &$cache = [])
  {
    if (isset($cache[$key])) return $cache[$key];
    $node = $this->memo[$key] ?? null;
    if ($node === null || !empty($node["open"])) return $cache[$key] = 0.0;
    if (!empty($node["end"]) || count($node["children"]) == 0) return $cache[$key] = $node["win"] ? 1.0 : 0.0;
    $cache[$key] = 0.0;
    $values = [];
    foreach ($node["children"] as $child) $values[] = $this->RandomWin($child["key"], $cache);
    return $cache[$key] = $this->IsSolverNode($node) ? array_sum($values) / count($values) : min($values);
  }

  // The attacker's reply at one of its nodes: a winning attack if there is one, else the one that asks the most.
  public function Reply($key, &$bits = [], &$random = [])
  {
    $node = $this->memo[$key];
    $best = null;
    $bestScore = null;
    foreach ($node["children"] as $child) {
      $score = $child["win"] ? [1, -$this->Bits($child["key"], $bits), $this->RandomWin($child["key"], $random)] : [0, 0, 0];
      if ($best === null || $score < $bestScore) {
        $best = $child;
        $bestScore = $score;
      }
    }
    return $best;
  }

  // The attacker's input for every position it moves in, for the live game.
  public function Policy()
  {
    $policy = [];
    $bits = [];
    $random = [];
    foreach ($this->memo as $key => $node) {
      if (!empty($node["end"]) || !empty($node["open"]) || $this->IsSolverNode($node) || count($node["children"]) == 0) continue;
      $policy[$key] = $this->Reply($key, $bits, $random)["input"];
    }
    return $policy;
  }

  // The easiest winning line from $rootState, replayed so each input can be described.
  public function Line($rootState, $rootKey)
  {
    $line = [];
    $bits = [];
    $random = [];
    $state = $rootState;
    $key = $rootKey;
    while (($node = $this->memo[$key] ?? null) !== null && $node["win"] && empty($node["end"]) && count($line) < 200) {
      $isSolver = $this->IsSolverNode($node);
      if ($isSolver) {
        $best = null;
        foreach ($node["children"] as $child) {
          if ($child["win"] && ($best === null || $this->Bits($child["key"], $bits) < $this->Bits($best["key"], $bits))) $best = $child;
        }
      } else $best = $this->Reply($key, $bits, $random);
      PuzzleSolverRestore($state);
      ParseGamestate();
      [$mode, $button, $card, , $chk] = $best["input"];
      try {
        $step = PuzzleDescribeInput($node["mover"], $mode, $button, $card, $chk);
      } catch (Throwable $e) {
        $step = null;
      }
      $winning = count(array_filter($node["children"], fn($child) => $child["win"]));
      $line[] = ["step" => $step, "solver" => $isSolver, "input" => $best["input"], "options" => count($node["children"]), "winning" => $winning];
      PuzzleSolverRestore($state);
      $state = implode("\r\n", PuzzleStep($node["mover"], $best["input"]));
      $key = $best["key"];
    }
    return $line;
  }
}

const PUZZLE_SOLVER_MAX_NODES = 40000;
const PUZZLE_SOLVER_MAX_RAISE = 30;
const PUZZLE_SOLVER_KNOWN_ZONES = [1, 3, 5, 6, 7, 8, 10, 13, 16, 17];

// Runs $callback(startState, turn) in a temp game holding the puzzle at $life.
function PuzzleSolverWithGame($content, $player, $survive, $life, $format, $callback)
{
  $mode = $survive ? "survive" : "lethal";
  $state = PreparePuzzleGamestate($content, $player, $life, "puzzlesolver1", "puzzlesolver2", $mode);
  $gameName = PuzzleCreateVerifyGame($state, $format, $mode, $survive ? ["player" => 3 - $player, "line" => []] : null);
  if ($gameName === null) return null;
  $saved = [];
  foreach (["gameName", "filepath", "filename", "lastWrittenGamestate", "puzzleSolverHold"] as $name) $saved[$name] = $GLOBALS[$name] ?? null;
  $GLOBALS["gameName"] = $gameName;
  $GLOBALS["filepath"] = "./Games/$gameName/";
  $GLOBALS["filename"] = "./Games/$gameName/gamestate.txt";
  $GLOBALS["lastWrittenGamestate"] = $state;
  $GLOBALS["puzzleSolverHold"] = $survive;
  ob_start();
  try {
    $start = implode("\r\n", PuzzleStep($player));
    ParseGamestate();
    return $callback($start, intval($GLOBALS["currentTurn"]));
  } catch (Throwable $e) {
    error_log("PuzzleSolver failed: " . $e->getMessage());
    return null;
  } finally {
    ob_end_clean();
    PuzzleDeleteVerifyGame($gameName);
    foreach ($saved as $name => $value) $GLOBALS[$name] = $value;
  }
}

// Whether the solver wins at $life: true, false, or null when the search ran out of budget.
function PuzzleSolverCheck($content, $player, $survive, $life, $format, $deadline)
{
  return PuzzleSolverWithGame($content, $player, $survive, $life, $format, function ($start, $turn) use ($player, $survive, $deadline) {
    $search = new PuzzleSolverSearch($player, $survive, $turn, PUZZLE_SOLVER_MAX_NODES, $deadline - microtime(true), true);
    $win = $search->memo[$search->Root($start)]["win"];
    return $win || !$search->truncated ? $win : null;
  });
}

function CurrentPuzzleSolve($encoded)
{
  $solve = json_decode((string)$encoded, true);
  return is_array($solve) && intval($solve["v"] ?? 0) == PUZZLE_SOLVER_VERSION ? $solve : null;
}

// Solves one candidate and stores the result, plus the attacker's policy for a sound survive puzzle.
function SolvePuzzleCandidate($conn, $candidateID, $seconds)
{
  $row = LoadPuzzleCandidate($conn, $candidateID);
  $content = $row ? @gzuncompress($row["gamestate"]) : false;
  if ($content === false) return null;
  $solve = SolvePuzzlePosition($content, intval($row["player"]), intval($row["kind"]), $row["format"], $seconds);
  $policy = $solve["policy"] ?? null;
  unset($solve["policy"]);
  PuzzleUpdateCandidate($conn, $candidateID, ["solve" => json_encode($solve),
    "policy" => $policy === null ? null : gzcompress(json_encode($policy), 6)]);
  return $solve;
}

const PUZZLE_SOLVER_RUBRIC = ["FORCED" => 10, "CHOICES" => 50, "NOT_RANDOM" => 25, "DECISIONS" => 15];
const PUZZLE_SOLVER_MIN_BITS = 2.0;

function PuzzleSolveDifficulty($bits)
{
  return $bits >= 4 ? "hard" : ($bits >= 2.5 ? "medium" : "easy");
}

function PuzzleSolveInterest($solve)
{
  $earned = [
    "FORCED" => 1,
    "CHOICES" => PuzzleBand($solve["bits"], [[1, 0], [1.5, 0.2], [2, 0.4], [3, 0.7], [4, 0.9], [PHP_INT_MAX, 1]]),
    "NOT_RANDOM" => PuzzleBand($solve["randomWin"], [[0.01, 1], [0.03, 0.8], [0.08, 0.5], [0.2, 0.2], [PHP_INT_MAX, 0]]),
    "DECISIONS" => PuzzleBand($solve["decisions"], [[1, 0], [2, 0.5], [3, 0.8], [PHP_INT_MAX, 1]])
  ];
  $criteria = [];
  $points = 0;
  foreach (PUZZLE_SOLVER_RUBRIC as $code => $weight) {
    $got = (int)round($weight * $earned[$code]);
    $criteria[] = ["code" => $code, "points" => $got, "max" => $weight, "measured" => true];
    $points += $got;
  }
  $max = array_sum(PUZZLE_SOLVER_RUBRIC);
  $percent = (int)round(100 * $points / $max);
  return ["percent" => $percent, "potential" => $percent, "capped" => false, "points" => $points, "max" => $max, "criteria" => $criteria];
}

// A solved position is judged by the search instead of the heuristics: its life, difficulty, interest and line.
function PuzzleApplySolve($analysis, $solve)
{
  if ($solve === null) return $analysis + ["solve" => null];
  $analysis["solve"] = array_diff_key($solve, ["steps" => true]);
  $flags = [];
  if ($solve["status"] !== "sound") {
    $flags[] = ["code" => $solve["status"] === "unsound" ? "SOLVER_UNSOUND" : "SOLVER_TRUNCATED", "value" => 0];
    $analysis["flags"] = $flags;
    $analysis["filtered"] = true;
    $analysis["interest"] = 0;
    $analysis["rubric"] = ["percent" => 0, "potential" => 0, "capped" => true, "points" => 0, "max" => 0, "criteria" => []];
    return $analysis;
  }
  $life = intval($solve["life"]);
  $realLife = intval($solve["realLife"]);
  if ($life > $realLife) $flags[] = ["code" => "LIFE_RAISED", "value" => $life - $realLife];
  if ($life < $realLife) $flags[] = ["code" => "LIFE_LOWERED", "value" => $realLife - $life];
  $flags[] = ["code" => "SOLVER_BITS", "value" => $solve["bits"]];
  $interest = PuzzleSolveInterest($solve);
  $analysis["flags"] = $flags;
  if ($analysis["kind"] === "survive") $analysis["life"] = $life;
  $analysis["opponentLife"] = $life;
  $analysis["filtered"] = $solve["bits"] < PUZZLE_SOLVER_MIN_BITS || !empty($solve["hidden"]);
  $analysis["score"] = $interest["percent"];
  $analysis["interest"] = $interest["percent"];
  $analysis["rubric"] = $interest;
  $analysis["difficulty"] = PuzzleSolveDifficulty($solve["bits"]);
  return $analysis;
}

function PuzzleSolverKnownCards($state, $player)
{
  $lines = explode("\r\n", $state);
  $known = [];
  foreach (PUZZLE_SOLVER_KNOWN_ZONES as $zone) {
    foreach (explode(" ", trim($lines[$zone + ($player - 1) * 18] ?? "")) as $token) $known[$token] = ($known[$token] ?? 0) + 1;
  }
  return $known;
}

function PuzzleSolverHidden($content, $player, $steps)
{
  $lines = explode("\r\n", $content);
  $known = "";
  foreach (PUZZLE_SOLVER_KNOWN_ZONES as $zone) $known .= " " . ($lines[$zone + ($player - 1) * 18] ?? "");
  foreach ($steps as $step) {
    if (($step["kind"] ?? "") !== "PLAY") continue;
    if (($step["from"] ?? "") === "MYDECK") return true;
    foreach ($step["cards"] ?? [] as $cardID) if (!str_contains($known, $cardID)) return true;
  }
  return false;
}

// Proves a position and measures it. Lethal raises the opponent's life to the highest the solver can still kill;
// survive lowers the solver's own life to the lowest it can still survive at. Life is never moved the other way.
function SolvePuzzlePosition($content, $player, $kind, $format, $seconds)
{
  $started = microtime(true);
  $deadline = $started + $seconds;
  $survive = $kind == PUZZLE_KIND_SURVIVE;
  $healths = explode(" ", trim(explode("\r\n", $content)[0]));
  $realLife = max(1, intval($healths[($survive ? $player : 3 - $player) - 1] ?? 0));
  $result = ["v" => PUZZLE_SOLVER_VERSION, "kind" => $survive ? "survive" : "lethal", "realLife" => $realLife];
  $check = fn($life) => PuzzleSolverCheck($content, $player, $survive, $life, $format, $deadline);

  $first = $check($realLife);
  if ($first !== true) return $result + ["status" => $first === false ? "unsound" : "truncated", "seconds" => round(microtime(true) - $started, 1)];
  $good = $realLife;
  $bad = $survive ? 0 : $realLife + PUZZLE_SOLVER_MAX_RAISE + 1;
  $stride = 1;
  while (abs($bad - $good) > 1 && microtime(true) < $deadline) {
    $life = abs($bad - $good) > 2 * $stride ? $good + ($survive ? -$stride : $stride) : intdiv($good + $bad, 2);
    $won = $check($life);
    if ($won === null) break;
    if ($won) {
      $good = $life;
      $stride *= 2;
    } else $bad = $life;
  }

  $measured = PuzzleSolverWithGame($content, $player, $survive, $good, $format, function ($start, $turn) use ($player, $survive, $deadline) {
    $search = new PuzzleSolverSearch($player, $survive, $turn, PUZZLE_SOLVER_MAX_NODES, max(1, $deadline - microtime(true)));
    $rootKey = $search->Root($start);
    if (!$search->memo[$rootKey]["win"]) return ["truncated" => true];
    $steps = [];
    $decisions = 0;
    foreach ($search->Line($start, $rootKey) as $entry) {
      if (!$entry["solver"]) continue;
      if ($entry["winning"] < $entry["options"]) ++$decisions;
      if ($entry["step"] !== null) PuzzleAddStep($steps, $entry["step"]);
    }
    return [
      "truncated" => $search->truncated,
      "bits" => round($search->Bits($rootKey), 2),
      "randomWin" => round($search->RandomWin($rootKey), 4),
      "decisions" => $decisions,
      "nodes" => $search->nodes,
      "steps" => $steps,
      "policy" => $survive ? $search->Policy() : null
    ];
  });
  $seconds = round(microtime(true) - $started, 1);
  if ($measured === null || $measured["truncated"]) return $result + ["status" => "truncated", "life" => $good, "seconds" => $seconds];
  return $result + [
    "status" => "sound",
    "life" => $good,
    "exact" => abs($bad - $good) == 1,
    "bits" => $measured["bits"],
    "randomWin" => $measured["randomWin"],
    "decisions" => $measured["decisions"],
    "nodes" => $measured["nodes"],
    "hidden" => PuzzleSolverHidden($content, $player, $measured["steps"]),
    "seconds" => $seconds,
    "steps" => $measured["steps"],
    "policy" => $measured["policy"]
  ];
}
