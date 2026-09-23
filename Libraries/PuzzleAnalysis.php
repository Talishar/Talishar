<?php

include_once __DIR__ . "/../Constants.php";
include_once __DIR__ . "/../GeneratedCode/GeneratedCardDictionaries.php";
require_once __DIR__ . "/GamestateCompatibility.php";

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

function PuzzleZoneSize($line, $pieces)
{
  $line = trim($line);
  return $line === "" ? 0 : intdiv(count(explode(" ", $line)), $pieces);
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

function PuzzleBestAttackLine($hand, $arsenal, $weapons, $floating, $actionPoints)
{
  $attacks = [];
  foreach ($hand as $card) if ($card["type"] == "AA") $attacks[] = $card + ["fromHand" => true];
  foreach ($arsenal as $card) if ($card["type"] == "AA") $attacks[] = $card + ["fromHand" => false];
  foreach ($weapons as $card) $attacks[] = ["cost" => PUZZLE_WEAPON_COST, "fromHand" => false] + $card;
  usort($attacks, fn($a, $b) => $b["power"] <=> $a["power"]);
  $attacks = array_slice($attacks, 0, PUZZLE_MAX_ATTACKS_SEARCHED);
  $handPitch = array_sum(array_column($hand, "pitch"));
  $best = ["damage" => 0, "attacks" => 0, "totalCost" => array_sum(array_column($attacks, "cost"))];
  $count = count($attacks);
  for ($mask = 1; $mask < (1 << $count); ++$mask) {
    $damage = 0;
    $cost = 0;
    $used = 0;
    $goAgain = 0;
    $pitchLost = 0;
    for ($i = 0; $i < $count; ++$i) {
      if (!($mask & (1 << $i))) continue;
      $damage += $attacks[$i]["power"];
      $cost += $attacks[$i]["cost"];
      ++$used;
      if ($attacks[$i]["goAgain"]) ++$goAgain;
      if ($attacks[$i]["fromHand"]) $pitchLost += $attacks[$i]["pitch"];
    }
    if ($used - $goAgain > $actionPoints || $cost > $floating + $handPitch - $pitchLost) continue;
    if ($damage > $best["damage"] || ($damage == $best["damage"] && $used < $best["attacks"])) {
      $best["damage"] = $damage;
      $best["attacks"] = $used;
    }
  }
  return $best;
}

function PuzzleBand($value, $bands)
{
  foreach ($bands as [$limit, $score]) if ($value <= $limit) return $score;
  return end($bands)[1];
}

function AnalyzePuzzlePosition($content, $player, $meta)
{
  $lines = explode("\r\n", $content);
  $opponent = $player == 1 ? 2 : 1;
  $offset = ($player - 1) * 18;
  $opponentOffset = ($opponent - 1) * 18;
  $healths = explode(" ", trim($lines[0]));
  $resources = explode(" ", trim($lines[4 + $offset]));

  $hand = PuzzleZoneCards($lines[1 + $offset], HandPieces());
  $arsenal = PuzzleZoneCards($lines[5 + $offset], ArsenalPieces());
  $weapons = PuzzleCharacterCards($lines[3 + $offset], "W");
  $opponentEquipment = PuzzleCharacterCards($lines[3 + $opponentOffset], "E");
  $floating = intval($resources[0] ?? 0);
  $actionPoints = max(1, intval(trim($lines[43])));
  $life = intval($healths[$opponent - 1] ?? 0);
  $defense = array_sum(array_column($opponentEquipment, "defense"));
  $handPitch = array_sum(array_column($hand, "pitch"));
  $line = PuzzleBestAttackLine($hand, $arsenal, $weapons, $floating, $actionPoints);
  $options = count($hand) + count($arsenal) + count($weapons);
  $margin = $line["damage"] - $defense - $life;
  $available = $floating + $handPitch;

  $flags = [];
  $lifeScore = PuzzleBand($life, [[2, 0.1], [4, 0.5], [12, 1.0], [20, 0.8], [PHP_INT_MAX, 0.4]]);
  if ($life <= 2) $flags[] = ["code" => "LOW_LIFE", "value" => $life];
  else if ($life > 20) $flags[] = ["code" => "HIGH_LIFE", "value" => $life];
  $optionScore = PuzzleBand($options, [[1, 0.0], [2, 0.4], [3, 0.7], [PHP_INT_MAX, 1.0]]);
  if ($options <= 2) $flags[] = ["code" => "FEW_OPTIONS", "value" => $options];
  $resourceRatio = $available > 0 ? $line["totalCost"] / $available : 0;
  $resourceScore = $resourceRatio > 1 ? 1.0 : ($resourceRatio >= 0.6 ? 0.7 : 0.4);
  if ($resourceRatio > 1) $flags[] = ["code" => "RESOURCE_TIGHT", "value" => $available];

  if (is_array($meta)) {
    $overkill = intval($meta["overkill"] ?? 0);
    $cardsPlayed = intval($meta["cardsPlayed"] ?? 0);
    $tightScore = PuzzleBand($overkill, [[0, 1.0], [2, 0.8], [5, 0.4], [PHP_INT_MAX, 0.1]]);
    $complexityScore = PuzzleBand($cardsPlayed, [[1, 0.1], [2, 0.5], [4, 0.9], [PHP_INT_MAX, 1.0]]);
    if ($overkill == 0) $flags[] = ["code" => "EXACT_LETHAL", "value" => 0];
    else if ($overkill >= 5) $flags[] = ["code" => "OVERKILL", "value" => $overkill];
    if ($cardsPlayed <= 1) $flags[] = ["code" => "ONE_CARD", "value" => $cardsPlayed];
    else if ($cardsPlayed >= 5) $flags[] = ["code" => "BIG_TURN", "value" => $cardsPlayed];
    $emptiedOverkill = intval($meta["threatened"] ?? 0) - $defense - $life;
    if (intval($meta["blocked"] ?? 0) > $defense && $emptiedOverkill >= 3) {
      $flags[] = ["code" => "EASY_WITHOUT_HAND", "value" => $emptiedOverkill];
    }
  } else {
    $tightScore = PuzzleBand($margin, [[-3, 0.6], [1, 1.0], [5, 0.5], [PHP_INT_MAX, 0.2]]);
    $complexityScore = PuzzleBand($line["attacks"], [[1, 0.3], [2, 0.6], [PHP_INT_MAX, 0.9]]);
    $flags[] = ["code" => "NO_TURN_DATA", "value" => 0];
  }
  if ($margin < 0) $flags[] = ["code" => "NEEDS_EFFECTS", "value" => -$margin];
  else if ($margin >= 6) $flags[] = ["code" => "RAW_POWER", "value" => $margin];

  $score = 100 * (0.2 * $lifeScore + 0.2 * $optionScore + 0.3 * $tightScore
    + 0.2 * $complexityScore + 0.1 * $resourceScore);
  $penalties = ["LOW_LIFE" => 0.5, "ONE_CARD" => 0.5, "FEW_OPTIONS" => 0.6, "OVERKILL" => 0.6, "RAW_POWER" => 0.6, "HIGH_LIFE" => 0.7];
  $trivial = false;
  foreach ($flags as $flag) {
    $score *= $penalties[$flag["code"]] ?? 1.0;
    if (in_array($flag["code"], ["LOW_LIFE", "ONE_CARD", "OVERKILL", "RAW_POWER"], true)) $trivial = true;
  }
  if ($trivial || $optionScore == 0.0) $difficulty = "easy";
  else if ($tightScore >= 0.8 && $complexityScore >= 0.9 && $optionScore >= 0.7) $difficulty = "hard";
  else $difficulty = "medium";
  $score = (int)round($score);

  return [
    "life" => intval($healths[$player - 1] ?? 0),
    "opponentLife" => $life,
    "hand" => $hand,
    "arsenal" => $arsenal,
    "weapons" => $weapons,
    "floating" => $floating,
    "actionPoints" => $actionPoints,
    "handPitch" => $handPitch,
    "opponentEquipment" => $opponentEquipment,
    "opponentDefense" => $defense,
    "opponentHandCount" => PuzzleZoneSize($lines[1 + $opponentOffset], HandPieces()),
    "opponentArsenalCount" => PuzzleZoneSize($lines[5 + $opponentOffset], ArsenalPieces()),
    "estimatedDamage" => $line["damage"],
    "estimatedAttacks" => $line["attacks"],
    "realTurn" => is_array($meta) ? $meta : null,
    "score" => $score,
    "difficulty" => $difficulty,
    "flags" => $flags
  ];
}
