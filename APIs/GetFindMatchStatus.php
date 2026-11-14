<?php

// Find match status: who is waiting in a format, who fits the player's
// deck and hero filter, and why the others don't. The client joins one of
// `matches` with its own deck, or hosts a public lobby when there are none.
//
// POST JSON: { hero: set ID or card ID ("" when unknown), preference: lobby
// description string in the CreateGame format ("Looking to play against X, Y" /
// "No interest in playing against X, Y"), format: format code ("" for none) }

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

SetHeaders();
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$path = "../Games";
$useGameCacheSentinels = GameCacheSentinelsAvailable() && class_exists('APCUIterator');

$_POST = ReadJsonBody();
$requestedHero = substr(trim((string)TryPOST("hero", "")), 0, 64);
$requestedPreference = substr(trim((string)TryPOST("preference", "")), 0, 500);
$requestedFormat = substr(trim((string)TryPOST("format", "")), 0, 32);

session_start();
if (!IsUserLoggedIn() && isset($_COOKIE["rememberMeToken"])) loginFromCookie();

$response = new stdClass();
$response->blockers = new stdClass();

if (IsUserLoggedIn() && IsBannedPlayer(LoggedInUserName())) {
  session_write_close();
  echo json_encode($response);
  exit;
}

$isShadowBanned = GameListViewerIsShadowBanned();
$viewer = LoadGameListViewer($isShadowBanned);
$userName = (string)($_SESSION["useruid"] ?? "");
session_write_close();

$heroCardId = FindMatchHeroCardId($requestedHero);
$preference = ParseHeroPreference($requestedPreference);
$joiner = ['userName' => $userName, 'heroCardId' => $heroCardId, 'preference' => $preference];

$response->heroKnown = $heroCardId !== '';
if ($heroCardId !== '') {
  $response->hero = SetID($heroCardId);
  $response->heroName = html_entity_decode(CardName($heroCardId), ENT_QUOTES, 'UTF-8');
  $response->heroShortName = FindMatchHeroShortName($heroCardId);
}
$response->hasPreference = $preference !== null;

$matchFormat = $isShadowBanned ? FindMatchShadowFormat($requestedFormat) : $requestedFormat;
$summary = null;
if ($requestedFormat !== '') {
  $summary = new stdClass();
  $summary->format = $requestedFormat;
  $summary->competitive = FindMatchIsCompetitiveFormat($requestedFormat);
  $legalityFormat = FindMatchLegalityFormat($requestedFormat);
  $summary->heroLegal = ($heroCardId === '' || $legalityFormat === null) ? null : FindMatchHeroLegal(SetID($heroCardId), $legalityFormat);
  $summary->waiting = 0;
  $summary->matches = [];
  $summary->reasons = new stdClass();
  $summary->otherFormats = new stdClass();
}

$currentTime = round(microtime(true) * 1000);
$scan = GetGameListScan($path, $currentTime, $useGameCacheSentinels);
foreach (($scan['open'] ?? []) as [$gameToken, $visibility, $p1uid, $hasHero, $p1Hero, $openFormat, $formatName, $description, $creatorName]) {
  if (!OpenGameVisibleToViewer($viewer, $p1uid, $visibility, $openFormat)) continue;

  $codes = FindMatchLobbyBlockers($joiner, [
    'gameName' => $gameToken,
    'p1uid' => $p1uid,
    'visibility' => $visibility,
    'format' => $openFormat,
    'hero' => $hasHero ? $p1Hero : '',
    'description' => $description,
  ]);
  $response->blockers->{$gameToken} = $codes;

  if ($summary === null) continue;
  if ($openFormat !== $matchFormat) {
    $shownFormat = FindMatchUnshadowFormat($openFormat);
    $summary->otherFormats->{$shownFormat} = ($summary->otherFormats->{$shownFormat} ?? 0) + 1;
    continue;
  }
  ++$summary->waiting;
  $reason = FindMatchPrimaryReason($codes);
  if ($reason === null) $summary->matches[] = intval($gameToken);
  else $summary->reasons->{$reason} = ($summary->reasons->{$reason} ?? 0) + 1;
}

if ($summary !== null) {
  sort($summary->matches);
  $response->summary = $summary;
}

echo json_encode($response);
