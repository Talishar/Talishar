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

function HoldPrioritySetting($player)
{
  global $SET_AlwaysHoldPriority;
  $settings = GetSettings($player);
  return $settings[$SET_AlwaysHoldPriority];
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

function IsLanguageJP($player)
{
  global $SET_Language;
  if(function_exists("GetSettings")) {
    $settings = GetSettings($player);
    return $settings[$SET_Language] == "2";
  } else if(isset($_SESSION['language'])) {
    return $_SESSION['language'] == "2";
  }
  return false;
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
    default: return "CardBack";
  }
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

function ParseSettingsStringValueToIdInt(string $value)
{
  //TODO NOTE: use array_flip to turn it the other way around (int -> string);
  $settingsToId = array(
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
  );
  return $settingsToId[$value];
}

function ChangeSetting($player, $setting, $value, $playerId = "")
{
  global $SET_MuteChat, $SET_AlwaysHoldPriority, $layerPriority;
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
    }
  }
  if($playerId != "" && SaveSettingInDatabase($setting)) SaveSetting($playerId, $setting, $value);
}

function GetSettingsUI($player)
{
  global $SET_AlwaysHoldPriority, $SET_DarkMode, $SET_ManualMode, $SET_SkipARs, $SET_SkipDRs, $SET_AutotargetArcane, $SET_ColorblindMode;
  global $SET_ShortcutAttackThreshold, $SET_EnableDynamicScaling, $SET_Mute, $SET_Cardback, $SET_MuteChat, $SET_DisableStats;
  global $SET_CasterMode, $SET_StreamerMode;
  $rv = "";
  $settings = GetSettings($player);
  $currentValue = HoldPrioritySetting($player);
  $rv .= "<h3>Hold Priority Settings: </h3>";
  $rv .= CreateRadioButton($SET_AlwaysHoldPriority . "-0", "Auto-pass Priority", 26, $SET_AlwaysHoldPriority . "-" . $currentValue, "Auto-pass Priority");
  $rv .= CreateRadioButton($SET_AlwaysHoldPriority . "-4", "Always Pass Priority", 26, $SET_AlwaysHoldPriority . "-" . $currentValue, "Always Pass Priority");
  $rv .= "<BR>";
  $rv .= CreateRadioButton($SET_AlwaysHoldPriority . "-1", "Always Hold Priority", 26, $SET_AlwaysHoldPriority . "-" . $currentValue, "Always Hold Priority");
  $rv .= CreateRadioButton($SET_AlwaysHoldPriority . "-2", "Hold Priority All Opp", 26, $SET_AlwaysHoldPriority . "-" . $currentValue, "Hold Priority All Opp");
  $rv .= "<BR>";
  $rv .= CreateRadioButton($SET_AlwaysHoldPriority . "-3", "Hold Priority Opp. Attacks", 26, $SET_AlwaysHoldPriority . "-" . $currentValue, "Hold Priority Opp. Attacks");
  $rv .= "<BR>";
  if ($settings[$SET_SkipARs] == 0) $rv .= CreateCheckbox($SET_SkipARs . "-1", "Skip Attack Reactions", 26, false, "Skip Attack Reactions");
  else $rv .= CreateCheckbox($SET_SkipARs . "-0", "Skip Attack Reactions", 26, true, "Skip Attack Reactions");
  $rv .= "<BR>";
  if ($settings[$SET_SkipDRs] == 0) $rv .= CreateCheckbox($SET_SkipDRs . "-1", "Skip Defense Reactions", 26, false, "Skip Defense Reactions");
  else $rv .= CreateCheckbox($SET_SkipDRs . "-0", "Skip Defense Reactions", 26, true, "Skip Defense Reactions");
  $rv .= "<BR>";
  if ($settings[$SET_AutotargetArcane] == 0) $rv .= CreateCheckbox($SET_AutotargetArcane . "-1", "Arcane Manual Targetting", 26, true, "Manual Targetting");
  else $rv .= CreateCheckbox($SET_AutotargetArcane . "-0", "Arcane Manual Targetting", 26, false, "Manual Targetting");
  $rv .= "<BR>";
  $currentValue = ShortcutAttackThreshold($player);
  $rv .= "<h3 style='padding-top:10px;'>Attack Shortcut Threshold: </h3>";
  $rv .= CreateRadioButton($SET_ShortcutAttackThreshold . "-0", "Never Skip", 26, $SET_ShortcutAttackThreshold . "-" . $currentValue, "Never Skip");
  $rv .= CreateRadioButton($SET_ShortcutAttackThreshold . "-1", "Skip 1 Power Attacks", 26, $SET_ShortcutAttackThreshold . "-" . $currentValue, "Skip 1 Power Attacks");
  $rv .= "<BR>";
  $rv .= CreateRadioButton($SET_ShortcutAttackThreshold . "-99", "Skip All Attacks", 26, $SET_ShortcutAttackThreshold . "-" . $currentValue, "Skip All Attacks");
  $rv .= "<BR>";
  $rv .= "<h3>In-Game Theme:</h3>";
  $rv .= CreateRadioButton($SET_DarkMode . "-0", "Normal Mode", 26, $SET_DarkMode . "-" . $settings[$SET_DarkMode], "Normal Mode");
  $rv .= CreateRadioButton($SET_DarkMode . "-1", "Dark Mode", 26, $SET_DarkMode . "-" . $settings[$SET_DarkMode], "Dark Mode");
  $rv .= "<BR>";
  $rv .= CreateRadioButton($SET_DarkMode . "-2", "Plain Mode", 26, $SET_DarkMode . "-" . $settings[$SET_DarkMode], "Plain Mode");
  $rv .= CreateRadioButton($SET_DarkMode . "-3", "Dark Plain Mode", 26, $SET_DarkMode . "-" . $settings[$SET_DarkMode], "Dark Plain Mode");

  $rv .= "<h3>Card Backs</h3>";
  $hasCardBacks = false;
  foreach(PatreonCampaign::cases() as $campaign) {
    if(isset($_SESSION[$campaign->SessionID()]) || (isset($_SESSION["useruid"]) && $campaign->IsTeamMember($_SESSION["useruid"]))) {
      $hasCardBacks = true;
      $cardBacks = $campaign->CardBacks();
      $cardBacks = explode(",", $cardBacks);
      for($i = 0; $i < count($cardBacks); ++$i) {
        $name = $campaign->CampaignName() . (count($cardBacks) > 1 ? " " . $i + 1 : "");
        $rv .= CreateRadioButton($SET_Cardback . "-" . $cardBacks[$i], str_replace(' ', '', $name), 26, $SET_Cardback . "-" . $settings[$SET_Cardback], $name);
      }
    }
  }

  $rv .= "<BR>";
  if($settings[$SET_ManualMode] == 0) $rv .= CreateCheckbox($SET_ManualMode . "-1", "Manual Mode", 26, false, "Manual Mode");
  else $rv .= CreateCheckbox($SET_ManualMode . "-0", "Manual Mode", 26, true, "Manual Mode");
  $rv .= "<BR>";

  if($settings[$SET_ColorblindMode] == 0) $rv .= CreateCheckbox($SET_ColorblindMode . "-1", "Accessibility Mode", 26, false, "Accessibility Mode");
  else $rv .= CreateCheckbox($SET_ColorblindMode . "-0", "Accessibility Mode", 26, true, "Accessibility Mode");
  $rv .= "<BR>";

  if($settings[$SET_EnableDynamicScaling] == 0) $rv .= CreateCheckbox($SET_EnableDynamicScaling . "-1", "Dynamic Scaling (Under Dev)", 26, false, "Dynamic Scaling (Under Dev)", true);
  else $rv .= CreateCheckbox($SET_EnableDynamicScaling . "-0", "Dynamic Scaling (Under Dev)", 26, true, "Dynamic Scaling (Under Dev)", true);
  $rv .= "<BR>";

  if($settings[$SET_Mute] == 0) $rv .= CreateCheckbox($SET_Mute . "-1", "Mute", 26, false, "Mute", true);
  else $rv .= CreateCheckbox($SET_Mute . "-0", "Unmute", 26, true, "Unmute", true);
  $rv .= "<BR>";

  if($settings[$SET_MuteChat] == 0) $rv .= CreateCheckbox($SET_MuteChat . "-1", "Disable Chat", 26, false, "Disable Chat", true);
  else $rv .= CreateCheckbox($SET_MuteChat . "-0", "Disable Chat", 26, true, "Disable Chat", true);
  $rv .= "<BR>";

  if($settings[$SET_DisableStats] == 0) $rv .= CreateCheckbox($SET_DisableStats . "-1", "Disable Stats", 26, false, "Disable Stats", true);
  else $rv .= CreateCheckbox($SET_DisableStats . "-0", "Disable Stats", 26, true, "Disable Stats", true);
  $rv .= "<BR>";

  if($settings[$SET_CasterMode] == 0) $rv .= CreateCheckbox($SET_CasterMode . "-1", "Caster Mode", 26, false, "Caster Mode", true);
  else $rv .= CreateCheckbox($SET_CasterMode . "-0", "Caster Mode", 26, true, "Caster Mode", true);
  $rv .= "<BR>";

  if($settings[$SET_StreamerMode] == 0) $rv .= CreateCheckbox($SET_StreamerMode . "-1", "Streamer Mode", 26, false, "Streamer Mode", true);
  else $rv .= CreateCheckbox($SET_StreamerMode . "-0", "Streamer Mode", 26, true, "Streamer Mode", true);
  $rv .= "<BR>";

  return $rv;
}

function SaveSettingInDatabase($setting)
{
  global $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats, $SET_Language;
  global $SET_Format, $SET_FavoriteDeckIndex, $SET_GameVisibility, $SET_AlwaysHoldPriority, $SET_ManualMode;
  global $SET_StreamerMode, $SET_AutotargetArcane, $SET_Playmat;
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
      return true;
    default: return false;
  }
}

function TranslationExist($Language, $cardID)
{
  switch($Language) {
    case "JP": //Japanese
      switch($cardID) {
        case "CRU046":
        case "CRU050":
        case "CRU063":
        case "CRU069":
        case "CRU072":
        case "CRU073":
        case "CRU074":
        case "CRU186":
        case "CRU187":
        case "CRU194":
        case "WTR100":
        case "WTR191":
          return true;
        default: return false;
      }
      break;
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
    case "livinglegendscc": return 4;
    case "commoner": return 5;
    case "sealed": return 6;
    case "draft": return 7;
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
    case 4: return "livinglegendscc";
    case 5: return "commoner";
    case 6: return "sealed";
    case 7: return "draft";
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
