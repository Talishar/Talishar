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
$isFaBMeta = str_contains($decklink, "fabmeta") && !str_contains($decklink, "fabtcgmeta");
$isFaBTCGMeta = str_contains($decklink, "fabtcgmeta");

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

// Find the hero card using the same identifier logic as JoinGame.php's GetCardId().
// Fabrary uses 'identifier' (Talishar internal format, e.g. "rhinar") as the primary field,
// while FabDB uses printings[].sku.sku (e.g. "WTR067-COLD-FOIL").
// We use GeneratedCardType() to reliably detect Character ("C") type cards.

$cards = isset($deckObj->{'cards'}) ? $deckObj->{'cards'} : [];

if (is_array($cards) && count($cards) > 0) {
  foreach ($cards as $card) {
    // Extract identifier using the same approach as JoinGame GetCardId()
    $cardID = "";
    if ($isFaBDB) {
      if (isset($card->{'printings'}[0]->{'sku'}->{'sku'})) {
        // FabDB returns SetID format (e.g. "WTR067-COLD-FOIL"), convert to internal ID
        $setID = explode("-", $card->{'printings'}[0]->{'sku'}->{'sku'})[0];
        $internalID = GeneratedSetIDtoCardID($setID);
        $cardID = !empty($internalID) ? $internalID : $setID;
      }
    } else if ($isFaBMeta) {
      $cardID = $card->{'identifier'} ?? "";
    } else if (isset($card->{'identifier'})) {
      // Fabrary: identifier uses dashes, convert to underscores
      $cardID = str_replace("-", "_", $card->{'identifier'});
    } else if (isset($card->{'cardIdentifier'})) {
      $cardID = $card->{'cardIdentifier'};
    }

    if (empty($cardID)) continue;

    // Use GeneratedCardType to reliably identify hero (Character) cards
    if (str_contains(GeneratedCardType($cardID), "C")) {
      $heroID = GeneratedSetID($cardID);
      break;
    }
  }
}

// Add the deck to favorites without validation
// If we couldn't extract hero, pass empty string - database will handle it
addFavoriteDeck($userID, $decklink, $deckName, $heroID, $deckFormat);

$response->success = true;
$response->message = "Deck added to favorites successfully!";

echo json_encode($response);
