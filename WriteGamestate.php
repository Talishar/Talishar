<?php

if(!isset($filename) || !str_contains($filename, "gamestate.txt")) $filename = "./Games/" . $gameName . "/gamestate.txt";
$dir = dirname($filename);
if (!is_dir($dir)) mkdir($dir, 0700, true);

// Serialize in full before opening any handle. A partial gamestate.txt is
// unrecoverable and readers do not take a shared lock.
$gamestateLines = [
  implode(" ", $playerHealths),
  
  // Player 1
  implode(" ", $p1Hand),
  implode(" ", $p1Deck),
  implode(" ", $p1CharEquip),
  implode(" ", $p1Resources),
  implode(" ", $p1Arsenal),
  implode(" ", $p1Items),
  implode(" ", $p1Auras),
  implode(" ", $p1Discard),
  implode(" ", $p1Pitch),
  implode(" ", $p1Banish),
  implode(" ", $p1ClassState),
  implode(" ", $p1CharacterEffects),
  implode(" ", $p1Soul),
  implode(" ", $p1CardStats),
  implode(" ", $p1TurnStats),
  implode(" ", $p1Allies),
  implode(" ", $p1Permanents),
  implode(" ", $p1Settings),
  
  // Player 2
  implode(" ", $p2Hand),
  implode(" ", $p2Deck),
  implode(" ", $p2CharEquip),
  implode(" ", $p2Resources),
  implode(" ", $p2Arsenal),
  implode(" ", $p2Items),
  implode(" ", $p2Auras),
  implode(" ", $p2Discard),
  implode(" ", $p2Pitch),
  implode(" ", $p2Banish),
  implode(" ", $p2ClassState),
  implode(" ", $p2CharacterEffects),
  implode(" ", $p2Soul),
  implode(" ", $p2CardStats),
  implode(" ", $p2TurnStats),
  implode(" ", $p2Allies),
  implode(" ", $p2Permanents),
  implode(" ", $p2Settings),
  
  implode(" ", $landmarks),
  $winner,
  $firstPlayer,
  $currentPlayer,
  $currentTurn,
  implode(" ", $turn),
  $actionPoints,
  implode(" ", $combatChain),
  implode(" ", $combatChainState),
  implode(" ", $currentTurnEffects),
  implode(" ", $currentTurnEffectsFromCombat),
  implode(" ", $nextTurnEffects),
  implode(" ", $decisionQueue),
  implode(" ", $dqVars),
  implode(" ", $dqState),
  implode(" ", $layers),
  implode(" ", $layerPriority),
  $mainPlayer,
  implode(" ", $lastPlayed),
  count($chainLinks),
];

// Add chain links
$chainLinksCount = count($chainLinks);
for ($i = 0; $i < $chainLinksCount; ++$i) {
  if (isset($chainLinks[$i]) && is_array($chainLinks[$i]))
    $gamestateLines[] = implode(" ", $chainLinks[$i]);
  else
    $gamestateLines[] = "";
}

array_push($gamestateLines,
  implode(" ", $chainLinkSummary),
  $p1Key,
  $p2Key,
  $permanentUniqueIDCounter,
  $inGameStatus, // Game status -- 0 = START, 1 = PLAY, 2 = OVER
  "", // Animations - Deprecated
  $currentPlayerActivity, //Not Used - Current Player activity status -- 0 = active, 2 = inactive
  "", // Unused
  "", // Unused
  $p1TotalTime, // Player 1 total time
  $p2TotalTime, // Player 2 total time
  $lastUpdateTime, // Last update time
  $roguelikeGameID, // Roguelike game ID
  implode(" ", $events), // Events
  $EffectContext, // Update number the gamestate is for
  implode(" ", $p1Inventory),
  implode(" ", $p2Inventory),
  $p1IsAI,
  $p2IsAI,
  $AIHasInfiniteHP == "1" ? "1" : "0",
  json_encode($p1CardTurnLog),
  json_encode($p2CardTurnLog),
  implode(" ", is_array($attackQueue ?? null) ? $attackQueue : []),
  json_encode($p1LifeHistory ?? []),
  json_encode($p2LifeHistory ?? []),
  json_encode($p1ArcaneDamageDealt ?? []),
  json_encode($p2ArcaneDamageDealt ?? []),
  max(0, min(100, intval($practiceDummyWeaponPower ?? 4)))
);

$gamestateContent = implode("\r\n", $gamestateLines) . "\r\n";

$previousAbortSetting = ignore_user_abort(true);
$previousTimeLimit = ini_get('max_execution_time');
@set_time_limit(0);

$restoreExecutionGuards = static function () use ($previousAbortSetting, $previousTimeLimit) {
  ignore_user_abort($previousAbortSetting);
  if ($previousTimeLimit !== false) @set_time_limit((int)$previousTimeLimit);
};

$lockPath = $dir . "/gamestate.lock";
$lockHandler = fopen($lockPath, "c");

if ($lockHandler === false) {
  $restoreExecutionGuards();
  error_log("ERROR: Failed to open gamestate lock file: " . $lockPath . " (from game: " . $gameName . ")");
  exit;
}

if (!flock($lockHandler, LOCK_EX)) {
  fclose($lockHandler);
  $restoreExecutionGuards();
  error_log("ERROR: WriteGamestate could not lock " . $lockPath . " — action not persisted (game: " . $gameName . ")");
  exit;
}

$tempPath = $filename . "." . getmypid() . "." . uniqid("", true) . ".tmp";
$writeSucceeded = false;
$handler = fopen($tempPath, "wb");

if ($handler === false) {
  error_log("ERROR: Failed to open temp gamestate file: " . $tempPath . " (from game: " . $gameName . ")");
} else {
  $bytesWritten = fwrite($handler, $gamestateContent);
  // Flush before close so a short write is caught before we rename over a good file.
  $flushed = fflush($handler);
  fclose($handler);

  if ($bytesWritten === strlen($gamestateContent) && $flushed) {
    if (rename($tempPath, $filename)) {
      $writeSucceeded = true;
    } else {
      error_log("ERROR: Failed to move gamestate into place: " . $tempPath . " -> " . $filename . " (game: " . $gameName . ")");
    }
  } else {
    error_log("ERROR: Short write to temp gamestate file: " . $tempPath . " (game: " . $gameName . ")");
  }

  if (!$writeSucceeded && file_exists($tempPath)) @unlink($tempPath);
}

flock($lockHandler, LOCK_UN);
fclose($lockHandler);
$restoreExecutionGuards();

if (!$writeSucceeded) exit;

WriteGamestateCache($gameName, $gamestateContent);

$lastWrittenGamestate = $gamestateContent;
