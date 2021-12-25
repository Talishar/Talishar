<?php

  $SET_AlwaysHoldPriority = 0;

  function AlwaysHoldPriority($player)
  {
    global $SET_AlwaysHoldPriority;
    $settings = GetSettings($player);
    return $settings[$SET_AlwaysHoldPriority] == 1;
  }

  function ChangeSetting($player, $setting, $value)
  {
    $settings = &GetSettings($player);
    $settings[$setting] = $value;
  }

  function GetSettingsUI($player)
  {
    global $SET_AlwaysHoldPriority;
    $rv = "";
    $settings = GetSettings($player);
    if($settings[$SET_AlwaysHoldPriority] == 0) $rv .= CreateButton($player, "Always Hold Priority", 26, $SET_AlwaysHoldPriority . "-1", "24px");
    else $rv .= CreateButton($player, "Auto-Pass Priority", 26, $SET_AlwaysHoldPriority . "-0", "24px");
    return $rv;
  }

?>