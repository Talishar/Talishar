<?php

function initializePlayerState($handler, $deckHandler, $player)
{
  global $p1IsPatron, $p2IsPatron, $p1IsChallengeActive, $p2IsChallengeActive, $p1id, $p2id;
  global $SET_AlwaysHoldPriority, $SET_TryUI2, $SET_DarkMode, $SET_ManualMode, $SET_SkipARs, $SET_SkipDRs, $SET_PassDRStep, $SET_AutotargetArcane;
  global $SET_ColorblindMode, $SET_EnableDynamicScaling, $SET_Mute, $SET_Cardback, $SET_IsPatron;
  global $SET_MuteChat, $SET_DisableStats, $SET_CasterMode, $SET_Language;
  $charEquip = GetArray($deckHandler);
  $deckCards = GetArray($deckHandler);
  $deckSize = count($deckCards);
  fwrite($handler, "\r\n"); //Hand

  if($player == 1) $p1IsChallengeActive = "0";
  else if($player == 2) $p2IsChallengeActive = "0";

  //Equipment challenge
  /*
  if($charEquip[0] != "ARC001" && $charEquip[0] != "ARC002" && $charEquip[1] == "CRU177")
  {
    if($player == 1) $p1IsChallengeActive = "1";
    else if($player == 2) $p2IsChallengeActive = "1";
  }
  */
/*
  $challengeThreshold = (CharacterHealth($charEquip[0]) > 25 ? 6 : 4);
  $numChallengeCard = 0;
  for($i=0; $i<count($deckCards); ++$i)
  {
    if($deckCards[$i] == "ARC185") ++$numChallengeCard;
    if($deckCards[$i] == "ARC186") ++$numChallengeCard;
    if($deckCards[$i] == "ARC187") ++$numChallengeCard;
  }
  if($player == 1 && $numChallengeCard >= $challengeThreshold) $p1IsChallengeActive = "1";
  else if($player == 2 && $numChallengeCard >= $challengeThreshold) $p2IsChallengeActive = "1";
*/
  fwrite($handler, implode(" ", $deckCards) . "\r\n");

  for ($i = 0; $i < count($charEquip); ++$i) {
    fwrite($handler, $charEquip[$i] . " 2 0 0 0 " . CharacterNumUsesPerTurn($charEquip[$i]) . " 0 0 0 " . CharacterDefaultActiveState($charEquip[$i]) . ($i < count($charEquip) - 1 ? " " : "\r\n"));
  }
  //Character and equipment. First is ID. Four numbers each. First is status (0=Destroy/unavailable, 1=Used, 2=Unused, 3=Disabled). Second is num counters
  //Third is attack modifier, fourth is block modifier
  //Order: Character, weapon 1, weapon 2, head, chest, arms, legs
  fwrite($handler, "0 0\r\n"); //Resources float/needed
  fwrite($handler, "\r\n"); //Arsenal
  fwrite($handler, "\r\n"); //Item
  fwrite($handler, "\r\n"); //Aura
  fwrite($handler, "\r\n"); //Discard
  fwrite($handler, "\r\n"); //Pitch
  fwrite($handler, "\r\n"); //Banish
  fwrite($handler, "0 0 0 0 0 0 0 0 DOWN 0 -1 0 0 0 0 0 0 -1 0 0 0 0 NA 0 0 0 - -1 0 0 0 0 0 0 - 0 0 0 0 0 0 - 0 - - 0 -1 0 0 0 0 0 - 0 0 0 0 0 -1 0 - 0 0 - 0 0\r\n"); //Class State
  fwrite($handler, "\r\n"); //Character effects
  fwrite($handler, "\r\n"); //Soul
  fwrite($handler, "\r\n"); //Card Stats
  fwrite($handler, "\r\n"); //Turn Stats
  fwrite($handler, "\r\n"); //Allies
  fwrite($handler, "\r\n"); //Permanents
  $holdPriority = "0"; //Auto-pass layers
  $isPatron = ($player == 1 ? $p1IsPatron : $p2IsPatron);
  if($isPatron == "") $isPatron = "0";
  $mute = 0;
  $userId = ($player == 1 ? $p1id : $p2id);
  $savedSettings = LoadSavedSettings($userId);
  $settingArray = [];
  for($i=0; $i<=23; ++$i)
  {
    $value = "";
    switch($i)
    {
      case $SET_Mute: $value = $mute; break;
      case $SET_IsPatron: $value = $isPatron; break;
      default: $value = SettingDefaultValue($i, $charEquip[0]); break;
    }
    array_push($settingArray, $value);
  }
  for($i=0; $i<count($savedSettings); $i+=2)
  {
    $settingArray[$savedSettings[intval($i)]] = $savedSettings[intval($i)+1];
  }
  fwrite($handler, implode(" ", $settingArray) . "\r\n"); //Settings
}

function SettingDefaultValue($setting, $hero)
{
  global $SET_AlwaysHoldPriority, $SET_TryUI2, $SET_DarkMode, $SET_ManualMode, $SET_SkipARs, $SET_SkipDRs, $SET_PassDRStep, $SET_AutotargetArcane;
  global $SET_ColorblindMode, $SET_EnableDynamicScaling, $SET_Mute, $SET_Cardback, $SET_IsPatron;
  global $SET_MuteChat, $SET_DisableStats, $SET_CasterMode, $SET_Language, $SET_Playmat;
  switch($setting)
  {
    case $SET_TryUI2: return "1";
    case $SET_AutotargetArcane: return "1";
    case $SET_Playmat: return ($hero == "DUMMY" ? 8 : 0);
    default: return "0";
  }
}

function GetArray($handler)
{
  $line = trim(fgets($handler));
  if ($line == "") return [];
  return explode(" ", $line);
}

?>
