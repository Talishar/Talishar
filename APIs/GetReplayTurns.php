<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";

SetHeaders();

$response = new stdClass();
$response->turns = [];
$gameName = TryGet("gameName", "");

if (!IsGameNameValid($gameName) || GetCachePiece($gameName, 10) !== "1") {
  http_response_code(400);
  $response->error = "Replay not found.";
  echo json_encode($response);
  exit;
}

foreach (glob("../Games/$gameName/turn_*-*_Gamestate.txt") ?: [] as $file) {
  if (preg_match('/turn_([12])-(\\d+)_Gamestate\\.txt$/', basename($file), $matches)) {
    $response->turns[] = ["player" => (int)$matches[1], "number" => (int)$matches[2]];
  }
}

usort($response->turns, fn($a, $b) => $a["number"] <=> $b["number"] ?: $a["player"] <=> $b["player"]);
echo json_encode($response);
