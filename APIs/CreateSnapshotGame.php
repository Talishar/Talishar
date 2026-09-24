<?php

session_start();
include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/ReplayLibraries.php";
include_once "../Libraries/SnapshotLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/FormatCodes.php";
include_once "../includes/functions.inc.php";

SetHeaders();
$userId = ReplaySessionUserId();
session_write_close();
if (!IsValidReplayUserId($userId)) ExitJsonResponse(["error" => "Log in to start a snapshot."], 401);

$request = ReadJsonBody() ?: [];
$number = ParsePositiveReplayNumber($request["snapshotNumber"] ?? null);
if ($number === null) ExitJsonResponse(["error" => "Invalid snapshot number."], 400);
$path = UserReplayPath($userId, $number);
if (!IsSnapshotDirectory($path) || !is_file($path . "gamestate.txt") || !is_file($path . "GameFile.txt")) {
  ExitJsonResponse(["error" => "Snapshot not found."], 404);
}
$metadata = json_decode((string)file_get_contents($path . "replayMetadata.json"), true);
$ownerSeat = (int)($metadata["savedByPlayerID"] ?? 0);
if ($ownerSeat !== 1 && $ownerSeat !== 2) ExitJsonResponse(["error" => "Snapshot seat is invalid."], 400);

$gamestate = (string)file_get_contents($path . "gamestate.txt");
$state = explode("\r\n", $gamestate);
$gameFile = file($path . "GameFile.txt", FILE_IGNORE_NEW_LINES);
$chainLinks = filter_var($state[56] ?? null, FILTER_VALIDATE_INT);
if (!is_array($gameFile) || count($gameFile) < 44 || $chainLinks === false || $chainLinks < 0 || count($state) < 76 + $chainLinks) {
  ExitJsonResponse(["error" => "Snapshot files are incomplete."], 400);
}
if (trim($state[61 + $chainLinks]) !== "1") ExitJsonResponse(["error" => "This snapshot is not an active game."], 400);

$gameName = GetGameCounter("../");
$gamePath = "../Games/$gameName/";
if (!@mkdir($gamePath, 0700, true)) ExitJsonResponse(["error" => "Could not create a game."], 500);
$p1Key = bin2hex(random_bytes(32));
$p2Key = bin2hex(random_bytes(32));
$state[58 + $chainLinks] = $p1Key;
$state[59 + $chainLinks] = $p2Key;
$state[74 + $chainLinks] = "0";
$state[75 + $chainLinks] = "0";
$state[76 + $chainLinks] = "0";
$gamestate = implode("\r\n", $state);

$gameFile[2] = "5";
$gameFile[4] = "private";
$gameFile[7] = $p1Key;
$gameFile[8] = $p2Key;
$gameFile[9] = "-";
$gameFile[10] = "-";
$gameFile[$ownerSeat === 1 ? 9 : 10] = $userId;
$gameFile[11] = "-";
$gameFile[12] = "-";
$gameFile[14] = GetClientIP();
$gameFile[21] = "";
$gameFile[35] = "0";
$gameFile[36] = "0";
$gameFile[37] = GenerateGameGUID();
$gameFile[38] = "[]";
$gameFile[39] = "[]";

$invite = bin2hex(random_bytes(32));
$writes = [
  "GameFile.txt" => implode("\r\n", $gameFile) . "\r\n",
  "gamestate.txt" => $gamestate,
  "origGamestate.txt" => $gamestate,
  "beginTurnGamestate.txt" => $gamestate,
  "lastTurnGamestate.txt" => $gamestate,
  "startChainLinkGamestate.txt" => $gamestate,
  "gamestateBackup.txt" => $gamestate,
  "gamelog.txt" => "",
  "commandfile.txt" => "",
  "snapshotInviteHash.txt" => hash("sha256", $invite),
  "snapshotOwnerSeat.txt" => (string)$ownerSeat
];
foreach ($writes as $filename => $content) {
  if (file_put_contents($gamePath . $filename, $content, LOCK_EX) === false) {
    foreach (array_keys($writes) as $writtenFile) {
      if (is_file($gamePath . $writtenFile)) @unlink($gamePath . $writtenFile);
    }
    @rmdir($gamePath);
    ExitJsonResponse(["error" => "Could not initialize the snapshot game."], 500);
  }
}

$currentTime = round(microtime(true) * 1000);
$formatCode = FormatCode(trim($gameFile[3]));
WriteCache($gameName, "1!$currentTime!$currentTime!0!-1!$currentTime!!!0!0!0!0!$formatCode!5!0!0");
WriteGamestateCache($gameName, $gamestate);
WriteJsonResponse([
  "success" => true,
  "gameName" => $gameName,
  "playerID" => $ownerSeat,
  "authKey" => $ownerSeat === 1 ? $p1Key : $p2Key,
  "inviteToken" => $invite
]);
