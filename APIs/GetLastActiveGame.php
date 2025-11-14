<?php
/**
 * GetLastActiveGame.php
 * Retrieves the last active game for a player who may have disconnected.
 * Allows players to rejoin their game if it's still in progress.
 * 
 * Returns:
 * - gameExists (bool): Whether the game file still exists
 * - gameInProgress (bool): Whether the game is still active (not over or in sideboard)
 * - gameName (int): Game ID
 * - playerID (int): Player's ID in the game (1 or 2)
 * - authKey (string): Auth key to rejoin the game
 * - opponentName (string): Opponent's username
 * - opponentDisconnected (bool): Whether opponent is currently disconnected
 */

include "../HostFiles/Redirector.php";
include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../CardDictionary.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";

SetHeaders();

session_start();

// Try to login from cookie first
if (!isset($_SESSION["userid"]) && isset($_COOKIE["rememberMeToken"])) {
    include_once "../includes/functions.inc.php";
    loginFromCookie();
}

session_write_close();

$response = new stdClass();

// Check if user is logged in and has a last game
if (!isset($_SESSION["lastGameName"]) || !isset($_SESSION["lastPlayerId"]) || !isset($_SESSION["lastAuthKey"])) {
    $response->gameExists = false;
    $response->gameInProgress = false;
    echo json_encode($response);
    exit;
}

$gameName = $_SESSION["lastGameName"];
$playerID = $_SESSION["lastPlayerId"];
$authKey = $_SESSION["lastAuthKey"];

// Validate game name
if (!IsGameNameValid($gameName)) {
    $response->gameExists = false;
    $response->gameInProgress = false;
    echo json_encode($response);
    exit;
}

// Check if game directory exists
if (!file_exists("../Games/" . $gameName . "/")) {
    $response->gameExists = false;
    $response->gameInProgress = false;
    echo json_encode($response);
    exit;
}

$response->gameExists = true;
$response->gameName = $gameName;
$response->playerID = $playerID;
$response->authKey = $authKey;

// Parse game file to check status and opponent info
ob_start();
include "./APIParseGamefile.php";
ob_end_clean();

// Check if game is still in progress
// Cache piece 14 is set to 99 (MGS_GameOver) when game ends
// If cache piece 14 is 99, the game is definitely over
$cacheStatus = GetCachePiece($gameName, 14);
$MGS_GameOver = 99;

$response->gameInProgress = false;

// If cache piece 14 is set to 99, game is over
if ($cacheStatus == $MGS_GameOver) {
    $response->gameInProgress = false;
} else if ($gameStatus == 5) {
    // Game status is 5 (GameStarted) and cache piece 14 is not 99 (GameOver)
    // So game is in progress
    $response->gameInProgress = true;
}

// Get opponent info
$opponentID = ($playerID == 1 ? 2 : 1);
$response->opponentName = ($opponentID == 1 ? ($p1uid != "-" ? $p1uid : "Player 1") : ($p2uid != "-" ? $p2uid : "Player 2"));

// Check if opponent is currently disconnected
// Cache piece structure: playerID+3 contains the connection status
// 0 = connected, -1 = disconnected, 1 = just joined
$opponentStatus = GetCachePiece($gameName, $opponentID + 3);
$response->opponentDisconnected = ($opponentStatus == "-1" || $opponentStatus == "");

// Verify auth key matches
$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
    // Auth key mismatch - don't allow recovery
    $response->gameInProgress = false;
    $response->authKeyMismatch = true;
}

echo json_encode($response);
exit;
