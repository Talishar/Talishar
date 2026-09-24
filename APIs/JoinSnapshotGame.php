<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();
$request = ReadJsonBody() ?: [];
$gameName = $request["gameName"] ?? null;
$token = $request["inviteToken"] ?? null;
if (!(is_int($gameName) || (is_string($gameName) && ctype_digit($gameName))) || (int)$gameName < 1 || !is_string($token) || !preg_match('/^[0-9a-f]{64}$/', $token)) {
  ExitJsonResponse(["error" => "Invalid snapshot invite."], 400);
}
$gameName = (int)$gameName;
$path = "../Games/$gameName/";
$storedHash = @file_get_contents($path . "snapshotInviteHash.txt");
if (!is_string($storedHash) || !hash_equals(trim($storedHash), hash("sha256", $token))) {
  ExitJsonResponse(["error" => "Snapshot invite not found."], 404);
}
$gameFile = @file($path . "GameFile.txt", FILE_IGNORE_NEW_LINES);
if (!is_array($gameFile) || count($gameFile) < 9) ExitJsonResponse(["error" => "Snapshot game is unavailable."], 404);
// The seat is saved alongside the invite; never trust the caller to choose it.
$seatFile = @file_get_contents($path . "snapshotOwnerSeat.txt");
if ($seatFile !== "1" && $seatFile !== "2") ExitJsonResponse(["error" => "Snapshot invite is incomplete."], 404);
$seat = $seatFile === "1" ? 2 : 1;
WriteJsonResponse([
  "success" => true,
  "gameName" => $gameName,
  "playerID" => $seat,
  "authKey" => trim($gameFile[$seat === 1 ? 7 : 8])
]);
