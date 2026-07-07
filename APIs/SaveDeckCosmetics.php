<?php

include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

include_once "../AccountFiles/AccountSessionAPI.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/CosmeticsLibrary.php";
include_once "../Assets/patreon-php-master/src/PatreonDictionary.php";
include_once "../Assets/MetafyDictionary.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

header('Content-Type: application/json');

function DeckCosmeticsError($message, $code = 400)
{
  http_response_code($code);
  echo json_encode(["success" => false, "message" => $message]);
  exit;
}

if (!IsUserLoggedIn()) {
  DeckCosmeticsError("You must be logged in to customize decks.", 401);
}

$userID = LoggedInUser();
$userName = LoggedInUserName();

$_POST = json_decode(file_get_contents('php://input'), true) ?? [];
$decklink = TryPOST("decklink", "");
$cardBackId = strval(TryPOST("cardBackId", "0"));
$playmatId = strval(TryPOST("playmatId", "0"));
$altArts = TryPOST("altArts", []);

if (empty($decklink)) {
  DeckCosmeticsError("Deck link is required.");
}
if (!is_array($altArts)) {
  DeckCosmeticsError("Invalid alt art selection.");
}

$conn = GetDBConnection(DBL_SAVE_DECK_COSMETICS);
if (!$conn) {
  DeckCosmeticsError("Service temporarily unavailable. Please try again later.", 503);
}

$sql = "SELECT 1 FROM favoritedeck WHERE decklink=? AND usersId=?";
$stmt = mysqli_stmt_init($conn);
$owned = false;
if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "ss", $decklink, $userID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  $owned = mysqli_stmt_num_rows($stmt) > 0;
  mysqli_stmt_close($stmt);
}
if (!$owned) {
  mysqli_close($conn);
  DeckCosmeticsError("Deck not found in your favorites.", 404);
}

$entitlements = GetUserCosmeticsEntitlements($userName);
$entitledCardBackIds = array_map(fn($cb) => strval($cb->id), $entitlements->cardBacks);
$entitledPlaymatIds = array_map(fn($pm) => strval($pm->id), $entitlements->playmats);

if (!in_array($cardBackId, $entitledCardBackIds, true)) {
  mysqli_close($conn);
  DeckCosmeticsError("You have not unlocked that card back.", 403);
}
if (!in_array($playmatId, $entitledPlaymatIds, true)) {
  mysqli_close($conn);
  DeckCosmeticsError("You have not unlocked that playmat.", 403);
}

$altArtEntitlements = GetUserAltArtEntitlements($userName);
$validatedAltArts = [];
foreach ($altArts as $entry) {
  $cardId = strval($entry['cardId'] ?? '');
  $altPath = strval($entry['altPath'] ?? '');
  if ($cardId === '' || $altPath === '') {
    mysqli_close($conn);
    DeckCosmeticsError("Invalid alt art selection.");
  }
  if (!isset($altArtEntitlements[$cardId]) || !in_array($altPath, $altArtEntitlements[$cardId], true)) {
    mysqli_close($conn);
    DeckCosmeticsError("You have not unlocked that alt art for " . $cardId . ".", 403);
  }
  $validatedAltArts[] = [$cardId, $altPath];
}

mysqli_begin_transaction($conn);
try {
  $sql = "UPDATE favoritedeck SET cardBack = ?, playmat = ?, altArtsCustomized = 1 WHERE decklink = ? AND usersId = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) throw new Exception("prepare failed");
  mysqli_stmt_bind_param($stmt, "ssss", $cardBackId, $playmatId, $decklink, $userID);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    throw new Exception("update failed");
  }
  mysqli_stmt_close($stmt);

  $sql = "DELETE FROM deck_alt_arts WHERE usersId = ? AND decklink = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) throw new Exception("prepare failed");
  mysqli_stmt_bind_param($stmt, "ss", $userID, $decklink);
  if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    throw new Exception("delete failed");
  }
  mysqli_stmt_close($stmt);

  if (count($validatedAltArts) > 0) {
    $sql = "INSERT INTO deck_alt_arts (usersId, decklink, cardId, altPath) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) throw new Exception("prepare failed");
    foreach ($validatedAltArts as [$cardId, $altPath]) {
      mysqli_stmt_bind_param($stmt, "ssss", $userID, $decklink, $cardId, $altPath);
      if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("insert failed");
      }
    }
    mysqli_stmt_close($stmt);
  }

  mysqli_commit($conn);
} catch (Exception $e) {
  mysqli_rollback($conn);
  mysqli_close($conn);
  DeckCosmeticsError("Failed to save deck customization. Please try again later.", 500);
}

mysqli_close($conn);
session_write_close();

echo json_encode([
  "success" => true,
  "message" => "Deck customization saved.",
  "cardBackId" => $cardBackId,
  "playmatId" => $playmatId,
  "altArts" => array_map(fn($pair) => ["cardId" => $pair[0], "altPath" => $pair[1]], $validatedAltArts)
]);
