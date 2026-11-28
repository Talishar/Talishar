<?php

include_once __DIR__ . "/BotCardFacts.php";
include_once __DIR__ . "/BotHeroRules.php";

const BOT_DRAW_VALUE = 3.25;
const BOT_HIT_CHANCE = 0.6;
const BOT_TERMINAL_FACTOR = 0.6;
const BOT_TYPICAL_INCOMING = 4;
const BOT_BLOCK_USE_RATE = 0.75;
const BOT_PITCH_FUNDING_RATE = 0.75;
const BOT_DISRUPTION_BASE = 0.05;
const BOT_DISRUPTION_CAP = 0.3;
const BOT_DISRUPTION_HIT_CHANCE = 0.5;
const BOT_TYPICAL_ATTACK_POWER = 4;
const BOT_MIN_HIT_CHANCE = 0.05;
const BOT_MAX_HIT_CHANCE = 0.95;
const BOT_DEFAULT_CARD_DEFENSE = 3;
const BOT_DEFAULT_BLOCK_PROPENSITY = 0.5;
const BOT_ARMOR_BLOCK_COST = 0.5;
const BOT_BLOCKING_DECK_CARD_DISCOUNT = 0.7;
const BOT_BLOCK_RESERVE = 1.5;
const BOT_BLOCK_SCALE = 2;
const BOT_BLOCKER_RELIABILITY = 0.9;
const BOT_PRESSURE_LIFE = 15;
const BOT_PITCH_TIE_WEIGHT = 0.01;

function BotCardFacts($cardID)
{
  static $merged = [];
  if (isset($merged[$cardID])) return $merged[$cardID];
  $facts = BotCardFactsData()[$cardID] ?? [];
  $override = BotCardFactOverrides()[$cardID] ?? null;
  return $merged[$cardID] = $override === null ? $facts : array_merge($facts, $override);
}

function BotOnHitFactValue($onHit)
{
  if (!is_array($onHit)) return 0.0;
  return floatval($onHit["s"] ?? 0) + floatval($onHit["d"] ?? 0) * BOT_DRAW_VALUE
    + floatval($onHit["h"] ?? 0) * floatval($onHit["hv"] ?? 0) + floatval($onHit["a"] ?? 0) * 4;
}

function BotCardUses($cardID, $playerID)
{
  static $cache = [];
  $key = $playerID . "|" . $cardID;
  if (isset($cache[$key])) return $cache[$key];
  $facts = BotCardFacts($cardID);
  $kind = isset(BOT_PLAYABLE_MELDS[$cardID]) ? "action" : match (CardType($cardID)) {
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
    "onHit" => $kind == "attack" ? BotOnHitFactValue($facts["oh"] ?? null) : 0.0,
    "bonus" => floatval($facts["bv"] ?? 0),
    "setFlags" => intval($facts["sf"] ?? 0),
    "charges" => ($kind == "attack" || $kind == "action") && GeneratedHasCharge($cardID),
    "yellowChargePower" => intval($facts["ycp"] ?? 0),
    "needFlags" => intval($facts["nf"] ?? 0),
    "goAgainFlags" => intval($facts["gf"] ?? 0),
  ];
}

function BotOffenseValue($uses, $hitChance = BOT_HIT_CHANCE)
{
  if ($uses["kind"] == "attack") {
    $onHit = $uses["onHit"] ?? 0.0;
    $chance = is_array($hitChance) ? BotHitChance($uses["power"], $onHit, $hitChance) : floatval($hitChance);
    $value = $uses["power"] + $onHit * $chance;
    return $uses["goAgain"] ? $value : $value * BOT_TERMINAL_FACTOR;
  }
  return $uses["pump"] + ($uses["pumpGoAgain"] ? 2.0 : 0.0) + ($uses["bonus"] ?? 0.0);
}

function BotBlockUseValue($uses)
{
  return min($uses["defense"], BOT_TYPICAL_INCOMING) * ($uses["kind"] == "defense-reaction" ? 0.9 : BOT_BLOCK_USE_RATE);
}

function BotStandaloneValue($uses, $hitChance = BOT_HIT_CHANCE)
{
  return max(BotOffenseValue($uses, $hitChance), BotBlockUseValue($uses), $uses["pitch"] * BOT_PITCH_FUNDING_RATE, $uses["alternative"]);
}

function BotArsenalValue($uses, $discount, $hitChance = BOT_HIT_CHANCE)
{
  switch ($uses["kind"]) {
    case "attack":
    case "attack-reaction":
    case "action":
    case "instant":
      $value = BotOffenseValue($uses, $hitChance);
      if ($value == 0) $value = $uses["pitch"] * 0.25;
      if ($uses["ambush"]) $value = max($value, BotBlockUseValue($uses));
      break;
    case "defense-reaction":
      $value = BotBlockUseValue($uses);
      break;
    case "block":
      if (!$uses["ambush"]) return -BOT_DRAW_VALUE;
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
  $profile = BotDefenderProfile($playerID, true);
  $key = $playerID . "|" . count($deck) . "|" . ($deck[0] ?? "") . "|" . $profile["key"];
  if (isset($cache[$key])) return $cache[$key];
  $total = 0.0;
  $count = 0;
  $pieces = DeckPieces();
  for ($i = 0, $deckCount = count($deck); $i < $deckCount; $i += $pieces) {
    if ($deck[$i] === "") continue;
    $total += BotStandaloneValue(BotCardUses($deck[$i], $playerID), $profile);
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
  return BotStandaloneValue(BotCardUses($cardID, $playerID), BotDefenderProfile($playerID, true)) - BotAverageDrawValue($playerID);
}

function BotHeldCardValue($cardID, $playerID)
{
  return max(0.0, BotCardOpportunityValue($cardID, $playerID));
}

function BotArsenalUses($uses, $cardID)
{
  if (str_starts_with($cardID, "lightning_surge_")) $uses["goAgain"] = true;
  return $uses;
}

function BotArsenalCardValue($cardID, $playerID)
{
  if (BotHeroCardDead($cardID, $playerID)) return -BOT_DRAW_VALUE;
  return BotArsenalValue(BotArsenalUses(BotCardUses($cardID, $playerID), $cardID), BotDisruptionDiscount($playerID), BotDefenderProfile($playerID, true));
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
    if (!BotWeaponSwingAllowed($cardID, $playerID)) continue;
    $power = BotWeaponSolverPower($cardID, $playerID, max(0, intval(PowerValue($cardID, $playerID, "CHAR", $i, false, false)) + intval($character[$i + 3] ?? 0)));
    if ($power == 0) continue;
    $weapon = BotHeroWeaponPatch([
      "id" => $cardID,
      "power" => $power,
      "cost" => BotNumericCost(AbilityCost($cardID)),
      "goAgain" => HasGoAgain($cardID) || AbilityHasGoAgain($cardID, "EQUIP"),
      "subtype" => strtolower(CardSubType($cardID)),
      "onHit" => BotOnHitFactValue(BotCardFacts($cardID)["oh"] ?? null),
      "brute" => ClassContains($cardID, "BRUTE", $playerID),
      "setFlags" => 0,
      "needFlags" => 0,
      "goAgainFlags" => 0,
    ], $playerID);
    $weapons[] = $weapon;
    if (BotHeroExtraWeaponSwing($cardID, $playerID, $currentTurn)) {
      $weapons[] = ["power" => $power * BOT_HIT_CHANCE, "onHit" => 0.0, "needFlags" => $weapon["needFlags"] | BOT_FLAG_WEAPON_SWUNG] + $weapon;
    }
    if (BotHeroFlurrySwing($cardID, $playerID)) {
      $weapons[] = ["needFlags" => $weapon["needFlags"] | BOT_FLAG_WEAPON_SWUNG | BOT_FLAG_FLURRY, "goAgainFlags" => 0] + $weapon;
    }
    if ($cardID == "raydn_duskbane") {
      $weapons[] = ["needFlags" => $weapon["needFlags"] | BOT_FLAG_WEAPON_SWUNG | BOT_FLAG_LUMINA | BOT_FLAG_CHARGED] + $weapon;
    }
  }
  foreach (BotHeroAllyAttackers($playerID, $currentTurn) as $attacker) $weapons[] = $attacker;
  return $weapons;
}

function BotAbilityItems($playerID, $currentTurn)
{
  $items = [];
  foreach (BotHeroSolverSources($playerID, $currentTurn) as $source) {
    $uses = BotHeroAbilityUses($source["id"]);
    if ($uses === null) continue;
    $items[] = $uses + [
      "id" => $source["id"], "zone" => "CHAR", "inHand" => false, "held" => 0.0, "arsenal" => 0.0,
      "isAA" => false, "subtype" => "",
    ];
  }
  return $items;
}

function BotSolverContext($playerID, $ap, $resources, $currentTurn)
{
  $weapons = BotSolverWeapons($playerID, $currentTurn);
  $profile = BotDefenderProfile($playerID, !$currentTurn);
  $abilities = BotAbilityItems($playerID, $currentTurn);
  $pending = BotHeroStartPending($playerID, $currentTurn);
  $flags = $currentTurn ? BotHeroStartFlags($playerID) : BotHeroNextTurnFlags($playerID);
  $flagValues = BotHeroFlagValues($playerID);
  $gates = BotHeroGateCards($playerID);
  $briar = BotIsBriar($playerID);
  return [
    "briar" => $briar,
    "gates" => $gates,
    "ap" => $ap,
    "resources" => $resources,
    "weapons" => $weapons,
    "profile" => $profile,
    "abilities" => $abilities,
    "pending" => $pending,
    "flags" => $flags,
    "flagValues" => $flagValues,
    "key" => ($currentTurn ? "now" : "next") . ":$ap:$resources:" . implode(",", array_map(fn($weapon) => $weapon["id"] . $weapon["needFlags"], $weapons))
      . ":" . $profile["key"] . ":" . implode(",", array_column($abilities, "id")) . ":" . count($pending) . ":$flags:" . json_encode($flagValues)
      . ":" . implode(",", array_column($gates, "id")) . ($briar ? ":briar" : ""),
  ];
}

function BotContextStateKey($playerID, $currentTurn)
{
  global $actionPoints, $currentTurnEffects, $turn;
  $opponent = 3 - $playerID;
  $parts = [
    $playerID, $currentTurn ? 1 : 0, implode(" ", GetPlayerCharacter($playerID)), implode(" ", GetAuras($playerID)), implode(" ", GetAllies($playerID)),
    count(GetBanish($playerID)), count(GetHand($opponent)), GetHealth($opponent), implode(" ", GetPlayerCharacter($opponent)),
  ];
  if ($currentTurn) {
    array_push($parts, $actionPoints, implode(" ", GetResources($playerID)), implode(" ", $currentTurnEffects), $turn[0] ?? "",
      implode(" ", GetHand($playerID)), json_encode($GLOBALS["p" . $playerID . "ClassState"] ?? []), BotChainStartFlags($playerID));
  }
  return md5(implode("|", $parts));
}

function BotNextTurnContext($playerID)
{
  static $cache = [];
  $key = BotContextStateKey($playerID, false);
  if (count($cache) > 64) $cache = [];
  return $cache[$key] ??= BotSolverContext($playerID, 1, BotHeroNextTurnResources($playerID), false);
}

function BotCurrentTurnContext($playerID)
{
  global $actionPoints;
  static $cache = [];
  $key = BotContextStateKey($playerID, true);
  if (count($cache) > 64) $cache = [];
  $resources = &GetResources($playerID);
  return $cache[$key] ??= BotSolverContext($playerID, max(0, intval($actionPoints)), max(0, intval($resources[0] ?? 0)), true);
}

function BotPumpApplies($targets, $attack)
{
  foreach (explode("|", $targets) as $target) {
    if ($target == "any") return true;
    if ($target == "pumped") {
      if (!empty($attack["pumped"])) return true;
      continue;
    }
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
  $futureProfile = BotDefenderProfile($playerID, true);
  $profile = $context["profile"];
  $items = [];
  $arsenalOpen = true;
  foreach ($cards as $card) {
    $uses = BotCardUses($card["id"], $playerID);
    if ($card["zone"] == "ARS") $arsenalOpen = false;
    $items[] = BotHeroCardPatch($uses + [
      "id" => $card["id"],
      "zone" => $card["zone"],
      "inHand" => $card["zone"] == "HAND",
      "held" => max(0.0, BotStandaloneValue($uses, $futureProfile) - $average) + BotHeroHeldBonus($card["id"], $playerID),
      "raw" => BotStandaloneValue($uses, $futureProfile),
      "arsenal" => BotArsenalValue(BotArsenalUses($uses, $card["id"]), $discount, $futureProfile),
      "isAA" => $uses["kind"] == "attack",
      "subtype" => strtolower(CardSubType($card["id"])),
    ], $playerID, $context);
  }
  foreach ($context["abilities"] as $ability) {
    $items[] = $ability;
    $cards[] = ["id" => $ability["id"], "zone" => "CHAR", "index" => 0];
  }
  foreach ($context["gates"] ?? [] as $gate) {
    $uses = BotCardUses($gate["id"], $playerID);
    $items[] = BotHeroCardPatch($uses + [
      "id" => $gate["id"], "zone" => "GATE", "inHand" => false, "held" => 0.0, "raw" => 0.0, "arsenal" => 0.0,
      "isAA" => $uses["kind"] == "attack", "subtype" => strtolower(CardSubType($gate["id"])),
    ], $playerID, $context);
    $items[count($items) - 1]["cost"] += 1;
    $cards[] = $gate;
  }
  $twinBits = [];
  foreach ($items as $index => $item) {
    $twin = BotHeroBanishTwin($item, $playerID);
    if ($twin === null) continue;
    $twinIndex = count($items);
    $items[$index]["twin"] = $twinIndex;
    $items[] = $twin + ["twin" => $index];
    $cards[] = ["id" => $item["id"], "zone" => "BANISH", "index" => $cards[$index]["index"] ?? 0];
    $twinBits[$twinIndex] = $index;
  }
  $usedMask = function ($mask) use ($twinBits) {
    foreach ($twinBits as $twin => $original) if ($mask & (1 << $twin)) $mask |= 1 << $original;
    return $mask;
  };
  $weapons = $context["weapons"];
  $resources = $context["resources"];
  $count = count($items);

  $leftoverMemo = [];
  $leftover = function ($mask, $weaponMask) use (&$leftoverMemo, $items, $weapons, $resources, $count, $arsenalOpen, $usedMask) {
    $key = $mask . ":" . $weaponMask;
    if (isset($leftoverMemo[$key])) return $leftoverMemo[$key];
    $cost = 0;
    foreach ($items as $index => $item) if ($mask & (1 << $index)) $cost += $item["cost"];
    foreach ($weapons as $index => $weapon) if ($weaponMask & (1 << $index)) $cost += $weapon["cost"];
    $need = max(0, $cost - $resources);
    $mask = $usedMask($mask);
    $best = [0 => [0.0, 0]];
    for ($s = 1; $s <= $need; ++$s) $best[$s] = [INF, 0];
    foreach ($items as $index => $item) {
      if (($mask & (1 << $index)) || !$item["inHand"] || $item["pitch"] == 0) continue;
      for ($s = $need; $s >= 0; --$s) {
        if ($best[$s][0] === INF) continue;
        $to = min($need, $s + $item["pitch"]);
        $loss = $best[$s][0] + $item["held"] + $item["raw"] * BOT_PITCH_TIE_WEIGHT;
        if ($loss < $best[$to][0]) $best[$to] = [$loss, $best[$s][1] | (1 << $index)];
      }
    }
    if ($best[$need][0] === INF) return $leftoverMemo[$key] = null;
    $pitched = $best[$need][1];
    $value = 0.0;
    $arsenalGain = 0.0;
    $slotOpen = $arsenalOpen;
    foreach ($items as $index => $item) {
      if ($item["zone"] == "ARS" && ($mask & (1 << $index))) $slotOpen = true;
    }
    for ($index = 0; $index < $count; ++$index) {
      if ($mask & (1 << $index) || $pitched & (1 << $index)) continue;
      $item = $items[$index];
      if ($item["zone"] == "CHAR" || $item["zone"] == "BANISH" || $item["zone"] == "GATE") continue;
      if (!$item["inHand"]) {
        $value += $item["arsenal"];
        continue;
      }
      $value += $item["held"];
      if ($slotOpen) $arsenalGain = max($arsenalGain, $item["arsenal"] - $item["held"]);
    }
    return $leftoverMemo[$key] = $value + $arsenalGain;
  };

  $pitchAvailable = function ($mask) use ($items, $usedMask) {
    $mask = $usedMask($mask);
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
  $flagsMet = fn($flags, $needed) => ($flags & $needed) == $needed;
  $flagValues = $context["flagValues"] ?? [];
  $briar = !empty($context["briar"]);
  $conditions = [];
  foreach (array_merge($items, $weapons) as $entry) $conditions[$entry["cond"]["ga"] ?? ""] = true;
  $keyMask = ~0;
  if (!isset($conditions["lastLightning"])) $keyMask &= ~BOT_FLAG_LAST_ACTION_LIGHTNING;
  if (!isset($conditions["instant"])) $keyMask &= ~BOT_FLAG_INSTANT_CHAIN;
  if (!isset($conditions["dagger"])) $keyMask &= ~(BOT_FLAG_DAGGER_ATTACKED | BOT_FLAG_DAGGER_HIT);
  if (!isset($conditions["dealt"])) $keyMask &= ~BOT_FLAG_DAMAGE_DEALT;
  if (!$briar) $keyMask &= ~(BOT_COUNTER_MAX << BOT_NAA_SHIFT);
  $actionPending = function ($pending, $flags, $nextFlags) use ($briar) {
    if ($briar && BotCounter($flags, BOT_NAA_SHIFT) == 1 && BotCounter($nextFlags, BOT_NAA_SHIFT) == 2) {
      $pending[] = ["key" => "briarEmbodiment", "power" => 0.0, "target" => "aa", "goAgain" => true];
    }
    return $pending;
  };

  $memo = [];
  $roots = [];
  $search = function ($mask, $weaponMask, $ap, $chain, $pending, $flags, $depth)
    use (&$search, &$memo, &$roots, $items, $weapons, $resources, $count, $leftover, $pitchAvailable, $costOf, $profile, $flagsMet, $flagValues, $usedMask, $actionPending, $keyMask) {
    $key = "$mask:$weaponMask:$ap:" . ($flags & $keyMask) . ":" . ($chain === null ? "-" : $chain["key"] . ($chain["ga"] ? "g" : "")) . ":" . implode(",", array_map(fn($p) => $p["key"], $pending));
    if ($depth > 0 && isset($memo[$key])) return $memo[$key];
    $stop = $leftover($mask, $weaponMask);
    $best = $stop === null ? [-INF, 0.0, 0.0] : [$stop, 0.0, $stop];
    if ($depth == 0) $roots["stop"] = $best[0];

    $consider = function ($moveKey, $value, $damage, $nextMask, $nextWeaponMask, $nextAp, $nextChain, $nextPending, $nextFlags, $alt = null)
      use (&$search, &$best, &$roots, $depth, $pitchAvailable, $costOf, $resources, $flags, $flagValues) {
      if ($costOf($nextMask, $nextWeaponMask) > $resources + $pitchAvailable($nextMask)) return;
      foreach ($flagValues as $bit => $bonus) if (($nextFlags & ~$flags) & $bit) $value += $bonus;
      $result = $search($nextMask, $nextWeaponMask, $nextAp, $nextChain, $nextPending, $nextFlags, $depth + 1);
      if ($result[0] === -INF) return;
      if ($alt !== null) {
        [$chance, $altAp, $altChain] = $alt;
        $other = $search($nextMask, $nextWeaponMask, $altAp, $altChain, $nextPending, $nextFlags, $depth + 1);
        if ($other[0] !== -INF) {
          $result = [$chance * $result[0] + (1 - $chance) * $other[0], $chance * $result[1] + (1 - $chance) * $other[1], $chance * $result[2] + (1 - $chance) * $other[2]];
        }
      }
      $total = $value + $result[0];
      if ($depth == 0) $roots[$moveKey] = max($roots[$moveKey] ?? -INF, $total);
      if ($total > $best[0]) $best = [$total, $damage + $result[1], $result[2]];
    };

    $attack = function ($attackInfo, $moveKey, $nextMask, $nextWeaponMask, $extraFlags = 0) use ($ap, $pending, $flags, $consider, $profile, $flagsMet) {
      $flagsNow = $flags | $extraFlags;
      $power = $attackInfo["power"] + (!empty($attackInfo["brute"]) && ($flags & BOT_FLAG_BLOODRUSH) ? 2 : 0) + BotConditionalPower($attackInfo, $flagsNow);
      $goAgain = $attackInfo["goAgain"] || ($attackInfo["goAgainFlags"] > 0 && $flagsMet($flagsNow, $attackInfo["goAgainFlags"])) || BotConditionalGoAgain($attackInfo, $flagsNow);
      $remaining = [];
      foreach ($pending as $pump) {
        if (BotPumpApplies($pump["target"], $attackInfo)) {
          $power += $pump["power"];
          if ($pump["goAgain"]) $goAgain = true;
        } else {
          $remaining[] = $pump;
        }
      }
      if ($goAgain && !empty($attackInfo["cond"]["gap"])) $power += $attackInfo["cond"]["gap"];
      $onHit = $attackInfo["onHit"];
      $value = $power + ($onHit > 0 ? $onHit * BotHitChance($power, $onHit, $profile) : 0.0) - ($attackInfo["selfCost"] ?? 0) + ($attackInfo["cond"]["atk"] ?? 0);
      $chain = $attackInfo + ["ga" => $goAgain, "pumped" => $power > $attackInfo["power"] || !empty($attackInfo["chargedPower"]) || !empty($attackInfo["pumpedBase"])];
      $nextFlags = BotAttackedFlags($flagsNow | $attackInfo["setFlags"], $attackInfo) | (!empty($attackInfo["cond"]["atk"]) ? BOT_FLAG_DAMAGE_DEALT : 0);
      $chance = null;
      if (!$goAgain && !empty($attackInfo["cond"]["goh"])) $chance = BotHitChance($power, $onHit, $profile);
      else if (!$goAgain && isset($attackInfo["cond"]["gop"])) $chance = floatval($attackInfo["cond"]["gop"]);
      else if (!$goAgain && ($conditional = BotConditionalGoAgainChance($attackInfo, $flagsNow)) > 0) $chance = $conditional;
      if ($chance !== null) {
        $consider($moveKey, $value, $power, $nextMask, $nextWeaponMask, $ap, ["ga" => true] + $chain, $remaining, $nextFlags, [$chance, $ap - 1, $chain]);
        return;
      }
      $consider($moveKey, $value, $power, $nextMask, $nextWeaponMask, $ap - 1 + ($goAgain ? 1 : 0), $chain, $remaining, $nextFlags);
    };

    for ($index = 0; $index < $count; ++$index) {
      if ($mask & (1 << $index)) continue;
      $item = $items[$index];
      if (isset($item["twin"]) && ($mask & (1 << $item["twin"]))) continue;
      if ($item["needFlags"] > 0 && !$flagsMet($flags, $item["needFlags"])) continue;
      $nextMask = $mask | (1 << $index);
      $moveKey = "c" . $index;
      $isPump = $item["pump"] > 0 || $item["pumpGoAgain"];
      if ($item["kind"] == "scab") {
        if ($ap < 1) continue;
        $expect = [0.0, 0.0, 0.0];
        foreach ([[1 / 6, -1], [1 / 3, 0], [1 / 3, 1], [1 / 6, 2]] as [$chance, $delta]) {
          $outcome = $search($nextMask, $weaponMask, $ap + $delta, null, $pending, $flags, $depth + 1);
          if ($outcome[0] === -INF) continue 2;
          for ($k = 0; $k < 3; ++$k) $expect[$k] += $chance * $outcome[$k];
        }
        if ($depth == 0) $roots[$moveKey] = max($roots[$moveKey] ?? -INF, $expect[0]);
        if ($expect[0] > $best[0]) $best = $expect;
      } else if ($item["kind"] == "banish") {
        $consider($moveKey, $item["bonus"], 0.0, $nextMask, $weaponMask, $ap, $chain, $pending, $flags | $item["setFlags"]);
      } else if ($item["kind"] == "attack" && $ap > 0) {
        $attackInfo = [
          "key" => $moveKey, "power" => $item["power"], "goAgain" => $item["goAgain"], "isAA" => $item["isAA"],
          "cost" => $item["cost"], "subtype" => $item["subtype"], "onHit" => $item["onHit"],
          "setFlags" => $item["setFlags"], "goAgainFlags" => $item["goAgainFlags"], "draconicGoAgain" => false, "brute" => $item["brute"] ?? false,
          "id" => $item["id"], "cond" => $item["cond"] ?? [], "dracAttack" => $item["dracAttack"] ?? false, "red" => $item["red"] ?? false,
          "lightning" => $item["lightning"] ?? false, "iraSecond" => $item["iraSecond"] ?? false,
        ];
        $attack($attackInfo, $moveKey, $nextMask, $weaponMask);
        if (!empty($item["charges"])) {
          $chargedYellow = false;
          $chargedOther = false;
          $used = $usedMask($nextMask);
          foreach ($items as $other => $fuel) {
            if (($used & (1 << $other)) || !$fuel["inHand"]) continue;
            $yellow = $fuel["pitch"] == 2;
            if ($yellow ? $chargedYellow : $chargedOther) continue;
            if ($yellow) $chargedYellow = true; else $chargedOther = true;
            $chargedInfo = $attackInfo;
            if ($yellow && !empty($item["yellowChargePower"])) {
              $chargedInfo["power"] += $item["yellowChargePower"];
              $chargedInfo["pumpedBase"] = true;
            }
            $attack($chargedInfo, $moveKey, $nextMask | (1 << $other), $weaponMask, BOT_FLAG_CHARGED | BOT_FLAG_SOUL | ($yellow ? BOT_FLAG_YELLOW_SOUL : 0));
          }
        }
      } else if (!$isPump && $item["kind"] == "action" && ($item["bonus"] ?? 0.0) > 0 && $ap > 0) {
        $nextFlags = BotPlayedFlags($flags, $item) | $item["setFlags"];
        $consider($moveKey, $item["bonus"], 0.0, $nextMask, $weaponMask, $ap - 1 + ($item["goAgain"] ? 1 : 0), null, $actionPending($pending, $flags, $nextFlags), $nextFlags);
      } else if ($isPump && ($item["pumpNext"] || $item["kind"] == "action")) {
        if ($item["kind"] == "action" && $ap < 1) continue;
        $nextAp = $item["kind"] == "action" ? $ap - 1 + ($item["goAgain"] ? 1 : 0) : $ap;
        $nextPending = $pending;
        $nextPending[] = ["key" => $moveKey, "power" => $item["pump"], "target" => $item["pumpTarget"], "goAgain" => $item["pumpGoAgain"]];
        $nextFlags = BotPlayedFlags($flags, $item) | $item["setFlags"];
        $consider($moveKey, $item["bonus"] ?? 0.0, 0.0, $nextMask, $weaponMask, $nextAp, $item["kind"] == "action" ? null : $chain, $actionPending($nextPending, $flags, $nextFlags), $nextFlags);
      } else if (($item["kind"] == "attack-reaction" || $item["kind"] == "instant") && $chain !== null) {
        $applies = $isPump && BotPumpApplies($item["pumpTarget"], $chain);
        $veinLink = !empty($item["draconic"]) && $chain["draconicGoAgain"] && !$chain["ga"];
        if (!$applies && !$veinLink) continue;
        $gain = ($applies ? $item["pump"] : 0.0) + ($veinLink ? 1.0 : 0.0) + ($item["bonus"] ?? 0.0);
        $nextAp = $ap;
        $nextChain = $chain;
        if ($applies && $item["pump"] > 0) $nextChain["pumped"] = true;
        if ((($applies && $item["pumpGoAgain"]) || $veinLink) && !$chain["ga"]) {
          ++$nextAp;
          $nextChain["ga"] = true;
        }
        $consider($moveKey, $gain, $gain, $nextMask, $weaponMask, $nextAp, $nextChain, $pending, BotPlayedFlags($flags, $item) | $item["setFlags"]);
      }
    }
    if ($ap > 0) {
      foreach ($weapons as $index => $weapon) {
        if ($weaponMask & (1 << $index)) continue;
        if ($weapon["needFlags"] > 0 && !$flagsMet($flags, $weapon["needFlags"])) continue;
        $moveKey = "w" . $index;
        $attack([
          "key" => $moveKey, "power" => $weapon["power"], "goAgain" => $weapon["goAgain"], "isAA" => false,
          "cost" => $weapon["cost"], "subtype" => $weapon["subtype"], "onHit" => $weapon["onHit"],
          "setFlags" => $weapon["setFlags"], "goAgainFlags" => $weapon["goAgainFlags"], "draconicGoAgain" => $weapon["draconicGoAgain"] ?? false,
          "chargedPower" => $weapon["chargedPower"] ?? false, "selfCost" => $weapon["selfCost"] ?? 0, "brute" => $weapon["brute"] ?? false,
          "id" => $weapon["id"], "cond" => $weapon["cond"] ?? [], "dracAttack" => $weapon["dracAttack"] ?? false, "iraSecond" => $weapon["iraSecond"] ?? false,
        ], $moveKey, $mask, $weaponMask | (1 << $index));
      }
    }
    if ($depth > 0) $memo[$key] = $best;
    return $best;
  };

  $result = $search(0, 0, $context["ap"], null, $context["pending"], $context["flags"], 0);
  if ($result[0] === -INF) $result = [0.0, 0.0, 0.0];
  $firstMoves = [];
  foreach ($roots as $moveKey => $value) {
    if ($moveKey == "stop") {
      $firstMoves["stop"] = $value;
    } else if ($moveKey[0] == "w") {
      $weaponKey = "W:" . $weapons[intval(substr($moveKey, 1))]["id"];
      $firstMoves[$weaponKey] = max($firstMoves[$weaponKey] ?? -INF, $value);
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

function BotOwnedCards($player)
{
  $cards = [];
  $zones = [[GetDeck($player), DeckPieces()], [GetHand($player), 1], [GetArsenal($player), ArsenalPieces()],
    [GetDiscard($player), DiscardPieces()], [GetPitch($player), PitchPieces()], [GetBanish($player), BanishPieces()]];
  foreach ($zones as [$zone, $pieces]) {
    for ($i = 0, $count = count($zone); $i < $count; $i += $pieces) {
      if (($zone[$i] ?? "") !== "") $cards[] = $zone[$i];
    }
  }
  return $cards;
}

function BotDeckStyle($player)
{
  static $cache = [];
  if (isset($cache[$player])) return $cache[$player];
  $total = 0;
  $reactions = 0;
  $attacks = 0;
  $blues = 0;
  $defended = 0;
  $defense = 0;
  foreach (BotOwnedCards($player) as $cardID) {
    ++$total;
    $type = CardType($cardID);
    if ($type == "DR") ++$reactions;
    if ($type == "AA") ++$attacks;
    if (intval(PitchValue($cardID)) == 3) ++$blues;
    $printed = GeneratedBlockValue($cardID);
    if (is_numeric($printed) && intval($printed) >= 0) {
      ++$defended;
      $defense += intval($printed);
    }
  }
  if ($total == 0) return $cache[$player] = ["blockPropensity" => BOT_DEFAULT_BLOCK_PROPENSITY, "cardDefense" => BOT_DEFAULT_CARD_DEFENSE];
  $share = fn($part) => $part / $total;
  $cardDefense = $defended > 0 ? $defense / $defended : BOT_DEFAULT_CARD_DEFENSE;
  $propensity = BOT_DEFAULT_BLOCK_PROPENSITY + 2 * ($share($reactions) - 0.1) + 0.8 * ($share($blues) - 0.35)
    - 0.8 * ($share($attacks) - 0.5) + 0.15 * ($cardDefense - BOT_DEFAULT_CARD_DEFENSE);
  return $cache[$player] = ["blockPropensity" => max(0.0, min(1.0, $propensity)), "cardDefense" => $cardDefense];
}

function BotDefenderProfile($playerID, $nextTurn = false)
{
  static $cache = [];
  $opponent = 3 - $playerID;
  $character = &GetPlayerCharacter($opponent);
  $handCount = count(GetHand($opponent));
  $cacheKey = $playerID . ($nextTurn ? "n" : "c") . $handCount . "|" . GetHealth($opponent) . "|" . implode(" ", $character);
  if (isset($cache[$cacheKey])) return $cache[$cacheKey];
  if (count($cache) > 64) $cache = [];
  if ($nextTurn) $handCount = max($handCount, intval(CharacterIntellect($character[0] ?? "")));
  $armor = 0;
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    if (intval($character[$i + 1] ?? 0) != 2 || ($character[$i + 6] ?? 0) == 1 || CardType($character[$i]) != "E") continue;
    $armor = max($armor, intval(BlockValue($character[$i], $opponent, "EQUIP", false)) + min(0, intval($character[$i + 4] ?? 0)));
  }
  $life = intval(GetHealth($opponent));
  return $cache[$cacheKey] = ["handCount" => $handCount, "life" => $life, "armor" => $armor, "key" => "$handCount.$life.$armor"] + BotDeckStyle($opponent);
}

function BotHitChance($power, $onHit, $profile)
{
  if ($power <= 0) return BOT_MIN_HIT_CHANCE;
  $fromHand = max(0, $power - $profile["armor"]);
  $cards = intval(ceil($fromHand / max(1, $profile["cardDefense"])));
  if ($cards > $profile["handCount"]) return BOT_MAX_HIT_CHANCE;
  $cardCost = BOT_DRAW_VALUE * (1 - BOT_BLOCKING_DECK_CARD_DISCOUNT * $profile["blockPropensity"]);
  $cost = $cards * $cardCost + ($profile["armor"] > 0 ? BOT_ARMOR_BLOCK_COST : 0);
  $pressure = 1 + max(0, BOT_PRESSURE_LIFE - $profile["life"]) / BOT_PRESSURE_LIFE;
  $margin = ($power + $onHit) * $pressure - $cost - BOT_BLOCK_RESERVE;
  $blocks = $power + $onHit >= $profile["life"] ? 1 : 1 / (1 + exp(-$margin / BOT_BLOCK_SCALE));
  return max(BOT_MIN_HIT_CHANCE, min(BOT_MAX_HIT_CHANCE, 1 - $blocks * BOT_BLOCKER_RELIABILITY ** $cards));
}
