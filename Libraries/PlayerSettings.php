<?php

  $SET_AlwaysHoldPriority = 0;
  $SET_TryUI2 = 1;
  $SET_DarkMode = 2;
  $SET_ManualMode = 3;

  $SET_SkipARs = 4;
  $SET_SkipDRs = 5;

  $SET_PassDRStep = 6;

  $SET_AutotargetArcane = 7;//Auto-target opponent with arcane damage

  $SET_ColorblindMode = 8;//Colorblind mode settings

  $SET_ShortcutAttackThreshold = 9;//Threshold to shortcut attacks
  $SET_EnableDynamicScaling = 10;//Threshold to shortcut attacks

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
    if($settings == null) return false;
    return $settings[$SET_ColorblindMode] == "1";
  }

  function ShortcutAttackThreshold($player)
  {
    global $SET_ShortcutAttackThreshold;
    $settings = GetSettings($player);
    if(count($settings) < $SET_ShortcutAttackThreshold) return "0";
    return $settings[$SET_ShortcutAttackThreshold];
  }

  function IsDynamicScalingEnabled($player)
  {
    global $SET_EnableDynamicScaling;
    $settings = GetSettings($player);
    if($settings == null) return false;
    return $settings[$SET_EnableDynamicScaling] == "1";
  }

  function ChangeSetting($player, $setting, $value)
  {
    global $SET_ManualMode;
    /*
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

  function ApproveManualMode($player)
  {
    global $SET_ManualMode;
    $settings = &GetSettings($player);
    $settings[$SET_ManualMode] = $value;
  }

  function GetSettingsUI($player)
  {
    global $SET_AlwaysHoldPriority, $SET_TryUI2, $SET_DarkMode, $SET_ManualMode, $SET_SkipARs, $SET_SkipDRs, $SET_AutotargetArcane, $SET_ColorblindMode;
    global $SET_ShortcutAttackThreshold, $SET_EnableDynamicScaling;
    $rv = "";
    $rv .= "<h2>Settings</h2>";
    $settings = GetSettings($player);
    if($settings[$SET_AlwaysHoldPriority] != 0) $rv .= CreateButton($player, "Auto-pass Priority", 26, $SET_AlwaysHoldPriority . "-0", "24px");
    $rv .= "<BR>";
    if($settings[$SET_AlwaysHoldPriority] != 4) $rv .= CreateButton($player, "Always Pass Priority", 26, $SET_AlwaysHoldPriority . "-4", "24px");
    $rv .= "<BR>";
    if($settings[$SET_AlwaysHoldPriority] != 1) $rv .= CreateButton($player, "Always Hold Priority", 26, $SET_AlwaysHoldPriority . "-1", "24px");
    $rv .= "<BR>";
    if($settings[$SET_AlwaysHoldPriority] != 2) $rv .= CreateButton($player, "Hold Priority All Opp.", 26, $SET_AlwaysHoldPriority . "-2", "24px");
    $rv .= "<BR>";
    if($settings[$SET_AlwaysHoldPriority] != 3) $rv .= CreateButton($player, "Hold Priority Opp. Attacks", 26, $SET_AlwaysHoldPriority . "-3", "24px");
    $rv .= "<BR>";
    $rv .= "<BR>";
    if($settings[$SET_SkipARs] == 0) $rv .= CreateButton($player, "Skip Attack Reactions", 26, $SET_SkipARs . "-1", "24px");
    else if($settings[$SET_SkipARs] == 1) $rv .= CreateButton($player, "Hold Attack Reactions", 26, $SET_SkipARs . "-0", "24px");
    $rv .= "<BR>";
    if($settings[$SET_SkipDRs] == 0) $rv .= CreateButton($player, "Skip Defense Reactions", 26, $SET_SkipDRs . "-1", "24px");
    else if($settings[$SET_SkipDRs] == 1) $rv .= CreateButton($player, "Hold Defense Reactions", 26, $SET_SkipDRs . "-0", "24px");
    $rv .= "<BR>";
    $rv .= "<BR>";
    if($settings[$SET_AutotargetArcane] == 0) $rv .= CreateButton($player, "Arcane auto-target opponent", 26, $SET_AutotargetArcane . "-1", "24px");
    else if($settings[$SET_AutotargetArcane] == 1) $rv .= CreateButton($player, "Arcane manual target", 26, $SET_AutotargetArcane . "-0", "24px");
    $rv .= "<BR>";
    $rv .= "<BR>";
    if($settings[$SET_DarkMode] != 0) $rv .= CreateButton($player, "Normal Mode", 26, $SET_DarkMode . "-0", "24px", "", "", true);
    if($settings[$SET_DarkMode] != 1) $rv .= CreateButton($player, "Dark Mode", 26, $SET_DarkMode . "-1", "24px", "", "", true);
    if($settings[$SET_DarkMode] != 2) $rv .= CreateButton($player, "Plain Mode", 26, $SET_DarkMode . "-2", "24px", "", "", true);
    if($settings[$SET_DarkMode] != 3) $rv .= CreateButton($player, "Dark Plain Mode", 26, $SET_DarkMode . "-3", "24px", "", "", true);
    $rv .= "<BR>";
    $rv .= "<BR>";
    if($settings[$SET_ManualMode] == 0) $rv .= CreateButton($player, "Manual Mode", 26, $SET_ManualMode . "-1", "24px", "", "", true);
    else $rv .= CreateButton($player, "Turn Off Manual Mode", 26, $SET_ManualMode . "-0", "24px", "", "", true);
    $rv .= "<BR>";
    $rv .= "<BR>";
    //if($settings[$SET_ColorblindMode] == 0) $rv .= CreateButton($player, "Turn On color accessibility mode", 26, $SET_ColorblindMode . "-1", "24px", "", "", true);
    //else $rv .= CreateButton($player, "Turn Off color accessibility mode", 26, $SET_ColorblindMode . "-0", "24px", "", "", true);

    if($settings[$SET_ColorblindMode] == 0) $rv .= CreateCheckbox($SET_ColorblindMode . "-1", "Accessibility Mode", 26, false, "Accessibility Mode");
    else $rv .= CreateCheckbox($SET_ColorblindMode . "-0", "Accessibility Mode", 26, true, "Accessibility Mode");
    $rv .= "<BR>";

    if($settings[$SET_EnableDynamicScaling] == 0) $rv .= CreateCheckbox($SET_EnableDynamicScaling . "-1", "Dynamic Scaling (Under Dev)", 26, false, "Dynamic Scaling (Under Dev)");
    else $rv .= CreateCheckbox($SET_EnableDynamicScaling . "-0", "Dynamic Scaling (Under Dev)", 26, true, "Dynamic Scaling (Under Dev)");
    $rv .= "<BR>";

    $currentValue = ShortcutAttackThreshold($player);
    $rv .= "Attack Shortcut Threshold: ";
    $rv .= CreateRadioButton($SET_ShortcutAttackThreshold . "-0", "Never Shortcut", 26, $SET_ShortcutAttackThreshold . "-" . $currentValue, "Never Shortcut");
    $rv .= CreateRadioButton($SET_ShortcutAttackThreshold . "-1", "1 Attack", 26, $SET_ShortcutAttackThreshold . "-" . $currentValue, "1 Attack");
    $rv .= CreateRadioButton($SET_ShortcutAttackThreshold . "-99", "Always Shortcut", 26, $SET_ShortcutAttackThreshold . "-" . $currentValue, "Always Shortcut");
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

?>
