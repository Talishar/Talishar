<?php

const BOT_HEXAGORE_MAX_SELF_DAMAGE = 2;
const BOT_FLAG_SHARPENED = 1;
const BOT_FLAG_CHARGED = 2;
const BOT_FLAG_WEAPON_SWUNG = 4;
const BOT_FLAG_DISCARDED_SIX = 8;
const BOT_FLAG_BLOOD_DEBT_SUPPRESSED = 16;
const BOT_BANISH_SIX_CHANCE = 0.8;
const BOT_FLAG_FLURRY = 32;
const BOT_FLAG_YELLOW_SOUL = 64;
const BOT_FLAG_SOUL = 128;
const BOT_FLAG_LUMINA = 256;
const BOT_FLAG_LIGHTNING_ATTACK = 512;
const BOT_FLAG_BLOODRUSH = 1024;
const BOT_FLAG_NEVER = 1 << 20;
const BOT_LINK_SHIFT = 12;
const BOT_DRAC_SHIFT = 14;
const BOT_RED_SHIFT = 16;
const BOT_COUNTER_MAX = 3;
const BOT_FLAG_LIGHTNING_PLAYED = 1 << 18;
const BOT_FLAG_LAST_EDGE = 1 << 21;
const BOT_FLAG_LAST_TIGER = 1 << 22;
const BOT_COMBO_FLAGS = BOT_FLAG_LAST_EDGE | BOT_FLAG_LAST_TIGER;
const BOT_AGILITY_SETUP_VALUE = 2.5;
const BOT_MIGHT_SETUP_VALUE = 1.0;
const BOT_SOUL_HARVESTER_VALUE = 8.0;
const BOT_LEVIA_HAND_BANISHERS = ["cleave_the_heavens_red" => 0, "fallen_herald_yellow" => 0, "blood_harvest" => 3, "consuming_lash_yellow" => -1];

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
    "wild_ride_blue" => ["sf" => BOT_FLAG_DISCARDED_SIX], "bare_fangs_yellow" => ["sf" => BOT_FLAG_DISCARDED_SIX], "bare_fangs_blue" => ["sf" => BOT_FLAG_DISCARDED_SIX],
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
    "bravery_of_the_blade_red" => ["gf" => BOT_FLAG_CHARGED],
    "beaming_bravado_red" => ["ycp" => 1], "beaming_bravado_yellow" => ["ycp" => 1], "beaming_bravado_blue" => ["ycp" => 1],
    "duty_bound_blitz_red" => ["nf" => BOT_FLAG_YELLOW_SOUL], "duty_bound_blitz_yellow" => ["nf" => BOT_FLAG_YELLOW_SOUL], "duty_bound_blitz_blue" => ["nf" => BOT_FLAG_YELLOW_SOUL],
    "courageous_steelhand_red" => ["nf" => BOT_FLAG_CHARGED], "courageous_steelhand_yellow" => ["nf" => BOT_FLAG_CHARGED], "courageous_steelhand_blue" => ["nf" => BOT_FLAG_CHARGED],
    "lumina_ascension_yellow" => ["p" => 1, "pt" => "w", "sf" => BOT_FLAG_LUMINA],
    "blessing_of_suraya_yellow" => ["bv" => 1.5], "blessing_of_bellona_yellow" => ["bv" => 1.5],
    "beacon_of_victory_yellow" => ["p" => 1, "pt" => "any", "nf" => BOT_FLAG_SOUL],
    "rites_of_nightfall_blue" => ["bv" => 2.0],
  ];
  return $overrides;
}

function BotDiscardSixChance($cardID, $playerID)
{
  $others = 0;
  $six = 0;
  $skipped = false;
  foreach (GetHand($playerID) as $handCard) {
    if ($handCard === "") continue;
    if (!$skipped && $handCard == $cardID) { $skipped = true; continue; }
    ++$others;
    if (intval(PowerValue($handCard, $playerID, "HAND", -1, false, false)) >= 6) ++$six;
  }
  $deck = &GetDeck($playerID);
  $deckCards = 0;
  $deckSix = 0;
  for ($i = 0, $count = count($deck), $pieces = DeckPieces(); $i < $count; $i += $pieces) {
    if ($deck[$i] === "") continue;
    ++$deckCards;
    if (intval(PowerValue($deck[$i], $playerID, "HAND", -1, false, false)) >= 6) ++$deckSix;
  }
  return ($six + ($deckCards > 0 ? $deckSix / $deckCards : 0.0)) / ($others + 1);
}

function BotCounter($flags, $shift)
{
  return ($flags >> $shift) & BOT_COUNTER_MAX;
}

function BotCounterAdd($flags, $shift, $amount = 1)
{
  $value = min(BOT_COUNTER_MAX, BotCounter($flags, $shift) + max(0, intval($amount)));
  return ($flags & ~(BOT_COUNTER_MAX << $shift)) | ($value << $shift);
}

function BotComboSourceFlag($cardID)
{
  return match ($cardID) {
    "edge_of_autumn" => BOT_FLAG_LAST_EDGE,
    "crouching_tiger" => BOT_FLAG_LAST_TIGER,
    default => 0,
  };
}

function BotCardConditions($cardID)
{
  static $cache = [];
  if (isset($cache[$cardID])) return $cache[$cardID];
  $base = preg_replace('/_(red|yellow|blue)$/', '', $cardID);
  return $cache[$cardID] = match ($base) {
    "soulbead_strike", "torrent_of_tempo" => ["goh" => true],
    "lava_vein_loyalty", "cinderskin_devotion", "hot_on_their_heels", "mark_with_magma", "searing_emberblade" => ["ga" => "drac2"],
    "blaze_headlong" => ["ga" => "red2"],
    "scar_for_a_scar", "life_for_a_life", "blow_for_a_blow", "pound_for_pound" => ["ga" => "behind"],
    "star_fall" => ["ga" => "lightning", "lgp" => 1],
    "enflame_the_firebrand" => ["ga" => "drac2", "dp" => [4, 2]],
    "flying_kick" => ["lp" => [2, 2]],
    "lava_burst" => ["lp" => [3, 3]],
    "phoenix_flame" => ["dp" => [2, 1]],
    "pouncing_qi" => ["combo" => BOT_FLAG_LAST_TIGER, "cp" => 1, "cga" => true],
    "seek_vengeance", "vengeance_never_rests" => ["combo" => BOT_FLAG_LAST_EDGE, "cga" => true],
    default => [],
  };
}

function BotKayoHandPowerBonus($playerID)
{
  $character = &GetPlayerCharacter($playerID);
  $heroID = ShiyanaCharacter($character[0] ?? "");
  return ($heroID == "kayo" || $heroID == "kayo_armed_and_dangerous") && intval($character[1] ?? 0) < 3;
}

function BotIsLookTuff($cardID)
{
  return in_array($cardID, ["look_tuff_red", "chest_puff_red", "bluster_buff_red"], true);
}

function BotConditionalPower($attack, $flags)
{
  $cond = $attack["cond"] ?? [];
  $links = BotCounter($flags, BOT_LINK_SHIFT);
  $bonus = !empty($attack["iraSecond"]) && $links == 1 ? 1 : 0;
  if (isset($cond["lp"]) && $links >= $cond["lp"][0]) $bonus += $cond["lp"][1];
  if (isset($cond["dp"]) && BotCounter($flags, BOT_DRAC_SHIFT) + (!empty($attack["dracAttack"]) ? 1 : 0) >= $cond["dp"][0]) $bonus += $cond["dp"][1];
  if (isset($cond["combo"]) && ($flags & $cond["combo"])) $bonus += $cond["cp"] ?? 0;
  if (isset($cond["lgp"]) && ($flags & BOT_FLAG_LIGHTNING_PLAYED)) $bonus += $cond["lgp"];
  return $bonus;
}

function BotConditionalGoAgain($attack, $flags)
{
  $cond = $attack["cond"] ?? [];
  if (isset($cond["combo"]) && !empty($cond["cga"]) && ($flags & $cond["combo"])) return true;
  return match ($cond["ga"] ?? "") {
    "drac2" => BotCounter($flags, BOT_DRAC_SHIFT) + (!empty($attack["dracAttack"]) ? 1 : 0) >= 2,
    "red2" => BotCounter($flags, BOT_RED_SHIFT) + (!empty($attack["red"]) ? 1 : 0) >= 2,
    "lightning" => ($flags & BOT_FLAG_LIGHTNING_PLAYED) != 0,
    default => false,
  };
}

function BotPlayedFlags($flags, $item)
{
  if (!empty($item["red"])) $flags = BotCounterAdd($flags, BOT_RED_SHIFT);
  if (!empty($item["lightning"])) $flags |= BOT_FLAG_LIGHTNING_PLAYED;
  return $flags;
}

function BotAttackedFlags($flags, $attack)
{
  $flags = BotCounterAdd(BotPlayedFlags($flags, $attack), BOT_LINK_SHIFT);
  if (!empty($attack["dracAttack"])) $flags = BotCounterAdd($flags, BOT_DRAC_SHIFT);
  return ($flags & ~BOT_COMBO_FLAGS) | BotComboSourceFlag($attack["id"] ?? "");
}

function BotChainStartFlags($playerID)
{
  global $CS_NumAttacks, $CS_NumRedPlayed, $CS_NumLightningPlayed, $ChainLinks;
  $flags = BotCounterAdd(0, BOT_LINK_SHIFT, intval(GetClassState($playerID, $CS_NumAttacks)));
  $flags = BotCounterAdd($flags, BOT_DRAC_SHIFT, NumDraconicChainLinks());
  $flags = BotCounterAdd($flags, BOT_RED_SHIFT, intval(GetClassState($playerID, $CS_NumRedPlayed)));
  if (intval(GetClassState($playerID, $CS_NumLightningPlayed)) > 0) $flags |= BOT_FLAG_LIGHTNING_PLAYED;
  if (isset($ChainLinks) && $ChainLinks->NumLinks() > 0) $flags |= BotComboSourceFlag($ChainLinks->LastLink()->AttackCard()->ID());
  return $flags;
}

function BotIsIra($playerID)
{
  $heroID = BotHeroID($playerID);
  return $heroID == "ira_crimson_haze" || $heroID == "ira_scarlet_revenger";
}

function BotKodachiGoAgain($playerID)
{
  if (SearchCount(SearchPitch($playerID, minCost: 0, maxCost: 0)) > 0) return true;
  foreach (GetHand($playerID) as $cardID) {
    if ($cardID !== "" && intval(PitchValue($cardID)) > 0 && BotNumericCost(CardCost($cardID, "HAND")) == 0 && CardType($cardID) != "W") return true;
  }
  return false;
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
  if ($weapon["id"] == "raydn_duskbane") {
    $weapon["needFlags"] |= BOT_FLAG_CHARGED;
    $weapon["chargedPower"] = true;
  }
  if ($weapon["id"] == "zenith_blade") $weapon["goAgainFlags"] = BOT_FLAG_SHARPENED;
  if ($weapon["id"] == "scorpio_comet_tail") $weapon["needFlags"] |= BOT_FLAG_LIGHTNING_ATTACK;
  if ($weapon["id"] == "mandible_claw" || $weapon["id"] == "mandible_claw_r") $weapon["goAgainFlags"] = BOT_FLAG_DISCARDED_SIX;
  $weapon["draconicGoAgain"] = BotIsObsidianFireVein($weapon["id"]);
  if ($weapon["id"] == "hexagore_the_death_hydra") $weapon["selfCost"] = BotHexagoreSelfDamage($playerID);
  if (BotHeroID($playerID) == "fang_dracai_of_blades" && str_contains($weapon["subtype"], "dagger") && BotFealtyCount($playerID) >= 3) $weapon["cost"] = max(0, $weapon["cost"] - 1);
  $weapon["cond"] = BotCardConditions($weapon["id"]);
  $weapon["dracAttack"] = TalentContains($weapon["id"], "DRACONIC", $playerID);
  $weapon["iraSecond"] = BotIsIra($playerID);
  if ($weapon["id"] == "harmonized_kodachi" || $weapon["id"] == "harmonized_kodachi_r") $weapon["goAgain"] = $weapon["goAgain"] || BotKodachiGoAgain($playerID);
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
  $item["cond"] = BotCardConditions($item["id"]);
  $item["dracAttack"] = $item["kind"] == "attack" && TalentContains($item["id"], "DRACONIC", $playerID);
  $item["red"] = intval(PitchValue($item["id"])) == 1;
  $item["lightning"] = TalentContains($item["id"], "LIGHTNING", $playerID);
  $item["iraSecond"] = $item["kind"] == "attack" && BotIsIra($playerID);
  if (($item["cond"]["ga"] ?? "") == "behind" && PlayerHasLessHealth($playerID)) $item["goAgain"] = true;
  if (BotIsLookTuff($item["id"])) $item["cost"] += 1;
  if ($item["kind"] == "attack" && BotKayoHandPowerBonus($playerID)) $item["power"] = max(0, $item["power"] - 1);
  if ($item["id"] == "hunt_to_the_ends_of_rathe_red" && CheckMarked(3 - $playerID)) $item["power"] += 2;
  if (str_starts_with($item["id"], "wild_ride_") || str_starts_with($item["id"], "bare_fangs_")) {
    $chance = BotDiscardSixChance($item["id"], $playerID);
    if (str_starts_with($item["id"], "wild_ride_")) $item["cond"]["gop"] = $chance;
    else $item["power"] += 2 * $chance;
  }
  if ($item["kind"] == "attack" && TalentContains($item["id"], "LIGHTNING", $playerID)) $item["setFlags"] |= BOT_FLAG_LIGHTNING_ATTACK;
  $item["brute"] = $item["kind"] == "attack" && ClassContains($item["id"], "BRUTE", $playerID);
  if ($item["id"] == "bloodrush_bellow_yellow") {
    $others = 0;
    $short = 0;
    $skipped = false;
    foreach (GetHand($playerID) as $cardID) {
      if (!$skipped && $cardID == $item["id"]) { $skipped = true; continue; }
      ++$others;
      if (intval(PowerValue($cardID, $playerID, "HAND", -1, false, false)) < 6) ++$short;
    }
    $hits = $others > 0 && $short <= 1;
    $item["pump"] = 0.0;
    $item["goAgain"] = $hits;
    $item["bonus"] = $hits ? BOT_DRAW_VALUE : -BOT_DRAW_VALUE;
    $item["setFlags"] |= BOT_FLAG_BLOODRUSH;
  }
  if ($item["id"] == "hunts_end_red" && BotFealtyCount($playerID) < 3) $item["pump"] = 0.0;
  if ($item["id"] == "celestial_cataclysm_yellow" && count(GetSoul($playerID)) < 3) $item["needFlags"] |= BOT_FLAG_NEVER;
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
  if (intval(GetClassState($playerID, "NumYellowPutSoul")) > 0) $flags |= BOT_FLAG_YELLOW_SOUL;
  if (SearchCurrentTurnEffects("lumina_ascension_yellow", $playerID)) $flags |= BOT_FLAG_LUMINA;
  if (count(GetSoul($playerID)) > 0) $flags |= BOT_FLAG_SOUL;
  if (BotLightningAttackOnChain($playerID)) $flags |= BOT_FLAG_LIGHTNING_ATTACK;
  if (SearchCurrentTurnEffects("bloodrush_bellow_yellow", $playerID)) $flags |= BOT_FLAG_BLOODRUSH;
  return $flags | BotChainStartFlags($playerID) | BotHeroNextTurnFlags($playerID, true);
}

function BotLightningAttackOnChain($playerID)
{
  global $ChainLinks;
  if (!isset($ChainLinks)) return false;
  for ($i = 0, $count = $ChainLinks->NumLinks(); $i < $count; ++$i) {
    $attack = $ChainLinks->GetLink($i)->AttackCard();
    if ($attack->StillOnChain() && TalentContains($attack->ID(), "LIGHTNING", $playerID)) return true;
  }
  return false;
}

function BotHeroNextTurnFlags($playerID, $currentTurn = false)
{
  $flags = 0;
  if (SearchAurasForCard("flurry", $playerID) !== "") $flags |= BOT_FLAG_FLURRY;
  if (!$currentTurn && BotIsBoltyn($playerID)) {
    if (count(GetSoul($playerID)) > 0) $flags |= BOT_FLAG_SOUL;
    if (SearchAurasForCard("blessing_of_suraya_yellow", $playerID) !== "" || SearchAurasForCard("blessing_of_bellona_yellow", $playerID) !== "") $flags |= BOT_FLAG_YELLOW_SOUL | BOT_FLAG_SOUL;
  }
  return $flags;
}

function BotIsBoltyn($playerID)
{
  $heroID = BotHeroID($playerID);
  return $heroID == "ser_boltyn_breaker_of_dawn" || $heroID == "boltyn";
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

function BotAbilityUses($kind, $cost, $goAgain, $pump, $pumpTarget, $pumpGoAgain, $setFlags = 0, $needFlags = 0)
{
  return [
    "kind" => $kind, "power" => 0, "defense" => 0, "pitch" => 0, "cost" => $cost, "goAgain" => $goAgain,
    "pump" => floatval($pump), "pumpNext" => true, "pumpTarget" => $pumpTarget, "pumpGoAgain" => $pumpGoAgain,
    "ambush" => false, "arsenalPlay" => false, "alternative" => 0.0, "onHit" => 0.0,
    "setFlags" => $setFlags, "needFlags" => $needFlags, "goAgainFlags" => 0,
  ];
}

function BotHeroAbilityUses($heroID)
{
  return match ($heroID) {
    "hala_bladesaint_of_the_vow" => BotAbilityUses("action", 3, true, 1, "w:sword", false, BOT_FLAG_SHARPENED),
    "aurora_legacy_of_tempest" => BotAbilityUses("instant", 2, false, 0, "aa", true),
    "ser_boltyn_breaker_of_dawn" => ["pumpNext" => false] + BotAbilityUses("attack-reaction", 0, false, 0, "w|pumped", true, 0, BOT_FLAG_SOUL),
    default => null,
  };
}

function BotHeroSolverSources($playerID, $currentTurn)
{
  global $turn;
  $heroID = BotHeroID($playerID);
  if (BotHeroAbilityUses($heroID) === null) return [];
  $character = &GetPlayerCharacter($playerID);
  if ($heroID == "ser_boltyn_breaker_of_dawn") return intval($character[1] ?? 0) == 2 ? [["id" => $heroID, "zone" => "CHAR", "index" => 0]] : [];
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
  if (!BotIsLevia($playerID)) return [];
  $drain = BotLeviaDrain($playerID);
  return $drain > 0 ? [BOT_FLAG_BLOOD_DEBT_SUPPRESSED => $drain * BOT_BANISH_SIX_CHANCE] : [];
}

function BotLeviaDrain($playerID)
{
  return SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true));
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
  if (!BotIsLevia($playerID)) return false;
  if (intval(GetClassState($playerID, $CS_Num6PowPutIntoBanish)) > 0) return false;
  return BotLeviaDrain($playerID) >= 2;
}

function BotLeviaRedeemedNode($playerID)
{
  global $turn, $CS_Num6PowPutIntoBanish;
  if (!BotIsLevia($playerID)) return null;
  $life = intval(GetHealth($playerID));
  if ($life > 13) return null;
  $suppressed = intval(GetClassState($playerID, $CS_Num6PowPutIntoBanish)) > 0;
  if ($life >= 8 && ($suppressed || BotLeviaDrain($playerID) < $life)) return null;
  $permanents = &GetPermanents($playerID);
  $pieces = PermanentPieces();
  for ($i = 0, $count = count($permanents); $i < $count; $i += $pieces) {
    if ($permanents[$i] != "levia_redeemed" || !IsPlayable("levia_redeemed", $turn[0], "PLAY", $i)) continue;
    if (BotEffectiveCost("levia_redeemed", "PLAY", $i, AbilityCost("levia_redeemed")) > BotAvailablePitch($playerID)) continue;
    return ["levia_redeemed", "Permanent", $i, 1];
  }
  return null;
}

function BotIsLeviaFamily($playerID)
{
  return BotIsLevia($playerID) || BotHeroID($playerID) == "blasmophet_levia_consumed";
}

function BotIsLevia($playerID)
{
  $heroID = BotHeroID($playerID);
  return $heroID == "levia_shadowborn_abomination" || $heroID == "levia";
}

function BotWindupSetupValue($cardID, $playerID)
{
  if (str_starts_with($cardID, "agile_windup_")) return SearchAurasForCard("agility", $playerID) === "" ? BOT_AGILITY_SETUP_VALUE : 0.0;
  if (str_starts_with($cardID, "mighty_windup_")) return BOT_MIGHT_SETUP_VALUE;
  return 0.0;
}

function BotHeroBanishTwin($item, $playerID)
{
  if ($item["inHand"] && ($setup = BotWindupSetupValue($item["id"], $playerID)) > 0) {
    return [
      "kind" => "banish", "power" => 0, "defense" => 0, "pitch" => 0, "cost" => BotEffectiveCost($item["id"], "HAND", -1, 0), "goAgain" => false,
      "pump" => 0.0, "pumpNext" => false, "pumpTarget" => "any", "pumpGoAgain" => false, "ambush" => false, "arsenalPlay" => false,
      "alternative" => 0.0, "onHit" => 0.0, "bonus" => $setup, "setFlags" => 0, "needFlags" => 0, "goAgainFlags" => 0,
      "id" => $item["id"], "zone" => "BANISH", "inHand" => false, "held" => 0.0, "raw" => 0.0, "arsenal" => 0.0, "isAA" => false, "subtype" => "",
    ];
  }
  if (!$item["inHand"] || !isset(BOT_LEVIA_HAND_BANISHERS[$item["id"]]) || !BotIsLevia($playerID)) return null;
  $certainty = BotLeviaDrain($playerID) * (1 - BOT_BANISH_SIX_CHANCE);
  return [
    "kind" => "banish", "power" => 0, "defense" => 0, "pitch" => 0, "cost" => BotEffectiveCost($item["id"], "HAND", -1, 0) - BOT_LEVIA_HAND_BANISHERS[$item["id"]], "goAgain" => false,
    "pump" => 0.0, "pumpNext" => false, "pumpTarget" => "any", "pumpGoAgain" => false, "ambush" => false, "arsenalPlay" => false,
    "alternative" => 0.0, "onHit" => 0.0, "bonus" => $certainty, "setFlags" => BOT_FLAG_BLOOD_DEBT_SUPPRESSED, "needFlags" => 0, "goAgainFlags" => 0,
    "id" => $item["id"], "zone" => "BANISH", "inHand" => false, "held" => 0.0, "raw" => 0.0, "arsenal" => 0.0, "isAA" => false, "subtype" => "",
  ];
}

function BotHeroGateCards($playerID)
{
  if (!BotIsLeviaFamily($playerID)) return [];
  $gates = SearchCount(SearchAurasForCard("gate_to_iarathael", $playerID));
  if ($gates == 0) return [];
  $candidates = [];
  $banish = &GetBanish($playerID);
  foreach (explode(",", SearchBanish($playerID, "", "", -1, -1, "", "", true)) as $index) {
    if ($index === "") continue;
    $cardID = $banish[intval($index)] ?? "";
    $type = CardType($cardID);
    if ($type != "AA" && $type != "A") continue;
    $candidates[$cardID] = ["id" => $cardID, "zone" => "GATE", "index" => intval($index), "power" => intval(PowerValue($cardID, $playerID, "BANISH", -1, false, false))];
  }
  usort($candidates, fn($left, $right) => $right["power"] <=> $left["power"]);
  return array_slice(array_values($candidates), 0, min($gates, 2));
}

function BotHeroAllyAttackers($playerID, $currentTurn)
{
  if (!BotIsLeviaFamily($playerID)) return [];
  $attackers = [];
  $allies = &GetAllies($playerID);
  $pieces = AllyPieces();
  for ($i = 0, $count = count($allies); $i < $count; $i += $pieces) {
    if ($allies[$i] != "blasmophet_the_soul_harvester" || ($currentTurn && intval($allies[$i + 1] ?? 0) != 2)) continue;
    $attackers[] = [
      "id" => $allies[$i], "power" => 6, "cost" => 0, "goAgain" => false, "subtype" => "demon", "onHit" => 0.0,
      "setFlags" => 0, "needFlags" => 0, "goAgainFlags" => 0,
    ];
  }
  return $attackers;
}

function BotHeroHeldBonus($cardID, $playerID)
{
  if ($cardID != "doomsday_blue" || !BotIsLeviaFamily($playerID)) return 0.0;
  $allies = &GetAllies($playerID);
  return in_array("blasmophet_the_soul_harvester", $allies, true) ? 0.0 : BOT_SOUL_HARVESTER_VALUE;
}

function BotHeroCardDead($cardID, $playerID)
{
  return $cardID == "celestial_cataclysm_yellow" && count(GetSoul($playerID)) < 3;
}

function BotHeroCardPriority($cardID, $playerID)
{
  if ($cardID == "doomsday_blue" && BotIsLeviaFamily($playerID)) return 60.0;
  return null;
}

function BotGateIntent($cardID = null)
{
  static $intent = null;
  if ($cardID !== null) $intent = $cardID === "" ? null : $cardID;
  return $intent;
}

function BotBanishIntent($cardID, $intent = null)
{
  static $intents = [];
  if ($intent !== null) $intents[$cardID] = $intent;
  return $intents[$cardID] ?? null;
}

function BotPendingAbilityTypeCard()
{
  global $decisionQueue;
  return ($decisionQueue[0] ?? "") == "SETABILITYTYPE" ? strval($decisionQueue[2] ?? "") : "";
}

function BotHeroEndTurnNode($playerID)
{
  global $turn;
  if (!BotLeviaSuppressionNeeded($playerID)) return null;
  $hand = &GetHand($playerID);
  $resources = &GetResources($playerID);
  foreach (BOT_LEVIA_HAND_BANISHERS as $cardID => $gain) {
    foreach ($hand as $index => $handCard) {
      if ($handCard != $cardID || !IsPlayable($cardID, $turn[0], "HAND", $index)) continue;
      $pitch = intval($resources[0] ?? 0);
      foreach ($hand as $other => $otherCard) if ($other != $index) $pitch += intval(PitchValue($otherCard));
      if (BotEffectiveCost($cardID, "HAND", $index, 0) + max(0, -$gain) > $pitch) continue;
      BotBanishIntent($cardID, true);
      return [$cardID, "Hand", $index, 1];
    }
  }
  return null;
}
