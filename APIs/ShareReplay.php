<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/ReplayLibraries.php";

SetHeaders();

$userId = ReplaySessionUserId();
$response = new stdClass();

if (!IsValidReplayUserId($userId)) {
    session_write_close();
    $response->error = "You must be logged in to share replays.";
    ExitJsonResponse($response, 401);
}

session_write_close();

$request = ReadJsonBody() ?: [];
$rawReplayNumber = $request["replayNumber"] ?? null;
$replayNumber = ParsePositiveReplayNumber($rawReplayNumber);
if ($replayNumber === null) {
    $response->error = "Invalid or missing replayNumber.";
    ExitJsonResponse($response, 400);
}

$replayPath = UserReplayPath($userId, $replayNumber);
if (!file_exists($replayPath . "origGamestate.txt") || !file_exists($replayPath . "commandfile.txt")) {
    $response->error = "Replay not found or missing required files.";
    ExitJsonResponse($response, 404);
}
$token = bin2hex(random_bytes(32));

$sharedDir = "../Replays/shared/";
if (!file_exists($sharedDir) && !mkdir($sharedDir, 0700, true)) {
    $response->error = "Failed to create shared replays directory.";
    ExitJsonResponse($response, 500);
}

$tokenData = json_encode(["userId" => $userId, "replayNumber" => (int)$replayNumber]);
if (file_put_contents($sharedDir . $token . ".json", $tokenData) === false) {
    $response->error = "Failed to save share token.";
    ExitJsonResponse($response, 500);
}

$response->success = true;
$response->token = $token;
WriteJsonResponse($response);
