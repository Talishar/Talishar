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

  /*================
      Card Backs
  =================*/
function IsCardBackBlack($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 1;
}

function IsCardBackCream($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 2;
}

function IsCardBackGold($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 3;
}

function IsCardBackGrey($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 4;
}

function IsCardBackRed($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 5;
}

function IsCardBackTan($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 6;
}

function IsCardBackBlue($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 7;
}

function IsCardBackRuneblood($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 8;
}

function IsCardBackPushThePoint($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 9;
}

function IsCardBackGoAgainGaming($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 10;
}

function IsCardBackGAGAzaleaCult($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 11;
}

function IsCardBackGAGAzalea($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 12;
}

function IsCardBackGAGAzaleaShot($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 13;
}

function IsCardBackGAGDorinthea($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 14;
}

function IsCardBackGAGDromai($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 15;
}

function IsCardBackGAGKassai($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 16;
}

function IsCardBackRedZoneRogue($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 17;
}

function IsCardBackRZR10k($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 18;
}

function IsCardBackRZRKadikosLibrary($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 19;
}

function IsCardBackRZRVehya($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 20;
}

function IsCardBackFabrary1($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 21;
}

function IsCardBackFabrary2($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 22;
}

function IsCardBackManSant($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 23;
}

function IsCardBackAttackActionPodcast($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 24;
}

function IsCardBackArsenalPass($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 25;
}

function IsCardBackTheTekloFroundry($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 26;
}

function IsCardBackPummelowanko($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 27;
}

function IsCardBackDragonShieldProTeamWB($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 28;
}

function IsCardBackFleshAndCommonBlood($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 29;
}

function IsCardBackSinOnStream($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 30;
}

function IsCardBackFreshAndBuds($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 31;
}

function IsCardBackSloopdoop($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 32;
}

function IsCardBackDMArmada($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 33;
}

function IsCardBackInstantSpeed($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 34;
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
  if($settings1 == null || $settings2 == null) return false;
  return $settings1[$SET_CasterMode] == "1" && $settings2[$SET_CasterMode] == "1";
}

function ChangeSetting($player, $setting, $value, $playerId="")
{
  /*
  global $SET_ManualMode;

    if($setting == $SET_ManualMode && $value == 1)
    {
      $otherPlayer = ($player == 1 ? 2 : 1);
      WriteLog("Player " . $player . " has requested to enter manual mode.");
      PrependDecisionQueue("APPROVEMANUALMODE", $player, "-", 1);
      PrependDecisionQueue("NOPASS", $otherPlayer, "-");
      PrependDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_allow_the_other_player_to_enter_manual_mode");
      return;
    }
    */
  $settings = &GetSettings($player);
  $settings[$setting] = $value;
  if($playerId != "" && SaveSettingInDatabase($setting)) SaveSetting($playerId, $setting, $value);
}

// function ApproveManualMode($player)
// {
//   global $SET_ManualMode;
//   $settings = &GetSettings($player);
//   $settings[$SET_ManualMode] = $value; // TODO: fix $value. The variable is undefined
// }

function GetSettingsUI($player)
{
  global $SET_AlwaysHoldPriority, $SET_DarkMode, $SET_ManualMode, $SET_SkipARs, $SET_SkipDRs, $SET_AutotargetArcane, $SET_ColorblindMode;
  global $SET_ShortcutAttackThreshold, $SET_EnableDynamicScaling, $SET_Mute, $SET_Cardback, $SET_MuteChat, $SET_DisableStats;
  global $SET_CasterMode;
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
  $rv .= "<BR>";
  if ($settings[$SET_ManualMode] == 0) $rv .= CreateCheckbox($SET_ManualMode . "-1", "Manual Mode", 26, false, "Manual Mode");
  else $rv .= CreateCheckbox($SET_ManualMode . "-0", "Manual Mode", 26, true, "Manual Mode");
  $rv .= "<BR>";
  //if($settings[$SET_ColorblindMode] == 0) $rv .= CreateButton($player, "Turn On color accessibility mode", 26, $SET_ColorblindMode . "-1", "24px", "", "", true);
  //else $rv .= CreateButton($player, "Turn Off color accessibility mode", 26, $SET_ColorblindMode . "-0", "24px", "", "", true);

  if ($settings[$SET_ColorblindMode] == 0) $rv .= CreateCheckbox($SET_ColorblindMode . "-1", "Accessibility Mode", 26, false, "Accessibility Mode");
  else $rv .= CreateCheckbox($SET_ColorblindMode . "-0", "Accessibility Mode", 26, true, "Accessibility Mode");
  $rv .= "<BR>";

  if ($settings[$SET_EnableDynamicScaling] == 0) $rv .= CreateCheckbox($SET_EnableDynamicScaling . "-1", "Dynamic Scaling (Under Dev)", 26, false, "Dynamic Scaling (Under Dev)", true);
  else $rv .= CreateCheckbox($SET_EnableDynamicScaling . "-0", "Dynamic Scaling (Under Dev)", 26, true, "Dynamic Scaling (Under Dev)", true);
  $rv .= "<BR>";

  if ($settings[$SET_Mute] == 0) $rv .= CreateCheckbox($SET_Mute . "-1", "Mute", 26, false, "Mute", true);
  else $rv .= CreateCheckbox($SET_Mute . "-0", "Unmute", 26, true, "Unmute", true);
  $rv .= "<BR>";

  if ($settings[$SET_MuteChat] == 0) $rv .= CreateCheckbox($SET_MuteChat . "-1", "Disable Chat", 26, false, "Disable Chat", true);
  else $rv .= CreateCheckbox($SET_MuteChat . "-0", "Disable Chat", 26, true, "Disable Chat", true);
  $rv .= "<BR>";

  if ($settings[$SET_DisableStats] == 0) $rv .= CreateCheckbox($SET_DisableStats . "-1", "Disable Stats", 26, false, "Disable Stats", true);
  else $rv .= CreateCheckbox($SET_DisableStats . "-0", "Disable Stats", 26, true, "Disable Stats", true);
  $rv .= "<BR>";

  if ($settings[$SET_CasterMode] == 0) $rv .= CreateCheckbox($SET_CasterMode . "-1", "Caster Mode", 26, false, "Caster Mode", true);
  else $rv .= CreateCheckbox($SET_CasterMode . "-0", "Caster Mode", 26, true, "Caster Mode", true);
  $rv .= "<BR>";

  // 0 - Default
  // 1 - Black
  // 2 - Cream
  // 3 - Golden
  // 4 - Grey
  // 5 - Red
  // 6 - Tan
  // 7 - Blue
  // 8 - Runeblood
  // 9 - Push the Point
   $rv .= "<h3>Card Backs</h3>";
   $hasCardBacks = false;
   $rv .= CreateRadioButton($SET_Cardback . "-0", "Default", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Default");
   if (IsPatron($player)) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-1", "Black", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Black");
    $rv .= CreateRadioButton($SET_Cardback . "-2", "Cream", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Cream");
    $rv .= "<BR>";
    $rv .= CreateRadioButton($SET_Cardback . "-3", "Golden", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Golden");
    $rv .= CreateRadioButton($SET_Cardback . "-4", "Grey", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Grey");
    $rv .= CreateRadioButton($SET_Cardback . "-5", "Red", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Red ");
    $rv .= "<BR>";
    $rv .= CreateRadioButton($SET_Cardback . "-6", "Tan", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Tan");
    $rv .= CreateRadioButton($SET_Cardback . "-7", "Blue", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Blue");
    $rv .= CreateRadioButton($SET_Cardback . "-8", "Runeblood", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Runeblood");
    $rv .= "<BR>";
   }

  $isPtPPatron = false;
  $isPtPPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "Hamsack" || $_SESSION["useruid"] == "BigMedSi" || $_SESSION["useruid"] == "Tripp" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isPtPPatron'] || $isPtPPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-9", "PushThePoint", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Push the Point");
  }

  $isGoAgainGamingPatron = false;
  $isGoAgainGamingPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "GoAgainGamingAz" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isGoAgainGamingPatron'] || $isGoAgainGamingPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-10", "GoAgainGaming", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Go Again Gaming");
    $rv .= CreateRadioButton($SET_Cardback . "-11", "GAGAzaleaCult", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "GAG Azalea Cult");
    $rv .= CreateRadioButton($SET_Cardback . "-12", "GAGAzalea", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "GAG Azalea");
    $rv .= CreateRadioButton($SET_Cardback . "-13", "GAGAzaleaShot", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "GAG Azalea Shot");
    $rv .= CreateRadioButton($SET_Cardback . "-14", "GAGDorinthea", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "GAG Dorinthea");
    $rv .= CreateRadioButton($SET_Cardback . "-15", "GAGDromai", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "GAG Droami");
    $rv .= CreateRadioButton($SET_Cardback . "-16", "GAGKAssai", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "GAG Kassai");
  }

  $isRedZoneRoguePatron = false;
  $isRedZoneRoguePatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "RedZoneRogue" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isRedZoneRoguePatron'] || $isRedZoneRoguePatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-17", "RedZoneRogue", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Red Zone Rogue");
    $rv .= CreateRadioButton($SET_Cardback . "-18", "RZR10k", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "RZR 10k");
    $rv .= CreateRadioButton($SET_Cardback . "-19", "RZRKadikosLibrary", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "RZR Kadikos Library");
    $rv .= CreateRadioButton($SET_Cardback . "-20", "RZRVehya", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "RZR Vehya");
  }

  $isFabraryPatron = false;
  $isFabraryPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "phillip" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isFabraryPatron'] || $isFabraryPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-21", "Fabrary1", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Fabrary One");
    $rv .= CreateRadioButton($SET_Cardback . "-22", "Fabrary2", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Fabrary Two");
  }

  $isManSantPatron = false;
  $isManSantPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "Man_Sant" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isManSantPatron'] || $isManSantPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-23", "ManSant", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Man Sant");
  }

  $isAttackActionPodcastPatreon = false;
  $isAttackActionPodcastPatreon = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "chonigman" || $_SESSION["useruid"] == "Ijaque" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isAttackActionPodcastPatreon'] || $isAttackActionPodcastPatreon) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-24", "AttackActionPodcast", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Attack Action Podcast");
  }

  $isArsenalPassPatreon = false;
  $isArsenalPassPatreon = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "Brendan" || $_SESSION["useruid"] == "TheClub" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isArsenalPassPatreon'] || $isArsenalPassPatreon) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-25", "ArsenalPass", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Arsenal Pass");
  }

  $isTheTekloFoundryPatreon = false;
  $isTheTekloFoundryPatreon = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "TheTekloFoundry" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isTheTekloFoundryPatreon'] || $isTheTekloFoundryPatreon) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-26", "TheTekloFoundry", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "The Teklo Foundry");
  }

  $isPummelowanko = false;
  $isPummelowanko = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "MrShub" || $_SESSION["useruid"] == "duofanel" || $_SESSION["useruid"] == "PvtVoid");
  if ($isPummelowanko) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-27", "Pummelowanko", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Pummelowanko");
  }

  $isDragonShieldProTeam = false;
  $isDragonShieldProTeam = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "TwitchTvFabschool" || $_SESSION["useruid"] == "MattRogers" || $_SESSION["useruid"] == "TariqPatel" || $_SESSION["useruid"] == "PvtVoid");
  if ($isDragonShieldProTeam) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-28", "DragonShieldProTeamWB", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Dragon Shield Pro Team WB");
  }

  $isFleshAndCommonBloodPatreon = false;
  $isFleshAndCommonBloodPatreon = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "Smithel" || $_SESSION["useruid"] == "PvtVoid");
  if ($isFleshAndCommonBloodPatreon) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-29", "FleshAndCommonBlood", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Flesh And Common Blood");
  }

  $isSinOnStreamPatreon = false;
  $isSinOnStreamPatreon = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "SinOnStream" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isSinOnStreamPatreon'] || $isSinOnStreamPatreon) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-30", "SinOnStream", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Sin On Stream");
  }

  $isFreshAndBudsPatreon = false;
  $isFreshAndBudsPatreon = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "FreshLord" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isFreshAndBudsPatreon'] || $isFreshAndBudsPatreon) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-31", "FreshAndBuds", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Fresh and Buds");
  }

  $isSloopdoopPatron = false;
  $isSloopdoopPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "Sloopdoop" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isSloopdoopPatron'] || $isSloopdoopPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-32", "Sloopdoop", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Sloopdoop");
  }

  $isDMArmadaPatron = false;
  $isDMArmadaPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "DMArmada" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isDMArmadaPatron'] || $isDMArmadaPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-33", "DM Armada", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "DM Armada");
  }

  $isInstantSpeedPatron = false;
  $isInstantSpeedPatron = isset($_SESSION["useruid"]) && ($_SESSION["useruid"] == "Flake" || $_SESSION["useruid"] == "PvtVoid");
  if ($_SESSION['isInstantSpeedPatron'] || $isInstantSpeedPatron) {
    $hasCardBacks = true;
    $rv .= CreateRadioButton($SET_Cardback . "-34", "Instant Speed", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Instant Speed Podcast");
  }

  if (!$hasCardBacks) $rv .= "<h4>Become a patron to customize your card backs!</h4>";

  /*
    $rv .= "<BR>";
    if($settings[$SET_ManualMode] == 0) $rv .= CreateButton($player, "Request Manual Mode", 26, $SET_ManualMode . "-1", "24px");
    else $rv .= CreateButton($player, "Turn Off Manual Mode", 26, $SET_ManualMode . "-0", "18px");
    if(IsManualMode($player))
    {
      $rv .= "<h3>Manual Mode Options</h3>";
      $rv .= CreateButton($playerID, "Undo", 10000, 0, "24px");
      $rv .= "<BR>";
      $rv .= CreateButton($playerID, "+1 Action Point", 10002, 0, "24px");
    }
*/
  return $rv;
}

function SaveSettingInDatabase($setting)
{
  global $SET_DarkMode, $SET_ColorblindMode, $SET_Mute, $SET_Cardback, $SET_DisableStats;
  switch($setting)
  {
    case $SET_DarkMode:
    case $SET_ColorblindMode:
    case $SET_Mute:
    case $SET_Cardback:
    case $SET_DisableStats:
      return true;
    default: return false;
  }
}
