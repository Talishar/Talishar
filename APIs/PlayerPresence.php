<?php

include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/CacheLibraries.php";

SetHeaders();

$jsonInput = json_decode(file_get_contents('php://input'), true);
if (is_array($jsonInput)) $_POST = $jsonInput;

header('Content-Type: application/json; charset=utf-8');
$response = new stdClass();

$gameName = $_POST["gameName"] ?? $_GET["gameName"] ?? null;
if (!IsGameNameValid($gameName)) {
  $response->errorMessage = "Invalid game name.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$playerID = $_POST["playerID"] ?? $_GET["playerID"] ?? null;
if (!is_numeric($playerID) || ($playerID != 1 && $playerID != 2)) {
  $response->errorMessage = "Invalid player ID.";
  http_response_code(400);
  echo json_encode($response);
  exit;
}

$cacheKey = "presence_" . md5($gameName) . "_player_" . $playerID;
$rawPresence = $_POST["presence"] ?? $_GET["presence"] ?? "";

if (extension_loaded('apcu') && ini_get('apc.enabled') && function_exists('apcu_store')) {
  if ($rawPresence === "") {
    @apcu_delete($cacheKey);
  } else {
    $presence = json_decode($rawPresence, true);
    $allowedZones = [
      "arsenal" => true,
      "banish" => true,
      "deck" => true,
      "graveyard" => true,
      "hand" => true,
      "pitch" => true,
      "soul" => true
    ];
    $isValid = is_array($presence) && isset($presence["type"]);

    if ($isValid && $presence["type"] === "combat-summary") {
      $presence = ["type" => "combat-summary"];
    } elseif (
      $isValid &&
      $presence["type"] === "zone" &&
      isset($presence["zone"], $presence["owner"]) &&
      isset($allowedZones[$presence["zone"]]) &&
      ($presence["owner"] === "self" || $presence["owner"] === "opponent")
    ) {
      // Store only the allowlisted zone-level fields. Card data is never accepted.
      $presence = [
        "type" => "zone",
        "zone" => $presence["zone"],
        "owner" => $presence["owner"]
      ];
    } else {
      $response->errorMessage = "Invalid presence.";
      http_response_code(400);
      echo json_encode($response);
      exit;
    }

    @apcu_store($cacheKey, $presence, 5);
  }
}

$response->success = true;
echo json_encode($response);
