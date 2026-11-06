<?php

// Runs in its own PHP process, because tests/Engine stubs out engine functions that this needs for real.
// Plays out Gauntlets of Iron Will scenarios against in-memory state and prints the results as JSON. Nothing is saved.

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');

$workDir = sys_get_temp_dir() . '/talishar_gauntlets_' . getmypid() . '/';
mkdir($workDir, 0777, true);
file_put_contents($workDir . 'gamestate.txt', str_repeat("\r\n", 120));
register_shutdown_function(function () use ($workDir) {
  @unlink($workDir . 'gamestate.txt');
  @rmdir($workDir);
});

$gameName = "0";
$filename = $workDir . 'gamestate.txt';
$filepath = $workDir;
chdir(dirname(__DIR__, 3));

function WriteLog($text, $playerColor = 0, $highlight = false, $path = "./", $highlightColor = "brown") {}
function FlushLogBuffer() {}

include "GameLogic.php";
include "GameTerms.php";
include_once "Libraries/SHMOPLibraries.php";
include "Libraries/StatFunctions.php";
include "Libraries/UILibraries.php";
include "Libraries/PlayerSettings.php";
include "Libraries/NetworkingLibraries.php";
include "Libraries/CacheLibraries.php";
include "AI/CombatDummy.php";
include "Libraries/HTTPLibraries.php";
require_once "Libraries/CoreLibraries.php";
include "ParseGamestate.php";

function ChainEntry($cardID, $player, $from, $uniqueID) {
  return [$cardID, $player, $from, 0, 0, 0, 0, $uniqueID, $uniqueID, $cardID, "-", "-"];
}

// Starts a chain link where the Gauntlets have blocked the attack and their trigger has resolved.
function BlockedAttack($attackID) {
  global $mainPlayer, $defPlayer, $currentPlayer, $combatChain, $currentTurnEffects, $chainLinks, $turn, $layers, $decisionQueue;
  $mainPlayer = 1;
  $defPlayer = 2;
  $currentPlayer = 1;
  $turn = ["A", 1, "-"];
  $layers = [];
  $decisionQueue = [];
  $chainLinks = [];
  $currentTurnEffects = [];
  ResetCombatChainState();
  $combatChain = array_merge(ChainEntry($attackID, 1, "HAND", 1001), ChainEntry("gauntlets_of_iron_will", 2, "EQUIP", 2001));
  CacheCombatResult();
}

function GauntletsTrigger() {
  CacheCombatResult(); // a real game refreshes this at the end of every request, before the trigger resolves
  (new gauntlets_of_iron_will(2))->ProcessTrigger(0);
  CacheCombatResult();
}

function Buff($amount) {
  global $CombatChain;
  $CombatChain->AttackCard()->ModifyPower($amount);
}

// The real Uzuri ability, the post-play check, then any triggers it queued, through the engine's ProcessTrigger.
function UzuriSwap($newAttackID) {
  global $layers;
  $banish = &GetBanish(1);
  $banish = array_pad([$newAttackID, "UZURI"], BanishPieces(), "-");
  PlayAbility("uzuri_switchblade", "PLAY", 0);
  CurrentEffectAfterPlayOrActivateAbility();
  $afterSwap = Snapshot();
  $pieces = LayerPieces();
  for ($i = count($layers) - $pieces; $i >= 0; $i -= $pieces) {
    ProcessTrigger($layers[$i + 1], $layers[$i + 2], $layers[$i + 6] ?? "-", $layers[$i + 3] ?? "-", $layers[$i + 4] ?? "-");
    array_splice($layers, $i, $pieces);
  }
  return $afterSwap;
}

// The cache refresh mirrors Libraries/GameFinalization.php, which runs it before every save.
function Snapshot() {
  global $currentTurnEffects;
  CacheCombatResult();
  $effects = [];
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) $effects[] = $currentTurnEffects[$i];
  return ["power" => (int)CachedTotalPower(), "effects" => $effects];
}

$results = [];

BlockedAttack("wounding_blow_red");
GauntletsTrigger();
Buff(2);
$results["buffWithoutSwap"] = Snapshot();

BlockedAttack("flip_the_script_blue");
GauntletsTrigger();
$results["flipTheScriptAfterSwap"] = UzuriSwap("command_and_conquer_red");
$results["flipTheScriptAfterTrigger"] = Snapshot();

BlockedAttack("wounding_blow_red");
GauntletsTrigger();
UzuriSwap("ravenous_rabble_red");
Buff(2);
$results["buffAfterSwap"] = Snapshot();

BlockedAttack("wounding_blow_red");
AddCurrentTurnEffect("marked", 1);
Buff(3);
GauntletsTrigger();
$results["staleBaselineAfterSwap"] = UzuriSwap("ravenous_rabble_red");
Buff(1);
$results["staleBaselineAfterBuff"] = Snapshot();

BlockedAttack("wounding_blow_red");
GauntletsTrigger();
Buff(1);
UzuriSwap("ravenous_rabble_red");
Buff(2);
$results["activeSwappedOut"] = Snapshot();

BlockedAttack("wounding_blow_red");
Buff(-1);
GauntletsTrigger();
Buff(1);
$results["negativeBaseline"] = Snapshot();

BlockedAttack("wounding_blow_red");
GauntletsTrigger();
$currentPlayer = 2;
Buff(2);
$results["buffDuringDefenderPriority"] = Snapshot();

BlockedAttack("wounding_blow_red");
AddCurrentTurnEffect("marked", 1);
GauntletsTrigger();
AddCurrentTurnEffect("marked", 2);
Buff(1);
CleanUpCombatEffects();
$results["linkResolvesWhileActive"] = Snapshot();

echo json_encode($results);
