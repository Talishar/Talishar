<?php

include "../Libraries/SHMOPLibraries.php";
include "../Libraries/HTTPLibraries.php";
include "../HostFiles/Redirector.php";
include "../CardDictionary.php";

SetHeaders();

$_POST = json_decode(file_get_contents('php://input'), true);
$gameName = TryPOST("gameName", "");

$response = new stdClass();

if (!IsGameNameValid($gameName)) {
  $response->error = "Invalid game name.";
  echo json_encode($response);
  exit;
}

$path = "../Games/" . $gameName . "/";

// Check if game exists and has a gamestate (in progress)
$gs = $path . "gamestate.txt";
if (file_exists($gs)) {
  $response->format = GetCachePiece($gameName, 13);
  echo json_encode($response);
  exit;
}

// Check if game exists and hasn't started yet (open game)
$gf = $path . "GameFile.txt";
if (file_exists($gf)) {
  include 'APIParseGamefile.php';
  $response->format = $format;
  echo json_encode($response);
  exit;
}

$response->error = "Game not found.";
echo json_encode($response);
exit;
