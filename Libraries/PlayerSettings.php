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

$SET_StreamerMode = 22; //Did this player enable caster mode
$SET_Playmat = 23; //Did this player enable caster mode
$SET_AlwaysAllowUndo = 24;//Do you want to always allow undo
$SET_DisableAltArts = 25;//Do you want to disable alt arts
$SET_ManualTunic = 26;//Do you want to manually tick up tunic each turn

function HoldPrioritySetting($player)
{
  global $SET_AlwaysHoldPriority;
  $settings = GetSettings($player);
  return $settings[$SET_AlwaysHoldPriority];
}

function ManualTunicSetting($player)
{
  global $SET_ManualTunic;
  $settings = GetSettings($player);
  return $settings[$SET_ManualTunic];
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
  return $settings[$SET_DarkMode] == 1 || $settings[$SET_DarkMode] == 3;
}

function IsPlainMode($player)
{
  global $SET_DarkMode;
  $settings = GetSettings($player);
  return $settings[$SET_DarkMode] == 2;
}

function IsDarkPlainMode($player)
{
  global $SET_DarkMode;
  $settings = GetSettings($player);
  return $settings[$SET_DarkMode] == 3;
}

function IsPatron($player)
{
  global $SET_IsPatron;
  $settings = GetSettings($player);
  if(count($settings) < $SET_IsPatron) return false;
  return $settings[$SET_IsPatron] == "1";
}

function GetPlaymat($player)
{
  global $SET_Playmat;
  $settings = GetSettings($player);
  return $settings[$SET_Playmat];
}

function GetCardBack($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return match ($settings[$SET_Cardback]) {
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
    default => "CardBack",
  };
}

function IsManualMode($player)
{
  global $SET_ManualMode;
  $settings = GetSettings($player);
  return $settings[$SET_ManualMode];
}

function ShouldSkipARs($player)
{
  global $SET_SkipARs;
  $settings = GetSettings($player);
  return $settings[$SET_SkipARs];
}

function ShouldSkipDRs($player)
{
  global $SET_SkipDRs, $SET_PassDRStep;
  $settings = GetSettings($player);
  $skip = $settings[$SET_SkipDRs] || $settings[$SET_PassDRStep];
  ChangeSetting($player, $SET_PassDRStep, 0);
  return $skip;
}

function ShouldAutotargetOpponent($player)
{
  global $SET_AutotargetArcane;
  $settings = GetSettings($player);
  return $settings[$SET_AutotargetArcane] == "1";
}

function IsColorblindMode($player)
{
  global $SET_ColorblindMode;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_ColorblindMode] == "1";
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
  return $settings[$SET_EnableDynamicScaling] == "1";
}

function IsMuted($player)
{
  global $SET_Mute;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_Mute] == "1";
}

function IsChatMuted()
{
  global $SET_MuteChat;
  $p1Settings = GetSettings(1);
  $p2Settings = GetSettings(2);
  return $p1Settings[$SET_MuteChat] == "1" || $p2Settings[$SET_MuteChat] == "1";
}

function AreStatsDisabled($player)
{
  global $SET_DisableStats;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_DisableStats] == "1";
}

function IsCasterMode()
{
  global $SET_CasterMode;
  $settings1 = GetSettings(1);
  $settings2 = GetSettings(2);
  if ($settings1 == null || $settings2 == null) return false;
  return $settings1[$SET_CasterMode] == "1" && $settings2[$SET_CasterMode] == "1";
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
  $settings = GetSettings($player);
  if($settings == null || count($settings) <= $SET_DisableAltArts) return false;
  return $settings[$SET_DisableAltArts] == "1";
}

function ParseSettingsStringValueToIdInt(string $value)
{
  $settingsToId = [
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
    "IsStreamerMode" => 22,
    "Playmat" => 23,
    "AlwaysAllowUndo" => 24,
    "DisableAltArts" => 25,
    "ManualTunic" => 26,
  ];
  return $settingsToId[$value];
}

function ChangeSetting($player, $setting, $value, $playerId = "")
{
  global $SET_MuteChat, $SET_AlwaysHoldPriority, $SET_CasterMode, $layerPriority, $gameName;
  if($player != "") {
    $settings = &GetSettings($player);
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
  if($playerId != "" && SaveSettingInDatabase($setting)) SaveSetting($playerId, $setting, $value);
}

function SaveSettingInDatabase($setting)
{
  global $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats, $SET_Language;
  global $SET_Format, $SET_FavoriteDeckIndex, $SET_GameVisibility, $SET_AlwaysHoldPriority, $SET_ManualMode;
  global $SET_StreamerMode, $SET_AutotargetArcane, $SET_Playmat, $SET_AlwaysAllowUndo, $SET_DisableAltArts;
  global $SET_ManualTunic;
  switch($setting) {
    case $SET_DarkMode:
    case $SET_ColorblindMode:
    case $SET_Mute:
    case $SET_Cardback:
    case $SET_DisableStats:
    case $SET_Language:
    case $SET_Format:
    case $SET_FavoriteDeckIndex:
    case $SET_GameVisibility:
    case $SET_AlwaysHoldPriority:
    case $SET_ManualMode:
    case $SET_StreamerMode:
    case $SET_AutotargetArcane:
    case $SET_Playmat:
    case $SET_AlwaysAllowUndo:
    case $SET_DisableAltArts:
    case $SET_ManualTunic:
      return true;
    default: return false;
  }
}

function FormatCode($format)
{
  switch($format) {
    case "cc": return 0;
    case "compcc": return 1;
    case "blitz": return 2;
    case "compblitz": return 3;
    case "openformatcc": return 4;
    case "commoner": return 5;
    case "sealed": return 6;
    case "draft": return 7;
    case "llcc": return 8;
    case "llblitz": return 9; //Currently not used
    case "openformatblitz": return 10;
    case "clash": return -1;
    case "openformatllcc": return 11;
    case "openformatllblitz": return 12; //Currently not used
    case "precon": return -2;
    default: return -1;
  }
}

function FormatName($formatCode)
{
  switch($formatCode)
  {
    case 0: return "cc";
    case 1: return "compcc";
    case 2: return "blitz";
    case 3: return "compblitz";
    case 4: return "openformatcc";
    case 5: return "commoner";
    case 6: return "sealed";
    case 7: return "draft";
    case 8: return "llcc";
    case 9: return "llblitz"; //Currently not used
    case 10: return "openformatblitz";
    case -1: return "clash";
    case 11: return "openformatllcc";
    case 12: return "openformatllblitz"; //Currently not used
    case -2: return "precon";
    default: return "-";
  }
}

function IsTeamCardAdvantage($userID)
{
  switch ($userID) {
    case "JacobK": case "Pastry Boi": case "Brotworst": case "1nigoMontoya (Cody)": case "Motley":
    case "jimmyhl1329": case "Stilltzkin": case "krav": case "infamousb": case "FatFabJesus": case "MisterPNP":
      return true;
    default: break;
  }
  return false;
}

function IsTeamSecondCycle($userID)
{
  switch($userID) {
    case "The4thAWOL": case "Beserk": case "Dudebroski": case "deathstalker182": case "TryHardYeti": case "Fledermausmann":
    case "Loganninty7": case "flamedog3": case "Swankypants": case "Blazing For Lethal?": case "Jeztus": case "gokkar":
    case "Kernalxklink": case "Kymo13":
      return true;
    default: break;
  }
  return false;
}

function IsTeamSonicDoom($userID)
{
  switch($userID) {
    case "KanoSux": case "BestBoy": case "CRGrey": case "jujubeans": case "YodasUncle":
    case "ravenklath": case "Blazing For Lethal?": case "DimGuy": case "JoeyReads": case "OompaLoompaTron": case "OceansForce":
    case "radiotoast": case "ThePitchStack": case "KanosWaterBottle": case "yamsandwic": case "ThatOneKano": case "YuutoSJ": case "ZorbyX": case "littlsnek":
      return true;
    default: break;
  }
  return false;
}

function IsTeamPummel($userID)
{
  switch($userID) {
    case "MkDk": case "Kutter": case "Smeoz": case "Fabio": case "JustFonta": case "M3X": case "Tommaso":
    case "PDMPLB":
      return true;
    default: break;
  }
  return false;
}

function IsTeamEmperorsRome($userID)
{
  switch($userID) {
    case "Daniele90rm": case "Excelsa": case "kano90": case "Maalox10": case "TriangoloRotondo": case "Piervillo": case "Rean":
    case "Jekpack": case "playboikrame": case "Danyr99": case "ZiFrank": case "Fevic":
      return true;
    default: break;
  }
  return false;
}

function IsTeamTalishar($userID)
{
  switch($userID) {
    case "HelpMeJace2":
    case "RainyDays":
    case "Ragnell":
    case "Hochi":
    case "Cwaugh":
    case "QZXK20":
    case "VexingTie":
      return true;
    default: break;
  }
  return false;
}

function IsTeamTideBreakers($userID)
{
  switch($userID) {
    case "OotTheMonk": case "Yarandor": case "grossmaul2130": case "EggShot": case "Kasadoom": case "Gulto":
    case "FinnElbe": case "Stardragon": case "DragonSlayer": case "TerranceSkill": case "TaddelDown":
      return true;
    default: break;
  }
  return false;
}

function IsTeamSunflowerSamurai($userID)
{
  switch($userID) {
    case "Usagi":
    case "HidaEishi":
    case "kaikou":
    case "Akuma":
    case "Free":
    case "yoeresel":
    case "Kohs":
    case "Ch3sh1r3":
    case "NardoPotente":
    case "dtitan":
    case "Pokechtulhu":
    case "CarlosGG":
    case "N1MP0":
    case "Clenyu":
    case "juanmonzonf":
    case "Raiswind":
    case "Bossen":
      return true;
    default: break;
  }
  return false;
}

function isTeamCupofTCG($userID)
{
  switch($userID) {
    case "Cody1304":
    case "Glem":
    case "parallaxdream":
    case "2birds1stone":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function isTeamScowlingFleshBag($userID)
{
  switch($userID) {
    case "ScowlingFleshBag":
    case "SmilingFleshBag":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamThaiCardsShop($userID)
{
  switch($userID) {
    case "terrythai03":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamFABChaos($userID)
{
  switch($userID) {
    case "SaXoChaos":
    case "nakezuma":
    case "Broken":
    case "Atsacus":
    case "rkntl":
    case "SlyNight":
    case "Elnor":
    case "mythen":
    case "Enegon":
    case "Obnoxious":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamColdFoilControl($userID)
{
  switch($userID) {
    case "Z-Gin":
    case "Chaco":
    case "Kentshero":
    case "Ardent":
    case "PurpleHaze":
    case "luxas":
    case "chefwheaton":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamRighteousGaming($userID)
{
  switch($userID) {
    case "MisterPNP":
    case "RighteousGaming":
    case "Perodic":
    case "zzdog":
    case "krav":
    case "Motley":
    case "Pastry Boi":
    case "amodell":
    case "TrentMcB":
    case "pzych":
    case "TCGTALK":
    case "deragun":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamMetalFab($userID)
{
  switch($userID) {
      case "PvtVoid":
      case "deathstalker182":
      case "Closetnerds":
      case "Diene9":
      case "acroriver":
      case "ShadowGriffin":
      case "Kentshero":
      case "thekingg21":
      case "lupinefiasco":
      case "onlyrunverynoob":
      case "Brishen":
      case "Sinthrandir":
      case "killerbrews":
      case "Z-Gin":
      case "Obliterage":
      case "Redbeard":
      return true;
    default: break;
  }
  return false;
}
