<?php

const MAX_BACKUP_SLOTS = 5;
const BACKUP_FILE_PREFIX = "gamestateBackup_";
const BACKUP_METADATA_FILE = "undoMetadata.json";
const BEGIN_TURN_BACKUP = "beginTurnGamestate.txt";
const LAST_TURN_BACKUP = "lastTurnGamestate.txt";

/**
 * Initialize backup metadata for a game if it doesn't exist
 * Creates empty unified undo stack
 */
function InitializeBackupHistory($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  $metadataFile = $filepath . BACKUP_METADATA_FILE;
  
  if (!file_exists($metadataFile)) {
    $metadata = [
      "undoStack" => [],        // All undo items (actions and turn checkpoints)
      "currentPosition" => 0,   // 0 = live, 1+ = steps back in stack
      "nextActionSlot" => 0,    // Rotating buffer for action backups
      "maxStackSize" => MAX_BACKUP_SLOTS,
      "createdAt" => time()
    ];
    
    file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }
}

/**
 * Load backup metadata from disk
 */
function LoadBackupMetadata($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  $metadataFile = $filepath . BACKUP_METADATA_FILE;
  if (!file_exists($metadataFile)) {
    return null;
  }
  
  $json = file_get_contents($metadataFile);
  $data = json_decode($json, true);
  
  // Migrate old format to new unified stack format
  if (isset($data["stack"]) && !isset($data["undoStack"])) {
    $newStack = [];
    // Convert old action stack to new format
    foreach ($data["stack"] as $slot) {
      $newStack[] = ["type" => "action", "slot" => $slot];
    }
    $data["undoStack"] = $newStack;
    $data["currentPosition"] = isset($data["undoDepth"]) ? $data["undoDepth"] : 0;
    $data["nextActionSlot"] = isset($data["nextSlot"]) ? $data["nextSlot"] : 0;
    $data["maxStackSize"] = MAX_BACKUP_SLOTS;
    unset($data["stack"]);
    unset($data["undoDepth"]);
    unset($data["nextSlot"]);
    SaveBackupMetadata($gameName, $data, $filepath);
  }
  
  return $data;
}

/**
 * Save backup metadata to disk
 */
function SaveBackupMetadata($gameName, $metadata, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  $metadataFile = $filepath . BACKUP_METADATA_FILE;
  file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

/**
 * Create a new action backup - adds to unified undo stack
 * Shifts older items off if stack exceeds MAX_BACKUP_SLOTS
 */
function MakeGamestateBackupHistory($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  if (!file_exists($filepath . "gamestate.txt")) {
    WriteLog("Cannot backup gamestate; gamestate.txt does not exist.");
    return false;
  }
  
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata === null) {
    InitializeBackupHistory($gameName, $filepath);
    $metadata = LoadBackupMetadata($gameName, $filepath);
  }
  
  // Check if the last backup in the stack has the same content as current gamestate
  // This prevents duplicate backups when MakeGamestateBackup is called multiple times in quick succession
  $lastStackEntry = count($metadata["undoStack"]) > 0 ? end($metadata["undoStack"]) : null;
  $isDuplicate = false;
  if ($lastStackEntry !== null && $lastStackEntry["type"] === "action") {
    $lastBackupFile = $filepath . BACKUP_FILE_PREFIX . $lastStackEntry["slot"] . ".txt";
    if (file_exists($lastBackupFile) && file_get_contents($lastBackupFile) === file_get_contents($filepath . "gamestate.txt")) {
      // Current gamestate matches the last backup, don't create a duplicate
      $isDuplicate = true;
    }
  }
  
  if ($isDuplicate) {
    return true; // Successfully skipped duplicate backup
  }
  
  // If we're not at the live state (currentPosition > 0), we're making a new action after an undo
  // In this case, truncate the stack to discard all "future" actions that are no longer reachable
  if ($metadata["currentPosition"] > 0) {
    $newStackSize = count($metadata["undoStack"]) - $metadata["currentPosition"];
    $metadata["undoStack"] = array_slice($metadata["undoStack"], 0, $newStackSize);
  }
  
  // Get next action slot (rotating buffer)
  $slot = $metadata["nextActionSlot"];
  $backupFilename = $filepath . BACKUP_FILE_PREFIX . $slot . ".txt";
  
  if (!copy($filepath . "gamestate.txt", $backupFilename)) {
    WriteLog("Failed to create action backup at slot " . $slot);
    return false;
  }
  
  // Add action to undo stack
  $metadata["undoStack"][] = ["type" => "action", "slot" => $slot];
  
  // If stack exceeds max, remove oldest ACTION items (preserve ALL turn checkpoints)
  while (count($metadata["undoStack"]) > MAX_BACKUP_SLOTS) {
    // Find the oldest action entry to remove (skip turnStart and prevTurnStart)
    $removed = false;
    for ($i = 0; $i < count($metadata["undoStack"]); $i++) {
      if ($metadata["undoStack"][$i]["type"] === "action") {
        array_splice($metadata["undoStack"], $i, 1);
        $removed = true;
        break;
      }
    }
    // Safety check: if somehow we can't find an action to remove, stop
    // (this should never happen if we're tracking things correctly)
    if (!$removed) {
      break;
    }
  }
  
  // Reset position to live state (most recent)
  $metadata["currentPosition"] = 0;
  
  // Move to next action slot (rotates 0 to MAX_BACKUP_SLOTS-1)
  $metadata["nextActionSlot"] = ($metadata["nextActionSlot"] + 1) % MAX_BACKUP_SLOTS;
  
  SaveBackupMetadata($gameName, $metadata, $filepath);
  
  return true;
}

/**
 * Undo one step (move back in unified undo stack)
 * When stepsBack=1 (single undo), skips turn checkpoints to only undo actions
 */
function RevertGamestateByHistory($stepsBack, $gameName, $filepath = null)
{
  global $skipWriteGamestate;
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata === null) {
    WriteLog("No backup metadata found for game " . $gameName);
    return false;
  }
  
  $stack = &$metadata["undoStack"];
  $stackSize = count($stack);
  
  if ($stackSize === 0) {
    return false;
  }
  
  // For single undo (stepsBack=1), skip over turn checkpoints
  // Only actual action entries should be undone by a single click
  $searchPosition = $metadata["currentPosition"];
  $actionCount = 0;
  $targetIndex = -1;
  
  // Search backwards from current position to find the Nth action entry
  for ($i = 0; $i < $stackSize; $i++) {
    $checkIndex = $stackSize - 1 - $searchPosition - $i;
    if ($checkIndex < 0) {
      break;
    }
    
    $entry = $stack[$checkIndex];
    if ($entry["type"] === "action") {
      $actionCount++;
      if ($actionCount == $stepsBack) {
        $targetIndex = $checkIndex;
        break;
      }
    }
  }
  
  if ($targetIndex === -1) {
    return false;  // Couldn't find enough actions to undo
  }
  
  $targetEntry = $stack[$targetIndex];
  $newPosition = $stackSize - $targetIndex;
  
  // Can't move further back than stack size
  if ($newPosition > $stackSize) {
    return false;
  }
  
  if ($targetEntry["type"] === "action") {
    $backupFilename = $filepath . BACKUP_FILE_PREFIX . $targetEntry["slot"] . ".txt";
    if (!file_exists($backupFilename)) {
      WriteLog("Action backup file not found: " . $backupFilename);
      return false;
    }
    if (!copy($backupFilename, $filepath . "gamestate.txt")) {
      return false;
    }
  } else if ($targetEntry["type"] === "turnStart") {
    if (!file_exists($filepath . BEGIN_TURN_BACKUP)) {
      WriteLog("Turn start backup not found");
      return false;
    }
    if (!copy($filepath . BEGIN_TURN_BACKUP, $filepath . "gamestate.txt")) {
      return false;
    }
  } else if ($targetEntry["type"] === "prevTurnStart") {
    if (!file_exists($filepath . LAST_TURN_BACKUP)) {
      WriteLog("Previous turn backup not found");
      return false;
    }
    if (!copy($filepath . LAST_TURN_BACKUP, $filepath . "gamestate.txt")) {
      return false;
    }
  }
  
  $skipWriteGamestate = true;
  $gamestate = file_get_contents($filepath . "gamestate.txt");
  WriteGamestateCache($gameName, $gamestate);
  
  $metadata["currentPosition"] = $newPosition;
  SaveBackupMetadata($gameName, $metadata, $filepath);
  
  return true;
}

/**
 * Internal: Add turn start checkpoint to unified undo stack
 * Called by MakeStartTurnBackup() in ParseGamestate.php
 * This is a single entry point - the previous turn is automatically available
 * via the lastTurnGamestate.txt file that MakeStartTurnBackup() creates
 */
function AddTurnStartToStack($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  // Add to unified undo stack
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata === null) {
    InitializeBackupHistory($gameName, $filepath);
    $metadata = LoadBackupMetadata($gameName, $filepath);
  }
  
  // Add turn checkpoint - this represents the beginning of the current turn
  $metadata["undoStack"][] = ["type" => "turnStart"];
  
  // If stack exceeds max, remove oldest ACTION items (preserve ALL turn checkpoints)
  while (count($metadata["undoStack"]) > MAX_BACKUP_SLOTS) {
    // Find the oldest action entry to remove (skip all turn checkpoints)
    $removed = false;
    for ($i = 0; $i < count($metadata["undoStack"]); $i++) {
      if ($metadata["undoStack"][$i]["type"] === "action") {
        array_splice($metadata["undoStack"], $i, 1);
        $removed = true;
        break;
      }
    }
    // Safety check: if no action found to remove, stop
    // (this should never happen if we're tracking things correctly)
    if (!$removed) {
      break;
    }
  }
  
  // Reset to live state
  $metadata["currentPosition"] = 0;
  
  SaveBackupMetadata($gameName, $metadata, $filepath);
}

/**
 * Add previous turn start checkpoint to unified undo stack
 * Called when a new turn starts to mark the beginning of the previous turn
 * This is separate from turnStart which marks the current turn
 */
function AddPreviousTurnStartToStack($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  // Add to unified undo stack
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata === null) {
    InitializeBackupHistory($gameName, $filepath);
    $metadata = LoadBackupMetadata($gameName, $filepath);
  }
  
  // Remove any existing prevTurnStart entry (only one should exist at a time)
  for ($i = count($metadata["undoStack"]) - 1; $i >= 0; $i--) {
    if ($metadata["undoStack"][$i]["type"] === "prevTurnStart") {
      array_splice($metadata["undoStack"], $i, 1);
      break;
    }
  }
  
  // Add previous turn checkpoint - this represents the beginning of the previous turn
  $metadata["undoStack"][] = ["type" => "prevTurnStart"];
  
  // If stack exceeds max, remove oldest ACTION items (preserve ALL turn checkpoints)
  while (count($metadata["undoStack"]) > MAX_BACKUP_SLOTS) {
    // Find the oldest action entry to remove (skip all turn checkpoints)
    $removed = false;
    for ($i = 0; $i < count($metadata["undoStack"]); $i++) {
      if ($metadata["undoStack"][$i]["type"] === "action") {
        array_splice($metadata["undoStack"], $i, 1);
        $removed = true;
        break;
      }
    }
    // Safety check: if no action found to remove, stop
    if (!$removed) {
      break;
    }
  }
  
  // Don't reset currentPosition here - preserve undo state
  SaveBackupMetadata($gameName, $metadata, $filepath);
}

/**
 * Revert to turn start via unified stack
 * Finds the most recent turnStart entry and moves to it
 */
function RevertToBeginTurn($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata === null) {
    return false;
  }
  
  $stack = &$metadata["undoStack"];
  
  // Find most recent turnStart entry (searching backwards from end)
  $foundIndex = -1;
  for ($i = count($stack) - 1; $i >= 0; $i--) {
    if ($stack[$i]["type"] === "turnStart") {
      $foundIndex = $i;
      break;
    }
  }
  
  if ($foundIndex === -1) {
    return false;  // No turn start checkpoint
  }
  
  // Calculate how many steps back to reach this turnStart entry
  // Position 0 = live state (not in stack)
  // Position N = stack[$stackSize - N]
  // So if turnStart is at stack[$foundIndex], we need position = stackSize - foundIndex
  $stackSize = count($stack);
  $stepsBack = $stackSize - $foundIndex;
  return RevertGamestateByHistory($stepsBack, $gameName, $filepath);
}

/**
 * Revert to previous turn start via lastTurnGamestate.txt file
 * This is a direct revert (not counted in action undo stack)
 * Maintains separate from action undo depth to allow full action undo after this revert
 */
function RevertToLastTurn($gameName, $filepath = null)
{
  global $skipWriteGamestate;
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  if (!file_exists($filepath . LAST_TURN_BACKUP)) {
    return false;
  }
  
  if (!copy($filepath . LAST_TURN_BACKUP, $filepath . "gamestate.txt")) {
    return false;
  }
  
  $skipWriteGamestate = true;
  $gamestate = file_get_contents($filepath . LAST_TURN_BACKUP);
  WriteGamestateCache($gameName, $gamestate);
  
  // Reset undo depth to allow full undo history from this point
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata !== null) {
    $metadata["currentPosition"] = 0;
    SaveBackupMetadata($gameName, $metadata, $filepath);
  }
  
  return true;
}

/**
 * Get available undo steps from current position
 */
function GetAvailableUndoSteps($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  $metadata = LoadBackupMetadata($gameName, $filepath);
  if ($metadata === null) {
    return 0;
  }
  
  return count($metadata["undoStack"]) - $metadata["currentPosition"];
}

/**
 * Clean up all backup files and metadata when game ends
 */
function CleanupBackupHistory($gameName, $filepath = null)
{
  if ($filepath === null) {
    $filepath = "./Games/" . $gameName . "/";
  }
  
  // Remove all action backup files
  for ($i = 0; $i < MAX_BACKUP_SLOTS; $i++) {
    $file = $filepath . BACKUP_FILE_PREFIX . $i . ".txt";
    if (file_exists($file)) {
      @unlink($file);
    }
  }
  
  // Remove turn checkpoints
  @unlink($filepath . BEGIN_TURN_BACKUP);
  @unlink($filepath . LAST_TURN_BACKUP);
  
  // Remove metadata
  @unlink($filepath . BACKUP_METADATA_FILE);
}

/**
 * Legacy wrapper - for backward compatibility
 */
function MakeGamestateBackup($filename = "gamestateBackup.txt")
{
  global $gameName, $filepath;
  
  if ($filename == "gamestateBackup.txt") {
    MakeGamestateBackupHistory($gameName, $filepath);
  } else {
    if (!file_exists($filepath . "gamestate.txt")) {
      WriteLog("Cannot copy gamestate file; it does not exist.");
      return;
    }
    $result = copy($filepath . "gamestate.txt", $filepath . $filename);
    if (!$result) WriteLog("Copy of gamestate into " . $filename . " failed.");
  }
}

/**
 * Legacy wrapper - for backward compatibility
 */
function RevertGamestate($filename = "gamestateBackup.txt")
{
  global $gameName, $skipWriteGamestate, $filepath;
  
  if ($filename == "gamestateBackup.txt") {
    return RevertGamestateByHistory(1, $gameName, $filepath);
  } else {
    if (!file_exists($filepath . $filename)) {
      return false;
    }
    copy($filepath . $filename, $filepath . "gamestate.txt");
    $skipWriteGamestate = true;
    $gamestate = file_get_contents($filepath . $filename);
    WriteGamestateCache($gameName, $gamestate);
    return true;
  }
}
