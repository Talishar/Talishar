<?php

function initializePlayerState($handler, $deckHandler, $player)
{
  global $p1IsPatron, $p2IsPatron, $p1IsChallengeActive, $p2IsChallengeActive, $p1id, $p2id;
  global $SET_Mute, $SET_IsPatron, $p1Inventory, $p2Inventory;
  $charEquip = GetArray($deckHandler);
  $deckCards = GetArray($deckHandler);
  // Lines 3-11 are sideboard slots (headSB, chestSB, armsSB, legsSB, offhandSB,
  for ($i = 0; $i < 9; $i++) GetArray($deckHandler);
  $inventory = GetArray($deckHandler);
  if($player == 1) $p1Inventory = $inventory;
  else $p2Inventory = $inventory;
  fwrite($handler, "\r\n"); //Hand

  if($player == 1) $p1IsChallengeActive = "0";
  else if($player == 2) $p2IsChallengeActive = "0";

  fwrite($handler, implode(" ", $deckCards) . "\r\n");

  $hero = "";
  $charEquipCount = count($charEquip);
  $equipParts = [];
  for ($i = 0; $i < $charEquipCount; ++$i) {
    if(TypeContains($charEquip[$i], "C")) $hero = $charEquip[$i];
    if (IsModular($charEquip[$i])) $charEquip[$i] = "NONE00";
    $equipParts[] = $charEquip[$i] . " 2 0 0 0 " . CharacterNumUsesPerTurn($charEquip[$i]) . " 0 0 0 " . CharacterDefaultActiveState($charEquip[$i]) . " - " . GetUniqueId() . " " . HasCloaked($charEquip[$i], hero:$hero) . " 0 0";
  }
  if ($charEquipCount > 0) {
    fwrite($handler, implode(" ", $equipParts) . "\r\n");
  }
  //Character and equipment. First is ID. Four numbers each. First is status (0=Destroy/unavailable, 1=Used, 2=Unused, 3=Disabled). Second is num counters
  //Third is power modifier, fourth is block modifier
  //Order: Character, weapon 1, weapon 2, head, chest, arms, legs

  // Batch all static init lines into one write to cut 13 syscalls down to 1.
  fwrite($handler,
    "0 0\r\n" .    //Resources float/needed
    "\r\n" .        //Arsenal
    "\r\n" .        //Item
    "\r\n" .        //Aura
    "\r\n" .        //Discard
    "\r\n" .        //Pitch
    "\r\n" .        //Banish
    "0 0 0 0 0 0 0 0 DOWN 0 -1 0 0 0 0 0 0 -1 0 0 0 0 NA 0 0 0 - -1 0 0 0 0 0 0 - 0 0 0 0 0 0 0 0 - - 0 -1 0 0 0 0 0 - 0 0 0 0 0 -1 0 - 0 0 - 0 0 0 0 0 0 0 0 0 0 0 - 0 0 0 0 0 0 0 - 0 0 0 0 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0 0 0 - 0 0 0 0 0 0 0 0 0 0 0 0 0 - - 0 0 0 0 0 0 0 0\r\n" .  //Class State
    "\r\n" .        //Character effects
    "\r\n" .        //Soul
    "\r\n" .        //Card Stats
    "\r\n" .        //Turn Stats
    "\r\n" .        //Allies
    "\r\n"          //Permanents
  );
  $holdPriority = "0"; //Auto-pass layers
  $isPatron = ($player == 1 ? $p1IsPatron : $p2IsPatron) ?: "0";
  $mute = 0;
  $userId = ($player == 1 ? $p1id : $p2id);
  $savedSettings = LoadSavedSettings($userId);
  $settingArray = [];
  for($i=0; $i<=32; ++$i) // Settings: This need to go up when we put a new settings
  {
    $settingArray[] = SettingDefaultValue($i, $charEquip[0]);
  }
  $settingArray[$SET_Mute] = $mute;
  $settingArray[$SET_IsPatron] = $isPatron;
  $savedSettingsCount = count($savedSettings);
  for($i=0; $i<$savedSettingsCount; $i+=2)
  {
    $settingArray[$savedSettings[$i]] = $savedSettings[$i+1]; 
  }
  fwrite($handler, implode(" ", $settingArray) . "\r\n"); //Settings
}

function SettingDefaultValue($setting, $hero)
{
  global $SET_TryUI2, $SET_AutotargetArcane, $SET_Playmat, $SET_MirroredBoardLayout;
  switch($setting)
  {
    case $SET_TryUI2: return "1";
    case $SET_AutotargetArcane: return "1";
    case $SET_Playmat: return $hero == "DUMMY" ? 2 : 0;
    case $SET_MirroredBoardLayout: return "1";
    default: return "0";
  }
}

function GetArray($handler)
{
  $line = trim(fgets($handler));
  if ($line === "") return [];
  return explode(" ", $line);
}
