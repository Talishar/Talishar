<?php

session_start();

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
include_once '../includes/functions.inc.php';

SetHeaders();

session_write_close();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);
$token = $_POST["shareToken"] ?? null;

if ($token === null || $token === "") {
    $response->error = "Missing shareToken.";
    http_response_code(400);
    echo json_encode($response);
    exit;
}

if (!ctype_xdigit($token) || strlen($token) !== 64) {
    $response->error = "Invalid shareToken format.";
    http_response_code(400);
    echo json_encode($response);
    exit;
}

$tokenFile = "../Replays/shared/$token.json";
if (!file_exists($tokenFile)) {
    $response->error = "Shared replay not found. The link may have expired or been removed.";
    http_response_code(404);
    echo json_encode($response);
    exit;
}

$tokenData = json_decode(file_get_contents($tokenFile), true);
if (!isset($tokenData["userId"], $tokenData["replayNumber"])) {
    $response->error = "Corrupt share token data.";
    http_response_code(500);
    echo json_encode($response);
    exit;
}

$userId = $tokenData["userId"];
$replayNumber = (int)$tokenData["replayNumber"];
$replayPath = "../Replays/$userId/$replayNumber/";

$requiredFiles = ["origGamestate.txt", "commandfile.txt"];
foreach ($requiredFiles as $file) {
    if (!file_exists($replayPath . $file)) {
        $response->error = "Replay files are no longer available.";
        http_response_code(404);
        echo json_encode($response);
        exit;
    }
}

$gameName = GetGameCounter("../");

if (!file_exists("../Games/$gameName") && !mkdir("../Games/$gameName", 0700, true)) {
    $response->error = "Failed to create game directory.";
    http_response_code(500);
    echo json_encode($response);
    exit;
}

$startingHealth = "";
$p1Data = [1];
$p2Data = [2];
$gameStatus = 5;
$firstPlayerChooser = "";
$firstPlayer = 1;

$origGamestateContent = file_get_contents($replayPath . "origGamestate.txt");
$gamestateLine = explode("\r\n", $origGamestateContent);

$p1Key = "";
$p2Key = "";
for ($i = count($gamestateLine) - 1; $i >= 0; $i--) {
    $line = trim($gamestateLine[$i]);
    if (strlen($line) === 64 && ctype_xdigit($line)) {
        if ($p2Key === "") {
            $p2Key = $line;
        } elseif ($p1Key === "") {
            $p1Key = $line;
            break;
        }
    }
}

if (empty($p1Key) || empty($p2Key)) {
    $p1Key = hash("sha256", random_bytes(16));
    $p2Key = hash("sha256", random_bytes(16));
}

$p1uid = "-";
$p2uid = "-";
$p1id = "-";
$p2id = "-";
$hostIP = GetClientIP();
$p1StartingHealth = $startingHealth;

$filename = "../Games/$gameName/GameFile.txt";
$gameFileHandler = fopen($filename, "w");
if ($gameFileHandler === false) {
    $response->error = "Failed to create game file.";
    http_response_code(500);
    echo json_encode($response);
    exit;
}
include "../MenuFiles/WriteGamefile.php";
WriteGameFile();

$filename = "../Games/$gameName/gamelog.txt";
$handler = fopen($filename, "w");
if ($handler !== false) fclose($handler);

$currentTime = round(microtime(true) * 1000);
$isReplay = "1";
$visibility = "1";
WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1!" . $currentTime . "!!!" . $visibility . "!" . $isReplay . "!0!0!0!" . $gameStatus . "!0!0");

$copyErrors = [];
if (!@copy($replayPath . "origGamestate.txt", "../Games/$gameName/gamestate.txt")) {
    $copyErrors[] = "origGamestate.txt";
}
if (!@copy($replayPath . "commandfile.txt", "../Games/$gameName/replayCommands.txt")) {
    $copyErrors[] = "commandfile.txt";
}

for ($player = 1; $player < 3; ++$player) {
    $turn = 1;
    $src = $replayPath . "turn_$player-$turn" . "_Gamestate.txt";
    $dst = "../Games/$gameName/turn_$player-$turn" . "_Gamestate.txt";
    if (!file_exists($src)) {
        ++$turn;
        $src = $replayPath . "turn_$player-$turn" . "_Gamestate.txt";
        $dst = "../Games/$gameName/turn_$player-$turn" . "_Gamestate.txt";
    }
    while (file_exists($src)) {
        @copy($src, $dst);
        ++$turn;
        $src = $replayPath . "turn_$player-$turn" . "_Gamestate.txt";
        $dst = "../Games/$gameName/turn_$player-$turn" . "_Gamestate.txt";
    }
}

if (!empty($copyErrors)) {
    $response->error = "Failed to copy some replay files: " . implode(", ", $copyErrors);
    http_response_code(500);
    echo json_encode($response);
    exit;
}

$commandFile = file_get_contents("../Games/$gameName/replayCommands.txt");
$commandFile = "0\r\n$commandFile";
file_put_contents("../Games/$gameName/replayCommands.txt", $commandFile);

$gamestate = file_get_contents("../Games/$gameName/gamestate.txt");
WriteGamestateCache($gameName, $gamestate);

// Shared replay viewers enter as spectators (playerID = 3)
$response->success = true;
$response->gameName = $gameName;
$response->playerID = 3;
$response->authKey = $p1Key;
$response->message = "Shared replay loaded successfully.";
echo json_encode($response);
