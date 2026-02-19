<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once "../AccountFiles/AccountDatabaseAPI.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
SetHeaders();

$response = new stdClass();

// Helper function to check if a delimited string contains a value
if (!function_exists("DelimStringContains")) {
  function DelimStringContains($str, $find, $partial=false)
  {
    $arr = explode(",", $str);
    for($i=0; $i<count($arr); ++$i)
    {
      if($partial && str_contains($arr[$i], $find)) return true;
      else if($arr[$i] == $find) return true;
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
$curl = curl_init();
$isFaBDB = str_contains($decklink, "fabdb");

if ($isFaBDB) {
  $decklinkArr = explode("/", $decklink);
  $slug = $decklinkArr[count($decklinkArr) - 1];
  $apiLink = "https://api.fabdb.net/decks/" . $slug;
} else if (str_contains($decklink, "fabrary")) {
  $headers = [
    "x-api-key: " . $FaBraryKey,
    "Content-Type: application/json",
  ];
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  // Extract slug: https://fabrary.net/decks/SLUG or https://fabrary.net/decks/SLUG?matchupId=...
  $urlWithoutQuery = explode("?", $decklink)[0];
  $decklinkArr = explode("/", $urlWithoutQuery);
  $slug = $decklinkArr[count($decklinkArr) - 1];
  $apiLink = "https://atofkpq0x8.execute-api.us-east-2.amazonaws.com/prod/v1/decks/" . $slug;
} else {
  $decklinkArr = explode("/", $decklink);
  $slug = $decklinkArr[count($decklinkArr) - 1];
  $apiLink = "https://api.fabmeta.net/deck/" . $slug;
}

curl_setopt($curl, CURLOPT_URL, $apiLink);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$apiDeck = curl_exec($curl);
$apiInfo = curl_getinfo($curl);
curl_close($curl);

if ($apiDeck === FALSE) {
  $response->success = false;
  $response->message = "Deckbuilder API for this deck returns no data.";
  echo json_encode($response);
  exit;
}

$deckObj = json_decode($apiDeck);

// Check for API errors
if ($apiInfo['http_code'] == 403) {
  $response->success = false;
  $response->message = "API access denied. The deck URL may be invalid.";
  echo json_encode($response);
  exit;
}

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

// Try to find the hero by looking for a card with type C (Character)
// The Fabrary API returns cards in main deck order
// Heroes are typically:
// 1. Cards with uppercase set codes (WTR, ARC, CRU, etc.) + 1-3 digits
// 2. Only 1 copy in deck (total: 1)
// 3. NOT equipment/weapon/action/instant/attack reaction

$cards = isset($deckObj->{'cards'}) ? $deckObj->{'cards'} : [];
$foundHero = false;

if (is_array($cards) && count($cards) > 0) {
  foreach ($cards as $card) {
    if (!isset($card->{'cardIdentifier'})) continue;
    
    $cardIdentifier = $card->{'cardIdentifier'};
    $total = isset($card->{'total'}) ? $card->{'total'} : 0;
    $types = isset($card->{'types'}) ? $card->{'types'} : [];
    
    // Check if this is a 1-of card
    if ($total !== 1) continue;
    
    // Check if card type is NOT a common non-hero type
    $isCommonType = false;
    if (is_array($types)) {
      foreach ($types as $type) {
        if (in_array($type, ["Equipment", "Weapon", "Action", "Instant", "Attack Reaction"])) {
          $isCommonType = true;
          break;
        }
      }
    }
    
    // If it's a 1-of and not a common type, it's likely a hero
    if (!$isCommonType) {
      // Verify it looks like a hero card ID (uppercase set code + numbers)
      if (preg_match('/^[A-Z]{2,4}\d{1,3}$/', $cardIdentifier)) {
        $heroID = $cardIdentifier;
        $foundHero = true;
        break;
      }
    }
  }
}

// Add the deck to favorites without validation
// If we couldn't extract hero, pass empty string - database will handle it
addFavoriteDeck($userID, $decklink, $deckName, $heroID, $deckFormat);

$response->success = true;
$response->message = "Deck added to favorites successfully!";

echo json_encode($response);

