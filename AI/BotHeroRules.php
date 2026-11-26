<?php

const BOT_HEXAGORE_MAX_SELF_DAMAGE = 2;
const BOT_FLAG_SHARPENED = 1;
const BOT_FLAG_CHARGED = 2;
const BOT_FLAG_WEAPON_SWUNG = 4;
const BOT_FLAG_DISCARDED_SIX = 8;
const BOT_FLAG_BLOOD_DEBT_SUPPRESSED = 16;
const BOT_BANISH_SIX_CHANCE = 0.8;
const BOT_FLAG_FLURRY = 32;

function BotCardFactOverrides()
{
  static $overrides = null;
  if ($overrides !== null) return $overrides;
  $sharpen = fn($power) => ["p" => $power, "pt" => "w:sword", "pn" => 1, "sf" => BOT_FLAG_SHARPENED];
  $overrides = [
    "edict_of_steel_red" => ["sf" => BOT_FLAG_SHARPENED | BOT_FLAG_FLURRY] + $sharpen(1), "edict_of_steel_yellow" => $sharpen(1), "edict_of_steel_blue" => $sharpen(1),
    "sharp_incline_red" => $sharpen(1), "sharp_incline_yellow" => $sharpen(1), "sharp_incline_blue" => $sharpen(1),
    "cut_n_carve_red" => $sharpen(1), "cut_n_carve_yellow" => $sharpen(1), "cut_n_carve_blue" => $sharpen(1),
    "drawn_to_the_blade_yellow" => $sharpen(1),
    "brimming_blade_red" => $sharpen(2),
    "swordmasters_path_red" => ["pt" => "w:sword"], "swordmasters_path_yellow" => ["pt" => "w:sword"], "swordmasters_path_blue" => ["pt" => "w:sword"],
    "showdown_red" => ["pt" => "w:sword"], "showdown_yellow" => ["pt" => "w:sword"], "showdown_blue" => ["pt" => "w:sword"],
    "big_blinder_red" => ["pt" => "w:sword"], "big_blinder_yellow" => ["pt" => "w:sword"], "big_blinder_blue" => ["pt" => "w:sword"],
    "point_of_escalation_yellow" => ["p" => 2, "pt" => "w:sword"],
    "beckon_steel_blue" => ["p" => 0],
    "ole_blue" => ["p" => 0],
    "and_again_blue" => ["aw" => 1],
    "wild_ride_red" => ["sf" => BOT_FLAG_DISCARDED_SIX], "wild_ride_yellow" => ["sf" => BOT_FLAG_DISCARDED_SIX],
    "bare_fangs_red" => ["sf" => BOT_FLAG_DISCARDED_SIX], "wrecker_romp_blue" => ["sf" => BOT_FLAG_DISCARDED_SIX],
    "beckoning_hunger_red" => ["sf" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED], "dread_screamer_red" => ["sf" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED],
    "endless_maw_red" => ["sf" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED], "feeding_frenzy_red" => ["sf" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED],
    "shadowrealm_horror_red" => ["sf" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED],
    "pull_from_beyond_blue" => ["sf" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED],
    "pledge_fealty_red" => ["bv" => 2],
    "felling_swing_red" => ["pt" => "w:axe"], "felling_swing_yellow" => ["pt" => "w:axe"], "felling_swing_blue" => ["pt" => "w:axe"],
    "hit_and_run_red" => ["p" => 0, "pga" => 1, "pt" => "w", "pn" => 1],
    "hit_and_run_yellow" => ["p" => 0, "pga" => 1, "pt" => "w", "pn" => 1],
    "hit_and_run_blue" => ["p" => 0, "pga" => 1, "pt" => "w", "pn" => 1],
    "fatal_engagement_red" => ["p" => 2], "fatal_engagement_yellow" => ["p" => 1.6], "fatal_engagement_blue" => ["p" => 1.2],
    "ironsong_response_red" => ["p" => 1.5], "ironsong_response_yellow" => ["p" => 1], "ironsong_response_blue" => ["p" => 0.5],
  ];
  return $overrides;
}

function BotHeroID($playerID)
{
  $character = &GetPlayerCharacter($playerID);
  return $character[0] ?? "";
}

function BotHexagoreSelfDamage($playerID)
{
  return max(0, 6 - SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true)));
}

function BotWeaponSwingAllowed($weaponID, $playerID)
{
  if ($weaponID != "hexagore_the_death_hydra") return true;
  $selfDamage = BotHexagoreSelfDamage($playerID);
  if ($selfDamage <= BOT_HEXAGORE_MAX_SELF_DAMAGE) return true;
  return $selfDamage < intval(GetHealth($playerID)) && 6 >= intval(GetHealth(3 - $playerID));
}

function BotWeaponSolverPower($weaponID, $playerID, $power)
{
  return $weaponID == "raydn_duskbane" ? $power + 3 : $power;
}

function BotHeroWeaponPatch($weapon, $playerID)
{
  $weapon["setFlags"] |= BOT_FLAG_WEAPON_SWUNG;
  if ($weapon["id"] == "raydn_duskbane") $weapon["needFlags"] |= BOT_FLAG_CHARGED;
  if ($weapon["id"] == "zenith_blade") $weapon["goAgainFlags"] = BOT_FLAG_SHARPENED;
  if ($weapon["id"] == "mandible_claw" || $weapon["id"] == "mandible_claw_r") $weapon["goAgainFlags"] = BOT_FLAG_DISCARDED_SIX;
  $weapon["draconicGoAgain"] = BotIsObsidianFireVein($weapon["id"]);
  if (BotHeroID($playerID) == "fang_dracai_of_blades" && str_contains($weapon["subtype"], "dagger") && BotFealtyCount($playerID) >= 3) $weapon["cost"] = max(0, $weapon["cost"] - 1);
  return $weapon;
}

function BotIsObsidianFireVein($cardID)
{
  return $cardID == "obsidian_fire_vein" || $cardID == "obsidian_fire_vein_r";
}

function BotDraconicVeinBonus($cardID, $playerID)
{
  global $CombatChain, $mainPlayer;
  if ($mainPlayer != $playerID || !$CombatChain->HasCurrentLink()) return 0.0;
  if (!BotIsObsidianFireVein($CombatChain->AttackCard()->ID()) || DoesAttackHaveGoAgain()) return 0.0;
  if (!TalentContains($cardID, "DRACONIC", $playerID)) return 0.0;
  $character = &GetPlayerCharacter($playerID);
  $pieces = CharacterPieces();
  for ($i = 0, $count = count($character); $i < $count; $i += $pieces) {
    if (BotIsObsidianFireVein($character[$i]) && intval($character[$i + 1] ?? 0) == 2 && intval($character[$i + 5] ?? 0) > 0) return 3.0;
  }
  return count(BotOffensiveHandCards($playerID)) > 1 ? 3.0 : 1.0;
}

function BotHeroExtraWeaponSwing($weaponID, $playerID, $currentTurn)
{
  global $CS_AttacksWithWeapon;
  if (BotHeroID($playerID) != "dorinthea") return false;
  return !$currentTurn || intval(GetClassState($playerID, $CS_AttacksWithWeapon)) == 0;
}

function BotHeroCardPatch($item, $playerID, $context)
{
  $item["draconic"] = ($item["kind"] == "attack-reaction" || $item["kind"] == "instant") && TalentContains($item["id"], "DRACONIC", $playerID);
  if ($item["id"] == "hunts_end_red" && BotFealtyCount($playerID) < 3) $item["pump"] = 0.0;
  if ($item["id"] == "edict_of_steel_yellow" || $item["id"] == "edict_of_steel_blue") {
    $zenith = FindCharacterIndex($playerID, "zenith_blade");
    $character = &GetPlayerCharacter($playerID);
    $counters = $zenith == -1 ? 0 : intval($character[$zenith + 3] ?? 0);
    if ($counters + 1 >= ($item["id"] == "edict_of_steel_yellow" ? 2 : 3)) $item["setFlags"] |= BOT_FLAG_FLURRY;
  }
  if (empty(BotCardFacts($item["id"])["aw"])) return $item;
  $index = FindCharacterIndex($playerID, "zenith_blade");
  if ($index == -1) return $item;
  $item["kind"] = "attack";
  $character = &GetPlayerCharacter($playerID);
  $item["power"] = max(0, intval(PowerValue("zenith_blade", $playerID, "CHAR", $index, false, false)) + intval($character[$index + 3] ?? 0));
  $item["goAgain"] = false;
  $item["isAA"] = false;
  $item["subtype"] = "sword";
  $item["needFlags"] |= BOT_FLAG_SHARPENED | BOT_FLAG_WEAPON_SWUNG;
  return $item;
}

function BotHeroStartFlags($playerID)
{
  global $CS_NumCharged, $CS_AttacksWithWeapon, $CS_Num6PowDisc, $CS_Num6PowPutIntoBanish;
  $flags = 0;
  if (intval(GetClassState($playerID, $CS_Num6PowPutIntoBanish)) > 0) $flags |= BOT_FLAG_BLOOD_DEBT_SUPPRESSED;
  if (intval(GetClassState($playerID, $CS_Num6PowDisc)) > 0) $flags |= BOT_FLAG_DISCARDED_SIX;
  if (SearchCurrentTurnEffects("SHARPEN", $playerID)) $flags |= BOT_FLAG_SHARPENED;
  if (intval(GetClassState($playerID, $CS_NumCharged)) > 0) $flags |= BOT_FLAG_CHARGED;
  if (intval(GetClassState($playerID, $CS_AttacksWithWeapon)) > 0) $flags |= BOT_FLAG_WEAPON_SWUNG;
  return $flags | BotHeroNextTurnFlags($playerID);
}

function BotHeroNextTurnFlags($playerID)
{
  $flags = 0;
  if (SearchAurasForCard("flurry", $playerID) !== "") $flags |= BOT_FLAG_FLURRY;
  return $flags;
}

function BotHeroStartPending($playerID, $currentTurn)
{
  $pending = [];
  $auras = &GetAuras($playerID);
  $pieces = AuraPieces();
  for ($i = 0, $count = count($auras); $i < $count; $i += $pieces) {
    if ($auras[$i] == "embodiment_of_lightning") {
      $pending[] = ["key" => "embodiment" . $i, "power" => 0.0, "target" => "aa", "goAgain" => true];
    } else if (!$currentTurn && $auras[$i] == "might") {
      $pending[] = ["key" => "might" . $i, "power" => 1.0, "target" => "any", "goAgain" => false];
    } else if (!$currentTurn && $auras[$i] == "agility") {
      $pending[] = ["key" => "agility" . $i, "power" => 0.0, "target" => "any", "goAgain" => true];
    }
  }
  if ($currentTurn) {
    if (SearchCurrentTurnEffects("might", $playerID)) $pending[] = ["key" => "might", "power" => 1.0, "target" => "any", "goAgain" => false];
    if (SearchCurrentTurnEffects("agility", $playerID)) $pending[] = ["key" => "agility", "power" => 0.0, "target" => "any", "goAgain" => true];
  }
  return $pending;
}

function BotHeroNextTurnResources($playerID)
{
  return SearchCount(SearchAurasForCard("vigor", $playerID));
}

function BotAbilityUses($kind, $cost, $goAgain, $pump, $pumpTarget, $pumpGoAgain, $setFlags = 0)
{
  return [
    "kind" => $kind, "power" => 0, "defense" => 0, "pitch" => 0, "cost" => $cost, "goAgain" => $goAgain,
    "pump" => floatval($pump), "pumpNext" => true, "pumpTarget" => $pumpTarget, "pumpGoAgain" => $pumpGoAgain,
    "ambush" => false, "arsenalPlay" => false, "alternative" => 0.0, "onHit" => 0.0,
    "setFlags" => $setFlags, "needFlags" => 0, "goAgainFlags" => 0,
  ];
}

function BotHeroAbilityUses($heroID)
{
  return match ($heroID) {
    "hala_bladesaint_of_the_vow" => BotAbilityUses("action", 3, true, 1, "w:sword", false, BOT_FLAG_SHARPENED),
    "aurora_legacy_of_tempest" => BotAbilityUses("instant", 2, false, 0, "aa", true),
    default => null,
  };
}

function BotHeroSolverSources($playerID, $currentTurn)
{
  global $turn;
  $heroID = BotHeroID($playerID);
  if (BotHeroAbilityUses($heroID) === null) return [];
  $character = &GetPlayerCharacter($playerID);
  if ($currentTurn && (intval($character[1] ?? 0) != 2 || !IsPlayable($heroID, $turn[0], "CHAR", 0))) return [];
  if ($heroID == "hala_bladesaint_of_the_vow" && FindCharacterIndex($playerID, "zenith_blade") == -1) return [];
  if ($heroID == "aurora_legacy_of_tempest" && SearchAurasForCard("lightning_flow", $playerID) === "") return [];
  return [["id" => $heroID, "zone" => "CHAR", "index" => 0]];
}

function BotHeroHasSolverSource($cardID, $playerID)
{
  foreach (BotHeroSolverSources($playerID, true) as $source) {
    if ($source["id"] === $cardID) return true;
  }
  return false;
}

function BotBoltynReactionPriority($playerID)
{
  global $mainPlayer, $actionPoints;
  if ($mainPlayer != $playerID || DoesAttackHaveGoAgain() || !HasIncreasedAttack()) return 0.0;
  if (count(GetSoul($playerID)) == 0 || intval($actionPoints) > 0) return 0.0;
  $budget = BotAvailablePitch($playerID);
  foreach (BotOffensiveHandCards($playerID) as $card) {
    if (CardType($card["id"]) != "AA") continue;
    $cost = BotEffectiveCost($card["id"], $card["zone"], $card["index"], CardCost($card["id"], $card["zone"]));
    $pitch = $card["zone"] == "HAND" ? intval(PitchValue($card["id"])) : 0;
    if ($cost <= $budget - $pitch) return 24.0;
  }
  $raydn = FindCharacterIndex($playerID, "raydn_duskbane");
  $character = &GetPlayerCharacter($playerID);
  if ($raydn != -1 && intval($character[$raydn + 1] ?? 0) == 2 && intval($character[$raydn + 5] ?? 0) > 0) return 24.0;
  return 0.0;
}

function BotHeroFlagValues($playerID)
{
  $heroID = BotHeroID($playerID);
  if ($heroID != "levia_shadowborn_abomination" && $heroID != "levia") return [];
  $drain = SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true));
  return $drain > 0 ? [BOT_FLAG_BLOOD_DEBT_SUPPRESSED => $drain * BOT_BANISH_SIX_CHANCE] : [];
}

function BotFealtyCount($playerID)
{
  return SearchCount(SearchAurasForCard("fealty", $playerID));
}

function BotHeroFlurrySwing($weaponID, $playerID)
{
  return $weaponID == "zenith_blade" && BotHeroID($playerID) == "hala_bladesaint_of_the_vow";
}

function BotLeviaSuppressionNeeded($playerID)
{
  global $CS_Num6PowPutIntoBanish;
  $heroID = BotHeroID($playerID);
  if ($heroID != "levia_shadowborn_abomination" && $heroID != "levia") return false;
  if (intval(GetClassState($playerID, $CS_Num6PowPutIntoBanish)) > 0) return false;
  return SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true)) >= 2;
}

function BotHeroEndTurnNode($playerID)
{
  global $turn;
  if (!BotLeviaSuppressionNeeded($playerID)) return null;
  $hand = &GetHand($playerID);
  foreach (["cleave_the_heavens_red", "blood_harvest"] as $cardID) {
    foreach ($hand as $index => $handCard) {
      if ($handCard == $cardID && IsPlayable($cardID, $turn[0], "HAND", $index)) return [$cardID, "Hand", $index, 1];
    }
  }
  return null;
}
