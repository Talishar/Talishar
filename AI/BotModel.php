<?php

include_once __DIR__ . "/BotCardFacts.php";

const BOT_DRAW_VALUE = 3.25;
const BOT_HIT_CHANCE = 0.6;
const BOT_TERMINAL_FACTOR = 0.6;
const BOT_TYPICAL_INCOMING = 4;
const BOT_BLOCK_USE_RATE = 0.75;
const BOT_PITCH_FUNDING_RATE = 0.75;
const BOT_DISRUPTION_BASE = 0.05;
const BOT_DISRUPTION_CAP = 0.3;
const BOT_DISRUPTION_HIT_CHANCE = 0.5;

function BotCardFacts($cardID)
{
  return BotCardFactsData()[$cardID] ?? [];
}

function BotCardUses($cardID, $playerID)
{
  static $cache = [];
  $key = $playerID . "|" . $cardID;
  if (isset($cache[$key])) return $cache[$key];
  $facts = BotCardFacts($cardID);
  $kind = match (CardType($cardID)) {
    "AA" => "attack",
    "A" => "action",
    "AR" => "attack-reaction",
    "I" => "instant",
    "DR" => "defense-reaction",
    "B" => "block",
    "E" => "equipment",
    "W" => "weapon",
    default => "other",
  };
  $isPlayable = in_array($kind, ["attack", "action", "attack-reaction", "instant"], true);
  return $cache[$key] = [
    "kind" => $kind,
    "power" => $kind == "attack" ? max(0, intval(PowerValue($cardID, $playerID, "HAND", -1, false, false))) : 0,
    "defense" => max(0, intval(BlockValue($cardID, $playerID, "HAND", false))),
    "pitch" => max(0, intval(PitchValue($cardID))),
    "cost" => BotNumericCost(CardCost($cardID, "HAND")),
    "goAgain" => ($kind == "attack" || $kind == "action") && HasGoAgain($cardID, "HAND"),
    "pump" => $isPlayable && $kind != "attack" ? floatval($facts["p"] ?? 0) : 0.0,
    "pumpNext" => !empty($facts["pn"]),
    "pumpTarget" => $facts["pt"] ?? "any",
    "pumpGoAgain" => !empty($facts["pga"]),
    "ambush" => (bool)HasAmbush($cardID, $playerID),
    "arsenalPlay" => !empty($facts["ap"]),
    "alternative" => min(floatval($facts["pr"] ?? 0), BOT_TYPICAL_INCOMING) * BOT_BLOCK_USE_RATE,
  ];
}

function BotOffenseValue($uses, $hitChance = BOT_HIT_CHANCE)
{
  if ($uses["kind"] == "attack") return $uses["goAgain"] ? $uses["power"] : $uses["power"] * BOT_TERMINAL_FACTOR;
  return $uses["pump"] + ($uses["pumpGoAgain"] ? 2.0 : 0.0);
}

function BotBlockUseValue($uses)
{
  return min($uses["defense"], BOT_TYPICAL_INCOMING) * ($uses["kind"] == "defense-reaction" ? 0.9 : BOT_BLOCK_USE_RATE);
}

function BotStandaloneValue($uses)
{
  return max(BotOffenseValue($uses), BotBlockUseValue($uses), $uses["pitch"] * BOT_PITCH_FUNDING_RATE, $uses["alternative"]);
}

function BotArsenalValue($uses, $discount)
{
  switch ($uses["kind"]) {
    case "attack":
    case "attack-reaction":
    case "action":
    case "instant":
      $value = BotOffenseValue($uses);
      if ($value == 0) $value = $uses["pitch"] * 0.25;
      if ($uses["ambush"]) $value = max($value, BotBlockUseValue($uses));
      break;
    case "defense-reaction":
      $value = BotBlockUseValue($uses);
      break;
    default:
      return -BOT_DRAW_VALUE;
  }
  $share = $uses["kind"] == "defense-reaction" ? 0.5 : 1.0;
  $stranded = $uses["pitch"] >= 3 && $uses["kind"] != "defense-reaction" && !$uses["ambush"] && !$uses["arsenalPlay"];
  return $value * (1 - $discount * $share) - ($stranded ? BOT_DRAW_VALUE : 0.0);
}

function BotAverageDrawValue($playerID)
{
  static $cache = [];
  $deck = &GetDeck($playerID);
  $key = $playerID . "|" . count($deck) . "|" . ($deck[0] ?? "");
  if (isset($cache[$key])) return $cache[$key];
  $total = 0.0;
  $count = 0;
  $pieces = DeckPieces();
  for ($i = 0, $deckCount = count($deck); $i < $deckCount; $i += $pieces) {
    if ($deck[$i] === "") continue;
    $total += BotStandaloneValue(BotCardUses($deck[$i], $playerID));
    ++$count;
  }
  return $cache[$key] = $count > 0 ? $total / $count : BOT_DRAW_VALUE;
}

function BotUnseenCards($playerID)
{
  $cards = [];
  $deck = &GetDeck($playerID);
  $pieces = DeckPieces();
  for ($i = 0, $count = count($deck); $i < $count; $i += $pieces) {
    if ($deck[$i] !== "") $cards[] = $deck[$i];
  }
  $hand = &GetHand($playerID);
  foreach ($hand as $cardID) {
    if ($cardID !== "") $cards[] = $cardID;
  }
  $arsenal = &GetArsenal($playerID);
  $arsenalPieces = ArsenalPieces();
  for ($i = 0, $count = count($arsenal); $i < $count; $i += $arsenalPieces) {
    if (($arsenal[$i + 1] ?? "") == "DOWN") $cards[] = $arsenal[$i];
  }
  return $cards;
}

function BotDisruptionDiscount($playerID)
{
  static $cache = [];
  $opponent = 3 - $playerID;
  $unseen = BotUnseenCards($opponent);
  $key = $playerID . "|" . count($unseen);
  if (isset($cache[$key])) return $cache[$key];
  $total = count($unseen);
  $disruptors = 0;
  foreach ($unseen as $cardID) {
    if (!empty(BotCardFacts($cardID)["da"])) ++$disruptors;
  }
  if ($total == 0 || $disruptors == 0) return $cache[$key] = BOT_DISRUPTION_BASE;
  $character = &GetPlayerCharacter($opponent);
  $draws = min($total, max(1, intval(CharacterIntellect($character[0] ?? ""))));
  $missAll = 1.0;
  for ($i = 0; $i < $draws; ++$i) $missAll *= max(0, $total - $disruptors - $i) / ($total - $i);
  $chance = (1 - $missAll) * BOT_DISRUPTION_HIT_CHANCE;
  return $cache[$key] = max(BOT_DISRUPTION_BASE, min(BOT_DISRUPTION_CAP, $chance));
}

function BotCardOpportunityValue($cardID, $playerID)
{
  return BotStandaloneValue(BotCardUses($cardID, $playerID)) - BotAverageDrawValue($playerID);
}

function BotHeldCardValue($cardID, $playerID)
{
  return max(0.0, BotCardOpportunityValue($cardID, $playerID));
}

function BotArsenalCardValue($cardID, $playerID)
{
  return BotArsenalValue(BotCardUses($cardID, $playerID), BotDisruptionDiscount($playerID));
}

function BotOffensiveHandCards($playerID)
{
  $cards = [];
  $hand = &GetHand($playerID);
  foreach ($hand as $index => $cardID) {
    if ($cardID !== "") $cards[] = ["id" => $cardID, "zone" => "HAND", "index" => $index];
  }
  $arsenal = &GetArsenal($playerID);
  $arsenalPieces = ArsenalPieces();
  for ($i = 0, $count = count($arsenal); $i < $count; $i += $arsenalPieces) {
    if (($arsenal[$i] ?? "") === "" || ($arsenal[$i + 4] ?? 0) == 1) continue;
    $cards[] = ["id" => $arsenal[$i], "zone" => "ARS", "index" => $i];
  }
  return $cards;
}

function BotSolverWeapons($playerID, $currentTurn)
{
  $weapons = [];
  $character = &GetPlayerCharacter($playerID);
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    $cardID = $character[$i];
    if ($cardID === "" || CardType($cardID) != "W") continue;
    $status = intval($character[$i + 1] ?? 0);
    if ($status == 0 || ($currentTurn && ($status != 2 || intval($character[$i + 5] ?? 0) < 1))) continue;
    $power = max(0, intval(PowerValue($cardID, $playerID, "CHAR", $i, false, false)));
    if ($power == 0) continue;
    $weapons[] = [
      "id" => $cardID,
      "power" => $power,
      "cost" => BotNumericCost(AbilityCost($cardID)),
      "goAgain" => HasGoAgain($cardID) || AbilityHasGoAgain($cardID, "EQUIP"),
      "subtype" => strtolower(CardSubType($cardID)),
    ];
  }
  return $weapons;
}

function BotNextTurnContext($playerID)
{
  return ["ap" => 1, "resources" => 0, "weapons" => BotSolverWeapons($playerID, false), "key" => "next"];
}

function BotCurrentTurnContext($playerID)
{
  global $actionPoints;
  $resources = &GetResources($playerID);
  $ap = max(0, intval($actionPoints));
  $floating = max(0, intval($resources[0] ?? 0));
  $weapons = BotSolverWeapons($playerID, true);
  return [
    "ap" => $ap,
    "resources" => $floating,
    "weapons" => $weapons,
    "key" => "now:$ap:$floating:" . implode(",", array_column($weapons, "id")),
  ];
}

function BotPumpApplies($targets, $attack)
{
  foreach (explode("|", $targets) as $target) {
    if ($target == "any") return true;
    if ($attack["isAA"]) {
      if ($target == "aa") return true;
      if ($target == "c0" && $attack["cost"] == 0) return true;
      if ($target == "c1" && $attack["cost"] <= 1) return true;
      if ($target == "c2" && $attack["cost"] >= 2) return true;
    } else if (str_starts_with($target, "w")) {
      if ($target == "w") return true;
      foreach (explode(",", substr($target, 2)) as $subtype) {
        if (str_contains($attack["subtype"], $subtype)) return true;
      }
    }
  }
  return false;
}

function BotSolveHand($cards, $playerID, $context = null)
{
  static $cache = [];
  $context ??= BotNextTurnContext($playerID);
  $ids = [];
  foreach ($cards as $card) $ids[] = $card["zone"] . ":" . $card["id"];
  sort($ids);
  $cacheKey = $playerID . "|" . $context["key"] . "|" . implode(",", $ids);
  if (isset($cache[$cacheKey])) return $cache[$cacheKey];

  $average = BotAverageDrawValue($playerID);
  $discount = BotDisruptionDiscount($playerID);
  $items = [];
  $arsenalOpen = true;
  foreach ($cards as $card) {
    $uses = BotCardUses($card["id"], $playerID);
    if ($card["zone"] == "ARS") $arsenalOpen = false;
    $items[] = $uses + [
      "id" => $card["id"],
      "inHand" => $card["zone"] == "HAND",
      "held" => max(0.0, BotStandaloneValue($uses) - $average),
      "arsenal" => BotArsenalValue($uses, $discount),
      "isAA" => $uses["kind"] == "attack",
      "subtype" => strtolower(CardSubType($card["id"])),
    ];
  }
  $weapons = $context["weapons"];
  $resources = $context["resources"];
  $count = count($items);

  $leftoverMemo = [];
  $leftover = function ($mask, $weaponMask) use (&$leftoverMemo, $items, $weapons, $resources, $count, $arsenalOpen) {
    $key = $mask . ":" . $weaponMask;
    if (isset($leftoverMemo[$key])) return $leftoverMemo[$key];
    $cost = 0;
    foreach ($items as $index => $item) if ($mask & (1 << $index)) $cost += $item["cost"];
    foreach ($weapons as $index => $weapon) if ($weaponMask & (1 << $index)) $cost += $weapon["cost"];
    $need = max(0, $cost - $resources);
    $best = [0 => [0.0, 0]];
    for ($s = 1; $s <= $need; ++$s) $best[$s] = [INF, 0];
    foreach ($items as $index => $item) {
      if (($mask & (1 << $index)) || !$item["inHand"] || $item["pitch"] == 0) continue;
      for ($s = $need; $s >= 0; --$s) {
        if ($best[$s][0] === INF) continue;
        $to = min($need, $s + $item["pitch"]);
        $loss = $best[$s][0] + $item["held"];
        if ($loss < $best[$to][0]) $best[$to] = [$loss, $best[$s][1] | (1 << $index)];
      }
    }
    if ($best[$need][0] === INF) return $leftoverMemo[$key] = null;
    $pitched = $best[$need][1];
    $value = 0.0;
    $arsenalGain = 0.0;
    $slotOpen = $arsenalOpen;
    foreach ($items as $index => $item) {
      if (!$item["inHand"] && ($mask & (1 << $index))) $slotOpen = true;
    }
    for ($index = 0; $index < $count; ++$index) {
      if ($mask & (1 << $index) || $pitched & (1 << $index)) continue;
      $item = $items[$index];
      if (!$item["inHand"]) {
        $value += $item["arsenal"];
        continue;
      }
      $value += $item["held"];
      if ($slotOpen) $arsenalGain = max($arsenalGain, $item["arsenal"] - $item["held"]);
    }
    return $leftoverMemo[$key] = $value + $arsenalGain;
  };

  $pitchAvailable = function ($mask) use ($items) {
    $total = 0;
    foreach ($items as $index => $item) {
      if (!($mask & (1 << $index)) && $item["inHand"]) $total += $item["pitch"];
    }
    return $total;
  };
  $costOf = function ($mask, $weaponMask) use ($items, $weapons) {
    $cost = 0;
    foreach ($items as $index => $item) if ($mask & (1 << $index)) $cost += $item["cost"];
    foreach ($weapons as $index => $weapon) if ($weaponMask & (1 << $index)) $cost += $weapon["cost"];
    return $cost;
  };

  $memo = [];
  $roots = [];
  $search = function ($mask, $weaponMask, $ap, $chain, $pending, $depth)
    use (&$search, &$memo, &$roots, $items, $weapons, $resources, $count, $leftover, $pitchAvailable, $costOf) {
    $key = "$mask:$weaponMask:$ap:" . ($chain === null ? "-" : $chain["key"] . ($chain["ga"] ? "g" : "")) . ":" . implode(",", array_map(fn($p) => $p["key"], $pending));
    if ($depth > 0 && isset($memo[$key])) return $memo[$key];
    $stop = $leftover($mask, $weaponMask);
    $best = $stop === null ? [-INF, 0.0, 0.0] : [$stop, 0.0, $stop];
    if ($depth == 0) $roots["stop"] = $best[0];

    $consider = function ($moveKey, $damage, $nextMask, $nextWeaponMask, $nextAp, $nextChain, $nextPending)
      use (&$search, &$best, &$roots, $depth, $pitchAvailable, $costOf, $resources) {
      if ($costOf($nextMask, $nextWeaponMask) > $resources + $pitchAvailable($nextMask)) return;
      $result = $search($nextMask, $nextWeaponMask, $nextAp, $nextChain, $nextPending, $depth + 1);
      if ($result[0] === -INF) return;
      $value = $damage + $result[0];
      if ($depth == 0) $roots[$moveKey] = max($roots[$moveKey] ?? -INF, $value);
      if ($value > $best[0]) $best = [$value, $damage + $result[1], $result[2]];
    };

    $attack = function ($attackInfo, $moveKey, $nextMask, $nextWeaponMask) use ($ap, $pending, $consider) {
      $power = $attackInfo["power"];
      $goAgain = $attackInfo["goAgain"];
      $remaining = [];
      foreach ($pending as $pump) {
        if (BotPumpApplies($pump["target"], $attackInfo)) {
          $power += $pump["power"];
          if ($pump["goAgain"]) $goAgain = true;
        } else {
          $remaining[] = $pump;
        }
      }
      $chain = $attackInfo + ["ga" => $goAgain];
      $consider($moveKey, $power, $nextMask, $nextWeaponMask, $ap - 1 + ($goAgain ? 1 : 0), $chain, $remaining);
    };

    for ($index = 0; $index < $count; ++$index) {
      if ($mask & (1 << $index)) continue;
      $item = $items[$index];
      $nextMask = $mask | (1 << $index);
      $moveKey = "c" . $index;
      if ($item["kind"] == "attack" && $ap > 0) {
        $attack([
          "key" => $moveKey, "power" => $item["power"], "goAgain" => $item["goAgain"], "isAA" => true,
          "cost" => $item["cost"], "subtype" => $item["subtype"],
        ], $moveKey, $nextMask, $weaponMask);
      } else if ($item["pump"] > 0 && ($item["pumpNext"] || $item["kind"] == "action")) {
        if ($item["kind"] == "action" && $ap < 1) continue;
        $nextAp = $item["kind"] == "action" ? $ap - 1 + ($item["goAgain"] ? 1 : 0) : $ap;
        $nextPending = $pending;
        $nextPending[] = ["key" => $moveKey, "power" => $item["pump"], "target" => $item["pumpTarget"], "goAgain" => $item["pumpGoAgain"]];
        $consider($moveKey, 0.0, $nextMask, $weaponMask, $nextAp, $item["kind"] == "action" ? null : $chain, $nextPending);
      } else if ($item["pump"] > 0 && ($item["kind"] == "attack-reaction" || $item["kind"] == "instant") && $chain !== null) {
        if (!BotPumpApplies($item["pumpTarget"], $chain)) continue;
        $nextAp = $ap;
        $nextChain = $chain;
        if ($item["pumpGoAgain"] && !$chain["ga"]) {
          ++$nextAp;
          $nextChain["ga"] = true;
        }
        $consider($moveKey, $item["pump"], $nextMask, $weaponMask, $nextAp, $nextChain, $pending);
      }
    }
    if ($ap > 0) {
      foreach ($weapons as $index => $weapon) {
        if ($weaponMask & (1 << $index)) continue;
        $moveKey = "w" . $index;
        $attack([
          "key" => $moveKey, "power" => $weapon["power"], "goAgain" => $weapon["goAgain"], "isAA" => false,
          "cost" => $weapon["cost"], "subtype" => $weapon["subtype"],
        ], $moveKey, $mask, $weaponMask | (1 << $index));
      }
    }
    if ($depth > 0) $memo[$key] = $best;
    return $best;
  };

  $result = $search(0, 0, $context["ap"], null, [], 0);
  if ($result[0] === -INF) $result = [0.0, 0.0, 0.0];
  $firstMoves = [];
  foreach ($roots as $moveKey => $value) {
    if ($moveKey == "stop") {
      $firstMoves["stop"] = $value;
    } else if ($moveKey[0] == "w") {
      $firstMoves["W:" . $weapons[intval(substr($moveKey, 1))]["id"]] = $value;
    } else {
      $card = $cards[intval(substr($moveKey, 1))];
      $firstMoves[$card["zone"] . ":" . $card["id"]] = max($firstMoves[$card["zone"] . ":" . $card["id"]] ?? -INF, $value);
    }
  }
  if (count($cache) > 512) $cache = [];
  return $cache[$cacheKey] = [
    "value" => $result[0],
    "damage" => $result[1],
    "leftover" => $result[2],
    "first" => $firstMoves,
  ];
}

function BotResponseValue($playerID, $excluded = [])
{
  $cards = [];
  foreach (BotOffensiveHandCards($playerID) as $card) {
    if (!isset($excluded[$card["zone"] . ":" . $card["index"]])) $cards[] = $card;
  }
  return BotSolveHand($cards, $playerID)["value"];
}
