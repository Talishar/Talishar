<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/HTTPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/CosmeticsLibrary.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../Assets/MetafyDictionary.php";
include_once '../GeneratedCode/GeneratedCardDictionaries.php';

session_start();

SetHeaders();

$response = new stdClass();
$response->cards = [];
$response->tokens = [];

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (!IsUserLoggedIn()) {
  http_response_code(401);
  $response->message = "You must be logged in to view deck cards.";
  echo json_encode($response);
  exit;
}

$userID = LoggedInUser();
$userName = LoggedInUserName();
$decklink = TryGET("decklink", "");

if (empty($decklink)) {
  http_response_code(400);
  $response->message = "Deck link is required.";
  echo json_encode($response);
  exit;
}

// Confirm this deck actually belongs to the logged-in user before hitting an
// external deckbuilder API on their behalf.
$conn = GetDBConnection(DBL_GET_DECK_CARDS);
$sql = "SELECT hero FROM favoritedeck WHERE decklink=? AND usersId=?";
$stmt = mysqli_stmt_init($conn);
$owned = false;
$deckHero = "";
if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "ss", $decklink, $userID);
  mysqli_stmt_execute($stmt);
  $data = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($data)) {
    $owned = true;
    $deckHero = $row['hero'] ?? "";
  }
  mysqli_stmt_close($stmt);
}
mysqli_close($conn);

if (!$owned) {
  http_response_code(404);
  $response->message = "Deck not found in your favorites.";
  echo json_encode($response);
  exit;
}

$fetched = FetchDeckFromDeckbuilder($decklink);
if ($fetched === null || $fetched->deckObj == null) {
  http_response_code(502);
  $response->message = "Failed to retrieve deck from the deckbuilder API.";
  echo json_encode($response);
  exit;
}

$cardIds = ResolveDeckCardIds($fetched->deckObj, $fetched->isFaBDB, $fetched->isFaBMeta);
$altArtEntitlements = GetUserAltArtEntitlements($userName);

$selectedAltArts = [];
$conn = GetDBConnection(DBL_GET_DECK_CARDS);
if ($conn) {
  $sql = "SELECT cardId, altPath FROM deck_alt_arts WHERE decklink=? AND usersId=?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "ss", $decklink, $userID);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($data)) {
      $selectedAltArts[$row['cardId']] = $row['altPath'];
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}

foreach ($cardIds as $cardId) {
  if (!isset($altArtEntitlements[$cardId])) continue;
  $card = new stdClass();
  $card->cardId = $cardId;
  $card->altArts = $altArtEntitlements[$cardId];
  // Base card-square images on the CDN are named by the card slug (the same
  // identifier the game engine renders in-game, e.g. "adaptive_alpha_mold"),
  // NOT by the printed set ID. Converting via GeneratedSetID() here produced a
  // set code like "SUP253" and a broken cardsquares URL.
  $card->baseCardNumber = $cardId;
  $card->selectedAltPath = $selectedAltArts[$cardId] ?? null;
  $response->cards[] = $card;
}

if (function_exists('GeneratedCardTokens')) {
  $tokenIds = [];
  $queue = $cardIds;
  if (!empty($deckHero)) $queue[] = $deckHero;
  $processed = [];
  while (count($queue) > 0) {
    $cid = array_shift($queue);
    if (isset($processed[$cid])) continue;
    $processed[$cid] = true;
    $refs = GeneratedCardTokens($cid);
    if ($refs == "") continue;
    foreach (explode(",", $refs) as $tokenId) {
      if (!in_array($tokenId, $tokenIds)) {
        $tokenIds[] = $tokenId;
        $queue[] = $tokenId;
      }
    }
  }
  sort($tokenIds);
  foreach ($tokenIds as $tokenId) {
    $token = new stdClass();
    $token->cardId = $tokenId;
    $token->altArts = $altArtEntitlements[$tokenId] ?? [];
    $token->baseCardNumber = $tokenId;
    $token->selectedAltPath = $selectedAltArts[$tokenId] ?? null;
    $response->tokens[] = $token;
  }
}

session_write_close();
echo json_encode($response);
