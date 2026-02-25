<?php

/**
 * Moderator List Configuration
 * Centralized list of moderators and editors
 * Update this file to add or remove moderators/editors
 */

function GetModeratorList() {
  return [
    "OotTheMonk",
    "LaustinSpayce",
    "Tower",
    "PvtVoid",
    "Aegisworn",
    "Bluffkin"
  ];
}

function GetCardEditorList() {
  return [
    "OotTheMonk",
    "LaustinSpayce",  
    "Tower",
    "PvtVoid",
    "thatzachary",
    "DKGaming",
    "Bluffkin"
  ];
}

function IsUserModerator($useruid) {
  $modList = GetModeratorList();
  return in_array($useruid, $modList);
}

function IsCardEditor($useruid) {
  $editorList = GetCardEditorList();
  return in_array($useruid, $editorList);
}

?>

