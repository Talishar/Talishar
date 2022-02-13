<?php

  $SET_AlwaysHoldPriority = 0;
  $SET_TryUI2 = 1;
  $SET_DarkMode = 2;
  $SET_ManualMode = 3;

  function AlwaysHoldPriority($player)
  {
    global $SET_AlwaysHoldPriority;
    $settings = GetSettings($player);
    return $settings[$SET_AlwaysHoldPriority] == 1;
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
    return $settings[$SET_DarkMode];
  }

  function IsManualMode($player)
  {
    global $SET_ManualMode;
    $settings = GetSettings($player);
    return $settings[$SET_ManualMode];
  }

  function ChangeSetting($player, $setting, $value)
  {
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
    global $SET_AlwaysHoldPriority, $SET_TryUI2, $SET_DarkMode, $SET_ManualMode;
    $rv = "";
    $settings = GetSettings($player);
    if($settings[$SET_AlwaysHoldPriority] == 0) $rv .= CreateButton($player, "Always Hold Priority", 26, $SET_AlwaysHoldPriority . "-1", "24px");
    else $rv .= CreateButton($player, "Auto-Pass All Layers", 26, $SET_AlwaysHoldPriority . "-0", "24px");
    $rv .= "<BR>";
    if($settings[$SET_DarkMode] == 0) $rv .= CreateButton($player, "Dark Mode", 26, $SET_DarkMode . "-1", "24px");
    else $rv .= CreateButton($player, "Normal Mode", 26, $SET_DarkMode . "-0", "24px");
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
