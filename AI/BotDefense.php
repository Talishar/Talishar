<?php

include_once __DIR__ . "/BotModel.php";

const BOT_SIGNIFICANT_ON_HIT = 2.0;
const BOT_WASTED_WEAPON_LOCK = 3.0;
const BOT_MAX_HAND_LOSS_SCENARIOS = 35;
const BOT_MAX_BLOCK_SET = 4;

function BotAttackInfo()
{
  global $CombatChain, $mainPlayer;
  if (!$CombatChain->HasCurrentLink()) return null;
  $attackID = $CombatChain->AttackCard()->ID();
  return [
    "id" => $attackID,
    "isAA" => CardType($attackID) == "AA",
    "cost" => BotNumericCost(CardCost($attackID)),
    "subtype" => strtolower(CardSubType($attackID)),
    "isWeapon" => TypeContains($attackID, "W", $mainPlayer),
  ];
}

function BotOnHitEvaluation($defender)
{
  global $mainPlayer, $currentTurnEffects, $actionPoints;
  $result = [
    "value" => 0.0, "handLost" => 0, "handLossValue" => 0.0, "selector" => "",
    "arsenal" => false, "equipment" => false, "removesDefender" => false,
  ];
  $attack = BotAttackInfo();
  if ($attack === null || !ActiveOnHits()) return $result;
  $facts = BotCardFacts($attack["id"]);
  $parts = isset($facts["oh"]) ? [$facts["oh"]] : [];
  $result["removesDefender"] = !empty($facts["rd"]);
  $pieces = CurrentTurnEffectsPieces();
  for ($i = 0, $count = count($currentTurnEffects); $i < $count; $i += $pieces) {
    if ($currentTurnEffects[$i + 1] != $mainPlayer) continue;
    $effectFacts = BotCardFacts(explode("-", $currentTurnEffects[$i])[0]);
    if (isset($effectFacts["goh"])) $parts[] = $effectFacts["goh"];
  }
  $hasHand = count(GetHand($defender)) > 0;
  $hasArsenal = count(GetArsenal($defender)) > 0;
  $conversion = DoesAttackHaveGoAgain() || intval($actionPoints) > 0 || count(GetArsenal($mainPlayer)) == 0 ? 1 : 0;
  $severity = ["" => -1, "defender" => 0, "random" => 1, "attacker" => 2];
  foreach ($parts as $part) {
    $result["value"] += $part["s"] ?? 0;
    $result["value"] += ($part["d"] ?? 0) * (3 + $conversion);
    if (($part["h"] ?? 0) > 0 && $hasHand) {
      $lost = $part["h"] * $part["hv"];
      $result["handLost"] += $part["h"];
      $result["handLossValue"] += $lost;
      $result["value"] += $lost;
      $selector = $part["hv"] >= 4.5 ? "attacker" : ($part["hv"] >= 3 ? "random" : "defender");
      if ($severity[$selector] > $severity[$result["selector"]]) $result["selector"] = $selector;
    }
    if (($part["a"] ?? 0) > 0 && $hasArsenal) {
      $result["value"] += 4 * $part["a"];
      $result["arsenal"] = true;
    }
    if (!empty($part["e"])) $result["equipment"] = true;
  }
  if (count($parts) == 0) $result["value"] = 2.0;
  return $result;
}

function BotControlsWeaponSubtype($player, $subtype, $excludeID)
{
  $character = &GetPlayerCharacter($player);
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    if ($character[$i] === $excludeID || intval($character[$i + 1] ?? 0) == 0) continue;
    if (CardType($character[$i]) == "W" && str_contains(strtolower(CardSubType($character[$i])), $subtype)) return true;
  }
  return false;
}

function BotPublicThreat($defender, $defended = false)
{
  global $mainPlayer;
  $none = ["bonus" => 0, "direct" => 0];
  $attack = BotAttackInfo();
  if ($attack === null || !IsHeroAttackTarget()) return $none;
  $attacker = $mainPlayer;
  $units = [];
  $addAbilities = function ($cardID, $isAttackSource, $usesLeft) use (&$units, $attack, $attacker) {
    foreach (BotCardFacts($cardID)["ab"] ?? [] as [$gain, $direct, $resources, $limit, $self, $scope]) {
      if ($scope == "self" && !$isAttackSource) continue;
      if ($scope == "aa" && !$attack["isAA"]) continue;
      if (str_starts_with($scope, "sub:")) {
        [, $subtype, $offChain] = explode(":", $scope . ":");
        if (!BotControlsWeaponSubtype($attacker, $subtype, $offChain === "off" ? $attack["id"] : "")) continue;
      }
      for ($i = 0, $uses = min($limit, $usesLeft); $i < $uses; ++$i) $units[] = [$gain, $direct, $resources];
    }
  };
  $addAbilities($attack["id"], true, 6);
  $character = &GetPlayerCharacter($attacker);
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    if ($character[$i] === $attack["id"] || intval($character[$i + 1] ?? 0) != 2) continue;
    $addAbilities($character[$i], false, max(0, intval($character[$i + 5] ?? 0)));
  }
  $items = &GetItems($attacker);
  $pieces = ItemPieces();
  for ($i = 0, $count = count($items); $i < $count; $i += $pieces) {
    if (intval($items[$i + 2] ?? 0) != 2) continue;
    $addAbilities($items[$i], false, max(1, intval($items[$i + 3] ?? 1)));
  }
  $auras = &GetAuras($attacker);
  $pieces = AuraPieces();
  for ($i = 0, $count = count($auras); $i < $count; $i += $pieces) $addAbilities($auras[$i], false, 6);
  $arsenal = &GetArsenal($attacker);
  $pieces = ArsenalPieces();
  for ($i = 0, $count = count($arsenal); $i < $count; $i += $pieces) {
    if (($arsenal[$i + 1] ?? "") != "UP") continue;
    $type = CardType($arsenal[$i]);
    if ($type != "AR" && $type != "I") continue;
    $facts = BotCardFacts($arsenal[$i]);
    if (($facts["p"] ?? 0) <= 0 || !empty($facts["pn"]) || !BotPumpApplies($facts["pt"] ?? "any", $attack)) continue;
    $units[] = [$facts["p"], 0, BotNumericCost(CardCost($arsenal[$i]))];
  }
  if ($defended && str_starts_with($attack["id"], "spark_spray_") && intval(CachedTotalBlock()) == 0) $units[] = [1, 0, 1];
  if (count($units) == 0) return $none;

  $maxPitch = 0;
  foreach (BotUnseenCards($attacker) as $cardID) $maxPitch = max($maxPitch, intval(PitchValue($cardID)));
  $resources = &GetResources($attacker);
  $budget = intval($resources[0] ?? 0) + count(GetHand($attacker)) * ($maxPitch > 0 ? $maxPitch : 3);
  usort($units, fn($left, $right) => ($left[2] > 0) <=> ($right[2] > 0)
    ?: ($right[0] / max(1, $right[2])) <=> ($left[0] / max(1, $left[2])));
  $threat = $none;
  foreach ($units as [$gain, $direct, $cost]) {
    if ($cost > $budget) continue;
    $budget -= $cost;
    $threat[$direct ? "direct" : "bonus"] += $gain;
  }
  return $threat;
}

function BotPendingOwnReactionDefense($player)
{
  global $layers;
  $total = 0;
  $pieces = LayerPieces();
  for ($i = 0, $count = count($layers); $i < $count; $i += $pieces) {
    if ($layers[$i + 1] != $player || CardType($layers[$i]) != "DR") continue;
    $total += max(0, intval(BlockValue($layers[$i], $player, "LAYER", false)));
  }
  return $total;
}

function BotOpponentWeaponSwingLeft()
{
  global $mainPlayer;
  $character = &GetPlayerCharacter($mainPlayer);
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    if (CardType($character[$i]) != "W") continue;
    if (intval($character[$i + 1] ?? 0) == 2 && intval($character[$i + 5] ?? 0) > 0) return true;
  }
  return false;
}

function BotDefenseContext($player)
{
  global $mainPlayer, $actionPoints, $CCS_RequiredEquipmentBlock;
  $attack = BotAttackInfo();
  if ($attack === null) return null;
  $incoming = max(0, intval(CachedTotalPower()) - intval(CachedTotalBlock()) - BotPendingOwnReactionDefense($player));
  $threat = BotPublicThreat($player);
  $threatDefended = BotPublicThreat($player, true);
  $attackerHand = count(GetHand($mainPlayer));
  $attackerArsenal = intdiv(count(GetArsenal($mainPlayer)), ArsenalPieces());
  if (!DoesAttackHaveGoAgain() && intval($actionPoints) <= 0) {
    $stopProbability = 1.0;
    $representative = 0;
  } else {
    $stopProbability = max(0.15, min(0.75, 0.75 - 0.2 * min($attackerHand, 3) - 0.15 * $attackerArsenal));
    $representative = min(6, 3 + $attackerHand + $attackerArsenal);
  }
  $required = isset($CCS_RequiredEquipmentBlock) ? max(0, intval(GetCombatChainState($CCS_RequiredEquipmentBlock)) - NumEquipBlock("EQUIP")) : 0;
  return [
    "attack" => $attack,
    "incoming" => $incoming,
    "threat" => $threat,
    "threatDefended" => $threatDefended,
    "life" => intval(GetHealth($player)),
    "onHit" => BotOnHitEvaluation($player),
    "opening" => BotIsOpeningTurnDefense($player),
    "stopProbability" => $stopProbability,
    "representative" => $representative,
    "requiredEquipment" => $required,
    "dominate" => IsDominateActive(),
    "handDefended" => NumDefendedFromHand(),
    "phantasm" => IsPhantasmActive(),
    "weaponSwingLeft" => BotOpponentWeaponSwingLeft(),
    "weaponLockValue" => min(6, 3 + $attackerHand + $attackerArsenal),
  ];
}

function BotBlockCandidates($player, $ctx)
{
  $candidates = [];
  $restriction = "";
  $hand = &GetHand($player);
  foreach ($hand as $index => $cardID) {
    if ($cardID === "" || CardType($cardID) == "DR") continue;
    if (!IsPlayable($cardID, "B", "HAND", $index, $restriction, $player)) continue;
    $defense = max(0, intval(BlockValue($cardID, $player, "HAND", false)));
    if ($defense == 0) continue;
    $candidates[] = [
      "id" => $cardID, "zone" => "HAND", "index" => $index, "defense" => $defense, "equipment" => false,
      "isAA" => CardType($cardID) == "AA", "power" => max(0, intval(PowerValue($cardID, $player, "HAND", -1, false, false))),
    ];
  }
  $character = &GetPlayerCharacter($player);
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    $cardID = $character[$i];
    if (intval($character[$i + 1] ?? 0) != 2 || ($character[$i + 6] ?? 0) == 1 || !TypeContains($cardID, "E", $player)) continue;
    if (!IsPlayable($cardID, "B", "CHAR", $i, $restriction, $player)) continue;
    $defense = max(0, intval(BlockValue($cardID, $player, "EQUIP", false)) + BotEquipmentDefenseCounters($cardID, $player));
    if ($defense == 0 && $ctx["requiredEquipment"] == 0) continue;
    $candidates[] = ["id" => $cardID, "zone" => "CHAR", "index" => $i, "defense" => $defense, "equipment" => true, "isAA" => false, "power" => 0];
  }
  $arsenal = &GetArsenal($player);
  $pieces = ArsenalPieces();
  for ($i = 0, $count = count($arsenal); $i < $count; $i += $pieces) {
    $cardID = $arsenal[$i];
    if ($cardID === "" || !HasAmbush($cardID, $player) || !IsPlayable($cardID, "B", "ARS", $i, $restriction, $player)) continue;
    $defense = max(0, intval(BlockValue($cardID, $player, "ARS", false)));
    if ($defense == 0) continue;
    $candidates[] = [
      "id" => $cardID, "zone" => "ARS", "index" => $i, "defense" => $defense, "equipment" => false,
      "isAA" => CardType($cardID) == "AA", "power" => max(0, intval(PowerValue($cardID, $player, "ARS", -1, false, false))),
    ];
  }
  return $candidates;
}

function BotReactionCandidates($player, $playableNow)
{
  global $turn;
  $candidates = [];
  $restriction = "";
  $resources = &GetResources($player);
  $hand = &GetHand($player);
  foreach ($hand as $index => $cardID) {
    if ($cardID === "" || CardType($cardID) != "DR") continue;
    if ($playableNow && !IsPlayable($cardID, $turn[0], "HAND", $index, $restriction, $player)) continue;
    $candidates[] = ["id" => $cardID, "zone" => "HAND", "index" => $index, "cost" => BotEffectiveCost($cardID, "HAND", $index, CardCost($cardID, "HAND")),
      "defense" => max(0, intval(BlockValue($cardID, $player, "HAND", false))), "equipment" => false, "isAA" => false, "power" => 0];
  }
  $arsenal = &GetArsenal($player);
  $pieces = ArsenalPieces();
  for ($i = 0, $count = count($arsenal); $i < $count; $i += $pieces) {
    $cardID = $arsenal[$i];
    if ($cardID === "" || CardType($cardID) != "DR" || ($arsenal[$i + 4] ?? 0) == 1) continue;
    if ($playableNow && !IsPlayable($cardID, $turn[0], "ARS", $i, $restriction, $player)) continue;
    $candidates[] = ["id" => $cardID, "zone" => "ARS", "index" => $i, "cost" => BotEffectiveCost($cardID, "ARS", $i, CardCost($cardID, "ARS")),
      "defense" => max(0, intval(BlockValue($cardID, $player, "ARS", false))), "equipment" => false, "isAA" => false, "power" => 0];
  }
  return $candidates;
}

function BotCardKey($card)
{
  return $card["zone"] . ":" . $card["index"];
}

function BotCheapestPitch($player, $need, $excluded, $context = null)
{
  if ($need <= 0) return [];
  $hand = &GetHand($player);
  $pitchable = [];
  foreach ($hand as $index => $cardID) {
    if ($cardID === "" || isset($excluded["HAND:" . $index])) continue;
    $pitch = intval(PitchValue($cardID));
    if ($pitch > 0 && CanPitch($cardID, "HAND")) $pitchable[] = [$index, $pitch];
  }
  $count = count($pitchable);
  if ($count > 12) $count = 12;
  $best = null;
  for ($mask = 1; $mask < (1 << $count); ++$mask) {
    $total = 0;
    $selected = [];
    $minimum = PHP_INT_MAX;
    for ($i = 0; $i < $count; ++$i) {
      if (!($mask & (1 << $i))) continue;
      $total += $pitchable[$i][1];
      $minimum = min($minimum, $pitchable[$i][1]);
      $selected[] = $pitchable[$i][0];
    }
    if ($total < $need || $total - $minimum >= $need) continue;
    $after = $excluded;
    foreach ($selected as $index) $after["HAND:" . $index] = true;
    if ($context === null) {
      $value = BotResponseValue($player, $after);
    } else {
      $afterPitch = $context;
      $afterPitch["resources"] += $total - $need;
      $afterPitch["key"] .= ":" . ($total - $need);
      $value = BotSolveHand(BotRemainingOffense($player, $after), $player, $afterPitch)["value"];
    }
    $candidate = ["indices" => $selected, "value" => $value, "overpay" => $total - $need];
    if ($best === null || $value > $best["value"] + 1e-9
      || (abs($value - $best["value"]) <= 1e-9 && (count($selected) < count($best["indices"])
        || (count($selected) == count($best["indices"]) && $candidate["overpay"] < $best["overpay"])))) {
      $best = $candidate;
    }
  }
  return $best === null ? null : $best["indices"];
}

function BotReactionPackages($player, $reactions, $consumed, $ctx)
{
  $packages = [["cards" => [], "amount" => 0, "consumed" => []]];
  $count = min(count($reactions), 4);
  if ($count == 0) return $packages;
  $blockedFromHand = false;
  foreach ($consumed as $key => $_) {
    if (str_starts_with($key, "HAND:")) $blockedFromHand = true;
  }
  $resources = &GetResources($player);
  for ($mask = 1; $mask < (1 << $count); ++$mask) {
    $cards = [];
    $cost = 0;
    $amount = 0;
    $spent = $consumed;
    $skip = false;
    for ($i = 0; $i < $count; ++$i) {
      if (!($mask & (1 << $i))) continue;
      $reaction = $reactions[$i];
      if ($reaction["zone"] == "HAND" && $ctx["dominate"] && ($blockedFromHand || $ctx["handDefended"] > 0)) $skip = true;
      $cards[] = $reaction;
      $cost += $reaction["cost"];
      $amount += $reaction["defense"];
      $spent[BotCardKey($reaction)] = true;
    }
    if ($skip) continue;
    $pitch = BotCheapestPitch($player, $cost - intval($resources[0] ?? 0), $spent);
    if ($pitch === null) continue;
    foreach ($pitch as $index) $spent["HAND:" . $index] = true;
    $packages[] = ["cards" => $cards, "amount" => $amount, "consumed" => array_diff_key($spent, $consumed)];
  }
  return $packages;
}

function BotHandLossScenarios($player, $onHit, $consumed)
{
  if ($onHit["selector"] === "" || $onHit["handLost"] <= 0) return [[[], 1.0]];
  $eligible = [];
  $hand = &GetHand($player);
  foreach ($hand as $index => $cardID) {
    if ($cardID !== "" && !isset($consumed["HAND:" . $index])) $eligible[] = "HAND:" . $index;
  }
  $size = min($onHit["handLost"], count($eligible));
  if ($size == 0) return [[[], 1.0]];
  $subsets = [];
  $pick = function ($start, $chosen) use (&$pick, &$subsets, $eligible, $size) {
    if (count($subsets) >= BOT_MAX_HAND_LOSS_SCENARIOS) return;
    if (count($chosen) == $size) {
      $subsets[] = array_fill_keys($chosen, true);
      return;
    }
    for ($i = $start, $count = count($eligible); $i < $count; ++$i) $pick($i + 1, [...$chosen, $eligible[$i]]);
  };
  $pick(0, []);
  if ($onHit["selector"] == "random") return array_map(fn($lost) => [$lost, 1 / count($subsets)], $subsets);
  $chosen = null;
  $chosenValue = 0.0;
  foreach ($subsets as $lost) {
    $value = BotResponseValue($player, $consumed + $lost);
    if ($chosen === null || ($onHit["selector"] == "defender" ? $value > $chosenValue : $value < $chosenValue)) {
      $chosen = $lost;
      $chosenValue = $value;
    }
  }
  return [[$chosen, 1.0]];
}

function BotContinuationResponse($player, $consumed, $representative)
{
  static $cache = [];
  $keys = array_keys($consumed);
  sort($keys);
  $cacheKey = $player . "|" . $representative . "|" . implode(",", $keys) . "|" . count(GetHand($player));
  if (isset($cache[$cacheKey])) return $cache[$cacheKey];
  $candidates = [];
  $hand = &GetHand($player);
  foreach ($hand as $index => $cardID) {
    if ($cardID === "" || isset($consumed["HAND:" . $index]) || CardType($cardID) == "DR") continue;
    $defense = max(0, intval(BlockValue($cardID, $player, "HAND", false)));
    if ($defense > 0) $candidates[] = ["key" => "HAND:" . $index, "defense" => $defense, "opportunity" => BotCardOpportunityValue($cardID, $player) * 0.1];
  }
  $evaluate = function ($defenders) use ($player, $consumed, $representative) {
    $spent = $consumed;
    $defense = 0;
    $opportunity = 0.0;
    foreach ($defenders as $defender) {
      $spent[$defender["key"]] = true;
      $defense += $defender["defense"];
      $opportunity += $defender["opportunity"];
    }
    $continuation = [
      "response" => BotResponseValue($player, $spent),
      "prevented" => min($representative, $defense),
      "overblock" => max(0, $defense - $representative),
      "opportunity" => $opportunity,
    ];
    $continuation["score"] = $continuation["response"] + $continuation["prevented"] - $continuation["overblock"] * 1.5 - $opportunity;
    return $continuation;
  };
  $best = $evaluate([]);
  for ($first = 0, $count = count($candidates); $first < $count; ++$first) {
    $one = $evaluate([$candidates[$first]]);
    if ($one["score"] > $best["score"]) $best = $one;
    for ($second = $first + 1; $second < $count; ++$second) {
      $two = $evaluate([$candidates[$first], $candidates[$second]]);
      if ($two["score"] > $best["score"]) $best = $two;
    }
  }
  if (count($cache) > 256) $cache = [];
  return $cache[$cacheKey] = $best;
}

function BotHasSpareHandBlocker($player, $ctx, $consumed)
{
  if ($ctx["dominate"]) return false;
  $hand = &GetHand($player);
  $spares = [];
  $restriction = "";
  foreach ($hand as $index => $cardID) {
    if ($cardID === "" || isset($consumed["HAND:" . $index]) || CardType($cardID) == "DR") continue;
    if (intval(BlockValue($cardID, $player, "HAND", false)) <= 0) continue;
    if (!IsPlayable($cardID, "B", "HAND", $index, $restriction, $player)) continue;
    $spares[] = "HAND:" . $index;
  }
  if (count($spares) == 0) return false;
  if ($ctx["opening"]) return true;
  $damage = BotSolveHand(BotRemainingOffense($player, $consumed), $player)["damage"];
  foreach ($spares as $key) {
    if (BotSolveHand(BotRemainingOffense($player, $consumed + [$key => true]), $player)["damage"] >= $damage - 1e-9) return true;
  }
  return false;
}

function BotRemainingOffense($player, $excluded)
{
  $cards = [];
  foreach (BotOffensiveHandCards($player) as $card) {
    if (!isset($excluded[BotCardKey($card)])) $cards[] = $card;
  }
  return $cards;
}

function BotIsMidgameDurableArmor($cardID, $player)
{
  if (intval(GetHealth($player)) > 20) return false;
  return intval(BlockValue($cardID, $player, "EQUIP", false)) >= 2 && (HasBattleworn($cardID) || HasTemper($cardID));
}

function BotScoreDefensePlan($player, $ctx, $set, $package)
{
  $consumed = [];
  $defense = 0;
  $equipment = [];
  $handBlockers = [];
  $phantasmDefense = null;
  $backlash = 0;
  foreach ($set as $card) {
    $consumed[BotCardKey($card)] = true;
    $defense += $card["defense"];
    if ($card["equipment"]) $equipment[] = $card;
    else $handBlockers[] = $card;
    if ($card["isAA"]) {
      $backlash = max($backlash, BotDefenderPowerBacklash($card["id"], $player, false));
      if ($ctx["phantasm"] && $card["power"] >= 6 && !ClassContains($card["id"], "ILLUSIONIST", $player)) {
        $phantasmDefense = max($phantasmDefense ?? 0, $card["defense"]);
      }
    }
  }
  $consumed += $package["consumed"];
  $prevention = $package["amount"];
  $incoming = $ctx["incoming"] + $backlash;
  $threat = count($set) > 0 ? $ctx["threatDefended"] : $ctx["threat"];
  $threatened = max($incoming, $ctx["incoming"] + $threat["bonus"] + $backlash);
  $survivableLife = $ctx["life"] - $threat["direct"];
  $lethal = $threatened >= $survivableLife;
  $destroys = $phantasmDefense !== null;
  $prevented = $destroys ? max(0, $incoming - $prevention) : min(max(0, $incoming - $prevention), $defense);
  $overblock = $destroys ? $defense - $phantasmDefense : max(0, $defense - max(0, $incoming - $prevention));
  $stopsHit = $incoming > 0 && ($destroys || $defense + $prevention >= $incoming);
  $onHit = $ctx["onHit"];
  $meaningfulOnHit = $onHit["value"] >= BOT_SIGNIFICANT_ON_HIT;
  $devastatingOnHit = $onHit["handLost"] > 0 || $onHit["arsenal"];
  $stopHitValue = $stopsHit ? $onHit["value"] - ($onHit["selector"] !== "" ? $onHit["handLossValue"] : 0) : 0.0;
  $removesDefender = !$stopsHit && $incoming > 0 && $onHit["removesDefender"];
  $forbid = ["score" => -1000000.0, "set" => $set, "package" => $package];

  if (!$lethal && !BotIsPuzzle()) {
    foreach ($equipment as $card) {
      $cardID = $card["id"];
      if ($cardID == "fyendals_spring_tunic") {
        if ($ctx["opening"] || !($stopsHit && $devastatingOnHit)) return $forbid;
        continue;
      }
      if ($stopsHit && $meaningfulOnHit) continue;
      if ($ctx["opening"]) return $forbid;
      if ($cardID == "kabuto_of_imperial_authority" && $ctx["weaponSwingLeft"]) continue;
      if (BotIsMidgameDurableArmor($cardID, $player)) continue;
      if ($ctx["attack"]["isWeapon"] && str_starts_with($cardID, "blade_beckoner_")) continue;
      if ($ctx["requiredEquipment"] > 0) continue;
      return $forbid;
    }
    $spendsArmor = false;
    foreach ($equipment as $card) {
      if ($card["id"] != "kabuto_of_imperial_authority" || !$ctx["weaponSwingLeft"]) $spendsArmor = true;
    }
    if ($spendsArmor && $ctx["requiredEquipment"] == 0 && BotHasSpareHandBlocker($player, $ctx, $consumed)) return $forbid;
  }

  $equipmentCost = 0.0;
  foreach ($equipment as $card) {
    $equipmentCost += BotEquipmentWearCost($card["id"], $card["defense"]);
    if ($card["id"] == "kabuto_of_imperial_authority") {
      $equipmentCost += $ctx["weaponSwingLeft"] ? -$ctx["weaponLockValue"] : BOT_WASTED_WEAPON_LOCK;
    }
    if ($removesDefender) $equipmentCost += max(2, $card["defense"]);
  }
  $handOpportunity = 0.0;
  foreach ($handBlockers as $card) $handOpportunity += BotCardOpportunityValue($card["id"], $player) * 0.1;
  $plannedOpportunity = 0.0;
  foreach (array_keys($package["consumed"]) as $key) {
    [$zone, $index] = explode(":", $key);
    $cardID = $zone == "HAND" ? (GetHand($player)[$index] ?? "") : (GetArsenal($player)[$index] ?? "");
    if ($cardID !== "") $plannedOpportunity += BotCardOpportunityValue($cardID, $player);
  }

  $scenarios = $stopsHit || $incoming <= 0 ? [[[], 1.0]] : BotHandLossScenarios($player, $onHit, $consumed);
  $stopResponse = 0.0;
  $continuation = ["response" => 0.0, "prevented" => 0.0, "overblock" => 0.0, "opportunity" => 0.0];
  $stopProbability = $ctx["stopProbability"];
  foreach ($scenarios as [$lost, $weight]) {
    $spent = $consumed + $lost;
    $response = BotResponseValue($player, $spent);
    $stopResponse += $response * $weight;
    if ($ctx["representative"] > 0 && $stopProbability < 1) {
      $next = BotContinuationResponse($player, $spent, $ctx["representative"]);
      foreach (["response", "prevented", "overblock", "opportunity"] as $field) $continuation[$field] += $next[$field] * $weight;
    } else {
      $continuation["response"] += $response * $weight;
    }
  }
  $continueProbability = 1 - $stopProbability;
  $blendedResponse = $stopResponse * $stopProbability + $continuation["response"] * $continueProbability;
  $unprevented = $destroys ? 0 : max(0, $incoming - $defense - $prevention);
  $risk = BotLifeThresholdRisk($ctx["life"] - $unprevented);
  $value = BotValueBreakdown([
    "damageThreatened" => $blendedResponse,
    "damagePrevented" => $prevented + $continuation["prevented"] * $continueProbability,
    "onHitValue" => $stopHitValue,
    "equipmentCost" => $equipmentCost,
    "cardOpportunityCost" => $handOpportunity + $plannedOpportunity * 0.1 + $continuation["opportunity"] * $continueProbability,
    "overblockCost" => $overblock * 1.5 + $continuation["overblock"] * 1.5 * $continueProbability,
    "lifeThresholdRisk" => is_infinite($risk) ? 0.0 : $risk,
  ]);
  $plan = ["set" => $set, "package" => $package, "value" => $value, "lethal" => $lethal, "stopsHit" => $stopsHit];

  $equipmentShort = $ctx["requiredEquipment"] > count($equipment) ? 1000000.0 : 0.0;
  if ($lethal) {
    $needed = max(0, $threatened - $prevention - $survivableLife + 1);
    $survives = $survivableLife > 0 && ($destroys || $defense >= $needed);
    if (!$survives) {
      $preservation = $plannedOpportunity;
      foreach ($handBlockers as $card) $preservation += BotCardOpportunityValue($card["id"], $player);
      return $plan + ["score" => $defense * 100 - $equipmentCost - $preservation - $equipmentShort, "survives" => false];
    }
    return $plan + ["score" => 100000 + $value["total"] - $equipmentShort, "survives" => true];
  }
  if ($ctx["opening"]) {
    return $plan + ["score" => $prevented * 100 + $stopHitValue - $overblock * 1.5 - $equipmentCost - count($set) * 0.01 - $equipmentShort, "survives" => true];
  }
  return $plan + ["score" => $value["total"] - $equipmentShort, "survives" => true];
}

function BotBlockSubsets($candidates, $ctx)
{
  $subsets = [[]];
  $target = max(1, $ctx["incoming"] + $ctx["threatDefended"]["bonus"]);
  $count = count($candidates);
  $walk = function ($start, $chosen, $defense, $minimum, $fromHand) use (&$walk, &$subsets, $candidates, $count, $target, $ctx) {
    for ($i = $start; $i < $count; ++$i) {
      $card = $candidates[$i];
      $nextFromHand = $fromHand + ($card["zone"] == "HAND" ? 1 : 0);
      if ($ctx["dominate"] && $nextFromHand + $ctx["handDefended"] > 1) continue;
      $nextDefense = $defense + $card["defense"];
      $nextMinimum = min($minimum, $card["defense"]);
      if ($nextDefense - $nextMinimum >= $target) continue;
      $next = [...$chosen, $card];
      $subsets[] = $next;
      if (count($next) < BOT_MAX_BLOCK_SET) $walk($i + 1, $next, $nextDefense, $nextMinimum, $nextFromHand);
    }
  };
  $walk(0, [], 0, PHP_INT_MAX, 0);
  return $subsets;
}

function BotBestBlockPlan($player)
{
  $ctx = BotDefenseContext($player);
  if ($ctx === null || $ctx["incoming"] <= 0) return null;
  $reactions = BotReactionCandidates($player, false);
  $best = null;
  foreach (BotBlockSubsets(BotBlockCandidates($player, $ctx), $ctx) as $set) {
    $consumed = [];
    foreach ($set as $card) $consumed[BotCardKey($card)] = true;
    foreach (BotReactionPackages($player, $reactions, $consumed, $ctx) as $package) {
      $plan = BotScoreDefensePlan($player, $ctx, $set, $package);
      if ($best === null || $plan["score"] > $best["score"] + 1e-9) $best = $plan;
    }
  }
  return $best;
}

function BotBestReactionPlan($player)
{
  $ctx = BotDefenseContext($player);
  if ($ctx === null || $ctx["incoming"] <= 0) return null;
  $reactions = BotReactionCandidates($player, true);
  $resources = &GetResources($player);
  $best = null;
  $count = min(count($reactions), 4);
  $handBlocked = $ctx["handDefended"] > 0;
  for ($mask = 0; $mask < (1 << $count); ++$mask) {
    $set = [];
    $cost = 0;
    $consumed = [];
    $skip = false;
    for ($i = 0; $i < $count; ++$i) {
      if (!($mask & (1 << $i))) continue;
      if ($reactions[$i]["zone"] == "HAND" && $ctx["dominate"] && $handBlocked) $skip = true;
      $set[] = $reactions[$i];
      $cost += $reactions[$i]["cost"];
      $consumed[BotCardKey($reactions[$i])] = true;
    }
    if ($skip) continue;
    $pitch = BotCheapestPitch($player, $cost - intval($resources[0] ?? 0), $consumed);
    if ($pitch === null) continue;
    $package = ["cards" => [], "amount" => 0, "consumed" => []];
    foreach ($pitch as $index) $package["consumed"]["HAND:" . $index] = true;
    $plan = BotScoreDefensePlan($player, $ctx, $set, $package);
    if ($best === null || $plan["score"] > $best["score"] + 1e-9) $best = $plan;
  }
  return $best;
}

function BotChoosePitchIndex($player, $need)
{
  global $mainPlayer;
  $context = $mainPlayer == $player ? BotCurrentTurnContext($player) : null;
  $indices = BotCheapestPitch($player, $need, [], $context);
  if ($indices === null || count($indices) == 0) return null;
  $hand = &GetHand($player);
  usort($indices, fn($left, $right) => intval(PitchValue($hand[$right])) <=> intval(PitchValue($hand[$left])));
  return $indices[0];
}

function BotQueuedPitchNeed()
{
  global $decisionQueue;
  $pieces = DecisionQueuePieces();
  for ($i = 0, $count = min(count($decisionQueue), $pieces * 8); $i < $count; $i += $pieces) {
    if ($decisionQueue[$i] == "SUBPITCHVALUE") return max(1, intval($decisionQueue[$i + 2]));
  }
  return null;
}

function BotChooseArcaneBarrier($player, $options)
{
  global $dqVars, $mainPlayer;
  $damage = intval(explode("-", strval($dqVars[0] ?? "0"))[0]);
  $life = intval(GetHealth($player));
  $resources = &GetResources($player);
  $floating = intval($resources[0] ?? 0);
  $context = $mainPlayer == $player ? BotCurrentTurnContext($player) : null;
  $full = $context === null ? BotResponseValue($player) : BotSolveHand(BotOffensiveHandCards($player), $player, $context)["value"];
  $best = null;
  foreach ($options as $option) {
    $amount = intval($option);
    $pitch = BotCheapestPitch($player, $amount - $floating, [], $context);
    if ($pitch === null) continue;
    $excluded = [];
    foreach ($pitch as $index) $excluded["HAND:" . $index] = true;
    $remaining = $context === null ? BotResponseValue($player, $excluded)
      : BotSolveHand(BotRemainingOffense($player, $excluded), $player, $context)["value"];
    $prevented = min($amount, $damage);
    $score = $prevented - max(0.0, $full - $remaining) - BotSurvivalRisk($life - ($damage - $prevented));
    if ($best === null || $score > $best[0] + 1e-9 || (abs($score - $best[0]) <= 1e-9 && $amount < $best[1])) {
      $best = [$score, $amount, $option];
    }
  }
  return $best === null ? ($options[0] ?? "0") : $best[2];
}

function BotSurvivalRisk($lifeAfter)
{
  $risk = BotLifeThresholdRisk($lifeAfter);
  return is_infinite($risk) ? 100000.0 : $risk;
}

function BotAttackReactionValue($cardID, $player)
{
  global $defPlayer;
  $attack = BotAttackInfo();
  if ($attack === null) return 0.0;
  $uses = BotCardUses($cardID, $player);
  if ($uses["pump"] <= 0 || $uses["pumpNext"] || !BotPumpApplies($uses["pumpTarget"], $attack)) return 0.0;
  $power = intval(CachedTotalPower());
  $block = intval(CachedTotalBlock());
  $gained = max(0, $power + $uses["pump"] - $block) - max(0, $power - $block);
  if ($uses["pumpGoAgain"] && !DoesAttackHaveGoAgain()) $gained += 2;
  $value = $gained - BotHeldCardValue($cardID, $player);
  if ($power - $block < intval(GetHealth($defPlayer)) && $power + $uses["pump"] - $block >= intval(GetHealth($defPlayer))) $value += 100000;
  return $value > 0 ? 20 + $value * 10 : 0.0;
}

?>
