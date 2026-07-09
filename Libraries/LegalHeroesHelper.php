<?php

require_once __DIR__ . '/CoreLibraries.php';

if (!function_exists("SubtypeContains")) {
  function SubtypeContains($cardID, $subtype, $player = "")
  {
    return DelimStringContains(CardSubtype($cardID), $subtype);
  }
}

if (!function_exists("TypeContains")) {
  function TypeContains($cardID, $type, $player = "")
  {
    return DelimStringContains(CardType($cardID), $type);
  }
}

// Source of truth for the hero universe. Mirrors HEROES_OF_RATHE in
// Talishar-FE/src/routes/index/components/filter/constants.ts. When a new
// hero ships, add it here AND in the FE constants file.
//
// heroId is the slug (lowercase + underscores) used by Bazaar/Fabrary in
// matchup payloads, and by Talishar's own banned-card list in JoinGame.php.
$ALL_HEROES_OF_RATHE = [
  ['heroId' => 'rhinar_reckless_rampage', 'name' => 'Rhinar Reckless Rampage', 'young' => false],
  ['heroId' => 'rhinar', 'name' => 'Rhinar', 'young' => true],
  ['heroId' => 'bravo_showstopper', 'name' => 'Bravo Showstopper', 'young' => false],
  ['heroId' => 'bravo', 'name' => 'Bravo', 'young' => true],
  ['heroId' => 'katsu_the_wanderer', 'name' => 'Katsu the Wanderer', 'young' => false],
  ['heroId' => 'katsu', 'name' => 'Katsu', 'young' => true],
  ['heroId' => 'dorinthea_ironsong', 'name' => 'Dorinthea Ironsong', 'young' => false],
  ['heroId' => 'dorinthea', 'name' => 'Dorinthea', 'young' => true],
  ['heroId' => 'dash_inventor_extraordinaire', 'name' => 'Dash Inventor Extraordinaire', 'young' => false],
  ['heroId' => 'dash', 'name' => 'Dash', 'young' => true],
  ['heroId' => 'azalea_ace_in_the_hole', 'name' => 'Azalea Ace in the Hole', 'young' => false],
  ['heroId' => 'azalea', 'name' => 'Azalea', 'young' => true],
  ['heroId' => 'viserai_rune_blood', 'name' => 'Viserai Rune Blood', 'young' => false],
  ['heroId' => 'viserai', 'name' => 'Viserai', 'young' => true],
  ['heroId' => 'kano_dracai_of_aether', 'name' => 'Kano Dracai of Aether', 'young' => false],
  ['heroId' => 'kano', 'name' => 'Kano', 'young' => true],
  ['heroId' => 'kayo_berserker_runt', 'name' => 'Kayo Berserker Runt', 'young' => true],
  ['heroId' => 'ira_crimson_haze', 'name' => 'Ira Crimson Haze', 'young' => true],
  ['heroId' => 'benji_the_piercing_wind', 'name' => 'Benji the Piercing Wind', 'young' => true],
  ['heroId' => 'kassai_cintari_sellsword', 'name' => 'Kassai Cintari Sellsword', 'young' => true],
  ['heroId' => 'shiyana_diamond_gemini', 'name' => 'Shiyana Diamond Gemini', 'young' => true],
  ['heroId' => 'data_doll_mkii', 'name' => 'Data Doll MKII', 'young' => true],
  ['heroId' => 'kavdaen_trader_of_skins', 'name' => 'Kavdaen Trader of Skins', 'young' => true],
  ['heroId' => 'prism_sculptor_of_arc_light', 'name' => 'Prism Sculptor of Arc Light', 'young' => false],
  ['heroId' => 'prism', 'name' => 'Prism', 'young' => true],
  ['heroId' => 'ser_boltyn_breaker_of_dawn', 'name' => 'Ser Boltyn Breaker of Dawn', 'young' => false],
  ['heroId' => 'boltyn', 'name' => 'Boltyn', 'young' => true],
  ['heroId' => 'levia_shadowborn_abomination', 'name' => 'Levia Shadowborn Abomination', 'young' => false],
  ['heroId' => 'levia', 'name' => 'Levia', 'young' => true],
  ['heroId' => 'chane_bound_by_shadow', 'name' => 'Chane Bound by Shadow', 'young' => false],
  ['heroId' => 'chane', 'name' => 'Chane', 'young' => true],
  ['heroId' => 'oldhim_grandfather_of_eternity', 'name' => 'Oldhim Grandfather of Eternity', 'young' => false],
  ['heroId' => 'oldhim', 'name' => 'Oldhim', 'young' => true],
  ['heroId' => 'lexi_livewire', 'name' => 'Lexi Livewire', 'young' => false],
  ['heroId' => 'lexi', 'name' => 'Lexi', 'young' => true],
  ['heroId' => 'briar_warden_of_thorns', 'name' => 'Briar Warden of Thorns', 'young' => false],
  ['heroId' => 'briar', 'name' => 'Briar', 'young' => true],
  ['heroId' => 'dromai_ash_artist', 'name' => 'Dromai Ash Artist', 'young' => false],
  ['heroId' => 'dromai', 'name' => 'Dromai', 'young' => true],
  ['heroId' => 'fai_rising_rebellion', 'name' => 'Fai Rising Rebellion', 'young' => false],
  ['heroId' => 'fai', 'name' => 'Fai', 'young' => true],
  ['heroId' => 'uzuri_switchblade', 'name' => 'Uzuri Switchblade', 'young' => false],
  ['heroId' => 'uzuri', 'name' => 'Uzuri', 'young' => true],
  ['heroId' => 'arakni_solitary_confinement', 'name' => 'Arakni Solitary Confinement', 'young' => true],
  ['heroId' => 'riptide_lurker_of_the_deep', 'name' => 'Riptide Lurker of the Deep', 'young' => false],
  ['heroId' => 'riptide', 'name' => 'Riptide', 'young' => true],
  ['heroId' => 'iyslander_stormbind', 'name' => 'Iyslander Stormbind', 'young' => false],
  ['heroId' => 'iyslander', 'name' => 'Iyslander', 'young' => true],
  ['heroId' => 'arakni_huntsman', 'name' => 'Arakni Huntsman', 'young' => false],
  ['heroId' => 'arakni', 'name' => 'Arakni', 'young' => true],
  ['heroId' => 'bravo_star_of_the_show', 'name' => 'Bravo Star of the Show', 'young' => false],
  ['heroId' => 'valda_brightaxe', 'name' => 'Valda Brightaxe', 'young' => true],
  ['heroId' => 'genis_wotchuneed', 'name' => 'Genis Wotchuneed', 'young' => true],
  ['heroId' => 'dorinthea_quicksilver_prodigy', 'name' => 'Dorinthea Quicksilver Prodigy', 'young' => true],
  ['heroId' => 'emperor_dracai_of_aesir', 'name' => 'Emperor Dracai of Aesir', 'young' => true],
  ['heroId' => 'yoji_royal_protector', 'name' => 'Yoji Royal Protector', 'young' => true],
  ['heroId' => 'prism_awakener_of_sol', 'name' => 'Prism Awakener of Sol', 'young' => false],
  ['heroId' => 'prism_advent_of_thrones', 'name' => 'Prism Advent of Thrones', 'young' => true],
  ['heroId' => 'vynnset_iron_maiden', 'name' => 'Vynnset Iron Maiden', 'young' => false],
  ['heroId' => 'vynnset', 'name' => 'Vynnset', 'young' => true],
  ['heroId' => 'professor_teklovossen', 'name' => 'Professor Teklovossen', 'young' => true],
  ['heroId' => 'brevant_civic_protector', 'name' => 'Brevant Civic Protector', 'young' => true],
  ['heroId' => 'melody_sing-along', 'name' => 'Melody Sing-along', 'young' => true],
  ['heroId' => 'dash_i/o', 'name' => 'Dash I/O', 'young' => false],
  ['heroId' => 'dash_database', 'name' => 'Dash Database', 'young' => true],
  ['heroId' => 'maxx_the_hype_nitro', 'name' => 'Maxx The Hype Nitro', 'young' => false],
  ['heroId' => 'maxx_nitro', 'name' => 'Maxx Nitro', 'young' => true],
  ['heroId' => 'teklovossen_esteemed_magnate', 'name' => 'Teklovossen Esteemed Magnate', 'young' => false],
  ['heroId' => 'teklovossen', 'name' => 'Teklovossen', 'young' => true],
  ['heroId' => 'kayo_armed_and_dangerous', 'name' => 'Kayo Armed and Dangerous', 'young' => false],
  ['heroId' => 'kayo', 'name' => 'Kayo', 'young' => true],
  ['heroId' => 'betsy_skin_in_the_game', 'name' => 'Betsy Skin in the Game', 'young' => false],
  ['heroId' => 'betsy', 'name' => 'Betsy', 'young' => true],
  ['heroId' => 'victor_goldmane_high_and_mighty', 'name' => 'Victor Goldmane High and Mighty', 'young' => false],
  ['heroId' => 'victor_goldmane', 'name' => 'Victor Goldmane', 'young' => true],
  ['heroId' => 'kassai_of_the_golden_sand', 'name' => 'Kassai of the Golden Sand', 'young' => false],
  ['heroId' => 'kassai', 'name' => 'Kassai', 'young' => true],
  ['heroId' => 'olympia_prized_fighter', 'name' => 'Olympia Prized Fighter', 'young' => false],
  ['heroId' => 'olympia', 'name' => 'Olympia', 'young' => true],
  ['heroId' => 'nuu_alluring_desire', 'name' => 'Nuu Alluring Desire', 'young' => false],
  ['heroId' => 'nuu', 'name' => 'Nuu', 'young' => true],
  ['heroId' => 'enigma_ledger_of_ancestry', 'name' => 'Enigma Ledger of Ancestry', 'young' => false],
  ['heroId' => 'enigma', 'name' => 'Enigma', 'young' => true],
  ['heroId' => 'zen_tamer_of_purpose', 'name' => 'Zen Tamer of Purpose', 'young' => false],
  ['heroId' => 'zen', 'name' => 'Zen', 'young' => true],
  ['heroId' => 'terra', 'name' => 'Terra', 'young' => true],
  ['heroId' => 'blaze_firemind', 'name' => 'Blaze Firemind', 'young' => true],
  ['heroId' => 'florian_rotwood_harbinger', 'name' => 'Florian Rotwood Harbinger', 'young' => false],
  ['heroId' => 'florian', 'name' => 'Florian', 'young' => true],
  ['heroId' => 'aurora_shooting_star', 'name' => 'Aurora Shooting Star', 'young' => false],
  ['heroId' => 'aurora', 'name' => 'Aurora', 'young' => true],
  ['heroId' => 'verdance_thorn_of_the_rose', 'name' => 'Verdance Thorn of the Rose', 'young' => false],
  ['heroId' => 'verdance', 'name' => 'Verdance', 'young' => true],
  ['heroId' => 'oscilio_constella_intelligence', 'name' => 'Oscilio Constella Intelligence', 'young' => false],
  ['heroId' => 'oscilio', 'name' => 'Oscilio', 'young' => true],
  ['heroId' => 'jarl_vetreidi', 'name' => 'Jarl Vetreidi', 'young' => false],
  ['heroId' => 'arakni_marionette', 'name' => 'Arakni Marionette', 'young' => false],
  ['heroId' => 'arakni_web_of_deceit', 'name' => 'Arakni Web of Deceit', 'young' => true],
  ['heroId' => 'cindra_dracai_of_retribution', 'name' => 'Cindra Dracai of Retribution', 'young' => false],
  ['heroId' => 'cindra', 'name' => 'Cindra', 'young' => true],
  ['heroId' => 'fang_dracai_of_blades', 'name' => 'Fang Dracai of Blades', 'young' => false],
  ['heroId' => 'fang', 'name' => 'Fang', 'young' => true],
  ['heroId' => 'arakni_5l!p3d_7hru_7h3_cr4x', 'name' => 'Arakni 5L!p3d 7hRu 7h3 cR4X', 'young' => false],
  ['heroId' => 'puffin_hightail', 'name' => 'Puffin Hightail', 'young' => false],
  ['heroId' => 'puffin', 'name' => 'Puffin', 'young' => true],
  ['heroId' => 'gravy_bones_shipwrecked_looter', 'name' => 'Gravy Bones Shipwrecked Looter', 'young' => false],
  ['heroId' => 'gravy_bones', 'name' => 'Gravy Bones', 'young' => true],
  ['heroId' => 'marlynn_treasure_hunter', 'name' => 'Marlynn Treasure Hunter', 'young' => false],
  ['heroId' => 'marlynn', 'name' => 'Marlynn', 'young' => true],
  ['heroId' => 'ira_scarlet_revenger', 'name' => 'Ira Scarlet Revenger', 'young' => false],
  ['heroId' => 'valda_seismic_impact', 'name' => 'Valda Seismic Impact', 'young' => false],
  ['heroId' => 'scurv_stowaway', 'name' => 'Scurv Stowaway', 'young' => true],
  ['heroId' => 'tuffnut_bumbling_hulkster', 'name' => 'Tuffnut Bumbling Hulkster', 'young' => false],
  ['heroId' => 'tuffnut', 'name' => 'Tuffnut', 'young' => true],
  ['heroId' => 'pleiades_superstar', 'name' => 'Pleiades Superstar', 'young' => false],
  ['heroId' => 'pleiades', 'name' => 'Pleiades', 'young' => true],
  ['heroId' => 'kayo_underhanded_cheat', 'name' => 'Kayo Underhanded Cheat', 'young' => false],
  ['heroId' => 'kayo_strong-arm', 'name' => 'Kayo Strong-Arm', 'young' => true],
  ['heroId' => 'lyath_goldmane_vile_savant', 'name' => 'Lyath Goldmane Vile Savant', 'young' => false],
  ['heroId' => 'lyath_goldmane', 'name' => 'Lyath Goldmane', 'young' => true],
  ['heroId' => 'bravo_flattering_showman', 'name' => 'Bravo Flattering Showman', 'young' => true],
  ['heroId' => 'enigma_new_moon', 'name' => 'Enigma New Moon', 'young' => true],
  ['heroId' => 'hala_bladesaint_of_the_vow', 'name' => 'Hala Bladesaint of the Vow', 'young' => false],
  ['heroId' => 'hala', 'name' => 'Hala', 'young' => true],
];

// Hero-specific ban check.
//
// Mirrors the hero-relevant subset of isBannedInFormat() in JoinGame.php
// (line 829). The full function lives in JoinGame.php which can't be
// include()d safely (side effects), so we duplicate the bits that matter
// for heroes:
//   - Living Legends (Constants.php $livingLegends) are banned in CC
//   - Format-specific hero bans live in $heroBansByFormat below
//
// TODO: refactor isBannedInFormat() out of JoinGame.php into a Libraries/
// file so this can call it directly and we can drop the duplication.
function isHeroBannedInFormat($heroId, $format) {
  global $livingLegends;

  // Format aliases (matches JoinGame.php convention)
  if ($format == 'compblitz') $format = 'blitz';
  if ($format == 'compcc') $format = 'cc';
  if ($format == 'compllcc' || $format == 'futurell') $format = 'llcc';
  if ($format == 'compsage' || $format == 'futuresage') $format = 'sage';

  // Living Legends are banned in Classic Constructed
  if (($format == 'cc' || $format == 'futurecc') && is_array($livingLegends ?? null) && in_array($heroId, $livingLegends, true)) {
    return true;
  }

  // Other format-specific hero bans (none currently — placeholder for future)
  $heroBansByFormat = [
    // 'cc'    => [],
    // 'blitz' => [],
  ];
  return isset($heroBansByFormat[$format]) && in_array($heroId, $heroBansByFormat[$format], true);
}

// Returns the format-legal heroes (young/adult split applied, banned heroes
// excluded) with class info attached. Consumed by GetLobbyInfo.php and
// GetLobbyRefresh.php; surfaces as `legalHeroes: LegalHero[]` on the lobby
// response payload.
function GetLegalHeroes($format) {
  global $ALL_HEROES_OF_RATHE;

  // Format-tier filter: young heroes for Blitz/Sage/Commoner/Clash formats,
  // adult heroes for everything else (CC, LL CC, Draft, Sealed, Gage, etc.)
  $youngFormats = [
    'blitz', 'compblitz', 'openblitz',
    'sage', 'compsage', 'opensage', 'futuresage',
    'commoner',
    'clash',
  ];
  $useYoung = in_array($format, $youngFormats, true);

  $legal = [];
  foreach ($ALL_HEROES_OF_RATHE as $h) {
    if ($h['young'] !== $useYoung) continue;
    if (isHeroBannedInFormat($h['heroId'], $format)) continue;

    // Class string from CardClass() in CardDictionary.php: "RUNEBLADE",
    // "WIZARD", "ASSASSIN", etc. Empty string if unknown.
    $class = function_exists('CardClass') ? CardClass($h['heroId']) : '';

    $legal[] = [
      'heroId' => $h['heroId'],
      'name'   => $h['name'],
      'class'  => $class,
      'young'  => $h['young'],
    ];
  }
  return $legal;
}
