<?php

// One Find match step, decided under the Find match queue lock (Libraries/FindMatchQueue.php):
// { action: "join", games: [...] }: join these lobbies in order, the first is reserved for you.
// { action: "wait", retryMs }: a fitting player is creating a lobby right now, ask again shortly.
// { action: "host", ticket }: create a public lobby and pass the ticket to CreateGame.php.
//
// POST JSON: { hero, preference, format } as for GetFindMatchStatus.php, plus
// exclude: lobbies this search already failed to join,
// or { cancel: ticket } when hosting failed.

include_once "../Libraries/SHMOPLibraries.php";
include "../Libraries/HTTPLibraries.php";
include "../HostFiles/Redirector.php";
include "../AccountFiles/AccountSessionAPI.php";
require_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
include_once "../AccountFiles/AccountDatabaseAPI.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Libraries/BlockedUserLibraries.php';
include_once '../Libraries/FriendLibraries.php';
include_once '../Libraries/CoreLibraries.php';
include_once '../Libraries/GameListScan.php';
include_once '../Libraries/GameListViewer.php';
include_once '../CardDictionary.php';
include_once '../Libraries/LegalHeroesHelper.php';
include_once '../Libraries/HeroPreference.php';
include_once '../Libraries/FindMatchLibraries.php';
include_once '../Libraries/FindMatchQueue.php';

SetHeaders();
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$_POST = ReadJsonBody();
$requestedHero = substr(trim((string)TryPOST("hero", "")), 0, 64);
$requestedPreference = substr(trim((string)TryPOST("preference", "")), 0, 500);
$requestedFormat = substr(trim((string)TryPOST("format", "")), 0, 32);
$cancelTicket = substr(trim((string)TryPOST("cancel", "")), 0, 64);
$exclude = array_slice(array_map("intval", is_array($_POST["exclude"] ?? null) ? $_POST["exclude"] : []), 0, 50);

session_start();
if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();

$response = new stdClass();
if (!IsUserLoggedIn()) {
  session_write_close();
  $response->error = "You must be logged in to find a match.";
  echo json_encode($response);
  exit;
}
if (IsBannedPlayer(LoggedInUserName())) {
  session_write_close();
  $response->error = "Unable to find a match.";
  echo json_encode($response);
  exit;
}

$userName = (string)($_SESSION["useruid"] ?? "");
if ($cancelTicket !== "") {
  session_write_close();
  FindMatchQueueCancel($cancelTicket, $userName);
  $response->action = "cancelled";
  echo json_encode($response);
  exit;
}
if ($requestedFormat === "") {
  session_write_close();
  $response->error = "Choose a format.";
  echo json_encode($response);
  exit;
}

$isShadowBanned = GameListViewerIsShadowBanned();
$viewer = LoadGameListViewer($isShadowBanned);
session_write_close();

$joiner = [
  'userName' => $userName,
  'heroCardId' => FindMatchHeroCardId($requestedHero),
  'preference' => ParseHeroPreference($requestedPreference),
  'preferenceText' => $requestedPreference,
];
$matchFormat = $isShadowBanned ? FindMatchShadowFormat($requestedFormat) : $requestedFormat;

$useGameCacheSentinels = GameCacheSentinelsAvailable() && class_exists('APCUIterator');
$scan = GetGameListScan("../Games", round(microtime(true) * 1000), $useGameCacheSentinels);
$fits = array_values(array_diff(FindMatchFitsInFormat($scan ?? [], $viewer, $joiner, $matchFormat), $exclude));

foreach (FindMatchQueueStep($viewer, $joiner, $matchFormat, $fits, $exclude) as $key => $value) $response->{$key} = $value;
echo json_encode($response);
