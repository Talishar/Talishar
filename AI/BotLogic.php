<?php

function BotValueBreakdown($components = [])
{
  $positive = [
    "damageThreatened", "damagePrevented", "onHitValue", "futureHandValue",
    "arsenalValue", "boardValue", "opponentConversionDenied", "strategicAdjustment",
  ];
  $negative = [
    "equipmentCost", "cardOpportunityCost", "resourceWaste", "overblockCost",
    "lifeThresholdRisk", "fatigueCost",
  ];
  $value = [];
  $total = 0.0;
  foreach ($positive as $key) {
    $value[$key] = floatval($components[$key] ?? 0);
    $total += $value[$key];
  }
  foreach ($negative as $key) {
    $value[$key] = floatval($components[$key] ?? 0);
    $total -= $value[$key];
  }
  $value["total"] = $total;
  return $value;
}

function BotLifeThresholdRisk($lifeAfterDamage, $thresholds = null)
{
  if ($lifeAfterDamage <= 0) return INF;
  $thresholds ??= [
    ["atOrBelow" => 1, "cost" => 4.0],
    ["atOrBelow" => 2, "cost" => 2.5],
    ["atOrBelow" => 4, "cost" => 1.0],
  ];
  usort($thresholds, fn($left, $right) => $left["atOrBelow"] <=> $right["atOrBelow"]);
  foreach ($thresholds as $threshold) {
    if ($lifeAfterDamage <= $threshold["atOrBelow"]) return floatval($threshold["cost"]);
  }
  return 0.0;
}

function BotEvaluateOpponentResponse(
  $rawDamage,
  $opponentLife,
  $opponentHandCount,
  $opponentEquipmentDefense,
  $turnNumber
) {
  $attackThreat = max(0.0, floatval($rawDamage));
  if ($attackThreat == 0.0) {
    return [
      "rawDamage" => 0.0,
      "attackThreat" => 0.0,
      "expectedPrevention" => 0.0,
      "expectedDamage" => 0.0,
      "hitRate" => 1.0,
    ];
  }

  $lethalPressure = $rawDamage >= $opponentLife;
  $lowLife = $opponentLife <= 10;
  $reserveCards = ($lethalPressure || $lowLife) ? 0 : 1;
  $efficientCapacity = max(0, $opponentHandCount - $reserveCards) * 3;
  $maximumCapacity = $opponentHandCount * 3 + $opponentEquipmentDefense;
  $efficientPrevention = min($attackThreat, $efficientCapacity);
  $maximumPrevention = min($attackThreat, $maximumCapacity);
  $efficientWeight = $lethalPressure ? 0.3 : ($turnNumber <= 1 ? 0.2 : 0.55);
  $maximumWeight = $lethalPressure ? 0.6 : ($turnNumber <= 1 ? 0.7 : 0.25);
  $expectedPrevention = $efficientPrevention * $efficientWeight
    + $maximumPrevention * $maximumWeight;
  $expectedDamage = max(0.0, $attackThreat - $expectedPrevention);

  return [
    "rawDamage" => floatval($rawDamage),
    "attackThreat" => $attackThreat,
    "expectedPrevention" => $expectedPrevention,
    "expectedDamage" => $expectedDamage,
    "hitRate" => $expectedDamage / $attackThreat,
  ];
}

function BotResponseWeightedDamage($response)
{
  return floatval($response["rawDamage"] ?? 0) * 0.75
    + floatval($response["expectedDamage"] ?? 0) * 0.25;
}

function BotCardRolesFromStats($cardType, $pitch, $defense, $attack, $hasPrevention = false)
{
  $pitch = max(0, intval($pitch));
  $defense = max(0, intval($defense));
  $attack = max(0, intval($attack));
  $isAttack = $cardType == "AA";
  $isAttackReaction = $cardType == "AR";
  $isDefenseReaction = $cardType == "DR";

  $tags = [];
  if ($isAttack) $tags[] = "attack";
  if ($isAttackReaction) $tags[] = "attack-reaction";
  if ($isDefenseReaction) $tags[] = "defense-reaction";
  if ($hasPrevention || $isDefenseReaction) $tags[] = "prevention";
  if ($pitch == 1 && ($isAttack || $isAttackReaction)) $tags[] = "red-offense";
  if ($pitch == 3 && $defense >= 3) $tags[] = "blue-block-3";

  $colorPlayValue = max(0, 3 - $pitch) * 2;
  $reactionValue = $isDefenseReaction
    ? $defense + 7
    : ($isAttackReaction ? $attack + 6 : 0);
  $preventionValue = in_array("prevention", $tags, true) ? 8 : 0;
  $playValue = max($isAttack ? $attack + $colorPlayValue : 0, $reactionValue, $preventionValue, $pitch);
  $retainValue = $playValue + (($isDefenseReaction || $hasPrevention) ? 4 : 0);

  return [
    "playValue" => floatval($playValue),
    "pitchCost" => floatval($retainValue + ($pitch == 3 ? -2 : ($pitch == 1 ? 3 : 0))),
    "blockCost" => floatval($retainValue + ($defense >= 3 ? -1 : 2)),
    "retainValue" => floatval($retainValue),
    "arsenalValue" => floatval($retainValue + (($isDefenseReaction || $isAttackReaction) ? 4 : 0)),
    "tags" => $tags,
  ];
}

function BotNumericCost($cost)
{
  if (is_numeric($cost)) return max(0, intval($cost));
  if (!is_string($cost) || $cost === "") return 0;
  $choices = array_values(array_filter(explode(",", $cost), "is_numeric"));
  return count($choices) > 0 ? max(0, intval($choices[0])) : 0;
}

function BotCardRoles($cardID, $playerID = 2)
{
  $type = CardType($cardID);
  $pitch = PitchValue($cardID);
  $defense = BlockValue($cardID, $playerID, "HAND", false);
  $attack = PowerValue($cardID, $playerID, "HAND", -1, false, false);
  return BotCardRolesFromStats($type, $pitch, $defense, $attack);
}

function BotEquipmentDefense($playerID)
{
  $character = &GetPlayerCharacter($playerID);
  $pieces = CharacterPieces();
  $defense = 0;
  for ($index = 0, $count = count($character); $index < $count; $index += $pieces) {
    if (($character[$index + 1] ?? 0) != 2 || ($character[$index + 6] ?? 0) == 1) continue;
    $defense += max(0, intval(BlockValue($character[$index], $playerID, "EQUIP", false)));
  }
  return $defense;
}

function BotShouldPreserveOpeningHandForPlayer($playerID)
{
  global $currentTurn, $mainPlayer, $firstPlayer;
  if ($currentTurn != 0 || $mainPlayer != $playerID || $mainPlayer != $firstPlayer) return false;
  $opponentHand = &GetHand(3 - $playerID);
  return count($opponentHand) > 2;
}

function BotHasAttackInHand($playerID, $exceptCardID = null)
{
  $skipped = false;
  $hand = &GetHand($playerID);
  foreach ($hand as $cardID) {
    if (!$skipped && $exceptCardID !== null && $cardID === $exceptCardID) {
      $skipped = true;
      continue;
    }
    if (CardType($cardID) == "AA") return true;
  }
  return false;
}

function BotHeroAdjustment($cardID, $heroID, $context, $playerID)
{
  global $CS_NumAttacks;
  $adjustment = 0.0;
  $attacksThisTurn = isset($CS_NumAttacks) ? intval(GetClassState($playerID, $CS_NumAttacks)) : 0;

  if (($heroID == "ira_crimson_haze" || $heroID == "ira_scarlet_revenger" || $heroID == "ira") && $context == "Action") {
    if (CardType($cardID) == "AA" && $attacksThisTurn == 1) $adjustment += 3;
    if (str_starts_with($cardID, "flying_kick_") && $attacksThisTurn >= 2) $adjustment += 5;
    if ((str_starts_with($cardID, "soulbead_strike_") || str_starts_with($cardID, "torrent_of_tempo_"))
      && BotHasAttackInHand($playerID, $cardID)) $adjustment += 16;
    if (str_starts_with($cardID, "bittering_thorns_") && BotHasAttackInHand($playerID, $cardID)) {
      $adjustment += 7;
    }
    if (str_starts_with($cardID, "snatch_")) $adjustment += 4;
    if ($cardID == "edge_of_autumn" && $attacksThisTurn == 0) $adjustment += 8;
    if (function_exists("ComboActive") && ComboActive($cardID)) {
      if (str_starts_with($cardID, "seek_vengeance_")) $adjustment += 24;
      if ($cardID == "vengeance_never_rests_blue") $adjustment += 22;
      if ($cardID == "enact_vengeance_red") {
        $opponentArsenal = &GetArsenal(3 - $playerID);
        $adjustment += 10 + (count($opponentArsenal) > 0 ? 8 : 0);
      }
    }
  }

  if (($heroID == "fai_rising_rebellion" || $heroID == "fai") && $context == "Action") {
    if ($cardID === $heroID) return BotFaiHeroAdjustment($cardID, $playerID);
    if (str_starts_with($cardID, "brand_with_cinderclaw_") && $attacksThisTurn == 0) $adjustment += 8;
    if ($cardID == "spreading_flames_red" && $attacksThisTurn == 0) $adjustment += 9;
    if ($cardID == "lava_burst_red" && $attacksThisTurn < 3) $adjustment -= 6;
    if ($cardID == "phoenix_flame_red" && $attacksThisTurn == 0) $adjustment -= 4;
    $adjustment += BotFaiSupportAdjustment($cardID, $playerID);
  }

  return $adjustment;
}

function BotEndOfTurnAbilityThreshold()
{
  return 5.0;
}

function BotIsFaiPump($cardID)
{
  return str_starts_with($cardID, "rise_from_the_ashes_");
}

function BotFaiPumpValue($cardID)
{
  return match ($cardID) {
    "rise_from_the_ashes_red" => 4,
    "rise_from_the_ashes_yellow" => 3,
    default => 2,
  };
}

function BotFaiPumpActive($playerID)
{
  foreach (["rise_from_the_ashes_red", "rise_from_the_ashes_yellow", "rise_from_the_ashes_blue"] as $variant) {
    if (SearchCurrentTurnEffects($variant, $playerID)) return true;
  }
  return false;
}

function BotDraconicAttacksAvailable($playerID, $exceptCardID = null)
{
  $skipped = false;
  $count = 0;
  foreach (BotOffensiveCards($playerID) as $candidate) {
    if (!$skipped && $exceptCardID !== null && $candidate === $exceptCardID) {
      $skipped = true;
      continue;
    }
    if (CardType($candidate) == "AA" && TalentContains($candidate, "DRACONIC", $playerID)) ++$count;
  }
  return $count;
}

function BotFaiSupportAdjustment($cardID, $playerID)
{
  global $currentTurn, $CS_NumAttacks;
  if (!BotIsFaiPump($cardID)) return 0.0;
  if (BotFaiPumpActive($playerID)) return 0.0;
  if (BotDraconicAttacksAvailable($playerID, $cardID) == 0) return 0.0;

  $opponent = 3 - $playerID;
  $opponentHand = &GetHand($opponent);
  $response = BotEvaluateOpponentResponse(
    BotFaiPumpValue($cardID),
    max(1, intval(GetHealth($opponent))),
    count($opponentHand),
    BotEquipmentDefense($opponent),
    intval($currentTurn) + 1
  );
  $score = BotResponseWeightedDamage($response) * 10;
  $attacksThisTurn = isset($CS_NumAttacks) ? intval(GetClassState($playerID, $CS_NumAttacks)) : 0;
  if ($attacksThisTurn == 0) $score += 12;
  return $score;
}

function BotFaiHeroAdjustment($cardID, $playerID)
{
  if (SearchDiscardForCard($playerID, "phoenix_flame_red") === "") return -100.0;
  if (SearchCurrentTurnEffects("amnesia_red", $playerID)) return -100.0;
  $hand = &GetHand($playerID);
  $intellect = max(1, intval(CharacterIntellect($cardID)));
  if (count($hand) >= $intellect) return -100.0;
  $cost = BotNumericCost(AbilityCost($cardID));
  if ($cost <= 0) return 8.0;
  $resources = &GetResources($playerID);
  if (max(0, intval($resources[0] ?? 0)) >= $cost) return 2.0;
  return -100.0;
}

function BotActionPriority($cardID, $heroID, $playerID, $zone = "Hand")
{
  global $currentTurn;
  $type = CardType($cardID);
  $roles = BotCardRoles($cardID, $playerID);
  $isActivatedPermanent = in_array($zone, ["Character", "Item", "Ally"], true);
  $cost = BotNumericCost($isActivatedPermanent ? AbilityCost($cardID) : CardCost($cardID, "HAND"));
  $score = 0.0;

  if ($type == "AA" || $type == "W") {
    $attack = max(0, intval(PowerValue($cardID, $playerID, "HAND", -1, false, false)));
    $opponent = 3 - $playerID;
    $opponentHealth = max(1, intval(GetHealth($opponent)));
    $opponentHand = &GetHand($opponent);
    $response = BotEvaluateOpponentResponse(
      $attack,
      $opponentHealth,
      count($opponentHand),
      BotEquipmentDefense($opponent),
      intval($currentTurn) + 1
    );
    $score = BotResponseWeightedDamage($response) * 10;
    $hasGoAgain = $isActivatedPermanent
      ? AbilityHasGoAgain($cardID, $zone == "Character" ? "EQUIP" : "PLAY")
      : HasGoAgain($cardID, "HAND");
    if ($hasGoAgain) {
      $followUp = BotProjectedDamage(BotWithoutCard(BotOffensiveCards($playerID), $cardID), $playerID);
      $score += $followUp * 8;
    }
    if ($cost == 0) $score += 3;
    if ($type == "W" && $isActivatedPermanent) $score += BotWeaponActivationBonus();
    if ($attack >= $opponentHealth) $score += 100000;
  } else if ($type == "A") {
    $score = $roles["playValue"] + 2 - $cost;
  } else if ($type == "I") {
    $score = 1;
  } else if ($type == "AR" || $type == "DR") {
    return 0.0;
  } else if ($type == "E") {
    $score = 1;
  } else if ($type == "C" && $zone == "Character") {
    // Active hero abilities are still filtered by Talishar's IsPlayable gate.
    $score = 4;
  } else if ($isActivatedPermanent) {
    $score = 3;
  }

  return max(0.0, $score + BotHeroAdjustment($cardID, $heroID, "Action", $playerID));
}

function BotEquipmentWearCost($cardID, $defense)
{
  $defense = max(0, intval($defense));
  if (HasBattleworn($cardID)) return 1.0;
  if (HasTemper($cardID)) return $defense <= 1 ? 2.0 : 1.5;
  if (HasGuardwell($cardID)) return max(1.0, floatval($defense));
  if (HasBladeBreak($cardID)) return max(2.0, floatval($defense));
  return $defense > 0 ? 2.0 : 0.0;
}

function BotCardOpportunity($cardID, $playerID)
{
  if (BotIsFaiPump($cardID)) return floatval(BotFaiPumpValue($cardID));
  if ($cardID == "phoenix_flame_red") return 1.0;
  $type = CardType($cardID);
  if ($type == "DR") return 5.0;
  if ($type == "AR") return str_starts_with($cardID, "razor_reflex_") ? 7.0 : 5.0;
  if ($type == "AA" || $type == "W") {
    return max(0.0, floatval(PowerValue($cardID, $playerID, "HAND", -1, false, false)));
  }
  return max(1.0, floatval(PitchValue($cardID)));
}

function BotOffensiveCards($playerID)
{
  $cards = [];
  $hand = &GetHand($playerID);
  foreach ($hand as $cardID) {
    if ($cardID !== "") $cards[] = $cardID;
  }
  $arsenal = &GetArsenal($playerID);
  $arsenalPieces = ArsenalPieces();
  for ($index = 0, $count = count($arsenal); $index < $count; $index += $arsenalPieces) {
    if (($arsenal[$index] ?? "") !== "") $cards[] = $arsenal[$index];
  }
  return $cards;
}

function BotWithoutCard($cardIDs, $cardID)
{
  $found = array_search($cardID, $cardIDs, true);
  if ($found === false) return $cardIDs;
  array_splice($cardIDs, $found, 1);
  return $cardIDs;
}

function BotBestAttackSequence($attacks, $pitchPool)
{
  $count = count($attacks);
  if ($count == 0) return 0.0;
  $totalPitch = $pitchPool;
  foreach ($attacks as $attack) $totalPitch += $attack["pitch"];

  $best = 0.0;
  $walk = function ($used, $spentPitch, $spentCost, $damage)
    use (&$walk, $attacks, $count, $totalPitch, &$best) {
    if ($damage > $best) $best = $damage;
    for ($index = 0; $index < $count; ++$index) {
      if ($used & (1 << $index)) continue;
      $attack = $attacks[$index];
      $pitch = $spentPitch + $attack["pitch"];
      $cost = $spentCost + $attack["cost"];
      if ($totalPitch - $pitch - $cost < 0) continue;
      $damageAfter = $damage + $attack["power"];
      if ($attack["goAgain"]) $walk($used | (1 << $index), $pitch, $cost, $damageAfter);
      else if ($damageAfter > $best) $best = $damageAfter;
    }
  };
  $walk(0, 0, 0, 0.0);
  return $best;
}

function BotProjectedDamage($cardIDs, $playerID)
{
  static $cache = [];
  $key = $playerID . "|" . implode(",", $cardIDs);
  if (isset($cache[$key])) return $cache[$key];

  $attacks = [];
  $pitchPool = 0;
  $reactionDamage = 0.0;
  foreach ($cardIDs as $cardID) {
    $type = CardType($cardID);
    $power = max(0, intval(PowerValue($cardID, $playerID, "HAND", -1, false, false)));
    if ($type == "AA") {
      $attacks[] = [
        "power" => $power,
        "cost" => BotNumericCost(CardCost($cardID, "HAND")),
        "pitch" => max(0, intval(PitchValue($cardID))),
        "goAgain" => HasGoAgain($cardID, "HAND") ? true : false,
      ];
    } else if ($type == "AR") {
      $reactionDamage += str_starts_with($cardID, "razor_reflex_") ? 3.0 : $power;
    } else {
      $pitchPool += max(0, intval(PitchValue($cardID)));
    }
  }
  return $cache[$key] = $reactionDamage + BotBestAttackSequence($attacks, $pitchPool);
}

function BotIsOpeningTurnDefense($playerID)
{
  global $currentTurn, $mainPlayer;
  return intval($currentTurn) == 0 && $mainPlayer != $playerID;
}

function BotStopHitValue()
{
  return function_exists("ActiveOnHits") && ActiveOnHits() ? 3.0 : 0.0;
}

function BotEquipmentBlockRequired()
{
  global $CCS_RequiredEquipmentBlock;
  if (!isset($CCS_RequiredEquipmentBlock)) return false;
  return GetCombatChainState($CCS_RequiredEquipmentBlock) > NumEquipBlock("EQUIP");
}

function BotEquipmentBlockAllowed($playerID, $block, $incoming)
{
  $life = intval(GetHealth($playerID));
  if ($incoming >= $life) return true;
  if (BotLifeThresholdRisk($life - $incoming) > 0) return true;
  return $incoming - $block <= 0 && BotStopHitValue() > 0;
}

function BotScoreDefenseCandidate(
  $incoming,
  $life,
  $block,
  $opportunityCost,
  $isEquipment = false,
  $firstTurn = false,
  $onHitValue = 0.0,
  $offenseLoss = 0.0,
  $equipmentCost = 2.0
) {
  $incoming = max(0, intval($incoming));
  $life = max(0, intval($life));
  $block = max(0, intval($block));
  if ($incoming == 0 || $block == 0) return -1000000.0;

  $prevented = min($incoming, $block);
  $overblock = max(0, $block - $incoming);
  $unprevented = max(0, $incoming - $block);
  $opportunityCost = max(0.0, floatval($opportunityCost));
  $offenseLoss = max(0.0, floatval($offenseLoss));
  $equipmentCost = $isEquipment ? max(0.0, floatval($equipmentCost)) : 0.0;

  if ($incoming >= $life) {
    $preservation = $opportunityCost + $offenseLoss;
    if ($unprevented >= $life) return $block * 100 - $equipmentCost - $preservation;
    return 100000 + $prevented * 10 + $onHitValue * 10
      - $preservation * 10 - $overblock * 2 - $equipmentCost;
  }

  if ($firstTurn) {
    return $prevented * 100 + $onHitValue - $overblock * 1.5 - $equipmentCost;
  }

  $riskDelta = BotLifeThresholdRisk($life - $incoming) - BotLifeThresholdRisk($life - $unprevented);
  return $prevented
    + ($unprevented == 0 ? $onHitValue : 0.0)
    + $riskDelta
    - $overblock * 1.5
    - $offenseLoss
    - $opportunityCost * 0.1
    - $equipmentCost;
}

function BotDefensePriority($cardID, $playerID, $zone)
{
  if (CardType($cardID) == "DR") return 0.0;
  $isEquipment = ($zone == "Character");
  $block = max(0, intval(BlockValue($cardID, $playerID, $isEquipment ? "EQUIP" : "HAND", false)));
  if ($block == 0) return 0.0;
  $incoming = max(0, intval(CachedTotalPower()) - intval(CachedTotalBlock()));
  if ($incoming == 0) return 0.0;

  if ($isEquipment) {
    if (BotEquipmentBlockRequired()) return 100000.0 + $block;
    if (!BotEquipmentBlockAllowed($playerID, $block, $incoming)) return -1000000.0;
  }

  $offenseLoss = 0.0;
  $opportunity = 0.0;
  if (!$isEquipment) {
    $offense = BotOffensiveCards($playerID);
    $offenseLoss = max(0.0, BotProjectedDamage($offense, $playerID)
      - BotProjectedDamage(BotWithoutCard($offense, $cardID), $playerID));
    $opportunity = BotCardOpportunity($cardID, $playerID);
  }

  return BotScoreDefenseCandidate(
    $incoming,
    intval(GetHealth($playerID)),
    $block,
    $opportunity,
    $isEquipment,
    BotIsOpeningTurnDefense($playerID),
    BotStopHitValue(),
    $offenseLoss,
    $isEquipment ? BotEquipmentWearCost($cardID, $block) : 0.0
  );
}

function BotReactionPriority($cardID, $playerID, $zone = "Hand")
{
  $type = CardType($cardID);
  $roles = BotCardRoles($cardID, $playerID);
  if ($type == "DR") {
    $incoming = max(0, intval(CachedTotalPower()) - intval(CachedTotalBlock()));
    $defense = max(0, intval(BlockValue($cardID, $playerID, "HAND", false)));
    if ($incoming == 0 || $defense == 0) return 0.0;
    $overblock = max(0, $defense - $incoming);
    $score = 20 + min($incoming, $defense) * 4 - $overblock * 1.5;
    if ($incoming >= intval(GetHealth($playerID)) && $defense >= $incoming - intval(GetHealth($playerID)) + 1) {
      $score += 100000;
    }
    return $score;
  }
  if ($type == "AR") {
    if (str_starts_with($cardID, "razor_reflex_")) return 32;
    if ($cardID == "legacy_of_ikaru_blue") {
      return function_exists("ComboActive") && ComboActive($cardID) ? 18 : 10;
    }
    return 20 + $roles["playValue"];
  }

  if ($zone == "Character") {
    global $combatChain, $mainPlayer;
    if ($mainPlayer != $playerID || count($combatChain) == 0) return 0.0;
    if ($cardID == "snapdragon_scalers") {
      return !DoesAttackHaveGoAgain() && BotHasAttackInHand($playerID) ? 28 : 0;
    }
    if ($cardID == "okana_scar_wraps") {
      $attackID = $combatChain[0] ?? "";
      $isVengeanceAttack = str_contains($attackID, "vengeance");
      return $isVengeanceAttack && CachedTotalPower() >= CachedTotalBlock() ? 22 : 0;
    }
    $character = &GetPlayerCharacter($playerID);
    if ($cardID === ($character[0] ?? "") && ($cardID == "fai" || $cardID == "fai_rising_rebellion")) {
      return max(0.0, 4.0 + BotFaiHeroAdjustment($cardID, $playerID));
    }
    if (function_exists("GetResolvedAbilityType") && GetResolvedAbilityType($cardID, "EQUIP", $playerID) == "AR") {
      return 8;
    }
  }
  return 0.0;
}

function BotArsenalPriority($cardID, $playerID)
{
  $type = CardType($cardID);
  if ($type == "DR") return 90.0;
  if ($type == "AR") return 80.0;
  if ($type == "AA" && BotNumericCost(CardCost($cardID, "HAND")) == 0) {
    return 60.0 + max(0, intval(PowerValue($cardID, $playerID, "HAND", -1, false, false)));
  }
  return 25.0 + BotCardOpportunity($cardID, $playerID);
}

function BotPitchPriority($cardID, $playerID)
{
  if (BotIsFaiPump($cardID)) return 1.0;
  return 100.0 + intval(PitchValue($cardID)) * 10 - BotCardOpportunity($cardID, $playerID) * 2;
}


function BotPriority($cardID, $heroID, $type, $playerID = null, $zone = "Hand")
{
  global $currentPlayer;
  $playerID ??= intval($currentPlayer ?: 2);
  return match (intval($type)) {
    0 => BotDefensePriority($cardID, $playerID, $zone),
    1, 2 => BotActionPriority($cardID, $heroID, $playerID, $zone),
    3, 4 => BotReactionPriority($cardID, $playerID, $zone),
    5 => BotPitchPriority($cardID, $playerID),
    6 => BotArsenalPriority($cardID, $playerID),
    7 => max(0.0, BotActionPriority($cardID, $heroID, $playerID, $zone)),
    default => 0.0,
  };
}

function BotCardFromDecisionOption($phase, $option, $playerID)
{
  if ($phase == "CHOOSEDECK" || $phase == "MAYCHOOSEDECK") {
    $deck = &GetDeck($playerID);
    $index = intval($option) * DeckPieces();
    return $deck[$index] ?? null;
  }
  if (str_contains($option, "-")) {
    $cardID = GetMZCard($playerID, $option);
    return $cardID == "-" ? null : $cardID;
  }
  return null;
}

function BotWeaponActivationBonus()
{
  return 14.0;
}

function BotCurrentAttackPower($playerID)
{
  global $combatChain;
  $power = intval(CachedTotalPower());
  if ($power > 0) return $power;
  $attackID = $combatChain[0] ?? "";
  return $attackID === "" ? 0 : max(0, intval(PowerValue($attackID, $playerID, "CC")));
}

function BotAttackTargetScore($option, $playerID)
{
  $opponent = 3 - $playerID;
  $power = BotCurrentAttackPower($playerID);
  if (str_starts_with($option, "THEIRCHAR")) {
    return $power >= max(1, intval(GetHealth($opponent))) ? 100000.0 : 10.0;
  }
  if (str_starts_with($option, "THEIRALLY")) {
    $allies = &GetAllies($opponent);
    $index = intval(substr($option, strrpos($option, "-") + 1));
    return $power >= max(1, intval($allies[$index + 2] ?? 0)) ? 30.0 : 14.0;
  }
  if (str_starts_with($option, "THEIRAURAS")) return 12.0;
  return 1.0;
}

function BotWarOrPeace($playerID)
{
  $cards = BotOffensiveCards($playerID);
  $attackValue = BotProjectedDamage($cards, $playerID);
  $nonAttackValue = 0.0;
  foreach ($cards as $cardID) {
    if (CardType($cardID) == "A") $nonAttackValue += BotCardRoles($cardID, $playerID)["playValue"];
  }
  return $attackValue >= $nonAttackValue ? "War" : "Peace";
}

function BotChooseDecisionOption($phase, $options, $playerID, $context = "")
{
  $options = array_values(array_filter($options, fn($option) => $option !== ""));
  if (count($options) == 0) return "PASS";

  $context = strtolower(str_replace("_", " ", strval($context)));
  $normalized = array_map(fn($option) => strtolower(trim(strval($option))), $options);
  $warIndex = array_search("war", $normalized, true);
  $peaceIndex = array_search("peace", $normalized, true);
  if ($warIndex !== false && $peaceIndex !== false) {
    return $options[BotWarOrPeace($playerID) == "War" ? $warIndex : $peaceIndex];
  }

  $helpText = strtolower(str_replace("_", " ", strval(GetDQHelpText())));
  if (str_contains($helpText, "target for the attack")) {
    $bestTarget = 0;
    $bestTargetScore = -INF;
    foreach ($options as $index => $option) {
      $score = BotAttackTargetScore(trim(strval($option)), $playerID);
      if ($score > $bestTargetScore) {
        $bestTargetScore = $score;
        $bestTarget = $index;
      }
    }
    return $options[$bestTarget];
  }

  $yesIndex = array_search("yes", $normalized, true);
  $noIndex = array_search("no", $normalized, true);
  if ($yesIndex !== false && $noIndex !== false) {
    $decline = str_contains($context, "pay");
    return $options[$decline ? $noIndex : $yesIndex];
  }

  $bestIndex = 0;
  $bestScore = -INF;
  foreach ($options as $index => $option) {
    $normalizedOption = strtolower(trim(strval($option)));
    $cardID = BotCardFromDecisionOption($phase, $option, $playerID);
    if ($cardID !== null) {
      $roles = BotCardRoles($cardID, $playerID);
      $spendingChoice = str_contains($context, "discard")
        || str_contains($context, "bottom");
      $score = $spendingChoice ? -$roles["retainValue"] : $roles["playValue"];
    } else if ($normalizedOption == "pass" || $normalizedOption == "no") {
      $score = -2;
    } else if (preg_match('/^pay[ _-]?(\d+)$/i', strval($option), $matches)) {
      $score = intval($matches[1]) * 20;
    } else {
      $score = 1;
    }
    if ($score > $bestScore) {
      $bestScore = $score;
      $bestIndex = $index;
    }
  }
  return $options[$bestIndex];
}

?>
