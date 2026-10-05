<?php

// What makes a position worth a day, after chess problem composition and the TCG puzzle series: it is sound, the
// obvious line fails, it turns on one idea you can name, nothing is wasted, and everything needed is on the table.
// Interest is the share of these points a position earns, out of the criteria that apply to its kind.
const PUZZLE_RUBRIC = [
  "SOUND" => 10,
  "NOT_OBVIOUS" => 20,
  "BEATS_BOT" => 20,
  "TRICK" => 15,
  "EXACT" => 10,
  "ECONOMY" => 8,
  "DEPTH" => 7,
  "READABLE" => 4,
  "FAIR" => 4,
  "STAKES" => 2
];

// A position the printed numbers or the bot already solve is not a puzzle, however tidy it is.
const PUZZLE_TOO_EASY_CAP = 40;

const PUZZLE_HIDDEN_ZONES = ["MYDECK" => true, "THEIRDECK" => true];

// A line that plays a card out of a deck only works if the solver knows what is on top.
function PuzzleUsesHiddenCards($steps, $lesson)
{
  if (($lesson["theme"] ?? "") === "DECK") return true;
  foreach (is_array($steps) ? $steps : [] as $step) {
    if (isset(PUZZLE_HIDDEN_ZONES[$step["from"] ?? ""])) return true;
  }
  return false;
}

function PuzzleDecisions($steps, $kinds)
{
  return count(array_filter(is_array($steps) ? $steps : [], fn($step) => in_array($step["kind"] ?? "", $kinds, true)));
}

// $earned maps a criterion to the share of its points earned, or null when it does not apply. $unmeasured lists the
// criteria that only a check can measure; potential is the interest if they all came out full.
function PuzzleInterest($earned, $unmeasured, $tooEasy)
{
  $criteria = [];
  $points = 0;
  $max = 0;
  $missing = 0;
  foreach (PUZZLE_RUBRIC as $code => $weight) {
    if (($earned[$code] ?? null) === null) continue;
    $got = (int)round($weight * $earned[$code]);
    $criteria[] = ["code" => $code, "points" => $got, "max" => $weight, "measured" => !in_array($code, $unmeasured, true)];
    $points += $got;
    $max += $weight;
    if (in_array($code, $unmeasured, true)) $missing += $weight - $got;
  }
  $cap = $tooEasy ? PUZZLE_TOO_EASY_CAP : 100;
  return [
    "percent" => $max == 0 ? 0 : min($cap, (int)round(100 * $points / $max)),
    "potential" => $max == 0 ? 0 : min($cap, (int)round(100 * ($points + $missing) / $max)),
    "capped" => $tooEasy,
    "points" => $points,
    "max" => $max,
    "criteria" => $criteria
  ];
}

function PuzzleTrickShare($lesson)
{
  if ($lesson === null) return 0;
  if ($lesson["theme"] === "COUNT") return 0;
  if ($lesson["theme"] === "SEQUENCING" || $lesson["theme"] === "BLOCK_CHOICE") return 0.5;
  return 1;
}

function PuzzleLethalInterest($proven, $gap, $bot, $life, $margin, $spare, $killLength, $options, $needed, $lesson, $steps, $tooEasy)
{
  $plays = PuzzleDecisions($steps, ["PLAY", "ACTIVATE"]);
  $length = $plays > 0 ? $plays : $killLength;
  $unmeasured = [];
  if (!$proven) array_push($unmeasured, "SOUND", "TRICK");
  if ($bot === null) $unmeasured[] = "BEATS_BOT";
  return PuzzleInterest([
    "SOUND" => $proven ? 1 : 0,
    "NOT_OBVIOUS" => PuzzleBand($gap, [[0, 0], [1, 0.5], [2, 0.75], [PHP_INT_MAX, 1]]),
    "BEATS_BOT" => $bot === null || $bot["won"] ? 0 : PuzzleBand($life - $bot["damage"], [[1, 0.5], [2, 0.75], [PHP_INT_MAX, 1]]),
    "TRICK" => PuzzleTrickShare($lesson),
    "EXACT" => $margin === null ? 0 : PuzzleBand($margin, [[0, 1], [1, 0.7], [2, 0.4], [PHP_INT_MAX, 0]]),
    "ECONOMY" => $spare === null ? 0 : PuzzleBand($spare, [[1, 1], [2, 0.6], [3, 0.3], [PHP_INT_MAX, 0]]),
    "DEPTH" => $length === null ? 0 : PuzzleBand($length, [[1, 0], [2, 0.6], [5, 1], [7, 0.7], [PHP_INT_MAX, 0.4]]),
    "READABLE" => PuzzleBand($options, [[2, 0], [8, 1], [10, 0.6], [PHP_INT_MAX, 0.3]]),
    "FAIR" => PuzzleUsesHiddenCards($steps, $lesson) ? 0 : 1,
    "STAKES" => PuzzleBand($needed, [[7, 0], [11, 0.6], [PHP_INT_MAX, 1]])
  ], $unmeasured, $tooEasy);
}

// The survive proof lowers the life until the defense is exact, and holding cards back is the point, so exact and
// economy do not apply.
function PuzzleSurviveInterest($proven, $gap, $bot, $options, $incoming, $lesson, $steps, $tooEasy)
{
  $unmeasured = [];
  if (!$proven) array_push($unmeasured, "SOUND", "TRICK", "DEPTH");
  if ($bot === null) $unmeasured[] = "BEATS_BOT";
  $decisions = PuzzleDecisions($steps, ["BLOCK", "PLAY", "ACTIVATE"]);
  return PuzzleInterest([
    "SOUND" => $proven ? 1 : 0,
    "NOT_OBVIOUS" => PuzzleBand($gap, [[-3, 0], [-1, 0.5], [0, 0.75], [PHP_INT_MAX, 1]]),
    "BEATS_BOT" => $bot === null || $bot["won"] ? 0 : 1,
    "TRICK" => PuzzleTrickShare($lesson),
    "DEPTH" => PuzzleBand($decisions, [[0, 0], [1, 0.3], [2, 0.7], [5, 1], [PHP_INT_MAX, 0.7]]),
    "READABLE" => PuzzleBand($options, [[2, 0], [8, 1], [10, 0.6], [PHP_INT_MAX, 0.3]]),
    "FAIR" => PuzzleUsesHiddenCards($steps, $lesson) ? 0 : 1,
    "STAKES" => PuzzleBand($incoming, [[5, 0], [8, 0.6], [PHP_INT_MAX, 1]])
  ], $unmeasured, $tooEasy);
}
