<?php

include_once __DIR__ . "/../Constants.php";
include_once __DIR__ . "/../GeneratedCode/GeneratedCardDictionaries.php";
require_once __DIR__ . "/GamestateCompatibility.php";
include_once __DIR__ . "/PuzzleLesson.php";
include_once __DIR__ . "/PuzzleRubric.php";

const PUZZLE_WEAPON_COST = 1;
const PUZZLE_MAX_ATTACKS_SEARCHED = 10;

function PuzzleCard($cardID)
{
  return [
    "id" => $cardID,
    "name" => GeneratedCardName($cardID),
    "type" => GeneratedCardType($cardID),
    "cost" => max(0, intval(GeneratedCardCost($cardID))),
    "pitch" => max(0, intval(GeneratedPitchValue($cardID))),
    "power" => max(0, intval(GeneratedPowerValue($cardID))),
    "defense" => max(0, intval(GeneratedBlockValue($cardID))),
    "goAgain" => GeneratedGoAgain($cardID) == true
  ];
}

function PuzzleZoneCards($line, $pieces)
{
  $zone = trim($line) === "" ? [] : explode(" ", trim($line));
  $cards = [];
  for ($i = 0, $count = count($zone); $i < $count; $i += $pieces) $cards[] = PuzzleCard($zone[$i]);
  return $cards;
}

function PuzzleCharacterCards($line, $type)
{
  $character = NormalizeLegacyCharacterState(trim($line) === "" ? [] : explode(" ", trim($line)));
  $pieces = CharacterPieces();
  $cards = [];
  for ($i = $pieces, $count = count($character); $i < $count; $i += $pieces) {
    if (intval($character[$i + 1] ?? 0) == 0 || GeneratedCardType($character[$i]) != $type) continue;
    $card = PuzzleCard($character[$i]);
    $card["defense"] = max(0, $card["defense"] + intval($character[$i + 4] ?? 0));
    $cards[] = $card;
  }
  return $cards;
}

function PuzzleBlockers($equipment, $hand, $arsenal)
{
  $blockers = array_column($equipment, "defense");
  foreach ($hand as $card) $blockers[] = $card["defense"];
  foreach ($arsenal as $card) if ($card["type"] == "DR") $blockers[] = $card["defense"];
  $blockers = array_values(array_filter($blockers, fn($defense) => $defense > 0));
  rsort($blockers);
  return $blockers;
}

function PuzzlePrevented($powers, $blockers)
{
  $prevented = 0;
  foreach ($blockers as $defense) {
    $link = array_keys($powers, max($powers))[0];
    if ($powers[$link] <= 0) break;
    $blocked = min($defense, $powers[$link]);
    $powers[$link] -= $blocked;
    $prevented += $blocked;
  }
  return $prevented;
}

function PuzzleAttackLines($hand, $arsenal, $weapons, $floating, $actionPoints, $life, $blockers)
{
  $attacks = [];
  foreach ($hand as $index => $card) if ($card["type"] == "AA") $attacks[] = $card + ["handIndex" => $index, "isCard" => true];
  foreach ($arsenal as $card) if ($card["type"] == "AA") $attacks[] = $card + ["handIndex" => -1, "isCard" => true];
  foreach ($weapons as $card) $attacks[] = ["cost" => PUZZLE_WEAPON_COST, "handIndex" => -1, "isCard" => false] + $card;
  usort($attacks, fn($a, $b) => $b["power"] <=> $a["power"]);
  $attacks = array_slice($attacks, 0, PUZZLE_MAX_ATTACKS_SEARCHED);
  $best = ["damage" => 0, "through" => -1, "attacks" => 0, "killCards" => null, "killAttacks" => 0];
  $count = count($attacks);
  for ($mask = 1; $mask < (1 << $count); ++$mask) {
    $powers = [];
    $cost = 0;
    $withoutGoAgain = 0;
    $cardsPlayed = 0;
    $played = [];
    for ($i = 0; $i < $count; ++$i) {
      if (!($mask & (1 << $i))) continue;
      $powers[] = $attacks[$i]["power"];
      $cost += $attacks[$i]["cost"];
      if (!$attacks[$i]["goAgain"]) ++$withoutGoAgain;
      if ($attacks[$i]["isCard"]) ++$cardsPlayed;
      if ($attacks[$i]["handIndex"] >= 0) $played[$attacks[$i]["handIndex"]] = true;
    }
    if ($withoutGoAgain > $actionPoints) continue;
    $pitches = [];
    foreach ($hand as $index => $card) if (!isset($played[$index]) && $card["pitch"] > 0) $pitches[] = $card["pitch"];
    rsort($pitches);
    $deficit = $cost - $floating;
    $pitched = 0;
    while ($deficit > 0 && $pitched < count($pitches)) $deficit -= $pitches[$pitched++];
    if ($deficit > 0) continue;
    $damage = array_sum($powers);
    $through = $damage - PuzzlePrevented($powers, $blockers);
    if ($through > $best["through"] || ($through == $best["through"] && count($powers) < $best["attacks"])) {
      $best["damage"] = $damage;
      $best["through"] = $through;
      $best["attacks"] = count($powers);
    }
    $cardsUsed = $cardsPlayed + $pitched;
    if ($through >= $life && ($best["killCards"] === null || $cardsUsed < $best["killCards"])) {
      $best["killCards"] = $cardsUsed;
      $best["killAttacks"] = count($powers);
    }
  }
  $best["through"] = max(0, $best["through"]);
  return $best;
}

function PuzzleStepCard($cardID)
{
  $name = GeneratedCardName($cardID);
  if ($name === "") $name = $cardID;
  foreach (["red", "yellow", "blue"] as $color) {
    if (str_ends_with($cardID, "_$color")) return ["id" => $cardID, "name" => "$name ($color)"];
  }
  return ["id" => $cardID, "name" => $name];
}

function PuzzleSolution($encoded)
{
  $steps = json_decode($encoded ?? "", true);
  if (!is_array($steps)) return null;
  foreach ($steps as &$step) {
    foreach (["cards", "top", "bottom"] as $key) {
      if (isset($step[$key])) $step[$key] = array_map("PuzzleStepCard", $step[$key]);
    }
    if (!empty($step["target"])) $step["target"] = PuzzleStepCard($step["target"]);
    else unset($step["target"]);
  }
  return $steps;
}

function PuzzleBand($value, $bands)
{
  foreach ($bands as [$limit, $score]) if ($value <= $limit) return $score;
  return end($bands)[1];
}

function PuzzleBotSummary($baseline)
{
  $bot = $baseline["bot"] ?? null;
  if (!is_array($bot)) return null;
  return [
    "won" => !empty($bot["won"]),
    "damage" => intval($bot["damage"] ?? 0),
    "played" => array_map("PuzzleStepCard", $bot["played"] ?? []),
    "pitched" => array_map("PuzzleStepCard", $bot["pitched"] ?? []),
    "blocked" => array_map("PuzzleStepCard", $bot["blocked"] ?? [])
  ];
}

function PuzzleLessonView($lesson)
{
  if ($lesson === null) return null;
  $lesson["keyCards"] = array_map("PuzzleStepCard", $lesson["keyCards"]);
  return $lesson;
}

function PuzzleProofView($proof)
{
  if (!is_array($proof)) return null;
  if (isset($proof["blocks"])) $proof["spare"] = array_map("PuzzleStepCard", array_merge(...array_values($proof["blocks"])));
  unset($proof["blocks"]);
  return $proof;
}

function PuzzleDifficulty($score)
{
  return $score >= 70 ? "hard" : ($score >= 45 ? "medium" : "easy");
}

// Puzzles a player could solve by reading only the numbers on the cards, or that the bot solves, teach nothing:
// they are flagged and filtered. The rest rank by how much of the damage has to come from card text.
function AnalyzePuzzlePosition($content, $player, $meta, $proof, $baseline = null, $kind = PUZZLE_KIND_LETHAL, $steps = null)
{
  if ($kind == PUZZLE_KIND_SURVIVE) return AnalyzeSurvivePosition($content, $player, $meta, $proof, $baseline, $steps);
  $lines = explode("\r\n", $content);
  $opponent = $player == 1 ? 2 : 1;
  $offset = ($player - 1) * 18;
  $opponentOffset = ($opponent - 1) * 18;
  $healths = explode(" ", trim($lines[0]));
  $resources = explode(" ", trim($lines[4 + $offset]));

  $hand = PuzzleZoneCards($lines[1 + $offset], HandPieces());
  $arsenal = PuzzleZoneCards($lines[5 + $offset], ArsenalPieces());
  $weapons = PuzzleCharacterCards($lines[3 + $offset], "W");
  $equipment = PuzzleCharacterCards($lines[3 + $offset], "E");
  $opponentEquipment = PuzzleCharacterCards($lines[3 + $opponentOffset], "E");
  $opponentHand = PuzzleZoneCards($lines[1 + $opponentOffset], HandPieces());
  $opponentArsenal = PuzzleZoneCards($lines[5 + $opponentOffset], ArsenalPieces());
  $floating = intval($resources[0] ?? 0);
  $actionPoints = max(1, intval(trim($lines[43])));
  $realLife = intval($healths[$opponent - 1] ?? 0);
  $proven = is_array($proof) && ($proof["status"] ?? "") === "proven";
  $life = $proven ? intval($proof["life"]) : $realLife;

  $equipmentBlock = array_sum(array_column($opponentEquipment, "defense"));
  $blockers = PuzzleBlockers($opponentEquipment, $opponentHand, $opponentArsenal);
  $block = array_sum($blockers);
  $needed = $life + $block;
  $cards = count($hand) + count($arsenal);
  $options = $cards + count($weapons);
  $line = PuzzleAttackLines($hand, $arsenal, $weapons, $floating, $actionPoints, $life, $blockers);
  $killsByPower = $line["killCards"] !== null;
  $margin = $proven ? intval($proof["overkill"] ?? 0) : ($killsByPower ? $line["through"] - $life : null);
  if ($proven && $killsByPower) $margin = max($margin, $line["through"] - $life);

  $turn = $proven ? $proof : (is_array($meta) ? $meta : null);
  $realPlayed = $turn === null ? null : intval($turn["cardsPlayed"] ?? 0);
  $spare = $killsByPower ? $cards - $line["killCards"] : null;
  if ($realPlayed !== null) $spare = max($spare ?? 0, $cards - $realPlayed - intval($turn["pitched"] ?? 0));
  $killLength = $killsByPower ? $line["killAttacks"] : null;
  if ($realPlayed > 0) $killLength = min($killLength ?? $realPlayed, $realPlayed);

  // Before the proof an overkill means the life will be raised, so judge the plain line against that.
  $checkLife = $proven ? $life : $realLife + max(0, intval($meta["overkill"] ?? 0));
  $gap = $checkLife - $line["through"];
  $bot = PuzzleBotSummary($baseline);

  $flags = [];
  if ($gap <= 0) $flags[] = ["code" => "PLAIN_STATS", "value" => $line["through"]];
  else $flags[] = ["code" => "NEEDS_TEXT", "value" => $gap];
  if ($bot !== null) $flags[] = ["code" => $bot["won"] ? "BOT_SOLVES" : "BOT_FAILS", "value" => $bot["damage"]];
  if ($needed <= 7) $flags[] = ["code" => "LOW_PRESSURE", "value" => $needed];
  else if ($needed >= 20) $flags[] = ["code" => "HIGH_PRESSURE", "value" => $needed];
  if ($spare === 0) $flags[] = ["code" => "ALL_CARDS", "value" => $cards];
  else if ($spare !== null && $spare >= 2) $flags[] = ["code" => "SPARE_CARDS", "value" => $spare];
  if (!$proven) $flags[] = ["code" => "UNPROVEN", "value" => 0];
  if ($margin === 0) $flags[] = ["code" => "EXACT_LETHAL", "value" => 0];
  if ($proven && $life > $realLife) $flags[] = ["code" => "LIFE_RAISED", "value" => $life - $realLife];
  if ($options <= 2) $flags[] = ["code" => "FEW_OPTIONS", "value" => $options];
  if ($killLength !== null && $killLength <= 1) $flags[] = ["code" => "ONE_CARD", "value" => $killLength];
  if ($realPlayed >= 5) $flags[] = ["code" => "BIG_TURN", "value" => $realPlayed];
  if ($realPlayed === null) $flags[] = ["code" => "NO_TURN_DATA", "value" => 0];

  $gapScore = PuzzleBand($gap, [[0, 0.0], [1, 0.35], [2, 0.55], [3, 0.7], [5, 0.85], [PHP_INT_MAX, 1.0]]);
  $botScore = $bot === null ? 0.5 : ($bot["won"] ? 0.0 : PuzzleBand($life - $bot["damage"], [[1, 0.5], [3, 0.8], [PHP_INT_MAX, 1.0]]));
  $marginScore = $margin === null ? 0.9 : PuzzleBand($margin, [[0, 1.0], [2, 0.7], [5, 0.35], [PHP_INT_MAX, 0.1]]);
  $optionScore = PuzzleBand($options, [[1, 0.0], [2, 0.4], [3, 0.7], [PHP_INT_MAX, 1.0]]);
  $lengthScore = $killLength === null ? 0.7 : PuzzleBand($killLength, [[1, 0.1], [2, 0.5], [3, 0.8], [PHP_INT_MAX, 1.0]]);
  $score = 100 * (0.4 * $gapScore + 0.3 * $botScore + 0.1 * $marginScore + 0.1 * $optionScore + 0.1 * $lengthScore);
  $penalties = ["LOW_PRESSURE" => 0.8, "ONE_CARD" => 0.5, "FEW_OPTIONS" => 0.6];
  foreach ($flags as $flag) $score *= $penalties[$flag["code"]] ?? 1;
  $score = (int)round($score);
  $lesson = PuzzleLessonView(PuzzleLesson(PUZZLE_KIND_LETHAL, $steps, $baseline));
  $filtered = $gap <= 0 || ($bot["won"] ?? false);
  $interest = PuzzleLethalInterest($proven, $gap, $bot, $life, $margin, $spare, $killLength, $options, $needed, $lesson, $steps, $filtered);

  return [
    "kind" => "lethal",
    "life" => intval($healths[$player - 1] ?? 0),
    "opponentLife" => $life,
    "realLife" => $realLife,
    "hand" => $hand,
    "arsenal" => $arsenal,
    "weapons" => $weapons,
    "equipment" => $equipment,
    "floating" => $floating,
    "actionPoints" => $actionPoints,
    "handPitch" => array_sum(array_column($hand, "pitch")),
    "opponentEquipment" => $opponentEquipment,
    "opponentHand" => $opponentHand,
    "opponentEquipmentBlock" => $equipmentBlock,
    "opponentHandBlock" => $block - $equipmentBlock,
    "opponentBlock" => $block,
    "needed" => $needed,
    "spareCards" => $spare,
    "proof" => PuzzleProofView($proof),
    "estimatedDamage" => $line["damage"],
    "estimatedThrough" => $line["through"],
    "estimatedAttacks" => $line["attacks"],
    "gap" => $gap,
    "bot" => $bot,
    "filtered" => $filtered,
    "lesson" => $lesson,
    "realTurn" => is_array($meta) ? $meta : null,
    "score" => $score,
    "interest" => $interest["percent"],
    "rubric" => $interest,
    "difficulty" => PuzzleDifficulty($score),
    "flags" => $flags
  ];
}

// Blocking by the numbers is exactly what the bot does, so for a survive puzzle the bot is the plain-stats test:
// if it lives through the attack by blocking greedily, the puzzle teaches nothing. The defense margin only ranks.
function AnalyzeSurvivePosition($content, $player, $meta, $proof, $baseline, $steps)
{
  $lines = explode("\r\n", $content);
  $opponent = 3 - $player;
  $offset = ($player - 1) * 18;
  $opponentOffset = ($opponent - 1) * 18;
  $healths = explode(" ", trim($lines[0]));
  $hand = PuzzleZoneCards($lines[1 + $offset], HandPieces());
  $arsenal = PuzzleZoneCards($lines[5 + $offset], ArsenalPieces());
  $equipment = PuzzleCharacterCards($lines[3 + $offset], "E");
  $realLife = intval($healths[$player - 1] ?? 0);
  $proven = is_array($proof) && ($proof["status"] ?? "") === "proven";
  $life = $proven ? intval($proof["life"]) : $realLife;
  $incoming = intval(($proven ? $proof : $meta)["threatened"] ?? 0);
  $defense = array_sum(PuzzleBlockers($equipment, $hand, $arsenal));
  $gap = $incoming - $defense - $life + 1;
  $bot = PuzzleBotSummary($baseline);
  $options = count($hand) + count($arsenal) + count($equipment);

  $flags = [];
  if ($bot !== null) $flags[] = ["code" => $bot["won"] ? "BOT_SOLVES" : "BOT_FAILS", "value" => $bot["damage"]];
  if (!$proven) $flags[] = ["code" => "UNPROVEN", "value" => 0];
  if ($proven && $life < $realLife) $flags[] = ["code" => "LIFE_LOWERED", "value" => $realLife - $life];
  if ($options <= 2) $flags[] = ["code" => "FEW_OPTIONS", "value" => $options];
  if (($meta["cardsPlayed"] ?? 0) >= 4) $flags[] = ["code" => "BIG_TURN", "value" => intval($meta["cardsPlayed"])];

  $botScore = $bot === null ? 0.5 : ($bot["won"] ? 0.0 : 1.0);
  $gapScore = PuzzleBand($gap, [[-3, 0.0], [0, 0.3], [2, 0.6], [4, 0.8], [PHP_INT_MAX, 1.0]]);
  $optionScore = PuzzleBand($options, [[1, 0.0], [2, 0.4], [3, 0.7], [PHP_INT_MAX, 1.0]]);
  $score = 100 * (0.6 * $botScore + 0.25 * $gapScore + 0.15 * $optionScore);
  if ($options <= 2) $score *= 0.6;
  $score = (int)round($score);
  $lesson = PuzzleLessonView(PuzzleLesson(PUZZLE_KIND_SURVIVE, $steps, $baseline));
  $filtered = $bot["won"] ?? false;
  $interest = PuzzleSurviveInterest($proven, $gap, $bot, $options, $incoming, $lesson, $steps, $filtered);

  return [
    "kind" => "survive",
    "life" => $life,
    "opponentLife" => $life,
    "realLife" => $realLife,
    "hand" => $hand,
    "arsenal" => $arsenal,
    "weapons" => [],
    "equipment" => $equipment,
    "floating" => 0,
    "actionPoints" => 0,
    "handPitch" => array_sum(array_column($hand, "pitch")),
    "opponentEquipment" => PuzzleCharacterCards($lines[3 + $opponentOffset], "E"),
    "opponentHand" => [],
    "opponentEquipmentBlock" => 0,
    "opponentHandBlock" => 0,
    "opponentBlock" => 0,
    "needed" => $incoming,
    "spareCards" => null,
    "proof" => PuzzleProofView($proof),
    "estimatedDamage" => $incoming,
    "estimatedThrough" => max(0, $incoming - $defense),
    "estimatedAttacks" => intval($meta["cardsPlayed"] ?? 0),
    "gap" => $gap,
    "bot" => $bot,
    "filtered" => $filtered,
    "lesson" => $lesson,
    "realTurn" => is_array($meta) ? $meta : null,
    "score" => $score,
    "interest" => $interest["percent"],
    "rubric" => $interest,
    "difficulty" => PuzzleDifficulty($score),
    "flags" => $flags
  ];
}
