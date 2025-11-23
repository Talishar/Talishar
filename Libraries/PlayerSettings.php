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
$SET_AlwaysShowCounters = 32; //Always show counters on cards

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
 switch($settings[$SET_Cardback]) {
    case 1: return "CBBlack";
    case 2: return "CBCreamWhite";
    case 3: return "CBGold";
    case 4: return "CBWhite";
    case 5: return "CBRed";
    case 6: return "CBParchment";
    case 7: return "CBBlue";
    case 8: return "CBRuneblood";
    case 9: return "CBPushThePoint";
    case 10: return "CBGoAgainGaming";
    case 11: return "CBGAG_AzaleaCult";
    case 12: return "CBGAG_Azalea";
    case 13: return "CBGAG_AzaleaShot";
    case 14: return "CBGAG_Dorinthea";
    case 15: return "CBGAG_Dromai";
    case 16: return "CBGAG_Kassai";
    case 17: return "CBRedZoneRogue";
    case 18: return "CBRZR_10k";
    case 19: return "CBRZR_KadikosLibrary";
    case 20: return "CBRZR_Vehya";
    case 21: return "CBFabrary1";
    case 22: return "CBFabrary2";
    case 23: return "CBManSant";
    case 24: return "CBAttackActionPodcast";
    case 25: return "CBArsenalPass";
    case 26: return "CBTekloFoundry";
    case 27: return "CBPummelowanko";
    case 28: return "CBDragonShieldProTeamWB";
    case 29: return "CBFleshAndCommonBlood";
    case 30: return "CBSinOnStream";
    case 31: return "CBFreshAndBuds";
    case 32: return "CBSloopdoop";
    case 33: return "CBDMArmada";
    case 34: return "CBInstantSpeed";
    case 35: return "CBTheCardGuyz";
    case 36: return "CBHomeTownTCG";
    case 37: return "CBAscentGaming";
    case 38: return "CBFleshAndPod";
    case 39: return "CBKappolo";
    case 40: return "CBLibrariansOfSolana";
    case 41: return "CBTheMetrixMetagame";
    case 42: return "CBEternalOracles";
    case 43: return "CBTheTablePit";
    case 44: return "CBTCGTed";
    case 45: return "CBLuminaris";
    case 46: return "CBFaBLab";
    case 47: return "CBCardAdvantage";
    case 48: return "CBOnHit";
    case 49: return "CBSecondCycle";
    case 50: return "CBRavenousBabble";
    case 51: return "CBBlackWingStudio";
    case 52: return "CBManSantBlack";
    case 53: return "CBOnHitEffect";
    case 54: return "CBDaganWhite";
    case 55: return "CBSonicDoom";
    case 56: return "CBBrandao";
    case 57: return "CBFabrary3";
    case 58: return "CBFabrary4";
    case 59: return "CBFabrary5";
    case 60: return "CBFabrary6";
    case 61: return "CBFabrary7";
    case 62: return "CBFabrary8";
    case 63: return "CBOffTheRailsTCG";
    case 64: return "CBPummel";
    case 65: return "CBNxi";
    case 66: return "CBPvtVoid";
    case 67: return "CBEmperorsRome";
    case 68: return "CBWeMakeBest";
    case 69: return "CBWeMakeBest2";
    case 70: return "CBSunflowerSamurai";
    case 71: return "CBMnRCast";
    case 72: return "CBOnTheBauble";
    case 73: return "CBGorganianTome";
    case 74: return "CBFABChaos";
    case 75: return "CBColdFoilControl";
    case 76: return "CBDailyFab";
    case 77: return "CBRighteousGaming";
    case 78: return "CBRighteousGaming2";
    case 79: return "CBThePlagueHive";
    case 80: return "CBDropcast";
    case 81: return "CBSunflowerSamurai";
    case 82: return "CBTalisharTeam";
    case 83: return "CBTalisharTeam2";
    case 84: return "CBTideBreakers";
    case 85: return "CBCD1";
    case 86: return "CBCD2";
    case 87: return "CBCupofTCG";
    case 88: return "CBScowlingFleshBag";
    case 89: return "CBDazzyfizzle";
    case 90: return "CBDazzyfizzle1";
    case 91: return "CBDazzyfizzle2";
    case 92: return "CBDazzyfizzle3";
    case 93: return "CBDazzyfizzle4";
    case 94: return "CBDazzyfizzle5";
    case 95: return "CBDazzyfizzle6";
    case 96: return "CBThaiCardsShop";
    case 97: return "CBNikobru";
    case 98: return "CBDazzyfizzle7";
    case 99: return "CBDazzyfizzle8";
    case 100: return "CBSmilingFleshBag";
    case 101: return "CBDashciples";
    case 102: return "CBBlitzkriegMeph";
    case 103: return "CBHamMan215";
    case 104: return "CBNewHorizons";
    case 105: return "CBMetalFab";
    case 106: return "CBPotatoSquad";
    case 107: return "CBThreeFloating1";
    case 108: return "CBThreeFloating2";
    case 109: return "CBThreeFloating3";
    case 110: return "CBSteelfur";
    case 111: return "CBFleshAndBad";
    case 112: return "CBFabledBrazil";
    case 113: return "CBSilvarisGarden";
    case 114: return "CBDazzyfizzle9";
    case 115: return "CBDazzyfizzle10";
    case 116: return "CBDazzyfizzle11";
    case 117: return "CBDazzyfizzle12";
    case 118: return "CBAggroBlaze";
    case 119: return "CBFatAndFurious";
    case 120: return "CBRighteousGaming3";
    case 121: return "CBFreshAndBuds2";
    case 122: return "CBNull";
    case 123: return "CBPitchDevils";
    default: return "CardBack";
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
  if (IsReplay() || IsPlayerAI(2) || IsPlayerAI(1)) return true;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_DisableStats] == "1";
}

function AreGlobalStatsDisabled($player)
{
  global $SET_DisableFabInsights;
  if (IsReplay() || IsPlayerAI(2) || IsPlayerAI(1)) return true;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_DisableFabInsights] == "1";
}

function IsHeroIntroDisabled($player)
{
  global $SET_DisableHeroIntro;
  $settings = GetSettings($player);
  if ($settings == null) return false;
  return $settings[$SET_DisableHeroIntro] == "1";
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
  if ($player > 2) return true; //spectators
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
  ];
  return $settingsToId[$value];
}

function ChangeSetting($player, $setting, $value, $playerId = "")
{
  global $SET_MuteChat, $SET_AlwaysHoldPriority, $SET_CasterMode, $layerPriority, $gameName;
  // Only update game state if not in profile context
  if($player != "" && $player != 0) {
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
  if($playerId != "" && SaveSettingInDatabase($setting)) {
    SaveSetting($playerId, $setting, $value);
  }
}

function SaveSettingInDatabase($setting)
{
  global $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats, $SET_Language;
  global $SET_Format, $SET_FavoriteDeckIndex, $SET_GameVisibility, $SET_AlwaysHoldPriority, $SET_ManualMode;
  global $SET_StreamerMode, $SET_AutotargetArcane, $SET_Playmat, $SET_AlwaysAllowUndo, $SET_DisableAltArts, $SET_AlwaysShowCounters;
  global $SET_ManualTunic, $SET_DisableFabInsights, $SET_DisableHeroIntro, $SET_MirroredBoardLayout, $SET_MirroredPlayerBoardLayout;
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
    case $SET_DisableFabInsights:
    case $SET_DisableHeroIntro:
    case $SET_MirroredBoardLayout:
    case $SET_MirroredPlayerBoardLayout:
    case $SET_AlwaysShowCounters:
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
    case "compllcc": return 13;
    case "sage": return 14;
    case "compsage": return 15;
    case "openformatsage": return 16;
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
    case 13: return "compllcc";
    case 14: return "sage";
    case 15: return "compsage";
    case -2: return "precon";
    default: return "-";
  }
}

function IsTeamCardAdvantage($userName)
{
  switch ($userName) {
    case "JacobK": case "Pastry Boi": case "Brotworst": case "1nigoMontoya (Cody)": case "Motley":
    case "jimmyhl1329": case "Stilltzkin": case "krav": case "infamousb": case "FatFabJesus": case "MisterPNP":
      return true;
    default: break;
  }
  return false;
}

function IsTeamSecondCycle($userName)
{
  switch($userName) {
    case "The4thAWOL": case "Beserk": case "Dudebroski": case "deathstalker182": case "TryHardYeti": case "Fledermausmann":
    case "Loganninty7": case "flamedog3": case "Swankypants": case "Blazing For Lethal?": case "Jeztus": case "gokkar":
    case "Kernalxklink": case "Kymo13":
      return true;
    default: break;
  }
  return false;
}

function IsTeamSonicDoom($userName)
{
  switch($userName) {
    case "KanoSux": case "BestBoy": case "CRGrey": case "jujubeans": case "YodasUncle":
    case "ravenklath": case "Blazing For Lethal?": case "DimGuy": case "JoeyReads": case "OompaLoompaTron": case "Ocean":
    case "radiotoast": case "ThePitchStack": case "KanosWaterBottle": case "yamsandwic": case "ThatOneKano": case "YuutoSJ": case "ZorbyX": case "littlsnek":
      return true;
    default: break;
  }
  return false;
}

function IsTeamPummel($userName)
{
  switch($userName) {
    case "MkDk": case "Kutter": case "Smeoz": case "Fabio": case "JustFonta": case "M3X": case "Tommaso":
    case "PDMPLB":
      return true;
    default: break;
  }
  return false;
}

function IsTeamEmperorsRome($userName)
{
  switch($userName) {
    case "Daniele90rm": case "Excelsa": case "kano90": case "Maalox10": case "TriangoloRotondo": case "Piervillo": case "Rean":
    case "Jekpack": case "playboikrame": case "Danyr99": case "ZiFrank": case "Fevic":
      return true;
    default: break;
  }
  return false;
}

function IsTeamTalishar($userName)
{
  switch($userName) {
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

function IsTeamTideBreakers($userName)
{
  switch($userName) {
    case "OotTheMonk": case "Yarandor": case "grossmaul2130": case "EggShot": case "Kasadoom": case "Gulto":
    case "FinnElbe": case "Stardragon": case "DragonSlayer": case "TerranceSkill": case "TaddelDown":
    case "Ilya": case "PastaPaul":
      return true;
    default: break;
  }
  return false;
}

function IsTeamSunflowerSamurai($userName)
{
  switch($userName) {
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

function isTeamCupofTCG($userName)
{
  switch($userName) {
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

function isTeamScowlingFleshBag($userName)
{
  switch($userName) {
    case "Scowling":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamThaiCardsShop($userName)
{
  switch($userName) {
    case "thaicards":
    case "PvtVoid":
      return true;
    default: break;
  }
  return false;
}

function IsTeamFABChaos($userName)
{
  switch($userName) {
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

function IsTeamColdFoilControl($userName)
{
  switch($userName) {
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

function IsTeamRighteousGaming($userName)
{
  switch($userName) {
    case "RighteousGaming":
    case "Perodic":
    case "zzdog":
    case "krav":
    case "Motley":
    case "amodell":
    case "TrentMcB":
    case "pzych":
    case "deragun":
    case "PvtVoid":
    case "Harvey0209":
    case "f1av0r":
    case "Vemnyx":
    case "mclair":
    case "FomToolery":
    case "lostinspacefab":
    case "SQJ":
    case "magusoftheguild":
    case "S1lverback55":
      return true;
    default: break;
  }
  return false;
}

function IsTeamMetalFab($userName)
{
  switch($userName) {
      case "PvtVoid":
      case "deathstalker182":
      case "Closetnerds":
      case "Diene9":
      case "acroriver":
      case "ShadowGriffin":
      case "Kentshero":
      case "thekingg21":
      case "Lupinefiasco":
      case "onlyrunverynoob":
      case "Brishen":
      case "Sinthrandir":
      case "killerbrews":
      case "Z-Gin":
      case "Obliterage":
      case "RedBeard":
      case "KillerBrews":
      return true;
    default: break;
  }
  return false;
}

function IsTeamPotatoSquad($userName)
{
  switch($userName) {
    case "PvtVoid":
    case "Corry":
    case "Gibbie":
    case "sycotik":
    case "ruin":
    case "Xandorion":
    case "ObiJohn":
    case "tader":
    case "Wittman1":
    case "enflames91":
    case "SlimDrew23":
    case "NoRaven":
    case "middiekittie":
    case "archangel224":
      return true;
    default: break;
  }
  return false;
}

function IsTeamFabledBrazil($userName)
{
  switch($userName) {
    case "PvtVoid":
    case "tetsuo":
    case "hugodeoz":
    case "diorge":
    case "LGB":
    case "IvanTaranto":
    case "mishel157":
    case "DanielDertoni":
    case "caduads":
    case "DracaiBR":
    case "gravebeat":
    case "LiP":
    case "Laerte":
    case "izumi":
    case "Gandanm":
    case "DShima":
    case "FiotzGames":
      return true;
    default: break;
  }
  return false;
}

function IsTeamFatAndFurious($userName)
{
  switch ($userName) {
    case "PvtVoid":
    case "OopsAllPummels":
    case "AngelPillow":
    case "stefchwan":
    case "JK":
    case "Astropeleki":
    case "Debread":
    case "Tilemachos27":
    case "Intzah":
    case "Cubacash":
    case "karyo":
    case "Ironclad":
    case "Jorin":
    case "anastaso73":
    case "z4risu":
      return true;
    default:
      break;
  }
  return false;
}

function IsTeamPitchDevils($userName)
{
  switch ($userName) {
    case "PvtVoid":
    case "Lestat":
    case "elnino":
    case "RTZ":
    case "Schmax":
    case "Belphegor":
    case "FloJo":
    case "MikeDwyer":
    case "Dionysos":
    case "Sosa":
    case "TaddelDown":
    case "inama":
    case "Kanopterix":
      return true;
    default:
      break;
  }
  return false;
}