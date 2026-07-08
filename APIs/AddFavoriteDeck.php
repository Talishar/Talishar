<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../AccountFiles/AccountDatabaseAPI.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../GeneratedCode/GeneratedCardDictionaries.php';
SetHeaders();

$response = new stdClass();

// Helper function to check if a delimited string contains a value
if (!function_exists("DelimStringContains")) {
  function DelimStringContains($str, $find, $partial=false)
  {
    foreach (explode(",", $str) as $item) {
      if ($partial ? str_contains($item, $find) : $item == $find) return true;
    }
    return false;
  }
}

$_POST = json_decode(file_get_contents('php://input'), true);
$decklink = TryPOST("fabdb", "");

if (empty($decklink)) {
  $response->success = false;
  $response->message = "Deck URL is required.";
  echo json_encode($response);
  exit;
}

session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (!isset($_SESSION["userid"])) {
  $response->success = false;
  $response->message = "You must be logged in to add favorite decks.";
  echo json_encode($response);
  exit;
}

$userID = $_SESSION["userid"];
session_write_close();

// Load the deck from the deckbuilder API to get deck name
$fetched = FetchDeckFromDeckbuilder($decklink);

if ($fetched === null) {
  $response->success = false;
  $response->message = "Deckbuilder API for this deck returns no data.";
  echo json_encode($response);
  exit;
}

// Check for API errors
if ($fetched->httpCode == 403) {
  $response->success = false;
  $response->message = "API access denied. The deck URL may be invalid.";
  echo json_encode($response);
  exit;
}

$deckObj = $fetched->deckObj;
$isFaBDB = $fetched->isFaBDB;
$isFaBMeta = $fetched->isFaBMeta;

if ($deckObj == null) {
  $response->success = false;
  $response->message = "Failed to retrieve deck from API.";
  echo json_encode($response);
  exit;
}

// Extract deck metadata from API response
$deckName = "Imported Deck";
if (isset($deckObj->{'name'})) {
  $deckName = $deckObj->{'name'};
}

$heroID = ""; // Will be populated if we find a character card
$deckFormat = "";

// Get format if available
if (isset($deckObj->{'format'})) {
  $deckFormat = $deckObj->{'format'};
  if (is_string($deckFormat)) {
  } else {
    $deckFormat = ""; // Format might be an object, reset to empty
  }
}

// Find the hero card using the same identifier logic as JoinGame.php's GetCardId().
// Fabrary uses 'identifier' (Talishar internal format, e.g. "rhinar") as the primary field,
// while FabDB uses printings[].sku.sku (e.g. "WTR067-COLD-FOIL").
// We use GeneratedCardType() to reliably detect Character ("C") type cards.

$deckCardIds = ResolveDeckCardIds($deckObj, $isFaBDB, $isFaBMeta);

foreach ($deckCardIds as $cardID) {
  // Use GeneratedCardType to reliably identify hero (Character) cards
  if (str_contains(GeneratedCardType($cardID), "C")) {
    $heroID = GeneratedSetID($cardID);
    break;
  }
}

// Add the deck to favorites without validation
// If we couldn't extract hero, pass empty string - database will handle it
addFavoriteDeck($userID, $decklink, $deckName, $heroID, $deckFormat);

$response->success = true;
$response->message = "Deck added to favorites successfully!";

echo json_encode($response);
