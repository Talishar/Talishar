<?php

include_once __DIR__ . '/../Assets/AllAltArtVariations.php';

$SET_AlwaysHoldPriority = 0;
$SET_TryUI2 = 1;
$SET_DarkMode = 2;
$SET_ManualMode = 3;

$SET_SkipARs = 4;
$SET_SkipDRs = 5;
$SET_PassDRStep = 6;

$SET_AutotargetArcane = 7; //Auto-target opponent with arcane damage
$SET_ColorblindMode = 8; //Colorblind mode settings
$SET_ShortcutAttackThreshold = 9; //Threshold to shortcut attacks
$SET_EnableDynamicScaling = 10; //Threshold to shortcut attacks
$SET_Mute = 11; //Mute sounds

$SET_Cardback = 12; //Card backs
$SET_IsPatron = 13; //Is Patron

$SET_MuteChat = 14; //Did this player mute chat

$SET_DisableStats = 15; //Did this player disable stats
$SET_CasterMode = 16; //Did this player enable caster mode

//Menu settings
$SET_Language = 17; //What language is this player using?
$SET_Format = 18; //What format did this player create a game for last?
$SET_Deprecated = 19; //Deprecated
$SET_FavoriteDeckIndex = 20; //What deck did this player play a game with last
$SET_GameVisibility = 21; //The visibility of the last game you created

$SET_StreamerMode = 23; //Did this player enable caster mode
$SET_Playmat = 24; //Did this player enable caster mode
$SET_AlwaysAllowUndo = 25;//Do you want to always allow undo
$SET_DisableAltArts = 26;//Do you want to disable alt arts
$SET_ManualTunic = 27;//Do you want to manually tick up tunic each turn
$SET_DisableFabInsights = 28; //Did the player disable global stat tracking
$SET_DisableHeroIntro = 29; //Did the player disable hero intro animation
$SET_MirroredBoardLayout = 30; //Did the player enable mirrored board layout (opponent)
$SET_MirroredPlayerBoardLayout = 31; //Did the player enable mirrored board layout (player)
$SET_AlwaysShowCounters = 32; //Always show counters on zones
$SET_HideHandFromFriends = 33; //Hide your hand content from friends
$SET_GemsOffByDefault = 34; //Should gems start switched off instead of using each card's default
$SET_HideGamesFromFriends = 35; //Hide your games from your friends in the open game and spectate lists

function HoldPrioritySetting($player)
{
  global $SET_AlwaysHoldPriority;
  $settings = GetSettings($player);
  return $settings[$SET_AlwaysHoldPriority] ?? 0;
}

function GemsOffByDefaultSetting($player)
{
  global $SET_GemsOffByDefault;
  if ($player != 1 && $player != 2) return 0;
  $settings = GetSettings($player);
  if ($settings == null) return 0;
  return ($settings[$SET_GemsOffByDefault] ?? 0) == 1 ? 1 : 0;
}

function ApplyGemsOffDefault($state, $player)
{
  return ($state == 1 && GemsOffByDefaultSetting($player) == 1) ? 0 : $state;
}

function ManualTunicSetting($player)
{
  global $SET_ManualTunic;
  $settings = GetSettings($player);
  return $settings[$SET_ManualTunic] ?? 0;
}

function UseNewUI($player)
{
  global $SET_TryUI2;
  $settings = GetSettings($player);
  return $settings[$SET_TryUI2] == 1;
}

function IsDarkMode($player)
{
  global $SET_DarkMode;
  $settings = GetSettings($player);
  return $settings[$SET_DarkMode] ?? 0 == 1 || $settings[$SET_DarkMode] ?? 0 == 3;
}

function IsPlainMode($player)
{
  global $SET_DarkMode;
  $settings = GetSettings($player);
  return $settings[$SET_DarkMode] ?? 0 == 2;
}

function IsDarkPlainMode($player)
{
  global $SET_DarkMode;
  $settings = GetSettings($player);
  return $settings[$SET_DarkMode] ?? 0 == 3;
}

function IsPatron($player)
{
  global $SET_IsPatron;
  $settings = GetSettings($player);
  if(count($settings) < $SET_IsPatron) return false;
  return $settings[$SET_IsPatron] ?? "0" == "1";
}

function ResetFavoriteDeckCosmeticOverrideCache()
{
  global $favoriteDeckCosmeticOverrideCache, $deckCosmeticDataCache;
  $favoriteDeckCosmeticOverrideCache = [];
  $deckCosmeticDataCache = [];
}

function NormalizeDeckLinkForMatch($deckLink)
{
  $link = trim(strval($deckLink));
  if ($link === '') return '';
  $link = preg_replace('/[?#].*$/', '', $link);                      // "?tab=edit", "#cards"
  $link = preg_replace('#^[a-zA-Z][a-zA-Z0-9+.\-]*://#', '', $link); // http:// vs https://
  $link = preg_replace('#^www\.#i', '', $link);
  $link = rtrim($link, '/');
  $hostEnd = strpos($link, '/');
  if ($hostEnd === false) return strtolower($link);
  return strtolower(substr($link, 0, $hostEnd)) . substr($link, $hostEnd);
}

function FindFavoriteDeckRow($conn, $userId, $deckLink, $columns = [])
{
  if (!$conn || empty($userId) || $userId === '-' || trim(strval($deckLink)) === '') return null;

  $select = ['decklink'];
  foreach ($columns as $column) {
    if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $column)) $select[] = $column;
  }
  $select = implode(', ', array_unique($select));

  $sql = "SELECT $select FROM favoritedeck WHERE decklink = ? AND usersId = ? LIMIT 1";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $deckLink, $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
    if ($row) return $row;
  }

  $wanted = NormalizeDeckLinkForMatch($deckLink);
  if ($wanted === '') return null;

  $match = null;
  $sql = "SELECT $select FROM favoritedeck WHERE usersId = ?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($res && ($row = mysqli_fetch_assoc($res))) {
      if (NormalizeDeckLinkForMatch($row['decklink'] ?? '') === $wanted) {
        $match = $row;
        break;
      }
    }
    if ($res) mysqli_free_result($res);
    mysqli_stmt_close($stmt);
  }
  return $match;
}

function DeckCosmeticCacheKey($userId, $deckLink)
{
  return 'deck_cosmetics_' . hash('sha256', intval($userId) . '|' . NormalizeDeckLinkForMatch($deckLink));
}

function IsDeckCosmeticCacheAvailable()
{
  return extension_loaded('apcu') && ini_get('apc.enabled') && function_exists('apcu_fetch');
}

function InvalidateDeckCosmeticCache($userId, $deckLink)
{
  global $favoriteDeckCosmeticOverrideCache, $deckCosmeticDataCache;
  $favoriteDeckCosmeticOverrideCache = [];
  $deckCosmeticDataCache = [];
  if (IsDeckCosmeticCacheAvailable()) {
    @apcu_delete(DeckCosmeticCacheKey($userId, $deckLink));
  }
}

function GetDeckCosmeticData($userId, $deckLink)
{
  global $deckCosmeticDataCache;
  $requestKey = intval($userId) . '|' . NormalizeDeckLinkForMatch($deckLink);
  if (!isset($deckCosmeticDataCache) || !is_array($deckCosmeticDataCache)) {
    $deckCosmeticDataCache = [];
  }
  if (array_key_exists($requestKey, $deckCosmeticDataCache)) {
    return $deckCosmeticDataCache[$requestKey];
  }

  if (
    empty($userId) || $userId === '-' || empty($deckLink) ||
    !function_exists('GetDBConnection') || !defined('DBL_BUILD_GAME_STATE')
  ) {
    return $deckCosmeticDataCache[$requestKey] = null;
  }

  $sharedKey = DeckCosmeticCacheKey($userId, $deckLink);
  if (IsDeckCosmeticCacheAvailable()) {
    $cacheHit = false;
    $cached = @apcu_fetch($sharedKey, $cacheHit);
    if ($cacheHit && is_array($cached) && array_key_exists('value', $cached)) {
      return $deckCosmeticDataCache[$requestKey] = $cached['value'];
    }
  }

  $conn = GetDBConnection(DBL_BUILD_GAME_STATE);
  if (!$conn) return $deckCosmeticDataCache[$requestKey] = null;

  $row = FindFavoriteDeckRow(
    $conn,
    $userId,
    $deckLink,
    ['cardBack', 'playmat', 'altArtsCustomized']
  );
  $data = null;
  if ($row !== null) {
    $customized = intval($row['altArtsCustomized'] ?? 0) === 1;
    $storedLink = strval($row['decklink']);
    $map = [];
    if ($customized) {
      $sql = "SELECT cardId, altPath FROM deck_alt_arts WHERE decklink = ? AND usersId = ?";
      $stmt = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $storedLink, $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($res && ($altArtRow = mysqli_fetch_assoc($res))) {
          $map[$altArtRow['cardId']] = $altArtRow['altPath'];
        }
        if ($res) mysqli_free_result($res);
        mysqli_stmt_close($stmt);
      }
    }
    $data = [
      'cardBack' => strval($row['cardBack'] ?? '0'),
      'playmat' => strval($row['playmat'] ?? '0'),
      'altArtsCustomized' => $customized,
      'altArts' => $map,
    ];
  }
  mysqli_close($conn);

  if (IsDeckCosmeticCacheAvailable()) {
    // SaveDeckCosmetics invalidates this immediately; the TTL is a safety net
    // for changes made outside the application.
    @apcu_store($sharedKey, ['value' => $data], 300);
  }
  return $deckCosmeticDataCache[$requestKey] = $data;
}

function GetFavoriteDeckCosmeticOverride($player)
{
  global $p1id, $p2id, $p1DeckLink, $p2DeckLink, $favoriteDeckCosmeticOverrideCache;
  if (!isset($favoriteDeckCosmeticOverrideCache) || !is_array($favoriteDeckCosmeticOverrideCache)) {
    $favoriteDeckCosmeticOverrideCache = [];
  }
  if (array_key_exists($player, $favoriteDeckCosmeticOverrideCache)) {
    return $favoriteDeckCosmeticOverrideCache[$player];
  }

  $userId = ($player == 1) ? ($p1id ?? '') : ($p2id ?? '');
  $deckLink = ($player == 1) ? ($p1DeckLink ?? '') : ($p2DeckLink ?? '');

  $data = GetDeckCosmeticData($userId, $deckLink);
  $result = $data === null ? null : [
    'cardBack' => $data['cardBack'],
    'playmat' => $data['playmat']
  ];

  return $favoriteDeckCosmeticOverrideCache[$player] = $result;
}

function GetDeckAltArtOverride($userId, $deckLink)
{
  $data = GetDeckCosmeticData($userId, $deckLink);
  if ($data === null) return null;
  return [
    'customized' => $data['altArtsCustomized'],
    'map' => $data['altArts'],
  ];
}

function ApplyDeckAltArtOverride($poolAltArts, $userId, $deckLink)
{
  $override = GetDeckAltArtOverride($userId, $deckLink);
  if ($override === null || !$override['customized']) {
    if (!function_exists('IsOptInOnlyAltArt')) return $poolAltArts;
    return array_values(array_filter($poolAltArts, function ($altArt) {
      return !IsOptInOnlyAltArt($altArt->altPath ?? '');
    }));
  }

  $result = [];
  foreach ($override['map'] as $cardId => $altPath) {
    $altArt = new stdClass();
    $altArt->name = 'My Deck';
    $altArt->cardId = $cardId;
    $altArt->altPath = $altPath;
    $result[] = $altArt;
  }
  return $result;
}

function GetPlaymat($player)
{
  global $SET_Playmat;
  $override = GetFavoriteDeckCosmeticOverride($player);
  if ($override !== null && $override['playmat'] !== '0') {
    return $override['playmat'];
  }
  $settings = GetSettings($player);
  return $settings[$SET_Playmat] ?? 0;
}

function GetCardBack($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  static $cardBackMap = [
    1 => "CBBlack",
    2 => "CBCreamWhite",
    3 => "CBGold",
    4 => "CBWhite",
    5 => "CBRed",
    6 => "CBParchment",
    7 => "CBBlue",
    8 => "CBRuneblood",
    9 => "CBPushThePoint",
    10 => "CBGoAgainGaming",
    11 => "CBGAG_AzaleaCult",
    12 => "CBGAG_Azalea",
    13 => "CBGAG_AzaleaShot",
    14 => "CBGAG_Dorinthea",
    15 => "CBGAG_Dromai",
    16 => "CBGAG_Kassai",
    17 => "CBRedZoneRogue",
    18 => "CBRZR_10k",
    19 => "CBRZR_KadikosLibrary",
    20 => "CBRZR_Vehya",
    21 => "CBFabrary1",
    22 => "CBFabrary2",
    23 => "CBManSant",
    24 => "CBAttackActionPodcast",
    25 => "CBArsenalPass",
    26 => "CBTekloFoundry",
    27 => "CBPummelowanko",
    28 => "CBDragonShieldProTeamWB",
    29 => "CBFleshAndCommonBlood",
    30 => "CBSinOnStream",
    31 => "CBFreshAndBuds",
    32 => "CBSloopdoop",
    33 => "CBDMArmada",
    34 => "CBInstantSpeed",
    35 => "CBTheCardGuyz",
    36 => "CBHomeTownTCG",
    37 => "CBAscentGaming",
    38 => "CBFleshAndPod",
    39 => "CBKappolo",
    40 => "CBLibrariansOfSolana",
    41 => "CBTheMetrixMetagame",
    42 => "CBEternalOracles",
    43 => "CBTheTablePit",
    44 => "CBTCGTed",
    45 => "CBLuminaris",
    46 => "CBFaBLab",
    47 => "CBCardAdvantage",
    48 => "CBOnHit",
    49 => "CBSecondCycle",
    50 => "CBRavenousBabble",
    51 => "CBBlackWingStudio",
    52 => "CBManSantBlack",
    53 => "CBOnHitEffect",
    54 => "CBDaganWhite",
    55 => "CBSonicDoom",
    56 => "CBBrandao",
    57 => "CBFabrary3",
    58 => "CBFabrary4",
    59 => "CBFabrary5",
    60 => "CBFabrary6",
    61 => "CBFabrary7",
    62 => "CBFabrary8",
    63 => "CBOffTheRailsTCG",
    64 => "CBPummel",
    65 => "CBNxi",
    66 => "CBPvtVoid",
    67 => "CBEmperorsRome",
    68 => "CBWeMakeBest",
    69 => "CBWeMakeBest2",
    70 => "CBSunflowerSamurai",
    71 => "CBMnRCast",
    72 => "CBOnTheBauble",
    73 => "CBGorganianTome",
    74 => "CBFABChaos",
    75 => "CBColdFoilControl",
    76 => "CBDailyFab",
    77 => "CBRighteousGaming",
    78 => "CBRighteousGaming2",
    79 => "CBThePlagueHive",
    80 => "CBDropcast",
    81 => "CBSunflowerSamurai",
    82 => "CBTalisharTeam",
    83 => "CBTalisharTeam2",
    84 => "CBTideBreakers",
    85 => "CBCD1",
    86 => "CBCD2",
    87 => "CBCupofTCG",
    88 => "CBScowlingFleshBag",
    89 => "CBDazzyfizzle",
    90 => "CBDazzyfizzle1",
    91 => "CBDazzyfizzle2",
    92 => "CBDazzyfizzle3",
    93 => "CBDazzyfizzle4",
    94 => "CBDazzyfizzle5",
    95 => "CBDazzyfizzle6",
    96 => "CBThaiCardsShop",
    97 => "CBNikobru",
    98 => "CBDazzyfizzle7",
    99 => "CBDazzyfizzle8",
    100 => "CBSmilingFleshBag",
    101 => "CBDashciples",
    102 => "CBBlitzkriegMeph",
    103 => "CBHamMan215",
    104 => "CBNewHorizons",
    105 => "CBMetalFab",
    106 => "CBPotatoSquad",
    107 => "CBThreeFloating1",
    108 => "CBThreeFloating2",
    109 => "CBThreeFloating3",
    110 => "CBSteelfur",
    111 => "CBFleshAndBad",
    112 => "CBFabledBrazil",
    113 => "CBSilvarisGarden",
    114 => "CBDazzyfizzle9",
    115 => "CBDazzyfizzle10",
    116 => "CBDazzyfizzle11",
    117 => "CBDazzyfizzle12",
    118 => "CBAggroBlaze",
    119 => "CBFatAndFurious",
    120 => "CBRighteousGaming3",
    121 => "CBFreshAndBuds2",
    122 => "CBNull",
    123 => "CBPitchDevils",
    124 => "CBMickz",
    125 => "CBMickzValda",
    126 => "CBOllinTogether",
    127 => "CBSnapDragons",
    128 => "CBFabDads",
    129 => "CBFablazing",
    130 => "CBScowlingFleshBag2",
    131 => "CBSnow",
    132 => "CBPitchDevils2",
    133 => "RedLine",
    134 => "CBSkillIssue",
    135 => "CBWingedHussars",
    136 => "CBFabInsight",
    137 => "CBNxi2",
    138 => "CBOddwillows",
    139 => "CBShine",
    140 => "CB_IndyFab",
  ];
  $override = GetFavoriteDeckCosmeticOverride($player);
  $cardBackId = ($override !== null && $override['cardBack'] !== '0')
    ? $override['cardBack']
    : ($settings[$SET_Cardback] ?? 0);
  return $cardBackMap[$cardBackId] ?? "CardBack";
}

function IsManualMode($player)
{
  global $SET_ManualMode;
  $settings = GetSettings($player);
  return $settings[$SET_ManualMode] ?? 0;
}

function ShouldSkipARs($player)
{
  global $SET_SkipARs;
  $settings = GetSettings($player);
  return $settings[$SET_SkipARs] ?? 0;
}

function ShouldSkipDRs($player)
{
  global $SET_SkipDRs, $SET_PassDRStep;
  $settings = GetSettings($player);
  $skip = ($settings[$SET_SkipDRs] ?? false) || ($settings[$SET_PassDRStep] ?? false);
  ChangeSetting($player, $SET_PassDRStep, 0);
  return $skip;
}

function ShouldAutotargetOpponent($player)
{
  //this is going to break in replays
  global $SET_AutotargetArcane;
  $settings = GetSettings($player);
  return ($settings[$SET_AutotargetArcane] ?? "0") == "1";
}

function IsColorblindMode($player)
{
  global $SET_ColorblindMode;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return ($settings[$SET_ColorblindMode] ?? "0") == "1";
}

function ShortcutAttackThreshold($player)
{
  global $SET_ShortcutAttackThreshold;
  $settings = GetSettings($player);
  if (count($settings) < $SET_ShortcutAttackThreshold) return "0";
  return $settings[$SET_ShortcutAttackThreshold];
}

function IsDynamicScalingEnabled($player)
{
  if (!function_exists("GetSettings")) return false;
  global $SET_EnableDynamicScaling;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return ($settings[$SET_EnableDynamicScaling] ?? "0") == "1";
}

function IsMuted($player)
{
  global $SET_Mute;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return ($settings[$SET_Mute] ?? "0") == "1";
}

function IsChatMuted()
{
  global $SET_MuteChat;
  $p1Settings = GetSettings(1);
  $p2Settings = GetSettings(2);
  return ($p1Settings[$SET_MuteChat] ?? "0") == "1" || ($p2Settings[$SET_MuteChat] ?? "0") == "1";
}

function AreStatsDisabled($player)
{
  global $SET_DisableStats;
  if (IsReplay() || IsPlayerAI(2) || IsPlayerAI(1)) return true;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return ($settings[$SET_DisableStats] ?? "0") == "1";
}

function AreGlobalStatsDisabled($player)
{
  global $SET_DisableFabInsights;
  if (IsReplay() || IsPlayerAI(2) || IsPlayerAI(1)) return true;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return ($settings[$SET_DisableFabInsights] ?? "0") == "1";
}

function IsHeroIntroDisabled($player)
{
  global $SET_DisableHeroIntro;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return ($settings[$SET_DisableHeroIntro] ?? "0") == "1";
}

function IsCasterMode()
{
  global $SET_CasterMode;
  $settings1 = GetSettings(1);
  $settings2 = GetSettings(2);
  if ($settings1 == null || $settings2 == null) return false;
  return ($settings1[$SET_CasterMode] ?? "0") == "1" && ($settings2[$SET_CasterMode] ?? "0") == "1";
}

function IsHideHandFromFriends($player)
{
  global $SET_HideHandFromFriends;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return isset($settings[$SET_HideHandFromFriends]) && $settings[$SET_HideHandFromFriends] == "1";
}

function IsHideGamesFromFriends($player)
{
  global $SET_HideGamesFromFriends;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return isset($settings[$SET_HideGamesFromFriends]) && $settings[$SET_HideGamesFromFriends] == "1";
}

function IsStreamerMode($player)
{
  global $SET_StreamerMode;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_StreamerMode] == "1";
}

function AlwaysAllowUndo($player)
{
  global $SET_AlwaysAllowUndo;
  $settings = GetSettings($player);
  if($settings == null) return false;
  return $settings[$SET_AlwaysAllowUndo] == "1";
}

$UNDO_REASON_LABELS = [
  1 => "Misclick",
  2 => "Passed too fast",
  3 => "Forgot a trigger",
  4 => "Wrong target"
];

function NormalizeUndoReason($reason)
{
  global $UNDO_REASON_LABELS;
  $code = intval($reason);
  return isset($UNDO_REASON_LABELS[$code]) ? $code : 0;
}

function AltArtsDisabled($player)
{
  global $SET_DisableAltArts;
  if ($player > 2) return true; //spectators
  $settings = GetSettings($player);
  if($settings == null || count($settings) <= $SET_DisableAltArts) return false;
  return $settings[$SET_DisableAltArts] == "1";
}

function ParseSettingsStringValueToIdInt(string $value)
{
  static $settingsToId = [
    "HoldPrioritySetting" => 0,
    "TryReactUI" => 1,
    "DarkMode" => 2,
    "ManualMode" => 3,
    "SkipARWindow" => 4,
    "SkipDRWindow" => 5,
    "AutoTargetOpponent" => 7,
    "ColorblindMode" => 8,
    "ShortcutAttackThreshold" => 9,
    "MuteSound" => 11,
    "CardBack" => 12,
    "IsPatron" => 13,
    "MuteChat" => 14,
    "DisableStats" => 15,
    "IsCasterMode" => 16,
    "IsStreamerMode" => 23,
    "Playmat" => 24,
    "AlwaysAllowUndo" => 25,
    "DisableAltArts" => 26,
    "ManualTunic" => 27,
    "DisableFabInsights" => 28,
    "DisableHeroIntro" => 29,
    "MirroredBoardLayout" => 30,
    "MirroredPlayerBoardLayout" => 31,
    "AlwaysShowCounters" => 32,
    "HideHandFromFriends" => 33,
    "GemsOffByDefault" => 34,
    "HideGamesFromFriends" => 35,
  ];
  return $settingsToId[$value];
}

function ChangeSetting($player, $setting, $value, $playerId = "")
{
  global $SET_MuteChat, $SET_AlwaysHoldPriority, $SET_CasterMode, $layerPriority, $gameName;
  // Only update game state if not in profile context
  if($player != "" && $player != 0) {
    $settings = &GetSettings($player);
    if (($settings[$setting] ?? null) === $value) return; // Already at this value, skip write and any DB call
    if (is_numeric($setting)) {
      for ($i = 0; $i < $setting; ++$i) {
        if (!isset($settings[$i])) $settings[$i] = "0";
      }
      ksort($settings);
    }
    $settings[$setting] = $value;
    if($setting == $SET_MuteChat) {
      if($value == "1") {
        ClearLog(1);
        WriteLog("Chat disabled by player " . $player);
      } else {
        WriteLog("Chat enabled by player " . $player);
      }
    } else if($setting == $SET_AlwaysHoldPriority) {
      $layerPriority[$player - 1] = "1";
    } else if($setting == $SET_CasterMode) {
      if(IsCasterMode()) SetCachePiece($gameName, 9, "1");
    }
  }
  if($playerId != "" && SaveSettingInDatabase($setting)) {
    SaveSetting($playerId, $setting, $value);
  }
}

function SaveSettingInDatabase($setting)
{
  static $persistable = null;
  if ($persistable === null) {
    global $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats, $SET_Language;
    global $SET_Format, $SET_FavoriteDeckIndex, $SET_GameVisibility, $SET_AlwaysHoldPriority, $SET_ManualMode;
    global $SET_StreamerMode, $SET_AutotargetArcane, $SET_Playmat, $SET_AlwaysAllowUndo, $SET_DisableAltArts, $SET_AlwaysShowCounters;
    global $SET_ManualTunic, $SET_DisableFabInsights, $SET_DisableHeroIntro, $SET_MirroredBoardLayout, $SET_MirroredPlayerBoardLayout, $SET_HideHandFromFriends;
    global $SET_HideGamesFromFriends;
    global $SET_GemsOffByDefault;
    $persistable = array_fill_keys([
      $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats,
      $SET_Language, $SET_Format, $SET_FavoriteDeckIndex, $SET_GameVisibility, $SET_AlwaysHoldPriority,
      $SET_ManualMode, $SET_StreamerMode, $SET_AutotargetArcane, $SET_Playmat, $SET_AlwaysAllowUndo,
      $SET_DisableAltArts, $SET_ManualTunic, $SET_DisableFabInsights, $SET_DisableHeroIntro,
      $SET_MirroredBoardLayout, $SET_MirroredPlayerBoardLayout, $SET_AlwaysShowCounters, $SET_HideHandFromFriends,
      $SET_GemsOffByDefault, $SET_HideGamesFromFriends,
    ], true);
  }
  return isset($persistable[$setting]);
}

function FormatCode($format)
{
  static $formatMap = [
    "cc" => 0,
    "compcc" => 1,
    "blitz" => 2,
    "compblitz" => 3,     //Currently not used
    "futurecc" => 4,
    "commoner" => 5,
    "sealed" => 6,
    "draft" => 7,
    "llcc" => 8,
    "llblitz" => 9,       //Currently not used
    "openformatblitz" => 10, //Currently not used
    "clash" => -1,
    "futurell" => 11,     //Currently not used
    "openformatllblitz" => 12, //Currently not used
    "compllcc" => 13,
    "sage" => 14,
    "compsage" => 15,
    "futuresage" => 16,
    "open" => 17,
    "gage" => 18,
    "precon" => -2,
  ];
  return $formatMap[$format] ?? -1;
}

function FormatName($formatCode)
{
  static $nameMap = [
    0 => "cc",
    1 => "compcc",
    2 => "blitz",
    3 => "compblitz",     //Currently not used
    4 => "futurecc",
    5 => "commoner",
    6 => "sealed",
    7 => "draft",
    8 => "llcc",
    9 => "llblitz",       //Currently not used
    10 => "openformatblitz",
    -1 => "clash",
    11 => "futurell",
    12 => "openformatllblitz", //Currently not used
    13 => "compllcc",
    14 => "sage",
    15 => "compsage",
    16 => "futuresage",
    17 => "open",
    18 => "gage",
    -2 => "precon",
  ];
  return $nameMap[$formatCode] ?? "-";
}

//Campaign supporter rosters live in PatreonDictionary.php
