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

// 0 - Default
// 1 - Black
// 2 - Cream
// 3 - Golden
// 4 - Grey
// 5 - Red
// 6 - Tan
// 7 - Blue
function IsCardBackBlackMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 1;
}

function IsCardBackCreamMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 2;
}

function IsCardBackGoldMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 3;
}

function IsCardBackGreyMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 4;
}

function IsCardBackRedMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 5;
}

function IsCardBackTanMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 6;
}

function IsCardBackBlueMode($player)
{
  global $SET_Cardback;
  $settings = GetSettings($player);
  return $settings[$SET_Cardback] == 7;
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

function ChangeSetting($player, $setting, $value)
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
   if (IsPatron($player)) {
    $rv .= "<h3>Card Backs</h3>";
    $rv .= CreateRadioButton($SET_Cardback . "-0", "Default", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Default");
    $rv .= CreateRadioButton($SET_Cardback . "-1", "Black", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Black");
    $rv .= CreateRadioButton($SET_Cardback . "-2", "Cream", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Cream");
    $rv .= "<BR>";
    $rv .= CreateRadioButton($SET_Cardback . "-3", "Golden", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Golden");
    $rv .= CreateRadioButton($SET_Cardback . "-4", "Grey", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Grey");
    $rv .= CreateRadioButton($SET_Cardback . "-5", "Red", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Red ");
    $rv .= "<BR>";
    $rv .= CreateRadioButton($SET_Cardback . "-6", "Tan", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Tan");
    $rv .= CreateRadioButton($SET_Cardback . "-7", "Blue", 26, $SET_Cardback . "-" . $settings[$SET_Cardback], "Blue");
   }

  /*
    $rv .= "<BR>";
    if($settings[$SET_ManualMode] == 0) $rv .= CreateButton($player, "Request Manual Mode", 26, $SET_ManualMode . "-1", "24px");
    else $rv .= CreateButton($player, "Turn Off Manual Mode", 26, $SET_ManualMode . "-0", "24px");
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
