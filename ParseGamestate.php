<?php

require_once __DIR__ . '/Libraries/GamestateCompatibility.php';
require_once __DIR__ . '/Constants.php'; // ClassStateFromString
require_once __DIR__ . '/Libraries/HandInstanceIDs.php';
require_once __DIR__ . '/Libraries/RollbackStates.php';

global $gameName;
function GetStringArray($line)
{
  $line = trim($line);
  if($line == "") return [];
  return explode(" ", $line);
}

if(!is_numeric($gameName)) exit;
if(!isset($filename) || !str_contains($filename, "gamestate.txt")) $filename = "./Games/" . $gameName . "/gamestate.txt";
if(!isset($filepath)) $filepath = "./Games/" . $gameName . "/";

ParseGamestate($parseGamestateHistoricalStats ?? true);

function GamestateSanitize($input)
{
  return str_replace([",", " "], ["<44>", "_"], $input);
}

function GamestateUnsanitize($input)
{
  return str_replace(["<44>", "_"], [",", " "], $input);
}

function ParseGamestate($parseHistoricalStats = true)
{
  global $gameName, $playerHealths;
  global $p1Hand, $p1Deck, $p1CharEquip, $p1Resources, $p1Arsenal, $p1Items, $p1Auras, $p1Discard, $p1Pitch, $p1Banish;
  global $p1ClassState, $p1CharacterEffects, $p1Soul, $p1CardStats, $p1TurnStats, $p1Allies, $p1Permanents, $p1Settings;
  global $p2Hand, $p2Deck, $p2CharEquip, $p2Resources, $p2Arsenal, $p2Items, $p2Auras, $p2Discard, $p2Pitch, $p2Banish;
  global $p2ClassState, $p2CharacterEffects, $p2Soul, $p2CardStats, $p2TurnStats, $p2Allies, $p2Permanents, $p2Settings;
  global $p1CardTurnLog, $p2CardTurnLog, $p1LifeHistory, $p2LifeHistory, $p1ArcaneDamageDealt, $p2ArcaneDamageDealt;
  global $p1ContractsCompleted, $p2ContractsCompleted, $p1CardsDisrupted, $p2CardsDisrupted;
  global $landmarks, $winner, $firstPlayer, $currentPlayer, $currentTurn, $turn, $actionPoints, $combatChain, $combatChainState;
  global $currentTurnEffects, $currentTurnEffectsFromCombat, $nextTurnEffects, $decisionQueue, $dqVars, $dqState;
  global $layers, $layerPriority, $mainPlayer, $defPlayer, $lastPlayed, $chainLinks, $chainLinkSummary, $p1Key, $p2Key;
  global $permanentUniqueIDCounter, $inGameStatus, $animations, $currentPlayerActivity;
  global $p1TotalTime, $p2TotalTime, $lastUpdateTime, $events, $EffectContext;
  global $mainPlayerGamestateStillBuilt, $mpgBuiltFor, $myStateBuiltFor, $playerID;
  global $p1Inventory, $p2Inventory, $p1IsAI, $p2IsAI, $AIHasInfiniteHP, $attackQueue, $practiceDummyWeaponPower;
  global $p1TurnCount, $p2TurnCount, $handInstanceIDs;

  $mainPlayerGamestateStillBuilt = 0;
  $mpgBuiltFor = -1;
  $myStateBuiltFor = -1;

  // explode once; avoids a redundant O(n) substr_count scan on the same string
  $gamestateContent = explode("\r\n", ReadGamestateCache($gameName));
  $gamestateLineCount = count($gamestateContent);
  if ($gamestateLineCount < 60) {
    global $filename;
    $gsFile = (isset($filename) && str_contains($filename, "gamestate.txt"))
      ? $filename : "./Games/" . $gameName . "/gamestate.txt";
    $fileContent = @file_get_contents($gsFile);
    if ($fileContent !== false) {
      $gamestateContent = explode("\r\n", $fileContent);
      $gamestateLineCount = count($gamestateContent);
      if ($gamestateLineCount >= 60) {
        WriteGamestateCache($gameName, $fileContent);
      }
    }
  }
  if ($gamestateLineCount < 60) exit;

  $playerHealths = GetStringArray($gamestateContent[0]); // 1

  //Player 1
  $p1Hand = GetStringArray($gamestateContent[1]); // 2
  $p1Deck = GetStringArray($gamestateContent[2]); // 3
  $p1CharEquip = GetStringArray($gamestateContent[3]); // 4
  $p1CharEquip = NormalizeLegacyCharacterState($p1CharEquip);
  $p1Resources = GetStringArray($gamestateContent[4]); // 5
  $p1Arsenal = GetStringArray($gamestateContent[5]); // 6
  $p1Items = GetStringArray($gamestateContent[6]); // 7
  $p1Auras = GetStringArray($gamestateContent[7]); // 8
  $p1Discard = GetStringArray($gamestateContent[8]); // 9
  $p1Pitch = GetStringArray($gamestateContent[9]); // 10
  $p1Banish = GetStringArray($gamestateContent[10]); // 11
  $p1ClassState = ClassStateFromString($gamestateContent[11]); // 12
  $p1CharacterEffects = GetStringArray($gamestateContent[12]); // 13
  $p1Soul = GetStringArray($gamestateContent[13]); // 14
  $p1CardStats = GetStringArray($gamestateContent[14]); // 15
  $p1TurnStats = GetStringArray($gamestateContent[15]); // 16
  $p1Allies = GetStringArray($gamestateContent[16]); // 17
  $p1Permanents = GetStringArray($gamestateContent[17]); // 18
  $p1Settings = GetStringArray($gamestateContent[18]); // 19

  //Player 2
  $p2Hand = GetStringArray($gamestateContent[19]); // 20
  $p2Deck = GetStringArray($gamestateContent[20]); // 21
  $p2CharEquip = GetStringArray($gamestateContent[21]); // 22
  $p2CharEquip = NormalizeLegacyCharacterState($p2CharEquip);
  $p2Resources = GetStringArray($gamestateContent[22]); // 23
  $p2Arsenal = GetStringArray($gamestateContent[23]); // 24
  $p2Items = GetStringArray($gamestateContent[24]); // 25
  $p2Auras = GetStringArray($gamestateContent[25]); // 26
  $p2Discard = GetStringArray($gamestateContent[26]); // 27
  $p2Pitch = GetStringArray($gamestateContent[27]); // 28
  $p2Banish = GetStringArray($gamestateContent[28]); // 29
  $p2ClassState = ClassStateFromString($gamestateContent[29]); // 30
  $p2CharacterEffects = GetStringArray($gamestateContent[30]); // 31
  $p2Soul = GetStringArray($gamestateContent[31]); // 32
  $p2CardStats = GetStringArray($gamestateContent[32]); // 33
  $p2TurnStats = GetStringArray($gamestateContent[33]); // 34
  $p2Allies = GetStringArray($gamestateContent[34]); // 35
  $p2Permanents = GetStringArray($gamestateContent[35]); // 36
  $p2Settings = GetStringArray($gamestateContent[36]); // 37

  $landmarks = GetStringArray($gamestateContent[37]);
  $winner = trim($gamestateContent[38]);
  $firstPlayer = trim($gamestateContent[39]);
  $currentPlayer = trim($gamestateContent[40]);
  $currentTurn = trim($gamestateContent[41]);
  $turn = GetStringArray($gamestateContent[42]);
  $turn[2] ??= "";
  $actionPoints = trim($gamestateContent[43]);
  $combatChain = GetStringArray($gamestateContent[44]);
  $combatChainState = GetStringArray($gamestateContent[45]);
  $combatChainState = NormalizeCombatChainState($combatChainState);
  $currentTurnEffects = GetStringArray($gamestateContent[46]);
  $currentTurnEffectsFromCombat = GetStringArray($gamestateContent[47]);
  $nextTurnEffects = GetStringArray($gamestateContent[48]);
  $decisionQueue = GetStringArray($gamestateContent[49]);
  $dqVars = GetStringArray($gamestateContent[50]);
  $dqState = GetStringArray($gamestateContent[51]);
  $layers = GetStringArray($gamestateContent[52]);
  $layerPriority = GetStringArray($gamestateContent[53]);
  $mainPlayer = trim($gamestateContent[54]);
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  $lastPlayed = GetStringArray($gamestateContent[55]);
  $numChainLinks = isset($gamestateContent[56]) ? trim($gamestateContent[56]) : 0;
  if (!is_numeric($numChainLinks)) $numChainLinks = 0;
  $chainLinks = [];
  for ($i = 0; $i < $numChainLinks; ++$i) {
    $chainLinks[] = GetStringArray($gamestateContent[57+$i]);
  }
  $chainLinkSummary = GetStringArray($gamestateContent[57+$numChainLinks]);
  $p1Key = trim($gamestateContent[58+$numChainLinks]);
  $p2Key = trim($gamestateContent[59+$numChainLinks]);
  $permanentUniqueIDCounter = trim($gamestateContent[60+$numChainLinks]);
  $inGameStatus = trim($gamestateContent[61+$numChainLinks]); //Game status -- 0 = START, 1 = PLAY, 2 = OVER
  $animations = GetStringArray($gamestateContent[62+$numChainLinks]); //Animations
  $currentPlayerActivity = trim($gamestateContent[63+$numChainLinks]); // Not Used - Current Player activity status -- 0 = active, 2 = inactive
  //64 + numChainLinks unused
  //65 + numChainLinks unused
  $p1TotalTime = trim($gamestateContent[66+$numChainLinks]); //Player 1 total time
  $p2TotalTime = trim($gamestateContent[67+$numChainLinks]); //Player 2 total time
  $lastUpdateTime = trim($gamestateContent[68+$numChainLinks]); //Last update time
  // 69 + numChainLinks reserved for backward-compatible field alignment
  $events = GetStringArray($gamestateContent[70+$numChainLinks]); //Events
  $EffectContext = trim($gamestateContent[71+$numChainLinks]);
  $p1Inventory = GetStringArray($gamestateContent[72+$numChainLinks]);
  $p2Inventory = GetStringArray($gamestateContent[73+$numChainLinks]);
  $p1IsAI = trim($gamestateContent[74+$numChainLinks]);
  $p2IsAI = trim($gamestateContent[75+$numChainLinks]);
  $AIHasInfiniteHP = isset($gamestateContent[76+$numChainLinks]) ? trim($gamestateContent[76+$numChainLinks]) == "1" : false;
  if ($parseHistoricalStats) {
    $p1CardTurnLog = isset($gamestateContent[77+$numChainLinks]) ? json_decode(trim($gamestateContent[77+$numChainLinks]), true) ?? [] : [];
    $p2CardTurnLog = isset($gamestateContent[78+$numChainLinks]) ? json_decode(trim($gamestateContent[78+$numChainLinks]), true) ?? [] : [];
  } else {
    $p1CardTurnLog = [];
    $p2CardTurnLog = [];
  }
  $attackQueue = GetStringArray($gamestateContent[79+$numChainLinks] ?? "");
  if ($parseHistoricalStats) {
    $p1LifeHistory = isset($gamestateContent[80+$numChainLinks]) ? json_decode(trim($gamestateContent[80+$numChainLinks]), true) ?? [] : [];
    $p2LifeHistory = isset($gamestateContent[81+$numChainLinks]) ? json_decode(trim($gamestateContent[81+$numChainLinks]), true) ?? [] : [];
    $p1ArcaneDamageDealt = isset($gamestateContent[82+$numChainLinks]) ? json_decode(trim($gamestateContent[82+$numChainLinks]), true) ?? [] : [];
    $p2ArcaneDamageDealt = isset($gamestateContent[83+$numChainLinks]) ? json_decode(trim($gamestateContent[83+$numChainLinks]), true) ?? [] : [];
  } else {
    $p1LifeHistory = [];
    $p2LifeHistory = [];
    $p1ArcaneDamageDealt = [];
    $p2ArcaneDamageDealt = [];
  }
  $practiceDummyWeaponPower = isset($gamestateContent[84+$numChainLinks])
    ? max(0, min(100, intval($gamestateContent[84+$numChainLinks])))
    : 4;

  // for replays and current games as of this push
  $p1TurnCount = is_numeric(trim($gamestateContent[85+$numChainLinks] ?? "")) ? intval($gamestateContent[85+$numChainLinks])
    : intval($currentTurn) + ($firstPlayer == 1 && $mainPlayer == $firstPlayer ? 1 : 0);
  $p2TurnCount = is_numeric(trim($gamestateContent[86+$numChainLinks] ?? "")) ? intval($gamestateContent[86+$numChainLinks])
    : intval($currentTurn) + ($firstPlayer == 2 && $mainPlayer == $firstPlayer ? 1 : 0);
  if ($firstPlayer == 1) { if ($p1TurnCount < 1) $p1TurnCount = 1; }
  else if ($firstPlayer == 2) { if ($p2TurnCount < 1) $p2TurnCount = 1; }

  // Parsed regardless of $parseHistoricalStats: these accumulate during play, so
  // dropping them on a read would wipe them on the next write.
  $p1ContractsCompleted = intval(trim($gamestateContent[87+$numChainLinks] ?? ""));
  $p2ContractsCompleted = intval(trim($gamestateContent[88+$numChainLinks] ?? ""));
  $p1CardsDisrupted = json_decode(trim($gamestateContent[89+$numChainLinks] ?? ""), true) ?? [];
  $p2CardsDisrupted = json_decode(trim($gamestateContent[90+$numChainLinks] ?? ""), true) ?? [];
  $handInstanceIDs = DecodeHandInstanceIDs($gamestateContent[91+$numChainLinks] ?? "");
  BuildMyGamestate($playerID);
}

function DoGamestateUpdate()
{
  global $mainPlayerGamestateStillBuilt, $myStateBuiltFor;
  if ($mainPlayerGamestateStillBuilt == 1) UpdateMainPlayerGameStateInner();
  else if ($myStateBuiltFor != -1) UpdateGameStateInner();
}

function GamestateViewKeys()
{
  static $keys = [
    "my" => ["myHand", "myDeck", "myResources", "myCharacter", "myArsenal", "myItems", "myAuras", "myDiscard", "myPitch", "myBanish", "myClassState", "myCharacterEffects", "mySoul", "myCardStats", "myTurnStats", "myCardTurnLog", "myHealth"],
    "their" => ["theirHand", "theirDeck", "theirResources", "theirCharacter", "theirArsenal", "theirItems", "theirAuras", "theirDiscard", "theirPitch", "theirBanish", "theirClassState", "theirCharacterEffects", "theirSoul", "theirCardStats", "theirTurnStats", "theirCardTurnLog", "theirHealth"],
    "main" => ["mainHand", "mainDeck", "mainResources", "mainCharacter", "mainArsenal", "mainItems", "mainAuras", "mainDiscard", "mainPitch", "mainBanish", "mainClassState", "mainCharacterEffects", "mainSoul", "mainCardStats", "mainTurnStats", "mainCardTurnLog", "mainHealth"],
    "def" => ["defHand", "defDeck", "defResources", "defCharacter", "defArsenal", "defItems", "defAuras", "defDiscard", "defPitch", "defBanish", "defClassState", "defCharacterEffects", "defSoul", "defCardStats", "defTurnStats", "defCardTurnLog", "defHealth"],
    1 => ["p1Hand", "p1Deck", "p1Resources", "p1CharEquip", "p1Arsenal", "p1Items", "p1Auras", "p1Discard", "p1Pitch", "p1Banish", "p1ClassState", "p1CharacterEffects", "p1Soul", "p1CardStats", "p1TurnStats", "p1CardTurnLog"],
    2 => ["p2Hand", "p2Deck", "p2Resources", "p2CharEquip", "p2Arsenal", "p2Items", "p2Auras", "p2Discard", "p2Pitch", "p2Banish", "p2ClassState", "p2CharacterEffects", "p2Soul", "p2CardStats", "p2TurnStats", "p2CardTurnLog"],
  ];
  return $keys;
}

function CopyPlayerStateToView($player, $viewPrefix)
{
  global $playerHealths;
  $keys = GamestateViewKeys();
  $viewKeys = $keys[$viewPrefix];
  foreach ($keys[$player] as $i => $playerKey) {
    $GLOBALS[$viewKeys[$i]] = $GLOBALS[$playerKey] ?? null;
  }
  $GLOBALS[$viewKeys[16]] = $playerHealths[$player - 1];
}

function CopyViewStateToPlayer($viewPrefix, $player)
{
  global $playerHealths;
  $keys = GamestateViewKeys();
  $viewKeys = $keys[$viewPrefix];
  foreach ($keys[$player] as $i => $playerKey) {
    $GLOBALS[$playerKey] = $GLOBALS[$viewKeys[$i]] ?? null;
  }
  $playerHealths[$player - 1] = $GLOBALS[$viewKeys[16]] ?? null;
}

function BuildMyGamestate($playerID)
{
  global $myStateBuiltFor, $mainPlayerGamestateStillBuilt;
  DoGamestateUpdate();
  $mainPlayerGamestateStillBuilt = 0;
  $myStateBuiltFor = $playerID == 1 ? 1 : 2;
  CopyPlayerStateToView($myStateBuiltFor, "my");
  CopyPlayerStateToView($myStateBuiltFor == 1 ? 2 : 1, "their");
}

function BuildMainPlayerGameState()
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt, $mpgBuiltFor;
  DoGamestateUpdate();
  $mpgBuiltFor = $mainPlayer;
  CopyPlayerStateToView($mainPlayer == 1 ? 1 : 2, "main");
  CopyPlayerStateToView($mainPlayer == 1 ? 2 : 1, "def");
  $mainPlayerGamestateStillBuilt = 1;
}

function UpdateGameStateInner()
{
  global $myStateBuiltFor;
  CopyViewStateToPlayer("my", $myStateBuiltFor == 1 ? 1 : 2);
  CopyViewStateToPlayer("their", $myStateBuiltFor == 1 ? 2 : 1);
}

function UpdateMainPlayerGameStateInner()
{
  global $mpgBuiltFor;
  CopyViewStateToPlayer("main", $mpgBuiltFor == 1 ? 1 : 2);
  CopyViewStateToPlayer("def", $mpgBuiltFor == 1 ? 2 : 1);
}

function SaveGamestateSnapshot($destination)
{
  global $filepath, $lastWrittenGamestate;
  if (IsRollbackSnapshot($destination)) {
    $content = $lastWrittenGamestate ?? @file_get_contents($filepath . "gamestate.txt");
    return $content !== false && WriteRollbackSnapshot($destination, $content);
  }
  if (isset($lastWrittenGamestate)) {
    return file_put_contents($destination, $lastWrittenGamestate) !== false;
  }
  return copy($filepath . "gamestate.txt", $destination);
}

function WriteGamestateFileAtomic($filename, $content)
{
  $lockHandler = fopen(dirname($filename) . "/gamestate.lock", "c");
  if ($lockHandler === false || !flock($lockHandler, LOCK_EX)) {
    if ($lockHandler !== false) fclose($lockHandler);
    error_log("ERROR: Could not lock gamestate for atomic write: " . $filename);
    return false;
  }
  $tempPath = $filename . "." . getmypid() . "." . uniqid("", true) . ".tmp";
  $writeSucceeded = false;
  $handler = fopen($tempPath, "wb");
  if ($handler !== false) {
    $bytesWritten = fwrite($handler, $content);
    $flushed = fflush($handler);
    fclose($handler);
    $writeSucceeded = $bytesWritten === strlen($content) && $flushed && rename($tempPath, $filename);
    if (!$writeSucceeded && file_exists($tempPath)) @unlink($tempPath);
  }
  flock($lockHandler, LOCK_UN);
  fclose($lockHandler);
  if (!$writeSucceeded) error_log("ERROR: Atomic gamestate write failed: " . $filename);
  return $writeSucceeded;
}

function MakeGamestateBackup($filename = "gamestateBackup.txt")
{
  global $filepath, $lastWrittenGamestate;
  if(!isset($lastWrittenGamestate) && !file_exists($filepath . "gamestate.txt")) WriteLog("Cannot copy gamestate file; it does not exist.");

  // Handle special backups (like preBlockBackup.txt, beginTurnGamestate.txt, etc.)
  if ($filename != "gamestateBackup.txt") {
    $result = SaveGamestateSnapshot($filepath . $filename);
    if(!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
    return;
  }
  
  // Rotate the bounded undo history in a single APCu entry.
  $currentGamestate = $lastWrittenGamestate ?? @file_get_contents($filepath . "gamestate.txt");
  $result = $currentGamestate !== false && PushUndoState($filepath, $currentGamestate);
  if(!$result) WriteLog("Copy of gamestate into gamestateBackup_0.txt failed.");
}

function RevertGamestate($filename = "gamestateBackup.txt", $stepsBack = 1)
{
  global $gameName, $skipWriteGamestate, $filepath, $p1Settings, $p2Settings, $CS_NumUndoesThisTurn, $CS_PendingNAACard;
  
  // Handle special backups (like preBlockBackup.txt, beginTurnGamestate.txt, lastTurnGamestate.txt)
  if ($filename != "gamestateBackup.txt") {
    $snapshot = ReadRollbackSnapshot($filepath . $filename);
    if ($snapshot === false) return;
    // apply current settings to the backup, they are preferences and not game state
    $gamestateBackup = preg_split('/(?<=\n)/', $snapshot, -1, PREG_SPLIT_NO_EMPTY);
    if (isset($gamestateBackup[18])) $gamestateBackup[18] = implode(" ", $p1Settings) . "\r\n";
    if (isset($gamestateBackup[36])) $gamestateBackup[36] = implode(" ", $p2Settings) . "\r\n";
    $gamestate = implode('', $gamestateBackup);
    if (!WriteGamestateFileAtomic($filepath . "gamestate.txt", $gamestate)) return;
    $skipWriteGamestate = true;
    WriteGamestateCache($gameName, $gamestate);
    $GLOBALS['lastWrittenGamestate'] = $gamestate; // keep in-memory mirror of gamestate.txt current
    return;
  }
  
  // Multi-level undo: Revert to backup N steps back
  $stepsBack = (int)$stepsBack;
  if ($stepsBack < 1 || $stepsBack > MAX_UNDO_BACKUPS) return;
  $targetBackup = $stepsBack - 1; // stepsBack=1 means backup_0, stepsBack=2 means backup_1, etc.
  $backupFile = $filepath . "gamestateBackup_" . $targetBackup . ".txt";
  
  $snapshot = ReadRollbackSnapshot($backupFile);
  if ($snapshot === false) {
    WriteLog("Cannot undo further: Please revert to start of this/previous turn instead.");
    return;
  }
  // apply current settings to the backup
  $gamestateBackup = preg_split('/(?<=\n)/', $snapshot, -1, PREG_SPLIT_NO_EMPTY);
  $gamestateBackup[18] = implode(" ", $p1Settings) . "\r\n";
  $gamestateBackup[36] = implode(" ", $p2Settings) . "\r\n";
  // don't reset the number of undoes used
  $p1ClassState = ClassStateFromString($gamestateBackup[11] ?? "");
  $p2ClassState = ClassStateFromString($gamestateBackup[29] ?? "");
  $p1ClassState[$CS_NumUndoesThisTurn] = GetClassState(1, $CS_NumUndoesThisTurn);
  $p2ClassState[$CS_NumUndoesThisTurn] = GetClassState(2, $CS_NumUndoesThisTurn);
  // Clear pending NAA from both players on undo
  $p1ClassState[$CS_PendingNAACard] = "-";
  $p2ClassState[$CS_PendingNAACard] = "-";
  $gamestateBackup[11] = ClassStateToString($p1ClassState) . "\r\n";
  $gamestateBackup[29] = ClassStateToString($p2ClassState) . "\r\n";
  $gamestate = implode('', $gamestateBackup);
  if (!is_dir($filepath)) {
    WriteLog("Cannot undo further: the game session was cleaned up before the undo could complete.");
    return;
  }
  // Restore the target backup as current gamestate
  if (!WriteGamestateFileAtomic($filepath . "gamestate.txt", $gamestate)) return;
  $skipWriteGamestate = true;
  WriteGamestateCache($gameName, $gamestate);
  $GLOBALS['lastWrittenGamestate'] = $gamestate; // keep in-memory mirror of gamestate.txt current
  
  $result = ConsumeUndoStates($filepath, $stepsBack, $gamestate);
  if(!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
}

function SaveReplay() {
  return true;
}

function MakeStartChainLinkBackup()
{
  global $filepath;
  SaveGamestateSnapshot($filepath . "startChainLinkGamestate.txt");
}

function MakeStartTurnBackup()
{
  global $mainPlayer, $currentTurn, $filepath;
  $thisTurnFN = $filepath . "beginTurnGamestate.txt";
  RotateTurnRollbackState($filepath);
  SaveGamestateSnapshot($thisTurnFN);
  MakeGamestateBackup();
  $startGameFN = $filepath . "startGamestate.txt";
  if ((IsPatron(1) || IsPatron(2)) && $currentTurn == 0 && !file_exists($startGameFN)) {
    SaveGamestateSnapshot($startGameFN);
  }
  if (SaveReplay()) {
    $numberedTurnFN = $filepath . "turn_$mainPlayer-$currentTurn" . "_Gamestate.txt";
    SaveGamestateSnapshot($numberedTurnFN);
    $commandFile = fopen("$filepath/commandfile.txt", "a");
    fwrite($commandFile, "$mainPlayer StartTurn $currentTurn 0\r\n");
    fclose($commandFile);
  }
}

function GetAvailableUndoSteps()
{
  global $filepath;
  $availableSteps = 0;
  
  for ($i = 0; $i < MAX_UNDO_BACKUPS; $i++) {
    $backupFile = $filepath . "gamestateBackup_" . $i . ".txt";
    if (RollbackSnapshotExists($backupFile)) {
      $availableSteps++;
    } else {
      break; // No more consecutive backups
    }
  }
  
  return $availableSteps;
}

function UndoComparableGamestate($gamestate)
{
  $lines = explode("\r\n", $gamestate);
  $links = intval($lines[56] ?? 0);
  foreach ([11, 18, 29, 36, 63 + $links, 66 + $links, 67 + $links, 68 + $links, 70 + $links] as $index) unset($lines[$index]);
  return implode("\r\n", $lines);
}

function UndoStepsBack()
{
  global $filepath;
  $newest = ReadRollbackSnapshot($filepath . "gamestateBackup_0.txt");
  $current = @file_get_contents($filepath . "gamestate.txt");
  if ($newest === false || $current === false) return 1;
  if (UndoComparableGamestate($newest) !== UndoComparableGamestate($current)) return 1;
  return RollbackSnapshotExists($filepath . "gamestateBackup_1.txt") ? 2 : 0;
}

function ResetUndoBackupsForRematch()
{
  global $filepath;
  
  // Clear all checkpoints so undoing cannot reach the previous game.
  DeleteRollbackStates(basename(rtrim($filepath, '/\\')));
}
