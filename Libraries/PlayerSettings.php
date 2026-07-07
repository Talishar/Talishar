<?php

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

function HoldPrioritySetting($player)
{
  global $SET_AlwaysHoldPriority;
  $settings = GetSettings($player);
  return $settings[$SET_AlwaysHoldPriority] ?? 0;
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

function GetPlaymat($player)
{
  global $SET_Playmat;
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
  ];
  return $cardBackMap[$settings[$SET_Cardback] ?? 0] ?? "CardBack";
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
    $persistable = array_fill_keys([
      $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats,
      $SET_Language, $SET_Format, $SET_FavoriteDeckIndex, $SET_GameVisibility, $SET_AlwaysHoldPriority,
      $SET_ManualMode, $SET_StreamerMode, $SET_AutotargetArcane, $SET_Playmat, $SET_AlwaysAllowUndo,
      $SET_DisableAltArts, $SET_ManualTunic, $SET_DisableFabInsights, $SET_DisableHeroIntro,
      $SET_MirroredBoardLayout, $SET_MirroredPlayerBoardLayout, $SET_AlwaysShowCounters, $SET_HideHandFromFriends,
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

function IsTeamCardAdvantage($userName)
{
  static $members = ["JacobK" => 1, "Pastry Boi" => 1, "Brotworst" => 1, "1nigoMontoya (Cody)" => 1, "Motley" => 1,
    "jimmyhl1329" => 1, "Stilltzkin" => 1, "krav" => 1, "infamousb" => 1, "FatFabJesus" => 1, "MisterPNP" => 1];
  return isset($members[$userName]);
}

function IsTeamSecondCycle($userName)
{
  static $members = ["The4thAWOL" => 1, "Beserk" => 1, "Dudebroski" => 1, "deathstalker182" => 1, "TryHardYeti" => 1, "Fledermausmann" => 1,
    "Loganninty7" => 1, "flamedog3" => 1, "Swankypants" => 1, "Blazing For Lethal?" => 1, "Jeztus" => 1, "gokkar" => 1,
    "Kernalxklink" => 1, "Kymo13" => 1];
  return isset($members[$userName]);
}

function IsTeamSonicDoom($userName)
{
  static $members = ["KanoSux" => 1, "BestBoy" => 1, "CRGrey" => 1, "jujubeans" => 1, "YodasUncle" => 1,
    "ravenklath" => 1, "Blazing For Lethal?" => 1, "DimGuy" => 1, "JoeyReads" => 1, "OompaLoompaTron" => 1, "Ocean" => 1,
    "radiotoast" => 1, "ThePitchStack" => 1, "KanosWaterBottle" => 1, "yamsandwic" => 1, "ThatOneKano" => 1, "YuutoSJ" => 1, "ZorbyX" => 1, "littlsnek" => 1,
    "AWizardofEarthsea" => 1];
  return isset($members[$userName]);
}

function IsTeamPummel($userName)
{
  static $members = ["MkDk" => 1, "Kutter" => 1, "Smeoz" => 1, "Fabio" => 1, "JustFonta" => 1, "M3X" => 1, "Tommaso" => 1, "PDMPLB" => 1];
  return isset($members[$userName]);
}

function IsTeamEmperorsRome($userName)
{
  static $members = ["Daniele90rm" => 1, "Excelsa" => 1, "kano90" => 1, "Maalox10" => 1, "TriangoloRotondo" => 1, "Piervillo" => 1, "Rean" => 1,
    "Jekpack" => 1, "playboikrame" => 1, "Danyr99" => 1, "ZiFrank" => 1, "Fevic" => 1];
  return isset($members[$userName]);
}

function IsTeamTalishar($userName)
{
  static $members = ["HelpMeJace2" => 1, "RainyDays" => 1, "Ragnell" => 1, "Hochi" => 1, "Cwaugh" => 1, "QZXK20" => 1, "VexingTie" => 1];
  return isset($members[$userName]);
}

function IsTeamTideBreakers($userName)
{
  static $members = ["OotTheMonk" => 1, "Yarandor" => 1, "grossmaul2130" => 1, "EggShot" => 1, "Kasadoom" => 1, "Gulto" => 1,
    "FinnElbe" => 1, "Stardragon" => 1, "DragonSlayer" => 1, "TerranceSkill" => 1, "TaddelDown" => 1,
    "Ilya" => 1, "PastaPaul" => 1];
  return isset($members[$userName]);
}

function IsTeamSunflowerSamurai($userName)
{
  static $members = ["Usagi" => 1, "HidaEishi" => 1, "kaikou" => 1, "Akuma" => 1, "Free" => 1, "yoeresel" => 1, "Kohs" => 1,
    "Ch3sh1r3" => 1, "NardoPotente" => 1, "dtitan" => 1, "Pokechtulhu" => 1, "CarlosGG" => 1, "N1MP0" => 1,
    "Clenyu" => 1, "juanmonzonf" => 1, "Raiswind" => 1, "Bossen" => 1];
  return isset($members[$userName]);
}

function isTeamCupofTCG($userName)
{
  static $members = ["Cody1304" => 1, "Glem" => 1, "parallaxdream" => 1, "2birds1stone" => 1];
  return isset($members[$userName]);
}

function isTeamScowlingFleshBag($userName)
{
  static $members = ["Scowling" => 1, "PvtVoid" => 1];
  return isset($members[$userName]);
}

function IsTeamThaiCardsShop($userName)
{
  static $members = ["thaicards" => 1];
  return isset($members[$userName]);
}

function IsTeamFABChaos($userName)
{
  static $members = ["SaXoChaos" => 1, "nakezuma" => 1, "Broken" => 1, "Atsacus" => 1, "rkntl" => 1,
    "SlyNight" => 1, "Elnor" => 1, "mythen" => 1, "Enegon" => 1, "Obnoxious" => 1];
  return isset($members[$userName]);
}

function IsTeamColdFoilControl($userName)
{
  static $members = ["Z-Gin" => 1, "Chaco" => 1, "Kentshero" => 1, "Ardent" => 1, "PurpleHaze" => 1, "luxas" => 1, "chefwheaton" => 1];
  return isset($members[$userName]);
}

function IsTeamRighteousGaming($userName)
{
  static $members = ["RighteousGaming" => 1, "Perodic" => 1, "zzdog" => 1, "krav" => 1, "Motley" => 1, "amodell" => 1,
    "TrentMcB" => 1, "pzych" => 1, "deragun" => 1, "Harvey0209" => 1, "f1av0r" => 1, "Vemnyx" => 1,
    "mclair" => 1, "FomToolery" => 1, "lostinspacefab" => 1, "SQJ" => 1, "magusoftheguild" => 1, "S1lverback55" => 1];
  return isset($members[$userName]);
}

function IsTeamMetalFab($userName)
{
  static $members = ["deathstalker182" => 1, "Closetnerds" => 1, "Diene9" => 1, "acroriver" => 1, "ShadowGriffin" => 1,
    "Kentshero" => 1, "thekingg21" => 1, "Lupinefiasco" => 1, "onlyrunverynoob" => 1, "Brishen" => 1,
    "Sinthrandir" => 1, "killerbrews" => 1, "Z-Gin" => 1, "Obliterage" => 1, "RedBeard" => 1, "KillerBrews" => 1];
  return isset($members[$userName]);
}

function IsTeamPotatoSquad($userName)
{
  static $members = ["Corry" => 1, "Gibbie" => 1, "sycotik" => 1, "ruin" => 1, "Xandorion" => 1, "ObiJohn" => 1,
    "tader" => 1, "Wittman1" => 1, "enflames91" => 1, "SlimDrew23" => 1, "NoRaven" => 1, "middiekittie" => 1,
    "archangel224" => 1, "Nick56" => 1, "SCORPIO" => 1, "ArgentGrey" => 1, "SynThePanda93" => 1,
    "welpcakes" => 1, "RiptideRipper" => 1, "gilfab" => 1, "dautt" => 1, "Grublo" => 1];
  return isset($members[$userName]);
}

function IsTeamFabledBrazil($userName)
{
  static $members = ["tetsuo" => 1, "hugodeoz" => 1, "diorge" => 1, "LGB" => 1, "mishel157" => 1, "DanielDertoni" => 1,
    "caduads" => 1, "DracaiBR" => 1, "gravebeat" => 1, "LiP" => 1, "DShima" => 1, "RodinhoTeclados" => 1];
  return isset($members[$userName]);
}

function IsTeamFatAndFurious($userName)
{
  static $members = ["OopsAllPummels" => 1, "AngelPillow" => 1, "stefchwan" => 1, "JK" => 1, "Astropeleki" => 1,
    "Debread" => 1, "Tilemachos27" => 1, "Intzah" => 1, "Cubacash" => 1, "karyo" => 1,
    "Ironclad" => 1, "Jorin" => 1, "anastaso73" => 1, "z4risu" => 1];
  return isset($members[$userName]);
}

function IsTeamPitchDevils($userName)
{
  static $members = ["Lestat" => 1, "elnino" => 1, "RTZ" => 1, "Schmax" => 1, "Belphegor" => 1, "FloJo" => 1,
    "MikeDwyer" => 1, "Dionysos" => 1, "Sosa" => 1, "TaddelDown" => 1, "inama" => 1, "Kanopterix" => 1];
  return isset($members[$userName]);
}

function IsTeamSnapDragons($userName)
{
  static $members = ["iamtherealdylanthompson" => 1, "SpoostingBendog" => 1, "EdgeOfAir" => 1, "Matt" => 1,
    "Diomedesau" => 1, "Nyjin" => 1, "Manavon" => 1, "Trouthammer" => 1, "N3ardeath" => 1,
    "Snaps" => 1, "TheGlib" => 1, "TheJudester" => 1];
  return isset($members[$userName]);
}

function IsTeamFabDads($userName)
{
  static $members = ["LostInDaSpace" => 1, "Belazhul" => 1, "zaketanapareis" => 1, "thilakinos" => 1, "Debread" => 1,
    "mellone" => 1, "makvag" => 1, "Pitsirikos" => 1, "Alith0r0sKykl0pas" => 1, "Jim" => 1, "nikfabfanfatty" => 1];
  return isset($members[$userName]);
}

function IsTeamRedLine($userName)
{
  static $members = ["Aegisworn" => 1, "CornOnJacob" => 1, "jonam33" => 1, "Scribnibble" => 1, "Yuriiko" => 1,
    "Sharp" => 1, "MXBloom" => 1, "Lazaeus" => 1, "bloodbit" => 1, "hurricanewes" => 1, "Aljo" => 1, "Flempa" => 1];
  return isset($members[$userName]);
}

function IsTeamSkillIssue($userName)
{
  static $members = ["Vaxildan" => 1, "kk96" => 1, "Skoupakas69" => 1, "BreakingChaos" => 1, "TheCouncillor" => 1, "JaxC" => 1,
    "Cubacash" => 1, "kungfoukios" => 1, "sudogreeko" => 1, "katsubina" => 1, "NikolasG" => 1, "LegenProMax" => 1,
    "sadonEmsi" => 1, "DioReformed" => 1, "AggroBlazeNo1Fan" => 1, "kenobi" => 1, "Giannis92" => 1, "AssassinoCapuccino" => 1];
  return isset($members[$userName]);
}

function IsTeamWingedHussars($userName)
{
  static $members = ["Calebovitsch" => 1, "Steve119" => 1, "Lucid" => 1, "Seba" => 1, "raskoks" => 1, "Chudy" => 1, "metatron" => 1,
    "Dovi" => 1, "dssstefan" => 1, "makos" => 1, "RavenLemur" => 1, "XIR" => 1, "PvtVoid" => 1];
  return isset($members[$userName]);
}

function IsTeamFabInsight($userName)
{
    static $members = ["FaBInsights" => 1, "PvtVoid" => 1];
  return isset($members[$userName]);
}

function IsTeamOddwillows($userName)
{
  static $members = ["BenOddwillows" => 1, "PvtVoid" => 1];
  return isset($members[$userName]);
}
