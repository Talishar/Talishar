<?php

  $SET_AlwaysHoldPriority = 0;
  $SET_TryUI2 = 1;

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

  function ChangeSetting($player, $setting, $value)
  {
    $settings = &GetSettings($player);
    $settings[$setting] = $value;
  }

  function GetSettingsUI($player)
  {
    global $SET_AlwaysHoldPriority, $SET_TryUI2;
    $rv = "";
    $settings = GetSettings($player);
    if($settings[$SET_AlwaysHoldPriority] == 0) $rv .= CreateButton($player, "Always Hold Priority", 26, $SET_AlwaysHoldPriority . "-1", "24px");
    else $rv .= CreateButton($player, "Auto-Pass Layers", 26, $SET_AlwaysHoldPriority . "-0", "24px");
    if($settings[$SET_TryUI2] == 0) $rv .= CreateButton($player, "Try New UI", 26, $SET_TryUI2 . "-1", "24px");
    else $rv .= CreateButton($player, "Use Default UI", 26, $SET_TryUI2 . "-0", "24px");
    return $rv;
  }

?>