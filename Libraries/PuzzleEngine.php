<?php

// Loads the engine for endpoints that run puzzle games (proofs, the bot baseline, the survive script).
// Include it from the endpoint's top level: the engine keeps its state in globals, resolves includes from the
// game root, and its files define globals at top level, so endpoint logic belongs inside functions.
chdir(__DIR__ . "/..");
include_once "WriteLog.php";
include_once "GameLogic.php";
include_once "GameTerms.php";
include_once "HostFiles/Redirector.php";
include_once "Libraries/SHMOPLibraries.php";
include_once "Libraries/StatFunctions.php";
include_once "Libraries/UILibraries.php";
include_once "Libraries/PlayerSettings.php";
include_once "Libraries/NetworkingLibraries.php";
include_once "Libraries/CacheLibraries.php";
include_once "Libraries/PromptLog.php";
include_once "includes/MetafyHelper.php";
include_once "AI/CombatDummy.php";
include_once "Libraries/HTTPLibraries.php";
include_once "Libraries/ReplayLibraries.php";
require_once "Libraries/CoreLibraries.php";
include_once "APIKeys/APIKeys.php";
include_once "Libraries/ValidationLibraries.php";
include_once "Libraries/PuzzleHarvest.php";
include_once "Libraries/PuzzleVerify.php";
include_once "Libraries/PuzzlePlay.php";
