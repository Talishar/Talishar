<?php

// Define constants for repeated values
const DEFAULT_CLASS_STATE = "0 0 0 0 0 0 0 0 DOWN 0 -1 0 0 0 0 0 0 -1 0 0 0 0 NA 0 0 0 - -1 0 0 0 0 0 0 - 0 0 0 0 0 0 0 0 - - 0 -1 0 0 0 0 0 - 0 0 0 0 0 -1 0 - 0 0 - 0 0 0 0 0 0 0 0 0 0 0 - 0 0 0 0 0 0 0 - 0 0 0 0 0 0 0 0 - 0 0 0 0 0 0";

function initializePlayerState($handler, $deckHandler, int $player): void
{
  global $p1IsPatron, $p2IsPatron, $p1IsChallengeActive, $p2IsChallengeActive, $p1id, $p2id;
  global $SET_Mute, $SET_IsPatron, $p1Inventory, $p2Inventory;

  // Read initial arrays
  $charEquip = GetArray($deckHandler);
  $deckCards = GetArray($deckHandler);
  $inventory = GetArray($deckHandler);

  // Set inventory based on player
  if($player == 1) {
    $p1Inventory = $inventory;
    $p1IsChallengeActive = "0";
  } else {
    $p2Inventory = $inventory;
    $p2IsChallengeActive = "0";
  }

  // Write initial state
  fwrite($handler, "\r\n"); // Hand
  fwrite($handler, implode(" ", $deckCards) . "\r\n");

  // Process character and equipment
  $hero = "";
  $equipmentLines = [];
  foreach ($charEquip as $i => $equip) {
    if(TypeContains($equip, "C")) $hero = $equip;
    $equipmentLines[] = $equip . " 2 0 0 0 " . CharacterNumUsesPerTurn($equip) . " 0 0 0 " . 
                       CharacterDefaultActiveState($equip) . " - " . GetUniqueId() . " " . 
                       HasCloaked($equip, hero:$hero) . " 0 0";
  }
  fwrite($handler, implode(" ", $equipmentLines) . "\r\n");

  // Write static game state sections
  $staticSections = [
    "0 0\r\n", // Resources float/needed
    "\r\n", // Arsenal
    "\r\n", // Item
    "\r\n", // Aura
    "\r\n", // Discard
    "\r\n", // Pitch
    "\r\n", // Banish
    DEFAULT_CLASS_STATE . "\r\n", // Class State
    "\r\n", // Character effects
    "\r\n", // Soul
    "\r\n", // Card Stats
    "\r\n", // Turn Stats
    "\r\n", // Allies
    "\r\n"  // Permanents
  ];
  fwrite($handler, implode("", $staticSections));

  // Process settings
  $holdPriority = "0";
  $isPatron = ($player == 1 ? $p1IsPatron : $p2IsPatron) ?: "0";
  $mute = 0;
  $userId = ($player == 1 ? $p1id : $p2id);
  $savedSettings = LoadSavedSettings($userId);
  
  $settingArray = array_fill(0, 27, "0");
  $settingArray[$SET_Mute] = $mute;
  $settingArray[$SET_IsPatron] = $isPatron;
  
  // Apply saved settings
  for($i = 0; $i < count($savedSettings); $i += 2) {
    $settingArray[$savedSettings[$i]] = $savedSettings[$i + 1];
  }
  
  fwrite($handler, implode(" ", $settingArray) . "\r\n");
}

function GetArray($handler): array
{
  $line = trim(fgets($handler));
  return $line === "" ? [] : explode(" ", $line);
}
