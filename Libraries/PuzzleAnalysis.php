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

function PuzzleBand($value, $bands)
{
  foreach ($bands as [$limit, $score]) if ($value <= $limit) return $score;
  return end($bands)[1];
}

function AnalyzePuzzlePosition($content, $player, $meta, $emptyOpponentHand = false, $raiseLife = false)
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
  $opponentHand = $emptyOpponentHand ? [] : PuzzleZoneCards($lines[1 + $opponentOffset], HandPieces());
  $opponentArsenal = $emptyOpponentHand ? [] : PuzzleZoneCards($lines[5 + $opponentOffset], ArsenalPieces());
  $floating = intval($resources[0] ?? 0);
  $actionPoints = max(1, intval(trim($lines[43])));
  $originalLife = intval($healths[$opponent - 1] ?? 0);

  $equipmentBlock = array_sum(array_column($opponentEquipment, "defense"));
  $blockers = PuzzleBlockers($opponentEquipment, $opponentHand, $opponentArsenal);
  $block = array_sum($blockers);
  $provenSlack = is_array($meta) ? intval($meta["overkill"] ?? 0) + intval($meta["blocked"] ?? 0) - $block : null;
  $lifeBonus = $raiseLife && $provenSlack > 0 ? $provenSlack : 0;
  $life = $originalLife + $lifeBonus;
  $needed = $life + $block;
  $cards = count($hand) + count($arsenal);
  $options = $cards + count($weapons);
  $line = PuzzleAttackLines($hand, $arsenal, $weapons, $floating, $actionPoints, $life, $blockers);
  $killsByPower = $line["killCards"] !== null;
  $margin = $killsByPower ? $line["through"] - $life : null;
  if ($provenSlack !== null && $provenSlack >= $lifeBonus) $margin = max($margin ?? 0, $provenSlack - $lifeBonus);

  $realPlayed = is_array($meta) ? intval($meta["cardsPlayed"] ?? 0) : null;
  $spare = $killsByPower ? $cards - $line["killCards"] : null;
  if ($realPlayed !== null) $spare = max($spare ?? 0, $cards - $realPlayed - intval($meta["pitched"] ?? 0));
  $killLength = $killsByPower ? $line["killAttacks"] : null;
  if ($realPlayed > 0) $killLength = min($killLength ?? $realPlayed, $realPlayed);

  $flags = [];
  $pressureScore = PuzzleBand($needed, [[4, 0.0], [7, 0.25], [10, 0.45], [14, 0.65], [18, 0.8], [23, 0.9], [PHP_INT_MAX, 1.0]]);
  if ($needed <= 7) $flags[] = ["code" => "LOW_PRESSURE", "value" => $needed];
  else if ($needed >= 20) $flags[] = ["code" => "HIGH_PRESSURE", "value" => $needed];
  $spareScore = $spare === null ? 0.6 : PuzzleBand($spare, [[0, 1.0], [1, 0.6], [2, 0.2], [PHP_INT_MAX, 0.0]]);
  if ($spare === 0) $flags[] = ["code" => "ALL_CARDS", "value" => $cards];
  else if ($spare !== null && $spare >= 2) $flags[] = ["code" => "SPARE_CARDS", "value" => $spare];
  $marginScore = $margin === null ? 0.9 : PuzzleBand($margin, [[0, 1.0], [2, 0.7], [5, 0.35], [PHP_INT_MAX, 0.1]]);
  if ($margin === null) $flags[] = ["code" => "UNPROVEN", "value" => $provenSlack === null ? 0 : -$provenSlack];
  else if ($margin == 0) $flags[] = ["code" => "EXACT_LETHAL", "value" => 0];
  else if ($margin >= 6) $flags[] = ["code" => "RAW_POWER", "value" => $margin];
  if ($lifeBonus > 0) $flags[] = ["code" => "LIFE_RAISED", "value" => $lifeBonus];
  $optionScore = PuzzleBand($options, [[1, 0.0], [2, 0.4], [3, 0.7], [PHP_INT_MAX, 1.0]]);
  if ($options <= 2) $flags[] = ["code" => "FEW_OPTIONS", "value" => $options];
  $lengthScore = $killLength === null ? 0.7 : PuzzleBand($killLength, [[1, 0.1], [2, 0.5], [3, 0.8], [PHP_INT_MAX, 1.0]]);
  if ($killLength !== null && $killLength <= 1) $flags[] = ["code" => "ONE_CARD", "value" => $killLength];
  if ($realPlayed >= 5) $flags[] = ["code" => "BIG_TURN", "value" => $realPlayed];
  if ($realPlayed === null) $flags[] = ["code" => "NO_TURN_DATA", "value" => 0];

  $score = 100 * (0.3 * $pressureScore + 0.3 * $spareScore + 0.15 * $marginScore
    + 0.1 * $optionScore + 0.15 * $lengthScore);
  $penalties = ["LOW_PRESSURE" => 0.5, "ONE_CARD" => 0.5, "SPARE_CARDS" => 0.6, "RAW_POWER" => 0.6, "FEW_OPTIONS" => 0.6];
  $trivial = false;
  foreach ($flags as $flag) {
    if (!isset($penalties[$flag["code"]])) continue;
    $score *= $penalties[$flag["code"]];
    $trivial = true;
  }
  $score = (int)round($score);
  if ($trivial) $difficulty = "easy";
  else if ($score >= 70 && $margin !== null && $spare !== null && $spare <= 1 && $needed >= 14) $difficulty = "hard";
  else if ($score >= 45) $difficulty = "medium";
  else $difficulty = "easy";

  return [
    "life" => intval($healths[$player - 1] ?? 0),
    "opponentLife" => $life,
    "lifeBonus" => $lifeBonus,
    "hand" => $hand,
    "arsenal" => $arsenal,
    "weapons" => $weapons,
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
    "provenSlack" => $provenSlack,
    "estimatedDamage" => $line["damage"],
    "estimatedThrough" => $line["through"],
    "estimatedAttacks" => $line["attacks"],
    "realTurn" => is_array($meta) ? $meta : null,
    "score" => $score,
    "difficulty" => $difficulty,
    "flags" => $flags
  ];
}
