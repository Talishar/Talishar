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

function GetContributorList() {
  return [
    "sugitime",
    "OotTheMonk",
    "Launch",
    "LaustinSpayce",
    "Star_Seraph",
    "Tower",
    "Etasus",
    "scary987",
    "Celenar",
    "DKGaming",
    "Aegisworn",
    "PvtVoid",
    "Bluffkin",
    "Bluffkin1"
  ];
}

function IsUserContributor($useruid) {
  static $contributorMap = null;
  if ($contributorMap === null) {
    $contributorMap = array_flip(GetContributorList());
  }
  return $useruid !== null && $useruid !== "" && isset($contributorMap[$useruid]);
}

function IsUserModerator($useruid) {
  static $modMap = null;
  if ($modMap === null) {
    $modMap = array_flip(GetModeratorList());
  }
  return isset($modMap[$useruid]);
}

function IsCardEditor($useruid) {
  static $editorMap = null;
  if ($editorMap === null) {
    $editorMap = array_flip(GetCardEditorList());
  }
  return isset($editorMap[$useruid]);
}

