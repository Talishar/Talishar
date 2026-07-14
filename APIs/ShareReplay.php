<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../AccountFiles/AccountSessionAPI.php";

SetHeaders();

$userId = $_SESSION["useruid"] ?? "";
$isPatron = IsLoggedInUserPatron();
session_write_close();

$response = new stdClass();

if ($userId === "") {
    $response->error = "You must be logged in to share replays.";
    http_response_code(401);
    echo json_encode($response);
    exit;
}

if (!$isPatron) {
    $response->error = "Replay sharing is only available to patrons.";
    http_response_code(403);
    echo json_encode($response);
    exit;
}

$_POST = json_decode(file_get_contents('php://input'), true);
$replayNumber = $_POST["replayNumber"] ?? null;

if ($replayNumber === null || !is_numeric($replayNumber)) {
    $response->error = "Invalid or missing replayNumber.";
    http_response_code(400);
    echo json_encode($response);
    exit;
}

$replayPath = "../Replays/$userId/$replayNumber/";
if (!file_exists($replayPath . "origGamestate.txt") || !file_exists($replayPath . "commandfile.txt")) {
    $response->error = "Replay not found or missing required files.";
    http_response_code(404);
    echo json_encode($response);
    exit;
}

$token = bin2hex(random_bytes(32));

$sharedDir = "../Replays/shared/";
if (!file_exists($sharedDir) && !mkdir($sharedDir, 0700, true)) {
    $response->error = "Failed to create shared replays directory.";
    http_response_code(500);
    echo json_encode($response);
    exit;
}

$tokenData = json_encode(["userId" => $userId, "replayNumber" => (int)$replayNumber]);
if (file_put_contents($sharedDir . $token . ".json", $tokenData) === false) {
    $response->error = "Failed to save share token.";
    http_response_code(500);
    echo json_encode($response);
    exit;
}

$response->success = true;
$response->token = $token;
echo json_encode($response);
