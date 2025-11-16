<?php

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";

SetHeaders();

header('Content-Type: application/json; charset=utf-8');
$response = new stdClass();

// Get parameters
$gameName = TryGet("gameName", "");
$turnNumber = intval(TryGet("turnNumber", 0));
$playerID = intval(TryGet("playerID", 3));

if (!IsGameNameValid($gameName)) {
  $response->errorMessage = "Invalid game name.";
  echo json_encode($response);
  exit;
}

// Check if this is a replay
$isReplay = GetCachePiece($gameName, 10);
if ($isReplay !== "1") {
  $response->errorMessage = "This game is not a replay.";
  echo json_encode($response);
  exit;
}

// Game directory
$gameDir = "./Games/" . $gameName . "/";
if (!is_dir($gameDir)) {
  $response->errorMessage = "Game directory not found.";
  echo json_encode($response);
  exit;
}

// The original gamestate is stored as gamestate.txt (copied from replay's origGamestate.txt)
$origGamestateFile = $gameDir . "gamestate.txt";
if (!file_exists($origGamestateFile)) {
  $response->errorMessage = "Game state file not found at: " . $origGamestateFile;
  echo json_encode($response);
  exit;
}

// Store the original gamestate content for later use
$origGamestateContent = file_get_contents($origGamestateFile);

// If turnNumber is 0, return the original gamestate
if ($turnNumber === 0) {
  WriteGamestateCache($gameName, $origGamestateContent);
  $response->success = true;
  $response->message = "Loaded original gamestate.";
  echo json_encode($response);
  exit;
}

// Otherwise, try to load turn_{player}-{sequence}_Gamestate.txt
// The sequence number represents the game turn number
$turnLoaded = false;

// First, try the expected format: turn_{player}-{turnNumber}_Gamestate.txt
for ($p = 1; $p <= 2; $p++) {
  $turnFile = $gameDir . "turn_" . $p . "-" . $turnNumber . "_Gamestate.txt";
  if (file_exists($turnFile)) {
    $gamestate = file_get_contents($turnFile);
    WriteGamestateCache($gameName, $gamestate);
    $response->success = true;
    $response->message = "Loaded turn " . $turnNumber . ".";
    $response->turnFile = basename($turnFile);
    $turnLoaded = true;
    break;
  }
}

// If not found with exact match, list available turn files for debugging
if (!$turnLoaded) {
  $turnFiles = glob($gameDir . "turn_*_Gamestate.txt");
  $availableTurns = [];
  foreach ($turnFiles as $file) {
    $availableTurns[] = basename($file);
  }
  
  $response->errorMessage = "Turn " . $turnNumber . " not found.";
  $response->availableTurns = $availableTurns;
  $response->requestedTurnNumber = $turnNumber;
  echo json_encode($response);
  exit;
}

echo json_encode($response);
exit;

?>
