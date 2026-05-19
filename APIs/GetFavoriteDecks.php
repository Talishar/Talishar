<?php

include "../AccountFiles/AccountSessionAPI.php";
include_once '../includes/functions.inc.php';
include_once "../includes/dbh.inc.php";
include_once "../Libraries/PlayerSettings.php";
include_once "../Libraries/HTTPLibraries.php";
require_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';

SetHeaders();

if (!IsUserLoggedIn()) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$response = new stdClass();
$response->favoriteDecks = [];
if (IsUserLoggedIn()) {
  $savedSettings = LoadSavedSettings(LoggedInUser());
  $settingArray = [];
  for ($i = 0; $i < count($savedSettings); $i += 2) {
    $settingArray[$savedSettings[intval($i)]] = $savedSettings[intval($i) + 1];
  }

  $favoriteDecks = LoadFavoriteDecks(LoggedInUser());
  if (count($favoriteDecks) > 0) {
    $selIndex = -1;
    if (isset($settingArray[$SET_FavoriteDeckIndex])) $selIndex = $settingArray[$SET_FavoriteDeckIndex];
    $response->lastUsedDeckIndex = $selIndex;
    for ($i = 0; $i < count($favoriteDecks); $i += 4) {
      $deck = new stdClass();
      $deck->index = $i;
      $deck->key = $i . "<fav>" . $favoriteDecks[$i];
      $deck->name = $favoriteDecks[$i + 1];
      $deck->hero = $favoriteDecks[$i + 2];
      $deck->format = $favoriteDecks[$i + 3];
      $deck->cardBack = "DEFAULT";
      $deck->playmat = "DEFAULT";
      $deck->link = $favoriteDecks[$i];
      array_push($response->favoriteDecks, $deck);
    }
  }

  // Fetch FaB Bazaar decks via Metafy ID (if user has Metafy linked)
  $metafyID = null;
  $conn = GetDBConnection();
  if ($conn) {
    $sql = "SELECT metafyID FROM users WHERE usersId=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      $uid = LoggedInUser();
      mysqli_stmt_bind_param($stmt, "s", $uid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      if ($row) $metafyID = $row['metafyID'];
      mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
  }

  if (!empty($metafyID)) {
    include_once __DIR__ . '/../APIKeys/APIKeys.php';
    $bazaarUrl = "https://fabbazaar.app/api/decks/list?metafy_id=" . urlencode($metafyID);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $bazaarUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "x-api-key: " . $FaBBazaarKey,
      "Content-Type: application/json",
      "User-Agent: Talishar"
    ));
    $bazaarResponse = curl_exec($curl);
    $bazaarHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($bazaarResponse !== false && $bazaarHttpCode === 200) {
      $bazaarData = json_decode($bazaarResponse, true);
      if (isset($bazaarData['decks']) && is_array($bazaarData['decks'])) {
        $nextIndex = count($response->favoriteDecks) * 4;
        foreach ($bazaarData['decks'] as $bDeck) {
          $deckLink = "https://fabbazaar.app/decks/" . ($bDeck['publicId'] ?? '');
          $deck = new stdClass();
          $deck->index = $nextIndex;
          $deck->key = $nextIndex . "<fav>" . $deckLink;
          $deck->name = $bDeck['name'] ?? 'Untitled';
          $deck->hero = $bDeck['heroCardId'] ?? '';
          $deck->format = $bDeck['format'] ?? '';
          $deck->cardBack = "DEFAULT";
          $deck->playmat = "DEFAULT";
          $deck->link = $deckLink;
          array_push($response->favoriteDecks, $deck);
          $nextIndex += 4;
        }
      }
    }
  }

  //Load other settings
  if (isset($settingArray[$SET_Format])) $response->lastFormat = FormatName($settingArray[$SET_Format]);
  if (isset($settingArray[$SET_GameVisibility])) $response->lastVisibility = $settingArray[$SET_GameVisibility];
}
echo json_encode($response);
